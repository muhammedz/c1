@extends('adminlte::page')

@section('title', 'Dosya Yönetim Sistemi - Medya')

@section('content_header')
    <h1>Dosya Yönetim Sistemi</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.filemanagersystem.dashboard') }}">Ana Klasör</a></li>
                    
                    @if($folder)
                        @php
                        $breadcrumbs = [];
                        $parent = $folder;
                        
                        while($parent) {
                            $breadcrumbs[] = $parent;
                            $parent = $parent->parent;
                        }
                        
                        $breadcrumbs = array_reverse($breadcrumbs);
                        @endphp
                        
                        @foreach($breadcrumbs as $breadcrumb)
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.filemanagersystem.folders.index', ['parent_id' => $breadcrumb->id]) }}">
                                    {{ $breadcrumb->folder_name }}
                                </a>
                            </li>
                        @endforeach
                    @endif
                    
                    <li class="breadcrumb-item active" aria-current="page">Dosyalar</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ $folder ? route('admin.filemanagersystem.folders.index', ['parent_id' => $folder->id]) : route('admin.filemanagersystem.folders.index') }}" class="btn btn-secondary">
                <i class="fas fa-folder"></i> Klasörlere Dön
            </a>
            @if(isset($folder))
                <a href="{{ route('admin.filemanagersystem.media.create', ['folder_id' => $folder->id]) }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Yeni Dosya Yükle
                </a>
            @else
                <a href="{{ route('admin.filemanagersystem.media.create') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Yeni Dosya Yükle
                </a>
            @endif
        </div>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="row">
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-header">Hızlı Erişim</div>
                <div class="card-body">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.filemanagersystem.folders.index') }}">
                                <i class="fas fa-folder"></i> Ana Klasör
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('admin.filemanagersystem.media.index') }}">
                                <i class="fas fa-file"></i> Tüm Dosyalar
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.filemanagersystem.categories.index') }}">
                                <i class="fas fa-tags"></i> Kategoriler
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <span>{{ $folder ? "{$folder->folder_name} içindeki dosyalar" : 'Tüm Dosyalar' }}</span>
                    <span class="text-muted small ms-2">({{ $medias->total() }} dosya)</span>
                </div>
                
                <div class="card-body">
                    @if($medias->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 50px">#</th>
                                        <th>Dosya Adı</th>
                                        <th>Tür</th>
                                        <th>Boyut</th>
                                        <th>Yükleyen</th>
                                        <th>Tarih</th>
                                        <th>İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($medias as $media)
                                        <tr>
                                            <td>{{ $loop->iteration + $medias->firstItem() - 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas {{ $media->iconClass }} fa-lg me-2 text-muted"></i>
                                                    <span>{{ $media->original_name }}</span>
                                                    @if($media->isImage() && $media->hasWebpVersion)
                                                        <span class="badge bg-success ms-2">WebP</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>{{ $media->mime_type }}</td>
                                            <td>
                                                {{ $media->formattedSize }}
                                                @if($media->isImage() && $media->hasWebpVersion)
                                                    <br><small class="text-success">(WebP: {{ $media->formattedWebpSize }})</small>
                                                @endif
                                            </td>
                                            <td>{{ $media->user ? $media->user->name : 'Bilinmiyor' }}</td>
                                            <td>{{ $media->created_at->format('d.m.Y H:i') }}</td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('admin.filemanagersystem.media.show', ['media' => $media->id]) }}" class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.filemanagersystem.media.edit', ['media' => $media->id]) }}" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button class="btn btn-sm btn-success" disabled>
                                                        <i class="fas fa-download"></i>
                                                    </button>
                                                    <form action="{{ route('admin.filemanagersystem.media.destroy', ['media' => $media->id]) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bu dosyayı silmek istediğinizden emin misiniz?')">
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
                        
                        <div class="mt-4">
                            {{ $medias->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-file-upload fa-3x mb-3 text-muted"></i>
                            <p>Bu klasörde henüz dosya bulunmuyor.</p>
                            <a href="{{ route('admin.filemanagersystem.media.create', ['folder_id' => $folder ? $folder->id : null]) }}" class="btn btn-sm btn-success">
                                <i class="fas fa-upload"></i> Dosya Yükle
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@stop 