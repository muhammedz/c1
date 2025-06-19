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

    /* Belge yönetimi stilleri */
    .document-item {
        transition: all 0.3s ease;
        background-color: #f8f9fa;
    }
    
    .document-item:hover {
        background-color: #e9ecef;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .document-icon {
        font-size: 1.5rem;
        width: 40px;
        text-align: center;
    }
    
    .file-icon-pdf { color: #dc3545; }
    .file-icon-doc, .file-icon-docx { color: #2b579a; }
    .file-icon-xls, .file-icon-xlsx { color: #217346; }
    .file-icon-ppt, .file-icon-pptx { color: #d24726; }
    .file-icon-txt { color: #6c757d; }
    .file-icon-zip, .file-icon-rar { color: #ffc107; }
    .file-icon-default { color: #6c757d; }
    
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

    /* FileManagerSystem Görsel Alanı */
    #filemanagersystem_image_preview {
        max-width: 100%;
        margin-top: 10px;
    }
    
    #filemanagersystem_image_preview img {
        max-width: 100%;
        max-height: 200px;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0,0,0,.1);
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
            
            @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Hata Detayı:</strong> {{ session('error') }}
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
            
            <form action="{{ route('admin.news.update', $news->id) }}" method="POST" id="news-form" enctype="multipart/form-data">
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
                        
                        <!-- Haber Görseli -->
                <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Haber Görseli</h5>
                            </div>
                            <div class="card-body">
                                <!-- FileManagerSystem Görsel -->
                        <div class="mb-4">
                                    <label for="filemanagersystem_image" class="form-label">Ana Görsel</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control @error('image') is-invalid @enderror" id="filemanagersystem_image" name="image" value="{{ old('image', $news->image) }}" readonly>
                                <button class="btn btn-primary" type="button" id="filemanagersystem_image_button">
                                    <i class="fas fa-folder-open"></i> Göz At
                                </button>
                                <button class="btn btn-danger" type="button" id="filemanagersystem_image_clear">
                                    <i class="fas fa-times"></i>
                                </button>
                                    </div>
                                    <div id="filemanagersystem_image_preview" class="mt-3" style="{{ $news->image ? '' : 'display: none;' }}">
                                <div class="position-relative d-inline-block">
                                            <img src="{{ $news->image ? asset($news->image) : '' }}" class="img-fluid" alt="Seçilen görsel" style="max-height: 200px; max-width: 100%; width: auto; height: auto; object-fit: contain;">
                                </div>
                                    </div>
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="alert alert-warning mt-2" id="image-warning">
                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                        <strong>Not:</strong> Ana görsel alanı opsiyoneldir. İsterseniz bir görsel seçebilirsiniz.
                                    </div>
                            <small class="text-muted mt-2 d-block">
                                <i class="fas fa-info-circle"></i> Önerilen görsel boyutu: 1200x630 piksel
                            </small>
                                </div>
                                
                                <!-- Görsel Alt Metni -->
                        <div class="mb-4">
                            <label for="filemanagersystem_image_alt" class="form-label">Görsel Alt Metni</label>
                            <input type="text" class="form-control @error('filemanagersystem_image_alt') is-invalid @enderror" id="filemanagersystem_image_alt" name="filemanagersystem_image_alt" value="{{ old('filemanagersystem_image_alt', $news->filemanagersystem_image_alt) }}">
                            @error('filemanagersystem_image_alt')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                                <!-- Görsel Başlığı -->
                        <div class="mb-4">
                            <label for="filemanagersystem_image_title" class="form-label">Görsel Başlığı</label>
                            <input type="text" class="form-control @error('filemanagersystem_image_title') is-invalid @enderror" id="filemanagersystem_image_title" name="filemanagersystem_image_title" value="{{ old('filemanagersystem_image_title', $news->filemanagersystem_image_title) }}">
                            @error('filemanagersystem_image_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                        
                        <!-- Fotoğraf Galerisi -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-images me-2"></i>
                                    Fotoğraf Galerisi
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Habere ait fotoğraf galerisini oluşturmak için birden fazla resim yükleyebilirsiniz. 
                                        Resimler otomatik olarak dosya yönetim sistemine kaydedilecektir.
                                    </small>
                                </div>
                                
                                <!-- Toplu Resim Yükleme Alanı -->
                                <div class="gallery-upload-area" id="gallery-upload-area">
                                    <div class="upload-dropzone" id="gallery-dropzone">
                                        <div class="dropzone-content text-center">
                                            <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                            <h5>Resimleri Buraya Sürükleyin</h5>
                                            <p class="text-muted">veya tıklayarak seçin</p>
                                            <button type="button" class="btn btn-primary" id="select-gallery-files">
                                                <i class="fas fa-images me-1"></i>
                                                Resim Seç
                                            </button>
                                            <input type="file" id="gallery-file-input" multiple accept="image/*" style="display: none;">
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Yükleme Progress Bar -->
                                <div class="upload-progress mt-3" id="gallery-upload-progress" style="display: none;">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <small class="text-muted">Yükleniyor...</small>
                                        <small class="text-muted" id="upload-status">0/0</small>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                             role="progressbar" 
                                             style="width: 0%" 
                                             id="gallery-progress-bar"></div>
                                    </div>
                                </div>
                                
                                <!-- Galeri Önizleme -->
                                <div class="gallery-preview mt-4" id="gallery-preview" style="display: none;">
                                    <h6 class="mb-3">
                                        <i class="fas fa-eye me-1"></i>
                                        Galeri Önizleme
                                        <span class="badge bg-primary ms-2" id="gallery-count">0</span>
                                    </h6>
                                    
                                    <div class="gallery-grid" id="gallery-grid">
                                        <!-- Galeri resimleri buraya dinamik olarak eklenecek -->
                                    </div>
                                    
                                    <div class="gallery-actions mt-3">
                                        <button type="button" class="btn btn-outline-secondary btn-sm" id="clear-gallery">
                                            <i class="fas fa-trash me-1"></i>
                                            Tümünü Temizle
                                        </button>
                                        <button type="button" class="btn btn-outline-primary btn-sm" id="add-more-images">
                                            <i class="fas fa-plus me-1"></i>
                                            Daha Fazla Ekle
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Hidden Input for Gallery Data -->
                                <input type="hidden" name="filemanagersystem_gallery" id="filemanagersystem_gallery" value="{{ old('filemanagersystem_gallery', json_encode($news->filemanagersystem_gallery ?? [])) }}">
                                
                                @error('filemanagersystem_gallery')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
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
                            <input type="text" class="form-control datepicker @error('published_at') is-invalid @enderror" id="published_at" name="published_at" value="{{ old('published_at', $news->published_at ? $news->published_at->format('d.m.Y') : '') }}" autocomplete="off">
                                    @error('published_at')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                        <div class="mb-4">
                                    <label for="end_date" class="form-label">Bitiş Tarihi</label>
                            <input type="text" class="form-control datepicker @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ old('end_date', $news->end_date ? $news->end_date->format('d.m.Y') : '') }}" autocomplete="off">
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                            <small class="text-muted">Belirtilirse, bu tarihte haber otomatik olarak yayından kaldırılır.</small>
                                </div>
                                
                        <div class="form-check mb-3">
                                    <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $news->is_featured) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">
                                <strong>Öne Çıkar</strong>
                                <small class="d-block text-muted">Haber ana sayfada öne çıkarılacaktır.</small>
                            </label>
                                </div>
                                
                        <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="is_headline" name="is_headline" value="1" {{ old('is_headline', $news->is_headline) ? 'checked' : '' }} {{ $maxHeadlinesReached && !$news->is_headline ? 'disabled' : '' }}>
                            <label class="form-check-label" for="is_headline">
                                <strong>Manşet</strong>
                                <small class="d-block text-muted">Haber manşet bölümünde gösterilecektir.</small>
                            </label>
                                    @if($maxHeadlinesReached && !$news->is_headline)
                                <div class="text-danger small mt-2">Maksimum manşet sayısına ulaşıldı (4).</div>
                                    @endif
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
                
                <!-- Galeri -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Galeri</h5>
                    </div>
                    <div class="card-body">
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
                                @if(isset($news->filemanagersystem_gallery) && is_array($news->filemanagersystem_gallery))
                                    @foreach($news->filemanagersystem_gallery as $galleryImage)
                                        @if(!empty($galleryImage))
                                            <div class="gallery-item" data-url="{{ $galleryImage }}">
                                                <img src="{{ $galleryImage }}" alt="Gallery Image">
                                                <button type="button" class="remove-btn" data-url="{{ $galleryImage }}">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                                <input type="hidden" name="filemanagersystem_gallery[]" value="{{ $galleryImage }}">
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div id="gallery-inputs"></div>
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
                        <p class="text-muted mb-3">Bu haberin hitap ettiği hedef kitleleri seçin:</p>
                        
                        <div class="row">
                            @foreach($hedefKitleler as $hedefKitle)
                            <div class="col-md-6 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="hedef_kitleler[]" 
                                        id="hedef-kitle-{{ $hedefKitle->id }}" value="{{ $hedefKitle->id }}"
                                        {{ in_array($hedefKitle->id, $news->hedefKitleler->pluck('id')->toArray()) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="hedef-kitle-{{ $hedefKitle->id }}">
                                        {{ $hedefKitle->name }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Belgeler -->
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-file-alt me-2 text-primary"></i>
                            <h5 class="mb-0">Belgeler</h5>
                            <span class="badge bg-secondary ms-2">{{ $news->documents->count() }}</span>
                        </div>
                        <div>
                            <button type="button" class="btn btn-sm btn-primary" id="add-new-document">
                                <i class="fas fa-plus"></i> Yeni Belge Ekle
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($news->documents->count() > 0)
                            <!-- Mevcut Belgeler -->
                            <div class="mb-4">
                                <h6 class="mb-3">
                                    <i class="fas fa-list me-1"></i> Mevcut Belgeler
                                </h6>
                                
                                <div class="row">
                                    @foreach($news->documents as $document)
                                    <div class="col-md-6 mb-3">
                                        <div class="border rounded p-3 document-item" data-id="{{ $document->id }}">
                                            <div class="d-flex align-items-center">
                                                <i class="{{ $document->icon_class }} me-2"></i>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">{{ $document->name }}</h6>
                                                    @if($document->description)
                                                        <p class="text-muted small mb-1">{{ $document->description }}</p>
                                                    @endif
                                                    <small class="text-muted">{{ $document->file_name }} ({{ $document->formatted_size }})</small>
                                                </div>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="{{ route('admin.news.documents.download', [$news, $document]) }}" target="_blank">
                                                            <i class="fas fa-download me-1"></i> İndir
                                                        </a></li>
                                                        <li><button class="dropdown-item edit-document" data-id="{{ $document->id }}" data-name="{{ $document->name }}" data-description="{{ $document->description }}">
                                                            <i class="fas fa-edit me-1"></i> Düzenle
                                                        </button></li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li><button class="dropdown-item text-danger delete-document" data-id="{{ $document->id }}">
                                                            <i class="fas fa-trash me-1"></i> Sil
                                                        </button></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Henüz belge eklenmemiş.</p>
                            </div>
                        @endif

                        <!-- Yeni Belge Ekleme Formu -->
                        <div id="new-document-form" style="display: none;">
                            <hr>
                            <h6 class="mb-3">
                                <i class="fas fa-plus me-1"></i> Yeni Belge Ekle
                            </h6>
                            
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-1"></i>
                                Bu form ana formdan bağımsızdır. Belge yükledikten sonra sayfa yenilenecektir.
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

<!-- Belge Yükleme Formu (Ana formdan bağımsız) -->
<div id="document-upload-form-container" style="display: none;">
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-upload me-1"></i> Yeni Belge Yükle
            </h5>
        </div>
        <div class="card-body">
            <form id="document-upload-form" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="document_name">Belge Adı <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="document_name" name="name" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="document_file">Dosya <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="document_file" name="file" required
                                   accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip,.rar">
                        </div>
                    </div>
                </div>
                
                <div class="form-group mb-3">
                    <label for="document_description">Açıklama</label>
                    <textarea class="form-control" id="document_description" name="description" rows="2"></textarea>
                </div>
                
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload"></i> Belge Yükle
                    </button>
                    <button type="button" class="btn btn-secondary" id="cancel-document-form">
                        <i class="fas fa-times"></i> İptal
                    </button>
                </div>
            </form>
        </div>
    </div>
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
@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.tr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.4.2/tinymce.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script src="{{ asset('js/slug-helper.js') }}"></script>

<script>
    $(document).ready(function() {
    // TinyMCE Editör
    tinymce.init({
        selector: '#content',
        plugins: 'preview importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons',
        menubar: 'file edit view insert format tools table help',
        toolbar: 'undo redo | bold italic underline strikethrough | fontfamily image fontsize blocks | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen preview save print | insertfile media template link anchor codesample | ltr rtl',
        toolbar_sticky: true,
        image_advtab: false,
        height: 500,
        quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
        quickbars_insert_toolbar: 'quickimage | quicktable quicklink hr',
        quickbars_insert_toolbar_hover: false,
        quickbars_image_toolbar: false,
        noneditable_class: 'mceNonEditable',
        language: 'tr',
        language_url: '/js/tinymce/langs/tr.js', // Türkçe dil dosyası
        toolbar_mode: 'sliding',
        contextmenu: 'link table',  // image'i çıkardık
        skin: 'oxide',
        content_css: 'default',
        content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 16px; }',
            relative_urls: false,
            remove_script_host: false,
        convert_urls: true,
            branding: false,
            promotion: false,
        paste_data_images: true, // Panodaki resimlerin yapıştırılmasını sağlar
        automatic_uploads: false, // Otomatik yüklemeyi devre dışı bıraktık
        object_resizing: 'img',
        file_picker_types: 'file media', // image tipini de ekleyelim
        
        // Varsayılan resim dialogunu tamamen devre dışı bırak
        images_upload_handler: function (blobInfo, success, failure) {
            failure('Görsel yükleme devre dışı.');
        },
        
        // Görsel dialogu tamamen devre dışı bırak
        image_title: false,
        image_description: false, 
        image_advtab: false,
        image_uploadtab: false,
        
        // Varsayılan dosya seçici fonksiyonu override
        file_picker_callback: function (callback, value, meta) {
            // Resim ekleme işlemleri için FileManagerSystem kullanılacak
            if (meta.filetype === 'image') {
                // Geçici bir ID oluştur
                const tempId = Date.now();
                const relatedType = 'news_content';
            
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
        },
        
        // FileManagerSystem entegrasyonu için özel buton
        setup: function (editor) {
            // TinyMCE başlatıldığında image plugininin davranışını değiştir
            editor.on('PreInit', function() {
                // Image plugin komutlarını ele geçir ve engelle
                editor.on('BeforeExecCommand', function(e) {
                    if (e.command === 'mceImage') {
                        // FileManagerSystem'i aç
                        openFileManagerSystemPicker(editor);
                        e.preventDefault();
                        return false;
                    }
                });
                
                // Quickbars'ı özelleştir
                editor.contentStyles.push(`
                    .tox-toolbar-overlord {
                        font-size: 14px !important;
                    }
                    .tox-pop__dialog {
                        background-color: #fff !important;
                        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2) !important;
                        border-radius: 6px !important;
                    }
                    .tox-toolbar__group {
                        padding: 4px !important;
                    }
                    .tox-toolbar__primary {
                        background-color: #fff !important;
                    }
                    .tox-tbtn {
                        margin: 2px !important;
                    }
                    .tox-tbtn--enabled, .tox-tbtn:hover {
                        background-color: #e9ecef !important;
                    }
                    .tox-icon.tox-tbtn__icon-wrap {
                        font-size: 16px !important;
                    }
                `);
            });
            
            // TinyMCE başlatıldığında custom butonları ekle
            editor.on('init', function() {
                // Özel toolbar butonu ekle
                editor.ui.registry.addButton('customimage', {
                    icon: 'image',
                    tooltip: 'Resim Ekle/Düzenle',
                    onAction: function() {
                        openFileManagerSystemPicker(editor);
                    }
                });
                
                // QuickImage toolbarı için özel buton ekle
                editor.ui.registry.addButton('quickimage', {
                    icon: 'image',
                    tooltip: 'Hızlı Resim Ekleme',
                    onAction: function() {
                        openFileManagerSystemPicker(editor);
                    }
                });
                
                // Insert menüsündeki Image öğesini değiştir
                try {
                    // Önce varsa kaldır
                    editor.ui.registry.removeMenuItem('image');
                } catch (e) {
                    console.log('Image menü öğesi zaten kaldırılmış');
                }
                
                // Kendi özel öğemizi ekle
                editor.ui.registry.addMenuItem('image', {
                    text: 'Resim...',
                    icon: 'image',
                    onAction: function() {
                        openFileManagerSystemPicker(editor);
                    }
                });
            });
            
            // FileManagerSystem picker açma fonksiyonu
            function openFileManagerSystemPicker(editor) {
                // Geçici ID oluştur
                const tempId = Date.now();
                const relatedType = 'news_content';
                
                // MediaPicker URL
                const mediapickerUrl = '/admin/filemanagersystem/mediapicker?type=image&filter=all&related_type=' + relatedType + '&related_id=' + tempId;
                
                console.log('FileManagerSystem açılıyor:', mediapickerUrl);
                
                // iFrame'i güncelle
                $('#mediapickerFrame').attr('src', mediapickerUrl);
                
                // Modal'ı göster - Bootstrap 5 ile uyumlu
                var modal = new bootstrap.Modal(document.getElementById('mediapickerModal'));
                modal.show();
                
                // Mesaj dinleme işlevi
                function handleMediaSelection(event) {
                    try {
                        if (event.data && event.data.type === 'mediaSelected') {
                            console.log('Seçilen medya:', event.data);
                            
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
        },
        });

        // TomSelect
        new TomSelect('#tags', {
            plugins: ['remove_button'],
            persist: false,
            create: true,
            maxItems: 10
        });

        // Datepicker
    $('.datepicker').datepicker({
        format: 'dd.mm.yyyy',
        autoclose: true,
        todayHighlight: true,
        language: 'tr',
        weekStart: 1, // Pazartesi ile başla
        daysOfWeekDisabled: [], // Hiçbir günü devre dışı bırakma
        clearBtn: true, // Temizle butonu
        todayBtn: 'linked' // Bugün butonu
    });

    // Slug Oluşturma Fonksiyonu - SlugHelper kullanımı
    function slugify(text) {
        return SlugHelper.create(text);
    }
    
    // Başlık alanı değiştiğinde slug oluştur
    $('#title').on('input', function() {
        const title = $(this).val();
        if (title) {
            const slug = slugify(title);
            $('#slug').val(slug);
            $('#slug-preview').text(slug);
        } else {
            $('#slug').val('');
            $('#slug-preview').text('-');
        }
    });
    
    // Slug yenileme butonu
    $('#slug-regenerate').on('click', function() {
        const title = $('#title').val();
        if (title) {
            const slug = slugify(title);
            $('#slug').val(slug);
            $('#slug-preview').text(slug);
        }
    });

    // Debug işlevleri 
    function updateDebugInfo() {
        const inputVal = $('#filemanagersystem_image').val();
        $('#debug-input-value').text(inputVal ? inputVal : 'Boş');
        $('#debug-image-url').text($('#filemanagersystem_image_preview img').attr('src') || 'Görsel seçilmedi');
        $('#debug-selection-time').text(new Date().toLocaleTimeString());
    }
    
    // Debug kontrol butonu
    $('#debug-check-button').on('click', function() {
        updateDebugInfo();
        console.log('Debug değerleri güncellendi');
        console.log('Form gönderilirken input değeri:', $('#filemanagersystem_image').val());
        alert('Input değeri: ' + $('#filemanagersystem_image').val() + '\nGörsel zamanı: ' + $('#debug-selection-time').text());
    });
    
    // Form submit olmadan önce değerleri kontrol et
    $('#news-form').on('submit', function(e) {
        // Görsel değerini kontrol et
        const imageVal = $('#filemanagersystem_image').val();
        console.log('Form gönderilmeden önce görsel değeri:', imageVal);
        
        // Eğer görsel seçilmemişse uyarı göster ve formu durdur
        if (!imageVal || imageVal.trim() === '') {
            $('#image-warning').show();
            // Form gönderimini düzgün şekilde yapabilmesi için bu kısımda return false; yapmıyoruz
        } else {
            $('#image-warning').hide();
        }
        
        // Görsel önizlemesini zorla
        if (imageVal) {
            $('#filemanagersystem_image_preview').show();
            $('#filemanagersystem_image_preview img').attr('src', imageVal);
        }
        
        // Debug panelini güncelle
        updateDebugInfo();
        
        // Form içeriğini formData olarak topla ve logla
        const formData = new FormData(this);
        console.log('Form gönderilirken filemanagersystem_image değeri:', formData.get('filemanagersystem_image'));
        
        // Formdaki tüm değerleri konsola yazdır
        for (let [key, value] of formData.entries()) {
            console.log(key + ': ' + value);
        }
        
        // Birkaç önemli değeri debug ekranına yazdır
        $('#debug-info').append('<hr><p class="mt-2"><strong>Form gönderim değerleri:</strong></p>');
        $('#debug-info').append('<p>filemanagersystem_image: <code>' + formData.get('filemanagersystem_image') + '</code></p>');
        $('#debug-info').append('<p>title: <code>' + formData.get('title') + '</code></p>');
        
        // Form kontrolü başarılıysa normal devam et
        return true;
    });

    // Sayfa yüklendiğinde filemanagersystem_image değerini kontrol et ve önizlemeyi göster
    const initialImageValue = $('#filemanagersystem_image').val();
    if (initialImageValue) {
        console.log('Mevcut görsel değeri:', initialImageValue);
        $('#filemanagersystem_image_preview').show();
        $('#filemanagersystem_image_preview img').attr('src', initialImageValue);
        $('#image-warning').hide(); // Görsel varsa uyarıyı gizle
        updateDebugInfo();
    }

    // Kategori seçimi işlemleri
    function updateCategoryCount() {
        const selectedCount = $('input[name="categories[]"]:checked').length;
        $('.category-count').text(selectedCount);
        
        // Seçili kategorileri göster
        const selectedList = $('#selected-categories-list');
        selectedList.empty();
        
        $('input[name="categories[]"]:checked').each(function() {
            const categoryName = $(this).closest('label').find('.category-name').text();
            const categoryId = $(this).val();
            
            const badge = $('<span>')
                .addClass('badge bg-primary me-2 mb-2 p-2')
                .html(`
                    ${categoryName}
                    <button type="button" class="btn-close btn-close-white ms-2" 
                        style="font-size: 0.5em;" data-category-id="${categoryId}">
                    </button>
                `);
            
            selectedList.append(badge);
        });
    }

    // Sayfa yüklendiğinde kategori sayısını güncelle
    updateCategoryCount();

    // Kategori seçildiğinde sayıyı güncelle
    $('input[name="categories[]"]').on('change', function() {
        updateCategoryCount();
    });

    // Tümünü seç butonu
    $('#select-all-categories').on('click', function() {
        const allChecked = $('input[name="categories[]"]:checked').length === $('input[name="categories[]"]').length;
        
        $('input[name="categories[]"]').prop('checked', !allChecked);
        updateCategoryCount();
    });

    // Seçili kategorilerden kaldırma
    $('#selected-categories-list').on('click', '.btn-close', function() {
        const categoryId = $(this).data('category-id');
        $(`input[name="categories[]"][value="${categoryId}"]`).prop('checked', false);
        updateCategoryCount();
    });

    // Form gönderilmeden önce kategori kontrolü
    $('#news-form').on('submit', function(e) {
        const selectedCategories = $('input[name="categories[]"]:checked').length;
        
        if (selectedCategories === 0) {
            e.preventDefault();
            alert('Lütfen en az bir kategori seçiniz.');
            return false;
        }
        
        return true;
    });

    // Durum seçimi işlemleri
    function updateStatusSelection(selectedStatus) {
        // Tüm kartlardan active sınıfını kaldır
        $('.status-option').removeClass('active border-primary').addClass('border');
        $('.status-option').css({
            'box-shadow': 'none',
            'background-color': 'white'
        });
        $('.status-option').find('.status-icon, .status-title').css({
            'color': '#6c757d'
        });
        
        // Seçili kartı bul ve active sınıfını ekle
        const selectedCard = $(`.status-card[data-status="${selectedStatus}"] .status-option`);
        selectedCard.addClass('active border-primary').removeClass('border');
        selectedCard.css({
            'box-shadow': '0 0 10px rgba(52,144,220,0.5)',
            'background-color': '#e6f2ff'
        });
        selectedCard.find('.status-icon, .status-title').css({
            'color': '#3490dc'
        });
        
        // Gizli input'a değeri ata
        $('#status-input').val(selectedStatus);
        
        console.log('Durum güncellendi:', selectedStatus);
    }

    // Durum kartlarına tıklama olayı
    $('.status-card').on('click', function() {
        const selectedStatus = $(this).data('status');
        updateStatusSelection(selectedStatus);
    });

    // Sayfa yüklendiğinde mevcut durumu seç
    const currentStatus = $('#status-input').val();
    if (currentStatus) {
        updateStatusSelection(currentStatus);
        console.log('Mevcut durum seçildi:', currentStatus);
    } else {
        // Varsayılan olarak "draft" seç
        updateStatusSelection('draft');
        console.log('Varsayılan durum seçildi: draft');
    }

    // Form gönderilmeden önce durum kontrolü
    $('#news-form').on('submit', function(e) {
        const selectedStatus = $('#status-input').val();
        console.log('Form gönderiliyor, seçili durum:', selectedStatus);
        
        if (!selectedStatus || !['published', 'draft'].includes(selectedStatus)) {
            e.preventDefault();
            alert('Lütfen bir durum seçiniz (Yayında/Taslak).');
            return false;
        }
        
        return true;
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
    // FileManagerSystem entegrasyonu
    $('#filemanagersystem_image_button').on('click', function() {
        const input = $('#filemanagersystem_image');
        const preview = $('#filemanagersystem_image_preview');
        const previewImg = preview.find('img');
        
        // Geçici bir ID oluştur - sadece sayısal değer
        const tempId = Date.now();
        const relatedType = 'news';
        
        // MediaPicker URL - mutlaka related_id ve related_type parametreleri ekleyelim
        const mediapickerUrl = '/admin/filemanagersystem/mediapicker?type=image&filter=all&related_type=' + relatedType + '&related_id=' + tempId;
        
        console.log('Alt kısım FileManagerSystem açılıyor:', mediapickerUrl);
        
        // iFrame'i güncelle
        $('#mediapickerFrame').attr('src', mediapickerUrl);
        
        // Bootstrap 5 Modal oluştur ve aç
        var modal = new bootstrap.Modal(document.getElementById('mediapickerModal'));
        modal.show();
        
        // iframe'den mesaj dinleme ve hata yakalama
        function handleMediaSelection(event) {
            try {
                if (event.data && event.data.type === 'mediaSelected') {
                    console.log('Alt kısım için seçilen medya:', event.data);
                    
                    // event.data'dan doğrudan URL değerini al
                    if (event.data.mediaUrl) {
                        // Medya URL'sini temizle
                        let mediaUrl = event.data.mediaUrl;
                        
                        // Eğer URL göreceli ise (/ ile başlıyorsa) tam URL'ye çevir
                        if (mediaUrl && mediaUrl.startsWith('/')) {
                            mediaUrl = mediaUrl.substring(1); // Baştaki / işaretini kaldır
                        }
                        
                        // Görsel URL'sini forma kaydet ve önizlemede göster
                        input.val(mediaUrl);
                        previewImg.attr('src', '/' + mediaUrl);
                        preview.show();
                        
                        // Uyarıyı gizle
                        $('#image-warning').hide();
                        
                    } else if (event.data.mediaId) {
                        // URL bulunamadıysa "uploads/" yolu ile dosya ID'sini kullan
                        const previewUrl = 'uploads/media/' + event.data.mediaId;
                        input.val(previewUrl);
                        
                        // Önizleme için ID ile resmi göster
                        previewImg.attr('src', '/' + previewUrl);
                        preview.show();
                        
                        // Uyarıyı gizle
                        $('#image-warning').hide();
                    }
                    
                    // Modalı kapat
                    modal.hide();
                    
                    // Event listener'ı kaldır
                    window.removeEventListener('message', handleMediaSelection);
                } else if (event.data && event.data.type === 'mediapickerError') {
                    // Medya seçicide bir hata oluştu
                    console.error('Alt kısım medya seçici hatası:', event.data.message);
                    alert('Medya seçici hatası: ' + event.data.message);
                    modal.hide();
                    
                    // Event listener'ı kaldır
                    window.removeEventListener('message', handleMediaSelection);
                }
            } catch (error) {
                console.error('Alt kısım medya seçimi işlenirken hata oluştu:', error);
                console.error('Hata detayı:', error.stack);
                alert('Medya seçimi işlenirken bir hata oluştu: ' + error.message);
                
                // Event listener'ı kaldır
                window.removeEventListener('message', handleMediaSelection);
            }
        }
        
        // Mevcut event listener'ı kaldır ve yenisini ekle
        window.removeEventListener('message', handleMediaSelection);
        window.addEventListener('message', handleMediaSelection);
    });

    // Temizle butonu için işlev
    $('#filemanagersystem_image_clear').on('click', function() {
        $('#filemanagersystem_image').val('');
        $('#filemanagersystem_image_preview').hide();
        $('#image-warning').show();
    });

    // ===== BELGE YÖNETİMİ BAŞLANGIÇ =====
    
    // Yeni belge ekleme formunu göster/gizle
    $('#add-new-document').on('click', function() {
        $('#document-upload-form-container').toggle();
        if ($('#document-upload-form-container').is(':visible')) {
            $(this).html('<i class="fas fa-minus"></i> İptal');
            // Sayfayı forma kaydır
            $('html, body').animate({
                scrollTop: $('#document-upload-form-container').offset().top - 100
            }, 500);
        } else {
            $(this).html('<i class="fas fa-plus"></i> Yeni Belge Ekle');
        }
    });

    $('#cancel-document-form').on('click', function() {
        $('#document-upload-form-container').hide();
        $('#add-new-document').html('<i class="fas fa-plus"></i> Yeni Belge Ekle');
        $('#document-upload-form')[0].reset();
    });

    // Belge yükleme formu
    $('#document-upload-form').on('submit', function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        var submitBtn = $(this).find('button[type="submit"]');
        var originalText = submitBtn.html();
        
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Yükleniyor...');
        
        $.ajax({
            url: '{{ route("admin.news.documents.store", $news) }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    // Formu temizle
                    $('#document-upload-form')[0].reset();
                    $('#document-upload-form-container').hide();
                    $('#add-new-document').html('<i class="fas fa-plus"></i> Yeni Belge Ekle');
                    
                    // Başarı mesajı
                    alert('Belge başarıyla yüklendi!');
                    
                    // Sayfayı yenile
                    location.reload();
                }
            },
            error: function(xhr) {
                var errors = xhr.responseJSON.errors;
                if (errors) {
                    var errorMsg = '';
                    $.each(errors, function(key, value) {
                        errorMsg += value[0] + '\n';
                    });
                    alert('Hata: ' + errorMsg);
                } else {
                    alert('Bir hata oluştu.');
                }
            },
            complete: function() {
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Belge düzenleme
    $(document).on('click', '.edit-document', function() {
        var documentId = $(this).data('id');
        var documentName = $(this).data('name');
        var documentDescription = $(this).data('description');
        
        var newName = prompt('Belge adını düzenleyin:', documentName);
        if (newName && newName !== documentName) {
            var newDescription = prompt('Belge açıklamasını düzenleyin:', documentDescription || '');
            
            $.ajax({
                url: '{{ route("admin.news.documents.update", [$news, "__DOCUMENT_ID__"]) }}'.replace('__DOCUMENT_ID__', documentId),
                type: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}',
                    name: newName,
                    description: newDescription
                },
                success: function(response) {
                    if (response.success) {
                        alert('Belge başarıyla güncellendi!');
                        location.reload();
                    }
                },
                error: function() {
                    alert('Belge güncellenirken bir hata oluştu.');
                }
            });
        }
    });

    // Belge silme
    $(document).on('click', '.delete-document', function() {
        var documentId = $(this).data('id');
        
        if (confirm('Bu belgeyi silmek istediğinizden emin misiniz?')) {
            $.ajax({
                url: '{{ route("admin.news.documents.destroy", [$news, "__DOCUMENT_ID__"]) }}'.replace('__DOCUMENT_ID__', documentId),
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        alert('Belge başarıyla silindi!');
                        location.reload();
                    }
                },
                error: function() {
                    alert('Belge silinirken bir hata oluştu.');
                }
            });
        }
    });

        // ===== BELGE YÖNETİMİ BİTİŞ =====
    
    // ===== GALERİ YÖNETİMİ BAŞLANGIÇ =====
    
    let galleryImages = [];
    
    // Dosya seçimi
    $('#select-gallery-files, #add-more-images').on('click', function() {
        $('#gallery-file-input').click();
    });
    
    // Drag & Drop olayları
    $('#gallery-dropzone').on('dragover', function(e) {
        e.preventDefault();
        $(this).addClass('dragover');
    });
    
    $('#gallery-dropzone').on('dragleave', function(e) {
        e.preventDefault();
        $(this).removeClass('dragover');
    });
    
    $('#gallery-dropzone').on('drop', function(e) {
        e.preventDefault();
        $(this).removeClass('dragover');
        
        const files = e.originalEvent.dataTransfer.files;
        handleGalleryFiles(files);
    });
    
    // Dosya input değişimi
    $('#gallery-file-input').on('change', function() {
        const files = this.files;
        handleGalleryFiles(files);
    });
    
    // Dosyaları işleme fonksiyonu
    function handleGalleryFiles(files) {
        const validFiles = [];
        
        // Dosya türü kontrolü
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            if (file.type.startsWith('image/')) {
                validFiles.push(file);
            } else {
                alert(file.name + ' dosyası resim formatında değil. Lütfen sadece resim dosyası seçin.');
            }
        }
        
        if (validFiles.length > 0) {
            uploadGalleryImages(validFiles);
        }
    }
    
    // Galeri resimlerini yükleme
    function uploadGalleryImages(files) {
        console.log('Galeri yükleme başlıyor, dosya sayısı:', files.length);
        
        const formData = new FormData();
        
        // Dosyaları FormData'ya ekle
        for (let i = 0; i < files.length; i++) {
            formData.append('files[]', files[i]);
            console.log('Dosya eklendi:', files[i].name, 'Boyut:', files[i].size);
        }
        
        // CSRF token ekle
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        formData.append('folder_id', ''); // Boş bırak, otomatik klasörleme yapılacak
        formData.append('is_public', '1');
        
        // Progress bar göster ve sıfırla
        $('#gallery-upload-progress').show();
        $('#gallery-progress-bar').css('width', '0%');
        $('#upload-status').text('0/' + files.length);
        
        // Upload butonunu devre dışı bırak
        $('#select-gallery-files').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Yükleniyor...');
        
        $.ajax({
            url: '{{ route("admin.filemanagersystem.media.store") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            timeout: 300000, // 5 dakika timeout
            xhr: function() {
                const xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                        const percentComplete = (e.loaded / e.total) * 100;
                        console.log('Upload progress:', percentComplete + '%');
                        $('#gallery-progress-bar').css('width', percentComplete + '%');
                        $('#upload-status').text('Yükleniyor... ' + Math.round(percentComplete) + '%');
                    }
                });
                return xhr;
            },
            beforeSend: function() {
                console.log('AJAX isteği başlıyor...');
            },
            success: function(response) {
                console.log('Galeri yükleme başarılı:', response);
                
                // Progress bar'ı %100 yap
                $('#gallery-progress-bar').css('width', '100%');
                $('#upload-status').text('Tamamlandı!');
                
                if (response.success && response.uploaded_files) {
                    // Yüklenen dosyaları galeri listesine ekle
                    response.uploaded_files.forEach(function(file) {
                        addImageToGallery(file);
                    });
                    
                    updateGalleryDisplay();
                    
                    // Başarı mesajı
                    showGalleryMessage('success', response.uploaded_files.length + ' resim başarıyla yüklendi.');
                    
                    // 2 saniye sonra progress bar'ı gizle
                    setTimeout(function() {
                        $('#gallery-upload-progress').hide();
                    }, 2000);
                } else {
                    $('#gallery-upload-progress').hide();
                    showGalleryMessage('error', 'Resim yükleme sırasında bir hata oluştu.');
                }
            },
            error: function(xhr, status, error) {
                console.error('Galeri yükleme hatası:', error);
                console.error('XHR Status:', status);
                console.error('Response:', xhr.responseText);
                
                $('#gallery-upload-progress').hide();
                
                let errorMessage = 'Resim yükleme sırasında bir hata oluştu.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.status === 413) {
                    errorMessage = 'Dosya boyutu çok büyük. Lütfen daha küçük dosyalar seçin.';
                } else if (xhr.status === 422) {
                    errorMessage = 'Dosya formatı desteklenmiyor. Lütfen sadece resim dosyası seçin.';
                } else if (xhr.status === 500) {
                    errorMessage = 'Sunucu hatası. Lütfen daha sonra tekrar deneyin.';
                }
                
                showGalleryMessage('error', errorMessage);
            },
            complete: function() {
                console.log('AJAX isteği tamamlandı.');
                // Upload butonunu tekrar aktif et
                $('#select-gallery-files').prop('disabled', false).html('<i class="fas fa-images me-1"></i>Resim Seç');
            }
        });
    }
    
    // Resmi galeriye ekleme
    function addImageToGallery(imageData) {
        galleryImages.push({
            id: imageData.id,
            url: imageData.url,
            name: imageData.name || imageData.original_name,
            size: imageData.size,
            order: galleryImages.length
        });
    }
    
    // Galeri görünümünü güncelleme
    function updateGalleryDisplay() {
        console.log('updateGalleryDisplay çağrıldı, galleryImages:', galleryImages);
        
        const galleryGrid = $('#gallery-grid');
        console.log('Gallery grid elementi:', galleryGrid.length);
        
        galleryGrid.empty();
        
        if (galleryImages.length === 0) {
            console.log('Galeri boş, gizleniyor');
            $('#gallery-preview').hide();
            return;
        }
        
        console.log('Galeri gösteriliyor, resim sayısı:', galleryImages.length);
        $('#gallery-preview').show();
        $('#gallery-count').text(galleryImages.length);
        
        galleryImages.forEach(function(image, index) {
            const imageItem = $(`
                <div class="gallery-item" data-index="${index}">
                    <div class="gallery-image">
                        <img src="${image.url}" alt="${image.name}" loading="lazy">
                        <div class="gallery-overlay">
                            <button type="button" class="btn btn-sm btn-danger remove-gallery-image" data-index="${index}">
                                <i class="fas fa-trash"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-primary move-up" data-index="${index}" ${index === 0 ? 'disabled' : ''}>
                                <i class="fas fa-arrow-up"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-primary move-down" data-index="${index}" ${index === galleryImages.length - 1 ? 'disabled' : ''}>
                                <i class="fas fa-arrow-down"></i>
                            </button>
                        </div>
                    </div>
                    <div class="gallery-info">
                        <small class="text-muted">${image.name}</small>
                    </div>
                </div>
            `);
            
            galleryGrid.append(imageItem);
        });
        
        // Hidden input'u güncelle
        $('#filemanagersystem_gallery').val(JSON.stringify(galleryImages));
    }
    
    // Galeri resmi silme
    $(document).on('click', '.remove-gallery-image', function() {
        const index = parseInt($(this).data('index'));
        galleryImages.splice(index, 1);
        
        // Order değerlerini yeniden düzenle
        galleryImages.forEach(function(image, newIndex) {
            image.order = newIndex;
        });
        
        updateGalleryDisplay();
        showGalleryMessage('info', 'Resim galeriden kaldırıldı.');
    });
    
    // Resim sıralama - yukarı
    $(document).on('click', '.move-up', function() {
        const index = parseInt($(this).data('index'));
        if (index > 0) {
            const temp = galleryImages[index];
            galleryImages[index] = galleryImages[index - 1];
            galleryImages[index - 1] = temp;
            
            updateGalleryDisplay();
        }
    });
    
    // Resim sıralama - aşağı
    $(document).on('click', '.move-down', function() {
        const index = parseInt($(this).data('index'));
        if (index < galleryImages.length - 1) {
            const temp = galleryImages[index];
            galleryImages[index] = galleryImages[index + 1];
            galleryImages[index + 1] = temp;
            
            updateGalleryDisplay();
        }
    });
    
    // Tüm galeriyi temizleme
    $('#clear-gallery').on('click', function() {
        if (confirm('Tüm galeri resimlerini kaldırmak istediğinizden emin misiniz?')) {
            galleryImages = [];
            updateGalleryDisplay();
            showGalleryMessage('info', 'Galeri temizlendi.');
        }
    });
    
    // Galeri mesajları
    function showGalleryMessage(type, message) {
        console.log('Galeri mesajı gösteriliyor:', type, message);
        
        const alertClass = type === 'success' ? 'alert-success' : 
                          type === 'error' ? 'alert-danger' : 'alert-info';
        
        const iconClass = type === 'success' ? 'fas fa-check-circle' : 
                         type === 'error' ? 'fas fa-exclamation-triangle' : 'fas fa-info-circle';
        
        const messageDiv = $(`
            <div class="alert ${alertClass} alert-dismissible fade show mt-2" role="alert">
                <i class="${iconClass} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Kapat"></button>
            </div>
        `);
        
        // Önceki mesajları temizle
        $('.alert').remove();
        
        // Galeri alanının üzerine ekle
        $('#gallery-upload-area').after(messageDiv);
        
        // 8 saniye sonra otomatik kaldır (başarı mesajları için daha uzun süre)
        const timeout = type === 'success' ? 8000 : 6000;
        setTimeout(function() {
            messageDiv.fadeOut(function() {
                $(this).remove();
            });
        }, timeout);
    }
    
    // Sayfa yüklendiğinde mevcut galeri verilerini yükle
    const existingGallery = $('#filemanagersystem_gallery').val();
    if (existingGallery && existingGallery !== '[]') {
        try {
            galleryImages = JSON.parse(existingGallery);
            updateGalleryDisplay();
        } catch (e) {
            console.error('Mevcut galeri verileri parse edilemedi:', e);
        }
    }
    
    // ===== GALERİ YÖNETİMİ BİTİŞ =====
});
</script>

<!-- Galeri CSS Stilleri -->
<style>
.gallery-upload-area {
    margin-bottom: 1rem;
}

.upload-dropzone {
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    padding: 2rem;
    text-align: center;
    transition: all 0.3s ease;
    background-color: #f8f9fa;
}

.upload-dropzone:hover,
.upload-dropzone.dragover {
    border-color: #007bff;
    background-color: #e3f2fd;
}

.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1rem;
}

.gallery-item {
    position: relative;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    overflow: hidden;
    background: white;
}

.gallery-image {
    position: relative;
    aspect-ratio: 16/9;
    overflow: hidden;
}

.gallery-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.gallery-item:hover .gallery-image img {
    transform: scale(1.05);
}

.gallery-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.gallery-item:hover .gallery-overlay {
    opacity: 1;
}

.gallery-overlay .btn {
    border-radius: 50%;
    width: 35px;
    height: 35px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.gallery-info {
    padding: 0.5rem;
    text-align: center;
}

.gallery-info small {
    display: block;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* Progress bar stilleri */
.upload-progress {
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    padding: 15px;
    margin-top: 15px;
}

.upload-progress .progress {
    height: 12px;
    border-radius: 6px;
    box-shadow: inset 0 1px 2px rgba(0,0,0,.1);
}

.upload-progress .progress-bar {
    background: linear-gradient(45deg, #007bff, #0056b3);
    transition: width 0.3s ease;
}

/* Alert mesajları */
.alert {
    border-radius: 8px;
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
}

.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
}

.alert-info {
    background-color: #d1ecf1;
    color: #0c5460;
}

/* Responsive tasarım */
@media (max-width: 768px) {
    .gallery-grid {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 0.5rem;
    }
    
    .upload-dropzone {
        padding: 1.5rem 1rem;
    }
    
    .gallery-overlay .btn {
        width: 30px;
        height: 30px;
    }
}
</style>
@endsection 