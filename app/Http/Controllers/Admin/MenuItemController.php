<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MenuCategory;
use App\Models\MenuItem;

class MenuItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:menu_categories,id'
        ]);

        $category = MenuCategory::with('items')->findOrFail($request->category_id);
        $items = $category->items()->orderBy('order')->get();
        $menu = $category->menusystem;

        return view('admin.menusystem.menu-items.index', compact('category', 'items', 'menu'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:menu_categories,id'
        ]);

        $category = MenuCategory::findOrFail($request->category_id);
        $menu = $category->menusystem;

        return view('admin.menusystem.menu-items.create', compact('category', 'menu'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:menu_categories,id',
            'name' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'icon' => 'required|string|max:50',
            'status' => 'boolean'
        ]);

        $category = MenuCategory::findOrFail($request->category_id);
        
        $category->items()->create([
            'name' => $request->name,
            'url' => $request->url,
            'icon' => $request->icon,
            'order' => $category->items()->count() + 1,
            'status' => $request->has('status') ? 1 : 0
        ]);

        return redirect()->route('menusystem.items.index', ['category_id' => $category->id])
            ->with('success', 'Alt menü başarıyla oluşturuldu.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $item = MenuItem::findOrFail($id);
        $category = $item->category;
        $menu = $category->menusystem;

        return view('admin.menusystem.menu-items.edit', compact('item', 'category', 'menu'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'icon' => 'required|string|max:50',
            'status' => 'boolean'
        ]);

        $item = MenuItem::findOrFail($id);
        
        $item->update([
            'name' => $request->name,
            'url' => $request->url,
            'icon' => $request->icon,
            'status' => $request->has('status') ? 1 : 0
        ]);

        return redirect()->route('menusystem.items.index', ['category_id' => $item->menu_category_id])
            ->with('success', 'Alt menü başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = MenuItem::findOrFail($id);
        $categoryId = $item->menu_category_id;
        
        $item->delete();

        return redirect()->route('menusystem.items.index', ['category_id' => $categoryId])
            ->with('success', 'Alt menü başarıyla silindi.');
    }

    /**
     * Update the order of items.
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:menu_items,id',
            'items.*.order' => 'required|integer|min:0',
        ]);

        foreach ($request->items as $itemData) {
            MenuItem::findOrFail($itemData['id'])->update([
                'order' => $itemData['order']
            ]);
        }

        return response()->json(['success' => true]);
    }
    
    /**
     * Get material icons list.
     */
    public function getIcons()
    {
        // Google Material Icons listesi
        $icons = [
            'home', 'person', 'settings', 'email', 'phone', 'calendar_today', 'description',
            'attach_money', 'credit_card', 'shopping_cart', 'store', 'local_offer',
            'location_on', 'directions', 'public', 'flight', 'hotel', 'restaurant',
            'local_cafe', 'school', 'business', 'account_balance', 'work', 'build',
            'apps', 'dashboard', 'article', 'list', 'folder', 'backup', 'cloud',
            'photo', 'music_note', 'movie', 'games', 'sports', 'fitness_center',
            'announcement', 'help', 'info', 'warning', 'error', 'search', 'link'
        ];

        return response()->json($icons);
    }
}
