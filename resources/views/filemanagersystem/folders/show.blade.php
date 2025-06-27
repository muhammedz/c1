@extends('adminlte::page')

@section('title', 'Klasör: ' . $folder->name)

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Klasör: {{ $folder->name }}</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.filemanagersystem.index') }}">Dosya Yönetim Sistemi</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.filemanagersystem.folders.index') }}">Klasörler</a></li>
                <li class="breadcrumb-item active">{{ $folder->name }}</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <!-- Klasör Bilgileri Kartı -->
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">Klasör Bilgileri</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Klasör Adı:</strong> {{ $folder->name }}</p>
                        <p><strong>Klasör Yolu:</strong> {{ $folder->path }}</p>
                        <p><strong>Üst Klasör:</strong> {{ $parentFolder ? $parentFolder->name : 'Ana Klasör' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Oluşturma Tarihi:</strong> {{ $folder->created_at->format('d.m.Y H:i') }}</p>
                        <p><strong>Dosya Sayısı:</strong> {{ $medias->count() }}</p>
                        <p><strong>Alt Klasör Sayısı:</strong> {{ $subfolders->count() }}</p>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <a href="{{ route('admin.filemanagersystem.folders.edit', $folder->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Düzenle
                        </a>
                        <form action="{{ route('admin.filemanagersystem.folders.destroy', $folder->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Bu klasörü silmek istediğinizden emin misiniz?')">
                                <i class="fas fa-trash"></i> Sil
                            </button>
                        </form>
                        <a href="{{ route('admin.filemanagersystem.index') }}" class="btn btn-success">
                            <i class="fas fa-upload"></i> Dosya Yükle
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alt Klasörler Kartı -->
        @if($subfolders->count() > 0)
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">Alt Klasörler</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Klasör Adı</th>
                                <th>Dosya Sayısı</th>
                                <th>Oluşturma Tarihi</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subfolders as $subfolder)
                                <tr>
                                    <td>
                                        <i class="fas fa-folder text-warning"></i>
                                        <a href="{{ route('admin.filemanagersystem.folders.show', $subfolder->id) }}">
                                            {{ $subfolder->name }}
                                        </a>
                                    </td>
                                    <td>{{ $subfolder->medias->count() }}</td>
                                    <td>{{ $subfolder->created_at->format('d.m.Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('admin.filemanagersystem.folders.edit', $subfolder->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.filemanagersystem.folders.destroy', $subfolder->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bu klasörü silmek istediğinizden emin misiniz?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        <!-- Dosyalar Kartı -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Dosyalar</h3>
            </div>
            <div class="card-body">
                @if($medias->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Önizleme</th>
                                    <th>Dosya Adı</th>
                                    <th>Tür</th>
                                    <th>Boyut</th>
                                    <th>Oluşturma Tarihi</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($medias as $media)
                                    <tr>
                                        <td class="text-center">
                                            @if(strstr($media->mime_type, 'image/'))
                                                <img src="{{ asset($media->url) }}" alt="{{ $media->name }}" class="img-thumbnail" style="max-width: 50px; max-height: 50px;">
                                            @else
                                                <i class="fas fa-file fa-2x"></i>
                                            @endif
                                        </td>
                                        <td>{{ $media->name }}</td>
                                        <td>{{ $media->mime_type }}</td>
                                        <td>{{ $media->size }} byte</td>
                                        <td>{{ $media->created_at->format('d.m.Y H:i') }}</td>
                                        <td>
                                            <a href="{{ route('admin.filemanagersystem.preview', $media->id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-primary" onclick="alert('Ana dosya yönetim sayfasından düzenleyebilirsiniz.')">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('admin.filemanagersystem.media.destroy', $media->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bu dosyayı silmek istediğinizden emin misiniz?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info">
                        Bu klasörde henüz dosya bulunmuyor.
                    </div>
                @endif
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        console.log('Hi!');
    </script>
@stop 