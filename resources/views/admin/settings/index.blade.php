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

    <!-- Favicon Ayarları -->
    <div class="row">
        <div class="col-12">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-star"></i> Favicon Ayarları
                    </h3>
                </div>
                <form action="{{ route('admin.settings.favicon.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="favicon">
                                        <i class="fas fa-image"></i> Favicon Dosyası
                                    </label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input @error('favicon') is-invalid @enderror" 
                                                   id="favicon" name="favicon" accept="image/*">
                                            <label class="custom-file-label" for="favicon">Favicon seçin...</label>
                                        </div>
                                    </div>
                                    @error('favicon')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle"></i> 
                                        Önerilen boyut: 32x32 piksel, PNG veya ICO formatı. Maksimum dosya boyutu: 2MB
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Mevcut Favicon</label>
                                    <div class="mt-2">
                                        @if(isset($settings['site_favicon']) && $settings['site_favicon']->value)
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset('uploads/' . $settings['site_favicon']->value) }}" 
                                                     alt="Mevcut Favicon" 
                                                     class="img-thumbnail mr-3" 
                                                     style="width: 32px; height: 32px;">
                                                <div>
                                                    <p class="mb-1"><strong>Aktif favicon</strong></p>
                                                    <a href="{{ route('admin.settings.favicon.delete') }}" 
                                                       class="btn btn-sm btn-danger" 
                                                       onclick="return confirm('Favicon silinsin mi?')">
                                                        <i class="fas fa-trash"></i> Sil
                                                    </a>
                                                </div>
                                            </div>
                                        @else
                                            <div class="alert alert-warning">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                Henüz favicon yüklenmemiş. Varsayılan favicon kullanılıyor.
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-lightbulb"></i>
                            <strong>Bilgi:</strong> Favicon, web sitenizin tarayıcı sekmesinde görünen küçük simgedir. 
                            Yüklediğiniz favicon tüm sitede kullanılacaktır.
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-info">
                            <i class="fas fa-upload"></i> Favicon Yükle
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Preloader Ayarları -->
    <div class="row">
        <div class="col-12">
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-spinner"></i> Preloader Ayarları
                    </h3>
                </div>
                <form action="{{ route('admin.settings.preloader.update') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" 
                                               class="custom-control-input" 
                                               id="preloader_enabled" 
                                               name="preloader_enabled" 
                                               value="1"
                                               {{ (isset($settings['preloader_enabled']) && $settings['preloader_enabled']->value == '1') || !isset($settings['preloader_enabled']) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="preloader_enabled">
                                            <strong>Preloader'ı Aktif Et</strong>
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle"></i> 
                                        Bu seçenek aktif olduğunda sayfa yüklenirken beyaz bir preloader ekranı gösterilir.
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-lightbulb"></i>
                            <strong>Bilgi:</strong> Preloader sayfa yüklenme sürecini kullanıcılara göstermek için kullanılır. 
                            Çok hızlı bir şekilde (0.05 saniye) gösterilir ve animasyon içermez.
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save"></i> Preloader Ayarlarını Kaydet
                        </button>
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
                        <i class="fas fa-shield-alt"></i> Güvenlik Ayarları
                    </h3>
                </div>
                <form action="{{ route('admin.settings.session-timeout.update') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="session_timeout">
                                        <i class="fas fa-clock"></i> Oturum Süresi (Dakika)
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control @error('session_timeout') is-invalid @enderror" 
                                            id="session_timeout" 
                                            name="session_timeout">
                                        <option value="15" {{ old('session_timeout', $settings['session_timeout']->value ?? 120) == 15 ? 'selected' : '' }}>15 dakika</option>
                                        <option value="30" {{ old('session_timeout', $settings['session_timeout']->value ?? 120) == 30 ? 'selected' : '' }}>30 dakika</option>
                                        <option value="60" {{ old('session_timeout', $settings['session_timeout']->value ?? 120) == 60 ? 'selected' : '' }}>1 saat</option>
                                        <option value="120" {{ old('session_timeout', $settings['session_timeout']->value ?? 120) == 120 ? 'selected' : '' }}>2 saat (varsayılan)</option>
                                        <option value="180" {{ old('session_timeout', $settings['session_timeout']->value ?? 120) == 180 ? 'selected' : '' }}>3 saat</option>
                                        <option value="240" {{ old('session_timeout', $settings['session_timeout']->value ?? 120) == 240 ? 'selected' : '' }}>4 saat</option>
                                        <option value="360" {{ old('session_timeout', $settings['session_timeout']->value ?? 120) == 360 ? 'selected' : '' }}>6 saat</option>
                                        <option value="480" {{ old('session_timeout', $settings['session_timeout']->value ?? 120) == 480 ? 'selected' : '' }}>8 saat</option>
                                        <option value="720" {{ old('session_timeout', $settings['session_timeout']->value ?? 120) == 720 ? 'selected' : '' }}>12 saat</option>
                                        <option value="1440" {{ old('session_timeout', $settings['session_timeout']->value ?? 120) == 1440 ? 'selected' : '' }}>24 saat</option>
                                    </select>
                                    @error('session_timeout')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle"></i> 
                                        Admin panelinde bu süre kadar hareketsiz kalındığında otomatik logout olur.
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="alert alert-info">
                                    <i class="fas fa-shield-alt"></i>
                                    <strong>Güvenlik Bilgisi:</strong>
                                    <br>• <strong>Kısa süreler (15-30 dk)</strong>: Yüksek güvenlik, sık login gerekir
                                    <br>• <strong>Orta süreler (1-4 saat)</strong>: Dengeli güvenlik ve kullanım
                                    <br>• <strong>Uzun süreler (6+ saat)</strong>: Pratik kullanım, daha az güvenlik
                                    <br><br>
                                    <strong>Varsayılan:</strong> 2 saat (kurumsal kullanım için önerilen)
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-secondary">
                            <i class="fas fa-save"></i> Güvenlik Ayarlarını Kaydet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Dosya Yönetimi Ayarları -->
    <div class="row">
        <div class="col-12">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-upload"></i> Dosya Yönetimi Ayarları
                    </h3>
                </div>
                <form action="{{ route('admin.settings.file-upload-limit.update') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="max_file_upload_size">
                                        <i class="fas fa-file-upload"></i> Maksimum Dosya Boyutu (MB)
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control @error('max_file_upload_size') is-invalid @enderror" 
                                            id="max_file_upload_size" 
                                            name="max_file_upload_size">
                                        <option value="1" {{ old('max_file_upload_size', $settings['max_file_upload_size']->value ?? 50) == 1 ? 'selected' : '' }}>1 MB</option>
                                        <option value="2" {{ old('max_file_upload_size', $settings['max_file_upload_size']->value ?? 50) == 2 ? 'selected' : '' }}>2 MB</option>
                                        <option value="5" {{ old('max_file_upload_size', $settings['max_file_upload_size']->value ?? 50) == 5 ? 'selected' : '' }}>5 MB</option>
                                        <option value="10" {{ old('max_file_upload_size', $settings['max_file_upload_size']->value ?? 50) == 10 ? 'selected' : '' }}>10 MB</option>
                                        <option value="20" {{ old('max_file_upload_size', $settings['max_file_upload_size']->value ?? 50) == 20 ? 'selected' : '' }}>20 MB</option>
                                        <option value="50" {{ old('max_file_upload_size', $settings['max_file_upload_size']->value ?? 50) == 50 ? 'selected' : '' }}>50 MB (varsayılan)</option>
                                        <option value="100" {{ old('max_file_upload_size', $settings['max_file_upload_size']->value ?? 50) == 100 ? 'selected' : '' }}>100 MB</option>
                                        <option value="200" {{ old('max_file_upload_size', $settings['max_file_upload_size']->value ?? 50) == 200 ? 'selected' : '' }}>200 MB</option>
                                        <option value="300" {{ old('max_file_upload_size', $settings['max_file_upload_size']->value ?? 50) == 300 ? 'selected' : '' }}>300 MB</option>
                                        <option value="500" {{ old('max_file_upload_size', $settings['max_file_upload_size']->value ?? 50) == 500 ? 'selected' : '' }}>500 MB</option>
                                    </select>
                                    @error('max_file_upload_size')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle"></i> 
                                        Tek seferde yüklenebilecek maksimum dosya boyutu.
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="alert alert-success">
                                    <i class="fas fa-file-upload"></i>
                                    <strong>Dosya Boyutu Önerileri:</strong>
                                    <br>• <strong>1-5 MB</strong>: Belgeler, resimler için ideal
                                    <br>• <strong>10-50 MB</strong>: Sunumlar, PDF'ler için uygun
                                    <br>• <strong>100+ MB</strong>: Video dosyaları için gerekli
                                    <br><br>
                                    <strong>Dikkat:</strong> Büyük dosyalar server hafızasını ve yükleme süresini etkiler.
                                    <br><br>
                                    <strong>Mevcut Server Limitleri:</strong>
                                    <br>• PHP Max Upload: {{ ini_get('upload_max_filesize') }}
                                    <br>• PHP Post Max: {{ ini_get('post_max_size') }}
                                    <br>• PHP Memory: {{ ini_get('memory_limit') }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Dosya Yönetimi Ayarlarını Kaydet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Google Analytics -->
    <div class="row">
        <div class="col-md-6">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fab fa-google text-danger"></i> Google Analytics
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">
                        Site istatistikleri, ziyaretçi analizi ve performans raporları için Google Analytics'e erişim.
                    </p>
                    <a href="https://accounts.google.com/v3/signin/identifier?dsh=S809692%3A1656229414280602&flowEntry=ServiceLogin&flowName=WebLiteSignIn&hl=tr&service=analytics&ifkv=AX3vH3_jSrfHgShpL5YSHGoTUF8s7Z4AL-8mWS76jrS9oNMxW0NP219YKFErBLJKDOf15XUYwR27eA" 
                       target="_blank" 
                       class="btn btn-primary btn-block">
                        <i class="fab fa-google mr-2"></i>
                        Google Analytics'e Git
                        <i class="fas fa-external-link-alt ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Gelecekte eklenecek diğer ayar bölümleri için yer -->
    <div class="row">
        <div class="col-12">
            <div class="card card-light">
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
    
    /* Google Analytics Kartı Stilleri */
    .card-outline.card-primary {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .card-outline.card-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,123,255,0.3);
    }
    
    .card-outline.card-primary .btn-primary {
        background: linear-gradient(45deg, #007bff, #0056b3);
        border: none;
        transition: all 0.3s ease;
    }
    
    .card-outline.card-primary .btn-primary:hover {
        background: linear-gradient(45deg, #0056b3, #004085);
        transform: translateY(-1px);
        box-shadow: 0 4px 15px rgba(0,123,255,0.4);
    }
    

    
    /* Bilgi Alert'i */
    .alert-info {
        border-left: 4px solid #17a2b8;
        background-color: #f8f9fa;
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
    
    // Custom file input label
    $('.custom-file-input').on('change', function() {
        var fileName = $(this).val().split('\\').pop();
        $(this).siblings('.custom-file-label').addClass('selected').html(fileName);
    });
    
    // Session timeout seçimi değiştiğinde bilgilendirme göster
    $('#session_timeout').on('change', function() {
        var selectedValue = $(this).val();
        var selectedText = $(this).find('option:selected').text();
        
        // Uyarı mesajı göster
        if (selectedValue <= 30) {
            toastr.warning('Kısa timeout süreleri güvenli ancak sık login gerektirir.', 'Güvenlik Uyarısı');
        } else if (selectedValue >= 480) {
            toastr.info('Uzun timeout süreleri praktik ancak güvenlik riski oluşturabilir.', 'Bilgilendirme');
        }
    });
    
    // Dosya yükleme limiti seçimi değiştiğinde bilgilendirme göster
    $('#max_file_upload_size').on('change', function() {
        var selectedValue = $(this).val();
        var selectedText = $(this).find('option:selected').text();
        
        // Mevcut server limitlerini kontrol et
        var phpMaxUpload = '{{ ini_get("upload_max_filesize") }}';
        var phpPostMax = '{{ ini_get("post_max_size") }}';
        
        // Uyarı mesajları göster
        if (selectedValue <= 5) {
            toastr.success('Küçük dosya limiti performans için idealdir.', 'Performans');
        } else if (selectedValue >= 200) {
            toastr.warning('Büyük dosya limitleri server performansını etkileyebilir.', 'Performans Uyarısı');
        }
        
        // Server limitinden büyükse uyar
        if (parseInt(selectedValue) > parseInt(phpMaxUpload)) {
            toastr.error('Seçilen limit PHP server limitinden (' + phpMaxUpload + ') büyük!', 'Server Limit Uyarısı');
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
        
        // Session timeout form'u için özel kontrol
        if ($(this).attr('action').includes('session-timeout')) {
            var sessionTimeout = $('#session_timeout').val();
            if (sessionTimeout) {
                $(this).find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Kaydediliyor...');
            }
        }
        
        // Dosya yükleme limiti form'u için özel kontrol
        if ($(this).attr('action').includes('file-upload-limit')) {
            var fileUploadLimit = $('#max_file_upload_size').val();
            if (fileUploadLimit) {
                $(this).find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Kaydediliyor...');
            }
        }
    });
    
    // Google Analytics link tıklama tracking
    $('a[href*="accounts.google.com"]').on('click', function(e) {
        var link = $(this);
        
        // Başarı mesajı göster
        toastr.info('Google Analytics yeni sekmede açılıyor...', 'Yönlendiriliyor');
        
        // Link analizi
        console.log('Google Analytics linkine tıklandı:', {
            href: link.attr('href'),
            timestamp: new Date().toISOString(),
            user_agent: navigator.userAgent
        });
    });
    
    // Analytics kartı hover efekti
    $('.card-outline.card-primary').hover(
        function() {
            $(this).addClass('shadow-lg').css('transform', 'translateY(-2px)');
        },
        function() {
            $(this).removeClass('shadow-lg').css('transform', 'translateY(0)');
        }
    );
</script>
@stop 