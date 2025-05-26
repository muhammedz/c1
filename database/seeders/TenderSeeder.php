<?php

namespace Database\Seeders;

use App\Models\Tender;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenders = [
            [
                'title' => 'İçme Suyu Şebeke Hattı Yenileme İşi',
                'unit' => 'Su ve Kanalizasyon Müdürlüğü',
                'summary' => 'İlçemiz sınırları içerisinde bulunan eskimiş içme suyu şebeke hatlarının yenilenmesi işi.',
                'kik_no' => '2025/123456',
                'address' => 'Su ve Kanalizasyon Müdürlüğü, Belediye Binası Kat:3, Merkez',
                'phone' => '0212 555 1234',
                'fax' => '0212 555 1235',
                'email' => 'sukanalizasyon@belediye.gov.tr',
                'document_url' => 'https://belediye.gov.tr/ihaleler/icme-suyu-sebeke-dokumanlari.pdf',
                'description' => 'Toplam 25 km uzunluğunda PE100 borular ile içme suyu şebeke hattı yenileme işidir. Çalışma kapsamında hafriyat, boru döşeme, dolgu ve asfalt kaplama işleri yapılacaktır.',
                'delivery_place' => 'Muhtelif Mahalleler',
                'delivery_date' => '180 takvim günü içerisinde teslim edilecektir',
                'tender_address' => 'Belediye Binası, Toplantı Salonu, Zemin Kat',
                'tender_datetime' => Carbon::now()->addDays(30)->setTime(10, 0),
                'content' => '<h3>İhale Bilgileri</h3><p>İlçemiz sınırları içerisindeki içme suyu şebeke hatlarının yenilenmesi işidir. Bu kapsamda;</p><ul><li>Mevcut boruların sökülmesi</li><li>PE100 boruların temini ve döşenmesi</li><li>Vana ve bağlantı parçalarının montajı</li><li>Dolgu ve asfalt kaplama işleri</li></ul><p>yer almaktadır.</p>',
                'status' => 'active',
            ],
            [
                'title' => 'Park ve Bahçelerin Bakım ve Onarım İşi',
                'unit' => 'Park ve Bahçeler Müdürlüğü',
                'summary' => 'İlçe genelindeki park ve bahçelerin yıllık bakım ve onarım hizmet alım işi.',
                'kik_no' => '2025/234567',
                'address' => 'Park ve Bahçeler Müdürlüğü, Hizmet Binası, 1. Kat',
                'phone' => '0212 555 4321',
                'fax' => '0212 555 4322',
                'email' => 'parkbahceler@belediye.gov.tr',
                'document_url' => 'https://belediye.gov.tr/ihaleler/park-bahce-bakim-dokumanlari.pdf',
                'description' => 'İlçe genelinde bulunan 45 adet park ve bahçenin yıllık bakım, onarım ve peyzaj düzenleme işleri. Çim biçme, ağaç budama, sulama sistemleri kontrolü, mevsimlik çiçek dikimi ve park ekipmanlarının bakımı.',
                'delivery_place' => 'İlçe Geneli',
                'delivery_date' => '365 gün süreyle hizmet verilecektir',
                'tender_address' => 'Belediye Ek Hizmet Binası, İhale Salonu',
                'tender_datetime' => Carbon::now()->addDays(45)->setTime(14, 30),
                'content' => '<h3>Hizmet Kapsamı</h3><p>İlçe genelindeki parkların bakım ve onarımını içeren bu ihalede aşağıdaki hizmetler sunulacaktır:</p><ul><li>Düzenli çim biçme işlemleri</li><li>Ağaç ve çalıların budanması</li><li>Sulama sistemlerinin kontrolü ve tamiri</li><li>Mevsimlik çiçek dikimi</li><li>Park mobilyalarının bakım ve onarımı</li><li>Çocuk oyun gruplarının kontrolü ve bakımı</li></ul>',
                'status' => 'active',
            ],
            [
                'title' => 'Belediye Hizmet Binası İnşaatı',
                'unit' => 'Fen İşleri Müdürlüğü',
                'summary' => 'Yeni belediye hizmet binası yapım işi.',
                'kik_no' => '2025/345678',
                'address' => 'Fen İşleri Müdürlüğü, Belediye Ana Hizmet Binası, 2. Kat',
                'phone' => '0212 555 8765',
                'fax' => '0212 555 8766',
                'email' => 'fenisleri@belediye.gov.tr',
                'document_url' => 'https://belediye.gov.tr/ihaleler/hizmet-binasi-dokumanlari.zip',
                'description' => '10.000 m² kapalı alana sahip, 6 katlı betonarme karkas yeni hizmet binası yapım işi. Bina içerisinde ofisler, toplantı salonları, konferans salonu, yemekhane ve kapalı otopark bulunacaktır.',
                'delivery_place' => 'Merkez Mahallesi, 123 Sokak, No: 1',
                'delivery_date' => '540 takvim günü içerisinde tamamlanacaktır',
                'tender_address' => 'Mevcut Belediye Binası, Meclis Salonu',
                'tender_datetime' => Carbon::now()->addDays(60)->setTime(11, 0),
                'content' => '<h3>Yapım İşi Detayları</h3><p>Yeni belediye hizmet binası aşağıdaki özelliklere sahip olacaktır:</p><ul><li>10.000 m² kapalı alan</li><li>6 kat (Zemin + 5 kat)</li><li>100 araçlık kapalı otopark</li><li>300 kişilik konferans salonu</li><li>Modern ofis alanları</li><li>Engelli erişimine uygun tasarım</li><li>Enerji verimli sistemler</li></ul><p>Yapım işi tüm elektrik, mekanik, peyzaj ve çevre düzenleme işlerini kapsamaktadır.</p>',
                'status' => 'active',
            ],
            [
                'title' => 'Yol Asfaltlama ve Kaldırım Yapım İşi',
                'unit' => 'Fen İşleri Müdürlüğü',
                'summary' => 'İlçe genelinde muhtelif cadde ve sokakların asfaltlama ve kaldırım yapım işi.',
                'kik_no' => '2025/456789',
                'address' => 'Fen İşleri Müdürlüğü, Belediye Ana Hizmet Binası, 2. Kat',
                'phone' => '0212 555 8765',
                'fax' => '0212 555 8766',
                'email' => 'fenisleri@belediye.gov.tr',
                'document_url' => 'https://belediye.gov.tr/ihaleler/asfalt-kaldirim-dokumanlari.pdf',
                'description' => 'İlçe genelinde toplam 35 km yolun asfaltlanması ve 20 km kaldırım yapım işi. İş kapsamında altyapı iyileştirmeleri, yağmur suyu drenaj kanalları, bordür ve kaldırım taşı döşemesi yapılacaktır.',
                'delivery_place' => 'İlçe Geneli',
                'delivery_date' => '240 takvim günü içerisinde tamamlanacaktır',
                'tender_address' => 'Belediye Binası, Toplantı Salonu, Zemin Kat',
                'tender_datetime' => Carbon::now()->addDays(15)->setTime(10, 30),
                'content' => '<h3>Yapım İşi Kapsamı</h3><p>Bu ihale kapsamında aşağıdaki işler gerçekleştirilecektir:</p><ul><li>Mevcut asfaltın sökülmesi ve hafriyatı</li><li>Altyapı iyileştirmeleri</li><li>Asfalt serimi (toplam 35 km)</li><li>Yağmur suyu drenaj kanallarının yapımı</li><li>Bordür ve kaldırım taşı döşemesi (toplam 20 km)</li><li>Sinyalizasyon ve yol çizgi işleri</li></ul><p>Tüm malzeme ve işçilik yüklenici tarafından karşılanacaktır.</p>',
                'status' => 'completed',
            ],
            [
                'title' => 'Çöp Toplama ve Nakil Hizmet Alımı',
                'unit' => 'Temizlik İşleri Müdürlüğü',
                'summary' => 'İlçe genelinde evsel atıkların toplanması ve nakil işi.',
                'kik_no' => '2025/567890',
                'address' => 'Temizlik İşleri Müdürlüğü, Belediye Ek Hizmet Binası, Zemin Kat',
                'phone' => '0212 555 9876',
                'fax' => '0212 555 9877',
                'email' => 'temizlik@belediye.gov.tr',
                'document_url' => 'https://belediye.gov.tr/ihaleler/cop-toplama-dokumanlari.pdf',
                'description' => 'İlçe genelinde günlük 120 ton evsel atığın toplanması, taşınması ve bertaraf edilmesi hizmeti. 30 mahallede günlük çöp toplama hizmeti verilecektir.',
                'delivery_place' => 'İlçe Geneli',
                'delivery_date' => '365 gün süreyle hizmet verilecektir',
                'tender_address' => 'Belediye Ek Hizmet Binası, İhale Salonu',
                'tender_datetime' => Carbon::now()->subDays(90)->setTime(11, 0),
                'content' => '<h3>Hizmet Detayları</h3><p>Bu ihale kapsamında aşağıdaki hizmetler verilecektir:</p><ul><li>Günlük ortalama 120 ton evsel atığın toplanması</li><li>30 mahallede düzenli çöp toplama hizmeti</li><li>Toplanan atıkların transfer istasyonuna nakli</li><li>Konteyner yıkama ve dezenfekte işlemleri</li><li>Gerekli araç ve ekipmanların temini</li></ul><p>Hizmet 365 gün boyunca kesintisiz olarak sürdürülecektir.</p>',
                'status' => 'cancelled',
            ],
        ];

        foreach ($tenders as $tenderData) {
            Tender::create($tenderData);
        }
    }
} 