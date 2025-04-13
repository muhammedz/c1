<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsTag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsTagController extends Controller
{
    /**
     * Haber Etiketleri listesi
     */
    public function index()
    {
        $newsTags = NewsTag::orderBy('name')
            ->paginate(50);
            
        return view('admin.news-tags.index', compact('newsTags'));
    }
    
    /**
     * Yeni haber etiketi oluşturma formu
     */
    public function create()
    {
        return view('admin.news-tags.create');
    }
    
    /**
     * Haber etiketi kaydetme
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:news_tags,name',
        ]);
        
        $newsTag = NewsTag::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'usage_count' => 0
        ]);
        
        return redirect()->route('admin.news-tags.index')
            ->with('success', 'Haber etiketi başarıyla oluşturuldu.');
    }
    
    /**
     * Haber etiketini düzenleme formu
     */
    public function edit(NewsTag $newsTag)
    {
        return view('admin.news-tags.edit', compact('newsTag'));
    }
    
    /**
     * Haber etiketi güncelleme
     */
    public function update(Request $request, NewsTag $newsTag)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:news_tags,name,' . $newsTag->id,
        ]);
        
        $newsTag->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);
        
        return redirect()->route('admin.news-tags.index')
            ->with('success', 'Haber etiketi başarıyla güncellendi.');
    }
    
    /**
     * Haber etiketi silme
     */
    public function destroy(NewsTag $newsTag)
    {
        // Etiket ilişkileri otomatik silinecek (cascade)
        $newsTag->delete();
        
        return redirect()->route('admin.news-tags.index')
            ->with('success', 'Haber etiketi başarıyla silindi.');
    }
    
    /**
     * AJAX haber etiketi otomatik tamamlama
     */
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:2'
        ]);
        
        $query = $request->input('query');
        
        $newsTags = NewsTag::where('name', 'like', '%' . $query . '%')
            ->orderBy('usage_count', 'desc')
            ->take(10)
            ->get(['id', 'name']);
            
        return response()->json($newsTags);
    }
    
    /**
     * Kullanılmayan haber etiketlerini temizleme
     */
    public function cleanup()
    {
        $count = NewsTag::where('usage_count', 0)->delete();
        
        return redirect()->route('admin.news-tags.index')
            ->with('success', $count . ' kullanılmayan haber etiketi başarıyla temizlendi.');
    }
} 