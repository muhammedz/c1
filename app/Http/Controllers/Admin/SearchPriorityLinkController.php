<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SearchPriorityLink;
use Illuminate\Http\Request;

class SearchPriorityLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $priorityLinks = SearchPriorityLink::ordered()->get();
        return view('admin.search_settings.priority_links.index', compact('priorityLinks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.search_settings.priority_links.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'search_keywords' => 'required|string|max:1000',
            'title' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:100',
            'priority' => 'nullable|integer|min:1|max:100',
            'is_active' => 'boolean',
        ], [
            'search_keywords.required' => 'Arama anahtar kelimeleri zorunludur.',
            'search_keywords.max' => 'Arama anahtar kelimeleri en fazla 1000 karakter olabilir.',
            'title.required' => 'Başlık zorunludur.',
            'title.max' => 'Başlık en fazla 255 karakter olabilir.',
            'url.required' => 'URL zorunludur.',
            'url.max' => 'URL en fazla 255 karakter olabilir.',
            'description.max' => 'Açıklama en fazla 500 karakter olabilir.',
            'icon.max' => 'İkon en fazla 100 karakter olabilir.',
            'priority.integer' => 'Öncelik sayısal bir değer olmalıdır.',
            'priority.min' => 'Öncelik en az 1 olmalıdır.',
            'priority.max' => 'Öncelik en fazla 100 olabilir.',
        ]);

        // Eğer öncelik belirtilmemişse, son sıraya ekle
        if (!$request->filled('priority')) {
            $maxPriority = SearchPriorityLink::max('priority') ?? 0;
            $request->merge(['priority' => $maxPriority + 1]);
        }

        SearchPriorityLink::create([
            'search_keywords' => $request->search_keywords,
            'title' => $request->title,
            'url' => $request->url,
            'description' => $request->description,
            'icon' => $request->icon,
            'priority' => $request->priority,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.search-settings.index')
            ->with('success', 'Öncelik linki başarıyla eklendi.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SearchPriorityLink $priorityLink)
    {
        return view('admin.search_settings.priority_links.show', compact('priorityLink'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SearchPriorityLink $priorityLink)
    {
        return view('admin.search_settings.priority_links.edit', compact('priorityLink'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SearchPriorityLink $priorityLink)
    {
        $request->validate([
            'search_keywords' => 'required|string|max:1000',
            'title' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:100',
            'priority' => 'required|integer|min:1|max:100',
            'is_active' => 'boolean',
        ], [
            'search_keywords.required' => 'Arama anahtar kelimeleri zorunludur.',
            'search_keywords.max' => 'Arama anahtar kelimeleri en fazla 1000 karakter olabilir.',
            'title.required' => 'Başlık zorunludur.',
            'title.max' => 'Başlık en fazla 255 karakter olabilir.',
            'url.required' => 'URL zorunludur.',
            'url.max' => 'URL en fazla 255 karakter olabilir.',
            'description.max' => 'Açıklama en fazla 500 karakter olabilir.',
            'icon.max' => 'İkon en fazla 100 karakter olabilir.',
            'priority.required' => 'Öncelik zorunludur.',
            'priority.integer' => 'Öncelik sayısal bir değer olmalıdır.',
            'priority.min' => 'Öncelik en az 1 olmalıdır.',
            'priority.max' => 'Öncelik en fazla 100 olabilir.',
        ]);

        $priorityLink->update([
            'search_keywords' => $request->search_keywords,
            'title' => $request->title,
            'url' => $request->url,
            'description' => $request->description,
            'icon' => $request->icon,
            'priority' => $request->priority,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.search-settings.index')
            ->with('success', 'Öncelik linki başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SearchPriorityLink $priorityLink)
    {
        $priorityLink->delete();

        return redirect()->route('admin.search-settings.index')
            ->with('success', 'Öncelik linki başarıyla silindi.');
    }

    /**
     * Sıralamayı güncelle
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:search_priority_links,id',
            'items.*.priority' => 'required|integer|min:1',
        ]);

        foreach ($request->items as $item) {
            SearchPriorityLink::where('id', $item['id'])->update(['priority' => $item['priority']]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Aktiflik durumunu değiştir
     */
    public function toggleActive(SearchPriorityLink $priorityLink)
    {
        $priorityLink->update([
            'is_active' => !$priorityLink->is_active
        ]);

        return response()->json([
            'success' => true,
            'is_active' => $priorityLink->is_active
        ]);
    }
}
