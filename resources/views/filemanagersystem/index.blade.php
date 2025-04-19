@extends('adminlte::page')

@section('title', 'Dosya Yönetim Sistemi')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Dosya Yönetim Sistemi</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Dosya Yönetim Sistemi</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <!-- Sol Panel - Klasör ve Kategori Ağacı -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Klasörler</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="nav flex-column nav-pills" id="folder-tree">
                        @foreach($folders as $folder)
                            @include('filemanagersystem.partials.folder-item', ['folder' => $folder])
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">Kategoriler</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="nav flex-column nav-pills" id="category-tree">
                        @foreach($categories as $category)
                            @include('filemanagersystem.partials.category-item', ['category' => $category])
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Sağ Panel - Dosya Listesi -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Dosyalar</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.filemanagersystem.media.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-upload"></i> Dosya Yükle
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Arama ve Filtreleme -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Dosya ara...">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="btn-group float-right">
                                <button type="button" class="btn btn-default">
                                    <i class="fas fa-th-large"></i>
                                </button>
                                <button type="button" class="btn btn-default">
                                    <i class="fas fa-list"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Dosya Listesi -->
                    <div class="row" id="file-list">
                        @foreach($recentFiles as $file)
                            <div class="col-md-3 col-sm-6">
                                <div class="card file-item">
                                    <div class="card-body text-center">
                                        @if($file->isImage())
                                            @if($file->has_webp)
                                                <picture>
                                                    <source srcset="{{ asset($file->webp_url) }}" type="image/webp">
                                                    <source srcset="{{ asset($file->url) }}" type="{{ $file->mime_type }}">
                                                    <img src="{{ asset($file->url) }}" alt="{{ $file->name }}" class="img-fluid mb-2" style="max-height: 120px;">
                                                </picture>
                                            @else
                                                <img src="{{ asset($file->url) }}" alt="{{ $file->name }}" class="img-fluid mb-2" style="max-height: 120px;">
                                            @endif
                                        @else
                                            <i class="fas {{ $file->getIconClassAttribute() }} fa-3x mb-2"></i>
                                        @endif
                                        <h6 class="card-title">{{ $file->original_name ?? $file->name }}</h6>
                                        <p class="card-text small text-muted">
                                            {{ $file->getHumanReadableSizeAttribute() }}
                                            @if($file->isImage() && $file->has_webp)
                                                <br><span class="badge badge-success">WebP: {{ $file->formattedWebpSize ?? round(filesize(public_path('uploads/' . $file->webp_path)) / 1024, 2) . ' KB' }}</span>
                                            @endif
                                        </p>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.filemanagersystem.preview', $file->id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.filemanagersystem.media.edit', $file->id) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="deleteFile({{ $file->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .file-item {
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            height: 100%;
        }
        .file-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .file-item .card-body {
            padding: 1rem;
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .file-item .card-title {
            margin-top: 0.5rem;
            font-size: 0.9rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .file-item .btn-group {
            margin-top: auto;
        }
        .file-item img {
            object-fit: contain;
            width: 100%;
            height: 120px;
            margin: 0 auto;
        }
        .file-item i.fas {
            color: #6c757d;
        }
    </style>
@stop

@section('js')
    <script>
        function deleteFile(id) {
            if (confirm('Bu dosyayı silmek istediğinizden emin misiniz?')) {
                // CSRF token al
                var token = '{{ csrf_token() }}';
                
                // Form oluştur
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = '/admin/filemanagersystem/media/' + id;
                form.style.display = 'none';
                
                // CSRF token input
                var csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = token;
                form.appendChild(csrfInput);
                
                // Method override input
                var methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);
                
                // Formu sayfaya ekle ve gönder
                document.body.appendChild(form);
                form.submit();
                
                return false;
            }
        }
    </script>
@stop 