<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PageSetting;
use Illuminate\Support\Facades\Validator;

class PageSettingController extends Controller
{
    /**
     * Ayarları göster
     */
    public function edit()
    {
        $settings = PageSetting::getSettings();
        
        return view('admin.pages.settings.edit', compact('settings'));
    }
    
    /**
     * Ayarları güncelle
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hero_badge_text' => 'nullable|string|max:255',
            'hero_title' => 'nullable|string|max:255',
            'hero_title_highlight' => 'nullable|string|max:255',
            'hero_description' => 'nullable|string',
            'search_title' => 'nullable|string|max:255',
            'search_placeholder' => 'nullable|string|max:255',
            'search_button_text' => 'nullable|string|max:255',
            'popular_searches_title' => 'nullable|string|max:255',
            'popular_searches' => 'nullable|array',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Popular searches'ı düzenle
        $popularSearches = [];
        if ($request->has('popular_search_text') && is_array($request->popular_search_text)) {
            foreach ($request->popular_search_text as $key => $text) {
                if (!empty($text) && !empty($request->popular_search_query[$key])) {
                    $popularSearches[] = [
                        'text' => $text,
                        'search' => $request->popular_search_query[$key]
                    ];
                }
            }
        }
        
        $settings = PageSetting::getSettings();
        $settings->update([
            'hero_badge_text' => $request->hero_badge_text,
            'hero_title' => $request->hero_title,
            'hero_title_highlight' => $request->hero_title_highlight,
            'hero_description' => $request->hero_description,
            'search_title' => $request->search_title,
            'search_placeholder' => $request->search_placeholder,
            'search_button_text' => $request->search_button_text,
            'popular_searches_title' => $request->popular_searches_title,
            'popular_searches' => $popularSearches,
        ]);
        
        return redirect()->route('admin.pages.settings.edit')
            ->with('success', 'Sayfa ayarları başarıyla güncellendi.');
    }
}
