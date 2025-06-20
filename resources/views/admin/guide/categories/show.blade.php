@extends('adminlte::page')

@section('title', 'Rehber Kategorisi Detayı')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Rehber Kategorisi Detayı</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.guide-categories.index') }}">Rehber Kategorileri</a></li>
        <li class="breadcrumb-item active">{{ $guideCategory->name }}</li>
    </ol>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div><i class="fas fa-info-circle me-1"></i> Kategori Bilgileri</div>
                    <div>
                        <a href="{{ route('admin.guide-categories.edit', $guideCategory) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> Düzenle
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Kategori Adı:</strong>
                        </div>
                        <div class="col-md-9">
                            {{ $guideCategory->name }}
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>URL Slug:</strong>
                        </div>
                        <div class="col-md-9">
                            <code>{{ $guideCategory->slug }}</code>
                        </div>
                    </div>
                    
                    @if($guideCategory->description)
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Açıklama:</strong>
                        </div>
                        <div class="col-md-9">
                            {!! nl2br(e($guideCategory->description)) !!}
                        </div>
                    </div>
                    @endif
                    
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>İkon:</strong>
                        </div>
                        <div class="col-md-9">
                            @if($guideCategory->icon)
                                <i class="{{ $guideCategory->icon }} fa-lg text-primary me-2"></i>
                                <code>{{ $guideCategory->icon }}</code>
                            @else
                                <span class="text-muted">Belirlenmemiş</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Sıra:</strong>
                        </div>
                        <div class="col-md-9">
                            {{ $guideCategory->sort_order ?? 'Belirlenmemiş' }}
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Durum:</strong>
                        </div>
                        <div class="col-md-9">
                            <span class="badge bg-{{ $guideCategory->is_active ? 'success' : 'danger' }}">
                                {{ $guideCategory->is_active ? 'Aktif' : 'Pasif' }}
                            </span>
                        </div>
                    </div>
                    
                    @if($guideCategory->meta_title)
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Meta Başlık:</strong>
                        </div>
                        <div class="col-md-9">
                            {{ $guideCategory->meta_title }}
                        </div>
                    </div>
                    @endif
                    
                    @if($guideCategory->meta_description)
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Meta Açıklama:</strong>
                        </div>
                        <div class="col-md-9">
                            {{ $guideCategory->meta_description }}
                        </div>
                    </div>
                    @endif
                    
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Oluşturma Tarihi:</strong>
                        </div>
                        <div class="col-md-9">
                            {{ $guideCategory->created_at->format('d.m.Y H:i') }}
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Güncellenme Tarihi:</strong>
                        </div>
                        <div class="col-md-9">
                            {{ $guideCategory->updated_at->format('d.m.Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>
            
            @if($guideCategory->places && $guideCategory->places->count() > 0)
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-map-marker-alt me-1"></i> Bu Kategorideki Yerler ({{ $guideCategory->places->count() }})
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Yer Adı</th>
                                    <th>Durum</th>
                                    <th>Sıra</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($guideCategory->places as $place)
                                <tr>
                                    <td>
                                        <strong>{{ $place->title }}</strong>
                                        @if($place->images && $place->images->count() > 0)
                                            <i class="fas fa-image text-info ms-1" title="Görselli"></i>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $place->is_active ? 'success' : 'danger' }}">
                                            {{ $place->is_active ? 'Aktif' : 'Pasif' }}
                                        </span>
                                    </td>
                                    <td>{{ $place->sort_order ?? '-' }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.guide-places.show', $place) }}" class="btn btn-info btn-sm" title="Görüntüle">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.guide-places.edit', $place) }}" class="btn btn-primary btn-sm" title="Düzenle">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.guide-places.destroy', $place) }}" method="POST" style="display: inline;" onsubmit="return confirm('Bu yeri silmek istediğinize emin misiniz?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Sil">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.guide-places.index', ['category_id' => $guideCategory->id]) }}" class="btn btn-outline-primary">
                            <i class="fas fa-list"></i> Tüm Yerleri Görüntüle
                        </a>
                        <a href="{{ route('admin.guide-places.create', ['category_id' => $guideCategory->id]) }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Yeni Yer Ekle
                        </a>
                    </div>
                </div>
            </div>
            @else
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-map-marker-alt me-1"></i> Bu Kategorideki Yerler
                </div>
                <div class="card-body text-center">
                    <div class="text-muted">
                        <i class="fas fa-inbox fa-3x mb-3"></i>
                        <p>Bu kategoride henüz yer bulunmuyor.</p>
                        <a href="{{ route('admin.guide-places.create', ['category_id' => $guideCategory->id]) }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> İlk Yeri Ekle
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
        
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-bar me-1"></i> İstatistikler
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-primary">{{ $guideCategory->places->count() }}</h4>
                                <small class="text-muted">Toplam Yer</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success">{{ $guideCategory->places->where('is_active', true)->count() }}</h4>
                            <small class="text-muted">Aktif Yer</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-cogs me-1"></i> İşlemler
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.guide-categories.edit', $guideCategory) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Kategoriyi Düzenle
                        </a>
                        
                        @if($guideCategory->places->count() == 0)
                        <form action="{{ route('admin.guide-categories.destroy', $guideCategory) }}" method="POST" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Bu kategoriyi silmek istediğinize emin misiniz?')">
                                <i class="fas fa-trash"></i> Kategoriyi Sil
                            </button>
                        </form>
                        @else
                        <button type="button" class="btn btn-danger" disabled title="Bu kategoriye ait yerler bulunduğu için silinemez">
                            <i class="fas fa-trash"></i> Kategoriyi Sil
                        </button>
                        <small class="text-muted">Kategoriyi silmek için önce tüm yerleri silmelisiniz.</small>
                        @endif
                        
                        <a href="{{ route('admin.guide-categories.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kategori Listesine Dön
                        </a>
                    </div>
                </div>
            </div>
            
            @if($guideCategory->icon)
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-palette me-1"></i> İkon Önizleme
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="{{ $guideCategory->icon }}" style="font-size: 4rem; color: #007bff;"></i>
                    </div>
                    <code>{{ $guideCategory->icon }}</code>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection 