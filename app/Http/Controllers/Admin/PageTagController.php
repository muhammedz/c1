<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageTag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageTagController extends Controller
{
    /**
     * Sayfa Etiketleri listesi
     */
    public function index()
    {
        $pageTags = PageTag::orderBy('name')
            ->paginate(50);
            
        return view('admin.page-tags.index', compact('pageTags'));
    }
    
    /**
     * Yeni sayfa etiketi oluşturma formu
     */
    public function create()
    {
        return view('admin.page-tags.create');
    }
    
    /**
     * Sayfa etiketi kaydetme
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:page_tags,name',
        ]);
        
        $pageTag = PageTag::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'usage_count' => 0
        ]);
        
        return redirect()->route('admin.page-tags.index')
            ->with('success', 'Sayfa etiketi başarıyla oluşturuldu.');
    }
    
    /**
     * Sayfa etiketini düzenleme formu
     */
    public function edit(PageTag $pageTag)
    {
        return view('admin.page-tags.edit', compact('pageTag'));
    }
    
    /**
     * Sayfa etiketi güncelleme
     */
    public function update(Request $request, PageTag $pageTag)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:page_tags,name,' . $pageTag->id,
        ]);
        
        $pageTag->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);
        
        return redirect()->route('admin.page-tags.index')
            ->with('success', 'Sayfa etiketi başarıyla güncellendi.');
    }
    
    /**
     * Sayfa etiketi silme
     */
    public function destroy(PageTag $pageTag)
    {
        // Etiket ilişkileri otomatik silinecek (cascade)
        $pageTag->delete();
        
        return redirect()->route('admin.page-tags.index')
            ->with('success', 'Sayfa etiketi başarıyla silindi.');
    }
    
    /**
     * AJAX sayfa etiketi otomatik tamamlama
     */
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:2'
        ]);
        
        $query = $request->input('query');
        
        $pageTags = PageTag::where('name', 'like', '%' . $query . '%')
            ->orderBy('usage_count', 'desc')
            ->take(10)
            ->get(['id', 'name']);
            
        return response()->json($pageTags);
    }
    
    /**
     * Kullanılmayan sayfa etiketlerini temizleme
     */
    public function cleanup()
    {
        $count = PageTag::where('usage_count', 0)->delete();
        
        return redirect()->route('admin.page-tags.index')
            ->with('success', $count . ' kullanılmayan sayfa etiketi başarıyla temizlendi.');
    }
} 