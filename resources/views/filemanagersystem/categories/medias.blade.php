@extends('adminlte::page')

@section('title', 'Kategori: ' . $category->name)

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="{{ route('filemanagersystem.categories.index') }}">Kategoriler</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div>
                            <div class="btn-group">
                                <a href="{{ route('filemanagersystem.categories.edit', $category) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i> Kategoriyi Düzenle
                                </a>
                                <a href="{{ route('filemanagersystem.medias.create', ['category_id' => $category->id]) }}" class="btn btn-sm btn-success">
                                    <i class="fas fa-upload"></i> Dosya Yükle
                                </a>
                                <a href="{{ route('filemanagersystem.categories.index') }}" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-list"></i> Tüm Kategoriler
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="mb-4">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h5 class="card-title">{{ $category->name }}</h5>
                                @if($category->description)
                                    <p class="card-text">{{ $category->description }}</p>
                                @endif
                                <div class="mt-2">
                                    <span class="badge bg-info">Dosya Sayısı: {{ $medias->total() }}</span>
                                    @if($category->parent)
                                        <span class="badge bg-secondary">Üst Kategori: {{ $category->parent->name }}</span>
                                    @endif
                                    <span class="badge {{ $category->is_active ? 'bg-success' : 'bg-danger' }}">
                                        {{ $category->is_active ? 'Aktif' : 'Pasif' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($medias->isEmpty())
                        <div class="alert alert-info">
                            Bu kategoride henüz dosya bulunmuyor. <a href="{{ route('filemanagersystem.medias.create', ['category_id' => $category->id]) }}" class="alert-link">Dosya yükle</a>.
                        </div>
                    @else
                        <div class="row">
                            @foreach($medias as $media)
                                <div class="col-md-3 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body text-center">
                                            <div style="height: 150px; display: flex; align-items: center; justify-content: center;">
                                                @if(in_array($media->mime_type, ['image/jpeg', 'image/png', 'image/gif', 'image/webp']))
                                                    <img src="{{ $media->url }}" alt="{{ $media->original_name }}" class="img-fluid" style="max-height: 150px;">
                                                @else
                                                    <div class="display-4">
                                                        <i class="{{ $media->getIconClass() }}"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <h6 class="card-title mt-3" title="{{ $media->original_name }}">
                                                {{ Str::limit($media->original_name, 20) }}
                                            </h6>
                                            
                                            <p class="card-text small">
                                                <span class="badge bg-secondary">{{ strtoupper($media->extension) }}</span>
                                                <span class="badge bg-info">{{ $media->getFormattedSize() }}</span>
                                            </p>
                                        </div>
                                        <div class="card-footer bg-transparent border-top-0">
                                            <div class="btn-group btn-group-sm w-100">
                                                <a href="{{ route('filemanagersystem.medias.show', $media) }}" class="btn btn-outline-primary" title="Görüntüle">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('filemanagersystem.medias.edit', $media) }}" class="btn btn-outline-secondary" title="Düzenle">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('filemanagersystem.medias.download', $media) }}" class="btn btn-outline-success" title="İndir">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                                <button type="button" class="btn btn-outline-danger" 
                                                        onclick="if(confirm('Bu dosyayı silmek istediğinizden emin misiniz?')) { document.getElementById('delete-form-{{ $media->id }}').submit(); }" title="Sil">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                <form id="delete-form-{{ $media->id }}" action="{{ route('filemanagersystem.medias.destroy', $media) }}" method="POST" class="d-none">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            {{ $medias->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 