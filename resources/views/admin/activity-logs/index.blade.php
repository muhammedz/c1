@extends('adminlte::page')

@section('title', 'Activity Logs')

@section('plugins.Toastr', true)

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Activity Logs</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Activity Logs</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <!-- Filtreler -->
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-filter"></i>
                Filtreler
            </h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="user_id">Kullanıcı</label>
                        <select name="user_id" id="user_id" class="form-control">
                            <option value="">Tüm Kullanıcılar</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="model_type">Model Türü</label>
                        <select name="model_type" id="model_type" class="form-control">
                            <option value="">Tüm Modeller</option>
                            @foreach($modelTypes as $modelType)
                                <option value="{{ $modelType }}" {{ request('model_type') == $modelType ? 'selected' : '' }}>
                                    {{ class_basename($modelType) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="action">İşlem</label>
                        <select name="action" id="action" class="form-control">
                            <option value="">Tüm İşlemler</option>
                            @foreach($actions as $action)
                                <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                    {{ ucfirst($action) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="date_from">Başlangıç Tarihi</label>
                        <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>
                </div>
                
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="date_to">Bitiş Tarihi</label>
                        <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>
                </div>
                
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="search">Arama</label>
                        <input type="text" name="search" id="search" class="form-control" placeholder="Açıklama, kullanıcı adı..." value="{{ request('search') }}">
                    </div>
                </div>
                
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Filtrele
                    </button>
                    <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Temizle
                    </a>
                    <button type="button" class="btn btn-info" id="btn-stats">
                        <i class="fas fa-chart-bar"></i> İstatistikler
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Activity Logs Listesi -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-list"></i>
                Activity Logs ({{ $activityLogs->total() }} kayıt)
            </h3>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Kullanıcı</th>
                        <th>Model</th>
                        <th>İşlem</th>
                        <th>Açıklama</th>
                        <th>IP Adresi</th>
                        <th>Tarih</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activityLogs as $log)
                        <tr>
                            <td>{{ $log->id }}</td>
                            <td>
                                @if($log->user)
                                    <span class="badge badge-primary">{{ $log->user->name }}</span>
                                @else
                                    <span class="badge badge-secondary">{{ $log->user_name ?? 'Sistem' }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-info">{{ $log->getModelNameInTurkish() }}</span>
                                @if($log->model_id)
                                    <small class="text-muted">#{{ $log->model_id }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge 
                                    @if($log->action == 'created') badge-success
                                    @elseif($log->action == 'updated') badge-warning
                                    @elseif($log->action == 'deleted') badge-danger
                                    @elseif($log->action == 'restored') badge-info
                                    @else badge-secondary
                                    @endif
                                ">
                                    {{ $log->getActionInTurkish() }}
                                </span>
                            </td>
                            <td>
                                <span title="{{ $log->getFormattedDescription() }}">
                                    {{ Str::limit($log->getFormattedDescription(), 50) }}
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">{{ $log->ip_address }}</small>
                            </td>
                            <td>
                                <small>{{ $log->created_at->format('d.m.Y H:i') }}</small>
                            </td>
                            <td>
                                <a href="{{ route('admin.activity-logs.show', $log) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    Henüz activity log kaydı bulunmuyor.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
                </div>
        @if($activityLogs->hasPages())
            <div class="card-footer text-center">
                <div class="btn-group" role="group">
                    @if($activityLogs->previousPageUrl())
                        <a href="{{ $activityLogs->appends(request()->query())->previousPageUrl() }}" class="btn btn-outline-primary">
                            <i class="fas fa-chevron-left"></i> Geri
                        </a>
                    @else
                        <button class="btn btn-outline-secondary" disabled>
                            <i class="fas fa-chevron-left"></i> Geri
                        </button>
                    @endif
                    
                    <span class="btn btn-light">
                        Sayfa {{ $activityLogs->currentPage() }} / {{ $activityLogs->lastPage() }}
                    </span>
                    
                    @if($activityLogs->nextPageUrl())
                        <a href="{{ $activityLogs->appends(request()->query())->nextPageUrl() }}" class="btn btn-outline-primary">
                            İleri <i class="fas fa-chevron-right"></i>
                        </a>
                    @else
                        <button class="btn btn-outline-secondary" disabled>
                            İleri <i class="fas fa-chevron-right"></i>
                        </button>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>

<!-- İstatistikler Modal -->
<div class="modal fade" id="statsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Activity Log İstatistikleri</h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="stats-content">
                <div class="text-center">
                    <i class="fas fa-spinner fa-spin"></i> Yükleniyor...
                </div>
            </div>
        </div>
    </div>
</div>


@stop

@section('css')
<style>
.table td {
    vertical-align: middle;
}
.badge {
    font-size: 0.8em;
}

</style>
@stop

@section('js')
<script>
$(document).ready(function() {
    // İstatistikler butonu
    $('#btn-stats').click(function() {
        $('#statsModal').modal('show');
        
        $.ajax({
            url: '{{ route("admin.activity-logs.stats") }}',
            method: 'GET',
            data: { days: 7 },
            success: function(data) {
                let html = `
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-chart-line"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Toplam Aktivite</span>
                                    <span class="info-box-number">${data.total_activities}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-users"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Aktif Kullanıcı</span>
                                    <span class="info-box-number">${data.unique_users}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h5>İşlem Türlerine Göre</h5>
                            <ul class="list-group">
                `;
                
                data.activities_by_action.forEach(function(item) {
                    html += `<li class="list-group-item d-flex justify-content-between align-items-center">
                        ${item.action}
                        <span class="badge badge-primary badge-pill">${item.count}</span>
                    </li>`;
                });
                
                html += `</ul></div><div class="col-md-6"><h5>Model Türlerine Göre</h5><ul class="list-group">`;
                
                data.activities_by_model.forEach(function(item) {
                    let modelName = item.model_type.split('\\').pop();
                    html += `<li class="list-group-item d-flex justify-content-between align-items-center">
                        ${modelName}
                        <span class="badge badge-info badge-pill">${item.count}</span>
                    </li>`;
                });
                
                html += `</ul></div></div>`;
                
                $('#stats-content').html(html);
            },
            error: function() {
                $('#stats-content').html('<div class="alert alert-danger">İstatistikler yüklenirken hata oluştu.</div>');
            }
        });
    });

});
</script>
@stop 