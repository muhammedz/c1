<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceTag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceTagController extends Controller
{
    /**
     * Hizmet Etiketleri listesi
     */
    public function index()
    {
        $serviceTags = ServiceTag::orderBy('name')
            ->paginate(50);
            
        return view('admin.service-tags.index', compact('serviceTags'));
    }
    
    /**
     * Yeni hizmet etiketi oluşturma formu
     */
    public function create()
    {
        return view('admin.service-tags.create');
    }
    
    /**
     * Hizmet etiketi kaydetme
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:service_tags,name',
        ]);
        
        $serviceTag = ServiceTag::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'usage_count' => 0
        ]);
        
        return redirect()->route('admin.service-tags.index')
            ->with('success', 'Hizmet etiketi başarıyla oluşturuldu.');
    }
    
    /**
     * Hizmet etiketini düzenleme formu
     */
    public function edit(ServiceTag $serviceTag)
    {
        return view('admin.service-tags.edit', compact('serviceTag'));
    }
    
    /**
     * Hizmet etiketi güncelleme
     */
    public function update(Request $request, ServiceTag $serviceTag)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:service_tags,name,' . $serviceTag->id,
        ]);
        
        $serviceTag->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);
        
        return redirect()->route('admin.service-tags.index')
            ->with('success', 'Hizmet etiketi başarıyla güncellendi.');
    }
    
    /**
     * Hizmet etiketi silme
     */
    public function destroy(ServiceTag $serviceTag)
    {
        // Etiket ilişkileri otomatik silinecek (cascade)
        $serviceTag->delete();
        
        return redirect()->route('admin.service-tags.index')
            ->with('success', 'Hizmet etiketi başarıyla silindi.');
    }
    
    /**
     * AJAX hizmet etiketi otomatik tamamlama
     */
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:2'
        ]);
        
        $query = $request->input('query');
        
        $serviceTags = ServiceTag::where('name', 'like', '%' . $query . '%')
            ->orderBy('usage_count', 'desc')
            ->take(10)
            ->get(['id', 'name']);
            
        return response()->json($serviceTags);
    }
    
    /**
     * Kullanılmayan hizmet etiketlerini temizleme
     */
    public function cleanup()
    {
        $count = ServiceTag::where('usage_count', 0)->delete();
        
        return redirect()->route('admin.service-tags.index')
            ->with('success', $count . ' kullanılmayan hizmet etiketi başarıyla temizlendi.');
    }
}
