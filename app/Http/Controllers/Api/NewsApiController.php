<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NewsResource;
use App\Http\Resources\NewsCollection;
use App\Http\Resources\NewsCategoryResource;
use App\Models\News;
use App\Models\NewsCategory;
use App\Models\NewsTag;
use Illuminate\Http\Request;

class NewsApiController extends Controller
{
    /**
     * Tüm haberleri listeler
     *
     * @param Request $request
     * @return NewsCollection
     */
    public function index(Request $request)
    {
        $query = News::published()->with(['category', 'tags']);
        
        // Sıralama
        $sortField = $request->input('sort_by', 'published_at');
        $sortDirection = $request->input('sort_direction', 'desc');
        $query->orderBy($sortField, $sortDirection);
        
        // Sayfalama
        $perPage = $request->input('per_page', 10);
        $news = $query->paginate($perPage);
        
        return new NewsCollection($news);
    }
    
    /**
     * Belirli bir haberin detaylarını gösterir
     *
     * @param string $slug
     * @return NewsResource
     */
    public function show($slug)
    {
        $news = News::where('slug', $slug)
            ->published()
            ->with(['category', 'tags', 'galleryImages'])
            ->firstOrFail();
        
        // Görüntüleme sayısını artır
        $news->incrementViews();
        
        return new NewsResource($news);
    }
    
    /**
     * Öne çıkan haberleri listeler
     *
     * @param Request $request
     * @return NewsCollection
     */
    public function getFeatured(Request $request)
    {
        $limit = $request->input('limit', 6);
        $news = News::featured()
            ->published()
            ->with(['category', 'tags'])
            ->orderBy('published_at', 'desc')
            ->take($limit)
            ->get();
        
        return new NewsCollection($news);
    }
    
    /**
     * Haber kategorilerini listeler
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getCategories()
    {
        $categories = NewsCategory::where('is_active', true)
            ->orderBy('order', 'asc')
            ->get();
        
        return NewsCategoryResource::collection($categories);
    }
    
    /**
     * Kategoriye göre haberleri listeler
     *
     * @param Request $request
     * @param int $category_id
     * @return NewsCollection
     */
    public function getByCategory(Request $request, $category_id)
    {
        $category = NewsCategory::findOrFail($category_id);
        
        $query = $category->news()
            ->published()
            ->with(['category', 'tags']);
        
        // Sıralama
        $sortField = $request->input('sort_by', 'published_at');
        $sortDirection = $request->input('sort_direction', 'desc');
        $query->orderBy($sortField, $sortDirection);
        
        // Sayfalama
        $perPage = $request->input('per_page', 10);
        $news = $query->paginate($perPage);
        
        return new NewsCollection($news);
    }
    
    /**
     * Haberlerde arama yapar
     *
     * @param Request $request
     * @return NewsCollection
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        
        if (empty($query)) {
            return response()->json([
                'message' => 'Arama sorgusu gereklidir',
                'status' => 'error'
            ], 400);
        }
        
        $newsQuery = News::published()
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%")
                  ->orWhere('summary', 'like', "%{$query}%");
            })
            ->with(['category', 'tags']);
        
        // Sayfalama
        $perPage = $request->input('per_page', 10);
        $news = $newsQuery->paginate($perPage);
        
        return new NewsCollection($news);
    }
} 