@extends('adminlte::page')

@section('title', 'Yeni Sayfa')

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
        max-width: 100%;
        margin-top: 1rem;
    }
    
    #image-preview img {
        max-height: 200px;
        border-radius: 6px;
        box-shadow: 0 2px 6px rgba(0,0,0,.1);
    }
    
    .validation-errors-summary {
        background: #fff5f5;
        border: 1px solid #feb2b2;
        color: #c53030;
        padding: 1rem;
        border-radius: 6px;
        margin-bottom: 1.5rem;
    }
    
    .validation-errors-summary h5 {
        margin-top: 0;
        font-size: 1rem;
        margin-bottom: 0.5rem;
    }
    
    .validation-errors-summary ul {
        margin-bottom: 0;
        padding-left: 1.5rem;
    }
    
    .validation-errors-summary li {
        margin-bottom: 0.25rem;
    }
    
    .publish-options-card {
        padding: 0;
    }
    
    .status-option {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #e9ecef;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .status-option:last-child {
        border-bottom: none;
    }
    
    .status-option:hover {
        background-color: #f8f9fa;
    }
    
    .status-option.active {
        background-color: #e6f2ff;
    }
    
    .status-option i {
        width: 20px;
        text-align: center;
        margin-right: 10px;
    }
    
    /* MediaPicker Modal Stilleri */
    #mediapickerModal .modal-dialog {
        max-width: 98%;
        height: 90vh;
        margin: 0 auto;
    }
    
    #mediapickerModal .modal-content {
        height: 90vh;
        border-radius: 8px;
        border: none;
    }
    
    #mediapickerModal .modal-body {
        padding: 0;
        height: calc(100% - 56px);
    }
    
    #mediapickerModal .modal-header {
        border-radius: 8px 8px 0 0;
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
    }
    
    #mediapickerFrame {
        border: none;
        width: 100%;
        height: 100%;
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
</style>
@stop

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2">Yeni Sayfa</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.pages.index') }}" class="text-decoration-none">Sayfalar</a></li>
                    <li class="breadcrumb-item active">Yeni Sayfa</li>
                </ol>
            </nav>
        </div>
        
        <div>
            <a href="{{ route('admin.pages.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Geri Dön
            </a>
        </div>
    </div>
    
    <!-- Hata ve Uyarılar -->
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Hata!</strong> {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Başarılı!</strong> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Doğrulama Hataları!</strong>
        <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
            
    <form action="{{ route('admin.pages.store') }}" method="POST" id="page-form">
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
                        </div>
                        
                        <div class="mb-4">
                            <label for="slug" class="form-label">URL</label>
                            <!-- Slug ve URL Önizleme -->
                            <div class="mt-2 p-2 bg-light border rounded">
                                <div class="small text-muted mb-1">
                                    <i class="fas fa-link me-1"></i> Oluşturulacak URL:
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="text-secondary">{{ url('/') }}/</span>
                                    <span class="text-primary fw-bold" id="slug-preview">-</span>
                                </div>
                                <div class="small text-muted mt-1">
                                    <i class="fas fa-info-circle me-1"></i> Slug:
                                </div>
                                <div class="input-group mt-1">
                                    <input type="text" class="form-control form-control-sm @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug') }}" placeholder="Otomatik oluşturulur">
                                    <button class="btn btn-sm btn-outline-secondary" type="button" id="slug-regenerate">
                                        <i class="fas fa-sync-alt"></i> Yenile
                                    </button>
                                </div>
                                <small class="form-text text-muted mt-1">
                                    Otomatik oluşturulan slug'ı düzenleyebilirsiniz. Boş bırakırsanız otomatik oluşturulacaktır.
                                </small>
                            </div>
                            @error('slug')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                                
                        <div class="mb-4">
                            <label for="summary" class="form-label">Özet</label>
                            <textarea class="form-control @error('summary') is-invalid @enderror" id="summary" name="summary" rows="3" style="height: 100px;">{{ old('summary') }}</textarea>
                            @error('summary')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Sayfa listesinde ve sosyal medya paylaşımlarında görünecek kısa açıklama.</small>
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
                
                <!-- Sayfa Görseli -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Sayfa Görseli</h5>
                    </div>
                    <div class="card-body">
                        <!-- FileManagerSystem Görsel -->
                        <div class="mb-4">
                            <label for="filemanagersystem_image" class="form-label">Sayfa Görseli <span class="text-danger">*</span></label>
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
                                <img src="{{ old('image') }}" alt="Önizleme" class="img-thumbnail">
                            </div>
                            <div class="alert alert-warning mt-2" id="image-warning" style="display: {{ old('image') ? 'none' : 'block' }};">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                <strong>Not:</strong> Ana görsel alanı opsiyoneldir. İsterseniz bir görsel seçebilirsiniz.
                            </div>
                        </div>

                        <!-- Görsel Alt Metni -->
                        <div class="mb-4">
                            <label for="filemanagersystem_image_alt" class="form-label">Görsel Alt Metni</label>
                            <input type="text" class="form-control @error('filemanagersystem_image_alt') is-invalid @enderror" id="filemanagersystem_image_alt" name="filemanagersystem_image_alt" value="{{ old('filemanagersystem_image_alt') }}">
                            @error('filemanagersystem_image_alt')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Görselin HTML alt özelliği için kullanılır (SEO ve erişilebilirlik için önemlidir)</small>
                        </div>

                        <!-- Görsel Başlığı -->
                        <div class="mb-4">
                            <label for="filemanagersystem_image_title" class="form-label">Görsel Başlığı</label>
                            <input type="text" class="form-control @error('filemanagersystem_image_title') is-invalid @enderror" id="filemanagersystem_image_title" name="filemanagersystem_image_title" value="{{ old('filemanagersystem_image_title') }}">
                            @error('filemanagersystem_image_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Görselin HTML title özelliği için kullanılır</small>
                        </div>
                    </div>
                </div>
                        
                <!-- Medya -->
                <div class="card mb-4">
                    <div class="card-header bg-light d-flex align-items-center">
                        <i class="fas fa-images me-2 text-primary"></i>
                        <h5 class="mb-0">Medya</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="image" class="form-label">Ana Görsel</label>
                            <div class="input-group">
                                <input type="text" class="form-control @error('image') is-invalid @enderror" id="image" name="image" value="{{ old('image') }}" readonly>
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button" id="image-browser" data-input="image" data-preview="image-preview">
                                        <i class="fas fa-folder-open"></i> Göz At
                                    </button>
                                    <button class="btn btn-danger" type="button" id="image-clear">
                                        <i class="fas fa-times"></i> Temizle
                                    </button>
                                </div>
                            </div>
                            @error('image')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            
                            <div id="image-preview" class="mt-3" style="{{ old('image') ? '' : 'display: none;' }}">
                                <img src="{{ old('image') }}" alt="Görsel Önizleme" class="img-fluid" style="max-height: 200px; border-radius: 8px;">
                                <div class="mt-2 small text-muted">
                                    <span id="image-dimensions">0 x 0</span> &bull; <span id="image-size">0 KB</span>
                                </div>
                            </div>
                        </div>
                                
                        <div class="form-group">
                            <label for="gallery">Galeri Görselleri <small class="text-muted">(En fazla 10 görsel)</small></label>
                            <div class="d-flex">
                                <button type="button" id="gallery-browser" class="btn btn-outline-primary" data-input="fake-gallery-input">
                                    <i class="fas fa-images"></i> Görsel Ekle (<span id="gallery-count">0</span>/10)
                                </button>
                            </div>
                            <input type="hidden" id="fake-gallery-input" class="fake-gallery-input">
                            
                            <div id="gallery-preview" class="mt-3" style="display: none;">
                                <div id="gallery-items" class="d-flex flex-wrap gap-3"></div>
                                <div id="gallery-inputs"></div>
                            </div>
                            @error('gallery')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            @error('gallery.*')
                                <span class="text-danger">{{ $message }}</span>
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
                            <input type="text" class="form-control @error('meta_title') is-invalid @enderror" id="meta_title" name="meta_title" value="{{ old('meta_title') }}">
                            @error('meta_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Boş bırakılırsa sayfa başlığı kullanılacaktır.</small>
                        </div>
                                
                        <div class="mb-4">
                            <label for="meta_description" class="form-label">Meta Açıklama</label>
                            <textarea class="form-control @error('meta_description') is-invalid @enderror" id="meta_description" name="meta_description" rows="3">{{ old('meta_description') }}</textarea>
                            @error('meta_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Boş bırakılırsa sayfa özeti kullanılacaktır.</small>
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

                        <div class="mb-4" id="publish-date-container" @if(old('status') != 'scheduled') style="display: none;" @endif>
                            <label for="published_at" class="form-label">Yayın Tarihi</label>
                            <div class="input-group">
                                <input type="text" class="form-control datepicker @error('published_at') is-invalid @enderror" id="published_at" name="published_at" value="{{ old('published_at', now()->format('d.m.Y H:i')) }}" autocomplete="off">
                                <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                            </div>
                            @error('published_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" {{ old('is_featured') ? 'checked' : '' }} {{ $maxFeaturedReached ? 'disabled' : '' }}>
                            <label class="form-check-label" for="is_featured">
                                <strong>Öne Çıkan</strong>
                                <small class="d-block text-muted">Sayfa öne çıkan bölümünde gösterilecektir.</small>
                            </label>
                            @if($maxFeaturedReached)
                                <div class="text-danger small mt-2">Maksimum öne çıkan sayfa sayısına ulaşıldı (4).</div>
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
                                    <a href="{{ route('admin.page-categories.create') }}" target="_blank" class="btn btn-primary btn-sm" style="font-size: 0.8rem; padding: 0.25rem 0.5rem;">
                                        <i class="fas fa-plus me-1"></i>Yeni Kategori
                                    </a>
                                </div>
                            </div>

                            <!-- Kategori Listesi -->
                            <div class="category-list mb-3" style="max-height: 400px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 4px;">
                                @foreach($pageCategories as $category)
                                <div class="category-item d-flex align-items-center py-1 px-2 mb-1 rounded hover-bg-light">
                                    <div class="form-check mb-0 w-100">
                                        <label class="d-flex align-items-center gap-1">
                                            <input type="checkbox" name="categories[]" value="{{ $category->id }}" class="form-check-input me-1" {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
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
                        @error('categories')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
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
                                <input type="hidden" id="tags" name="tags" value="{{ old('tags') }}">
                            </div>
                        </div>
                        @error('tags')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save"></i> Sayfayı Kaydet
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

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
@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.tr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script src="{{ asset('js/slug-helper.js') }}"></script>

<!-- TinyMCE Editör -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.4.2/tinymce.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
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
                    const relatedType = 'page_content';
                    
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
                        tooltip: 'Hızlı Resim Ekle',
                        onAction: function() {
                            openFileManagerSystemPicker(editor);
                        }
                    });
                });
            }
        });
        
        // FileManagerSystem Picker fonksiyonu
        function openFileManagerSystemPicker(editor) {
            // Geçici bir ID oluştur
            const tempId = Date.now();
            const relatedType = 'page_content';
            
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
                            // Editöre ekle
                            editor.insertContent(`<img src="${mediaUrl}" alt="${altText}" />`);
                            
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
        }
        
        // Slug oluşturma fonksiyonu - SlugHelper kullanımı
        function createSlug(text) {
            return SlugHelper.create(text);
        }
        
        // Slug işlevselliği
        const titleInput = document.getElementById('title');
        const slugPreview = document.getElementById('slug-preview');
        const slugInput = document.getElementById('slug');
        const slugRegenerateBtn = document.getElementById('slug-regenerate');
        let slugIsManual = false; // Kullanıcı manuel olarak slug değiştirmiş mi?
        
        // Başlık yazıldığında otomatik slug oluşturma
        titleInput.addEventListener('input', function() {
            if (!slugIsManual || slugInput.value === '') {
                const newSlug = createSlug(this.value);
                slugInput.value = newSlug;
                updateSlugPreview();
            }
        });
        
        // Slug manuel değiştirildiğinde
        slugInput.addEventListener('input', function() {
            slugIsManual = true;
            updateSlugPreview();
        });
        
        // Slug yeniden oluşturma butonu
        slugRegenerateBtn.addEventListener('click', function() {
            const title = titleInput.value;
            if (title.trim() !== '') {
                const slug = createSlug(title);
                slugInput.value = slug;
                slugIsManual = false;
                updateSlugPreview();
                
                // Efekt ekleyelim
                slugInput.classList.add('border-success');
                setTimeout(() => {
                    slugInput.classList.remove('border-success');
                }, 1000);
            }
        });
        
        // Slug önizlemesini güncelleme
        function updateSlugPreview() {
            const slugValue = slugInput.value.trim() || 'ornek-sayfa';
            slugPreview.textContent = slugValue;
        }
        
        // Sayfa yüklendiğinde önizlemeyi güncelle
        updateSlugPreview();
        
        // Durum seçici
        document.querySelectorAll('.status-option').forEach(option => {
            option.addEventListener('click', function() {
                const status = this.dataset.status;
                document.getElementById('status-input').value = status;
                
                // Aktif durumu güncelle
                document.querySelectorAll('.status-option').forEach(opt => {
                    opt.classList.remove('active');
                });
                this.classList.add('active');
                
                // Zamanlama konteynerini göster/gizle
                if (status === 'scheduled') {
                    document.getElementById('publish-date-container').style.display = 'block';
                } else {
                    document.getElementById('publish-date-container').style.display = 'none';
                }
            });
        });
        
        // Tarih & saat seçici
        $('.datepicker').datepicker({
            format: 'dd.mm.yyyy',
            language: 'tr',
            autoclose: true,
            todayHighlight: true
        });
    });
</script>

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
        const relatedType = 'page';
        
        // MediaPicker URL - mutlaka related_id ve related_type parametreleri ekleyelim
        const mediapickerUrl = '/admin/filemanagersystem/mediapicker?type=image&filter=all&related_type=' + relatedType + '&related_id=' + tempId;
        
        // iFrame'i güncelle
        $('#mediapickerFrame').attr('src', mediapickerUrl);
        
        // Bootstrap 5 Modal oluştur ve aç
        var modal = new bootstrap.Modal(document.getElementById('mediapickerModal'));
        modal.show();
        
        // iframe'den mesaj dinleme ve hata yakalama
        function handleMediaSelection(event) {
            try {
                if (event.data && event.data.type === 'mediaSelected') {
                    // event.data'dan doğrudan URL değerini al
                    if (event.data.mediaUrl) {
                        // Medya URL'sini temizle
                        let mediaUrl = event.data.mediaUrl;
                        
                        // Eğer URL göreceli ise (/ ile başlıyorsa) tam URL'ye çevir
                        if (mediaUrl && mediaUrl.startsWith('/')) {
                            const baseUrl = window.location.protocol + '//' + window.location.host;
                            mediaUrl = baseUrl + mediaUrl;
                        }
                        
                        // Görsel URL'sini forma kaydet ve önizlemede göster
                        input.val(mediaUrl);
                        previewImg.attr('src', mediaUrl);
                        preview.show();
                        
                        // Uyarıyı gizle
                        $('#image-warning').hide();
                    } else if (event.data.mediaId) {
                        // URL bulunamadıysa "uploads/" yolu ile dosya ID'sini kullan
                        const previewUrl = '/uploads/media/' + event.data.mediaId;
                        input.val(previewUrl);
                        
                        // Önizleme için ID ile resmi göster
                        previewImg.attr('src', '/admin/filemanagersystem/media/preview/' + event.data.mediaId);
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
                    alert('Medya seçici hatası: ' + event.data.message);
                    modal.hide();
                    
                    // Event listener'ı kaldır
                    window.removeEventListener('message', handleMediaSelection);
                }
            } catch (error) {
                alert('Medya seçimi işlenirken bir hata oluştu: ' + error.message);
                
                // Event listener'ı kaldır
                window.removeEventListener('message', handleMediaSelection);
            }
        }
        
        // Mevcut event listener'ı kaldır ve yenisini ekle
        window.removeEventListener('message', handleMediaSelection);
        window.addEventListener('message', handleMediaSelection);
    });
});
</script>
@stop 