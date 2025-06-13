@extends('adminlte::page')

@section('title', 'Hizmetler')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Hizmetler</h1>
        <a href="{{ route('admin.services.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Yeni Hizmet Ekle
        </a>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tüm Hizmetler</h3>
                    <div class="card-tools">
                        <form action="{{ route('admin.services.index') }}" method="GET" class="input-group input-group-sm" style="width: 250px;">
                            <input type="text" name="search" class="form-control float-right" placeholder="Ara..." value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 60px;">ID</th>
                                <th style="width: 250px;">Başlık</th>
                                <th style="width: 180px;">Kategoriler</th>
                                <th style="width: 150px;">Birim</th>
                                <th style="width: 200px;">Hizmet Konuları</th>
                                <th style="width: 180px;">Hedef Kitleler</th>
                                <th style="width: 200px;">İlgili Haber Kategorileri</th>
                                <th style="width: 120px;">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($services as $service)
                            <tr>
                                <td class="text-center">
                                    <span class="badge badge-light">{{ $service->id }}</span>
                                </td>
                                <td>
                                    <div class="service-title">
                                        <strong>{{ Str::limit($service->title, 40) }}</strong>
                                        <br>
                                        <small class="text-muted">{{ Str::limit($service->slug, 35) }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="badge-container">
                                        @if($service->categories->count() > 0)
                                            @foreach($service->categories as $category)
                                                <span class="badge badge-primary badge-sm mb-1">{{ $category->name }}</span>
                                            @endforeach
                                        @else
                                            <span class="text-muted small">Kategori yok</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($service->unit)
                                        <span class="badge badge-info badge-sm">{{ $service->unit->name }}</span>
                                    @else
                                        <span class="text-muted small">Birim yok</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="badge-container">
                                        @if($service->serviceTopics->count() > 0)
                                            @foreach($service->serviceTopics as $topic)
                                                <span class="badge badge-success badge-sm mb-1">{{ Str::limit($topic->name, 20) }}</span>
                                            @endforeach
                                        @else
                                            <span class="text-muted small">Konu yok</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="badge-container">
                                        @if($service->hedefKitleler->count() > 0)
                                            @foreach($service->hedefKitleler as $hedefKitle)
                                                <span class="badge badge-warning badge-sm mb-1">{{ Str::limit($hedefKitle->name, 15) }}</span>
                                            @endforeach
                                        @else
                                            <span class="text-muted small">Hedef yok</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="badge-container">
                                        @if($service->newsCategories->count() > 0)
                                            @foreach($service->newsCategories as $newsCategory)
                                                <span class="badge badge-secondary badge-sm mb-1">{{ Str::limit($newsCategory->name, 15) }}</span>
                                            @endforeach
                                        @else
                                            <span class="text-muted small">Haber yok</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group-vertical btn-group-sm">
                                        <a href="{{ route('admin.services.edit', $service->id) }}" class="btn btn-info btn-sm mb-1" title="Düzenle">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal-delete-{{ $service->id }}" title="Sil">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    
                                    <!-- Modal -->
                                    <div class="modal fade" id="modal-delete-{{ $service->id }}" tabindex="-1" role="dialog" aria-labelledby="modal-delete-{{ $service->id }}-label" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modal-delete-{{ $service->id }}-label">Silme Onayı</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>"{{ $service->title }}" başlıklı hizmeti silmek istediğinizden emin misiniz?</p>
                                                    <p class="text-danger">Bu işlem geri alınamaz.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">İptal</button>
                                                    <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST" style="display: inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Evet, Sil</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">Henüz hizmet bulunmamaktadır.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    {{ $services->links('custom.pagination') }}
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .table th, .table td {
        vertical-align: top;
        padding: 12px 8px;
    }
    
    .table thead th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        font-weight: 600;
        color: #495057;
    }
    
    .badge-container {
        display: flex;
        flex-wrap: wrap;
        gap: 2px;
        align-items: flex-start;
    }
    
    .badge-sm {
        font-size: 0.7em;
        padding: 0.25em 0.5em;
        margin-right: 2px;
        margin-bottom: 2px;
        line-height: 1.2;
    }
    
    .service-title {
        line-height: 1.3;
    }
    
    .service-title strong {
        color: #2c3e50;
        font-size: 0.9em;
    }
    
    .service-title small {
        font-size: 0.75em;
        color: #6c757d;
    }
    
    .table-responsive {
        border-radius: 0.375rem;
    }
    
    .table tr:hover {
        background-color: #f8f9fa;
    }
    
    .btn-group-vertical .btn {
        width: 35px;
        height: 30px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .text-muted.small {
        font-size: 0.75em;
        font-style: italic;
    }
    
    /* Responsive düzenlemeler */
    @media (max-width: 1200px) {
        .table th, .table td {
            font-size: 0.85em;
            padding: 8px 6px;
        }
        
        .badge-sm {
            font-size: 0.65em;
            padding: 0.2em 0.4em;
        }
    }
</style>
@stop

@section('js')
<script>
    $(function() {
        // Flash mesajı için otomatik kapanma
        $('.alert').delay(3000).fadeOut(500);
          });
  </script>
@stop 