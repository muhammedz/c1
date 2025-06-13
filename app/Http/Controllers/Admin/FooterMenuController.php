<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FooterMenu;
use Illuminate\Http\Request;

class FooterMenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menus = FooterMenu::with('links')->ordered()->get();
        return view('admin.footer.menus.index', compact('menus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.footer.menus.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean'
        ]);

        FooterMenu::create([
            'title' => $request->title,
            'order' => $request->order,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('admin.footer.menus.index')
                        ->with('success', 'Menü başarıyla oluşturuldu.');
    }

    /**
     * Display the specified resource.
     */
    public function show(FooterMenu $footerMenu)
    {
        $footerMenu->load('links');
        return view('admin.footer.menus.show', compact('footerMenu'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FooterMenu $footerMenu)
    {
        return view('admin.footer.menus.edit', compact('footerMenu'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FooterMenu $footerMenu)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean'
        ]);

        $footerMenu->update([
            'title' => $request->title,
            'order' => $request->order,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('admin.footer.menus.index')
                        ->with('success', 'Menü başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FooterMenu $footerMenu)
    {
        $footerMenu->delete();
        
        return redirect()->route('admin.footer.menus.index')
                        ->with('success', 'Menü başarıyla silindi.');
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*' => 'required|integer'
        ]);

        foreach ($request->orders as $id => $order) {
            FooterMenu::where('id', $id)->update(['order' => $order]);
        }

        return response()->json(['success' => true]);
    }

    public function toggleStatus(FooterMenu $footerMenu)
    {
        $footerMenu->update(['is_active' => !$footerMenu->is_active]);
        
        return redirect()->back()->with('success', 'Menü durumu güncellendi.');
    }
}
