<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SearchSetting;
use App\Models\SearchQuickLink;
use App\Models\SearchPopularQuery;
use Illuminate\Http\Request;

class SearchSettingController extends Controller
{
    /**
     * Ana ayarlar sayfasını göster
     */
    public function index()
    {
        $settings = SearchSetting::getSettings();
        $quickLinks = SearchQuickLink::orderBy('order')->get();
        $popularQueries = SearchPopularQuery::orderBy('order')->get();
        
        return view('admin.search_settings.index', compact('settings', 'quickLinks', 'popularQueries'));
    }
    
    /**
     * Ayarları güncelle
     */
    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'placeholder' => 'required|string|max:255',
            'max_quick_links' => 'required|integer|min:0|max:10',
            'max_popular_queries' => 'required|integer|min:0|max:10',
            'show_quick_links' => 'boolean',
            'show_popular_queries' => 'boolean',
        ]);
        
        $settings = SearchSetting::getSettings();
        
        $settings->update([
            'title' => $request->title,
            'placeholder' => $request->placeholder,
            'max_quick_links' => $request->max_quick_links,
            'max_popular_queries' => $request->max_popular_queries,
            'show_quick_links' => $request->has('show_quick_links'),
            'show_popular_queries' => $request->has('show_popular_queries'),
        ]);
        
        return redirect()->route('admin.search-settings.index')
            ->with('success', 'Arama ayarları başarıyla güncellendi.');
    }
}
