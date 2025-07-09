@extends('adminlte::page')

@section('title', 'Sayfa Düzenle - ' . $page->title)

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
    
    /* Kategori kartı ve rozet stilleri */
    .category-badge {
        display: inline-flex;
        align-items: center;
        background: #e9ecef;
        border-radius: 0.25rem;
        padding: 0.25rem 0.5rem;
        margin: 0.125rem;
        font-size: 0.875rem;
    }
    
    .category-badge .remove-category {
        margin-left: 0.5rem;
        color: #dc3545;
        cursor: pointer;
    }
    
    .selected-categories {
        margin-top: 10px;
    }
    
    .hover-bg-light:hover {
        background-color: rgba(0,0,0,0.05);
    }
    
    .category-item {
        cursor: pointer;
        margin-bottom: 5px;
        transition: all 0.2s;
    }
    
    /* Görsel önizleme */
    .image-preview-container {
        position: relative;
        margin-top: 10px;
        border-radius: 0.375rem;
        overflow: hidden;
        border: 1px solid #dee2e6;
    }
    
    .image-preview-container img {
        max-width: 100%;
        height: auto;
    }
    
    .image-preview-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        opacity: 0;
        transition: opacity 0.3s;
    }
    
    .image-preview-container:hover .image-preview-overlay {
        opacity: 1;
    }
    
    .image-preview-info {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0,0,0,0.7);
        color: white;
        padding: 5px 10px;
        font-size: 0.8rem;
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
            <h1 class="h3 mb-2">Sayfa Düzenle</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.pages.index') }}" class="text-decoration-none">Sayfalar</a></li>
                    <li class="breadcrumb-item active">Düzenle</li>
                </ol>
            </nav>
        </div>
        
        <div>
            <a href="{{ route('pages.show', $page->slug) }}" target="_blank" class="btn btn-outline-primary me-2">
                <i class="fas fa-eye"></i> Önizleme
            </a>
            <a href="{{ route('admin.pages.index') }}" class="btn btn-outline-secondary">
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
            
    <form action="{{ route('admin.pages.update', $page->id) }}" method="POST" id="page-form">
        @csrf
        @method('PUT')
        
        <div class="row">
            <div class="col-lg-8">
                <!-- Ana İçerik -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="mb-4">
                            <label for="title" class="form-label">Başlık <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $page->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="slug" class="form-label">URL</label>
                            <!-- Slug ve URL Önizleme Kutusu -->
                            <div class="mt-2 p-2 bg-light border rounded mb-2">
                                <div class="small text-muted mb-1">
                                    <i class="fas fa-link me-1"></i> Oluşturulacak URL:
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="text-secondary small fw-bold">{{ url('/') }}/</span>
                                    <span class="text-primary fw-bold" id="slug-preview">{{ old('slug', $page->slug) }}</span>
                                </div>
                                <div class="small text-muted mt-2">
                                    <i class="fas fa-info-circle me-1"></i> Slug:
                                </div>
                                <div class="input-group mt-1">
                                    <input type="text" class="form-control form-control-sm @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $page->slug) }}" placeholder="Otomatik oluşturulur">
                                    <button class="btn btn-sm btn-outline-secondary" type="button" id="slug-regenerate" title="Başlıktan yeniden oluştur">
                                        <i class="fas fa-sync-alt"></i> Yenile
                                    </button>
                                </div>
                                <small class="form-text text-muted mt-1">
                                    <i class="fas fa-info-circle me-1"></i> Otomatik oluşturulan slug'ı düzenleyebilirsiniz. Boş bırakırsanız başlıktan otomatik oluşturulacaktır.
                                </small>
                            </div>
                            @error('slug')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                                
                        <div class="mb-4">
                            <label for="summary" class="form-label">Özet</label>
                            <textarea class="form-control @error('summary') is-invalid @enderror" id="summary" name="summary" rows="3" style="height: 100px;">{{ old('summary', $page->summary) }}</textarea>
                            @error('summary')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Sayfa listesinde ve sosyal medya paylaşımlarında görünecek kısa açıklama.</small>
                        </div>
                                
                        <div class="mb-4">
                            <label for="content" class="form-label">İçerik <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="10">{{ old('content', $page->content) }}</textarea>
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
                                <input type="text" class="form-control @error('image') is-invalid @enderror" id="filemanagersystem_image" name="image" value="{{ old('image', $page->image ?? '') }}">
                                <button type="button" class="btn btn-primary" id="filemanagersystem_image_button">
                                    <i class="fas fa-image"></i> Görsel Seç
                                </button>
                            </div>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="filemanagersystem_image_preview" class="mt-2" style="display: {{ old('image', $page->image) ? 'block' : 'none' }};">
                                <img src="{{ old('image', $page->image) }}" alt="Önizleme" class="img-thumbnail">
                            </div>
                            <div class="alert alert-warning mt-2" id="image-warning" style="display: {{ old('image', $page->image) ? 'none' : 'block' }};">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                <strong>Not:</strong> Ana görsel alanı opsiyoneldir. İsterseniz bir görsel seçebilirsiniz.
                            </div>
                        </div>

                        <!-- Görsel Alt Metni -->
                        <div class="mb-4">
                            <label for="filemanagersystem_image_alt" class="form-label">Görsel Alt Metni</label>
                            <input type="text" class="form-control @error('filemanagersystem_image_alt') is-invalid @enderror" id="filemanagersystem_image_alt" name="filemanagersystem_image_alt" value="{{ old('filemanagersystem_image_alt', $page->filemanagersystem_image_alt ?? '') }}">
                            @error('filemanagersystem_image_alt')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Görselin HTML alt özelliği için kullanılır (SEO ve erişilebilirlik için önemlidir)</small>
                        </div>

                        <!-- Görsel Başlığı -->
                        <div class="mb-4">
                            <label for="filemanagersystem_image_title" class="form-label">Görsel Başlığı</label>
                            <input type="text" class="form-control @error('filemanagersystem_image_title') is-invalid @enderror" id="filemanagersystem_image_title" name="filemanagersystem_image_title" value="{{ old('filemanagersystem_image_title', $page->filemanagersystem_image_title ?? '') }}">
                            @error('filemanagersystem_image_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Görselin HTML title özelliği için kullanılır</small>
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
                            <input type="text" class="form-control @error('meta_title') is-invalid @enderror" id="meta_title" name="meta_title" value="{{ old('meta_title', $page->meta_title) }}">
                            @error('meta_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Boş bırakılırsa sayfa başlığı kullanılacaktır.</small>
                        </div>
                                
                        <div class="mb-4">
                            <label for="meta_description" class="form-label">Meta Açıklama</label>
                            <textarea class="form-control @error('meta_description') is-invalid @enderror" id="meta_description" name="meta_description" rows="3">{{ old('meta_description', $page->meta_description) }}</textarea>
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
                                <input type="hidden" name="status" id="status-input" value="{{ old('status', $page->status) }}" required>
                                @error('status')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </label>
                            
                            <div class="publish-options-card border rounded overflow-hidden">
                                <div class="status-option @if(old('status', $page->status) == 'published') active @endif" data-status="published">
                                    <i class="fas fa-check-circle text-success"></i>
                                    <strong>Yayınlandı</strong>
                                    <div class="text-muted small">Sayfa hemen yayınlanacak</div>
                                </div>
                                <div class="status-option @if(old('status', $page->status) == 'draft') active @endif" data-status="draft">
                                    <i class="fas fa-save text-secondary"></i>
                                    <strong>Taslak</strong>
                                    <div class="text-muted small">Sayfa taslak olarak kaydedilecek</div>
                                </div>
                                <div class="status-option @if(old('status', $page->status) == 'scheduled') active @endif" data-status="scheduled">
                                    <i class="fas fa-calendar-alt text-primary"></i>
                                    <strong>Zamanlanmış</strong>
                                    <div class="text-muted small">Sayfa belirtilen tarihte yayınlanacak</div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4" id="publish-date-container" @if(old('status', $page->status) != 'scheduled') style="display: none;" @endif>
                            <label for="published_at" class="form-label">Yayın Tarihi</label>
                            <div class="input-group">
                                <input type="text" class="form-control datepicker @error('published_at') is-invalid @enderror" id="published_at" name="published_at" value="{{ old('published_at', $page->published_at ? $page->published_at->format('d.m.Y H:i') : now()->format('d.m.Y H:i')) }}" autocomplete="off">
                                <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                            </div>
                            @error('published_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" {{ old('is_featured', $page->is_featured) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">
                                <strong>Öne Çıkan</strong>
                                <small class="d-block text-muted">Sayfa öne çıkan bölümünde gösterilecektir.</small>
                            </label>
                        </div>
                        
                        <div class="mb-3 d-flex justify-content-between align-items-center mt-4">
                            <small class="text-muted">Oluşturulma: {{ $page->created_at->format('d.m.Y H:i') }}</small>
                            @if($page->status == 'published' && $page->published_at)
                                <small class="text-muted">Yayınlanma: {{ $page->published_at->format('d.m.Y H:i') }}</small>
                            @endif
                        </div>
                        
                        <div class="mb-3 d-flex justify-content-between align-items-center">
                            <small class="text-muted">Son Güncelleme: {{ $page->updated_at->format('d.m.Y H:i') }}</small>
                        </div>
                    </div>
                </div>
                        
                <!-- Kategoriler -->
                <div class="card mb-3">
                    <div class="card-header bg-light d-flex align-items-center">
                        <i class="fas fa-folder-tree me-2 text-primary"></i>
                        <h5 class="mb-0">Kategoriler</h5>
                        <span class="badge bg-primary ms-2 category-count">{{ count(old('categories', $page->categories->pluck('id')->toArray())) }}</span>
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
                                            <input type="checkbox" name="categories[]" value="{{ $category->id }}" class="form-check-input me-1" {{ in_array($category->id, old('categories', $page->categories->pluck('id')->toArray())) ? 'checked' : '' }}>
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
                        <span class="badge bg-primary ms-2 tag-count">{{ $page->tags && is_object($page->tags) ? $page->tags->count() : 0 }}</span>
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
                                <input type="hidden" id="tags" name="tags" value="{{ old('tags', $page->tags ? implode(',', $page->tags->pluck('name')->toArray()) : '') }}">
                            </div>
                        </div>
                        @error('tags')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save"></i> Değişiklikleri Kaydet
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
<!-- Laravel FileManager kaldırıldı -->
<!-- <script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script> -->
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
        
        // Kategori seçici
        new TomSelect('#categories', {
            plugins: ['remove_button'],
            maxItems: null,
            placeholder: 'Kategori seçin...',
            allowEmptyOption: true,
            closeAfterSelect: false
        });
        
        // Etiket seçici
        new TomSelect('#tags-input', {
            plugins: ['remove_button', 'restore_on_backspace'],
            persist: false,
            createOnBlur: true,
            create: true,
            maxItems: null,
            placeholder: 'Etiket ekleyin...',
            delimiter: ',',
            closeAfterSelect: false
        });
        
        // Kategori işlemleri
        const categoryItems = $('.category-item input[type="checkbox"]');
        const selectedCategoriesList = $('#selected-categories-list');
        const categoryCount = $('.category-count');
        
        // Başlangıçta kategori sayısını güncelle
        function updateCategoryCount() {
            const checkedCount = categoryItems.filter(':checked').length;
            categoryCount.text(checkedCount);
        }
        
        // Tümünü seç butonu
        $('#select-all-categories').on('click', function() {
            // Tüm checkboxlar seçili mi kontrol et
            const allChecked = categoryItems.length === categoryItems.filter(':checked').length;
            
            // Eğer tümü seçiliyse, hepsini kaldır; değilse hepsini seç
            categoryItems.each(function() {
                $(this).prop('checked', !allChecked);
            });
            
            // Seçili kategorileri güncelle
            updateSelectedCategories();
        });
        
        // Kategori checkbox'larını dinle
        categoryItems.on('change', function() {
            updateSelectedCategories();
        });
        
        // Seçili kategorileri göster
        function updateSelectedCategories() {
            // Seçili kategorileri temizle
            selectedCategoriesList.empty();
            
            // Seçili tüm kategorileri bul
            const selectedCategories = categoryItems.filter(':checked');
            
            // Kategori sayacını güncelle
            updateCategoryCount();
            
            // Her seçili kategori için rozet oluştur
            selectedCategories.each(function() {
                const categoryName = $(this).closest('.form-check').find('.category-name').text();
                const categoryId = $(this).val();
                
                const categoryBadge = $('<div class="category-badge"></div>');
                categoryBadge.html(`
                    <span>${categoryName}</span>
                    <i class="fas fa-times remove-category" data-id="${categoryId}"></i>
                `);
                selectedCategoriesList.append(categoryBadge);
            });
        }
        
        // Kategori rozetinden kategori kaldırma
        selectedCategoriesList.on('click', '.remove-category', function() {
            const categoryId = $(this).data('id');
            const checkbox = categoryItems.filter(`[value="${categoryId}"]`);
            if (checkbox.length > 0) {
                checkbox.prop('checked', false);
                updateSelectedCategories();
            }
        });

        // Başlangıçta seçili kategorileri göster
        updateSelectedCategories();
        
        // Etiket sistemi
        const tagInput = $('#tag-input');
        const addTagBtn = $('#add-tag-btn');
        const selectedTagsList = $('#selected-tags-list');
        const tagsInput = $('#tags');
        const tagCount = $('.tag-count');
        
        // Mevcut etiketleri yükle (varsa)
        let selectedTags = [];
        if (tagsInput.val()) {
            selectedTags = tagsInput.val().split(',').map(tag => tag.trim()).filter(tag => tag);
            updateTagsDisplay();
        }
        
        // Etiket ekle butonuna tıklandığında
        addTagBtn.on('click', function() {
            addTag();
        });
        
        // Enter tuşuna basıldığında etiket ekle
        tagInput.on('keydown', function(e) {
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
            const tagValue = tagInput.val().trim();
            
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
                tagInput.val('');
                
                // Etiketleri güncelle
                updateTagsDisplay();
            }
        }
        
        // Etiketleri görüntüleme işlevi
        function updateTagsDisplay() {
            // Etiketleri hidden input'a ekle
            tagsInput.val(selectedTags.join(','));
            
            // Etiket sayacını güncelle
            tagCount.text(selectedTags.length);
            
            // Etiket listesini temizle ve yeniden oluştur
            selectedTagsList.empty();
            
            selectedTags.forEach(tag => {
                const tagBadge = $('<div class="category-badge"></div>');
                tagBadge.html(`
                    <span>#${tag}</span>
                    <i class="fas fa-times remove-tag" data-tag="${tag}"></i>
                `);
                selectedTagsList.append(tagBadge);
            });
        }
        
        // Etiket silme işlevi (Event delegation)
        selectedTagsList.on('click', '.remove-tag', function() {
            const tagToRemove = $(this).data('tag');
            
            // Etiketi diziden kaldır
            selectedTags = selectedTags.filter(tag => tag !== tagToRemove);
            
            // Etiket görüntüsünü güncelle
            updateTagsDisplay();
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