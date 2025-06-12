<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ServiceCategoryController extends Controller
{
    /**
     * Müdürlükler Kategorisi listesi
     */
    public function index()
    {
        $serviceCategories = ServiceCategory::with('parent')
            ->orderBy('order')
            ->paginate(20);
            
        return view('admin.service-categories.index', compact('serviceCategories'));
    }
    
    /**
     * Yeni müdürlük kategorisi oluşturma formu
     */
    public function create()
    {
        $serviceCategories = ServiceCategory::orderBy('name')->get();
        return view('admin.service-categories.create', compact('serviceCategories'));
    }
    
    /**
     * Müdürlük kategorisi kaydetme
     */
    public function store(Request $request)
    {
        \Illuminate\Support\Facades\Log::info('Hizmet kategorisi eklenmeye çalışılıyor - BAŞLANGIÇ', [
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
                'parent_id' => 'nullable|exists:service_categories,id',
                'order' => 'nullable|integer|min:0',
            ]);
            
            \Illuminate\Support\Facades\Log::info('Validasyon başarılı');
            
            // Slug oluştur
            $slug = Str::slug($request->name);
            
            // Slug kontrolü
            $existingCategory = ServiceCategory::where('slug', $slug)->first();
            if ($existingCategory) {
                \Illuminate\Support\Facades\Log::warning('Hizmet kategorisi zaten mevcut', ['slug' => $slug]);
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Bu isme sahip bir hizmet kategorisi zaten mevcut. Lütfen farklı bir isim seçin.');
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
            
            \Illuminate\Support\Facades\Log::info('Hizmet kategorisi verileri hazırlandı', $categoryData);
            
            // DB işlemini izole et
            \Illuminate\Support\Facades\DB::beginTransaction();
            
            $serviceCategory = ServiceCategory::create($categoryData);
            
            \Illuminate\Support\Facades\DB::commit();
            
            \Illuminate\Support\Facades\Log::info('Hizmet kategorisi başarıyla eklendi', [
                'category_id' => $serviceCategory->id, 
                'category' => $serviceCategory->toArray()
            ]);
            
                    return redirect()->route('admin.service-categories.index')
            ->with('success', 'Müdürlük kategorisi başarıyla oluşturuldu.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Illuminate\Support\Facades\Log::error('Validasyon hatası', [
                'errors' => $e->errors(),
            ]);
            
            throw $e; // Laravel'in kendi hata işleme mekanizmasına bırak
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Hizmet kategorisi eklenirken hata oluştu', [
                'error' => $e->getMessage(),
                'class' => get_class($e),
                'trace' => $e->getTraceAsString()
            ]);
            
            if (\Illuminate\Support\Facades\DB::transactionLevel() > 0) {
                \Illuminate\Support\Facades\DB::rollBack();
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Hizmet kategorisi oluşturulurken bir hata oluştu: ' . $e->getMessage());
        }
    }
    
    /**
     * Hizmet kategorisini düzenleme formu
     */
    public function edit(ServiceCategory $serviceCategory)
    {
        $serviceCategories = ServiceCategory::where('id', '!=', $serviceCategory->id)
            ->orderBy('name')
            ->get();
            
        return view('admin.service-categories.edit', compact('serviceCategory', 'serviceCategories'));
    }
    
    /**
     * Hizmet kategorisi güncelleme
     */
    public function update(Request $request, ServiceCategory $serviceCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:service_categories,id',
            'order' => 'nullable|integer|min:0'
        ]);
        
        // Kendisini parent olarak seçmeyi engelle
        if ($request->parent_id == $serviceCategory->id) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Bir hizmet kategorisi kendisini üst kategori olarak seçemez.');
        }
        
        // Alt kategorileri kendisine parent olarak seçmeyi engelle
        $childIds = $this->getAllChildIds($serviceCategory);
        if ($request->parent_id && in_array($request->parent_id, $childIds)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Bir alt hizmet kategorisi, üst kategori olarak seçilemez.');
        }
        
        // Slug kontrolü
        $newSlug = Str::slug($request->name);
        $existingCategory = ServiceCategory::where('slug', $newSlug)
            ->where('id', '!=', $serviceCategory->id)
            ->first();
            
        if ($existingCategory) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Bu isme sahip başka bir hizmet kategorisi zaten mevcut. Lütfen farklı bir isim seçin.');
        }
        
        // Debug amaçlı log
        \Illuminate\Support\Facades\Log::info('Hizmet kategorisi güncelleme isteği', [
            'id' => $serviceCategory->id,
            'request_has_is_active' => $request->has('is_active'),
            'request_input' => $request->all()
        ]);
        
        $serviceCategory->update([
            'name' => $request->name,
            'slug' => $newSlug,
            'icon' => $request->icon,
            'description' => $request->description,
            'parent_id' => $request->parent_id,
            'order' => $request->order ?? 0,
            'is_active' => $request->has('is_active') ? 1 : 0
        ]);
        
        return redirect()->route('admin.service-categories.index')
            ->with('success', 'Müdürlük kategorisi başarıyla güncellendi.');
    }
    
    /**
     * Müdürlük kategorisi silme
     */
    public function destroy(ServiceCategory $serviceCategory)
    {
        // İlişkili hizmet kontrolü
        if ($serviceCategory->services()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Bu müdürlük kategorisine ait hizmetler bulunduğu için silinemez. Önce hizmetleri başka kategorilere taşıyın veya silin.');
        }
        
        // Alt kategorileri ana kategoriye taşı
        ServiceCategory::where('parent_id', $serviceCategory->id)
            ->update(['parent_id' => $serviceCategory->parent_id]);
            
        $serviceCategory->delete();
        
        return redirect()->route('admin.service-categories.index')
            ->with('success', 'Müdürlük kategorisi başarıyla silindi.');
    }
    
    /**
     * Müdürlük kategorisi sıralama
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'serviceCategories' => 'required|array',
            'serviceCategories.*' => 'exists:service_categories,id'
        ]);
        
        foreach ($request->serviceCategories as $order => $id) {
            ServiceCategory::where('id', $id)->update(['order' => $order + 1]);
        }
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Bir müdürlük kategorisinin tüm alt kategori id'lerini recursive olarak getir
     */
    private function getAllChildIds(ServiceCategory $serviceCategory, array $ids = [])
    {
        $children = ServiceCategory::where('parent_id', $serviceCategory->id)->get();
        
        foreach ($children as $child) {
            $ids[] = $child->id;
            $ids = $this->getAllChildIds($child, $ids);
        }
        
        return $ids;
    }
}
