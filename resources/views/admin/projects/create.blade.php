@extends('adminlte::page')

@section('title', 'Yeni Proje Ekle')

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
    
    /* FileManagerSystem Görsel Alanı */
    #filemanagersystem_image_preview {
        border: 1px solid #ddd;
        padding: 10px;
        border-radius: 5px;
    }
    
    #filemanagersystem_image_preview img {
        max-width: 100%;
        max-height: 300px;
    }
    
    /* FileManagerSystem Modal Stil */
    #mediapickerModal .modal-dialog {
        max-width: 90%;
        margin: 1.75rem auto;
    }
    
    #mediapickerModal .modal-content {
        height: 90vh;
    }
    
    #mediapickerModal .modal-body {
        padding: 0;
        height: calc(90vh - 60px);
    }
    
    #mediapickerModal .modal-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
    }
</style>
@stop

@section('content_header')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="m-0 text-dark">Yeni Proje Ekle</h1>
        <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Projelere Dön
        </a>
    </div>
@stop

@section('content')
<form action="{{ route('admin.projects.store') }}" method="POST" enctype="multipart/form-data" id="project-form">
    @csrf
    
    <div class="row">
        <div class="col-lg-8">
            <!-- Proje Bilgileri -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Proje Bilgileri</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <label for="title" class="form-label">Proje Başlığı <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required autofocus>
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
                                <span class="text-secondary">{{ url('/') }}/projeler/</span>
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
                        <small class="text-muted">Proje listesinde ve sosyal medya paylaşımlarında görünecek kısa açıklama.</small>
                    </div>
                    
                    <div class="mb-4">
                        <label for="description" class="form-label">İçerik <span class="text-danger">*</span></label>
                        <textarea class="form-control tinymce @error('description') is-invalid @enderror" id="description" name="description" rows="10">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Proje Görseli -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Proje Görseli</h5>
                </div>
                <div class="card-body">
                    <!-- FileManagerSystem Görsel -->
                    <div class="mb-4">
                        <label for="filemanagersystem_image" class="form-label">Kapak Görseli <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" class="form-control @error('cover_image') is-invalid @enderror" id="filemanagersystem_image" name="cover_image" value="{{ old('cover_image') }}" required>
                            <button type="button" class="btn btn-primary" id="filemanagersystem_image_button">
                                <i class="fas fa-image"></i> Görsel Seç
                            </button>
                        </div>
                        @error('cover_image')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <div id="filemanagersystem_image_preview" class="mt-2" style="display: {{ old('cover_image') ? 'block' : 'none' }};">
                            <img src="{{ old('cover_image') }}" alt="Önizleme" class="img-thumbnail">
                        </div>
                        <div class="alert alert-warning mt-2" id="image-warning" style="display: {{ old('cover_image') ? 'none' : 'block' }};">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            <strong>Not:</strong> Ana görsel alanı zorunludur. Lütfen bir görsel seçiniz.
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
            
            <!-- Proje Galerisi -->
            <div class="card mb-4">
                <div class="card-header bg-light d-flex align-items-center">
                    <i class="fas fa-images me-2 text-primary"></i>
                    <h5 class="mb-0">Proje Galerisi</h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Galeri Görselleri <small class="text-muted">(En fazla 10 görsel)</small></label>
                        <div class="gallery-container">
                            <div id="gallery-items" class="d-flex flex-wrap gap-3">
                                <!-- Galeri öğeleri buraya eklenecek -->
                            </div>
                            <button type="button" id="add-gallery-item" class="btn btn-success mt-2">
                                <i class="fas fa-plus"></i> Galeri Görseli Ekle (<span id="gallery-count">0</span>/10)
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- SEO Ayarları -->
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
                        <small class="text-muted">Boş bırakılırsa proje başlığı kullanılacaktır.</small>
                    </div>
                    
                    <div class="mb-4">
                        <label for="meta_description" class="form-label">Meta Açıklama</label>
                        <textarea class="form-control @error('meta_description') is-invalid @enderror" id="meta_description" name="meta_description" rows="3">{{ old('meta_description') }}</textarea>
                        @error('meta_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Boş bırakılırsa proje özeti kullanılacaktır.</small>
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
                    <!-- Durum -->
                    <div class="mb-4">
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', '1') === '1' ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Proje aktif olarak yayınlansın</label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Anasayfada göster -->
                    <div class="mb-4">
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="show_on_homepage" name="show_on_homepage" value="1" {{ old('show_on_homepage') === '1' ? 'checked' : '' }}>
                                <label class="custom-control-label" for="show_on_homepage">Anasayfada göster</label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tamamlanma Yüzdesi -->
                    <div class="mb-4">
                        <label for="completion_percentage" class="form-label">Tamamlanma Durumu (%)</label>
                        <div class="input-group">
                            <input type="number" class="form-control @error('completion_percentage') is-invalid @enderror" id="completion_percentage" name="completion_percentage" value="{{ old('completion_percentage', 100) }}" min="0" max="100">
                            <div class="input-group-append">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        @error('completion_percentage')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Tarih -->
                    <div class="mb-4">
                        <label for="project_date" class="form-label">Proje Tarihi</label>
                        <input type="date" class="form-control @error('project_date') is-invalid @enderror" id="project_date" name="project_date" value="{{ old('project_date', date('Y-m-d')) }}">
                        @error('project_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Sıralama -->
                    <div class="mb-4">
                        <label for="order" class="form-label">Sıralama</label>
                        <input type="number" class="form-control @error('order') is-invalid @enderror" id="order" name="order" value="{{ old('order', 0) }}" min="0">
                        @error('order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Düşük sayılar daha önce gösterilir.</small>
                    </div>
                </div>
            </div>
            
            <!-- Kategori -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Kategori</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Kategori Seçin</label>
                        <select class="form-control @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
                            <option value="">Kategori Seçin</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Kaydet -->
            <div class="card mb-4">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                        <i class="fas fa-save mr-1"></i> Projeyi Kaydet
                    </button>
                    <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary btn-block mt-2">
                        <i class="fas fa-times mr-1"></i> İptal
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- FileManagerSystem Modal -->
<div class="modal fade" id="mediapickerModal" tabindex="-1" role="dialog" aria-labelledby="mediapickerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediapickerModalLabel">Medya Seçici</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <iframe id="mediapickerFrame" src="" style="width: 100%; height: 600px; border: none;"></iframe>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
    <!-- TinyMCE Editör -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.4.2/tinymce.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // TinyMCE Editör
        tinymce.init({
            selector: '.tinymce',
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
                    const relatedType = 'project_content';
                    
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
            const relatedType = 'project_content';
            
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
        
        // Slug oluşturma
        const titleInput = document.getElementById('title');
        const slugInput = document.getElementById('slug');
        const slugPreview = document.getElementById('slug-preview');
        const slugRegenerateBtn = document.getElementById('slug-regenerate');
        let slugIsManual = false; // Kullanıcı manuel olarak slug değiştirmiş mi?
        
        // Slug oluşturma fonksiyonu
        function createSlug(text) {
            const trMap = {
                'ğ': 'g', 'Ğ': 'G', 'ü': 'u', 'Ü': 'U', 'ş': 's', 'Ş': 'S', 'ı': 'i', 'İ': 'I',
                'ö': 'o', 'Ö': 'O', 'ç': 'c', 'Ç': 'C'
            };

            for (let key in trMap) {
                text = text.replace(new RegExp(key, 'g'), trMap[key]);
            }

            return text
                .toLowerCase()
                .trim()
                .replace(/[^a-z0-9-]/g, '-')
                .replace(/-+/g, '-')
                .replace(/^-|-$/g, '');
        }
        
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
            const slugValue = slugInput.value.trim() || 'ornek-proje';
            slugPreview.textContent = slugValue;
        }
        
        // Sayfa yüklendiğinde önizlemeyi güncelle
        updateSlugPreview();
        
        // Kapak Görseli Seçimi
        const imageInput = document.getElementById('filemanagersystem_image');
        const imagePreview = document.getElementById('filemanagersystem_image_preview');
        const imageWarning = document.getElementById('image-warning');
        const imageButton = document.getElementById('filemanagersystem_image_button');
        
        imageInput.addEventListener('change', function() {
            if (this.value) {
                imagePreview.style.display = 'block';
                imageWarning.style.display = 'none';
            } else {
                imagePreview.style.display = 'none';
                imageWarning.style.display = 'block';
            }
        });
        
        // FileManagerSystem görsel seçici
        $('#filemanagersystem_image_button').on('click', function() {
            const input = $('#filemanagersystem_image');
            const preview = $('#filemanagersystem_image_preview');
            const imageWarning = $('#image-warning');
            
            // Geçici ID oluştur
            const tempId = Date.now();
            const relatedType = 'project';
            
            // FileManagerSystem modal için URL
            const mediapickerUrl = '/admin/filemanagersystem/mediapicker?type=image&filter=all&related_type=' + relatedType + '&related_id=' + tempId;
            
            // iframe src'yi güncelle ve modalı göster
            $('#mediapickerFrame').attr('src', mediapickerUrl);
            var modal = new bootstrap.Modal(document.getElementById('mediapickerModal'));
            modal.show();
            
            // Medya seçim olayını dinle
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
                            
                            // Alt ve title alanlarını otomatik doldur
                            if (event.data.alt) {
                                $('#filemanagersystem_image_alt').val(event.data.alt);
                            }
                            
                            if (event.data.title) {
                                $('#filemanagersystem_image_title').val(event.data.title);
                            }
                            
                            // Önizleme güncelleme
                            preview.show();
                            imageWarning.hide();
                            
                            let previewImg = preview.find('img');
                            
                            if (previewImg.length === 0) {
                                previewImg = $('<img />');
                                preview.html(previewImg);
                            }
                            
                            previewImg.attr('src', mediaUrl);
                            previewImg.attr('alt', 'Önizleme');
                            previewImg.addClass('img-thumbnail');
                        } else if (event.data.mediaId) {
                            // URL bulunamadıysa "uploads/" yolu ile dosya ID'sini kullan
                            const previewUrl = '/uploads/media/' + event.data.mediaId;
                            input.val(previewUrl);
                            
                            // Önizleme için ID ile resmi göster
                            let previewImg = preview.find('img');
                            
                            if (previewImg.length === 0) {
                                previewImg = $('<img />');
                                preview.html(previewImg);
                            }
                            
                            previewImg.attr('src', '/admin/filemanagersystem/media/preview/' + event.data.mediaId);
                            previewImg.attr('alt', 'Önizleme');
                            previewImg.addClass('img-thumbnail');
                            
                            preview.show();
                            imageWarning.hide();
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
            
            // Event listener ekle
            window.removeEventListener('message', handleMediaSelection);
            window.addEventListener('message', handleMediaSelection);
        });
        
        // Galeri görsellerini ekleme işlemleri
        const galleryItems = document.getElementById('gallery-items');
        const addGalleryItemButton = document.getElementById('add-gallery-item');
        const galleryCountDisplay = document.getElementById('gallery-count');
        let galleryItemCount = 0;
        
        // Mevcut galeri öğelerini sayı
        function updateGalleryCount() {
            const items = galleryItems.querySelectorAll('.gallery-item').length;
            galleryCountDisplay.textContent = items;
            
            // Maksimum 10 görsel eklenebilir
            if (items >= 10) {
                addGalleryItemButton.disabled = true;
                addGalleryItemButton.classList.add('disabled');
            } else {
                addGalleryItemButton.disabled = false;
                addGalleryItemButton.classList.remove('disabled');
            }
        }
        
        // Sayfa yüklendiğinde mevcut galeri öğelerini oluştur
        @if(old('gallery_images') && is_array(old('gallery_images')))
            @foreach(old('gallery_images') as $index => $image)
                @if(!empty($image))
                    addGalleryItem('{{ $image }}');
                @endif
            @endforeach
        @endif
        
        updateGalleryCount();
        
        // Yeni galeri öğesi ekleme
        addGalleryItemButton.addEventListener('click', function() {
            // Maksimum 10 görsel kontrolü
            if (galleryItems.querySelectorAll('.gallery-item').length >= 10) {
                alert('En fazla 10 görsel ekleyebilirsiniz.');
                return;
            }
            
            // Geçici ID oluştur
            const tempId = Date.now();
            const relatedType = 'project_gallery';
            
            // FileManagerSystem modal için URL
            const mediapickerUrl = '/admin/filemanagersystem/mediapicker?type=image&filter=all&related_type=' + relatedType + '&related_id=' + tempId;
            
            // iframe src'yi güncelle ve modalı göster
            $('#mediapickerFrame').attr('src', mediapickerUrl);
            var modal = new bootstrap.Modal(document.getElementById('mediapickerModal'));
            modal.show();
            
            // Medya seçim olayını dinle
            function gallerySelectHandler(event) {
                try {
                    if (event.data && event.data.type === 'mediaSelected') {
                        // Dosya seçildiğinde
                        if (event.data.mediaUrl) {
                            // Medya URL'sini temizle
                            let mediaUrl = event.data.mediaUrl;
                            
                            // Eğer URL göreceli ise (/ ile başlıyorsa) tam URL'ye çevir
                            if (mediaUrl && mediaUrl.startsWith('/')) {
                                const baseUrl = window.location.protocol + '//' + window.location.host;
                                mediaUrl = baseUrl + mediaUrl;
                            }
                            
                            // Galeri öğesi olarak ekle
                            addGalleryItem(mediaUrl);
                        } else if (event.data.mediaId) {
                            // URL bulunamadıysa "uploads/" yolu ile dosya ID'sini kullan
                            const previewUrl = '/uploads/media/' + event.data.mediaId;
                            
                            // Galeri öğesi olarak ekle
                            addGalleryItem(previewUrl);
                        }
                        
                        // Modal'ı kapat
                        modal.hide();
                        
                        // Event listener'ı kaldır
                        window.removeEventListener('message', gallerySelectHandler);
                    } else if (event.data && event.data.type === 'mediapickerError') {
                        // Hata durumunda
                        console.error('FileManagerSystem hatası:', event.data.message);
                        alert('Bir hata oluştu: ' + event.data.message);
                        modal.hide();
                        
                        // Event listener'ı kaldır
                        window.removeEventListener('message', gallerySelectHandler);
                    }
                } catch (error) {
                    console.error('Galeri seçimi işlenirken hata oluştu:', error);
                    alert('Galeri seçimi işlenirken bir hata oluştu.');
                    
                    // Event listener'ı kaldır
                    window.removeEventListener('message', gallerySelectHandler);
                }
            }
            
            // Event listener ekle
            window.removeEventListener('message', gallerySelectHandler);
            window.addEventListener('message', gallerySelectHandler);
        });
        
        // Galeri öğesi ekleme fonksiyonu
        function addGalleryItem(imageUrl) {
            // Maksimum 10 görsel kontrolü
            if (galleryItems.querySelectorAll('.gallery-item').length >= 10) {
                alert('En fazla 10 görsel ekleyebilirsiniz.');
                return;
            }
            
            const galleryItem = document.createElement('div');
            galleryItem.className = 'gallery-item m-2';
            galleryItem.style.width = '150px';
            galleryItem.style.height = '150px';
            
            const img = document.createElement('img');
            img.src = imageUrl;
            img.alt = 'Galeri Görseli';
            
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `gallery_images[${galleryItemCount}]`;
            input.value = imageUrl;
            
            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'remove-btn';
            removeBtn.innerHTML = '<i class="fas fa-times"></i>';
            removeBtn.onclick = function() {
                galleryItem.remove();
                updateGalleryCount();
            };
            
            galleryItem.appendChild(img);
            galleryItem.appendChild(input);
            galleryItem.appendChild(removeBtn);
            
            galleryItems.appendChild(galleryItem);
            galleryItemCount++;
            
            updateGalleryCount();
        }
        
        // Form submit öncesi doğrulama
        document.getElementById('project-form').addEventListener('submit', function(e) {
            const coverImage = document.getElementById('filemanagersystem_image').value;
            if (!coverImage) {
                e.preventDefault();
                alert('Lütfen bir kapak görseli seçin.');
                document.getElementById('filemanagersystem_image').focus();
                return false;
            }
        });
    });
    </script>
@stop 