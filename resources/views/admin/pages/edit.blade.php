@extends('adminlte::page')

@section('title', 'Sayfa Düzenle')

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
                            <div class="input-group">
                                <span class="input-group-text text-muted" id="slug-addon">{{ url('/') }}/</span>
                                <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $page->slug) }}" aria-describedby="slug-addon">
                            </div>
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Boş bırakırsanız başlıktan otomatik oluşturulacaktır.</small>
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
                        
                <!-- Medya -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Medya</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label for="image" class="form-label">Ana Görsel <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control @error('image') is-invalid @enderror" id="image" name="image" value="{{ old('image', $page->image) }}" required readonly>
                                <button class="btn btn-primary" type="button" id="image-browser">
                                    <i class="fas fa-folder-open"></i> Göz At
                                </button>
                                <button class="btn btn-danger" type="button" id="image-clear">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div id="image-preview" class="mt-3" style="{{ old('image', $page->image) ? '' : 'display: none;' }}">
                                <div class="position-relative d-inline-block">
                                    <img src="{{ old('image', $page->image) }}" class="img-fluid" alt="Seçilen görsel" style="max-height: 200px; max-width: 100%; width: auto; height: auto; object-fit: contain;">
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
                            <div class="gallery-container" id="gallery-preview" style="{{ count($page->gallery ?? []) > 0 ? 'display: grid;' : 'display: none;' }}">
                                @if(!empty($page->gallery))
                                    @foreach($page->gallery as $index => $galleryItem)
                                        <div class="gallery-item" data-id="gallery-existing-{{ $index }}">
                                            <img src="{{ $galleryItem }}" class="img-fluid" alt="Galeri görsel">
                                            <button type="button" class="remove-btn" data-id="gallery-existing-{{ $index }}">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                        <input type="hidden" name="gallery[]" id="gallery-existing-{{ $index }}" value="{{ $galleryItem }}">
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
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Kategoriler</h5>
                        <a href="{{ route('admin.page-categories.create') }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-plus"></i> Yeni Kategori
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="category-select-container mb-3">
                            <select id="categories" name="categories[]" multiple class="form-control">
                                @foreach($categories ?? [] as $category)
                                    <option value="{{ $category->id }}" {{ in_array($category->id, old('categories', $page->categories->pluck('id')->toArray())) ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('categories')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                        
                <!-- Etiketler -->
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Etiketler</h5>
                    </div>
                    <div class="card-body">
                        <div class="tag-select-container mb-3">
                            <input type="text" id="tags-input" name="tags" value="{{ old('tags', $page->tags ? implode(',', $page->tags) : '') }}" class="form-control">
                        </div>
                        @error('tags')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i> 
                            Birden fazla etiket eklemek için virgül kullanın veya Enter tuşuna basın.
                        </small>
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
@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.tr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // TinyMCE için düzenleyici ayarları
        tinymce.init({
            selector: '#content',
            plugins: 'preview importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons',
            menubar: 'file edit view insert format tools table help',
            toolbar: 'undo redo | bold italic underline strikethrough | fontfamily fontsize blocks | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl',
            toolbar_sticky: true,
            image_advtab: true,
            height: 500,
            quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
            noneditable_class: 'mceNonEditable',
            toolbar_mode: 'sliding',
            contextmenu: 'link image table',
            skin: 'oxide',
            content_css: 'default',
            content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 16px; }',
            language: 'tr',
            language_url: '/js/tinymce/langs/tr.js', // Türkçe dil dosyası
            relative_urls: false,
            remove_script_host: false,
            convert_urls: true,
            file_picker_callback: function (callback, value, meta) {
                // Dosya seçicisi için özel entegrasyon
                if (meta.filetype === 'file' || meta.filetype === 'image') {
                    window.open('/filemanager/dialog.php?type=' + meta.filetype + '&field_id=tinymce-file', 'filemanager', 'width=900,height=600');
                    window.SetUrl = function (url, width, height, alt) {
                        callback(url, {alt: alt});
                    };
                }
            }
        });
        
        // URL oluşturucu
        document.getElementById('title').addEventListener('blur', function() {
            const slugInput = document.getElementById('slug');
            if (slugInput.value === '') {
                const title = this.value;
                const slug = title.toLowerCase()
                    .replace(/[^\w\s-]/g, '') // Özel karakterleri kaldır
                    .replace(/\s+/g, '-') // Boşlukları tirelere çevir
                    .replace(/--+/g, '-') // Çoklu tireleri tek tireye indir
                    .trim('-'); // Baştaki ve sondaki tireleri kaldır
                slugInput.value = slug;
            }
        });
        
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
        
        // Medya yönetimi
        document.getElementById('image-browser').addEventListener('click', function() {
            window.open('/filemanager/dialog.php?type=1&field_id=image', 'filemanager', 'width=900,height=600');
        });
        
        document.getElementById('image-clear').addEventListener('click', function() {
            document.getElementById('image').value = '';
            document.getElementById('image-preview').style.display = 'none';
            document.querySelector('#image-preview img').src = '';
        });
        
        // Ana görsel önizleme
        const imageInput = document.getElementById('image');
        imageInput.addEventListener('change', updateImagePreview);
        
        function updateImagePreview() {
            const preview = document.getElementById('image-preview');
            const previewImg = preview.querySelector('img');
            
            if (imageInput.value) {
                previewImg.src = imageInput.value;
                preview.style.display = 'block';
            } else {
                preview.style.display = 'none';
            }
        }
        
        // Galeri yönetimi
        let galleryCounter = {{ count($page->gallery ?? []) }};
        
        document.getElementById('gallery-browser').addEventListener('click', function() {
            window.open('/filemanager/dialog.php?type=1&field_id=gallery-' + galleryCounter, 'filemanager', 'width=900,height=600');
            window.SetUrl = function(url, width, height, alt) {
                addGalleryItem(url);
                galleryCounter++;
            };
        });
        
        function addGalleryItem(url) {
            const galleryContainer = document.getElementById('gallery-preview');
            const inputsContainer = document.getElementById('gallery-inputs');
            const id = 'gallery-' + galleryCounter;
            
            // Önizleme öğesini oluştur
            const galleryItem = document.createElement('div');
            galleryItem.className = 'gallery-item';
            galleryItem.dataset.id = id;
            galleryItem.innerHTML = `
                <img src="${url}" class="img-fluid" alt="Galeri görsel">
                <button type="button" class="remove-btn" data-id="${id}">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            // Gizli input oluştur
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'gallery[]';
            input.id = id;
            input.value = url;
            
            galleryContainer.appendChild(galleryItem);
            inputsContainer.appendChild(input);
            galleryContainer.style.display = 'grid';
            
            // Silme işlemini ekle
            galleryItem.querySelector('.remove-btn').addEventListener('click', function() {
                const itemId = this.dataset.id;
                document.querySelector(`.gallery-item[data-id="${itemId}"]`).remove();
                document.getElementById(itemId).remove();
                
                if (galleryContainer.children.length === 0) {
                    galleryContainer.style.display = 'none';
                }
            });
        }
        
        // Var olan galeri öğeleri için silme fonksiyonu ekle
        document.querySelectorAll('.gallery-item .remove-btn').forEach(button => {
            button.addEventListener('click', function() {
                const itemId = this.dataset.id;
                document.querySelector(`.gallery-item[data-id="${itemId}"]`).remove();
                document.getElementById(itemId).remove();
                
                const galleryContainer = document.getElementById('gallery-preview');
                if (galleryContainer.children.length === 0) {
                    galleryContainer.style.display = 'none';
                }
            });
        });
        
        // Responsinator için medya yükleme callback'i
        window.responsive_filemanager_callback = function(field_id) {
            const url = document.getElementById(field_id).value;
            
            if (field_id === 'image') {
                updateImagePreview();
            } else if (field_id.startsWith('gallery-')) {
                // Galeri için işlem yapma (url zaten eklenmiş olacak)
                document.getElementById('gallery-preview').style.display = 'grid';
            }
        };
    });
</script>
@stop 