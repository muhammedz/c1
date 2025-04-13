@extends('adminlte::page')

@section('title', 'Haber Kategorileri')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Haber Kategorileri</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Haber Kategorileri</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div><i class="fas fa-table me-1"></i> Kategori Listesi</div>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Yeni Kategori
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
            
            <table id="categories-table" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th style="width: 50px;">Sıra</th>
                        <th>Ad</th>
                        <th>Açıklama</th>
                        <th>Üst Kategori</th>
                        <th style="width: 80px;">Durum</th>
                        <th style="width: 150px;">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="sortable">
                    @foreach($categories as $index => $category)
                    <tr data-id="{{ $category->id }}">
                        <td class="text-center handle">
                            <span class="btn btn-sm btn-light">
                                <i class="fas fa-arrows-alt"></i>
                            </span>
                        </td>
                        <td>
                            {{ $category->name }}
                            @if($category->icon)
                                <i class="{{ $category->icon }} ms-1"></i>
                            @endif
                        </td>
                        <td>{{ Str::limit($category->description, 50) }}</td>
                        <td>{{ $category->parent ? $category->parent->name : '-' }}</td>
                        <td class="text-center">
                            <span class="badge bg-{{ $category->is_active ? 'success' : 'danger' }}">
                                {{ $category->is_active ? 'Aktif' : 'Pasif' }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex">
                                <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-primary btn-sm me-2">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bu kategoriyi silmek istediğinize emin misiniz?')">
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
                {{ $categories->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
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
                    url: '{{ route("admin.categories.update-order") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        order: order
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success('Kategori sıralaması güncellendi');
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