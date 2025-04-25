<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\Models\MenuCard;
use Illuminate\Support\Facades\DB;

class MenuCardSeeder extends Seeder
{
    /**
     * Hizmetler menüsü için kartları oluşturur.
     */
    public function run(): void
    {
        // Foreign key kontrolünü geçici olarak devre dışı bırakalım
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Mevcut kartları temizleyelim
        MenuCard::truncate();
        
        // Hizmetler menüsünü bulalım
        $hizmetlerMenu = Menu::where('name', 'Hizmetler')
            ->where('type', 'header')
            ->whereNull('parent_id')
            ->first();
            
        if (!$hizmetlerMenu) {
            $this->command->error('Hizmetler menüsü bulunamadı!');
            return;
        }
        
        // Kartları oluşturalım
        $cards = [
            // Vatandaş Hizmetleri Grubu
            [
                'title' => 'E-Belediye',
                'icon' => 'computer',
                'url' => '/hizmetler/e-belediye',
                'data_category' => 'vatandas-hizmetleri',
                'color' => '#007b32',
                'order' => 1
            ],
            [
                'title' => 'Vergi Ödemeleri',
                'icon' => 'payments',
                'url' => '/hizmetler/vergi-odemeleri',
                'data_category' => 'vatandas-hizmetleri',
                'color' => '#007b32',
                'order' => 2
            ],
            [
                'title' => 'Beyaz Masa',
                'icon' => 'support_agent',
                'url' => '/hizmetler/beyaz-masa',
                'data_category' => 'vatandas-hizmetleri',
                'color' => '#007b32',
                'order' => 3
            ],
            
            // İşletme Hizmetleri Grubu
            [
                'title' => 'Ruhsat İşlemleri',
                'icon' => 'assignment',
                'url' => '/hizmetler/ruhsat-islemleri',
                'data_category' => 'isletme-hizmetleri',
                'color' => '#00965e',
                'order' => 4
            ],
            [
                'title' => 'İşyeri Açma',
                'icon' => 'store',
                'url' => '/hizmetler/isyeri-acma',
                'data_category' => 'isletme-hizmetleri',
                'color' => '#00965e',
                'order' => 5
            ],
            [
                'title' => 'Denetim',
                'icon' => 'fact_check',
                'url' => '/hizmetler/denetim',
                'data_category' => 'isletme-hizmetleri',
                'color' => '#00965e',
                'order' => 6
            ],
            
            // Sosyal ve Kültürel Hizmetler
            [
                'title' => 'Kültür Merkezleri',
                'icon' => 'theater_comedy',
                'url' => '/hizmetler/kultur-merkezleri',
                'data_category' => 'sosyal-ve-kulturel',
                'color' => '#6a1b9a',
                'order' => 7
            ],
            [
                'title' => 'Etkinlikler',
                'icon' => 'event',
                'url' => '/hizmetler/etkinlikler',
                'data_category' => 'sosyal-ve-kulturel',
                'color' => '#6a1b9a',
                'order' => 8
            ],
            [
                'title' => 'Sosyal Destek',
                'icon' => 'volunteer_activism',
                'url' => '/hizmetler/sosyal-destek',
                'data_category' => 'sosyal-ve-kulturel',
                'color' => '#6a1b9a',
                'order' => 9
            ],
            
            // Çevre ve Sağlık Grubu
            [
                'title' => 'Çevre Koruma',
                'icon' => 'eco',
                'url' => '/hizmetler/cevre-koruma',
                'data_category' => 'cevre-ve-saglik',
                'color' => '#f9a825',
                'order' => 10
            ],
            [
                'title' => 'Sağlık Hizmetleri',
                'icon' => 'health_and_safety',
                'url' => '/hizmetler/saglik-hizmetleri',
                'data_category' => 'cevre-ve-saglik',
                'color' => '#f9a825',
                'order' => 11
            ],
            [
                'title' => 'Temizlik İşleri',
                'icon' => 'cleaning_services',
                'url' => '/hizmetler/temizlik-isleri',
                'data_category' => 'cevre-ve-saglik',
                'color' => '#f9a825',
                'order' => 12
            ],
            
            // Spor Hizmetleri Grubu
            [
                'title' => 'Spor Tesisleri',
                'icon' => 'sports_soccer',
                'url' => '/hizmetler/spor-tesisleri',
                'data_category' => 'spor-hizmetleri',
                'color' => '#d32f2f',
                'order' => 13
            ],
            [
                'title' => 'Spor Kursları',
                'icon' => 'sports',
                'url' => '/hizmetler/spor-kurslari',
                'data_category' => 'spor-hizmetleri',
                'color' => '#d32f2f',
                'order' => 14
            ],
            [
                'title' => 'Spor Etkinlikleri',
                'icon' => 'event_available',
                'url' => '/hizmetler/spor-etkinlikleri',
                'data_category' => 'spor-hizmetleri',
                'color' => '#d32f2f',
                'order' => 15
            ],
        ];
        
        foreach ($cards as $card) {
            MenuCard::create(array_merge($card, ['menu_id' => $hizmetlerMenu->id, 'is_active' => 1]));
        }
        
        // Foreign key kontrolünü tekrar etkinleştirelim
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $this->command->info('Menü kartları başarıyla oluşturuldu!');
    }
} 