<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MenuSystem;
use App\Models\MenuCategory;

class MenuCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([
            'menusystem_id' => 'required|exists:menusystem,id'
        ]);

        $menu = MenuSystem::with('categories')->findOrFail($request->menusystem_id);
        $categories = $menu->categories()->orderBy('order')->get();

        return view('admin.menusystem.menu-categories.index', compact('menu', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $request->validate([
            'menusystem_id' => 'required|exists:menusystem,id'
        ]);

        $menu = MenuSystem::findOrFail($request->menusystem_id);
        return view('admin.menusystem.menu-categories.create', compact('menu'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'menusystem_id' => 'required|exists:menusystem,id',
            'name' => 'required|string|max:255',
            'url' => 'nullable|string|max:255',
            'status' => 'boolean'
        ]);

        $menu = MenuSystem::findOrFail($request->menusystem_id);
        
        $menu->categories()->create([
            'name' => $request->name,
            'url' => $request->url,
            'order' => $menu->categories()->count() + 1,
            'status' => $request->has('status') ? 1 : 0
        ]);

        return redirect()->route('menusystem.categories.index', ['menusystem_id' => $menu->id])
            ->with('success', 'Alt başlık başarıyla oluşturuldu.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = MenuCategory::findOrFail($id);
        $menu = $category->menusystem;

        return view('admin.menusystem.menu-categories.edit', compact('category', 'menu'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'nullable|string|max:255',
            'status' => 'boolean'
        ]);

        $category = MenuCategory::findOrFail($id);
        
        $category->update([
            'name' => $request->name,
            'url' => $request->url,
            'status' => $request->has('status') ? 1 : 0
        ]);

        return redirect()->route('menusystem.categories.index', ['menusystem_id' => $category->menusystem_id])
            ->with('success', 'Alt başlık başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = MenuCategory::findOrFail($id);
        $menuId = $category->menusystem_id;
        
        $category->delete();

        return redirect()->route('menusystem.categories.index', ['menusystem_id' => $menuId])
            ->with('success', 'Alt başlık başarıyla silindi.');
    }

    /**
     * Update the order of categories.
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'categories' => 'required|array',
            'categories.*.id' => 'required|exists:menu_categories,id',
            'categories.*.order' => 'required|integer|min:0',
        ]);

        foreach ($request->categories as $categoryData) {
            MenuCategory::findOrFail($categoryData['id'])->update([
                'order' => $categoryData['order']
            ]);
        }

        return response()->json(['success' => true]);
    }
}
