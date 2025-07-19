@extends('adminlte::page')

@section('title', 'Cache Test')

@section('content_header')
    <h1>📊 Analytics Cache Test</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Otomatik Cache Kontrolü Test</h3>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h5><i class="icon fas fa-info"></i> Test Mekanizması</h5>
                    Bu sayfa cache freshness kontrolünü test etmek için oluşturuldu. 
                    Farklı limitleri test ederek sistemin nasıl çalıştığını görebilirsiniz.
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <h5>Cache Durumu:</h5>
                        <button class="btn btn-primary" onclick="checkCacheStatus()">
                            <i class="fas fa-search"></i> Cache Durumunu Kontrol Et
                        </button>
                        <div id="cacheStatus" class="mt-3"></div>
                    </div>
                    
                    <div class="col-md-6">
                        <h5>Test Limitleri:</h5>
                        <button class="btn btn-warning" onclick="testCacheLimit(5)">
                            5 Dakika Limit
                        </button>
                        <button class="btn btn-danger" onclick="testCacheLimit(1)">
                            1 Dakika Limit
                        </button>
                        <button class="btn btn-success" onclick="testCacheLimit(120)">
                            120 Dakika Limit
                        </button>
                        <div id="testResults" class="mt-3"></div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card card-outline card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Ana Sayfa Test</h3>
                            </div>
                            <div class="card-body">
                                <p>Ana analytics sayfasında otomatik cache kontrolü aktif:</p>
                                <a href="{{ route('admin.analytics.index') }}" class="btn btn-lg btn-primary">
                                    <i class="fas fa-chart-bar"></i> Ana Analytics Sayfasına Git
                                </a>
                                <small class="form-text text-muted">
                                    Sayfa yüklenirken cache 60 dakikadan eski ise otomatik temizlenecek ve sayfa yenilenecek.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
function checkCacheStatus() {
    $('#cacheStatus').html('<i class="fas fa-spinner fa-spin"></i> Kontrol ediliyor...');
    
    $.get('/admin/analytics/widget-data')
        .done(function(response) {
            if (response.stats && response.stats.cached_at) {
                $('#cacheStatus').html(`
                    <div class="alert alert-success">
                        <strong>Cache Mevcut:</strong><br>
                        Son güncelleme: ${response.stats.cached_at}<br>
                        Toplam kullanıcı: ${response.stats.total_users.toLocaleString()}<br>
                        Toplam sayfa görüntüleme: ${response.stats.total_pageviews.toLocaleString()}
                    </div>
                `);
            } else {
                $('#cacheStatus').html(`
                    <div class="alert alert-warning">
                        <strong>Cache bulunamadı veya eksik bilgi</strong>
                    </div>
                `);
            }
        })
        .fail(function() {
            $('#cacheStatus').html(`
                <div class="alert alert-danger">
                    <strong>Cache kontrol hatası</strong>
                </div>
            `);
        });
}

function testCacheLimit(minutes) {
    $('#testResults').html(`<i class="fas fa-spinner fa-spin"></i> ${minutes} dakika limit testi yapılıyor...`);
    
    // Simüle edilmiş test - gerçek API endpoint'i yok
    setTimeout(() => {
        $('#testResults').html(`
            <div class="alert alert-info">
                <strong>${minutes} Dakika Limit Testi:</strong><br>
                Bu bir simülasyon. Gerçek testte cache yaşı kontrol edilir ve gerekirse temizlenir.<br>
                <small>Ana sayfada gerçek test yapılabilir.</small>
            </div>
        `);
    }, 1000);
}

// Sayfa yüklendiğinde cache durumunu kontrol et
$(document).ready(function() {
    checkCacheStatus();
});
</script>
@stop 