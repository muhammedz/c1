@extends('adminlte::page')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Hızlı Aramayı Düzenle: {{ $quickLink->title }}</h3>
                </div>
                
                <div class="card-body">
                    <form action="{{ route('admin.search-quick-links.update', $quickLink->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label for="title">Başlık</label>
                            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $quickLink->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="url">URL</label>
                            <input type="text" name="url" id="url" class="form-control @error('url') is-invalid @enderror" value="{{ old('url', $quickLink->url) }}" required>
                            <small class="form-text text-muted">Örnek: <code>/search?q=E-Belediye</code></small>
                            @error('url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="order">Sıralama</label>
                                    <input type="number" name="order" id="order" class="form-control @error('order') is-invalid @enderror" value="{{ old('order', $quickLink->order) }}" min="0" required>
                                    @error('order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mt-4">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="isActive" name="is_active" {{ $quickLink->is_active ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="isActive">Aktif</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-right">
                            <a href="{{ route('admin.search-settings.index') }}" class="btn btn-secondary">İptal</a>
                            <button type="submit" class="btn btn-primary">Güncelle</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 