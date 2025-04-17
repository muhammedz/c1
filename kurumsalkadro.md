# Kurumsal Kadro Modülü Yol Haritası

## 1. Veritabanı Yapısı

### 1.1 Tablolar

#### 1.1.1 `corporate_categories` Tablosu (Kategoriler) ✅
- `id` - Otomatik artan birincil anahtar
- `name` - Kategori adı (örn: "Meclis Üyeleri")
- `slug` - URL'de kullanılacak slug (örn: "meclis-uyeleri")
- `description` - Kategori açıklaması (opsiyonel)
- `image` - Kategori resmi (opsiyonel)
- `status` - Durum (aktif/pasif)
- `order` - Sıralama
- `created_at` - Oluşturulma tarihi
- `updated_at` - Güncellenme tarihi

#### 1.1.2 `corporate_members` Tablosu (Üyeler) ✅
- `id` - Otomatik artan birincil anahtar
- `corporate_category_id` - Kategori ID (foreign key)
- `name` - Ad Soyad
- `slug` - URL'de kullanılacak slug (örn: "ayse-kaya")
- `title` - Unvan
- `image` - Profil fotoğrafı
- `short_description` - Kısa açıklama
- `biography` - Detaylı biyografi (TinyMCE ile düzenlenmiş)
- `facebook` - Facebook linki (opsiyonel)
- `twitter` - Twitter/X linki (opsiyonel)
- `instagram` - Instagram linki (opsiyonel)
- `linkedin` - LinkedIn linki (opsiyonel)
- `status` - Durum (aktif/pasif)
- `order` - Sıralama
- `created_at` - Oluşturulma tarihi
- `updated_at` - Güncellenme tarihi

### 1.2 Migration Dosyaları ✅
- `create_corporate_categories_table.php` ✅
- `create_corporate_members_table.php` ✅

## 2. Model Sınıfları ✅

### 2.1 `CorporateCategory` Model ✅
- İlişkiler:
  - `members()` - Kategoriye ait üyeleri getiren ilişki (hasMany) ✅
- Özellikler:
  - Slug otomatik oluşturma ✅
  - Aktif kayıtları getiren scope ✅
  - Sıralı kayıtları getiren scope ✅

### 2.2 `CorporateMember` Model ✅
- İlişkiler:
  - `category()` - Üyenin ait olduğu kategoriyi getiren ilişki (belongsTo) ✅
- Özellikler:
  - Slug otomatik oluşturma ✅
  - Aktif kayıtları getiren scope ✅
  - Sıralı kayıtları getiren scope ✅
  - Medya/dosya yükleme özellikleri ✅

## 3. Controller Sınıfları ✅

### 3.1 Admin Tarafı

#### 3.1.1 `CorporateCategoryController` ✅
- `index()` - Kategori listesini görüntüleme ✅
- `create()` - Yeni kategori oluşturma formunu gösterme ✅
- `store()` - Yeni kategori kaydetme ✅
- `edit()` - Kategori düzenleme formunu gösterme ✅
- `update()` - Kategori güncelleme ✅
- `destroy()` - Kategori silme ✅

#### 3.1.2 `CorporateMemberController` ✅
- `index($category_id)` - Belirli bir kategoriye ait üyeleri listeleme ✅
- `create($category_id)` - Yeni üye oluşturma formunu gösterme ✅
- `store()` - Yeni üye kaydetme ✅
- `edit($id)` - Üye düzenleme formunu gösterme ✅
- `update($id)` - Üye güncelleme ✅
- `destroy($id)` - Üye silme ✅
- `order()` - Üye sıralamasını güncelleme (AJAX) ✅

### 3.2 Frontend Tarafı

#### 3.2.1 `CorporateController` ✅
- `index()` - Tüm kategorileri listeleme ✅
- `showCategory($slug)` - Kategori detayını ve üyelerini gösterme ✅
- `showMember($categorySlug, $memberSlug)` - Üye detayını gösterme ✅

## 4. View Dosyaları ✅

### 4.1 Admin Panel View'leri ✅

#### 4.1.1 Kategori View'leri ✅
- `resources/views/admin/corporate/categories/index.blade.php` ✅
- `resources/views/admin/corporate/categories/create.blade.php` ✅
- `resources/views/admin/corporate/categories/edit.blade.php` ✅

#### 4.1.2 Üye View'leri ✅
- `resources/views/admin/corporate/members/index.blade.php` ✅
- `resources/views/admin/corporate/members/create.blade.php` ✅
- `resources/views/admin/corporate/members/edit.blade.php` ✅

### 4.2 Frontend View'leri ✅
- `resources/views/frontend/corporate/index.blade.php` - Tüm kategorileri listeler ✅
- `resources/views/frontend/corporate/category.blade.php` - Kategori detayı ve üyeleri ✅
- `resources/views/frontend/corporate/member.blade.php` - Üye detayı ✅

## 5. Route Tanımlamaları ✅

### 5.1 Admin Routes ✅
```php
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    // Kategori Yönetimi
    Route::resource('corporate/categories', CorporateCategoryController::class);
    
    // Üye Yönetimi
    Route::get('corporate/categories/{category}/members', [CorporateMemberController::class, 'index'])->name('corporate.members.index');
    Route::get('corporate/categories/{category}/members/create', [CorporateMemberController::class, 'create'])->name('corporate.members.create');
    Route::post('corporate/categories/{category}/members', [CorporateMemberController::class, 'store'])->name('corporate.members.store');
    Route::get('corporate/members/{member}/edit', [CorporateMemberController::class, 'edit'])->name('corporate.members.edit');
    Route::put('corporate/members/{member}', [CorporateMemberController::class, 'update'])->name('corporate.members.update');
    Route::delete('corporate/members/{member}', [CorporateMemberController::class, 'destroy'])->name('corporate.members.destroy');
    Route::post('corporate/members/order', [CorporateMemberController::class, 'order'])->name('corporate.members.order');
});
```

### 5.2 Frontend Routes ✅
```php
// Kurumsal Kadro
Route::get('/kurumsal-kadro', [CorporateController::class, 'index'])->name('corporate.index');
Route::get('/{categorySlug}', [CorporateController::class, 'showCategory'])->name('corporate.category');
Route::get('/{categorySlug}/{memberSlug}', [CorporateController::class, 'showMember'])->name('corporate.member');
```

## 6. AdminLTE Entegrasyonu ✅

### 6.1 Sol Menüye Ekleme ✅
AdminLTE sidebar menüsüne "Kurumsal Kadro" başlığı eklenecek ve altına kategori listesi dinamik olarak getirilecek.

### 6.2 JavaScript ve CSS Entegrasyonu ✅
- Drag-drop sıralama için Sortable.js ✅
- TinyMCE editor entegrasyonu ✅
- Resim yükleme ve önizleme özellikleri ✅

## 7. Geliştirme Adımları

1. Migration dosyalarını oluştur ve çalıştır ✅
2. Model sınıflarını oluştur ✅
3. Controller sınıflarını oluştur ✅
4. Admin panel view'lerini oluştur ✅
5. Admin route'larını tanımla ✅
6. AdminLTE menü entegrasyonunu yap ✅
7. Frontend controller'ı oluştur ✅
8. Frontend view'lerini oluştur ✅
9. Frontend route'larını tanımla ✅
10. Test ve hata ayıklama

## 8. Ek Özellikler (İleride Eklenebilir)

1. Kategori ve üye için SEO meta alanları
2. Üye sayfası için breadcrumb yapısı
3. Üyeler için gelişmiş arama ve filtreleme
4. Kategori ve üye sayfaları için cache mekanizması 