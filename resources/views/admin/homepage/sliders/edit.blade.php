@extends('adminlte::page')

@section('title', 'Slider Düzenle')

@section('adminlte_css_pre')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

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

                        <!-- Direkt Link -->
                        <div class="form-group">
                            <label for="direct_link">Direkt Link <small class="text-muted">(Slider'a tıklandığında gidilecek sayfa)</small></label>
                            <input type="text" class="form-control @error('direct_link') is-invalid @enderror" id="direct_link" name="direct_link" value="{{ old('direct_link', $slider->direct_link) }}" placeholder="Örn: /hakkimizda veya https://example.com">
                            @error('direct_link')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">Bu alan doldurulursa, slider'a tıklandığında bu adrese yönlendirilir.</small>
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
                                    <button type="button" class="btn btn-success" id="direct_upload_button">
                                        <i class="fas fa-upload"></i> Direkt Yükle
                                    </button>
                                </div>
                            </div>
                            @error('filemanagersystem_image')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            
                            <!-- Direkt dosya yükleme input'u (gizli) -->
                            <input type="file" id="direct_file_input" accept="image/*" style="display: none;">
                            
                            <!-- Yükleme progress bar'ı -->
                            <div id="upload_progress" class="mt-2" style="display: none;">
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
                                </div>
                                <small class="text-muted">Dosya yükleniyor...</small>
                            </div>
                            
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

        // Direkt dosya yükleme
        $('#direct_upload_button').on('click', function() {
            $('#direct_file_input').click();
        });

        $('#direct_file_input').on('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            // Dosya tipini kontrol et
            if (!file.type.startsWith('image/')) {
                alert('Lütfen sadece görsel dosyası seçin.');
                return;
            }

            // Dosya boyutunu kontrol et (10MB limit)
            if (file.size > 10 * 1024 * 1024) {
                alert('Dosya boyutu 10MB\'dan küçük olmalıdır.');
                return;
            }

            // FormData oluştur
            const formData = new FormData();
            formData.append('files[]', file); // Controller'ın beklediği format
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
            formData.append('related_type', 'homepage_slider');
            formData.append('related_id', {{ $slider->id }});
            formData.append('is_public', '1'); // Public olarak işaretle

            // Progress bar'ı göster
            const progressBar = $('#upload_progress');
            const progressBarFill = progressBar.find('.progress-bar');
            progressBar.show();
            progressBarFill.css('width', '0%');

            // AJAX ile dosyayı yükle
            $.ajax({
                url: '{{ route("admin.filemanagersystem.media.store") }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                xhr: function() {
                    const xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener('progress', function(e) {
                        if (e.lengthComputable) {
                            const percentComplete = (e.loaded / e.total) * 100;
                            progressBarFill.css('width', percentComplete + '%');
                        }
                    });
                    return xhr;
                },
                success: function(response) {
                    console.log('Dosya yükleme başarılı:', response);
                    
                    // Controller'dan dönen response'u kontrol et
                    if (response && response.uploaded_files && response.uploaded_files.length > 0) {
                        const uploadedFile = response.uploaded_files[0];
                        
                        // Başarılı yükleme
                        $('#filemanagersystem_image').val(uploadedFile.url);
                        
                        // Önizlemeyi güncelle
                        const preview = $('#filemanagersystem_image_preview');
                        const previewImg = preview.find('img');
                        previewImg.attr('src', uploadedFile.url);
                        preview.show();
                        
                        // Progress bar'ı gizle
                        progressBar.hide();
                        
                        // Başarı mesajı
                        if (typeof toastr !== 'undefined') {
                            toastr.success('Görsel başarıyla yüklendi!');
                        } else {
                            alert('Görsel başarıyla yüklendi!');
                        }
                        
                    } else {
                        throw new Error(response.message || 'Dosya yükleme başarısız');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Dosya yükleme hatası:', xhr.responseText);
                    
                    let errorMessage = 'Dosya yüklenirken bir hata oluştu.';
                    
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.message) {
                            errorMessage = response.message;
                        } else if (response.errors) {
                            errorMessage = Object.values(response.errors).flat().join(', ');
                        }
                    } catch (e) {
                        // JSON parse hatası, varsayılan mesajı kullan
                    }
                    
                    alert(errorMessage);
                    progressBar.hide();
                },
                complete: function() {
                    // Input'u temizle
                    $('#direct_file_input').val('');
                }
            });
        });

        // Form gönderiminden önce kontrol
        $('#slider-form').on('submit', function(e) {
            // İlgili validasyon kontrolleri burada yapılabilir
        });
    });
</script>
@stop 