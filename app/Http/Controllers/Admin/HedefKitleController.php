<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HedefKitle;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class HedefKitleController extends Controller
{
    /**
     * Hedef Kitleler listesi
     */
    public function index()
    {
        $hedefKitleler = HedefKitle::orderBy('order')
            ->paginate(20);
            
        return view('admin.hedef-kitleler.index', compact('hedefKitleler'));
    }
    
    /**
     * Yeni hedef kitle oluşturma formu
     */
    public function create()
    {
        return view('admin.hedef-kitleler.create');
    }
    
    /**
     * Hedef kitle kaydetme
     */
    public function store(Request $request)
    {
        \Illuminate\Support\Facades\Log::info('Hedef kitle eklenmeye çalışılıyor - BAŞLANGIÇ', [
            'http_method' => $request->method(),
            'url' => $request->url(),
            'is_ajax' => $request->ajax(),
            'all_input' => $request->all(),
            'has_csrf' => $request->has('_token'),
            'csrf' => $request->input('_token'),
            'headers' => $request->headers->all(),
        ]);
        
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'order' => 'nullable|integer|min:0',
            ]);
            
            \Illuminate\Support\Facades\Log::info('Validasyon başarılı');
            
            // Slug oluştur
            $slug = Str::slug($request->name);
            
            // Slug kontrolü
            $existingCategory = HedefKitle::where('slug', $slug)->first();
            if ($existingCategory) {
                \Illuminate\Support\Facades\Log::warning('Hedef kitle zaten mevcut', ['slug' => $slug]);
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Bu isme sahip bir hedef kitle zaten mevcut. Lütfen farklı bir isim seçin.');
            }
            
            $hedefKitleData = [
                'name' => $request->name,
                'slug' => $slug,
                'description' => $request->description,
                'order' => $request->order ?? 0,
                'is_active' => $request->has('is_active') ? true : false
            ];
            
            \Illuminate\Support\Facades\Log::info('Hedef kitle verileri hazırlandı', $hedefKitleData);
            
            // DB işlemini izole et
            \Illuminate\Support\Facades\DB::beginTransaction();
            
            $hedefKitle = HedefKitle::create($hedefKitleData);
            
            \Illuminate\Support\Facades\DB::commit();
            
            \Illuminate\Support\Facades\Log::info('Hedef kitle başarıyla eklendi', [
                'hedef_kitle_id' => $hedefKitle->id, 
                'hedef_kitle' => $hedefKitle->toArray()
            ]);
            
            return redirect()->route('admin.hedef-kitleler.index')
                ->with('success', 'Hedef kitle başarıyla oluşturuldu.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Illuminate\Support\Facades\Log::error('Validasyon hatası', [
                'errors' => $e->errors(),
            ]);
            
            throw $e; // Laravel'in kendi hata işleme mekanizmasına bırak
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Hedef kitle eklenirken hata oluştu', [
                'error' => $e->getMessage(),
                'class' => get_class($e),
                'trace' => $e->getTraceAsString()
            ]);
            
            if (\Illuminate\Support\Facades\DB::transactionLevel() > 0) {
                \Illuminate\Support\Facades\DB::rollBack();
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Hedef kitle oluşturulurken bir hata oluştu: ' . $e->getMessage());
        }
    }
    
    /**
     * Hedef kitle detaylarını göster
     */
    public function show(HedefKitle $hedefKitle)
    {
        // Hedef kitlenin haberlerini getir
        $news = $hedefKitle->news()
            ->select('news.*')
            ->orderBy('news.published_at', 'desc')
            ->paginate(10);
            
        // Hedef kitlenin hizmetlerini getir
        $services = $hedefKitle->services()
            ->select('services.*')
            ->orderBy('services.created_at', 'desc')
            ->paginate(10);
            
        return view('admin.hedef-kitleler.show', compact('hedefKitle', 'news', 'services'));
    }
    
    /**
     * Hedef kitle düzenleme formu
     */
    public function edit(HedefKitle $hedefKitle)
    {
        return view('admin.hedef-kitleler.edit', compact('hedefKitle'));
    }
    
    /**
     * Hedef kitle güncelleme
     */
    public function update(Request $request, HedefKitle $hedefKitle)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);
        
        // Slug kontrolü
        $newSlug = Str::slug($request->name);
        $existingCategory = HedefKitle::where('slug', $newSlug)
            ->where('id', '!=', $hedefKitle->id)
            ->first();
            
        if ($existingCategory) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Bu isme sahip başka bir hedef kitle zaten mevcut. Lütfen farklı bir isim seçin.');
        }
        
        $hedefKitle->update([
            'name' => $request->name,
            'slug' => $newSlug,
            'description' => $request->description,
            'order' => $request->order ?? 0,
            'is_active' => $request->has('is_active')
        ]);
        
        return redirect()->route('admin.hedef-kitleler.index')
            ->with('success', 'Hedef kitle başarıyla güncellendi.');
    }
    
    /**
     * Hedef kitle silme
     */
    public function destroy(HedefKitle $hedefKitle)
    {
        // İlişkili haber kontrolü
        if ($hedefKitle->news()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Bu hedef kitleye ait haberler bulunduğu için silinemez. Önce haberleri başka hedef kitlelere taşıyın veya hedef kitle ilişkisini kaldırın.');
        }
        
        // İlişkili hizmet kontrolü
        if ($hedefKitle->services()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Bu hedef kitleye ait hizmetler bulunduğu için silinemez. Önce hizmetleri başka hedef kitlelere taşıyın veya hedef kitle ilişkisini kaldırın.');
        }
            
        $hedefKitle->delete();
        
        return redirect()->route('admin.hedef-kitleler.index')
            ->with('success', 'Hedef kitle başarıyla silindi.');
    }
    
    /**
     * Hedef kitle sıralama
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'hedefKitleler' => 'required|array',
            'hedefKitleler.*' => 'exists:hedef_kitleler,id'
        ]);
        
        foreach ($request->hedefKitleler as $order => $id) {
            HedefKitle::where('id', $id)->update(['order' => $order + 1]);
        }
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Belirli bir hedef kitlenin haberlerini getir
     */
    public function getNews(HedefKitle $hedefKitle)
    {
        // Hedef kitlenin ilişkili haberlerini getir
        $news = $hedefKitle->news()
            ->select('news.*')
            ->orderBy('news.published_at', 'desc')
            ->paginate(10);
            
        // Partial view döndür
        return view('admin.hedef-kitleler.partials.news-list', compact('news', 'hedefKitle'));
    }
    
    /**
     * Belirli bir hedef kitlenin hizmetlerini getir
     */
    public function getServices(HedefKitle $hedefKitle)
    {
        // Hedef kitlenin ilişkili hizmetlerini getir
        $services = $hedefKitle->services()
            ->select('services.*')
            ->orderBy('services.created_at', 'desc')
            ->paginate(10);
            
        // Partial view döndür
        return view('admin.hedef-kitleler.partials.services-list', compact('services', 'hedefKitle'));
    }
} 