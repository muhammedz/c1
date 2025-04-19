@extends('adminlte::page')

@section('title', 'Dosya Detayları: ' . $media->name)

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <a href="{{ route('admin.filemanagersystem.media.index') }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left"></i> Medya Listesine Dön
            </a>
            
            @if($media->folder)
            <a href="{{ route('admin.filemanagersystem.folders.show', $media->folder->id) }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-folder"></i> {{ $media->folder->folder_name }}
            </a>
            @endif
            
            <div class="float-right">
                <a href="{{ route('admin.filemanagersystem.media.edit', ['media' => $media->id]) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Düzenle
                </a>
                <form action="{{ route('admin.filemanagersystem.media.destroy', ['media' => $media->id]) }}" method="POST" class="d-inline" id="delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Bu dosyayı silmek istediğinizden emin misiniz?')">
                        <i class="fas fa-trash"></i> Sil
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Dosya Detayları</h3>
                    <div class="card-tools">
                        @if($media->folder)
                            <a href="{{ route('admin.filemanagersystem.folders.show', $media->folder->id) }}" class="btn btn-sm btn-secondary">
                                <i class="fas fa-arrow-left mr-1"></i> Klasöre Dön
                            </a>
                        @else
                            <a href="{{ route('admin.filemanagersystem.media.index') }}" class="btn btn-sm btn-secondary">
                                <i class="fas fa-arrow-left mr-1"></i> Dosya Listesine Dön
                            </a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.filemanagersystem.folders.index') }}">Ana Klasör</a></li>
                                @if($media->folder)
                                    @if($media->folder->parent)
                                        <li class="breadcrumb-item"><a href="{{ route('admin.filemanagersystem.folders.show', $media->folder->parent->id) }}">{{ $media->folder->parent->folder_name }}</a></li>
                                    @endif
                                    <li class="breadcrumb-item"><a href="{{ route('admin.filemanagersystem.folders.show', $media->folder->id) }}">{{ $media->folder->folder_name }}</a></li>
                                @endif
                                <li class="breadcrumb-item active">{{ $media->original_name }}</li>
                            </ol>
                        </nav>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fas fa-check"></i> Başarılı!</h5>
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Dosya Önizleme</h5>
                                </div>
                                <div class="card-body text-center">
                                    @if(Str::startsWith($media->mime_type, 'image/'))
                                        <img src="{{ $media->url }}" alt="{{ $media->original_name }}" class="img-fluid mb-3 border">
                                    @elseif(Str::startsWith($media->mime_type, 'video/'))
                                        <div class="embed-responsive embed-responsive-16by9 mb-3">
                                            <video class="embed-responsive-item" controls>
                                                <source src="{{ $media->url }}" type="{{ $media->mime_type }}">
                                                Tarayıcınız video oynatmayı desteklemiyor.
                                            </video>
                                        </div>
                                    @elseif($media->mime_type == 'application/pdf')
                                        <div class="embed-responsive embed-responsive-16by9 mb-3">
                                            <embed class="embed-responsive-item" src="{{ $media->url }}" type="application/pdf">
                                        </div>
                                    @else
                                        <div class="py-5">
                                            <i class="{{ $media->getIconClass() }} fa-5x text-secondary mb-3"></i>
                                            <p class="mt-3">Bu dosya türü önizleme için desteklenmiyor.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Dosya Bilgileri</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <th style="width: 140px;">Dosya Adı:</th>
                                                <td>{{ $media->original_name }}</td>
                                            </tr>
                                            <tr>
                                                <th>Sistem Adı:</th>
                                                <td>{{ $media->name }}</td>
                                            </tr>
                                            <tr>
                                                <th>Dosya Tipi:</th>
                                                <td>{{ $media->mime_type }}</td>
                                            </tr>
                                            <tr>
                                                <th>Uzantı:</th>
                                                <td>.{{ $media->extension }}</td>
                                            </tr>
                                            <tr>
                                                <th>Dosya Boyutu:</th>
                                                <td>{{ $media->getFormattedSize() }}</td>
                                            </tr>
                                            <tr>
                                                <th>Klasör:</th>
                                                <td>
                                                    @if($media->folder)
                                                        <a href="{{ route('admin.filemanagersystem.folders.show', $media->folder->id) }}">
                                                            {{ $media->folder->folder_name }}
                                                        </a>
                                                    @else
                                                        <span class="text-muted">Ana Klasör</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Erişim:</th>
                                                <td>
                                                    @if($media->is_public)
                                                        <span class="badge badge-success">Herkese Açık</span>
                                                    @else
                                                        <span class="badge badge-secondary">Sadece Yetkililer</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Yükleyen:</th>
                                                <td>{{ $media->user ? $media->user->name : 'Bilinmiyor' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Yükleme Tarihi:</th>
                                                <td>{{ $media->created_at->format('d.m.Y H:i') }}</td>
                                            </tr>
                                            @if($media->created_at != $media->updated_at)
                                            <tr>
                                                <th>Son Güncelleme:</th>
                                                <td>{{ $media->updated_at->format('d.m.Y H:i') }}</td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                    
                                    @if($media->is_public)
                                    <div class="mt-4">
                                        <label for="public_url">Genel URL:</label>
                                        <div class="input-group">
                                            <input type="text" id="public_url" class="form-control" value="{{ $media->url }}" readonly>
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('public_url')">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <small class="form-text text-muted">Bu URL ile dosya kimlik doğrulama olmadan erişilebilir.</small>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function copyToClipboard(elementId) {
    var copyText = document.getElementById(elementId);
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    document.execCommand("copy");
    
    // Kopya bildirimini göster
    var tooltip = document.createElement("div");
    tooltip.textContent = "Kopyalandı!";
    tooltip.style.position = "fixed";
    tooltip.style.left = "50%";
    tooltip.style.top = "20%";
    tooltip.style.transform = "translate(-50%, -50%)";
    tooltip.style.padding = "8px 16px";
    tooltip.style.background = "rgba(0,0,0,0.7)";
    tooltip.style.color = "white";
    tooltip.style.borderRadius = "4px";
    tooltip.style.zIndex = "9999";
    document.body.appendChild(tooltip);
    
    setTimeout(function() {
        document.body.removeChild(tooltip);
    }, 1500);
}
</script>
@endpush
@endsection 