<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreNewsRequest;
use App\Http\Requests\Admin\UpdateNewsRequest;
use App\Models\News;
use App\Models\NewsCategory;
use App\Models\HedefKitle;
use App\Models\NewsTag;
use App\Services\NewsService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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
        
        // Varsayılan olarak manşet haberleri de getir
        if (!isset($filters['headline'])) {
            $filters['headline'] = 'all';
        }
        
        // Varsayılan sıralama parametrelerini ayarla (yayın tarihine göre en yeni üstte)
        if (!isset($filters['sort'])) {
            $filters['sort'] = 'published_at';
        }
        if (!isset($filters['direction'])) {
            $filters['direction'] = 'desc';
        }
        
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
        // Manşet haberi limiti kontrolü
        $maxHeadlinesReached = News::where('is_headline', true)->count() >= 4;
        
        // Kategorileri al
        $newsCategories = NewsCategory::orderBy('name')->get();
        
        // Hedef kitleleri al
        $hedefKitleler = HedefKitle::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.news.create', compact('maxHeadlinesReached', 'newsCategories', 'hedefKitleler'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNewsRequest $request)
    {
        try {
            $news = $this->newsService->createNews($request->validated());
            
            // Validasyon başarılı, haber oluşturuluyor
            DB::beginTransaction();

            // Haber etiketlerini ekle
            if ($request->has('tags') && !empty($request->tags)) {
                $tagNames = explode(',', $request->tags);
                foreach ($tagNames as $tagName) {
                    $tagName = trim($tagName);
                    if (!empty($tagName)) {
                        $tag = NewsTag::firstOrCreate(['name' => $tagName, 'slug' => Str::slug($tagName)]);
                        $news->tags()->attach($tag->id);
                    }
                }
            }
            
            // Hedef kitleleri ekle
            if ($request->has('hedef_kitleler')) {
                $news->hedefKitleler()->sync($request->hedef_kitleler);
            }

            // Belgeleri yükle (eğer varsa)
            if ($request->hasFile('files')) {
                $this->uploadDocuments($request, $news);
            }

            DB::commit();
            
            return redirect()->route('admin.news.index')->with('success', 'Haber başarıyla oluşturuldu.');
        } catch (\Exception $e) {
            // Hata detaylarını logla
            Log::error('NewsController hata: ' . $e->getMessage());
            
            // Hata mesajını kullanıcıya göster
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }
    
    /**
     * Son log kayıtlarından hata mesajını alır
     */
    private function getLatestErrorLog()
    {
        try {
            $logPath = storage_path('logs/laravel-' . date('Y-m-d') . '.log');
            if (file_exists($logPath)) {
                $logs = file_get_contents($logPath);
                $lines = explode("\n", $logs);
                $lines = array_reverse($lines);
                
                foreach ($lines as $line) {
                    if (strpos($line, 'Haber oluşturma hatası:') !== false) {
                        // Hata mesajını ayıkla
                        preg_match('/Haber oluşturma hatası: (.*?)(\[|$)/', $line, $matches);
                        if (isset($matches[1])) {
                            return trim($matches[1]);
                        }
                    }
                }
            }
            return null;
        } catch (\Exception $e) {
            return null;
        }
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
        // Documents ilişkisini yükle
        $news->load('documents');
        
        // Galeri verilerini hem MediaRelation'lardan hem de JSON alanından yükle
        $galleryData = [];
        
        // Debug: Mevcut galeri verilerini logla
        \Log::info('Edit sayfası galeri debug:', [
            'news_id' => $news->id,
            'filemanagersystem_gallery_raw' => $news->getRawOriginal('filemanagersystem_gallery'),
            'filemanagersystem_gallery_cast' => $news->filemanagersystem_gallery,
            'filemanagersystem_gallery_type' => gettype($news->filemanagersystem_gallery)
        ]);
        
        // Önce MediaRelation'lardan kontrol et
        $galleryImages = $news->galleryImages();
        \Log::info('MediaRelation galeri sayısı:', ['count' => $galleryImages->count()]);
        
        if ($galleryImages->count() > 0) {
            foreach ($galleryImages as $media) {
                $galleryData[] = [
                    'id' => $media->id,
                    'url' => $media->url,
                    'name' => $media->original_name ?? 'Galeri Resmi',
                    'size' => $media->size ?? 0
                ];
            }
            \Log::info('MediaRelation\'dan yüklenen galeri:', ['count' => count($galleryData)]);
        } 
        // Eğer MediaRelation'larda veri yoksa, JSON alanından yükle
        elseif (!empty($news->filemanagersystem_gallery)) {
            $existingGallery = $news->filemanagersystem_gallery;
            \Log::info('JSON alanından galeri yükleniyor:', [
                'type' => gettype($existingGallery),
                'data' => $existingGallery
            ]);
            
            // String ise JSON decode et
            if (is_string($existingGallery)) {
                $decodedGallery = json_decode($existingGallery, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decodedGallery)) {
                    $existingGallery = $decodedGallery;
                    \Log::info('String JSON decode edildi:', ['decoded' => $existingGallery]);
                }
            }
            
            if (is_array($existingGallery)) {
                foreach ($existingGallery as $index => $item) {
                    if (is_string($item)) {
                        // String URL formatında
                        $galleryData[] = [
                            'id' => 'existing_' . $index,
                            'url' => $item,
                            'name' => 'Mevcut Resim ' . ($index + 1),
                            'size' => 0
                        ];
                    } elseif (is_array($item) && isset($item['url'])) {
                        // Object formatında
                        $galleryData[] = [
                            'id' => $item['id'] ?? 'existing_' . $index,
                            'url' => $item['url'],
                            'name' => $item['name'] ?? 'Mevcut Resim ' . ($index + 1),
                            'size' => $item['size'] ?? 0
                        ];
                    }
                }
                \Log::info('JSON\'dan yüklenen galeri:', ['count' => count($galleryData), 'data' => $galleryData]);
            } else {
                \Log::warning('Galeri verisi array değil:', ['type' => gettype($existingGallery), 'data' => $existingGallery]);
            }
        } else {
            \Log::info('Galeri verisi bulunamadı');
        }
        
        // filemanagersystem_gallery alanını güncel verilerle doldur
        $news->filemanagersystem_gallery = $galleryData;
        \Log::info('Final galeri verisi:', ['count' => count($galleryData), 'data' => $galleryData]);
        
        $headlineCount = News::where('is_headline', true)->count();
        $maxHeadlinesReached = $headlineCount >= 4 && !$news->is_headline;
        $newsCategories = NewsCategory::where('is_active', true)->orderBy('name')->get();
        $selectedCategories = $news->categories->pluck('id')->toArray();
        $tags = $news->tags->pluck('name')->implode(',');
        $hedefKitleler = HedefKitle::orderBy('name')->get();
        
        return view('admin.news.edit', compact('news', 'maxHeadlinesReached', 'newsCategories', 'selectedCategories', 'tags', 'hedefKitleler'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, News $news)
    {
        try {
            // Validasyon başarılı, haber güncelleniyor
            DB::beginTransaction();
            
            // NewsService üzerinden haberi güncelle
            $result = $this->newsService->updateNews($request->all(), $news->id);

            // Haber etiketlerini güncelle
            if ($request->has('tags')) {
                $news->tags()->sync([]);
                if (!empty($request->tags)) {
                    $tagNames = explode(',', $request->tags);
                    foreach ($tagNames as $tagName) {
                        $tagName = trim($tagName);
                        if (!empty($tagName)) {
                            $tag = NewsTag::firstOrCreate(['name' => $tagName, 'slug' => Str::slug($tagName)]);
                            $news->tags()->attach($tag->id);
                        }
                    }
                }
            }
            
            // Hedef kitleleri güncelle
            if ($request->has('hedef_kitleler')) {
                $news->hedefKitleler()->sync($request->hedef_kitleler);
            } else {
                $news->hedefKitleler()->sync([]);
            }

            DB::commit();
            
            // Başarılı ise anasayfaya yönlendir
            return redirect()->route('admin.news.index')->with('success', 'Haber başarıyla güncellendi.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Haber güncelleme hatası: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
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
        
        return response()->json(['success' => false, 'message' => 'Durum değiştirilirken bir hata oluştu.']);
    }
    
    /**
     * Upload gallery image.
     */
    public function uploadGalleryImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:2048'
        ]);
        
        $result = $this->newsService->uploadGalleryImage($request->file('image'));
        
        if ($result) {
            return response()->json([
                'success' => true,
                'url' => $result
            ]);
        }
        
        return response()->json(['success' => false, 'message' => 'Görsel yüklenirken bir hata oluştu.']);
    }
    
    /**
     * Handle bulk actions.
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer',
            'action' => 'required|string|in:delete,publish,draft,archive'
        ]);
        
        $ids = $request->input('ids');
        $action = $request->input('action');
        
        $result = $this->newsService->handleBulkAction($ids, $action);
        
        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => $result['message']
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => $result['message'] ?? 'İşlem sırasında bir hata oluştu.'
        ]);
    }

    /**
     * Upload documents for news
     */
    private function uploadDocuments(Request $request, News $news)
    {
        $files = $request->file('files');
        $names = $request->input('names', []);
        
        // Klasör yoksa oluştur
        $uploadPath = 'uploads/news/documents';
        if (!file_exists(public_path($uploadPath))) {
            mkdir(public_path($uploadPath), 0755, true);
        }

        // En yüksek sıra numarasını bul
        $maxSortOrder = $news->allDocuments()->max('sort_order') ?? 0;

        foreach ($files as $index => $file) {
            try {
                // Dosya bilgilerini al
                $originalFileName = $file->getClientOriginalName();
                $fileSize = $file->getSize();
                $mimeType = $file->getMimeType();
                
                // Dosya adını temizle ve benzersiz yap
                $originalName = pathinfo($originalFileName, PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $fileName = Str::slug($originalName) . '_' . time() . '_' . $index . '.' . $extension;
                
                $filePath = $uploadPath . '/' . $fileName;
                
                // Dosyayı taşı
                $file->move(public_path($uploadPath), $fileName);
                
                // Belge adını belirle
                $documentName = isset($names[$index]) && !empty($names[$index]) 
                    ? $names[$index] 
                    : pathinfo($originalFileName, PATHINFO_FILENAME);
                
                // Veritabanına kaydet
                $news->allDocuments()->create([
                    'name' => $documentName,
                    'description' => null,
                    'file_path' => $filePath,
                    'file_name' => $originalFileName,
                    'file_size' => $fileSize,
                    'mime_type' => $mimeType,
                    'sort_order' => $maxSortOrder + count($files) - $index,
                    'is_active' => true,
                ]);

            } catch (\Exception $e) {
                Log::error('Belge yükleme hatası: ' . $e->getMessage());
            }
        }
    }
}
