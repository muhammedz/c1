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
        // Şimdilik varsayılan ayarları döndürüyoruz
        // İleride header ayarları eklendikçe buradan döndürülebilir
        return (object) [
            'logo_path' => 'images/logo-cankaya.png',
            'header_height' => 96,
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
     * Menü alt başlıklarını getirir
     */
    public function getMenuItems($menuId)
    {
        return Cache::remember('menu_items_' . $menuId, 60, function () use ($menuId) {
            // İlgili menünün aktif alt öğelerini al
            return MenuSystemItem::active()
                ->where('menu_id', $menuId)
                ->whereNull('parent_id')
                ->orderBy('order')
                ->with(['children' => function ($query) {
                    $query->where('status', true)
                          ->orderBy('order');
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