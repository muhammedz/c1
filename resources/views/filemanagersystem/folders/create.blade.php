@extends('adminlte::page')

@section('title', 'Dosya Yönetim Sistemi - Yeni Klasör')

@section('content_header')
    <h1>Yeni Klasör Oluştur</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.filemanagersystem.dashboard') }}">Ana Klasör</a></li>
                    
                    @if($parentFolder)
                        @php
                        $breadcrumbs = [];
                        $parent = $parentFolder;
                        
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
                    
                    <li class="breadcrumb-item active" aria-current="page">Yeni Klasör</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.filemanagersystem.folders.index', ['parent_id' => $parentFolder ? $parentFolder->id : null]) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Geri Dön
            </a>
        </div>
    </div>
    
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
                            <a class="nav-link" href="{{ route('admin.filemanagersystem.index') }}">
                                <i class="fas fa-file"></i> Tüm Dosyalar
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">Klasör Bilgileri</div>
                
                <div class="card-body">
                    <form action="{{ route('admin.filemanagersystem.folders.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="folder_name" class="form-label">Klasör Adı <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('folder_name') is-invalid @enderror" id="folder_name" name="folder_name" value="{{ old('folder_name') }}" required>
                            @error('folder_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="parent_id" class="form-label">Üst Klasör</label>
                            <select class="form-select @error('parent_id') is-invalid @enderror" id="parent_id" name="parent_id">
                                <option value="">Ana Klasör</option>
                                @foreach($folders as $folder)
                                    <option value="{{ $folder->id }}" {{ (old('parent_id') ?? ($parentFolder ? $parentFolder->id : null)) == $folder->id ? 'selected' : '' }}>
                                        {{ $folder->folder_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('parent_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="folder_description" class="form-label">Açıklama</label>
                            <textarea class="form-control @error('folder_description') is-invalid @enderror" id="folder_description" name="folder_description" rows="3">{{ old('folder_description') }}</textarea>
                            @error('folder_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="status" name="status" value="1" {{ old('status', 1) ? 'checked' : '' }}>
                            <label class="form-check-label" for="status">Aktif</label>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Klasör Oluştur
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop 