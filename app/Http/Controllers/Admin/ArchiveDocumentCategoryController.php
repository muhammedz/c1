<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ArchiveDocumentCategory;
use App\Models\Archive;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArchiveDocumentCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'archive_id' => 'required|exists:archives,id',
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'order' => 'nullable|integer|min:0',
        ]);

        $data = $request->all();
        
        // Slug oluştur
        $data['slug'] = Str::slug($data['name']);
        
        // Benzersiz slug kontrolü (aynı arşiv içinde)
        $originalSlug = $data['slug'];
        $counter = 1;
        while (ArchiveDocumentCategory::where('archive_id', $data['archive_id'])
                                      ->where('slug', $data['slug'])
                                      ->exists()) {
            $data['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Otomatik sıra numarası (en son sıra + 1)
        if (!isset($data['order']) || $data['order'] == 0) {
            $maxOrder = ArchiveDocumentCategory::where('archive_id', $data['archive_id'])->max('order') ?? 0;
            $data['order'] = $maxOrder + 1;
        }

        $category = ArchiveDocumentCategory::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Kategori başarıyla oluşturuldu.',
            'category' => $category
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(ArchiveDocumentCategory $archiveDocumentCategory)
    {
        return response()->json($archiveDocumentCategory);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ArchiveDocumentCategory $archiveDocumentCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ArchiveDocumentCategory $archiveDocumentCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'order' => 'nullable|integer|min:0',
        ]);

        $data = $request->all();
        
        // Slug oluştur
        $data['slug'] = Str::slug($data['name']);
        
        // Benzersiz slug kontrolü (aynı arşiv içinde, mevcut kategori hariç)
        $originalSlug = $data['slug'];
        $counter = 1;
        while (ArchiveDocumentCategory::where('archive_id', $archiveDocumentCategory->archive_id)
                                      ->where('slug', $data['slug'])
                                      ->where('id', '!=', $archiveDocumentCategory->id)
                                      ->exists()) {
            $data['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        $archiveDocumentCategory->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Kategori başarıyla güncellendi.',
            'category' => $archiveDocumentCategory
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ArchiveDocumentCategory $archiveDocumentCategory)
    {
        // İlişkili belgeleri kontrol et
        if ($archiveDocumentCategory->documents()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Bu kategoriye ait belgeler var. Önce belgeleri başka kategoriye taşıyın veya silin.'
            ], 422);
        }

        $archiveDocumentCategory->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kategori başarıyla silindi.'
        ]);
    }

    /**
     * Get categories for specific archive
     */
    public function getArchiveCategories(Archive $archive)
    {
        $categories = $archive->documentCategories()
                             ->withCount('documents')
                             ->get();

        return response()->json($categories);
    }

    /**
     * Update category order
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'categories' => 'required|array',
            'categories.*.id' => 'required|exists:archive_document_categories,id',
            'categories.*.order' => 'required|integer|min:1',
        ]);

        foreach ($request->categories as $categoryData) {
            ArchiveDocumentCategory::where('id', $categoryData['id'])
                                  ->update(['order' => $categoryData['order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Kategori sıralaması başarıyla güncellendi.'
        ]);
    }
}
