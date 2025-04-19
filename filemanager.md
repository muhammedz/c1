# Laravel File Manager Modülü Geliştirme Planı

Bu dosya, Laravel için Wordpress Media Library tarzında yeni bir file manager modülünün geliştirme planını içerir.

## Temel Bilgiler
- Dosya depolama: `public/uploads` dizini (symlink kullanılmayacak)
- Wordpress Media Library benzeri arayüz ve işlevsellik
- Tüm CMS modüllerine entegre edilebilir yapıda
- AJAX tabanlı dinamik yükleme ve işlevsellik
- Responsive tasarım ile mobil uyumluluk

## Veritabanı Tasarımı

### filemanagersystem_medias Tablosu
| Alan Adı | Veri Tipi | Açıklama |
|----------|-----------|----------|
| id | bigint(20) unsigned | Otomatik artan birincil anahtar |
| file_name | varchar(255) | Dosyanın orijinal adı |
| file_path | varchar(255) | Dosyanın sunucudaki yolu |
| file_url | varchar(255) | Dosyanın erişim URL'i |
| file_size | int(10) unsigned | Dosya boyutu (byte cinsinden) |
| file_type | varchar(100) | Dosya tipi (mime type) |
| extension | varchar(10) | Dosya uzantısı |
| alt_text | varchar(255) | Görsel için alternatif metin |
| title | varchar(255) | Dosya başlığı |
| description | text | Dosya açıklaması |
| folder_id | bigint(20) unsigned | Bağlı olduğu klasör ID'si (foreign key) |
| category_id | bigint(20) unsigned | Bağlı olduğu kategori ID'si (foreign key) |
| is_public | tinyint(1) | Dosyanın herkese açık olup olmadığı |
| custom_properties | json | Özel özellikler (JSON formatında) |
| dimensions | varchar(50) | Görsel boyutları (örn: 800x600) |
| uploaded_by | bigint(20) unsigned | Yükleyen kullanıcı ID'si (foreign key) |
| created_at | timestamp | Oluşturma tarihi |
| updated_at | timestamp | Güncelleme tarihi |
| deleted_at | timestamp | Silme tarihi (soft delete için) |

### filemanagersystem_folders Tablosu
| Alan Adı | Veri Tipi | Açıklama |
|----------|-----------|----------|
| id | bigint(20) unsigned | Otomatik artan birincil anahtar |
| name | varchar(255) | Klasör adı |
| slug | varchar(255) | URL-friendly klasör adı |
| path | varchar(255) | Klasörün tam yolu (üst klasörlerle birlikte) |
| parent_id | bigint(20) unsigned | Üst klasör ID'si (self referencing) |
| order | int(10) unsigned | Sıralama değeri |
| created_by | bigint(20) unsigned | Oluşturan kullanıcı ID'si |
| created_at | timestamp | Oluşturma tarihi |
| updated_at | timestamp | Güncelleme tarihi |
| deleted_at | timestamp | Silme tarihi (soft delete için) |

### filemanagersystem_categories Tablosu
| Alan Adı | Veri Tipi | Açıklama |
|----------|-----------|----------|
| id | bigint(20) unsigned | Otomatik artan birincil anahtar |
| name | varchar(255) | Kategori adı |
| slug | varchar(255) | URL-friendly kategori adı |
| description | text | Kategori açıklaması |
| parent_id | bigint(20) unsigned | Üst kategori ID'si (self referencing) |
| order | int(10) unsigned | Sıralama değeri |
| created_by | bigint(20) unsigned | Oluşturan kullanıcı ID'si |
| created_at | timestamp | Oluşturma tarihi |
| updated_at | timestamp | Güncelleme tarihi |
| deleted_at | timestamp | Silme tarihi (soft delete için) |

### filemanagersystem_relations Tablosu
| Alan Adı | Veri Tipi | Açıklama |
|----------|-----------|----------|
| id | bigint(20) unsigned | Otomatik artan birincil anahtar |
| media_id | bigint(20) unsigned | Medya dosyası ID'si (foreign key) |
| related_id | bigint(20) unsigned | İlişkili içerik ID'si |
| related_type | varchar(255) | İlişkili içerik tipi (morph ilişkisi için) |
| field_name | varchar(100) | İlişkilendirilen alan adı (örn: 'featured_image', 'gallery') |
| order | int(10) unsigned | Sıralama değeri (galeri için) |
| custom_properties | json | Özel özellikler (JSON formatında) |
| created_at | timestamp | Oluşturma tarihi |
| updated_at | timestamp | Güncelleme tarihi |

## Görev Listesi

### 1. Veritabanı Yapısı
- [x] filemanagersystem_medias tablosu migration dosyası oluşturma
  - Tüm dosya bilgilerini (ad, yol, boyut, tip) depolama
  - Meta verileri (alt text, başlık, açıklama) depolama
  - İlişkisel referanslar (klasör, kategori, kullanıcı)
- [x] filemanagersystem_folders tablosu migration dosyası oluşturma
  - Klasör hiyerarşisi için self-referencing ilişki
  - Sıralama ve görünürlük özellikleri
- [x] filemanagersystem_categories tablosu migration dosyası oluşturma
  - Kategori hiyerarşisi için self-referencing ilişki
  - Tanımlayıcı özellikler (slug, açıklama)
- [x] filemanagersystem_relations tablosu migration dosyası oluşturma
  - Polymorphic ilişki yapısı (herhangi bir modelle ilişkilendirme)
  - Medya dosyaları ile içerikler arasında çoka-çok ilişki
- [x] Migration'ları çalıştırma
  - Development ortamında test
  - Migration rollback planı

### 2. Model Yapısı
- [x] FilemanagersystemMedia modeli oluşturma
  - Temel özellikler ve ilişkiler
  - Dosya tipi belirleme metodları (isImage, isVideo, isDocument gibi)
  - URL ve path accessors
  - Thumbnail oluşturma ve erişim metodları
  - Soft delete yapılandırması
- [x] FilemanagersystemFolder modeli oluşturma
  - Hiyerarşik yapı metodları (getParent, getChildren, getAncestors)
  - Path oluşturma ve ayrıştırma
  - Klasör içi dosya ve alt klasör listeleme metodları
- [x] FilemanagersystemCategory modeli oluşturma
  - Hiyerarşik kategori yapısı
  - Kategoriye ait medya dosyalarını listeleme
  - Slug oluşturma (setNameAttribute)
- [x] FilemanagersystemRelation modeli oluşturma
  - Polymorphic ilişki tanımları
  - İlişkili içerik metodları
  - Sıralama işlevleri
- [x] Model ilişkilerini tanımlama
  - One-to-many ilişkiler (folder->media, category->media)
  - Many-to-many ilişkiler (media->categories)
  - Polymorphic ilişkiler (medya ile diğer modeller arasında)
- [x] Model accessor ve mutator metodlarını tanımlama
  - URL oluşturma accessors
  - Boyut formatlandırma (getHumanReadableSizeAttribute)
  - Resim boyutlarını parçalama (getWidthAttribute, getHeightAttribute)

### 3. Controller Yapısı
- [ ] FilemanagersystemController oluşturma (ana controller)
  - Ana görünüm rendering
  - Rota parametrelerini işleme (type, related_to, related_id)
  - Seçim modu yapılandırması (tekli/çoklu)
- [x] FilemanagersystemMediaController oluşturma (dosya işlemleri)
  - Dosya listeleme işlevi (pagination, filtreleme)
  - Dosya yükleme işlevi (validation, storage)
  - Dosya güncelleme işlevi (rename, meta veriler)
  - Dosya silme işlevi (soft/hard delete)
  - Dosya detayları görüntüleme
  - Toplu işlemler (çoklu seçim, çoklu silme)
- [x] FilemanagersystemFolderController oluşturma (klasör işlemleri)
  - Klasör listeleme (hiyerarşik yapı)
  - Klasör oluşturma
  - Klasör güncelleme
  - Klasör silme (içeriğiyle veya içeriğini taşıyarak)
  - Ağaç yapısı verisi döndürme (TreeView için)
- [x] FilemanagersystemCategoryController oluşturma (kategori işlemleri)
  - Kategori listeleme (hiyerarşik)
  - Kategori oluşturma
  - Kategori güncelleme
  - Kategori silme
  - Kategoriye göre dosya filtreleme
- [ ] FilemanagersystemRelationController oluşturma (ilişkilendirme işlemleri)
  - İçerik ile dosya ilişkilendirme
  - İlişki silme
  - İlişkili dosyaları listeleme
  - İlişkili dosyaları sıralama

### 4. Servis Sınıfları
- [ ] FilemanagersystemService oluşturma
  - Yapılandırma yükleme (config)
  - Genel yetkilendirme metodları
  - File manager durumunu yönetme
- [ ] FilemanagersystemMediaService oluşturma (dosya yükleme, silme, düzenleme)
  - Dosya yükleme işlemi (validateFile, saveFile)
  - Dosya silme işlemi (softDelete, forceDelete)
  - Dosyayı taşıma (moveFile)
  - Dosya meta verilerini güncelleme
  - Mime type tespiti ve doğrulama
  - Dosya adı çakışmalarını çözme
- [ ] FilemanagersystemFolderService oluşturma (klasör işlemleri)
  - Klasör oluşturma (fiziksel klasör + DB kaydı)
  - Klasör silme (içerikle veya içeriğini taşıyarak)
  - Klasör taşıma
  - Klasör ağacı oluşturma
  - Fiziksel klasör ile DB uyumunu kontrol etme
- [ ] FilemanagersystemCategoryService oluşturma (kategori işlemleri)
  - Kategori CRUD işlemleri
  - Kategori ağacı oluşturma
  - Kategoriler arası dosya taşıma
- [ ] FilemanagersystemImageService oluşturma (resim düzenleme)
  - Resim boyutlandırma (resize)
  - Resim kırpma (crop)
  - Resim döndürme (rotate)
  - Thumbnail oluşturma (farklı boyutlar için)
  - Resim kalitesi ayarlama
  - Intervention Image kütüphanesi entegrasyonu

### 5. Route Tanımları
- [x] Admin panel rotaları (web.php)
  - Ana dosya yönetim sistemi görünümü (/admin/filemanagersystem)
  - Medya CRUD rotaları 
  - Klasör CRUD rotaları
  - Kategori CRUD rotaları
  - İlişki yönetimi rotaları
- [ ] API rotaları (api.php)
  - AJAX ile dosya yükleme
  - AJAX ile dosya listeleme
  - AJAX ile klasör işlemleri
  - AJAX ile kategori işlemleri
  - AJAX ile arama ve filtreleme
- [ ] Middleware tanımları (yetkilendirme için)
  - FilemanagersystemPermission middleware
  - FilemanagersystemUploadSecurity middleware
  - RoleBasedAccess middleware

### 6. View Yapısı
- [x] Admin paneli ana görünümü
  - Master layout entegrasyonu
  - JavaScript bileşenleri için container
  - SEO ve meta etiketleri
- [x] Dosya yükleme sayfası/modalı
  - Drag & drop bölgesi
  - Dosya seçme butonu
  - Yükleme ilerleme çubuğu
  - Hata bildirimleri
- [x] Dosya listeleme sayfası
  - Izgara görünümü (grid view)
  - Liste görünümü (list view)
  - Sıralama seçenekleri
  - Filtreleme arayüzü
  - Sayfalama (pagination)
- [x] Klasör yönetimi sayfası
  - Klasör ağacı (TreeView)
  - Klasör içeriği görünümü
  - Klasör ekleme/düzenleme/silme arayüzü
- [x] Kategori yönetimi sayfası
  - Kategori ağacı
  - Kategori ekleme/düzenleme/silme arayüzü
  - Kategoriye göre filtreleme
- [x] Dosya düzenleme/detay sayfası
  - Dosya önizleme
  - Meta veri düzenleme formu
  - Resim düzenleme araçları
  - İlişkili içerikler listesi
- [ ] İçerik editörlerine entegre edilebilir modal
  - TinyMCE entegrasyonu için
  - İçerik formları için dosya seçici
  - Tekli/çoklu seçim modu
  - Seçim sonrası callback fonksiyonları

### 7. Frontend Geliştirme
- [ ] CSS/SCSS yapısı oluşturma
  - Modüler SCSS mimarisi
  - Tema değişkenleri ve renkler
  - Responsive breakpoint'ler
  - Animasyon ve geçiş efektleri
  - Icon set entegrasyonu
- [ ] JavaScript bileşenleri oluşturma
  - Vanilla JavaScript ve jQuery yapılandırması
  - AJAX ile veri işleme
  - Event yönetimi
  - Modüler yapı
- [ ] File uploader bileşeni
  - Dropzone.js entegrasyonu
  - Çoklu dosya yükleme
  - İlerleme göstergesi
  - Hata yönetimi
  - MIME tipi kontrolü
- [ ] File browser bileşeni
  - Izgara ve liste görünümleri
  - Seçim işlemleri (tekli/çoklu)
  - Önizleme oluşturma
  - Sağ tıklama menüsü
  - Klavye kısayolları
- [ ] Folder tree bileşeni
  - Hiyerarşik klasör yapısı
  - Klasör açma/kapama
  - Yeni klasör ekleme
  - Drag & drop ile klasör taşıma
  - Klasör içeriğini gösterme
- [ ] Image editor bileşeni
  - Cropper.js entegrasyonu
  - Resim kırpma
  - Resim döndürme
  - Resim boyutlandırma
  - Filtre uygulama
- [ ] File picker modal bileşeni
  - İçerik editörleri için modal
  - Callback fonksiyonları
  - Seçim modu (tekli/çoklu)
  - Filtreleme ve arama entegrasyonu
- [ ] Drag & drop işlemleri
  - Dosya sürükleme ve bırakma
  - Klasörler arası dosya taşıma
  - Kategoriler arası dosya taşıma
  - Sıralama için sürükle bırak
- [ ] Responsive tasarım
  - Mobil öncelikli yaklaşım
  - Breakpoint'lere göre davranış değişiklikleri
  - Touch desteği
  - Mobil cihazlarda optimizasyon

### 8. Dosya İşleme Özellikleri
- [x] Toplu dosya yükleme
  - Çoklu dosya seçimi
  - Sürükle bırak yükleme
  - Yığın işleme
  - İlerleme göstergesi
- [x] Dosya önizleme
  - Resim önizleme (farklı boyutlar)
  - Video önizleme (poster frame, oynatıcı)
  - PDF önizleme (ilk sayfa thumbnail)
  - Belge önizleme (icon ve meta veriler)
- [x] Dosya düzenleme (yeniden adlandırma, açıklama ekleme)
  - İnline düzenleme
  - Form tabanlı düzenleme
  - Meta verileri düzenleme
- [x] Dosya silme (tekli ve toplu)
  - Soft delete (geri dönüştürülebilir)
  - Kalıcı silme
  - Silme onayı
  - Toplu silme işlemleri
- [ ] Dosya kopyalama/taşıma
  - Klasörler arası taşıma
  - Dosya kopyalama
  - Taşıma sırasında çakışma yönetimi
  - Bulk actions için çoklu taşıma
- [ ] Resim kırpma ve boyutlandırma
  - Visual cropper
  - Aspect ratio seçenekleri
  - Önizleme
  - Custom ölçüler

### 9. Klasör ve Kategori Yönetimi
- [x] Klasör oluşturma
  - Üst klasör seçimi
  - Ad ve açıklama alanları
  - Varsayılan izinler
- [x] Klasör düzenleme
  - Ad ve açıklama güncelleme
  - Taşıma (üst klasör değiştirme)
  - İzinleri güncelleme
- [x] Klasör silme
  - İçerik ile birlikte silme
  - İçeriği başka klasöre taşıma
  - Silme onayı
- [x] Kategori oluşturma
  - Üst kategori seçimi
  - Ad, slug ve açıklama alanları
  - SEO bilgileri
- [x] Kategori düzenleme
  - Meta verileri güncelleme
  - İlişkili dosyaları görüntüleme
  - Taşıma (üst kategori değiştirme)
- [x] Kategori silme
  - İlişkili dosyaları yönetme
  - Silme onayı
- [x] Ağaç yapısı hiyerarşi
  - Sürükle bırak ile düzenleme
  - Genişlet/daralt fonksiyonları
  - Düzey bazlı gösterim
  - İç içe klasör/kategori desteği

### 10. Arama ve Filtreleme
- [ ] Dosya adına göre arama
  - Anlık arama (real-time)
  - Fuzzy search desteği
  - Gelişmiş arama parametreleri
- [ ] Dosya tipine göre filtreleme
  - Resim filtreleme
  - Video filtreleme
  - Belge filtreleme
  - Özel MIME türlerine göre filtreleme
- [x] Klasöre göre filtreleme
  - Tek klasör seçimi
  - Alt klasörlerle birlikte filtreleme
  - Klasör dışı dosyaları gösterme
- [x] Kategoriye göre filtreleme
  - Tek kategori seçimi
  - Çoklu kategori seçimi
  - Kategori dışı dosyaları gösterme
- [ ] Tarihe göre filtreleme
  - Tarih aralığı seçimi
  - Önceden tanımlanmış periodlar (Bugün, Bu hafta, Bu ay)
  - Yükleme veya güncelleme tarihine göre
- [ ] Yükleyen kullanıcıya göre filtreleme
  - Kullanıcı listesi
  - Çoklu kullanıcı seçimi
  - Sadece kendi dosyalarını görme modu

### 11. TinyMCE Entegrasyonu
- [ ] TinyMCE eklentisi oluşturma
  - Plugin dosyası oluşturma
  - Buton ve komutlar ekleme
  - Config entegrasyonu
- [ ] File picker butonu ekleme
  - Toolbar butonu
  - Shortcut key
  - Medya insertion dialog
- [ ] Seçilen dosyanın editöre eklenmesi
  - Resim ekleme (responsive seçeneklerle)
  - Video ekleme (player seçenekleriyle)
  - Dosya linki ekleme
  - Galeri ekleme
- [ ] Resim düzenleme entegrasyonu
  - Editördeki resmi düzenleme
  - Alt ve title etiketleri
  - Alignment ve boyut seçenekleri
  - Caption ekleme

### 12. İçerik Modülleri Entegrasyonu
- [ ] Sayfalar modülü entegrasyonu
  - Öne çıkan görsel seçimi
  - Galeri entegrasyonu
  - İçerik editöründe kullanım
- [ ] Haberler modülü entegrasyonu
  - Haber görseli seçimi
  - Galeri entegrasyonu
  - İçerik editöründe kullanım
- [ ] Projeler modülü entegrasyonu
  - Proje görseli seçimi
  - Proje galerisi entegrasyonu
  - Proje dosyaları entegrasyonu
- [ ] Hizmetler modülü entegrasyonu
  - Hizmet görseli seçimi
  - Hizmet galerisi entegrasyonu
  - İçerik editöründe kullanım
- [ ] Diğer modüller için genel entegrasyon yapısı
  - Generic media picker entegrasyonu
  - Trait veya interface ile standardizasyon
  - Entegrasyon dökümantasyonu

### 13. Yetkilendirme ve Güvenlik
- [x] Rol tabanlı yetkilendirme
  - Admin rolü (tam erişim)
  - Editor rolü (kendi yüklediği ve ortak dosyalar)
  - Katkıcı rolü (sadece kendi yüklediği dosyalar)
  - Okuyucu rolü (sadece görüntüleme)
- [ ] Dosya tipi kontrolü
  - İzin verilen MIME tipleri
  - Yasaklı dosya uzantıları
  - Filtreleme ve doğrulama
- [ ] Dosya boyutu kontrolü
  - Maksimum boyut sınırlaması
  - Resim boyutları sınırlaması
  - Boyut uyarıları
- [ ] CSRF koruması
  - Form ve AJAX isteklerinde CSRF token
  - CSRF exception handling
- [ ] XSS koruması
  - Input sanitization
  - Output escaping
  - Content Security Policy

### 14. Cache ve Performans
- [ ] Önbellek yapısı oluşturma
  - Cache driver yapılandırması
  - Cache stratejisi (TTL, tags)
  - Cache invalidation kuralları
- [ ] Resim thumbnail'leri önbellekleme
  - Otomatik thumbnail oluşturma
  - Lazy oluşturma (ilk istek anında)
  - Thumbnail yeniden oluşturma (regeneration)
- [ ] Klasör/dosya listesini önbellekleme
  - Hiyerarşik yapı cacheing
  - Sayfalama ile cache entegrasyonu
  - Filtreleme sonuçlarını cacheleme
- [ ] Lazy loading (tembel yükleme) yapısı
  - Görünür alan içindeki içeriği yükleme
  - Scroll ile daha fazla içerik yükleme
  - Image lazy loading

### 15. Test ve Debug
- [ ] Unit testler yazma
  - Model testleri
  - Servis testleri
  - Controller testleri
- [ ] Feature testler yazma
  - Dosya yükleme testleri
  - Klasör/kategori işlemleri testleri
  - Arama ve filtreleme testleri
  - API endpoint testleri
- [ ] Browser testler yazma
  - UI etkileşim testleri
  - JavaScript fonksiyonlarını test etme
  - Form ve AJAX işlemlerini test etme
- [ ] Hata ayıklama ve düzeltme
  - Error logging
  - Exception handling
  - Debug modunu yapılandırma
  - Performance profiling

### 16. Dökümantasyon
- [ ] Kullanıcı kılavuzu hazırlama
  - Kullanım adımları
  - Ekran görüntüleri
  - Özellik açıklamaları
  - SSS bölümü
- [ ] API dökümantasyonu
  - Endpoint listesi
  - Request/response formatları
  - Authentication gereksinimleri
  - Örnek kodlar
- [ ] Kod dökümantasyonu
  - PHPDoc annotations
  - README dosyaları
  - Mimari açıklamaları
  - Class ve method açıklamaları
- [ ] Kurulum kılavuzu
  - Sistem gereksinimleri
  - Kurulum adımları
  - Yapılandırma seçenekleri
  - Troubleshooting

### 17. Dağıtım
- [ ] Mevcut file manager'dan yeni sisteme geçiş planı
  - Eski sistemden veri aktarımı
  - Rota değişiklikleri
  - Geriye dönük uyumluluk
- [ ] Verileri aktarma scripti
  - Dosya taşıma
  - Veritabanı kayıtlarını aktarma
  - İlişkileri koruma
- [ ] Canlı ortama geçiş
  - Deployment süreci
  - Zero-downtime update
  - Rollback planı
- [ ] Performans izleme
  - Metrics toplama
  - Bottleneck analizi
  - Optimizasyon planı

## Teknik Mimari Şeması

```
+------------------+    +-------------------+    +------------------+
| Client Interface |    | TinyMCE/Editor    |    | Content Forms    |
+--------+---------+    +--------+----------+    +---------+--------+
         |                       |                        |
         v                       v                        v
+------------------+    +-------------------+    +------------------+
| File Picker      |    | Media Insertion   |    | Field Components |
+--------+---------+    +--------+----------+    +---------+--------+
         |                       |                        |
         +----------------------++-------------------------+
                                |
                                v
+----------------------------------------------------------+
| Frontend Layer (JavaScript Components)                   |
| +-----------------+  +----------------+  +--------------+|
| | File Browser    |  | Folder Tree    |  | Image Editor ||
| +-----------------+  +----------------+  +--------------+|
+------------------------------+---------------------------+
                               |
                               v
+----------------------------------------------------------+
| Controllers (HTTP/API Handlers)                          |
| +------------------------+  +------------------------+    |
| | FilemanagersystemMedia|  | FilemanagersystemFolder|    |
| | Controller            |  | Controller             |    |
| +------------------------+  +------------------------+    |
+------------------------------+---------------------------+
                               |
                               v
+----------------------------------------------------------+
| Service Layer                                            |
| +------------------------+  +------------------------+    |
| | FilemanagersystemMedia|  | FilemanagersystemFolder|    |
| | Service               |  | Service                |    |
| +------------------------+  +------------------------+    |
+------------------------------+---------------------------+
                               |
                               v
+----------------------------------------------------------+
| Model Layer (Eloquent Models)                            |
| +------------------------+  +------------------------+    |
| | FilemanagersystemMedia|  | FilemanagersystemFolder|    |
| +------------------------+  +------------------------+    |
+------------------------------+---------------------------+
                               |
                               v
+----------------------------------------------------------+
| Database & Storage                                       |
| +------------------------+  +------------------------+    |
| | MySQL/MariaDB          |  | File System           |    |
| +------------------------+  +------------------------+    |
+----------------------------------------------------------+
```

## Kullanıcı Deneyimi Senaryoları

### Senaryo 1: İçerik Editörü - Haber Yazma
1. Editör, yeni bir haber oluşturur
2. "Öne Çıkan Görsel" alanına tıklar
3. Filemanagersystem modal açılır, sadece resim filtresi aktiftir
4. Editör, yeni bir resim yüklemek için "Yükle" butonuna tıklar
5. Drag & drop ile resmi yükler
6. Yüklenen resim seçili halde gelir
7. "Seç" butonuna tıklar
8. Modal kapanır ve seçilen resim habere öne çıkan görsel olarak eklenir

### Senaryo 2: Yönetici - Medya Organizasyonu
1. Yönetici, Filemanagersystem'i açar
2. Organizasyon için yeni bir klasör oluşturur ("2023 Etkinlikleri")
3. Toplu dosya yükleme ile etkinlik fotoğraflarını yükler
4. Yüklenen dosyaları seçer ve "Meta Verileri Düzenle" butonuna tıklar
5. Toplu olarak kategori atar ve açıklama ekler
6. Dosyaları, klasör içinde yeniden adlandırır ve sıralar

### Senaryo 3: İçerik Editörü - TinyMCE İçine Görsel Ekleme
1. Editör, TinyMCE içinde içerik yazarken görsel eklemek ister
2. TinyMCE toolbar'daki "Medya Ekle" butonuna tıklar
3. Filemanagersystem modal açılır
4. Klasör ağacından ilgili klasöre gider
5. Resmi seçer ve "Düzenle" butonuna tıklar
6. Resim düzenleyici açılır ve resmi kırpar/boyutlandırır
7. Düzenlemeyi kaydeder ve "Seç" butonuna tıklar
8. Modal kapanır ve düzenlenmiş resim içeriğe eklenir

## Özellikler Listesi

### Temel Özellikler
- [x] Drag & drop dosya yükleme
- [x] Toplu dosya yükleme
- [x] Dosya önizleme (resim, video, pdf, vb.)
- [x] Klasör oluşturma ve yönetimi
- [x] Kategori oluşturma ve yönetimi
- [x] Ağaç yapısında klasör/kategori gezinmesi
- [ ] Dosya kesme/kopyalama/yapıştırma
- [x] Dosya yeniden adlandırma
- [x] Dosya silme
- [x] Dosya meta verilerini düzenleme

### Resim İşleme Özellikleri
- [x] Resim önizleme
- [ ] Resim boyutlandırma
- [ ] Resim kırpma
- [ ] Resim döndürme
- [ ] Resim kalitesini ayarlama

### Arama ve Filtreleme Özellikleri
- [ ] Tam metin arama
- [x] Dosya tipi filtresi
- [x] Klasör filtresi
- [x] Kategori filtresi
- [ ] Tarih filtresi
- [ ] Kullanıcı filtresi

### Entegrasyon Özellikleri
- [ ] TinyMCE editörü entegrasyonu
- [ ] İçerik formları entegrasyonu
- [ ] Galeri oluşturma entegrasyonu
- [ ] Dosya seçici modal

### Güvenlik Özellikleri
- [x] Rol tabanlı dosya erişimi
- [x] Güvenli dosya yükleme kontrolü
- [x] Dosya tipi kısıtlaması
- [x] Dosya boyutu kısıtlaması

## Teknik Gereksinimleri
- Laravel 10+
- PHP 8.1+
- MySQL/MariaDB
- Intervention Image kütüphanesi (resim işleme için)
- jQuery (frontend için)
- TinyMCE (entegrasyon için)
- Dropzone.js (sürükle bırak yükleme için)
- Cropper.js (resim kırpma için)
- FontAwesome (ikonlar için)

## Zaman Çizelgesi
- [x] **1. Hafta**: Veritabanı yapısı, modeller ve migrationlar
  - Tablo tasarımı ve migration dosyaları
  - Model sınıfları ve ilişkiler
  - Factory ve seeder'lar (test verileri)
- [x] **2. Hafta**: Controller, servisler ve temel dosya işlemleri
  - Controller sınıfları
  - Servis sınıfları
  - Temel dosya yükleme ve listeleme işlevleri
  - Route tanımları
- [x] **3. Hafta**: Klasör ve kategori yönetimi
  - Klasör CRUD işlemleri
  - Kategori CRUD işlemleri
  - Hiyerarşik yapılar
  - Ağaç bileşeni
- [ ] **4. Hafta**: Frontend geliştirme, arayüz tasarımı
  - JavaScript bileşenleri
  - CSS/SCSS stil dosyaları
  - Responsive tasarım
  - Kullanıcı deneyimi iyileştirmeleri
- [ ] **5. Hafta**: TinyMCE ve içerik modülleri entegrasyonu
  - TinyMCE plugin
  - İçerik modülleri için field bileşenleri
  - Entegrasyon testleri
- [ ] **6. Hafta**: Arama, filtreleme ve resim işleme
  - Arama ve filtreleme altyapısı
  - Resim düzenleyici
  - Thumbnail sistemi
  - Önbellek mekanizmaları
- [ ] **7. Hafta**: Test, hata düzeltme ve performans iyileştirme
  - Unit ve feature testler
  - Hata düzeltmeleri
  - Performans optimizasyonları
  - Güvenlik taraması
- [ ] **8. Hafta**: Dökümantasyon ve dağıtım
  - Kullanıcı ve geliştirici dökümantasyonu
  - Deploy scripti
  - Veri aktarım araçları
  - Son kontroller ve release 