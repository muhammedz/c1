<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\Models\MenuTag;
use Illuminate\Support\Facades\DB;

class MenuTagSeeder extends Seeder
{
    /**
     * Hizmetler menüsü için etiketleri oluşturur.
     */
    public function run(): void
    {
        // Foreign key kontrolünü geçici olarak devre dışı bırakalım
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Mevcut etiketleri temizleyelim
        MenuTag::truncate();
        
        // Hizmetler menüsünü bulalım
        $hizmetlerMenu = Menu::where('name', 'Hizmetler')
            ->where('type', 'header')
            ->whereNull('parent_id')
            ->first();
            
        if (!$hizmetlerMenu) {
            $this->command->error('Hizmetler menüsü bulunamadı!');
            return;
        }
        
        // Hizmetler menüsünün mega_menu_layout'unu card_grid olarak ayarlayalım
        $hizmetlerMenu->mega_menu_layout = 'card_grid';
        $hizmetlerMenu->layout_settings = [
            'tags_title' => 'Hizmet Grupları:',
            'action_text' => 'Tüm Hizmetler',
            'action_link' => '/hizmetler',
            'action_icon' => 'list_alt'
        ];
        $hizmetlerMenu->save();
        
        // Etiketleri oluşturalım
        $tags = [
            [
                'name' => 'Vatandaş Hizmetleri',
                'color_class' => 'blue',
                'url' => '/hizmetler/vatandas',
                'order' => 1
            ],
            [
                'name' => 'İşletme Hizmetleri',
                'color_class' => 'green',
                'url' => '/hizmetler/isletme',
                'order' => 2
            ],
            [
                'name' => 'Sosyal ve Kültürel',
                'color_class' => 'purple',
                'url' => '/hizmetler/sosyal-kulturel',
                'order' => 3
            ],
            [
                'name' => 'Çevre ve Sağlık',
                'color_class' => 'yellow',
                'url' => '/hizmetler/cevre-saglik',
                'order' => 4
            ],
            [
                'name' => 'Spor Hizmetleri',
                'color_class' => 'red',
                'url' => '/hizmetler/spor',
                'order' => 5
            ]
        ];
        
        foreach ($tags as $tag) {
            MenuTag::create(array_merge($tag, ['menu_id' => $hizmetlerMenu->id, 'is_active' => 1]));
        }
        
        // Foreign key kontrolünü tekrar etkinleştirelim
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $this->command->info('Menü etiketleri başarıyla oluşturuldu!');
    }
} 