# Pages Modülü Dönüşüm Planı

Bu doküman, News (haber) modülünün Pages (sayfalar) modülüne dönüştürülmesi için gerekli adımları içermektedir.

## 1. Model Oluşturma ve Güncelleme

- [x] `Page.php` modelini News.php modelindeki özelliklerle güncelle
  - [x] İlişkiler (kategoriler, etiketler) ekle
  - [x] Scope'lar ekle (yayınlanan, öne çıkan, popüler vb.)
  - [x] Yardımcı metodları ekle
- [x] `PageCategory.php` modelini oluştur (NewsCategory'den kopyala ve güncelle)
- [x] `PageTag.php` modelini oluştur (NewsTag'den kopyala ve güncelle)

## 2. Migration Oluşturma

- [x] Mevcut `pages` tablosunu güncellemek için migration oluştur
  - [x] Eksik alanları ekle (is_featured, featured_order, summary, gallery vb.)
- [x] `page_categories` tablosu için migration oluştur
- [x] `page_tags` tablosu için migration oluştur
- [x] `page_category` pivot tablosu için migration oluştur
- [x] `page_tag` pivot tablosu için migration oluştur

## 3. Repository ve Service Sınıfları

- [x] `PageRepository.php` sınıfını oluştur (NewsRepository'den kopyala)
  - [x] İlgili metotları güncelle
- [x] `PageService.php` sınıfını oluştur (NewsService'den kopyala)
  - [x] İlgili metotları güncelle

## 4. Controller Sınıfları

- [x] `PageController.php` controller'ını oluştur (NewsController'dan kopyala)
  - [x] İlgili metotları güncelle (index, create, store, show, edit, update, destroy)
  - [x] Özel metotları ekle/güncelle (toggleFeatured, updateFeaturedOrder vb.)
- [x] `PageCategoryController.php` controller'ını oluştur (NewsCategoryController'dan kopyala)
- [x] `PageTagController.php` controller'ını oluştur (NewsTagController'dan kopyala)

## 5. Form Request Sınıfları

- [x] `StorePageRequest.php` sınıfını oluştur
- [x] `UpdatePageRequest.php` sınıfını oluştur

## 6. View Dosyaları

### Admin Panel Viewları

- [x] `resources/views/admin/pages/index.blade.php`
- [x] `resources/views/admin/pages/create.blade.php`
- [x] `resources/views/admin/pages/edit.blade.php`
- [x] `resources/views/admin/pages/show.blade.php`
- [x] `resources/views/admin/page-categories/index.blade.php`
- [x] `resources/views/admin/page-categories/create.blade.php` (modal olabilir)
- [x] `resources/views/admin/page-categories/edit.blade.php` (modal olabilir)
- [x] `resources/views/admin/page-tags/index.blade.php`

### Frontend Viewları (Ziyaretçilerin Göreceği Kısım)

Frontend view'ları, mevcut site tasarımına ve akışına uygun olarak yapılmalıdır. Mevcut haber modülünün frontend tasarımına sadık kalarak oluşturulacaktır:

- [x] `resources/views/front/pages/index.blade.php` (Tüm sayfaların listelendiği ana sayfa)
  - [x] Header bölümü (mevcut tasarıma uygun başlık ve açıklama)
  - [x] Öne çıkan sayfalar için slider/banner bölümü
  - [x] Kategori filtreleme bölümü
  - [x] Sayfa listeleme grid yapısı (kart tasarımı)
  - [x] Her sayfa kartında:
    - [x] Sayfa görseli
    - [x] Başlık
    - [x] Kısa açıklama
    - [x] Kategori bilgisi
    - [x] Devamını oku butonu
  - [x] Sayfalama (pagination) bölümü
  - [x] Kenar çubuğu (sidebar) bileşenleri (kategoriler, popüler sayfalar vb.)

- [x] `resources/views/front/pages/show.blade.php` (Tek bir sayfanın detay görünümü)
  - [x] Header bölümü (sayfa başlığı, kategori bilgileri, tarih vb.)
  - [x] Kapak görseli
  - [x] İçerik bölümü (HTML formatlanmış içerik)
  - [x] Galeri bölümü (eğer varsa)
  - [x] Etiketler bölümü
  - [x] İlgili sayfalar bölümü
  - [x] Sayfa yorum bölümü (eğer eklenecekse)
  - [x] Sosyal medya paylaşım butonları
  - [x] Kenar çubuğu (sidebar) bileşenleri

- [x] `resources/views/front/pages/category.blade.php` (Kategori bazlı sayfa listeleme)
  - [x] Kategori header bölümü (kategori adı, açıklaması)
  - [x] Sayfa listeleme grid yapısı
  - [x] Sayfalama (pagination) bölümü
  - [x] Kenar çubuğu (sidebar) bileşenleri

- [x] Responsive tasarım: Tüm sayfaların mobil uyumlu olması
  - [x] Mobil görünüm için CSS medya sorguları
  - [x] Mobil menü ve navigasyon elementleri

## 7. Route Tanımlamaları

### Admin Panel Routeları

```php
// Sayfa Yönetimi
Route::resource('pages', App\Http\Controllers\Admin\PageController::class);
Route::post('/pages/{page}/toggle-featured', [App\Http\Controllers\Admin\PageController::class, 'toggleFeatured'])->name('pages.toggle-featured');
Route::post('/pages/{page}/toggle-status', [App\Http\Controllers\Admin\PageController::class, 'toggleStatus'])->name('pages.toggle-status');
Route::post('/pages/update-featured-order', [App\Http\Controllers\Admin\PageController::class, 'updateFeaturedOrder'])->name('pages.update-featured-order');
Route::post('/pages/upload-gallery-image', [App\Http\Controllers\Admin\PageController::class, 'uploadGalleryImage'])->name('pages.upload-gallery-image');

// Kategori Yönetimi
Route::resource('page-categories', PageCategoryController::class)->names('page-categories');
Route::post('/page-categories/update-order', [PageCategoryController::class, 'updateOrder'])->name('page-categories.update-order');

// Etiket Yönetimi
Route::resource('page-tags', PageTagController::class)->names('page-tags');
Route::get('/page-tags/cleanup', [PageTagController::class, 'cleanup'])->name('page-tags.cleanup');
Route::get('/page-tags/search', [PageTagController::class, 'search'])->name('page-tags.search');
```

### Frontend Routeları

```php
// Ön Yüz Sayfa Rotaları
Route::prefix('sayfalar')->name('pages.')->group(function () {
    Route::get('/', [App\Http\Controllers\Front\PageController::class, 'index'])->name('index');
    Route::get('/kategori/{slug}', [App\Http\Controllers\Front\PageController::class, 'category'])->name('category');
    Route::get('/{slug}', [App\Http\Controllers\Front\PageController::class, 'show'])->name('show');
});
```

## 8. Front Controller Oluşturma

- [x] `app/Http/Controllers/Front/PageController.php` dosyasını oluştur
  - [x] `index()` metodu: Tüm sayfaları listele
  - [x] `show()` metodu: Tek bir sayfanın detaylarını göster
  - [x] `category()` metodu: Kategori bazlı sayfaları listele

## 9. Frontend Tema Entegrasyonu

- [x] Ana şablon (`layout.blade.php`) ile entegrasyon
- [x] CSS/JS değişikliklerini mevcut temaya entegre etme
- [x] Mevcut header, footer ve navigasyon bileşenlerini kullanma
- [x] Renkler, fontlar ve stil kurallarını mevcut temadan miras alma

## 10. Provider Güncellemesi (İsteğe Bağlı)

- [x] AppServiceProvider.php dosyasında gerekli bağlamaları yap

## 11. Test ve Kontrol

- [x] Admin panel üzerinden sayfa oluşturmayı test et
- [x] Kategorilerle ilişkilendirmeyi test et
- [x] Etiketlerle ilişkilendirmeyi test et
- [x] Frontend görünümünü test et
  - [x] Ana sayfa (sayfalar listesi) görünümünü kontrol et
  - [x] Sayfa detay görünümünü kontrol et
  - [x] Kategori sayfası görünümünü kontrol et
  - [x] Responsive görünümü test et (mobil, tablet ve desktop)
  - [x] Tarayıcı uyumluluğunu test et (Chrome, Firefox, Safari vb.)

## Değişiklikler ve Önemli Noktalar

- News -> Page 
- news -> pages
- Haber -> Sayfa
- haberler -> sayfalar
- manşet -> öne çıkan (headline -> featured_page)

## Özel Durumlar

- Pages modelinde sadece is_published alanı var, ancak News modelindeki gibi status alanı da eklenebilir
- News modelindeki manşet (headline) kavramı, sayfa modülünde öne çıkan sayfa (featured) olarak düşünülebilir
- News modelindeki arşiv özelliği sayfalar için gerekli olmayabilir
- Pages modeline view_count gibi izlenme sayısı alanları eklenebilir

## Not

- Tüm dosyaları oluşturduktan sonra ilgili migration'ları çalıştırın
- Sayfa modülü ile haber modülü arasındaki yapısal farkları göz önünde bulundurarak gerekli ayarlamaları yapın
- NewsController ve PageController arasındaki işlevsel farklılıkları belirleyin ve uygun şekilde değiştirin
- Frontend görünüm için mevcut tema bileşenlerini ve site yapısını dikkate alın 