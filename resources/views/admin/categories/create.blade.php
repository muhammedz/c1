@extends('adminlte::page')

@section('title', 'Yeni Haber Kategorisi')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Yeni Haber Kategorisi</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Haber Kategorileri</a></li>
        <li class="breadcrumb-item active">Yeni Kategori</li>
    </ol>
    
    <!-- Debug bilgisi -->
    <div class="alert alert-info">
        <p><strong>Debug Bilgisi:</strong></p>
        <p>Form Action URL: {{ route('admin.categories.store') }}</p>
        <p>CSRF Token: {{ csrf_token() }}</p>
        <p>Method: POST</p>
    </div>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-plus me-1"></i> Yeni Kategori Ekle
        </div>
        <div class="card-body">
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            
            <form action="{{ route('admin.categories.store') }}" method="POST" id="categoryForm">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Kategori Adı <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="icon" class="form-label">İkon (Font Awesome Sınıfı)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i id="icon-preview" class="{{ old('icon', 'fas fa-folder') }}"></i></span>
                                <input type="text" class="form-control @error('icon') is-invalid @enderror" id="icon" name="icon" value="{{ old('icon', 'fas fa-folder') }}" placeholder="fas fa-folder">
                                @error('icon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted">Örnek: fas fa-folder, fas fa-newspaper, vb.</small>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Açıklama</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="parent_id" class="form-label">Üst Kategori</label>
                            <select class="form-select @error('parent_id') is-invalid @enderror" id="parent_id" name="parent_id">
                                <option value="">Ana Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('parent_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('parent_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="order" class="form-label">Sıralama</label>
                            <input type="number" class="form-control @error('order') is-invalid @enderror" id="order" name="order" value="{{ old('order', 0) }}" min="0">
                            @error('order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Aktif</label>
                </div>
                
                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary me-2">İptal</a>
                    <button type="submit" class="btn btn-primary" id="submitBtn">Kaydet</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // İkon önizleme
    $('#icon').on('input', function() {
        var iconClass = $(this).val();
        $('#icon-preview').attr('class', iconClass);
    });
    
    // Form gönderildiğinde debug için
    $('#categoryForm').on('submit', function(e) {
        console.log('Form gönderiliyor...', {
            name: $('#name').val(),
            icon: $('#icon').val(),
            description: $('#description').val(),
            parent_id: $('#parent_id').val(),
            order: $('#order').val(),
            is_active: $('#is_active').is(':checked'),
            token: $('input[name="_token"]').val(),
            action: $(this).attr('action'),
            method: $(this).attr('method')
        });
    });
});
</script>
@endpush 