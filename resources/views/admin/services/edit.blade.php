@extends('adminlte::page')

@section('title', 'Hizmet Düzenle')

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet">

<style>
    .validation-errors-summary {
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
        border-radius: 0.25rem;
        color: #721c24;
        padding: 0.75rem 1.25rem;
    }

    .validation-errors-summary h5 {
        font-size: 1.25rem;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
    }

    .validation-errors-summary h5 i {
        margin-right: 0.5rem;
    }

    .validation-errors-summary ul {
        margin-bottom: 0;
        padding-left: 1rem;
    }
    
    .status-option {
        position: relative;
        transition: all 0.2s ease;
    }
    
    .status-option.active {
        border-color: #3490dc !important;
        box-shadow: 0 0 0 0.2rem rgba(52, 144, 220, 0.25);
    }
    
    .status-option:hover {
        transform: translateY(-2px);
    }
    
    .status-card {
        flex: 1;
        max-width: 200px;
    }
    
    .compact .status-option {
        padding: 10px;
    }
    
    .compact .status-option .card-body {
        padding: 0.5rem;
    }
    
    .compact .status-option .card-title {
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
    }
    
    .compact .status-option .card-text {
        font-size: 0.8rem;
    }
    
    .tox-tinymce {
        border-radius: 0.25rem !important;
    }
    
    .category-badge {
        display: inline-flex;
        align-items: center;
        background-color: #e9ecef;
        border-radius: 0.25rem;
        padding: 0.25rem 0.5rem;
        margin-right: 0.5rem;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }
    
    .category-badge .remove-category, .category-badge .remove-tag {
        margin-left: 0.5rem;
        color: #dc3545;
        cursor: pointer;
    }
    
    .category-item {
        transition: all 0.2s ease;
    }
    
    .category-item:hover {
        background-color: #f8f9fa;
    }
    
    .category-list {
        padding: 0.5rem;
    }
    
    .help-tooltip {
        color: #6c757d;
        cursor: help;
        margin-left: 0.5rem;
    }
    
    .help-tooltip:hover {
        color: #495057;
    }
</style>
@stop

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-edit mr-2"></i>Hizmet Düzenle: {{ $service->title }}</h1>
        <div>
            <a href="{{ route('admin.services.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left mr-1"></i> Hizmet Listesine Dön
            </a>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <!-- Başarı mesajı gösterimi -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
    
    <!-- Hata mesajları gösterimi -->
    @if ($errors->any())
    <div class="validation-errors-summary mb-4">
        <h5><i class="fas fa-exclamation-circle"></i> Hata</h5>
        <p>Lütfen formu göndermeden önce aşağıdaki hataları düzeltin:</p>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    
                <form action="{{ route('admin.services.update', $service->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
        
        <div class="row">
            <!-- Sol Sütun - Ana İçerik -->
            <div class="col-lg-8">
                <!-- Temel Bilgiler -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5><i class="fas fa-info-circle mr-2"></i>Temel Bilgiler</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label for="title" class="form-label">Başlık <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $service->title) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                        
                        <!-- Slug ve URL Önizleme -->
                        <div class="mt-2 p-2 bg-light border rounded">
                            <div class="small text-muted mb-1">
                                <i class="fas fa-link me-1"></i> Oluşturulacak URL:
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="text-secondary">{{ url('/') }}/hizmetler/</span>
                                <span class="text-primary fw-bold" id="slug-preview">{{ old('slug', $service->slug) ?: '-' }}</span>
                            </div>
                            <div class="small text-muted mt-1">
                                <i class="fas fa-info-circle me-1"></i> Slug:
                            </div>
                            <div class="input-group mt-1">
                                <input type="text" class="form-control form-control-sm" id="slug" name="slug" value="{{ old('slug', $service->slug) }}" placeholder="Otomatik oluşturulur">
                                <button class="btn btn-sm btn-outline-secondary" type="button" id="slug-regenerate">
                                    <i class="fas fa-sync-alt"></i> Yenile
                                </button>
                                <a href="{{ url('/hizmetler/' . (old('slug', $service->slug) ?: '-')) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-external-link-alt"></i> Önizle
                                </a>
                            </div>
                            <small class="form-text text-muted mt-1">
                                Otomatik oluşturulan slug'ı düzenleyebilirsiniz. Boş bırakırsanız otomatik oluşturulacaktır.
                            </small>
                                </div>
                                </div>
                                
                    <div class="mb-4">
                        <label for="summary" class="form-label">Özet</label>
                        <textarea class="form-control @error('summary') is-invalid @enderror" id="summary" name="summary" rows="3" style="height: 100px;">{{ old('summary', $service->summary) }}</textarea>
                                    @error('summary')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                        <small class="text-muted">Hizmet listesinde ve sosyal medya paylaşımlarında görünecek kısa açıklama.</small>
                                </div>
                                
                    <div class="mb-4">
                        <label for="content" class="form-label">İçerik <span class="text-danger">*</span></label>
                        <textarea class="form-control tinymce-full @error('content') is-invalid @enderror" id="content" name="content" rows="10">{{ old('content', $service->content) }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                                </div>
                            </div>

            <!-- Hizmet Görseli -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Hizmet Görseli</h5>
                </div>
                <div class="card-body">
                    <!-- FileManagerSystem Görsel -->
                    <div class="mb-4">
                        <label for="filemanagersystem_image" class="form-label">Hizmet Görseli <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" class="form-control @error('image') is-invalid @enderror" id="filemanagersystem_image" name="image" value="{{ old('image', $service->image) }}">
                            <button type="button" class="btn btn-primary" id="filemanagersystem_image_button">
                                <i class="fas fa-image"></i> Görsel Seç
                            </button>
                        </div>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="filemanagersystem_image_preview" class="mt-2" style="display: {{ old('image', $service->image) ? 'block' : 'none' }};">
                            <img src="{{ old('image', $service->image) ? asset(str_replace('/storage/', '', old('image', $service->image))) : '' }}" alt="Önizleme" class="img-thumbnail">
                        </div>
                        <div class="alert alert-warning mt-2" id="image-warning">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            <strong>Not:</strong> Hizmet görsel alanı opsiyoneldir. İsterseniz bir görsel seçebilirsiniz.
                        </div>
                    </div>
                </div>
            </div>

            <!-- CTA Ayarları -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Çağrı Butonu Ayarları</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <label for="cta_text" class="form-label">Buton Metni</label>
                        <input type="text" class="form-control @error('cta_text') is-invalid @enderror" id="cta_text" name="cta_text" value="{{ old('cta_text', $service->cta_text) }}" placeholder="Örn: Hemen Başvur, Fiyat Teklifi Al">
                        @error('cta_text')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Detay sayfasında gösterilecek çağrı butonunun metni.</small>
                    </div>
                            
                    <div class="mb-4">
                        <label for="cta_url" class="form-label">Buton URL</label>
                        <input type="text" class="form-control @error('cta_url') is-invalid @enderror" id="cta_url" name="cta_url" value="{{ old('cta_url', $service->cta_url) }}" placeholder="Örn: /iletisim, https://example.com/form">
                        @error('cta_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Butona tıklandığında gidilecek sayfanın URL'i. Boş bırakılırsa iletişim sayfasına yönlendirir.</small>
                    </div>
                </div>
            </div>
                                
            <!-- SEO -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">SEO Ayarları</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <label for="meta_title" class="form-label">Meta Başlık</label>
                        <input type="text" class="form-control @error('meta_title') is-invalid @enderror" id="meta_title" name="meta_title" value="{{ old('meta_title', $service->meta_title) }}">
                        @error('meta_title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                        <small class="text-muted">Boş bırakılırsa hizmet başlığı kullanılacaktır.</small>
                    </div>
                    
                    <div class="mb-4">
                        <label for="meta_description" class="form-label">Meta Açıklama</label>
                        <textarea class="form-control @error('meta_description') is-invalid @enderror" id="meta_description" name="meta_description" rows="3">{{ old('meta_description', $service->meta_description) }}</textarea>
                        @error('meta_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                        <small class="text-muted">Boş bırakılırsa hizmet özeti kullanılacaktır.</small>
                                </div>
                                    </div>
                                </div>
                                
            <!-- Sistem Bilgileri -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Sistem Bilgileri</h5>
                        </div>
                                        <div class="card-body">
                                            <dl class="row">
                                                <dt class="col-sm-3">Oluşturulma Tarihi:</dt>
                                                <dd class="col-sm-9">{{ $service->created_at->format('d.m.Y H:i') }}</dd>
                                                
                                                <dt class="col-sm-3">Son Güncelleme:</dt>
                                                <dd class="col-sm-9">{{ $service->updated_at->format('d.m.Y H:i') }}</dd>
                                                
                                                <dt class="col-sm-3">Görüntülenme Sayısı:</dt>
                                                <dd class="col-sm-9">{{ $service->view_count ?? 0 }}</dd>
                                            </dl>
                                        </div>
            </div>
        </div>
            
            <!-- Sağ Sütun - Kontrol Paneli -->
            <div class="col-lg-4">
                <!-- Yayın Ayarları -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Yayın Ayarları</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label class="form-label d-flex align-items-center justify-content-between">
                                <span>Durum <span class="text-danger">*</span></span>
                                <input type="hidden" name="status" id="status-input" value="{{ old('status', $service->status) }}" required>
                                @error('status')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </label>
                            
                            <div class="status-selector d-flex gap-2 mt-2">
                                <div class="status-card compact flex-grow-1" data-status="published" style="max-width: 50%; margin: 0 auto;">
                                    <div class="card h-100 status-option {{ old('status', $service->status) == 'published' ? 'border-primary active' : 'border' }}" style="cursor: pointer; transition: all 0.3s ease; border-width: 3px !important; border-radius: 12px !important; overflow: hidden; position: relative; box-shadow: {{ old('status', $service->status) == 'published' ? '0 0 10px rgba(52,144,220,0.5)' : 'none' }}; background-color: {{ old('status', $service->status) == 'published' ? '#e6f2ff' : 'white' }};">
                                        
                                        @if(old('status', $service->status) == 'published')
                                        <div style="position: absolute; top: 0; left: 0; width: 8px; height: 100%; background-color: #3490dc;"></div>
                                        <div style="position: absolute; top: 5px; right: 6px; font-size: 14px; color: #3490dc; font-weight: bold;">✓</div>
                                        @endif
                                        
                                        <div class="card-body p-2 text-center">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <i class="fas fa-check-circle status-icon me-2" style="font-size: 1.2rem; color: {{ old('status', $service->status) == 'published' ? '#3490dc' : '#6c757d' }}; transition: all 0.3s ease;"></i>
                                                <span class="status-title" style="font-weight: {{ old('status', $service->status) == 'published' ? '700' : '500' }}; font-size: 0.9rem; color: {{ old('status', $service->status) == 'published' ? '#3490dc' : '#212529' }};">Yayında</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="status-card compact flex-grow-1" data-status="draft" style="max-width: 50%; margin: 0 auto;">
                                    <div class="card h-100 status-option {{ old('status', $service->status) == 'draft' ? 'border-primary active' : 'border' }}" style="cursor: pointer; transition: all 0.3s ease; border-width: 3px !important; border-radius: 12px !important; overflow: hidden; position: relative; box-shadow: {{ old('status', $service->status) == 'draft' ? '0 0 10px rgba(52,144,220,0.5)' : 'none' }}; background-color: {{ old('status', $service->status) == 'draft' ? '#e6f2ff' : 'white' }};">
                                        
                                        @if(old('status', $service->status) == 'draft')
                                        <div style="position: absolute; top: 0; left: 0; width: 8px; height: 100%; background-color: #3490dc;"></div>
                                        <div style="position: absolute; top: 5px; right: 6px; font-size: 14px; color: #3490dc; font-weight: bold;">✓</div>
                                        @endif
                                        
                                        <div class="card-body p-2 text-center">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <i class="fas fa-edit status-icon me-2" style="font-size: 1.2rem; color: {{ old('status', $service->status) == 'draft' ? '#3490dc' : '#6c757d' }}; transition: all 0.3s ease;"></i>
                                                <span class="status-title" style="font-weight: {{ old('status', $service->status) == 'draft' ? '700' : '500' }}; font-size: 0.9rem; color: {{ old('status', $service->status) == 'draft' ? '#3490dc' : '#212529' }};">Taslak</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="published_at" class="form-label">Yayın Tarihi</label>
                            <input type="text" class="form-control datepicker @error('published_at') is-invalid @enderror" id="published_at" name="published_at" value="{{ old('published_at', optional($service->published_at)->format('Y-m-d')) }}" autocomplete="off">
                            @error('published_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="end_date" class="form-label">Bitiş Tarihi</label>
                            <input type="text" class="form-control datepicker @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ old('end_date', optional($service->end_date)->format('Y-m-d')) }}" autocomplete="off">
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Belirtilirse, bu tarihte hizmet otomatik olarak yayından kaldırılır.</small>
                        </div>
                        
                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" {{ old('is_featured', $service->is_featured) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">
                                <strong>Öne Çıkar</strong>
                                <small class="d-block text-muted">Hizmet ana sayfada öne çıkarılacaktır.</small>
                            </label>
                        </div>
                        
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="is_headline" name="is_headline" {{ old('is_headline', $service->is_headline) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_headline">
                                <strong>Manşet</strong>
                                <small class="d-block text-muted">Hizmet manşet bölümünde gösterilecektir.</small>
                            </label>
                        </div>
                    </div>
                </div>
                
                <!-- Kategoriler -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Kategoriler ve Etiketler</h5>
                    </div>
                    <div class="card-body">
                        <!-- Kategoriler -->
                        <div class="card mb-3">
                            <div class="card-header bg-light d-flex align-items-center">
                                <i class="fas fa-folder-tree me-2 text-primary"></i>
                                <h5 class="mb-0">Kategoriler</h5>
                                <span class="badge bg-primary ms-2 category-count">0</span>
                            </div>
                            <div class="card-body">
                                <div class="category-wrapper">
                                    <!-- Arama ve Tümünü Seç -->
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="ms-auto">
                                            <button type="button" class="btn btn-light btn-sm border" id="select-all-categories" style="font-size: 0.8rem; padding: 0.25rem 0.5rem;">
                                                <i class="fas fa-check-square me-1"></i>Tümü
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Kategori Listesi -->
                                    <div class="category-list mb-3" style="max-height: 400px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 4px;">
                                        @foreach($categories as $category)
                                        <div class="category-item d-flex align-items-center py-1 px-2 mb-1 rounded hover-bg-light">
                                            <div class="form-check mb-0 w-100">
                                                <label class="d-flex align-items-center gap-1">
                                                    <input type="checkbox" name="categories[]" value="{{ $category->id }}" class="form-check-input me-1" {{ in_array($category->id, old('categories', $service->categories->pluck('id')->toArray())) ? 'checked' : '' }}>
                                                    @if(isset($category->icon))
                                                        <i class="{{ $category->icon }}" style="margin-right: 3px; font-size: 0.9em;"></i>
                                                    @endif
                                                    <span class="category-name">{{ $category->name }}</span>
                                                    @if(isset($category->description))
                                                        <i class="fas fa-info-circle ms-1 text-muted" style="font-size: 0.85em;" data-bs-toggle="tooltip" title="{{ $category->description }}"></i>
                                                    @endif
                                                </label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>

                                    <!-- Seçili Kategoriler -->
                                    <div class="selected-categories">
                                        <label class="form-label text-muted mb-2">
                                            <i class="fas fa-tags me-1"></i> Seçili Kategoriler
                                        </label>
                                        <div class="d-flex flex-wrap gap-2" id="selected-categories-list"></div>
                            </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Etiketler -->
                        <div class="card tag-card mb-3">
                            <div class="card-header bg-light d-flex align-items-center">
                                <i class="fas fa-tags me-2 text-primary"></i>
                                <h5 class="mb-0">Etiketler</h5>
                                <span class="badge bg-primary ms-2 tag-count">0</span>
                            </div>
                            <div class="card-body">
                                <div class="tag-wrapper">
                                    <!-- Ekleme Alanı -->
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="input-group">
                                            <input type="text" id="tag-input" class="form-control" placeholder="Etiket eklemek için yazın...">
                                            <button class="btn btn-outline-primary" type="button" id="add-tag-btn">
                                                <i class="fas fa-plus"></i> Ekle
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <!-- Etiket Açıklaması -->
                                    <div class="mb-3">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Birden fazla etiket eklemek için her eklemeden sonra "Ekle" butonuna tıklayın veya virgül ile ayırın.
                                        </small>
                                    </div>

                                    <!-- Seçili Etiketler -->
                                    <div class="selected-tags">
                                        <label class="form-label text-muted mb-2">
                                            <i class="fas fa-hashtag me-1"></i> Eklenen Etiketler
                                        </label>
                                        <div class="d-flex flex-wrap gap-2" id="selected-tags-list"></div>
                                        <input type="hidden" id="tags-input" name="tags" value="{{ old('tags', $service->tags->pluck('name')->implode(',')) }}">
                            </div>
                        </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">
                    <i class="fas fa-save mr-2"></i> Değişiklikleri Kaydet
                </button>
                
                <a href="{{ route('admin.services.index') }}" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-times mr-2"></i> İptal
                </a>
            </div>
        </div>
        
        <!-- Detay Sayfası İçeriği -->
        <div class="card">
            <div class="card-header">
                <h5>Detay Sayfası İçeriği</h5>
                <small class="text-muted">Hizmet detay sayfasında gösterilecek özel içerik bölümleri.</small>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Bu bölümdeki içerikler, hizmet detay sayfasında bulunan özel bölümlerde gösterilecektir. İçerikler zengin metin editörü ile düzenlenebilir.
                </div>
                
                <!-- Hizmetin Amacı -->
                <div class="mb-4">
                    <label for="service_purpose" class="form-label">Hizmetin Amacı</label>
                    <div class="d-flex justify-content-end mb-2">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="is_purpose_visible" name="details[is_purpose_visible]" value="1" {{ old('details.is_purpose_visible', isset($service->features['is_purpose_visible']) && $service->features['is_purpose_visible']) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_purpose_visible">
                                <span class="text-success" id="purpose_visible_text">Görünür</span>
                                <span class="text-danger d-none" id="purpose_hidden_text">Gizli</span>
                            </label>
                        </div>
                    </div>
                    <textarea class="form-control tinymce" id="service_purpose" name="details[service_purpose]" rows="5">{{ old('details.service_purpose', isset($service->features['service_purpose']) ? $service->features['service_purpose'] : '') }}</textarea>
                    <small class="form-text text-muted">Bu bölüm, hizmet detay sayfasındaki "Hizmetin Amacı" bölümünde gösterilecektir.</small>
                </div>
                
                <!-- Kimler Başvurabilir -->
                <div class="mb-4">
                    <label for="who_can_apply" class="form-label">Kimler Başvurabilir</label>
                    <div class="d-flex justify-content-end mb-2">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="is_who_can_apply_visible" name="details[is_who_can_apply_visible]" value="1" {{ old('details.is_who_can_apply_visible', isset($service->features['is_who_can_apply_visible']) && $service->features['is_who_can_apply_visible']) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_who_can_apply_visible">
                                <span class="text-success" id="who_can_apply_visible_text">Görünür</span>
                                <span class="text-danger d-none" id="who_can_apply_hidden_text">Gizli</span>
                            </label>
                        </div>
                    </div>
                    <textarea class="form-control tinymce" id="who_can_apply" name="details[who_can_apply]" rows="5">{{ old('details.who_can_apply', isset($service->features['who_can_apply']) ? $service->features['who_can_apply'] : '') }}</textarea>
                    <small class="form-text text-muted">Bu bölüm, hizmet detay sayfasındaki "Kimler Başvurabilir" bölümünde gösterilecektir.</small>
                </div>
                
                <!-- Başvuru Şartları -->
                <div class="mb-4">
                    <label for="requirements" class="form-label">Başvuru Şartları</label>
                    <div class="d-flex justify-content-end mb-2">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="is_requirements_visible" name="details[is_requirements_visible]" value="1" {{ old('details.is_requirements_visible', isset($service->features['is_requirements_visible']) && $service->features['is_requirements_visible']) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_requirements_visible">
                                <span class="text-success" id="requirements_visible_text">Görünür</span>
                                <span class="text-danger d-none" id="requirements_hidden_text">Gizli</span>
                            </label>
                        </div>
                    </div>
                    <textarea class="form-control tinymce" id="requirements" name="details[requirements]" rows="5">{{ old('details.requirements', isset($service->features['requirements']) ? $service->features['requirements'] : '') }}</textarea>
                    <small class="form-text text-muted">Bu bölüm, hizmet detay sayfasındaki "Başvuru Şartları" bölümünde gösterilecektir.</small>
                </div>
                
                <!-- Başvuru Süreci -->
                <div class="mb-4">
                    <label for="application_process" class="form-label">Başvuru Süreci</label>
                    <div class="d-flex justify-content-end mb-2">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="is_application_process_visible" name="details[is_application_process_visible]" value="1" {{ old('details.is_application_process_visible', isset($service->features['is_application_process_visible']) && $service->features['is_application_process_visible']) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_application_process_visible">
                                <span class="text-success" id="application_process_visible_text">Görünür</span>
                                <span class="text-danger d-none" id="application_process_hidden_text">Gizli</span>
                            </label>
                        </div>
                    </div>
                    <textarea class="form-control tinymce" id="application_process" name="details[application_process]" rows="5">{{ old('details.application_process', isset($service->features['application_process']) ? $service->features['application_process'] : '') }}</textarea>
                    <small class="form-text text-muted">Bu bölüm, hizmet detay sayfasındaki "Başvuru Süreci" bölümünde gösterilecektir.</small>
                </div>
                
                <!-- İşlem Süresi (Tablo) -->
                <div class="mb-4">
                    <label class="form-label d-flex justify-content-between align-items-center">
                        <span>İşlem Süresi Tablosu</span>
                        <div class="d-flex align-items-center">
                            <div class="form-check form-switch me-3">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_processing_times_visible" name="details[is_processing_times_visible]" value="1" {{ old('details.is_processing_times_visible', isset($service->features['is_processing_times_visible']) && $service->features['is_processing_times_visible']) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_processing_times_visible">
                                    <span class="text-success" id="processing_times_visible_text">Görünür</span>
                                    <span class="text-danger d-none" id="processing_times_hidden_text">Gizli</span>
                                </label>
                            </div>
                            <button type="button" class="btn btn-sm btn-primary" id="add-processing-time-row">
                                <i class="fas fa-plus"></i> Satır Ekle
                            </button>
                        </div>
                    </label>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered" id="processing-time-table">
                            <thead>
                                <tr>
                                    <th style="width: 30%">İşlem Adı</th>
                                    <th style="width: 20%">Süre</th>
                                    <th>Açıklama</th>
                                    <th style="width: 50px">İşlem</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(old('details.processing_times', isset($service->features['processing_times']) ? $service->features['processing_times'] : []))
                                    @foreach(old('details.processing_times', isset($service->features['processing_times']) ? $service->features['processing_times'] : []) as $index => $time)
                                    <tr>
                                        <td>
                                            <input type="text" class="form-control" name="details[processing_times][{{ $index }}][title]" value="{{ is_array($time) && isset($time['title']) ? $time['title'] : '' }}" placeholder="Başlık">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="details[processing_times][{{ $index }}][time]" value="{{ is_array($time) && isset($time['time']) ? $time['time'] : '' }}" placeholder="İşlem süresi">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="details[processing_times][{{ $index }}][description]" value="{{ is_array($time) && isset($time['description']) ? $time['description'] : '' }}" placeholder="Açıklama">
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-danger remove-row">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                <tr>
                                    <td>
                                        <input type="text" class="form-control" name="details[processing_times][0][title]" placeholder="Başlık">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="details[processing_times][0][time]" placeholder="İşlem süresi">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="details[processing_times][0][description]" placeholder="Açıklama">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger remove-row">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <small class="form-text text-muted">Bu tablo, hizmet detay sayfasındaki "İşlem Süresi" bölümünde gösterilecektir.</small>
                </div>
                
                <!-- Ücretler (Tablo) -->
                <div class="mb-4">
                    <label class="form-label d-flex justify-content-between align-items-center">
                        <span>Ücretler Tablosu</span>
                        <div class="d-flex align-items-center">
                            <div class="form-check form-switch me-3">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_fees_visible" name="details[is_fees_visible]" value="1" {{ old('details.is_fees_visible', isset($service->features['is_fees_visible']) && $service->features['is_fees_visible']) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_fees_visible">
                                    <span class="text-success" id="fees_visible_text">Görünür</span>
                                    <span class="text-danger d-none" id="fees_hidden_text">Gizli</span>
                                </label>
                            </div>
                            <button type="button" class="btn btn-sm btn-primary" id="add-fee-row">
                                <i class="fas fa-plus"></i> Satır Ekle
                            </button>
                        </div>
                    </label>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered" id="fees-table">
                            <thead>
                                <tr>
                                    <th style="width: 35%">Hizmet Paketi</th>
                                    <th>Açıklama</th>
                                    <th style="width: 20%">Fiyat</th>
                                    <th style="width: 50px">İşlem</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(old('details.fees', isset($service->features['fees']) ? $service->features['fees'] : []))
                                    @foreach(old('details.fees', $service->features['fees']) as $index => $fee)
                                    <tr>
                                        <td>
                                            <input type="text" class="form-control" name="details[fees][{{ $index }}][package]" value="{{ is_array($fee) && array_key_exists('package', $fee) ? $fee['package'] : '' }}" placeholder="Paket adı">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="details[fees][{{ $index }}][description]" value="{{ is_array($fee) && array_key_exists('description', $fee) ? $fee['description'] : '' }}" placeholder="Açıklama">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="details[fees][{{ $index }}][price]" value="{{ is_array($fee) && array_key_exists('price', $fee) ? $fee['price'] : '' }}" placeholder="Fiyat">
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-danger remove-row">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                <tr>
                                    <td>
                                        <input type="text" class="form-control" name="details[fees][0][package]" placeholder="Paket adı">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="details[fees][0][description]" placeholder="Açıklama">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="details[fees][0][price]" placeholder="Fiyat">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger remove-row">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <small class="form-text text-muted">Bu tablo, hizmet detay sayfasındaki "Ücretler" bölümünde gösterilecektir.</small>
                </div>
                
                <!-- Ödeme Seçenekleri (Tablo) -->
                <div class="mb-4">
                    <label class="form-label d-flex justify-content-between align-items-center">
                        <span>Ödeme Seçenekleri Tablosu</span>
                        <button type="button" class="btn btn-sm btn-primary" id="add-payment-option-row">
                            <i class="fas fa-plus"></i> Seçenek Ekle
                        </button>
                    </label>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered" id="payment-options-table">
                            <thead>
                                <tr>
                                    <th style="width: 25%">Ödeme Yöntemi</th>
                                    <th style="width: 20%">Vade</th>
                                    <th>Açıklama</th>
                                    <th style="width: 50px">İşlem</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(old('details.payment_options', isset($service->features['payment_options']) ? $service->features['payment_options'] : []))
                                    @foreach(old('details.payment_options', isset($service->features['payment_options']) ? $service->features['payment_options'] : []) as $index => $option)
                                    <tr>
                                        <td>
                                            <input type="text" class="form-control" name="details[payment_options][{{ $index }}][method]" value="{{ is_array($option) && array_key_exists('method', $option) ? $option['method'] : '' }}" placeholder="Ödeme yöntemi">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="details[payment_options][{{ $index }}][term]" value="{{ is_array($option) && array_key_exists('term', $option) ? $option['term'] : '' }}" placeholder="Vade">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="details[payment_options][{{ $index }}][description]" value="{{ is_array($option) && array_key_exists('description', $option) ? $option['description'] : '' }}" placeholder="Açıklama">
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-danger remove-row">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                <tr>
                                    <td>
                                        <input type="text" class="form-control" name="details[payment_options][0][method]" placeholder="Ödeme yöntemi">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="details[payment_options][0][term]" placeholder="Vade">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="details[payment_options][0][description]" placeholder="Açıklama">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger remove-row">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <small class="form-text text-muted">Bu tablo, hizmet detay sayfasındaki "Ödeme Seçenekleri" bölümünde gösterilecektir.</small>
                </div>
                
                <!-- Diğer Bilgiler -->
                <div class="mb-4">
                    <label for="additional_info" class="form-label">Diğer Bilgiler</label>
                    <div class="d-flex justify-content-end mb-2">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="is_additional_info_visible" name="details[is_additional_info_visible]" value="1" {{ old('details.is_additional_info_visible', isset($service->features['is_additional_info_visible']) && $service->features['is_additional_info_visible']) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_additional_info_visible">
                                <span class="text-success" id="additional_info_visible_text">Görünür</span>
                                <span class="text-danger d-none" id="additional_info_hidden_text">Gizli</span>
                            </label>
                        </div>
                    </div>
                    <textarea class="form-control tinymce" id="additional_info" name="details[additional_info]" rows="5">{{ old('details.additional_info', isset($service->features['additional_info']) ? $service->features['additional_info'] : '') }}</textarea>
                    <small class="form-text text-muted">Bu bölüm, hizmet detay sayfasındaki "Diğer Bilgiler" bölümünde gösterilecektir.</small>
                </div>
                
                <!-- Standart Formlar -->
                <div class="mb-4">
                    <label for="standard_forms" class="form-label">Standart Formlar</label>
                    <div class="d-flex justify-content-end mb-2">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="is_standard_forms_visible" name="details[is_standard_forms_visible]" value="1" {{ old('details.is_standard_forms_visible', isset($service->features['is_standard_forms_visible']) && $service->features['is_standard_forms_visible']) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_standard_forms_visible">
                                <span class="text-success" id="standard_forms_visible_text">Görünür</span>
                                <span class="text-danger d-none" id="standard_forms_hidden_text">Gizli</span>
                            </label>
                        </div>
                    </div>
                    <textarea class="form-control tinymce" id="standard_forms" name="details[standard_forms]" rows="5">{{ old('details.standard_forms', isset($service->features['standard_forms']) ? $service->features['standard_forms'] : '') }}</textarea>
                    <small class="form-text text-muted">Bu bölüm, hizmet detay sayfasındaki "Standart Formlar" bölümünde gösterilecektir.</small>
                    
                    <!-- Dosya Yükleme Bölümü -->
                    <div class="mt-4">
                        <label class="form-label d-flex justify-content-between align-items-center">
                            <span>Dosyalar</span>
                            <button type="button" class="btn btn-sm btn-primary" id="add-document-row">
                                <i class="fas fa-plus"></i> Dosya Ekle
                            </button>
                        </label>
                        
                        <div class="table-responsive">
                            <table class="table table-bordered" id="documents-table">
                                <thead>
                                    <tr>
                                        <th style="width: 25%">Dosya Adı</th>
                                        <th>Açıklama</th>
                                        <th style="width: 20%">Dosya</th>
                                        <th style="width: 50px">İşlem</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(old('documents', isset($service->features['documents']) ? $service->features['documents'] : []))
                                        @foreach(old('documents', $service->features['documents']) as $index => $document)
                                        <tr>
                                            <td>
                                                <input type="text" class="form-control" name="documents[{{ $index }}][name]" value="{{ is_array($document) && array_key_exists('name', $document) ? $document['name'] : '' }}" placeholder="Dosya adı">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="documents[{{ $index }}][description]" value="{{ is_array($document) && array_key_exists('description', $document) ? $document['description'] : '' }}" placeholder="Açıklama">
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <input type="text" class="form-control document-file-input" name="documents[{{ $index }}][file]" value="{{ is_array($document) && array_key_exists('file', $document) ? $document['file'] : '' }}" readonly>
                                                    <button type="button" class="btn btn-primary file-browser" data-index="{{ $index }}">
                                                        <i class="fas fa-file"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-danger remove-row">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @else
                                    <tr>
                                        <td>
                                            <input type="text" class="form-control" name="documents[0][name]" placeholder="Dosya adı">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="documents[0][description]" placeholder="Açıklama">
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <input type="text" class="form-control document-file-input" name="documents[0][file]" readonly>
                                                <button type="button" class="btn btn-primary file-browser" data-index="0">
                                                    <i class="fas fa-file"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-danger remove-row">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <small class="form-text text-muted">Buraya ekleyeceğiniz dosyalar, hizmet detay sayfasındaki "Standart Formlar" bölümünde indirilebilir olarak gösterilecektir.</small>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Diğer Meta Bilgileri -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">İlave Alanlar</h5>
    </div>
    <div class="card-body">
        <div class="mb-4">
            <label for="meta_keywords" class="form-label">Meta Anahtar Kelimeler</label>
            <input type="text" class="form-control @error('meta_keywords') is-invalid @enderror" id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords', $service->meta_keywords) }}">
            @error('meta_keywords')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="text-muted">Virgülle ayırarak giriniz</small>
        </div>
    </div>
        </div>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input@1.3.4/dist/bs-custom-file-input.min.js"></script>
<script src="{{ asset('vendor/tinymce/tinymce.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.tr.min.js"></script>

<script>
    $(document).ready(function() {
        // TinyMCE editörü yükleme
        tinymce.init({
            selector: '.tinymce-full',
            license_key: 'gpl',
            height: 500,
            language: null, // Dil desteğini kaldırdık
            menubar: 'file edit view insert format tools table help',
            plugins: 'preview importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons',
            toolbar: 'undo redo | bold italic underline strikethrough | fontfamily image fontsize blocks | alignleft aligncenter alignright alignjustify | outdent indent | numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen preview save print | insertfile media link anchor codesample | ltr rtl',
            toolbar_sticky: true,
            image_advtab: false,
            branding: false,
            promotion: false,
            image_caption: true,
            quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
            noneditable_noneditable_class: 'mceNonEditable',
            toolbar_mode: 'sliding',
            contextmenu: 'link image table',
            browser_spellcheck: true,
            skin: 'oxide',
            content_css: 'default',
            content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 16px; }',
            relative_urls: false,
            remove_script_host: false,
            convert_urls: false,
            file_picker_callback: function (callback, value, meta) {
                openFileManagerSystemPicker(callback);
            }
        });
        
        // Select2 initalization
        $('.select2').select2({
            theme: 'bootstrap-5',
            width: '100%'
        });
        
        // Date picker initialization
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true,
            language: 'tr'
        });

        // Dinamik tablolar için script
        console.log('Dinamik tablolar script yüklendi');
        
        // TinyMCE basit editör yapılandırması (.tinymce sınıfı için)
        tinymce.init({
            selector: '.tinymce',
            height: 200,
            menubar: false,
            plugins: 'lists link image table code',
            toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright | bullist numlist | link image | table | code',
            content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; }',
            language: 'tr',
            language_url: '/js/tinymce/langs/tr.js',
            branding: false,
            promotion: false,
            skin: 'oxide',
            relative_urls: false,
            remove_script_host: false,
            convert_urls: true,
            object_resizing: 'img',
            paste_data_images: true,
            automatic_uploads: false,
            
            // Varsayılan dosya seçici fonksiyonu override
            file_picker_callback: function (callback, value, meta) {
                // Resim ekleme işlemleri için FileManagerSystem kullanılacak
                if (meta.filetype === 'image') {
                    // Geçici bir ID oluştur
                    const tempId = Date.now();
                    const relatedType = 'service_details';
                    
                    // MediaPicker URL
                    const mediapickerUrl = '/admin/filemanagersystem/mediapicker?type=image&filter=all&related_type=' + relatedType + '&related_id=' + tempId;
                    
                    // iFrame'i güncelle
                    $('#mediapickerFrame').attr('src', mediapickerUrl);
                    
                    // Modal'ı göster - Bootstrap 5 ile uyumlu
                    var modal = new bootstrap.Modal(document.getElementById('mediapickerModal'));
                    modal.show();
                    
                    // Mesaj dinleme işlevi
                    function handleFilePickerSelection(event) {
                        try {
                            if (event.data && event.data.type === 'mediaSelected') {
                                let mediaUrl = '';
                                let altText = event.data.mediaAlt || '';
                                
                                // URL değerini al
                                if (event.data.mediaUrl) {
                                    mediaUrl = event.data.mediaUrl;
                                    
                                    // URL çevirme
                                    if (mediaUrl && mediaUrl.startsWith('/')) {
                                        const baseUrl = window.location.protocol + '//' + window.location.host;
                                        mediaUrl = baseUrl + mediaUrl;
                                    }
                                } else if (event.data.mediaId) {
                                    // ID ile kullan
                                    const previewUrl = '/admin/filemanagersystem/media/preview/' + event.data.mediaId;
                                    mediaUrl = previewUrl;
                                }
                                
                                if (mediaUrl) {
                                    // Callback'e URL'yi ve alt metni ilet
                                    callback(mediaUrl, { alt: altText });
                                    
                                    // Modalı kapat
                                    modal.hide();
                                    
                                    // Event listener'ı kaldır
                                    window.removeEventListener('message', handleFilePickerSelection);
                                }
                            } else if (event.data && event.data.type === 'mediapickerError') {
                                console.error('FileManagerSystem hatası:', event.data.message);
                                alert('Medya seçici hatası: ' + event.data.message);
                                modal.hide();
                                
                                window.removeEventListener('message', handleFilePickerSelection);
                            }
                        } catch (error) {
                            console.error('Medya seçimi işlenirken hata oluştu:', error);
                            alert('Medya seçimi işlenirken bir hata oluştu.');
                            
                            window.removeEventListener('message', handleFilePickerSelection);
                        }
                    }
                    
                    // Event listener ekle
                    window.removeEventListener('message', handleFilePickerSelection);
                    window.addEventListener('message', handleFilePickerSelection);
                    
                    return false;
                }
                
                // Diğer dosya tipleri için standart dosya seçiciyi kullan
                if (meta.filetype === 'file' || meta.filetype === 'media') {
                    window.open('/filemanager/dialog.php?type=' + meta.filetype + '&field_id=tinymce-file', 'filemanager', 'width=900,height=600');
                    window.SetUrl = function (url, width, height, alt) {
                        callback(url, {alt: alt});
                    };
                }
            }
        });
        
        // İşlem Süresi dinamik tablo
        $('#add-processing-time-row').on('click', function() {
            const tableBody = $('#processing-time-table tbody');
            const rowCount = tableBody.find('tr').length;
            
            const newRow = `
                <tr>
                    <td>
                        <input type="text" class="form-control" name="details[processing_times][${rowCount}][title]" placeholder="Başlık">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="details[processing_times][${rowCount}][time]" placeholder="İşlem süresi">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="details[processing_times][${rowCount}][description]" placeholder="Açıklama">
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger remove-row">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            
            tableBody.append(newRow);
        });
        
        // Ücretler dinamik tablo
        $('#add-fee-row').on('click', function() {
            const tableBody = $('#fees-table tbody');
            const rowCount = tableBody.find('tr').length;
            
            const newRow = `
                <tr>
                    <td>
                        <input type="text" class="form-control" name="details[fees][${rowCount}][package]" placeholder="Paket adı">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="details[fees][${rowCount}][description]" placeholder="Açıklama">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="details[fees][${rowCount}][price]" placeholder="Fiyat">
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger remove-row">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            
            tableBody.append(newRow);
        });
        
        // Ödeme Seçenekleri dinamik tablo
        $('#add-payment-option-row').on('click', function() {
            const tableBody = $('#payment-options-table tbody');
            const rowCount = tableBody.find('tr').length;
            
            const newRow = `
                <tr>
                    <td>
                        <input type="text" class="form-control" name="details[payment_options][${rowCount}][method]" placeholder="Ödeme yöntemi">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="details[payment_options][${rowCount}][term]" placeholder="Vade">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="details[payment_options][${rowCount}][description]" placeholder="Açıklama">
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger remove-row">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            
            tableBody.append(newRow);
        });
        
        // Satır silme işlemi (tüm tablolar için)
        $(document).on('click', '.remove-row', function() {
            // Eğer tabloda sadece bir satır kaldıysa silmeyi engelle
            const tableBody = $(this).closest('tbody');
            if (tableBody.find('tr').length > 1) {
                $(this).closest('tr').remove();
            } else {
                // Tabloyu boşalt
                tableBody.find('input').val('');
            }
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Slug oluşturma fonksiyonu
    function createSlug(text) {
        return text
            .toString()
            .toLowerCase()
            .trim()
            .replace(/\s+/g, '-')           // Boşlukları tire ile değiştir
            .replace(/[ğ]/g, 'g')           // Türkçe karakterleri değiştir
            .replace(/[ç]/g, 'c')
            .replace(/[ş]/g, 's')
            .replace(/[ı]/g, 'i')
            .replace(/[ö]/g, 'o')
            .replace(/[ü]/g, 'u')
            .replace(/[^a-z0-9\-]/g, '')    // Alfanümerik ve tire dışındaki karakterleri kaldır
            .replace(/\-\-+/g, '-')         // Birden fazla tireyi tek tireye dönüştür
            .replace(/^-+/, '')             // Baştaki tireleri kaldır
            .replace(/-+$/, '');            // Sondaki tireleri kaldır
    }
    
    // Başlık değiştiğinde slug'ı güncelle
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');
    const slugPreview = document.getElementById('slug-preview');
    const slugRegenerateBtn = document.getElementById('slug-regenerate');
    
    if (titleInput && slugInput && slugPreview) {
        titleInput.addEventListener('input', function() {
            const title = this.value;
            const slug = createSlug(title);
            
            if (title.trim() === '') {
                slugPreview.textContent = '-';
                slugInput.value = '';
            } else {
                slugPreview.textContent = slug;
                // Eğer kullanıcı slug'ı manuel olarak değiştirmediyse otomatik güncelle
                if (!slugInput.dataset.manuallyChanged || slugInput.dataset.manuallyChanged === "false") {
                    slugInput.value = slug;
                }
            }
        });
        
        // Slug input değişimini takip et
        slugInput.addEventListener('input', function() {
            // Manuel değişiklik yapıldığını işaretle
            this.dataset.manuallyChanged = "true";
            // Önizlemeyi güncelle
            slugPreview.textContent = this.value || '-';
        });
        
        // Slug'ı başlıktan yeniden oluşturmak için buton
        if (slugRegenerateBtn) {
            slugRegenerateBtn.addEventListener('click', function() {
                const title = titleInput.value;
                if (title.trim() !== '') {
                    const slug = createSlug(title);
                    slugInput.value = slug;
                    slugPreview.textContent = slug;
                    // Manuel değişiklik bayrağını sıfırla
                    slugInput.dataset.manuallyChanged = "false";
                }
            });
        }
    }
    
    // Durum seçim kartları işlevselliği
    const statusOptions = document.querySelectorAll('.status-option');
    const statusInput = document.getElementById('status-input');
    
    if (statusOptions.length && statusInput) {
        statusOptions.forEach(option => {
            option.addEventListener('click', function() {
                const statusCard = this.closest('.status-card');
                const selectedStatus = statusCard.dataset.status;
                
                // Hidden input değerini güncelle
                statusInput.value = selectedStatus;
                
                // Tüm kartları sıfırla
                statusOptions.forEach(opt => {
                    // Kart stilini sıfırla
                    opt.classList.remove('active', 'border-primary');
                    opt.classList.add('border');
                    opt.style.backgroundColor = 'white';
                    opt.style.boxShadow = 'none';
                    
                    // Vurgu çizgisini kaldır
                    const line = opt.querySelector('div[style*="position: absolute; top: 0; left: 0"]');
                    if (line) line.remove();
                    
                    // Onay işaretini kaldır
                    const check = opt.querySelector('div[style*="position: absolute; top: 5px; right: 6px"]');
                    if (check) check.remove();
                    
                    // İkonu normal renge getir
                    const icon = opt.querySelector('.status-icon');
                    if (icon) icon.style.color = '#6c757d';
                    
                    // Metni normal stile getir
                    const title = opt.querySelector('.status-title');
                    if (title) {
                        title.style.fontWeight = '500';
                        title.style.color = '#212529';
                    }
                });
                
                // Seçilen kartı aktif yap
                this.classList.add('active', 'border-primary');
                this.classList.remove('border');
                this.style.backgroundColor = '#e6f2ff';
                this.style.boxShadow = '0 0 10px rgba(52,144,220,0.5)';
                
                // Sol kenar çizgisi ekle
                const leftLine = document.createElement('div');
                leftLine.style.position = 'absolute';
                leftLine.style.top = '0';
                leftLine.style.left = '0';
                leftLine.style.width = '8px';
                leftLine.style.height = '100%';
                leftLine.style.backgroundColor = '#3490dc';
                this.appendChild(leftLine);
                
                // Onay işareti ekle
                const checkMark = document.createElement('div');
                checkMark.style.position = 'absolute';
                checkMark.style.top = '5px';
                checkMark.style.right = '6px';
                checkMark.style.fontSize = '14px';
                checkMark.style.color = '#3490dc';
                checkMark.style.fontWeight = 'bold';
                checkMark.innerHTML = '✓';
                this.appendChild(checkMark);
                
                // İkonu vurgula
                const selectedIcon = this.querySelector('.status-icon');
                if (selectedIcon) {
                    selectedIcon.style.color = '#3490dc';
                    selectedIcon.style.transform = 'scale(1.2)';
                }
                
                // Başlığı vurgula
                const selectedTitle = this.querySelector('.status-title');
                if (selectedTitle) {
                    selectedTitle.style.fontWeight = '700';
                    selectedTitle.style.color = '#3490dc';
                }
            });
        });
    }
    });
</script>

<script>
    // Görsel URL'lerini rölatif hale getir (http://localhost:8000 kısmını kaldır)
    function makeRelativeUrl(url) {
        // Boş URL kontrolü
        if (!url) return url;
        
        // URL zaten rölatif ise ve doğru formatta ise değiştirme
        if (url.startsWith('/') && !url.startsWith('//')) {
            // Yinelenen /storage/ yolunu düzelt
            if (url.includes('/storage//storage/')) {
                return url.replace('/storage//storage/', '/storage/');
            }
            if (url.includes('/storage/storage/')) {
                return url.replace('/storage/storage/', '/storage/');
            }
            return url;
        }
        
        // URL tam ise (http veya https ile başlıyorsa), rölatif yap
        try {
            const urlObj = new URL(url);
            let pathname = urlObj.pathname;
            
            // URL'deki yinelenen /storage/ yolunu düzelt
            if (pathname.includes('/storage//storage/')) {
                pathname = pathname.replace('/storage//storage/', '/storage/');
            }
            if (pathname.includes('/storage/storage/')) {
                pathname = pathname.replace('/storage/storage/', '/storage/');
            }
            
            return pathname;
        } catch (e) {
            // Eğer geçerli bir URL değilse olduğu gibi döndür
            console.error('URL parsing failed:', e, url);
            return url;
        }
    }
    
    $(document).ready(function() {
        console.log('Dosya işlemleri script yüklendi');
        
        // Galeri filemanager değişikliğini dinleme
        $('#fake-gallery-input').on('change', function() {
            // Seçilen görseli al
            var url = $(this).val();
            if (url) {
                // URL'yi rölatif yola dönüştür
                url = makeRelativeUrl(url);
                addGalleryItem(url);
                $(this).val(''); // input'u temizle
            }
        });
        
        // Image inputu değiştiğinde rölatif yola dönüştür
        $('#image').on('change', function() {
            const url = $(this).val();
            if (url) {
                // URL'yi rölatif hale getir
                $(this).val(makeRelativeUrl(url));
            }
        });
        
        // Ana görsel URL'sini düzelt
        if ($('#image').val()) {
            const cleanUrl = makeRelativeUrl($('#image').val());
            $('#image').val(cleanUrl);
            $('#image-preview').find('img').attr('src', cleanUrl);
        }
        
        // Galeri görsel URL'lerini düzelt
        $('#gallery-preview input[name="gallery[]"]').each(function() {
            const cleanUrl = makeRelativeUrl($(this).val());
            $(this).val(cleanUrl);
            $(this).closest('.gallery-item').find('img').attr('src', cleanUrl);
        });
    });
</script>

<script>
    // Dinamik tablo fonksiyonları
    $(document).ready(function() {
        console.log('Dinamik tablolar script yüklendi');
        
        // İşlem Süresi dinamik tablo
        $('#add-processing-time-row').on('click', function() {
            console.log('İşlem süresi satır ekleme butonu tıklandı');
            const tableBody = $('#processing-time-table tbody');
            const rowCount = tableBody.find('tr').length;
            
            const newRow = `
                <tr>
                    <td>
                        <input type="text" class="form-control" name="details[processing_times][${rowCount}][title]" placeholder="Başlık">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="details[processing_times][${rowCount}][time]" placeholder="İşlem süresi">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="details[processing_times][${rowCount}][description]" placeholder="Açıklama">
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger remove-row">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            
            tableBody.append(newRow);
        });
        
        // Ücretler dinamik tablo
        $('#add-fee-row').on('click', function() {
            console.log('Ücretler satır ekleme butonu tıklandı');
            const tableBody = $('#fees-table tbody');
            const rowCount = tableBody.find('tr').length;
            
            const newRow = `
                <tr>
                    <td>
                        <input type="text" class="form-control" name="details[fees][${rowCount}][package]" placeholder="Paket adı">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="details[fees][${rowCount}][description]" placeholder="Açıklama">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="details[fees][${rowCount}][price]" placeholder="Fiyat">
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger remove-row">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            
            tableBody.append(newRow);
        });
        
        // Ödeme Seçenekleri dinamik tablo
        $('#add-payment-option-row').on('click', function() {
            console.log('Ödeme seçenekleri satır ekleme butonu tıklandı');
            const tableBody = $('#payment-options-table tbody');
            const rowCount = tableBody.find('tr').length;
            
            const newRow = `
                <tr>
                    <td>
                        <input type="text" class="form-control" name="details[payment_options][${rowCount}][method]" placeholder="Ödeme yöntemi">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="details[payment_options][${rowCount}][term]" placeholder="Vade">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="details[payment_options][${rowCount}][description]" placeholder="Açıklama">
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger remove-row">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            
            tableBody.append(newRow);
        });
        
        // Satır silme işlemi (tüm tablolar için)
        $(document).on('click', '.remove-row', function() {
            console.log('Satır silme butonu tıklandı');
            // Eğer tabloda sadece bir satır kaldıysa silmeyi engelle
            const tableBody = $(this).closest('tbody');
            if (tableBody.find('tr').length > 1) {
                $(this).closest('tr').remove();
            } else {
                // Tabloyu boşalt
                tableBody.find('input').val('');
            }
        });
        
        // Hizmetin Amacı bölümü için aktif/pasif düğmesinin durumunu güncelle
        function updatePurposeVisibilityStatus() {
            if ($('#is_purpose_visible').is(':checked')) {
                $('#purpose_visible_text').removeClass('d-none');
                $('#purpose_hidden_text').addClass('d-none');
            } else {
                $('#purpose_visible_text').addClass('d-none');
                $('#purpose_hidden_text').removeClass('d-none');
            }
        }
        
        // Sayfa yüklendiğinde durumu ayarla
        updatePurposeVisibilityStatus();
        
        // Durum değiştiğinde güncelle
        $('#is_purpose_visible').on('change', function() {
            updatePurposeVisibilityStatus();
        });
        
        // Kimler Başvurabilir bölümü için aktif/pasif düğmesinin durumunu güncelle
        function updateWhoCanApplyVisibilityStatus() {
            if ($('#is_who_can_apply_visible').is(':checked')) {
                $('#who_can_apply_visible_text').removeClass('d-none');
                $('#who_can_apply_hidden_text').addClass('d-none');
            } else {
                $('#who_can_apply_visible_text').addClass('d-none');
                $('#who_can_apply_hidden_text').removeClass('d-none');
            }
        }
        
        // Sayfa yüklendiğinde Kimler Başvurabilir durumunu ayarla
        updateWhoCanApplyVisibilityStatus();
        
        // Kimler Başvurabilir durum değiştiğinde güncelle
        $('#is_who_can_apply_visible').on('change', function() {
            updateWhoCanApplyVisibilityStatus();
        });
        
        // Başvuru Şartları bölümü için aktif/pasif düğmesinin durumunu güncelle
        function updateRequirementsVisibilityStatus() {
            if ($('#is_requirements_visible').is(':checked')) {
                $('#requirements_visible_text').removeClass('d-none');
                $('#requirements_hidden_text').addClass('d-none');
            } else {
                $('#requirements_visible_text').addClass('d-none');
                $('#requirements_hidden_text').removeClass('d-none');
            }
        }
        
        // Sayfa yüklendiğinde Başvuru Şartları durumunu ayarla
        updateRequirementsVisibilityStatus();
        
        // Başvuru Şartları durum değiştiğinde güncelle
        $('#is_requirements_visible').on('change', function() {
            updateRequirementsVisibilityStatus();
        });
        
        // Başvuru Süreci bölümü için aktif/pasif düğmesinin durumunu güncelle
        function updateApplicationProcessVisibilityStatus() {
            if ($('#is_application_process_visible').is(':checked')) {
                $('#application_process_visible_text').removeClass('d-none');
                $('#application_process_hidden_text').addClass('d-none');
            } else {
                $('#application_process_visible_text').addClass('d-none');
                $('#application_process_hidden_text').removeClass('d-none');
            }
        }
        
        // Sayfa yüklendiğinde Başvuru Süreci durumunu ayarla
        updateApplicationProcessVisibilityStatus();
        
        // Başvuru Süreci durum değiştiğinde güncelle
        $('#is_application_process_visible').on('change', function() {
            updateApplicationProcessVisibilityStatus();
        });
        
        // İşlem Süresi Tablosu bölümü için aktif/pasif düğmesinin durumunu güncelle
        function updateProcessingTimesVisibilityStatus() {
            if ($('#is_processing_times_visible').is(':checked')) {
                $('#processing_times_visible_text').removeClass('d-none');
                $('#processing_times_hidden_text').addClass('d-none');
            } else {
                $('#processing_times_visible_text').addClass('d-none');
                $('#processing_times_hidden_text').removeClass('d-none');
            }
        }
        
        // Sayfa yüklendiğinde İşlem Süresi durumunu ayarla
        updateProcessingTimesVisibilityStatus();
        
        // İşlem Süresi durum değiştiğinde güncelle
        $('#is_processing_times_visible').on('change', function() {
            updateProcessingTimesVisibilityStatus();
        });
        
        // Ücretler Tablosu bölümü için aktif/pasif düğmesinin durumunu güncelle
        function updateFeesVisibilityStatus() {
            if ($('#is_fees_visible').is(':checked')) {
                $('#fees_visible_text').removeClass('d-none');
                $('#fees_hidden_text').addClass('d-none');
            } else {
                $('#fees_visible_text').addClass('d-none');
                $('#fees_hidden_text').removeClass('d-none');
            }
        }
        
        // Sayfa yüklendiğinde Ücretler durumunu ayarla
        updateFeesVisibilityStatus();
        
        // Ücretler durum değiştiğinde güncelle
        $('#is_fees_visible').on('change', function() {
            updateFeesVisibilityStatus();
        });
        
        // Diğer Bilgiler bölümü için aktif/pasif düğmesinin durumunu güncelle
        function updateAdditionalInfoVisibilityStatus() {
            if ($('#is_additional_info_visible').is(':checked')) {
                $('#additional_info_visible_text').removeClass('d-none');
                $('#additional_info_hidden_text').addClass('d-none');
            } else {
                $('#additional_info_visible_text').addClass('d-none');
                $('#additional_info_hidden_text').removeClass('d-none');
            }
        }
        
        // Sayfa yüklendiğinde Diğer Bilgiler durumunu ayarla
        updateAdditionalInfoVisibilityStatus();
        
        // Diğer Bilgiler durum değiştiğinde güncelle
        $('#is_additional_info_visible').on('change', function() {
            updateAdditionalInfoVisibilityStatus();
        });
        
        // Standart Formlar bölümü için aktif/pasif düğmesinin durumunu güncelle
        function updateStandardFormsVisibilityStatus() {
            if ($('#is_standard_forms_visible').is(':checked')) {
                $('#standard_forms_visible_text').removeClass('d-none');
                $('#standard_forms_hidden_text').addClass('d-none');
            } else {
                $('#standard_forms_visible_text').addClass('d-none');
                $('#standard_forms_hidden_text').removeClass('d-none');
            }
        }
        
        // Sayfa yüklendiğinde Standart Formlar durumunu ayarla
        updateStandardFormsVisibilityStatus();
        
        // Standart Formlar durum değiştiğinde güncelle
        $('#is_standard_forms_visible').on('change', function() {
            updateStandardFormsVisibilityStatus();
        });
    });
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

<script>
// Modal kapatma fonksiyonu
        function closeModal() {
            try {
        const modalElement = document.getElementById('mediapickerModal');
                if (modalElement) {
            // Bootstrap 5 yöntemi ile kapatmayı dene
            const modalInstance = bootstrap.Modal.getOrCreateInstance(modalElement);
                    if (modalInstance) {
                        modalInstance.hide();
                        console.log('Modal resmi yöntemle kapatıldı');
                        return true;
                    }
                }
    } catch (error) {
        console.error('Modal resmi yöntemle kapatılırken hata oluştu:', error);
            }
            
    // jQuery yöntemiyle kapatmayı dene
            try {
                $('#mediapickerModal').modal('hide');
                console.log('Modal jQuery ile kapatıldı');
                return true;
            } catch (error) {
        console.error('Modal jQuery yöntemiyle kapatılırken hata oluştu:', error);
            }
            
            return false;
        }
        
// Dosya işleme scriptleri
$(document).ready(function() {
    console.log('Dosya işlemleri script yüklendi');
    
    // Galeri filemanager değişikliğini dinleme
    $('#fake-gallery-input').on('change', function() {
        // Seçilen görseli al
        var url = $(this).val();
        if (url) {
            // URL'yi rölatif yola dönüştür
            url = makeRelativeUrl(url);
            addGalleryItem(url);
            $(this).val(''); // input'u temizle
        }
    });
    
    // Image inputu değiştiğinde rölatif yola dönüştür
    $('#image').on('change', function() {
        const url = $(this).val();
        if (url) {
            // URL'yi rölatif hale getir
            $(this).val(makeRelativeUrl(url));
        }
    });
    
    // Ana görsel URL'sini düzelt
    if ($('#image').val()) {
        const cleanUrl = makeRelativeUrl($('#image').val());
        $('#image').val(cleanUrl);
        $('#image-preview').find('img').attr('src', cleanUrl);
    }
    
    // Galeri görsel URL'lerini düzelt
    $('#gallery-preview input[name="gallery[]"]').each(function() {
        const cleanUrl = makeRelativeUrl($(this).val());
        $(this).val(cleanUrl);
        $(this).closest('.gallery-item').find('img').attr('src', cleanUrl);
        });
    });
</script>

<script>
    // FileManagerSystem Picker fonksiyonu 
    function openFileManagerSystemPicker(editor) {
        // Geçici bir ID oluştur
        const tempId = Date.now();
        const relatedType = 'service_content';
        
        // MediaPicker URL
        const mediapickerUrl = '/admin/filemanagersystem/mediapicker?type=image&filter=all&related_type=' + relatedType + '&related_id=' + tempId;
        
        // iFrame'i güncelle
        $('#mediapickerFrame').attr('src', mediapickerUrl);
        
        // Modal'ı göster - Bootstrap 5 ile uyumlu
        var modal = new bootstrap.Modal(document.getElementById('mediapickerModal'));
        modal.show();
        
        // Mesaj dinleme işlevi
        function handleMediaSelection(event) {
            try {
                if (event.data && event.data.type === 'mediaSelected') {
                    let mediaUrl = '';
                    let altText = event.data.mediaAlt || '';
                    let titleText = event.data.mediaTitle || '';
                    
                    // URL değerini al
                    if (event.data.mediaUrl) {
                        mediaUrl = event.data.mediaUrl;
                        
                        // URL çevirme
                        if (mediaUrl && mediaUrl.startsWith('/')) {
                            const baseUrl = window.location.protocol + '//' + window.location.host;
                            mediaUrl = baseUrl + mediaUrl;
                        }
                    } else if (event.data.mediaId) {
                        // ID ile kullan
                        const previewUrl = '/admin/filemanagersystem/media/preview/' + event.data.mediaId;
                        mediaUrl = previewUrl;
                    }
                    
                    if (mediaUrl) {
                        // Görsel ekle
                        editor.insertContent('<img src="' + mediaUrl + '" alt="' + altText + '" title="' + titleText + '" />');
                        
                        // Modalı kapat
                        modal.hide();
                        
                        // Event listener'ı kaldır
                        window.removeEventListener('message', handleMediaSelection);
                    }
                } else if (event.data && event.data.type === 'mediapickerError') {
                    console.error('FileManagerSystem hatası:', event.data.message);
                    alert('Medya seçici hatası: ' + event.data.message);
                    modal.hide();
                    
                    window.removeEventListener('message', handleMediaSelection);
                }
            } catch (error) {
                console.error('Medya seçimi işlenirken hata oluştu:', error);
                alert('Medya seçimi işlenirken bir hata oluştu.');
                
                window.removeEventListener('message', handleMediaSelection);
            }
        }
        
        // Event listener ekle
        window.removeEventListener('message', handleMediaSelection);
        window.addEventListener('message', handleMediaSelection);
    }
</script>

<!-- FileManagerSystem görsel seçici script -->
<script>
$(document).ready(function() {
    // FileManagerSystem entegrasyonu - Ana Görsel Seçimi
    $('#filemanagersystem_image_button, #filemanagersystem_gallery_button').on('click', function() {
        const buttonId = $(this).attr('id');
        const isGallery = buttonId === 'filemanagersystem_gallery_button';
        
        const input = isGallery ? $('#filemanagersystem_gallery') : $('#filemanagersystem_image');
        const preview = isGallery ? $('#filemanagersystem_gallery_preview') : $('#filemanagersystem_image_preview');
        
        // Geçici bir ID oluştur
        const tempId = Date.now();
        const relatedType = 'service';
        
        // MediaPicker URL
        const mediapickerUrl = '/admin/filemanagersystem/mediapicker?type=image&filter=all&related_type=' + relatedType + '&related_id=' + tempId;
        
        // iFrame'i güncelle
        $('#mediapickerFrame').attr('src', mediapickerUrl);
        
        // Modal'ı göster - Bootstrap 5 ile uyumlu
        var modalEl = document.getElementById('mediapickerModal');
        var modal = new bootstrap.Modal(modalEl);
        modal.show();
        
        // Medya seçimi mesaj dinleyicisi
        function handleMediaSelection(event) {
            try {
                if (event.data && event.data.type === 'mediaSelected') {
                    let mediaUrl = '';
                    
                    // URL değerini al
                    if (event.data.mediaUrl) {
                        mediaUrl = event.data.mediaUrl;
                    } else if (event.data.mediaId) {
                        // ID ile kullan
                        mediaUrl = '/admin/filemanagersystem/media/preview/' + event.data.mediaId;
                    }
                    
                    if (mediaUrl) {
                        // Input'a URL'yi ekle
                        input.val(mediaUrl);
                        
                        if (isGallery) {
                            // Galeri önizlemesine ekle
                            addGalleryItem(mediaUrl);
                        } else {
                            // Önizlemeyi göster (ana görsel için)
                            preview.show().find('img').attr('src', mediaUrl);
                        }
                        
                        // Modalı kapat
                        modal.hide();
                        
                        // Event listener'ı kaldır
                        window.removeEventListener('message', handleMediaSelection);
                    }
                } else if (event.data && event.data.type === 'mediapickerError') {
                    console.error('MediaPicker hatası:', event.data.message);
                    alert('Medya seçici hatası: ' + event.data.message);
                    modal.hide();
                    
                    window.removeEventListener('message', handleMediaSelection);
                }
            } catch (error) {
                console.error('Medya seçimi işlenirken hata oluştu:', error);
                window.removeEventListener('message', handleMediaSelection);
            }
        }
        
        // Event listener ekle
        window.removeEventListener('message', handleMediaSelection);
        window.addEventListener('message', handleMediaSelection);
    });

    // Mevcut görsel varsa göster
    const initialImageValue = $('#filemanagersystem_image').val();
    if (initialImageValue) {
        $('#filemanagersystem_image_preview').show();
        $('#filemanagersystem_image_preview img').attr('src', initialImageValue);
    }
    
    // Galeri görselini silme
    $(document).on('click', '.gallery-item .remove-btn', function() {
        $(this).closest('.gallery-item').remove();
    });
    
    // Galeriye öğe ekleme
    function addGalleryItem(url) {
        const itemId = 'gallery-item-' + Date.now();
        const html = `
            <div class="gallery-item" id="${itemId}">
                <img src="${url}" alt="Galeri görseli" class="img-fluid">
                <button type="button" class="remove-btn" data-id="${itemId}">
                    <i class="fas fa-times"></i>
                </button>
                <input type="hidden" name="gallery[]" value="${url}">
            </div>
        `;
        $('#gallery-preview').append(html);
    }
});
</script>
@stop 