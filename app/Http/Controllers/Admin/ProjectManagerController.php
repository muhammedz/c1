<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\ProjectSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ProjectManagerController extends Controller
{
    /**
     * Projelerin listelendiği ana sayfa
     */
    public function index(Request $request)
    {
        $query = Project::with('category');
        
        // Arama
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }
        
        // Kategori filtresi
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->get('category_id'));
        }
        
        // Durum filtresi
        if ($request->filled('status')) {
            $status = $request->get('status');
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            } elseif ($status === 'homepage') {
                $query->where('show_on_homepage', true);
            }
        }
        
        // Sıralama (proje tarihine göre, en yeni üstte)
        $sortField = $request->get('sort', 'project_date');
        $sortDirection = $request->get('direction', 'desc');
        
        if ($sortField === 'project_date') {
            $query->orderBy('project_date', $sortDirection)
                  ->orderBy('created_at', $sortDirection);
        } else {
            $query->orderBy($sortField, $sortDirection);
        }
        
        $projects = $query->paginate(15)->appends($request->query());
        $categories = ProjectCategory::orderBy('order', 'asc')->get();
        $settings = ProjectSettings::first() ?? new ProjectSettings(['is_active' => true]);
        
        return view('admin.projects.index', compact('projects', 'categories', 'settings'));
    }
    
    /**
     * Yeni proje ekleme formu
     */
    public function create()
    {
        $categories = ProjectCategory::orderBy('name', 'asc')->get();
        return view('admin.projects.create', compact('categories'));
    }
    
    /**
     * Yeni proje kaydetme
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:projects',
            'description' => 'required|string',
            'category_id' => 'nullable|exists:project_categories,id',
            'completion_percentage' => 'nullable|integer|min:0|max:100',
            'project_date' => 'nullable|date',
            'cover_image' => 'nullable|string', // URL olarak alınacak
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'nullable|string', // URL olarak alınacak
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'show_on_homepage' => 'nullable|boolean',
        ]);
        
        // Slug oluştur (eğer boşsa)
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }
        
        // Veritabanında proje oluştur
        try {
            DB::beginTransaction();
            
            $project = new Project();
            $project->title = $validated['title'];
            $project->slug = $validated['slug'];
            $project->description = $validated['description'];
            $project->category_id = $validated['category_id'] ?? null;
            $project->completion_percentage = $validated['completion_percentage'] ?? 100;
            $project->project_date = $validated['project_date'] ?? now();
            
            // LFM'den gelen resim yolunu doğrudan kaydet
            if ($request->has('cover_image') && !empty($request->cover_image)) {
                // URL formatındaki dosya yolunu kaydet
                // Laravel File Manager URL'i veya tam yol olabilir
                $coverImagePath = $request->cover_image;
                
                // http://localhost:8000/storage/ şeklindeki yolları düzelt
                if (Str::contains($coverImagePath, ['/storage/', 'http'])) {
                    // URL'den dosya yolunu çıkar
                    $storagePosition = strpos($coverImagePath, '/storage/');
                    if ($storagePosition !== false) {
                        $coverImagePath = substr($coverImagePath, $storagePosition + 9); // "/storage/" kısmını kaldır (9 karakter)
                    }
                }
                
                $project->cover_image = $coverImagePath;
            }
            
            $project->order = $validated['order'] ?? 0;
            $project->is_active = isset($validated['is_active']);
            $project->show_on_homepage = isset($validated['show_on_homepage']);
            $project->save();
            
            // Galeri görselleri
            if ($request->has('gallery_images') && is_array($request->gallery_images)) {
                $galleryImages = [];
                
                foreach ($request->gallery_images as $index => $galleryImageUrl) {
                    if (empty($galleryImageUrl)) continue;
                    
                    // URL formatındaki dosya yolunu işle
                    $galleryImagePath = $galleryImageUrl;
                    
                    // http://localhost:8000/storage/ şeklindeki yolları düzelt
                    if (Str::contains($galleryImagePath, ['/storage/', 'http'])) {
                        // URL'den dosya yolunu çıkar
                        $storagePosition = strpos($galleryImagePath, '/storage/');
                        if ($storagePosition !== false) {
                            $galleryImagePath = substr($galleryImagePath, $storagePosition + 9); // "/storage/" kısmını kaldır (9 karakter)
                        }
                    }
                    
                    $galleryImages[] = [
                        'image_path' => $galleryImagePath,
                        'order' => $index + 1
                    ];
                }
                
                // Galeri görsellerini kaydet
                if (!empty($galleryImages)) {
                    $project->images()->createMany($galleryImages);
                }
            }
            
            DB::commit();
            
            return redirect()->route('admin.projects.index')
                ->with('success', 'Proje başarıyla oluşturuldu.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Proje oluşturulurken bir hata oluştu: ' . $e->getMessage());
        }
    }
    
    /**
     * Proje detayını görüntüleme
     */
    public function show($id)
    {
        $project = Project::with(['category', 'images'])->findOrFail($id);
        $categories = ProjectCategory::orderBy('name', 'asc')->get();
        
        return view('admin.projects.show', compact('project', 'categories'));
    }
    
    /**
     * Proje düzenleme formu
     */
    public function editProject($id)
    {
        $project = Project::with(['category', 'images'])->findOrFail($id);
        $categories = ProjectCategory::orderBy('name', 'asc')->get();
        
        return view('admin.projects.edit', compact('project', 'categories'));
    }
    
    /**
     * Proje güncelleme
     */
    public function updateProject(Request $request, $id)
    {
        $project = Project::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:projects,slug,' . $id,
            'description' => 'required|string',
            'category_id' => 'nullable|exists:project_categories,id',
            'completion_percentage' => 'nullable|integer|min:0|max:100',
            'project_date' => 'nullable|date',
            'cover_image' => 'nullable|string', // URL olarak alınacak
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'nullable|string', // URL olarak alınacak
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'show_on_homepage' => 'nullable|boolean',
            'delete_gallery' => 'nullable|array',
            'delete_gallery.*' => 'nullable|integer'
        ]);
        
        // Slug oluştur (eğer boşsa)
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }
        
        try {
            DB::beginTransaction();
            
            $project->title = $validated['title'];
            $project->slug = $validated['slug'];
            $project->description = $validated['description'];
            $project->category_id = $validated['category_id'] ?? null;
            $project->completion_percentage = $validated['completion_percentage'] ?? 100;
            $project->project_date = $validated['project_date'] ?? now();
            
            // LFM'den gelen resim yolunu doğrudan kaydet
            if ($request->has('cover_image') && !empty($request->cover_image)) {
                // URL formatındaki dosya yolunu kaydet
                // Laravel File Manager URL'i veya tam yol olabilir
                $coverImagePath = $request->cover_image;
                
                // http://localhost:8000/storage/ şeklindeki yolları düzelt
                if (Str::contains($coverImagePath, ['/storage/', 'http'])) {
                    // URL'den dosya yolunu çıkar
                    $storagePosition = strpos($coverImagePath, '/storage/');
                    if ($storagePosition !== false) {
                        $coverImagePath = substr($coverImagePath, $storagePosition + 9); // "/storage/" kısmını kaldır (9 karakter)
                    }
                }
                
                $project->cover_image = $coverImagePath;
            }
            
            $project->order = $validated['order'] ?? 0;
            $project->is_active = isset($validated['is_active']) ? true : false;
            $project->show_on_homepage = isset($validated['show_on_homepage']) ? true : false;
            $project->save();
            
            // Silinecek galeri resimleri
            if ($request->has('delete_gallery') && is_array($request->delete_gallery)) {
                foreach ($request->delete_gallery as $imageId) {
                    $projectImage = $project->images()->find($imageId);
                    if ($projectImage) {
                        // Bu aşamada silmeye gerek yok çünkü bunlar LFM ile yönetiliyor
                        $projectImage->delete();
                    }
                }
            }
            
            // Galeri görselleri
            if ($request->has('gallery_images') && is_array($request->gallery_images)) {
                $galleryImages = [];
                
                foreach ($request->gallery_images as $index => $galleryImageUrl) {
                    if (empty($galleryImageUrl)) continue;
                    
                    // URL formatındaki dosya yolunu işle
                    $galleryImagePath = $galleryImageUrl;
                    
                    // http://localhost:8000/storage/ şeklindeki yolları düzelt
                    if (Str::contains($galleryImagePath, ['/storage/', 'http'])) {
                        // URL'den dosya yolunu çıkar
                        $storagePosition = strpos($galleryImagePath, '/storage/');
                        if ($storagePosition !== false) {
                            $galleryImagePath = substr($galleryImagePath, $storagePosition + 9); // "/storage/" kısmını kaldır (9 karakter)
                        }
                    }
                    
                    $galleryImages[] = [
                        'image_path' => $galleryImagePath,
                        'order' => $index + 1
                    ];
                }
                
                // Galeri görsellerini kaydet
                if (!empty($galleryImages)) {
                    $project->images()->createMany($galleryImages);
                }
            }
            
            DB::commit();
            
            return redirect()->route('admin.projects.index')
                ->with('success', 'Proje başarıyla güncellendi.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Proje güncellenirken bir hata oluştu: ' . $e->getMessage());
        }
    }
    
    /**
     * Proje silme
     */
    public function delete($id)
    {
        $project = Project::with('images')->findOrFail($id);
        
        try {
            DB::beginTransaction();
            
            // Kapak görselini sil
            if ($project->cover_image) {
                Storage::delete('public/' . $project->cover_image);
            }
            
            // Galeri görsellerini sil
            foreach ($project->images as $image) {
                Storage::delete('public/' . $image->image_path);
            }
            
            // Projeyi ve ilişkili kayıtları sil
            $project->images()->delete();
            $project->delete();
            
            DB::commit();
        
            return redirect()->route('admin.projects.index')
                ->with('success', 'Proje başarıyla silindi.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('admin.projects.index')
                ->with('error', 'Proje silinirken bir hata oluştu: ' . $e->getMessage());
        }
    }
    
    /**
     * Projenin görünürlük durumunu değiştir
     */
    public function toggleVisibility($id)
    {
        $project = Project::findOrFail($id);
        $project->is_active = !$project->is_active;
        $project->save();
        
        return response()->json([
            'success' => true,
            'is_active' => $project->is_active
        ]);
    }
    
    /**
     * Projenin anasayfada gösterilme durumunu değiştir
     */
    public function toggleHomepage($id)
    {
        $project = Project::findOrFail($id);
        $project->show_on_homepage = !$project->show_on_homepage;
        $project->save();
        
        return response()->json([
            'success' => true,
            'show_on_homepage' => $project->show_on_homepage
        ]);
    }
    
    /**
     * Proje sıralamasını güncelle
     */
    public function updateOrder(Request $request)
    {
        $validated = $request->validate([
            'orders' => 'required|array',
            'orders.*' => 'required|integer|min:0'
        ]);
        
        try {
            DB::beginTransaction();
            
            foreach ($validated['orders'] as $id => $order) {
                Project::where('id', $id)->update(['order' => $order]);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Proje kategorilerini yönetme sayfası
     */
    public function categories()
    {
        $categories = ProjectCategory::orderBy('order', 'asc')->get();
        return view('admin.projects.categories', compact('categories'));
    }
    
    /**
     * Yeni kategori ekleme
     */
    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'nullable|string|max:100|unique:project_categories',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean'
        ]);
        
        // Slug oluştur (eğer boşsa)
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }
        
        $maxOrder = ProjectCategory::max('order') ?? 0;
        
        $category = new ProjectCategory();
        $category->name = $validated['name'];
        $category->slug = $validated['slug'];
        $category->order = $validated['order'] ?? ($maxOrder + 1);
        $category->is_active = isset($validated['is_active']);
        $category->save();
        
        return redirect()->route('admin.projects.categories')
            ->with('success', 'Kategori başarıyla eklendi.');
    }
    
    /**
     * Kategori güncelleme
     */
    public function updateCategory(Request $request, $id)
    {
        $category = ProjectCategory::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'nullable|string|max:100|unique:project_categories,slug,' . $id,
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean'
        ]);
        
        // Slug oluştur (eğer boşsa)
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }
        
        $category->name = $validated['name'];
        $category->slug = $validated['slug'];
        $category->order = $validated['order'] ?? $category->order;
        $category->is_active = isset($validated['is_active']);
        $category->save();
        
        return redirect()->route('admin.projects.categories')
            ->with('success', 'Kategori başarıyla güncellendi.');
    }
    
    /**
     * Kategori silme
     */
    public function deleteCategory($id)
    {
        $category = ProjectCategory::findOrFail($id);
        
        // Kategoriye bağlı proje var mı kontrol et
        $projectCount = Project::where('category_id', $id)->count();
        
        if ($projectCount > 0) {
            return redirect()->route('admin.projects.categories')
                ->with('error', 'Bu kategoriye bağlı ' . $projectCount . ' adet proje bulunmaktadır. Lütfen önce bu projeleri başka bir kategoriye taşıyın veya silin.');
        }
        
        $category->delete();
        
        return redirect()->route('admin.projects.categories')
            ->with('success', 'Kategori başarıyla silindi.');
    }
    
    /**
     * Kategori görünürlük durumunu değiştir
     */
    public function toggleCategoryVisibility($id)
    {
        $category = ProjectCategory::findOrFail($id);
        $category->is_active = !$category->is_active;
        $category->save();
        
        return response()->json([
            'success' => true,
            'is_active' => $category->is_active
        ]);
    }
    
    /**
     * Kategori sıralamasını güncelle
     */
    public function updateCategoryOrder(Request $request)
    {
        $validated = $request->validate([
            'orders' => 'required|array',
            'orders.*' => 'required|integer|min:0'
        ]);
        
        try {
            DB::beginTransaction();
            
            foreach ($validated['orders'] as $id => $order) {
                ProjectCategory::where('id', $id)->update(['order' => $order]);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Ayarlar sayfası
     */
    public function settings()
    {
        $settings = ProjectSettings::first() ?? new ProjectSettings([
            'is_active' => true,
            'show_categories_filter' => true,
            'items_per_page' => 6,
            'section_title' => 'Projelerimiz',
            'section_description' => 'Tamamladığımız ve devam eden projelerimiz',
            'show_view_all' => true,
            'view_all_text' => 'Tüm Projeleri Gör',
            'view_all_url' => '/projeler'
        ]);
        
        return view('admin.projects.settings', compact('settings'));
    }
    
    /**
     * Ayarları güncelleme
     */
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'section_title' => 'required|string|max:255',
            'section_description' => 'nullable|string',
            'items_per_page' => 'required|integer|min:1|max:24',
            'show_categories_filter' => 'nullable|boolean',
            'show_view_all' => 'nullable|boolean',
            'view_all_text' => 'nullable|string|max:255',
            'view_all_url' => 'nullable|string|max:255',
        ]);
        
        $settings = ProjectSettings::first();
        
        if (!$settings) {
            $settings = new ProjectSettings();
        }
        
        $settings->section_title = $validated['section_title'];
        $settings->section_description = $validated['section_description'];
        $settings->items_per_page = $validated['items_per_page'];
        $settings->show_categories_filter = isset($validated['show_categories_filter']);
        $settings->show_view_all = isset($validated['show_view_all']);
        $settings->view_all_text = $validated['view_all_text'];
        $settings->view_all_url = $validated['view_all_url'];
        $settings->save();
        
        return redirect()->route('admin.projects.settings')
            ->with('success', 'Ayarlar başarıyla güncellendi.');
    }
    
    /**
     * Modül görünürlük durumunu değiştir
     */
    public function toggleModuleVisibility()
    {
        $settings = ProjectSettings::first();
        
        if (!$settings) {
            $settings = new ProjectSettings();
            $settings->section_title = 'Projelerimiz';
            $settings->items_per_page = 6;
        }
        
        $settings->is_active = !$settings->is_active;
        $settings->save();
        
        return response()->json([
            'success' => true,
            'is_active' => $settings->is_active
        ]);
    }
} 