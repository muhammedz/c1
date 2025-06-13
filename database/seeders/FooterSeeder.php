<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FooterMenu;
use App\Models\FooterMenuLink;
use App\Models\FooterSetting;

class FooterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Footer ayarlarını oluştur
        FooterSetting::create([
            'company_name' => 'Çankaya',
            'company_subtitle' => 'BELEDİYESİ',
            'address_line1' => 'Ziya Gökalp Caddesi',
            'address_line2' => 'No: 11 Kızılay/Ankara',
            'contact_center_title' => 'Çankaya İletişim Merkezi',
            'contact_center_phone' => '444 06 01',
            'whatsapp_title' => 'Whatsapp Hattı',
            'whatsapp_number' => '0(505) 167 19 67',
            'email_title' => 'E-Posta',
            'email_address' => 'iletisimmerkezi@cankaya.bel.tr',
            'kep_title' => 'Kep Adresi',
            'kep_address' => 'cankayabelediyesi@hs01.kep.tr',
            'copyright_left' => '© 2024 Çankaya Belediyesi. Tüm hakları saklıdır.',
            'copyright_right' => 'web tasarım by'
        ]);

        // KURUMSAL menüsü
        $kurumsal = FooterMenu::create([
            'title' => 'KURUMSAL',
            'order' => 1,
            'is_active' => true
        ]);

        $kurumsalLinks = [
            'Başkan',
            'Belediye Encümeni',
            'Belediye Meclisi',
            'Meclis Üyeleri',
            'Etik Komisyon Üyeleri',
            'Şirketler',
            'Başkan Yardımcıları',
            'Meclis Çalışma Komisyonları',
            'Uluslararası İş Birlikleri',
            'Önceki Dönem Belediye Başkanlarımız',
            'Müdürlükler',
            'Misyon Vizyon İlkelerimiz',
            'Bilgi Güvenliği Politikamız',
            'Kişisel Verilerinizi Önemsiyoruz'
        ];

        foreach ($kurumsalLinks as $index => $link) {
            FooterMenuLink::create([
                'footer_menu_id' => $kurumsal->id,
                'title' => $link,
                'url' => '#',
                'order' => $index + 1,
                'is_active' => true
            ]);
        }

        // ÇANKAYAMIZ menüsü
        $cankayamiz = FooterMenu::create([
            'title' => 'ÇANKAYAMIZ',
            'order' => 2,
            'is_active' => true
        ]);

        $cankayamizLinks = [
            'Rakamlarla Çankaya',
            'Anıtkabir\'in Tarihi',
            'Kültürel Miras',
            'Tarihçe',
            'Doğal Yapı',
            'Çankaya\'nın Adı',
            'Antik Tarih',
            'Ekonomik Yaşam',
            'Başkent Ankara',
            'Kültürel Yaşam'
        ];

        foreach ($cankayamizLinks as $index => $link) {
            FooterMenuLink::create([
                'footer_menu_id' => $cankayamiz->id,
                'title' => $link,
                'url' => '#',
                'order' => $index + 1,
                'is_active' => true
            ]);
        }

        // PROJELERİMİZ menüsü
        $projelerimiz = FooterMenu::create([
            'title' => 'PROJELERİMİZ',
            'order' => 3,
            'is_active' => true
        ]);

        $projelerimizLinks = [
            'Gerçekleşen Projeler',
            'Planlanan Projeler',
            'Devam Eden Projeler',
            'Uluslararası Projeler',
            'Sosyal Projeler'
        ];

        foreach ($projelerimizLinks as $index => $link) {
            FooterMenuLink::create([
                'footer_menu_id' => $projelerimiz->id,
                'title' => $link,
                'url' => '#',
                'order' => $index + 1,
                'is_active' => true
            ]);
        }

        // HİZMETLERİMİZ menüsü
        $hizmetlerimiz = FooterMenu::create([
            'title' => 'HİZMETLERİMİZ',
            'order' => 4,
            'is_active' => true
        ]);

        $hizmetlerimizLinks = [
            'Sosyal Hizmetler',
            'Parklarımız',
            'Kültür Ve Sanat Hizmetleri',
            'Sağlık Hizmetleri',
            'Engelli Destek Hizmetleri',
            'Temizlik Hizmetleri',
            'Veteriner Hizmetleri',
            'Spor Ve Sosyal Tesislerimiz',
            'Geri Dönüşüm Hizmetleri',
            'Günüz Bakımevleri',
            'Zabıta Hizmetleri',
            'Emlak ve İstimlak Hizmetleri',
            'İlaçlama Ve Çiçek Denetimi',
            'Pazar Yerleri',
            'Vergi Ödeme',
            'Çayed Yarım Elma',
            'Kütüphanelerimiz'
        ];

        foreach ($hizmetlerimizLinks as $index => $link) {
            FooterMenuLink::create([
                'footer_menu_id' => $hizmetlerimiz->id,
                'title' => $link,
                'url' => '#',
                'order' => $index + 1,
                'is_active' => true
            ]);
        }
    }
}
