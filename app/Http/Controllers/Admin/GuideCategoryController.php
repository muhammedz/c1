<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GuideCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GuideCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = GuideCategory::query();
        
        // Arama filtresi
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Durum filtresi
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }
        
        $categories = $query->withCount('places')->ordered()->paginate(15);
        
        return view('admin.guide.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.guide.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:guide_categories,slug',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500'
        ]);

        try {
            DB::beginTransaction();
            
            $data = $request->all();
            
            // Slug otomatik oluştur
            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($data['name']);
            }
            
            // Slug benzersizliğini kontrol et
            $originalSlug = $data['slug'];
            $counter = 1;
            while (GuideCategory::where('slug', $data['slug'])->exists()) {
                $data['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
            
            $category = GuideCategory::create($data);
            
            DB::commit();
            
            return redirect()->route('admin.guide-categories.index')
                ->with('success', 'Rehber kategorisi başarıyla oluşturuldu.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Guide Category oluşturma hatası: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Kategori oluşturulurken bir hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(GuideCategory $guideCategory)
    {
        $guideCategory->load(['places' => function($query) {
            $query->ordered()->with('images');
        }]);
        
        return view('admin.guide.categories.show', compact('guideCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GuideCategory $guideCategory)
    {
        return view('admin.guide.categories.edit', compact('guideCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GuideCategory $guideCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:guide_categories,slug,' . $guideCategory->id,
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500'
        ]);

        try {
            DB::beginTransaction();
            
            $data = $request->all();
            
            // Slug otomatik oluştur
            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($data['name']);
            }
            
            // Slug benzersizliğini kontrol et (mevcut kayıt hariç)
            $originalSlug = $data['slug'];
            $counter = 1;
            while (GuideCategory::where('slug', $data['slug'])->where('id', '!=', $guideCategory->id)->exists()) {
                $data['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
            
            $guideCategory->update($data);
            
            DB::commit();
            
            return redirect()->route('admin.guide-categories.index')
                ->with('success', 'Rehber kategorisi başarıyla güncellendi.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Guide Category güncelleme hatası: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Kategori güncellenirken bir hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GuideCategory $guideCategory)
    {
        try {
            // Kategoriye ait yerler var mı kontrol et
            if ($guideCategory->places()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Bu kategoriye ait yerler bulunduğu için silinemez. Önce yerleri silin veya başka kategoriye taşıyın.');
            }
            
            $guideCategory->delete();
            
            return redirect()->route('admin.guide-categories.index')
                ->with('success', 'Rehber kategorisi başarıyla silindi.');
                
        } catch (\Exception $e) {
            Log::error('Guide Category silme hatası: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Kategori silinirken bir hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Toggle category status
     */
    public function toggleStatus(GuideCategory $guideCategory)
    {
        try {
            $guideCategory->update([
                'is_active' => !$guideCategory->is_active
            ]);
            
            $status = $guideCategory->is_active ? 'aktif' : 'pasif';
            
            return response()->json([
                'success' => true,
                'message' => "Kategori {$status} duruma getirildi.",
                'status' => $guideCategory->is_active
            ]);
            
        } catch (\Exception $e) {
            Log::error('Guide Category durum değiştirme hatası: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Durum değiştirilirken bir hata oluştu.'
            ], 500);
        }
    }

    /**
     * Update sort order
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:guide_categories,id',
            'items.*.sort_order' => 'required|integer|min:0'
        ]);

        try {
            DB::beginTransaction();
            
            foreach ($request->items as $item) {
                GuideCategory::where('id', $item['id'])
                    ->update(['sort_order' => $item['sort_order']]);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Sıralama başarıyla güncellendi.'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Guide Category sıralama hatası: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Sıralama güncellenirken bir hata oluştu.'
            ], 500);
        }
    }
}
