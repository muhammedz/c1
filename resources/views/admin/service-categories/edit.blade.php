@extends('adminlte::page')

@section('title', 'Müdürlük Kategorisi Düzenle')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Müdürlük Kategorisi Düzenle</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.service-categories.index') }}">Müdürlükler Kategorisi</a></li>
        <li class="breadcrumb-item active">Müdürlük Kategorisi Düzenle</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-edit me-1"></i> {{ $serviceCategory->name }} Müdürlük Kategorisini Düzenle
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
            
            <form action="{{ route('admin.service-categories.update', $serviceCategory->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Müdürlük Kategorisi Adı <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $serviceCategory->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="icon" class="form-label">İkon (Font Awesome Sınıfı)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i id="icon-preview" class="{{ $serviceCategory->icon ?: 'fas fa-folder' }}"></i></span>
                                <input type="text" class="form-control @error('icon') is-invalid @enderror" id="icon" name="icon" value="{{ old('icon', $serviceCategory->icon) }}" placeholder="fas fa-folder">
                                @error('icon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted">Örnek: fas fa-folder, fas fa-tools, vb.</small>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Açıklama</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $serviceCategory->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="parent_id" class="form-label">Üst Müdürlük Kategorisi</label>
                            <select class="form-select @error('parent_id') is-invalid @enderror" id="parent_id" name="parent_id">
                                <option value="">Ana Müdürlük Kategorisi</option>
                                @foreach($serviceCategories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('parent_id', $serviceCategory->parent_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
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
                            <input type="number" class="form-control @error('order') is-invalid @enderror" id="order" name="order" value="{{ old('order', $serviceCategory->order) }}" min="0">
                            @error('order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', $serviceCategory->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Aktif</label>
                </div>
                
                <div class="mb-3">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">İlişkili Hizmetler</h5>
                        </div>
                        <div class="card-body">
                            @if($serviceCategory->services->count() > 0)
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
                                            @foreach($serviceCategory->services->take(5) as $service)
                                                <tr>
                                                    <td>{{ $service->title }}</td>
                                                    <td class="text-center">
                                                        <span class="badge bg-{{ $service->status === 'published' ? 'success' : 'warning' }}">
                                                            {{ $service->status === 'published' ? 'Yayında' : 'Taslak' }}
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="{{ route('admin.services.edit', $service->id) }}" class="btn btn-sm btn-primary">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                
                                @if($serviceCategory->services->count() > 5)
                                    <div class="text-center mt-2">
                                        <span class="text-muted">{{ $serviceCategory->services->count() - 5 }} hizmet daha...</span>
                                    </div>
                                @endif
                            @else
                                <div class="alert alert-info mb-0">
                                    Bu müdürlük kategorisine ait hizmet bulunmamaktadır.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.service-categories.index') }}" class="btn btn-secondary me-2">İptal</a>
                    <button type="submit" class="btn btn-primary">Güncelle</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
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