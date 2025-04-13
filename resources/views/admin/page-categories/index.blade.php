@extends('adminlte::page')

@section('title', 'Sayfa Kategorileri')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Sayfa Kategorileri</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Sayfa Kategorileri</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div><i class="fas fa-table me-1"></i> Sayfa Kategorisi Listesi</div>
            <a href="{{ route('admin.page-categories.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Yeni Sayfa Kategorisi
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
            
            <table id="page-categories-table" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th style="width: 50px;">Sıra</th>
                        <th>Ad</th>
                        <th>Açıklama</th>
                        <th>Üst Sayfa Kategorisi</th>
                        <th style="width: 80px;">Durum</th>
                        <th style="width: 150px;">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="sortable">
                    @foreach($pageCategories as $index => $pageCategory)
                    <tr data-id="{{ $pageCategory->id }}">
                        <td class="text-center handle">
                            <span class="btn btn-sm btn-light">
                                <i class="fas fa-arrows-alt"></i>
                            </span>
                        </td>
                        <td>
                            {{ $pageCategory->name }}
                            @if($pageCategory->icon)
                                <i class="{{ $pageCategory->icon }} ms-1"></i>
                            @endif
                        </td>
                        <td>{{ Str::limit($pageCategory->description, 50) }}</td>
                        <td>{{ $pageCategory->parent ? $pageCategory->parent->name : '-' }}</td>
                        <td class="text-center">
                            <span class="badge bg-{{ $pageCategory->is_active ? 'success' : 'danger' }}">
                                {{ $pageCategory->is_active ? 'Aktif' : 'Pasif' }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex">
                                <a href="{{ route('admin.page-categories.edit', $pageCategory->id) }}" class="btn btn-primary btn-sm me-2">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.page-categories.destroy', $pageCategory->id) }}" method="POST" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bu sayfa kategorisini silmek istediğinize emin misiniz?')">
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
                {{ $pageCategories->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    .handle {
        cursor: move;
    }
</style>
@endpush

@push('js')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $(function() {
        $('.sortable').sortable({
            handle: '.handle',
            axis: 'y',
            update: function() {
                var order = [];
                $('.sortable tr').each(function() {
                    order.push($(this).data('id'));
                });
                
                $.ajax({
                    url: '{{ route("admin.page-categories.update-order") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        pageCategories: order
                    },
                    success: function(response) {
                        if (response.success) {
                            // Başarılı mesajı göster
                            toastr.success('Sıralama başarıyla güncellendi.');
                        }
                    },
                    error: function() {
                        // Hata mesajı göster
                        toastr.error('Sıralama güncellenirken bir hata oluştu.');
                        
                        // Sıralamayı geri al
                        $('.sortable').sortable('cancel');
                    }
                });
            }
        });
        
        // Silme işlemi için onay
        $('.delete-form').on('submit', function(e) {
            if (!confirm('Bu kategoriyi silmek istediğinize emin misiniz?')) {
                e.preventDefault();
            }
        });
    });
</script>
@endpush 