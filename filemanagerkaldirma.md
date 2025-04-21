# Laravel File Manager Kaldırma Planı

## UYARI: ÖNEMLİ NOT
- Bu plan SADECE *unisharp/laravel-filemanager* paketini ve ilişkili bileşenlerini kaldırmayı amaçlar.
- http://localhost:8000/admin/filemanagersystem ile erişilen FileManagerSystem modülüne ve ilgili bileşenlerine KESİNLİKLE dokunulmayacaktır.
- İki farklı dosya yönetim sistemi mevcuttur ve sadece unisharp/laravel-filemanager paketi kaldırılacaktır.

## Tespit Edilen Bileşenler

### 1. Paketler ve Bağımlılıklar
- [x] unisharp/laravel-filemanager (composer.json içinde)

### 2. Kontrolcüler
- [x] app/Http/Controllers/FileManagerController.php (Ana File Manager kontrolcüsü)
- [x] app/Http/Controllers/Admin/FileManagerController.php (Admin paneli için File Manager görünümü kontrolcüsü)
- [x] app/Http/Controllers/Admin/CustomFileManagerController.php (Özelleştirilmiş File Manager kontrolcüsü)

### 3. Servis ve Helper Sınıfları
- [x] app/Services/FileManager/CustomLfmPathHelper.php (Özelleştirilmiş File Manager yol helper'ı)
- [x] app/Providers/FileManagerServiceProvider.php (File Manager servis sağlayıcısı)

### 4. Rotalar
- [x] routes/web.php içindeki filemanager prefix'li rotalar
  ```php
  Route::group(['prefix' => 'filemanager', 'middleware' => ['web']], function () {
      Route::any('/upload', '\App\Http\Controllers\FileManagerController@upload')->name('unisharp.lfm.upload');
      Route::post('/custom-upload', [App\Http\Controllers\Admin\CustomFileManagerController::class, 'upload'])->name('custom-filemanager.upload');
      \UniSharp\LaravelFilemanager\Lfm::routes();
  });
  ```
- [x] routes/web.php içindeki file-manager-page rotası
  ```php
  Route::get('/file-manager-page', [App\Http\Controllers\Admin\FileManagerController::class, 'index'])->name('file-manager');
  ```
- [x] routes/web.php içindeki custom-filemanager rotası
  ```php
  Route::get('/custom-filemanager', [App\Http\Controllers\Admin\CustomFileManagerController::class, 'index'])->name('custom-filemanager');
  ```
- [x] routes/web.php içindeki API route'ları
  ```php
  Route::get('/api/content-files', [App\Http\Controllers\Admin\CustomFileManagerController::class, 'getContentFiles'])->name('api.content-files');
  Route::post('/filemanager/relation', [App\Http\Controllers\Admin\CustomFileManagerController::class, 'saveMediaRelation'])->name('filemanager.relation');
  ```

### 5. Görünümler
- [x] resources/views/admin/filemanager/index.blade.php (File Manager ana görünümü)
- [x] resources/views/admin/filemanager/custom.blade.php (Özelleştirilmiş File Manager görünümü)

### 6. Konfigürasyonlar
- [x] config/lfm.php (Laravel File Manager konfigürasyonu)

### 7. Service Provider ve Facade Kayıtları
- [x] config/app.php içindeki Service Provider kayıtları
  ```php
  UniSharp\LaravelFilemanager\LaravelFilemanagerServiceProvider::class,
  App\Providers\FileManagerServiceProvider::class,
  ```

### 8. Public Dizini ve Asset Dosyaları
- [x] public/vendor/laravel-filemanager/* (Paket tarafından yayınlanan public dosyaları)
  - [x] public/vendor/laravel-filemanager/js/stand-alone-button.js
  - [x] public/vendor/laravel-filemanager/js/script.js
  - [x] public/vendor/laravel-filemanager/js/filemanager.js
  - [x] public/vendor/laravel-filemanager/js/filemanager.min.js
  - [x] public/vendor/laravel-filemanager/css/* (Tüm CSS dosyaları)
  - [x] public/vendor/laravel-filemanager/img/* (Tüm resim dosyaları)

### 9. Diğer Bileşenler ve Entegrasyonlar
- [x] TinyMCE entegrasyonu (Laravel File Manager'ı TinyMCE içinde kullanmak için JavaScript entegrasyonu)
  - Bu entegrasyon şu dosyalarda yer alıyor:
  - [x] resources/views/admin/services/create.blade.php
  - [x] resources/views/admin/services/edit.blade.php
  - [x] resources/views/admin/projects/create.blade.php
  - [x] resources/views/admin/projects/edit.blade.php
  - [x] resources/views/admin/events/create.blade.php
  - [x] resources/views/admin/events/edit.blade.php
  - [x] resources/views/admin/pages/edit.blade.php (SetUrl fonksiyonu)
- [x] AppServiceProvider içindeki File Manager ile ilgili kodlar
  ```php
  // Laravel File Manager entegrasyonu için public disk URL'lerini düzelt
  \Illuminate\Support\Facades\URL::forceRootUrl(config('app.url'));
  
  // Laravel File Manager'ın yanıt vermeden önce URL dönüşümü yapmasını sağla
  \Event::listen('lfm.api.response', function ($response) {
      if (isset($response['result'])) {
          // URL'deki storage yollarını uploads olarak değiştir
          if (isset($response['result']['result']) && !empty($response['result']['result'])) {
              $url = $response['result']['result'];
              if (is_string($url) && strpos($url, '/storage/') !== false) {
                  $response['result']['result'] = str_replace('/storage/', '/uploads/', $url);
              }
          }
      }
      return $response;
  });
  ```
- [x] JavaScript entegrasyon öğeleri (`window.SetUrl` fonksiyonu ve file picker callbackler)
  - Örnek: resources/views/admin/pages/edit.blade.php içindeki:
  ```javascript
  window.SetUrl = function(items) {
      console.log('FileManager SetUrl çağrıldı', items);
      // Bu işlev filemanager tarafından otomatik olarak çağrılır
      if (window.currentFileInput) {
          if (items.length > 0) {
              var url = items[0].url;
              window.currentFileInput.val(url).trigger('change');
              // ...
          }
      }
      // ...
  };
  ```

## Kaldırma Adımları

### Adım 1: Rotaların Devre Dışı Bırakılması
- [x] routes/web.php dosyasındaki filemanager prefix'li rotaları kaldır
  ```php
  Route::group(['prefix' => 'filemanager', 'middleware' => ['web']], function () {
      Route::any('/upload', '\App\Http\Controllers\FileManagerController@upload')->name('unisharp.lfm.upload');
      Route::post('/custom-upload', [App\Http\Controllers\Admin\CustomFileManagerController::class, 'upload'])->name('custom-filemanager.upload');
      \UniSharp\LaravelFilemanager\Lfm::routes();
  });
  ```
- [x] routes/web.php dosyasındaki file-manager-page ve custom-filemanager rotalarını kaldır
  ```php
  Route::get('/file-manager-page', [App\Http\Controllers\Admin\FileManagerController::class, 'index'])->name('file-manager');
  Route::get('/custom-filemanager', [App\Http\Controllers\Admin\CustomFileManagerController::class, 'index'])->name('custom-filemanager');
  ```
- [x] routes/web.php içindeki API route'larını kaldır
  ```php
  Route::get('/api/content-files', [App\Http\Controllers\Admin\CustomFileManagerController::class, 'getContentFiles'])->name('api.content-files');
  Route::post('/filemanager/relation', [App\Http\Controllers\Admin\CustomFileManagerController::class, 'saveMediaRelation'])->name('filemanager.relation');
  ```

### Adım 2: Kontrolcü ve Servis Dosyalarını Kaldırma
- [x] app/Http/Controllers/FileManagerController.php dosyasını kaldır
- [x] app/Http/Controllers/Admin/FileManagerController.php dosyasını kaldır
- [x] app/Http/Controllers/Admin/CustomFileManagerController.php dosyasını kaldır
- [x] app/Services/FileManager/CustomLfmPathHelper.php dosyasını kaldır
- [x] app/Providers/FileManagerServiceProvider.php dosyasını kaldır

### Adım 3: Görünüm Dosyalarını Kaldırma
- [x] resources/views/admin/filemanager/index.blade.php dosyasını kaldır
- [x] resources/views/admin/filemanager/custom.blade.php dosyasını kaldır

### Adım 4: Konfigürasyon Dosyalarını Kaldırma
- [x] config/lfm.php dosyasını kaldır

### Adım 5: Service Provider ve Facade Kayıtlarını Kaldırma
- [x] config/app.php dosyasındaki service provider kayıtlarını kaldır
  ```php
  // Kaldırılacak
  UniSharp\LaravelFilemanager\LaravelFilemanagerServiceProvider::class,
  App\Providers\FileManagerServiceProvider::class,
  ```

### Adım 6: AppServiceProvider İçindeki File Manager Kodlarını Düzenleme
- [x] app/Providers/AppServiceProvider.php dosyasındaki Laravel File Manager ile ilgili kodları kaldır
  ```php
  // Kaldırılacak
  // Laravel File Manager entegrasyonu için public disk URL'lerini düzelt
  \Illuminate\Support\Facades\URL::forceRootUrl(config('app.url'));
  
  // Laravel File Manager'ın yanıt vermeden önce URL dönüşümü yapmasını sağla
  \Event::listen('lfm.api.response', function ($response) {
      if (isset($response['result'])) {
          // URL'deki storage yollarını uploads olarak değiştir
          if (isset($response['result']['result']) && !empty($response['result']['result'])) {
              $url = $response['result']['result'];
              if (is_string($url) && strpos($url, '/storage/') !== false) {
                  $response['result']['result'] = str_replace('/storage/', '/uploads/', $url);
              }
          }
      }
      return $response;
  });
  ```

### Adım 7: TinyMCE ve Diğer JavaScript Entegrasyonlarını Düzenleme
- [x] Sadece Laravel File Manager ile bağlantılı olan kodları düzelt
  - Şu dosyalardaki file-picker-callback gibi kodlar düzenlenecek:
  - [x] resources/views/admin/services/create.blade.php
  - [x] resources/views/admin/services/edit.blade.php
  - [x] resources/views/admin/projects/create.blade.php
  - [x] resources/views/admin/projects/edit.blade.php
  - [x] resources/views/admin/events/create.blade.php
  - [x] resources/views/admin/events/edit.blade.php
  - [x] resources/views/admin/pages/edit.blade.php
- [x] stand-alone-button.js referanslarını script etiketlerinden kaldır:
  ```html
  <!-- Kaldırılacak -->
  <script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
  ```
- [x] FileManager entegrasyonu için kullanılan jQuery kodlarını düzenle:
  ```javascript
  // Kaldırılacak
  $('#lfm').filemanager('image', {prefix: '/admin/filemanager'});
  ```

### Adım 8: JavaScript Özel Fonksiyonlarını Düzenleme
- [x] Sadece Laravel File Manager ile çalışan JavaScript fonksiyonlarını düzenle, FileManagerSystem ile çalışanları bırak

### Adım 9: Public Dosyalarını Kaldırma
- [x] public/vendor/laravel-filemanager/ dizinini kaldır (sadece bu dizini)

### Adım 10: Bağımlılıkları Kaldırma
- [x] composer.json dosyasından "unisharp/laravel-filemanager" paketini kaldır
- [ ] `composer update` komutunu çalıştır

### Adım 11: Cache ve Yapılandırma Temizliği
- [x] `php artisan config:clear` komutunu çalıştır
- [x] `php artisan cache:clear` komutunu çalıştır
- [x] `php artisan view:clear` komutunu çalıştır
- [x] `php artisan route:clear` komutunu çalıştır

## Dikkat Edilmesi Gerekenler

### 1. FileManagerSystem Modülüne Kesinlikle Dokunulmamalı
- http://localhost:8000/admin/filemanagersystem URL'i ile erişilen FileManagerSystem modülü ve tüm bileşenleri korunacaktır.
- app/Http/Controllers/FileManagerSystem/* klasöründeki kontrolcüler korunmalıdır.
- app/Models/FileManagerSystem/* klasöründeki modeller korunmalıdır.
- resources/views/filemanagersystem/* klasöründeki görünümler korunmalıdır.
- routes dosyalarındaki filemanagersystem prefix'li rotalar korunmalıdır.
- config/filemanagersystem.php dosyası korunmalıdır.

### 2. uploads Disk Yapılandırması
- config/filesystems.php içindeki "uploads" disk yapılandırması her iki sistem tarafından da kullanılıyor olabilir, kesinlikle kaldırılmamalıdır.

### 3. TinyMCE Entegrasyon Alternatifi
- Laravel File Manager kaldırıldıktan sonra, TinyMCE editörü artık FileManagerSystem ile çalışacak şekilde düzenlenmelidir.
- public/js/tinymce.js dosyası FileManagerSystem için entegrasyon içeriyor, bu dosyaya dokunulmamalıdır.

### 4. Yedekleme
- Kaldırma işlemine başlamadan önce tüm kodun ve veritabanının yedeklerini alın.

### 5. Aşamalı Kaldırma
- Tüm değişiklikleri bir seferde yapmak yerine adım adım ilerleyin ve her adımdan sonra FileManagerSystem fonksiyonlarının çalıştığını test edin. 