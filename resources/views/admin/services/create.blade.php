@extends('adminlte::page')

@section('title', 'Yeni Hizmet Ekle')

@section('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
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
                                    <span class="text-secondary">{{ url('/') }}/hizmet/</span>
                                    <span class="text-primary fw-bold" id="slug-preview">-</span>
                                </div>
                                <div class="small text-muted mt-1">
                                    <i class="fas fa-info-circle me-1"></i> Slug:
                                </div>
                                <div class="input-group mt-1">
                                    <input type="text" class="form-control form-control-sm" id="slug" name="slug" value="{{ old('slug') }}" placeholder="Otomatik oluşturulur">
                                    <button class="btn btn-sm btn-outline-secondary" type="button" id="slug-regenerate">
                                        <i class="fas fa-sync-alt"></i> Yenile
                                    </button>
                                </div>
                                <small class="form-text text-muted mt-1">
                                    Otomatik oluşturulan slug'ı düzenleyebilirsiniz. Boş bırakırsanız otomatik oluşturulacaktır.
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
                        </div>
                    </div>
                </div>
                        
                <!-- Medya -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Medya</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label for="image" class="form-label">Ana Görsel <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control @error('image') is-invalid @enderror" id="image" name="image" value="{{ old('image') }}" required readonly>
                                <button class="btn btn-primary" type="button" id="image-browser" data-input="image" data-preview="image-preview">
                                    <i class="fas fa-folder-open"></i> Göz At
                                </button>
                                <button class="btn btn-danger" type="button" id="image-clear">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div id="image-preview" class="mt-3" style="display: none;">
                                <div class="position-relative d-inline-block">
                                    <img src="" class="img-fluid" alt="Seçilen görsel">
                                </div>
                            </div>
                            @error('image')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="text-muted mt-2 d-block">
                                <i class="fas fa-info-circle"></i> Önerilen görsel boyutu: 1200x630 piksel
                            </small>
                        </div>
                                
                        <div class="mb-4">
                            <label class="form-label d-flex justify-content-between align-items-center">
                                <span>Galeri</span>
                                <small class="text-muted">En fazla 10 görsel ekleyebilirsiniz</small>
                            </label>
                            <div class="d-flex mb-3">
                                <button type="button" id="gallery-browser" class="btn btn-outline-primary" data-input="fake-gallery-input">
                                    <i class="fas fa-images me-1"></i> Görsel Ekle
                                </button>
                            </div>
                            <input type="hidden" id="fake-gallery-input" class="fake-gallery-input">
                            <div class="gallery-container" id="gallery-preview"></div>
                            <div id="gallery-inputs"></div>
                            @error('gallery')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            @error('gallery.*')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Hero Özellikleri -->
                        <div class="mb-4">
                            <label class="form-label d-flex justify-content-between align-items-center">
                                <span>Hero Özellikleri</span>
                                <small class="text-muted">Hizmet detay sayfasında gösterilecek özellikler</small>
                            </label>
                            
                            <div class="card">
                                <div class="card-body">
                                    <p class="text-muted small mb-3">Bu özellikler hizmet detay sayfasında ana görsel üzerinde listelenecektir.</p>
                                    
                                    <div id="features-container">
                                        @if(old('features'))
                                            @foreach(old('features') as $index => $feature)
                                                <div class="feature-item mb-2 d-flex align-items-center">
                                                    <input type="text" class="form-control" name="features[]" value="{{ $feature }}" placeholder="Özellik ekleyin">
                                                    <button type="button" class="btn btn-sm btn-danger ms-2 remove-feature"><i class="fas fa-times"></i></button>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="feature-item mb-2 d-flex align-items-center">
                                                <input type="text" class="form-control" name="features[]" placeholder="Özellik ekleyin">
                                                <button type="button" class="btn btn-sm btn-danger ms-2 remove-feature"><i class="fas fa-times"></i></button>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <button type="button" id="add-feature" class="btn btn-sm btn-outline-primary mt-2">
                                        <i class="fas fa-plus me-1"></i> Özellik Ekle
                                    </button>
                                </div>
                            </div>
                            
                            @error('features')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            @error('features.*')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
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
                        <div class="mb-4">
                            <label for="meta_title" class="form-label">Meta Başlık</label>
                            <input type="text" class="form-control @error('meta_title') is-invalid @enderror" id="meta_title" name="meta_title" value="{{ old('meta_title') }}">
                            @error('meta_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Boş bırakılırsa hizmet başlığı kullanılacaktır.</small>
                        </div>
                                
                        <div class="mb-4">
                            <label for="meta_description" class="form-label">Meta Açıklama</label>
                            <textarea class="form-control @error('meta_description') is-invalid @enderror" id="meta_description" name="meta_description" rows="3">{{ old('meta_description') }}</textarea>
                            @error('meta_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Boş bırakılırsa hizmet özeti kullanılacaktır.</small>
                        </div>
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
                                                    <input type="checkbox" name="categories[]" value="{{ $category->id }}" class="form-check-input me-1" {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
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
                                        <input type="hidden" id="tags-input" name="tags" value="{{ old('tags') }}">
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

    </form>
</div>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('vendor/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet">
@endsection

@section('js')
<script src="{{ asset('vendor/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('vendor/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.tr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6.4.2/tinymce.min.js" referrerpolicy="origin"></script>
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
<script>
    // TinyMCE'yi dinamik olarak yükle
    var script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = '{{ asset("vendor/tinymce/tinymce/js/tinymce/tinymce.min.js") }}';
    
    script.onload = function() {
        console.log('TinyMCE yüklendi');
        
        // TinyMCE Editör
        tinymce.init({
            selector: '#content',
            language: 'tr',
            height: 500,
            menubar: true,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | blocks | ' +
                'bold italic backcolor | alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +
                'removeformat | image | help',
            images_upload_url: '{{ route("admin.tinymce.upload") }}',
            images_upload_credentials: true,
            branding: false,
            promotion: false,
            content_css: [
                '{{ asset("vendor/adminlte/dist/css/adminlte.min.css") }}',
                'https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700'
            ]
        });
    };
    
    document.head.appendChild(script);

    $(function() {
        // Laravel File Manager butonları
        $('#image-browser').filemanager('image');
        $('#gallery-browser').filemanager('image');
        
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
        
        // CKEditor
        ClassicEditor
            .create(document.querySelector('#content'), {
                toolbar: {
                    items: [
                        'heading', '|',
                        'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|',
                        'blockQuote', 'insertTable', '|',
                        'undo', 'redo', '|',
                        'alignment'
                    ]
                },
                language: 'tr'
            })
            .catch(error => {
                console.error(error);
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

        // Slug oluşturma
        function slugify(text) {
            var trMap = {
                'çÇ':'c', 'ğĞ':'g', 'şŞ':'s', 'üÜ':'u', 'ıİ':'i', 'öÖ':'o'
            };
            for(var key in trMap) {
                text = text.replace(new RegExp('['+key+']','g'), trMap[key]);
            }
            return text.toString().toLowerCase()
                .replace(/\s+/g, '-')           // Replace spaces with -
                .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
                .replace(/\-\-+/g, '-')         // Replace multiple - with single -
                .replace(/^-+/, '')             // Trim - from start of text
                .replace(/-+$/, '');            // Trim - from end of text
        }
        
        function updateSlugPreview() {
            var slug = $('#slug').val() || slugify($('#title').val()) || '-';
            $('#slug-preview').text(slug);
        }

        $('#title').on('input', function() {
            if ($('#slug').val() === '') {
                updateSlugPreview();
            }
        });

        $('#slug').on('input', updateSlugPreview);

        $('#slug-regenerate').on('click', function() {
            var newSlug = slugify($('#title').val());
            $('#slug').val(newSlug);
            updateSlugPreview();
        });

        // İlk yükleme sırasında slug önizlemeyi güncelle
        updateSlugPreview();
        
        // Kategori ve Etiket işlemleri
        // Kategori sayacını güncelle
        function updateCategoryCount() {
            const checkedCategories = document.querySelectorAll('input[name="categories[]"]:checked');
            const categoryCount = document.querySelector('.category-count');
            categoryCount.textContent = checkedCategories.length;
            
            // Seçili kategoriler listesini güncelle
            updateSelectedCategoriesList();
        }
        
        // Seçili kategoriler listesini güncelle
        function updateSelectedCategoriesList() {
            const selectedCategoriesList = document.getElementById('selected-categories-list');
            selectedCategoriesList.innerHTML = '';
            
            const checkedCategories = document.querySelectorAll('input[name="categories[]"]:checked');
            
            checkedCategories.forEach(checkbox => {
                const categoryName = checkbox.closest('label').querySelector('.category-name').textContent;
                const categoryBadge = document.createElement('div');
                categoryBadge.className = 'category-badge';
                categoryBadge.innerHTML = `
                    <span>${categoryName}</span>
                    <i class="fas fa-times remove-category" data-id="${checkbox.value}"></i>
                `;
                selectedCategoriesList.appendChild(categoryBadge);
            });
        }
        
        // Kategori seçimi değişince sayacı güncelle
        document.querySelectorAll('input[name="categories[]"]').forEach(checkbox => {
            checkbox.addEventListener('change', updateCategoryCount);
        });
        
        // Tümünü seç butonu
        document.getElementById('select-all-categories').addEventListener('click', function() {
            const checkboxes = document.querySelectorAll('input[name="categories[]"]');
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            
            checkboxes.forEach(cb => {
                cb.checked = !allChecked;
            });
            
            updateCategoryCount();
        });
        
        // Seçili kategorileri kaldırma (Event delegation)
        document.getElementById('selected-categories-list').addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-category')) {
                const categoryId = e.target.dataset.id;
                const checkbox = document.querySelector(`input[name="categories[]"][value="${categoryId}"]`);
                if (checkbox) {
                    checkbox.checked = false;
                    updateCategoryCount();
                }
            }
        });
        
        // Etiket işlemleri
        const tagInput = document.getElementById('tag-input');
        const addTagBtn = document.getElementById('add-tag-btn');
        const selectedTagsList = document.getElementById('selected-tags-list');
        const tagsInput = document.getElementById('tags-input');
        const tagCount = document.querySelector('.tag-count');
        
        // Mevcut etiketleri diziye çevir
        let selectedTags = tagsInput.value ? tagsInput.value.split(',').map(tag => tag.trim()) : [];
        
        // İlk yüklemede etiket görüntüsünü güncelle
        updateTagsDisplay();
        
        // Etiket ekleme butonu
        addTagBtn.addEventListener('click', function() {
            addTag();
        });
        
        // Enter tuşu ile etiket ekleme
        tagInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addTag();
            }
        });
        
        // Virgül ile etiket ekleme
        tagInput.addEventListener('input', function(e) {
            const value = e.target.value;
            if (value.includes(',')) {
                const tags = value.split(',');
                const lastTag = tags.pop().trim();
                
                tags.forEach(tag => {
                    if (tag.trim()) {
                        addTagToList(tag.trim());
                    }
                });
                
                e.target.value = lastTag;
            }
        });
        
        // Etiket ekleme fonksiyonu
        function addTag() {
            const tagValue = tagInput.value.trim();
            if (tagValue) {
                addTagToList(tagValue);
                tagInput.value = '';
                tagInput.focus();
            }
        }
        
        // Listeye etiket ekleme
        function addTagToList(tag) {
            // Aynı etiket varsa ekleme
            if (!selectedTags.includes(tag)) {
                selectedTags.push(tag);
                updateTagsDisplay();
            }
        }
        
        // Etiket görüntüsünü güncelle
        function updateTagsDisplay() {
            // Etiketleri hidden input'a ekle
            tagsInput.value = selectedTags.join(',');
            
            // Etiket sayacını güncelle
            tagCount.textContent = selectedTags.length;
            
            // Etiket listesini temizle ve yeniden oluştur
            selectedTagsList.innerHTML = '';
            
            selectedTags.forEach(tag => {
                const tagBadge = document.createElement('div');
                tagBadge.className = 'category-badge';
                tagBadge.innerHTML = `
                    <span>#${tag}</span>
                    <i class="fas fa-times remove-tag" data-tag="${tag}"></i>
                `;
                selectedTagsList.appendChild(tagBadge);
            });
        }
        
        // Etiket silme işlevi (Event delegation)
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
    });
</script>
@endsection 