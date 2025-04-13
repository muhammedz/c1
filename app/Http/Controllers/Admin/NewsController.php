<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreNewsRequest;
use App\Http\Requests\Admin\UpdateNewsRequest;
use App\Models\News;
use App\Models\NewsCategory;
use App\Services\NewsService;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * @var NewsService
     */
    protected $newsService;
    
    /**
     * NewsController constructor.
     * 
     * @param NewsService $newsService
     */
    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
        // Burada yetki kontrolü eklenebilir
        // $this->middleware('permission:news-list', ['only' => ['index']]);
        // $this->middleware('permission:news-create', ['only' => ['create', 'store']]);
        // $this->middleware('permission:news-edit', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:news-delete', ['only' => ['destroy']]);
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->all();
        $news = $this->newsService->getNews($filters);
        $headlineCount = News::where('is_headline', true)->count();
        $newsCategories = NewsCategory::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.news.index', compact('news', 'headlineCount', 'newsCategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $headlineCount = News::where('is_headline', true)->count();
        $maxHeadlinesReached = $headlineCount >= 4;
        $newsCategories = NewsCategory::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.news.create', compact('maxHeadlinesReached', 'newsCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNewsRequest $request)
    {
        $news = $this->newsService->createNews($request->validated());
        
        if ($news) {
            return redirect()->route('admin.news.index')->with('success', 'Haber başarıyla oluşturuldu.');
        }
        
        return redirect()->back()->with('error', 'Haber oluşturulurken bir hata oluştu.');
    }

    /**
     * Display the specified resource.
     */
    public function show(News $news)
    {
        $news->load(['categories', 'tags']);
        return view('admin.news.show', compact('news'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(News $news)
    {
        $headlineCount = News::where('is_headline', true)->count();
        $maxHeadlinesReached = $headlineCount >= 4 && !$news->is_headline;
        $newsCategories = NewsCategory::where('is_active', true)->orderBy('name')->get();
        $selectedCategories = $news->categories->pluck('id')->toArray();
        $tags = $news->tags->pluck('name')->implode(',');
        
        return view('admin.news.edit', compact('news', 'maxHeadlinesReached', 'newsCategories', 'selectedCategories', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNewsRequest $request, News $news)
    {
        $result = $this->newsService->updateNews($request->validated(), $news->id);
        
        if ($result) {
            return redirect()->route('admin.news.index')->with('success', 'Haber başarıyla güncellendi.');
        }
        
        return redirect()->back()->with('error', 'Haber güncellenirken bir hata oluştu.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(News $news)
    {
        $result = $this->newsService->deleteNews($news->id);
        
        if ($result) {
            return redirect()->route('admin.news.index')->with('success', 'Haber başarıyla silindi.');
        }
        
        return redirect()->back()->with('error', 'Haber silinirken bir hata oluştu.');
    }
    
    /**
     * Toggle headline status.
     */
    public function toggleHeadline(News $news)
    {
        $result = $this->newsService->toggleHeadline($news);
        
        if ($result) {
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false, 'message' => 'Manşet durumu değiştirilirken bir hata oluştu.']);
    }
    
    /**
     * Update headline order.
     */
    public function updateHeadlineOrder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer'
        ]);
        
        $result = $this->newsService->updateHeadlineOrder($request->order);
        
        if ($result) {
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false, 'message' => 'Manşet sırası güncellenirken bir hata oluştu.']);
    }
    
    /**
     * Toggle featured status.
     */
    public function toggleFeatured(News $news)
    {
        $result = $this->newsService->toggleFeatured($news);
        
        if ($result) {
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false, 'message' => 'Öne çıkarma durumu değiştirilirken bir hata oluştu.']);
    }
    
    /**
     * Toggle archive status.
     */
    public function toggleArchive(News $news)
    {
        $result = $this->newsService->toggleArchive($news);
        
        if ($result) {
            return redirect()->back()->with('success', 'Arşiv durumu başarıyla değiştirildi.');
        }
        
        return redirect()->back()->with('error', 'Arşiv durumu değiştirilirken bir hata oluştu.');
    }
    
    /**
     * Toggle publication status.
     */
    public function toggleStatus(News $news)
    {
        $result = $this->newsService->toggleStatus($news);
        
        if ($result) {
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false, 'message' => 'Yayın durumu değiştirilirken bir hata oluştu.']);
    }
    
    /**
     * Upload gallery image.
     */
    public function uploadGalleryImage(Request $request)
    {
        $request->validate([
            'file' => 'required|image|max:2048'
        ]);
        
        try {
            $path = $request->file('file')->store('news/gallery', 'public');
            return response()->json(['location' => '/storage/' . $path]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Resim yüklenirken bir hata oluştu: ' . $e->getMessage()], 500);
        }
    }
}
