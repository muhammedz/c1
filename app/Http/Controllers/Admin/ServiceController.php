<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreServiceRequest;
use App\Http\Requests\Admin\UpdateServiceRequest;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServiceTag;
use App\Services\ServiceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ServiceController extends Controller
{
    /**
     * @var ServiceService
     */
    protected $serviceService;
    
    /**
     * ServiceController constructor.
     * 
     * @param ServiceService $serviceService
     */
    public function __construct(ServiceService $serviceService)
    {
        $this->serviceService = $serviceService;
        // Burada yetki kontrolü eklenebilir
        // $this->middleware('permission:service-list', ['only' => ['index']]);
        // $this->middleware('permission:service-create', ['only' => ['create', 'store']]);
        // $this->middleware('permission:service-edit', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:service-delete', ['only' => ['destroy']]);
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->all();
        $services = $this->serviceService->getServices($filters);
        $headlineCount = Service::where('is_headline', true)->count();
        $serviceCategories = ServiceCategory::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.services.index', compact('services', 'headlineCount', 'serviceCategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $headlineCount = Service::where('is_headline', true)->count();
        $maxHeadlinesReached = $headlineCount >= 4;
        $categories = ServiceCategory::where('is_active', true)->orderBy('name')->get();
        $tags = ServiceTag::orderBy('name')->get();
        
        return view('admin.services.create', compact('maxHeadlinesReached', 'categories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreServiceRequest $request)
    {
        try {
            // Boş array değerlerini temizle
            $features = array_filter($request->input('features', []));
            
            $data = $request->validated();
            
            // URL'deki yinelenen /storage/ yolunu düzelt
            if (isset($data['image'])) {
                $data['image'] = $this->fixStoragePath($data['image']);
            }
            
            // Galeri URL'lerini düzelt
            if (isset($data['gallery']) && is_array($data['gallery'])) {
                foreach ($data['gallery'] as $key => $url) {
                    $data['gallery'][$key] = $this->fixStoragePath($url);
                }
            }
            
            // Kategoriler ve etiketler ayrı tutulur
            $categories = $request->input('categories', []);
            $tags = $request->input('tags', []);
            
            // Tarih ayarlamaları
            if ($request->has('published_at') && !empty($request->published_at)) {
                $data['published_at'] = Carbon::parse($request->published_at);
            } else {
                $data['published_at'] = Carbon::now();
            }
            
            if ($request->has('end_date') && !empty($request->end_date)) {
                $data['end_date'] = Carbon::parse($request->end_date);
            }
            
            // Eğer slug boş geldiyse title'dan oluştur
            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($data['title']);
            }
            
            // Özellikler dizisini ekleyelim
            $data['features'] = $features;
            
            // Diğer alanlar
            $data['view_count'] = 0;
            $data['is_scheduled'] = !empty($request->end_date);
            
            // Yeni service objesi oluştur
            $service = Service::create($data);
            
            // Kategorileri ekle
            if (!empty($categories)) {
                $service->categories()->sync($categories);
            }
            
            // Etiketleri ekle
            if (!empty($tags)) {
                // Önce etiketleri düzgün formatta hazırla
                $tagIds = [];
                foreach ($tags as $tag) {
                    $tagModel = ServiceTag::findOrCreateByName($tag);
                    $tagIds[] = $tagModel->id;
                }
                
                // Etiketleri service ile ilişkilendir
                if (!empty($tagIds)) {
                    $service->tags()->sync($tagIds);
                }
            }
            
            return redirect()->route('admin.services.edit', $service->id)
                             ->with('success', 'Hizmet başarıyla oluşturuldu.');
        } catch (\Exception $e) {
            Log::error('Hizmet oluşturma hatası: ' . $e->getMessage());
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Hizmet oluşturulurken bir hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        $service->load(['categories', 'tags']);
        return view('admin.services.show', compact('service'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        // Gallery değerini kontrol et, eğer string ise boş dizi olarak ayarla
        if ($service->gallery && !is_array($service->gallery)) {
            $service->gallery = [];
        }
        
        $headlineCount = Service::where('is_headline', true)->count();
        $maxHeadlinesReached = $headlineCount >= 4 && !$service->is_headline;
        $categories = ServiceCategory::where('is_active', true)->orderBy('name')->get();
        $selectedCategories = $service->categories->pluck('id')->toArray();
        $tags = ServiceTag::orderBy('name')->get();
        
        return view('admin.services.edit', compact('service', 'maxHeadlinesReached', 'categories', 'selectedCategories', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateServiceRequest $request, Service $service)
    {
        try {
            // Boş array değerlerini temizle
            $features = array_filter($request->input('features', []));
            
            $data = $request->validated();
            
            // URL'deki yinelenen /storage/ yolunu düzelt
            if (isset($data['image'])) {
                $data['image'] = $this->fixStoragePath($data['image']);
            }
            
            // Galeri URL'lerini düzelt
            if (isset($data['gallery']) && is_array($data['gallery'])) {
                foreach ($data['gallery'] as $key => $url) {
                    $data['gallery'][$key] = $this->fixStoragePath($url);
                }
            }
            
            // Kategoriler ve etiketler ayrı tutulur
            $categories = $request->input('categories', []);
            $tags = $request->input('tags', []);
            
            // Tarih ayarlamaları
            if ($request->has('published_at') && !empty($request->published_at)) {
                $data['published_at'] = Carbon::parse($request->published_at);
            }
            
            if ($request->has('end_date') && !empty($request->end_date)) {
                $data['end_date'] = Carbon::parse($request->end_date);
            } else {
                $data['end_date'] = null;
            }
            
            // Eğer slug boş geldiyse title'dan oluştur
            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($data['title']);
            }
            
            // Özellikler dizisini ekleyelim
            $data['features'] = $features;
            
            // Yayınlanma durumu ile ilgili özel ayarlar
            $data['is_scheduled'] = !empty($request->end_date);
            
            // Hizmeti güncelle
            $service->update($data);
            
            // Kategorileri güncelle
            $service->categories()->sync($categories);
            
            // Etiketleri güncelle
            if (!empty($tags)) {
                // Önce etiketleri düzgün formatta hazırla
                $tagIds = [];
                foreach ($tags as $tag) {
                    $tagModel = ServiceTag::findOrCreateByName($tag);
                    $tagIds[] = $tagModel->id;
                }
                
                // Etiketleri service ile ilişkilendir
                $service->tags()->sync($tagIds);
            } else {
                $service->tags()->detach();
            }
            
            return redirect()->route('admin.services.edit', $service->id)
                             ->with('success', 'Hizmet başarıyla güncellendi.');
        } catch (\Exception $e) {
            Log::error('Hizmet güncelleme hatası: ' . $e->getMessage());
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Hizmet güncellenirken bir hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        $result = $this->serviceService->deleteService($service->id);
        
        if ($result) {
            return redirect()->route('admin.services.index')->with('success', 'Hizmet başarıyla silindi.');
        }
        
        return redirect()->back()->with('error', 'Hizmet silinirken bir hata oluştu.');
    }
    
    /**
     * Toggle headline status.
     */
    public function toggleHeadline(Service $service)
    {
        $result = $this->serviceService->toggleHeadline($service);
        
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
        
        $result = $this->serviceService->updateHeadlineOrder($request->order);
        
        if ($result) {
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false, 'message' => 'Manşet sırası güncellenirken bir hata oluştu.']);
    }
    
    /**
     * Toggle featured status.
     */
    public function toggleFeatured(Service $service)
    {
        $result = $this->serviceService->toggleFeatured($service);
        
        if ($result) {
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false, 'message' => 'Öne çıkarma durumu değiştirilirken bir hata oluştu.']);
    }
    
    /**
     * Toggle archive status.
     */
    public function toggleArchive(Service $service)
    {
        $result = $this->serviceService->toggleArchive($service);
        
        if ($result) {
            return redirect()->back()->with('success', 'Arşiv durumu başarıyla değiştirildi.');
        }
        
        return redirect()->back()->with('error', 'Arşiv durumu değiştirilirken bir hata oluştu.');
    }
    
    /**
     * Toggle publication status.
     */
    public function toggleStatus(Service $service)
    {
        $result = $this->serviceService->toggleStatus($service);
        
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
            $uploadPath = 'uploads/services/gallery';
            
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

    /**
     * URL'deki yinelenen /storage/ yolunu düzeltir
     */
    private function fixStoragePath($url)
    {
        // Boş URL kontrolü
        if (empty($url)) {
            return $url;
        }
        
        // Yinelenen /storage/ yolunu düzelt
        if (strpos($url, '/storage//storage/') !== false) {
            return str_replace('/storage//storage/', '/storage/', $url);
        }
        
        if (strpos($url, '/storage/storage/') !== false) {
            return str_replace('/storage/storage/', '/storage/', $url);
        }
        
        // Eğer URL http:// veya https:// ile başlıyorsa
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            try {
                $parsed = parse_url($url);
                $path = $parsed['path'] ?? '';
                
                // Path içinde yinelenen /storage/ yolunu düzelt
                if (strpos($path, '/storage//storage/') !== false) {
                    $path = str_replace('/storage//storage/', '/storage/', $path);
                }
                
                if (strpos($path, '/storage/storage/') !== false) {
                    $path = str_replace('/storage/storage/', '/storage/', $path);
                }
                
                return $path;
            } catch (\Exception $e) {
                return $url;
            }
        }
        
        return $url;
    }

    /**
     * Hizmetler modülü için debug bilgisi göster
     */
    public function debug()
    {
        $routes = \Route::getRoutes();
        $serviceRoutes = [];
        
        foreach ($routes as $route) {
            // Admin servisleri ile ilgili tüm rotaları göster
            if ((str_contains($route->uri, 'services') || str_contains($route->uri, 'service')) && 
                (str_contains($route->uri, 'admin') || str_contains($route->action['prefix'] ?? '', 'admin'))) {
                $serviceRoutes[] = [
                    'uri' => $route->uri,
                    'name' => $route->getName(),
                    'methods' => $route->methods(),
                    'controller' => $route->getActionName(),
                    'middleware' => isset($route->action['middleware']) ? implode(', ', (array) $route->action['middleware']) : 'none'
                ];
            }
        }
        
        // View dosyalarını kontrol et
        $viewPath = resource_path('views/admin/services');
        $views = [];
        
        if (is_dir($viewPath)) {
            $directories = new \RecursiveDirectoryIterator($viewPath);
            $iterator = new \RecursiveIteratorIterator($directories);
            
            foreach ($iterator as $file) {
                if ($file->isFile() && $file->getExtension() === 'php') {
                    $relativePath = str_replace(resource_path('views/'), '', $file->getPathname());
                    $views[] = $relativePath;
                }
            }
        }
        
        // Mevcut debug sayfası rotaları
        $debugRoutes = [
            'direct' => url('/admin/services/debug'),
            'named_direct' => route('direct.services.debug'),
            'admin_prefix' => url('/admin/services/debug'),
            'current_url' => request()->url(),
        ];
        
        return view('admin.services.debug', compact('serviceRoutes', 'views', 'debugRoutes'));
    }
}
