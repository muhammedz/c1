<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Archive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ArchiveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Archive::with('user')->withCount('documents');

        // Arama
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->search($search);
        }

        // Durum filtresi
        if ($request->filled('status')) {
            $query->status($request->get('status'));
        }

        // Sıralama
        $query->orderBy('created_at', 'desc');

        $archives = $query->paginate(15);

        return view('admin.archives.index', compact('archives'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.archives.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:archives,slug,NULL,id,deleted_at,NULL',
            'content' => 'nullable|string',
            'excerpt' => 'nullable|string|max:500',
            'status' => 'required|in:draft,published,archived',
            'is_featured' => 'boolean',
            'show_download_button' => 'boolean',
            'download_button_text' => 'nullable|string|max:255',
            'download_button_url' => 'nullable|url|max:500',
            'published_at' => 'nullable|date',
        ], [
            'slug.unique' => 'Bu slug zaten kullanılıyor. Lütfen farklı bir slug girin.',
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id();

        // Slug oluşturma
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        } else {
            $data['slug'] = Str::slug($data['slug']);
        }

        // Yayın tarihi ayarlama
        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        $archive = Archive::create($data);

        return redirect()->route('admin.archives.edit', $archive)
                        ->with('success', 'Arşiv başarıyla oluşturuldu.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Archive $archive)
    {
        $archive->load(['user', 'documents']);
        return view('admin.archives.show', compact('archive'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Archive $archive)
    {
        $archive->load(['allDocuments.category', 'documentCategories']);
        return view('admin.archives.edit', compact('archive'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Archive $archive)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:archives,slug,' . $archive->id . ',id,deleted_at,NULL',
            'content' => 'nullable|string',
            'excerpt' => 'nullable|string|max:500',
            'status' => 'required|in:draft,published,archived',
            'is_featured' => 'boolean',
            'show_download_button' => 'boolean',
            'download_button_text' => 'nullable|string|max:255',
            'download_button_url' => 'nullable|url|max:500',
            'published_at' => 'nullable|date',
        ], [
            'slug.unique' => 'Bu slug zaten kullanılıyor. Lütfen farklı bir slug girin.',
            'slug.required' => 'Slug alanı zorunludur.',
        ]);

        $data = $request->all();

        // Slug temizleme
        if (!empty($data['slug'])) {
            $data['slug'] = Str::slug($data['slug']);
        }

        // Yayın tarihi ayarlama
        if ($data['status'] === 'published' && empty($data['published_at']) && $archive->status !== 'published') {
            $data['published_at'] = now();
        }

        $archive->update($data);

        return redirect()->route('admin.archives.edit', $archive)
                        ->with('success', 'Arşiv başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Archive $archive)
    {
        $archive->delete();

        return redirect()->route('admin.archives.index')
                        ->with('success', 'Arşiv başarıyla silindi.');
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore($id)
    {
        $archive = Archive::withTrashed()->findOrFail($id);
        $archive->restore();

        return redirect()->route('admin.archives.index')
                        ->with('success', 'Arşiv başarıyla geri yüklendi.');
    }

    /**
     * Permanently delete the specified resource from storage.
     */
    public function forceDelete($id)
    {
        $archive = Archive::withTrashed()->findOrFail($id);
        
        // İlişkili belgeleri de sil
        foreach ($archive->allDocuments as $document) {
            if ($document->fileExists()) {
                unlink(public_path($document->file_path));
            }
        }
        
        $archive->forceDelete();

        return redirect()->route('admin.archives.index')
                        ->with('success', 'Arşiv kalıcı olarak silindi.');
    }

    /**
     * Bulk actions
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,publish,unpublish,feature,unfeature',
            'selected' => 'required|array|min:1',
            'selected.*' => 'exists:archives,id'
        ]);

        $archives = Archive::whereIn('id', $request->selected);

        switch ($request->action) {
            case 'delete':
                $archives->delete();
                $message = 'Seçili arşivler silindi.';
                break;
            case 'publish':
                $archives->update(['status' => 'published', 'published_at' => now()]);
                $message = 'Seçili arşivler yayınlandı.';
                break;
            case 'unpublish':
                $archives->update(['status' => 'draft']);
                $message = 'Seçili arşivler taslağa alındı.';
                break;
            case 'feature':
                $archives->update(['is_featured' => true]);
                $message = 'Seçili arşivler öne çıkarıldı.';
                break;
            case 'unfeature':
                $archives->update(['is_featured' => false]);
                $message = 'Seçili arşivler öne çıkarmadan kaldırıldı.';
                break;
        }

        return redirect()->route('admin.archives.index')
                        ->with('success', $message);
    }
}
