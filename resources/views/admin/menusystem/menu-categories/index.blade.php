@extends('adminlte::page')

@section('title', 'Menü Kategorileri')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Menü Kategorileri</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Panel</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.menusystem.index') }}">Menü Yönetimi</a></li>
                <li class="breadcrumb-item active">Menü Kategorileri</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Panel</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.menusystem.index') }}">Menü Yönetimi</a></li>
                        <li class="breadcrumb-item active">Menü Kategorileri</li>
                    </ol>
                </div>
                <h4 class="page-title">Menü Kategorileri</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            <h4 class="card-title">{{ $menu->name ?? 'Tüm' }} Menüsü Kategorileri</h4>
                        </div>
                        <div class="col-6 text-right">
                            @if(isset($menu))
                            <a href="{{ route('admin.menusystem.categories.create', ['menu_id' => $menu->id]) }}" class="btn btn-primary">
                                <i class="mdi mdi-plus-circle mr-1"></i> Yeni Kategori Ekle
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(isset($categories) && count($categories) > 0)
                    <div class="table-responsive">
                        <table class="table table-centered table-striped dt-responsive nowrap w-100" id="categories-table">
                            <thead>
                                <tr>
                                    <th style="width: 5%">ID</th>
                                    <th style="width: 25%">Kategori Adı</th>
                                    <th style="width: 25%">URL</th>
                                    <th style="width: 10%">Sıralama</th>
                                    <th style="width: 10%">Durum</th>
                                    <th style="width: 10%">Öğe Sayısı</th>
                                    <th style="width: 15%">İşlemler</th>
                                </tr>
                            </thead>
                            <tbody id="categories-list">
                                @foreach($categories as $category)
                                <tr class="category-item" data-id="{{ $category->id }}">
                                    <td>{{ $category->id }}</td>
                                    <td>{{ $category->name }}</td>
                                    <td>{{ $category->url ?? '-' }}</td>
                                    <td>
                                        {{ $category->order }}
                                        <span class="handle float-right">
                                            <i class="mdi mdi-drag-horizontal"></i>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input toggle-status" 
                                                id="categoryStatus{{ $category->id }}" 
                                                data-id="{{ $category->id }}" 
                                                {{ $category->status ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="categoryStatus{{ $category->id }}"></label>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-light">{{ $category->items_count ?? 0 }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.menusystem.categories.edit', $category->id) }}" class="btn btn-sm btn-info">
                                                <i class="mdi mdi-pencil"></i>
                                            </a>
                                            <a href="{{ route('admin.menusystem.items.index', ['category_id' => $category->id]) }}" class="btn btn-sm btn-success">
                                                <i class="mdi mdi-format-list-bulleted"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger delete-category" data-id="{{ $category->id }}">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center">
                        <p>Bu menüye ait kategori bulunamadı.</p>
                        @if(isset($menu))
                        <a href="{{ route('admin.menusystem.categories.create', ['menu_id' => $menu->id]) }}" class="btn btn-primary">
                            <i class="mdi mdi-plus-circle mr-1"></i> Yeni Kategori Ekle
                        </a>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
<script>
    $(document).ready(function() {
        // Sürükle bırak ile sıralama
        new Sortable(document.getElementById('categories-list'), {
            handle: '.handle',
            animation: 150,
            onEnd: function() {
                updateCategoriesOrder();
            }
        });
        
        // Durum değiştirme
        $('.toggle-status').change(function() {
            const categoryId = $(this).data('id');
            const status = $(this).prop('checked') ? 1 : 0;
            
            $.ajax({
                url: '{{ route("admin.menusystem.categories.update-status") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: categoryId,
                    status: status
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success('Kategori durumu güncellendi');
                    } else {
                        toastr.error('Kategori durumu güncellenirken hata oluştu');
                        $('#categoryStatus' + categoryId).prop('checked', !status);
                    }
                },
                error: function() {
                    toastr.error('Kategori durumu güncellenirken hata oluştu');
                    $('#categoryStatus' + categoryId).prop('checked', !status);
                }
            });
        });
        
        // Kategori silme
        $('.delete-category').click(function() {
            const categoryId = $(this).data('id');
            
            Swal.fire({
                title: 'Emin misiniz?',
                text: 'Bu kategoriyi silmek istediğinize emin misiniz? Bu işlem geri alınamaz!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Evet, sil!',
                cancelButtonText: 'İptal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("admin.menusystem.categories.destroy", "") }}/' + categoryId,
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire(
                                    'Silindi!',
                                    'Kategori başarıyla silindi.',
                                    'success'
                                );
                                // Sayfayı yenile
                                location.reload();
                            } else {
                                Swal.fire(
                                    'Hata!',
                                    'Silme işlemi sırasında bir hata oluştu.',
                                    'error'
                                );
                            }
                        },
                        error: function() {
                            Swal.fire(
                                'Hata!',
                                'Silme işlemi sırasında bir hata oluştu.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
        
        // Kategorilerin sıralamasını güncelleme
        function updateCategoriesOrder() {
            const orderData = [];
            
            $('#categories-list tr').each(function(index) {
                orderData.push({
                    id: $(this).data('id'),
                    order: index
                });
            });
            
            $.ajax({
                url: '{{ route("admin.menusystem.categories.order") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    categories: orderData
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success('Sıralama başarıyla güncellendi');
                    } else {
                        toastr.error('Sıralama güncellenirken hata oluştu');
                    }
                },
                error: function() {
                    toastr.error('Sıralama güncellenirken hata oluştu');
                }
            });
        }
    });
</script>
@endsection 