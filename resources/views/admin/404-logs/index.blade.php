@extends('adminlte::page')

@section('title', '404 Takip Yönetimi')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>404 Takip Yönetimi</h1>
        <div>
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#bulkActionsModal">
                <i class="fas fa-tasks"></i> Toplu İşlemler
            </button>
        </div>
    </div>
@stop

@section('content')
    <!-- İstatistik Kartları -->
    <div class="row mb-3">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $stats['total_404s'] }}</h3>
                    <p>Toplam 404 Hatası</p>
                </div>
                <div class="icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $stats['unresolved_404s'] }}</h3>
                    <p>Çözülmemiş 404'ler</p>
                </div>
                <div class="icon">
                    <i class="fas fa-question-circle"></i>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $stats['today_404s'] }}</h3>
                    <p>Bugünkü 404'ler</p>
                </div>
                <div class="icon">
                    <i class="fas fa-calendar-day"></i>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $stats['top_404s']->count() }}</h3>
                    <p>En Çok Hit Alan</p>
                </div>
                <div class="icon">
                    <i class="fas fa-fire"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtreleme Formu -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Filtreler</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.404-logs.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>URL Arama</label>
                            <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="URL ara...">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Durum</label>
                            <select name="status" class="form-control">
                                <option value="">Tümü</option>
                                <option value="unresolved" {{ request('status') == 'unresolved' ? 'selected' : '' }}>Çözülmemiş</option>
                                <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Çözülmüş</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Başlangıç Tarihi</label>
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Bitiş Tarihi</label>
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div class="d-flex">
                                <button type="submit" class="btn btn-primary mr-2">
                                    <i class="fas fa-search"></i> Filtrele
                                </button>
                                <a href="{{ route('admin.404-logs.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Temizle
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- 404 Logları Tablosu -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">404 Logları ({{ $logs->total() }} kayıt)</h3>
        </div>
        <div class="card-body p-0">
            @if($logs->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th width="30">
                                    <input type="checkbox" id="select-all">
                                </th>
                                <th>
                                    <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'url', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}">
                                        URL
                                        @if(request('sort_by') == 'url')
                                            <i class="fas fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'hit_count', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}">
                                        Hit Sayısı
                                        @if(request('sort_by') == 'hit_count')
                                            <i class="fas fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'last_seen_at', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}">
                                        Son Görülme
                                        @if(request('sort_by') == 'last_seen_at')
                                            <i class="fas fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>Referer</th>
                                <th>Durum</th>
                                <th width="200">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($logs as $log)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="log-checkbox" value="{{ $log->id }}">
                                    </td>
                                    <td>
                                        <code>{{ $log->url }}</code>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $log->hit_count > 10 ? 'danger' : ($log->hit_count > 5 ? 'warning' : 'info') }}">
                                            {{ $log->hit_count }}
                                        </span>
                                    </td>
                                    <td>
                                        <small>{{ $log->last_seen_at->format('d.m.Y H:i') }}</small>
                                    </td>
                                    <td>
                                        @if($log->referer)
                                            <small class="text-muted">{{ Str::limit($log->referer, 30) }}</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($log->is_resolved)
                                            <span class="badge badge-success">Çözülmüş</span>
                                        @else
                                            <span class="badge badge-warning">Çözülmemiş</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.404-logs.show', $log) }}" class="btn btn-sm btn-info" title="Detay">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if(!$log->is_resolved)
                                                <a href="{{ route('admin.redirects.create', ['from_url' => $log->url]) }}" class="btn btn-sm btn-success" title="Yönlendir">
                                                    <i class="fas fa-share"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-warning resolve-btn" data-id="{{ $log->id }}" title="Çözüldü İşaretle">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            @endif
                                            <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="{{ $log->id }}" title="Sil">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="card-footer">
                    {{ $logs->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h5>404 log kaydı bulunamadı</h5>
                    <p class="text-muted">Henüz hiç 404 hatası kaydedilmemiş veya filtrelere uygun kayıt yok.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Toplu İşlemler Modal -->
    <div class="modal fade" id="bulkActionsModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Toplu İşlemler</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Seçili kayıtlar için yapmak istediğiniz işlemi seçin:</p>
                    <div class="form-group">
                        <button type="button" class="btn btn-warning btn-block" id="bulk-resolve">
                            <i class="fas fa-check"></i> Çözüldü Olarak İşaretle
                        </button>
                        <button type="button" class="btn btn-danger btn-block" id="bulk-delete">
                            <i class="fas fa-trash"></i> Seçilenleri Sil
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .table code {
            background-color: #f8f9fa;
            padding: 2px 4px;
            border-radius: 3px;
            font-size: 0.875em;
        }
        .small-box .inner h3 {
            font-size: 2.2rem;
        }
    </style>
@stop

@section('js')
<script>
$(document).ready(function() {
    // Select all checkbox
    $('#select-all').change(function() {
        $('.log-checkbox').prop('checked', $(this).prop('checked'));
    });

    // Resolve single log
    $('.resolve-btn').click(function() {
        const id = $(this).data('id');
        const btn = $(this);
        
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

    // Delete single log
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
                    location.reload();
                }
            })
            .fail(function() {
                toastr.error('Bir hata oluştu!');
            });
        }
    });

    // Bulk resolve
    $('#bulk-resolve').click(function() {
        const selectedIds = $('.log-checkbox:checked').map(function() {
            return $(this).val();
        }).get();

        if (selectedIds.length === 0) {
            toastr.warning('Lütfen en az bir kayıt seçin!');
            return;
        }

        if (confirm(`${selectedIds.length} adet kayıt çözüldü olarak işaretlensin mi?`)) {
            $.post('{{ route('admin.404-logs.bulk-resolve') }}', {
                _token: '{{ csrf_token() }}',
                ids: selectedIds
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

    // Bulk delete
    $('#bulk-delete').click(function() {
        const selectedIds = $('.log-checkbox:checked').map(function() {
            return $(this).val();
        }).get();

        if (selectedIds.length === 0) {
            toastr.warning('Lütfen en az bir kayıt seçin!');
            return;
        }

        if (confirm(`${selectedIds.length} adet kayıt silinsin mi? Bu işlem geri alınamaz!`)) {
            $.post('{{ route('admin.404-logs.bulk-delete') }}', {
                _token: '{{ csrf_token() }}',
                ids: selectedIds
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
});
</script>
@stop 