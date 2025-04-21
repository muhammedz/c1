<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CorporateCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CorporateCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = CorporateCategory::orderBy('order', 'asc')->get();
        return view('admin.corporate.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.corporate.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:corporate_categories',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'filemanagersystem_image' => 'nullable|string',
            'filemanagersystem_image_alt' => 'nullable|string|max:255',
            'filemanagersystem_image_title' => 'nullable|string|max:255',
            'status' => 'required|boolean'
        ]);

        $data = $request->except(['_token', 'image']);
        
        // Slug oluştur
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        
        // Görsel işleme
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('corporate/categories', 'public');
            $data['image'] = $imagePath;
        }

        // FileManagerSystem ile ilişki kurma
        $filemanagersystemImage = $request->filemanagersystem_image;

        if ($request->filled('filemanagersystem_image')) {
            // URL formatını kontrol et
            
            // 1. /uploads/media/123 formatı
            if (preg_match('#^/uploads/media/(\d+)$#', $filemanagersystemImage, $matches)) {
                $mediaId = $matches[1];
            }
            // 2. /admin/filemanagersystem/media/preview/123 formatı
            elseif (preg_match('#/media/preview/(\d+)#', $filemanagersystemImage, $matches)) {
                $mediaId = $matches[1];
            }
            // Diğer URL formatlarını işleme
            else {
                // URL'yi olduğu gibi kullan ancak medya ilişkisi kurmayı dene
                $mediaId = null;
                
                // Tam URL veya yol olup olmadığını kontrol et
                $media = \App\Models\FileManagerSystem\Media::where('url', $filemanagersystemImage)
                    ->orWhere('path', $filemanagersystemImage)
                    ->first();
                
                if ($media) {
                    $mediaId = $media->id;
                } else {
                    // Eğer medya bulunamazsa, yeni bir medya kaydı oluşturabilirsin
                    $newMedia = \App\Models\FileManagerSystem\Media::create([
                        'name' => 'Corporate Category Image',
                        'url' => $filemanagersystemImage,
                        'type' => 'image',
                    ]);
                    $mediaId = $newMedia->id;
                }
            }
            
            // filemanagersystem_image için URL'yi ayarla
            $data['filemanagersystem_image'] = $filemanagersystemImage;
        }

        $category = CorporateCategory::create($data);

        // MediaRelation ilişkisi kur
        if (isset($mediaId) && $mediaId) {
            $category->mediaRelations()->create([
                'media_id' => $mediaId,
                'field_name' => 'filemanagersystem_image',
            ]);
        }

        return redirect()->route('admin.corporate.categories.index')
            ->with('success', 'Kurumsal kadro kategorisi başarıyla oluşturuldu.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = CorporateCategory::findOrFail($id);
        return view('admin.corporate.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = CorporateCategory::findOrFail($id);
        return view('admin.corporate.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = CorporateCategory::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:corporate_categories,slug,' . $id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'filemanagersystem_image' => 'nullable|string',
            'filemanagersystem_image_alt' => 'nullable|string|max:255',
            'filemanagersystem_image_title' => 'nullable|string|max:255',
            'status' => 'required|boolean'
        ]);

        $data = $request->except(['_token', '_method', 'image']);
        
        // Slug oluştur
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        
        // Görsel işleme
        if ($request->hasFile('image')) {
            // Eğer eski bir görsel varsa sil
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            
            $imagePath = $request->file('image')->store('corporate/categories', 'public');
            $data['image'] = $imagePath;
        }

        // FileManagerSystem ilişkisini güncelle
        $filemanagersystemImage = $request->filemanagersystem_image;

        if ($request->filled('filemanagersystem_image')) {
            // URL formatını kontrol et
            
            // 1. /uploads/media/123 formatı
            if (preg_match('#^/uploads/media/(\d+)$#', $filemanagersystemImage, $matches)) {
                $mediaId = $matches[1];
            }
            // 2. /admin/filemanagersystem/media/preview/123 formatı
            elseif (preg_match('#/media/preview/(\d+)#', $filemanagersystemImage, $matches)) {
                $mediaId = $matches[1];
            }
            // Diğer URL formatlarını işleme
            else {
                // URL'yi olduğu gibi kullan ancak medya ilişkisi kurmayı dene
                $mediaId = null;
                
                // Tam URL veya yol olup olmadığını kontrol et
                $media = \App\Models\FileManagerSystem\Media::where('url', $filemanagersystemImage)
                    ->orWhere('path', $filemanagersystemImage)
                    ->first();
                
                if ($media) {
                    $mediaId = $media->id;
                } else {
                    // Eğer medya bulunamazsa, yeni bir medya kaydı oluşturabilirsin
                    $newMedia = \App\Models\FileManagerSystem\Media::create([
                        'name' => 'Corporate Category Image',
                        'url' => $filemanagersystemImage,
                        'type' => 'image',
                    ]);
                    $mediaId = $newMedia->id;
                }
            }
            
            // filemanagersystem_image için URL'yi ayarla
            $data['filemanagersystem_image'] = $filemanagersystemImage;
        }

        $category->update($data);

        // MediaRelation ilişkisini güncelle
        if (isset($mediaId) && $mediaId) {
            // Önceki ilişkiyi kaldır
            $category->mediaRelations()->where('field_name', 'filemanagersystem_image')->delete();
            
            // Yeni ilişki oluştur
            $category->mediaRelations()->create([
                'media_id' => $mediaId,
                'field_name' => 'filemanagersystem_image',
            ]);
        }

        return redirect()->route('admin.corporate.categories.index')
            ->with('success', 'Kurumsal kadro kategorisi başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = CorporateCategory::findOrFail($id);

        // Kategoriye bağlı üyeler varsa, kategoriyi silme işlemini engelle
        if ($category->members()->count() > 0) {
            return redirect()
                ->route('admin.corporate.categories.index')
                ->with('error', 'Bu kategoriye bağlı üyeler bulunduğu için silinemez.');
        }

        // Kategori resmini sil
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        // FileManagerSystem ilişkisini sil
        $category->mediaRelations()->delete();

        $category->delete();

        return redirect()
            ->route('admin.corporate.categories.index')
            ->with('success', 'Kategori başarıyla silindi.');
    }

    /**
     * Update the order of categories.
     */
    public function updateOrder(Request $request)
    {
        $categories = $request->input('categories', []);
        
        foreach ($categories as $order => $categoryId) {
            CorporateCategory::where('id', $categoryId)->update(['order' => $order]);
        }
        
        return response()->json(['success' => true]);
    }
}
