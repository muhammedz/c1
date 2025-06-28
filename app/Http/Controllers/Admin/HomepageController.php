<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\QuickMenuCategory;
use App\Models\QuickMenuItem;
use App\Models\HeaderSetting;
use App\Services\MediaService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\MediaRelation;

class HomepageController extends Controller
{
    protected $mediaService;

    public function __construct(MediaService $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    /**
     * Anasayfa yönetimi dashboard
     */
    public function index()
    {
        return view('admin.homepage.index');
    }
    
    /**
     * Slider Yönetimi
     */
    
    /**
     * Tüm sliderlari listele
     */
    public function sliders()
    {
        $sliders = Slider::orderBy('order')->get();
        return view('admin.homepage.sliders.index', compact('sliders'));
    }
    
    /**
     * Slider oluşturma formunu göster
     */
    public function createSlider()
    {
        return view('admin.homepage.sliders.create');
    }
    
    /**
     * Yeni slider kaydet
     */
    public function storeSlider(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:50',
            'button_url' => 'nullable|string|max:255',
            'direct_link' => 'nullable|string|max:255',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'filemanagersystem_image' => 'nullable|string',
            'filemanagersystem_image_alt' => 'nullable|string|max:255',
            'filemanagersystem_image_title' => 'nullable|string|max:255'
        ]);

        // Order değeri boş ise en sondaki değeri al ve bir ekle
        if (empty($request->order)) {
            $lastOrder = Slider::max('order') ?? 0;
            $request->merge(['order' => $lastOrder + 1]);
        }

        // Görsel URL'si dosya yolu veya tam URL olabilir
        $filemanagersystemImage = $request->filemanagersystem_image;

        $slider = Slider::create($request->all());

        try {
            // FileManagerSystem ile ilişki kur
            if ($request->filled('filemanagersystem_image')) {
                // URL'den ID'yi çıkart (eğer varsa)
                $mediaId = null;
                
                // 1. /uploads/media/123 formatı
                if (preg_match('#^/uploads/media/(\d+)$#', $filemanagersystemImage, $matches)) {
                    $mediaId = $matches[1];
                }
                // 2. /admin/filemanagersystem/media/preview/123 formatı
                elseif (preg_match('#/media/preview/(\d+)#', $filemanagersystemImage, $matches)) {
                    $mediaId = $matches[1];
                }
                
                if ($mediaId) {
                    // Medya ID'si ile ilişkilendirme
                    \Log::debug('Slider oluşturma: Medya ID ile ilişkilendirme', [
                        'slider_id' => $slider->id,
                        'media_id' => $mediaId
                    ]);
                    
                    $this->createMediaRelationById($slider, $mediaId);
                } else {
                    // URL veya dosya yolu ile medyayı bul ve ilişkilendir
                    $media = \App\Models\FileManagerSystem\Media::where('url', $filemanagersystemImage)
                        ->orWhere('path', $filemanagersystemImage)
                        ->first();
                        
                    if ($media) {
                        \Log::debug('Slider oluşturma: URL ile ilişkilendirme', [
                            'slider_id' => $slider->id,
                            'media_id' => $media->id,
                            'url' => $filemanagersystemImage
                        ]);
                        
                        $this->createMediaRelationById($slider, $media->id);
                    } else {
                        \Log::error('Slider oluşturma: Medya bulunamadı', [
                            'slider_id' => $slider->id,
                            'url' => $filemanagersystemImage
                        ]);
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error('Slider görsel ilişkilendirme hatası: ' . $e->getMessage(), [
                'slider_id' => $slider->id,
                'filemanagersystem_image' => $filemanagersystemImage,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }

        return redirect()->route('admin.homepage.sliders')
            ->with('success', 'Slider başarıyla oluşturuldu.');
    }
    
    /**
     * Slider düzenleme formunu göster
     */
    public function editSlider($id)
    {
        $slider = Slider::findOrFail($id);
        return view('admin.homepage.sliders.edit', compact('slider'));
    }
    
    /**
     * Slider güncelle
     */
    public function updateSlider(Request $request, $id)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:50',
            'button_url' => 'nullable|string|max:255',
            'direct_link' => 'nullable|string|max:255',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'filemanagersystem_image' => 'nullable|string',
            'filemanagersystem_image_alt' => 'nullable|string|max:255',
            'filemanagersystem_image_title' => 'nullable|string|max:255'
        ]);

        $slider = Slider::findOrFail($id);
        
        // Order değeri boş ise mevcut değeri koru
        if (empty($request->order)) {
            $request->merge(['order' => $slider->order]);
        }
        
        // Görsel URL'si dosya yolu veya tam URL olabilir
        $filemanagersystemImage = $request->filemanagersystem_image;
        
        $slider->update($request->all());

        try {
            // FileManagerSystem ilişkisini güncelle
            if ($request->filled('filemanagersystem_image')) {
                // URL'den ID'yi çıkart (eğer varsa)
                $mediaId = null;
                
                // 1. /uploads/media/123 formatı
                if (preg_match('#^/uploads/media/(\d+)$#', $filemanagersystemImage, $matches)) {
                    $mediaId = $matches[1];
                }
                // 2. /admin/filemanagersystem/media/preview/123 formatı
                elseif (preg_match('#/media/preview/(\d+)#', $filemanagersystemImage, $matches)) {
                    $mediaId = $matches[1];
                }
                
                if ($mediaId) {
                    // Medya ID'si ile ilişkilendirme
                    \Log::debug('Slider güncelleme: Medya ID ile ilişkilendirme', [
                        'slider_id' => $slider->id,
                        'media_id' => $mediaId
                    ]);
                    
                    $this->updateMediaRelationById($slider, $mediaId);
                } else {
                    // URL veya dosya yolu ile medyayı bul ve ilişkilendir
                    $media = \App\Models\FileManagerSystem\Media::where('url', $filemanagersystemImage)
                        ->orWhere('path', $filemanagersystemImage)
                        ->first();
                        
                    if ($media) {
                        \Log::debug('Slider güncelleme: URL ile ilişkilendirme', [
                            'slider_id' => $slider->id,
                            'media_id' => $media->id,
                            'url' => $filemanagersystemImage
                        ]);
                        
                        $this->updateMediaRelationById($slider, $media->id);
                    } else {
                        \Log::error('Slider güncelleme: Medya bulunamadı', [
                            'slider_id' => $slider->id,
                            'url' => $filemanagersystemImage
                        ]);
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error('Slider görsel ilişkilendirme güncelleme hatası: ' . $e->getMessage(), [
                'slider_id' => $slider->id,
                'filemanagersystem_image' => $filemanagersystemImage,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }

        return redirect()->route('admin.homepage.sliders')
            ->with('success', 'Slider başarıyla güncellendi.');
    }
    
    /**
     * Slider silme
     */
    public function deleteSlider($id)
    {
        $slider = Slider::findOrFail($id);
        
        $slider->delete();
        
        return redirect()->route('admin.homepage.sliders')
            ->with('success', 'Slider başarıyla silindi.');
    }
    
    /**
     * Slider aktif/pasif durumunu değiştir
     */
    public function toggleSlider($id)
    {
        $slider = Slider::findOrFail($id);
        $slider->is_active = !$slider->is_active;
        $slider->save();
        
        return response()->json([
            'success' => true,
            'is_active' => $slider->is_active
        ]);
    }
    
    /**
     * Slider sıralamasını güncelle
     */
    public function updateSliderOrder(Request $request)
    {
        $items = $request->get('items');
        
        foreach ($items as $item) {
            Slider::where('id', $item['id'])->update(['order' => $item['order']]);
        }
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Quick Menu Yönetimi
     */
    
    /**
     * Tüm quick menu kategorilerini listele
     */
    public function quickMenus()
    {
        $categories = QuickMenuCategory::orderBy('order')->get();
        return view('admin.homepage.quick-menus.index', compact('categories'));
    }
    
    /**
     * Yeni quick menu kategorisi oluşturma formu
     */
    public function createQuickMenu()
    {
        return view('admin.homepage.quick-menus.create');
    }
    
    /**
     * Yeni quick menu kategorisi kaydetme
     */
    public function storeQuickMenu(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'icon' => 'required|string|max:50',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);
        
        $data = $request->all();
        $data['is_active'] = $request->has('is_active');
        
        QuickMenuCategory::create($data);
        
        return redirect()->route('admin.homepage.quick-menus.index')
            ->with('success', 'Menü kategorisi başarıyla oluşturuldu.');
    }
    
    /**
     * Quick menu kategorisi düzenleme formu
     */
    public function editQuickMenu($id)
    {
        $category = QuickMenuCategory::findOrFail($id);
        return view('admin.homepage.quick-menus.edit', compact('category'));
    }
    
    /**
     * Quick menu kategorisi güncelleme
     */
    public function updateQuickMenu(Request $request, $id)
    {
        $category = QuickMenuCategory::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'icon' => 'required|string|max:50',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);
        
        $data = $request->all();
        $data['is_active'] = $request->has('is_active');
        
        $category->update($data);
        
        return redirect()->route('admin.homepage.quick-menus.index')
            ->with('success', 'Menü kategorisi başarıyla güncellendi.');
    }
    
    /**
     * Quick menu kategorisi silme
     */
    public function deleteQuickMenu($id)
    {
        $category = QuickMenuCategory::findOrFail($id);
        
        // Kategoriye bağlı tüm öğeler de cascade olarak silinecek
        
        $category->delete();
        
        return redirect()->route('admin.homepage.quick-menus.index')
            ->with('success', 'Menü kategorisi ve altındaki tüm öğeler başarıyla silindi.');
    }
    
    /**
     * Quick menu kategorisi aktif/pasif durumunu değiştir
     */
    public function toggleQuickMenu($id)
    {
        $category = QuickMenuCategory::findOrFail($id);
        $category->is_active = !$category->is_active;
        $category->save();
        
        return response()->json([
            'success' => true,
            'is_active' => $category->is_active
        ]);
    }
    
    /**
     * Quick menu kategori sıralamasını güncelle
     */
    public function updateQuickMenuOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'orders' => 'required|array',
            'orders.*' => 'required|integer|min:0'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Geçersiz veri formatı.'
            ], 400);
        }
        
        foreach ($request->orders as $id => $order) {
            QuickMenuCategory::where('id', $id)->update(['order' => $order]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Sıralama başarıyla güncellendi.'
        ]);
    }
    
    /**
     * Quick Menu Items Yönetimi
     */
    
    /**
     * Belirli bir kategoriye ait menü öğelerini listele
     */
    public function quickMenuItems($category_id)
    {
        $category = QuickMenuCategory::findOrFail($category_id);
        $items = $category->items()->orderByRaw('LOWER(title) COLLATE utf8mb4_turkish_ci ASC')->get(); // Türkçe alfabetik sıralama
        
        return view('admin.homepage.quick-menus.items.index', compact('category', 'items'));
    }
    
    /**
     * Yeni quick menu öğesi oluşturma formu
     */
    public function createQuickMenuItem($category_id)
    {
        $category = QuickMenuCategory::findOrFail($category_id);
        return view('admin.homepage.quick-menus.items.create', compact('category'));
    }
    
    /**
     * Yeni quick menu öğesi kaydetme
     */
    public function storeQuickMenuItem(Request $request, $category_id)
    {
        $category = QuickMenuCategory::findOrFail($category_id);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'target' => 'required|in:_self,_blank',
            'icon' => 'nullable|string|max:50',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);
        
        $data = $request->all();
        $data['category_id'] = $category_id;
        $data['is_active'] = $request->has('is_active');
        
        QuickMenuItem::create($data);
        
        return redirect()->route('admin.homepage.quick-menus.items', $category_id)
            ->with('success', 'Menü öğesi başarıyla oluşturuldu.');
    }
    
    /**
     * Quick menu öğesi düzenleme formu
     */
    public function editQuickMenuItem($category_id, $id)
    {
        $item = QuickMenuItem::findOrFail($id);
        $category = $item->category;

        // Kategori ID kontrolü
        if ($category->id != $category_id) {
            return redirect()->route('admin.homepage.quick-menus.items', $category_id)
                ->with('error', 'Bu menü öğesi belirtilen kategoriye ait değil.');
        }
        
        return view('admin.homepage.quick-menus.items.edit', compact('item', 'category'));
    }
    
    /**
     * Quick menu öğesi güncelleme
     */
    public function updateQuickMenuItem(Request $request, $category_id, $id)
    {
        $item = QuickMenuItem::findOrFail($id);
        
        // Kategori ID kontrolü
        if ($item->category_id != $category_id) {
            return redirect()->route('admin.homepage.quick-menus.items', $category_id)
                ->with('error', 'Bu menü öğesi belirtilen kategoriye ait değil.');
        }
        
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'target' => 'required|in:_self,_blank',
            'icon' => 'nullable|string|max:50',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);
        
        $data = $request->all();
        $data['is_active'] = $request->has('is_active');
        
        $item->update($data);
        
        return redirect()->route('admin.homepage.quick-menus.items', $category_id)
            ->with('success', 'Menü öğesi başarıyla güncellendi.');
    }
    
    /**
     * Quick menu öğesi silme
     */
    public function deleteQuickMenuItem($category_id, $id)
    {
        $item = QuickMenuItem::findOrFail($id);
        
        // Kategori ID kontrolü
        if ($item->category_id != $category_id) {
            return redirect()->route('admin.homepage.quick-menus.items', $category_id)
                ->with('error', 'Bu menü öğesi belirtilen kategoriye ait değil.');
        }
        
        $item->delete();
        
        return redirect()->route('admin.homepage.quick-menus.items', $category_id)
            ->with('success', 'Menü öğesi başarıyla silindi.');
    }
    
    /**
     * Quick menu öğesi aktif/pasif durumunu değiştir
     */
    public function toggleQuickMenuItem($category_id, $id)
    {
        $item = QuickMenuItem::findOrFail($id);
        
        // Kategori ID kontrolü
        if ($item->category_id != $category_id) {
            return response()->json([
                'success' => false,
                'message' => 'Bu menü öğesi belirtilen kategoriye ait değil.'
            ], 400);
        }
        
        $item->is_active = !$item->is_active;
        $item->save();
        
        return response()->json([
            'success' => true,
            'is_active' => $item->is_active
        ]);
    }
    
    /**
     * Quick menu öğeleri sıralamasını güncelle
     */
    public function updateQuickMenuItemOrder(Request $request, $category_id)
    {
        $validator = Validator::make($request->all(), [
            'items' => 'required|array',
            'items.*.id' => 'required|integer|exists:quick_menu_items,id',
            'items.*.order' => 'required|integer|min:0'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Geçersiz veri formatı.'
            ], 400);
        }
        
        foreach ($request->items as $item) {
            QuickMenuItem::where('id', $item['id'])->update(['order' => $item['order']]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Sıralama başarıyla güncellendi.'
        ]);
    }

    /**
     * URL'deki yinelenen /storage/ yolunu düzeltir
     */
    private function fixStoragePath($url)
    {
        if (empty($url)) {
            return $url;
        }
        
        // URL zaten rölatif ise ve doğru formatta ise değiştirme
        if (strpos($url, '/') === 0 && strpos($url, '//') !== 0) {
            // Yinelenen /storage/ yolunu düzelt
            if (strpos($url, '/storage//storage/') !== false) {
                return str_replace('/storage//storage/', '/storage/', $url);
            }
            if (strpos($url, '/storage/storage/') !== false) {
                return str_replace('/storage/storage/', '/storage/', $url);
            }
            return $url;
        }
        
        // URL tam ise (http veya https ile başlıyorsa), rölatif yap
        try {
            $urlObj = parse_url($url);
            $path = $urlObj['path'] ?? '';
            
            // Yinelenen /storage/ yolunu düzelt
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

    /**
     * FileManagerSystem ile medya ilişkisi oluştur
     */
    private function createMediaRelation(Slider $slider, string $filePath)
    {
        // MediaRelation sınıfı
        $mediaRelationClass = \App\Models\FileManagerSystem\MediaRelation::class;

        $mediaRelation = new $mediaRelationClass();
        $mediaRelation->related_type = 'homepage_slider';
        $mediaRelation->related_id = $slider->id;
        $mediaRelation->field_name = 'slider_image';
        $mediaRelation->order = 0;
        
        // Dosya yolundan media bilgisini bulmaya çalış
        $media = \App\Models\FileManagerSystem\Media::where('url', $filePath)
            ->orWhere('path', $filePath)
            ->first();
            
        if ($media) {
            $mediaRelation->media_id = $media->id;
            $mediaRelation->save();
            
            \Log::debug('Medya ilişkisi dosya yolu ile oluşturuldu', [
                'media_id' => $media->id,
                'file_path' => $filePath,
                'related_type' => 'homepage_slider',
                'related_id' => $slider->id
            ]);
        } else {
            \Log::error('Medya bulunamadı, ilişki oluşturulamadı', [
                'file_path' => $filePath,
                'slider_id' => $slider->id
            ]);
        }
    }

    /**
     * FileManagerSystem medya ilişkisini güncelle
     */
    private function updateMediaRelation(Slider $slider, string $filePath)
    {
        // MediaRelation sınıfı
        $mediaRelationClass = \App\Models\FileManagerSystem\MediaRelation::class;

        // Eski ilişkileri sil
        $mediaRelationClass::where('related_type', 'homepage_slider')
            ->where('related_id', $slider->id)
            ->delete();
        
        // Dosya yolundan media bilgisini bulmaya çalış
        $media = \App\Models\FileManagerSystem\Media::where('url', $filePath)
            ->orWhere('path', $filePath)
            ->first();
            
        if ($media) {
            $mediaRelation = new $mediaRelationClass();
            $mediaRelation->media_id = $media->id;
            $mediaRelation->related_type = 'homepage_slider';
            $mediaRelation->related_id = $slider->id;
            $mediaRelation->field_name = 'slider_image';
            $mediaRelation->order = 0;
            $mediaRelation->save();
            
            \Log::debug('Medya ilişkisi dosya yolu ile güncellendi', [
                'media_id' => $media->id,
                'file_path' => $filePath,
                'related_type' => 'homepage_slider',
                'related_id' => $slider->id
            ]);
        } else {
            \Log::error('Medya bulunamadı, ilişki güncellenemedi', [
                'file_path' => $filePath,
                'slider_id' => $slider->id
            ]);
        }
    }

    /**
     * Yeni medya ilişkisini ID üzerinden oluşturur
     */
    private function createMediaRelationById(Slider $slider, int $mediaId)
    {
        try {
            // MediaRelation sınıfı
            $mediaRelationClass = \App\Models\FileManagerSystem\MediaRelation::class;
            
            // Mevcut ilişkiyi kontrol et
            $existingRelation = $mediaRelationClass::where('media_id', $mediaId)
                ->where('related_type', 'homepage_slider')
                ->where('related_id', $slider->id)
                ->first();
            
            // İlişki yoksa oluştur
            if (!$existingRelation) {
                $mediaRelation = new $mediaRelationClass();
                $mediaRelation->media_id = $mediaId;
                $mediaRelation->related_type = 'homepage_slider';
                $mediaRelation->related_id = $slider->id;
                $mediaRelation->save();
                
                \Log::debug('Medya ilişkisi ID ile oluşturuldu', [
                    'media_id' => $mediaId,
                    'related_type' => 'homepage_slider',
                    'related_id' => $slider->id
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Medya ilişkisi oluşturma hatası (ID ile): ' . $e->getMessage(), [
                'media_id' => $mediaId,
                'slider_id' => $slider->id,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Medya ilişkisini ID üzerinden günceller
     */
    private function updateMediaRelationById(Slider $slider, int $mediaId)
    {
        try {
            // MediaRelation sınıfı
            $mediaRelationClass = \App\Models\FileManagerSystem\MediaRelation::class;
            
            // Önce eski tüm ilişkilerini sil
            $mediaRelationClass::where('related_type', 'homepage_slider')
                ->where('related_id', $slider->id)
                ->delete();
            
            // Yeni ilişki oluştur
            $mediaRelation = new $mediaRelationClass();
            $mediaRelation->media_id = $mediaId;
            $mediaRelation->related_type = 'homepage_slider';
            $mediaRelation->related_id = $slider->id;
            $mediaRelation->save();
            
            \Log::debug('Medya ilişkisi ID ile güncellendi', [
                'media_id' => $mediaId,
                'related_type' => 'homepage_slider',
                'related_id' => $slider->id
            ]);
        } catch (\Exception $e) {
            \Log::error('Medya ilişkisi güncelleme hatası (ID ile): ' . $e->getMessage(), [
                'media_id' => $mediaId,
                'slider_id' => $slider->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Header Yönetimi
     */
    
    /**
     * Header ayarları sayfası
     */
    public function header()
    {
        $headerSettings = HeaderSetting::getSettings();
        $mainMenuCount = 0; // Bu değer menü sistemi ile entegre edilecek
        $activeMenuCount = 0; // Bu değer menü sistemi ile entegre edilecek
        
        return view('admin.homepage.header.index', compact('headerSettings', 'mainMenuCount', 'activeMenuCount'));
    }
    
    /**
     * Header ayarlarını güncelle
     */
    public function updateHeaderSettings(Request $request)
    {
        $request->validate([
            'logo_path' => 'nullable|string|max:255',
            'secondary_logo_path' => 'nullable|string|max:255',
            'slogan_path' => 'nullable|string|max:255',
            'show_search_button' => 'nullable|boolean',
            'header_bg_color' => 'nullable|string|max:20',
            'header_text_color' => 'nullable|string|max:20',
            'header_height' => 'nullable|integer|min:50|max:200',
            'sticky_header' => 'nullable|boolean',
            'custom_css' => 'nullable|string',
            'additional_scripts' => 'nullable|string',
            'custom_header_html' => 'nullable|string',
            'mobile_logo_path' => 'nullable|string|max:255',
        ]);

        $headerSettings = HeaderSetting::getSettings();
        
        // Boolean değerleri düzelt
        $data = $request->all();
        $data['show_search_button'] = $request->has('show_search_button') ? 1 : 0;
        $data['sticky_header'] = $request->has('sticky_header') ? 1 : 0;
        
        // URL'leri storage path'e çevir
        $urlFields = ['logo_path', 'secondary_logo_path', 'slogan_path', 'mobile_logo_path'];
        foreach ($urlFields as $field) {
            if (!empty($data[$field])) {
                // URL'den storage kısmını çıkararak sadece dosya yolunu al
                if (strpos($data[$field], '/storage/') !== false) {
                    $data[$field] = str_replace('/storage/', '', $data[$field]);
                }
                // Eğer tam URL geliyorsa, sadece storage sonrası kısmı al
                if (strpos($data[$field], 'storage/') === 0) {
                    $data[$field] = substr($data[$field], 8); // "storage/" kısmını çıkar
                }
            }
        }
        
        $headerSettings->update($data);

        return redirect()->route('admin.homepage.header')
            ->with('success', 'Header ayarları başarıyla güncellendi.');
    }
}
