@extends('adminlte::page')

@section('title', 'Sayfa Geri Bildirimleri')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Sayfa Geri Bildirimleri</h1>
        <div>
            <a href="{{ route('admin.page-feedback.export') }}" class="btn btn-success">
                <i class="fas fa-download"></i> Excel'e Aktar
            </a>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <!-- Genel İstatistikler -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ number_format($generalStats['total_feedbacks']) }}</h3>
                    <p>Toplam Geri Bildirim</p>
                </div>
                <div class="icon">
                    <i class="fas fa-comments"></i>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ number_format($generalStats['helpful_count']) }}</h3>
                    <p>Yardımcı Oldu</p>
                </div>
                <div class="icon">
                    <i class="fas fa-thumbs-up"></i>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ number_format($generalStats['not_helpful_count']) }}</h3>
                    <p>Yardımcı Olmadı</p>
                </div>
                <div class="icon">
                    <i class="fas fa-thumbs-down"></i>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-secondary">
                <div class="inner">
                    <h3>{{ number_format($generalStats['total_pages']) }}</h3>
                    <p>Geri Bildirim Alan Sayfa</p>
                </div>
                <div class="icon">
                    <i class="fas fa-file-alt"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Başarı Oranı Grafiği -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Genel Başarı Oranı</h3>
                </div>
                <div class="card-body">
                    <canvas id="successChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Aylık Trend (Son 6 Ay)</h3>
                </div>
                <div class="card-body">
                    <canvas id="trendChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Sayfa Bazlı İstatistikler -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Sayfa Bazlı Geri Bildirim İstatistikleri</h3>
                    <div class="card-tools">
                        <form action="{{ route('admin.page-feedback.index') }}" method="GET" class="form-inline">
                            <div class="input-group input-group-sm" style="width: 200px;">
                                <input type="text" name="search" class="form-control" placeholder="Sayfa ara..." value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>
                                    <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'page_title', 'sort_direction' => request('sort_direction') === 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none">
                                        Sayfa Başlığı
                                        @if(request('sort_by') === 'page_title')
                                            <i class="fas fa-sort-{{ request('sort_direction') === 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'total_feedbacks', 'sort_direction' => request('sort_direction') === 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none">
                                        Toplam Geri Bildirim
                                        @if(request('sort_by') === 'total_feedbacks' || !request('sort_by'))
                                            <i class="fas fa-sort-{{ request('sort_direction') === 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'helpful_count', 'sort_direction' => request('sort_direction') === 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none">
                                        Yardımcı Oldu
                                        @if(request('sort_by') === 'helpful_count')
                                            <i class="fas fa-sort-{{ request('sort_direction') === 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'not_helpful_count', 'sort_direction' => request('sort_direction') === 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none">
                                        Yardımcı Olmadı
                                        @if(request('sort_by') === 'not_helpful_count')
                                            <i class="fas fa-sort-{{ request('sort_direction') === 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'helpful_percentage', 'sort_direction' => request('sort_direction') === 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none">
                                        Başarı Oranı
                                        @if(request('sort_by') === 'helpful_percentage')
                                            <i class="fas fa-sort-{{ request('sort_direction') === 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>Son Geri Bildirim</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pageStats as $stat)
                            <tr>
                                <td>
                                    <div class="d-flex flex-column">
                                        <strong>{{ Str::limit($stat->page_title, 50) }}</strong>
                                        <small class="text-muted">{{ Str::limit($stat->page_url, 80) }}</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-info">{{ $stat->total_feedbacks }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-success">{{ $stat->helpful_count }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-warning">{{ $stat->not_helpful_count }}</span>
                                </td>
                                <td>
                                    @php
                                        $percentage = $stat->helpful_percentage;
                                        $badgeClass = $percentage >= 70 ? 'success' : ($percentage >= 50 ? 'warning' : 'danger');
                                    @endphp
                                    <span class="badge badge-{{ $badgeClass }}">{{ $percentage }}%</span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($stat->last_feedback_at)->diffForHumans() }}
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.page-feedback.show', ['page_url' => $stat->page_url]) }}" 
                                           class="btn btn-sm btn-info" title="Detayları Görüntüle">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ $stat->page_url }}" target="_blank" 
                                           class="btn btn-sm btn-secondary" title="Sayfayı Görüntüle">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                onclick="confirmDelete('{{ $stat->page_url }}', '{{ $stat->page_title }}')"
                                                title="Geri Bildirimleri Sil">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-info-circle"></i>
                                        Henüz geri bildirim bulunmamaktadır.
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($pageStats->hasPages())
                <div class="card-footer clearfix">
                    {{ $pageStats->withQueryString()->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Son Geri Bildirimler -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Son Geri Bildirimler</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Sayfa</th>
                                <th>Geri Bildirim</th>
                                <th>IP Adresi</th>
                                <th>Tarih</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentFeedbacks as $feedback)
                            <tr>
                                <td>
                                    <div class="d-flex flex-column">
                                        <strong>{{ Str::limit($feedback->page_title, 40) }}</strong>
                                        <small class="text-muted">{{ Str::limit($feedback->page_url, 60) }}</small>
                                    </div>
                                </td>
                                <td>
                                    @if($feedback->is_helpful)
                                        <span class="badge badge-success">
                                            <i class="fas fa-thumbs-up"></i> Yardımcı oldu
                                        </span>
                                    @else
                                        <span class="badge badge-warning">
                                            <i class="fas fa-thumbs-down"></i> Yardımcı olmadı
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <code>{{ $feedback->user_ip }}</code>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        {{ $feedback->created_at->format('d.m.Y H:i') }}
                                    </small>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Silme Onay Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Geri Bildirimleri Sil</h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Bu sayfa için tüm geri bildirimleri silmek istediğinizden emin misiniz?</p>
                <p><strong id="pageTitle"></strong></p>
                <p class="text-danger">Bu işlem geri alınamaz!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">İptal</button>
                <form id="deleteForm" method="POST" action="{{ route('admin.page-feedback.destroy-page') }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="page_url" id="pageUrl">
                    <button type="submit" class="btn btn-danger">Sil</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .small-box .inner h3 {
        font-size: 2.2rem;
    }
    .table th a {
        color: #495057;
    }
    .table th a:hover {
        color: #007bff;
    }
</style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Başarı Oranı Grafiği
    const successCtx = document.getElementById('successChart').getContext('2d');
    new Chart(successCtx, {
        type: 'doughnut',
        data: {
            labels: ['Yardımcı Oldu', 'Yardımcı Olmadı'],
            datasets: [{
                data: [{{ $generalStats['helpful_count'] }}, {{ $generalStats['not_helpful_count'] }}],
                backgroundColor: ['#28a745', '#ffc107'],
                borderWidth: 0
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

    // Aylık Trend Grafiği
    const trendCtx = document.getElementById('trendChart').getContext('2d');
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: [
                @foreach($monthlyTrends as $trend)
                    '{{ \Carbon\Carbon::createFromFormat("Y-m", $trend->month)->format("M Y") }}',
                @endforeach
            ],
            datasets: [{
                label: 'Toplam Geri Bildirim',
                data: [
                    @foreach($monthlyTrends as $trend)
                        {{ $trend->total_feedbacks }},
                    @endforeach
                ],
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                tension: 0.4
            }]
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
});

function confirmDelete(pageUrl, pageTitle) {
    document.getElementById('pageUrl').value = pageUrl;
    document.getElementById('pageTitle').textContent = pageTitle;
    $('#deleteModal').modal('show');
}
</script>
@stop 