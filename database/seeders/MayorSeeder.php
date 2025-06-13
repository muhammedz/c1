<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Mayor;
use App\Models\MayorContent;

class MayorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Başkan kaydı oluştur
        $mayor = Mayor::create([
            'name' => 'Av. Hüseyin Can Güner',
            'title' => 'Belediye Başkanı',
            'biography' => '1993 yılında Ankara\'da doğdu. Avukat, Ankara Üniversitesi Hukuk Fakültesi\'nden 2015 yılında mezun oldu. Cumhuriyet Halk Partisi\'ne 2012 yılında kaydoldu. Ankara İl Gençlik Kolları Üniversite Komisyonunda, Sosyal Demokrat Öğrenciler örgütlenmesinde ve Çankaya İlçe Gençlik Kolunda çalışmalar yürüttü.',
            'social_twitter' => 'https://x.com/hcanguner',
            'social_instagram' => 'https://www.instagram.com/hcanguner/',
            'social_email' => 'huseyincanguner@cankaya.bel.tr',
            'page_title' => 'Başkanımız',
            'meta_description' => 'Başkanımızın biyografisi, faaliyetleri ve duyuruları',
            'hero_bg_color' => '#00352b',
            'is_active' => true
        ]);

        // Hikayeler
        $stories = [
            [
                'title' => 'Belediye Projeleri',
                'description' => 'Başkan Güner, sürdürülebilir belediyecilik anlayışıyla yeni projeleri hayata geçiriyor.',
                'extra_data' => [
                    'category' => 'projeler',
                    'gradient' => 'from-amber-500 to-green-500'
                ]
            ],
            [
                'title' => 'Etkinlikler',
                'description' => 'Çankaya\'da kültürel ve sanatsal etkinliklerle dolu bir yaz sezonu başlıyor.',
                'extra_data' => [
                    'category' => 'etkinlikler',
                    'gradient' => 'from-purple-500 to-pink-500'
                ]
            ],
            [
                'title' => 'Mahalle Buluşmaları',
                'description' => 'Başkan Güner, mahalle sakinleriyle bir araya geliyor, sorunları yerinde tespit ediyor.',
                'extra_data' => [
                    'category' => 'bulusmalar',
                    'gradient' => 'from-blue-500 to-cyan-400'
                ]
            ],
            [
                'title' => 'Kent Tarımı',
                'description' => 'Çankaya\'da kent tarımı projeleriyle organik tarım yaygınlaşıyor, yerel üretim destekleniyor.',
                'extra_data' => [
                    'category' => 'tarim',
                    'gradient' => 'from-green-500 to-emerald-400'
                ]
            ],
            [
                'title' => 'Eğitim Destekleri',
                'description' => 'Gençlere ve çocuklara yönelik eğitim destekleri artarak devam ediyor.',
                'extra_data' => [
                    'category' => 'egitim',
                    'gradient' => 'from-red-500 to-orange-400'
                ]
            ]
        ];

        foreach ($stories as $index => $story) {
            MayorContent::create([
                'mayor_id' => $mayor->id,
                'type' => 'story',
                'title' => $story['title'],
                'description' => $story['description'],
                'extra_data' => $story['extra_data'],
                'sort_order' => $index + 1,
                'is_active' => true
            ]);
        }

        // Gündem
        $agenda = [
            [
                'title' => 'Belediye Meclis Toplantısı',
                'description' => 'Çankaya Belediyesi meclis toplantısında başkanlık yapacak ve bütçe görüşmelerini yönetecek.',
                'extra_data' => [
                    'event_date' => '2024-06-24',
                    'event_time' => '10:00',
                    'location' => 'Çankaya Belediyesi Meclis Salonu',
                    'event_type' => 'toplantı',
                    'participants' => 'Meclis Üyeleri'
                ]
            ],
            [
                'title' => 'Kent Tarımı Projesi Açılışı',
                'description' => 'Çankaya Belediyesi\'nin yürüttüğü Kent Tarımı Projesi\'nin açılış törenine katılacak ve konuşma yapacak.',
                'extra_data' => [
                    'event_date' => '2024-06-25',
                    'event_time' => '14:30',
                    'location' => 'Çankaya Kent Tarım Alanı',
                    'event_type' => 'açılış',
                    'participants' => 'Halka Açık'
                ]
            ],
            [
                'title' => 'Mahalle Ziyaretleri',
                'description' => 'Çankaya\'daki mahalleleri ziyaret ederek vatandaşların sorunlarını dinleyecek ve çözüm önerileri sunacak.',
                'extra_data' => [
                    'event_date' => '2024-06-27',
                    'event_time' => '09:00',
                    'location' => 'Çukurambar ve 100.Yıl Mahalleleri',
                    'event_type' => 'ziyaret',
                    'participants' => 'Mahalle Sakinleri'
                ]
            ]
        ];

        foreach ($agenda as $index => $item) {
            MayorContent::create([
                'mayor_id' => $mayor->id,
                'type' => 'agenda',
                'title' => $item['title'],
                'description' => $item['description'],
                'extra_data' => $item['extra_data'],
                'sort_order' => $index + 1,
                'is_active' => true
            ]);
        }

        // Değerler
        $values = [
            [
                'title' => 'Sürdürülebilirlik',
                'description' => 'Çevre dostu ve sürdürülebilir projelerle geleceğe yatırım',
                'extra_data' => [
                    'icon' => 'eco',
                    'color' => 'green'
                ]
            ],
            [
                'title' => 'Katılımcılık',
                'description' => 'Vatandaşlarımızın karar alma süreçlerine aktif katılımı',
                'extra_data' => [
                    'icon' => 'people',
                    'color' => 'blue'
                ]
            ],
            [
                'title' => 'Sosyal Adalet',
                'description' => 'Eşit hizmet ve fırsat adaleti ilkesi',
                'extra_data' => [
                    'icon' => 'balance',
                    'color' => 'purple'
                ]
            ],
            [
                'title' => 'Şeffaflık',
                'description' => 'Açık ve hesap verebilir yönetim anlayışı',
                'extra_data' => [
                    'icon' => 'visibility',
                    'color' => 'amber'
                ]
            ]
        ];

        foreach ($values as $index => $value) {
            MayorContent::create([
                'mayor_id' => $mayor->id,
                'type' => 'value',
                'title' => $value['title'],
                'description' => $value['description'],
                'extra_data' => $value['extra_data'],
                'sort_order' => $index + 1,
                'is_active' => true
            ]);
        }

        // Galeri
        $gallery = [
            [
                'title' => 'Mahalle Ziyareti',
                'description' => 'Başkan Güner mahalle sakinleriyle buluşuyor',
                'extra_data' => [
                    'category' => 'ziyaretler',
                    'alt_text' => 'Mahalle Ziyareti'
                ]
            ],
            [
                'title' => 'Meclis Toplantısı',
                'description' => 'Belediye Meclis toplantısından kareler',
                'extra_data' => [
                    'category' => 'toplantılar',
                    'alt_text' => 'Meclis Toplantısı'
                ]
            ],
            [
                'title' => 'Kent Tarımı',
                'description' => 'Kent tarımı projesi çalışmaları',
                'extra_data' => [
                    'category' => 'projeler',
                    'alt_text' => 'Kent Tarımı'
                ]
            ],
            [
                'title' => 'Etkinlik',
                'description' => 'Çankaya festivali etkinlikleri',
                'extra_data' => [
                    'category' => 'etkinlikler',
                    'alt_text' => 'Festival Etkinliği'
                ]
            ]
        ];

        foreach ($gallery as $index => $item) {
            MayorContent::create([
                'mayor_id' => $mayor->id,
                'type' => 'gallery',
                'title' => $item['title'],
                'description' => $item['description'],
                'extra_data' => $item['extra_data'],
                'sort_order' => $index + 1,
                'is_active' => true
            ]);
        }
    }
}
