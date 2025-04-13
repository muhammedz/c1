<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServiceTag;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Tüm hizmetleri listeler
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $services = Service::with(['categories', 'tags'])
            ->published()
            ->orderBy('published_at', 'desc')
            ->paginate(12);
            
        $categories = ServiceCategory::where('is_active', true)
            ->withCount(['services' => function($query) {
                $query->published();
            }])
            ->orderBy('name')
            ->get();
            
        $tags = ServiceTag::withCount(['services' => function($query) {
                $query->published();
            }])
            ->orderByDesc('usage_count')
            ->limit(20)
            ->get();
            
        return view('front.services.index', compact('services', 'categories', 'tags'));
    }
    
    /**
     * Kategoriye göre hizmet listele
     *
     * @param  string  $slug
     * @return \Illuminate\View\View
     */
    public function category($slug)
    {
        $category = ServiceCategory::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();
            
        $services = $category->services()
            ->published()
            ->orderBy('published_at', 'desc')
            ->paginate(12);
            
        $categories = ServiceCategory::where('is_active', true)
            ->withCount(['services' => function($query) {
                $query->published();
            }])
            ->orderBy('name')
            ->get();
            
        $tags = ServiceTag::withCount(['services' => function($query) {
                $query->published();
            }])
            ->orderByDesc('usage_count')
            ->limit(20)
            ->get();
            
        return view('front.services.category', compact('services', 'categories', 'tags', 'category'));
    }
    
    /**
     * Hizmet detayını gösterir
     *
     * @param  string  $slug
     * @return \Illuminate\View\View
     */
    public function show($slug)
    {
        $service = Service::where('slug', $slug)
            ->published()
            ->firstOrFail();
            
        // Görüntülenme sayısını artır
        $service->incrementViews();
        
        // İlgili hizmetleri getir
        $relatedServices = $service->getRelatedServices(4);
        
        $categories = ServiceCategory::where('is_active', true)
            ->withCount(['services' => function($query) {
                $query->published();
            }])
            ->orderBy('name')
            ->get();
            
        $tags = ServiceTag::withCount(['services' => function($query) {
                $query->published();
            }])
            ->orderByDesc('usage_count')
            ->limit(20)
            ->get();
            
        return view('front.services.show', compact('service', 'relatedServices', 'categories', 'tags'));
    }
} 