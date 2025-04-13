@extends('adminlte::page')

@section('title', 'Hizmet Etiketleri')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Hizmet Etiketleri</h1>
        <a href="{{ route('admin.service-tags.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Yeni Etiket Ekle
        </a>
    </div>
@stop

@section('content')
<div class="container-fluid">
    @include('admin.partials.alerts')

    <div class="card">
        <div class="card-body">
            <table id="service-tags-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="width: 50px;">ID</th>
                        <th>Ad</th>
                        <th>Slug</th>
                        <th style="width: 200px;">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($serviceTags as $serviceTag)
                    <tr>
                        <td>{{ $serviceTag->id }}</td>
                        <td>{{ $serviceTag->name }}</td>
                        <td>{{ $serviceTag->slug }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.service-tags.edit', $serviceTag) }}" class="btn btn-primary">
                                    <i class="fas fa-edit"></i> Düzenle
                                </a>
                                
                                <button type="button" class="btn btn-danger btn-delete" data-id="{{ $serviceTag->id }}">
                                    <i class="fas fa-trash"></i> Sil
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <form id="delete-form" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('vendor/datatables/css/dataTables.bootstrap4.min.css') }}">
@stop

@section('js')
    <script src="{{ asset('vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>
    
    <script>
        $(function() {
            $('#service-tags-table').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Turkish.json"
                }
            });
            
            // Silme işlemi
            $('.btn-delete').on('click', function() {
                const id = $(this).data('id');
                
                Swal.fire({
                    title: 'Emin misiniz?',
                    text: "Bu etiket silinecek. Bu işlem geri alınamaz!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Evet, sil!',
                    cancelButtonText: 'İptal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = $('#delete-form');
                        form.attr('action', `/admin/service-tags/${id}`);
                        form.submit();
                    }
                });
            });
        });
    </script>
@stop 