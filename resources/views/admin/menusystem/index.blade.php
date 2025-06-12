@extends('adminlte::page')

@section('title', 'Menü Yönetimi')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Menü Yönetimi</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Panel</a></li>
                <li class="breadcrumb-item active">Menü Yönetimi</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <form class="form-inline" method="GET" action="{{ route('admin.menusystem.index') }}">
                                <div class="form-group mb-2 mr-2">
                                    <select name="type" class="form-control">
                                        <option value="">Tüm Tipler</option>
                                        <option value="1" {{ request('type') == '1' ? 'selected' : '' }}>Küçük Menü</option>
                                        <option value="2" {{ request('type') == '2' ? 'selected' : '' }}>Büyük Menü</option>
                                        <option value="3" {{ request('type') == '3' ? 'selected' : '' }}>Buton Menü</option>
                                    </select>
                                </div>
                                <div class="form-group mb-2 mr-2">
                                    <select name="position" class="form-control">
                                        <option value="">Tüm Konumlar</option>
                                        <option value="header" {{ request('position') == 'header' ? 'selected' : '' }}>Üst Menü</option>
                                        <option value="footer" {{ request('position') == 'footer' ? 'selected' : '' }}>Alt Menü</option>
                                        <option value="sidebar" {{ request('position') == 'sidebar' ? 'selected' : '' }}>Yan Menü</option>
                                        <option value="mobile" {{ request('position') == 'mobile' ? 'selected' : '' }}>Mobil Menü</option>
                                    </select>
                                </div>
                                <div class="form-group mb-2 mr-2">
                                    <input type="text" name="search" class="form-control" placeholder="Ara..." value="{{ request('search') }}">
                                </div>
                                <button type="submit" class="btn btn-primary mb-2">Filtrele</button>
                                <a href="{{ route('admin.menusystem.index') }}" class="btn btn-secondary mb-2 ml-1">Sıfırla</a>
                            </form>
                        </div>
                        <div class="col-md-6 text-md-right">
                            <a href="{{ route('admin.menusystem.create') }}" class="btn btn-primary mb-2">
                                <i class="fas fa-plus-circle mr-1"></i> Yeni Menü
                            </a>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-centered table-striped dt-responsive nowrap w-100" id="menus-table">
                            <thead>
                                <tr>
                                    <th style="width: 5%">ID</th>
                                    <th style="width: 20%">Menü Adı</th>
                                    <th style="width: 15%">Tipi</th>
                                    <th style="width: 15%">Konumu</th>
                                    <th style="width: 10%">Öğe Sayısı</th>
                                    <th style="width: 10%">Sıralama</th>
                                    <th style="width: 10%">Durum</th>
                                    <th style="width: 15%">İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($menus as $menu)
                                <tr>
                                    <td>{{ $menu->id }}</td>
                                    <td>{{ $menu->name }}</td>
                                    <td>
                                        @if($menu->type == 1)
                                        <span class="badge badge-info">Küçük Menü</span>
                                        @elseif($menu->type == 2)
                                        <span class="badge badge-primary">Büyük Menü</span>
                                        @elseif($menu->type == 3)
                                        <span class="badge badge-warning">Buton Menü</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($menu->position == 'header')
                                        <span class="badge badge-success">Üst Menü</span>
                                        @elseif($menu->position == 'footer')
                                        <span class="badge badge-secondary">Alt Menü</span>
                                        @elseif($menu->position == 'sidebar')
                                        <span class="badge badge-warning">Yan Menü</span>
                                        @elseif($menu->position == 'mobile')
                                        <span class="badge badge-dark">Mobil Menü</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($menu->type == 2 || $menu->type == 3)
                                        <span class="badge badge-light">{{ $menu->items_count ?? 0 }}</span>
                                        @else
                                        <span class="badge badge-light">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $menu->order }}</td>
                                    <td>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input toggle-status" 
                                                id="menuStatus{{ $menu->id }}" 
                                                data-id="{{ $menu->id }}" 
                                                {{ $menu->status ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="menuStatus{{ $menu->id }}"></label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.menusystem.edit', $menu->id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if($menu->type == 2 || $menu->type == 3)
                                            <a href="{{ route('admin.menusystem.items', $menu->id) }}" class="btn btn-sm btn-success">
                                                <i class="fas fa-list"></i>
                                            </a>
                                            @endif
                                            <button type="button" class="btn btn-sm btn-danger delete-menu" data-id="{{ $menu->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">Henüz menü bulunmuyor</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="pagination justify-content-center mt-3">
                        {{ $menus->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Durum değiştirme
        $('.toggle-status').change(function() {
            const menuId = $(this).data('id');
            const status = $(this).prop('checked') ? 1 : 0;
            
            $.ajax({
                url: '{{ route("admin.menusystem.update-status") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: menuId,
                    status: status
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success('Menü durumu güncellendi');
                    } else {
                        toastr.error('Menü durumu güncellenirken hata oluştu');
                        // Hata durumunda switch'i eski haline getir
                        $('#menuStatus' + menuId).prop('checked', !status);
                    }
                },
                error: function() {
                    toastr.error('Menü durumu güncellenirken hata oluştu');
                    // Hata durumunda switch'i eski haline getir
                    $('#menuStatus' + menuId).prop('checked', !status);
                }
            });
        });
        
        // Menü silme
        $('.delete-menu').click(function() {
            const menuId = $(this).data('id');
            
            Swal.fire({
                title: 'Emin misiniz?',
                text: 'Bu menüyü silmek istediğinize emin misiniz? Bu işlem geri alınamaz!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Evet, sil!',
                cancelButtonText: 'İptal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/admin/menusystem/' + menuId,
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire(
                                'Silindi!',
                                'Menü başarıyla silindi.',
                                'success'
                            ).then(() => {
                                // Sayfayı yenile
                                window.location.reload();
                            });
                        },
                        error: function(xhr, status, error) {
                            console.log('Ajax Error: ', xhr.responseText);
                            Swal.fire(
                                'Hata!',
                                'Menü silinirken hata oluştu: ' + (xhr.responseJSON?.message || 'Bilinmeyen hata'),
                                'error'
                            );
                        }
                    });
                }
            });
        });
    });
</script>
@endsection