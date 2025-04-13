@extends('adminlte::page')

@section('title', 'Mobil Uygulama Yönetimi')

@section('content_header')
    <h1>Mobil Uygulama Yönetimi</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Mobil Uygulama Ayarları</h3>
            
            <div class="card-tools">
                <button type="button" id="toggle-visibility-btn" 
                        class="btn {{ $mobileAppSettings->is_active ?? true ? 'btn-success' : 'btn-danger' }} btn-lg">
                    <i class="fas {{ $mobileAppSettings->is_active ?? true ? 'fa-eye' : 'fa-eye-slash' }} mr-1"></i>
                    {{ $mobileAppSettings->is_active ?? true ? 'Aktif' : 'Pasif' }}
                </button>
            </div>
        </div>
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-check"></i> Başarılı!</h5>
                {{ session('success') }}
            </div>
        @endif

        <!-- Durum Bildirimi Alert -->
        <div id="status-alert" class="alert alert-success alert-dismissible" style="display: none;">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-check"></i> Başarılı!</h5>
            <span id="status-message"></span>
        </div>
        
        <div class="card-body">
            <form action="{{ route('admin.homepage.update-mobile-app') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row">
                    <!-- Sol Sütun - Uygulama Bilgileri -->
                    <div class="col-md-6">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title">Uygulama Bilgileri</h3>
                            </div>
                            <div class="card-body">
                                <!-- Uygulama Logosu -->
                                <div class="form-group">
                                    <label for="app_logo">Uygulama Logosu</label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="app_logo" name="app_logo">
                                            <label class="custom-file-label" for="app_logo">Dosya Seç</label>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">Önerilen boyut: 128x128 piksel, PNG formatı</small>
                                    @if($mobileAppSettings->app_logo)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . $mobileAppSettings->app_logo) }}" alt="Uygulama Logosu" class="img-thumbnail" style="max-width: 128px;">
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Uygulama Adı -->
                                <div class="form-group">
                                    <label for="app_name">Uygulama Adı</label>
                                    <input type="text" class="form-control" id="app_name" name="app_name" value="{{ old('app_name', $mobileAppSettings->app_name) }}" placeholder="Örn: Uygulama Adı">
                                </div>
                                
                                <!-- Uygulama Alt Başlığı -->
                                <div class="form-group">
                                    <label for="app_subtitle">Uygulama Alt Başlığı</label>
                                    <input type="text" class="form-control" id="app_subtitle" name="app_subtitle" value="{{ old('app_subtitle', $mobileAppSettings->app_subtitle) }}" placeholder="Örn: PNG formatında">
                                </div>
                                
                                <!-- Uygulama Açıklaması -->
                                <div class="form-group">
                                    <label for="app_description">Uygulama Açıklaması</label>
                                    <textarea class="form-control" id="app_description" name="app_description" rows="3" placeholder="Uygulama hakkında kısa açıklama">{{ old('app_description', $mobileAppSettings->app_description) }}</textarea>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Mağaza Bağlantıları -->
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title">Mağaza Bağlantıları</h3>
                            </div>
                            <div class="card-body">
                                <!-- App Store Bağlantısı -->
                                <div class="form-group">
                                    <label for="app_store_link">App Store Bağlantısı</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fab fa-apple"></i></span>
                                        </div>
                                        <input type="url" class="form-control" id="app_store_link" name="app_store_link" value="{{ old('app_store_link', $mobileAppSettings->app_store_link) }}" placeholder="https://apps.apple.com/...">
                                    </div>
                                </div>
                                
                                <!-- Google Play Bağlantısı -->
                                <div class="form-group">
                                    <label for="google_play_link">Google Play Bağlantısı</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fab fa-google-play"></i></span>
                                        </div>
                                        <input type="url" class="form-control" id="google_play_link" name="google_play_link" value="{{ old('google_play_link', $mobileAppSettings->google_play_link) }}" placeholder="https://play.google.com/...">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sağ Sütun - Görsel ve Bağlantı Kartları -->
                    <div class="col-md-6">
                        <!-- Telefon Görseli -->
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title">Telefon Görseli</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="phone_image">Telefon Ekran Görseli</label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="phone_image" name="phone_image">
                                            <label class="custom-file-label" for="phone_image">Dosya Seç</label>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">Önerilen boyut: 320x650 piksel, PNG formatı</small>
                                    @if($mobileAppSettings->phone_image)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . $mobileAppSettings->phone_image) }}" alt="Telefon Görseli" class="img-thumbnail" style="max-height: 300px;">
                                        </div>
                                    @else
                                        <div class="mt-2 text-center">
                                            <img src="{{ asset('assets/image/mobile-app.png') }}" alt="Varsayılan Telefon Görseli" class="img-thumbnail" style="max-height: 300px;">
                                            <p class="text-muted">Varsayılan görsel</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Bağlantı Kartları -->
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title">Bağlantı Kartları</h3>
                            </div>
                            <div class="card-body">
                                <!-- 1. Bağlantı Kartı -->
                                <div class="card mb-3">
                                    <div class="card-header bg-light">
                                        <h3 class="card-title">1. Bağlantı Kartı</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="link_card_1_title">Başlık</label>
                                            <input type="text" class="form-control" id="link_card_1_title" name="link_card_1_title" value="{{ old('link_card_1_title', $mobileAppSettings->link_card_1_title) }}" placeholder="Örn: Başlık Yazısı Linkle Gidecek">
                                        </div>
                                        <div class="form-group">
                                            <label for="link_card_1_url">Bağlantı URL</label>
                                            <input type="url" class="form-control" id="link_card_1_url" name="link_card_1_url" value="{{ old('link_card_1_url', $mobileAppSettings->link_card_1_url) }}" placeholder="https://...">
                                        </div>
                                        <div class="form-group">
                                            <label for="link_card_1_icon">İkon Kodu</label>
                                            <input type="text" class="form-control" id="link_card_1_icon" name="link_card_1_icon" value="{{ old('link_card_1_icon', $mobileAppSettings->link_card_1_icon) }}" placeholder="Örn: user">
                                            <small class="form-text text-muted">
                                                <a href="https://fontawesome.com/icons" target="_blank">Font Awesome</a> 
                                                simge isimlerini kullanabilirsiniz (örneğin "user", "envelope", "phone", vb.)
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- 2. Bağlantı Kartı -->
                                <div class="card mb-3">
                                    <div class="card-header bg-light">
                                        <h3 class="card-title">2. Bağlantı Kartı</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="link_card_2_title">Başlık</label>
                                            <input type="text" class="form-control" id="link_card_2_title" name="link_card_2_title" value="{{ old('link_card_2_title', $mobileAppSettings->link_card_2_title) }}" placeholder="Örn: Başlık Yazısı Linkle Gidecek">
                                        </div>
                                        <div class="form-group">
                                            <label for="link_card_2_url">Bağlantı URL</label>
                                            <input type="url" class="form-control" id="link_card_2_url" name="link_card_2_url" value="{{ old('link_card_2_url', $mobileAppSettings->link_card_2_url) }}" placeholder="https://...">
                                        </div>
                                        <div class="form-group">
                                            <label for="link_card_2_icon">İkon Kodu</label>
                                            <input type="text" class="form-control" id="link_card_2_icon" name="link_card_2_icon" value="{{ old('link_card_2_icon', $mobileAppSettings->link_card_2_icon) }}" placeholder="Örn: envelope">
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- 3. Bağlantı Kartı -->
                                <div class="card mb-3">
                                    <div class="card-header bg-light">
                                        <h3 class="card-title">3. Bağlantı Kartı</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="link_card_3_title">Başlık</label>
                                            <input type="text" class="form-control" id="link_card_3_title" name="link_card_3_title" value="{{ old('link_card_3_title', $mobileAppSettings->link_card_3_title) }}" placeholder="Örn: Başlık Yazısı Linkle Gidecek">
                                        </div>
                                        <div class="form-group">
                                            <label for="link_card_3_url">Bağlantı URL</label>
                                            <input type="url" class="form-control" id="link_card_3_url" name="link_card_3_url" value="{{ old('link_card_3_url', $mobileAppSettings->link_card_3_url) }}" placeholder="https://...">
                                        </div>
                                        <div class="form-group">
                                            <label for="link_card_3_icon">İkon Kodu</label>
                                            <input type="text" class="form-control" id="link_card_3_icon" name="link_card_3_icon" value="{{ old('link_card_3_icon', $mobileAppSettings->link_card_3_icon) }}" placeholder="Örn: phone">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save"></i> Kaydet
                        </button>
                        <a href="{{ route('front.home') }}" target="_blank" class="btn btn-secondary ml-2">
                            <i class="fas fa-external-link-alt"></i> Anasayfada Görüntüle
                        </a>
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
            
            // Görünürlük butonu tıklaması
            $('#toggle-visibility-btn').on('click', function() {
                var btn = $(this);
                
                // Buton yükleniyor görünümü
                btn.prop('disabled', true);
                btn.html('<i class="fas fa-spinner fa-spin"></i> İşleniyor...');
                
                // AJAX isteği
                $.ajax({
                    url: '{{ route('admin.homepage.toggle-mobile-app-visibility') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(response) {
                        // Başarılı durumda buton güncelleme
                        if (response.success) {
                            if (response.is_active) {
                                btn.removeClass('btn-danger').addClass('btn-success');
                                btn.html('<i class="fas fa-eye mr-1"></i> Aktif');
                            } else {
                                btn.removeClass('btn-success').addClass('btn-danger');
                                btn.html('<i class="fas fa-eye-slash mr-1"></i> Pasif');
                            }
                            
                            // Bildirim göster
                            $('#status-message').text(response.message);
                            $('#status-alert').fadeIn();
                            
                            // 3 saniye sonra bildirimi kapat
                            setTimeout(function() {
                                $('#status-alert').fadeOut();
                            }, 3000);
                        } else {
                            // Hata durumu
                            alert('Bir hata oluştu: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        // Sunucu hatası
                        alert('Sunucu hatası: ' + error);
                    },
                    complete: function() {
                        // İşlem tamamlandığında butonu etkinleştir
                        btn.prop('disabled', false);
                    }
                });
            });
        });
    </script>
@stop 