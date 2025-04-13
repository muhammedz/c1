@extends('adminlte::page')

@section('title', 'Haber Düzenle')

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
    
    .ts-dropdown {
        border: 1px solid rgba(0, 0, 0, 0.15);
        border-radius: 0.375rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    
    .ts-dropdown .active {
        background-color: #e9ecef;
        color: #1e2125;
    }
    
    .ts-wrapper.multi .ts-control > div {
        background: #e9ecef;
        border: none;
        border-radius: 0.25rem;
        color: #212529;
        padding: 2px 8px;
        margin: 2px;
    }
    
    .ts-wrapper.plugin-remove_button .item .remove {
        border-left: 1px solid #dee2e6;
        padding-left: 6px;
        margin-left: 6px;
        color: #dc3545;
    }
    
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
    
    .help-tooltip {
        color: #6c757d;
        cursor: help;
        margin-left: 0.5rem;
    }
    
    .help-tooltip:hover {
        color: #495057;
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
    
    .category-badge .remove {
        margin-left: 0.5rem;
        color: #dc3545;
        cursor: pointer;
    }
    
    .tag-item {
        display: inline-flex;
        align-items: center;
        background: #e9ecef;
        border-radius: 0.25rem;
        padding: 0.25rem 0.5rem;
        margin: 0.125rem;
        font-size: 0.875rem;
    }
    
    .btn-primary {
        background-color: #3490dc;
        border-color: #3490dc;
        padding: 0.6rem 1.2rem;
    }
    
    .btn-primary:hover {
        background-color: #2779bd;
        border-color: #2779bd;
    }
    
    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
        padding: 0.6rem 1.2rem;
    }
    
    .form-check {
        padding: 1rem;
        background: #f8fafc;
        border-radius: 6px;
        margin-bottom: 1rem;
    }
    
    .text-danger {
        color: #e3342f !important;
    }
    
    .text-muted {
        color: #6c757d !important;
    }
    
    .datepicker {
        border-radius: 6px;
        box-shadow: 0 2px 6px rgba(0,0,0,.15);
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
    
    /* TomSelect Özelleştirmeleri - Kategoriler için */
    .ts-wrapper.categories {
        width: 100%;
        font-family: inherit;
    }
    
    .ts-wrapper.categories .ts-control {
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        padding: 0.5rem;
        min-height: 120px;
        background: #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.04);
        transition: all 0.2s ease-in-out;
    }
    
    .ts-wrapper.categories .ts-control:hover {
        border-color: #a1a9b3;
    }
    
    .ts-wrapper.categories.focus .ts-control {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
    }
    
    .ts-wrapper.categories .ts-dropdown {
        border: 1px solid rgba(0,0,0,0.1);
        border-radius: 0.375rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        background: #fff;
    }
    
    .ts-wrapper.categories .option {
        padding: 8px 12px;
        border-bottom: 1px solid #f8f9fa;
        transition: all 0.2s ease;
    }
    
    .ts-wrapper.categories .option:hover {
        background-color: #f8f9fa;
    }
    
    .ts-wrapper.categories .option.active {
        background-color: #e9ecef;
    }
    
    .ts-wrapper.categories .ts-dropdown .create {
        padding: 8px 12px;
        color: #007bff;
    }
    
    .ts-wrapper.categories .item {
        background: #007bff !important;
        color: #fff !important;
        border-radius: 20px !important;
        padding: 2px 10px !important;
        margin: 2px !important;
    }
    
    .ts-wrapper.categories .item.active {
        background: #0056b3 !important;
    }
    
    .category-info {
        padding: 8px 12px;
        background-color: #f8f9fa;
        border-radius: 4px;
        border-left: 3px solid #007bff;
    }

    .wp-category-select {
        background: #fff;
    }

    .category-tabs {
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
    }

    .tab-header {
        background: #f8f9fa;
    }

    .category-list {
        background: #fff;
        border: 1px solid #eee;
        border-radius: 4px;
        padding: 0.5rem;
    }

    .category-item .form-check {
        margin: 0;
        padding: 0;
    }

    .category-item .form-check-input {
        margin-top: 0.3em;
    }

    .category-item .form-check-label {
        font-size: 0.9rem;
        color: #333;
        cursor: pointer;
        padding: 2px 0;
    }

    .category-item {
        transition: background-color 0.15s;
        border: 1px solid transparent;
        margin-bottom: 2px;
    }

    .category-item:hover {
        background-color: #f0f0f0;
        border-color: #dee2e6;
    }

    .selected-categories {
        border-top: 1px solid #dee2e6;
        padding-top: 1rem;
        margin-top: 1rem;
    }

    .selected-category-badge {
        display: inline-flex;
        align-items: center;
        background: #e9ecef;
        border-radius: 20px;
        padding: 4px 12px;
        font-size: 0.875rem;
        color: #495057;
    }

    .selected-category-badge .remove-category {
        margin-left: 6px;
        color: #dc3545;
        cursor: pointer;
        font-size: 0.8rem;
    }

    .selected-category-badge .remove-category:hover {
        color: #bd2130;
    }

    /* Kategori arama kutusuna ait stili kaldırıyorum */
    .category-wrapper {
        position: relative;
    }

    .category-item {
        transition: background-color 0.2s;
        border: 1px solid transparent;
    }

    .category-item:hover {
        background-color: #f8f9fa;
        border-color: #dee2e6;
    }

    .category-item .form-check-label {
        cursor: pointer;
        font-size: 0.95rem;
    }

    .category-badge {
        display: inline-flex;
        align-items: center;
        background: #e9ecef;
        padding: 0.35rem 0.75rem;
        border-radius: 50px;
        font-size: 0.875rem;
        color: #495057;
        gap: 0.5rem;
    }

    .category-badge .remove-category {
        color: #dc3545;
        cursor: pointer;
        opacity: 0.7;
        transition: opacity 0.2s;
    }

    .category-badge .remove-category:hover {
        opacity: 1;
    }

    .hover-bg-light:hover {
        background-color: #f8f9fa;
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
    
    .status-option.active::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 6px;
        height: 100%;
        background-color: #3490dc;
    }

    .status-option.active::after {
        content: '✓';
        position: absolute;
        top: 5px;
        right: 6px;
        font-size: 14px;
        color: #3490dc;
        font-weight: bold;
    }
    
    .status-card.compact .status-icon {
        font-size: 1.3rem;
        transition: all 0.3s ease;
    }
    
    .status-option:hover .status-icon,
    .status-option.active .status-icon {
        transform: scale(1.2);
        color: #3490dc !important;
    }
    
    .status-option .status-title {
        font-weight: 500;
        font-size: 0.95rem;
        transition: all 0.2s ease;
    }
    
    .status-option.active .status-title {
        color: #3490dc !important;
        font-weight: 700 !important;
    }
    
    /* Validation hata mesajları için */
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
</style>
@endsection

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2">Haber Düzenle</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.news.index') }}" class="text-decoration-none">Haberler</a></li>
        <li class="breadcrumb-item active">Haber Düzenle</li>
    </ol>
            </nav>
        </div>
        
        <div>
            <a href="{{ route('admin.news.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Geri Dön
            </a>
        </div>
    </div>
    
            @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
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
            
            <form action="{{ route('admin.news.update', $news->id) }}" method="POST" id="news-form">
                @csrf
                @method('PUT')
                
                <div class="row">
            <div class="col-lg-8">
                        <!-- Ana İçerik -->
                <div class="card mb-4">
                            <div class="card-body">
                        <div class="mb-4">
                                    <label for="title" class="form-label">Başlık <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $news->title) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                            
                            <!-- Slug ve URL Önizleme -->
                            <div class="mt-2 p-2 bg-light border rounded">
                                <div class="small text-muted mb-1">
                                    <i class="fas fa-link me-1"></i> Oluşturulacak URL:
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="text-secondary">{{ url('/') }}/haber/</span>
                                    <span class="text-primary fw-bold" id="slug-preview">{{ $news->slug }}</span>
                                </div>
                                <div class="small text-muted mt-1">
                                    <i class="fas fa-info-circle me-1"></i> Slug:
                                </div>
                                <div class="input-group mt-1">
                                    <input type="text" class="form-control form-control-sm" id="slug" name="slug" value="{{ $news->slug }}" placeholder="Otomatik oluşturulur">
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
                            <textarea class="form-control @error('summary') is-invalid @enderror" id="summary" name="summary" rows="3" style="height: 100px;">{{ old('summary', $news->summary) }}</textarea>
                                    @error('summary')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                            <small class="text-muted">Haber listesinde ve sosyal medya paylaşımlarında görünecek kısa açıklama.</small>
                                </div>
                                
                        <div class="mb-4">
                                    <label for="content" class="form-label">İçerik <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="10">{{ old('content', $news->content) }}</textarea>
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
                                        <input type="text" class="form-control @error('image') is-invalid @enderror" id="image" name="image" value="{{ old('image', $news->image) }}" required readonly>
                                <button class="btn btn-primary" type="button" id="image-browser">
                                    <i class="fas fa-folder-open"></i> Göz At
                                </button>
                                <button class="btn btn-danger" type="button" id="image-clear">
                                    <i class="fas fa-times"></i>
                                </button>
                                    </div>
                            <div id="image-preview" class="mt-3" style="{{ $news->image ? '' : 'display: none;' }}">
                                <div class="position-relative d-inline-block">
                                    <img src="{{ $news->image }}" class="img-fluid" alt="Seçilen görsel" style="max-height: 200px; max-width: 100%; width: auto; height: auto; object-fit: contain;">
                                </div>
                                    </div>
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
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
                                <button type="button" id="gallery-browser" class="btn btn-outline-primary">
                                    <i class="fas fa-images me-1"></i> Görsel Ekle
                                </button>
                            </div>
                            <div class="gallery-container" id="gallery-preview" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 15px;">
                                        @if(isset($news->gallery) && is_array($news->gallery))
                                            @foreach($news->gallery as $galleryImage)
                                        @if(!empty($galleryImage))
                                                <div class="gallery-item" data-url="{{ $galleryImage }}">
                                                    <img src="{{ $galleryImage }}" alt="Gallery Image">
                                                <button type="button" class="remove-btn" data-url="{{ $galleryImage }}">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                                    <input type="hidden" name="gallery[]" value="{{ $galleryImage }}">
                                                </div>
                                        @endif
                                            @endforeach
                                        @endif
                                    </div>
                            <div id="gallery-inputs"></div>
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
                                    <input type="text" class="form-control @error('meta_title') is-invalid @enderror" id="meta_title" name="meta_title" value="{{ old('meta_title', $news->meta_title) }}">
                                    @error('meta_title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                            <small class="text-muted">Boş bırakılırsa haber başlığı kullanılacaktır.</small>
                                </div>
                                
                        <div class="mb-4">
                                    <label for="meta_description" class="form-label">Meta Açıklama</label>
                                    <textarea class="form-control @error('meta_description') is-invalid @enderror" id="meta_description" name="meta_description" rows="3">{{ old('meta_description', $news->meta_description) }}</textarea>
                                    @error('meta_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                            <small class="text-muted">Boş bırakılırsa haber özeti kullanılacaktır.</small>
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
                                <input type="hidden" name="status" id="status-input" value="{{ old('status', $news->status) }}" required>
                                    @error('status')
                                    <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                            </label>
                            
                            <div class="status-selector d-flex gap-2 mt-2">
                                <div class="status-card compact flex-grow-1" data-status="published" style="max-width: 50%; margin: 0 auto;">
                                    <div class="card h-100 status-option {{ old('status', $news->status) == 'published' ? 'border-primary active' : 'border' }}" style="cursor: pointer; transition: all 0.3s ease; border-width: 3px !important; border-radius: 12px !important; overflow: hidden; position: relative; box-shadow: {{ old('status', $news->status) == 'published' ? '0 0 10px rgba(52,144,220,0.5)' : 'none' }}; background-color: {{ old('status', $news->status) == 'published' ? '#e6f2ff' : 'white' }};">
                                        
                                        @if(old('status', $news->status) == 'published')
                                        <div style="position: absolute; top: 0; left: 0; width: 8px; height: 100%; background-color: #3490dc;"></div>
                                        <div style="position: absolute; top: 5px; right: 6px; font-size: 14px; color: #3490dc; font-weight: bold;">✓</div>
                                        @endif
                                        
                                        <div class="card-body p-2 text-center">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <i class="fas fa-check-circle status-icon me-2" style="font-size: 1.2rem; color: {{ old('status', $news->status) == 'published' ? '#3490dc' : '#6c757d' }}; transition: all 0.3s ease;"></i>
                                                <span class="status-title" style="font-weight: {{ old('status', $news->status) == 'published' ? '700' : '500' }}; font-size: 0.9rem; color: {{ old('status', $news->status) == 'published' ? '#3490dc' : '#212529' }};">Yayında</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="status-card compact flex-grow-1" data-status="draft" style="max-width: 50%; margin: 0 auto;">
                                    <div class="card h-100 status-option {{ old('status', $news->status) == 'draft' ? 'border-primary active' : 'border' }}" style="cursor: pointer; transition: all 0.3s ease; border-width: 3px !important; border-radius: 12px !important; overflow: hidden; position: relative; box-shadow: {{ old('status', $news->status) == 'draft' ? '0 0 10px rgba(52,144,220,0.5)' : 'none' }}; background-color: {{ old('status', $news->status) == 'draft' ? '#e6f2ff' : 'white' }};">
                                        
                                        @if(old('status', $news->status) == 'draft')
                                        <div style="position: absolute; top: 0; left: 0; width: 8px; height: 100%; background-color: #3490dc;"></div>
                                        <div style="position: absolute; top: 5px; right: 6px; font-size: 14px; color: #3490dc; font-weight: bold;">✓</div>
                                        @endif
                                        
                                        <div class="card-body p-2 text-center">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <i class="fas fa-edit status-icon me-2" style="font-size: 1.2rem; color: {{ old('status', $news->status) == 'draft' ? '#3490dc' : '#6c757d' }}; transition: all 0.3s ease;"></i>
                                                <span class="status-title" style="font-weight: {{ old('status', $news->status) == 'draft' ? '700' : '500' }}; font-size: 0.9rem; color: {{ old('status', $news->status) == 'draft' ? '#3490dc' : '#212529' }};">Taslak</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                                    <label for="published_at" class="form-label">Yayın Tarihi</label>
                            <input type="text" class="form-control datepicker @error('published_at') is-invalid @enderror" id="published_at" name="published_at" value="{{ old('published_at', $news->published_at ? $news->published_at->format('Y-m-d') : '') }}" autocomplete="off">
                                    @error('published_at')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                        <div class="mb-4">
                                    <label for="end_date" class="form-label">Bitiş Tarihi</label>
                            <input type="text" class="form-control datepicker @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ old('end_date', $news->end_date ? $news->end_date->format('Y-m-d') : '') }}" autocomplete="off">
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                            <small class="text-muted">Belirtilirse, bu tarihte haber otomatik olarak yayından kaldırılır.</small>
                                </div>
                                
                        <div class="form-check mb-3">
                                    <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" {{ old('is_featured', $news->is_featured) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">
                                <strong>Öne Çıkar</strong>
                                <small class="d-block text-muted">Haber ana sayfada öne çıkarılacaktır.</small>
                            </label>
                                </div>
                                
                        <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="is_headline" name="is_headline" {{ old('is_headline', $news->is_headline) ? 'checked' : '' }} {{ $maxHeadlinesReached && !$news->is_headline ? 'disabled' : '' }}>
                            <label class="form-check-label" for="is_headline">
                                <strong>Manşet</strong>
                                <small class="d-block text-muted">Haber manşet bölümünde gösterilecektir.</small>
                            </label>
                                    @if($maxHeadlinesReached && !$news->is_headline)
                                <div class="text-danger small mt-2">Maksimum manşet sayısına ulaşıldı (4).</div>
                                    @endif
                                </div>
                                
                        <div class="mt-4">
                                    <div class="card border-info">
                                        <div class="card-body p-2">
                                            <div class="row g-0">
                                                <div class="col-6">
                                                    <small class="d-block text-muted">Oluşturulma:</small>
                                                    <small>{{ $news->created_at->format('d.m.Y H:i') }}</small>
                                                </div>
                                                <div class="col-6">
                                                    <small class="d-block text-muted">Görüntülenme:</small>
                                                    <small>{{ $news->view_count ?? 0 }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                                        @foreach($newsCategories as $category)
                                    <div class="category-item d-flex align-items-center py-1 px-2 mb-1 rounded hover-bg-light">
                                        <div class="form-check mb-0 w-100">
                                            <label class="d-flex align-items-center gap-1">
                                                <input type="checkbox" name="categories[]" value="{{ $category->id }}" class="form-check-input me-1" {{ in_array($category->id, old('categories', $selectedCategories)) ? 'checked' : '' }}>
                                                @if($category->icon)
                                                    <i class="{{ $category->icon }}" style="margin-right: 3px; font-size: 0.9em;"></i>
                                                @endif
                                                <span class="category-name">{{ $category->name }}</span>
                                                @if($category->description)
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
                                <input type="hidden" id="tags" name="tags" value="{{ old('tags', $tags) }}">
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Kaydet -->
                <div class="card">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-save me-2"></i> Haberi Güncelle
                        </button>
                </div>
        </div>
    </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.tr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

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
</script>

<script>
    // Form öğelerini hazırla
    $(document).ready(function() {
    // Tarih Seçici
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true,
        language: 'tr'
    });
    
    // Ana Görsel Seçici
    $('#image-browser').on('click', function() {
            window.open('/filemanager?type=Images&_token={{ csrf_token() }}', 'FileManager', 'width=1000,height=700');
            
            window.SetUrl = function(items) {
                if (!items || !items.length) return;
                
                var fileUrl = items[0].url;
                console.log("Seçilen dosya:", fileUrl);
                
                // Form input'unu güncelle
                $('#image').val(fileUrl);
                
                // Önizleme göster
                var img = $('#image-preview img');
                img.attr('src', fileUrl);
                $('#image-preview').show();
            };
        });
    
    // Görsel Temizle
    $('#image-clear').on('click', function() {
        $('#image').val('');
        $('#image-preview').hide();
    });
    
        // Etiket Yönetimi
        const tagInput = document.getElementById('tag-input');
        const addTagBtn = document.getElementById('add-tag-btn');
        const selectedTagsList = document.getElementById('selected-tags-list');
        const tagsInput = document.getElementById('tags');
        const tagCount = document.querySelector('.tag-count');
        
        // Mevcut etiketleri yükle (varsa)
        let selectedTags = [];
        if (tagsInput.value) {
            selectedTags = tagsInput.value.split(',').map(tag => tag.trim()).filter(tag => tag);
            updateTagsDisplay();
        }
        
        // Etiket ekle butonuna tıklandığında
        addTagBtn.addEventListener('click', function() {
            addTag();
        });
        
        // Enter tuşuna basıldığında etiket ekle
        tagInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addTag();
            }
            
            // Virgül tuşuna basıldığında da etiket ekle
            if (e.key === ',') {
                e.preventDefault();
                addTag();
            }
        });
        
        // Etiket ekleme işlevi
        function addTag() {
            const tagValue = tagInput.value.trim();
            
            if (tagValue) {
                // Virgülle ayrılmış birden fazla etiket olabilir
                const tags = tagValue.split(',').map(tag => tag.trim()).filter(tag => tag);
                
                tags.forEach(tag => {
                    // Eğer etiket zaten eklenmediyse ekle
                    if (!selectedTags.includes(tag)) {
                        selectedTags.push(tag);
                    }
                });
                
                // Input alanını temizle
                tagInput.value = '';
                
                // Etiketleri güncelle
                updateTagsDisplay();
            }
        }
        
        // Etiketleri görüntüleme işlevi
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
        
        // Galeri için FileManager entegrasyonu
        $('#gallery-browser').on('click', function() {
            window.open('/filemanager?type=Images&_token={{ csrf_token() }}', 'FileManager', 'width=1000,height=700');
            
            window.SetUrl = function(items) {
                if (!items || !items.length) return;
                
                // Birden fazla resim eklenebilir
                items.forEach(function(item) {
                    var url = item.url;
                    console.log("Galeriye eklenen dosya:", url);
                    addGalleryItem(url);
                });
            };
        });
        
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
    });
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Kategori sistemi
    const selectAllBtn = document.getElementById('select-all-categories');
    const categoryItems = document.querySelectorAll('.category-item');
    const selectedCategoriesList = document.getElementById('selected-categories-list');
    const categoryCount = document.querySelector('.category-count');
    
    // Slug ve URL önizleme işlevi
    const titleInput = document.getElementById('title');
    const slugPreview = document.getElementById('slug-preview');
    const slugInput = document.getElementById('slug');
    const slugRegenerateBtn = document.getElementById('slug-regenerate');
    
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
    
    // Tümünü seç/kaldır
    selectAllBtn.addEventListener('click', function() {
        const checkboxes = document.querySelectorAll('input[name="categories[]"]');
        const allChecked = Array.from(checkboxes).every(cb => cb.checked);
        checkboxes.forEach(cb => {
            cb.checked = !allChecked;
        });
        updateSelectedCategories();
    });

    // Seçili kategorileri güncelle
    function updateSelectedCategories() {
        selectedCategoriesList.innerHTML = '';
        const selectedCheckboxes = document.querySelectorAll('input[name="categories[]"]:checked');
        
        selectedCheckboxes.forEach(cb => {
            try {
                let categoryName = '';
                
                // Etiket içeriğini al
                const label = cb.closest('label');
                if (label) {
                    // Tüm içeriği al ve gereksiz metinleri temizle
                    categoryName = label.textContent.trim();
                }
                
                // Seçili kategori rozeti oluştur
                const badge = document.createElement('div');
                badge.className = 'category-badge';
                badge.innerHTML = `
                    <span>${categoryName}</span>
                    <i class="fas fa-times remove-category" data-id="${cb.value}"></i>
                `;
                selectedCategoriesList.appendChild(badge);
            } catch (error) {
                console.error('Kategori güncelleme hatası:', error);
            }
        });

        // Kategori sayısını güncelle
        categoryCount.textContent = selectedCheckboxes.length;
    }

    // Kategori seçimi değiştiğinde
    document.querySelectorAll('input[name="categories[]"]').forEach(cb => {
        cb.addEventListener('change', updateSelectedCategories);
    });

    // Seçili kategori kaldırma
    selectedCategoriesList.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-category')) {
            const categoryId = e.target.dataset.id;
            const checkbox = document.querySelector(`input[value="${categoryId}"]`);
            if (checkbox) {
                checkbox.checked = false;
                updateSelectedCategories();
            }
        }
    });

    // Başlangıçta seçili kategorileri göster
    updateSelectedCategories();
    
    // Tooltip'leri etkinleştir
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }

    // Durum seçim kartları işlevselliği
    const statusOptions = document.querySelectorAll('.status-option');
    const statusInput = document.getElementById('status-input');
    
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
    });
</script>
@endpush
@endsection 