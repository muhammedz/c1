<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePageRequest;
use App\Http\Requests\Admin\UpdatePageRequest;
use App\Models\Page;
use App\Models\PageCategory;
use App\Services\PageService;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * @var PageService
     */
    protected $pageService;
    
    /**
     * PageController constructor.
     * 
     * @param PageService $pageService
     */
    public function __construct(PageService $pageService)
    {
        $this->pageService = $pageService;
        // Burada yetki kontrolü eklenebilir
        // $this->middleware('permission:page-list', ['only' => ['index']]);
        // $this->middleware('permission:page-create', ['only' => ['create', 'store']]);
        // $this->middleware('permission:page-edit', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:page-delete', ['only' => ['destroy']]);
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->all();
        $pages = $this->pageService->getPages($filters);
        $featuredCount = Page::where('is_featured', true)->count();
        $pageCategories = PageCategory::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.pages.index', compact('pages', 'featuredCount', 'pageCategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $featuredCount = Page::where('is_featured', true)->count();
        $maxFeaturedReached = $featuredCount >= 4;
        $pageCategories = collect();
        
        return view('admin.pages.create', compact('maxFeaturedReached', 'pageCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePageRequest $request)
    {
        $page = $this->pageService->createPage($request->validated());
        
        if ($page) {
            return redirect()->route('admin.pages.index')->with('success', 'Sayfa başarıyla oluşturuldu.');
        }
        
        return redirect()->back()->with('error', 'Sayfa oluşturulurken bir hata oluştu.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Page $page)
    {
        $page->load(['categories', 'tags']);
        return view('admin.pages.show', compact('page'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Page $page)
    {
        $featuredCount = Page::where('is_featured', true)->count();
        $maxFeaturedReached = $featuredCount >= 4 && !$page->is_featured;
        $pageCategories = collect();
        $selectedCategories = [];
        $tags = $page->tags->pluck('name')->implode(',');
        
        return view('admin.pages.edit', compact('page', 'maxFeaturedReached', 'pageCategories', 'selectedCategories', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePageRequest $request, Page $page)
    {
        $result = $this->pageService->updatePage($request->validated(), $page->id);
        
        if ($result) {
            return redirect()->route('admin.pages.index')->with('success', 'Sayfa başarıyla güncellendi.');
        }
        
        return redirect()->back()->with('error', 'Sayfa güncellenirken bir hata oluştu.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page)
    {
        $result = $this->pageService->deletePage($page->id);
        
        if ($result) {
            return redirect()->route('admin.pages.index')->with('success', 'Sayfa başarıyla silindi.');
        }
        
        return redirect()->back()->with('error', 'Sayfa silinirken bir hata oluştu.');
    }
    
    /**
     * Toggle featured status.
     */
    public function toggleFeatured(Page $page)
    {
        $result = $this->pageService->toggleFeatured($page);
        
        if ($result) {
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false, 'message' => 'Öne çıkarma durumu değiştirilirken bir hata oluştu.']);
    }
    
    /**
     * Update featured order.
     */
    public function updateFeaturedOrder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer'
        ]);
        
        $result = $this->pageService->updateFeaturedOrder($request->order);
        
        if ($result) {
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false, 'message' => 'Öne çıkan sayfa sırası güncellenirken bir hata oluştu.']);
    }
    
    /**
     * Toggle archive status.
     */
    public function toggleArchive(Page $page)
    {
        $result = $this->pageService->toggleArchive($page);
        
        if ($result) {
            return redirect()->back()->with('success', 'Arşiv durumu başarıyla değiştirildi.');
        }
        
        return redirect()->back()->with('error', 'Arşiv durumu değiştirilirken bir hata oluştu.');
    }
    
    /**
     * Toggle publication status.
     */
    public function toggleStatus(Page $page)
    {
        $result = $this->pageService->toggleStatus($page);
        
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
            $path = $request->file('file')->store('pages/gallery', 'public');
            return response()->json(['location' => '/storage/' . $path]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Resim yüklenirken bir hata oluştu: ' . $e->getMessage()], 500);
        }
    }
} 