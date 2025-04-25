@extends('adminlte::page')

@section('title', 'Yeni Menü Kategorisi Ekle')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Yeni Menü Kategorisi Ekle</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Panel</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.menusystem.index') }}">Menü Yönetimi</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.menusystem.categories.index', ['menu_id' => $menu->id]) }}">Menü Kategorileri</a></li>
                <li class="breadcrumb-item active">Yeni Kategori</li>
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
                    <h4 class="card-title">{{ $menu->name }} Menüsüne Yeni Kategori Ekle</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.menusystem.categories.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Kategori Adı <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="url">URL (Opsiyonel)</label>
                                    <input type="text" class="form-control @error('url') is-invalid @enderror" id="url" name="url" value="{{ old('url') }}">
                                    @error('url')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                    <small class="form-text text-muted">Kategori başlığının kendisi bir bağlantıya sahip olacaksa URL ekleyin.</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="order">Sıralama</label>
                                    <input type="number" class="form-control @error('order') is-invalid @enderror" id="order" name="order" value="{{ old('order', 0) }}" min="0">
                                    @error('order')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Durum</label>
                                    <div class="custom-control custom-switch mt-2">
                                        <input type="checkbox" class="custom-control-input" id="status" name="status" value="1" {{ old('status', '1') == '1' ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="status">Aktif</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-12 text-right">
                                <a href="{{ route('admin.menusystem.categories.index', ['menu_id' => $menu->id]) }}" class="btn btn-light mr-2">İptal</a>
                                <button type="submit" class="btn btn-primary">Kaydet</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 