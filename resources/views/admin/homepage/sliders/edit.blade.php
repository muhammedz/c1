@extends('adminlte::page')

@section('title', 'Slider Düzenle')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Slider Düzenle: {{ $slider->title }}</h1>
        <a href="{{ route('admin.homepage.sliders') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Slider Listesine Dön
        </a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Slider Bilgileri</h3>
        </div>
        
        <form action="{{ route('admin.homepage.sliders.update', $slider->id) }}" method="POST" id="slider-form">
            @csrf
            @method('PUT')
            
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <h5><i class="icon fas fa-ban"></i> Hata!</h5>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <div class="row">
                    <div class="col-md-6">
                        <!-- Başlık -->
                        <div class="form-group">
                            <label for="title">Başlık</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $slider->title) }}">
                            @error('title')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <!-- Alt Başlık -->
                        <div class="form-group">
                            <label for="subtitle">Alt Başlık</label>
                            <input type="text" class="form-control @error('subtitle') is-invalid @enderror" id="subtitle" name="subtitle" value="{{ old('subtitle', $slider->subtitle) }}">
                            @error('subtitle')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <!-- Sıralama -->
                        <div class="form-group">
                            <label for="order">Sıralama</label>
                            <input type="number" class="form-control @error('order') is-invalid @enderror" id="order" name="order" value="{{ old('order', $slider->order) }}" min="0">
                            @error('order')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <!-- Durum -->
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', $slider->is_active) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Aktif olarak yayınla</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <!-- Buton Metni -->
                        <div class="form-group">
                            <label for="button_text">Buton Metni</label>
                            <input type="text" class="form-control @error('button_text') is-invalid @enderror" id="button_text" name="button_text" value="{{ old('button_text', $slider->button_text) }}" placeholder="Örn: Detaylı Bilgi">
                            @error('button_text')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <!-- Buton Linki -->
                        <div class="form-group">
                            <label for="button_url">Buton Linki</label>
                            <input type="text" class="form-control @error('button_url') is-invalid @enderror" id="button_url" name="button_url" value="{{ old('button_url', $slider->button_url) }}" placeholder="Örn: /projects/example">
                            @error('button_url')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- FileManagerSystem Görsel -->
                        <div class="form-group">
                            <label for="filemanagersystem_image">Slider Görseli</label>
                            <div class="input-group">
                                <input type="text" class="form-control @error('filemanagersystem_image') is-invalid @enderror" id="filemanagersystem_image" name="filemanagersystem_image" value="{{ old('filemanagersystem_image', $slider->filemanagersystem_image) }}" readonly>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-primary" id="filemanagersystem_image_button">
                                        <i class="fas fa-image"></i> Görsel Seç
                                    </button>
                                </div>
                            </div>
                            @error('filemanagersystem_image')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <div id="filemanagersystem_image_preview" class="mt-2" style="{{ $slider->filemanagersystem_image ? '' : 'display: none;' }}">
                                <img src="{{ $slider->filemanagersystem_image_url ?? $slider->filemanagersystem_image }}" alt="Önizleme" class="img-thumbnail" style="max-height: 200px;">
                            </div>
                        </div>

                        <!-- Görsel Alt Metni -->
                        <div class="form-group">
                            <label for="filemanagersystem_image_alt">Görsel Alt Metni</label>
                            <input type="text" class="form-control @error('filemanagersystem_image_alt') is-invalid @enderror" id="filemanagersystem_image_alt" name="filemanagersystem_image_alt" value="{{ old('filemanagersystem_image_alt', $slider->filemanagersystem_image_alt) }}">
                            @error('filemanagersystem_image_alt')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Görsel Başlığı -->
                        <div class="form-group">
                            <label for="filemanagersystem_image_title">Görsel Başlığı</label>
                            <input type="text" class="form-control @error('filemanagersystem_image_title') is-invalid @enderror" id="filemanagersystem_image_title" name="filemanagersystem_image_title" value="{{ old('filemanagersystem_image_title', $slider->filemanagersystem_image_title) }}">
                            @error('filemanagersystem_image_title')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Güncelle
                </button>
                <a href="{{ route('admin.homepage.sliders') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> İptal
                </a>
            </div>
        </form>
    </div>

<!-- MediaPicker Modal -->
<div class="modal fade" id="mediapickerModal" tabindex="-1" role="dialog" aria-labelledby="mediapickerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document" style="max-width: 90%;">
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

@section('js')
<script>
    $(document).ready(function() {
        // FileManagerSystem entegrasyonu
        $('#filemanagersystem_image_button').on('click', function() {
            const input = $('#filemanagersystem_image');
            const preview = $('#filemanagersystem_image_preview');
            const previewImg = preview.find('img');
            
            console.log('MediaPicker açılıyor...');
            
            // Slider ID'sini ve ilişki tipini belirleyelim
            const sliderId = {{ $slider->id }};
            const relatedType = 'homepage_slider';
            
            // MediaPicker URL - ilişkili sliderın ID'sini ve tipini ekleyelim
            const mediapickerUrl = '/admin/filemanagersystem/mediapicker?type=image&filter=all&related_type=' + relatedType + '&related_id=' + sliderId;
            
            console.log('MediaPicker URL:', mediapickerUrl);
            
            // iFrame'i güncelle ve modalı göster
            $('#mediapickerFrame').attr('src', mediapickerUrl);
            $('#mediapickerModal').modal('show');
            
            // iframe'den mesaj dinleme ve hata yakalama
            function handleMediaSelection(event) {
                try {
                    if (event.data && event.data.type === 'mediaSelected') {
                        console.log('Seçilen medya:', event.data);
                        
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
                            previewImg.attr('src', mediaUrl);
                            preview.show();
                            
                            console.log('Medya URL kaydedildi:', mediaUrl);
                        } else {
                            console.error('Medya URL bulunamadı');
                            
                            // URL bulunamadıysa "uploads/" yolu ile dosya ID'sini kullan
                            input.val('/uploads/media/' + event.data.mediaId);
                            
                            // Önizleme için ID ile resmi göster
                            const previewUrl = '/admin/filemanagersystem/media/preview/' + event.data.mediaId;
                            previewImg.attr('src', previewUrl);
                            preview.show();
                        }
                        
                        // Modalı kapat
                        $('#mediapickerModal').modal('hide');
                    } else if (event.data && event.data.type === 'mediapickerError') {
                        // Medya seçicide bir hata oluştu
                        console.error('Medya seçici hatası:', event.data.message);
                        alert('Medya seçicide bir hata oluştu: ' + event.data.message);
                        $('#mediapickerModal').modal('hide');
                    }
                } catch (error) {
                    console.error('Medya seçimi işlenirken hata oluştu:', error);
                    alert('Medya seçimi işlenirken bir hata oluştu.');
                }
            }
            
            // Mevcut event listener'ı kaldır ve yenisini ekle
            window.removeEventListener('message', handleMediaSelection);
            window.addEventListener('message', handleMediaSelection);
            
            // iframe yüklenmesini kontrol et
            $('#mediapickerFrame').on('load', function() {
                console.log('MediaPicker iframe yüklendi');
            }).on('error', function() {
                console.error('MediaPicker iframe yüklenirken hata oluştu');
                alert('Medya seçici yüklenirken bir hata oluştu. Lütfen sayfayı yenileyip tekrar deneyin.');
                $('#mediapickerModal').modal('hide');
            });
        });

        // Form gönderiminden önce kontrol
        $('#slider-form').on('submit', function(e) {
            // İlgili validasyon kontrolleri burada yapılabilir
        });
    });
</script>
@stop 