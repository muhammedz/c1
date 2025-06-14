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
        $links = $footerMenu->links()->orderByRaw('LOWER(title) COLLATE utf8mb4_turkish_ci ASC')->get(); // Türkçe alfabetik sıralama
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
        // Debug bilgisi ekle
        \Illuminate\Support\Facades\Log::info('FooterMenuLink Store Debug: ', [
            'request_all' => $request->all(),
            'menu_id' => $footerMenu->id,
            'request_method' => $request->method(),
            'request_url' => $request->url()
        ]);

        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'url' => 'required|string|max:255',
                'order' => 'required|integer|min:0',
                'is_active' => 'nullable|in:on,1,true'
            ]);

            $linkData = [
                'title' => $request->title,
                'url' => $request->url,
                'order' => $request->order,
                'is_active' => $request->has('is_active')
            ];

            \Illuminate\Support\Facades\Log::info('FooterMenuLink Create Data: ', $linkData);

            $link = $footerMenu->links()->create($linkData);

            \Illuminate\Support\Facades\Log::info('FooterMenuLink Created: ', [
                'link_id' => $link->id,
                'link_data' => $link->toArray()
            ]);

            return redirect()->route('admin.footer.menus.links.index', $footerMenu)
                            ->with('success', 'Link başarıyla oluşturuldu.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Illuminate\Support\Facades\Log::error('FooterMenuLink Store Validation Error: ', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);

            return redirect()->back()
                            ->withErrors($e->errors())
                            ->withInput()
                            ->with('error', 'Form verilerinde hata var.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('FooterMenuLink Store Error: ', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                            ->withInput()
                            ->with('error', 'Link oluşturulurken bir hata oluştu: ' . $e->getMessage());
        }
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
        // Debug bilgisi
        \Illuminate\Support\Facades\Log::info('FooterMenuLink Edit Debug: ', [
            'menu_id' => $footerMenu->id,
            'link_id' => $link->id,
            'link_data' => $link->toArray(),
            'link_belongs_to_menu' => $link->footer_menu_id === $footerMenu->id
        ]);

        $menu = $footerMenu; // View'da $menu değişkeni kullanılıyor
        return view('admin.footer.links.edit', compact('menu', 'link'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FooterMenu $footerMenu, FooterMenuLink $link)
    {
        // Link'in doğru menu'ye ait olup olmadığını kontrol et
        if ($link->footer_menu_id !== $footerMenu->id) {
            \Illuminate\Support\Facades\Log::error('FooterMenuLink Update - Link does not belong to menu: ', [
                'link_id' => $link->id,
                'link_menu_id' => $link->footer_menu_id,
                'expected_menu_id' => $footerMenu->id
            ]);
            
            return redirect()->route('admin.footer.menus.links.index', $footerMenu)
                            ->with('error', 'Link bu menüye ait değil.');
        }

        // Debug bilgisi
        \Illuminate\Support\Facades\Log::info('FooterMenuLink Update Debug: ', [
            'request_all' => $request->all(),
            'link_id' => $link->id,
            'menu_id' => $footerMenu->id,
            'link_before' => $link->toArray(),
            'link_belongs_to_menu' => $link->footer_menu_id === $footerMenu->id
        ]);

        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'url' => 'required|string|max:255',
                'order' => 'required|integer|min:0',
                'is_active' => 'nullable|in:on,1,true'
            ]);

            \Illuminate\Support\Facades\Log::info('FooterMenuLink Validation Passed: ', $validated);
            $updateData = [
                'title' => $request->title,
                'url' => $request->url,
                'order' => $request->order,
                'is_active' => $request->has('is_active')
            ];

            \Illuminate\Support\Facades\Log::info('FooterMenuLink Update Data: ', $updateData);

            $result = $link->update($updateData);

            \Illuminate\Support\Facades\Log::info('FooterMenuLink Update Result: ', [
                'result' => $result,
                'link_after' => $link->fresh()->toArray()
            ]);

            return redirect()->route('admin.footer.menus.links.index', $footerMenu)
                            ->with('success', 'Link başarıyla güncellendi.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Illuminate\Support\Facades\Log::error('FooterMenuLink Validation Error: ', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);

            return redirect()->back()
                            ->withErrors($e->errors())
                            ->withInput()
                            ->with('error', 'Form verilerinde hata var.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('FooterMenuLink Update Error: ', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                            ->withInput()
                            ->with('error', 'Link güncellenirken bir hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FooterMenu $footerMenu, FooterMenuLink $link)
    {
        // Link'in doğru menu'ye ait olup olmadığını kontrol et
        if ($link->footer_menu_id !== $footerMenu->id) {
            \Illuminate\Support\Facades\Log::error('FooterMenuLink Destroy - Link does not belong to menu: ', [
                'link_id' => $link->id,
                'link_menu_id' => $link->footer_menu_id,
                'expected_menu_id' => $footerMenu->id
            ]);
            
            return redirect()->route('admin.footer.menus.links.index', $footerMenu)
                            ->with('error', 'Link bu menüye ait değil.');
        }

        try {
            \Illuminate\Support\Facades\Log::info('FooterMenuLink Destroy: ', [
                'menu_id' => $footerMenu->id,
                'link_id' => $link->id,
                'link_title' => $link->title
            ]);

            $linkTitle = $link->title;
            $link->delete();

            return redirect()->route('admin.footer.menus.links.index', $footerMenu)
                            ->with('success', "'{$linkTitle}' linki başarıyla silindi.");
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('FooterMenuLink Destroy Error: ', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('admin.footer.menus.links.index', $footerMenu)
                            ->with('error', 'Link silinirken bir hata oluştu: ' . $e->getMessage());
        }
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
