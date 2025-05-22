<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SearchQuickLink;
use App\Models\SearchSetting;
use Illuminate\Http\Request;

class SearchQuickLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $quickLinks = SearchQuickLink::orderBy('order')->get();
        return view('admin.search_settings.quick_links.index', compact('quickLinks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.search_settings.quick_links.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Gelen verileri log'a yazdır
            \Log::info('Quick Link Request Data', $request->all());
            
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'url' => 'required|string|max:255',
                'order' => 'nullable|integer|min:0',
                'is_active' => 'nullable',
            ], [
                'is_active.boolean' => 'Is active sadece doğru veya yanlış olmalıdır.',
            ]);
            
            \Log::info('Validated Data', $validated);
            
            // Eğer sıra belirtilmemişse, son sıraya ekleyin
            if (!$request->filled('order')) {
                $maxOrder = SearchQuickLink::max('order') ?? 0;
                $request->merge(['order' => $maxOrder + 1]);
            }
            
            $quickLink = SearchQuickLink::create([
                'title' => $request->title,
                'url' => $request->url,
                'order' => $request->order,
                'is_active' => $request->has('is_active') ? true : false,
            ]);
            
            \Log::info('Created Quick Link', ['id' => $quickLink->id]);
            
            return redirect()->route('admin.search-settings.index')
                ->with('success', 'Hızlı arama bağlantısı başarıyla eklendi.');
        } catch (\Exception $e) {
            \Log::error('Error creating Quick Link: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Bir hata oluştu: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SearchQuickLink $quickLink)
    {
        return view('admin.search_settings.quick_links.edit', compact('quickLink'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SearchQuickLink $quickLink)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);
        
        $quickLink->update([
            'title' => $request->title,
            'url' => $request->url,
            'order' => $request->order,
            'is_active' => $request->has('is_active'),
        ]);
        
        return redirect()->route('admin.search-settings.index')
            ->with('success', 'Hızlı arama bağlantısı başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SearchQuickLink $quickLink)
    {
        $quickLink->delete();
        
        return redirect()->route('admin.search-settings.index')
            ->with('success', 'Hızlı arama bağlantısı başarıyla silindi.');
    }
    
    /**
     * Sıralamayı güncelle
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:search_quick_links,id',
            'items.*.order' => 'required|integer|min:0',
        ]);
        
        foreach ($request->items as $item) {
            SearchQuickLink::where('id', $item['id'])->update(['order' => $item['order']]);
        }
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Aktiflik durumunu değiştir
     */
    public function toggleActive(SearchQuickLink $quickLink)
    {
        $quickLink->update([
            'is_active' => !$quickLink->is_active
        ]);
        
        return response()->json([
            'success' => true,
            'is_active' => $quickLink->is_active
        ]);
    }
}
