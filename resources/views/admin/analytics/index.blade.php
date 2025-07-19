@extends('adminlte::page')

@section('title', 'Web Sitesi İstatistikleri')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1><i class="fas fa-chart-line mr-2"></i>Web Sitesi İstatistikleri</h1>
            @if(isset($cacheCheck))
                <small class="text-muted">
                    <i class="fas fa-info-circle"></i> 
                    {{ $cacheCheck['reason'] }}
                    @if($cacheCheck['age'] > 0)
                        ({{ $cacheCheck['age'] }} dakika eski)
                    @endif
                </small>
            @endif
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">İstatistikler</li>
            </ol>
        </div>
    </div>

    @if(session('cache_refreshed'))
        <div class="alert alert-success alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <i class="fas fa-sync-alt"></i> <strong>Cache Yenilendi!</strong> {{ session('cache_refreshed') }}
        </div>
    @endif
@stop

@section('content')
    <!-- İstatistik Kartları -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ number_format($stats['today_users']) }}</h3>
                    <p>Bugünkü Ziyaretçiler</p>
                    <small class="text-sm">{{ number_format($stats['today_pageviews']) }} sayfa görüntüleme</small>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ number_format($stats['this_week_users']) }}</h3>
                    <p>Bu Hafta Toplam</p>
                    <small class="text-sm">
                        @if($stats['week_growth'] > 0)
                            <i class="fas fa-arrow-up text-white"></i> +{{ $stats['week_growth'] }}%
                        @elseif($stats['week_growth'] < 0)
                            <i class="fas fa-arrow-down text-white"></i> {{ $stats['week_growth'] }}%
                        @else
                            <i class="fas fa-minus text-white"></i> {{ $stats['week_growth'] }}%
                        @endif
                        geçen haftaya göre
                    </small>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ number_format($stats['total_users']) }}</h3>
                    <p>Son 30 Gün Toplam</p>
                    <small class="text-sm">{{ number_format($stats['total_pageviews']) }} sayfa görüntüleme</small>
                </div>
                <div class="icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3><i class="fas fa-sync-alt fa-spin"></i></h3>
                    <p>Son Güncelleme</p>
                    <small class="text-sm">{{ $stats['cached_at'] }}</small>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
                <button class="small-box-footer btn btn-sm" onclick="clearAnalyticsCache()">
                    Cache Temizle <i class="fas fa-sync"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Günlük Ziyaretçi Grafiği -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-area mr-2"></i>Son 30 Gün Ziyaretçi Trendi</h3>
                    <div class="card-tools">
                        <select class="form-control form-control-sm" id="dayFilter" onchange="updateChart()">
                            <option value="7">Son 7 Gün</option>
                            <option value="30" selected>Son 30 Gün</option>
                            <option value="90">Son 90 Gün</option>
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="visitorsChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Cihaz Türü Dağılımı -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-mobile-alt mr-2"></i>Cihaz Türü Dağılımı</h3>
                </div>
                <div class="card-body">
                    <canvas id="deviceChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- En Popüler Sayfalar -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-star mr-2"></i>En Popüler Sayfalar</h3>
                    <div class="card-tools">
                        <select class="form-control form-control-sm" id="pageLimit" onchange="updateTopPages()">
                            <option value="5">İlk 5</option>
                            <option value="10" selected>İlk 10</option>
                            <option value="20">İlk 20</option>
                        </select>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped" id="topPagesTable">
                            <thead>
                                <tr>
                                    <th>Sayfa</th>
                                    <th class="text-center">Görüntülenme</th>
                                    <th class="text-center">Ziyaretçi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topPages as $page)
                                    <tr>
                                        <td>
                                            <strong>{{ \Str::limit($page['title'], 40) }}</strong><br>
                                            <small class="text-muted">{{ \Str::limit($page['url'], 50) }}</small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-primary">{{ number_format($page['pageviews']) }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-success">{{ number_format($page['users']) }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">Veri bulunamadı</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Trafik Kaynakları -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-external-link-alt mr-2"></i>Trafik Kaynakları</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="description-block border-right">
                                <span class="description-percentage text-success">
                                    <i class="fas fa-home"></i>
                                </span>
                                <h5 class="description-header">{{ $trafficSources['direct'] ?? 0 }}</h5>
                                <span class="description-text">DOĞRUDAN</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="description-block">
                                <span class="description-percentage text-info">
                                    <i class="fab fa-google"></i>
                                </span>
                                <h5 class="description-header">{{ $trafficSources['search'] ?? 0 }}</h5>
                                <span class="description-text">ARAMA</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="description-block border-right">
                                <span class="description-percentage text-warning">
                                    <i class="fab fa-facebook"></i>
                                </span>
                                <h5 class="description-header">{{ $trafficSources['social'] ?? 0 }}</h5>
                                <span class="description-text">SOSYAL MEDYA</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="description-block">
                                <span class="description-percentage text-secondary">
                                    <i class="fas fa-link"></i>
                                </span>
                                <h5 class="description-header">{{ $trafficSources['referral'] ?? 0 }}</h5>
                                <span class="description-text">DİĞER SİTELER</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hızlı Linkler -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-external-link-alt mr-2"></i>Hızlı Linkler</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <a href="{{ route('admin.analytics.reports') }}" class="btn btn-block btn-outline-primary">
                                <i class="fas fa-chart-bar mr-2"></i>Detaylı Raporlar
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('admin.analytics.settings') }}" class="btn btn-block btn-outline-warning">
                                <i class="fas fa-sliders-h mr-2"></i>Ayarlar
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="https://analytics.google.com" target="_blank" class="btn btn-block btn-outline-danger">
                                <i class="fab fa-google mr-2"></i>Google Analytics
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .small-box .small-box-footer {
            background: rgba(0,0,0,0.1);
            color: inherit;
            border: none;
            padding: 3px 0;
            width: 100%;
        }
        .small-box .small-box-footer:hover {
            background: rgba(0,0,0,0.2);
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Grafik verileri
        const dailyVisitorsData = @json($dailyVisitors);
        const deviceTypesData = @json($deviceTypes);
        
        // Ziyaretçi grafiği
        let visitorsChart;
        
        function initVisitorsChart(data) {
            const ctx = document.getElementById('visitorsChart').getContext('2d');
            
            if (visitorsChart) {
                visitorsChart.destroy();
            }
            
            visitorsChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.map(item => item.date),
                    datasets: [
                        {
                            label: 'Ziyaretçiler',
                            data: data.map(item => item.users),
                            borderColor: 'rgb(54, 162, 235)',
                            backgroundColor: 'rgba(54, 162, 235, 0.1)',
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: 'Sayfa Görüntüleme',
                            data: data.map(item => item.pageviews),
                            borderColor: 'rgb(75, 192, 192)',
                            backgroundColor: 'rgba(75, 192, 192, 0.1)',
                            tension: 0.4,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
        
        // Cihaz türü grafiği
        function initDeviceChart() {
            const ctx = document.getElementById('deviceChart').getContext('2d');
            
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Masaüstü', 'Mobil', 'Tablet'],
                    datasets: [{
                        data: [
                            deviceTypesData.desktop || 0,
                            deviceTypesData.mobile || 0,
                            deviceTypesData.tablet || 0
                        ],
                        backgroundColor: [
                            'rgb(54, 162, 235)',
                            'rgb(255, 99, 132)',
                            'rgb(255, 205, 86)'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
        
        // Sayfa yüklendiğinde grafikleri başlat
        document.addEventListener('DOMContentLoaded', function() {
            initVisitorsChart(dailyVisitorsData);
            initDeviceChart();
        });
        
        // Grafik güncelleme
        function updateChart() {
            const days = document.getElementById('dayFilter').value;
            
            fetch(`{{ route('admin.analytics.daily-visitors') }}?days=${days}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        initVisitorsChart(data.data);
                    }
                })
                .catch(error => console.error('Grafik güncelleme hatası:', error));
        }
        
        // Popüler sayfalar güncelleme
        function updateTopPages() {
            const limit = document.getElementById('pageLimit').value;
            
            fetch(`{{ route('admin.analytics.top-pages') }}?limit=${limit}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        updateTopPagesTable(data.data);
                    }
                })
                .catch(error => console.error('Sayfa listesi güncelleme hatası:', error));
        }
        
        // Tablo güncelleme
        function updateTopPagesTable(pages) {
            const tbody = document.querySelector('#topPagesTable tbody');
            tbody.innerHTML = '';
            
            if (pages.length === 0) {
                tbody.innerHTML = '<tr><td colspan="3" class="text-center text-muted">Veri bulunamadı</td></tr>';
                return;
            }
            
            pages.forEach(page => {
                const row = `
                    <tr>
                        <td>
                            <strong>${page.title.substring(0, 40)}${page.title.length > 40 ? '...' : ''}</strong><br>
                            <small class="text-muted">${page.url.substring(0, 50)}${page.url.length > 50 ? '...' : ''}</small>
                        </td>
                        <td class="text-center">
                            <span class="badge badge-primary">${page.pageviews.toLocaleString()}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge badge-success">${page.users.toLocaleString()}</span>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        }
        
        // Cache temizleme
        function clearAnalyticsCache() {
            if (!confirm('Analytics cache temizlensin mi? Bu işlem biraz zaman alabilir.')) {
                return;
            }
            
            fetch('{{ route('admin.analytics.clear-cache') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert(data.message);
                    location.reload();
                } else {
                    alert('Hata: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Cache temizleme hatası:', error);
                alert('Bir hata oluştu. Konsolu kontrol edin.');
            });
        }
    </script>
@stop 