@extends('adminlte::page')

@section('title', 'Başkan Sayfası Yönetimi')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Başkan Sayfası</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Ana Sayfa</a></li>
                        <li class="breadcrumb-item active">Başkan Sayfası</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Başkan Profili Kartı -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-tie mr-2"></i>
                        Başkan Profili & Sayfa Ayarları
                    </h3>
                </div>
                
                <form action="{{ route('admin.mayor.update', $mayor) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="card-body">
                        <div class="row">
                            <!-- Sol Kolon -->
                            <div class="col-md-6">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-user mr-1"></i>
                                    Kişisel Bilgiler
                                </h5>
                                
                                <!-- Ad Soyad -->
                                <div class="form-group">
                                    <label for="name">Ad Soyad <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $mayor->name) }}" required>
                                    @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Unvan -->
                                <div class="form-group">
                                    <label for="title">Unvan <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title', $mayor->title) }}" required>
                                    @error('title')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Profil Fotoğrafı -->
                                <div class="form-group">
                                    <label for="profile_image">Profil Fotoğrafı</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input @error('profile_image') is-invalid @enderror" 
                                                   id="profile_image" name="profile_image" accept="image/*">
                                            <label class="custom-file-label" for="profile_image">Dosya seçin</label>
                                        </div>
                                    </div>
                                    @if($mayor->profile_image)
                                        <div class="mt-2">
                                            <img src="{{ $mayor->profile_image_url }}" alt="Profil Fotoğrafı" 
                                                 class="img-thumbnail" style="max-width: 150px;">
                                        </div>
                                    @endif
                                    @error('profile_image')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Biyografi -->
                                <div class="form-group">
                                    <label for="biography">Biyografi</label>
                                    <textarea class="form-control @error('biography') is-invalid @enderror" 
                                              id="biography" name="biography" rows="8">{{ old('biography', $mayor->biography) }}</textarea>
                                    @error('biography')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Sağ Kolon -->
                            <div class="col-md-6">
                                <h5 class="text-success mb-3">
                                    <i class="fas fa-share-alt mr-1"></i>
                                    Sosyal Medya Hesapları
                                </h5>
                                
                                <!-- Twitter -->
                                <div class="form-group">
                                    <label for="social_twitter">
                                        <i class="fab fa-twitter text-info mr-1"></i>
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
                                    <label for="social_instagram">
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
                                    <label for="social_facebook">
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
                                    <label for="social_linkedin">
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
                                    <label for="social_email">
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

                                <hr>

                                <h5 class="text-warning mb-3">
                                    <i class="fas fa-cog mr-1"></i>
                                    Sayfa Ayarları
                                </h5>

                                <!-- Sayfa Başlığı -->
                                <div class="form-group">
                                    <label for="page_title">Sayfa Başlığı <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('page_title') is-invalid @enderror" 
                                           id="page_title" name="page_title" 
                                           value="{{ old('page_title', $mayor->page_title) }}" required>
                                    @error('page_title')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Meta Açıklama -->
                                <div class="form-group">
                                    <label for="meta_description">Meta Açıklama</label>
                                    <textarea class="form-control @error('meta_description') is-invalid @enderror" 
                                              id="meta_description" name="meta_description" rows="3"
                                              placeholder="SEO için sayfa açıklaması">{{ old('meta_description', $mayor->meta_description) }}</textarea>
                                    @error('meta_description')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Hero Arka Plan Rengi -->
                                <div class="form-group">
                                    <label for="hero_bg_color">Hero Arka Plan Rengi</label>
                                    <input type="color" class="form-control @error('hero_bg_color') is-invalid @enderror" 
                                           id="hero_bg_color" name="hero_bg_color" 
                                           value="{{ old('hero_bg_color', $mayor->hero_bg_color) }}">
                                    @error('hero_bg_color')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Hero Arka Plan Görseli -->
                                <div class="form-group">
                                    <label for="hero_bg_image">Hero Arka Plan Görseli</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input @error('hero_bg_image') is-invalid @enderror" 
                                                   id="hero_bg_image" name="hero_bg_image" accept="image/*">
                                            <label class="custom-file-label" for="hero_bg_image">Dosya seçin</label>
                                        </div>
                                    </div>
                                    @if($mayor->hero_bg_image)
                                        <div class="mt-2">
                                            <img src="{{ $mayor->hero_bg_image_url }}" alt="Hero Arka Plan" 
                                                 class="img-thumbnail" style="max-width: 200px;">
                                        </div>
                                    @endif
                                    @error('hero_bg_image')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i>
                            Kaydet
                        </button>
                        <a href="{{ url('/baskan') }}" target="_blank" class="btn btn-info">
                            <i class="fas fa-eye mr-1"></i>
                            Önizleme
                        </a>
                    </div>
                </form>
            </div>

            <!-- İçerik Yönetimi Kartları -->
            <div class="row">
                <div class="col-md-3">
                    <div class="card card-widget widget-user-2">
                        <div class="widget-user-header bg-warning">
                            <h3 class="widget-user-username">Hikayeler</h3>
                            <h5 class="widget-user-desc">İnstagram tarzı hikaye kartları</h5>
                        </div>
                        <div class="card-footer p-0">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('admin.mayor-content.index', ['type' => 'story']) }}" class="nav-link">
                                        <i class="fas fa-book mr-2"></i>
                                        Hikayeleri Yönet
                                        <span class="float-right badge bg-warning">{{ $mayor->stories->count() }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card card-widget widget-user-2">
                        <div class="widget-user-header bg-info">
                            <h3 class="widget-user-username">Gündem</h3>
                            <h5 class="widget-user-desc">Etkinlikler ve toplantılar</h5>
                        </div>
                        <div class="card-footer p-0">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('admin.mayor-content.index', ['type' => 'agenda']) }}" class="nav-link">
                                        <i class="fas fa-calendar mr-2"></i>
                                        Gündemi Yönet
                                        <span class="float-right badge bg-info">{{ $mayor->agenda->count() }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card card-widget widget-user-2">
                        <div class="widget-user-header bg-success">
                            <h3 class="widget-user-username">Değerler</h3>
                            <h5 class="widget-user-desc">Kurumsal değerler</h5>
                        </div>
                        <div class="card-footer p-0">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('admin.mayor-content.index', ['type' => 'value']) }}" class="nav-link">
                                        <i class="fas fa-star mr-2"></i>
                                        Değerleri Yönet
                                        <span class="float-right badge bg-success">{{ $mayor->values->count() }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card card-widget widget-user-2">
                        <div class="widget-user-header bg-purple">
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
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // TinyMCE for biography
    if (typeof tinymce !== 'undefined') {
        tinymce.init({
            selector: '#biography',
            height: 300,
            plugins: 'advlist autolink lists link image charmap print preview anchor searchreplace visualblocks code fullscreen insertdatetime media table paste code help wordcount',
            toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
        });
    }

    // File input labels
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });
});
</script>
@endpush
@endsection 