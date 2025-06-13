@extends('adminlte::page')

@section('title', 'Logo ve Planlar Yönetimi')

@section('content_header')
    <h1>Logo ve Planlar Yönetimi</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Logo ve Planlar Ayarları</h3>
            
            <div class="card-tools">
                <button type="button" id="toggle-visibility-btn" 
                        class="btn {{ $logoPlans->is_active ?? true ? 'btn-success' : 'btn-danger' }} btn-lg">
                    <i class="fas {{ $logoPlans->is_active ?? true ? 'fa-eye' : 'fa-eye-slash' }} mr-1"></i>
                    {{ $logoPlans->is_active ?? true ? 'Aktif' : 'Pasif' }}
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
            <form action="{{ route('admin.homepage.update-logo-and-plans') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row">
                    <!-- Sol Sütun - Kart 1 ve Kart 2 -->
                    <div class="col-md-6">
                        <!-- Kart 1 - Yazı Sayfaya Gidecek -->
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title">1. Kart - Sol Üst</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="card1_title">Başlık</label>
                                    <input type="text" class="form-control" id="card1_title" name="card1_title" 
                                           value="{{ old('card1_title', $logoPlans->card1_title) }}" placeholder="Örn: Yazı Sayfaya Gidecek">
                                </div>
                                
                                <div class="form-group">
                                    <label for="card1_icon">İkon</label>
                                    <input type="text" class="form-control icon-picker" id="card1_icon" name="card1_icon" 
                                           value="{{ old('card1_icon', $logoPlans->card1_icon) }}">
                                    <small class="text-muted">İkon seçmek için "İkon Seç" butonuna tıklayın</small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="card1_url">Bağlantı URL</label>
                                    <input type="text" class="form-control" id="card1_url" name="card1_url" 
                                           value="{{ old('card1_url', $logoPlans->card1_url) }}" placeholder="Örn: /belge-sayfa">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Kart 2 - Stratejik Plan -->
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title">2. Kart - Sol Alt</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="card2_title">Başlık</label>
                                    <input type="text" class="form-control" id="card2_title" name="card2_title" 
                                           value="{{ old('card2_title', $logoPlans->card2_title) }}" placeholder="Örn: Stratejik Plan">
                                </div>
                                
                                <div class="form-group">
                                    <label for="card2_image">Görsel</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="card2_image" name="card2_image" 
                                               value="{{ old('card2_image', $logoPlans->card2_image) }}" readonly>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-primary" id="card2_image_button">
                                                <i class="fas fa-images"></i> Medya Seç
                                            </button>
                                        </div>
                                    </div>
                                    <small class="text-muted">Önerilen boyut: 128x128 piksel, PNG formatı</small>
                                    
                                    <div class="mt-2" id="card2_image_preview" style="{{ $logoPlans->card2_image ? '' : 'display: none;' }}">
                                        @if($logoPlans->card2_image)
                                            <img src="{{ asset($logoPlans->card2_image) }}" alt="Kart 2 Görseli" class="img-thumbnail" style="max-width: 128px;">
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="card2_url">Bağlantı URL</label>
                                    <input type="text" class="form-control" id="card2_url" name="card2_url" 
                                           value="{{ old('card2_url', $logoPlans->card2_url) }}" placeholder="Örn: /stratejik-plan.pdf">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sağ Sütun - Büyük Logo Kartı -->
                    <div class="col-md-6">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title">3. Kart - Büyük Logo</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="logo_title">Başlık/Açıklama</label>
                                    <input type="text" class="form-control" id="logo_title" name="logo_title" 
                                           value="{{ old('logo_title', $logoPlans->logo_title) }}" placeholder="Örn: Farklı Logo gelecek">
                                    <small class="text-muted">Bu alana yazılan metin logo yoksa görüntülenecektir</small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="logo_image">Logo Görseli</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="logo_image" name="logo_image" 
                                               value="{{ old('logo_image', $logoPlans->logo_image) }}" readonly>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-primary" id="logo_image_button">
                                                <i class="fas fa-images"></i> Medya Seç
                                            </button>
                                        </div>
                                    </div>
                                    <small class="text-muted">Önerilen boyut: 300x100 piksel, PNG formatı</small>
                                    
                                    <div class="mt-2" id="logo_image_preview" style="{{ $logoPlans->logo_image ? '' : 'display: none;' }}">
                                        @if($logoPlans->logo_image)
                                            <img src="{{ asset($logoPlans->logo_image) }}" alt="Logo Görseli" class="img-thumbnail" style="max-width: 300px;">
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="logo_bg_color">Arkaplan Rengi</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-palette"></i></span>
                                        </div>
                                        <input type="color" class="form-control" id="logo_bg_color" name="logo_bg_color" 
                                               value="{{ old('logo_bg_color', $logoPlans->logo_bg_color ?? '#004d2e') }}">
                                    </div>
                                </div>
                                
                                <!-- Önizleme -->
                                <div class="card mt-4">
                                    <div class="card-header bg-light">
                                        <h3 class="card-title">Ön İzleme</h3>
                                    </div>
                                    <div class="card-body text-center p-4" id="logo_preview" style="background-color: {{ $logoPlans->logo_bg_color ?? '#004d2e' }}; color: white; min-height: 150px; display: flex; align-items: center; justify-content: center; border-radius: 0.5rem;">
                                        @if($logoPlans->logo_image)
                                            <img src="{{ asset($logoPlans->logo_image) }}" alt="Logo Görseli" style="max-width: 100%; max-height: 150px;">
                                        @else
                                            <p class="font-weight-bold" style="font-size: 1.5rem;">{{ $logoPlans->logo_title ?? 'Farklı Logo gelecek' }}<br>Png formatında</p>
                                        @endif
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
                    <iframe id="mediapickerFrame" style="width: 100%; height: 80vh; border: none;"></iframe>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/icon-picker.css') }}">
    <style>
        .custom-file-input:lang(tr) ~ .custom-file-label::after {
            content: "Gözat";
        }
    </style>
@stop

@section('js')
    <script src="{{ asset('js/icon-picker.js') }}"></script>
    <script>
        // Asset URL'yi JavaScript'te kullanmak için
        const asset_url = '{{ url('/') }}/';
        
        $(function() {
            // Renk değişikliğinde önizlemeyi güncelle
            $('#logo_bg_color').on('input', function() {
                var color = $(this).val();
                $('#logo_preview').css('background-color', color);
            });
            
            // Görünürlük butonu tıklaması
            $('#toggle-visibility-btn').on('click', function() {
                var btn = $(this);
                
                // Buton yükleniyor görünümü
                btn.prop('disabled', true);
                btn.html('<i class="fas fa-spinner fa-spin"></i> İşleniyor...');
                
                // AJAX isteği
                $.ajax({
                    url: '{{ route('admin.homepage.toggle-logo-and-plans-visibility') }}',
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
            
            // Global mesaj işleyicisi
            function handleFilemanagerMessage(event) {
                console.log('Mesaj alındı:', event.data);
                
                let data;
                // Gelen veri string mi yoksa obje mi kontrol et
                if (typeof event.data === 'string') {
                    try {
                        data = JSON.parse(event.data);
                    } catch (e) {
                        console.log('String mesaj JSON olarak işlenemedi:', e);
                        return;
                    }
                } else {
                    // Zaten obje ise doğrudan kullan
                    data = event.data;
                }
                
                // Seçim verisi kontrol et
                if (data && data.type === 'mediaSelected') {
                    console.log('Medya seçildi:', data);
                    
                    // Aktif input ID ve preview ID'lerini al
                    const inputId = activeInputId;
                    const previewId = activePreviewId;
                    
                    if (!inputId || !previewId) {
                        console.error('Aktif input veya preview ID bulunamadı');
                        return;
                    }
                    
                    // URL değerini al ve düzelt
                    let mediaUrl = data.mediaUrl;
                    
                    // URL'yi temizle - external URL'leri ve gereksiz yolları kaldır
                    if (mediaUrl) {
                        console.log('Orijinal URL:', mediaUrl);
                        
                        // Eğer tam URL ise (http:// veya https:// içeriyorsa)
                        if (mediaUrl.includes('http://') || mediaUrl.includes('https://')) {
                            // Domain kısmını çıkar, sadece gerçek dosya yolunu al
                            // Örnek: https://example.com/storage/images/file.jpg -> uploads/images/file.jpg
                            
                            // URL'den domain kısmını çıkar
                            const urlObj = new URL(mediaUrl);
                            const pathName = urlObj.pathname;
                            
                            // /storage/ kısmını da çıkar (eğer varsa)
                            if (pathName.includes('/storage/')) {
                                const cleanPath = pathName.split('/storage/')[1];
                                mediaUrl = 'uploads/' + cleanPath;
                            } else {
                                // Eğer /storage/ yoksa, / ile başlayan path'i temizle ve uploads/ ekle
                                const cleanPath = pathName.startsWith('/') ? pathName.substring(1) : pathName;
                                mediaUrl = cleanPath.startsWith('uploads/') ? cleanPath : 'uploads/' + cleanPath;
                            }
                        } else if (mediaUrl.startsWith('/storage/')) {
                            // Sadece /storage/ ile başlıyorsa, bu kısmı çıkar ve uploads/ ekle
                            const cleanPath = mediaUrl.substring('/storage/'.length);
                            mediaUrl = 'uploads/' + cleanPath;
                        } else if (mediaUrl.startsWith('storage/')) {
                            // storage/ ile başlıyorsa, bu kısmı çıkar ve uploads/ ekle
                            const cleanPath = mediaUrl.substring('storage/'.length);
                            mediaUrl = 'uploads/' + cleanPath;
                        } else if (!mediaUrl.startsWith('uploads/')) {
                            // uploads/ ile başlamıyorsa ekle
                            mediaUrl = 'uploads/' + mediaUrl;
                        }
                        
                        console.log('Temizlenmiş URL:', mediaUrl);
                    }
                    
                    // Input alanını güncelle
                    $('#' + inputId).val(mediaUrl);
                    
                    // Önizleme alanını güncelle
                    const previewElement = $('#' + previewId);
                    let previewImg = previewElement.find('img');
                    
                    if (previewImg.length === 0) {
                        previewImg = $('<img>').addClass('img-thumbnail');
                        previewElement.html(previewImg);
                    }
                    
                    // Önizleme için tam URL kullan
                    const previewUrl = asset_url + mediaUrl;
                    previewImg.attr('src', previewUrl);
                    previewImg.attr('alt', data.alt || 'Seçilen Görsel');
                    previewElement.show();
                    
                    // Eğer logo görseli ise ana önizlemeyi de güncelle
                    if (inputId === 'logo_image') {
                        $('#logo_preview').html('<img src="' + previewUrl + '" alt="Logo Görseli" style="max-width: 100%; max-height: 150px;">');
                    }
                    
                    // Modal'ı kapat
                    $('#mediapickerModal').modal('hide');
                }
            }
            
            // Global değişkenler
            var activeInputId = '';
            var activePreviewId = '';
            
            // Kart 2 görseli seçimi
            $('#card2_image_button').on('click', function() {
                // Aktif input ve preview bilgilerini güncelle
                activeInputId = 'card2_image';
                activePreviewId = 'card2_image_preview';
                
                // Geçici ID oluştur
                const tempId = Date.now();
                const relatedType = 'logo_plans';
                
                // MediaPicker URL
                const mediapickerUrl = '/admin/filemanagersystem/mediapicker?type=image&filter=all&related_type=' + relatedType + '&related_id=' + tempId;
                
                // iFrame'i güncelle
                $('#mediapickerFrame').attr('src', mediapickerUrl);
                
                // Global mesaj dinleyicisi ekle
                window.removeEventListener('message', handleFilemanagerMessage);
                window.addEventListener('message', handleFilemanagerMessage);
                
                // Modal'ı göster
                $('#mediapickerModal').modal('show');
            });
            
            // Logo görseli seçimi
            $('#logo_image_button').on('click', function() {
                // Aktif input ve preview bilgilerini güncelle
                activeInputId = 'logo_image';
                activePreviewId = 'logo_image_preview';
                
                // Geçici ID oluştur
                const tempId = Date.now();
                const relatedType = 'logo_plans';
                
                // MediaPicker URL
                const mediapickerUrl = '/admin/filemanagersystem/mediapicker?type=image&filter=all&related_type=' + relatedType + '&related_id=' + tempId;
                
                // iFrame'i güncelle
                $('#mediapickerFrame').attr('src', mediapickerUrl);
                
                // Global mesaj dinleyicisi ekle
                window.removeEventListener('message', handleFilemanagerMessage);
                window.addEventListener('message', handleFilemanagerMessage);
                
                // Modal'ı göster
                $('#mediapickerModal').modal('show');
            });
            
            // Modal kapandığında event listener'ı kaldır
            $('#mediapickerModal').on('hidden.bs.modal', function() {
                window.removeEventListener('message', handleFilemanagerMessage);
            });
        });
    </script>
@stop 