@extends('adminlte::page')

@section('title', 'Menü Öğesi Düzenle')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Menü Öğesi Düzenle</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Panel</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.menusystem.index') }}">Menü Yönetimi</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.menusystem.edit', $menu->id) }}">{{ $menu->name }}</a></li>
                <li class="breadcrumb-item active">Öğe Düzenle</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $menuItem->title }} - Düzenle</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.menusystem.items.update', $menuItem->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">Başlık <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title', $menuItem->title) }}" required>
                                    @error('title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="url">URL <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('url') is-invalid @enderror" 
                                           id="url" name="url" value="{{ old('url', $menuItem->url) }}" required>
                                    @error('url')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="order">Sıralama</label>
                                    <input type="number" class="form-control @error('order') is-invalid @enderror" 
                                           id="order" name="order" value="{{ old('order', $menuItem->order) }}" min="0">
                                    @error('order')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="icon">İkon (Opsiyonel)</label>
                                    <input type="text" class="form-control @error('icon') is-invalid @enderror" 
                                           id="icon" name="icon" value="{{ old('icon', $menuItem->icon) }}">
                                    <small class="form-text text-muted">Örnek: fas fa-home</small>
                                    @error('icon')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        @if($menu->type == 3)
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="button_style">Buton Stili</label>
                                    <select class="form-control @error('button_style') is-invalid @enderror" 
                                            id="button_style" name="button_style">
                                        <option value="">Varsayılan</option>
                                        <option value="primary" {{ old('button_style', $menuItem->button_style) == 'primary' ? 'selected' : '' }}>Birincil</option>
                                        <option value="secondary" {{ old('button_style', $menuItem->button_style) == 'secondary' ? 'selected' : '' }}>İkincil</option>
                                        <option value="success" {{ old('button_style', $menuItem->button_style) == 'success' ? 'selected' : '' }}>Başarılı</option>
                                        <option value="danger" {{ old('button_style', $menuItem->button_style) == 'danger' ? 'selected' : '' }}>Tehlike</option>
                                        <option value="warning" {{ old('button_style', $menuItem->button_style) == 'warning' ? 'selected' : '' }}>Uyarı</option>
                                        <option value="info" {{ old('button_style', $menuItem->button_style) == 'info' ? 'selected' : '' }}>Bilgi</option>
                                    </select>
                                    @error('button_style')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="status" name="status" value="1" 
                                       {{ old('status', $menuItem->status) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="status">Aktif</label>
                            </div>
                        </div>
                        
                        <div class="text-right">
                            <button type="submit" class="btn btn-success">Güncelle</button>
                            <a href="{{ route('admin.menusystem.edit', $menu->id) }}" class="btn btn-secondary">İptal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop 