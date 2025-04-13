@extends('adminlte::page')

@section('title', 'Yeni Slider Ekle')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Yeni Slider Ekle</h1>
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
        
        <form action="{{ route('admin.homepage.sliders.store') }}" method="POST" id="slider-form">
            @csrf
            
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
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}">
                            @error('title')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <!-- Alt Başlık -->
                        <div class="form-group">
                            <label for="subtitle">Alt Başlık</label>
                            <input type="text" class="form-control @error('subtitle') is-invalid @enderror" id="subtitle" name="subtitle" value="{{ old('subtitle') }}">
                            @error('subtitle')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <!-- Sıralama -->
                        <div class="form-group">
                            <label for="order">Sıralama</label>
                            <input type="number" class="form-control @error('order') is-invalid @enderror" id="order" name="order" value="{{ old('order', 0) }}" min="0">
                            @error('order')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <!-- Durum -->
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', '1') === '1' ? 'checked' : '' }}>
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
                                <input type="text" class="form-control @error('image') is-invalid @enderror" id="image" name="image" value="{{ old('image') }}" required>
                            </div>
                            <small class="form-text text-muted">Önerilen boyut: 1920x800 piksel</small>
                            @error('image')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            
                            <div id="slider_image_preview" class="mt-3">
                                @if(old('image'))
                                    <img src="{{ old('image') }}" alt="Preview" class="img-fluid" style="max-height: 300px">
                                @endif
                            </div>
                        </div>
                        
                        <!-- Buton Metni -->
                        <div class="form-group">
                            <label for="button_text">Buton Metni</label>
                            <input type="text" class="form-control @error('button_text') is-invalid @enderror" id="button_text" name="button_text" value="{{ old('button_text') }}" placeholder="Örn: Detaylı Bilgi">
                            @error('button_text')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <!-- Buton Linki -->
                        <div class="form-group">
                            <label for="button_url">Buton Linki</label>
                            <input type="text" class="form-control @error('button_url') is-invalid @enderror" id="button_url" name="button_url" value="{{ old('button_url') }}" placeholder="Örn: /projects/example">
                            @error('button_url')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Kaydet
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
            // Laravel File Manager butonu
            $('#slider_image_button').filemanager('image');
            
            // Form doğrulama
            $('#slider-form').on('submit', function(e) {
                if (!$('#image').val()) {
                    e.preventDefault();
                    alert('Lütfen bir slider görseli seçiniz.');
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