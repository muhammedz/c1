# Haber Modülünde FileManager Sistem Entegrasyonu Planı

## Genel Bakış
Bu plan, haber modülündeki görsel yükleme alanlarını FileManager sistem modülüyle entegre etmek için adımları içermektedir. Entegrasyon, slider modülünde kullanılan yöntem örnek alınarak yapılacaktır.

## Mevcut Durum Analizi
- Haber modülü şu anda kendi içinde görsel yükleme sistemi kullanıyor
- FileManager sistem modülü `/admin/filemanagersystem` adresinde çalışıyor
- Slider modülünde (`/admin/homepage/sliders`) benzer bir entegrasyon yapılmış ve örnek alınacak

## Entegre Edilecek Alanlar
- [x] Haber ana görseli (image alanı)
- [x] Haber galerisi (gallery alanı)

## Yapılacak İşlemler

### 1. Veritabanı Yapısını Güncelleme
- [x] `news` tablosunda gerekli sütunların eklenmesi için migration oluşturma
  ```php
  <?php

  use Illuminate\Database\Migrations\Migration;
  use Illuminate\Database\Schema\Blueprint;
  use Illuminate\Support\Facades\Schema;

  return new class extends Migration
  {
      /**
       * Run the migrations.
       */
      public function up(): void
      {
          Schema::table('news', function (Blueprint $table) {
              $table->string('filemanagersystem_image')->nullable()->after('image');
              $table->string('filemanagersystem_image_alt')->nullable()->after('filemanagersystem_image');
              $table->string('filemanagersystem_image_title')->nullable()->after('filemanagersystem_image_alt');
              $table->json('filemanagersystem_gallery')->nullable()->after('gallery');
          });
      }

      /**
       * Reverse the migrations.
       */
      public function down(): void
      {
          Schema::table('news', function (Blueprint $table) {
              $table->dropColumn('filemanagersystem_image');
              $table->dropColumn('filemanagersystem_image_alt');
              $table->dropColumn('filemanagersystem_image_title');
              $table->dropColumn('filemanagersystem_gallery');
          });
      }
  };
  ```

### 2. Model Güncellemeleri
- [x] `News` modelinde `fillable` alanlarına yeni sütunları ekleme
  ```php
  // app/Models/News.php
  
  protected $fillable = [
      'title', 
      'slug', 
      'summary',
      'content', 
      'image', 
      'gallery',
      'filemanagersystem_image',
      'filemanagersystem_image_alt',
      'filemanagersystem_image_title',
      'filemanagersystem_gallery',
      'is_headline', 
      'headline_order', 
      'is_featured',
      'view_count',
      'status',
      'is_scheduled',
      'meta_title',
      'meta_description',
      'published_at',
      'end_date'
  ];
  
  protected $casts = [
      'is_headline' => 'boolean',
      'is_featured' => 'boolean',
      'is_scheduled' => 'boolean',
      'published_at' => 'datetime',
      'end_date' => 'datetime',
      'gallery' => 'array',
      'filemanagersystem_gallery' => 'array'
  ];
  ```

- [x] `News` modelinde MediaRelation ilişkisini tanımlama
  ```php
  // app/Models/News.php - modele eklenecek metotlar
  
  /**
   * Haber'e ait medya ilişkilerini getir - MorphMany ilişkisi ile
   */
  public function mediaRelations(): MorphMany
  {
      return $this->morphMany(MediaRelation::class, 'related', 'related_type', 'related_id');
  }

  /**
   * Haber'e bağlı medya dosyalarını getir
   */
  public function media()
  {
      return $this->hasManyThrough(
          Media::class,
          MediaRelation::class,
          'related_id',
          'id',
          'id',
          'media_id'
      )->where('related_type', 'news');
  }

  /**
   * Ana görsel için medya ilişkisi
   */
  public function featuredImage()
  {
      return $this->media()->where('field_name', 'featured_image')->first();
  }

  /**
   * Galeri görselleri için medya ilişkileri
   */
  public function galleryImages()
  {
      return $this->media()->where('field_name', 'gallery')->orderBy('order')->get();
  }

  /**
   * Haber ana görselinin tam URL'ini döndürür
   */
  public function getFilemanagersystemImageUrlAttribute(): ?string
  {
      if (empty($this->filemanagersystem_image)) {
          return null;
      }
      
      // Eğer URL zaten tam bir URL ise (http:// ile başlıyorsa) direkt döndür
      if (strpos($this->filemanagersystem_image, 'http://') === 0 || strpos($this->filemanagersystem_image, 'https://') === 0) {
          return $this->filemanagersystem_image;
      }
      
      // Media ID kontrolü - /uploads/media/ID formatı
      if (preg_match('#^/uploads/media/(\d+)$#', $this->filemanagersystem_image, $matches)) {
          $mediaId = $matches[1];
          $media = \App\Models\FileManagerSystem\Media::find($mediaId);
          
          if ($media) {
              // Medya bulundu, URL'i döndür
              return asset($media->url);
          }
          
          // Medya bulunamadı, ilişkili medyaları kontrol et
          $relatedMedia = $this->featuredImage();
          if ($relatedMedia) {
              return asset($relatedMedia->url);
          }
      }
      
      return asset($this->filemanagersystem_image);
  }
  ```

### 3. Controller Güncellemeleri
- [x] `NewsService` ve `NewsController` içinde `store` ve `update` metotlarını FileManager entegrasyonu için güncelleme
  ```php
  // app/Services/NewsService.php - ekleme/güncelleme metotlarına eklemeler

  /**
   * Create news.
   *
   * @param array $data
   * @return News|false
   */
  public function createNews(array $data)
  {
      try {
          // ... mevcut kodlar ...
          
          // İlişkili medya varsa ilişkiyi kur
          if (!empty($data['filemanagersystem_image'])) {
              $this->createMediaRelationForNews($news, $data['filemanagersystem_image']);
          }
          
          // Galeri ilişkilerini kur
          if (!empty($data['filemanagersystem_gallery']) && is_array($data['filemanagersystem_gallery'])) {
              $this->createGalleryMediaRelations($news, $data['filemanagersystem_gallery']);
          }
          
          return $news;
          
      } catch (\Exception $e) {
          \Log::error('Haber oluşturma hatası: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
          return false;
      }
  }
  ```

- [x] FileManager medya ilişkilendirme işlemi için yardımcı metotları ekleme
  ```php
  // app/Services/NewsService.php - eklenecek yeni metotlar
  
  /**
   * Ana görsel için medya ilişkisi oluşturur
   */
  private function createMediaRelationForNews(News $news, string $filemanagersystemImage)
  {
      try {
          // Medya ID'sini bul
          $mediaId = null;
          
          // 1. /uploads/media/123 formatı
          if (preg_match('#^/uploads/media/(\d+)$#', $filemanagersystemImage, $matches)) {
              $mediaId = $matches[1];
          }
          // 2. /admin/filemanagersystem/media/preview/123 formatı
          elseif (preg_match('#/media/preview/(\d+)#', $filemanagersystemImage, $matches)) {
              $mediaId = $matches[1];
          }
          
          if ($mediaId) {
              // Mevcut ilişkiyi kontrol et
              $existingRelation = \App\Models\FileManagerSystem\MediaRelation::where('media_id', $mediaId)
                  ->where('related_type', 'news')
                  ->where('related_id', $news->id)
                  ->where('field_name', 'featured_image')
                  ->first();
              
              // İlişki yoksa oluştur
              if (!$existingRelation) {
                  $mediaRelation = new \App\Models\FileManagerSystem\MediaRelation();
                  $mediaRelation->media_id = $mediaId;
                  $mediaRelation->related_type = 'news';
                  $mediaRelation->related_id = $news->id;
                  $mediaRelation->field_name = 'featured_image';
                  $mediaRelation->order = 0;
                  $mediaRelation->save();
              }
          }
      } catch (\Exception $e) {
          \Log::error('Haber medya ilişkisi oluşturma hatası: ' . $e->getMessage(), [
              'news_id' => $news->id,
              'filemanagersystem_image' => $filemanagersystemImage,
              'trace' => $e->getTraceAsString()
          ]);
      }
  }
  
  /**
   * Galerisi ilişkilerini oluşturur
   */
  private function createGalleryMediaRelations(News $news, array $galleryItems)
  {
      try {
          foreach ($galleryItems as $index => $item) {
              // Medya ID'sini çıkart
              $mediaId = null;
              
              if (is_array($item) && isset($item['id'])) {
                  $mediaId = $item['id'];
              } elseif (is_string($item)) {
                  // URL'den ID'yi çıkart
                  if (preg_match('#^/uploads/media/(\d+)$#', $item, $matches)) {
                      $mediaId = $matches[1];
                  } elseif (preg_match('#/media/preview/(\d+)#', $item, $matches)) {
                      $mediaId = $matches[1];
                  }
              }
              
              if ($mediaId) {
                  // Galeri için ilişki oluştur
                  $mediaRelation = new \App\Models\FileManagerSystem\MediaRelation();
                  $mediaRelation->media_id = $mediaId;
                  $mediaRelation->related_type = 'news';
                  $mediaRelation->related_id = $news->id;
                  $mediaRelation->field_name = 'gallery';
                  $mediaRelation->order = $index;
                  $mediaRelation->save();
              }
          }
      } catch (\Exception $e) {
          \Log::error('Haber galeri ilişkisi oluşturma hatası: ' . $e->getMessage(), [
              'news_id' => $news->id,
              'trace' => $e->getTraceAsString()
          ]);
      }
  }
  
  /**
   * Ana görsel ilişkisini günceller
   */
  private function updateMediaRelationForNews(News $news, string $filemanagersystemImage)
  {
      try {
          // Önce mevcut ana görsel ilişkilerini sil
          \App\Models\FileManagerSystem\MediaRelation::where('related_type', 'news')
              ->where('related_id', $news->id)
              ->where('field_name', 'featured_image')
              ->delete();
          
          // Yeni ilişki oluştur
          $this->createMediaRelationForNews($news, $filemanagersystemImage);
          
      } catch (\Exception $e) {
          \Log::error('Haber medya ilişkisi güncelleme hatası: ' . $e->getMessage(), [
              'news_id' => $news->id,
              'filemanagersystem_image' => $filemanagersystemImage,
              'trace' => $e->getTraceAsString()
          ]);
      }
  }
  
  /**
   * Galeri ilişkilerini günceller
   */
  private function updateGalleryMediaRelations(News $news, array $galleryItems)
  {
      try {
          // Önce mevcut galeri ilişkilerini sil
          \App\Models\FileManagerSystem\MediaRelation::where('related_type', 'news')
              ->where('related_id', $news->id)
              ->where('field_name', 'gallery')
              ->delete();
          
          // Yeni ilişkileri oluştur
          $this->createGalleryMediaRelations($news, $galleryItems);
          
      } catch (\Exception $e) {
          \Log::error('Haber galeri ilişkisi güncelleme hatası: ' . $e->getMessage(), [
              'news_id' => $news->id,
              'trace' => $e->getTraceAsString()
          ]);
      }
  }
  ```

### 4. View Güncellemeleri
- [x] `create.blade.php` görsel seçimi için FileManager entegrasyonu ekleme
  ```html
  <!-- Ana Görsel -->
  <div class="form-group">
      <label for="filemanagersystem_image">Haber Görseli</label>
      <div class="input-group">
          <input type="text" class="form-control @error('filemanagersystem_image') is-invalid @enderror" 
                id="filemanagersystem_image" name="filemanagersystem_image" 
                value="{{ old('filemanagersystem_image') }}" readonly>
          <div class="input-group-append">
              <button type="button" class="btn btn-primary" id="filemanagersystem_image_button">
                  <i class="fas fa-image"></i> Görsel Seç
              </button>
          </div>
      </div>
      @error('filemanagersystem_image')
          <span class="invalid-feedback">{{ $message }}</span>
      @enderror
      <div id="filemanagersystem_image_preview" class="mt-2" style="display: none;">
          <img src="" alt="Önizleme" class="img-thumbnail" style="max-height: 200px;">
      </div>
  </div>

  <!-- Görsel Alt Metni -->
  <div class="form-group">
      <label for="filemanagersystem_image_alt">Görsel Alt Metni</label>
      <input type="text" class="form-control @error('filemanagersystem_image_alt') is-invalid @enderror" 
            id="filemanagersystem_image_alt" name="filemanagersystem_image_alt" 
            value="{{ old('filemanagersystem_image_alt') }}">
      @error('filemanagersystem_image_alt')
          <span class="invalid-feedback">{{ $message }}</span>
      @enderror
  </div>

  <!-- Görsel Başlığı -->
  <div class="form-group">
      <label for="filemanagersystem_image_title">Görsel Başlığı</label>
      <input type="text" class="form-control @error('filemanagersystem_image_title') is-invalid @enderror" 
            id="filemanagersystem_image_title" name="filemanagersystem_image_title" 
            value="{{ old('filemanagersystem_image_title') }}">
      @error('filemanagersystem_image_title')
          <span class="invalid-feedback">{{ $message }}</span>
      @enderror
  </div>

  <!-- Galeri -->
  <div class="form-group">
      <label>Galeri Görselleri</label>
      <div class="d-flex justify-content-between align-items-center mb-2">
          <div>Görselleri sürükleyerek sıralayabilirsiniz.</div>
          <button type="button" class="btn btn-sm btn-primary" id="add_gallery_item">
              <i class="fas fa-plus"></i> Görsel Ekle
          </button>
      </div>
      <div id="gallery_container" class="gallery-container mb-3">
          <!-- Galeri öğeleri buraya eklenecek -->
          <input type="hidden" name="filemanagersystem_gallery" id="filemanagersystem_gallery" value="">
      </div>
  </div>
  ```

- [x] MediaPicker modalını ekleme
  ```html
  <!-- MediaPicker Modal -->
  <div class="modal fade" id="mediapickerModal" tabindex="-1" role="dialog" aria-labelledby="mediapickerModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl" role="document" style="max-width: 90%;">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="mediapickerModalLabel">Medya Seçici</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Kapat">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body p-0">
                  <iframe id="mediapickerFrame" style="width: 100%; height: 80vh; border: none;"></iframe>
              </div>
          </div>
      </div>
  </div>
  ```

### 5. JavaScript Entegrasyonu
- [x] Ana görsel için FileManager seçici JavaScript kodunu ekleme
  ```javascript
  // Ana görsel seçici
  $('#filemanagersystem_image_button').on('click', function() {
      const input = $('#filemanagersystem_image');
      const preview = $('#filemanagersystem_image_preview');
      const previewImg = preview.find('img');
      
      // Geçici bir ID oluştur
      const tempId = 'temp_' + Date.now();
      const relatedType = 'news';
      
      // MediaPicker URL
      const mediapickerUrl = '/admin/filemanagersystem/mediapicker?type=image&filter=all&related_type=' + relatedType + '&related_id=' + tempId;
      
      // iFrame'i güncelle ve modalı göster
      $('#mediapickerFrame').attr('src', mediapickerUrl);
      $('#mediapickerModal').modal('show');
      
      // Medya seçimini dinle
      function handleMediaSelection(event) {
          try {
              if (event.data && event.data.type === 'mediaSelected') {
                  if (event.data.mediaUrl) {
                      let mediaUrl = event.data.mediaUrl;
                      
                      // Görsel URL'sini forma kaydet ve önizlemede göster
                      input.val(mediaUrl);
                      previewImg.attr('src', mediaUrl);
                      preview.show();
                  } else {
                      // URL bulunamadıysa ID ile kullan
                      input.val('/uploads/media/' + event.data.mediaId);
                      
                      // Önizleme için ID ile resmi göster
                      const previewUrl = '/admin/filemanagersystem/media/preview/' + event.data.mediaId;
                      previewImg.attr('src', previewUrl);
                      preview.show();
                  }
                  
                  // Modalı kapat
                  $('#mediapickerModal').modal('hide');
              }
          } catch (error) {
              console.error('Medya seçimi işlenirken hata oluştu:', error);
          }
      }
      
      // Event listener ekle
      window.removeEventListener('message', handleMediaSelection);
      window.addEventListener('message', handleMediaSelection);
  });
  ```

- [x] Galeri için FileManager seçici ve sıralama JavaScript kodunu ekleme
  ```javascript
  // Galeri görselleri yönetimi
  let galleryItems = [];
  
  // Galeri görsel ekleme butonu
  $('#add_gallery_item').on('click', function() {
      const relatedType = 'news';
      const tempId = 'temp_' + Date.now();
      
      // MediaPicker URL - galeri için
      const mediapickerUrl = '/admin/filemanagersystem/mediapicker?type=image&filter=all&related_type=' + relatedType + '&related_id=' + tempId + '&field_name=gallery';
      
      // iFrame'i güncelle ve modalı göster
      $('#mediapickerFrame').attr('src', mediapickerUrl);
      $('#mediapickerModal').modal('show');
      
      // Medya seçimini dinle
      function handleGalleryMediaSelection(event) {
          try {
              if (event.data && event.data.type === 'mediaSelected') {
                  // Galeri öğesi ekle
                  const mediaItem = {
                      id: event.data.mediaId,
                      url: event.data.mediaUrl || '/uploads/media/' + event.data.mediaId,
                      preview: event.data.mediaUrl || '/admin/filemanagersystem/media/preview/' + event.data.mediaId
                  };
                  
                  galleryItems.push(mediaItem);
                  renderGalleryItems();
                  
                  // Modalı kapat
                  $('#mediapickerModal').modal('hide');
              }
          } catch (error) {
              console.error('Galeri medya seçimi işlenirken hata oluştu:', error);
          }
      }
      
      // Event listener ekle
      window.removeEventListener('message', handleGalleryMediaSelection);
      window.addEventListener('message', handleGalleryMediaSelection);
  });
  
  // Galeri öğelerini render et
  function renderGalleryItems() {
      const container = $('#gallery_container');
      container.html('');
      
      galleryItems.forEach((item, index) => {
          const itemEl = $(`
              <div class="gallery-item" data-id="${item.id}" data-index="${index}">
                  <img src="${item.preview}" alt="Galeri görseli">
                  <button type="button" class="remove-btn remove-gallery-item" data-index="${index}">
                      <i class="fas fa-times"></i>
                  </button>
                  <input type="hidden" name="filemanagersystem_gallery[]" value="${item.id}">
              </div>
          `);
          
          container.append(itemEl);
      });
      
      // Sıralanabilir yap
      initSortable();
      
      // JSON olarak gizli inputa kaydet
      $('#filemanagersystem_gallery').val(JSON.stringify(galleryItems.map(item => item.id)));
  }
  
  // Galeri öğelerini sıralama
  function initSortable() {
      if (typeof Sortable !== 'undefined') {
          new Sortable(document.getElementById('gallery_container'), {
              animation: 150,
              onEnd: function() {
                  // Sıralama değişikliğini kaydet
                  const newOrder = [];
                  $('#gallery_container .gallery-item').each(function() {
                      const id = $(this).data('id');
                      const oldIndex = $(this).data('index');
                      newOrder.push(galleryItems[oldIndex]);
                  });
                  
                  galleryItems = newOrder;
                  renderGalleryItems();
              }
          });
      }
  }
  
  // Galeri öğesi kaldırma
  $(document).on('click', '.remove-gallery-item', function() {
      const index = $(this).data('index');
      galleryItems.splice(index, 1);
      renderGalleryItems();
  });
  ```

### 6. FileManagerSystem MediaRelation Sınıfında Güncelleme
- [x] MediaRelation modelinde news ilişkisini tanımlama
  ```php
  // app/Models/FileManagerSystem/MediaRelation.php içindeki getRelatedClass() metoduna ekleme
  public static function getRelatedClass($relatedType)
  {
      $map = [
          'homepage_slider' => \App\Models\Slider::class,
          'corporate_category' => \App\Models\CorporateCategory::class,
          'corporate_member' => \App\Models\CorporateMember::class,
          'news' => \App\Models\News::class, // Haber modülü için yeni eklenen satır
          // Diğer ilişki tipleri burada tanımlanabilir
      ];
      
      return $map[$relatedType] ?? null;
  }
  ```

## Detaylı Adımlar

### 1. Veritabanı Migration Oluşturma
- [x] `php artisan make:migration add_filemanagersystem_columns_to_news_table` komutu ile migration oluştur
- [x] Migration dosyasını yukarıda belirtilen şekilde düzenle
- [x] `php artisan migrate` komutu ile migration'ı çalıştır

### 2. News Model Güncelleme
- [x] `News` modelinde fillable alanlarını güncelle
- [x] İlişkiler ve medya ilişkisi için gereken metotları ekle

### 3. NewsController ve NewsService Güncelleme
- [x] Medya ilişkileri için yardımcı metotları ekle
- [x] `store` ve `update` metotlarını medya ilişkilerini destekleyecek şekilde güncelle

### 4. Blade Template Güncellemeleri
- [x] `create.blade.php` ve `edit.blade.php` dosyalarına FileManager entegrasyonu için gereken HTML kodlarını ekle
- [x] MediaPicker modalını ekle

### 5. JavaScript Entegrasyonu
- [x] Ana görsel ve galeri için FileManager medya seçici JavaScript kodlarını ekle
- [x] Galeri görsellerini sıralama ve yönetme kodlarını ekle

## Entegrasyon Test Planı

1. **Ana Görsel Entegrasyonu**
   - [ ] Yeni haber oluşturma ekranında ana görsel seçebilme
   - [ ] Seçilen görselin önizlemesini görebilme
   - [ ] Ana görselin veritabanına kaydedilmesi
   - [ ] Ana görsel için FileManagerSystem ilişkisinin doğru kurulması

2. **Galeri Entegrasyonu**
   - [ ] Galeri için birden fazla görsel seçebilme
   - [ ] Galeri görsellerini sıralayabilme
   - [ ] Galeri görsellerinin veritabanına kaydedilmesi
   - [ ] Galeri görselleri için FileManagerSystem ilişkilerinin doğru kurulması

3. **Düzenleme İşlemleri**
   - [ ] Haber düzenleme ekranında mevcut görselleri görebilme
   - [ ] Ana görsel ve galeri görsellerini değiştirebilme
   - [ ] Değişikliklerin veritabanına doğru kaydedilmesi

4. **Frontend Entegrasyonu**
   - [ ] Haber görüntüleme sayfasında FileManagerSystem görsellerin doğru şekilde gösterilmesi
   - [ ] Galeri görsellerinin doğru sırada gösterilmesi

## Geriye Dönük Uyumluluk
- Geçiş sürecinde hem eski görsel sistemi hem de yeni FileManagerSystem desteklenecek
- Frontend gösterimi için önce filemanagersystem_image, yoksa image alanı kullanılacak

## Ek Geliştirmeler
- Daha sonraki aşamada eski görselleri toplu olarak FileManagerSystem'e taşımak için bir araç geliştirilebilir
- Eski görsel alanlarının tamamen kaldırılması için ikinci bir migration planlanabilir 