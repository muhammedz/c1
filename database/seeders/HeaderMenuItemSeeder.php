<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\Models\HeaderMenuItem;
use App\Models\HeaderMegaMenu;
use App\Models\HeaderMegaMenuItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HeaderMenuItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Foreign key kontrolleri geçici olarak devre dışı bırakın
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Verileri sil - foreign key kontrollerinden kaçınmak için DB::table()->delete() kullan
        DB::table('header_mega_menu_items')->delete();
        DB::table('header_mega_menus')->delete();
        DB::table('header_menu_items')->delete();
        
        // Ana menü öğelerini al
        $mainMenus = Menu::where('type', 'header')
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        // Her ana menü için
        foreach ($mainMenus as $mainMenu) {
            // Ana menüyü HeaderMenuItem olarak oluştur
            $headerMenuItem = HeaderMenuItem::create([
                'title' => $mainMenu->name,
                'url' => $mainMenu->url,
                'order' => $mainMenu->order,
                'icon' => $mainMenu->icon,
                'is_active' => $mainMenu->is_active,
                'has_mega_menu' => $mainMenu->is_mega_menu,
                'mega_menu_type' => 'normal',
                'is_bold' => false,
                'custom_class' => '',
                'target' => '_self',
                'mobile_visibility' => true
            ]);

            // Alt menüleri al - ilk seviye alt menüler (mega menü kategorileri)
            $subMenus = Menu::where('parent_id', $mainMenu->id)
                ->where('is_active', true)
                ->orderBy('order')
                ->get();

            // Alt menüler varsa ve mega menü ise
            if ($subMenus->count() > 0 && $mainMenu->is_mega_menu) {
                // Her kategori (ikinci seviye menü) için bir mega menü bölümü oluştur
                foreach ($subMenus as $categoryIndex => $category) {
                    $megaMenu = HeaderMegaMenu::create([
                        'menu_item_id' => $headerMenuItem->id,
                        'title' => $category->name,
                        'content' => null,
                        'custom_class' => '',
                        'order' => $categoryIndex + 1,
                        'is_active' => true,
                        'column_width' => '1/4',
                        'background_color' => null
                    ]);

                    // Üçüncü seviye menü öğelerini al (kategori altındaki öğeler)
                    $categoryItems = Menu::where('parent_id', $category->id)
                        ->where('is_active', true)
                        ->orderBy('order')
                        ->get();

                    // Her öğe için mega menü öğesi oluştur
                    foreach ($categoryItems as $itemIndex => $item) {
                        HeaderMegaMenuItem::create([
                            'mega_menu_id' => $megaMenu->id,
                            'title' => $item->name,
                            'url' => $item->url,
                            'icon' => $item->icon,
                            'order' => $itemIndex + 1,
                            'is_active' => $item->is_active,
                            'custom_class' => '',
                            'target' => '_self'
                        ]);
                    }
                }
            } 
            // Alt menüler var ama mega menü değilse (normal dropdown)
            else if ($subMenus->count() > 0) {
                // Her alt menü için normal alt menü öğesi oluştur
                foreach ($subMenus as $index => $subMenu) {
                    $subMenuItem = HeaderMenuItem::create([
                        'title' => $subMenu->name,
                        'url' => $subMenu->url,
                        'order' => $index + 1,
                        'parent_id' => $headerMenuItem->id,
                        'icon' => $subMenu->icon,
                        'is_active' => $subMenu->is_active,
                        'has_mega_menu' => false,
                        'mega_menu_type' => 'normal',
                        'is_bold' => false,
                        'custom_class' => '',
                        'target' => '_self',
                        'mobile_visibility' => true
                    ]);

                    // Üçüncü seviye menü öğelerini kontrol et ve ekle
                    $thirdLevelItems = Menu::where('parent_id', $subMenu->id)
                        ->where('is_active', true)
                        ->orderBy('order')
                        ->get();

                    foreach ($thirdLevelItems as $thirdItemIndex => $thirdItem) {
                        HeaderMenuItem::create([
                            'title' => $thirdItem->name,
                            'url' => $thirdItem->url,
                            'order' => $thirdItemIndex + 1,
                            'parent_id' => $subMenuItem->id,
                            'icon' => $thirdItem->icon,
                            'is_active' => $thirdItem->is_active,
                            'has_mega_menu' => false,
                            'mega_menu_type' => 'normal',
                            'is_bold' => false,
                            'custom_class' => '',
                            'target' => '_self',
                            'mobile_visibility' => true
                        ]);
                    }
                }
            }
        }
        
        // Foreign key kontrollerini tekrar etkinleştirin
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Cache'i temizle
        if (app()->bound('App\Services\HeaderService')) {
            app('App\Services\HeaderService')->clearCache();
        }

        $this->command->info('Header menü öğeleri başarıyla HeaderMenuItem tablosuna aktarıldı!');
    }
} 