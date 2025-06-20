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

        <!-- Mevcut Resimler -->
        @if($guidePlace->images->count() > 0)
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Mevcut Resimler</h3>
            </div>
            <div class="card-body">
                <div class="row" id="existing-images">
                    @foreach($guidePlace->images as $image)
                        <div class="col-6 mb-3" id="image-{{ $image->id }}">
                            <div class="card">
                                <img src="{{ $image->image_url }}" class="card-img-top" style="height: 100px; object-fit: cover;">
                                <div class="card-body p-2">
                                    <div class="btn-group btn-group-sm w-100">
                                        <button type="button" 
                                                class="btn btn-outline-primary btn-sm {{ $image->is_featured ? 'active' : '' }}"
                                                onclick="toggleFeatured({{ $image->id }})">
                                            <i class="fas fa-star"></i>
                                        </button>
                                        <button type="button" 
                                                class="btn btn-outline-danger btn-sm"
                                                onclick="deleteImage({{ $image->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

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
                        Yere ait fotoğrafları yüklemek için birden fazla resim seçebilirsiniz.
                    </small>
                </div>
                
                <div class="form-group">
                    <label for="images">Resimler</label>
                    <input type="file" 
                           class="form-control @error('images.*') is-invalid @enderror" 
                           id="images" 
                           name="images[]" 
                           multiple 
                           accept="image/*"
                           onchange="previewImages(this)">
                    @error('images.*')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">
                        JPG, PNG, GIF formatlarında, maksimum 5MB boyutunda resimler yükleyebilirsiniz.
                    </small>
                </div>
                
                <!-- Resim Önizleme -->
                <div id="image-preview" class="mt-3" style="display: none;">
                    <h6>Seçilen Resimler:</h6>
                    <div id="preview-container" class="d-flex flex-wrap gap-2">
                        <!-- Önizlemeler buraya gelecek -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .upload-dropzone {
        border: 2px dashed #3490dc;
        border-radius: 8px;
        background: #f8fafc;
        min-height: 150px;
        padding: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .upload-dropzone:hover,
    .upload-dropzone.dragover {
        border-color: #2779bd;
        background: #f1f7fe;
    }
    
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
    
    .image-preview {
        position: relative;
        display: inline-block;
        margin: 5px;
    }
    
    .image-preview img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 5px;
        border: 2px solid #ddd;
    }
    
    .image-preview .remove-image {
        position: absolute;
        top: -5px;
        right: -5px;
        background: #dc3545;
        color: white;
        border: none;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        font-size: 12px;
        cursor: pointer;
    }
</style>
@stop

@section('js')
<script>
// Resim önizleme fonksiyonu
function previewImages(input) {
    const previewDiv = document.getElementById('image-preview');
    const previewContainer = document.getElementById('preview-container');
    
    // Önceki önizlemeleri temizle
    previewContainer.innerHTML = '';
    
    if (input.files && input.files.length > 0) {
        previewDiv.style.display = 'block';
        
        Array.from(input.files).forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imageDiv = document.createElement('div');
                    imageDiv.className = 'image-preview-item position-relative d-inline-block me-2 mb-2';
                    imageDiv.innerHTML = `
                        <img src="${e.target.result}" 
                             class="img-thumbnail" 
                             style="width: 100px; height: 100px; object-fit: cover;">
                        <button type="button" 
                                class="btn btn-danger btn-sm position-absolute" 
                                style="top: -5px; right: -5px; width: 20px; height: 20px; padding: 0; border-radius: 50%; font-size: 10px;"
                                onclick="removePreviewImage(this, ${index})">
                            ×
                        </button>
                    `;
                    previewContainer.appendChild(imageDiv);
                };
                reader.readAsDataURL(file);
            }
        });
    } else {
        previewDiv.style.display = 'none';
    }
}

// Önizleme resmini kaldır
function removePreviewImage(button, index) {
    const input = document.getElementById('images');
    const dt = new DataTransfer();
    
    // Mevcut dosyaları al ve belirtilen index'i hariç tut
    Array.from(input.files).forEach((file, i) => {
        if (i !== index) {
            dt.items.add(file);
        }
    });
    
    // Input'u güncelle
    input.files = dt.files;
    
    // Önizlemeyi güncelle
    previewImages(input);
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

// Slug otomatik oluşturma
$(document).ready(function() {
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
</script>
@stop