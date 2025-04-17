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
     * Handle image path processing to ensure correct storage format
     * 
     * @param string|null $imagePath
     * @return string|null
     */
    protected function processImagePath(?string $imagePath): ?string
    {
        if (!$imagePath) {
            return null;
        }
        
        // URL'leri temizle - tam URL şeklinde gelmişse
        if (Str::startsWith($imagePath, ['http://', 'https://'])) {
            $parsedUrl = parse_url($imagePath);
            $path = $parsedUrl['path'] ?? '';
            
            // /storage/ ile başlıyorsa storage/ olarak kaydet
            if (Str::startsWith($path, '/storage/')) {
                return Str::replaceFirst('/storage/', '', $path);
            }
            
            return $path;
        }
        
        // Storage ile başlıyorsa storagei sil
        if (Str::startsWith($imagePath, '/storage/')) {
            return Str::replaceFirst('/storage/', '', $imagePath);
        }
        
        return $imagePath;
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
            'selected_image' => 'nullable|string|max:255',
            'status' => 'required|boolean'
        ]);

        $data = $request->except(['_token', 'selected_image']);
        
        // Slug oluştur
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        
        // Görsel işleme
        if ($request->filled('selected_image')) {
            $data['image'] = $this->processImagePath($request->selected_image);
        }

        CorporateCategory::create($data);

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
            'selected_image' => 'nullable|string|max:255',
            'status' => 'required|boolean'
        ]);

        $data = $request->except(['_token', '_method', 'selected_image']);
        
        // Slug oluştur
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        
        // Görsel işleme
        if ($request->filled('selected_image')) {
            $data['image'] = $this->processImagePath($request->selected_image);
        }

        $category->update($data);

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
