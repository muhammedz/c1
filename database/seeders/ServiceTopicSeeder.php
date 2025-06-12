<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ServiceTopic;
use Illuminate\Support\Str;

class ServiceTopicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $topics = [
            [
                'name' => 'Cenaze',
                'description' => 'Cenaze hizmetleri ve işlemleri',
                'icon' => 'fas fa-heart',
                'color' => '#6c757d',
                'order' => 1,
            ],
            [
                'name' => 'Çevre/Gürültü/İklim',
                'description' => 'Çevre koruma, gürültü kontrolü ve iklim değişikliği konuları',
                'icon' => 'fas fa-leaf',
                'color' => '#28a745',
                'order' => 2,
            ],
            [
                'name' => 'Denetleme/Şeffaflık',
                'description' => 'Belediye denetim ve şeffaflık uygulamaları',
                'icon' => 'fas fa-search',
                'color' => '#17a2b8',
                'order' => 3,
            ],
            [
                'name' => 'Eğitim',
                'description' => 'Eğitim hizmetleri ve kurslar',
                'icon' => 'fas fa-graduation-cap',
                'color' => '#007bff',
                'order' => 4,
            ],
            [
                'name' => 'Engelli Hizmetleri',
                'description' => 'Engelli vatandaşlar için özel hizmetler',
                'icon' => 'fas fa-wheelchair',
                'color' => '#6610f2',
                'order' => 5,
            ],
            [
                'name' => 'Erişilebilirlik',
                'description' => 'Erişilebilirlik ve ulaşım kolaylığı hizmetleri',
                'icon' => 'fas fa-universal-access',
                'color' => '#e83e8c',
                'order' => 6,
            ],
            [
                'name' => 'Etkinlik/Organizasyon',
                'description' => 'Belediye etkinlikleri ve organizasyonlar',
                'icon' => 'fas fa-calendar-alt',
                'color' => '#fd7e14',
                'order' => 7,
            ],
            [
                'name' => 'Harita/Kentsel Dönüşüm',
                'description' => 'Harita hizmetleri ve kentsel dönüşüm projeleri',
                'icon' => 'fas fa-map',
                'color' => '#20c997',
                'order' => 8,
            ],
            [
                'name' => 'Hukuk',
                'description' => 'Hukuki danışmanlık ve hizmetler',
                'icon' => 'fas fa-gavel',
                'color' => '#495057',
                'order' => 9,
            ],
            [
                'name' => 'İmar/Yapı',
                'description' => 'İmar planları ve yapı ruhsatları',
                'icon' => 'fas fa-building',
                'color' => '#6c757d',
                'order' => 10,
            ],
            [
                'name' => 'İtfaiye',
                'description' => 'İtfaiye hizmetleri ve acil durum müdahaleleri',
                'icon' => 'fas fa-fire-extinguisher',
                'color' => '#dc3545',
                'order' => 11,
            ],
            [
                'name' => 'Kadın/Aile',
                'description' => 'Kadın ve aile hizmetleri',
                'icon' => 'fas fa-female',
                'color' => '#e83e8c',
                'order' => 12,
            ],
            [
                'name' => 'Kültür/Sanat',
                'description' => 'Kültür ve sanat etkinlikleri',
                'icon' => 'fas fa-palette',
                'color' => '#6610f2',
                'order' => 13,
            ],
            [
                'name' => 'Muhtarlık',
                'description' => 'Muhtarlık işlemleri ve hizmetler',
                'icon' => 'fas fa-user-tie',
                'color' => '#343a40',
                'order' => 14,
            ],
            [
                'name' => 'Nikah',
                'description' => 'Nikah işlemleri ve nikah sarayı hizmetleri',
                'icon' => 'fas fa-heart',
                'color' => '#e83e8c',
                'order' => 15,
            ],
            [
                'name' => 'Park/Bahçe',
                'description' => 'Park ve bahçe bakım hizmetleri',
                'icon' => 'fas fa-tree',
                'color' => '#28a745',
                'order' => 16,
            ],
            [
                'name' => 'Pazaryeri',
                'description' => 'Pazar yerleri ve esnaf hizmetleri',
                'icon' => 'fas fa-shopping-basket',
                'color' => '#fd7e14',
                'order' => 17,
            ],
            [
                'name' => 'Sağlık',
                'description' => 'Sağlık hizmetleri ve sağlık ocakları',
                'icon' => 'fas fa-heartbeat',
                'color' => '#dc3545',
                'order' => 18,
            ],
            [
                'name' => 'Sosyal Hizmetler',
                'description' => 'Sosyal yardım ve destek hizmetleri',
                'icon' => 'fas fa-hands-helping',
                'color' => '#17a2b8',
                'order' => 19,
            ],
            [
                'name' => 'Spor',
                'description' => 'Spor tesisleri ve spor etkinlikleri',
                'icon' => 'fas fa-running',
                'color' => '#007bff',
                'order' => 20,
            ],
            [
                'name' => 'Temizlik',
                'description' => 'Şehir temizliği ve atık yönetimi',
                'icon' => 'fas fa-broom',
                'color' => '#28a745',
                'order' => 21,
            ],
            [
                'name' => 'Ulaşım',
                'description' => 'Toplu taşıma ve ulaşım hizmetleri',
                'icon' => 'fas fa-bus',
                'color' => '#007bff',
                'order' => 22,
            ],
            [
                'name' => 'Veteriner',
                'description' => 'Veteriner hizmetleri ve hayvan sağlığı',
                'icon' => 'fas fa-paw',
                'color' => '#6610f2',
                'order' => 23,
            ],
            [
                'name' => 'Yardım/Destek',
                'description' => 'Vatandaş yardım ve destek hizmetleri',
                'icon' => 'fas fa-handshake',
                'color' => '#20c997',
                'order' => 24,
            ],
            [
                'name' => 'Zabıta',
                'description' => 'Zabıta hizmetleri ve denetimler',
                'icon' => 'fas fa-shield-alt',
                'color' => '#495057',
                'order' => 25,
            ],
        ];

        foreach ($topics as $topicData) {
            ServiceTopic::create([
                'name' => $topicData['name'],
                'slug' => Str::slug($topicData['name']),
                'description' => $topicData['description'],
                'icon' => $topicData['icon'],
                'color' => $topicData['color'],
                'order' => $topicData['order'],
                'is_active' => true,
                'meta_title' => $topicData['name'] . ' | Çankaya Belediyesi',
                'meta_description' => $topicData['description'] . ' - Çankaya Belediyesi hizmetleri',
            ]);
        }

        $this->command->info('25 adet hizmet konusu başarıyla oluşturuldu!');
    }
} 