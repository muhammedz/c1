@extends('adminlte::page')

@section('title', 'Yeni Hizmet Ekle')

@section('plugins.Toastr', true)

@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<style>
    .content-wrapper {
        background-color: #f4f6f9;
    }
    
    .form-label {
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #2c3e50;
    }
    
    .card {
        border: none;
        box-shadow: 0 0 20px rgba(0,0,0,.08);
        margin-bottom: 1.5rem;
    }
    
    .card-header {
        background-color: #fff;
        border-bottom: 1px solid #edf2f7;
        padding: 1rem 1.25rem;
    }
    
    .card-header h5 {
        margin: 0;
        font-size: 1rem;
        font-weight: 600;
    }
    
    .card-body {
        padding: 1.5rem;
    }
    
    .form-control, .form-select {
        border-color: #e2e8f0;
        padding: 0.6rem 1rem;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #3490dc;
        box-shadow: 0 0 0 0.2rem rgba(52,144,220,.25);
    }
    
    .dropzone {
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
    
    .dropzone:hover {
        border-color: #2779bd;
        background: #f1f7fe;
    }
    
    .gallery-container {
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
    
    .ts-wrapper {
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        box-shadow: none;
    }
    
    .ts-wrapper.focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    
    .ts-control {
        border: none !important;
        padding: 0.375rem 0.75rem;
    }
    
    /* Ana görsel için stil */
    #image-preview {
        background: #f8fafc;
        border-radius: 8px;
        text-align: center;
        max-width: 100%;
        margin-top: 15px;
        box-shadow: 0 1px 3px rgba(0,0,0,.1);
        padding: 10px;
        border: 1px solid #e9ecef;
    }
    
    #image-preview img {
        max-height: 200px !important;
        max-width: 100% !important;
        width: auto !important;
        height: auto !important;
        object-fit: contain !important;
    }
    
    /* Durum seçim kartları için */
    .status-option {
        cursor: pointer;
        transition: all 0.25s ease;
        border-width: 2px !important;
        border-radius: 10px !important;
        height: 100%;
        position: relative;
        overflow: hidden;
    }
    
    .status-option:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.15);
        border-color: #b8daff !important;
    }
    
    .status-option.active {
        border-color: #3490dc !important;
        background-color: #e6f2ff !important;
        box-shadow: 0 0 0 3px rgba(52,144,220,0.25);
    }
    
    .validation-errors-summary {
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
        border-radius: 0.25rem;
        padding: 1rem;
        margin-bottom: 20px;
        color: #721c24;
    }
    
    .validation-errors-summary ul {
        margin-bottom: 0;
        padding-left: 20px;
    }
    
    .validation-errors-summary h5 {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
        font-size: 1rem;
    }
    
    .validation-errors-summary h5 i {
        margin-right: 0.5rem;
    }
    
    /* Kategori ve Etiket Kartları İçin Stiller */
    .category-card, .tag-card {
        background-color: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 0.5rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    
    .category-card .card-header, .tag-card .card-header {
        background-color: #fff;
        border-bottom: 1px solid #e9ecef;
        padding: 0.75rem 1rem;
        font-weight: 600;
    }
    
    .category-card .card-body, .tag-card .card-body {
        padding: 1rem;
    }
    
    .category-badge {
        display: inline-flex;
        align-items: center;
        background: #e9ecef;
        border-radius: 0.25rem;
        padding: 0.25rem 0.5rem;
        margin: 0.125rem;
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
@endsection

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2">Yeni Hizmet</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.services.index') }}" class="text-decoration-none">Hizmetler</a></li>
                    <li class="breadcrumb-item active">Yeni Hizmet</li>
                </ol>
            </nav>
        </div>
        
        <div>
            <a href="{{ route('admin.services.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Geri Dön
            </a>
        </div>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if ($errors->any())
        <div class="validation-errors-summary">
            <h5><i class="fas fa-exclamation-triangle"></i> Lütfen aşağıdaki hataları düzeltin:</h5>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
            
    <form action="{{ route('admin.services.store') }}" method="POST" id="service-form" enctype="multipart/form-data">
        @csrf
        
        <div class="row">
            <div class="col-lg-8">
                <!-- Ana İçerik -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="mb-4">
                            <label for="title" class="form-label">Başlık <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
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
                                    <span class="text-primary fw-bold" id="slug-preview">{{ old('slug') ?: '-' }}</span>
                                </div>
                                <div class="small text-muted mt-1">
                                    <i class="fas fa-info-circle me-1"></i> Slug:
                                </div>
                                <div class="input-group mt-1">
                                    <input type="text" class="form-control form-control-sm @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug') }}" placeholder="Otomatik oluşturulur">
                                    <button class="btn btn-sm btn-outline-secondary" type="button" id="slug-regenerate">
                                        <i class="fas fa-sync-alt"></i> Yenile
                                    </button>
                                    <a href="{{ url('/hizmetler/' . (old('slug') ?: '-')) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-external-link-alt"></i> Önizle
                                    </a>
                                </div>
                                @error('slug')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted mt-1">
                                    Otomatik oluşturulan slug'ı düzenleyebilirsiniz. Boş bırakırsanız başlıktan otomatik oluşturulacaktır.
                                </small>
                            </div>
                        </div>
                                
                        <div class="mb-4">
                            <label for="summary" class="form-label">Özet</label>
                            <textarea class="form-control @error('summary') is-invalid @enderror" id="summary" name="summary" rows="3" style="height: 100px;">{{ old('summary') }}</textarea>
                            @error('summary')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Hizmet listesinde ve sosyal medya paylaşımlarında görünecek kısa açıklama.</small>
                        </div>
                                
                        <div class="mb-4">
                            <label for="content" class="form-label">İçerik <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="10">{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Hizmet detay sayfasında gösterilecek ana içerik.</small>
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
                            <label for="filemanagersystem_image" class="form-label">Hizmet Görseli</label>
                            <div class="input-group">
                                <input type="text" class="form-control @error('image') is-invalid @enderror" id="filemanagersystem_image" name="image" value="{{ old('image') }}">
                                <button type="button" class="btn btn-primary" id="filemanagersystem_image_button">
                                    <i class="fas fa-image"></i> Görsel Seç
                                </button>
                            </div>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="filemanagersystem_image_preview" class="mt-2" style="display: {{ old('image') ? 'block' : 'none' }};">
                                <img src="{{ old('image') ? asset(str_replace('/storage/', '', old('image'))) : '' }}" alt="Önizleme" class="img-thumbnail">
                            </div>
                            <div class="alert alert-info mt-2" id="image-warning">
                                <i class="fas fa-info-circle me-1"></i>
                                <strong>Not:</strong> Önerilen görsel boyutu: 1200x800 piksel. Görsel seçmezseniz varsayılan görsel kullanılacaktır.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Galeri Görselleri -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Galeri Görselleri</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label for="filemanagersystem_gallery" class="form-label">Galeri Görselleri</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="filemanagersystem_gallery" placeholder="Görseller burada görünecek..." disabled>
                                <button type="button" class="btn btn-primary" id="filemanagersystem_gallery_button">
                                    <i class="fas fa-images"></i> Görsel Ekle
                                </button>
                            </div>
                            <small class="text-muted">Galeri görselleri detay sayfasında bir slayt olarak gösterilecektir.</small>
                        </div>
                        
                        <!-- Galeri Önizleme -->
                        <div class="gallery-container" id="gallery-preview">
                            @if(old('gallery') && is_array(old('gallery')))
                                @foreach(old('gallery') as $galleryImage)
                                <div class="gallery-item" id="gallery-item-{{ rand(1000, 9999) }}">
                                    <img src="{{ asset(str_replace('/storage/', '', $galleryImage)) }}" alt="Galeri Görseli" style="width: 100%; height: 100%; object-fit: cover;">
                                    <button type="button" class="remove-btn" data-id="gallery-item-{{ rand(1000, 9999) }}">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    <input type="hidden" name="gallery[]" value="{{ $galleryImage }}">
                                </div>
                                @endforeach
                            @endif
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
                            <input type="text" class="form-control @error('cta_text') is-invalid @enderror" id="cta_text" name="cta_text" value="{{ old('cta_text') }}" placeholder="Örn: Hemen Başvur, Fiyat Teklifi Al">
                            @error('cta_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Detay sayfasında gösterilecek çağrı butonunun metni.</small>
                        </div>
                                
                        <div class="mb-4">
                            <label for="cta_url" class="form-label">Buton URL</label>
                            <input type="text" class="form-control @error('cta_url') is-invalid @enderror" id="cta_url" name="cta_url" value="{{ old('cta_url') }}" placeholder="Örn: /iletisim, https://example.com/form">
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
                        <div class="mb-3">
                            <label for="meta_title" class="form-label">Meta Başlık</label>
                            <input type="text" class="form-control" id="meta_title" name="meta_title" value="{{ old('meta_title') }}">
                            <small class="text-muted">Boş bırakılırsa hizmet başlığı kullanılır. (60-65 karakter ideal)</small>
                        </div>
                        <div class="mb-3">
                            <label for="meta_description" class="form-label">Meta Açıklama</label>
                            <textarea class="form-control" id="meta_description" name="meta_description" rows="3">{{ old('meta_description') }}</textarea>
                            <small class="text-muted">Boş bırakılırsa hizmet açıklaması kullanılır. (150-160 karakter ideal)</small>
                        </div>
                        <div class="mb-3">
                            <label for="meta_keywords" class="form-label">Meta Anahtar Kelimeler</label>
                            <input type="text" class="form-control" id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords') }}">
                            <small class="text-muted">Virgülle ayırarak ekleyin. Örn: hizmet, eğitim, danışmanlık (5-7 kelime ideal)</small>
                        </div>
                    </div>
                </div>
                
                <!-- Hedef Kitleler -->
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center">
                        <i class="fas fa-users me-2 text-primary"></i>
                        <h5 class="mb-0">Hedef Kitleler</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">Bu hizmetin hitap ettiği hedef kitleleri seçin:</p>
                        
                        <div class="row">
                            @foreach($hedefKitleler as $hedefKitle)
                            <div class="col-md-6 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="hedef_kitleler[]" 
                                        id="hedef-kitle-{{ $hedefKitle->id }}" value="{{ $hedefKitle->id }}"
                                        {{ in_array($hedefKitle->id, old('hedef_kitleler', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="hedef-kitle-{{ $hedefKitle->id }}">
                                        {{ $hedefKitle->name }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <!-- Haber Kategorileri -->
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center">
                        <i class="fas fa-newspaper me-2 text-primary"></i>
                        <h5 class="mb-0">İlgili Haber Kategorileri</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">Bu hizmetle ilgili haberlerin gösterileceği kategorileri seçin:</p>
                        
                        <div class="row">
                            @foreach($newsCategories as $newsCategory)
                            <div class="col-md-6 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="news_category_ids[]" 
                                        id="news-category-{{ $newsCategory->id }}" value="{{ $newsCategory->id }}"
                                        {{ in_array($newsCategory->id, old('news_category_ids', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="news-category-{{ $newsCategory->id }}">
                                        @if($newsCategory->icon)
                                            <i class="{{ $newsCategory->icon }} me-1"></i>
                                        @endif
                                        {{ $newsCategory->name }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        @if($newsCategories->isEmpty())
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Henüz haber kategorisi bulunmamaktadır. <a href="{{ route('admin.news-categories.create') }}" target="_blank">Yeni kategori ekleyin</a>.
                        </div>
                        @endif
                    </div>
                </div>
            </div>
                    
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
                                <input type="hidden" name="status" id="status-input" value="{{ old('status', 'published') }}" required>
                                @error('status')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </label>
                            
                            <div class="status-selector d-flex gap-2 mt-2">
                                <div class="status-card compact flex-grow-1" data-status="published" style="max-width: 50%; margin: 0 auto;">
                                    <div class="card h-100 status-option {{ old('status', 'published') == 'published' ? 'border-primary active' : 'border' }}" style="cursor: pointer; transition: all 0.3s ease; border-width: 3px !important; border-radius: 12px !important; overflow: hidden; position: relative; box-shadow: {{ old('status', 'published') == 'published' ? '0 0 10px rgba(52,144,220,0.5)' : 'none' }}; background-color: {{ old('status', 'published') == 'published' ? '#e6f2ff' : 'white' }};">
                                        
                                        @if(old('status', 'published') == 'published')
                                        <div style="position: absolute; top: 0; left: 0; width: 8px; height: 100%; background-color: #3490dc;"></div>
                                        <div style="position: absolute; top: 5px; right: 6px; font-size: 14px; color: #3490dc; font-weight: bold;">✓</div>
                                        @endif
                                        
                                        <div class="card-body p-2 text-center">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <i class="fas fa-check-circle status-icon me-2" style="font-size: 1.2rem; color: {{ old('status', 'published') == 'published' ? '#3490dc' : '#6c757d' }}; transition: all 0.3s ease;"></i>
                                                <span class="status-title" style="font-weight: {{ old('status', 'published') == 'published' ? '700' : '500' }}; font-size: 0.9rem; color: {{ old('status', 'published') == 'published' ? '#3490dc' : '#212529' }};">Yayında</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="status-card compact flex-grow-1" data-status="draft" style="max-width: 50%; margin: 0 auto;">
                                    <div class="card h-100 status-option {{ old('status') == 'draft' ? 'border-primary active' : 'border' }}" style="cursor: pointer; transition: all 0.3s ease; border-width: 3px !important; border-radius: 12px !important; overflow: hidden; position: relative; box-shadow: {{ old('status') == 'draft' ? '0 0 10px rgba(52,144,220,0.5)' : 'none' }}; background-color: {{ old('status') == 'draft' ? '#e6f2ff' : 'white' }};">
                                        
                                        @if(old('status') == 'draft')
                                        <div style="position: absolute; top: 0; left: 0; width: 8px; height: 100%; background-color: #3490dc;"></div>
                                        <div style="position: absolute; top: 5px; right: 6px; font-size: 14px; color: #3490dc; font-weight: bold;">✓</div>
                                        @endif
                                        
                                        <div class="card-body p-2 text-center">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <i class="fas fa-edit status-icon me-2" style="font-size: 1.2rem; color: {{ old('status') == 'draft' ? '#3490dc' : '#6c757d' }}; transition: all 0.3s ease;"></i>
                                                <span class="status-title" style="font-weight: {{ old('status') == 'draft' ? '700' : '500' }}; font-size: 0.9rem; color: {{ old('status') == 'draft' ? '#3490dc' : '#212529' }};">Taslak</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Gizli input ile status değerini saklayalım -->
                            <input type="hidden" name="status" id="status" value="{{ old('status', 'published') }}">
                        </div>

                        <div class="mb-4">
                            <label for="published_at" class="form-label">Yayın Tarihi</label>
                            <input type="text" class="form-control datepicker @error('published_at') is-invalid @enderror" id="published_at" name="published_at" value="{{ old('published_at', date('Y-m-d')) }}" autocomplete="off">
                            @error('published_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="end_date" class="form-label">Bitiş Tarihi</label>
                            <input type="text" class="form-control datepicker @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ old('end_date') }}" autocomplete="off">
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Belirtilirse, bu tarihte hizmet otomatik olarak yayından kaldırılır.</small>
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" {{ old('is_featured') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">
                                <strong>Öne Çıkar</strong>
                                <small class="d-block text-muted">Hizmet ana sayfada öne çıkarılacaktır.</small>
                            </label>
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="is_headline" name="is_headline" {{ old('is_headline') ? 'checked' : '' }} {{ $maxHeadlinesReached ? 'disabled' : '' }}>
                            <label class="form-check-label" for="is_headline">
                                <strong>Manşete Ekle</strong>
                                <small class="d-block text-muted">Hizmet ana sayfada manşette gösterilecektir.</small>
                            </label>
                            @if($maxHeadlinesReached)
                                <small class="text-danger">Maksimum manşet sayısına ulaşıldı (4)</small>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Kategoriler ve Etiketler -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Kategoriler ve Birim</h5>
                    </div>
                    <div class="card-body">
                        <!-- Birim Seçimi -->
                        <div class="mb-4">
                            <label for="services_unit_id" class="form-label">Birim</label>
                            <select class="form-select @error('services_unit_id') is-invalid @enderror" id="services_unit_id" name="services_unit_id">
                                <option value="">Birim Seçin</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}" {{ old('services_unit_id') == $unit->id ? 'selected' : '' }}>
                                        {{ $unit->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('services_unit_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

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
                                                    <input type="checkbox" name="category_ids[]" value="{{ $category->id }}" class="form-check-input me-1" {{ in_array($category->id, old('category_ids', [])) ? 'checked' : '' }}>
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
                        
                        <!-- Hizmet Konuları -->
                        <div class="card mb-3">
                            <div class="card-header bg-light d-flex align-items-center">
                                <i class="fas fa-list-ul me-2 text-primary"></i>
                                <h5 class="mb-0">Hizmet Konuları</h5>
                                <span class="badge bg-success ms-2 topic-count">0</span>
                            </div>
                            <div class="card-body">
                                <div class="topic-wrapper">
                                    <!-- Açıklama -->
                                    <div class="alert alert-info mb-3">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Bu hizmetin hangi konularda yer alacağını seçin. Hizmet, seçilen konuların sayfalarında görüntülenecektir.
                                    </div>
                                    
                                    <!-- Arama ve Tümünü Seç -->
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="ms-auto">
                                            <button type="button" class="btn btn-light btn-sm border" id="select-all-topics" style="font-size: 0.8rem; padding: 0.25rem 0.5rem;">
                                                <i class="fas fa-check-square me-1"></i>Tümü
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Konu Listesi -->
                                    <div class="topic-list mb-3" style="max-height: 300px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 4px;">
                                        @foreach($serviceTopics as $topic)
                                        <div class="topic-item d-flex align-items-center py-2 px-3 mb-1 rounded hover-bg-light">
                                            <div class="form-check mb-0 w-100">
                                                <label class="d-flex align-items-center gap-2">
                                                    <input type="checkbox" name="service_topic_ids[]" value="{{ $topic->id }}" class="form-check-input me-1" {{ in_array($topic->id, old('service_topic_ids', [])) ? 'checked' : '' }}>
                                                    @if($topic->icon)
                                                        <i class="{{ $topic->icon }}" style="color: {{ $topic->color }}; font-size: 1.1em;"></i>
                                                    @endif
                                                    <span class="topic-name fw-medium">{{ $topic->name }}</span>
                                                    @if($topic->description)
                                                        <i class="fas fa-info-circle ms-1 text-muted" style="font-size: 0.85em;" data-bs-toggle="tooltip" title="{{ $topic->description }}"></i>
                                                    @endif
                                                    <span class="badge bg-light text-muted ms-auto" style="font-size: 0.7em;">{{ $topic->services_count }} hizmet</span>
                                                </label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>

                                    <!-- Seçili Konular -->
                                    <div class="selected-topics">
                                        <label class="form-label text-muted mb-2">
                                            <i class="fas fa-list-ul me-1"></i> Seçili Konular
                                        </label>
                                        <div class="d-flex flex-wrap gap-2" id="selected-topics-list"></div>
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
                                        <input type="hidden" id="tags-input" name="tags" value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Kaydet/İptal Butonları -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary px-5">
                                <i class="fas fa-save me-2"></i> Kaydet
                            </button>
                            <a href="{{ route('admin.services.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i> İptal
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detay Sayfası İçeriği -->
        <div class="card mt-4">
            <div class="card-header">
                <h5>Detay Sayfası İçeriği</h5>
                <small class="text-muted">Hizmet detay sayfasında gösterilecek özel içerik bölümleri.</small>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Bu bölümdeki içerikler, hizmetin detay sayfasında farklı sekmelerde gösterilecektir. Görünürlük ayarlarını kullanarak hangi sekmelerin gösterileceğini belirleyebilirsiniz.
                </div>
                
                <!-- Hizmetin Amacı -->
                <div class="mb-4">
                    <label for="service_purpose" class="form-label">Hizmetin Amacı</label>
                    <div class="d-flex justify-content-end mb-2">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="is_purpose_visible" name="details[is_purpose_visible]" value="1" {{ old('details.is_purpose_visible', '1') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_purpose_visible">
                                <span class="text-success" id="purpose_visible_text">Görünür</span>
                                <span class="text-danger d-none" id="purpose_hidden_text">Gizli</span>
                            </label>
                        </div>
                    </div>
                    <textarea class="form-control tinymce" id="service_purpose" name="details[service_purpose]" rows="5">{{ old('details.service_purpose', '') }}</textarea>
                    <small class="form-text text-muted">Bu bölüm, hizmet detay sayfasındaki "Hizmetin Amacı" bölümünde gösterilecektir.</small>
                </div>
                
                <!-- Kimler Başvurabilir -->
                <div class="mb-4">
                    <label for="who_can_apply" class="form-label">Kimler Başvurabilir</label>
                    <div class="d-flex justify-content-end mb-2">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="is_who_can_apply_visible" name="details[is_who_can_apply_visible]" value="1" {{ old('details.is_who_can_apply_visible', '1') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_who_can_apply_visible">
                                <span class="text-success" id="who_can_apply_visible_text">Görünür</span>
                                <span class="text-danger d-none" id="who_can_apply_hidden_text">Gizli</span>
                            </label>
                        </div>
                    </div>
                    <textarea class="form-control tinymce" id="who_can_apply" name="details[who_can_apply]" rows="5">{{ old('details.who_can_apply', '') }}</textarea>
                    <small class="form-text text-muted">Bu bölüm, hizmet detay sayfasındaki "Kimler Başvurabilir" bölümünde gösterilecektir.</small>
                </div>
                
                <!-- Başvuru Şartları -->
                <div class="mb-4">
                    <label for="requirements" class="form-label">Başvuru Şartları</label>
                    <div class="d-flex justify-content-end mb-2">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="is_requirements_visible" name="details[is_requirements_visible]" value="1" {{ old('details.is_requirements_visible', '1') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_requirements_visible">
                                <span class="text-success" id="requirements_visible_text">Görünür</span>
                                <span class="text-danger d-none" id="requirements_hidden_text">Gizli</span>
                            </label>
                        </div>
                    </div>
                    <textarea class="form-control tinymce" id="requirements" name="details[requirements]" rows="5">{{ old('details.requirements', '') }}</textarea>
                    <small class="form-text text-muted">Bu bölüm, hizmet detay sayfasındaki "Başvuru Şartları" bölümünde gösterilecektir.</small>
                </div>
                
                <!-- Başvuru Süreci -->
                <div class="mb-4">
                    <label for="application_process" class="form-label">Başvuru Süreci</label>
                    <div class="d-flex justify-content-end mb-2">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="is_application_process_visible" name="details[is_application_process_visible]" value="1" {{ old('details.is_application_process_visible', '1') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_application_process_visible">
                                <span class="text-success" id="application_process_visible_text">Görünür</span>
                                <span class="text-danger d-none" id="application_process_hidden_text">Gizli</span>
                            </label>
                        </div>
                    </div>
                    <textarea class="form-control tinymce" id="application_process" name="details[application_process]" rows="5">{{ old('details.application_process', '') }}</textarea>
                    <small class="form-text text-muted">Bu bölüm, hizmet detay sayfasındaki "Başvuru Süreci" bölümünde gösterilecektir.</small>
                </div>
                
                <!-- İşlem Süresi (Tablo) -->
                <div class="mb-4">
                    <label class="form-label d-flex justify-content-between align-items-center">
                        <span>İşlem Süresi Tablosu</span>
                        <div class="d-flex align-items-center">
                            <div class="form-check form-switch me-3">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_processing_times_visible" name="details[is_processing_times_visible]" value="1" {{ old('details.is_processing_times_visible', '1') ? 'checked' : '' }}>
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
                                @if(old('details.processing_times', []))
                                    @foreach(old('details.processing_times', []) as $index => $time)
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
                                <input class="form-check-input" type="checkbox" role="switch" id="is_fees_visible" name="details[is_fees_visible]" value="1" {{ old('details.is_fees_visible', '1') ? 'checked' : '' }}>
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
                                @if(old('details.fees', []))
                                    @foreach(old('details.fees', []) as $index => $fee)
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
                        <div class="d-flex align-items-center">
                            <div class="form-check form-switch me-3">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_payment_options_visible" name="details[is_payment_options_visible]" value="1" {{ old('details.is_payment_options_visible', '1') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_payment_options_visible">
                                    <span class="text-success" id="payment_options_visible_text">Görünür</span>
                                    <span class="text-danger d-none" id="payment_options_hidden_text">Gizli</span>
                                </label>
                            </div>
                            <button type="button" class="btn btn-sm btn-primary" id="add-payment-option-row">
                                <i class="fas fa-plus"></i> Seçenek Ekle
                            </button>
                        </div>
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
                                @if(old('details.payment_options', []))
                                    @foreach(old('details.payment_options', []) as $index => $option)
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
                            <input class="form-check-input" type="checkbox" role="switch" id="is_additional_info_visible" name="details[is_additional_info_visible]" value="1" {{ old('details.is_additional_info_visible', '1') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_additional_info_visible">
                                <span class="text-success" id="additional_info_visible_text">Görünür</span>
                                <span class="text-danger d-none" id="additional_info_hidden_text">Gizli</span>
                            </label>
                        </div>
                    </div>
                    <textarea class="form-control tinymce" id="additional_info" name="details[additional_info]" rows="5">{{ old('details.additional_info', '') }}</textarea>
                    <small class="form-text text-muted">Bu bölüm, hizmet detay sayfasındaki "Diğer Bilgiler" bölümünde gösterilecektir.</small>
                </div>
                
                <!-- Standart Formlar -->
                <div class="mb-4">
                    <label for="standard_forms" class="form-label">Standart Formlar</label>
                    <div class="d-flex justify-content-end mb-2">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="is_standard_forms_visible" name="details[is_standard_forms_visible]" value="1" {{ old('details.is_standard_forms_visible', '1') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_standard_forms_visible">
                                <span class="text-success" id="standard_forms_visible_text">Görünür</span>
                                <span class="text-danger d-none" id="standard_forms_hidden_text">Gizli</span>
                            </label>
                        </div>
                    </div>
                    <textarea class="form-control tinymce" id="standard_forms" name="details[standard_forms]" rows="5">{{ old('details.standard_forms', '') }}</textarea>
                    <small class="form-text text-muted">Bu bölüm, hizmet detay sayfasındaki "Standart Formlar" bölümünde gösterilecektir.</small>
                </div>
                
                <!-- Dokümanlar -->
                <div class="mb-4">
                    <label class="form-label d-flex justify-content-between align-items-center">
                        <span>Dokümanlar</span>
                        <button type="button" class="btn btn-sm btn-primary" id="add-document-row">
                            <i class="fas fa-plus"></i> Doküman Ekle
                        </button>
                    </label>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered" id="documents-table">
                            <thead>
                                <tr>
                                    <th style="width: 40%">Doküman Adı</th>
                                    <th>Dosya</th>
                                    <th style="width: 50px">İşlem</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(old('documents', []))
                                    @foreach(old('documents', []) as $index => $document)
                                    <tr>
                                        <td>
                                            <input type="text" class="form-control" name="documents[{{ $index }}][name]" value="{{ $document['name'] ?? '' }}" placeholder="Doküman adı">
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <input type="text" class="form-control document-file-input" name="documents[{{ $index }}][file]" value="{{ $document['file'] ?? '' }}" placeholder="Dosya seçin">
                                                <button type="button" class="btn btn-outline-secondary document-file-button">
                                                    <i class="fas fa-file"></i> Seç
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
                                        <input type="text" class="form-control" name="documents[0][name]" placeholder="Doküman adı">
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="text" class="form-control document-file-input" name="documents[0][file]" placeholder="Dosya seçin">
                                            <button type="button" class="btn btn-outline-secondary document-file-button">
                                                <i class="fas fa-file"></i> Seç
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
    </form>
</div>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('vendor/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet">
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input@1.3.4/dist/bs-custom-file-input.min.js"></script>
<script src="{{ asset('vendor/tinymce/tinymce.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.tr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script src="{{ asset('js/slug-helper.js') }}"></script>
<script>
    $(function() {
        // TinyMCE Editör
        tinymce.init({
            selector: '#content, .tinymce',
            license_key: 'gpl',
            height: 300,
            language: null, // Dil desteğini kaldırdık
            menubar: true,
            plugins: 'preview importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons',
            toolbar: 'undo redo | bold italic underline strikethrough | fontfamily image fontsize blocks | alignleft aligncenter alignright alignjustify | outdent indent | numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen preview save print | insertfile media link anchor codesample | ltr rtl',
            images_upload_url: '{{ route("admin.tinymce.upload") }}',
            images_upload_credentials: true,
            branding: false,
            promotion: false,
            toolbar_sticky: true,
            image_advtab: false,
            relative_urls: false,
            remove_script_host: false,
            convert_urls: false,
            content_css: [
                '{{ asset("vendor/adminlte/dist/css/adminlte.min.css") }}',
                'https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700'
            ]
        });

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
        
        // Image inputu değiştiğinde rölatif yola dönüştür
        $('#image').on('change', function() {
            const url = $(this).val();
            if (url) {
                // URL'yi rölatif hale getir
                $(this).val(makeRelativeUrl(url));
            }
        });
        
        // Datepicker
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true,
            language: 'tr'
        });
        
        // Durum seçimleri
        $('.status-option').on('click', function() {
            const selectedStatus = $(this).closest('.status-card').data('status');
            $('#status-input').val(selectedStatus);
            
            // Aktif sınıfları kaldır
            $('.status-option').removeClass('active border-primary').addClass('border');
            $('.status-option').find('.status-icon').css('color', '#6c757d');
            $('.status-option').find('.status-title').css({
                'color': '#212529',
                'font-weight': '500'
            });
            
            // Özel kenar çubuğu ve kontrol işaretini kaldır
            $('.status-option').find('div[style*="position: absolute"]').remove();
            
            // Seçili olan için aktif sınıfları uygula
            $(this).addClass('active border-primary').removeClass('border');
            $(this).css('box-shadow', '0 0 10px rgba(52,144,220,0.5)');
            $(this).css('background-color', '#e6f2ff');
            $(this).find('.status-icon').css('color', '#3490dc');
            $(this).find('.status-title').css({
                'color': '#3490dc',
                'font-weight': '700'
            });
            
            // Seçili olana özel kenar çubuğu ve kontrol işareti ekle
            if (!$(this).find('div[style*="position: absolute"]').length) {
                $(this).prepend('<div style="position: absolute; top: 0; left: 0; width: 8px; height: 100%; background-color: #3490dc;"></div>');
                $(this).prepend('<div style="position: absolute; top: 5px; right: 6px; font-size: 14px; color: #3490dc; font-weight: bold;">✓</div>');
            }
        });

        // Clear Button for Main Image
        $('#image-clear').on('click', function() {
            $('#image').val('');
            $('#image-preview').hide().find('img').attr('src', '');
        });

        // Slug oluşturma - SlugHelper kullanımı
        function slugify(text) {
            return SlugHelper.create(text);
        }
        
        $('#title').on('keyup', function() {
            var title = $(this).val();
            if (title) {
                var slug = slugify(title);
                $('#slug').val(slug);
                $('#slug-preview').text(slug);
            } else {
                $('#slug').val('');
                $('#slug-preview').text('-');
            }
        });
        
        $('#slug').on('keyup', function() {
            var slug = $(this).val();
            
            if (slug) {
                slug = slugify(slug);
                $(this).val(slug);
                $('#slug-preview').text(slug);
            } else {
                $('#slug-preview').text('-');
            }
        });
        
        $('#slug-regenerate').on('click', function() {
            var title = $('#title').val();
            if (title) {
                var slug = slugify(title);
                // Rastgele bir sayı ekleyerek benzersiz slug oluşturma
                var randomNum = new Date().getTime() % 10000;
                slug = slug + '-' + randomNum;
                
                $('#slug').val(slug);
                $('#slug-preview').text(slug);
                
                // Kullanıcıya bilgilendirme
                toastr.success('Yeni URL kodu oluşturuldu!', 'Başarılı', {timeOut: 2000});
            }
        });
        
        // Hizmet Konuları İşlemleri
        // Konu seçim değişikliklerini izle
        $('input[name="service_topic_ids[]"]').on('change', function() {
            updateSelectedTopics();
            updateTopicCount();
        });
        
        // Tüm konuları seç/kaldır
        $('#select-all-topics').on('click', function() {
            const allChecked = $('input[name="service_topic_ids[]"]:checked').length === $('input[name="service_topic_ids[]"]').length;
            $('input[name="service_topic_ids[]"]').prop('checked', !allChecked);
            updateSelectedTopics();
            updateTopicCount();
        });
        
        // Seçili konuları güncelle
        function updateSelectedTopics() {
            const selectedTopicsList = $('#selected-topics-list');
            selectedTopicsList.empty();
            
            $('input[name="service_topic_ids[]"]:checked').each(function() {
                const topicId = $(this).val();
                const topicName = $(this).closest('label').find('.topic-name').text();
                const topicIcon = $(this).closest('label').find('i').first().attr('class');
                const topicColor = $(this).closest('label').find('i').first().css('color');
                
                const badge = $(`
                    <span class="badge bg-light text-dark border d-flex align-items-center gap-1" style="font-size: 0.85em;">
                        ${topicIcon ? `<i class="${topicIcon}" style="color: ${topicColor};"></i>` : ''}
                        ${topicName}
                        <button type="button" class="btn-close btn-close-sm ms-1" data-topic-id="${topicId}" style="font-size: 0.6em;"></button>
                    </span>
                `);
                
                selectedTopicsList.append(badge);
            });
        }
        
        // Seçili konuları kaldırma
        $(document).on('click', '#selected-topics-list .btn-close', function() {
            const topicId = $(this).data('topic-id');
            $(`input[name="service_topic_ids[]"][value="${topicId}"]`).prop('checked', false);
            updateSelectedTopics();
            updateTopicCount();
        });
        
        // Konu sayısını güncelle
        function updateTopicCount() {
            const count = $('input[name="service_topic_ids[]"]:checked').length;
            $('.topic-count').text(count);
        }
        
        // Sayfa yüklendiğinde seçili konuları göster
        updateSelectedTopics();
        updateTopicCount();
        
        // Kategori seçimi - DOM yüklendikten sonra seçimi kur
        var selectedCategories = [];

        @if(old('category_ids'))
            @foreach(old('category_ids') as $categoryId)
                selectedCategories.push({{ $categoryId }});
            @endforeach
        @endif
        
        // Kategori seçimi değiştiğinde
        $(document).on('click', '.category-checkbox', function() {
            var categoryId = parseInt($(this).val());
            
            if ($(this).is(':checked')) {
                if (!selectedCategories.includes(categoryId)) {
                    selectedCategories.push(categoryId);
                }
            } else {
                var index = selectedCategories.indexOf(categoryId);
                if (index > -1) {
                    selectedCategories.splice(index, 1);
                }
            }
            
            // Kategori sayısını güncelle
            updateCategoryCount();
        });
        
        // Kategori sayısını güncelleme
        function updateCategoryCount() {
            $('.category-count').text(selectedCategories.length);
        }
        
        // Etiket seçimi için
        var tagInput = document.getElementById('tag-input');
        var addTagBtn = document.getElementById('add-tag-btn');
        var selectedTagsList = document.getElementById('selected-tags-list');
        var tagsInput = document.getElementById('tags-input');
        
        // Eski etiketleri ekle
        var selectedTags = [];
        
        @if(old('tags'))
            @php
                $oldTags = old('tags');
                // Eğer tags bir string ise, JSON olarak parse etmeye çalış
                if (is_string($oldTags)) {
                    try {
                        $oldTags = json_decode($oldTags);
                    } catch (\Exception $e) {
                        $oldTags = [];
                    }
                }
            @endphp
            
            @foreach($oldTags as $tag)
                selectedTags.push("{{ $tag }}");
            @endforeach
        @endif
        
        // Sayfa yüklendiğinde etiketleri göster
        updateTagsDisplay();
        
        // Etiket ekle butonu
        addTagBtn.addEventListener('click', function() {
            addTag();
        });
        
        // Enter basınca etiket ekle
        tagInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ',') {
                e.preventDefault();
                addTag();
            }
        });
        
        // Etiket ekleme fonksiyonu
        function addTag() {
            var tag = tagInput.value.trim();
            
            if (!tag) return; // Boş etiket eklemeyi engelle
            
            // Virgül ile ayrılmış birden fazla etiket ekleme
            var tags = tag.split(',');
            
            tags.forEach(function(item) {
                var trimmedTag = item.trim();
                if (trimmedTag && !selectedTags.includes(trimmedTag)) {
                    selectedTags.push(trimmedTag);
                }
            });
            
            // Input'u temizle
            tagInput.value = '';
            
            // Etiketleri güncelle
            updateTagsDisplay();
        }
        
        // Etiket seçim alanını güncelleme
        function updateTagsDisplay() {
            // Önce tüm etiketleri temizle
            selectedTagsList.innerHTML = '';
            
            // Etiketleri ekleme
            selectedTags.forEach(function(tag) {
                var badge = document.createElement('span');
                badge.className = 'badge bg-light text-dark p-2 me-2 mb-2';
                badge.style.fontSize = '0.9rem';
                badge.innerHTML = `
                    ${tag}
                    <i class="fas fa-times ms-2 text-danger remove-tag" style="cursor: pointer;" data-tag="${tag}"></i>
                `;
                selectedTagsList.appendChild(badge);
            });
            
            // Hidden input'a değerleri ekle - dizi yerine virgülle ayrılmış string olarak
            tagsInput.value = selectedTags.join(',');
        }
        
        // Etiket silme
        selectedTagsList.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-tag')) {
                const tagToRemove = e.target.dataset.tag;
                
                // Etiketi diziden kaldır
                selectedTags = selectedTags.filter(tag => tag !== tagToRemove);
                
                // Etiket görüntüsünü güncelle
                updateTagsDisplay();
            }
        });
        
        // Sayfanın ilk yüklenmesinde kategori sayacını güncelle
        updateCategoryCount();

        // Galeriye resim ekleme fonksiyonu
        function addGalleryItem(url) {
            // Benzersiz bir ID oluştur
            var itemId = 'gallery-item-' + Date.now() + '-' + Math.floor(Math.random() * 1000);
            
            // Galeri öğesi HTML'i
            var itemHtml = `
                <div class="gallery-item" id="${itemId}">
                    <img src="${url}" alt="Galeri Görseli" style="width: 100%; height: 100%; object-fit: cover;">
                    <button type="button" class="remove-btn" data-id="${itemId}">
                        <i class="fas fa-times"></i>
                    </button>
                    <input type="hidden" name="gallery[]" value="${url}">
                </div>
            `;
                
            // Galeriye ekle
            $('#gallery-preview').append(itemHtml);
        }
        
        // Galeri görseli silme (event delegation)
        $(document).on('click', '.gallery-item .remove-btn', function() {
            var itemId = $(this).data('id');
            $('#' + itemId).remove();
        });

        // Hero özellikleri ekleme
        $('#add-feature').on('click', function() {
            const featureHtml = `
                <div class="feature-item mb-2 d-flex align-items-center">
                    <input type="text" class="form-control" name="features[]" placeholder="Özellik ekleyin">
                    <button type="button" class="btn btn-sm btn-danger ms-2 remove-feature"><i class="fas fa-times"></i></button>
                </div>
            `;
            $('#features-container').append(featureHtml);
        });
        
        // Hero özellikleri silme (delegate event)
        $(document).on('click', '.remove-feature', function() {
            $(this).closest('.feature-item').remove();
        });
        
        // Tablo satırları ekleme - İşlem Süresi
        $('#add-processing-time-row').on('click', function() {
            const index = $('#processing-time-table tbody tr').length;
            const html = `
                <tr>
                    <td>
                        <input type="text" class="form-control" name="details[processing_times][${index}][title]" placeholder="Başlık">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="details[processing_times][${index}][time]" placeholder="İşlem süresi">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="details[processing_times][${index}][description]" placeholder="Açıklama">
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger remove-row">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            $('#processing-time-table tbody').append(html);
        });
        
        // Tablo satırları ekleme - Ücretler
        $('#add-fee-row').on('click', function() {
            const index = $('#fees-table tbody tr').length;
            const html = `
                <tr>
                    <td>
                        <input type="text" class="form-control" name="details[fees][${index}][package]" placeholder="Paket adı">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="details[fees][${index}][description]" placeholder="Açıklama">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="details[fees][${index}][price]" placeholder="Fiyat">
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger remove-row">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            $('#fees-table tbody').append(html);
        });
        
        // Tablo satırları ekleme - Ödeme Seçenekleri
        $('#add-payment-option-row').on('click', function() {
            const index = $('#payment-options-table tbody tr').length;
            const html = `
                <tr>
                    <td>
                        <input type="text" class="form-control" name="details[payment_options][${index}][method]" placeholder="Ödeme yöntemi">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="details[payment_options][${index}][term]" placeholder="Vade">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="details[payment_options][${index}][description]" placeholder="Açıklama">
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger remove-row">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            $('#payment-options-table tbody').append(html);
        });
        
        // Tablo satırları ekleme - Dokümanlar
        $('#add-document-row').on('click', function() {
            const index = $('#documents-table tbody tr').length;
            const html = `
                <tr>
                    <td>
                        <input type="text" class="form-control" name="documents[${index}][name]" placeholder="Doküman adı">
                    </td>
                    <td>
                        <div class="input-group">
                            <input type="text" class="form-control document-file-input" name="documents[${index}][file]" placeholder="Dosya seçin">
                            <button type="button" class="btn btn-outline-secondary document-file-button">
                                <i class="fas fa-file"></i> Seç
                            </button>
                        </div>
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger remove-row">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            $('#documents-table tbody').append(html);
        });
        
        // Tablo satırı silme - Genel
        $(document).on('click', '.remove-row', function() {
            $(this).closest('tr').remove();
        });
        
        // Görünürlük bölümleri için toggle işlemleri
        $('.form-check-input[role="switch"]').on('change', function() {
            const targetId = $(this).attr('id');
            const isChecked = $(this).is(':checked');
            const visibleTextId = '#' + targetId.replace('is_', '') + '_visible_text';
            const hiddenTextId = '#' + targetId.replace('is_', '') + '_hidden_text';
            
            if (isChecked) {
                $(visibleTextId).removeClass('d-none');
                $(hiddenTextId).addClass('d-none');
            } else {
                $(visibleTextId).addClass('d-none');
                $(hiddenTextId).removeClass('d-none');
            }
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

<!-- FileManagerSystem görsel seçici script -->
<script>
$(document).ready(function() {
    // FileManagerSystem entegrasyonu - Ana Görsel Seçimi
    $('#filemanagersystem_image_button, #filemanagersystem_gallery_button').on('click', function() {
        const buttonId = $(this).attr('id');
        const isGallery = buttonId === 'filemanagersystem_gallery_button';
        
        const input = isGallery ? $('#filemanagersystem_gallery') : $('#filemanagersystem_image');
        const preview = isGallery ? $('#filemanagersystem_gallery_preview') : $('#filemanagersystem_image_preview');
        const previewImg = preview.find('img');
        
        // Geçici bir ID oluştur
        const tempId = Date.now();
        const relatedType = 'service';
        
        // MediaPicker URL
        const mediapickerUrl = '/admin/filemanagersystem/mediapicker?type=image&filter=all&related_type=' + relatedType + '&related_id=' + tempId;
        
        // iFrame'i güncelle
        $('#mediapickerFrame').attr('src', mediapickerUrl);
        
        // Modal'ı göster - Bootstrap 5 ile uyumlu
        var modal = new bootstrap.Modal(document.getElementById('mediapickerModal'));
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
                        if (isGallery) {
                            // Galeri için seçim
                            addGalleryItem(mediaUrl);
                        } else {
                            // Ana Görsel için seçim
                            input.val(mediaUrl);
                            
                            // Önizlemeyi göster
                            previewImg.attr('src', mediaUrl);
                            preview.show();
                            
                            // Uyarıyı gizle
                            $('#image-warning').hide();
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
        $('#image-warning').hide(); // Görsel varsa uyarıyı gizle
    }
    
    // Doküman dosya seçme butonuna tıklandığında
    $(document).on('click', '.document-file-button', function() {
        const fileInput = $(this).closest('.input-group').find('.document-file-input');
        
        // Geçici bir ID oluştur
        const tempId = Date.now();
        const relatedType = 'document';
        
        // MediaPicker URL
        const mediapickerUrl = '/admin/filemanagersystem/mediapicker?type=document&filter=all&related_type=' + relatedType + '&related_id=' + tempId;
        
        // iFrame'i güncelle
        $('#mediapickerFrame').attr('src', mediapickerUrl);
        
        // Modal'ı göster - Bootstrap 5 ile uyumlu
        var modal = new bootstrap.Modal(document.getElementById('mediapickerModal'));
        modal.show();
        
        // Medya seçimi mesaj dinleyicisi
        function handleDocumentSelection(event) {
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
                        // Dosya URL'sini input'a ekle
                        fileInput.val(mediaUrl);
                        
                        // Modalı kapat
                        modal.hide();
                        
                        // Event listener'ı kaldır
                        window.removeEventListener('message', handleDocumentSelection);
                    }
                } else if (event.data && event.data.type === 'mediapickerError') {
                    console.error('MediaPicker hatası:', event.data.message);
                    alert('Medya seçici hatası: ' + event.data.message);
                    modal.hide();
                    
                    window.removeEventListener('message', handleDocumentSelection);
                }
            } catch (error) {
                console.error('Doküman seçimi işlenirken hata oluştu:', error);
                window.removeEventListener('message', handleDocumentSelection);
            }
        }
        
        // Event listener ekle
        window.removeEventListener('message', handleDocumentSelection);
        window.addEventListener('message', handleDocumentSelection);
    });
});
</script>
@stop 