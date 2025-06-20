@extends('adminlte::page')

@section('title', 'Activity Log Detayı')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Activity Log Detayı</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.activity-logs.index') }}">Activity Logs</a></li>
                    <li class="breadcrumb-item active">Detay</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Ana Bilgiler -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle"></i>
                        Genel Bilgiler
                    </h3>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-3">ID:</dt>
                        <dd class="col-sm-9">{{ $activityLog->id }}</dd>
                        
                        <dt class="col-sm-3">Kullanıcı:</dt>
                        <dd class="col-sm-9">
                            @if($activityLog->user)
                                <span class="badge badge-primary">{{ $activityLog->user->name }}</span>
                                <small class="text-muted">({{ $activityLog->user->email }})</small>
                            @else
                                <span class="badge badge-secondary">{{ $activityLog->user_name ?? 'Sistem' }}</span>
                            @endif
                        </dd>
                        
                        <dt class="col-sm-3">Model:</dt>
                        <dd class="col-sm-9">
                            <span class="badge badge-info">{{ $activityLog->getModelNameInTurkish() }}</span>
                            @if($activityLog->model_id)
                                <small class="text-muted">(ID: {{ $activityLog->model_id }})</small>
                            @endif
                            <br>
                            <small class="text-muted">{{ $activityLog->model_type }}</small>
                        </dd>
                        
                        <dt class="col-sm-3">İşlem:</dt>
                        <dd class="col-sm-9">
                            <span class="badge 
                                @if($activityLog->action == 'created') badge-success
                                @elseif($activityLog->action == 'updated') badge-warning
                                @elseif($activityLog->action == 'deleted') badge-danger
                                @elseif($activityLog->action == 'restored') badge-info
                                @else badge-secondary
                                @endif
                            ">
                                {{ $activityLog->getActionInTurkish() }}
                            </span>
                        </dd>
                        
                        <dt class="col-sm-3">Açıklama:</dt>
                        <dd class="col-sm-9">{{ $activityLog->getFormattedDescription() }}</dd>
                        
                        <dt class="col-sm-3">Tarih:</dt>
                        <dd class="col-sm-9">
                            {{ $activityLog->created_at->format('d.m.Y H:i:s') }}
                            <small class="text-muted">({{ $activityLog->created_at->diffForHumans() }})</small>
                        </dd>
                    </dl>
                </div>
            </div>

            <!-- Değişiklikler -->
            @if($activityLog->old_values || $activityLog->new_values)
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-exchange-alt"></i>
                            Değişiklikler
                        </h3>
                    </div>
                    <div class="card-body">
                        @php
                            $changes = $activityLog->getChangesSummary();
                        @endphp
                        
                        @if(!empty($changes))
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Alan</th>
                                            <th>Eski Değer</th>
                                            <th>Yeni Değer</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($changes as $field => $change)
                                            <tr>
                                                <td><strong>{{ $field }}</strong></td>
                                                <td>
                                                    @if(is_null($change['old']))
                                                        <span class="text-muted">-</span>
                                                    @else
                                                        <span class="text-danger">{{ Str::limit($change['old'], 100) }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if(is_null($change['new']))
                                                        <span class="text-muted">-</span>
                                                    @else
                                                        <span class="text-success">{{ Str::limit($change['new'], 100) }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                Bu işlem için değişiklik bilgisi bulunmuyor.
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Ham Veri -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-code"></i>
                        Ham Veri
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if($activityLog->old_values)
                            <div class="col-md-6">
                                <h6>Eski Değerler:</h6>
                                <pre class="bg-light p-2" style="max-height: 300px; overflow-y: auto;">{{ json_encode($activityLog->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                            </div>
                        @endif
                        
                        @if($activityLog->new_values)
                            <div class="col-md-6">
                                <h6>Yeni Değerler:</h6>
                                <pre class="bg-light p-2" style="max-height: 300px; overflow-y: auto;">{{ json_encode($activityLog->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Yan Panel -->
        <div class="col-md-4">
            <!-- Teknik Bilgiler -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-server"></i>
                        Teknik Bilgiler
                    </h3>
                </div>
                <div class="card-body">
                    <dl>
                        <dt>IP Adresi:</dt>
                        <dd>{{ $activityLog->ip_address ?? '-' }}</dd>
                        
                        <dt>User Agent:</dt>
                        <dd>
                            @if($activityLog->user_agent)
                                <small>{{ Str::limit($activityLog->user_agent, 50) }}</small>
                                @if(strlen($activityLog->user_agent) > 50)
                                    <br>
                                    <button type="button" class="btn btn-sm btn-link p-0" data-toggle="modal" data-target="#userAgentModal">
                                        Tam metni göster
                                    </button>
                                @endif
                            @else
                                -
                            @endif
                        </dd>
                        
                        <dt>URL:</dt>
                        <dd>{{ $activityLog->url ?? '-' }}</dd>
                    </dl>
                </div>
            </div>

            <!-- İşlemler -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-cogs"></i>
                        İşlemler
                    </h3>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-secondary btn-block">
                        <i class="fas fa-arrow-left"></i>
                        Geri Dön
                    </a>
                    
                    @if($activityLog->user_id)
                        <a href="{{ route('admin.activity-logs.index', ['user_id' => $activityLog->user_id]) }}" class="btn btn-info btn-block">
                            <i class="fas fa-user"></i>
                            Bu Kullanıcının Diğer Aktiviteleri
                        </a>
                    @endif
                    
                    <a href="{{ route('admin.activity-logs.index', ['model_type' => $activityLog->model_type]) }}" class="btn btn-warning btn-block">
                        <i class="fas fa-layer-group"></i>
                        Bu Model Türünün Diğer Aktiviteleri
                    </a>
                </div>
            </div>

            <!-- İlgili Aktiviteler -->
            @if($activityLog->model_id)
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-history"></i>
                            İlgili Aktiviteler
                        </h3>
                    </div>
                    <div class="card-body">
                        @php
                            $relatedLogs = \App\Models\ActivityLog::where('model_type', $activityLog->model_type)
                                ->where('model_id', $activityLog->model_id)
                                ->where('id', '!=', $activityLog->id)
                                ->orderBy('created_at', 'desc')
                                ->limit(5)
                                ->get();
                        @endphp
                        
                        @if($relatedLogs->count() > 0)
                            @foreach($relatedLogs as $relatedLog)
                                <div class="mb-2 p-2 border rounded">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge 
                                            @if($relatedLog->action == 'created') badge-success
                                            @elseif($relatedLog->action == 'updated') badge-warning
                                            @elseif($relatedLog->action == 'deleted') badge-danger
                                            @elseif($relatedLog->action == 'restored') badge-info
                                            @else badge-secondary
                                            @endif
                                        ">
                                            {{ $relatedLog->getActionInTurkish() }}
                                        </span>
                                        <small class="text-muted">{{ $relatedLog->created_at->format('d.m.Y H:i') }}</small>
                                    </div>
                                    <div class="mt-1">
                                        <small>{{ $relatedLog->user_name ?? 'Sistem' }}</small>
                                    </div>
                                    <div class="mt-1">
                                        <a href="{{ route('admin.activity-logs.show', $relatedLog) }}" class="btn btn-xs btn-outline-primary">
                                            Detay
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                            
                            <a href="{{ route('admin.activity-logs.index', ['model_type' => $activityLog->model_type, 'model_id' => $activityLog->model_id]) }}" class="btn btn-sm btn-outline-info btn-block">
                                Tümünü Görüntüle
                            </a>
                        @else
                            <div class="text-muted text-center">
                                <i class="fas fa-info-circle"></i>
                                İlgili başka aktivite bulunamadı.
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- User Agent Modal -->
@if($activityLog->user_agent && strlen($activityLog->user_agent) > 50)
    <div class="modal fade" id="userAgentModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">User Agent</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <pre class="bg-light p-2">{{ $activityLog->user_agent }}</pre>
                </div>
            </div>
        </div>
    </div>
@endif
@stop

@section('css')
<style>
.badge {
    font-size: 0.8em;
}
pre {
    font-size: 0.85em;
}
.btn-xs {
    padding: 0.25rem 0.4rem;
    font-size: 0.7rem;
    line-height: 1.2;
    border-radius: 0.2rem;
}
</style>
@stop 