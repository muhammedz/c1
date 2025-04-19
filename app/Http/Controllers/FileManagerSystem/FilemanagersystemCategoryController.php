<?php

namespace App\Http\Controllers\FileManagerSystem;

use App\Http\Controllers\Controller;
use App\Models\FileManagerSystem\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Services\FileManagerSystem\FilemanagersystemCategoryService;

class FilemanagersystemCategoryController extends Controller
{
    /**
     * Kategori listesini görüntüler.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::withCount('medias')->get();
        return view('filemanagersystem.categories.index', compact('categories'));
    }

    /**
     * Yeni kategori oluşturma formunu gösterir.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        return view('filemanagersystem.categories.create', compact('categories'));
    }

    /**
     * Yeni kategoriyi veritabanına kaydeder.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'slug' => 'nullable|max:255|unique:filemanagersystem_categories,slug',
            'parent_id' => 'nullable|exists:filemanagersystem_categories,id',
        ]);

        $category = new Category();
        $category->name = $request->name;
        $category->slug = $request->slug ?: Str::slug($request->name);
        $category->description = $request->description;
        $category->parent_id = $request->parent_id;
        $category->is_active = $request->has('is_active') ? 1 : 0;
        $category->created_by = Auth::id();
        $category->save();

        return redirect()->route('admin.filemanagersystem.categories.index')
            ->with('success', 'Kategori başarıyla oluşturuldu.');
    }

    /**
     * Belirli bir kategoriyi görüntüler.
     *
     * @param  \App\Models\FileManagerSystem\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return view('filemanagersystem.categories.show', compact('category'));
    }

    /**
     * Kategori düzenleme formunu gösterir.
     *
     * @param  \App\Models\FileManagerSystem\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        $categories = Category::where('id', '!=', $category->id)->get();
        return view('filemanagersystem.categories.edit', compact('category', 'categories'));
    }

    /**
     * Belirli bir kategoriyi günceller.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FileManagerSystem\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|max:255',
            'slug' => 'nullable|max:255|unique:filemanagersystem_categories,slug,' . $category->id,
            'parent_id' => 'nullable|exists:filemanagersystem_categories,id',
        ]);

        // Kendisini kendi üst kategorisi olarak atamayı önle
        if ($request->parent_id == $category->id) {
            return back()->withErrors(['parent_id' => 'Bir kategori kendisinin üst kategorisi olamaz']);
        }

        // Döngüye neden olması durumunu önle
        if ($request->parent_id && $this->wouldCreateLoop($category->id, $request->parent_id)) {
            return back()->withErrors(['parent_id' => 'Bu kategori seçimi döngüye sebep olacak']);
        }

        $category->name = $request->name;
        $category->slug = $request->slug ?: Str::slug($request->name);
        $category->description = $request->description;
        $category->parent_id = $request->parent_id;
        $category->is_active = $request->has('is_active') ? 1 : 0;
        $category->save();

        return redirect()->route('admin.filemanagersystem.categories.index')
            ->with('success', 'Kategori başarıyla güncellendi.');
    }

    /**
     * Belirli bir kategoriyi veritabanından siler.
     *
     * @param  \App\Models\FileManagerSystem\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        // Medya dosyaları kontrol edilebilir, varsa bağlantılar kaldırılabilir
        // veya hata dönülebilir
        if ($category->medias()->count() > 0) {
            return back()->withErrors(['error' => 'Bu kategoriye bağlı dosyalar var. Önce bu dosyaları başka bir kategoriye taşıyın veya kategori bağlantılarını kaldırın.']);
        }

        // Alt kategorilerin parent_id değerlerini güncelle
        Category::where('parent_id', $category->id)
            ->update(['parent_id' => $category->parent_id]);

        $category->delete();

        return redirect()->route('admin.filemanagersystem.categories.index')
            ->with('success', 'Kategori başarıyla silindi.');
    }

    /**
     * Belirli bir kategorinin medya dosyalarını listeler.
     *
     * @param  \App\Models\FileManagerSystem\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function medias(Category $category)
    {
        $medias = $category->medias()->paginate(20);
        return view('filemanagersystem.categories.medias', compact('category', 'medias'));
    }

    /**
     * İlişki ağacında bir döngü oluşturup oluşturmayacağını kontrol eder.
     *
     * @param  int  $categoryId
     * @param  int  $parentId
     * @return bool
     */
    private function wouldCreateLoop($categoryId, $parentId)
    {
        // Başlangıçta kontrol edilecek parent'ı ayarla
        $currentParentId = $parentId;
        
        // Ziyaret edilen tüm parent_id'leri takip et
        $visitedParents = [];
        
        // Maksimum derinlik limiti (güvenlik için)
        $maxDepth = 100;
        $depth = 0;
        
        // Sonsuz döngüyü önlemek için maksimum derinliği kontrol et
        while ($currentParentId !== null && $depth < $maxDepth) {
            // Eğer bu parent_id zaten ziyaret edildiyse, döngü var demektir
            if (in_array($currentParentId, $visitedParents)) {
                return true;
            }
            
            // Eğer şu anki parent, aslında bizim kategorimizse, döngü var demektir
            if ($currentParentId == $categoryId) {
                return true;
            }
            
            // Bu parent_id'yi ziyaret edilenlere ekle
            $visitedParents[] = $currentParentId;
            
            // Bu parent'ın kendi parent'ını bul
            $parentCategory = Category::find($currentParentId);
            $currentParentId = $parentCategory ? $parentCategory->parent_id : null;
            
            $depth++;
        }
        
        // Eğer buraya kadar geldiyse, döngü bulunmamış demektir
        return false;
    }
}
