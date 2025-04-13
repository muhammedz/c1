@extends('adminlte::page')

@section('title', 'Haber Kategorisi Düzenle')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Haber Kategorisi Düzenle</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.news-categories.index') }}">Haber Kategorileri</a></li>
        <li class="breadcrumb-item active">Haber Kategorisi Düzenle</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-edit me-1"></i> {{ $newsCategory->name }} Haber Kategorisini Düzenle
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            
            <form action="{{ route('admin.news-categories.update', $newsCategory->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Haber Kategorisi Adı <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $newsCategory->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="icon" class="form-label">İkon (Font Awesome Sınıfı)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i id="icon-preview" class="{{ $newsCategory->icon ?: 'fas fa-folder' }}"></i></span>
                                <input type="text" class="form-control @error('icon') is-invalid @enderror" id="icon" name="icon" value="{{ old('icon', $newsCategory->icon) }}" placeholder="fas fa-folder">
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
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $newsCategory->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="parent_id" class="form-label">Üst Haber Kategorisi</label>
                            <select class="form-select @error('parent_id') is-invalid @enderror" id="parent_id" name="parent_id">
                                <option value="">Ana Haber Kategorisi</option>
                                @foreach($newsCategories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('parent_id', $newsCategory->parent_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
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
                            <input type="number" class="form-control @error('order') is-invalid @enderror" id="order" name="order" value="{{ old('order', $newsCategory->order) }}" min="0">
                            @error('order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" {{ old('is_active', $newsCategory->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Aktif</label>
                </div>
                
                <div class="mb-3">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">İlişkili Haberler</h5>
                        </div>
                        <div class="card-body">
                            @if($newsCategory->news->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm table-striped">
                                        <thead>
                                            <tr>
                                                <th>Başlık</th>
                                                <th class="text-center">Durum</th>
                                                <th class="text-center">İşlem</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($newsCategory->news->take(5) as $news)
                                                <tr>
                                                    <td>{{ $news->title }}</td>
                                                    <td class="text-center">
                                                        <span class="badge bg-{{ $news->status === 'published' ? 'success' : 'warning' }}">
                                                            {{ $news->status === 'published' ? 'Yayında' : 'Taslak' }}
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="{{ route('admin.news.edit', $news->id) }}" class="btn btn-sm btn-primary">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                
                                @if($newsCategory->news->count() > 5)
                                    <div class="text-center mt-2">
                                        <span class="text-muted">{{ $newsCategory->news->count() - 5 }} haber daha...</span>
                                    </div>
                                @endif
                            @else
                                <div class="alert alert-info mb-0">
                                    Bu haber kategorisine ait haber bulunmamaktadır.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.news-categories.index') }}" class="btn btn-secondary me-2">İptal</a>
                    <button type="submit" class="btn btn-primary">Güncelle</button>
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
    });
</script>
@endpush 