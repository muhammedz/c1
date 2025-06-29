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
                                <!-- Profil Fotoğrafı - Medya Kütüphanesi -->
                                <div class="form-group">
                                    <label for="filemanagersystem_profile_photo">Profil Fotoğrafı</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control @error('filemanagersystem_profile_photo') is-invalid @enderror" id="filemanagersystem_profile_photo" name="filemanagersystem_profile_photo" value="{{ old('filemanagersystem_profile_photo', $profileSettings->filemanagersystem_profile_photo) }}" readonly>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-primary" id="filemanagersystem_profile_photo_button">
                                                <i class="fas fa-image"></i> Görsel Seç
                                            </button>
                                        </div>
                                    </div>
                                    <div id="filemanagersystem_profile_photo_preview" class="mt-2" style="{{ $profileSettings->filemanagersystem_profile_photo ? '' : 'display: none;' }}">
                                        <img src="{{ $profileSettings->filemanagersystem_profile_photo }}" alt="Profil Fotoğrafı" class="img-thumbnail" style="max-width: 200px;">
                                    </div>
                                    @error('filemanagersystem_profile_photo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="filemanagersystem_profile_photo_alt">Görsel Alt Metni</label>
                                            <input type="text" class="form-control @error('filemanagersystem_profile_photo_alt') is-invalid @enderror" id="filemanagersystem_profile_photo_alt" name="filemanagersystem_profile_photo_alt" value="{{ old('filemanagersystem_profile_photo_alt', $profileSettings->filemanagersystem_profile_photo_alt) }}">
                                            <small class="text-muted">Görsel yüklenemediğinde gösterilecek metin.</small>
                                            @error('filemanagersystem_profile_photo_alt')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="filemanagersystem_profile_photo_title">Görsel Başlığı</label>
                                            <input type="text" class="form-control @error('filemanagersystem_profile_photo_title') is-invalid @enderror" id="filemanagersystem_profile_photo_title" name="filemanagersystem_profile_photo_title" value="{{ old('filemanagersystem_profile_photo_title', $profileSettings->filemanagersystem_profile_photo_title) }}">
                                            <small class="text-muted">Görsel üzerine gelindiğinde gösterilecek metin.</small>
                                            @error('filemanagersystem_profile_photo_title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
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
                                <!-- İletişim Görseli - Medya Kütüphanesi -->
                                <div class="form-group">
                                    <label for="filemanagersystem_contact_image">İletişim Görseli</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control @error('filemanagersystem_contact_image') is-invalid @enderror" id="filemanagersystem_contact_image" name="filemanagersystem_contact_image" value="{{ old('filemanagersystem_contact_image', $profileSettings->filemanagersystem_contact_image) }}" readonly>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-primary" id="filemanagersystem_contact_image_button">
                                                <i class="fas fa-image"></i> Görsel Seç
                                            </button>
                                        </div>
                                    </div>
                                    <div id="filemanagersystem_contact_image_preview" class="mt-2" style="{{ $profileSettings->filemanagersystem_contact_image ? '' : 'display: none;' }}">
                                        <img src="{{ $profileSettings->filemanagersystem_contact_image }}" alt="İletişim Görseli" class="img-thumbnail" style="max-width: 200px;">
                                    </div>
                                    @error('filemanagersystem_contact_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="filemanagersystem_contact_image_alt">Görsel Alt Metni</label>
                                            <input type="text" class="form-control @error('filemanagersystem_contact_image_alt') is-invalid @enderror" id="filemanagersystem_contact_image_alt" name="filemanagersystem_contact_image_alt" value="{{ old('filemanagersystem_contact_image_alt', $profileSettings->filemanagersystem_contact_image_alt) }}">
                                            <small class="text-muted">Görsel yüklenemediğinde gösterilecek metin.</small>
                                            @error('filemanagersystem_contact_image_alt')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="filemanagersystem_contact_image_title">Görsel Başlığı</label>
                                            <input type="text" class="form-control @error('filemanagersystem_contact_image_title') is-invalid @enderror" id="filemanagersystem_contact_image_title" name="filemanagersystem_contact_image_title" value="{{ old('filemanagersystem_contact_image_title', $profileSettings->filemanagersystem_contact_image_title) }}">
                                            <small class="text-muted">Görsel üzerine gelindiğinde gösterilecek metin.</small>
                                            @error('filemanagersystem_contact_image_title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
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
                                    <label for="twitter_url">Twitter/X URL</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><span class="text-white font-bold" style="font-family: serif; font-size: 14px;">𝕏</span></span>
                                        </div>
                                        <input type="url" class="form-control" id="twitter_url" name="twitter_url" value="{{ old('twitter_url', $profileSettings->twitter_url) }}" placeholder="https://x.com/...">
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
    <style>
        .custom-file-input:lang(tr) ~ .custom-file-label::after {
            content: "Gözat";
        }
    </style>
@stop

@section('js')
    <script>
        $(function() {
            // FileManagerSystem entegrasyonu - Profil Fotoğrafı
            $('#filemanagersystem_profile_photo_button').on('click', function() {
                const input = $('#filemanagersystem_profile_photo');
                const preview = $('#filemanagersystem_profile_photo_preview');
                const previewImg = preview.find('img');
                
                // Geçici bir ID oluştur
                const tempId = Date.now();
                const relatedType = 'profile_settings';
                
                // MediaPicker URL
                const mediapickerUrl = '/admin/filemanagersystem/mediapicker?type=image&filter=all&related_type=' + relatedType + '&related_id=' + tempId;
                
                // iFrame'i güncelle
                $('#mediapickerFrame').attr('src', mediapickerUrl);
                
                // Modal'ı göster
                $('#mediapickerModal').modal('show');
                
                // Global mesaj dinleyicisi
                window.removeEventListener('message', handleFilemanagerMessage);
                window.addEventListener('message', handleFilemanagerMessage);
                
                // Mesaj işleme fonksiyonu
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
                    
                    // data objesinde type property'si var mı kontrol et - mediaSelected mesajını dinle
                    if (data && data.type === 'mediaSelected') {
                        console.log('Medya seçildi:', data);
                        
                        // Input alanını güncelle
                        input.val(data.mediaUrl);
                        
                        // Önizlemeyi güncelle
                        previewImg.attr('src', data.mediaUrl);
                        preview.show();
                        
                        // Modal'ı kapat
                        $('#mediapickerModal').modal('hide');
                    }
                }
            });
            
            // FileManagerSystem entegrasyonu - İletişim Görseli
            $('#filemanagersystem_contact_image_button').on('click', function() {
                const input = $('#filemanagersystem_contact_image');
                const preview = $('#filemanagersystem_contact_image_preview');
                const previewImg = preview.find('img');
                
                // Geçici bir ID oluştur
                const tempId = Date.now();
                const relatedType = 'profile_settings_contact';
                
                // MediaPicker URL
                const mediapickerUrl = '/admin/filemanagersystem/mediapicker?type=image&filter=all&related_type=' + relatedType + '&related_id=' + tempId;
                
                // iFrame'i güncelle
                $('#mediapickerFrame').attr('src', mediapickerUrl);
                
                // Modal'ı göster
                $('#mediapickerModal').modal('show');
                
                // Global mesaj dinleyicisi
                window.removeEventListener('message', handleFilemanagerMessage);
                window.addEventListener('message', handleFilemanagerMessage);
                
                // Mesaj işleme fonksiyonu
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
                    
                    // data objesinde type property'si var mı kontrol et - mediaSelected mesajını dinle
                    if (data && data.type === 'mediaSelected') {
                        console.log('Medya seçildi:', data);
                        
                        // Input alanını güncelle
                        input.val(data.mediaUrl);
                        
                        // Önizlemeyi güncelle
                        previewImg.attr('src', data.mediaUrl);
                        preview.show();
                        
                        // Modal'ı kapat
                        $('#mediapickerModal').modal('hide');
                    }
                }
            });
        });
    </script>
@stop 