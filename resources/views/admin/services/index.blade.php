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
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Görsel</th>
                                <th>Başlık</th>
                                <th>Slug</th>
                                <th>Durum</th>
                                <th>Görüntülenme</th>
                                <th>Öne Çıkan</th>
                                <th>Manşet</th>
                                <th>Oluşturulma Tarihi</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($services as $service)
                            <tr>
                                <td>{{ $service->id }}</td>
                                <td>
                                    @if($service->image)
                                        <img src="{{ asset(str_replace('/storage/', '', $service->image)) }}" alt="{{ $service->title }}" width="50" height="50" class="img-thumbnail">
                                    @else
                                        <span class="badge badge-secondary">Görsel Yok</span>
                                    @endif
                                </td>
                                <td>{{ $service->title }}</td>
                                <td>{{ $service->slug }}</td>
                                <td>
                                    @if($service->status == 'published')
                                        <span class="badge badge-success">Yayında</span>
                                    @else
                                        <span class="badge badge-warning">Taslak</span>
                                    @endif
                                </td>
                                <td>{{ $service->view_count ?? 0 }}</td>
                                <td>
                                    @if($service->is_featured)
                                        <span class="badge badge-info"><i class="fas fa-check"></i></span>
                                    @else
                                        <span class="badge badge-secondary"><i class="fas fa-times"></i></span>
                                    @endif
                                </td>
                                <td>
                                    @if($service->is_headline)
                                        <span class="badge badge-primary"><i class="fas fa-check"></i></span>
                                    @else
                                        <span class="badge badge-secondary"><i class="fas fa-times"></i></span>
                                    @endif
                                </td>
                                <td>{{ $service->created_at->format('d.m.Y H:i') }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.services.edit', $service->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-edit"></i> Düzenle
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modal-delete-{{ $service->id }}">
                                            <i class="fas fa-trash"></i> Sil
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
                                <td colspan="10" class="text-center">Henüz hizmet bulunmamaktadır.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    {{ $services->links() }}
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
        vertical-align: middle;
    }
    .img-thumbnail {
        object-fit: cover;
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