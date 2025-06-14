<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FooterMenu;
use App\Models\FooterMenuLink;
use Illuminate\Http\Request;

class FooterMenuLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(FooterMenu $footerMenu)
    {
        $links = $footerMenu->links()->ordered()->get();
        $menu = $footerMenu; // View'da $menu değişkeni kullanılıyor
        return view('admin.footer.links.index', compact('menu', 'links'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(FooterMenu $footerMenu)
    {
        $menu = $footerMenu; // View'da $menu değişkeni kullanılıyor
        return view('admin.footer.links.create', compact('menu'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, FooterMenu $footerMenu)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean'
        ]);

        $footerMenu->links()->create([
            'title' => $request->title,
            'url' => $request->url,
            'order' => $request->order,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('admin.footer.menus.links.index', $footerMenu)
                        ->with('success', 'Link başarıyla oluşturuldu.');
    }

    /**
     * Display the specified resource.
     */
    public function show(FooterMenu $footerMenu, FooterMenuLink $link)
    {
        $menu = $footerMenu; // View'da $menu değişkeni kullanılıyor
        return view('admin.footer.links.show', compact('menu', 'link'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FooterMenu $footerMenu, FooterMenuLink $link)
    {
        $menu = $footerMenu; // View'da $menu değişkeni kullanılıyor
        return view('admin.footer.links.edit', compact('menu', 'link'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FooterMenu $footerMenu, FooterMenuLink $link)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean'
        ]);

        $link->update([
            'title' => $request->title,
            'url' => $request->url,
            'order' => $request->order,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('admin.footer.menus.links.index', $footerMenu)
                        ->with('success', 'Link başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FooterMenu $footerMenu, FooterMenuLink $link)
    {
        $link->delete();
        
        return redirect()->route('admin.footer.menus.links.index', $footerMenu)
                        ->with('success', 'Link başarıyla silindi.');
    }

    public function updateOrder(Request $request, FooterMenu $footerMenu)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*' => 'required|integer'
        ]);

        foreach ($request->orders as $id => $order) {
            FooterMenuLink::where('id', $id)
                         ->where('footer_menu_id', $footerMenu->id)
                         ->update(['order' => $order]);
        }

        return response()->json(['success' => true]);
    }

    public function toggleStatus(FooterMenu $footerMenu, FooterMenuLink $link)
    {
        $link->update(['is_active' => !$link->is_active]);
        
        return redirect()->back()->with('success', 'Link durumu güncellendi.');
    }
}
