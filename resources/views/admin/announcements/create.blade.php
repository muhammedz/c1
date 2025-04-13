@extends('adminlte::page')

@section('title', 'Yeni Duyuru Ekle')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Yeni Duyuru Ekle</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Kontrol Paneli</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.announcements.index') }}">Duyurular</a></li>
                    <li class="breadcrumb-item active">Yeni Duyuru</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Duyuru Bilgileri</h3>
                </div>
                <form action="{{ route('admin.announcements.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="title">Duyuru Başlığı <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="content">Duyuru İçeriği <span class="text-danger">*</span></label>
                            <textarea name="content" id="content" class="form-control" rows="4" required>{{ old('content') }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="button_text">Buton Metni (İsteğe Bağlı)</label>
                                    <input type="text" name="button_text" id="button_text" class="form-control" value="{{ old('button_text') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="button_url">Buton URL (İsteğe Bağlı)</label>
                                    <input type="text" name="button_url" id="button_url" class="form-control" value="{{ old('button_url') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="bg_color">Arkaplan Rengi <span class="text-danger">*</span></label>
                                    <input type="color" name="bg_color" id="bg_color" class="form-control" value="{{ old('bg_color', '#fff3cd') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="text_color">Yazı Rengi <span class="text-danger">*</span></label>
                                    <input type="color" name="text_color" id="text_color" class="form-control" value="{{ old('text_color', '#856404') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="border_color">Kenarlık Rengi <span class="text-danger">*</span></label>
                                    <input type="color" name="border_color" id="border_color" class="form-control" value="{{ old('border_color', '#ffeeba') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="icon">İkon <span class="text-danger">*</span></label>
                                    <select name="icon" id="icon" class="form-control">
                                        <option value="info" {{ old('icon') == 'info' ? 'selected' : '' }}>Bilgi (info)</option>
                                        <option value="warning" {{ old('icon') == 'warning' ? 'selected' : '' }}>Uyarı (warning)</option>
                                        <option value="announcement" {{ old('icon') == 'announcement' ? 'selected' : '' }}>Duyuru (announcement)</option>
                                        <option value="campaign" {{ old('icon') == 'campaign' ? 'selected' : '' }}>Kampanya (campaign)</option>
                                        <option value="new_releases" {{ old('icon') == 'new_releases' ? 'selected' : '' }}>Yeni (new_releases)</option>
                                        <option value="notifications" {{ old('icon') == 'notifications' ? 'selected' : '' }}>Bildirim (notifications)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="position">Pozisyon <span class="text-danger">*</span></label>
                                    <select name="position" id="position" class="form-control">
                                        @foreach($positions as $key => $value)
                                            <option value="{{ $key }}" {{ old('position') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="max_views_per_user">Kullanıcı Başına Maksimum Görüntüleme</label>
                            <input type="number" name="max_views_per_user" id="max_views_per_user" class="form-control" min="0" value="{{ old('max_views_per_user', 0) }}">
                            <small class="form-text text-muted">0 = Sınırsız görüntüleme</small>
                        </div>

                        <div class="form-group">
                            <label>Görüntüleneceği Sayfalar</label>
                            <div class="row">
                                @foreach($pages as $key => $value)
                                    <div class="col-md-4">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" name="display_pages[]" id="page_{{ $key }}" value="{{ $key }}" {{ (is_array(old('display_pages')) && in_array($key, old('display_pages'))) || $key == 'all' ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="page_{{ $key }}">{{ $value }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" name="active" id="active" value="1" {{ old('active', '1') == '1' ? 'checked' : '' }}>
                                <label class="custom-control-label" for="active">Aktif</label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Önizleme</label>
                            <div id="announcement-preview" class="p-3 rounded">
                                <div class="d-flex align-items-start">
                                    <i id="preview-icon" class="material-icons mr-2">info</i>
                                    <div class="flex-grow-1">
                                        <h5 id="preview-title">Duyuru Başlığı</h5>
                                        <p id="preview-content">Duyuru içeriği burada görünecek.</p>
                                        <button id="preview-button" class="btn btn-sm">Buton</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Kaydet</button>
                        <a href="{{ route('admin.announcements.index') }}" class="btn btn-secondary">İptal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<style>
    #announcement-preview {
        border: 2px solid #ffeeba;
        background-color: #fff3cd;
        color: #856404;
    }
    
    #preview-button {
        background-color: transparent;
        border: 1px solid currentColor;
        color: inherit;
    }
</style>
@stop

@section('js')
<script>
    $(function() {
        // Önizleme güncelleme fonksiyonu
        function updatePreview() {
            const title = $('#title').val() || 'Duyuru Başlığı';
            const content = $('#content').val() || 'Duyuru içeriği burada görünecek.';
            const bgColor = $('#bg_color').val();
            const textColor = $('#text_color').val();
            const borderColor = $('#border_color').val();
            const icon = $('#icon').val();
            const buttonText = $('#button_text').val();
            
            $('#preview-title').text(title);
            $('#preview-content').text(content);
            $('#preview-icon').text(icon);
            
            // Buton görünürlüğü
            if (buttonText) {
                $('#preview-button').text(buttonText).show();
            } else {
                $('#preview-button').hide();
            }
            
            // Stil güncelleme
            $('#announcement-preview').css({
                'background-color': bgColor,
                'color': textColor,
                'border-color': borderColor
            });
            
            $('#preview-button').css({
                'color': textColor,
                'border-color': textColor
            });
        }
        
        // İlk yükleme
        updatePreview();
        
        // Input değişikliklerini izle
        $('#title, #content, #bg_color, #text_color, #border_color, #icon, #button_text').on('input change', updatePreview);
    });
</script>
@stop 