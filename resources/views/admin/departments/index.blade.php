@extends('adminlte::page')

@section('title', 'Bölümler')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Bölümler</h1>
        <a href="{{ route('admin.services.departments.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Yeni Bölüm Ekle
        </a>
    </div>
@stop

@section('content')
<div class="container-fluid">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tüm Bölümler</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Sıra</th>
                                <th>ID</th>
                                <th>Görsel</th>
                                <th>Bölüm Adı</th>
                                <th>Slug</th>
                                <th>Durum</th>
                                <th>Bağlı Hizmetler</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody id="sortable-departments">
                            @forelse($departments as $department)
                            <tr data-id="{{ $department->id }}">
                                <td class="handle" style="cursor: move;">
                                    <i class="fas fa-arrows-alt"></i>
                                </td>
                                <td>{{ $department->id }}</td>
                                <td>
                                    @if($department->image)
                                        <img src="{{ asset('storage/' . $department->image) }}" alt="{{ $department->name }}" width="50" height="50" class="img-thumbnail">
                                    @else
                                        <span class="badge badge-secondary">Görsel Yok</span>
                                    @endif
                                </td>
                                <td>{{ $department->name }}</td>
                                <td>{{ $department->slug }}</td>
                                <td>
                                    <form action="{{ route('admin.services.departments.toggle-status', $department->id) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm @if($department->is_active) btn-success @else btn-danger @endif">
                                            @if($department->is_active)
                                                <i class="fas fa-check"></i> Aktif
                                            @else
                                                <i class="fas fa-times"></i> Pasif
                                            @endif
                                        </button>
                                    </form>
                                </td>
                                <td>{{ $department->services->count() }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.services.departments.edit', $department->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-edit"></i> Düzenle
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modal-delete-{{ $department->id }}">
                                            <i class="fas fa-trash"></i> Sil
                                        </button>
                                    </div>
                                    
                                    <!-- Modal -->
                                    <div class="modal fade" id="modal-delete-{{ $department->id }}" tabindex="-1" role="dialog" aria-labelledby="modal-delete-{{ $department->id }}-label" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modal-delete-{{ $department->id }}-label">Silme Onayı</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>"{{ $department->name }}" isimli bölümü silmek istediğinizden emin misiniz?</p>
                                                    <p class="text-danger">Bu işlem geri alınamaz.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">İptal</button>
                                                    <form action="{{ route('admin.services.departments.destroy', $department->id) }}" method="POST" style="display: inline-block;">
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
                                <td colspan="8" class="text-center">Henüz bölüm bulunmamaktadır.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    @if(method_exists($departments, 'links'))
                        {{ $departments->links() }}
                    @endif
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
</div>
@stop

@section('css')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<style>
    .table th, .table td {
        vertical-align: middle;
    }
    .img-thumbnail {
        object-fit: cover;
    }
    .handle {
        cursor: move;
    }
</style>
@stop

@section('js')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script>
    $(function() {
        // Flash mesajı için otomatik kapanma
        $('.alert').delay(3000).fadeOut(500);
        
        // Sortable bölümleri
        $('#sortable-departments').sortable({
            handle: '.handle',
            update: function(event, ui) {
                let order = {};
                $('#sortable-departments tr').each(function(index) {
                    let id = $(this).data('id');
                    if (id) {
                        order[id] = index;
                    }
                });
                
                if (Object.keys(order).length > 0) {
                    $.ajax({
                        url: "{{ route('admin.services.departments.update-order') }}",
                        type: "POST",
                        data: {
                            order: order,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.success) {
                                // Başarılı işlem bildirimi
                                toastr.success('Sıralama başarıyla güncellendi');
                            }
                        },
                        error: function(error) {
                            console.error("Sıralama güncellenirken bir hata oluştu:", error);
                            toastr.error('Sıralama güncellenirken bir hata oluştu');
                        }
                    });
                }
            }
        });
    });
</script>
@stop 