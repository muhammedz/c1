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
            <div><i class="fas fa-table me-1"></i> Haber Kategorisi Listesi</div>
            <a href="{{ route('admin.news-categories.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Yeni Haber Kategorisi
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
            
            <!-- Arama ve Filtreleme -->
            <div class="search-container p-2 rounded mb-3 d-flex flex-wrap align-items-center gap-2" style="background-color: #f8f9fa;">
                <div class="dropdown-filter">
                    <form action="{{ route('admin.news-categories.index') }}" method="GET" id="status-form">
                        @if(request()->has('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        <select id="status-filter" name="status" class="form-select form-select-sm border-0 bg-light text-secondary" onchange="document.getElementById('status-form').submit()">
                            <option value="">Tüm Durumlar</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Pasif</option>
                        </select>
                    </form>
                </div>
                
                <div class="ms-auto search-box">
                    <form action="{{ route('admin.news-categories.index') }}" method="GET" class="m-0">
                        @if(request()->has('status'))
                            <input type="hidden" name="status" value="{{ request('status') }}">
                        @endif
                        <div class="input-group border-0 bg-light rounded pe-0">
                            <span class="input-group-text border-0 bg-transparent px-2">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="search" name="search" id="custom-search" class="form-control form-control-sm border-0 bg-light shadow-none" placeholder="Kategori adı veya açıklama ara..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-sm btn-primary">Ara</button>
                        </div>
                    </form>
                </div>
                
                @if(request()->anyFilled(['search', 'status']))
                <div>
                    <a href="{{ route('admin.news-categories.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-times-circle me-1"></i> Filtreleri Temizle
                    </a>
                </div>
                @endif
            </div>
            
            <table id="news-categories-table" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th style="width: 50px;">Sıra</th>
                        <th>Ad</th>
                        <th>Açıklama</th>
                        <th>Üst Haber Kategorisi</th>
                        <th style="width: 80px;">Durum</th>
                        <th style="width: 150px;">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="sortable">
                    @foreach($newsCategories as $index => $newsCategory)
                    <tr data-id="{{ $newsCategory->id }}">
                        <td class="text-center handle">
                            <span class="btn btn-sm btn-light">
                                <i class="fas fa-arrows-alt"></i>
                            </span>
                        </td>
                        <td>
                            {{ $newsCategory->name }}
                            @if($newsCategory->icon)
                                <i class="{{ $newsCategory->icon }} ms-1"></i>
                            @endif
                        </td>
                        <td>{{ Str::limit($newsCategory->description, 50) }}</td>
                        <td>{{ $newsCategory->parent ? $newsCategory->parent->name : '-' }}</td>
                        <td class="text-center">
                            <span class="badge bg-{{ $newsCategory->is_active ? 'success' : 'danger' }}">
                                {{ $newsCategory->is_active ? 'Aktif' : 'Pasif' }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex">
                                <a href="{{ route('admin.news-categories.edit', $newsCategory->id) }}" class="btn btn-primary btn-sm me-2">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.news-categories.destroy', $newsCategory->id) }}" method="POST" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bu haber kategorisini silmek istediğinize emin misiniz?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <!-- Laravel Pagination Linkleri -->
            <div class="pagination-wrapper">
                <div class="mt-3">
                    {{ $newsCategories->links('custom.pagination') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Pagination Stilleri */
.pagination {
    display: flex !important;
    justify-content: center !important;
    margin-top: 20px !important;
}

.pagination .page-item .page-link {
    display: inline-block !important;
    padding: 8px 12px !important;
    font-size: 14px !important;
    font-weight: 500 !important;
    color: #374151 !important;
    background-color: white !important;
    border: 1px solid #e5e7eb !important;
    border-radius: 8px !important;
    text-decoration: none !important;
    margin: 0 2px !important;
    transition: all 0.2s ease !important;
}

.pagination .page-item .page-link:hover {
    background-color: #f3f4f6 !important;
    color: #00352b !important;
    border-color: #00352b !important;
}

.pagination .page-item.active .page-link {
    color: white !important;
    background-color: #00352b !important;
    border-color: #00352b !important;
    box-shadow: 0 2px 4px rgba(0,53,43,0.3) !important;
}

.pagination .page-item.disabled .page-link {
    color: #9ca3af !important;
    background-color: #f3f4f6 !important;
    border-color: #e5e7eb !important;
    cursor: not-allowed !important;
}

.pagination .page-link svg,
.pagination .page-link path,
.pagination .page-item .page-link svg,
.pagination .page-item .page-link path {
    fill: currentColor !important;
    stroke: currentColor !important;
    width: 16px !important;
    height: 16px !important;
}

.pagination .page-item:first-child .page-link,
.pagination .page-item:last-child .page-link {
    border-radius: 8px !important;
}

/* Pagination container */
.pagination-wrapper {
    display: flex !important;
    justify-content: center !important;
    margin: 20px 0 !important;
}

/* Arama ve Filtreleme Stilleri */
.search-container {
    background-color: #f8f9fa !important;
    border: 1px solid #e9ecef !important;
    border-radius: 8px !important;
}

.dropdown-filter {
    min-width: 150px !important;
}

.dropdown-filter .form-select {
    background-color: #e9ecef !important;
    border: none !important;
    font-size: 14px !important;
    padding: 6px 12px !important;
}

.dropdown-filter .form-select:focus {
    background-color: #e9ecef !important;
    border-color: #00352b !important;
    box-shadow: 0 0 0 0.2rem rgba(0, 53, 43, 0.25) !important;
}

.search-box {
    min-width: 300px !important;
}

.search-box .input-group {
    background-color: #e9ecef !important;
    border-radius: 6px !important;
}

.search-box .form-control {
    background-color: transparent !important;
    border: none !important;
    font-size: 14px !important;
}

.search-box .form-control:focus {
    background-color: transparent !important;
    border: none !important;
    box-shadow: none !important;
}
</style>
@endpush

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
                    url: '{{ route("admin.news-categories.update-order") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        newsCategories: order
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success('Haber kategorisi sıralaması güncellendi');
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