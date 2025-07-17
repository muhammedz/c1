<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class NewsController extends Controller
{
    /**
     * Haber listesi sayfasını göster
     */
    public function index(Request $request)
    {
        $categories = NewsCategory::whereHas('news', function($query) {
            $query->where('status', true);
        })->get();

        // Arama varsa, sadece arama sonuçlarını göster
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            
            $news = News::with(['category', 'categories', 'hedefKitleler'])
                ->where('status', true)
                ->where(function($query) use ($searchTerm) {
                    $query->where('title', 'like', '%' . $searchTerm . '%')
                        ->orWhere('content', 'like', '%' . $searchTerm . '%')
                        ->orWhere('summary', 'like', '%' . $searchTerm . '%')
                        // Hedef kitle adına göre arama
                        ->orWhereHas('hedefKitleler', function($q) use ($searchTerm) {
                            $q->where('name', 'like', '%' . $searchTerm . '%');
                        });
                })
                ->when($request->category, function($query) use ($request) {
                    $query->whereHas('categories', function($q) use ($request) {
                        $q->where('news_categories.id', $request->category);
                    });
                })
                ->orderBy('published_at', 'desc')
                ->paginate(20);

            // Arama sonuçları için özel view
            return view('front.news.search', compact('news', 'categories', 'searchTerm'));
        }

        // Normal haber listesi (kategori bazlı)
        $news = News::with(['category', 'categories'])
            ->where('status', true)
            ->when($request->category, function($query) use ($request) {
                $query->whereHas('categories', function($q) use ($request) {
                    $q->where('news_categories.id', $request->category);
                });
            })
            ->orderBy('published_at', 'desc')
            ->paginate(20);

        // Yaklaşan etkinlikleri getir
        $upcomingEvents = \App\Models\Event::with('category')
            ->where('is_active', true)
            ->where('start_date', '>=', now())
            ->orderBy('start_date', 'asc')
            ->take(10)
            ->get();

        return view('front.news.index', compact('news', 'categories', 'upcomingEvents'));
    }

    /**
     * Kategori bazlı haber listesi
     */
    public function category($slug)
    {
        $category = NewsCategory::where('slug', $slug)->firstOrFail();
        
        $news = News::with(['category', 'categories'])
            ->whereHas('categories', function($query) use ($category) {
                $query->where('news_categories.id', $category->id);
            })
            ->where('status', true)
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        return view('front.news.category', compact('news', 'category'));
    }

    /**
     * Tekil haber detay sayfası
     */
    public function show($slug)
    {
        $news = News::with(['documents' => function($query) {
                $query->where('is_active', true)->orderBy('sort_order');
            }])
            ->where('slug', $slug)
            ->where('status', true)
            ->firstOrFail();

        // Görüntülenme sayısını artır
        $news->increment('views');

        return view('front.news.show', compact('news'));
    }

    /**
     * Haber belgesini indir
     */
    public function downloadDocument($newsSlug, $documentId)
    {
        $news = News::where('slug', $newsSlug)
            ->where('status', true)
            ->firstOrFail();

        $document = $news->documents()
            ->where('id', $documentId)
            ->where('is_active', true)
            ->firstOrFail();

        if (!$document->fileExists()) {
            abort(404, 'Dosya bulunamadı.');
        }

        return response()->download(public_path($document->file_path), $document->file_name);
    }

    /**
     * Haberi PDF olarak indir
     * 
     * Bu method, verilen slug'a sahip haberi PDF formatında kullanıcıya sunar.
     * PDF içeriği anlık olarak oluşturulur ve diske yazılmaz.
     * 
     * @param string $slug Haber slug'ı
     * @return \Illuminate\Http\Response PDF response
     * 
     * @example GET /haber/ornek-haber-basligi/pdf
     */
    public function downloadPdf($slug)
    {
        // Haberi veritabanından çek (aktif ve yayınlanmış olmalı)
        $news = News::where('slug', $slug)
            ->where('status', true)
            ->firstOrFail();

        // PDF için özel view hazırla
        $pdf = Pdf::loadView('pdf.haber-pdf', compact('news'));
        
        // PDF ayarları
        $pdf->setPaper('A4', 'portrait'); // A4 boyut, dikey
        $pdf->setOption('defaultFont', 'DejaVu Sans'); // Türkçe karakter desteği
        
        // PDF dosya adını hazırla (tarih + başlık)
        $filename = date('Y-m-d') . '_' . Str::slug($news->title) . '.pdf';
        
        // PDF'i kullanıcıya indir
        return $pdf->download($filename);
    }
} 