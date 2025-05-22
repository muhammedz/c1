<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServicesUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServicesUnitController extends Controller
{
    /**
     * Birimlerin listesini göster
     */
    public function index()
    {
        $units = ServicesUnit::orderBy('order')->get();
        return view('admin.services.units.index', compact('units'));
    }

    /**
     * Yeni birim oluşturma formunu göster
     */
    public function create()
    {
        return view('admin.services.units.create');
    }

    /**
     * Yeni birimi kaydet
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'boolean'
        ]);

        $unit = new ServicesUnit();
        $unit->name = $request->name;
        $unit->slug = Str::slug($request->name);
        $unit->description = $request->description;
        $unit->status = $request->has('status');
        $unit->order = ServicesUnit::max('order') + 1;
        $unit->save();

        return redirect()->route('admin.services.units.index')
            ->with('success', 'Birim başarıyla oluşturuldu.');
    }

    /**
     * Birim düzenleme formunu göster
     */
    public function edit(ServicesUnit $unit)
    {
        return view('admin.services.units.edit', compact('unit'));
    }

    /**
     * Birimi güncelle
     */
    public function update(Request $request, ServicesUnit $unit)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'boolean'
        ]);

        $unit->name = $request->name;
        $unit->slug = Str::slug($request->name);
        $unit->description = $request->description;
        $unit->status = $request->has('status');
        $unit->save();

        return redirect()->route('admin.services.units.index')
            ->with('success', 'Birim başarıyla güncellendi.');
    }

    /**
     * Birimi sil
     */
    public function destroy(ServicesUnit $unit)
    {
        // Birime bağlı hizmet var mı kontrol et
        if ($unit->services()->count() > 0) {
            return redirect()->route('admin.services.units.index')
                ->with('error', 'Bu birime bağlı hizmetler olduğu için silinemez.');
        }

        $unit->delete();

        return redirect()->route('admin.services.units.index')
            ->with('success', 'Birim başarıyla silindi.');
    }

    /**
     * Birimlerin sırasını güncelle
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'units' => 'required|array',
            'units.*' => 'exists:services_units,id'
        ]);

        foreach ($request->units as $order => $id) {
            ServicesUnit::where('id', $id)->update(['order' => $order + 1]);
        }

        return response()->json(['success' => true]);
    }
} 