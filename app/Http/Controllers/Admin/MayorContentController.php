<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mayor;
use App\Models\MayorContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MayorContentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $mayor = Mayor::first();
        if (!$mayor) {
            return redirect()->route('admin.mayor.index')
                ->with('error', 'Önce başkan bilgilerini oluşturun.');
        }

        $type = $request->get('type', 'story');
        $contents = MayorContent::where('mayor_id', $mayor->id)
            ->where('type', $type)
            ->orderBy('sort_order')
            ->paginate(20);

        return view('admin.mayor-content.index', compact('contents', 'type', 'mayor'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $mayor = Mayor::first();
        if (!$mayor) {
            return redirect()->route('admin.mayor.index')
                ->with('error', 'Önce başkan bilgilerini oluşturun.');
        }

        $type = $request->get('type', 'story');
        return view('admin.mayor-content.create', compact('type', 'mayor'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $mayor = Mayor::first();
        if (!$mayor) {
            return redirect()->route('admin.mayor.index')
                ->with('error', 'Önce başkan bilgilerini oluşturun.');
        }

        $request->validate([
            'type' => 'required|in:story,agenda,value,gallery',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'extra_data' => 'nullable|array',
            'sort_order' => 'nullable|integer',
        ]);

        $data = $request->except(['image']);
        $data['mayor_id'] = $mayor->id;

        // Görsel yükleme
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('mayor/content', 'public');
        }

        // Sort order belirleme
        if (!$data['sort_order']) {
            $maxOrder = MayorContent::where('mayor_id', $mayor->id)
                ->where('type', $request->type)
                ->max('sort_order');
            $data['sort_order'] = $maxOrder + 1;
        }

        MayorContent::create($data);

        return redirect()->route('admin.mayor-content.index', ['type' => $request->type])
            ->with('success', 'İçerik başarıyla oluşturuldu.');
    }

    /**
     * Display the specified resource.
     */
    public function show(MayorContent $mayorContent)
    {
        return view('admin.mayor-content.show', compact('mayorContent'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MayorContent $mayorContent)
    {
        return view('admin.mayor-content.edit', compact('mayorContent'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MayorContent $mayorContent)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'extra_data' => 'nullable|array',
            'sort_order' => 'nullable|integer',
        ]);

        $data = $request->except(['image']);

        // Görsel yükleme
        if ($request->hasFile('image')) {
            // Eski görseli sil
            if ($mayorContent->image && Storage::disk('public')->exists($mayorContent->image)) {
                Storage::disk('public')->delete($mayorContent->image);
            }
            
            $data['image'] = $request->file('image')->store('mayor/content', 'public');
        }

        $mayorContent->update($data);

        return redirect()->route('admin.mayor-content.index', ['type' => $mayorContent->type])
            ->with('success', 'İçerik başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MayorContent $mayorContent)
    {
        $type = $mayorContent->type;
        
        // Görseli sil
        if ($mayorContent->image && Storage::disk('public')->exists($mayorContent->image)) {
            Storage::disk('public')->delete($mayorContent->image);
        }

        $mayorContent->delete();

        return redirect()->route('admin.mayor-content.index', ['type' => $type])
            ->with('success', 'İçerik başarıyla silindi.');
    }

    /**
     * İçeriği aktif/pasif yap
     */
    public function toggleStatus(MayorContent $mayorContent)
    {
        $mayorContent->update(['is_active' => !$mayorContent->is_active]);
        
        $status = $mayorContent->is_active ? 'aktif' : 'pasif';
        
        return response()->json([
            'success' => true,
            'message' => "İçerik {$status} hale getirildi.",
            'is_active' => $mayorContent->is_active
        ]);
    }

    /**
     * Sıralamayı güncelle
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:mayor_content,id',
            'items.*.sort_order' => 'required|integer',
        ]);

        foreach ($request->items as $item) {
            MayorContent::where('id', $item['id'])
                ->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Sıralama başarıyla güncellendi.'
        ]);
    }

    /**
     * Toplu işlemler
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'items' => 'required|array',
            'items.*' => 'exists:mayor_content,id',
        ]);

        $contents = MayorContent::whereIn('id', $request->items);

        switch ($request->action) {
            case 'activate':
                $contents->update(['is_active' => true]);
                $message = 'Seçilen içerikler aktif hale getirildi.';
                break;
            case 'deactivate':
                $contents->update(['is_active' => false]);
                $message = 'Seçilen içerikler pasif hale getirildi.';
                break;
            case 'delete':
                // Görselleri sil
                foreach ($contents->get() as $content) {
                    if ($content->image && Storage::disk('public')->exists($content->image)) {
                        Storage::disk('public')->delete($content->image);
                    }
                }
                $contents->delete();
                $message = 'Seçilen içerikler silindi.';
                break;
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }
}
