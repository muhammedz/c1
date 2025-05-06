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
use Illuminate\Support\Facades\DB;

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
        $categories = ServiceCategory::where('is_active', true)
            ->orderBy('name')
            ->get();
            
        $tags = ServiceTag::orderBy('name')->get();
        
        // Hedef kitleleri ekle
        $hedefKitleler = \App\Models\HedefKitle::where('is_active', true)
            ->orderBy('order')
            ->get();
            
        return view('admin.services.create', compact('categories', 'tags', 'hedefKitleler'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreServiceRequest $request)
    {
        // Gelen verilerin tümünü alıyoruz
        $data = $request->validated();
        
        // Belirli alanlarda düzeltmeler yapıp veri yapısını oluşturuyoruz
        $serviceData = $this->prepareServiceData($data);
        
        try {
            DB::beginTransaction();
            
            // Hizmeti oluştur
            $service = Service::create($serviceData);
            
            // Kategorileri ekle
            if ($request->has('category_ids')) {
                $service->categories()->sync($request->category_ids);
            }
            
            // Hizmetin etiketlerini güncelle
            if ($request->has('tags')) {
                $this->serviceService->syncServiceTags($service, $request->tags);
            } else {
                // Etiket yoksa boş string ile sync işlemi yapılsın
                $this->serviceService->syncServiceTags($service, '');
            }
            
            // Hedef kitleleri ekle
            if ($request->has('hedef_kitleler')) {
                $service->hedefKitleler()->sync($request->hedef_kitleler);
            }
            
            DB::commit();
            
            // Başarılı mesajı göster
            return redirect()->route('admin.services.edit', $service)
                ->with('success', 'Hizmet başarıyla oluşturuldu.');
        } catch (\Exception $e) {
            DB::rollBack();
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
        
        // Features değerleri içerisindeki standard_forms dizisini string yap
        if (isset($service->features['standard_forms']) && is_array($service->features['standard_forms'])) {
            $features = $service->features;
            $features['standard_forms'] = '';
            
            // Özelliği doğrudan atama yapmadan güncelle
            $service->update(['features' => $features]);
            
            // Güncel veriyi yeniden alalım
            $service->refresh();
        }
        
        $headlineCount = Service::where('is_headline', true)->count();
        $maxHeadlinesReached = $headlineCount >= 4 && !$service->is_headline;
        $categories = ServiceCategory::where('is_active', true)->orderBy('name')->get();
        $selectedCategories = $service->categories->pluck('id')->toArray();
        $tags = ServiceTag::orderBy('name')->get();
        
        // Hedef kitleleri ekle
        $hedefKitleler = \App\Models\HedefKitle::where('is_active', true)
            ->orderBy('order')
            ->get();
        
        return view('admin.services.edit', compact('service', 'maxHeadlinesReached', 'categories', 'selectedCategories', 'tags', 'hedefKitleler'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateServiceRequest $request, Service $service)
    {
        // Gelen verilerin tümünü alıyoruz
        $data = $request->validated();
        
        // Belirli alanlarda düzeltmeler yapıp veri yapısını oluşturuyoruz
        $serviceData = $this->prepareServiceData($data, $service);
        
        try {
            DB::beginTransaction();
            
            // Hizmeti güncelle
            $service->update($serviceData);
            
            // Kategorileri güncelle
            if ($request->has('category_ids')) {
                $service->categories()->sync($request->category_ids);
            } else {
                $service->categories()->detach();
            }
            
            // Hizmetin etiketlerini güncelle
            if ($request->has('tags')) {
                $this->serviceService->syncServiceTags($service, $request->tags);
            } else {
                // Etiket yoksa boş string ile sync işlemi yapılsın
                $this->serviceService->syncServiceTags($service, '');
            }
            
            // Hedef kitleleri güncelle
            if ($request->has('hedef_kitleler')) {
                $service->hedefKitleler()->sync($request->hedef_kitleler);
            } else {
                $service->hedefKitleler()->detach();
            }
            
            DB::commit();
            
            // Başarılı mesajı göster
            return redirect()->route('admin.services.edit', $service)
                ->with('success', 'Hizmet başarıyla güncellendi.');
        } catch (\Exception $e) {
            DB::rollBack();
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
        
        // Başındaki /storage/ yolunu temizle
        if (strpos($url, '/storage/') === 0) {
            $url = substr($url, 9); // '/storage/' uzunluğu 9 karakter
        }
        
        // Yinelenen /storage/ yolunu düzelt
        if (strpos($url, '/storage//storage/') !== false) {
            $url = str_replace('/storage//storage/', '', $url);
        }
        
        if (strpos($url, '/storage/storage/') !== false) {
            $url = str_replace('/storage/storage/', '', $url);
        }
        
        // Eğer URL http:// veya https:// ile başlıyorsa
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            try {
                $parsed = parse_url($url);
                $path = $parsed['path'] ?? '';
                
                // Path içindeki storage yollarını temizle
                if (strpos($path, '/storage/') === 0) {
                    $path = substr($path, 9);
                }
                
                if (strpos($path, '/storage//storage/') !== false) {
                    $path = str_replace('/storage//storage/', '', $path);
                }
                
                if (strpos($path, '/storage/storage/') !== false) {
                    $path = str_replace('/storage/storage/', '', $path);
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

    private function prepareServiceData($data, $service = null)
    {
        // Boş array değerlerini temizle
        $features = array_filter($data['features'] ?? []);
        
        // URL'deki yinelenen /storage/ yolunu düzelt
        if (isset($data['image']) && !empty($data['image'])) {
            $data['image'] = $this->fixStoragePath($data['image']);
        } else {
            // Image yoksa data'dan kaldır
            unset($data['image']);
        }
        
        // Galeri URL'lerini düzelt
        if (isset($data['gallery']) && is_array($data['gallery'])) {
            foreach ($data['gallery'] as $key => $url) {
                $data['gallery'][$key] = $this->fixStoragePath($url);
            }
        }
        
        // Tarih ayarlamaları
        if ($data['published_at'] instanceof Carbon) {
            $data['published_at'] = $data['published_at']->toDateTimeString();
        }
        
        if ($data['end_date'] instanceof Carbon) {
            $data['end_date'] = $data['end_date']->toDateTimeString();
        }
        
        // Eğer slug boş geldiyse title'dan oluştur
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }
        
        // Slug zaten kullanılmışsa oluşturulan slug sonuna rastgele sayı ekle
        $baseSlug = $data['slug'];
        $counter = 1;
        
        while ($service && $service::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $baseSlug . '-' . $counter;
            $counter++;
        }
        
        // Özellikler dizisini ekleyelim
        $data['features'] = $features;
        
        // Detay sayfası içeriğini ekle
        $details = $data['details'] ?? [];
        if (!empty($details)) {
            foreach ($details as $key => $value) {
                $data['features'][$key] = $value;
            }
        }
        
        // Yayınlanma durumu ile ilgili özel ayarlar
        $data['is_scheduled'] = !empty($data['end_date']);
        
        // Varsayılan değerleri ata
        $data['view_count'] = $service ? $service->view_count : 0;
        $data['status'] = $data['status'] ?? 'published'; // Varsayılan olarak yayında
        
        return $data;
    }
}
