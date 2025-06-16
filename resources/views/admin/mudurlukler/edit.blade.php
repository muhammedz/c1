@extends('adminlte::page')

@section('title', 'Müdürlük Düzenle')

@section('plugins.Toastr', true)

@section('css')
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
    
    /* Dosya yükleme alanları */
    .file-upload-section {
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 15px;
        background: #f8f9fa;
    }
    
    .file-upload-section:hover {
        border-color: #3490dc;
        background: #f1f7fe;
    }
    
    .existing-file {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 15px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        margin-bottom: 10px;
        background: white;
        box-shadow: 0 1px 3px rgba(0,0,0,.1);
    }
    
    .existing-file .file-info {
        display: flex;
        align-items: center;
        flex-grow: 1;
    }
    
    .existing-file .file-info i {
        margin-right: 15px;
        color: #dc3545;
        font-size: 1.2em;
    }
    
    .existing-file .file-details h6 {
        margin: 0;
        font-weight: 600;
        color: #2c3e50;
    }
    
    .existing-file .file-details small {
        color: #6c757d;
    }
    
    .existing-file .file-actions {
        display: flex;
        gap: 5px;
    }
    
    .existing-file .file-actions .btn {
        padding: 5px 10px;
        font-size: 0.875rem;
    }
    
    .add-file-btn {
        background: #28a745;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 5px;
        cursor: pointer;
        margin-top: 10px;
    }
    
    .add-file-btn:hover {
        background: #218838;
    }
    
    .file-type-section {
        margin-bottom: 30px;
    }
    
    .file-type-header {
        background: #e9ecef;
        padding: 10px 15px;
        border-radius: 5px;
        margin-bottom: 15px;
        font-weight: 600;
        color: #495057;
    }
</style>
@stop

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Müdürlük Düzenle: {{ $mudurluk->name }}</h1>
        <div>
            @if($mudurluk->slug)
                <a href="{{ route('mudurlukler.show', $mudurluk->slug) }}" class="btn btn-info" target="_blank">
                    <i class="fas fa-eye"></i> Önizle
                </a>
            @endif
            <a href="{{ route('admin.mudurlukler.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Geri Dön
            </a>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
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

    <form action="{{ route('admin.mudurlukler.update', $mudurluk) }}" method="POST" enctype="multipart/form-data" id="mudurluk-form">
        @csrf
        @method('PUT')
        
        <div class="row">
            <!-- Sol Kolon -->
            <div class="col-lg-8">
                <!-- Temel Bilgiler -->
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-info-circle"></i> Temel Bilgiler</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Müdürlük Adı <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $mudurluk->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="slug" class="form-label">URL Slug</label>
                                    <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                                           id="slug" name="slug" value="{{ old('slug', $mudurluk->slug) }}">
                                    @error('slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Boş bırakılırsa otomatik oluşturulur</small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="summary" class="form-label">Kısa Açıklama</label>
                            <textarea class="form-control @error('summary') is-invalid @enderror" 
                                      id="summary" name="summary" rows="3">{{ old('summary', $mudurluk->summary) }}</textarea>
                            @error('summary')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- TinyMCE Editör Alanları -->
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-edit"></i> Detaylı İçerik</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label for="gorev_tanimi_ve_faaliyet_alani" class="form-label">Görev Tanımı ve Faaliyet Alanı</label>
                            <textarea class="form-control tinymce @error('gorev_tanimi_ve_faaliyet_alani') is-invalid @enderror" 
                                      id="gorev_tanimi_ve_faaliyet_alani" name="gorev_tanimi_ve_faaliyet_alani">{{ old('gorev_tanimi_ve_faaliyet_alani', $mudurluk->gorev_tanimi_ve_faaliyet_alani) }}</textarea>
                            @error('gorev_tanimi_ve_faaliyet_alani')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="yetki_ve_sorumluluklar" class="form-label">Yetki ve Sorumluluklar</label>
                            <textarea class="form-control tinymce @error('yetki_ve_sorumluluklar') is-invalid @enderror" 
                                      id="yetki_ve_sorumluluklar" name="yetki_ve_sorumluluklar">{{ old('yetki_ve_sorumluluklar', $mudurluk->yetki_ve_sorumluluklar) }}</textarea>
                            @error('yetki_ve_sorumluluklar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- PDF Belgeleri -->
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-file-pdf me-2 text-danger"></i>
                            <h5 class="mb-0">Belgeler</h5>
                            <span class="badge bg-secondary ms-2">{{ $mudurluk->files->count() }}</span>
                        </div>
                        <div>
                            <button type="button" class="btn btn-sm btn-primary" id="add-new-document">
                                <i class="fas fa-plus"></i> Yeni Belge Ekle
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">Bu müdürlüğe ait belgeleri yükleyebilirsiniz. Desteklenen format: PDF (Maksimum 10MB/dosya)</p>
                        
                        <!-- Mevcut Belgeler -->
                        @if($mudurluk->files->count() > 0)
                            <div class="mb-4">
                                <h6 class="mb-3">
                                    <i class="fas fa-list me-1"></i> Mevcut Belgeler
                                </h6>
                                @foreach($mudurluk->files as $file)
                                    <div class="existing-file border rounded p-3 mb-3" data-file-id="{{ $file->id }}">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="file-info d-flex align-items-center">
                                                <i class="fas fa-file-pdf text-danger me-3" style="font-size: 1.5em;"></i>
                                                <div class="file-details">
                                                    <h6 class="mb-1">{{ $file->title }}</h6>
                                                    <small class="text-muted">{{ $file->file_name }} ({{ number_format($file->file_size / 1024, 2) }} KB)</small>
                                                    <div class="mt-1">
                                                        <span class="badge bg-{{ $file->is_active ? 'success' : 'secondary' }}">
                                                            {{ $file->is_active ? 'Aktif' : 'Pasif' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="file-actions d-flex gap-2">
                                                <a href="{{ route('mudurlukler.download-file', [$mudurluk->slug, $file]) }}" 
                                                   class="btn btn-sm btn-primary" target="_blank" title="İndir">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-info edit-document" 
                                                        data-id="{{ $file->id }}" data-name="{{ $file->title }}" 
                                                        title="Düzenle">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-warning toggle-file-status" 
                                                        data-file-id="{{ $file->id }}" 
                                                        title="{{ $file->is_active ? 'Pasifleştir' : 'Aktifleştir' }}">
                                                    <i class="fas fa-{{ $file->is_active ? 'eye-slash' : 'eye' }}"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger remove-file" 
                                                        data-file-id="{{ $file->id }}" title="Sil">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-file-pdf fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Henüz belge eklenmemiş.</p>
                            </div>
                        @endif

                        <!-- Toplu Belge Yükleme -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">
                                    <i class="fas fa-upload me-1"></i> Toplu Belge Yükleme
                                </h6>
                                <button type="button" class="btn btn-outline-success btn-sm" id="toggle-bulk-upload">
                                    <i class="fas fa-plus"></i> Toplu Yükleme
                                </button>
                            </div>
                            
                            <div id="bulk-upload-form" style="display: none;">
                                <div class="border rounded p-3 bg-light">
                                    <div class="form-group mb-3">
                                        <label for="bulk_document_files">PDF Dosyaları</label>
                                        <input type="file" class="form-control" id="bulk_document_files" multiple accept=".pdf">
                                        <small class="form-text text-muted">
                                            Birden fazla PDF dosyası seçebilirsiniz. (Maksimum 10MB/dosya)
                                        </small>
                                    </div>
                                    
                                    <div id="selected-files-preview" class="mb-3" style="display: none;">
                                        <h6>Seçilen Dosyalar:</h6>
                                        <div id="files-list"></div>
                                    </div>
                                    
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-success" id="upload-bulk-documents">
                                            <i class="fas fa-upload"></i> Tüm Dosyaları Yükle
                                        </button>
                                        <button type="button" class="btn btn-secondary" id="cancel-bulk-upload">
                                            <i class="fas fa-times"></i> İptal
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Belge Yükleme Bilgisi -->
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Belge yüklemek için:</strong> Tek dosya için "Yeni Belge Ekle", birden fazla dosya için "Toplu Yükleme" butonunu kullanın. Belgeler AJAX ile anında yüklenir.
                        </div>
                    </div>
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
                            <label for="new_document_name">Belge Adı <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="new_document_name" name="document_name">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="new_document_file">PDF Dosyası <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="new_document_file" name="file" accept=".pdf">
                        </div>
                    </div>
                </div>
                
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload"></i> Belge Yükle
                    </button>
                    <button type="button" class="btn btn-secondary" id="cancel-new-document-form">
                        <i class="fas fa-times"></i> İptal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
            </div>

            <!-- Sağ Kolon -->
            <div class="col-lg-4">
                <!-- Yayınlama Ayarları -->
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-cog"></i> Yayınlama Ayarları</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="is_active" class="form-label">Durum</label>
                            <select class="form-select @error('is_active') is-invalid @enderror" id="is_active" name="is_active">
                                <option value="1" {{ old('is_active', $mudurluk->is_active) == '1' ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ old('is_active', $mudurluk->is_active) == '0' ? 'selected' : '' }}>Pasif</option>
                            </select>
                            @error('is_active')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="order_column" class="form-label">Sıralama</label>
                            <input type="number" class="form-control @error('order_column') is-invalid @enderror" 
                                   id="order_column" name="order_column" value="{{ old('order_column', $mudurluk->order_column) }}" min="0">
                            @error('order_column')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">İstatistikler</label>
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="bg-light p-2 rounded">
                                        <strong>{{ $mudurluk->view_count ?? 0 }}</strong><br>
                                        <small>Görüntülenme</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-light p-2 rounded">
                                        <strong>{{ $mudurluk->files->count() }}</strong><br>
                                        <small>Dosya</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ana Görsel -->
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-image"></i> Ana Görsel</h5>
                    </div>
                    <div class="card-body">
                        @if($mudurluk->image)
                            <div class="current-image mb-3">
                                <img src="{{ asset('storage/' . $mudurluk->image) }}" alt="{{ $mudurluk->name }}" 
                                     class="img-fluid rounded" style="max-height: 200px;">
                                <div class="mt-2">
                                    <small class="text-muted">Mevcut görsel</small>
                                </div>
                            </div>
                        @endif
                        
                        <div class="mb-3">
                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                   id="image" name="image" accept="image/*" onchange="previewImage(this)">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">JPG, PNG, GIF formatları desteklenir. Maksimum 2MB.</small>
                        </div>
                        <div id="image-preview" style="display: none;">
                            <img id="preview-img" src="" alt="Önizleme">
                        </div>
                    </div>
                </div>

                <!-- SEO Ayarları -->
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-search"></i> SEO Ayarları</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="meta_title" class="form-label">Meta Başlık</label>
                            <input type="text" class="form-control @error('meta_title') is-invalid @enderror" 
                                   id="meta_title" name="meta_title" value="{{ old('meta_title', $mudurluk->meta_title) }}" maxlength="60">
                            @error('meta_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Maksimum 60 karakter</small>
                        </div>

                        <div class="mb-3">
                            <label for="meta_description" class="form-label">Meta Açıklama</label>
                            <textarea class="form-control @error('meta_description') is-invalid @enderror" 
                                      id="meta_description" name="meta_description" rows="3" maxlength="160">{{ old('meta_description', $mudurluk->meta_description) }}</textarea>
                            @error('meta_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Maksimum 160 karakter</small>
                        </div>
                    </div>
                </div>

                <!-- Müdürlükler Kategorisi -->
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-tags"></i> Müdürlükler Kategorisi</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="service_category_ids" class="form-label">İlgili Müdürlükler Kategorisi</label>
                            <div class="category-list" style="max-height: 300px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 4px; padding: 10px;">
                                @foreach($serviceCategories as $category)
                                <div class="form-check mb-2">
                                    <input type="checkbox" name="service_category_ids[]" value="{{ $category->id }}" 
                                           class="form-check-input" id="category_{{ $category->id }}"
                                           {{ in_array($category->id, old('service_category_ids', $mudurluk->serviceCategories->pluck('id')->toArray())) ? 'checked' : '' }}>
                                    <label class="form-check-label d-flex align-items-center" for="category_{{ $category->id }}">
                                        @if($category->icon)
                                            <i class="{{ $category->icon }} me-2" style="font-size: 0.9em;"></i>
                                        @endif
                                        <span>{{ $category->name }}</span>
                                        @if($category->description)
                                            <i class="fas fa-info-circle ms-2 text-muted" style="font-size: 0.8em;" 
                                               data-bs-toggle="tooltip" title="{{ $category->description }}"></i>
                                        @endif
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            <small class="form-text text-muted">
                                Bu müdürlükle ilişkili müdürlük kategorilerini seçin. Seçilen kategorilerdeki hizmetler müdürlük sayfasında görüntülenecektir.
                            </small>
                            @error('service_category_ids')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Güncelle Butonu -->
                <div class="card">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-save"></i> Değişiklikleri Kaydet
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@stop

@section('js')
<script src="{{ asset('vendor/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('js/slug-helper.js') }}"></script>
<script>
    let fileCounters = {
        hizmet_standartlari: 0,
        yonetim_semalari: 0
    };

    $(document).ready(function() {
        // TinyMCE başlat
        initTinyMCE();
        
        // Slug otomatik oluşturma - SlugHelper kullanımı
        SlugHelper.autoSlug('#name', '#slug');

        // Dosya silme
        $('.remove-file').on('click', function() {
            const fileId = $(this).data('file-id');
            const fileElement = $(this).closest('.existing-file');
            
            if (confirm('Bu dosyayı silmek istediğinizden emin misiniz?')) {
                $.ajax({
                    url: '{{ route("admin.mudurlukler.remove-file", ":id") }}'.replace(':id', fileId),
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            fileElement.fadeOut(300, function() {
                                $(this).remove();
                            });
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
                        toastr.error('Dosya silinirken bir hata oluştu.');
                    }
                });
            }
        });

        // Dosya durumu değiştirme
        $('.toggle-file-status').on('click', function() {
            const fileId = $(this).data('file-id');
            const button = $(this);
            
            $.ajax({
                url: '{{ route("admin.mudurlukler.toggle-file", ":id") }}'.replace(':id', fileId),
                type: 'PATCH',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        const icon = button.find('i');
                        if (response.is_active) {
                            icon.removeClass('fa-eye').addClass('fa-eye-slash');
                            button.attr('title', 'Pasifleştir');
                        } else {
                            icon.removeClass('fa-eye-slash').addClass('fa-eye');
                            button.attr('title', 'Aktifleştir');
                        }
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('Dosya durumu değiştirilirken bir hata oluştu.');
                }
            });
        });

        // ===== BELGE YÖNETİMİ BAŞLANGIÇ =====
        
        // Yeni belge ekleme formunu göster/gizle
        $('#add-new-document').on('click', function() {
            if ($('#document-upload-form-container').is(':visible')) {
                $('#document-upload-form-container').hide();
                $(this).html('<i class="fas fa-plus"></i> Yeni Belge Ekle');
            } else {
                $('#document-upload-form-container').show();
                $(this).html('<i class="fas fa-minus"></i> Belge Yüklemeyi Gizle');
                $('#new_document_name').focus();
                
                // Sayfayı forma kaydır
                $('html, body').animate({
                    scrollTop: $('#document-upload-form-container').offset().top - 100
                }, 500);
            }
        });

        $('#cancel-new-document-form').on('click', function() {
            $('#document-upload-form-container').hide();
            $('#add-new-document').html('<i class="fas fa-plus"></i> Yeni Belge Ekle');
            $('#document-upload-form')[0].reset();
        });

        // Toplu belge yükleme formunu göster/gizle
        $('#toggle-bulk-upload').on('click', function() {
            if ($('#bulk-upload-form').is(':visible')) {
                $('#bulk-upload-form').hide();
                $(this).html('<i class="fas fa-plus"></i> Toplu Yükleme');
            } else {
                $('#bulk-upload-form').show();
                $(this).html('<i class="fas fa-minus"></i> Toplu Yüklemeyi Gizle');
                $('#bulk_document_files').focus();
            }
        });

        $('#cancel-bulk-upload').on('click', function() {
            $('#bulk-upload-form').hide();
            $('#toggle-bulk-upload').html('<i class="fas fa-plus"></i> Toplu Yükleme');
            $('#bulk_document_files').val('');
            $('#selected-files-preview').hide();
            $('#files-list').empty();
        });

        // Dosya seçimi önizlemesi
        $('#bulk_document_files').on('change', function() {
            var files = this.files;
            var filesList = $('#files-list');
            var preview = $('#selected-files-preview');
            
            filesList.empty();
            
            if (files.length > 0) {
                preview.show();
                
                for (var i = 0; i < files.length; i++) {
                    var file = files[i];
                    var fileSize = (file.size / 1024 / 1024).toFixed(2) + ' MB';
                    var fileName = file.name;
                    var nameWithoutExt = fileName.substring(0, fileName.lastIndexOf('.')) || fileName;
                    
                    var fileItem = $(`
                        <div class="border rounded p-2 mb-2 d-flex align-items-center">
                            <i class="fas fa-file-pdf text-danger me-2"></i>
                            <div class="flex-grow-1">
                                <div class="fw-bold">${fileName}</div>
                                <small class="text-muted">${fileSize}</small>
                            </div>
                            <div class="ms-2">
                                <input type="text" class="form-control form-control-sm file-name-input" 
                                       placeholder="Belge adı (opsiyonel)" value="${nameWithoutExt}" data-index="${i}">
                            </div>
                        </div>
                    `);
                    
                    filesList.append(fileItem);
                }
            } else {
                preview.hide();
            }
        });

        // Toplu belge yükleme
        $('#upload-bulk-documents').on('click', function() {
            var files = $('#bulk_document_files')[0].files;
            
            if (files.length === 0) {
                alert('Lütfen en az bir dosya seçin.');
                return;
            }
            
            var formData = new FormData();
            var fileNames = [];
            
            // Dosyaları FormData'ya ekle
            for (var i = 0; i < files.length; i++) {
                formData.append('files[]', files[i]);
                
                // Dosya adını belirle
                var customName = $('.file-name-input[data-index="' + i + '"]').val();
                if (customName && customName.trim() !== '') {
                    fileNames.push(customName.trim());
                } else {
                    // Dosya adından uzantıyı çıkar
                    var fileName = files[i].name;
                    var nameWithoutExt = fileName.substring(0, fileName.lastIndexOf('.')) || fileName;
                    fileNames.push(nameWithoutExt);
                }
            }
            
            // Dosya adlarını FormData'ya ekle
            for (var j = 0; j < fileNames.length; j++) {
                formData.append('names[]', fileNames[j]);
            }
            
            formData.append('_token', '{{ csrf_token() }}');
            

            
            var submitBtn = $(this);
            var originalText = submitBtn.html();
            
            submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Yükleniyor...');
            
            $.ajax({
                                 url: '{{ route("admin.mudurlukler.bulk-upload-documents", $mudurluk) }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        alert(response.message + '\nYüklenen belgeler: ' + (response.uploaded_documents ? response.uploaded_documents.length : 0));
                        
                        // Formu temizle ve gizle
                        $('#bulk_document_files').val('');
                        $('#selected-files-preview').hide();
                        $('#files-list').empty();
                        $('#bulk-upload-form').hide();
                        $('#toggle-bulk-upload').html('<i class="fas fa-plus"></i> Toplu Yükleme');
                        
                        // Sayfayı yenile
                        location.reload();
                    } else {
                        var errorDetails = '';
                        if (response.errors && response.errors.length > 0) {
                            errorDetails = '\n\nHata detayları:\n' + response.errors.join('\n');
                        }
                        alert('Hata: ' + response.message + errorDetails);
                    }
                },
                error: function(xhr) {
                    
                    var errors = xhr.responseJSON ? xhr.responseJSON.errors : null;
                    if (errors) {
                        var errorMsg = '';
                        $.each(errors, function(key, value) {
                            errorMsg += key + ': ' + value[0] + '\n';
                        });
                        alert('Validation Hataları:\n' + errorMsg);
                    } else {
                        alert('Bir hata oluştu. Status: ' + xhr.status + '\nDetay: ' + (xhr.responseJSON ? xhr.responseJSON.message : xhr.responseText));
                    }
                },
                complete: function() {
                    submitBtn.prop('disabled', false).html(originalText);
                }
            });
        });

        // Belge yükleme formu (ana formdan bağımsız)
        $('#document-upload-form').on('submit', function(e) {
            e.preventDefault();
            
            // Form validation
            var documentName = $('#new_document_name').val().trim();
            var file = $('#new_document_file')[0].files[0];
            
            if (!documentName) {
                alert('Lütfen belge adını girin.');
                $('#new_document_name').focus();
                return;
            }
            
            if (!file) {
                alert('Lütfen bir PDF dosyası seçin.');
                $('#new_document_file').focus();
                return;
            }
            
            var formData = new FormData(this);
            var submitBtn = $(this).find('button[type="submit"]');
            var originalText = submitBtn.html();
            
            submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Yükleniyor...');
            
            // Müdürlük belge yükleme route'unu kullanacağız
            var uploadUrl = '{{ route("admin.mudurlukler.upload-document", $mudurluk) }}';
            
            $.ajax({
                url: uploadUrl,
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
            
            var newName = prompt('Belge adını düzenleyin:', documentName);
            if (newName && newName !== documentName) {
                $.ajax({
                    url: '{{ route("admin.mudurlukler.update-document", [$mudurluk, "__DOCUMENT_ID__"]) }}'.replace('__DOCUMENT_ID__', documentId),
                    type: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}',
                        name: newName
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

        // Dosya silme (dinamik eklenen dosyalar için)
        $(document).on('click', '.remove-document', function() {
            $(this).closest('.document-item').remove();
            if ($('#documents-container').children().length === 0) {
                $('#documents-list').hide();
            }
        });

        // Form gönderilmeden önce TinyMCE içeriğini kaydet
        $('#mudurluk-form').on('submit', function(e) {
            // TinyMCE içeriğini kaydet
            tinymce.triggerSave();
            
            return true;
        });

        // ===== BELGE YÖNETİMİ BİTİŞ =====
    });

    function initTinyMCE() {
        tinymce.init({
            selector: '.tinymce',
            height: 400,
            menubar: false,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | blocks | bold italic forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
            language: 'tr',
            images_upload_url: '{{ route("admin.tinymce.upload") }}',
            images_upload_handler: function (blobInfo, success, failure) {
                var xhr, formData;
                xhr = new XMLHttpRequest();
                xhr.withCredentials = false;
                xhr.open('POST', '{{ route("admin.tinymce.upload") }}');
                xhr.setRequestHeader("X-CSRF-TOKEN", '{{ csrf_token() }}');
                xhr.onload = function() {
                    var json;
                    if (xhr.status != 200) {
                        failure('HTTP Error: ' + xhr.status);
                        return;
                    }
                    json = JSON.parse(xhr.responseText);
                    if (!json || typeof json.location != 'string') {
                        failure('Invalid JSON: ' + xhr.responseText);
                        return;
                    }
                    success(json.location);
                };
                formData = new FormData();
                formData.append('file', blobInfo.blob(), blobInfo.filename());
                xhr.send(formData);
            },
            setup: function (editor) {
                editor.on('change', function () {
                    editor.save();
                });
            }
        });
    }

    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#preview-img').attr('src', e.target.result);
                $('#image-preview').show();
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Belge listesine ekleme fonksiyonu
    function addDocumentToList(document) {
        var fileSize = (document.file.size / 1024 / 1024).toFixed(2) + ' MB';
        
        var documentItem = $(`
            <div class="border rounded p-3 mb-2 document-item" data-temp="true">
                <div class="d-flex align-items-center">
                    <i class="fas fa-file-pdf text-danger me-2"></i>
                    <div class="flex-grow-1">
                        <h6 class="mb-1">${document.name}</h6>
                        <small class="text-muted">${document.file.name} (${fileSize})</small>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger remove-document">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `);
        
        $('#documents-container').append(documentItem);
        $('#documents-list').show();
    }
</script>
@stop 