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

                <!-- PDF Dosya Yönetimi -->
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-file-pdf"></i> PDF Dosyaları</h5>
                    </div>
                    <div class="card-body">
                        <!-- Hizmet Standartları -->
                        <div class="file-type-section">
                            <div class="file-type-header">
                                <i class="fas fa-clipboard-list"></i> Hizmet Standartları
                            </div>
                            
                            <!-- Mevcut Dosyalar -->
                            @foreach($mudurluk->files->where('type', 'hizmet_standartlari') as $file)
                                <div class="existing-file" data-file-id="{{ $file->id }}">
                                    <div class="file-info">
                                        <i class="fas fa-file-pdf"></i>
                                        <div class="file-details">
                                            <h6>{{ $file->title }}</h6>
                                            <small>{{ $file->file_name }} ({{ number_format($file->file_size / 1024, 2) }} KB)</small>
                                        </div>
                                    </div>
                                    <div class="file-actions">
                                        <a href="{{ route('mudurlukler.download-file', [$mudurluk->slug, $file]) }}" 
                                           class="btn btn-sm btn-primary" target="_blank">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-warning toggle-file-status" 
                                                data-file-id="{{ $file->id }}" 
                                                title="{{ $file->is_active ? 'Pasifleştir' : 'Aktifleştir' }}">
                                            <i class="fas fa-{{ $file->is_active ? 'eye-slash' : 'eye' }}"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger remove-file" 
                                                data-file-id="{{ $file->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                            
                            <!-- Yeni Dosya Ekleme -->
                            <div id="hizmet-standartlari-container">
                                <!-- Dinamik dosya alanları buraya eklenecek -->
                            </div>
                            <button type="button" class="add-file-btn" onclick="addFileField('hizmet_standartlari')">
                                <i class="fas fa-plus"></i> Hizmet Standardı Ekle
                            </button>
                        </div>

                        <!-- Yönetim Şemaları -->
                        <div class="file-type-section">
                            <div class="file-type-header">
                                <i class="fas fa-sitemap"></i> Yönetim Şemaları
                            </div>
                            
                            <!-- Mevcut Dosyalar -->
                            @foreach($mudurluk->files->where('type', 'yonetim_semalari') as $file)
                                <div class="existing-file" data-file-id="{{ $file->id }}">
                                    <div class="file-info">
                                        <i class="fas fa-file-pdf"></i>
                                        <div class="file-details">
                                            <h6>{{ $file->title }}</h6>
                                            <small>{{ $file->file_name }} ({{ number_format($file->file_size / 1024, 2) }} KB)</small>
                                        </div>
                                    </div>
                                    <div class="file-actions">
                                        <a href="{{ route('mudurlukler.download-file', [$mudurluk->slug, $file]) }}" 
                                           class="btn btn-sm btn-primary" target="_blank">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-warning toggle-file-status" 
                                                data-file-id="{{ $file->id }}" 
                                                title="{{ $file->is_active ? 'Pasifleştir' : 'Aktifleştir' }}">
                                            <i class="fas fa-{{ $file->is_active ? 'eye-slash' : 'eye' }}"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger remove-file" 
                                                data-file-id="{{ $file->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                            
                            <!-- Yeni Dosya Ekleme -->
                            <div id="yonetim-semalari-container">
                                <!-- Dinamik dosya alanları buraya eklenecek -->
                            </div>
                            <button type="button" class="add-file-btn" onclick="addFileField('yonetim_semalari')">
                                <i class="fas fa-plus"></i> Yönetim Şeması Ekle
                            </button>
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

                <!-- Hizmet Kategorileri -->
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-tags"></i> Hizmet Kategorileri</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="service_category_ids" class="form-label">İlgili Hizmet Kategorileri</label>
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
                                Bu müdürlükle ilişkili hizmet kategorilerini seçin. Seçilen kategorilerdeki hizmetler müdürlük sayfasında görüntülenecektir.
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
<script>
    let fileCounters = {
        hizmet_standartlari: 0,
        yonetim_semalari: 0
    };

    $(document).ready(function() {
        // TinyMCE başlat
        initTinyMCE();
        
        // Slug otomatik oluşturma
        $('#name').on('input', function() {
            if ($('#slug').val() === '{{ $mudurluk->slug }}') {
                let slug = $(this).val()
                    .toLowerCase()
                    .replace(/ğ/g, 'g')
                    .replace(/ü/g, 'u')
                    .replace(/ş/g, 's')
                    .replace(/ı/g, 'i')
                    .replace(/ö/g, 'o')
                    .replace(/ç/g, 'c')
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-')
                    .trim('-');
                $('#slug').val(slug);
            }
        });

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

    function addFileField(type) {
        const container = document.getElementById(type.replace('_', '-') + '-container');
        if (!container) {
            console.error('Container bulunamadı:', type.replace('_', '-') + '-container');
            return;
        }
        
        const index = fileCounters[type];
        
        const fileDiv = document.createElement('div');
        fileDiv.className = 'file-upload-section';
        fileDiv.innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Dosya Başlığı</label>
                    <input type="text" class="form-control" name="${type}_titles[]" placeholder="Dosya başlığı...">
                </div>
                <div class="col-md-5">
                    <label class="form-label">PDF Dosyası</label>
                    <input type="file" class="form-control" name="${type}_files[]" accept=".pdf">
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger" onclick="removeFileField(this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
        
        container.appendChild(fileDiv);
        fileCounters[type]++;
    }

    function removeFileField(button) {
        button.closest('.file-upload-section').remove();
    }

    // Form gönderilmeden önce TinyMCE içeriğini kaydet
    $('#mudurluk-form').on('submit', function() {
        tinymce.triggerSave();
    });
</script>
@stop 