<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class NewsAndCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kategoriler
        $categories = [
            [
                'name' => 'Belediye Haberleri',
                'slug' => 'belediye-haberleri',
                'description' => 'Belediye ile ilgili duyurular, etkinlikler ve projeler.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kültür Sanat',
                'slug' => 'kultur-sanat',
                'description' => 'Kültürel etkinlikler, sergiler, konserler ve sanatsal faaliyetler.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Spor',
                'slug' => 'spor',
                'description' => 'Spor etkinlikleri, müsabakalar ve sportif faaliyetler.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Eğitim',
                'slug' => 'egitim',
                'description' => 'Eğitim faaliyetleri, kurslar, seminerler ve eğitimle ilgili duyurular.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sağlık',
                'slug' => 'saglik',
                'description' => 'Sağlık hizmetleri, bilinçlendirme kampanyaları ve sağlık etkinlikleri.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        
        // Kategorileri ekle ve ID'leri sakla
        $categoryMap = [];
        foreach ($categories as $category) {
            $categoryName = $category['name'];
            $categoryId = DB::table('news_categories')->insertGetId($category);
            $categoryMap[$categoryName] = $categoryId;
        }
        
        // Her kategori için 8 haber oluştur
        $image = '/uploads/images/XZVe3QziFNTSDO0di0Ub7sKg0GbAMXZ3Lbt7dVuU.webp';
        $loremIpsum = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec auctor, nisl eget ultricies ultricies, nisl nisl ultricies nisl, eget ultricies nisl nisl eget ultricies. Donec auctor, nisl eget ultricies ultricies, nisl nisl ultricies nisl, eget ultricies nisl nisl eget ultricies. Donec auctor, nisl eget ultricies ultricies, nisl nisl ultricies nisl, eget ultricies nisl nisl eget ultricies.

Proin ac ante a quam volutpat scelerisque. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae.

<h3>Önemli Başlık</h3>

<p>Nullam faucibus, sem vitae varius eleifend, ex turpis tincidunt ex, vel efficitur arcu nisl id magna. Nulla facilisi. Nulla facilisi. Nulla facilisi. Nulla facilisi. Nulla facilisi. Nulla facilisi. Nullam faucibus, sem vitae varius eleifend, ex turpis tincidunt ex, vel efficitur arcu nisl id magna.</p>

<ul>
    <li>Madde 1: Lorem ipsum dolor sit amet</li>
    <li>Madde 2: Consectetur adipiscing elit</li>
    <li>Madde 3: Proin ac ante a quam</li>
</ul>

Curabitur vel magna vel nunc lacinia lacinia. Curabitur vel magna vel nunc lacinia lacinia. Curabitur vel magna vel nunc lacinia lacinia. Curabitur vel magna vel nunc lacinia lacinia. Curabitur vel magna vel nunc lacinia lacinia.';
        
        foreach ($categoryMap as $categoryName => $categoryId) {
            // Bu kategori için 8 haber oluştur
            for ($i = 1; $i <= 8; $i++) {
                $title = $this->generateNewsTitle($categoryName, $i);
                $slug = Str::slug($title);
                $publishedAt = Carbon::now()->subDays(rand(1, 30))->subHours(rand(1, 24));
                
                $newsId = DB::table('news')->insertGetId([
                    'title' => $title,
                    'slug' => $slug,
                    'content' => $loremIpsum,
                    'image' => $image,
                    'status' => 'published',
                    'published_at' => $publishedAt,
                    'created_at' => $publishedAt,
                    'updated_at' => $publishedAt,
                ]);
                
                // Haber-kategori ilişkisini ekle
                DB::table('news_category')->insert([
                    'news_id' => $newsId,
                    'category_id' => $categoryId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        
        $this->command->info('5 kategori ve her kategoride 8 haber başarıyla eklendi. Toplam 40 haber oluşturuldu.');
    }
    
    /**
     * Kategoriye göre farklı haber başlıkları oluştur
     */
    private function generateNewsTitle($category, $index)
    {
        $titles = [
            'Belediye Haberleri' => [
                'Belediye Başkanından Önemli Açıklama',
                'Yeni Park Projesi İçin Çalışmalar Başladı',
                'Belediye Meclisi Yeni Kararlar Aldı',
                'Mahallemizde Altyapı Çalışmaları Tamamlandı',
                'Belediye Yeni Hizmet Binası Açıldı',
                'Çevre Düzenleme Projesi Başarıyla Tamamlandı',
                'Belediye Sosyal Yardım Kampanyası Başlattı',
                'Toplu Taşıma Hatlarında Güncelleme',
            ],
            'Kültür Sanat' => [
                'Şehir Tiyatrosu Yeni Sezona Hazır',
                'Uluslararası Sanat Festivali Başlıyor',
                'Tarihi Mekan Restorasyonu Tamamlandı',
                'Yerel Sanatçılar Sergisi Açıldı',
                'Müzik Festivali Bu Hafta Sonu Başlıyor',
                'Çocuklar İçin Sanat Atölyeleri Düzenleniyor',
                'Tarihi Eserlerin Dijital Arşivi Oluşturuldu',
                'Kültür Merkezine Yeni Etkinlik Salonu',
            ],
            'Spor' => [
                'Yerel Futbol Turnuvası Başlıyor',
                'Yüzme Havuzu Yenilendi ve Hizmete Açıldı',
                'Spor Kompleksi İnşaatı Hızla İlerliyor',
                'Gençlik Spor Kulübünden Büyük Başarı',
                'Yaz Spor Okulları Kayıtları Başladı',
                'Bisiklet Yolları Projesi Genişliyor',
                'Amatör Spor Kulüplerine Destek Paketi',
                'Spor Şenliklerinde Rekor Katılım',
            ],
            'Eğitim' => [
                'Okullarımızda Yeni Teknoloji Sınıfları',
                'Öğrencilere Ücretsiz Kurslar Başlıyor',
                'Eğitim Kurumlarında Güvenlik Önlemleri Artırıldı',
                'Okul Kütüphaneleri Yenileniyor',
                'Eğitimcilere Özel Seminer Düzenlendi',
                'Yeni Eğitim Tesisi Hizmete Açıldı',
                'Öğrencilere Kariyer Günleri Başlıyor',
                'Okullarda Çevre Bilinci Eğitimi',
            ],
            'Sağlık' => [
                'Yeni Sağlık Ocağı Hizmete Açıldı',
                'Ücretsiz Sağlık Taramaları Başlıyor',
                'Sağlıklı Yaşam İçin Yürüyüş Etkinliği',
                'Covid-19 Aşı Merkezi Kuruldu',
                'Sağlık Personeline Teşekkür Töreni',
                'Anne-Bebek Sağlığı Seminerleri Düzenleniyor',
                'Evde Sağlık Hizmetleri Genişletildi',
                'Sağlıklı Beslenme Kampanyası Başlatıldı',
            ],
        ];
        
        return $titles[$category][$index - 1] ?? "Haber Başlığı - $category $index";
    }
}
