@extends('adminlte::page')

@section('title', 'Footer Menüleri')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Footer Menüleri</h1>
        <div>
            <a href="{{ route('admin.footer.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Geri Dön
            </a>
            <a href="{{ route('admin.footer.menus.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Yeni Menü
            </a>
        </div>
    </div>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-list"></i> Menü Listesi
            </h3>
        </div>
        <div class="card-body">
            @if($menus->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="menus-table">
                        <thead>
                            <tr>
                                <th width="50">Sıra</th>
                                <th>Menü Başlığı</th>
                                <th width="100">Link Sayısı</th>
                                <th width="100">Durum</th>
                                <th width="150">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody id="sortable-menus">
                            @foreach($menus as $menu)
                                <tr data-id="{{ $menu->id }}">
                                    <td class="text-center">
                                        <i class="fas fa-grip-vertical handle" style="cursor: move;"></i>
                                        {{ $menu->order }}
                                    </td>
                                    <td>
                                        <strong>{{ $menu->title }}</strong>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-info">{{ $menu->links->count() }}</span>
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('admin.footer.menus.toggle', $menu) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm {{ $menu->is_active ? 'btn-success' : 'btn-secondary' }}">
                                                @if($menu->is_active)
                                                    <i class="fas fa-check"></i> Aktif
                                                @else
                                                    <i class="fas fa-times"></i> Pasif
                                                @endif
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.footer.menus.links.index', $menu) }}" 
                                               class="btn btn-sm btn-info" title="Linkleri Yönet">
                                                <i class="fas fa-link"></i>
                                            </a>
                                            <a href="{{ route('admin.footer.menus.edit', $menu) }}" 
                                               class="btn btn-sm btn-warning" title="Düzenle">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.footer.menus.destroy', $menu) }}" 
                                                  method="POST" style="display: inline;" 
                                                  onsubmit="return confirm('Bu menü silinsin mi? Tüm linkleri de silinecektir!')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Sil">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-list fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">Henüz menü oluşturulmamış</h4>
                    <p class="text-muted">İlk menünüzü oluşturmak için aşağıdaki butona tıklayın.</p>
                    <a href="{{ route('admin.footer.menus.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> İlk Menüyü Oluştur
                    </a>
                </div>
            @endif
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css">
    <style>
        .handle {
            cursor: move;
        }
        .ui-sortable-helper {
            background: #f8f9fa;
        }
        .sortable-placeholder {
            background: #e9ecef;
            height: 50px;
        }
    </style>
@stop

@section('js')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script>
        $(document).ready(function() {
            // Sürükle bırak sıralama
            $("#sortable-menus").sortable({
                handle: '.handle',
                placeholder: 'sortable-placeholder',
                update: function(event, ui) {
                    let orders = {};
                    $('#sortable-menus tr').each(function(index) {
                        let id = $(this).data('id');
                        orders[id] = index + 1;
                    });

                    $.ajax({
                        url: '{{ route("admin.footer.menus.order") }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            orders: orders
                        },
                        success: function(response) {
                            if (response.success) {
                                // Sıra numaralarını güncelle
                                $('#sortable-menus tr').each(function(index) {
                                    $(this).find('td:first').html(
                                        '<i class="fas fa-grip-vertical handle" style="cursor: move;"></i> ' + 
                                        (index + 1)
                                    );
                                });
                                
                                toastr.success('Menü sıralaması güncellendi');
                            }
                        },
                        error: function() {
                            toastr.error('Sıralama güncellenirken hata oluştu');
                        }
                    });
                }
            });

            // DataTable
            if ($('#menus-table tbody tr').length > 0) {
                $('#menus-table').DataTable({
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Turkish.json"
                    },
                    "order": [[ 0, "asc" ]],
                    "columnDefs": [
                        { "orderable": false, "targets": [4] }
                    ]
                });
            }
        });
    </script>
@stop 