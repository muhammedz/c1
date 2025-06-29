@extends('adminlte::page')

@section('title', 'Başkan Sayfası Yönetimi')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">
                    <i class="fas fa-user-tie text-primary mr-2"></i>
                    Başkan Sayfası Yönetimi
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Ana Sayfa</a></li>
                    <li class="breadcrumb-item active">Başkan Sayfası</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <i class="fas fa-exclamation-triangle mr-2"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- İçerik Yönetimi Kartları -->
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="mb-3">
                <i class="fas fa-layer-group text-info mr-2"></i>
                İçerik Yönetimi
            </h4>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Hikayeler -->
        <div class="col-lg-4 col-md-6">
            <div class="card card-widget widget-user-2 shadow-sm">
                <div class="widget-user-header bg-warning">
                    <div class="widget-user-image">
                        <i class="fas fa-book fa-2x"></i>
                    </div>
                    <h3 class="widget-user-username">Hikayeler</h3>
                    <h5 class="widget-user-desc">Başkanın hikayeleri</h5>
                </div>
                <div class="card-footer p-0">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="{{ route('admin.mayor-content.index', ['type' => 'story']) }}" class="nav-link">
                                <i class="fas fa-list mr-2"></i>
                                Hikayeleri Yönet
                                <span class="float-right badge bg-warning">{{ $mayor->stories->count() }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.mayor-content.create', ['type' => 'story']) }}" class="nav-link">
                                <i class="fas fa-plus mr-2"></i>
                                Yeni Hikaye Ekle
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Gündem -->
        <div class="col-lg-4 col-md-6">
            <div class="card card-widget widget-user-2 shadow-sm">
                <div class="widget-user-header bg-info">
                    <div class="widget-user-image">
                        <i class="fas fa-calendar fa-2x"></i>
                    </div>
                    <h3 class="widget-user-username">Gündem</h3>
                    <h5 class="widget-user-desc">Güncel etkinlikler</h5>
                </div>
                <div class="card-footer p-0">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="{{ route('admin.mayor-content.index', ['type' => 'agenda']) }}" class="nav-link">
                                <i class="fas fa-list mr-2"></i>
                                Gündemi Yönet
                                <span class="float-right badge bg-info">{{ $mayor->agenda->count() }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.mayor-content.create', ['type' => 'agenda']) }}" class="nav-link">
                                <i class="fas fa-plus mr-2"></i>
                                Yeni Gündem Ekle
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Galeri -->
        <div class="col-lg-4 col-md-6">
            <div class="card card-widget widget-user-2 shadow-sm">
                <div class="widget-user-header bg-purple">
                    <div class="widget-user-image">
                        <i class="fas fa-images fa-2x"></i>
                    </div>
                    <h3 class="widget-user-username">Galeri</h3>
                    <h5 class="widget-user-desc">Fotoğraf galerisi</h5>
                </div>
                <div class="card-footer p-0">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="{{ route('admin.mayor-content.index', ['type' => 'gallery']) }}" class="nav-link">
                                <i class="fas fa-images mr-2"></i>
                                Galeriyi Yönet
                                <span class="float-right badge bg-purple">{{ $mayor->gallery->count() }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.mayor-content.create', ['type' => 'gallery']) }}" class="nav-link">
                                <i class="fas fa-plus mr-2"></i>
                                Yeni Fotoğraf Ekle
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Başkan Profili Kartı -->
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-user-edit mr-2"></i>
                Başkan Profili & Sayfa Ayarları
            </h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        
        <form action="{{ route('admin.mayor.update', $mayor) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="card-body">
                <div class="row">
                    <!-- Sol Kolon -->
                    <div class="col-lg-6">
                        <div class="card card-light">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-user text-primary mr-2"></i>
                                    Kişisel Bilgiler
                                </h5>
                            </div>
                            <div class="card-body">
                                <!-- Ad Soyad -->
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold">
                                        <i class="fas fa-signature mr-1"></i>
                                        Ad Soyad <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $mayor->name) }}" required>
                                    @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Unvan -->
                                <div class="form-group">
                                    <label for="title" class="font-weight-bold">
                                        <i class="fas fa-medal mr-1"></i>
                                        Unvan <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title', $mayor->title) }}" required>
                                    @error('title')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Profil Fotoğrafı -->
                                <div class="form-group">
                                    <label for="profile_image" class="font-weight-bold">
                                        <i class="fas fa-camera mr-1"></i>
                                        Profil Fotoğrafı
                                    </label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input @error('profile_image') is-invalid @enderror" 
                                                   id="profile_image" name="profile_image" accept="image/*">
                                            <label class="custom-file-label" for="profile_image">Dosya seçin</label>
                                        </div>
                                    </div>
                                    @if($mayor->profile_image)
                                        <div class="mt-3 text-center">
                                            <img src="{{ $mayor->profile_image_url }}" alt="Profil Fotoğrafı" 
                                                 class="img-thumbnail shadow-sm" style="max-width: 200px; border-radius: 10px;">
                                            <p class="text-muted mt-2 mb-0">
                                                <small><i class="fas fa-info-circle mr-1"></i>Mevcut profil fotoğrafı</small>
                                            </p>
                                        </div>
                                    @endif
                                    @error('profile_image')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Biyografi -->
                                <div class="form-group">
                                    <label for="biography" class="font-weight-bold">
                                        <i class="fas fa-file-alt mr-1"></i>
                                        Biyografi
                                    </label>
                                    <textarea class="form-control tinymce-editor @error('biography') is-invalid @enderror" 
                                              id="biography" name="biography" rows="15" 
                                              placeholder="Başkanın biyografisini buraya yazın...">{{ old('biography', $mayor->biography) }}</textarea>
                                    @error('biography')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Zengin metin editörü ile biyografiyi düzenleyebilirsiniz. Resim, link ve formatlamalar ekleyebilirsiniz.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sağ Kolon -->
                    <div class="col-lg-6">
                        <!-- Sosyal Medya -->
                        <div class="card card-light">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-share-alt text-success mr-2"></i>
                                    Sosyal Medya Hesapları
                                </h5>
                            </div>
                            <div class="card-body">
                                <!-- Twitter -->
                                <div class="form-group">
                                                    <label for="social_twitter" class="font-weight-bold">
                    <span class="text-white font-bold" style="font-family: serif; font-size: 14px;">𝕏</span>
                    Twitter
                </label>
                                    <input type="url" class="form-control @error('social_twitter') is-invalid @enderror" 
                                           id="social_twitter" name="social_twitter" 
                                           value="{{ old('social_twitter', $mayor->social_twitter) }}"
                                           placeholder="https://twitter.com/username">
                                    @error('social_twitter')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Instagram -->
                                <div class="form-group">
                                    <label for="social_instagram" class="font-weight-bold">
                                        <i class="fab fa-instagram text-danger mr-1"></i>
                                        Instagram
                                    </label>
                                    <input type="url" class="form-control @error('social_instagram') is-invalid @enderror" 
                                           id="social_instagram" name="social_instagram" 
                                           value="{{ old('social_instagram', $mayor->social_instagram) }}"
                                           placeholder="https://instagram.com/username">
                                    @error('social_instagram')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Facebook -->
                                <div class="form-group">
                                    <label for="social_facebook" class="font-weight-bold">
                                        <i class="fab fa-facebook text-primary mr-1"></i>
                                        Facebook
                                    </label>
                                    <input type="url" class="form-control @error('social_facebook') is-invalid @enderror" 
                                           id="social_facebook" name="social_facebook" 
                                           value="{{ old('social_facebook', $mayor->social_facebook) }}"
                                           placeholder="https://facebook.com/username">
                                    @error('social_facebook')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- LinkedIn -->
                                <div class="form-group">
                                    <label for="social_linkedin" class="font-weight-bold">
                                        <i class="fab fa-linkedin text-info mr-1"></i>
                                        LinkedIn
                                    </label>
                                    <input type="url" class="form-control @error('social_linkedin') is-invalid @enderror" 
                                           id="social_linkedin" name="social_linkedin" 
                                           value="{{ old('social_linkedin', $mayor->social_linkedin) }}"
                                           placeholder="https://linkedin.com/in/username">
                                    @error('social_linkedin')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="form-group">
                                    <label for="social_email" class="font-weight-bold">
                                        <i class="fas fa-envelope text-secondary mr-1"></i>
                                        E-posta
                                    </label>
                                    <input type="email" class="form-control @error('social_email') is-invalid @enderror" 
                                           id="social_email" name="social_email" 
                                           value="{{ old('social_email', $mayor->social_email) }}"
                                           placeholder="baskan@belediye.gov.tr">
                                    @error('social_email')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Sayfa Ayarları -->
                        <div class="card card-light">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-cog text-warning mr-2"></i>
                                    Sayfa Ayarları
                                </h5>
                            </div>
                            <div class="card-body">
                                <!-- Sayfa Başlığı -->
                                <div class="form-group">
                                    <label for="page_title" class="font-weight-bold">
                                        <i class="fas fa-heading mr-1"></i>
                                        Sayfa Başlığı <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('page_title') is-invalid @enderror" 
                                           id="page_title" name="page_title" 
                                           value="{{ old('page_title', $mayor->page_title) }}" required>
                                    @error('page_title')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Meta Açıklama -->
                                <div class="form-group">
                                    <label for="meta_description" class="font-weight-bold">
                                        <i class="fas fa-search mr-1"></i>
                                        Meta Açıklama
                                    </label>
                                    <textarea class="form-control @error('meta_description') is-invalid @enderror" 
                                              id="meta_description" name="meta_description" rows="3"
                                              placeholder="SEO için sayfa açıklaması">{{ old('meta_description', $mayor->meta_description) }}</textarea>
                                    @error('meta_description')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Hero Arka Plan Rengi -->
                                <div class="form-group">
                                    <label for="hero_bg_color" class="font-weight-bold">
                                        <i class="fas fa-palette mr-1"></i>
                                        Hero Arka Plan Rengi
                                    </label>
                                    <input type="color" class="form-control @error('hero_bg_color') is-invalid @enderror" 
                                           id="hero_bg_color" name="hero_bg_color" 
                                           value="{{ old('hero_bg_color', $mayor->hero_bg_color) }}">
                                    @error('hero_bg_color')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Hero Arka Plan Görseli -->
                                <div class="form-group">
                                    <label for="hero_bg_image" class="font-weight-bold">
                                        <i class="fas fa-image mr-1"></i>
                                        Hero Arka Plan Görseli
                                    </label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input @error('hero_bg_image') is-invalid @enderror" 
                                                   id="hero_bg_image" name="hero_bg_image" accept="image/*">
                                            <label class="custom-file-label" for="hero_bg_image">Dosya seçin</label>
                                        </div>
                                    </div>
                                    @if($mayor->hero_bg_image)
                                        <div class="mt-3 text-center">
                                            <img src="{{ $mayor->hero_bg_image_url }}" alt="Hero Arka Plan" 
                                                 class="img-thumbnail shadow-sm" style="max-width: 250px; border-radius: 10px;">
                                            <p class="text-muted mt-2 mb-0">
                                                <small><i class="fas fa-info-circle mr-1"></i>Mevcut hero arka plan görseli</small>
                                            </p>
                                        </div>
                                    @endif
                                    @error('hero_bg_image')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card-footer">
                <div class="row">
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save mr-2"></i>
                            Değişiklikleri Kaydet
                        </button>
                    </div>
                    <div class="col-md-6 text-right">
                        <div class="btn-group">
                            <button type="button" class="btn btn-info toggle-status" data-id="{{ $mayor->id }}">
                                <i class="fas {{ $mayor->is_active ? 'fa-eye-slash' : 'fa-eye' }} mr-1"></i>
                                {{ $mayor->is_active ? 'Pasif Yap' : 'Aktif Yap' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

</div>

@section('css')
<style>
.card-light {
    border: 1px solid #dee2e6;
}
.card-light .card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}
.widget-user-2 .widget-user-header {
    border-top-left-radius: 4px;
    border-top-right-radius: 4px;
    padding: 20px;
}
.widget-user-image {
    float: left;
    margin-right: 15px;
    margin-top: -5px;
}
.shadow-sm {
    box-shadow: 0 .125rem .25rem rgba(0,0,0,.075)!important;
}
.form-control-lg {
    font-size: 1.1rem;
    font-weight: 500;
}
.font-weight-bold {
    font-weight: 600!important;
}

/* TinyMCE Editör Stilleri */
.tox-tinymce {
    border: 1px solid #ced4da !important;
    border-radius: 0.25rem !important;
}
.tox-editor-header {
    border-bottom: 1px solid #ced4da !important;
}
.tinymce-editor {
    visibility: hidden;
}
.form-group .tox-tinymce {
    margin-top: 5px;
}
</style>
@stop

@section('js')
<!-- TinyMCE -->
<script src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>
<script>
$(document).ready(function() {
    // TinyMCE Editör Başlatma
    tinymce.init({
        selector: '.tinymce-editor',
        height: 400,
        menubar: true,
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'help', 'wordcount', 'emoticons'
        ],
        toolbar: 'undo redo | blocks | bold italic underline strikethrough | ' +
                'alignleft aligncenter alignright alignjustify | ' +
                'bullist numlist outdent indent | removeformat | ' +
                'link image media table | code preview fullscreen | help',
        content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; line-height: 1.4; }',
        language: 'tr',
        language_url: '/js/tinymce/langs/tr.js',
        branding: false,
        promotion: false,
        images_upload_url: '{{ route("admin.tinymce.upload") }}',
        images_upload_handler: function (blobInfo, success, failure) {
            var xhr, formData;
            xhr = new XMLHttpRequest();
            xhr.withCredentials = false;
            xhr.open('POST', '{{ route("admin.tinymce.upload") }}');
            xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
            
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

    // Custom file input labels
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });

    // Toggle status
    $('.toggle-status').on('click', function() {
        var button = $(this);
        var mayorId = button.data('id');
        
        $.ajax({
            url: '/admin/mayor/' + mayorId + '/toggle-status',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Bir hata oluştu: ' + response.message);
                }
            },
            error: function() {
                alert('Bir hata oluştu. Lütfen tekrar deneyin.');
            }
        });
    });
});
</script>
@stop

@stop 