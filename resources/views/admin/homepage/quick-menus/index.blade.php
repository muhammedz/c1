@extends('adminlte::page')

@section('title', 'Hızlı Menü Yönetimi')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Hızlı Menü Kategorileri</h1>
        <a href="{{ route('admin.homepage.quick-menus.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Yeni Kategori Ekle
        </a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Hızlı Menü Kategori Listesi</h3>
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
            
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="width: 50px">#</th>
                            <th style="width: 60px">Sıra</th>
                            <th style="width: 50px">İkon</th>
                            <th>Kategori Adı</th>
                            <th>Açıklama</th>
                            <th style="width: 100px">Öğe Sayısı</th>
                            <th style="width: 80px">Durum</th>
                            <th style="width: 150px">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody id="sortable-categories">
                        @forelse($categories as $category)
                            <tr data-id="{{ $category->id }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="handle" style="cursor: move;">
                                        <i class="fas fa-arrows-alt"></i>
                                        <span class="order-number">{{ $category->order }}</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if($category->icon)
                                        <i class="{{ $category->icon }} fa-lg"></i>
                                    @else
                                        <i class="fas fa-minus text-muted"></i>
                                    @endif
                                </td>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->description ?? '-' }}</td>
                                <td class="text-center">
                                    {{ $category->items->count() }}
                                </td>
                                <td>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input toggle-status" 
                                               id="status_{{ $category->id }}" 
                                               data-id="{{ $category->id }}" 
                                               {{ $category->is_active ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="status_{{ $category->id }}"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.homepage.quick-menus.items', $category->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-list"></i> Öğeler
                                        </a>
                                        <button type="button" class="btn btn-sm btn-info dropdown-toggle dropdown-toggle-split" data-toggle="dropdown">
                                            <span class="sr-only">Menü</span>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a href="{{ route('admin.homepage.quick-menus.edit', $category->id) }}" class="dropdown-item">
                                                <i class="fas fa-edit"></i> Düzenle
                                            </a>
                                            <a href="{{ route('admin.homepage.quick-menus.items', $category->id) }}" class="dropdown-item">
                                                <i class="fas fa-list"></i> Öğeler
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <button type="button" class="dropdown-item text-danger delete-btn" data-id="{{ $category->id }}">
                                                <i class="fas fa-trash"></i> Sil
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Henüz eklenmiş bir hızlı menü kategorisi bulunmamaktadır.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Silme onay modal -->
    <div class="modal fade" id="delete-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h4 class="modal-title">Kategoriyi Sil</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Bu kategoriyi ve <strong>tüm bağlı menü öğelerini</strong> silmek istediğinize emin misiniz?</p>
                    <p class="text-danger"><strong>Dikkat:</strong> Bu işlem geri alınamaz!</p>
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
            $("#sortable-categories").sortable({
                handle: '.handle',
                update: function(event, ui) {
                    let items = [];
                    $('#sortable-categories tr').each(function(index) {
                        items.push({
                            id: $(this).data('id'),
                            order: index
                        });
                        $(this).find('.order-number').text(index);
                    });
                    
                    // AJAX ile sıralama güncelleme
                    $.ajax({
                        url: "{{ route('admin.homepage.quick-menus.order') }}",
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
                    url: `{{ route('admin.homepage.quick-menus.toggle', '') }}/${id}`,
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
                $('#delete-form').attr('action', `{{ route('admin.homepage.quick-menus.delete', '') }}/${id}`);
                $('#delete-modal').modal('show');
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
        
        .table tr {
            transition: background-color 0.3s;
        }
        
        .table tr:hover {
            background-color: rgba(0,0,0,0.03);
        }
        
        .ui-sortable-helper {
            display: table;
            border: 1px dashed #3c8dbc !important;
            background-color: #f8f9fa !important;
        }
    </style>
@stop 