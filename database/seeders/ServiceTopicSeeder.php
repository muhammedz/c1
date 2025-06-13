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
        // Kullanıcının verdiği listeyi birebir aynı şekilde ekliyoruz
        $serviceTopics = [
            [
                'name' => 'Cenaze',
                'description' => 'Cenaze hizmetleri ve işlemleri',
                'icon' => 'fas fa-cross',
                'color' => '#6c757d',
                'order' => 1
            ],
            [
                'name' => 'Çevre, Gürültü ve İklim Değişikliği',
                'description' => 'Çevre koruma, gürültü kontrolü ve iklim değişikliği ile ilgili hizmetler',
                'icon' => 'fas fa-leaf',
                'color' => '#28a745',
                'order' => 2
            ],
            [
                'name' => 'Denetim ve Şeffaflık',
                'description' => 'Denetim ve şeffaflık hizmetleri',
                'icon' => 'fas fa-search',
                'color' => '#17a2b8',
                'order' => 3
            ],
            [
                'name' => 'Eğitim',
                'description' => 'Eğitim ve öğretim hizmetleri',
                'icon' => 'fas fa-graduation-cap',
                'color' => '#007bff',
                'order' => 4
            ],
            [
                'name' => 'Geri Dönüşüm ve Dönüştürülebilir Atıklar',
                'description' => 'Geri dönüşüm ve atık yönetimi hizmetleri',
                'icon' => 'fas fa-recycle',
                'color' => '#28a745',
                'order' => 5
            ],
            [
                'name' => 'Sokak Hayvanları, Hayvan ve Veterinerlik',
                'description' => 'Sokak hayvanları ve veteriner hizmetleri',
                'icon' => 'fas fa-paw',
                'color' => '#fd7e14',
                'order' => 6
            ],
            [
                'name' => 'İhale',
                'description' => 'İhale işlemleri ve hizmetleri',
                'icon' => 'fas fa-gavel',
                'color' => '#6f42c1',
                'order' => 7
            ],
            [
                'name' => 'İlaçlama, Böcek, Sinek ve Haşere ile Mücadele',
                'description' => 'İlaçlama ve haşere mücadelesi hizmetleri',
                'icon' => 'fas fa-spray-can',
                'color' => '#dc3545',
                'order' => 8
            ],
            [
                'name' => 'İmar: İnşaat ve Yapılaşma',
                'description' => 'İmar planları, inşaat ruhsatları ve yapılaşma hizmetleri',
                'icon' => 'fas fa-building',
                'color' => '#6c757d',
                'order' => 9
            ],
            [
                'name' => 'İşyeri Ruhsatı, Dükkan, Özel İşletme',
                'description' => 'İşletme ruhsatları ve ticari işlemler',
                'icon' => 'fas fa-store',
                'color' => '#20c997',
                'order' => 10
            ],
            [
                'name' => 'Kanun ve Mevzuat',
                'description' => 'Yasal düzenlemeler ve mevzuat bilgileri',
                'icon' => 'fas fa-balance-scale',
                'color' => '#495057',
                'order' => 11
            ],
            [
                'name' => 'Kültür (Sanat Hariç)',
                'description' => 'Kültür ve tarih ile ilgili hizmetler',
                'icon' => 'fas fa-landmark',
                'color' => '#e83e8c',
                'order' => 12
            ],
            [
                'name' => 'Meclis Kararı',
                'description' => 'Meclis kararları ve uygulamaları',
                'icon' => 'fas fa-users',
                'color' => '#6f42c1',
                'order' => 13
            ],
            [
                'name' => 'Park, Doğa ve Yeşil Alan',
                'description' => 'Park, bahçe ve yeşil alan hizmetleri',
                'icon' => 'fas fa-tree',
                'color' => '#28a745',
                'order' => 14
            ],
            [
                'name' => 'Pazar Yerleri ve Sokak Satışları',
                'description' => 'Pazar yerleri ve sokak satış düzenlemeleri',
                'icon' => 'fas fa-shopping-basket',
                'color' => '#fd7e14',
                'order' => 15
            ],
            [
                'name' => 'Plan ve Harita',
                'description' => 'Şehir planlaması ve harita hizmetleri',
                'icon' => 'fas fa-map',
                'color' => '#17a2b8',
                'order' => 16
            ],
            [
                'name' => 'Sağlık',
                'description' => 'Sağlık hizmetleri ve uygulamaları',
                'icon' => 'fas fa-heartbeat',
                'color' => '#dc3545',
                'order' => 17
            ],
            [
                'name' => 'Spor ve Sağlık',
                'description' => 'Spor tesisleri ve sağlıklı yaşam hizmetleri',
                'icon' => 'fas fa-dumbbell',
                'color' => '#007bff',
                'order' => 18
            ],
            [
                'name' => 'Sokak Temizliği; Kamu Alanları, Bakım ve Güvenliği',
                'description' => 'Temizlik, bakım ve güvenlik hizmetleri',
                'icon' => 'fas fa-broom',
                'color' => '#20c997',
                'order' => 19
            ],
            [
                'name' => 'Vergi Ödemesi',
                'description' => 'Vergi ödemeleri ve mali işlemler',
                'icon' => 'fas fa-money-bill',
                'color' => '#ffc107',
                'order' => 20
            ],
            [
                'name' => 'Yardım ve Bağış',
                'description' => 'Sosyal yardım ve bağış hizmetleri',
                'icon' => 'fas fa-hands-helping',
                'color' => '#e83e8c',
                'order' => 21
            ],
            [
                'name' => 'Sanat (Görsel, Müzik, Edebiyat)',
                'description' => 'Görsel sanatlar, müzik ve edebiyat hizmetleri',
                'icon' => 'fas fa-music',
                'color' => '#6f42c1',
                'order' => 22
            ],
            [
                'name' => 'Yol ve Kaldırım',
                'description' => 'Yol yapımı ve kaldırım hizmetleri',
                'icon' => 'fas fa-road',
                'color' => '#495057',
                'order' => 23
            ],
            [
                'name' => 'Zabıta: Pazar ve İşyeri Denetimi',
                'description' => 'Zabıta denetimleri ve kontrolleri',
                'icon' => 'fas fa-shield-alt',
                'color' => '#dc3545',
                'order' => 24
            ],
            [
                'name' => 'Cinsiyet Eşitliği, Aile İçi Şiddet',
                'description' => 'Cinsiyet eşitliği ve aile içi şiddetle mücadele hizmetleri',
                'icon' => 'fas fa-venus-mars',
                'color' => '#e83e8c',
                'order' => 25
            ]
        ];

        foreach ($serviceTopics as $topicData) {
            // Slug oluştur
            $slug = Str::slug($topicData['name']);
            
            // Slug benzersizliği kontrolü
            $originalSlug = $slug;
            $counter = 1;
            while (ServiceTopic::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
            
            ServiceTopic::create([
                'name' => $topicData['name'],
                'slug' => $slug,
                'description' => $topicData['description'],
                'icon' => $topicData['icon'],
                'color' => $topicData['color'],
                'order' => $topicData['order'],
                'is_active' => true,
                'meta_title' => $topicData['name'] . ' Hizmetleri | Çankaya Belediyesi',
                'meta_description' => 'Çankaya Belediyesi ' . $topicData['name'] . ' konusundaki tüm hizmetleri keşfedin.'
            ]);
        }
        
        $this->command->info('25 hizmet konusu başarıyla eklendi!');
    }
} 