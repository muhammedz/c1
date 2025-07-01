<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SearchSetting;
use App\Models\SearchQuickLink;
use App\Models\SearchPopularQuery;
use App\Models\SearchPriorityLink;
use App\Models\SearchLog;
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
        
        // Priority links'i hem varsayılan sıralama hem de tıklama sayısına göre getir
        $priorityLinks = SearchPriorityLink::ordered()->get();
        $priorityLinksByClicks = SearchPriorityLink::orderByClicks()->take(10)->get();
        
        return view('admin.search_settings.index', compact(
            'settings', 
            'quickLinks', 
            'popularQueries',
            'priorityLinks',
            'priorityLinksByClicks'
        ));
    }
    
    /**
     * Yapılan aramalar sayfasını göster
     */
    public function searches()
    {
        // Arama istatistikleri
        $searchStats = SearchLog::getSearchStats();
        $recentSearches = SearchLog::getRecentSearches(50);
        $popularSearches = SearchLog::getPopularSearches(20);
        $zeroResultSearches = SearchLog::getZeroResultSearches(30);
        
        return view('admin.search_settings.searches', compact(
            'searchStats',
            'recentSearches',
            'popularSearches',
            'zeroResultSearches'
        ));
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
            'show_quick_links' => 'nullable',
            'show_popular_queries' => 'nullable',
            'search_in_mudurluk_files' => 'nullable',
        ]);
        
        $settings = SearchSetting::getSettings();
        
        $settings->update([
            'title' => $request->title,
            'placeholder' => $request->placeholder,
            'max_quick_links' => $request->max_quick_links,
            'max_popular_queries' => $request->max_popular_queries,
            'show_quick_links' => $request->input('show_quick_links') == '1',
            'show_popular_queries' => $request->input('show_popular_queries') == '1',
            'search_in_mudurluk_files' => $request->input('search_in_mudurluk_files') == '1',
        ]);
        
        return redirect()->route('admin.search-settings.index')
            ->with('success', 'Arama ayarları başarıyla güncellendi.');
    }
}
