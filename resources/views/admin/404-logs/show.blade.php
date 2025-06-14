@extends('adminlte::page')

@section('title', '404 Log Detayı')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>404 Log Detayı</h1>
        <div>
            <a href="{{ route('admin.404-logs.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Geri Dön
            </a>
            @if(!$notFoundLog->is_resolved)
                <a href="{{ route('admin.redirects.create', ['from_url' => $notFoundLog->url]) }}" class="btn btn-success">
                    <i class="fas fa-share"></i> Yönlendirme Oluştur
                </a>
            @endif
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <!-- Ana Bilgiler -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">URL Bilgileri</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th width="150">URL:</th>
                            <td><code>{{ $notFoundLog->url }}</code></td>
                        </tr>
                        <tr>
                            <th>Temiz URL:</th>
                            <td><code>{{ $notFoundLog->clean_url }}</code></td>
                        </tr>
                        <tr>
                            <th>Hit Sayısı:</th>
                            <td>
                                <span class="badge badge-{{ $notFoundLog->hit_count > 10 ? 'danger' : ($notFoundLog->hit_count > 5 ? 'warning' : 'info') }} badge-lg">
                                    {{ $notFoundLog->hit_count }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>İlk Görülme:</th>
                            <td>{{ $notFoundLog->first_seen_at->format('d.m.Y H:i:s') }}</td>
                        </tr>
                        <tr>
                            <th>Son Görülme:</th>
                            <td>{{ $notFoundLog->last_seen_at->format('d.m.Y H:i:s') }}</td>
                        </tr>
                        <tr>
                            <th>Durum:</th>
                            <td>
                                @if($notFoundLog->is_resolved)
                                    <span class="badge badge-success">Çözülmüş</span>
                                @else
                                    <span class="badge badge-warning">Çözülmemiş</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Referer Bilgileri -->
            @if($notFoundLog->referer)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Referer Bilgisi</h3>
                </div>
                <div class="card-body">
                    <p><strong>Kaynak URL:</strong></p>
                    <code>{{ $notFoundLog->referer }}</code>
                    <br><br>
                    <a href="{{ $notFoundLog->referer }}" target="_blank" class="btn btn-sm btn-info">
                        <i class="fas fa-external-link-alt"></i> Kaynak Sayfayı Aç
                    </a>
                </div>
            </div>
            @endif

            <!-- User Agent Bilgileri -->
            @if($notFoundLog->user_agent)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tarayıcı Bilgisi</h3>
                </div>
                <div class="card-body">
                    <small class="text-muted">{{ $notFoundLog->user_agent }}</small>
                </div>
            </div>
            @endif

            <!-- Hit Geçmişi Grafiği -->
            @if($hitHistory->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Son 30 Günlük Hit Geçmişi</h3>
                </div>
                <div class="card-body">
                    <canvas id="hitChart" height="100"></canvas>
                </div>
            </div>
            @endif
        </div>

        <!-- Yan Panel -->
        <div class="col-md-4">
            <!-- Hızlı İşlemler -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Hızlı İşlemler</h3>
                </div>
                <div class="card-body">
                    @if(!$notFoundLog->is_resolved)
                        <button type="button" class="btn btn-warning btn-block resolve-btn" data-id="{{ $notFoundLog->id }}">
                            <i class="fas fa-check"></i> Çözüldü Olarak İşaretle
                        </button>
                        <a href="{{ route('admin.redirects.create', ['from_url' => $notFoundLog->url]) }}" class="btn btn-success btn-block">
                            <i class="fas fa-share"></i> Yönlendirme Oluştur
                        </a>
                    @endif
                    
                    <button type="button" class="btn btn-danger btn-block delete-btn" data-id="{{ $notFoundLog->id }}">
                        <i class="fas fa-trash"></i> Bu Kaydı Sil
                    </button>
                    
                    <hr>
                    
                    <a href="{{ url($notFoundLog->url) }}" target="_blank" class="btn btn-info btn-block">
                        <i class="fas fa-external-link-alt"></i> URL'yi Test Et
                    </a>
                </div>
            </div>

            <!-- IP Bilgisi -->
            @if($notFoundLog->ip_address)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">IP Bilgisi</h3>
                </div>
                <div class="card-body">
                    <p><strong>IP Adresi:</strong></p>
                    <code>{{ $notFoundLog->ip_address }}</code>
                    <br><br>
                    <a href="https://whatismyipaddress.com/ip/{{ $notFoundLog->ip_address }}" target="_blank" class="btn btn-sm btn-info">
                        <i class="fas fa-search"></i> IP Sorgula
                    </a>
                </div>
            </div>
            @endif

            <!-- Öneriler -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Öneriler</h3>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li><i class="fas fa-lightbulb text-warning"></i> Benzer URL'ler için yönlendirme oluşturun</li>
                        <li><i class="fas fa-lightbulb text-warning"></i> Sık erişilen 404'ler için içerik oluşturun</li>
                        <li><i class="fas fa-lightbulb text-warning"></i> Sitemap'inizi güncelleyin</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .badge-lg {
            font-size: 1rem;
            padding: 0.5rem 0.75rem;
        }
        code {
            background-color: #f8f9fa;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.9em;
            word-break: break-all;
        }
    </style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Resolve button
    $('.resolve-btn').click(function() {
        const id = $(this).data('id');
        
        if (confirm('Bu URL çözüldü olarak işaretlensin mi?')) {
            $.post(`{{ route('admin.404-logs.resolve', '') }}/${id}`, {
                _token: '{{ csrf_token() }}'
            })
            .done(function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    location.reload();
                }
            })
            .fail(function() {
                toastr.error('Bir hata oluştu!');
            });
        }
    });

    // Delete button
    $('.delete-btn').click(function() {
        const id = $(this).data('id');
        
        if (confirm('Bu 404 log kaydı silinsin mi?')) {
            $.ajax({
                url: `{{ route('admin.404-logs.destroy', '') }}/${id}`,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                }
            })
            .done(function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    window.location.href = '{{ route('admin.404-logs.index') }}';
                }
            })
            .fail(function() {
                toastr.error('Bir hata oluştu!');
            });
        }
    });

    // Hit History Chart
    @if($hitHistory->count() > 0)
    const ctx = document.getElementById('hitChart').getContext('2d');
    const hitChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($hitHistory->pluck('date')->map(function($date) { return \Carbon\Carbon::parse($date)->format('d.m'); })) !!},
            datasets: [{
                label: 'Hit Sayısı',
                data: {!! json_encode($hitHistory->pluck('hits')) !!},
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
    @endif
});
</script>
@stop 