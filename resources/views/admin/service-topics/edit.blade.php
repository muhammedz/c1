@extends('adminlte::page')

@section('title', 'Hizmet Konusu Düzenle')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Hizmet Konusu Düzenle</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.service-topics.index') }}">Hizmet Konuları</a></li>
        <li class="breadcrumb-item active">{{ $serviceTopic->name }} Düzenle</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-edit me-1"></i> {{ $serviceTopic->name }} Hizmet Konusunu Düzenle
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
            
            <form action="{{ route('admin.service-topics.update', $serviceTopic->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Hizmet Konusu Adı <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                id="name" name="name" value="{{ old('name', $serviceTopic->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="slug" class="form-label">URL (Slug)</label>
                            <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                                id="slug" name="slug" value="{{ old('slug', $serviceTopic->slug) }}">
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Boş bırakırsanız otomatik oluşturulur</small>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Açıklama</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                        id="description" name="description" rows="3">{{ old('description', $serviceTopic->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="icon" class="form-label">İkon (Font Awesome Sınıfı)</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i id="icon-preview" class="{{ $serviceTopic->icon ?: 'fas fa-list' }}"></i>
                                </span>
                                <input type="text" class="form-control @error('icon') is-invalid @enderror" 
                                    id="icon" name="icon" value="{{ old('icon', $serviceTopic->icon) }}" 
                                    placeholder="fas fa-list">
                                @error('icon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted">Örnek: fas fa-heart, fas fa-graduation-cap</small>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="color" class="form-label">Renk</label>
                            <div class="input-group">
                                <input type="color" class="form-control form-control-color @error('color') is-invalid @enderror" 
                                    id="color" name="color" value="{{ old('color', $serviceTopic->color ?: '#007bff') }}">
                                <input type="text" class="form-control" id="color-text" 
                                    value="{{ old('color', $serviceTopic->color ?: '#007bff') }}" readonly>
                            </div>
                            @error('color')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="order" class="form-label">Sıralama</label>
                            <input type="number" class="form-control @error('order') is-invalid @enderror" 
                                id="order" name="order" value="{{ old('order', $serviceTopic->order) }}" min="0">
                            @error('order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="meta_title" class="form-label">Meta Başlık (SEO)</label>
                            <input type="text" class="form-control @error('meta_title') is-invalid @enderror" 
                                id="meta_title" name="meta_title" value="{{ old('meta_title', $serviceTopic->meta_title) }}">
                            @error('meta_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="meta_description" class="form-label">Meta Açıklama (SEO)</label>
                            <textarea class="form-control @error('meta_description') is-invalid @enderror" 
                                id="meta_description" name="meta_description" rows="2">{{ old('meta_description', $serviceTopic->meta_description) }}</textarea>
                            @error('meta_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" 
                        {{ old('is_active', $serviceTopic->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Aktif</label>
                </div>
                
                @if($serviceTopic->services->count() > 0)
                <div class="mb-3">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">İlişkili Hizmetler ({{ $serviceTopic->services->count() }})</h5>
                        </div>
                        <div class="card-body">
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
                                        @foreach($serviceTopic->services->take(10) as $service)
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
                            
                            @if($serviceTopic->services->count() > 10)
                                <div class="text-center mt-2">
                                    <span class="text-muted">{{ $serviceTopic->services->count() - 10 }} hizmet daha...</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
                
                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.service-topics.index') }}" class="btn btn-secondary me-2">İptal</a>
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
        
        // Renk değişimi
        $('#color').on('change', function() {
            $('#color-text').val($(this).val());
        });
        
        $('#color-text').on('input', function() {
            var color = $(this).val();
            if (color.match(/^#[0-9A-F]{6}$/i)) {
                $('#color').val(color);
            }
        });
    });
</script>
@endpush 