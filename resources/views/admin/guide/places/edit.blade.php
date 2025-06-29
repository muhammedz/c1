@extends('adminlte::page')

@section('title', 'Yer Düzenle - ' . $guidePlace->title)

@section('content_header')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="d-flex justify-content-between align-items-center">
        <h1>Yer Düzenle</h1>
        <div>
            <a href="{{ route('admin.guide-places.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Geri Dön
            </a>
            <a href="{{ route('guide.place', [$guidePlace->category->slug, $guidePlace->slug]) }}" target="_blank" class="btn btn-info">
                <i class="fas fa-eye"></i> Önizle
            </a>
        </div>
    </div>
@stop

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Yer Bilgileri</h3>
            </div>
            <form action="{{ route('admin.guide-places.update', $guidePlace) }}" method="POST" enctype="multipart/form-data" id="place-form">
                @csrf
                @method('PUT')
                
                <div class="card-body">
                    <!-- Başlık -->
                    <div class="form-group">
                        <label for="title">Başlık <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('title') is-invalid @enderror" 
                               id="title" 
                               name="title" 
                               value="{{ old('title', $guidePlace->title) }}" 
                               required
                               maxlength="255">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            <span id="titleCount">{{ strlen($guidePlace->title) }}</span>/255 karakter
                        </small>
                    </div>

                    <!-- Slug -->
                    <div class="form-group">
                        <label for="slug">URL Slug</label>
                        <input type="text" 
                               class="form-control @error('slug') is-invalid @enderror" 
                               id="slug" 
                               name="slug" 
                               value="{{ old('slug', $guidePlace->slug) }}"
                               maxlength="255">
                        @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Boş bırakılırsa başlıktan otomatik oluşturulur</small>
                    </div>

                    <!-- Özet -->
                    <div class="form-group">
                        <label for="excerpt">Özet</label>
                        <textarea class="form-control @error('excerpt') is-invalid @enderror" 
                                  id="excerpt" 
                                  name="excerpt" 
                                  rows="3"
                                  maxlength="500">{{ old('excerpt', $guidePlace->excerpt) }}</textarea>
                        @error('excerpt')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            <span id="excerptCount">{{ strlen($guidePlace->excerpt ?? '') }}</span>/500 karakter
                        </small>
                    </div>

                    <!-- İçerik -->
                    <div class="form-group">
                        <label for="content">İçerik</label>
                        <textarea class="form-control tinymce @error('content') is-invalid @enderror" 
                                  id="content" 
                                  name="content" 
                                  rows="15">{{ old('content', $guidePlace->content) }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- İletişim Bilgileri -->
                    <h5 class="mt-4 mb-3">İletişim Bilgileri</h5>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone">Telefon</label>
                                <input type="text" 
                                       class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" 
                                       name="phone" 
                                       value="{{ old('phone', $guidePlace->phone) }}"
                                       maxlength="20">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">E-posta</label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', $guidePlace->email) }}"
                                       maxlength="255">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address">Adres</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" 
                                  name="address" 
                                  rows="2"
                                  maxlength="500">{{ old('address', $guidePlace->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="website">Website</label>
                                <input type="url" 
                                       class="form-control @error('website') is-invalid @enderror" 
                                       id="website" 
                                       name="website" 
                                       value="{{ old('website', $guidePlace->website) }}"
                                       maxlength="255">
                                @error('website')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="maps_link">Kentrehberi Linki</label>
                                <input type="url" 
                                       class="form-control @error('maps_link') is-invalid @enderror" 
                                       id="maps_link" 
                                       name="maps_link" 
                                       value="{{ old('maps_link', $guidePlace->maps_link) }}"
                                       maxlength="500">
                                @error('maps_link')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="working_hours">Çalışma Saatleri</label>
                        <input type="text" 
                               class="form-control @error('working_hours') is-invalid @enderror" 
                               id="working_hours" 
                               name="working_hours" 
                               value="{{ old('working_hours', $guidePlace->working_hours) }}"
                               maxlength="255">
                        @error('working_hours')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- SEO -->
                    <h5 class="mt-4 mb-3">SEO Ayarları</h5>
                    
                    <div class="form-group">
                        <label for="meta_title">Meta Başlık</label>
                        <input type="text" 
                               class="form-control @error('meta_title') is-invalid @enderror" 
                               id="meta_title" 
                               name="meta_title" 
                               value="{{ old('meta_title', $guidePlace->meta_title) }}"
                               maxlength="60">
                        @error('meta_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            <span id="metaTitleCount">{{ strlen($guidePlace->meta_title ?? '') }}</span>/60 karakter
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="meta_description">Meta Açıklama</label>
                        <textarea class="form-control @error('meta_description') is-invalid @enderror" 
                                  id="meta_description" 
                                  name="meta_description" 
                                  rows="3"
                                  maxlength="160">{{ old('meta_description', $guidePlace->meta_description) }}</textarea>
                        @error('meta_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            <span id="metaDescCount">{{ strlen($guidePlace->meta_description ?? '') }}</span>/160 karakter
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="meta_keywords">Meta Anahtar Kelimeler</label>
                        <input type="text" 
                               class="form-control @error('meta_keywords') is-invalid @enderror" 
                               id="meta_keywords" 
                               name="meta_keywords" 
                               value="{{ old('meta_keywords', $guidePlace->meta_keywords) }}"
                               maxlength="255">
                        @error('meta_keywords')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Virgülle ayırın</small>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Güncelle
                    </button>
                    <a href="{{ route('admin.guide-places.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> İptal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Kategori Seçimi -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Kategori</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="guide_category_id">Kategori <span class="text-danger">*</span></label>
                    <select class="form-control @error('guide_category_id') is-invalid @enderror" 
                            id="guide_category_id" 
                            name="guide_category_id" 
                            required
                            form="place-form">
                        <option value="">Kategori Seçin</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" 
                                    {{ old('guide_category_id', $guidePlace->guide_category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('guide_category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Durum -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Durum</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" 
                               class="custom-control-input" 
                               id="is_active" 
                               name="is_active" 
                               value="1"
                               form="place-form"
                               {{ old('is_active', $guidePlace->is_active) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="is_active">Aktif</label>
                    </div>
                </div>

                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" 
                               class="custom-control-input" 
                               id="is_featured" 
                               name="is_featured" 
                               value="1"
                               form="place-form"
                               {{ old('is_featured', $guidePlace->is_featured) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="is_featured">Öne Çıkan</label>
                    </div>
                </div>
            </div>
        </div>

        {{-- Mevcut Resimler bölümü gizlendi - alt kısımda FileManagerSystem ile resim yönetimi mevcut --}}

        <!-- Yeni Resim Yükleme -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-images me-2"></i>
                    Resim Yükle
                </h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted d-block">
                        <i class="fas fa-info-circle me-1"></i>
                        FileManagerSystem'den resim seçerek yere ait fotoğrafları ekleyebilirsiniz.
                    </small>
                </div>
                
                <div class="form-group">
                    <label for="filemanagersystem_images">Resimler</label>
                    <div class="input-group">
                        <button type="button" class="btn btn-outline-primary" id="filemanagersystem_images_button">
                            <i class="fas fa-images"></i> Resim Seç
                        </button>
                        <button type="button" class="btn btn-outline-danger" id="filemanagersystem_images_clear">
                            <i class="fas fa-times"></i> Temizle
                        </button>
                    </div>
                    <input type="hidden" id="filemanagersystem_images" name="filemanagersystem_images" value="" form="place-form">
                    
                    <!-- Seçilen Resimler Önizleme -->
                    <div id="filemanagersystem_images_preview" class="mt-3" style="display: none;">
                        <h6>Seçilen Resimler:</h6>
                        <div id="selected-images-container" class="row">
                            <!-- Seçilen resimler buraya gelecek -->
                        </div>
                    </div>
                    
                    <small class="form-text text-muted">
                        FileManagerSystem'den birden fazla resim seçebilirsiniz.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 15px;
        margin-top: 1rem;
    }
    
    .gallery-item {
        position: relative;
        aspect-ratio: 1;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0,0,0,.1);
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
    }
    
    .gallery-item img {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important;
        max-height: none !important;
    }
    
    .gallery-item .remove-btn {
        position: absolute;
        top: 8px;
        right: 8px;
        background: rgba(255,255,255,.9);
        border-radius: 50%;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        border: none;
        color: #e3342f;
    }
    
    .gallery-item .remove-btn:hover {
        background: #fff;
        transform: scale(1.1);
    }
    
    .character-count {
        font-size: 0.875rem;
        color: #6c757d;
    }
    
    /* FileManagerSystem Resim Önizleme */
    #selected-images-container .col-md-3 {
        margin-bottom: 15px;
    }
    
    .selected-image-item {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0,0,0,.1);
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
    }
    
    .selected-image-item img {
        width: 100%;
        height: 150px;
        object-fit: cover;
    }
    
    .selected-image-item .remove-selected-image {
        position: absolute;
        top: 8px;
        right: 8px;
        background: rgba(255,255,255,.9);
        border-radius: 50%;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        border: none;
        color: #e3342f;
        font-size: 14px;
    }
    
    .selected-image-item .remove-selected-image:hover {
        background: #fff;
        transform: scale(1.1);
    }
    
    /* Media Picker Modal */
    #mediapickerModal .modal-dialog {
        max-width: 90%;
    }
    
    #mediapickerFrame {
        width: 100%;
        height: 80vh;
        border: none;
    }
</style>
@stop

@section('js')
<script>
// Seçilen resimler dizisi
let selectedImages = [];

// Mevcut FileManagerSystem resimlerini yükle
function loadExistingFileManagerSystemImages() {
    @if($guidePlace->images->isNotEmpty())
        @foreach($guidePlace->images as $image)
            @if(str_starts_with($image->image_path, 'filemanagersystem/'))
                @php
                    $mediaId = str_replace('filemanagersystem/', '', $image->image_path);
                @endphp
                selectedImages.push({
                    id: '{{ $mediaId }}',
                    url: '{{ $image->image_url }}',
                    alt: '{{ $image->alt_text }}',
                    title: '{{ $image->alt_text }}'
                });
            @endif
        @endforeach
        
        if (selectedImages.length > 0) {
            updateSelectedImagesDisplay();
            updateHiddenInput();
            console.log('Mevcut FileManagerSystem resimleri yüklendi:', selectedImages);
        }
    @endif
}

$(document).ready(function() {
    // Sayfa yüklendiğinde mevcut FileManagerSystem resimlerini yükle
    loadExistingFileManagerSystemImages();
    
    // FileManagerSystem resim seçici
    $('#filemanagersystem_images_button').on('click', function() {
    const tempId = Date.now();
    const relatedType = 'guide_place';
    
    // MediaPicker URL
    const mediapickerUrl = '/admin/filemanagersystem/mediapicker?type=image&filter=all&related_type=' + relatedType + '&related_id=' + tempId + '&multiple=true';
    
    console.log('FileManagerSystem açılıyor:', mediapickerUrl);
    
    // iFrame'i güncelle
    $('#mediapickerFrame').attr('src', mediapickerUrl);
    
    // Bootstrap 5 Modal oluştur ve aç
    var modal = new bootstrap.Modal(document.getElementById('mediapickerModal'));
    modal.show();
    
    // Mesaj dinleme işlevi
    function handleMediaSelection(event) {
        try {
            if (event.data && event.data.type === 'mediaSelected') {
                console.log('Seçilen medya:', event.data);
                
                // Tek resim seçimi
                if (event.data.mediaUrl || event.data.mediaId) {
                    addSelectedImage(event.data);
                }
                // Çoklu resim seçimi
                else if (event.data.selectedMedia && Array.isArray(event.data.selectedMedia)) {
                    event.data.selectedMedia.forEach(media => {
                        addSelectedImage(media);
                    });
                }
                
                updateSelectedImagesDisplay();
                updateHiddenInput();
                
                // Modalı kapat
                modal.hide();
                
                // Event listener'ı kaldır
                window.removeEventListener('message', handleMediaSelection);
            }
        } catch (error) {
            console.error('Medya seçimi işlenirken hata oluştu:', error);
            alert('Medya seçimi işlenirken bir hata oluştu: ' + error.message);
            
            // Event listener'ı kaldır
            window.removeEventListener('message', handleMediaSelection);
        }
    }
    
        // Mevcut event listener'ı kaldır ve yenisini ekle
        window.removeEventListener('message', handleMediaSelection);
        window.addEventListener('message', handleMediaSelection);
    });
    
    // Tümünü temizle
    $('#filemanagersystem_images_clear').on('click', function() {
        selectedImages = [];
        updateSelectedImagesDisplay();
        updateHiddenInput();
    });
    
    // Form submit edilmeden önce kontrol
    $('#place-form').on('submit', function(e) {
        const hiddenValue = $('#filemanagersystem_images').val();
        console.log('Form submit edilirken hidden input değeri:', hiddenValue);
        
        if (selectedImages.length > 0 && !hiddenValue) {
            console.warn('Seçili resimler var ama hidden input boş!');
            updateHiddenInput();
        }
    });
    
    // Slug otomatik oluşturma
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');
    
    if (titleInput && slugInput) {
        titleInput.addEventListener('input', function() {
            if (slugInput.value === '') {
                const title = this.value;
                const slug = title.toLowerCase()
                    .replace(/ğ/g, 'g')
                    .replace(/ü/g, 'u')
                    .replace(/ş/g, 's')
                    .replace(/ı/g, 'i')
                    .replace(/ö/g, 'o')
                    .replace(/ç/g, 'c')
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-')
                    .trim('-');
                slugInput.value = slug;
            }
        });
    }
});

// Seçilen resim ekleme
function addSelectedImage(mediaData) {
    let mediaUrl = '';
    let mediaId = '';
    
    if (mediaData.mediaUrl) {
        mediaUrl = mediaData.mediaUrl;
        if (mediaUrl.startsWith('/')) {
            mediaUrl = window.location.protocol + '//' + window.location.host + mediaUrl;
        }
    } else if (mediaData.mediaId) {
        mediaId = mediaData.mediaId;
        mediaUrl = '/admin/filemanagersystem/media/preview/' + mediaId;
    }
    
    if (mediaUrl) {
        // Aynı resmin zaten seçili olup olmadığını kontrol et
        const exists = selectedImages.some(img => img.url === mediaUrl || img.id === mediaId);
        if (!exists) {
            selectedImages.push({
                id: mediaId,
                url: mediaUrl,
                alt: mediaData.mediaAlt || '',
                title: mediaData.mediaTitle || ''
            });
        }
    }
}

// Seçilen resimleri görüntüleme
function updateSelectedImagesDisplay() {
    const container = $('#selected-images-container');
    const preview = $('#filemanagersystem_images_preview');
    
    container.empty();
    
    if (selectedImages.length > 0) {
        preview.show();
        
        selectedImages.forEach((image, index) => {
            const imageHtml = `
                <div class="col-md-3">
                    <div class="selected-image-item">
                        <img src="${image.url}" alt="${image.alt}" class="img-fluid">
                        <button type="button" class="remove-selected-image" onclick="removeSelectedImage(${index})">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            `;
            container.append(imageHtml);
        });
    } else {
        preview.hide();
    }
}

// Seçilen resmi kaldırma
function removeSelectedImage(index) {
    selectedImages.splice(index, 1);
    updateSelectedImagesDisplay();
    updateHiddenInput();
}

// Hidden input güncelleme
function updateHiddenInput() {
    const imageUrls = selectedImages.map(img => img.url);
    const jsonValue = JSON.stringify(imageUrls);
    $('#filemanagersystem_images').val(jsonValue);
    console.log('Hidden input güncellendi:', jsonValue);
}

// Öne çıkan resim toggle
function toggleFeatured(imageId) {
    $.ajax({
        url: `/admin/guide-places/images/${imageId}/toggle-featured`,
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                // Tüm featured butonlarını pasif yap
                $('.btn-outline-primary').removeClass('active');
                // Sadece bu butonu aktif yap
                $(`#image-${imageId} .btn-outline-primary`).addClass('active');
                
                toastr.success('Öne çıkan resim güncellendi');
            }
        },
        error: function() {
            toastr.error('Bir hata oluştu');
        }
    });
}

// Resim sil
function deleteImage(imageId) {
    if (confirm('Bu resmi silmek istediğinizden emin misiniz?')) {
        $.ajax({
            url: `/admin/guide-places/images/${imageId}`,
            method: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    $(`#image-${imageId}`).fadeOut(300, function() {
                        $(this).remove();
                    });
                    toastr.success('Resim silindi');
                }
            },
            error: function() {
                toastr.error('Bir hata oluştu');
            }
        });
    }
}

// Slug otomatik oluşturma - bu kısım ana jQuery ready function'a taşınacak
</script>

<!-- MediaPicker Modal -->
<div class="modal fade" id="mediapickerModal" tabindex="-1" role="dialog" aria-labelledby="mediapickerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediapickerModalLabel">Medya Seçici</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>
            <div class="modal-body p-0">
                <iframe id="mediapickerFrame" style="width: 100%; height: 80vh; border: none;"></iframe>
            </div>
        </div>
    </div>
</div>
@stop