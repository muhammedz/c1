<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class NewsCategoryController extends Controller
{
    /**
     * Haber Kategorileri listesi
     */
    public function index()
    {
        $newsCategories = NewsCategory::with('parent')
            ->orderBy('order')
            ->paginate(20);
            
        return view('admin.news-categories.index', compact('newsCategories'));
    }
    
    /**
     * Yeni haber kategorisi oluşturma formu
     */
    public function create()
    {
        $newsCategories = NewsCategory::orderBy('name')->get();
        return view('admin.news-categories.create', compact('newsCategories'));
    }
    
    /**
     * Haber kategorisi kaydetme
     */
    public function store(Request $request)
    {
        \Illuminate\Support\Facades\Log::info('Haber kategorisi eklenmeye çalışılıyor - BAŞLANGIÇ', [
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
                'parent_id' => 'nullable|exists:news_categories,id',
                'order' => 'nullable|integer|min:0',
            ]);
            
            \Illuminate\Support\Facades\Log::info('Validasyon başarılı');
            
            // Slug oluştur
            $slug = Str::slug($request->name);
            
            // Slug kontrolü
            $existingCategory = NewsCategory::where('slug', $slug)->first();
            if ($existingCategory) {
                \Illuminate\Support\Facades\Log::warning('Haber kategorisi zaten mevcut', ['slug' => $slug]);
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Bu isme sahip bir haber kategorisi zaten mevcut. Lütfen farklı bir isim seçin.');
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
            
            \Illuminate\Support\Facades\Log::info('Haber kategorisi verileri hazırlandı', $categoryData);
            
            // DB işlemini izole et
            \Illuminate\Support\Facades\DB::beginTransaction();
            
            $newsCategory = NewsCategory::create($categoryData);
            
            \Illuminate\Support\Facades\DB::commit();
            
            \Illuminate\Support\Facades\Log::info('Haber kategorisi başarıyla eklendi', [
                'category_id' => $newsCategory->id, 
                'category' => $newsCategory->toArray()
            ]);
            
            return redirect()->route('admin.news-categories.index')
                ->with('success', 'Haber kategorisi başarıyla oluşturuldu.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Illuminate\Support\Facades\Log::error('Validasyon hatası', [
                'errors' => $e->errors(),
            ]);
            
            throw $e; // Laravel'in kendi hata işleme mekanizmasına bırak
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Haber kategorisi eklenirken hata oluştu', [
                'error' => $e->getMessage(),
                'class' => get_class($e),
                'trace' => $e->getTraceAsString()
            ]);
            
            if (\Illuminate\Support\Facades\DB::transactionLevel() > 0) {
                \Illuminate\Support\Facades\DB::rollBack();
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Haber kategorisi oluşturulurken bir hata oluştu: ' . $e->getMessage());
        }
    }
    
    /**
     * Haber kategorisini düzenleme formu
     */
    public function edit(NewsCategory $newsCategory)
    {
        $newsCategories = NewsCategory::where('id', '!=', $newsCategory->id)
            ->orderBy('name')
            ->get();
            
        return view('admin.news-categories.edit', compact('newsCategory', 'newsCategories'));
    }
    
    /**
     * Haber kategorisi güncelleme
     */
    public function update(Request $request, NewsCategory $newsCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:news_categories,id',
            'is_active' => 'boolean'
        ]);
        
        // Kendisini parent olarak seçmeyi engelle
        if ($request->parent_id == $newsCategory->id) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Bir haber kategorisi kendisini üst kategori olarak seçemez.');
        }
        
        // Alt kategorileri kendisine parent olarak seçmeyi engelle
        $childIds = $this->getAllChildIds($newsCategory);
        if ($request->parent_id && in_array($request->parent_id, $childIds)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Bir alt haber kategorisi, üst kategori olarak seçilemez.');
        }
        
        // Slug kontrolü
        $newSlug = Str::slug($request->name);
        $existingCategory = NewsCategory::where('slug', $newSlug)
            ->where('id', '!=', $newsCategory->id)
            ->first();
            
        if ($existingCategory) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Bu isme sahip başka bir haber kategorisi zaten mevcut. Lütfen farklı bir isim seçin.');
        }
        
        $newsCategory->update([
            'name' => $request->name,
            'slug' => $newSlug,
            'icon' => $request->icon,
            'description' => $request->description,
            'parent_id' => $request->parent_id,
            'order' => $request->order ?? 0,
            'is_active' => $request->has('is_active')
        ]);
        
        return redirect()->route('admin.news-categories.index')
            ->with('success', 'Haber kategorisi başarıyla güncellendi.');
    }
    
    /**
     * Haber kategorisi silme
     */
    public function destroy(NewsCategory $newsCategory)
    {
        // İlişkili haber kontrolü
        if ($newsCategory->news()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Bu haber kategorisine ait haberler bulunduğu için silinemez. Önce haberleri başka kategorilere taşıyın veya silin.');
        }
        
        // Alt kategorileri ana kategoriye taşı
        NewsCategory::where('parent_id', $newsCategory->id)
            ->update(['parent_id' => $newsCategory->parent_id]);
            
        $newsCategory->delete();
        
        return redirect()->route('admin.news-categories.index')
            ->with('success', 'Haber kategorisi başarıyla silindi.');
    }
    
    /**
     * Haber kategorisi sıralama
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'newsCategories' => 'required|array',
            'newsCategories.*' => 'exists:news_categories,id'
        ]);
        
        foreach ($request->newsCategories as $order => $id) {
            NewsCategory::where('id', $id)->update(['order' => $order + 1]);
        }
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Bir haber kategorisinin tüm alt kategori id'lerini recursive olarak getir
     */
    private function getAllChildIds(NewsCategory $newsCategory, array $ids = [])
    {
        $children = NewsCategory::where('parent_id', $newsCategory->id)->get();
        
        foreach ($children as $child) {
            $ids[] = $child->id;
            $ids = $this->getAllChildIds($child, $ids);
        }
        
        return $ids;
    }
} 