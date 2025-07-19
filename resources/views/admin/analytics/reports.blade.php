@extends('adminlte::page')

@section('title', 'Analytics Raporları')

@section('content_header')
    <h1>📊 Analytics Raporları</h1>
@stop

@section('content')
<div class="row">
    <!-- Genel İstatistikler -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">📈 Detaylı İstatistikler</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-sm btn-primary" onclick="refreshReports()">
                        <i class="fas fa-sync"></i> Yenile
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Zaman Aralığı Seçici -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label>Zaman Aralığı:</label>
                        <select class="form-control" id="timeRange" onchange="updateReports()">
                            <option value="7">Son 7 Gün</option>
                            <option value="30" selected>Son 30 Gün</option>
                            <option value="90">Son 90 Gün</option>
                            <option value="365">Son 1 Yıl</option>
                        </select>
                    </div>
                </div>

                <!-- Metrik Kartları -->
                <div class="row" id="metricCards">
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3 id="totalUsers">-</h3>
                                <p>Toplam Kullanıcı</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3 id="totalPageviews">-</h3>
                                <p>Sayfa Görüntüleme</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-eye"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3 id="avgSessionDuration">-</h3>
                                <p>Ortalama Oturum</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3 id="bounceRate">-</h3>
                                <p>Çıkış Oranı</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="row">
    <!-- En Popüler Sayfalar -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">🏆 En Popüler Sayfalar</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Sayfa</th>
                            <th>Görüntüleme</th>
                            <th>%</th>
                        </tr>
                    </thead>
                    <tbody id="topPagesTable">
                        <tr>
                            <td colspan="3" class="text-center">
                                <i class="fas fa-spinner fa-spin"></i> Yükleniyor...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Trafik Kaynakları -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">🌐 Trafik Kaynakları</h3>
            </div>
            <div class="card-body">
                <div id="trafficSourcesData">
                    <div class="progress-group">
                        <span class="progress-text">Doğrudan</span>
                        <span class="float-right"><b id="directCount">0</b></span>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-primary" id="directBar" style="width: 0%"></div>
                        </div>
                    </div>
                    
                    <div class="progress-group">
                        <span class="progress-text">Arama Motorları</span>
                        <span class="float-right"><b id="searchCount">0</b></span>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-success" id="searchBar" style="width: 0%"></div>
                        </div>
                    </div>
                    
                    <div class="progress-group">
                        <span class="progress-text">Sosyal Medya</span>
                        <span class="float-right"><b id="socialCount">0</b></span>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-warning" id="socialBar" style="width: 0%"></div>
                        </div>
                    </div>
                    
                    <div class="progress-group">
                        <span class="progress-text">Diğer Siteler</span>
                        <span class="float-right"><b id="referralCount">0</b></span>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-danger" id="referralBar" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cihaz Dağılımı -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">📱 Cihaz Türleri</h3>
            </div>
            <div class="card-body">
                <canvas id="deviceChart"></canvas>
            </div>
        </div>
    </div>
</div>


@stop

@section('css')
<style>
.metric-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 10px;
    color: white;
    padding: 20px;
    margin-bottom: 20px;
}

.metric-card h3 {
    font-size: 2.5rem;
    font-weight: bold;
    margin-bottom: 5px;
}

.metric-card p {
    margin: 0;
    opacity: 0.8;
}

.progress-group {
    margin-bottom: 15px;
}

.progress {
    height: 10px;
}

.card {
    box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
    border: none;
}

.small-box .inner h3 {
    font-size: 2.2rem;
    font-weight: bold;
}
</style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let deviceChart = null;

// Sayfa yüklendiğinde
$(document).ready(function() {
    loadReportsData();
});

// Raporları yenile
function refreshReports() {
    loadReportsData();
}

// Zaman aralığı değiştiğinde
function updateReports() {
    loadReportsData();
}

// Ana veri yükleme fonksiyonu
function loadReportsData() {
    showLoading();
    console.log('Loading reports data...');
    
    const timeRange = $('#timeRange').val();
    
    // Genel istatistikleri yükle
    $.get('{{ route("admin.analytics.widget-data") }}')
        .done(function(data) {
            console.log('Widget data loaded:', data);
            updateMetricCards(data);
        })
        .fail(function(xhr, status, error) {
            console.error('Widget data failed:', xhr.responseText);
            showError('Genel istatistikler yüklenemedi: ' + error);
        });
    

    
    // Cihaz türlerini yükle
    loadDeviceData();
    
    // En popüler sayfaları yükle
    $.get('{{ route("admin.analytics.top-pages") }}')
        .done(function(response) {
            updateTopPagesTable(response.data || response);
        })
        .fail(function() {
            showError('Popüler sayfalar yüklenemedi');
        });
    
    // Trafik kaynaklarını yükle
    loadTrafficSources();
}

// Metrik kartlarını güncelle
function updateMetricCards(data) {
    $('#totalUsers').text(data.stats?.total_users || 0);
    $('#totalPageviews').text(data.stats?.total_pageviews || 0);
    $('#avgSessionDuration').text('2:30'); // Placeholder
    $('#bounceRate').text('65%'); // Placeholder
}



// Cihaz verilerini yükle
function loadDeviceData() {
    // Placeholder - cihaz verisi endpoint'i eklenebilir
    const deviceData = {
        labels: ['Desktop', 'Mobile', 'Tablet'],
        data: [60, 35, 5]
    };
    updateDeviceChart(deviceData);
}

// Cihaz grafiğini güncelle
function updateDeviceChart(data) {
    const ctx = document.getElementById('deviceChart').getContext('2d');
    
    if (deviceChart) {
        deviceChart.destroy();
    }
    
    deviceChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: data.labels,
            datasets: [{
                data: data.data,
                backgroundColor: [
                    '#3498db',
                    '#e74c3c',
                    '#f39c12'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
}

// En popüler sayfalar tablosunu güncelle
function updateTopPagesTable(data) {
    let html = '';
    const total = data.reduce((sum, page) => sum + page.pageviews, 0);
    
    data.forEach(page => {
        const percentage = total > 0 ? ((page.pageviews / total) * 100).toFixed(1) : 0;
        const title = page.title.length > 40 ? page.title.substring(0, 40) + '...' : page.title;
        
        html += `
            <tr>
                <td>
                    <strong>${title}</strong><br>
                    <small class="text-muted">${page.url}</small>
                </td>
                <td><span class="badge badge-info">${page.pageviews}</span></td>
                <td>${percentage}%</td>
            </tr>
        `;
    });
    
    $('#topPagesTable').html(html || '<tr><td colspan="3" class="text-center">Veri bulunamadı</td></tr>');
}

// Trafik kaynaklarını yükle
function loadTrafficSources() {
    $.get('{{ route("admin.analytics.traffic-sources") }}')
        .done(function(data) {
            updateTrafficSources(data);
        })
        .fail(function() {
            // Hata durumunda varsayılan veriler
            const sources = {
                direct: 0,
                search: 0,
                social: 0,
                referral: 0
            };
            updateTrafficSources(sources);
            showError('Trafik kaynakları yüklenemedi');
        });
}

// Trafik kaynaklarını güncelle
function updateTrafficSources(sources) {
    const total = sources.direct + sources.search + sources.social + sources.referral;
    
    if (total > 0) {
        $('#directCount').text(sources.direct);
        $('#searchCount').text(sources.search);
        $('#socialCount').text(sources.social);
        $('#referralCount').text(sources.referral);
        
        $('#directBar').css('width', ((sources.direct / total) * 100) + '%');
        $('#searchBar').css('width', ((sources.search / total) * 100) + '%');
        $('#socialBar').css('width', ((sources.social / total) * 100) + '%');
        $('#referralBar').css('width', ((sources.referral / total) * 100) + '%');
    }
}

// Yükleme göster
function showLoading() {
    $('#metricCards').addClass('loading');
}

// Hata göster
function showError(message) {
    console.error('Analytics Error:', message);
    if (typeof toastr !== 'undefined') {
        toastr.error(message);
    } else {
        alert('Hata: ' + message);
    }
}
</script>
@stop 