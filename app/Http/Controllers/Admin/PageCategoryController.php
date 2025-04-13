<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PageCategoryController extends Controller
{
    /**
     * Sayfa Kategorileri listesi
     */
    public function index()
    {
        $pageCategories = PageCategory::with('parent')
            ->orderBy('order')
            ->paginate(20);
            
        return view('admin.page-categories.index', compact('pageCategories'));
    }
    
    /**
     * Yeni sayfa kategorisi oluşturma formu
     */
    public function create()
    {
        $pageCategories = PageCategory::orderBy('name')->get();
        return view('admin.page-categories.create', compact('pageCategories'));
    }
    
    /**
     * Sayfa kategorisi kaydetme
     */
    public function store(Request $request)
    {
        \Illuminate\Support\Facades\Log::info('Sayfa kategorisi eklenmeye çalışılıyor - BAŞLANGIÇ', [
            'http_method' => $request->method(),
            'url' => $request->url(),
            'is_ajax' => $request->ajax(),
            'all_input' => $request->all(),
            'has_csrf' => $request->has('_token'),
            'csrf' => $request->input('_token'),
            'headers' => $request->headers->all(),
        ]);
        
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'icon' => 'nullable|string|max:50',
                'description' => 'nullable|string',
                'parent_id' => 'nullable|exists:page_categories,id',
                'order' => 'nullable|integer|min:0',
            ]);
            
            \Illuminate\Support\Facades\Log::info('Validasyon başarılı');
            
            // Slug oluştur
            $slug = Str::slug($request->name);
            
            // Slug kontrolü
            $existingCategory = PageCategory::where('slug', $slug)->first();
            if ($existingCategory) {
                \Illuminate\Support\Facades\Log::warning('Sayfa kategorisi zaten mevcut', ['slug' => $slug]);
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Bu isme sahip bir sayfa kategorisi zaten mevcut. Lütfen farklı bir isim seçin.');
            }
            
            $categoryData = [
                'name' => $request->name,
                'slug' => $slug,
                'icon' => $request->icon,
                'description' => $request->description,
                'parent_id' => $request->parent_id,
                'order' => $request->order ?? 0,
                'is_active' => $request->has('is_active') ? true : false
            ];
            
            \Illuminate\Support\Facades\Log::info('Sayfa kategorisi verileri hazırlandı', $categoryData);
            
            // DB işlemini izole et
            \Illuminate\Support\Facades\DB::beginTransaction();
            
            $pageCategory = PageCategory::create($categoryData);
            
            \Illuminate\Support\Facades\DB::commit();
            
            \Illuminate\Support\Facades\Log::info('Sayfa kategorisi başarıyla eklendi', [
                'category_id' => $pageCategory->id, 
                'category' => $pageCategory->toArray()
            ]);
            
            return redirect()->route('admin.page-categories.index')
                ->with('success', 'Sayfa kategorisi başarıyla oluşturuldu.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Illuminate\Support\Facades\Log::error('Validasyon hatası', [
                'errors' => $e->errors(),
            ]);
            
            throw $e; // Laravel'in kendi hata işleme mekanizmasına bırak
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Sayfa kategorisi eklenirken hata oluştu', [
                'error' => $e->getMessage(),
                'class' => get_class($e),
                'trace' => $e->getTraceAsString()
            ]);
            
            if (\Illuminate\Support\Facades\DB::transactionLevel() > 0) {
                \Illuminate\Support\Facades\DB::rollBack();
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Sayfa kategorisi oluşturulurken bir hata oluştu: ' . $e->getMessage());
        }
    }
    
    /**
     * Sayfa kategorisini düzenleme formu
     */
    public function edit(PageCategory $pageCategory)
    {
        $pageCategories = PageCategory::where('id', '!=', $pageCategory->id)
            ->orderBy('name')
            ->get();
            
        return view('admin.page-categories.edit', compact('pageCategory', 'pageCategories'));
    }
    
    /**
     * Sayfa kategorisi güncelleme
     */
    public function update(Request $request, PageCategory $pageCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:page_categories,id',
            'is_active' => 'boolean'
        ]);
        
        // Kendisini parent olarak seçmeyi engelle
        if ($request->parent_id == $pageCategory->id) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Bir sayfa kategorisi kendisini üst kategori olarak seçemez.');
        }
        
        // Alt kategorileri kendisine parent olarak seçmeyi engelle
        $childIds = $this->getAllChildIds($pageCategory);
        if ($request->parent_id && in_array($request->parent_id, $childIds)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Bir alt sayfa kategorisi, üst kategori olarak seçilemez.');
        }
        
        // Slug kontrolü
        $newSlug = Str::slug($request->name);
        $existingCategory = PageCategory::where('slug', $newSlug)
            ->where('id', '!=', $pageCategory->id)
            ->first();
            
        if ($existingCategory) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Bu isme sahip başka bir sayfa kategorisi zaten mevcut. Lütfen farklı bir isim seçin.');
        }
        
        $pageCategory->update([
            'name' => $request->name,
            'slug' => $newSlug,
            'icon' => $request->icon,
            'description' => $request->description,
            'parent_id' => $request->parent_id,
            'order' => $request->order ?? 0,
            'is_active' => $request->has('is_active')
        ]);
        
        return redirect()->route('admin.page-categories.index')
            ->with('success', 'Sayfa kategorisi başarıyla güncellendi.');
    }
    
    /**
     * Sayfa kategorisi silme
     */
    public function destroy(PageCategory $pageCategory)
    {
        // İlişkili sayfa kontrolü
        if ($pageCategory->pages()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Bu sayfa kategorisine ait sayfalar bulunduğu için silinemez. Önce sayfaları başka kategorilere taşıyın veya silin.');
        }
        
        // Alt kategorileri ana kategoriye taşı
        PageCategory::where('parent_id', $pageCategory->id)
            ->update(['parent_id' => $pageCategory->parent_id]);
            
        $pageCategory->delete();
        
        return redirect()->route('admin.page-categories.index')
            ->with('success', 'Sayfa kategorisi başarıyla silindi.');
    }
    
    /**
     * Sayfa kategorisi sıralama
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'pageCategories' => 'required|array',
            'pageCategories.*' => 'exists:page_categories,id'
        ]);
        
        foreach ($request->pageCategories as $order => $id) {
            PageCategory::where('id', $id)->update(['order' => $order + 1]);
        }
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Bir sayfa kategorisinin tüm alt kategori id'lerini recursive olarak getir
     */
    private function getAllChildIds(PageCategory $pageCategory, array $ids = [])
    {
        $children = PageCategory::where('parent_id', $pageCategory->id)->get();
        
        foreach ($children as $child) {
            $ids[] = $child->id;
            $ids = $this->getAllChildIds($child, $ids);
        }
        
        return $ids;
    }
} 