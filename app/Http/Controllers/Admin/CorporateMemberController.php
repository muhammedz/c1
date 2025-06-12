<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CorporateCategory;
use App\Models\CorporateMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Helpers\SlugHelper;

class CorporateMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $category = null)
    {
        $query = CorporateMember::with('category')->orderBy('order', 'asc');
        
        if ($category) {
            $query->where('corporate_category_id', $category);
            $category = CorporateCategory::findOrFail($category);
            $members = $query->get();
            return view('admin.corporate.members.index', compact('members', 'category'));
        } else {
            $members = $query->get();
            return view('admin.corporate.members.index', compact('members'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($category)
    {
        $categories = CorporateCategory::orderBy('name', 'asc')->pluck('name', 'id');
        $selectedCategory = $category;
        return view('admin.corporate.members.create', compact('categories', 'selectedCategory'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:corporate_members,slug',
            'corporate_category_id' => 'required|exists:corporate_categories,id',
            'title' => 'nullable|string|max:255',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'facebook' => 'nullable|string|max:255',
            'twitter' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'linkedin' => 'nullable|string|max:255',
            'website' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'order' => 'nullable|integer',
            'filemanagersystem_image' => 'nullable|string',
            'filemanagersystem_image_alt' => 'nullable|string|max:255',
            'filemanagersystem_image_title' => 'nullable|string|max:255',
            'show_detail' => 'nullable|boolean',
        ]);

        if (empty($request->slug)) {
            $request->merge(['slug' => SlugHelper::createUnique($request->name, CorporateMember::class)]);
        }

        $data = $request->except(['_token']);
        
        // Status checkbox'ından gelmiyorsa false yap
        $data['status'] = $request->has('status') ? 1 : 0;
        
        // show_detail checkbox'ından gelmiyorsa false yap
        $data['show_detail'] = $request->has('show_detail') ? 1 : 0;

        // FileManagerSystem ile ilişki kurma
        $filemanagersystemImage = $request->filemanagersystem_image;
        $mediaId = null;

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
                        'name' => 'Corporate Member Image',
                        'original_name' => 'Corporate_Member_Image_' . date('YmdHis'),
                        'url' => $filemanagersystemImage,
                        'path' => 'external/url-' . time(),
                        'type' => 'image',
                        'mime_type' => 'image/jpeg',
                        'size' => 0,
                    ]);
                    $mediaId = $newMedia->id;
                }
            }
            
            // filemanagersystem_image için URL'yi ayarla
            $data['filemanagersystem_image'] = $filemanagersystemImage;
        }

        $member = CorporateMember::create($data);

        // MediaRelation ilişkisi kur
        if (isset($mediaId) && $mediaId) {
            $member->mediaRelations()->create([
                'media_id' => $mediaId,
                'field_name' => 'filemanagersystem_image',
            ]);
        }

        return redirect()->route('admin.corporate.members.index', $category)
            ->with('success', 'Kurumsal kadro üyesi başarıyla oluşturuldu.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $member = CorporateMember::findOrFail($id);
        $categories = CorporateCategory::orderBy('name', 'asc')->pluck('name', 'id');
        return view('admin.corporate.members.edit', compact('member', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CorporateMember $member)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:corporate_members,slug,'.$member->id,
            'corporate_category_id' => 'required|exists:corporate_categories,id',
            'title' => 'nullable|string|max:255',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'facebook' => 'nullable|string|max:255',
            'twitter' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'linkedin' => 'nullable|string|max:255',
            'website' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'order' => 'nullable|integer',
            'filemanagersystem_image' => 'nullable|string',
            'filemanagersystem_image_alt' => 'nullable|string|max:255',
            'filemanagersystem_image_title' => 'nullable|string|max:255',
            'show_detail' => 'nullable|boolean',
        ]);

        if (empty($request->slug)) {
            $request->merge(['slug' => SlugHelper::createUnique($request->name, CorporateMember::class, 'slug', $member->id)]);
        }

        $data = $request->except(['_token', '_method']);
        
        // Status checkbox'ından gelmiyorsa false yap
        $data['status'] = $request->has('status') ? 1 : 0;
        
        // show_detail checkbox'ından gelmiyorsa false yap
        $data['show_detail'] = $request->has('show_detail') ? 1 : 0;

        // FileManagerSystem ilişkisini güncelle
        $filemanagersystemImage = $request->filemanagersystem_image;
        $mediaId = null;

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
                        'name' => 'Corporate Member Image',
                        'original_name' => 'Corporate_Member_Image_' . date('YmdHis'),
                        'url' => $filemanagersystemImage,
                        'path' => 'external/url-' . time(),
                        'type' => 'image',
                        'mime_type' => 'image/jpeg',
                        'size' => 0,
                    ]);
                    $mediaId = $newMedia->id;
                }
            }
            
            // filemanagersystem_image için URL'yi ayarla
            $data['filemanagersystem_image'] = $filemanagersystemImage;
        }

        $member->update($data);

        // MediaRelation ilişkisini güncelle
        if (isset($mediaId) && $mediaId) {
            // Önceki ilişkiyi kaldır
            $member->mediaRelations()->where('field_name', 'filemanagersystem_image')->delete();
            
            // Yeni ilişki oluştur
            $member->mediaRelations()->create([
                'media_id' => $mediaId,
                'field_name' => 'filemanagersystem_image',
            ]);
        }

        return redirect()->route('admin.corporate.members.index', $request->corporate_category_id)
            ->with('success', 'Kurumsal kadro üyesi başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $member = CorporateMember::findOrFail($id);
        $categoryId = $member->corporate_category_id;

        // FileManagerSystem ilişkisini sil
        $member->mediaRelations()->delete();

        $member->delete();

        return redirect()
            ->route('admin.corporate.members.index', ['category' => $categoryId])
            ->with('success', 'Üye başarıyla silindi.');
    }

    /**
     * Üyelerin sıralamasını günceller
     */
    public function order(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*' => 'required|integer|exists:corporate_members,id',
        ]);

        foreach ($request->items as $index => $id) {
            CorporateMember::where('id', $id)->update(['order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }
}
