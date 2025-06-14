@extends('adminlte::page')

@section('title', $category->name . ' - Menü Öğeleri')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>{{ $category->name }} - Menü Öğeleri</h1>
        <div>
            <a href="{{ route('admin.homepage.quick-menus.items.create', $category->id) }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Yeni Öğe Ekle
            </a>
            <a href="{{ route('admin.homepage.quick-menus.index') }}" class="btn btn-secondary ml-2">
                <i class="fas fa-arrow-left"></i> Kategorilere Dön
            </a>
        </div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $category->name }} Kategorisi Menü Öğeleri</h3>
        </div>
        
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-check"></i> Başarılı!</h5>
                    {{ session('success') }}
                </div>
            @endif
            
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-ban"></i> Hata!</h5>
                    {{ session('error') }}
                </div>
            @endif
            
            <div class="category-info mb-4">
                <div class="row">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center">
                            @if($category->icon)
                                <i class="{{ $category->icon }} fa-2x mr-3"></i>
                            @endif
                            <div>
                                <h4 class="mb-1">{{ $category->name }}</h4>
                                @if($category->description)
                                    <p class="text-muted mb-0">{{ $category->description }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-right">
                        <button type="button" class="btn btn-info btn-sm" id="sort-alphabetically">
                            <i class="fas fa-sort-alpha-down"></i> Alfabetik Sırala
                        </button>
                        <a href="{{ route('admin.homepage.quick-menus.edit', $category->id) }}" class="btn btn-warning btn-sm ml-2">
                            <i class="fas fa-edit"></i> Kategoriyi Düzenle
                        </a>
                        <span class="badge badge-{{ $category->is_active ? 'success' : 'danger' }} ml-2">
                            {{ $category->is_active ? 'Aktif' : 'Pasif' }}
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="width: 50px">#</th>
                            <th style="width: 60px">Sıra</th>
                            <th style="width: 50px">İkon</th>
                            <th>Başlık</th>
                            <th>URL</th>
                            <th style="width: 80px">Durum</th>
                            <th style="width: 120px">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody id="sortable-items">
                        @forelse($items as $item)
                            <tr data-id="{{ $item->id }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="handle" style="cursor: move;">
                                        <i class="fas fa-arrows-alt"></i>
                                        <span class="order-number">{{ $item->order }}</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if($item->icon)
                                        <i class="{{ $item->icon }} fa-lg"></i>
                                    @else
                                        <i class="fas fa-minus text-muted"></i>
                                    @endif
                                </td>
                                <td>{{ $item->title }}</td>
                                <td>
                                    <a href="{{ $item->url }}" target="_blank" class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $item->url }}">
                                        {{ $item->url }}
                                    </a>
                                </td>
                                <td>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input toggle-status" 
                                               id="status_{{ $item->id }}" 
                                               data-id="{{ $item->id }}" 
                                               {{ $item->is_active ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="status_{{ $item->id }}"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.homepage.quick-menus.items.edit', ['category_id' => $category->id, 'id' => $item->id]) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="{{ $item->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Bu kategoriye ait menü öğesi bulunmamaktadır.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($items->count() == 0)
                <div class="text-center mt-3">
                    <a href="{{ route('admin.homepage.quick-menus.items.create', $category->id) }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> İlk Menü Öğesini Ekle
                    </a>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Silme onay modal -->
    <div class="modal fade" id="delete-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h4 class="modal-title">Menü Öğesini Sil</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Bu menü öğesini silmek istediğinize emin misiniz?</p>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">İptal</button>
                    <form id="delete-form" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Evet, Sil</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $(document).ready(function() {
            // Sıralama işlemleri
            $("#sortable-items").sortable({
                handle: '.handle',
                update: function(event, ui) {
                    let items = [];
                    $('#sortable-items tr').each(function(index) {
                        items.push({
                            id: $(this).data('id'),
                            order: index
                        });
                        $(this).find('.order-number').text(index);
                    });
                    
                    // AJAX ile sıralama güncelleme
                    $.ajax({
                        url: "{{ route('admin.homepage.quick-menus.items.order', $category->id) }}",
                        method: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            items: items
                        },
                        success: function(response) {
                            toastr.success('Sıralama başarıyla güncellendi');
                        },
                        error: function(xhr) {
                            toastr.error('Sıralama güncellenirken bir hata oluştu');
                        }
                    });
                }
            });
            
            // Durum değiştirme
            $('.toggle-status').change(function() {
                const id = $(this).data('id');
                const isActive = $(this).prop('checked') ? 1 : 0;
                
                $.ajax({
                    url: "{{ route('admin.homepage.quick-menus.items.toggle', ['category_id' => $category->id, 'id' => ':id']) }}".replace(':id', id),
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        is_active: isActive
                    },
                    success: function(response) {
                        toastr.success('Durum başarıyla güncellendi');
                    },
                    error: function(xhr) {
                        toastr.error('Durum güncellenirken bir hata oluştu');
                        // Hata durumunda checkbox'ı eski haline getir
                        $('#status_' + id).prop('checked', !isActive);
                    }
                });
            });
            
            // Silme işlemi
            $('.delete-btn').click(function() {
                const id = $(this).data('id');
                $('#delete-form').attr('action', "{{ route('admin.homepage.quick-menus.items.delete', ['category_id' => $category->id, 'id' => ':id']) }}".replace(':id', id));
                $('#delete-modal').modal('show');
            });
            
            // Alfabetik sıralama
            $('#sort-alphabetically').click(function() {
                const rows = $('#sortable-items tr').get();
                
                rows.sort(function(a, b) {
                    const titleA = $(a).find('td:nth-child(4)').text().trim();
                    const titleB = $(b).find('td:nth-child(4)').text().trim();
                    return titleA.localeCompare(titleB, 'tr-TR', { 
                        sensitivity: 'base',
                        numeric: true,
                        ignorePunctuation: true
                    });
                });
                
                // Sıralanmış satırları tabloya ekle
                $.each(rows, function(index, row) {
                    $('#sortable-items').append(row);
                    $(row).find('.order-number').text(index);
                });
                
                // Sıralamayı kaydet
                let items = [];
                $('#sortable-items tr').each(function(index) {
                    items.push({
                        id: $(this).data('id'),
                        order: index
                    });
                });
                
                // AJAX ile sıralama güncelleme
                $.ajax({
                    url: "{{ route('admin.homepage.quick-menus.items.order', $category->id) }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        items: items
                    },
                    success: function(response) {
                        toastr.success('Öğeler alfabetik sıraya göre düzenlendi');
                    },
                    error: function(xhr) {
                        toastr.error('Sıralama güncellenirken bir hata oluştu');
                    }
                });
            });
        });
    </script>
@stop

@section('css')
    <style>
        .handle {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
            border-radius: 4px;
            padding: 5px;
        }
        
        .handle i {
            margin-right: 5px;
            color: #6c757d;
        }
        
        .order-number {
            font-weight: bold;
        }
        
        .category-info {
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
            border-left: 4px solid #007bff;
        }
        
        .ui-sortable-helper {
            display: table;
            border: 1px dashed #3c8dbc !important;
            background-color: #f8f9fa !important;
        }
        
        .table tr {
            transition: background-color 0.3s;
        }
        
        .table tr:hover {
            background-color: rgba(0,0,0,0.03);
        }
    </style>
@stop 