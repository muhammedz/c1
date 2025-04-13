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
        $featuredPages = Page::featured()->published()->orderBy('featured_order', 'asc')->get();
        $recentPages = Page::published()->orderBy('published_at', 'desc')->paginate(12);
        $categories = PageCategory::where('is_active', true)->orderBy('name')->get();
        
        return view('front.pages.index', compact('featuredPages', 'recentPages', 'categories'));
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
        $page->incrementViewCount();
        
        // İlişkili sayfaları getir
        $relatedPages = Page::published()
            ->whereHas('categories', function($query) use ($page) {
                $query->whereIn('category_id', $page->categories->pluck('id'));
            })
            ->where('id', '!=', $page->id)
            ->limit(4)
            ->get();
            
        return view('front.pages.show', compact('page', 'relatedPages'));
    }
    
    /**
     * Kategoriye göre sayfaları listele
     */
    public function category($slug)
    {
        $category = PageCategory::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();
            
        $pages = Page::published()
            ->whereHas('categories', function($query) use ($category) {
                $query->where('category_id', $category->id);
            })
            ->orderBy('published_at', 'desc')
            ->paginate(12);
            
        return view('front.pages.category', compact('category', 'pages'));
    }
} 