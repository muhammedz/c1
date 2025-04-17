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
                        <!-- Slider Görseli -->
                        <div class="form-group">
                            <label for="image">Slider Görseli <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-prepend">
                                    <a id="slider_image_button" data-input="image" data-preview="slider_image_preview" class="btn btn-primary">
                                        <i class="fas fa-image"></i> Görsel Seç
                                    </a>
                                </span>
                                <input type="text" class="form-control @error('image') is-invalid @enderror" id="image" name="image" value="{{ old('image', $slider->image) }}" required>
                            </div>
                            <small class="form-text text-muted">Önerilen boyut: 1920x800 piksel</small>
                            @error('image')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            
                            <div id="slider_image_preview" class="mt-3">
                                @if(old('image', $slider->image))
                                    <img src="{{ old('image', $slider->image_url) }}" alt="Preview" class="img-fluid" style="max-height: 300px">
                                @endif
                            </div>
                        </div>
                        
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
    <script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
    <script>
        $(document).ready(function() {
            // Temel URL'yi al
            var baseUrl = window.location.protocol + '//' + window.location.host;
            
            // Görsel seçildikten sonra çalışacak callback
            var onFileSelected = function(url, path) {
                console.log('Original URL: ', url);
                
                // Görece yolları tam URL'ye çevir
                if (url && url.indexOf('http') !== 0) {
                    // URL'yi doğrudan değiştir (storage yerine uploads)
                    if (url.indexOf('/storage/') !== -1) {
                        url = url.replace('/storage/', '/uploads/');
                    }
                    
                    // Eğer URL'de /images/ kısmı varsa /photos/ olarak değiştir
                    if (url.indexOf('/images/') !== -1) {
                        url = url.replace('/images/', '/photos/');
                    }
                }
                
                console.log('Converted URL: ', url);
                
                // Input değerini güncelle
                $('#image').val(url);
                
                // Önizleme göster
                var preview = $('#slider_image_preview');
                preview.html('<img src="' + url + '" alt="Önizleme" class="img-fluid" style="max-height: 300px">');
            };
            
            // File Manager butonunu özelleştir
            $('#slider_image_button').filemanager('image', {onFileSelected: onFileSelected});
            
            // Form gönderiminden önce URL formatını kontrol et
            $('#slider-form').on('submit', function(e) {
                if (!$('#image').val()) {
                    e.preventDefault();
                    alert('Lütfen bir slider görseli seçiniz.');
                    return;
                }
            });
        });
    </script>
@stop

@section('css')
    <style>
        #slider_image_preview img {
            max-width: 100%;
            max-height: 300px;
            object-fit: contain;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 5px;
        }
    </style>
@stop 