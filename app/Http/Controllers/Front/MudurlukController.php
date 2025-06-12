<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Mudurluk;
use App\Models\MudurlukFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MudurlukController extends Controller
{
    /**
     * Müdürlükler liste sayfası
     */
    public function index(Request $request)
    {
        $query = Mudurluk::active()->withCount('files');

        // Arama
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('summary', 'like', "%{$search}%")
                  ->orWhere('gorev_tanimi_ve_faaliyet_alani', 'like', "%{$search}%")
                  ->orWhere('yetki_ve_sorumluluklar', 'like', "%{$search}%");
            });
        }

        $mudurlukler = $query->ordered()->paginate(12)->appends($request->all());

        return view('front.mudurlukler.index', compact('mudurlukler'));
    }

    /**
     * Müdürlük detay sayfası
     */
    public function show($slug)
    {
        $mudurluk = Mudurluk::where('slug', $slug)
                           ->where('is_active', true)
                           ->firstOrFail();

        // Görüntülenme sayısını artır
        $mudurluk->incrementViews();

        // Dosyaları ve müdürlük kategorilerini yükle
        $mudurluk->load([
            'files' => function($query) {
                $query->where('is_active', true)->orderBy('type')->orderBy('order_column');
            },
            'serviceCategories' => function($query) {
                $query->where('is_active', true)->orderBy('name');
            }
        ]);

        // Dosyaları kategorilere ayır
        $hizmetStandartlari = $mudurluk->files->where('type', 'hizmet_standartlari');
        $yonetimSemalari = $mudurluk->files->where('type', 'yonetim_semalari');

        // İlgili hizmetleri getir (seçilen kategorilerdeki hizmetler)
        $relatedServices = collect();
        if ($mudurluk->serviceCategories->count() > 0) {
            $relatedServices = $mudurluk->relatedServices()->limit(8)->get();
        }

        // Diğer müdürlükleri getir (rastgele 6 tane)
        $otherMudurlukler = Mudurluk::active()
                                   ->where('id', '!=', $mudurluk->id)
                                   ->inRandomOrder()
                                   ->limit(6)
                                   ->get();

        return view('front.mudurlukler.show', compact(
            'mudurluk', 
            'hizmetStandartlari', 
            'yonetimSemalari', 
            'relatedServices',
            'otherMudurlukler'
        ));
    }

    /**
     * Dosya indirme
     */
    public function downloadFile($mudurlukSlug, MudurlukFile $file)
    {
        // Müdürlük kontrolü
        $mudurluk = Mudurluk::where('slug', $mudurlukSlug)->firstOrFail();
        
        // Dosyanın bu müdürlüğe ait olduğunu kontrol et
        if ($file->mudurluk_id !== $mudurluk->id) {
            abort(404);
        }

        // Dosyanın aktif olduğunu kontrol et
        if (!$file->is_active) {
            abort(404, 'Dosya artık erişilebilir değil.');
        }

        // Dosyanın var olduğunu kontrol et
        if (!Storage::disk('public')->exists($file->file_path)) {
            abort(404, 'Dosya bulunamadı.');
        }

        // Dosyayı indir
        return Storage::disk('public')->download(
            $file->file_path, 
            $file->file_name
        );
    }
}
