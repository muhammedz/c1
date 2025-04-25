# MENÜ MODÜLÜ PLANI

## 1. VERİTABANI YAPISI ✅

### Menü Tablosu (`menusystem`) ✅
- `id` - Primary Key
- `name` - Menü adı
- `url` - Menü bağlantısı (opsiyonel)
- `type` - Menü tipi (1: Küçük Menü, 2: Büyük Menü)
- `order` - Sıralama
- `status` - Durum (aktif/pasif)
- `created_at` - Oluşturulma tarihi
- `updated_at` - Güncelleme tarihi

### Alt Başlık Tablosu (`menu_categories`) ✅
- `id` - Primary Key
- `menusystem_id` - Foreign Key (menusystem tablosuna)
- `name` - Alt başlık adı
- `url` - Alt başlık bağlantısı (opsiyonel)
- `order` - Sıralama
- `status` - Durum (aktif/pasif)
- `created_at` - Oluşturulma tarihi
- `updated_at` - Güncelleme tarihi

### Alt Menü Tablosu (`menu_items`) ✅
- `id` - Primary Key
- `menu_category_id` - Foreign Key (menu_categories tablosuna)
- `name` - Alt menü adı
- `url` - Alt menü bağlantısı
- `icon` - Material Icons için ikon adı
- `order` - Sıralama
- `status` - Durum (aktif/pasif)
- `created_at` - Oluşturulma tarihi
- `updated_at` - Güncelleme tarihi

### Açıklama Tablosu (`menu_descriptions`) ✅
- `id` - Primary Key
- `menusystem_id` - Foreign Key (menusystem tablosuna)
- `description` - Açıklama metni
- `link_text` - Açıklama link metni
- `link_url` - Açıklama link URL'si
- `created_at` - Oluşturulma tarihi
- `updated_at` - Güncelleme tarihi

## 2. MODEL İLİŞKİLERİ ✅

### MenuSystem Modeli ✅
```php
class MenuSystem extends Model
{
    protected $table = 'menusystem';
    
    // Bir menünün birçok alt başlığı olabilir
    public function categories()
    {
        return $this->hasMany(MenuCategory::class, 'menusystem_id')->orderBy('order');
    }
    
    // Bir menünün bir açıklaması olabilir
    public function description()
    {
        return $this->hasOne(MenuDescription::class, 'menusystem_id');
    }
}
```

### MenuCategory Modeli ✅
```php
class MenuCategory extends Model
{
    // Bir alt başlık bir menüye aittir
    public function menusystem()
    {
        return $this->belongsTo(MenuSystem::class, 'menusystem_id');
    }
    
    // Bir alt başlığın birçok alt menüsü olabilir
    public function items()
    {
        return $this->hasMany(MenuItem::class)->orderBy('order');
    }
}
```

### MenuItem Modeli ✅
```php
class MenuItem extends Model
{
    // Bir alt menü bir alt başlığa aittir
    public function category()
    {
        return $this->belongsTo(MenuCategory::class, 'menu_category_id');
    }
}
```

### MenuDescription Modeli ✅
```php
class MenuDescription extends Model
{
    // Bir açıklama bir menüye aittir
    public function menusystem()
    {
        return $this->belongsTo(MenuSystem::class, 'menusystem_id');
    }
}
```

## 3. CONTROLLER YAPISI ✅

### MenuSystemController ✅
- `index()` - Tüm menüleri listele
- `create()` - Yeni menü oluşturma formu
- `store()` - Yeni menüyü kaydet
- `edit()` - Menü düzenleme formu
- `update()` - Menü bilgilerini güncelle
- `destroy()` - Menüyü sil
- `updateOrder()` - Menü sıralamasını güncelle
- `getMenuTypeForm()` - AJAX ile menü tipine göre form getir

### MenuCategoryController ✅
- `index()` - Belirli bir menüye ait alt başlıkları listele
- `create()` - Yeni alt başlık oluşturma formu
- `store()` - Yeni alt başlığı kaydet
- `edit()` - Alt başlık düzenleme formu
- `update()` - Alt başlık bilgilerini güncelle
- `destroy()` - Alt başlığı sil
- `updateOrder()` - Alt başlık sıralamasını güncelle

### MenuItemController ✅
- `index()` - Belirli bir alt başlığa ait alt menüleri listele
- `create()` - Yeni alt menü oluşturma formu
- `store()` - Yeni alt menüyü kaydet
- `edit()` - Alt menü düzenleme formu
- `update()` - Alt menü bilgilerini güncelle
- `destroy()` - Alt menüyü sil
- `updateOrder()` - Alt menü sıralamasını güncelle
- `getIcons()` - Material Icons listesini getir

## 4. ROUTE TANIMLARI ✅

```php
// Admin routes
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    // MenuSystem route group
    Route::prefix('menusystem')->name('menusystem.')->group(function () {
        // Ana menü routes
        Route::get('/', [MenuSystemController::class, 'index'])->name('index');
        Route::get('/create', [MenuSystemController::class, 'create'])->name('create');
        Route::post('/', [MenuSystemController::class, 'store'])->name('store');
        Route::get('/{menusystem}/edit', [MenuSystemController::class, 'edit'])->name('edit');
        Route::put('/{menusystem}', [MenuSystemController::class, 'update'])->name('update');
        Route::delete('/{menusystem}', [MenuSystemController::class, 'destroy'])->name('destroy');
        Route::post('/order', [MenuSystemController::class, 'updateOrder'])->name('order');
        Route::get('/type-form', [MenuSystemController::class, 'getMenuTypeForm'])->name('type-form');
        
        // Menu Category routes (menusystem altında)
        Route::get('/menu-categories', [MenuCategoryController::class, 'index'])->name('categories.index');
        Route::get('/menu-categories/create', [MenuCategoryController::class, 'create'])->name('categories.create');
        Route::post('/menu-categories', [MenuCategoryController::class, 'store'])->name('categories.store');
        Route::get('/menu-categories/{category}/edit', [MenuCategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/menu-categories/{category}', [MenuCategoryController::class, 'update'])->name('categories.update');
        Route::delete('/menu-categories/{category}', [MenuCategoryController::class, 'destroy'])->name('categories.destroy');
        Route::post('/menu-categories/order', [MenuCategoryController::class, 'updateOrder'])->name('categories.order');
        
        // Menu Item routes (menusystem altında)
        Route::get('/menu-items', [MenuItemController::class, 'index'])->name('items.index');
        Route::get('/menu-items/create', [MenuItemController::class, 'create'])->name('items.create');
        Route::post('/menu-items', [MenuItemController::class, 'store'])->name('items.store');
        Route::get('/menu-items/{item}/edit', [MenuItemController::class, 'edit'])->name('items.edit');
        Route::put('/menu-items/{item}', [MenuItemController::class, 'update'])->name('items.update');
        Route::delete('/menu-items/{item}', [MenuItemController::class, 'destroy'])->name('items.destroy');
        Route::post('/menu-items/order', [MenuItemController::class, 'updateOrder'])->name('items.order');
        Route::get('/menu-items/icons', [MenuItemController::class, 'getIcons'])->name('items.icons');
    });
});
```

## 5. VIEW DOSYALARI ❌

### Admin View Dosyaları

#### Menü Listesi
- `resources/views/admin/menusystem/index.blade.php`

#### Menü Oluşturma
- `resources/views/admin/menusystem/create.blade.php`
- `resources/views/admin/menusystem/partials/small_menu_form.blade.php`
- `resources/views/admin/menusystem/partials/big_menu_form.blade.php`

#### Menü Düzenleme
- `resources/views/admin/menusystem/edit.blade.php`

#### Alt Başlık Yönetimi
- `resources/views/admin/menusystem/menu-categories/index.blade.php`
- `resources/views/admin/menusystem/menu-categories/create.blade.php`
- `resources/views/admin/menusystem/menu-categories/edit.blade.php`

#### Alt Menü Yönetimi
- `resources/views/admin/menusystem/menu-items/index.blade.php`
- `resources/views/admin/menusystem/menu-items/create.blade.php`
- `resources/views/admin/menusystem/menu-items/edit.blade.php`
- `resources/views/admin/menusystem/menu-items/partials/icon_selector.blade.php`

### Frontend (Public) View Dosyaları
- Mevcut `resources/views/partials/header.blade.php` dosyasını kullanacak
- `resources/views/partials/menusystem/small_menu.blade.php`
- `resources/views/partials/menusystem/big_menu.blade.php`
- `resources/views/partials/menusystem/mobile_menu.blade.php`

## 6. JS FONKSİYONLARI ❌

### Admin Panel JS Dosyaları
```javascript
// resources/js/admin/menusystem.js

// Menü tipi seçildiğinde ilgili formu yükle
function loadMenuTypeForm() {
    const menuType = document.querySelector('input[name="type"]:checked').value;
    
    fetch(`/admin/menusystem/type-form?type=${menuType}`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('menu-type-form').innerHTML = html;
        });
}

// Alt başlık sıralama işlemleri için Sortable.js
function initSortableCategories() {
    new Sortable(document.getElementById('categories-list'), {
        handle: '.handle',
        animation: 150,
        onEnd: function(evt) {
            updateCategoriesOrder();
        }
    });
}

// Alt başlıkların sıralamasını güncelleme
function updateCategoriesOrder() {
    const categories = document.querySelectorAll('#categories-list .category-item');
    const orderData = [];
    
    categories.forEach((category, index) => {
        orderData.push({
            id: category.dataset.id,
            order: index
        });
    });
    
    fetch('/admin/menusystem/menu-categories/order', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ categories: orderData })
    });
}

// Alt menü sıralama işlemleri için Sortable.js
function initSortableItems() {
    new Sortable(document.getElementById('items-list'), {
        handle: '.handle',
        animation: 150,
        onEnd: function(evt) {
            updateItemsOrder();
        }
    });
}

// Alt menülerin sıralamasını güncelleme
function updateItemsOrder() {
    const items = document.querySelectorAll('#items-list .item-row');
    const orderData = [];
    
    items.forEach((item, index) => {
        orderData.push({
            id: item.dataset.id,
            order: index
        });
    });
    
    fetch('/admin/menusystem/menu-items/order', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ items: orderData })
    });
}

// Material Icons seçici
function initIconSelector() {
    const iconSearch = document.getElementById('icon-search');
    const iconsList = document.getElementById('icons-list');
    
    // İkon arama işlemi
    iconSearch.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const icons = iconsList.querySelectorAll('.icon-item');
        
        icons.forEach(icon => {
            const iconName = icon.dataset.name.toLowerCase();
            if (iconName.includes(searchTerm)) {
                icon.style.display = 'flex';
            } else {
                icon.style.display = 'none';
            }
        });
    });
    
    // İkon seçme işlemi
    iconsList.addEventListener('click', function(e) {
        if (e.target.closest('.icon-item')) {
            const selectedIcon = e.target.closest('.icon-item');
            const iconName = selectedIcon.dataset.name;
            
            document.getElementById('selected-icon').innerHTML = `
                <span class="material-icons">${iconName}</span>
                <span class="ml-2">${iconName}</span>
            `;
            document.getElementById('icon').value = iconName;
            
            // Tüm seçili ikonları temizle
            iconsList.querySelectorAll('.icon-item').forEach(i => {
                i.classList.remove('selected');
            });
            
            // Seçilen ikonu işaretle
            selectedIcon.classList.add('selected');
        }
    });
    
    // Material icons listesini alma
    fetch('/admin/menusystem/menu-items/icons')
        .then(response => response.json())
        .then(icons => {
            renderIconList(icons);
        });
}

// Yeni alt başlık ekleme işlemi
function addNewCategory() {
    const categoriesContainer = document.getElementById('categories-container');
    const categoryTemplate = document.getElementById('category-template');
    const newCategoryId = Date.now(); // Benzersiz geçici ID
    
    // Template kopyalanıp yeni ID atanır
    const newCategory = categoryTemplate.content.cloneNode(true);
    newCategory.querySelector('.category-item').dataset.id = newCategoryId;
    
    // Yeni kategori forma eklenir
    categoriesContainer.appendChild(newCategory);
    
    // Event listener'lar yeniden bağlanır
    initCategoryEvents();
}

// Yeni alt menü ekleme işlemi
function addNewItem(categoryId) {
    const itemsContainer = document.querySelector(`.category-items[data-category="${categoryId}"]`);
    const itemTemplate = document.getElementById('item-template');
    const newItemId = Date.now(); // Benzersiz geçici ID
    
    // Template kopyalanıp yeni ID atanır
    const newItem = itemTemplate.content.cloneNode(true);
    newItem.querySelector('.item-row').dataset.id = newItemId;
    
    // Yeni item forma eklenir
    itemsContainer.appendChild(newItem);
    
    // İkon seçici başlatılır
    initIconSelector();
}

// Dinamik form olaylarını bağla
document.addEventListener('DOMContentLoaded', function() {
    // Menü tipi değiştiğinde form güncellenir
    document.querySelectorAll('input[name="type"]').forEach(radio => {
        radio.addEventListener('change', loadMenuTypeForm);
    });
    
    // İlk yükleme
    if (document.querySelector('input[name="type"]:checked')) {
        loadMenuTypeForm();
    }
});
```

### Frontend JS Dosyaları
```javascript
// resources/js/menu.js

// Desktop megamenü hover işlemleri
function initDesktopMenus() {
    const menuGroups = document.querySelectorAll('.group');
    
    menuGroups.forEach(group => {
        group.addEventListener('mouseenter', function() {
            this.querySelector('.mega-menu')?.classList.add('block');
            this.querySelector('.mega-menu')?.classList.remove('hidden');
        });
        
        group.addEventListener('mouseleave', function() {
            this.querySelector('.mega-menu')?.classList.add('hidden');
            this.querySelector('.mega-menu')?.classList.remove('block');
        });
    });
}

// Mobil menü toggle işlemleri
function initMobileMenu() {
    // Bu fonksiyonlar header.blade.php içindeki mevcut JS ile aynı
    document.getElementById('mobileMenuButton')?.addEventListener('click', function() {
        const mobileMenu = document.getElementById('mobileMenu');
        mobileMenu.classList.toggle('hidden');
    });
    
    document.querySelectorAll('.mobile-menu-toggle').forEach(function(toggle) {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            const submenu = this.closest('.group').querySelector('.mobile-submenu');
            submenu.classList.toggle('hidden');
            this.innerHTML = submenu.classList.contains('hidden') ? 'expand_more' : 'expand_less';
        });
    });
    
    document.querySelectorAll('.mobile-category-toggle').forEach(function(toggle) {
        toggle.addEventListener('click', function() {
            const items = this.closest('div').querySelector('.mobile-category-items');
            items.classList.toggle('hidden');
            const icon = this.querySelector('.material-icons');
            icon.innerHTML = items.classList.contains('hidden') ? 'expand_more' : 'expand_less';
        });
    });
}

document.addEventListener('DOMContentLoaded', function() {
    initDesktopMenus();
    initMobileMenu();
});
```

## 7. UYGULAMA AKIŞI ❌

### Menü Oluşturma Akışı

1. Admin, "Menü Oluştur" sayfasına gider (`admin/menusystem/create`)
2. Form içinde:
   - Menü adı girer
   - İsteğe bağlı bağlantı (URL) ekler
   - Menü tipini seçer (Küçük / Büyük)

3. **Küçük Menü seçilirse:**
   - Menü adı ve URL ile basit kayıt işlemi yapılır
   - İşlem tamamlanır

4. **Büyük Menü seçilirse:**
   - Form Ajax ile genişler ve alt başlıklar için ek alanlar eklenir
   - "Alt Başlık Ekle" butonu ile her biri için:
     - Alt başlık adı
     - İsteğe bağlı alt başlık linki
   - Her alt başlık için "Alt Menü Ekle" butonu ile:
     - Alt menü adı
     - Alt menü linki
     - Material icon seçimi
   - Alt bölümde:
     - Açıklama metni (opsiyonel)
     - Açıklama linki metni (opsiyonel)
     - Açıklama linki URL'si (opsiyonel)

5. Form gönderilebilir:
   - Tüm veriler POST edilerek kaydedilir (`admin/menusystem`)
   - İlişkili tablolara veri yazılır

### Menü Düzenleme Akışı

1. Admin, "Menüler" sayfasından ilgili menüyü seçer (`admin/menusystem`)
2. Düzenle butonuna tıklanır (`admin/menusystem/{id}/edit`)
3. Mevcut bilgiler formda yüklenir
4. Menü tipine göre uygun form görüntülenir
5. Alt başlıklar ve alt menüler listelenir
6. Gerekli değişiklikler yapılır
7. Form güncellendiğinde veriler kaydedilir (`admin/menusystem/{id}`)

### Alt Başlık Yönetimi

1. Belirli bir menüye ait alt başlıklar görüntülenir (`admin/menusystem/menu-categories?menusystem_id={id}`)
2. Yeni alt başlık eklenebilir (`admin/menusystem/menu-categories/create?menusystem_id={id}`)
3. Alt başlıklar düzenlenebilir (`admin/menusystem/menu-categories/{id}/edit`)
4. Alt başlıklar silinebilir (`admin/menusystem/menu-categories/{id}`)
5. Alt başlıklar sürükle-bırak ile sıralanabilir

### Alt Menü Yönetimi

1. Belirli bir alt başlığa ait alt menüler görüntülenir (`admin/menusystem/menu-items?category_id={id}`)
2. Yeni alt menü eklenebilir (`admin/menusystem/menu-items/create?category_id={id}`)
3. Alt menüler düzenlenebilir (`admin/menusystem/menu-items/{id}/edit`)
4. Alt menüler silinebilir (`admin/menusystem/menu-items/{id}`)
5. Alt menüler sürükle-bırak ile sıralanabilir

### Görüntüleme Akışı (Frontend)

1. Sayfa yüklenirken, aktif menüler veritabanından çekilir
2. Menü tiplerine göre farklı blade dosyaları yüklenir:
   - Küçük menüler: `small_menu.blade.php`
   - Büyük menüler: `big_menu.blade.php`
3. Mobil görünüm için ayrıca `mobile_menu.blade.php` kullanılır
4. CSS sınıfları ve JS fonksiyonları, mevcut `header.blade.php` ile uyumlu çalışır

## 8. ADMİN PANEL ENTEGRASYONU ❌

### Admin Menü Yapısı
```
Admin Panel
├── Menü Sistemi
│   ├── Tüm Menüler (/admin/menusystem)
│   └── Yeni Menü Ekle (/admin/menusystem/create)
└── [Diğer Admin Menüleri]
```

### Menü Listesi Sayfası
- Tüm menüleri tablo şeklinde gösterir
- Sıralama, düzenleme ve silme işlemleri
- Menü tipi bilgisi (Küçük/Büyük)
- Durum (Aktif/Pasif) toggle butonu

### Yeni Menü Ekleme / Düzenleme Sayfası
- Dinamik form yapısı
- Menü tipine göre farklı içerik
- Alt başlık ve alt menüler için nested (iç içe) formlar
- Sürükle-bırak ile sıralama
- Ikon seçici bileşeni

## 9. MENÜ TİPLERİNE GÖRE İŞLEVLER ❌

### Küçük Menü
- Basit bir menü bağlantısı
- Sadece header'da yer alır
- Dropdown özelliği yok
- Basit bir tıklamayla URL'ye yönlendirir

### Büyük Menü (Mega Menü)
- Hover olduğunda açılır panel
- Birden fazla alt başlıklar içerir
- Her alt başlık altında ikonlu alt menüler
- Alt bölümde açıklama ve bağlantı alanı
- Material-icons kütüphanesi kullanılır
- `mega-menu-content` ve `mega-menu-category` sınıflarından faydalanır

## 10. MOBİL UYUMLULUK ❌

### Mobil Menü Yapısı
- Bootstrap/Tailwind breakpoint'lerine göre değişim
- md ekran boyutundan küçük cihazlarda hamburger menü
- Menü öğeleri accordion şeklinde açılır-kapanır
- Mega menü içerikleri hiyerarşik liste olarak görüntülenir
- Material-icons kullanılır

### Mobil Menü JS
- Tıklama yerine dokunmatik etkileşim
- Hamburger icon toggle işlevi
- Alt menüleri açıp kapama işlevleri
- CSS transition efektleri

## 11. MİGRATİON DOSYALARI ✅

```php
// Menus Migration
Schema::create('menusystem', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('url')->nullable();
    $table->tinyInteger('type')->default(1); // 1: Küçük Menü, 2: Büyük Menü
    $table->integer('order')->default(0);
    $table->boolean('status')->default(true);
    $table->timestamps();
});

// Menu Categories Migration
Schema::create('menu_categories', function (Blueprint $table) {
    $table->id();
    $table->foreignId('menusystem_id')->constrained('menusystem')->onDelete('cascade');
    $table->string('name');
    $table->string('url')->nullable();
    $table->integer('order')->default(0);
    $table->boolean('status')->default(true);
    $table->timestamps();
});

// Menu Items Migration (Tablo zaten mevcut)
Schema::create('menu_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('menu_category_id')->constrained()->onDelete('cascade');
    $table->string('name');
    $table->string('url');
    $table->string('icon')->default('link');
    $table->integer('order')->default(0);
    $table->boolean('status')->default(true);
    $table->timestamps();
});

// Menu Descriptions Migration
Schema::create('menu_descriptions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('menusystem_id')->constrained('menusystem')->onDelete('cascade');
    $table->string('description')->nullable();
    $table->string('link_text')->nullable();
    $table->string('link_url')->nullable();
    $table->timestamps();
});
```

## 12. UYGULAMA GÜVENLİĞİ ❌

### Form Validasyonu
```php
// MenuRequest
public function rules()
{
    return [
        'name' => 'required|string|max:255',
        'url' => 'nullable|string|max:255',
        'type' => 'required|in:1,2',
        'status' => 'boolean',
        
        // Büyük menü ise kategori ve öğeler için validasyon
        'categories.*.name' => 'required_if:type,2|string|max:255',
        'categories.*.url' => 'nullable|string|max:255',
        
        'items.*.name' => 'required|string|max:255',
        'items.*.url' => 'required|string|max:255',
        'items.*.icon' => 'required|string|max:50',
        
        'description' => 'nullable|string|max:500',
        'link_text' => 'nullable|string|max:100',
        'link_url' => 'nullable|string|max:255',
    ];
}
```

### Güvenlik Önlemleri
- CSRF koruması
- Yetkilendirme (middleware)
- Form validasyonu
- SQL injection koruması (Laravel ORM)
- XSS koruması (e, {{}} vs)

## 13. TEST PLANI ❌

### Unit Testler
- Model ilişkilerinin doğru çalıştığını test et
- Menü tiplerine göre doğru form elemanlarının görüntülendiğini test et

### Feature Testler
- Menü oluşturma işleminin başarılı olduğunu test et
- Alt başlık ve alt menü eklemenin çalıştığını test et
- Sıralama özelliğinin düzgün çalıştığını test et
- Silme işleminin ilişkili verileri de sildiğini test et

### Browser Testler
- Menünün desktop görünümünü test et
- Menünün mobil görünümünü test et
- Hover ve tıklama etkileşimlerini test et

## 14. KURULUM ✅

1. Migration dosyalarını oluştur ✅
```bash
php artisan make:migration create_menusystem_table
php artisan make:migration create_menu_categories_table
php artisan make:migration create_menu_items_table
php artisan make:migration create_menu_descriptions_table
```

2. Model dosyalarını oluştur ✅
```bash
php artisan make:model MenuSystem
php artisan make:model MenuCategory
php artisan make:model MenuItem
php artisan make:model MenuDescription
```

3. Controller dosyalarını oluştur ✅
```bash
php artisan make:controller Admin/MenuSystemController --resource
php artisan make:controller Admin/MenuCategoryController
php artisan make:controller Admin/MenuItemController
```

4. Route tanımlarını oluştur ✅
```php
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::prefix('menusystem')->name('menusystem.')->group(function () {
        // Ana menü routes
        Route::get('/', [MenuSystemController::class, 'index'])->name('index');
        Route::get('/create', [MenuSystemController::class, 'create'])->name('create');
        Route::post('/', [MenuSystemController::class, 'store'])->name('store');
        Route::get('/{menusystem}/edit', [MenuSystemController::class, 'edit'])->name('edit');
        Route::put('/{menusystem}', [MenuSystemController::class, 'update'])->name('update');
        Route::delete('/{menusystem}', [MenuSystemController::class, 'destroy'])->name('destroy');
        Route::post('/order', [MenuSystemController::class, 'updateOrder'])->name('order');
        Route::get('/type-form', [MenuSystemController::class, 'getMenuTypeForm'])->name('type-form');
        
        // Menu Category routes
        Route::get('/menu-categories', [MenuCategoryController::class, 'index'])->name('categories.index');
        Route::get('/menu-categories/create', [MenuCategoryController::class, 'create'])->name('categories.create');
        Route::post('/menu-categories', [MenuCategoryController::class, 'store'])->name('categories.store');
        Route::get('/menu-categories/{category}/edit', [MenuCategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/menu-categories/{category}', [MenuCategoryController::class, 'update'])->name('categories.update');
        Route::delete('/menu-categories/{category}', [MenuCategoryController::class, 'destroy'])->name('categories.destroy');
        Route::post('/menu-categories/order', [MenuCategoryController::class, 'updateOrder'])->name('categories.order');
        
        // Menu Item routes
        Route::get('/menu-items', [MenuItemController::class, 'index'])->name('items.index');
        Route::get('/menu-items/create', [MenuItemController::class, 'create'])->name('items.create');
        Route::post('/menu-items', [MenuItemController::class, 'store'])->name('items.store');
        Route::get('/menu-items/{item}/edit', [MenuItemController::class, 'edit'])->name('items.edit');
        Route::put('/menu-items/{item}', [MenuItemController::class, 'update'])->name('items.update');
        Route::delete('/menu-items/{item}', [MenuItemController::class, 'destroy'])->name('items.destroy');
        Route::post('/menu-items/order', [MenuItemController::class, 'updateOrder'])->name('items.order');
        Route::get('/menu-items/icons', [MenuItemController::class, 'getIcons'])->name('items.icons');
    });
});
```

5. View dosyalarını oluştur ❌:
   - `resources/views/admin/menusystem/*.blade.php`
   - `resources/views/admin/menusystem/menu-categories/*.blade.php`
   - `resources/views/admin/menusystem/menu-items/*.blade.php`

6. JS dosyalarını oluştur ❌:
   - `resources/js/admin/menusystem.js`

7. Migrationları çalıştır ✅
```bash
php artisan migrate
```

8. Admin paneline menü bileşenini ekle ❌:
   - Sidebar ya da header'a "Menü Sistemi" linkini ekle (`admin/menusystem`)

9. Frontend header.blade.php dosyasına menü bileşenini ekle ❌
10. Test et ve gerekirse hataları düzelt ❌ 