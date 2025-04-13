# CMS Sistem Analizi

## Genel Yapı
Bu proje Laravel 10 tabanlı bir içerik yönetim sistemi (CMS) olarak geliştirilmiştir. Sistem modüler bir yapıya sahiptir ve aşağıdaki temel modülleri içermektedir:

### Ana Modüller
1. **Sayfalar (Pages)** - Statik içerik sayfaları yönetimi

   **Sayfalar Modülü (Pages) - Detaylı Açıklama**
   
   Sayfalar modülü, web sitesinin statik içerik yönetimini sağlayan kapsamlı bir sistemdir. Bu modül, SEO dostu içerikler oluşturmak ve bunları kategorilere ayırarak organize etmek için tasarlanmıştır.
   
   **Model Yapısı:**
   - **Page Model**: Sayfa içeriği, meta bilgileri, durumu ve görünürlüğünü yöneten ana model
     - `title`, `slug`, `summary`, `content` - Temel içerik alanları
     - `featured_image`, `gallery` - Görsel içerik alanları
     - `is_featured`, `featured_order` - Öne çıkan sayfalar için özellikler
     - `status`, `is_scheduled`, `published_at`, `end_date` - Yayınlama durumu ve zamanlama
     - `meta_title`, `meta_description`, `meta_keywords` - SEO alanları
     - `template` - Özel şablon seçimi
     - `view_count` - Görüntülenme istatistikleri
   
   - **PageCategory Model**: Sayfaları kategorilere ayırmak için kullanılan model
     - Hiyerarşik kategori yapısı (ebeveyn-çocuk ilişkisi)
     - Kategori sıralama ve görünürlük kontrolü
     - SEO dostu slug yapısı
   
   - **PageTag Model**: Sayfaları etiketlemek için kullanılan model
     - Sayfa tagleme sistemi
     - Tag bulutu ve ilişkili içerik oluşturma
   
   **Özellikler:**
   
   1. **İçerik Yönetimi**:
      - Zengin metin editörü (TinyMCE) desteği
      - Çoklu medya galerisi yönetimi
      - HTML içerik desteği
      - Taslak/yayın durumu kontrolü
   
   2. **Öne Çıkan Sayfalar**:
      - Maksimum 4 adet öne çıkan sayfa tanımlama
      - Sürükle-bırak ile sıralama
      - Öne çıkan sayfalar için özel görünüm
   
   3. **Zamanlama ve Yayın Kontrolü**:
      - İçerik yayınlama zamanlaması
      - Otomatik yayınlama ve yayından kaldırma
      - Arşivleme ve geçmişe dönük içerik yönetimi
   
   4. **SEO Optimizasyonu**:
      - Özel meta başlık, açıklama ve anahtar kelime tanımlama
      - SEO dostu URL yapısı (slug)
      - Otomatik meta veri oluşturma
   
   5. **Kategori ve Etiket Yönetimi**:
      - Hiyerarşik kategori yapısı
      - Sınırsız etiketleme
      - Kategori bazlı listeleme ve filtreleme
   
   6. **İçerik Organizasyonu**:
      - Gelişmiş filtreleme ve arama özellikleri
      - Kategori ve etiket bazlı gruplama
      - İlişkili içerik önerileri
   
   7. **Önyüz Entegrasyonu**:
      - Öne çıkan sayfalar listesi
      - Yakın zamanda eklenen sayfalar
      - Popüler sayfalar (görüntülenme sayısına göre)
      - Kategori bazlı sayfa listeleme
   
   8. **İstatistikler**:
      - Sayfa görüntülenme sayısı takibi
      - En çok okunan içeriklerin analizi
   
   **Mimari Yaklaşım**:
   
   Sayfalar modülü, Repository pattern kullanılarak geliştirilmiştir:
   - **PageService**: İş mantığı ve veri manipülasyonu
   - **PageRepository**: Veritabanı işlemleri ve sorgulama
   - **PageController**: Admin paneli istek yönetimi
   - **Front/PageController**: Önyüz istek yönetimi
   
   **Admin Panel Özellikleri**:
   - Sayfa oluşturma, düzenleme, silme
   - Sayfa görünürlüğünü değiştirme
   - Öne çıkan sayfaları yönetme
   - Zamanlı yayınlama
   - Toplu işlem yapma
   
   **Önyüz Rotaları**:
   - `/sayfalar` - Tüm sayfalar listesi
   - `/sayfalar/kategori/{slug}` - Kategori bazlı sayfalar
   - `/sayfalar/{slug}` - Tekil sayfa görüntüleme

2. **Haberler (News)** - Haber içeriklerinin yönetimi

   **Haberler Modülü (News) - Detaylı Açıklama**
   
   Haberler modülü, dinamik içerik yönetimini sağlayan ve web sitesinin güncel içeriklerle beslenmesini kolaylaştıran bir sistemdir. Bu modül, haber, duyuru ve güncel bilgilerin kategorize edilmiş şekilde yayınlanmasını ve öne çıkarılmasını sağlar.
   
   **Model Yapısı:**
   - **News Model**: Haber içeriği, meta bilgileri, durumu ve görünürlüğünü yöneten ana model
     - `title`, `slug`, `summary`, `content` - Temel içerik alanları
     - `image`, `gallery` - Görsel içerik alanları
     - `is_headline`, `headline_order` - Manşet haberleri için özellikler
     - `is_featured` - Öne çıkan haberler için özellik
     - `status`, `is_scheduled`, `published_at`, `end_date` - Yayınlama durumu ve zamanlama
     - `meta_title`, `meta_description` - SEO alanları
     - `view_count` - Görüntülenme istatistikleri
   
   - **NewsCategory Model**: Haberleri kategorilere ayırmak için kullanılan model
     - Hiyerarşik kategori yapısı (ebeveyn-çocuk ilişkisi)
     - Kategori sıralama ve görünürlük kontrolü
     - SEO dostu slug yapısı
   
   - **NewsTag Model**: Haberleri etiketlemek için kullanılan model
     - Haber tagleme sistemi
     - İlişkili haber önerileri oluşturma
   
   **Özellikler:**
   
   1. **Haber Yönetimi**:
      - Zengin metin editörü (TinyMCE) desteği
      - Çoklu medya galerisi yönetimi
      - HTML içerik desteği
      - Taslak/yayın durumu kontrolü
   
   2. **Manşet Sistemi**:
      - Maksimum 4 adet manşet haber tanımlama
      - Sürükle-bırak ile manşet sıralama
      - Manşet haberler için özel görünüm
   
   3. **Zamanlama ve Yayın Kontrolü**:
      - İçerik yayınlama zamanlaması
      - Otomatik yayınlama ve yayından kaldırma
      - Arşivleme ve geçmişe dönük haber yönetimi
   
   4. **SEO Optimizasyonu**:
      - Özel meta başlık ve açıklama tanımlama
      - SEO dostu URL yapısı (slug)
      - Otomatik meta veri oluşturma
   
   5. **Kategori ve Etiket Yönetimi**:
      - Hiyerarşik kategori yapısı
      - Sınırsız etiketleme
      - Kategori bazlı listeleme ve filtreleme
   
   6. **Haber Organizasyonu**:
      - Gelişmiş filtreleme ve arama özellikleri
      - Kategori ve etiket bazlı gruplama
      - İlişkili haber önerileri
   
   7. **Önyüz Entegrasyonu**:
      - Manşet haberleri slider'ı
      - Son eklenen haberler listesi
      - Popüler haberler (görüntülenme sayısına göre)
      - Kategori bazlı haber listeleme
   
   8. **İstatistikler**:
      - Haber görüntülenme sayısı takibi
      - En çok okunan haberlerin analizi
   
   **Mimari Yaklaşım**:
   
   Haberler modülü, Repository pattern kullanılarak geliştirilmiştir:
   - **NewsService**: İş mantığı ve veri manipülasyonu
   - **NewsRepository**: Veritabanı işlemleri ve sorgulama
   - **NewsController**: Admin paneli istek yönetimi
   - **Front/NewsController**: Önyüz istek yönetimi
   
   **Admin Panel Özellikleri**:
   - Haber oluşturma, düzenleme, silme
   - Manşet ve öne çıkan haber yönetimi
   - Haber görünürlüğünü değiştirme
   - Zamanlı yayınlama
   - Toplu haber işlemleri
   
   **Önyüz Rotaları**:
   - `/haberler` - Tüm haberler listesi
   - `/haberler/kategori/{slug}` - Kategori bazlı haberler
   - `/haberler/{slug}` - Tekil haber görüntüleme

3. **Hizmetler (Services)** - Kurumsal hizmetlerin yönetimi

   **Hizmetler Modülü (Services) - Detaylı Açıklama**
   
   Hizmetler modülü, kurumsal web sitelerinin sunduğu hizmetlerin tanıtımını yönetmek için geliştirilmiş kapsamlı bir sistemdir. Bu modül, kurumun müşterilere sunduğu hizmetlerin detaylı olarak tanımlanmasını, kategorize edilmesini ve etkili bir şekilde sunulmasını sağlar.
   
   **Model Yapısı:**
   - **Service Model**: Hizmet içeriği, meta bilgileri, durumu ve görünürlüğünü yöneten ana model
     - `title`, `slug`, `summary`, `content` - Temel içerik alanları
     - `features` - Hizmet özellikleri/avantajları (dizi olarak saklanır)
     - `image`, `gallery` - Görsel içerik alanları
     - `is_headline`, `headline_order` - Öne çıkan hizmetler için özellikler
     - `is_featured` - Öne çıkan özelliği
     - `cta_text`, `cta_url` - Çağrı butonu ayarları
     - `status`, `is_scheduled`, `published_at`, `end_date` - Yayınlama durumu ve zamanlama
     - `meta_title`, `meta_description` - SEO alanları
     - `view_count` - Görüntülenme istatistikleri
   
   - **ServiceCategory Model**: Hizmetleri kategorilere ayırmak için kullanılan model
     - Hiyerarşik kategori yapısı (ebeveyn-çocuk ilişkisi)
     - Kategori sıralama ve görünürlük kontrolü
     - SEO dostu slug yapısı
   
   - **ServiceTag Model**: Hizmetleri etiketlemek için kullanılan model
     - Hizmet tagleme sistemi
     - İlişkili hizmet önerileri oluşturma
   
   - **FeaturedService Model**: Anasayfada gösterilecek öne çıkan hizmetler için özel model
     - Görünürlük ve sıralama kontrolü
     - Özel görsel ve açıklama yönetimi
   
   **Özellikler:**
   
   1. **Hizmet İçerik Yönetimi**:
      - Zengin metin editörü (TinyMCE) desteği
      - Çoklu medya galerisi yönetimi
      - Hizmet özellikleri listesi yönetimi
      - HTML içerik desteği
      - Taslak/yayın durumu kontrolü
   
   2. **Manşet/Öne Çıkan Hizmetler**:
      - Öne çıkan hizmetlerin yönetimi
      - Sürükle-bırak ile sıralama
      - Özel görünüm seçenekleri
   
   3. **Çağrı Butonları (CTA)**:
      - Her hizmet için özelleştirilebilir çağrı butonları
      - Dönüşüm odaklı aksiyon linkleri
   
   4. **Zamanlama ve Yayın Kontrolü**:
      - İçerik yayınlama zamanlaması
      - Otomatik yayınlama ve yayından kaldırma
      - Arşivleme ve geçmişe dönük hizmet yönetimi
   
   5. **SEO Optimizasyonu**:
      - Özel meta başlık ve açıklama tanımlama
      - SEO dostu URL yapısı (slug)
      - Otomatik meta veri oluşturma
   
   6. **Kategori ve Etiket Yönetimi**:
      - Hiyerarşik kategori yapısı
      - Sınırsız etiketleme
      - Kategori bazlı listeleme ve filtreleme
   
   7. **Hizmet Organizasyonu**:
      - Gelişmiş filtreleme ve arama özellikleri
      - Kategori ve etiket bazlı gruplama
      - İlişkili hizmet önerileri
   
   8. **Önyüz Entegrasyonu**:
      - Öne çıkan hizmetler bölümü
      - Hizmet kategorileri navigasyonu
      - Hizmet detay sayfaları
      - İlişkili hizmetler gösterimi
   
   **Mimari Yaklaşım**:
   
   Hizmetler modülü, Repository pattern kullanılarak geliştirilmiştir:
   - **ServiceService**: İş mantığı ve veri manipülasyonu
   - **ServiceRepository**: Veritabanı işlemleri ve sorgulama
   - **ServiceController**: Admin paneli istek yönetimi
   - **Front/ServiceController**: Önyüz istek yönetimi
   
   **Admin Panel Özellikleri**:
   - Hizmet oluşturma, düzenleme, silme
   - Özellikleri dinamik olarak yönetme
   - Öne çıkan hizmetleri yönetme
   - Hizmet görünürlüğünü değiştirme
   - Zamanlı yayınlama
   - Galeri yönetimi
   
   **Önyüz Rotaları**:
   - `/hizmetler` - Tüm hizmetler listesi
   - `/hizmetler/kategori/{slug}` - Kategori bazlı hizmetler
   - `/hizmetler/{slug}` - Tekil hizmet görüntüleme
   - `/hizmet/{slug}` - Alternatif tekil hizmet görüntüleme

4. **Projeler (Projects)** - Proje galeri ve bilgilerinin yönetimi

   **Projeler Modülü (Projects) - Detaylı Açıklama**
   
   Projeler modülü, kurumun gerçekleştirdiği projelerin portföy şeklinde sergilenmesini sağlayan bir sistemdir. Bu modül, proje yönetimi, proje görselleri, kategorizasyon ve ilerleme durumu gibi özellikleri barındıran kapsamlı bir yapı sunar.
   
   **Model Yapısı:**
   - **Project Model**: Proje içeriği, durumu ve görünürlüğünü yöneten ana model
     - `title`, `slug`, `description` - Temel içerik alanları
     - `category_id` - Proje kategorisi
     - `completion_percentage` - Tamamlanma yüzdesi
     - `project_date` - Proje tarihi
     - `cover_image` - Kapak görseli
     - `order` - Sıralama değeri
     - `is_active` - Görünürlük durumu
     - `show_on_homepage` - Anasayfada gösterilme durumu
   
   - **ProjectCategory Model**: Projeleri kategorilere ayırmak için kullanılan model
     - Sıralama ve görünürlük kontrolü
     - SEO dostu slug yapısı
   
   - **ProjectImage Model**: Proje galerisi için görselleri yöneten model
     - Görsel yolu ve sıralaması
     - İlişkili proje bağlantısı
   
   - **ProjectSettings Model**: Proje modülünün genel ayarlarını içeren model
     - Modül aktiflik durumu
     - Görüntüleme ayarları
     - Önyüz görünüm tercihleri
   
   **Özellikler:**
   
   1. **Proje Portföy Yönetimi**:
      - Zengin metin editörü desteği
      - Çoklu görsel galerisi
      - Tamamlanma yüzdesi takibi
      - Proje tarih bilgisi
   
   2. **Kategori Sistemi**:
      - Projeleri kategorilere ayırma
      - Kategori bazlı filtreleme
      - Kategori görünürlük kontrolü
   
   3. **Görsel Galeriler**:
      - Proje başına sınırsız görsel ekleme
      - Görselleri sürükle-bırak ile sıralama
      - Kapak görseli belirleme
   
   4. **Önyüz Gösterim Seçenekleri**:
      - Anasayfada gösterilen projeleri belirleme
      - Kategori bazlı proje listeleme
      - İlişkili projeleri gösterme
   
   5. **Sıralama ve Görünürlük**:
      - Projeleri sürükle-bırak ile sıralama
      - Proje görünürlüğünü kontrol etme
      - Kategorileri sıralama
   
   6. **İlerleme Durumu Gösterimi**:
      - Tamamlanma yüzdesine göre otomatik durum belirleme
      - Durum etiketleri ve renk kodları
      - İlerleme çubuğu gösterimi
   
   7. **Önyüz Entegrasyonu**:
      - Proje kategorileri navigasyonu
      - Galeri görünümü ile proje listeleme
      - Detaylı proje sayfaları
      - İlişkili projeler önerisi
   
   **Mimari Yaklaşım**:
   
   Projeler modülü, Controller tabanlı bir yapı kullanılarak geliştirilmiştir:
   - **ProjectManagerController**: Admin panel yönetimi
   - **FrontController**: Önyüz istek yönetimi
   
   **Admin Panel Özellikleri**:
   - Proje oluşturma, düzenleme, silme
   - Galeri görsellerini yönetme
   - Kategori yönetimi
   - Projeleri sıralama ve filtreleme
   - Tamamlanma yüzdesi belirleme
   - Anasayfada gösterim kontrolü
   - Modül genel ayarları
   
   **Önyüz Rotaları**:
   - `/projeler` - Tüm projeler listesi
   - `/projeler/kategori/{slug}` - Kategori bazlı projeler
   - `/projeler/{slug}` - Tekil proje görüntüleme

5. **Etkinlikler (Events)** - Etkinlik takvimi ve bilgilerinin yönetimi

   **Etkinlikler Modülü (Events) - Detaylı Açıklama**
   
   Etkinlikler modülü, kurumun düzenlediği veya katıldığı etkinliklerin takvim formatında yönetilmesini sağlayan kapsamlı bir sistemdir. Bu modül, etkinliklerin tarih bazlı listelenmesi, kategorizasyonu ve özelleştirilmesi için çeşitli özellikler sunar.
   
   **Model Yapısı:**
   - **Event Model**: Etkinlik içeriği, tarih bilgileri ve görünürlüğünü yöneten ana model
     - `title`, `slug`, `description` - Temel içerik alanları
     - `category_id` - Etkinlik kategorisi
     - `start_date`, `end_date` - Etkinlik başlangıç/bitiş tarihleri
     - `location`, `address` - Konum bilgileri
     - `organizer` - Düzenleyici bilgisi
     - `cover_image` - Kapak görseli
     - `register_required`, `register_url`, `max_participants` - Kayıt bilgileri
     - `is_active`, `show_on_homepage`, `is_featured` - Görünürlük ayarları
     - `order` - Sıralama değeri
     - `external_id`, `external_url` - Dış kaynak referansları
   
   - **EventCategory Model**: Etkinlikleri kategorilere ayırmak için kullanılan model
     - Sıralama ve görünürlük kontrolü
     - SEO dostu slug yapısı
   
   - **EventImage Model**: Etkinlik galerisi için görselleri yöneten model
     - Görsel yolu ve sıralaması
     - İlişkili etkinlik bağlantısı
   
   - **EventSettings Model**: Etkinlikler modülünün genel ayarlarını içeren model
     - Modül aktiflik durumu
     - Görüntüleme ayarları
     - Sayfa başına etkinlik sayısı
     - Anasayfa etkinlik limiti
   
   **Özellikler:**
   
   1. **Etkinlik Takvimi Yönetimi**:
      - Tarih bazlı etkinlik listeleme
      - Başlangıç ve bitiş tarihleri
      - Takvim görünümü ve liste görünümü
      - Yaklaşan, devam eden ve geçmiş etkinlik sınıflandırması
   
   2. **Kategori Sistemi**:
      - Etkinlikleri kategorilere ayırma
      - Kategori bazlı filtreleme
      - Kategori görünürlük kontrolü
   
   3. **Görsel Galeriler**:
      - Etkinlik başına sınırsız görsel ekleme
      - Görselleri sürükle-bırak ile sıralama
      - Kapak görseli belirleme
   
   4. **Konumsal Bilgiler**:
      - Etkinlik lokasyonu tanımlama
      - Adres ve yer bilgisi
      - Organizatör bilgisi
   
   5. **Kayıt Yönetimi**:
      - Etkinlik kayıt sistemi
      - Katılımcı limiti belirleme
      - Kayıt URL'i tanımlama
   
   6. **Dış Kaynak Entegrasyonu**:
      - Harici etkinlik verilerini içe aktarma
      - API entegrasyonu ve web scraping
      - Otomatik etkinlik güncelleme
   
   7. **Önyüz Entegrasyonu**:
      - Etkinlik takvimi görünümü
      - Etkinlik detay sayfaları
      - Kategori bazlı etkinlik listeleme
      - Yaklaşan etkinlikler widget'ı
   
   **Mimari Yaklaşım**:
   
   Etkinlikler modülü, Controller tabanlı bir yapı ve hizmet sınıfları kullanılarak geliştirilmiştir:
   - **EventManagerController**: Admin panel yönetimi
   - **EventScrapeController**: Dış kaynak veri çekme yönetimi
   - **EventController**: Önyüz istek yönetimi
   - **EventScraperService**: Dış kaynaklardan etkinlik verisi çekme servisi
   
   **Admin Panel Özellikleri**:
   - Etkinlik oluşturma, düzenleme, silme
   - Galeri görsellerini yönetme
   - Kategori yönetimi
   - Etkinlik görünürlüğünü kontrol etme
   - Etkinlikleri vurgulama (featured yapma)
   - Anasayfada gösterim kontrolü
   - Modül genel ayarları
   - Dış kaynaklardan etkinlik verisi çekme
   - Toplu etkinlik işlemleri (bulk actions)
   
   **Önyüz Rotaları**:
   - `/etkinlikler` - Tüm etkinlikler listesi
   - `/etkinlikler/takvim` - Takvim görünümü
   - `/etkinlikler/kategori/{slug}` - Kategori bazlı etkinlikler
   - `/etkinlikler/{slug}` - Tekil etkinlik görüntüleme

6. **Anasayfa Yönetimi** - Slider, hızlı menüler ve diğer özel bölümler

   **Anasayfa Yönetimi Modülü (Homepage Management) - Detaylı Açıklama**
   
   Anasayfa Yönetimi modülü, web sitesinin en önemli sayfası olan ana sayfanın tüm bileşenlerini kontrol etmek için geliştirilmiş çok yönlü bir sistemdir. Bu modül, slider, hızlı erişim menüleri, profil bilgileri, mobil uygulama tanıtımı ve logo alanları gibi çeşitli bölümleri yönetir.
   
   **Model Yapısı:**
   - **Slider Model**: Anasayfa slider'larını yöneten model
     - `title`, `subtitle` - Başlık ve alt başlık
     - `image` - Slider görseli
     - `button_text`, `button_url` - Buton metni ve URL'i
     - `order` - Sıralama değeri
     - `is_active` - Görünürlük durumu
   
   - **QuickMenuCategory Model**: Hızlı erişim menü kategorilerini yöneten model
     - `name`, `description` - Kategori adı ve açıklaması
     - `icon` - Kategori ikonu
     - `order` - Sıralama değeri
     - `is_active` - Görünürlük durumu
   
   - **QuickMenuItem Model**: Hızlı erişim menü öğelerini yöneten model
     - `category_id` - Bağlı olduğu kategori
     - `title` - Öğe başlığı
     - `url` - Bağlantı adresi
     - `icon` - Öğe ikonu
     - `order` - Sıralama değeri
     - `is_active` - Görünürlük durumu
   
   - **ProfileSettings Model**: Profil bölümü ayarlarını içeren model
     - `name`, `title` - İsim ve ünvan/pozisyon
     - `profile_photo` - Profil fotoğrafı
     - `social_media_links` - Sosyal medya bağlantıları
     - `contact_image` - İletişim görseli
   
   - **MobileAppSettings Model**: Mobil uygulama bölümü ayarlarını içeren model
     - `app_name`, `app_subtitle`, `app_description` - Uygulama bilgileri
     - `app_logo`, `phone_image` - Uygulama görselleri
     - `app_store_link`, `google_play_link` - Uygulama mağaza linkleri
     - `link_cards` - Hızlı erişim kartları
     - `is_active` - Bölüm görünürlüğü
   
   - **LogoPlanSettings Model**: Logo ve plan bölümü ayarlarını içeren model
     - `title`, `description` - Bölüm başlığı ve açıklaması
     - `background_image` - Arka plan görseli
     - `logos` - Logo görselleri ve bağlantıları
     - `is_active` - Bölüm görünürlüğü
   
   - **FeaturedService/FeaturedServiceSetting Model**: Öne çıkan hizmetler bölümü ayarlarını içeren modeller
     - Hizmet başlık ve açıklamaları
     - Görseller ve ikonlar
     - Sıralama ve görünürlük değerleri
   
   **Özellikler:**
   
   1. **Slider Yönetimi**:
      - Sınırsız sayıda slider ekleme
      - Her slider için başlık, alt başlık ve buton tanımlama
      - Sürükle-bırak ile slider sıralama
      - Görünürlük kontrolü
   
   2. **Hızlı Erişim Menüsü**:
      - Kategorili menü yapısı
      - Özelleştirilebilir ikonlar
      - Dinamik menü öğesi yönetimi
      - Sıralama ve görünürlük kontrolü
   
   3. **Profil Bilgileri Bölümü**:
      - Kurumsal kimlik veya kişisel profil sunumu
      - Sosyal medya entegrasyonu
      - Görsel özelleştirme
   
   4. **Mobil Uygulama Tanıtımı**:
      - Mobil uygulama görselleri ve açıklamaları
      - Mağaza bağlantıları
      - Hızlı link kartları
      - Bölüm görünürlük kontrolü
   
   5. **Logo ve Kurumsal Plan Alanı**:
      - Referans logoları gösterimi
      - Arka plan görseli değiştirme
      - Başlık ve açıklama özelleştirme
      - Bölüm görünürlük kontrolü
   
   6. **Öne Çıkan Hizmetler**:
      - Anasayfada gösterilecek hizmetleri belirleme
      - Özel ikon ve görsel ekleyebilme
      - Sürükle-bırak ile sıralama
      - Bölüm görünürlük kontrolü
   
   **Mimari Yaklaşım**:
   
   Anasayfa Yönetimi modülü, admin controller yapısı kullanılarak geliştirilmiştir:
   - **HomepageManagerController**: Tüm anasayfa bölümlerinin yönetimini sağlar
   - **HomepageController**: Slider ve hızlı menü yönetimini sağlar
   
   **Admin Panel Özellikleri**:
   - Tüm anasayfa bölümlerini tek panel üzerinden yönetme
   - Görsel yükleme ve düzenleme
   - Sıralama ve görünürlük kontrolü
   - Özelleştirilebilir bölüm içerikleri
   - Anında önizleme olanağı
   
   **Önyüz Entegrasyonu**:
   - Tüm bölümlerin anasayfada dinamik görüntülenmesi
   - Duyarlı (responsive) tasarım desteği
   - İlgili sayfalarla otomatik bağlantı

7. **Kullanıcı ve Rol Yönetimi** - Yetkilendirme sistemi

   **Kullanıcı ve Rol Yönetimi Modülü (User & Role Management) - Detaylı Açıklama**
   
   Kullanıcı ve Rol Yönetimi modülü, sistemdeki kullanıcıların yönetimi ve erişim kontrollerini sağlayan temel güvenlik bileşenidir. Bu modül, Spatie Laravel Permission paketi kullanılarak geliştirilmiş, kapsamlı bir yetkilendirme sistemi sunar.
   
   **Model Yapısı:**
   - **User Model**: Sistem kullanıcılarının temel özelliklerini içeren model
     - `name`, `email` - Temel kullanıcı bilgileri
     - `password` - Şifrelenmiş kullanıcı parolası
     - `is_active` - Kullanıcı aktiflik durumu
     - `avatar` - Kullanıcı profil resmi
     - `bio` - Biyografi/kullanıcı açıklaması
   
   - **Role Model (Spatie)**: Sistemdeki kullanıcı rollerini tanımlayan model
     - `name`, `guard_name` - Rol adı ve koruma adı
     - İlişkili izinler listesi
   
   - **Permission Model (Spatie)**: Sistemdeki izinleri tanımlayan model
     - `name`, `guard_name` - İzin adı ve koruma adı
     - İlişkili roller listesi
   
   **Özellikler:**
   
   1. **Kullanıcı Yönetimi**:
      - Kullanıcı oluşturma, düzenleme, silme
      - Kullanıcı profil resmi yükleme
      - Kullanıcı aktiflik durumu kontrolü
      - Güvenli şifre yönetimi (hashing)
   
   2. **Rol Tabanlı Erişim Kontrolü (RBAC)**:
      - Önceden tanımlanmış roller (admin, editor, vb.)
      - Rol oluşturma, düzenleme, silme
      - Her role özel izinler atama
      - Kullanıcılara birden fazla rol atayabilme
   
   3. **İzin Sistemi**:
      - Granüler izin tanımları (kaynak-eylem bazlı)
      - Her modül için ayrı izin grupları
      - İzinleri doğrudan kullanıcılara veya roller üzerinden atama
   
   4. **Middleware İle Güvenlik**:
      - URL bazlı erişim kısıtlamaları
      - Route gruplarına rol ve izin kontrolü
      - Blade direktifleri ile görünüm bazlı kısıtlama
   
   5. **Hesap Güvenliği**:
      - Güvenli şifre politikaları
      - En az bir yönetici hesabı zorunluluğu
      - Kullanıcı oturum yönetimi
   
   **Mimari Yaklaşım**:
   
   Kullanıcı ve Rol Yönetimi modülü, MVC mimarisi ile geliştirilmiştir:
   - **UserController/RoleController**: Kullanıcı ve rol yönetimi
   - **Auth Middleware**: Kimlik doğrulama ve yetkilendirme katmanı
   - **Spatie Permission**: Yetkilendirme kütüphanesi entegrasyonu
   
   **Admin Panel Özellikleri**:
   - Kullanıcı yönetimi arayüzü
   - Rol oluşturma ve düzenleme
   - Kullanıcılara rol atama
   - Sistem izin matrisinin görselleştirilmesi
   - Kullanıcı etkinliklerinin izlenmesi
   
   **Güvenlik Önlemleri**:
   - Rol hiyerarşisi (admin > editor > yazar vb.)
   - Kritik işlemler için yetki kontrolü
   - Son admin kullanıcısının silinmesine karşı koruma
   - Admin panel erişim kısıtlaması

## Veritabanı Yapısı
Sistem, birçok modele sahip kapsamlı bir veritabanı yapısı kullanmaktadır:

- **Temel modeller**: User, Category, Media, Menu, MenuItem
- **Sayfa modelleri**: Page, PageCategory, PageTag
- **Haber modelleri**: News, NewsCategory, NewsTag
- **Hizmet modelleri**: Service, ServiceCategory, ServiceTag, FeaturedService
- **Proje modelleri**: Project, ProjectCategory, ProjectGallery, ProjectImage, ProjectSettings
- **Etkinlik modelleri**: Event, EventCategory, EventImage, EventSettings
- **Anasayfa modelleri**: Slider, QuickMenuCategory, QuickMenuItem, ProfileSettings, MobileAppSettings, LogoPlanSettings

## Kullanılan Teknolojiler ve Paketler

### Backend (PHP/Laravel):
- Laravel 10.x framework
- Spatie Laravel Permission (rol ve izin yönetimi)
- UniSharp Laravel Filemanager (dosya yönetimi)
- Laravel AdminLTE (admin panel teması)
- Doctrine DBAL (veritabanı soyutlama katmanı)

### Frontend (JS/CSS):
- Bootstrap 5.x
- Axios (HTTP istekleri)
- Vite (derleme aracı)

## Mimari Yapı

### Controller Yapısı:
- Admin ve Front (önyüz) olarak ayrılmış controller'lar
- CRUD işlemleri için Resource controller'lar
- Modül bazlı yönetim controller'ları

### View Yapısı:
- Admin ve Front olarak ayrılmış blade şablonları
- Modüler bölümler ve partial'lar
- AdminLTE tabanlı admin arayüzü

### Route Yapısı:
- Admin paneli için prefix/middleware gruplanmış rotalar
- Önyüz için SEO dostu slug tabanlı rotalar
- Modül bazlı gruplandırılmış rotalar

## Özellikler ve İşlevsellik

### İçerik Yönetimi:
- CRUD işlemleri (oluşturma, okuma, güncelleme, silme)
- Kategori ve etiketleme sistemi
- İçerik sıralama (drag and drop)
- Görünürlük kontrolü (aktif/pasif)
- Zengin metin editörü (TinyMCE) entegrasyonu
- Dosya/medya yönetimi

### Önyüz Özellikleri:
- Dinamik anasayfa bölümleri
- Hızlı erişim menüleri
- Etkinlik takvimi
- Proje galerileri
- Hizmet listelemeleri

### Diğer Özellikler:
- Rol tabanlı yetkilendirme sistemi
- Etkinlik veri çekme (scraping) özelliği
- Responsive web tasarımı
- Mobil uygulama bağlantısı ayarları

## Modüllerin Detaylı Açıklaması

1. **Sayfalar Modülü**:
   - Kategori ve etiket bazlı sayfa yönetimi
   - Öne çıkan sayfalar ve durum kontrolü
   - Sayfa sıralama ve arşivleme

2. **Haberler Modülü**:
   - Manşet haberleri yönetimi
   - Kategori ve etiket bazlı haberler
   - Haber arşivleme ve durum kontrolü

3. **Hizmetler Modülü**:
   - Kurumsal hizmet içerik yönetimi
   - Öne çıkan hizmetler ve anasayfa görünürlüğü
   - Kategori ve etiket bazlı hizmet organizasyonu

4. **Projeler Modülü**:
   - Proje galerisi yönetimi
   - Kategori bazlı proje organizasyonu
   - Sürükle-bırak sıralama ve görünürlük kontrolü

5. **Etkinlikler Modülü**:
   - Etkinlik takvimi ve detay yönetimi
   - Dış kaynaklardan etkinlik verisi çekme
   - Kategori bazlı etkinlik filtreleme

6. **Anasayfa Yönetimi**:
   - Slider/banner yönetimi
   - Hızlı erişim menüsü yapılandırması
   - Profil ve mobil uygulama ayarları
   - Logo plan bölümü yapılandırması
   - Öne çıkan içerik bölümleri kontrolü

## Güvenlik ve Yetkilendirme

- Spatie Laravel Permission kullanarak rol tabanlı yetkilendirme
- Middleware ile yetkilendirme kontrolleri
- Admin paneline erişim güvenliği

## Rota Yapısı

### Admin Rotaları
- `/admin` - Admin paneli ana sayfa
- `/admin/news` - Haber yönetimi
- `/admin/services` - Hizmet yönetimi
- `/admin/pages` - Sayfa yönetimi
- `/admin/projects` - Proje yönetimi
- `/admin/events` - Etkinlik yönetimi
- `/admin/homepage` - Anasayfa yönetimi
- `/admin/users` - Kullanıcı yönetimi
- `/admin/roles` - Rol yönetimi

### Frontend Rotaları
- `/` - Ana sayfa
- `/hizmetler` - Hizmet listesi
- `/hizmetler/{slug}` - Hizmet detayı
- `/sayfalar/{slug}` - Sayfa görüntüleme
- `/projeler` - Proje listesi
- `/projeler/{slug}` - Proje detayı
- `/etkinlikler` - Etkinlik listesi
- `/etkinlikler/{slug}` - Etkinlik detayı

## Sonuç

Bu CMS, kurumsal bir web sitesi için geliştirilmiş kapsamlı bir içerik yönetim sistemidir. Modüler yapısı sayesinde ölçeklenebilir ve genişletilebilirdir. Sistem, içerik yönetimi için gerekli tüm temel özellikleri içermekte olup, kurumsal ihtiyaçlara yönelik özelleştirilmiş modüller sunmaktadır.

Temel güçlü yönleri:
1. Modüler yapı sayesinde kolay genişletilebilirlik
2. Rol tabanlı erişim kontrolü
3. Detaylı içerik kategorilendirme sistemi
4. Zengin medya yönetim araçları
5. Sezgisel kullanıcı arayüzü