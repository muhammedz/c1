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
                                    <label for="filemanagersystem_app_logo">Uygulama Logosu</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" id="filemanagersystem_app_logo" name="filemanagersystem_app_logo" value="{{ old('filemanagersystem_app_logo', $mobileAppSettings->app_logo ?? '') }}" readonly>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-primary mediapicker-btn" data-input="filemanagersystem_app_logo" data-preview="app_logo_preview" data-type="image">
                                                <i class="fas fa-images"></i> Medya Seç
                                            </button>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">Önerilen boyut: 128x128 piksel, PNG formatı</small>
                                    <div id="app_logo_preview" class="mt-2">
                                        @if($mobileAppSettings->app_logo)
                                            @if(strpos($mobileAppSettings->app_logo, '/uploads/') !== false)
                                                <img src="{{ $mobileAppSettings->app_logo }}" alt="Uygulama Logosu" class="img-thumbnail" style="max-width: 128px;">
                                            @else
                                                <img src="{{ asset('storage/' . $mobileAppSettings->app_logo) }}" alt="Uygulama Logosu" class="img-thumbnail" style="max-width: 128px;">
                                            @endif
                                        @endif
                                    </div>
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
                                
                                <!-- Uygulama Başlık Görseli -->
                                <div class="form-group">
                                    <label for="filemanagersystem_app_header_image">Uygulama Başlık Görseli</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" id="filemanagersystem_app_header_image" name="filemanagersystem_app_header_image" value="{{ old('filemanagersystem_app_header_image', $mobileAppSettings->app_header_image ?? '') }}" readonly>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-primary mediapicker-btn" data-input="filemanagersystem_app_header_image" data-preview="app_header_image_preview" data-type="image">
                                                <i class="fas fa-images"></i> Medya Seç
                                            </button>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">Önerilen boyut: 800x400 piksel, PNG formatı</small>
                                    
                                    <!-- Görsel Boyut Ayarları -->
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="app_header_image_width">Genişlik (px)</label>
                                                <input type="number" class="form-control" id="app_header_image_width" name="app_header_image_width" value="{{ old('app_header_image_width', $mobileAppSettings->app_header_image_width ?? 320) }}" min="50" max="800">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="app_header_image_height">Yükseklik (px)</label>
                                                <input type="number" class="form-control" id="app_header_image_height" name="app_header_image_height" value="{{ old('app_header_image_height', $mobileAppSettings->app_header_image_height ?? 200) }}" min="50" max="600">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div id="app_header_image_preview" class="mt-2">
                                        @if($mobileAppSettings->app_header_image)
                                            @if(strpos($mobileAppSettings->app_header_image, '/uploads/') !== false)
                                                <img src="{{ $mobileAppSettings->app_header_image }}" alt="Uygulama Başlık Görseli" class="img-thumbnail" style="max-width: 100%;">
                                            @else
                                                <img src="{{ asset('storage/' . $mobileAppSettings->app_header_image) }}" alt="Uygulama Başlık Görseli" class="img-thumbnail" style="max-width: 100%;">
                                            @endif
                                        @endif
                                    </div>
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
                                    <label for="filemanagersystem_phone_image">Telefon Ekran Görseli</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" id="filemanagersystem_phone_image" name="filemanagersystem_phone_image" value="{{ old('filemanagersystem_phone_image', $mobileAppSettings->phone_image ?? '') }}" readonly>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-primary mediapicker-btn" data-input="filemanagersystem_phone_image" data-preview="phone_image_preview" data-type="image">
                                                <i class="fas fa-images"></i> Medya Seç
                                            </button>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">Önerilen boyut: 320x650 piksel, PNG formatı</small>
                                    <div id="phone_image_preview" class="mt-2">
                                        @if($mobileAppSettings->phone_image)
                                            @if(strpos($mobileAppSettings->phone_image, '/uploads/') !== false)
                                                <img src="{{ $mobileAppSettings->phone_image }}" alt="Telefon Görseli" class="img-thumbnail" style="max-height: 300px;">
                                            @else
                                                <img src="{{ asset('storage/' . $mobileAppSettings->phone_image) }}" alt="Telefon Görseli" class="img-thumbnail" style="max-height: 300px;">
                                            @endif
                                        @else
                                            <div class="mt-2 text-center">
                                                <img src="{{ asset('assets/image/mobile-app.png') }}" alt="Varsayılan Telefon Görseli" class="img-thumbnail" style="max-height: 300px;">
                                                <p class="text-muted">Varsayılan görsel</p>
                                            </div>
                                        @endif
                                    </div>
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
                                            <div class="input-group">
                                                <input type="text" class="form-control icon-picker" id="link_card_1_icon" name="link_card_1_icon" value="{{ old('link_card_1_icon', $mobileAppSettings->link_card_1_icon) }}" placeholder="Örn: user">
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-outline-secondary mediapicker-btn" data-input="filemanagersystem_link_card_1_icon" data-preview="link_card_1_icon_preview" data-type="image">
                                                        <i class="fas fa-upload"></i> İkon Yükle
                                                    </button>
                                                </div>
                                            </div>
                                            <small class="form-text text-muted">
                                                <a href="https://fontawesome.com/icons" target="_blank">Font Awesome</a> 
                                                simge isimlerini kullanabilirsiniz veya özel ikon yükleyebilirsiniz.
                                            </small>
                                            <div id="link_card_1_icon_preview" class="mt-2"></div>
                                            <input type="hidden" id="filemanagersystem_link_card_1_icon" name="filemanagersystem_link_card_1_icon" value="{{ old('filemanagersystem_link_card_1_icon', $mobileAppSettings->link_card_1_custom_icon ?? '') }}">
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
                                            <div class="input-group">
                                                <input type="text" class="form-control icon-picker" id="link_card_2_icon" name="link_card_2_icon" value="{{ old('link_card_2_icon', $mobileAppSettings->link_card_2_icon) }}" placeholder="Örn: envelope">
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-outline-secondary mediapicker-btn" data-input="filemanagersystem_link_card_2_icon" data-preview="link_card_2_icon_preview" data-type="image">
                                                        <i class="fas fa-upload"></i> İkon Yükle
                                                    </button>
                                                </div>
                                            </div>
                                            <small class="form-text text-muted">
                                                <a href="https://fontawesome.com/icons" target="_blank">Font Awesome</a> 
                                                simge isimlerini kullanabilirsiniz veya özel ikon yükleyebilirsiniz.
                                            </small>
                                            <div id="link_card_2_icon_preview" class="mt-2"></div>
                                            <input type="hidden" id="filemanagersystem_link_card_2_icon" name="filemanagersystem_link_card_2_icon" value="{{ old('filemanagersystem_link_card_2_icon', $mobileAppSettings->link_card_2_custom_icon ?? '') }}">
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
                                            <div class="input-group">
                                                <input type="text" class="form-control icon-picker" id="link_card_3_icon" name="link_card_3_icon" value="{{ old('link_card_3_icon', $mobileAppSettings->link_card_3_icon) }}" placeholder="Örn: phone">
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-outline-secondary mediapicker-btn" data-input="filemanagersystem_link_card_3_icon" data-preview="link_card_3_icon_preview" data-type="image">
                                                        <i class="fas fa-upload"></i> İkon Yükle
                                                    </button>
                                                </div>
                                            </div>
                                            <small class="form-text text-muted">
                                                <a href="https://fontawesome.com/icons" target="_blank">Font Awesome</a> 
                                                simge isimlerini kullanabilirsiniz veya özel ikon yükleyebilirsiniz.
                                            </small>
                                            <div id="link_card_3_icon_preview" class="mt-2"></div>
                                            <input type="hidden" id="filemanagersystem_link_card_3_icon" name="filemanagersystem_link_card_3_icon" value="{{ old('filemanagersystem_link_card_3_icon', $mobileAppSettings->link_card_3_custom_icon ?? '') }}">
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

    <!-- MediaPicker Modal -->
    <div class="modal fade" id="mediapickerModal" tabindex="-1" role="dialog" aria-labelledby="mediapickerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mediapickerModalLabel">Medya Seçici</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Kapat">
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

@section('css')
    <style>
        .custom-file-input:lang(tr) ~ .custom-file-label::after {
            content: "Gözat";
        }
    </style>
@stop

@section('js')
    <script src="{{ asset('js/icon-picker.js') }}"></script>
    <script>
        $(function () {
            // Form submit işlemi
            $('form').on('submit', function(e) {
                // Form değerlerini loglayalım
                console.log('Form submit edildi');
                console.log('app_logo:', $('#filemanagersystem_app_logo').val());
                console.log('phone_image:', $('#filemanagersystem_phone_image').val());
                
                // İşleme devam et
                return true;
            });
            
            // Görünürlük durumu değişikliği
            $('#toggle-visibility-btn').on('click', function() {
                $.ajax({
                    url: '{{ route("admin.homepage.toggle-mobile-app-visibility") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            // Durum mesajını göster
                            $('#status-message').text(response.message);
                            $('#status-alert').show();
                            
                            // Buton rengini değiştir
                            if (response.is_active) {
                                $('#toggle-visibility-btn')
                                    .removeClass('btn-danger')
                                    .addClass('btn-success')
                                    .html('<i class="fas fa-eye mr-1"></i> Aktif');
                            } else {
                                $('#toggle-visibility-btn')
                                    .removeClass('btn-success')
                                    .addClass('btn-danger')
                                    .html('<i class="fas fa-eye-slash mr-1"></i> Pasif');
                            }
                            
                            // 3 saniye sonra mesajı gizle
                            setTimeout(function() {
                                $('#status-alert').fadeOut('slow');
                            }, 3000);
                        } else {
                            alert('Hata: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        alert('Bir hata oluştu: ' + xhr.responseText);
                    }
                });
            });

            // MediaPicker Entegrasyonu
            $('.mediapicker-btn').on('click', function() {
                try {
                    const input = $(this).data('input');
                    const preview = $(this).data('preview');
                    const type = $(this).data('type');
                    const relatedType = 'mobile_app_settings';
                    const relatedId = '{{ $mobileAppSettings->id ?? "temp_" . time() }}';
                    
                    // MediaPicker URL
                    const mediapickerUrl = '/admin/filemanagersystem/mediapicker?type=' + 
                        encodeURIComponent(type) + '&related_type=' + 
                        encodeURIComponent(relatedType) + '&related_id=' + 
                        encodeURIComponent(relatedId);
                    
                    // Modal açma ve iframe yükleme
                    $('#mediapickerFrame').attr('src', mediapickerUrl);
                    $('#mediapickerModal').modal('show');
                    
                    // Önceki mesaj dinleyiciyi temizleme
                    window.removeEventListener('message', handleMediaSelection);
                    
                    // Medya seçici mesaj dinleme fonksiyonu
                    function handleMediaSelection(event) {
                        try {
                            if (event.data && event.data.type === 'mediaSelected') {
                                let mediaUrl = '';
                                
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
                                
                                // Input değerini güncelle
                                if (input) {
                                    $('#' + input).val(event.data.mediaId);
                                }
                                
                                // Önizleme göster
                                if (preview) {
                                    // Önizleme için doğru URL'yi oluştur
                                    let previewImageUrl = '';
                                    if (event.data.mediaUrl) {
                                        previewImageUrl = event.data.mediaUrl;
                                        // Eğer URL tam değilse, base URL ekle
                                        if (previewImageUrl && previewImageUrl.startsWith('/')) {
                                            const baseUrl = window.location.protocol + '//' + window.location.host;
                                            previewImageUrl = baseUrl + previewImageUrl;
                                        }
                                    } else if (event.data.mediaId) {
                                        // Media ID ile preview URL oluştur
                                        previewImageUrl = window.location.protocol + '//' + window.location.host + '/admin/filemanagersystem/media/preview/' + event.data.mediaId;
                                    }
                                    
                                    if (previewImageUrl) {
                                        // Önizleme türüne göre farklı boyutlar
                                        let maxHeight = '48px';
                                        if (preview.includes('app_logo')) {
                                            maxHeight = '128px';
                                        } else if (preview.includes('phone_image')) {
                                            maxHeight = '300px';
                                        } else if (preview.includes('app_header_image')) {
                                            maxHeight = '200px';
                                        }
                                        
                                        $('#' + preview).html('<img src="' + previewImageUrl + '" alt="Seçilen Görsel" class="img-thumbnail" style="max-height: ' + maxHeight + ';">');
                                    }
                                }
                                
                                // Modalı kapat
                                $('#mediapickerModal').modal('hide');
                                
                                // Event listener'ı kaldır
                                window.removeEventListener('message', handleMediaSelection);
                            }
                        } catch (error) {
                            console.error('Medya seçimi işlenirken hata oluştu:', error);
                        }
                    }
                    
                    // Event listener ekle
                    window.addEventListener('message', handleMediaSelection);
                    
                } catch (error) {
                    console.error('MediaPicker açılırken hata oluştu:', error);
                }
            });

            // İkon seçiciyi başlat
            if (typeof setupIconPickers === 'function') {
                setupIconPickers();
            } else {
                console.error('icon-picker.js yüklenemedi!');
            }
        });
    </script>
@stop 