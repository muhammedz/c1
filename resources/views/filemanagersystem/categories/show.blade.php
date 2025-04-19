@extends('layouts.admin')

@section('title', 'Kategori: ' . $category->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3">Kategori: {{ $category->name }}</h1>
                <div>
                    <a href="{{ route('filemanagersystem.categories.edit', $category->id) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Düzenle
                    </a>
                    <a href="{{ route('filemanagersystem.categories.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kategorilere Dön
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Kategori Bilgileri</h3>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <strong>ID:</strong> {{ $category->id }}
                        </li>
                        <li class="list-group-item">
                            <strong>Adı:</strong> {{ $category->name }}
                        </li>
                        <li class="list-group-item">
                            <strong>Slug:</strong> {{ $category->slug }}
                        </li>
                        <li class="list-group-item">
                            <strong>Üst Kategori:</strong> {{ $category->parent ? $category->parent->name : '-' }}
                        </li>
                        <li class="list-group-item">
                            <strong>Açıklama:</strong> {{ $category->description ?: 'Açıklama yok' }}
                        </li>
                        <li class="list-group-item">
                            <strong>Durum:</strong> 
                            @if ($category->is_active)
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-danger">Pasif</span>
                            @endif
                        </li>
                        <li class="list-group-item">
                            <strong>Oluşturulma:</strong> {{ $category->created_at->format('d.m.Y H:i') }}
                        </li>
                        <li class="list-group-item">
                            <strong>Son Güncelleme:</strong> {{ $category->updated_at->format('d.m.Y H:i') }}
                        </li>
                        <li class="list-group-item">
                            <strong>Oluşturan:</strong> {{ $category->createdBy ? $category->createdBy->name : 'Bilinmiyor' }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Bu Kategorideki Dosyalar</h3>
                </div>
                <div class="card-body">
                    @if($category->medias->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Önizleme</th>
                                        <th>Dosya Adı</th>
                                        <th>Tip</th>
                                        <th>Boyut</th>
                                        <th>Klasör</th>
                                        <th>İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($category->medias as $media)
                                        <tr>
                                            <td>{{ $media->id }}</td>
                                            <td class="text-center">
                                                @if($media->isImage())
                                                    <img src="{{ $media->url }}" alt="{{ $media->name }}" class="img-thumbnail" style="max-width: 50px; max-height: 50px;">
                                                @else
                                                    <i class="{{ $media->getIconClass() }} fa-2x"></i>
                                                @endif
                                            </td>
                                            <td>{{ $media->name }}</td>
                                            <td>{{ $media->mime_type }}</td>
                                            <td>{{ $media->human_readable_size }}</td>
                                            <td>{{ $media->folder ? $media->folder->name : '-' }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('filemanagersystem.medias.show', $media->id) }}" class="btn btn-xs btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('filemanagersystem.medias.edit', $media->id) }}" class="btn btn-xs btn-primary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="{{ $media->url }}" class="btn btn-xs btn-success" target="_blank">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">Bu kategoride henüz bir dosya bulunmuyor.</div>
                    @endif
                </div>
            </div>
            
            @if($category->children->count() > 0)
                <div class="card mt-4">
                    <div class="card-header">
                        <h3 class="card-title">Alt Kategoriler</h3>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            @foreach($category->children as $child)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>{{ $child->name }}</span>
                                    <div>
                                        <a href="{{ route('filemanagersystem.categories.show', $child->id) }}" class="btn btn-xs btn-info">
                                            <i class="fas fa-eye"></i> Görüntüle
                                        </a>
                                        <a href="{{ route('filemanagersystem.categories.edit', $child->id) }}" class="btn btn-xs btn-primary">
                                            <i class="fas fa-edit"></i> Düzenle
                                        </a>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 