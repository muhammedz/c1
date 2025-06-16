@extends('adminlte::page')

@section('title', 'Rehber Kategorileri')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Rehber Kategorileri</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Rehber Kategorileri</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div><i class="fas fa-map-marker-alt me-1"></i> Rehber Kategorisi Listesi</div>
            <a href="{{ route('admin.guide-categories.create') }}" class="btn btn-primary btn-sm">
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
            
            <!-- Filtreler -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <form method="GET" action="{{ route('admin.guide-categories.index') }}">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Kategori adı veya açıklama ara..." value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="col-md-3">
                    <form method="GET" action="{{ route('admin.guide-categories.index') }}">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="">Tüm Durumlar</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Pasif</option>
                        </select>
                    </form>
                </div>
                <div class="col-md-3">
                    @if(request('search') || request('status'))
                        <a href="{{ route('admin.guide-categories.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Filtreleri Temizle
                        </a>
                    @endif
                </div>
            </div>
            
            <table id="guide-categories-table" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th style="width: 50px;">Sıra</th>
                        <th>Ad</th>
                        <th>Açıklama</th>
                        <th>İkon</th>
                        <th style="width: 80px;">Yer Sayısı</th>
                        <th style="width: 80px;">Durum</th>
                        <th style="width: 150px;">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="sortable">
                    @forelse($categories as $category)
                    <tr data-id="{{ $category->id }}">
                        <td class="text-center handle">
                            <span class="btn btn-sm btn-light">
                                <i class="fas fa-arrows-alt"></i>
                            </span>
                        </td>
                        <td>
                            <strong>{{ $category->name }}</strong>
                            <br>
                            <small class="text-muted">{{ $category->slug }}</small>
                        </td>
                        <td>{{ Str::limit($category->description, 80) }}</td>
                        <td class="text-center">
                            @if($category->icon)
                                <i class="{{ $category->icon }} fa-lg text-primary"></i>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge bg-info">{{ $category->places_count ?? 0 }}</span>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-{{ $category->is_active ? 'success' : 'danger' }} toggle-status" 
                                    data-id="{{ $category->id }}" 
                                    data-status="{{ $category->is_active }}">
                                {{ $category->is_active ? 'Aktif' : 'Pasif' }}
                            </button>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.guide-categories.show', $category) }}" class="btn btn-info btn-sm" title="Görüntüle">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.guide-categories.edit', $category) }}" class="btn btn-primary btn-sm" title="Düzenle">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.guide-categories.destroy', $category) }}" method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Sil" onclick="return confirm('Bu kategoriyi silmek istediğinize emin misiniz?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <div class="text-muted">
                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                <p>Henüz kategori bulunmuyor.</p>
                                <a href="{{ route('admin.guide-categories.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> İlk Kategoriyi Oluştur
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            @if($categories->hasPages())
                <div class="mt-3">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $(function() {
        // Sıralama işlevi
        $(".sortable").sortable({
            handle: '.handle',
            update: function(event, ui) {
                var items = [];
                $('.sortable tr').each(function(index) {
                    items.push({
                        id: $(this).data('id'),
                        sort_order: index + 1
                    });
                });
                
                $.ajax({
                    url: '{{ route("admin.guide-categories.update-order") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        items: items
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
                        toastr.error('Sıralama güncellenirken bir hata oluştu.');
                    }
                });
            }
        });
        
        // Durum değiştirme
        $('.toggle-status').click(function() {
            var button = $(this);
            var categoryId = button.data('id');
            var currentStatus = button.data('status');
            
            $.ajax({
                url: '{{ route("admin.guide-categories.toggle-status", ":id") }}'.replace(':id', categoryId),
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        // Buton görünümünü güncelle
                        if (response.status) {
                            button.removeClass('btn-danger').addClass('btn-success').text('Aktif');
                        } else {
                            button.removeClass('btn-success').addClass('btn-danger').text('Pasif');
                        }
                        button.data('status', response.status);
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('Durum değiştirilirken bir hata oluştu.');
                }
            });
        });
    });
</script>
@endpush 