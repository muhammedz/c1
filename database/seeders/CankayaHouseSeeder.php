<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CankayaHouse;
use App\Models\CankayaHouseCourse;
use Carbon\Carbon;

class CankayaHouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mevcut verileri temizle
        CankayaHouseCourse::query()->delete();
        CankayaHouse::query()->delete();
        
        // Çankaya Evleri
        $houses = [
            [
                'name' => 'Merkez Çankaya Evi',
                'slug' => 'merkez-cankaya-evi',
                'description' => 'Çankaya Belediyesi\'nin merkez lokasyonundaki eğitim merkezi. Modern donanımı ve geniş kurs seçenekleri ile hizmet vermektedir.',
                'address' => 'Kızılırmak Mahallesi, Atatürk Bulvarı No:123, Çankaya/Ankara',
                'phone' => '0312 555 0101',
                'location_link' => 'https://maps.google.com/?q=39.9208,32.8541',
                'images' => [],
                'status' => 'active',
                'order' => 1
            ],
            [
                'name' => 'Bahçelievler Çankaya Evi',
                'slug' => 'bahcelievler-cankaya-evi',
                'description' => 'Bahçelievler bölgesinde bulunan modern eğitim merkezi. Sanat ve el sanatları kursları konusunda uzmanlaşmıştır.',
                'address' => 'Bahçelievler Mahallesi, 7. Cadde No:45, Çankaya/Ankara',
                'phone' => '0312 555 0102',
                'location_link' => 'https://maps.google.com/?q=39.9108,32.8441',
                'images' => [],
                'status' => 'active',
                'order' => 2
            ],
            [
                'name' => 'Kavaklıdere Çankaya Evi',
                'slug' => 'kavaklidere-cankaya-evi',
                'description' => 'Kavaklıdere\'nin kalbi sayılan lokasyonda yer alan eğitim merkezi. Teknoloji ve dijital beceri kursları sunmaktadır.',
                'address' => 'Kavaklıdere Mahallesi, Tunalı Hilmi Caddesi No:67, Çankaya/Ankara',
                'phone' => '0312 555 0103',
                'location_link' => 'https://maps.google.com/?q=39.9308,32.8641',
                'images' => [],
                'status' => 'active',
                'order' => 3
            ],
            [
                'name' => 'Çayyolu Çankaya Evi',
                'slug' => 'cayyolu-cankaya-evi',
                'description' => 'Çayyolu bölgesinin modern yaşam merkezinde konumlanan eğitim merkezi. Spor ve sağlık kursları ağırlıklıdır.',
                'address' => 'Çayyolu Mahallesi, Alacaatlı Caddesi No:89, Çankaya/Ankara',
                'phone' => '0312 555 0104',
                'location_link' => 'https://maps.google.com/?q=39.9408,32.8741',
                'images' => [],
                'status' => 'active',
                'order' => 4
            ],
            [
                'name' => 'Balgat Çankaya Evi',
                'slug' => 'balgat-cankaya-evi',
                'description' => 'Balgat bölgesinde hizmet veren eğitim merkezi. Çocuk ve gençlik programları konusunda öne çıkmaktadır.',
                'address' => 'Balgat Mahallesi, Ziya Gökalp Caddesi No:34, Çankaya/Ankara',
                'phone' => '0312 555 0105',
                'location_link' => 'https://maps.google.com/?q=39.9508,32.8841',
                'images' => [],
                'status' => 'active',
                'order' => 5
            ],
            [
                'name' => 'Ayrancı Çankaya Evi',
                'slug' => 'ayranci-cankaya-evi',
                'description' => 'Ayrancı\'nın merkezi konumunda bulunan eğitim merkezi. Kültür ve sanat etkinlikleri düzenlemektedir.',
                'address' => 'Ayrancı Mahallesi, Güvenlik Caddesi No:56, Çankaya/Ankara',
                'phone' => '0312 555 0106',
                'location_link' => 'https://maps.google.com/?q=39.9608,32.8941',
                'images' => [],
                'status' => 'active',
                'order' => 6
            ]
        ];

        foreach ($houses as $houseData) {
            $house = CankayaHouse::create($houseData);
            
            // Her ev için kurslar ekle
            $this->createCoursesForHouse($house);
        }
    }

    private function createCoursesForHouse(CankayaHouse $house)
    {
        $courseTemplates = [
            // Sanat ve El Sanatları
            [
                'name' => 'Resim Kursu',
                'description' => 'Temel resim teknikleri ve sanat tarihi eğitimi. Başlangıç seviyesinden ileri seviyeye kadar.',
                'instructor' => 'Ayşe Yılmaz',
                'capacity' => 15,
                'price' => 250.00
            ],
            [
                'name' => 'Seramik Atölyesi',
                'description' => 'Çömlek yapımı ve seramik boyama teknikleri öğretilmektedir.',
                'instructor' => 'Mehmet Kaya',
                'capacity' => 12,
                'price' => 300.00
            ],
            [
                'name' => 'El Örgüsü Kursu',
                'description' => 'Örgü teknikleri, desen okuma ve yaratıcı projeler.',
                'instructor' => 'Fatma Demir',
                'capacity' => 20,
                'price' => null // Ücretsiz
            ],
            
            // Teknoloji ve Dijital
            [
                'name' => 'Bilgisayar Kursu',
                'description' => 'Temel bilgisayar kullanımı, internet ve ofis programları eğitimi.',
                'instructor' => 'Ali Özkan',
                'capacity' => 18,
                'price' => 200.00
            ],
            [
                'name' => 'Dijital Fotoğrafçılık',
                'description' => 'Dijital fotoğraf çekimi, düzenleme ve kompozisyon teknikleri.',
                'instructor' => 'Zeynep Arslan',
                'capacity' => 10,
                'price' => 350.00
            ],
            
            // Spor ve Sağlık
            [
                'name' => 'Yoga Kursu',
                'description' => 'Hatha yoga ve nefes teknikleri ile zihin-beden uyumu.',
                'instructor' => 'Selin Aydın',
                'capacity' => 25,
                'price' => 180.00
            ],
            [
                'name' => 'Pilates',
                'description' => 'Temel pilates hareketleri ve postür düzeltme egzersizleri.',
                'instructor' => 'Deniz Çelik',
                'capacity' => 15,
                'price' => 220.00
            ],
            
            // Dil ve Kültür
            [
                'name' => 'İngilizce Konuşma Kursu',
                'description' => 'Günlük konuşma İngilizcesi ve telaffuz çalışmaları.',
                'instructor' => 'John Smith',
                'capacity' => 20,
                'price' => 400.00
            ],
            [
                'name' => 'Türk Halk Oyunları',
                'description' => 'Geleneksel Türk halk oyunları öğretimi ve sahne performansı.',
                'instructor' => 'Hasan Yıldız',
                'capacity' => 30,
                'price' => null // Ücretsiz
            ],
            
            // Çocuk ve Gençlik
            [
                'name' => 'Çocuk Tiyatrosu',
                'description' => '7-14 yaş arası çocuklar için tiyatro eğitimi ve oyun teknikleri.',
                'instructor' => 'Gül Şahin',
                'capacity' => 16,
                'price' => 150.00
            ],
            [
                'name' => 'Robotik Kodlama',
                'description' => 'Çocuklar için temel robotik ve kodlama eğitimi.',
                'instructor' => 'Emre Koç',
                'capacity' => 12,
                'price' => 320.00
            ]
        ];

        // Her ev için rastgele 4-6 kurs seç
        $selectedCourses = collect($courseTemplates)->random(rand(4, 6));
        
        foreach ($selectedCourses as $courseTemplate) {
            // Farklı tarih aralıkları oluştur
            $startDate = Carbon::now()->addDays(rand(-30, 60));
            $endDate = $startDate->copy()->addDays(rand(30, 90));
            
            CankayaHouseCourse::create([
                'cankaya_house_id' => $house->id,
                'name' => $courseTemplate['name'],
                'description' => $courseTemplate['description'],
                'start_date' => $startDate,
                'end_date' => $endDate,
                'instructor' => $courseTemplate['instructor'],
                'capacity' => $courseTemplate['capacity'],
                'price' => $courseTemplate['price'],
                'status' => 'active'
            ]);
        }
    }
}
