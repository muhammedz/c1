@extends('adminlte::page')

@section('title', 'Footer Menü Linkleri')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1>{{ $menu->title }} - Linkler</h1>
            <small class="text-muted">Footer menü linklerini yönetin</small>
        </div>
        <div>
            <a href="{{ route('admin.footer.menus.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Menülere Dön
            </a>
            <a href="{{ route('admin.footer.menus.links.create', $menu) }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Yeni Link
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

    <div class="row">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-link"></i> Link Listesi
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-sm btn-info mr-2" id="sort-alphabetically">
                            <i class="fas fa-sort-alpha-down"></i> Alfabetik Sırala
                        </button>
                        <span class="badge badge-info">{{ $links->count() }} link</span>
                    </div>
                </div>
                <div class="card-body">
                    @if($links->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="links-table">
                                <thead>
                                    <tr>
                                        <th width="50">Sıra</th>
                                        <th>Link Başlığı</th>
                                        <th>URL</th>
                                        <th width="100">Durum</th>
                                        <th width="150">İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody id="sortable-links">
                                    @foreach($links as $link)
                                        <tr data-id="{{ $link->id }}">
                                            <td class="text-center">
                                                <i class="fas fa-grip-vertical handle" style="cursor: move;"></i>
                                                {{ $link->order }}
                                            </td>
                                            <td>
                                                <strong>{{ $link->title }}</strong>
                                            </td>
                                            <td>
                                                <a href="{{ $link->url }}" target="_blank" class="text-primary">
                                                    {{ Str::limit($link->url, 50) }}
                                                    <i class="fas fa-external-link-alt fa-sm"></i>
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <form action="{{ route('admin.footer.menus.links.toggle', [$menu, $link]) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm {{ $link->is_active ? 'btn-success' : 'btn-secondary' }}">
                                                        @if($link->is_active)
                                                            <i class="fas fa-check"></i> Aktif
                                                        @else
                                                            <i class="fas fa-times"></i> Pasif
                                                        @endif
                                                    </button>
                                                </form>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('admin.footer.menus.links.edit', [$menu, $link]) }}" 
                                                       class="btn btn-sm btn-warning" title="Düzenle">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.footer.menus.links.destroy', [$menu, $link]) }}" 
                                                          method="POST" style="display: inline;" 
                                                          onsubmit="return confirm('Bu link silinsin mi?')">
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
                            <i class="fas fa-link fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">Henüz link eklenmemiş</h4>
                            <p class="text-muted">Bu menü için ilk linki oluşturmak için aşağıdaki butona tıklayın.</p>
                            <a href="{{ route('admin.footer.menus.links.create', $menu) }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> İlk Linki Oluştur
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle"></i> Menü Bilgileri
                    </h3>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Menü:</strong></td>
                            <td>{{ $menu->title }}</td>
                        </tr>
                        <tr>
                            <td><strong>Sıralama:</strong></td>
                            <td>{{ $menu->order }}</td>
                        </tr>
                        <tr>
                            <td><strong>Durum:</strong></td>
                            <td>
                                @if($menu->is_active)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-secondary">Pasif</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Toplam Link:</strong></td>
                            <td>{{ $menu->links->count() }}</td>
                        </tr>
                        <tr>
                            <td><strong>Aktif Link:</strong></td>
                            <td>{{ $menu->activeLinks->count() }}</td>
                        </tr>
                    </table>

                    <div class="mt-3">
                        <a href="{{ route('admin.footer.menus.edit', $menu) }}" class="btn btn-warning btn-block">
                            <i class="fas fa-edit"></i> Menüyü Düzenle
                        </a>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-lightbulb"></i> İpuçları
                    </h3>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check text-success"></i> Linkleri sürükleyerek sıralayabilirsiniz</li>
                        <li><i class="fas fa-check text-success"></i> Pasif linkler footer'da görünmez</li>
                        <li><i class="fas fa-check text-success"></i> URL'lerin doğru olduğundan emin olun</li>
                        <li><i class="fas fa-check text-success"></i> Kısa ve anlaşılır başlıklar kullanın</li>
                    </ul>
                </div>
            </div>
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
            $("#sortable-links").sortable({
                handle: '.handle',
                placeholder: 'sortable-placeholder',
                update: function(event, ui) {
                    let orders = {};
                    $('#sortable-links tr').each(function(index) {
                        let id = $(this).data('id');
                        orders[id] = index + 1;
                    });

                    $.ajax({
                        url: '{{ route("admin.footer.menus.links.order", $menu) }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            orders: orders
                        },
                        success: function(response) {
                            if (response.success) {
                                // Sıra numaralarını güncelle
                                $('#sortable-links tr').each(function(index) {
                                    $(this).find('td:first').html(
                                        '<i class="fas fa-grip-vertical handle" style="cursor: move;"></i> ' + 
                                        (index + 1)
                                    );
                                });
                                
                                toastr.success('Link sıralaması güncellendi');
                            }
                        },
                        error: function() {
                            toastr.error('Sıralama güncellenirken hata oluştu');
                        }
                    });
                }
            });

            // Alfabetik sıralama
            $('#sort-alphabetically').click(function() {
                const rows = $('#sortable-links tr').get();
                
                rows.sort(function(a, b) {
                    const titleA = $(a).find('td:nth-child(2) strong').text().trim();
                    const titleB = $(b).find('td:nth-child(2) strong').text().trim();
                    return titleA.localeCompare(titleB, 'tr-TR', { 
                        sensitivity: 'base',
                        numeric: true,
                        ignorePunctuation: true
                    });
                });
                
                // Sıralanmış satırları tabloya ekle
                $.each(rows, function(index, row) {
                    $('#sortable-links').append(row);
                    $(row).find('td:first').html(
                        '<i class="fas fa-grip-vertical handle" style="cursor: move;"></i> ' + 
                        (index + 1)
                    );
                });
                
                // Sıralamayı kaydet
                let orders = {};
                $('#sortable-links tr').each(function(index) {
                    let id = $(this).data('id');
                    orders[id] = index + 1;
                });
                
                // AJAX ile sıralama güncelleme
                $.ajax({
                    url: '{{ route("admin.footer.menus.links.order", $menu) }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        orders: orders
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success('Linkler alfabetik sıraya göre düzenlendi');
                        }
                    },
                    error: function() {
                        toastr.error('Sıralama güncellenirken hata oluştu');
                    }
                });
            });

            // DataTable
            if ($('#links-table tbody tr').length > 0) {
                $('#links-table').DataTable({
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