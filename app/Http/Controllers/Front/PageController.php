<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageCategory;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Tüm sayfaları listele
     */
    public function index()
    {
        $query = Page::published();
        
        // Arama sorgusu varsa filtrele
        if (request()->has('search') && request('search')) {
            $searchTerm = request('search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                  ->orWhere('content', 'like', '%' . $searchTerm . '%');
            });
        }
        
        $featuredPage = Page::featured()->published()->orderBy('featured_order', 'asc')->first();
        $pages = $query->orderBy('published_at', 'desc')->paginate(12);
        $categories = PageCategory::where('is_active', true)->orderBy('name')->get();
        
        return view('front.pages.index', compact('featuredPage', 'pages', 'categories'));
    }
    
    /**
     * Belirli bir sayfayı göster
     */
    public function show($slug)
    {
        $page = Page::published()
            ->where('slug', $slug)
            ->firstOrFail();
            
        // Görüntülenme sayısını artır
        $page->incrementViews();
        
        // Tüm aktif kategorileri getir
        $categories = PageCategory::where('is_active', true)
            ->withCount(['pages' => function($query) {
                $query->published();
            }])
            ->having('pages_count', '>', 0)
            ->orderBy('name')
            ->get();
            
        return view('front.pages.show', compact('page', 'categories'));
    }
    
    /**
     * Kategoriye göre sayfaları listele
     */
    public function category($slug)
    {
        $category = PageCategory::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();
        
        // Tüm sayfaları getir (kategori filtresi kaldırıldı)
        $query = Page::published();
        
        // Arama sorgusu varsa filtrele
        if (request()->has('search') && request('search')) {
            $searchTerm = request('search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                  ->orWhere('content', 'like', '%' . $searchTerm . '%');
            });
        }
            
        // Sıralamayı eski tarihten yeniye çevir (asc) - sayfalama kaldırıldı
        $pages = $query->orderBy('published_at', 'asc')->get();
            
        return view('front.pages.category', compact('category', 'pages'));
    }
} 