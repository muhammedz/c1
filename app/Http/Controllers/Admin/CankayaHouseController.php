<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CankayaHouse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class CankayaHouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = CankayaHouse::query();

        // Arama filtresi
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        // Durum filtresi
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $cankayaHouses = $query->withCount('courses')
                              ->ordered()
                              ->paginate(15);

        return view('admin.cankaya-houses.index', compact('cankayaHouses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.cankaya-houses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string',
            'phone' => 'nullable|string|max:20',
            'location_link' => 'nullable|url',
            'images' => 'nullable|array',
            'images.*' => 'nullable|string|url',
            'status' => 'required|in:active,inactive',
            'order' => 'nullable|integer|min:0'
        ]);

        try {
            DB::beginTransaction();

            $data = $request->only(['name', 'description', 'address', 'phone', 'location_link', 'status', 'order']);
            
            // Slug oluştur
            $data['slug'] = Str::slug($request->name);
            
            // Eğer aynı slug varsa benzersiz yap
            $originalSlug = $data['slug'];
            $counter = 1;
            while (CankayaHouse::where('slug', $data['slug'])->exists()) {
                $data['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }

            // Resim URL'lerini al
            $images = [];
            if ($request->has('images') && is_array($request->images)) {
                $images = array_filter($request->images); // Boş değerleri filtrele
            }
            $data['images'] = $images;

            $cankayaHouse = CankayaHouse::create($data);

            DB::commit();

            return redirect()->route('admin.cankaya-houses.index')
                           ->with('success', 'Çankaya Evi başarıyla oluşturuldu.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Bir hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CankayaHouse $cankayaHouse)
    {
        $cankayaHouse->load(['courses' => function($query) {
            $query->orderBy('start_date', 'desc');
        }]);

        return view('admin.cankaya-houses.show', compact('cankayaHouse'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CankayaHouse $cankayaHouse)
    {
        return view('admin.cankaya-houses.edit', compact('cankayaHouse'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CankayaHouse $cankayaHouse)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string',
            'phone' => 'nullable|string|max:20',
            'location_link' => 'nullable|url',
            'images' => 'nullable|array',
            'images.*' => 'nullable|string|url',
            'status' => 'required|in:active,inactive',
            'order' => 'nullable|integer|min:0'
        ]);

        try {
            DB::beginTransaction();

            $data = $request->only(['name', 'description', 'address', 'phone', 'location_link', 'status', 'order']);
            
            // Slug güncelle (eğer isim değiştiyse)
            if ($request->name !== $cankayaHouse->name) {
                $data['slug'] = Str::slug($request->name);
                
                // Eğer aynı slug varsa benzersiz yap
                $originalSlug = $data['slug'];
                $counter = 1;
                while (CankayaHouse::where('slug', $data['slug'])->where('id', '!=', $cankayaHouse->id)->exists()) {
                    $data['slug'] = $originalSlug . '-' . $counter;
                    $counter++;
                }
            }

            // Resim URL'lerini al
            $images = [];
            if ($request->has('images') && is_array($request->images)) {
                $images = array_filter($request->images); // Boş değerleri filtrele
            }

            $data['images'] = $images;

            $cankayaHouse->update($data);

            DB::commit();

            return redirect()->route('admin.cankaya-houses.index')
                           ->with('success', 'Çankaya Evi başarıyla güncellendi.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Bir hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CankayaHouse $cankayaHouse)
    {
        try {
            DB::beginTransaction();

            // Resimleri sil
            if ($cankayaHouse->images) {
                foreach ($cankayaHouse->images as $image) {
                    Storage::disk('public')->delete($image);
                }
            }

            $cankayaHouse->delete();

            DB::commit();

            return redirect()->route('admin.cankaya-houses.index')
                           ->with('success', 'Çankaya Evi başarıyla silindi.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                           ->with('error', 'Bir hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Toggle status of the specified resource.
     */
    public function toggleStatus(CankayaHouse $cankayaHouse)
    {
        $cankayaHouse->update([
            'status' => $cankayaHouse->status === 'active' ? 'inactive' : 'active'
        ]);

        return redirect()->back()
                       ->with('success', 'Durum başarıyla güncellendi.');
    }

    /**
     * Remove image from gallery.
     */
    public function removeImage(Request $request, CankayaHouse $cankayaHouse)
    {
        $request->validate([
            'image' => 'required|string'
        ]);

        $images = $cankayaHouse->images ?? [];
        $imageToRemove = $request->image;

        // Resmi array'den kaldır
        $images = array_filter($images, function($image) use ($imageToRemove) {
            return $image !== $imageToRemove;
        });
        
        // Index'leri yeniden düzenle
        $images = array_values($images);

        $cankayaHouse->update(['images' => $images]);

        return response()->json(['success' => true]);
    }
}
