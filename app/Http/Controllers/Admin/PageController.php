<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePageRequest;
use App\Http\Requests\Admin\UpdatePageRequest;
use App\Models\Page;
use App\Models\PageCategory;
use App\Services\PageService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
        $pageCategories = PageCategory::where('is_active', true)->orderBy('name')->get();
        
        // Debug bilgisi
        \Illuminate\Support\Facades\Log::info('Page Create Debug: ', [
            'pageCategories' => $pageCategories->toArray(),
            'count' => $pageCategories->count(),
            'maxFeaturedReached' => $maxFeaturedReached
        ]);
        
        return view('admin.pages.create', compact('maxFeaturedReached', 'pageCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePageRequest $request)
    {
        // Debug başlangıcı
        \Illuminate\Support\Facades\Log::info('Page Store Debug - Başlangıç: ', [
            'request_all' => $request->all(),
            'request_validated' => $request->validated(),
            'submitted_categories' => $request->input('categories', [])
        ]);
        
        try {
            // PageService'i çağırmadan önce sadece title kontrolü yap
            $validatedData = $request->validated();
            
            // Title kontrolü
            if (empty($validatedData['title'])) {
                throw new \Exception('Başlık alanı boş olamaz');
            }
            
            // Eğer kategori seçilmemişse boş dizi olarak ayarla
            if (!isset($validatedData['categories'])) {
                $validatedData['categories'] = [];
            }
            
            $page = $this->pageService->createPage($validatedData);
            
            \Illuminate\Support\Facades\Log::info('Page Store Debug - Sonuç: ', [
                'success' => !empty($page),
                'page_id' => $page->id ?? null,
                'page_title' => $page->title ?? null
            ]);
            
            if ($page) {
                return redirect()->route('admin.pages.index')->with('success', 'Sayfa başarıyla oluşturuldu.');
            }
            
            return redirect()->back()->with('error', 'Sayfa oluşturulurken bir hata oluştu.')->withInput();
        } catch (\Exception $e) {
            // Hata bilgisini doğrudan kullanıcıya göster
            \Illuminate\Support\Facades\Log::error('Page Store Debug - Hata: ', [
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Sayfa oluşturulurken bir hata oluştu: ' . $e->getMessage());
        }
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
        $pageCategories = PageCategory::where('is_active', true)->orderBy('name')->get();
        $selectedCategories = $page->categories->pluck('id')->toArray();
        
        // Collection sorununu çözmek için
        try {
            // PHP 8.2 uyumlu yol - pluck ve sonra toArray
            if ($page->tags && $page->tags->count() > 0) {
                $tagsArray = $page->tags->pluck('name')->toArray();
                $tags = implode(',', $tagsArray);
            } else {
                $tags = '';
            }
        } catch (\Exception $e) {
            // Hata durumunda boş string
            $tags = '';
            \Illuminate\Support\Facades\Log::error('Tags implode error: ' . $e->getMessage());
        }
        
        // Debug
        \Illuminate\Support\Facades\Log::info('Page Edit Debug: ', [
            'page_id' => $page->id,
            'tags_type' => gettype($tags),
            'tags_value' => $tags,
            'tags_count' => $page->tags->count()
        ]);
        
        return view('admin.pages.edit', compact('page', 'maxFeaturedReached', 'pageCategories', 'selectedCategories', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePageRequest $request, Page $page)
    {
        try {
            // Debug bilgisi
            \Illuminate\Support\Facades\Log::info('Page Update Debug - Başlangıç: ', [
                'request_all' => $request->all(),
                'request_validated' => $request->validated(),
                'page_id' => $page->id
            ]);
            
            // Debug için forma gelen verileri session'a kaydet
            $debugData = [
                'gallery_input' => $request->input('gallery'),
                'gallery_type' => gettype($request->input('gallery')),
                'gallery_count' => is_array($request->input('gallery')) ? count($request->input('gallery')) : 0,
                'request_method' => $request->method(),
                'has_file' => $request->hasFile('gallery'),
                'all_request' => $request->all(),
                'timestamp' => now()->format('Y-m-d H:i:s')
            ];
            session()->flash('debug_data', $debugData);
            
            $result = $this->pageService->updatePage($request->validated(), $page->id);
            
            if ($result) {
                return redirect()->route('admin.pages.index')->with('success', 'Sayfa başarıyla güncellendi.');
            }
            
            return redirect()->back()->with('error', 'Sayfa güncellenirken bir hata oluştu.');
        } catch (\Exception $e) {
            // Hata detaylarını logla
            \Illuminate\Support\Facades\Log::error('Page Update Debug - Hata: ', [
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Sayfa güncellenirken bir hata oluştu: ' . $e->getMessage());
        }
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
            $file = $request->file('file');
            $originalFilename = time() . '_' . Str::random(10);
            $extension = $file->getClientOriginalExtension();
            $uploadPath = 'uploads/pages/gallery';
            
            // Benzersiz dosya adı oluştur
            $filename = $this->createUniqueFilename($uploadPath, $originalFilename, $extension);
            
            $file->move(public_path($uploadPath), $filename);
            $path = $uploadPath . '/' . $filename;
            
            return response()->json(['location' => asset($path)]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Resim yüklenirken bir hata oluştu: ' . $e->getMessage()], 500);
        }
    }
    
    /**
     * Benzersiz dosya adı oluştur
     * Eğer aynı isimde dosya varsa sonuna sayı ekler (örn: resim_1.jpg, resim_2.jpg)
     *
     * @param string $path Dizin yolu
     * @param string $filename Dosya adı (uzantısız)
     * @param string $extension Dosya uzantısı
     * @return string Benzersiz dosya adı (uzantı dahil)
     */
    private function createUniqueFilename($path, $filename, $extension)
    {
        $fullFilename = $filename . '.' . $extension;
        $fullPath = public_path($path . '/' . $fullFilename);
        
        if (!file_exists($fullPath)) {
            return $fullFilename;
        }
        
        $counter = 1;
        while (file_exists($fullPath)) {
            $fullFilename = $filename . '_' . $counter . '.' . $extension;
            $fullPath = public_path($path . '/' . $fullFilename);
            $counter++;
        }
        
        return $fullFilename;
    }
} 