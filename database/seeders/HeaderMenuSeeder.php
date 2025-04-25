<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HeaderMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mevcut header menü öğelerini temizle (sadece header tipindekiler)
        Menu::where('type', 'header')->delete();
        
        // Ana menüleri oluştur
        $kurumsal = Menu::create([
            'name' => 'Kurumsal',
            'slug' => 'kurumsal',
            'url' => '/kurumsal',
            'type' => 'header',
            'is_active' => 1,
            'is_mega_menu' => 1,
            'order' => 1,
        ]);
        
        $hizmetler = Menu::create([
            'name' => 'Hizmetler',
            'slug' => 'hizmetler',
            'url' => '/hizmetler',
            'type' => 'header',
            'is_active' => 1,
            'is_mega_menu' => 1,
            'order' => 2,
        ]);
        
        $duyurular = Menu::create([
            'name' => 'Duyurular',
            'slug' => 'duyurular',
            'url' => '/duyurular',
            'type' => 'header',
            'is_active' => 1,
            'is_mega_menu' => 1,
            'order' => 3,
        ]);
        
        $nenerede = Menu::create([
            'name' => 'Ne Nerede',
            'slug' => 'ne-nerede',
            'url' => '/ne-nerede',
            'type' => 'header',
            'is_active' => 1,
            'is_mega_menu' => 1,
            'order' => 4,
        ]);
        
        $iletisim = Menu::create([
            'name' => 'İletişim',
            'slug' => 'iletisim',
            'url' => '/iletisim',
            'type' => 'header',
            'is_active' => 1,
            'is_mega_menu' => 0,
            'order' => 5,
        ]);
        
        // KURUMSAL ALT MENÜLERİ
        
        // Belediye Makamları
        $belediyeMakamlari = $this->createSubMenuItem($kurumsal, 'Belediye Makamları', 'belediye-makamlari', '/kurumsal/belediye-makamlari', 1, 'person');
        
        // Belediye Makamları alt menü öğeleri
        $this->createThirdLevelItems($belediyeMakamlari, [
            ['name' => 'Belediye Başkanı', 'slug' => 'belediye-baskani', 'url' => '/kurumsal/belediye-makamlari/belediye-baskani', 'icon' => 'person'],
            ['name' => 'Belediye Meclisi', 'slug' => 'belediye-meclisi', 'url' => '/kurumsal/belediye-makamlari/belediye-meclisi', 'icon' => 'groups'],
            ['name' => 'Belediye Encümeni', 'slug' => 'belediye-encumeni', 'url' => '/kurumsal/belediye-makamlari/belediye-encumeni', 'icon' => 'table_chart'],
            ['name' => 'Başkan Yardımcıları', 'slug' => 'baskan-yardimcilari', 'url' => '/kurumsal/belediye-makamlari/baskan-yardimcilari', 'icon' => 'supervisor_account'],
            ['name' => 'Çalışma Komisyonları', 'slug' => 'calisma-komisyonlari', 'url' => '/kurumsal/belediye-makamlari/calisma-komisyonlari', 'icon' => 'assignment_ind'],
            ['name' => 'Müdürlükler', 'slug' => 'mudurlukler', 'url' => '/kurumsal/belediye-makamlari/mudurlukler', 'icon' => 'account_balance'],
        ]);
        
        // Kurumsal Politikalarımız
        $kurumsalPolitikalar = $this->createSubMenuItem($kurumsal, 'Kurumsal Politikalarımız', 'kurumsal-politikalarimiz', '/kurumsal/kurumsal-politikalarimiz', 2, 'verified');
        
        // Kurumsal Politikalarımız alt menü öğeleri
        $this->createThirdLevelItems($kurumsalPolitikalar, [
            ['name' => 'Hizmet Standartlarımız', 'slug' => 'hizmet-standartlarimiz', 'url' => '/kurumsal/kurumsal-politikalarimiz/hizmet-standartlarimiz', 'icon' => 'verified'],
            ['name' => 'Bilgi Güvenliği', 'slug' => 'bilgi-guvenligi', 'url' => '/kurumsal/kurumsal-politikalarimiz/bilgi-guvenligi', 'icon' => 'security'],
            ['name' => 'Uluslararası İş Birlikleri', 'slug' => 'uluslararasi-is-birlikleri', 'url' => '/kurumsal/kurumsal-politikalarimiz/uluslararasi-is-birlikleri', 'icon' => 'public'],
            ['name' => 'Engelsiz İş Yerleri', 'slug' => 'engelsiz-is-yerleri', 'url' => '/kurumsal/kurumsal-politikalarimiz/engelsiz-is-yerleri', 'icon' => 'accessible'],
            ['name' => 'Sıfır Atık', 'slug' => 'sifir-atik', 'url' => '/kurumsal/kurumsal-politikalarimiz/sifir-atik', 'icon' => 'restore_from_trash'],
            ['name' => 'İklim Değişikliği', 'slug' => 'iklim-degisikligi', 'url' => '/kurumsal/kurumsal-politikalarimiz/iklim-degisikligi', 'icon' => 'wb_sunny'],
            ['name' => 'Misyonumuz ve Vizyonumuz', 'slug' => 'misyonumuz-ve-vizyonumuz', 'url' => '/kurumsal/kurumsal-politikalarimiz/misyonumuz-ve-vizyonumuz', 'icon' => 'lightbulb'],
        ]);
        
        // Belediye İştirakleri
        $belediyeIstirakleri = $this->createSubMenuItem($kurumsal, 'Belediye İştirakleri', 'belediye-istirakleri', '/kurumsal/belediye-istirakleri', 3, 'business');
        
        // Belediye İştirakleri alt menü öğeleri
        $this->createThirdLevelItems($belediyeIstirakleri, [
            ['name' => 'Çankaya İmar A.Ş.', 'slug' => 'cankaya-imar-as', 'url' => '/kurumsal/belediye-istirakleri/cankaya-imar-as', 'icon' => 'business'],
            ['name' => 'Çankaya BELDE A.Ş.', 'slug' => 'cankaya-belde-as', 'url' => '/kurumsal/belediye-istirakleri/cankaya-belde-as', 'icon' => 'business'],
            ['name' => 'ÇANPAŞ', 'slug' => 'canpas', 'url' => '/kurumsal/belediye-istirakleri/canpas', 'icon' => 'business'],
            ['name' => 'BELPET', 'slug' => 'belpet', 'url' => '/kurumsal/belediye-istirakleri/belpet', 'icon' => 'business'],
        ]);
        
        // Kimlik
        $kimlik = $this->createSubMenuItem($kurumsal, 'Kimlik', 'kimlik', '/kurumsal/kimlik', 4, 'badge');
        
        // Kimlik alt menü öğeleri
        $this->createThirdLevelItems($kimlik, [
            ['name' => 'Kurumsal Kimlik', 'slug' => 'kurumsal-kimlik', 'url' => '/kurumsal/kimlik/kurumsal-kimlik', 'icon' => 'badge'],
            ['name' => 'Rakamlarla Çankaya', 'slug' => 'rakamlarla-cankaya', 'url' => '/kurumsal/kimlik/rakamlarla-cankaya', 'icon' => 'bar_chart'],
            ['name' => 'Tarihçe', 'slug' => 'tarihce', 'url' => '/kurumsal/kimlik/tarihce', 'icon' => 'history'],
            ['name' => 'Antik Tarih', 'slug' => 'antik-tarih', 'url' => '/kurumsal/kimlik/antik-tarih', 'icon' => 'museum'],
            ['name' => 'Kültürel Yaşam', 'slug' => 'kulturel-yasam', 'url' => '/kurumsal/kimlik/kulturel-yasam', 'icon' => 'theater_comedy'],
            ['name' => 'Anıtkabir\'in Tarihi', 'slug' => 'anitkabirin-tarihi', 'url' => '/kurumsal/kimlik/anitkabirin-tarihi', 'icon' => 'location_city'],
            ['name' => 'Doğal Yapı', 'slug' => 'dogal-yapi', 'url' => '/kurumsal/kimlik/dogal-yapi', 'icon' => 'landscape'],
            ['name' => 'Ekonomik Yaşam', 'slug' => 'ekonomik-yasam', 'url' => '/kurumsal/kimlik/ekonomik-yasam', 'icon' => 'savings'],
            ['name' => 'Kültürel Miras', 'slug' => 'kulturel-miras', 'url' => '/kurumsal/kimlik/kulturel-miras', 'icon' => 'palette'],
            ['name' => 'Çankaya\'nın Adı', 'slug' => 'cankayanin-adi', 'url' => '/kurumsal/kimlik/cankayanin-adi', 'icon' => 'label'],
        ]);
        
        // HİZMETLER ALT MENÜLERİ
        
        $this->createSubMenuItem($hizmetler, 'İş Yerleri', 'is-yerleri', '/hizmetler/is-yerleri', 1, 'store');
        $this->createSubMenuItem($hizmetler, 'Kültür', 'kultur', '/hizmetler/kultur', 2, 'theater_comedy');
        $this->createSubMenuItem($hizmetler, 'Sağlık', 'saglik', '/hizmetler/saglik', 3, 'local_hospital');
        $this->createSubMenuItem($hizmetler, 'İmar', 'imar', '/hizmetler/imar', 4, 'apartment');
        $this->createSubMenuItem($hizmetler, 'Sosyal Yardım', 'sosyal-yardim', '/hizmetler/sosyal-yardim', 5, 'volunteer_activism');
        $this->createSubMenuItem($hizmetler, 'Çevre', 'cevre', '/hizmetler/cevre', 6, 'nature');
        $this->createSubMenuItem($hizmetler, 'Veterinerlik', 'veterinerlik', '/hizmetler/veterinerlik', 7, 'pets');
        $this->createSubMenuItem($hizmetler, 'Temizlik', 'temizlik', '/hizmetler/temizlik', 8, 'cleaning_services');
        $this->createSubMenuItem($hizmetler, 'Kamu Emlak ve İstimlak', 'kamu-emlak-ve-istimlak', '/hizmetler/kamu-emlak-ve-istimlak', 9, 'real_estate_agent');
        $this->createSubMenuItem($hizmetler, 'Park', 'park', '/hizmetler/park', 10, 'park');
        $this->createSubMenuItem($hizmetler, 'Fen İşleri', 'fen-isleri', '/hizmetler/fen-isleri', 11, 'construction');
        
        // DUYURULAR ALT MENÜLERİ
        
        // Belediye Duyuruları
        $belediyeDuyurulari = $this->createSubMenuItem($duyurular, 'Belediye Duyuruları', 'belediye-duyurulari', '/duyurular/belediye-duyurulari', 1, 'announcement');
        
        // Belediye Duyuruları alt menü öğeleri
        $this->createThirdLevelItems($belediyeDuyurulari, [
            ['name' => 'Tüm Etkinlikler', 'slug' => 'tum-etkinlikler', 'url' => '/duyurular/belediye-duyurulari/tum-etkinlikler', 'icon' => 'announcement'],
            ['name' => 'Askıdaki Planlar', 'slug' => 'askidaki-planlar', 'url' => '/duyurular/belediye-duyurulari/askidaki-planlar', 'icon' => 'flag'],
            ['name' => 'Güncel Meclis Kararları', 'slug' => 'guncel-meclis-kararlari', 'url' => '/duyurular/belediye-duyurulari/guncel-meclis-kararlari', 'icon' => 'gavel'],
            ['name' => 'İhaleler', 'slug' => 'ihaleler', 'url' => '/duyurular/belediye-duyurulari/ihaleler', 'icon' => 'shopping_bag'],
        ]);
        
        // Planlar ve Projeler
        $planlarProjeler = $this->createSubMenuItem($duyurular, 'Planlar ve Projeler', 'planlar-ve-projeler', '/duyurular/planlar-ve-projeler', 2, 'description');
        
        // Planlar ve Projeler alt menü öğeleri
        $this->createThirdLevelItems($planlarProjeler, [
            ['name' => 'Yürürlükteki Stratejik Plan', 'slug' => 'yururlukteki-stratejik-plan', 'url' => '/duyurular/planlar-ve-projeler/yururlukteki-stratejik-plan', 'icon' => 'description'],
            ['name' => 'Fen İşleri Müdürlüğü Çalışma Planı', 'slug' => 'fen-isleri-mudurlugu-calisma-plani', 'url' => '/duyurular/planlar-ve-projeler/fen-isleri-mudurlugu-calisma-plani', 'icon' => 'construction'],
            ['name' => 'İmar Planları', 'slug' => 'imar-planlari', 'url' => '/duyurular/planlar-ve-projeler/imar-planlari', 'icon' => 'architecture'],
            ['name' => 'Kentsel Dönüşüm Projeleri', 'slug' => 'kentsel-donusum-projeleri', 'url' => '/duyurular/planlar-ve-projeler/kentsel-donusum-projeleri', 'icon' => 'apartment'],
        ]);
        
        // Basına Duyurular
        $basinaDuyurular = $this->createSubMenuItem($duyurular, 'Basına Duyurular', 'basina-duyurular', '/duyurular/basina-duyurular', 3, 'campaign');
        
        // Basına Duyurular alt menü öğeleri
        $this->createThirdLevelItems($basinaDuyurular, [
            ['name' => 'Basın Bültenleri', 'slug' => 'basin-bultenleri', 'url' => '/duyurular/basina-duyurular/basin-bultenleri', 'icon' => 'campaign'],
            ['name' => 'Haberler', 'slug' => 'haberler', 'url' => '/duyurular/basina-duyurular/haberler', 'icon' => 'newspaper'],
            ['name' => 'Etkinlik Takvimi', 'slug' => 'etkinlik-takvimi', 'url' => '/duyurular/basina-duyurular/etkinlik-takvimi', 'icon' => 'event'],
            ['name' => 'Fotoğraf Galerisi', 'slug' => 'fotograf-galerisi', 'url' => '/duyurular/basina-duyurular/fotograf-galerisi', 'icon' => 'photo_library'],
        ]);
        
        // NE NEREDE ALT MENÜLERİ
        
        // Kamu Tesisleri
        $kamuTesisleri = $this->createSubMenuItem($nenerede, 'Kamu Tesisleri', 'kamu-tesisleri', '/ne-nerede/kamu-tesisleri', 1, 'home');
        
        // Kamu Tesisleri alt menü öğeleri
        $this->createThirdLevelItems($kamuTesisleri, [
            ['name' => 'Çankaya Evlerimiz', 'slug' => 'cankaya-evlerimiz', 'url' => '/ne-nerede/kamu-tesisleri/cankaya-evlerimiz', 'icon' => 'home'],
            ['name' => 'Zabıta Karakollarımız', 'slug' => 'zabita-karakollarimiz', 'url' => '/ne-nerede/kamu-tesisleri/zabita-karakollarimiz', 'icon' => 'policy'],
            ['name' => 'Muhtarlıklar', 'slug' => 'muhtarliklar', 'url' => '/ne-nerede/kamu-tesisleri/muhtarliklar', 'icon' => 'location_city'],
            ['name' => 'Ücretsiz İnternet Alanlarımız', 'slug' => 'ucretsiz-internet-alanlarimiz', 'url' => '/ne-nerede/kamu-tesisleri/ucretsiz-internet-alanlarimiz', 'icon' => 'wifi'],
            ['name' => 'Acil Toplanma Alanları', 'slug' => 'acil-toplanma-alanlari', 'url' => '/ne-nerede/kamu-tesisleri/acil-toplanma-alanlari', 'icon' => 'warning'],
        ]);
        
        // Eğitim ve Kültür
        $egitimKultur = $this->createSubMenuItem($nenerede, 'Eğitim ve Kültür', 'egitim-ve-kultur', '/ne-nerede/egitim-ve-kultur', 2, 'child_care');
        
        // Eğitim ve Kültür alt menü öğeleri
        $this->createThirdLevelItems($egitimKultur, [
            ['name' => 'Kreşlerimiz', 'slug' => 'kreslerimiz', 'url' => '/ne-nerede/egitim-ve-kultur/kreslerimiz', 'icon' => 'child_care'],
            ['name' => 'Kütüphanelerimiz', 'slug' => 'kutuphanelerimiz', 'url' => '/ne-nerede/egitim-ve-kultur/kutuphanelerimiz', 'icon' => 'menu_book'],
            ['name' => 'Kültür ve Sanat Merkezlerimiz', 'slug' => 'kultur-ve-sanat-merkezlerimiz', 'url' => '/ne-nerede/egitim-ve-kultur/kultur-ve-sanat-merkezlerimiz', 'icon' => 'palette'],
            ['name' => 'Eğitim Merkezlerimiz', 'slug' => 'egitim-merkezlerimiz', 'url' => '/ne-nerede/egitim-ve-kultur/egitim-merkezlerimiz', 'icon' => 'school'],
            ['name' => 'Tiyatro ve Konser Alanlarımız', 'slug' => 'tiyatro-ve-konser-alanlarimiz', 'url' => '/ne-nerede/egitim-ve-kultur/tiyatro-ve-konser-alanlarimiz', 'icon' => 'theater_comedy'],
        ]);
        
        // Spor ve Altyapı
        $sporAltyapi = $this->createSubMenuItem($nenerede, 'Spor ve Altyapı', 'spor-ve-altyapi', '/ne-nerede/spor-ve-altyapi', 3, 'pool');
        
        // Spor ve Altyapı alt menü öğeleri
        $this->createThirdLevelItems($sporAltyapi, [
            ['name' => 'Spor Tesisi ve Havuzlarımız', 'slug' => 'spor-tesisi-ve-havuzlarimiz', 'url' => '/ne-nerede/spor-ve-altyapi/spor-tesisi-ve-havuzlarimiz', 'icon' => 'pool'],
            ['name' => 'Yol ve Kaldırım Çalışmalarımız', 'slug' => 'yol-ve-kaldirim-calismalarimiz', 'url' => '/ne-nerede/spor-ve-altyapi/yol-ve-kaldirim-calismalarimiz', 'icon' => 'construction'],
            ['name' => 'Geri Dönüşüm Merkezleri', 'slug' => 'geri-donusum-merkezleri', 'url' => '/ne-nerede/spor-ve-altyapi/geri-donusum-merkezleri', 'icon' => 'recycling'],
            ['name' => 'Tüm Harita Katmanları', 'slug' => 'tum-harita-katmanlari', 'url' => '/ne-nerede/spor-ve-altyapi/tum-harita-katmanlari', 'icon' => 'map'],
        ]);
    }
    
    /**
     * Alt menü öğesi oluştur ve geri döndür
     */
    private function createSubMenuItem($parentMenu, $name, $slug, $url, $order, $icon = null)
    {
        return Menu::create([
            'name' => $name,
            'slug' => $slug,
            'url' => $url,
            'type' => 'header',
            'parent_id' => $parentMenu->id,
            'is_active' => 1,
            'is_mega_menu' => 0,
            'order' => $order,
            'icon' => $icon,
        ]);
    }
    
    /**
     * Üçüncü seviye menü öğelerini oluştur
     */
    private function createThirdLevelItems($parentMenu, $items)
    {
        foreach ($items as $index => $item) {
            Menu::create([
                'name' => $item['name'],
                'slug' => $item['slug'],
                'url' => $item['url'],
                'type' => 'header',
                'parent_id' => $parentMenu->id,
                'is_active' => 1,
                'is_mega_menu' => 0,
                'order' => $index + 1,
                'icon' => $item['icon'] ?? null,
            ]);
        }
    }
} 