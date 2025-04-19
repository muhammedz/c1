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
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $slider->title) }}" required>
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
                            <input type="number" class="form-control @error('order') is-invalid @enderror" id="order" name="order" value="{{ old('order', $slider->order) }}" min="0" required>
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
@stop

@section('js')
<script>
    $(document).ready(function() {
        // FileManagerSystem entegrasyonu
        $('#filemanagersystem_image_button').on('click', function() {
            const input = $('#filemanagersystem_image');
            const preview = $('#filemanagersystem_image_preview');
            const previewImg = preview.find('img');
            
            // Özel FileManagerSystem'i açıyoruz
            const popup = window.open('/admin/filemanagersystem/picker?type=image', 'FileManagerSystem', 'width=900,height=600');
            
            // Global fonksiyon tanımlıyoruz
            window.setFileToElement = function(url, path) {
                if (url) {
                    console.log("Seçilen dosya URL: " + url);
                    console.log("Seçilen dosya yolu: " + path);
                    
                    // Değişken kontrol
                    let relativePath = path || url;
                    
                    // Eğer WebP versiyonu varsa yolu ona göre düzenle
                    // Picker'dan gelebilecek WebP yolu kontrolü
                    if (url.endsWith('.webp') && path && path.endsWith('.webp')) {
                        console.log("WebP dosyası seçildi: " + path);
                    } 
                    // Orijinal resim seçildiyse ve uploads/images/ içindeyse WebP'ye çevir
                    else if ((path.includes('/images/') || url.includes('/images/')) && 
                            (path.endsWith('.jpg') || path.endsWith('.jpeg') || path.endsWith('.png'))) {
                        // WebP yolunu oluştur ve kontrol et
                        let possibleWebpPath = path.replace(/\.(jpg|jpeg|png)$/i, '.webp');
                        let webpUrl = url.replace(/\.(jpg|jpeg|png)$/i, '.webp');
                        
                        // Kontrol için ajax ile WebP dosyasının varlığını kontrol et
                        $.ajax({
                            url: webpUrl,
                            type: 'HEAD',
                            async: false,
                            success: function() {
                                console.log("WebP versiyonu bulundu: " + webpUrl);
                                // WebP mevcut, yolları güncelle
                                relativePath = possibleWebpPath;
                                url = webpUrl;
                            },
                            error: function() {
                                console.log("WebP versiyonu bulunamadı, orijinal kullanılacak: " + url);
                            }
                        });
                    }
                    
                    // Eğer path verilmediyse URL'den oluştur
                    if (!path && url.includes('/uploads/')) {
                        // URL'yi daha doğru parçala
                        const urlObj = new URL(url);
                        const pathParts = urlObj.pathname.split('/');
                        
                        // '/uploads/' sonrası tüm yolu al
                        const uploadsIndex = pathParts.indexOf('uploads');
                        if (uploadsIndex !== -1) {
                            relativePath = pathParts.slice(uploadsIndex).join('/');
                        }
                    }
                    
                    console.log("Kaydedilecek yol: " + relativePath);
                    input.val(relativePath);
                    previewImg.attr('src', url);
                    preview.show();
                    popup.close();
                }
            };
        });

        // Form gönderiminden önce kontrol
        $('#slider-form').on('submit', function(e) {
            // İlgili validasyon kontrolleri burada yapılabilir
        });
    });
</script>
@stop 