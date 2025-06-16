@extends('adminlte::page')

@section('title', 'Rehber Yerleri')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Rehber Yerleri</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Rehber Yerleri</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div><i class="fas fa-map-pin me-1"></i> Rehber Yerleri Listesi</div>
            <a href="{{ route('admin.guide-places.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Yeni Yer Ekle
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
                <div class="col-md-4">
                    <form method="GET" action="{{ route('admin.guide-places.index') }}">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Yer adı, içerik veya adres ara..." value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="col-md-3">
                    <form method="GET" action="{{ route('admin.guide-places.index') }}">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <select name="category_id" class="form-select" onchange="this.form.submit()">
                            <option value="">Tüm Kategoriler</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
                <div class="col-md-2">
                    <form method="GET" action="{{ route('admin.guide-places.index') }}">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <input type="hidden" name="category_id" value="{{ request('category_id') }}">
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="">Tüm Durumlar</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Pasif</option>
                        </select>
                    </form>
                </div>
                <div class="col-md-3">
                    @if(request('search') || request('category_id') || request('status'))
                        <a href="{{ route('admin.guide-places.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Filtreleri Temizle
                        </a>
                    @endif
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 60px;">Resim</th>
                            <th>Başlık</th>
                            <th>Kategori</th>
                            <th>Adres</th>
                            <th>İletişim</th>
                            <th style="width: 80px;">Durum</th>
                            <th style="width: 150px;">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($places as $place)
                        <tr>
                            <td class="text-center">
                                @if($place->images->count() > 0)
                                    <img src="{{ $place->images->first()->thumbnail_url }}" 
                                         alt="{{ $place->title }}" 
                                         class="img-thumbnail" 
                                         style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" 
                                         style="width: 50px; height: 50px;">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $place->title }}</strong>
                                <br>
                                <small class="text-muted">{{ $place->slug }}</small>
                                @if($place->images->count() > 0)
                                    <br>
                                    <small class="text-info">
                                        <i class="fas fa-images"></i> {{ $place->images->count() }} resim
                                    </small>
                                @endif
                            </td>
                            <td>
                                @if($place->category->icon)
                                    <i class="{{ $place->category->icon }} text-primary me-1"></i>
                                @endif
                                {{ $place->category->name }}
                            </td>
                            <td>
                                @if($place->address)
                                    <small>{{ Str::limit($place->address, 50) }}</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($place->phone)
                                    <small><i class="fas fa-phone text-success"></i> {{ $place->phone }}</small><br>
                                @endif
                                @if($place->email)
                                    <small><i class="fas fa-envelope text-info"></i> {{ $place->email }}</small>
                                @endif
                                @if(!$place->phone && !$place->email)
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-{{ $place->is_active ? 'success' : 'danger' }} toggle-status" 
                                        data-id="{{ $place->id }}" 
                                        data-status="{{ $place->is_active }}">
                                    {{ $place->is_active ? 'Aktif' : 'Pasif' }}
                                </button>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.guide-places.show', $place) }}" class="btn btn-info btn-sm" title="Görüntüle">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.guide-places.edit', $place) }}" class="btn btn-primary btn-sm" title="Düzenle">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.guide-places.destroy', $place) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Sil" onclick="return confirm('Bu yeri silmek istediğinize emin misiniz?')">
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
                                    <p>Henüz yer bulunmuyor.</p>
                                    <a href="{{ route('admin.guide-places.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> İlk Yeri Oluştur
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($places->hasPages())
                <div class="mt-3">
                    {{ $places->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function() {
        // Durum değiştirme
        $('.toggle-status').click(function() {
            var button = $(this);
            var placeId = button.data('id');
            var currentStatus = button.data('status');
            
            $.ajax({
                url: '{{ route("admin.guide-places.toggle-status", ":id") }}'.replace(':id', placeId),
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