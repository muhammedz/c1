# Dinamik Mega Menü Sistemi Yol Haritası

Bu doküman, header.blade.php.bak dosyasındaki tasarımı koruyarak tam dinamik menü yapısı oluşturmak için gerekli adımları içerir. Her adım tamamlandığında ilgili kutucuğu işaretleyebilirsiniz.

## 1. Veritabanı Yapısı İyileştirmeleri

### Temel Tablo Güncellemeleri
- [x] `menus` tablosuna `mega_menu_layout` alanı ekleme (VARCHAR, NULL olabilir)
  - Değerler: "standard", "card_grid", "custom"
- [x] `menus` tablosuna `layout_settings` alanı ekleme (JSON, NULL olabilir)
  - Özel tasarım ayarlarını saklamak için

### Hizmetler Menüsü İçin Ek Tablolar
- [x] `menu_tags` tablosu oluşturma
  ```sql
  CREATE TABLE menu_tags (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    menu_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    color_class VARCHAR(50) DEFAULT 'blue',
    url VARCHAR(255),
    order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (menu_id) REFERENCES menus(id) ON DELETE CASCADE
  );
  ```
- [x] `menu_cards` tablosu oluşturma
  ```sql
  CREATE TABLE menu_cards (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
    menu_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    icon VARCHAR(100) NOT NULL,
    url VARCHAR(255) NOT NULL,
    data_category VARCHAR(100),
    color VARCHAR(50) DEFAULT '#007b32',
    order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (menu_id) REFERENCES menus(id) ON DELETE CASCADE
  );
  ```

## 2. Model Yapısı Güncellemeleri

### Model Eklemeleri
- [x] `MenuTag` modeli oluşturma
  - İlişkiler: menu (belongsTo)
  - Kitle atama alanları: name, color_class, url, order, is_active
- [x] `MenuCard` modeli oluşturma
  - İlişkiler: menu (belongsTo)
  - Kitle atama alanları: title, icon, url, data_category, color, order, is_active

### Mevcut Model Güncellemeleri
- [x] `Menu` (veya `HeaderMenuItem`) modelini güncelleme
  - Yeni alanlar: mega_menu_layout, layout_settings
  - İlişkiler: menuTags (hasMany), menuCards (hasMany)
- [x] Model özelleştirmeleri
  - Accessor/mutator metotları
  - layout_settings için JSON dönüşümleri

## 3. View Yapısı Güncellemeleri

### Partial View'lar Oluşturma
- [x] `partials/menus/standard_mega_menu.blade.php` oluşturma (kategorili mega menü yapısı)
  - Standart mega menü şablonu (Kurumsal, Duyurular, Nerede Ne Var menüleri için)
  - 3 seviyeli yapının (ana başlık, kategori, bağlantı) korunması
  - Material ikon entegrasyonu
  - Hover efektleri için CSS sınıfları
- [x] `partials/menus/card_grid_mega_menu.blade.php` oluşturma (Hizmetler menüsü yapısı)
  - Kart tabanlı grid sistemi (header.blade.php.bak'daki gibi)
  - Filtreleme için etiket sistemi
  - Her hizmet kartı için özel ikonlar ve renkler
- [x] `partials/menus/custom_mega_menu.blade.php` oluşturma (özelleştirilebilir yapı)
  - JSON yapısından menü render etme özelliği
  - Dinamik olarak oluşturulan HTML yapısı

### Ana Template Güncelleme
- [x] `dynamic_header.blade.php` dosyasını güncelleme
  - Ana navigasyon konteynerini header.blade.php.bak ile aynı sınıflarla düzenleme
  - Yeşil altçizgi animasyonu için CSS sınıflarını ekleme
  - Menü yapısını koşullu render etme:
  ```blade
  @if($menuItem->mega_menu_layout == 'standard' || !$menuItem->mega_menu_layout)
      @include('partials.menus.standard_mega_menu', ['menuItem' => $menuItem])
  @elseif($menuItem->mega_menu_layout == 'card_grid')
      @include('partials.menus.card_grid_mega_menu', ['menuItem' => $menuItem])
  @elseif($menuItem->mega_menu_layout == 'custom')
      @include('partials.menus.custom_mega_menu', ['menuItem' => $menuItem])
  @endif
  ```
- [x] Mobil menü yapısını güncelleme
  - İç içe geçmiş yapıyı koruma (ana menü, kategori, bağlantı)
  - Akordeon/genişleyen liste yapısını ekleme
  - Dokunmatik cihazlar için gerekli olay dinleyicilerini ekleme

## 4. Frontend Geliştirmeleri

### CSS Yapısı
- [x] `header_styles.css` dosyasını oluşturma/güncelleme
  - Header.blade.php.bak dosyasındaki tüm stil özelliklerini kapsama:
    - [x] Ana menü container yapısı (nav-desktop-menu sınıfı)
    - [x] Yeşil alt çizgi animasyonu (.underline-anim sınıfı)
    - [x] Mega menü konteynerlerinin boyutlandırma ve konumlandırması
    - [x] z-index ve shadow özellikleri
- [x] Mega menü tipleri için özel CSS
  - [x] Standart mega menü (3 sütunlu grid yapısı)
    - [x] Kategori başlıkları (bold, 16px)
    - [x] Bağlantı stilleri (hover efektleri)
    - [x] İkon konumlandırma (sol tarafta 24px)
  - [x] Kart grid mega menü (Hizmetler için)
    - [x] Kart tasarımı (border-radius, gölge efektleri)
    - [x] İkon stillendirme (yukarıda ortalanmış)
    - [x] Hover efektleri (kart büyütme, gölge derinleştirme)
    - [x] Filtre etiketleri (border-radius, renk varyasyonları)
  - [x] Mobil menü CSS
    - [x] Hamburger ikon animasyonu
    - [x] Overlay menü yapısı
    - [x] Akordeon menü stilleri

### JavaScript İşlevselliği
- [x] `menu_interactions.js` dosyasını oluşturma/güncelleme
  - [x] Mega menülerin açılması/kapanması için olay dinleyiciler
    - Masaüstünde hover ile açılma
    - Mobilde tıklama ile açılma
  - [x] Kart grid menüsü için filtreleme işlevselliği
    - Etiketlere tıklandığında ilgili kartları gösterme/gizleme
  - [x] Animasyon kontrolleri
    - Mega menü açılış/kapanış animasyonları
    - Alt çizgi animasyonu zamanlama kontrolü
  - [x] Responsive davranış yönetimi
    - Ekran boyutu değişimlerinde menü davranışını ayarlama
    - Dokunmatik cihazlarda uygun olay dinleyicileri kullanma

### Material Icons Entegrasyonu
- [x] Google Material Icons kütüphanesini dahil etme
  - CDN veya local dosya olarak
- [x] Tüm menü ikonlarını headermenuitems tablosundan dinamik çekme
- [x] Icon fallback mekanizması (belirtilen ikon bulunamazsa varsayılan kullanma)

### Mega Menü Tipleri İçin Özel HTML Yapısı
- [x] Standart Mega Menü Şablonu
  ```html
  <div class="mega-menu-container mm-standard">
    <div class="mm-grid-container">
      <div class="mm-category-column" ng-repeat="category in categories">
        <h4 class="mm-category-title">{{ kategori.name }}</h4>
        <ul class="mm-link-list">
          <li ng-repeat="link in category.links">
            <a href="{{ link.url }}">
              <i class="material-icons">{{ link.icon }}</i>
              <span>{{ link.title }}</span>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>
  ```
- [x] Hizmetler Kartlı Menü Şablonu
  ```html
  <div class="mega-menu-container mm-card-grid">
    <div class="mm-tags-container">
      <span class="mm-tag" data-category="all">Tümü</span>
      <!-- Dinamik etiketler -->
      <span class="mm-tag" ng-repeat="tag in tags" data-category="{{ tag.key }}">{{ tag.name }}</span>
    </div>
    <div class="mm-cards-container">
      <!-- Dinamik kartlar -->
      <a class="mm-card" ng-repeat="card in cards" href="{{ card.url }}" data-category="{{ card.category }}">
        <i class="material-icons" style="color: {{ card.color }}">{{ card.icon }}</i>
        <span class="mm-card-title">{{ card.title }}</span>
      </a>
    </div>
  </div>
  ```
- [x] Mobil Menü Şablonu
  ```html
  <div class="mobile-menu-container">
    <ul class="mobile-menu-list">
      <li class="mobile-menu-item" ng-repeat="item in mainMenuItems">
        <div class="mobile-menu-header" ng-click="toggleSubMenu(item)">
          {{ item.title }}
          <i class="material-icons">expand_more</i>
        </div>
        <div class="mobile-submenu" ng-if="item.subMenuItems.length">
          <!-- Alt menüler ve kategoriler -->
        </div>
      </li>
    </ul>
  </div>
  ```

### Animasyonlar ve Geçiş Efektleri
- [x] Hover animasyonları
  - [x] Ana menü öğeleri için yeşil alt çizgi animasyonu 
  - [x] Mega menü öğeleri için soldan sağa geçiş efekti
  - [x] Kartlar için büyütme ve gölge efektleri
- [x] Mega menü açılış/kapanış animasyonları
  - [x] Fade-in/out efekti
  - [x] Yukarıdan aşağı açılma animasyonu
- [x] Mobil menü animasyonları
  - [x] Hamburger ikon dönüşümü
  - [x] Yan kenardan açılma efekti
  - [x] Alt menü açılış akordeon efekti

### Responsive Tasarım
- [x] Mobil ve tablet görünümleri için medya sorguları
  ```css
  @media (max-width: 768px) {
    .desktop-menu { display: none; }
    .mobile-menu-toggle { display: block; }
  }
  ```
- [x] Progressive enhancement yaklaşımı
  - Temel işlevsellik tüm cihazlarda çalışacak
  - Gelişmiş özellikler (animasyonlar, geçişler) yalnızca destekleyen tarayıcılarda
- [x] Touch-friendly UI düzenlemeleri
  - Dokunma hedefleri minimum 44px x 44px
  - Swipe hareketleri için destek
  - Hover yerine tıklama olayları

## 5. Admin Panel Güncellemeleri

### Form Alanları Ekleme
- [x] Menü düzenleme formuna `mega_menu_layout` seçim alanı ekleme
- [x] Hizmetler (card_grid) menüsü için ek form bölümleri ekleme:
  - Etiket yönetimi
  - Kartlar yönetimi
  - Renk ve ikon seçimleri

### JavaScript İyileştirmeleri
- [x] Seçilen menü tipine göre form alanlarını dinamik gösterme/gizleme
- [x] Material Icons için entegre ikon seçici
- [x] Renk seçimleri için renk seçici

### Önizleme ve Test
- [ ] Menü düzenleme ekranına önizleme özelliği ekleme
- [ ] Kaydedilen menülerin canlı sitede test edilmesi

## 6. Controller ve Service Güncellemeleri

### HeaderController Güncellemeleri
- [x] Menü düzenleme/ekleme işlemlerinde yeni alanları yönetme
- [x] Ek tabloları ve ilişkileri yönetme (etiketler, kartlar)

### HeaderService Güncellemeleri
- [ ] `getMainMenuItems` metodunu güncelleme
  - Yeni ilişkileri ve yapıları yükleme
  - Menü özelliklerine göre veri hazırlama

## 7. Migration ve Seeder İşlemleri

### Migration Dosyaları
- [x] Yeni tabloları oluşturma migration'ları
- [x] Mevcut tabloları güncelleme migration'ları

### Seeder Güncellemeleri
- [ ] `HeaderMenuSeeder` güncelleme
  - Farklı menü tipleri için örnek veriler
- [ ] `MenuTagSeeder` ve `MenuCardSeeder` oluşturma
  - Hizmetler menüsü için örnek etiketler ve kartlar

## 8. Test ve Kalite Kontrol

### Testler
- [ ] Yeni menü yapısı birim testleri
- [ ] Tüm menü tipleri için entegrasyon testleri
- [ ] Tarayıcı uyumluluğu testleri

### Hata Yakalama ve İşleme
- [ ] Eksik veya hatalı ayar verileri için fallback mekanizmaları
- [ ] Hata log ve izleme sistemleri

## 9. Dokümantasyon ve Eğitim

### Doküman Oluşturma
- [ ] Admin kullanım kılavuzu
- [ ] Geliştirici dokümantasyonu
- [ ] Kod örnekleri ve açıklamaları

### Eğitim
- [ ] Yöneticiler için sistem kullanım eğitimi
- [ ] Geliştiriciler için teknik eğitim

## 10. Ek Özellikler (İsteğe Bağlı)

### Gelişmiş Özellikler
- [ ] Menü önbelleğe alma mekanizmaları
- [ ] Menü yapısını JSON olarak dışa/içe aktarma
- [ ] Menü versiyonlama sistemi
- [ ] A/B testi altyapısı

### Analitik ve İzleme
- [ ] Menü tıklama istatistikleri
- [ ] En çok kullanılan menü öğeleri
- [ ] Kullanıcı davranışı izleme

---

## Notlar
- Tasarım değişikliklerini minimumda tutmak, mevcut tasarımı korumak önemli
- Sayfa performansını korumak için JavaScript ve CSS optimizasyonları yapılmalı
- Responsive tasarımın tüm menü tipleri için çalıştığından emin olunmalı
- Header.blade.php.bak'taki şu özel tasarım öğelerine dikkat edilmeli:
  - Yeşil renk tonu (#007b32) tutarlı kullanılmalı
  - Material Icons font ailesi entegre edilmeli
  - Mega menü dropdown'ları için gölge ve border efektleri korunmalı
  - Tüm hover animasyonları aynı şekilde uygulanmalı
  - Mobil menü responsive breakpoint'leri düzgün ayarlanmalı (768px) 