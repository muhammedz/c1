<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\GuideCategory;

class GuideCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Zabıta Noktaları',
                'slug' => 'zabita-noktalari',
                'description' => 'Çankaya Belediyesi zabıta noktaları ve iletişim bilgileri',
                'icon' => 'fas fa-shield-alt',
                'sort_order' => 1,
                'is_active' => true,
                'meta_title' => 'Çankaya Zabıta Noktaları',
                'meta_description' => 'Çankaya Belediyesi zabıta noktaları, adresler ve iletişim bilgileri'
            ],
            [
                'name' => 'Sağlık Merkezleri',
                'slug' => 'saglik-merkezleri',
                'description' => 'Çankaya ilçesindeki sağlık merkezleri ve hastaneler',
                'icon' => 'fas fa-hospital',
                'sort_order' => 2,
                'is_active' => true,
                'meta_title' => 'Çankaya Sağlık Merkezleri',
                'meta_description' => 'Çankaya ilçesindeki sağlık merkezleri, hastaneler ve sağlık kuruluşları'
            ],
            [
                'name' => 'Eğitim Kurumları',
                'slug' => 'egitim-kurumlari',
                'description' => 'Çankaya ilçesindeki okullar ve eğitim kurumları',
                'icon' => 'fas fa-graduation-cap',
                'sort_order' => 3,
                'is_active' => true,
                'meta_title' => 'Çankaya Eğitim Kurumları',
                'meta_description' => 'Çankaya ilçesindeki okullar, üniversiteler ve eğitim kurumları'
            ],
            [
                'name' => 'Kamu Kurumları',
                'slug' => 'kamu-kurumlari',
                'description' => 'Çankaya ilçesindeki kamu kurumları ve devlet daireleri',
                'icon' => 'fas fa-building',
                'sort_order' => 4,
                'is_active' => true,
                'meta_title' => 'Çankaya Kamu Kurumları',
                'meta_description' => 'Çankaya ilçesindeki kamu kurumları, devlet daireleri ve resmi kurumlar'
            ],
            [
                'name' => 'Parklar ve Rekreasyon',
                'slug' => 'parklar-rekreasyon',
                'description' => 'Çankaya ilçesindeki parklar, yeşil alanlar ve rekreasyon tesisleri',
                'icon' => 'fas fa-tree',
                'sort_order' => 5,
                'is_active' => true,
                'meta_title' => 'Çankaya Parkları',
                'meta_description' => 'Çankaya ilçesindeki parklar, yeşil alanlar ve rekreasyon tesisleri'
            ]
        ];

        foreach ($categories as $category) {
            GuideCategory::create($category);
        }
    }
}
