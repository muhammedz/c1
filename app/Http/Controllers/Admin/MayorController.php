<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mayor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MayorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mayor = Mayor::first();
        
        if (!$mayor) {
            // Eğer başkan kaydı yoksa, yeni bir tane oluştur
            $mayor = Mayor::create([
                'name' => 'Av. Hüseyin Can Güner',
                'title' => 'Belediye Başkanı',
                'page_title' => 'Başkanımız',
                'meta_description' => 'Başkanımızın biyografisi, faaliyetleri ve duyuruları',
                'is_active' => true
            ]);
        }
        
        return view('admin.mayor.index', compact('mayor'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Başkan kaydı tek olduğu için create'e gerek yok
        return redirect()->route('admin.mayor.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Başkan kaydı tek olduğu için store'a gerek yok
        return redirect()->route('admin.mayor.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Mayor $mayor)
    {
        return view('admin.mayor.show', compact('mayor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mayor $mayor)
    {
        return view('admin.mayor.edit', compact('mayor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mayor $mayor)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'biography' => 'nullable|string',
            'social_twitter' => 'nullable|url',
            'social_instagram' => 'nullable|url',
            'social_facebook' => 'nullable|url',
            'social_linkedin' => 'nullable|url',
            'social_email' => 'nullable|email',
            'hero_bg_color' => 'nullable|string|max:7',
            'page_title' => 'required|string|max:255',
            'meta_description' => 'nullable|string',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'hero_bg_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = $request->except(['profile_image', 'hero_bg_image']);

        // Profil fotoğrafı yükleme
        if ($request->hasFile('profile_image')) {
            // Eski fotoğrafı sil
            if ($mayor->profile_image && Storage::disk('uploads')->exists($mayor->profile_image)) {
                Storage::disk('uploads')->delete($mayor->profile_image);
            }
            
            $data['profile_image'] = $request->file('profile_image')->store('mayor/profile', 'uploads');
        }

        // Hero arka plan görseli yükleme
        if ($request->hasFile('hero_bg_image')) {
            // Eski görseli sil
            if ($mayor->hero_bg_image && Storage::disk('uploads')->exists($mayor->hero_bg_image)) {
                Storage::disk('uploads')->delete($mayor->hero_bg_image);
            }
            
            $data['hero_bg_image'] = $request->file('hero_bg_image')->store('mayor/hero', 'uploads');
        }

        $mayor->update($data);

        return redirect()->route('admin.mayor.index')
            ->with('success', 'Başkan bilgileri başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mayor $mayor)
    {
        // Başkan kaydı silinmez, sadece pasif yapılır
        $mayor->update(['is_active' => false]);
        
        return redirect()->route('admin.mayor.index')
            ->with('success', 'Başkan kaydı pasif hale getirildi.');
    }

    /**
     * Başkan kaydını aktif/pasif yap
     */
    public function toggleStatus(Mayor $mayor)
    {
        $mayor->update(['is_active' => !$mayor->is_active]);
        
        $status = $mayor->is_active ? 'aktif' : 'pasif';
        
        return response()->json([
            'success' => true,
            'message' => "Başkan kaydı {$status} hale getirildi.",
            'is_active' => $mayor->is_active
        ]);
    }
}
