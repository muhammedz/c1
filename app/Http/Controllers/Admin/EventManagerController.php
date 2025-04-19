<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\EventImage;
use App\Models\EventSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;
use App\Services\EventScraperService;

class EventManagerController extends Controller
{
    /**
     * Etkinliklerin listelendiği ana sayfa
     */
    public function index()
    {
        $events = Event::with('category')->orderBy('start_date', 'desc')->get();
        $categories = EventCategory::orderBy('order', 'asc')->get();
        $settings = EventSettings::first() ?? new EventSettings(['is_active' => true]);
        
        return view('admin.events.index', compact('events', 'categories', 'settings'));
    }
    
    /**
     * Yeni etkinlik ekleme formu
     */
    public function create()
    {
        $categories = EventCategory::orderBy('name', 'asc')->get();
        return view('admin.events.create', compact('categories'));
    }
    
    /**
     * Yeni etkinlik kaydet
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:events',
            'description' => 'required|string',
            'category_id' => 'nullable|exists:event_categories,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'location' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'organizer' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
            'show_on_homepage' => 'boolean',
            'is_featured' => 'boolean',
            'register_required' => 'boolean',
            'register_url' => 'nullable|url|max:255',
            'external_id' => 'nullable|string|max:255',
            'external_url' => 'nullable|url|max:255',
        ]);
        
        // Kapak resmi işleme
        if ($request->hasFile('cover_image')) {
            $coverImage = $request->file('cover_image');
            $originalFilename = time() . '_' . Str::random(10);
            $extension = $coverImage->getClientOriginalExtension();
            $uploadPath = 'uploads/events';
            
            // Benzersiz dosya adı oluştur
            $filename = $this->createUniqueFilename($uploadPath, $originalFilename, $extension);
            
            // Resimi boyutlandır ve kaydet - v3 uyumlu
            $img = Image::read($coverImage);
            $img->resize(1200, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            
            // encode ve kaydet
            $encodedImage = $this->encodeByFormat($img, $extension);
            file_put_contents(public_path($uploadPath . '/' . $filename), $encodedImage);
            $validated['cover_image'] = $uploadPath . '/' . $filename;
        }
        
        // Etkinlik oluştur
        $event = Event::create($validated);
        
        // Galeri resimleri işle
        if ($request->has('gallery_images')) {
            foreach ($request->gallery_images as $index => $image) {
                $originalFilename = time() . '_' . $index . '_' . Str::random(5);
                $extension = $image->getClientOriginalExtension();
                $uploadPath = 'uploads/events/gallery';
                
                // Benzersiz dosya adı oluştur
                $filename = $this->createUniqueFilename($uploadPath, $originalFilename, $extension);
                
                // Resim boyutlandır ve kaydet - v3 uyumlu
                $img = Image::read($image);
                $img->resize(1200, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                
                // encode ve kaydet
                $encodedImage = $this->encodeByFormat($img, $extension);
                file_put_contents(public_path($uploadPath . '/' . $filename), $encodedImage);
                
                // Galeri resmi oluştur
                EventImage::create([
                    'event_id' => $event->id,
                    'image_path' => $uploadPath . '/' . $filename,
                    'order' => $index
                ]);
            }
        }
        
        return redirect()->route('admin.events.index')
            ->with('success', 'Etkinlik başarıyla oluşturuldu.');
    }
    
    /**
     * Etkinlik düzenleme formu
     */
    public function edit($id)
    {
        $event = Event::with('images')->findOrFail($id);
        $categories = EventCategory::orderBy('order')->get();
        return view('admin.events.edit', compact('event', 'categories'));
    }
    
    /**
     * Etkinlik güncelle
     */
    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:events,slug,' . $id,
            'description' => 'required|string',
            'category_id' => 'nullable|exists:event_categories,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'location' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'organizer' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
            'show_on_homepage' => 'boolean',
            'is_featured' => 'boolean',
            'register_required' => 'boolean',
            'register_url' => 'nullable|url|max:255',
            'external_id' => 'nullable|string|max:255',
            'external_url' => 'nullable|url|max:255',
        ]);
        
        // Kapak resmi işleme
        if ($request->hasFile('cover_image')) {
            // Eski resmi sil
            if ($event->cover_image) {
                if (file_exists(public_path($event->cover_image))) {
                    unlink(public_path($event->cover_image));
                }
            }
            
            $coverImage = $request->file('cover_image');
            $originalFilename = time() . '_' . Str::random(10);
            $extension = $coverImage->getClientOriginalExtension();
            $uploadPath = 'uploads/events';
            
            // Benzersiz dosya adı oluştur
            $filename = $this->createUniqueFilename($uploadPath, $originalFilename, $extension);
            
            // Resimi boyutlandır ve kaydet - v3 uyumlu
            $img = Image::read($coverImage);
            $img->resize(1200, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            
            // encode ve kaydet
            $encodedImage = $this->encodeByFormat($img, $extension);
            file_put_contents(public_path($uploadPath . '/' . $filename), $encodedImage);
            $validated['cover_image'] = $uploadPath . '/' . $filename;
        }
        
        // Etkinliği güncelle
        $event->update($validated);
        
        // Galeri resimleri işle
        if ($request->has('gallery_images')) {
            foreach ($request->gallery_images as $index => $image) {
                $originalFilename = time() . '_' . $index . '_' . Str::random(5);
                $extension = $image->getClientOriginalExtension();
                $uploadPath = 'uploads/events/gallery';
                
                // Benzersiz dosya adı oluştur
                $filename = $this->createUniqueFilename($uploadPath, $originalFilename, $extension);
                
                // Resim boyutlandır ve kaydet - v3 uyumlu
                $img = Image::read($image);
                $img->resize(1200, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                
                // encode ve kaydet
                $encodedImage = $this->encodeByFormat($img, $extension);
                file_put_contents(public_path($uploadPath . '/' . $filename), $encodedImage);
                
                // Galeri resmi oluştur
                EventImage::create([
                    'event_id' => $event->id,
                    'image_path' => $uploadPath . '/' . $filename,
                    'order' => $event->images()->count() + $index
                ]);
            }
        }
        
        return redirect()->route('admin.events.index')
            ->with('success', 'Etkinlik başarıyla güncellendi.');
    }
    
    /**
     * Etkinlik silme
     */
    public function delete($id)
    {
        try {
            $event = Event::findOrFail($id);
            $event->delete();
            
            return redirect()->route('admin.events.index')
                ->with('success', 'Etkinlik başarıyla silindi.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Etkinlik silinirken bir hata oluştu: ' . $e->getMessage());
        }
    }
    
    /**
     * Görünürlük değiştirme
     */
    public function toggleVisibility($id)
    {
        try {
            $event = Event::findOrFail($id);
            $event->is_active = !$event->is_active;
            $event->save();
            
            return redirect()->back()->with('success', 'Etkinlik görünürlüğü güncellendi.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Bir hata oluştu: ' . $e->getMessage());
        }
    }
    
    /**
     * Ana sayfada gösterme değiştirme
     */
    public function toggleHomepage($id)
    {
        try {
            $event = Event::findOrFail($id);
            $event->show_on_homepage = !$event->show_on_homepage;
            $event->save();
            
            return redirect()->back()->with('success', 'Etkinliğin ana sayfada gösterilme durumu güncellendi.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Bir hata oluştu: ' . $e->getMessage());
        }
    }
    
    /**
     * Öne çıkarma değiştirme
     */
    public function toggleFeatured($id)
    {
        try {
            $event = Event::findOrFail($id);
            $event->is_featured = !$event->is_featured;
            $event->save();
            
            return redirect()->back()->with('success', 'Etkinliğin öne çıkarılma durumu güncellendi.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Bir hata oluştu: ' . $e->getMessage());
        }
    }
    
    /**
     * Sıralama güncelle
     */
    public function updateOrder(Request $request)
    {
        try {
            $orderData = $request->input('order');
            if (!is_array($orderData)) {
                return response()->json(['error' => 'Geçersiz veri formatı'], 400);
            }
            
            foreach ($orderData as $item) {
                $event = Event::find($item['id']);
                if ($event) {
                    $event->order = $item['order'];
                    $event->save();
                }
            }
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Kategoriler sayfası
     */
    public function categories()
    {
        $categories = EventCategory::orderBy('order', 'asc')->get();
        return view('admin.events.categories', compact('categories'));
    }
    
    /**
     * Kategori ekleme
     */
    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:event_categories',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:50',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);
        
        // Slug oluştur (eğer boşsa)
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }
        
        try {
            $category = new EventCategory();
            $category->name = $validated['name'];
            $category->slug = $validated['slug'];
            $category->description = $validated['description'] ?? null;
            $category->color = $validated['color'] ?? '#3490dc';
            $category->order = $validated['order'] ?? 0;
            $category->is_active = isset($validated['is_active']);
            $category->save();
            
            return redirect()->route('admin.events.categories')
                ->with('success', 'Kategori başarıyla oluşturuldu.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Kategori oluşturulurken bir hata oluştu: ' . $e->getMessage());
        }
    }
    
    /**
     * Kategori güncelleme
     */
    public function updateCategory(Request $request, $id)
    {
        $category = EventCategory::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:event_categories,slug,' . $id,
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:50',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);
        
        // Slug oluştur (eğer boşsa)
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }
        
        try {
            $category->name = $validated['name'];
            $category->slug = $validated['slug'];
            $category->description = $validated['description'] ?? null;
            $category->color = $validated['color'] ?? '#3490dc';
            $category->order = $validated['order'] ?? 0;
            $category->is_active = isset($validated['is_active']);
            $category->save();
            
            return redirect()->route('admin.events.categories')
                ->with('success', 'Kategori başarıyla güncellendi.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Kategori güncellenirken bir hata oluştu: ' . $e->getMessage());
        }
    }
    
    /**
     * Kategori silme
     */
    public function deleteCategory($id)
    {
        try {
            $category = EventCategory::findOrFail($id);
            
            // Kategoriye bağlı etkinliklerin kategori_id'sini null yap
            Event::where('category_id', $id)->update(['category_id' => null]);
            
            $category->delete();
            
            return redirect()->route('admin.events.categories')
                ->with('success', 'Kategori başarıyla silindi.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Kategori silinirken bir hata oluştu: ' . $e->getMessage());
        }
    }
    
    /**
     * Kategori görünürlüğünü değiştirme
     */
    public function toggleCategoryVisibility($id)
    {
        try {
            $category = EventCategory::findOrFail($id);
            $category->is_active = !$category->is_active;
            $category->save();
            
            return redirect()->back()->with('success', 'Kategori görünürlüğü güncellendi.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Bir hata oluştu: ' . $e->getMessage());
        }
    }
    
    /**
     * Kategori sıralamasını güncelleme
     */
    public function updateCategoryOrder(Request $request)
    {
        try {
            $orderData = $request->input('order');
            if (!is_array($orderData)) {
                return response()->json(['error' => 'Geçersiz veri formatı'], 400);
            }
            
            foreach ($orderData as $item) {
                $category = EventCategory::find($item['id']);
                if ($category) {
                    $category->order = $item['order'];
                    $category->save();
                }
            }
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Ayarlar sayfası
     */
    public function settings()
    {
        $settings = EventSettings::first() ?? new EventSettings(['is_active' => true]);
        return view('admin.events.settings', compact('settings'));
    }
    
    /**
     * Ayarları güncelleme
     */
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'section_title' => 'nullable|string|max:255',
            'section_subtitle' => 'nullable|string|max:255',
            'homepage_limit' => 'nullable|integer|min:1|max:20',
            'show_past_events' => 'nullable|boolean',
            'show_category_filter' => 'nullable|boolean',
            'show_map' => 'nullable|boolean',
        ]);
        
        try {
            $settings = EventSettings::first();
            if (!$settings) {
                $settings = new EventSettings();
            }
            
            $settings->title = $validated['title'];
            $settings->description = $validated['description'] ?? null;
            $settings->section_title = $validated['section_title'] ?? null;
            $settings->section_subtitle = $validated['section_subtitle'] ?? null;
            $settings->homepage_limit = $validated['homepage_limit'] ?? 6;
            $settings->show_past_events = isset($validated['show_past_events']);
            $settings->show_category_filter = isset($validated['show_category_filter']);
            $settings->show_map = isset($validated['show_map']);
            $settings->save();
            
            return redirect()->route('admin.events.settings')
                ->with('success', 'Ayarlar başarıyla güncellendi.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ayarlar güncellenirken bir hata oluştu: ' . $e->getMessage());
        }
    }
    
    /**
     * Modül görünürlüğünü değiştirme
     */
    public function toggleModuleVisibility()
    {
        try {
            $settings = EventSettings::first();
            if (!$settings) {
                $settings = new EventSettings();
                $settings->title = 'Etkinlikler';
                $settings->homepage_limit = 6;
            }
            
            $settings->is_active = !$settings->is_active;
            $settings->save();
            
            return redirect()->back()->with('success', 'Modül görünürlüğü güncellendi.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Bir hata oluştu: ' . $e->getMessage());
        }
    }
    
    /**
     * Etkinlik tarama formunu gösterir
     */
    public function checkEvents()
    {
        return view('admin.events.check');
    }
    
    /**
     * Etkinlikleri kazıma işlemini başlatır
     */
    public function scrapeEvents(Request $request)
    {
        $url = $request->input('url');
        
        if (empty($url)) {
            return redirect()->route('admin.events.check')
                ->with('error', 'Etkinlik URL adresi belirtilmelidir.');
        }
        
        try {
            $scraperService = app(EventScraperService::class);
            $stats = $scraperService->scrapeEvents($url);
            
            $message = sprintf(
                'Etkinlik tarama tamamlandı: %d eklendi, %d atlandı, %d hata.',
                $stats['added'],
                $stats['skipped'],
                $stats['errors']
            );
            
            if ($stats['errors'] > 0 && !empty($stats['error_messages'])) {
                $message .= ' Hata mesajları: ' . implode(', ', array_slice($stats['error_messages'], 0, 3));
                
                if (count($stats['error_messages']) > 3) {
                    $message .= ' ve diğerleri...';
                }
            }
            
            $alertType = $stats['errors'] > 0 ? 'warning' : 'success';
            
            return redirect()->route('admin.events.index')
                ->with($alertType, $message);
                
        } catch (\Exception $e) {
            report($e);
            return redirect()->route('admin.events.check')
                ->with('error', 'Etkinlik tarama işlemi sırasında bir hata oluştu: ' . $e->getMessage());
        }
    }
    
    /**
     * Toplu işlem formu
     */
    public function bulkActions(Request $request)
    {
        $action = $request->input('bulk_action');
        $eventIds = $request->input('event_ids', []);
        
        if (empty($eventIds)) {
            return redirect()->route('admin.events.index')
                ->with('error', 'Lütfen en az bir etkinlik seçiniz.');
        }
        
        switch ($action) {
            case 'change_status':
                return $this->bulkChangeStatus($request, $eventIds);
            case 'change_homepage':
                return $this->bulkChangeHomepage($request, $eventIds);
            case 'delete':
                return $this->bulkDelete($eventIds);
            default:
                return redirect()->route('admin.events.index')
                    ->with('error', 'Geçersiz toplu işlem.');
        }
    }
    
    /**
     * Toplu etkinlik durumu değiştirme
     */
    private function bulkChangeStatus(Request $request, array $eventIds)
    {
        $status = $request->input('status');
        $statusValue = ($status === 'active');
        
        Event::whereIn('id', $eventIds)->update(['is_active' => $statusValue]);
        
        $statusText = $statusValue ? 'aktif' : 'pasif';
        return redirect()->route('admin.events.index')
            ->with('success', count($eventIds) . ' etkinlik ' . $statusText . ' durumuna getirildi.');
    }
    
    /**
     * Toplu ana sayfa görünürlüğü değiştirme
     */
    private function bulkChangeHomepage(Request $request, array $eventIds)
    {
        $status = $request->input('homepage_status');
        $statusValue = ($status === 'show');
        
        Event::whereIn('id', $eventIds)->update(['show_on_homepage' => $statusValue]);
        
        $statusText = $statusValue ? 'gösterilecek' : 'gizlenecek';
        return redirect()->route('admin.events.index')
            ->with('success', count($eventIds) . ' etkinlik ana sayfada ' . $statusText . '.');
    }
    
    /**
     * Toplu etkinlik silme
     */
    private function bulkDelete(array $eventIds)
    {
        // İlişkili kayıtları kontrol et
        $events = Event::whereIn('id', $eventIds)->get();
        
        DB::beginTransaction();
        try {
            foreach ($events as $event) {
                // Etkinlik görsellerini sil
                if ($event->cover_image) {
                    if (file_exists(public_path($event->cover_image))) {
                        unlink(public_path($event->cover_image));
                    }
                }
                
                // Galeri görsellerini sil
                foreach ($event->images as $image) {
                    if (file_exists(public_path($image->image_path))) {
                        unlink(public_path($image->image_path));
                    }
                    $image->delete();
                }
                
                // Etkinliği sil
                $event->delete();
            }
            
            DB::commit();
            return redirect()->route('admin.events.index')
                ->with('success', count($eventIds) . ' etkinlik başarıyla silindi.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.events.index')
                ->with('error', 'Etkinlikler silinirken bir hata oluştu: ' . $e->getMessage());
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
     * Format türüne göre doğru encode metodunu kullanır
     */
    private function encodeByFormat($image, $extension, $quality = 80)
    {
        $extension = strtolower($extension);
        
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                return $image->toJpeg($quality);
            case 'png':
                return $image->toPng();
            case 'gif':
                return $image->toGif();
            case 'webp':
                return $image->toWebp($quality);
            default:
                return $image->toJpeg($quality);
        }
    }
}
