@extends('adminlte::page')

@section('title', 'Header Yönetimi')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Header Yönetimi</h1>
        <a href="{{ route('admin.homepage.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Anasayfa Yönetimine Dön
        </a>
    </div>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-3">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Header Bilgileri</h3>
                </div>
                <div class="card-body box-profile">
                    <div class="text-center mb-3">
                        @if($headerSettings->logo_path)
                            <img src="{{ asset($headerSettings->logo_path) }}" alt="Logo" class="img-fluid" style="max-height: 100px;">
                        @else
                            <div class="text-muted">Logo yüklenmemiş</div>
                        @endif
                    </div>
                    
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Menü Öğesi</b> <a class="float-right">{{ $mainMenuCount }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Aktif Menü</b> <a class="float-right">{{ $activeMenuCount }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Yükseklik</b> <a class="float-right">{{ $headerSettings->header_height }}px</a>
                        </li>
                    </ul>

                    <a href="{{ route('admin.menusystem.index') }}" class="btn btn-primary btn-block">
                        <i class="fas fa-bars mr-1"></i> Menü Yönetimi
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="card card-success card-outline">
                <div class="card-header">
                    <h3 class="card-title">Header Ayarları</h3>
                </div>
                <form action="{{ route('admin.homepage.header.settings.update') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="logo_path">Logo</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="logo_path" name="logo_path" 
                                            value="{{ old('logo_path', $headerSettings->logo_path) }}" placeholder="Logo dosya yolu">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-secondary" onclick="openFileManager('logo_path')">
                                                <i class="fas fa-file-image"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @error('logo_path')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="secondary_logo_path">İkincil Logo (Atatürk Simgesi)</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="secondary_logo_path" name="secondary_logo_path" 
                                            value="{{ old('secondary_logo_path', $headerSettings->secondary_logo_path) }}" placeholder="İkincil logo dosya yolu">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-secondary" onclick="openFileManager('secondary_logo_path')">
                                                <i class="fas fa-file-image"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @error('secondary_logo_path')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="slogan_path">Slogan Görseli</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="slogan_path" name="slogan_path" 
                                            value="{{ old('slogan_path', $headerSettings->slogan_path) }}" placeholder="Slogan görseli dosya yolu">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-secondary" onclick="openFileManager('slogan_path')">
                                                <i class="fas fa-file-image"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @error('slogan_path')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="header_bg_color">Arkaplan Rengi</label>
                                    <div class="input-group">
                                        <input type="color" class="form-control" id="header_bg_color_picker" 
                                            value="{{ old('header_bg_color', $headerSettings->header_bg_color) }}" 
                                            onchange="document.getElementById('header_bg_color').value = this.value" style="height: 38px; width: 50px;">
                                        <input type="text" class="form-control" id="header_bg_color" name="header_bg_color" 
                                            value="{{ old('header_bg_color', $headerSettings->header_bg_color) }}" placeholder="#ffffff">
                                    </div>
                                    @error('header_bg_color')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="header_text_color">Metin Rengi</label>
                                    <div class="input-group">
                                        <input type="color" class="form-control" id="header_text_color_picker" 
                                            value="{{ old('header_text_color', $headerSettings->header_text_color) }}" 
                                            onchange="document.getElementById('header_text_color').value = this.value" style="height: 38px; width: 50px;">
                                        <input type="text" class="form-control" id="header_text_color" name="header_text_color" 
                                            value="{{ old('header_text_color', $headerSettings->header_text_color) }}" placeholder="#00352b">
                                    </div>
                                    @error('header_text_color')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="header_height">Header Yüksekliği (px)</label>
                                    <input type="number" class="form-control" id="header_height" name="header_height" 
                                        value="{{ old('header_height', $headerSettings->header_height) }}" min="50" max="200">
                                    @error('header_height')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="show_search_button" name="show_search_button" value="1" 
                                            {{ old('show_search_button', $headerSettings->show_search_button) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="show_search_button">Arama Butonunu Göster</label>
                                    </div>
                                    @error('show_search_button')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="sticky_header" name="sticky_header" value="1" 
                                            {{ old('sticky_header', $headerSettings->sticky_header) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="sticky_header">Yapışkan Header</label>
                                    </div>
                                    @error('sticky_header')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="custom_css">Özel CSS Kodları</label>
                                    <textarea class="form-control" id="custom_css" name="custom_css" rows="5" placeholder="Header için özel CSS kodları">{{ old('custom_css', $headerSettings->custom_css) }}</textarea>
                                    @error('custom_css')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="additional_scripts">Ek JavaScript Kodları</label>
                                    <textarea class="form-control" id="additional_scripts" name="additional_scripts" rows="5" placeholder="Header için ek JavaScript kodları">{{ old('additional_scripts', $headerSettings->additional_scripts) }}</textarea>
                                    @error('additional_scripts')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="custom_header_html">Özel HTML İçeriği</label>
                                    <textarea class="form-control" id="custom_header_html" name="custom_header_html" rows="5" placeholder="Header için özel HTML içeriği">{{ old('custom_header_html', $headerSettings->custom_header_html) }}</textarea>
                                    @error('custom_header_html')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save mr-1"></i> Kaydet
                        </button>
                        <a href="{{ route('admin.homepage.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times mr-1"></i> İptal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .preview-image {
            max-height: 100px;
            max-width: 100%;
            margin-top: 10px;
        }
    </style>
@stop

@section('js')
    <script>
        function openFileManager(inputId) {
            const relatedType = 'header_settings';
            const relatedId = '{{ $headerSettings->id ?? "header_main" }}';
            const type = 'image';
            
            // MediaPicker URL'ini doğru parametrelerle oluştur
            const mediapickerUrl = '/admin/filemanagersystem/mediapicker?type=' + 
                encodeURIComponent(type) + '&related_type=' + 
                encodeURIComponent(relatedType) + '&related_id=' + 
                encodeURIComponent(relatedId) + '&filter=all';
            
            // Popup pencere aç
            const popup = window.open(mediapickerUrl, 'FileManager', 'width=900,height=600');
            
            // Mesaj dinleyici ekle
            function handleMessage(event) {
                if (event.data && event.data.type === 'mediaSelected') {
                    // Seçilen dosyayı input'a yaz
                    document.getElementById(inputId).value = event.data.mediaUrl;
                    
                    // Görsel ön izleme oluştur
                    const previewId = inputId + '_preview';
                    let previewElement = document.getElementById(previewId);
                    
                    if (!previewElement) {
                        previewElement = document.createElement('img');
                        previewElement.id = previewId;
                        previewElement.className = 'preview-image';
                        document.getElementById(inputId).parentNode.parentNode.appendChild(previewElement);
                    }
                    
                    previewElement.src = event.data.mediaUrl;
                    
                    // Mesaj dinleyiciyi kaldır
                    window.removeEventListener('message', handleMessage);
                }
            }
            
            // Mesaj dinleyici ekle
            window.addEventListener('message', handleMessage);
            
            // Popup kapatıldığında dinleyiciyi temizle
            const checkClosed = setInterval(function() {
                if (popup.closed) {
                    clearInterval(checkClosed);
                    window.removeEventListener('message', handleMessage);
                }
            }, 1000);
        }
        
        // Renk seçici değişiklikleri izleme
        document.getElementById('header_bg_color').addEventListener('input', function() {
            document.getElementById('header_bg_color_picker').value = this.value;
        });
        document.getElementById('header_text_color').addEventListener('input', function() {
            document.getElementById('header_text_color_picker').value = this.value;
        });
    </script>
@stop 