@extends('adminlte::page')

@section('title', 'Dosya Düzenle: ' . $media->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Dosya Düzenle</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.filemanagersystem.media.show', ['media' => $media->id]) }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-eye"></i> Görüntüle
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.filemanagersystem.folders.index') }}">Ana Klasör</a></li>
                                @if($media->folder)
                                    @if($media->folder->parent)
                                        <li class="breadcrumb-item"><a href="{{ route('admin.filemanagersystem.folders.show', ['id' => $media->folder->parent->id]) }}">{{ $media->folder->parent->folder_name }}</a></li>
                                    @endif
                                    <li class="breadcrumb-item"><a href="{{ route('admin.filemanagersystem.folders.show', ['id' => $media->folder->id]) }}">{{ $media->folder->folder_name }}</a></li>
                                @endif
                                <li class="breadcrumb-item"><a href="{{ route('admin.filemanagersystem.media.show', ['media' => $media->id]) }}">{{ $media->original_name }}</a></li>
                                <li class="breadcrumb-item active">Düzenle</li>
                            </ol>
                        </nav>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-8">
                            <form action="{{ route('admin.filemanagersystem.media.update', ['media' => $media->id]) }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="form-group">
                                    <label for="name">Dosya Adı <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $media->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Bu isim sistem tarafından kullanılır. Dosya uzantısını (.pdf, .jpg, vb.) eklemeyin.</small>
                                </div>

                                <div class="form-group">
                                    <label for="folder_id">Klasör</label>
                                    <select class="form-control @error('folder_id') is-invalid @enderror" id="folder_id" name="folder_id">
                                        <option value="">-- Ana Klasör --</option>
                                        @foreach($folders as $folder)
                                            <option value="{{ $folder->id }}" {{ old('folder_id', $media->folder_id) == $folder->id ? 'selected' : '' }}>
                                                @if($folder->parent)
                                                    {{ $folder->parent->folder_name }} / {{ $folder->folder_name }}
                                                @else
                                                    {{ $folder->folder_name }}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('folder_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="is_public" name="is_public" value="1" 
                                               {{ old('is_public', $media->is_public) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_public">Herkese Açık</label>
                                    </div>
                                    <small class="form-text text-muted">Eğer işaretlenirse, dosya kimlik doğrulama olmadan da erişilebilir olur.</small>
                                </div>

                                <div class="form-group mb-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Güncelle
                                    </button>
                                    <a href="{{ route('admin.filemanagersystem.media.show', ['media' => $media->id]) }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> İptal
                                    </a>
                                </div>
                            </form>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Dosya Önizleme</h5>
                                </div>
                                <div class="card-body text-center">
                                    @if(Str::startsWith($media->mime_type, 'image/'))
                                        <img src="{{ $media->url }}" alt="{{ $media->original_name }}" class="img-fluid mb-3 border" style="max-height: 200px;">
                                    @else
                                        <i class="{{ $media->getIconClass() }} fa-5x text-secondary mb-3"></i>
                                    @endif
                                    
                                    <h5 class="mb-1">{{ $media->original_name }}</h5>
                                    <p class="text-muted">
                                        {{ $media->mime_type }} | {{ $media->getFormattedSize() }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 