@extends('adminlte::page')

@section('title', 'Etkinlik Verileri Çekme')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Etkinlik Verileri Çekme</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Anasayfa</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.events.index') }}">Etkinlikler</a></li>
                <li class="breadcrumb-item active">Etkinlik Verileri Çekme</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .borderBottom {
        border-bottom: 1px solid #dee2e6;
    }
    .event-card {
        border-radius: 0.25rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    .date-head {
        font-weight: 500;
    }
    #preview-container pre {
        background-color: #f8f9fa;
        padding: 10px;
        border-radius: 4px;
        border: 1px solid #eee;
    }
    .etkinlik-adi {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
    }
    .etkinlik-tur {
        font-size: 1.1rem;
        margin-bottom: 1rem;
    }
    .etkinlik-tarih {
        font-size: 0.9rem;
    }
</style>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Etkinlik Verileri Çekme</h3>
                </div>
                <div class="card-body">
                    <p>Bu ekran, harici bir kaynaktan etkinlikleri otomatik olarak çekmenizi sağlar.</p>
                    
                    @if (session('success'))
                        <div class="alert alert-success">
                            <i class="icon fas fa-check"></i> {{ session('success') }}
                        </div>
                    @endif
                    
                    @if (session('error'))
                        <div class="alert alert-danger">
                            <i class="icon fas fa-ban"></i> {{ session('error') }}
                        </div>
                    @endif
                    
                    @if (session('warning'))
                        <div class="alert alert-warning">
                            <i class="icon fas fa-exclamation-triangle"></i> {{ session('warning') }}
                        </div>
                    @endif
                    
                    <div id="result-container" style="display: none;" class="alert alert-info">
                        <div id="result-message"></div>
                        <div id="result-details" class="mt-3">
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
                            </div>
                            <div class="mt-2 small">
                                <span id="processed-count">0</span> / <span id="total-count">0</span> etkinlik işlendi
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <div class="info-box bg-info">
                                        <span class="info-box-icon"><i class="fas fa-file-alt"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Toplam Etkinlik</span>
                                            <span class="info-box-number" id="total-events">0</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="info-box bg-success">
                                        <span class="info-box-icon"><i class="fas fa-plus"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Yeni Etkinlik</span>
                                            <span class="info-box-number" id="new-events">0</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="info-box bg-warning">
                                        <span class="info-box-icon"><i class="fas fa-tags"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Yeni Kategori</span>
                                            <span class="info-box-number" id="new-categories">0</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="info-box bg-danger">
                                        <span class="info-box-icon"><i class="fas fa-exclamation-triangle"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Hatalar</span>
                                            <span class="info-box-number" id="error-count">0</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <form action="#" method="POST" id="scrape-form">
                        @csrf
                        <div class="form-group">
                            <label for="url">Etkinlik URL Adresi</label>
                            <input type="url" class="form-control" id="url" name="url" 
                                placeholder="Örnek: https://kultursanat.cankaya.bel.tr/etkinlikler" 
                                value="https://kultursanat.cankaya.bel.tr/etkinlikler" required>
                            <small class="form-text text-muted">Etkinliklerin çekileceği sayfanın URL'ini girin.</small>
                        </div>
                        
                        <div class="mt-4 mb-4">
                            <button type="submit" id="scrape-button" class="btn btn-primary">
                                <i class="fas fa-sync-alt"></i> Etkinlikleri Çek
                            </button>
                            <button type="button" id="preview-button" class="btn btn-info">
                                <i class="fas fa-eye"></i> Etkinlikleri Göster (Eklemeden)
                            </button>
                            <a href="{{ route('admin.events.index') }}" class="btn btn-secondary ml-2">
                                <i class="fas fa-arrow-left"></i> Etkinlikler Listesine Dön
                            </a>
                        </div>
                    </form>
                    
                    <!-- Önizleme Sonuçları Alanı -->
                    <div id="preview-container" style="display: none;" class="mt-4">
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">Etkinlik Önizleme</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="preview-content">
                                    <div class="text-center">
                                        <i class="fas fa-spinner fa-spin"></i> Yükleniyor...
                                    </div>
                                </div>
                                
                                <!-- Tek Etkinlik Ekleme Butonu -->
                                <div id="add-single-event-container" class="text-center mt-3" style="display: none;">
                                    <button type="button" id="add-single-event-button" class="btn btn-success btn-lg">
                                        <i class="fas fa-plus-circle"></i> Bu Etkinliği Ekle
                                    </button>
                                    <div id="add-result" class="mt-2" style="display: none;"></div>
                                </div>
                                
                                <div id="preview-debug" class="mt-4" style="display: none;">
                                    <div class="card card-outline card-warning">
                                        <div class="card-header">
                                            <h3 class="card-title">Debug Bilgileri</h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="nav nav-tabs mb-3" id="debug-tabs" role="tablist">
                                                <a class="nav-item nav-link active" id="debug-html-tab" data-toggle="tab" href="#debug-html" role="tab" aria-controls="debug-html" aria-selected="true">HTML</a>
                                                <a class="nav-item nav-link" id="debug-data-tab" data-toggle="tab" href="#debug-data" role="tab" aria-controls="debug-data" aria-selected="false">Veri Yapısı</a>
                                                <a class="nav-item nav-link" id="debug-image-tab" data-toggle="tab" href="#debug-image" role="tab" aria-controls="debug-image" aria-selected="false">Resim</a>
                                                <a class="nav-item nav-link" id="debug-selector-tab" data-toggle="tab" href="#debug-selector" role="tab" aria-controls="debug-selector" aria-selected="false">Seçiciler</a>
                                            </div>
                                            <div class="tab-content" id="debug-tabs-content">
                                                <div class="tab-pane fade show active" id="debug-html" role="tabpanel" aria-labelledby="debug-html-tab">
                                                    <pre id="debug-html-content" style="max-height: 300px; overflow: auto;"></pre>
                                                </div>
                                                <div class="tab-pane fade" id="debug-data" role="tabpanel" aria-labelledby="debug-data-tab">
                                                    <pre id="debug-data-content" style="max-height: 300px; overflow: auto;"></pre>
                                                </div>
                                                <div class="tab-pane fade" id="debug-image" role="tabpanel" aria-labelledby="debug-image-tab">
                                                    <div id="debug-image-content"></div>
                                                </div>
                                                <div class="tab-pane fade" id="debug-selector" role="tabpanel" aria-labelledby="debug-selector-tab">
                                                    <div class="alert alert-info">
                                                        <p><strong>Etkinlik Başlığı:</strong> <code>.//h2[@class="etkinlik-adi"]</code></p>
                                                        <p><strong>Etkinlik Türü:</strong> <code>.//h3[@class="etkinlik-tur"]</code></p>
                                                        <p><strong>Tarih:</strong> <code>.//div[contains(@class, "date-head") and contains(., "Tarih")]/following-sibling::span[@class="etkinlik-tarih col-10"]</code></p>
                                                        <p><strong>Saat:</strong> <code>.//div[contains(@class, "date-head") and contains(., "Saat")]/following-sibling::span[@class="etkinlik-tarih col-10"]</code></p>
                                                        <p><strong>Yer:</strong> <code>.//div[contains(@class, "date-head") and contains(., "Yer")]/following-sibling::span[@class="etkinlik-tarih col-10"]</code></p>
                                                        <p><strong>Görsel:</strong> <code>.//img[@class="img-fluid"]</code> - srcset ve src özellikleri kullanılır</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="button" id="use-preview-data" class="btn btn-success" style="display: none;">
                                    <i class="fas fa-check"></i> Bu Verileri Kullan ve Ayarları Güncelle
                                </button>
                                <button type="button" id="close-preview" class="btn btn-default float-right">
                                    <i class="fas fa-times"></i> Kapat
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Desteklenen Etkinlik Sayfaları</h3>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="#" class="list-group-item list-group-item-action" data-url="https://kultursanat.cankaya.bel.tr/etkinlikler">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">Çankaya Belediyesi Kültür Sanat</h5>
                                <small>Önerilen</small>
                            </div>
                            <p class="mb-1">Çankaya Belediyesi'nin tüm kültür sanat etkinliklerini içerir.</p>
                            <small>https://kultursanat.cankaya.bel.tr/etkinlikler</small>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">Etkinlik Çekme İşlemi Hakkında</h3>
                </div>
                <div class="card-body">
                    <h5>Bilgilendirme</h5>
                    <ul>
                        <li>Etkinlik çekme işlemi, belirtilen web sayfasını ziyaret ederek etkinlik bilgilerini otomatik olarak toplar.</li>
                        <li>İşlem sırasında etkinlik başlığı, tarihi, konumu, açıklaması ve varsa görselleri çekilir.</li>
                        <li>Daha önce eklenmiş etkinlikler tekrar eklenmez.</li>
                        <li>İşlem, sayfadaki HTML yapısına bağlıdır. Sayfa yapısı değişirse çekme işlemi başarısız olabilir.</li>
                    </ul>
                    
                    <h5>Güvenlik Uyarıları</h5>
                    <ul>
                        <li>Sadece güvendiğiniz ve yetkili olduğunuz web sitelerinden etkinlik çekin.</li>
                        <li>Yüksek miktarda istek göndermek, kaynak web sitesine yük bindirabilir.</li>
                        <li>İçerik telif hakkı kanunlarına uygunluğu sizin sorumluluğunuzdadır.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
    $(function() {
        // Sayfa yükleme mesajı (debugging için)
        console.log('Etkinlik kontrol sayfası yüklendi - ' + new Date().toLocaleTimeString());
        
        // Ajax istek sayacı
        var activeAjaxRequests = 0;
        
        // Ajax durumunu izle
        $(document).ajaxStart(function() {
            activeAjaxRequests++;
            console.log('AJAX istek başladı. Aktif istek sayısı: ' + activeAjaxRequests);
        }).ajaxStop(function() {
            activeAjaxRequests--;
            console.log('AJAX istek tamamlandı. Aktif istek sayısı: ' + activeAjaxRequests);
        }).ajaxError(function(event, xhr, settings) {
            console.error('AJAX istek hatası:', {
                url: settings.url,
                type: settings.type,
                status: xhr.status,
                statusText: xhr.statusText,
                responseText: xhr.responseText
            });
        });
        
        // AJAX ayarlarını güncelle
        $.ajaxSetup({
            cache: false,
            timeout: 60000 // 60 saniye
        });
        
        // AJAX ile form submit
        $('#scrape-form').on('submit', function(e) {
            e.preventDefault();
            
            $('#scrape-button').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> İşlem Devam Ediyor...');
            $('#result-container').show();
            $('#result-message').html('<i class="fas fa-spinner fa-spin"></i> Etkinlikler çekiliyor...');
            
            // İlk sayfayı işle
            processPage(1);
        });
        
        // Önizleme butonu
        $('#preview-button').on('click', function() {
            $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Veriler Alınıyor...');
            $('#preview-container').show();
            $('#preview-content').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Yükleniyor...</div>');
            
            // AJAX isteği başlangıcını logla
            console.log('AJAX isteği başlatılıyor - ' + new Date().toLocaleTimeString());
            
            $.ajax({
                url: '{{ route("admin.events.preview") }}',
                type: 'POST',
                data: {
                    _token: $('input[name="_token"]').val(),
                    url: $('#url').val(),
                    limit: 1 // Sadece 1 etkinlik getir
                },
                dataType: 'json', // JSON yanıt bekliyoruz
                timeout: 30000, // 30 saniyelik timeout
                beforeSend: function(xhr) {
                    console.log('AJAX isteği gönderiliyor - ' + new Date().toLocaleTimeString());
                },
                success: function(response) {
                    console.log('AJAX isteği başarılı - ' + new Date().toLocaleTimeString(), response);
                    $('#preview-button').prop('disabled', false).html('<i class="fas fa-eye"></i> Etkinlikleri Göster (Eklemeden)');
                    
                    if (response.success) {
                        displayPreviewResults(response);
                    } else {
                        displayPreviewError(response);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX isteği başarısız - ' + new Date().toLocaleTimeString());
                    console.error('Durum: ' + status);
                    console.error('Hata: ' + error);
                    console.error('Yanıt:', xhr.responseText);
                    console.error('Durum Kodu:', xhr.status);
                    
                    $('#preview-button').prop('disabled', false).html('<i class="fas fa-eye"></i> Etkinlikleri Göster (Eklemeden)');
                    
                    var errorMsg = 'Bir hata oluştu!';
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMsg = xhr.responseJSON.error;
                    } else if (xhr.status) {
                        errorMsg = 'HTTP Hata Kodu: ' + xhr.status + ' - ' + error;
                    }
                    
                    $('#preview-content').html(
                        '<div class="alert alert-danger">' +
                        '<h5><i class="icon fas fa-ban"></i> Hata!</h5>' +
                        errorMsg +
                        '<hr>' +
                        '<p>Teknik Detaylar:</p>' +
                        '<pre style="max-height: 200px; overflow: auto;">' + escapeHtml(xhr.responseText) + '</pre>' +
                        '</div>'
                    );
                    
                    // Debug bilgilerini göster
                    $('#debug-html-content').text('Hata nedeniyle HTML içeriği alınamadı.');
                    $('#preview-debug').show();
                }
            });
        });
        
        // Önizleme kapatma
        $('#close-preview').on('click', function() {
            $('#preview-container').hide();
            $('#preview-content').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Yükleniyor...</div>');
            $('#preview-debug').hide();
            $('#use-preview-data').hide();
            $('#add-single-event-container').hide();
            $('#add-result').hide();
            window.currentEventData = null;
        });
        
        // Önizleme verilerini kullanma
        $('#use-preview-data').on('click', function() {
            // Burada önizleme verilerini kullanarak EventScraperService.php dosyasını güncelleyecek AJAX çağrısı yapılabilir
            alert('Bu özellik henüz uygulanmadı. Önizleme verilerine göre EventScraperService.php dosyasındaki çözümleme mantığını güncellemeniz gerekecek.');
        });
        
        // Quick URL selection
        $('.list-group-item').on('click', function(e) {
            e.preventDefault();
            var url = $(this).data('url');
            $('#url').val(url);
        });
        
        // Sayfa işleme fonksiyonu
        function processPage(page) {
            $.ajax({
                url: '{{ route("admin.events.scrape") }}',
                type: 'POST',
                data: {
                    _token: $('input[name="_token"]').val(),
                    url: $('#url').val(),
                    page: page
                },
                success: function(response) {
                    console.log('API yanıtı:', response);
                    
                    // İstatistikleri güncelle
                    updateStats(response);
                    
                    // İşlenen etkinlik sayısını güncelle
                    var processedCount = parseInt($('#processed-count').text());
                    $('#processed-count').text(processedCount + response.processedEvents);
                    
                    // İlerleme çubuğunu güncelle
                    var progress = (processedCount + response.processedEvents) / response.totalEvents * 100;
                    $('.progress-bar').css('width', progress + '%');
                    
                    // Daha fazla sayfa varsa bir sonraki sayfayı işle
                    if (response.hasNextPage) {
                        $('#result-message').html('<i class="fas fa-spinner fa-spin"></i> Sayfa ' + (page + 1) + ' işleniyor...');
                        processPage(page + 1);
                    } else {
                        // İşlem tamamlandı
                        $('#result-message').html('<i class="fas fa-check-circle"></i> Etkinlik çekme işlemi tamamlandı!');
                        $('#scrape-button').prop('disabled', false).html('<i class="fas fa-sync-alt"></i> Etkinlikleri Çek');
                        
                        // Başarılı mesajı
                        var message = '<div class="alert alert-success mt-3">' +
                                      '<i class="icon fas fa-check"></i> Etkinlik çekme işlemi başarıyla tamamlandı! ' +
                                      'Toplam <strong>' + response.totalEvents + '</strong> etkinlik işlendi. ' +
                                      '<strong>' + response.newEvents + '</strong> yeni etkinlik eklendi.</div>';
                        $('#result-details').append(message);
                    }
                },
                error: function(xhr) {
                    console.error('Hata:', xhr);
                    
                    var errorMsg = 'Bir hata oluştu!';
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMsg = xhr.responseJSON.error;
                    }
                    
                    $('#result-message').html('<i class="fas fa-exclamation-triangle"></i> ' + errorMsg);
                    $('#error-count').text(parseInt($('#error-count').text()) + 1);
                    $('#scrape-button').prop('disabled', false).html('<i class="fas fa-sync-alt"></i> Etkinlikleri Çek');
                    
                    var errorAlert = '<div class="alert alert-danger mt-3">' +
                                    '<i class="icon fas fa-ban"></i> Etkinlik çekme sırasında bir hata oluştu: ' + errorMsg + '</div>';
                    $('#result-details').append(errorAlert);
                }
            });
        }
        
        // İstatistikleri güncelleme fonksiyonu
        function updateStats(data) {
            $('#total-events').text(data.totalEvents);
            $('#new-events').text(data.newEvents);
            $('#new-categories').text(data.newCategories);
            $('#total-count').text(data.totalEvents);
            
            if (data.errors && data.errors.length > 0) {
                $('#error-count').text(data.errors.length);
                
                // Hata detaylarını göster
                $.each(data.errors, function(index, error) {
                    var errorMsg = '<div class="text-danger"><small><i class="fas fa-exclamation-circle"></i> ' + error + '</small></div>';
                    $('#result-details').append(errorMsg);
                });
            }
        }
        
        // Önizleme sonuçlarını görüntüleme fonksiyonu
        function displayPreviewResults(response) {
            console.log('Etkinlik önizleme sonuçları:', response);
            
            var events = response.events;
            var previewContent = '<div class="alert alert-success mb-4">' +
                                '<i class="fas fa-check-circle mr-2"></i> ' +
                                events.length + ' adet etkinlik önizlemesi başarıyla yapıldı.' +
                                '</div>';
            
            // Etkinlik kartları oluştur
            previewContent += '<div class="row">';
            
            $.each(events, function(i, event) {
                // Event verilerini global değişkene ata (tek etkinlik eklenirken kullanılacak)
                window.currentEventData = event;
                
                previewContent += '<div class="col-md-12">' +
                    '<div class="card">' +
                        '<div class="card-header d-flex justify-content-between align-items-center bg-light">' +
                            '<h5 class="mb-0">' + (event.title || 'Başlıksız Etkinlik') + '</h5>' +
                            '<span class="badge badge-primary">' + (event.category || 'Genel') + '</span>' +
                        '</div>' +
                        '<div class="card-body">' +
                            '<div class="row">';
                            
                // Sol tarafta etkinlik bilgileri
                previewContent += '<div class="col-md-7">' +
                    '<div class="mb-3">' +
                        '<h6><i class="fas fa-calendar mr-2"></i> Tarih:</h6>' +
                        '<p>' + (event.dateText || 'Belirtilmemiş') + '</p>' +
                    '</div>' +
                    '<div class="mb-3">' +
                        '<h6><i class="fas fa-clock mr-2"></i> Saat:</h6>' +
                        '<p>' + (event.timeText || 'Belirtilmemiş') + '</p>' +
                    '</div>' +
                    '<div class="mb-3">' +
                        '<h6><i class="fas fa-map-marker-alt mr-2"></i> Konum:</h6>' +
                        '<p>' + (event.location || 'Belirtilmemiş') + '</p>' +
                    '</div>';
                    
                // Detay URL bilgisi
                if (event.detailUrl) {
                    previewContent += '<div class="mb-3">' +
                        '<h6><i class="fas fa-link mr-2"></i> Detay URL:</h6>' +
                        '<p><a href="' + event.detailUrl + '" target="_blank">' + event.detailUrl + '</a></p>' +
                    '</div>';
                }
                
                previewContent += '</div>';
                
                // Sağ tarafta görsel ve HTML
                previewContent += '<div class="col-md-5">';
                
                // Görsel önizleme
                if (event.imageUrl) {
                    previewContent += '<div class="image-preview mb-3 text-center">' +
                        '<h6><i class="fas fa-image mr-2"></i> Görsel:</h6>' +
                        '<div class="border p-2 mb-2" style="background-color: #f8f9fa; position: relative;">' +
                        '<div class="loading-spinner" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: rgba(255, 255, 255, 0.8); padding: 15px; border-radius: 5px;"><i class="fas fa-spinner fa-spin fa-2x"></i></div>' +
                        '<img src="' + event.imageUrl + '" class="img-fluid" style="max-height: 200px;" ' +
                        'onload="this.parentNode.querySelector(\'.loading-spinner\').style.display=\'none\'" ' +
                        'onerror="this.parentNode.innerHTML=\'<div class=\\\'alert alert-danger\\\'>Görsel yüklenemedi</div>\'">' +
                        '</div>' +
                        '<p class="small text-muted mt-1 mb-1">URL: ' + event.imageUrl + '</p>' +
                    '</div>';
                } else {
                    previewContent += '<div class="alert alert-warning">' +
                        '<i class="fas fa-exclamation-triangle mr-2"></i> Görsel bulunamadı' +
                    '</div>';
                }
                
                // HTML bilgisi (collapsible)
                if (event.imageHtml) {
                    previewContent += '<div class="card mb-3">' +
                        '<div class="card-header bg-secondary text-white" data-toggle="collapse" href="#htmlCollapse' + i + '" role="button" aria-expanded="false">' +
                            '<i class="fas fa-code mr-2"></i> HTML İçeriği (genişletmek için tıklayın)' +
                        '</div>' +
                        '<div class="collapse" id="htmlCollapse' + i + '">' +
                            '<div class="card-body">' +
                                '<pre style="max-height: 200px; overflow-y: auto; font-size: 11px;"><code>' + (event.imageHtml ? event.imageHtml.replace(/</g, '&lt;').replace(/>/g, '&gt;') : 'HTML içeriği bulunamadı') + '</code></pre>' +
                            '</div>' +
                        '</div>' +
                    '</div>';
                }
                
                previewContent += '</div>'; // col-md-5 sonu
                
                previewContent += '</div>'; // row sonu
                
                // Etkinlik Ekleme Butonu
                previewContent += '<div class="mt-3 text-right">' +
                    '<button type="button" class="btn btn-success" id="add-single-event-button">' +
                        '<i class="fas fa-plus-circle mr-1"></i> Bu Etkinliği Sisteme Ekle' +
                    '</button>' +
                '</div>';
                    
                previewContent += '</div>'; // card-body sonu
                previewContent += '</div>'; // card sonu
                previewContent += '</div>'; // col-md-12 sonu
            });
            
            previewContent += '</div>'; // row sonu
            
            // Sonuçları göster
            $('#preview-content').html(previewContent);
            
            // "Bu Etkinliği Sisteme Ekle" butonu görünür yapıp aktif et
            $('#add-single-event-container').show();
        }
        
        // Önizleme hatasını görüntüleme fonksiyonu
        function displayPreviewError(response) {
            var html = '<div class="alert alert-danger">' +
                       '<h5><i class="icon fas fa-ban"></i> Hata!</h5>' +
                       (response.message || 'Etkinlikler çekilemedi.') +
                       '</div>';
            
            if (response.html) {
                // Debug bilgilerini hazırla
                $('#debug-html-content').text(response.html);
                $('#debug-debug').show();
            }
            
            $('#preview-content').html(html);
        }
        
        // Tek etkinlik ekleme butonuna tıklama
        $('#add-single-event-button').click(function() {
            // Buton durumunu güncelle
            var $btn = $(this).button('loading');
            $(this).html('<i class="fas fa-spinner fa-spin"></i> Ekleniyor...');
            $(this).prop('disabled', true);
            
            // Eğer etkinlik verisi yoksa uyarı ver
            if (!window.currentEventData) {
                $('#add-result').html(
                    '<div class="alert alert-danger">' +
                    '<h5><i class="icon fas fa-ban"></i> Hata!</h5>' +
                    'Eklenecek etkinlik verisi bulunamadı. Lütfen önizlemeyi tekrar yapın.' +
                    '</div>'
                ).show();
                $btn.button('reset');
                $(this).html('<i class="fas fa-plus-circle"></i> Bu Etkinliği Ekle');
                $(this).prop('disabled', false);
                return;
            }
            
            // AJAX isteği ile etkinliği ekle
            $.ajax({
                url: '{{ route("admin.events.add-single-event") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    event_data: window.currentEventData
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#add-result').html(
                            '<div class="alert alert-success">' +
                            '<h5><i class="icon fas fa-check"></i> Başarılı!</h5>' +
                            response.message +
                            '<div class="mt-2">' +
                            '<a href="{{ route("admin.events.index") }}" class="btn btn-primary">' +
                            '<i class="fas fa-list"></i> Etkinlikler Listesine Git' +
                            '</a> ' +
                            '<a href="{{ route("admin.events.edit", "") }}/' + response.event_id + '" class="btn btn-info">' +
                            '<i class="fas fa-edit"></i> Etkinliği Düzenle' +
                            '</a>' +
                            '</div>' +
                            '</div>'
                        ).show();
                        // Buton pasif kalsın, başarıyla eklendi zaten
                        $('#add-single-event-button').hide();
                    } else {
                        $('#add-result').html(
                            '<div class="alert alert-warning">' +
                            '<h5><i class="icon fas fa-exclamation-triangle"></i> Uyarı!</h5>' +
                            response.message +
                            '</div>'
                        ).show();
                        $btn.button('reset');
                        $('#add-single-event-button').html('<i class="fas fa-plus-circle"></i> Bu Etkinliği Ekle');
                        $('#add-single-event-button').prop('disabled', false);
                    }
                },
                error: function(xhr) {
                    var errorMsg = 'Etkinlik eklenirken bir hata oluştu.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    
                    $('#add-result').html(
                        '<div class="alert alert-danger">' +
                        '<h5><i class="icon fas fa-ban"></i> Hata!</h5>' +
                        errorMsg +
                        '</div>'
                    ).show();
                    $btn.button('reset');
                    $('#add-single-event-button').html('<i class="fas fa-plus-circle"></i> Bu Etkinliği Ekle');
                    $('#add-single-event-button').prop('disabled', false);
                }
            });
        });
        
        // HTML karakterlerini kaçırma fonksiyonu
        function escapeHtml(text) {
            if (!text) return '';
            return text
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }
    });
</script>
@stop 