<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GuidePlace;
use App\Models\GuideCategory;
use App\Models\GuidePlaceImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class GuidePlaceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = GuidePlace::with(['category', 'images']);
        
        // Kategori filtresi
        if ($request->filled('category_id')) {
            $query->where('guide_category_id', $request->category_id);
        }
        
        // Arama filtresi
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }
        
        // Durum filtresi
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }
        
        $places = $query->ordered()->paginate(15);
        $categories = GuideCategory::active()->ordered()->get();
        
        return view('admin.guide.places.index', compact('places', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = GuideCategory::active()->ordered()->get();
        return view('admin.guide.places.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'guide_category_id' => 'required|exists:guide_categories,id',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:guide_places,slug',
            'content' => 'nullable|string',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
            'website' => 'nullable|url|max:255',
            'maps_link' => 'nullable|url',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'working_hours' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120'
        ]);

        try {
            DB::beginTransaction();
            
            $data = $request->all();
            
            // Slug otomatik oluştur
            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($data['title']);
            }
            
            // Slug benzersizliğini kontrol et
            $originalSlug = $data['slug'];
            $counter = 1;
            while (GuidePlace::where('slug', $data['slug'])->exists()) {
                $data['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
            
            $place = GuidePlace::create($data);
            
            // Resimleri yükle
            if ($request->hasFile('images')) {
                $this->uploadImages($request, $place);
            }
            
            DB::commit();
            
            return redirect()->route('admin.guide-places.index')
                ->with('success', 'Rehber yeri başarıyla oluşturuldu.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Guide Place oluşturma hatası: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Yer oluşturulurken bir hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(GuidePlace $guidePlace)
    {
        $guidePlace->load(['category', 'images' => function($query) {
            $query->ordered();
        }]);
        
        return view('admin.guide.places.show', compact('guidePlace'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GuidePlace $guidePlace)
    {
        $categories = GuideCategory::active()->ordered()->get();
        $guidePlace->load(['images' => function($query) {
            $query->ordered();
        }]);
        
        return view('admin.guide.places.edit', compact('guidePlace', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GuidePlace $guidePlace)
    {
        $request->validate([
            'guide_category_id' => 'required|exists:guide_categories,id',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:guide_places,slug,' . $guidePlace->id,
            'content' => 'nullable|string',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
            'website' => 'nullable|url|max:255',
            'maps_link' => 'nullable|url',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'working_hours' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120'
        ]);

        try {
            DB::beginTransaction();
            
            $data = $request->all();
            
            // Slug otomatik oluştur
            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($data['title']);
            }
            
            // Slug benzersizliğini kontrol et (mevcut kayıt hariç)
            $originalSlug = $data['slug'];
            $counter = 1;
            while (GuidePlace::where('slug', $data['slug'])->where('id', '!=', $guidePlace->id)->exists()) {
                $data['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
            
            $guidePlace->update($data);
            
            // Yeni resimleri yükle
            if ($request->hasFile('images')) {
                $this->uploadImages($request, $guidePlace);
            }
            
            DB::commit();
            
            return redirect()->route('admin.guide-places.index')
                ->with('success', 'Rehber yeri başarıyla güncellendi.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Guide Place güncelleme hatası: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Yer güncellenirken bir hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GuidePlace $guidePlace)
    {
        try {
            DB::beginTransaction();
            
            // İlişkili resimleri sil
            foreach ($guidePlace->images as $image) {
                $image->delete(); // Model'deki boot method otomatik dosyaları silecek
            }
            
            $guidePlace->delete();
            
            DB::commit();
            
            return redirect()->route('admin.guide-places.index')
                ->with('success', 'Rehber yeri başarıyla silindi.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Guide Place silme hatası: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Yer silinirken bir hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Toggle place status
     */
    public function toggleStatus(GuidePlace $guidePlace)
    {
        try {
            $guidePlace->update([
                'is_active' => !$guidePlace->is_active
            ]);
            
            $status = $guidePlace->is_active ? 'aktif' : 'pasif';
            
            return response()->json([
                'success' => true,
                'message' => "Yer {$status} duruma getirildi.",
                'status' => $guidePlace->is_active
            ]);
            
        } catch (\Exception $e) {
            Log::error('Guide Place durum değiştirme hatası: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Durum değiştirilirken bir hata oluştu.'
            ], 500);
        }
    }

    /**
     * Upload images for a place
     */
    public function uploadImages(Request $request, GuidePlace $place)
    {
        $request->validate([
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120'
        ]);

        try {
            $uploadedImages = [];
            
            foreach ($request->file('images') as $index => $file) {
                $filename = time() . '_' . $index . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $path = 'guide/places/' . $place->id;
                
                // Ana resmi kaydet
                $fullPath = $file->storeAs($path, $filename, 'public');
                
                // Thumbnail oluştur
                $this->createThumbnail($file, $path, $filename);
                
                // Medium boyut oluştur
                $this->createMediumSize($file, $path, $filename);
                
                // Veritabanına kaydet
                $image = GuidePlaceImage::create([
                    'guide_place_id' => $place->id,
                    'image_path' => $fullPath,
                    'alt_text' => $request->input("alt_texts.{$index}", $place->title),
                    'sort_order' => $place->images()->count() + $index + 1,
                    'is_featured' => $index === 0 && $place->images()->count() === 0 // İlk resim featured olsun
                ]);
                
                $uploadedImages[] = $image;
            }
            
            return response()->json([
                'success' => true,
                'message' => count($uploadedImages) . ' resim başarıyla yüklendi.',
                'images' => $uploadedImages
            ]);
            
        } catch (\Exception $e) {
            Log::error('Guide Place resim yükleme hatası: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Resimler yüklenirken bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete an image
     */
    public function deleteImage(GuidePlaceImage $image)
    {
        try {
            $image->delete(); // Model'deki boot method otomatik dosyaları silecek
            
            return response()->json([
                'success' => true,
                'message' => 'Resim başarıyla silindi.'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Guide Place resim silme hatası: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Resim silinirken bir hata oluştu.'
            ], 500);
        }
    }

    /**
     * Set featured image
     */
    public function setFeaturedImage(Request $request, GuidePlace $place)
    {
        $request->validate([
            'image_id' => 'required|exists:guide_place_images,id'
        ]);

        try {
            DB::beginTransaction();
            
            // Tüm resimlerin featured durumunu kaldır
            $place->images()->update(['is_featured' => false]);
            
            // Seçilen resmi featured yap
            GuidePlaceImage::where('id', $request->image_id)
                ->where('guide_place_id', $place->id)
                ->update(['is_featured' => true]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Öne çıkan resim başarıyla ayarlandı.'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Guide Place featured resim hatası: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Öne çıkan resim ayarlanırken bir hata oluştu.'
            ], 500);
        }
    }

    /**
     * Update image order
     */
    public function updateImageOrder(Request $request, GuidePlace $place)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*.id' => 'required|exists:guide_place_images,id',
            'images.*.sort_order' => 'required|integer|min:0'
        ]);

        try {
            DB::beginTransaction();
            
            foreach ($request->images as $imageData) {
                GuidePlaceImage::where('id', $imageData['id'])
                    ->where('guide_place_id', $place->id)
                    ->update(['sort_order' => $imageData['sort_order']]);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Resim sıralaması başarıyla güncellendi.'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Guide Place resim sıralama hatası: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Resim sıralaması güncellenirken bir hata oluştu.'
            ], 500);
        }
    }

    /**
     * Create thumbnail
     */
    private function createThumbnail($file, $path, $filename)
    {
        $thumbnailPath = $path . '/thumbnails';
        $thumbnailFilename = pathinfo($filename, PATHINFO_FILENAME) . '_thumb.' . pathinfo($filename, PATHINFO_EXTENSION);
        
        if (!Storage::disk('public')->exists($thumbnailPath)) {
            Storage::disk('public')->makeDirectory($thumbnailPath);
        }
        
        $image = Image::make($file);
        $image->fit(300, 200, function ($constraint) {
            $constraint->upsize();
        });
        
        Storage::disk('public')->put($thumbnailPath . '/' . $thumbnailFilename, $image->encode());
    }

    /**
     * Create medium size
     */
    private function createMediumSize($file, $path, $filename)
    {
        $mediumPath = $path . '/medium';
        $mediumFilename = pathinfo($filename, PATHINFO_FILENAME) . '_medium.' . pathinfo($filename, PATHINFO_EXTENSION);
        
        if (!Storage::disk('public')->exists($mediumPath)) {
            Storage::disk('public')->makeDirectory($mediumPath);
        }
        
        $image = Image::make($file);
        $image->resize(800, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        
        Storage::disk('public')->put($mediumPath . '/' . $mediumFilename, $image->encode());
    }
}
