<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\QuickMenuCategory;
use App\Models\QuickMenuItem;
use App\Services\MediaService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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
        $sliderCount = Slider::count();
        $activeSliderCount = Slider::where('is_active', true)->count();
        $menuCategoryCount = QuickMenuCategory::count();
        
        return view('admin.homepage.index', compact('sliderCount', 'activeSliderCount', 'menuCategoryCount'));
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
     * Yeni slider oluşturma formu
     */
    public function createSlider()
    {
        return view('admin.homepage.sliders.create');
    }
    
    /**
     * Yeni slider kaydetme
     */
    public function storeSlider(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'image' => 'required|string',
            'subtitle' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:50',
            'button_url' => 'nullable|string|max:255',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);
        
        $data = $request->all();
        $data['is_active'] = $request->has('is_active');
        
        // Eğer /storage/ ile başlıyorsa, değiştir
        if (str_starts_with($data['image'], '/storage/')) {
            $data['image'] = str_replace('/storage/', '/uploads/', $data['image']);
        }
        
        // Eğer gelen URL bir base64 ise
        if (strpos($data['image'], 'data:image') === 0) {
            // Base64'ten dosyaya çevir ve yükle
            $media = $this->mediaService->uploadBase64($data['image'], 'slider');
            $data['image'] = $media->file_path;
        } 
        // Eğer storage'dan gelen bir dosya ise
        else if (strpos($data['image'], '/storage/') !== false) {
            // Dosyayı storage'dan public/uploads'a taşı
            $sourcePath = storage_path('app/public/' . str_replace('/storage/', '', $data['image']));
            if (file_exists($sourcePath)) {
                $file = new \Illuminate\Http\UploadedFile(
                    $sourcePath,
                    basename($sourcePath),
                    mime_content_type($sourcePath),
                    null,
                    true
                );
                $media = $this->mediaService->upload($file, 'slider');
                $data['image'] = $media->file_path;
            }
        }
        
        Slider::create($data);
        
        return redirect()->route('admin.homepage.sliders')
            ->with('success', 'Slider başarıyla oluşturuldu.');
    }
    
    /**
     * Slider düzenleme formu
     */
    public function editSlider($id)
    {
        $slider = Slider::findOrFail($id);
        return view('admin.homepage.sliders.edit', compact('slider'));
    }
    
    /**
     * Slider güncelleme
     */
    public function updateSlider(Request $request, $id)
    {
        $slider = Slider::findOrFail($id);
        
        $request->validate([
            'title' => 'nullable|string|max:255',
            'image' => 'required|string',
            'subtitle' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:50',
            'button_url' => 'nullable|string|max:255',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);
        
        $data = $request->all();
        $data['is_active'] = $request->has('is_active');
        
        // Eğer /storage/ ile başlıyorsa, değiştir
        if (str_starts_with($data['image'], '/storage/')) {
            $data['image'] = str_replace('/storage/', '/uploads/', $data['image']);
        }
        
        // Eğer yeni bir resim yüklendiyse
        if ($data['image'] !== $slider->image) {
            // Eğer gelen URL bir base64 ise
            if (strpos($data['image'], 'data:image') === 0) {
                // Eski dosyayı sil
                if ($slider->image) {
                    $oldPath = public_path($slider->image);
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }
                
                // Base64'ten dosyaya çevir ve yükle
                $media = $this->mediaService->uploadBase64($data['image'], 'slider');
                $data['image'] = $media->file_path;
            }
            // Eğer storage'dan gelen bir dosya ise
            else if (strpos($data['image'], '/storage/') !== false) {
                // Eski dosyayı sil
                if ($slider->image) {
                    $oldPath = public_path($slider->image);
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }
                
                // Dosyayı storage'dan public/uploads'a taşı
                $sourcePath = storage_path('app/public/' . str_replace('/storage/', '', $data['image']));
                if (file_exists($sourcePath)) {
                    $file = new \Illuminate\Http\UploadedFile(
                        $sourcePath,
                        basename($sourcePath),
                        mime_content_type($sourcePath),
                        null,
                        true
                    );
                    $media = $this->mediaService->upload($file, 'slider');
                    $data['image'] = $media->file_path;
                }
            }
        }
        
        $slider->update($data);
        
        return redirect()->route('admin.homepage.sliders')
            ->with('success', 'Slider başarıyla güncellendi.');
    }
    
    /**
     * Slider silme
     */
    public function deleteSlider($id)
    {
        $slider = Slider::findOrFail($id);
        
        // Dosyayı sil
        if ($slider->image) {
            $path = public_path($slider->image);
            if (file_exists($path)) {
                unlink($path);
            }
        }
        
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
        $items = $category->items;
        
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
}
