@extends('adminlte::page')

@section('title', 'Ayarlar')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-cogs"></i> Ayarlar</h1>
    </div>
@stop

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle"></i> Lütfen aşağıdaki hataları düzeltin:
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <!-- SEO Ayarları -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-search"></i> SEO Ayarları
                    </h3>
                </div>
                <form action="{{ route('admin.settings.seo.update') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="homepage_title">
                                        <i class="fas fa-heading"></i> Anasayfa Başlığı
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('homepage_title') is-invalid @enderror" 
                                           id="homepage_title" 
                                           name="homepage_title" 
                                           value="{{ old('homepage_title', $settings['homepage_title']->value ?? 'Çankaya Belediyesi') }}"
                                           maxlength="60"
                                           placeholder="Anasayfa meta başlığını girin">
                                    @error('homepage_title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle"></i> 
                                        Maksimum 60 karakter (SEO için önerilen)
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="homepage_description">
                                        <i class="fas fa-align-left"></i> Anasayfa Açıklaması
                                        <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control @error('homepage_description') is-invalid @enderror" 
                                              id="homepage_description" 
                                              name="homepage_description" 
                                              rows="3"
                                              maxlength="160"
                                              placeholder="Anasayfa meta açıklamasını girin">{{ old('homepage_description', $settings['homepage_description']->value ?? 'Çankaya Belediyesi resmi web sitesi. Hizmetlerimiz, duyurularımız ve projelerimiz hakkında bilgi alın.') }}</textarea>
                                    @error('homepage_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle"></i> 
                                        Maksimum 160 karakter (SEO için önerilen)
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-lightbulb"></i>
                            <strong>Bilgi:</strong> Bu ayarlar web sitenizin arama motorlarında nasıl görüneceğini belirler. 
                            Başlık ve açıklama alanları Google ve diğer arama motorlarında sitenizin tanıtımı için kullanılır.
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Kaydet
                        </button>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary ml-2">
                            <i class="fas fa-arrow-left"></i> Geri Dön
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Gelecekte eklenecek diğer ayar bölümleri için yer -->
    <div class="row">
        <div class="col-12">
            <div class="card card-secondary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plus-circle"></i> Diğer Ayarlar
                    </h3>
                </div>
                <div class="card-body">
                    <p class="text-muted">
                        <i class="fas fa-info-circle"></i> 
                        Gelecekte buraya site geneli ayarları, sosyal medya linkleri, iletişim bilgileri gibi 
                        diğer ayar bölümleri eklenecektir.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .card-header {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        color: white;
    }
    .card-primary .card-header {
        background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
    }
    .form-control:focus {
        border-color: #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    }
    .btn-primary {
        background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
        border: none;
    }
    .btn-primary:hover {
        background: linear-gradient(135deg, #1e7e34 0%, #155724 100%);
    }
</style>
@stop

@section('js')
<script>
$(document).ready(function() {
    // Karakter sayacı
    $('#homepage_title').on('input', function() {
        var length = $(this).val().length;
        var maxLength = 60;
        var remaining = maxLength - length;
        
        if (remaining < 10) {
            $(this).next('.invalid-feedback').remove();
            $(this).after('<div class="text-warning small">Kalan karakter: ' + remaining + '</div>');
        }
    });
    
    $('#homepage_description').on('input', function() {
        var length = $(this).val().length;
        var maxLength = 160;
        var remaining = maxLength - length;
        
        if (remaining < 20) {
            $(this).next('.invalid-feedback').remove();
            $(this).after('<div class="text-warning small">Kalan karakter: ' + remaining + '</div>');
        }
    });
    
    // Form gönderilmeden önce doğrulama
    $('form').on('submit', function(e) {
        var title = $('#homepage_title').val().trim();
        var description = $('#homepage_description').val().trim();
        
        if (title.length === 0) {
            e.preventDefault();
            $('#homepage_title').focus();
            alert('Anasayfa başlığı boş bırakılamaz!');
            return false;
        }
        
        if (description.length === 0) {
            e.preventDefault();
            $('#homepage_description').focus();
            alert('Anasayfa açıklaması boş bırakılamaz!');
            return false;
        }
    });
});
</script>
@stop 