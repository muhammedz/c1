<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SearchPopularQuery;
use Illuminate\Http\Request;

class SearchPopularQueryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $popularQueries = SearchPopularQuery::orderBy('order')->get();
        return view('admin.search_settings.popular_queries.index', compact('popularQueries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.search_settings.popular_queries.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Gelen verileri log'a yazdır
            \Log::info('Popular Query Request Data', $request->all());
            
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'url' => 'required|string|max:255',
                'icon' => 'nullable|string|max:50',
                'order' => 'nullable|integer|min:0',
                'is_active' => 'nullable',
            ], [
                'is_active.boolean' => 'Is active sadece doğru veya yanlış olmalıdır.',
            ]);
            
            \Log::info('Validated Data', $validated);
            
            // Eğer sıra belirtilmemişse, son sıraya ekleyin
            if (!$request->filled('order')) {
                $maxOrder = SearchPopularQuery::max('order') ?? 0;
                $request->merge(['order' => $maxOrder + 1]);
            }
            
            $popularQuery = SearchPopularQuery::create([
                'title' => $request->title,
                'url' => $request->url,
                'icon' => $request->icon,
                'order' => $request->order,
                'is_active' => $request->has('is_active') ? true : false,
            ]);
            
            \Log::info('Created Popular Query', ['id' => $popularQuery->id]);
            
            return redirect()->route('admin.search-settings.index')
                ->with('success', 'Popüler arama sorgusu başarıyla eklendi.');
        } catch (\Exception $e) {
            \Log::error('Error creating Popular Query: ' . $e->getMessage());
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
    public function edit(SearchPopularQuery $popularQuery)
    {
        return view('admin.search_settings.popular_queries.edit', compact('popularQuery'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SearchPopularQuery $popularQuery)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'icon' => 'nullable|string|max:50',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);
        
        $popularQuery->update([
            'title' => $request->title,
            'url' => $request->url,
            'icon' => $request->icon,
            'order' => $request->order,
            'is_active' => $request->has('is_active'),
        ]);
        
        return redirect()->route('admin.search-settings.index')
            ->with('success', 'Popüler arama sorgusu başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SearchPopularQuery $popularQuery)
    {
        $popularQuery->delete();
        
        return redirect()->route('admin.search-settings.index')
            ->with('success', 'Popüler arama sorgusu başarıyla silindi.');
    }
    
    /**
     * Sıralamayı güncelle
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:search_popular_queries,id',
            'items.*.order' => 'required|integer|min:0',
        ]);
        
        foreach ($request->items as $item) {
            SearchPopularQuery::where('id', $item['id'])->update(['order' => $item['order']]);
        }
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Aktiflik durumunu değiştir
     */
    public function toggleActive(SearchPopularQuery $popularQuery)
    {
        $popularQuery->update([
            'is_active' => !$popularQuery->is_active
        ]);
        
        return response()->json([
            'success' => true,
            'is_active' => $popularQuery->is_active
        ]);
    }
    
    /**
     * Material Icons listesini döndür (JSON)
     */
    public function getIcons()
    {
        // Popüler Material Design ikonları
        $icons = [
            'search', 'home', 'mail', 'notifications', 'local_phone', 'person', 'settings',
            'help', 'info', 'warning', 'error', 'favorite', 'check_circle', 'cancel',
            'add', 'edit', 'delete', 'save', 'print', 'share', 'upload', 'download',
            'cloud', 'folder', 'access_time', 'event', 'location_on', 'navigation',
            'description', 'picture_as_pdf', 'image', 'video_library', 'music_note',
            'local_offer', 'shopping_cart', 'local_shipping', 'payment', 'build',
            'school', 'work', 'trending_up', 'equalizer', 'bar_chart', 'construction',
            'restaurant', 'local_cafe', 'local_bar', 'hotel', 'flight', 'directions_bus',
            'directions_car', 'train', 'local_taxi', 'directions_walk', 'local_hospital',
            'local_pharmacy', 'water_drop', 'waves', 'park', 'ac_unit', 'eco',
            'security', 'visibility', 'phone_android', 'laptop', 'desktop_windows',
            'keyboard', 'mouse', 'wifi', 'network_wifi', 'bluetooth', 'battery_charging_full',
            'bolt', 'lightbulb'
        ];
        
        return response()->json(['icons' => $icons]);
    }
}
