@extends('adminlte::page')

@section('title', 'Müdürlükler')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Müdürlükler</h1>
        <a href="{{ route('admin.mudurlukler.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Yeni Müdürlük Ekle
        </a>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tüm Müdürlükler</h3>
                    <div class="card-tools">
                        <form action="{{ route('admin.mudurlukler.index') }}" method="GET" class="input-group input-group-sm" style="width: 350px;">
                            <select name="status" class="form-control mr-2">
                                <option value="">Tüm Durumlar</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Pasif</option>
                            </select>
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
                                <th>Müdürlük Adı</th>
                                <th>Slug</th>
                                <th>Durum</th>
                                <th>Görüntülenme</th>
                                <th>Dosya Sayısı</th>
                                <th>Oluşturulma Tarihi</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($mudurlukler as $mudurluk)
                            <tr>
                                <td>{{ $mudurluk->id }}</td>
                                <td>
                                    @if($mudurluk->image)
                                        <img src="{{ asset('storage/' . $mudurluk->image) }}" alt="{{ $mudurluk->name }}" width="50" height="50" class="img-thumbnail">
                                    @else
                                        <span class="badge badge-secondary">Görsel Yok</span>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $mudurluk->name }}</strong>
                                    @if($mudurluk->summary)
                                        <br><small class="text-muted">{{ Str::limit($mudurluk->summary, 50) }}</small>
                                    @endif
                                </td>
                                <td>{{ $mudurluk->slug }}</td>
                                <td>
                                    @if($mudurluk->is_active)
                                        <span class="badge badge-success">Aktif</span>
                                    @else
                                        <span class="badge badge-warning">Pasif</span>
                                    @endif
                                </td>
                                <td>{{ $mudurluk->view_count ?? 0 }}</td>
                                <td>
                                    <span class="badge badge-info">{{ $mudurluk->files_count ?? 0 }}</span>
                                </td>
                                <td>{{ $mudurluk->created_at->format('d.m.Y H:i') }}</td>
                                <td>
                                    <div class="btn-group">
                                        @if($mudurluk->slug && $mudurluk->is_active)
                                            <a href="{{ route('mudurlukler.show', $mudurluk->slug) }}" class="btn btn-sm btn-success" title="Sitede Gör" target="_blank">
                                                <i class="fas fa-external-link-alt"></i>
                                            </a>
                                        @endif
                                        <a href="{{ route('admin.mudurlukler.show', $mudurluk->id) }}" class="btn btn-sm btn-secondary" title="Görüntüle">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.mudurlukler.edit', $mudurluk->id) }}" class="btn btn-sm btn-info" title="Düzenle">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modal-delete-{{ $mudurluk->id }}" title="Sil">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    
                                    <!-- Modal -->
                                    <div class="modal fade" id="modal-delete-{{ $mudurluk->id }}" tabindex="-1" role="dialog" aria-labelledby="modal-delete-{{ $mudurluk->id }}-label" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modal-delete-{{ $mudurluk->id }}-label">Silme Onayı</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>"{{ $mudurluk->name }}" müdürlüğünü silmek istediğinizden emin misiniz?</p>
                                                    <p class="text-danger">Bu işlem geri alınamaz ve tüm dosyalar da silinecektir.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">İptal</button>
                                                    <form action="{{ route('admin.mudurlukler.destroy', $mudurluk->id) }}" method="POST" style="display: inline-block;">
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
                                <td colspan="9" class="text-center">Henüz müdürlük bulunmamaktadır.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    {{ $mudurlukler->links() }}
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