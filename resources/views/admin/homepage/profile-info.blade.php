@extends('adminlte::page')

@section('title', 'Profil Bilgileri Yönetimi')

@section('content_header')
    <h1>Profil Bilgileri Yönetimi</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Profil Bilgileri Düzenleme</h3>
        </div>
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-check"></i> Başarılı!</h5>
                {{ session('success') }}
            </div>
        @endif
        
        <div class="card-body">
            <form action="{{ route('admin.homepage.update-profile-info') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row">
                    <!-- Sol Sütun - Kişisel Bilgiler -->
                    <div class="col-md-6">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title">Kişisel Bilgiler</h3>
                            </div>
                            <div class="card-body">
                                <!-- Profil Fotoğrafı -->
                                <div class="form-group">
                                    <label for="profile_photo">Profil Fotoğrafı</label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="profile_photo" name="profile_photo">
                                            <label class="custom-file-label" for="profile_photo">Dosya Seç</label>
                                        </div>
                                    </div>
                                    @if($profileSettings->profile_photo)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . $profileSettings->profile_photo) }}" alt="Profil Fotoğrafı" class="img-thumbnail" style="max-width: 200px;">
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- İsim -->
                                <div class="form-group">
                                    <label for="name">İsim</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $profileSettings->name) }}">
                                </div>
                                
                                <!-- Ünvan -->
                                <div class="form-group">
                                    <label for="title">Ünvan</label>
                                    <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $profileSettings->title) }}">
                                </div>
                            </div>
                        </div>
                        
                        <!-- İletişim Görseli -->
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title">İletişim Merkezi Görseli</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="contact_image">İletişim Görseli</label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="contact_image" name="contact_image">
                                            <label class="custom-file-label" for="contact_image">Dosya Seç</label>
                                        </div>
                                    </div>
                                    @if($profileSettings->contact_image)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . $profileSettings->contact_image) }}" alt="İletişim Görseli" class="img-thumbnail" style="max-width: 200px;">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sağ Sütun - Sosyal Medya Linkleri -->
                    <div class="col-md-6">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title">Sosyal Medya Bağlantıları</h3>
                            </div>
                            <div class="card-body">
                                <!-- Facebook -->
                                <div class="form-group">
                                    <label for="facebook_url">Facebook URL</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fab fa-facebook-f"></i></span>
                                        </div>
                                        <input type="url" class="form-control" id="facebook_url" name="facebook_url" value="{{ old('facebook_url', $profileSettings->facebook_url) }}" placeholder="https://facebook.com/...">
                                    </div>
                                </div>
                                
                                <!-- Instagram -->
                                <div class="form-group">
                                    <label for="instagram_url">Instagram URL</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fab fa-instagram"></i></span>
                                        </div>
                                        <input type="url" class="form-control" id="instagram_url" name="instagram_url" value="{{ old('instagram_url', $profileSettings->instagram_url) }}" placeholder="https://instagram.com/...">
                                    </div>
                                </div>
                                
                                <!-- Twitter -->
                                <div class="form-group">
                                    <label for="twitter_url">Twitter URL</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fab fa-twitter"></i></span>
                                        </div>
                                        <input type="url" class="form-control" id="twitter_url" name="twitter_url" value="{{ old('twitter_url', $profileSettings->twitter_url) }}" placeholder="https://twitter.com/...">
                                    </div>
                                </div>
                                
                                <!-- YouTube -->
                                <div class="form-group">
                                    <label for="youtube_url">YouTube URL</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fab fa-youtube"></i></span>
                                        </div>
                                        <input type="url" class="form-control" id="youtube_url" name="youtube_url" value="{{ old('youtube_url', $profileSettings->youtube_url) }}" placeholder="https://youtube.com/...">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Ön İzleme -->
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title">Ön İzleme</h3>
                            </div>
                            <div class="card-body">
                                <p>Profil bilgileriniz anasayfada aşağıdaki gibi görünecektir:</p>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> Değişiklikleri görmek için önce kaydetmeniz gerekmektedir.
                                </div>
                                
                                <a href="{{ route('front.home') }}" target="_blank" class="btn btn-secondary">
                                    <i class="fas fa-external-link-alt"></i> Anasayfada Görüntüle
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save"></i> Kaydet
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop

@section('css')
    <style>
        .custom-file-input:lang(tr) ~ .custom-file-label::after {
            content: "Gözat";
        }
    </style>
@stop

@section('js')
    <script>
        $(function() {
            // Dosya seçildiğinde adını göster
            $('.custom-file-input').on('change', function() {
                var fileName = $(this).val().split('\\').pop();
                $(this).siblings('.custom-file-label').addClass('selected').html(fileName);
            });
        });
    </script>
@stop 