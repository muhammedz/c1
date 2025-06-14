<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SeoSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $seoSettings = [
            [
                'key' => 'homepage_title',
                'value' => 'Çankaya Belediyesi - Resmi Web Sitesi',
                'group' => 'seo',
                'display_name' => 'Anasayfa Başlığı',
                'type' => 'text',
                'description' => 'Web sitesinin anasayfa meta başlığı',
                'is_public' => true,
                'is_required' => true,
                'order' => 1
            ],
            [
                'key' => 'homepage_description',
                'value' => 'Çankaya Belediyesi resmi web sitesi. Hizmetlerimiz, duyurularımız, projelerimiz ve etkinliklerimiz hakkında güncel bilgilere ulaşın.',
                'group' => 'seo',
                'display_name' => 'Anasayfa Açıklaması',
                'type' => 'textarea',
                'description' => 'Web sitesinin anasayfa meta açıklaması',
                'is_public' => true,
                'is_required' => true,
                'order' => 2
            ]
        ];

        foreach ($seoSettings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key'], 'group' => $setting['group']],
                $setting
            );
        }
    }
} 