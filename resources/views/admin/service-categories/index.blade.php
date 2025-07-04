@extends('adminlte::page')

@section('title', 'Hizmet Kategorileri')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Hizmet Kategorileri</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Hizmet Kategorileri</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div><i class="fas fa-table me-1"></i> Hizmet Kategorisi Listesi</div>
            <a href="{{ route('admin.service-categories.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Yeni Hizmet Kategorisi
            </a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            
            <table id="service-categories-table" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th style="width: 50px;">Sıra</th>
                        <th>Ad</th>
                        <th>Açıklama</th>
                        <th>Üst Hizmet Kategorisi</th>
                        <th style="width: 80px;">Durum</th>
                        <th style="width: 150px;">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="sortable">
                    @foreach($serviceCategories as $index => $serviceCategory)
                    <tr data-id="{{ $serviceCategory->id }}">
                        <td class="text-center handle">
                            <span class="btn btn-sm btn-light">
                                <i class="fas fa-arrows-alt"></i>
                            </span>
                        </td>
                        <td>
                            {{ $serviceCategory->name }}
                            @if($serviceCategory->icon)
                                <i class="{{ $serviceCategory->icon }} ms-1"></i>
                            @endif
                        </td>
                        <td>{{ Str::limit($serviceCategory->description, 50) }}</td>
                        <td>{{ $serviceCategory->parent ? $serviceCategory->parent->name : '-' }}</td>
                        <td class="text-center">
                            <span class="badge bg-{{ $serviceCategory->is_active ? 'success' : 'danger' }}">
                                {{ $serviceCategory->is_active ? 'Aktif' : 'Pasif' }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex">
                                <a href="{{ route('admin.service-categories.edit', $serviceCategory) }}" class="btn btn-primary btn-sm me-2">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.service-categories.destroy', $serviceCategory) }}" method="POST" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bu hizmet kategorisini silmek istediğinize emin misiniz?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="mt-3">
                {{ $serviceCategories->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $(function() {
        $(".sortable").sortable({
            handle: '.handle',
            update: function(event, ui) {
                var order = [];
                $('.sortable tr').each(function() {
                    order.push($(this).data('id'));
                });
                
                $.ajax({
                    url: '{{ route("admin.service-categories.update-order") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        serviceCategories: order
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success('Hizmet kategorisi sıralaması güncellendi');
                        } else {
                            toastr.error('Bir hata oluştu');
                        }
                    }
                });
            }
        });
    });
</script>
@endpush 