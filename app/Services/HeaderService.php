<?php

namespace App\Services;

use App\Models\MenuSystem;
use App\Models\MenuSystemItem;
use Illuminate\Support\Facades\Cache;

class HeaderService
{
    /**
     * Header ayarlarını getirir
     */
    public function getHeaderSettings()
    {
        // Gerçek header ayarlarını veritabanından al
        $headerSettings = \App\Models\HeaderSetting::getSettings();
        
        return (object) [
            'logo_path' => $headerSettings->logo_path ? asset('storage/' . ltrim($headerSettings->logo_path, '/')) : asset('images/logo-cankaya.png'),
            'secondary_logo_path' => $headerSettings->secondary_logo_path ? asset('storage/' . ltrim($headerSettings->secondary_logo_path, '/')) : asset('images/simge.png'),
            'slogan_path' => $headerSettings->slogan_path ? asset('storage/' . ltrim($headerSettings->slogan_path, '/')) : asset('images/slogan.png'),
            'show_search_button' => $headerSettings->show_search_button,
            'header_bg_color' => $headerSettings->header_bg_color ?: '#ffffff',
            'header_text_color' => $headerSettings->header_text_color ?: '#00352b',
            'header_height' => $headerSettings->header_height ?: 96,
            'sticky_header' => $headerSettings->sticky_header,
            'custom_css' => $headerSettings->custom_css,
            'additional_scripts' => $headerSettings->additional_scripts,
            'custom_header_html' => $headerSettings->custom_header_html,
            'mobile_logo_path' => $headerSettings->mobile_logo_path ? asset('storage/' . ltrim($headerSettings->mobile_logo_path, '/')) : null,
        ];
    }

    /**
     * Ana menü öğelerini getirir
     */
    public function getMainMenuItems()
    {
        // Cache kullanarak performansı artırıyoruz, 60 dakika önbellekte tutuyoruz
        return Cache::remember('header_main_menu_items', 60, function () {
            // Header pozisyonundaki aktif menüleri al
            return MenuSystem::active()
                ->where('position', 'header')
                ->orderBy('order')
                ->get();
        });
    }

    /**
     * Menü alt başlıklarını getirir (3 seviyeli)
     */
    public function getMenuItems($menuId)
    {
        return Cache::remember('menu_items_' . $menuId, 60, function () use ($menuId) {
            // İlgili menünün aktif alt öğelerini al (3 seviyeli)
            return MenuSystemItem::active()
                ->where('menu_id', $menuId)
                ->whereNull('parent_id')
                ->orderBy('order')
                ->with(['children' => function ($query) {
                    $query->where('status', true)
                          ->orderBy('order')
                          ->with(['children' => function ($subQuery) {
                              $subQuery->where('status', true)
                                       ->orderBy('order');
                          }]);
                }])
                ->get();
        });
    }

    /**
     * Cache'i temizler
     */
    public function clearCache()
    {
        Cache::forget('header_main_menu_items');
        
        // Tüm menu_items cache'lerini temizle
        $menus = MenuSystem::all();
        foreach ($menus as $menu) {
            Cache::forget('menu_items_' . $menu->id);
        }
    }
} 