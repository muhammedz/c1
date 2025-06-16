@extends('adminlte::page')

@section('title', 'Rehber Kategorisi Düzenle')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Rehber Kategorisi Düzenle</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.guide-categories.index') }}">Rehber Kategorileri</a></li>
        <li class="breadcrumb-item active">{{ $guideCategory->name }}</li>
    </ol>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-edit me-1"></i> Kategori Bilgileri
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <form action="{{ route('admin.guide-categories.update', $guideCategory) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Kategori Adı <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $guideCategory->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="sort_order" class="form-label">Sıra</label>
                                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                           id="sort_order" name="sort_order" value="{{ old('sort_order', $guideCategory->sort_order) }}" min="0">
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="slug" class="form-label">URL Slug</label>
                            <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                                   id="slug" name="slug" value="{{ old('slug', $guideCategory->slug) }}">
                            <div class="form-text">Boş bırakılırsa otomatik oluşturulur</div>
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Açıklama</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4">{{ old('description', $guideCategory->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="icon" class="form-label">İkon (Font Awesome)</label>
                            <div class="input-group">
                                <input type="text" class="form-control @error('icon') is-invalid @enderror" 
                                       id="icon" name="icon" value="{{ old('icon', $guideCategory->icon) }}" placeholder="fas fa-map-marker-alt">
                                <button type="button" class="btn btn-outline-secondary" id="icon-preview">
                                    <i id="icon-display" class="{{ old('icon', $guideCategory->icon ?: 'fas fa-question') }}"></i>
                                </button>
                            </div>
                            <div class="form-text">Örnek: fas fa-map-marker-alt, fas fa-building, fas fa-hospital</div>
                            @error('icon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                       {{ old('is_active', $guideCategory->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Aktif
                                </label>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.guide-categories.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Geri Dön
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Güncelle
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-search-plus me-1"></i> SEO Ayarları
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="meta_title" class="form-label">Meta Başlık</label>
                        <input type="text" class="form-control @error('meta_title') is-invalid @enderror" 
                               id="meta_title" name="meta_title" value="{{ old('meta_title', $guideCategory->meta_title) }}" maxlength="255">
                        <div class="form-text">Boş bırakılırsa kategori adı kullanılır</div>
                        @error('meta_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="meta_description" class="form-label">Meta Açıklama</label>
                        <textarea class="form-control @error('meta_description') is-invalid @enderror" 
                                  id="meta_description" name="meta_description" rows="3" maxlength="500">{{ old('meta_description', $guideCategory->meta_description) }}</textarea>
                        <div class="form-text">Boş bırakılırsa açıklama kullanılır</div>
                        @error('meta_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-bar me-1"></i> İstatistikler
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-primary">{{ $guideCategory->places()->count() }}</h4>
                                <small class="text-muted">Toplam Yer</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success">{{ $guideCategory->activePlaces()->count() }}</h4>
                            <small class="text-muted">Aktif Yer</small>
                        </div>
                    </div>
                    
                    @if($guideCategory->places()->count() > 0)
                        <hr>
                        <div class="d-grid">
                            <a href="{{ route('admin.guide-places.index', ['category_id' => $guideCategory->id]) }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-list"></i> Yerleri Görüntüle
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-info-circle me-1"></i> Yardım
                </div>
                <div class="card-body">
                    <h6>İkon Örnekleri:</h6>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-map-marker-alt text-primary"></i> <code>fas fa-map-marker-alt</code></li>
                        <li><i class="fas fa-building text-primary"></i> <code>fas fa-building</code></li>
                        <li><i class="fas fa-hospital text-primary"></i> <code>fas fa-hospital</code></li>
                        <li><i class="fas fa-shield-alt text-primary"></i> <code>fas fa-shield-alt</code></li>
                        <li><i class="fas fa-graduation-cap text-primary"></i> <code>fas fa-graduation-cap</code></li>
                    </ul>
                    <small class="text-muted">
                        Daha fazla ikon için <a href="https://fontawesome.com/icons" target="_blank">Font Awesome</a> sitesini ziyaret edin.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function() {
        // Slug otomatik oluşturma (sadece boşsa)
        var originalSlug = '{{ $guideCategory->slug }}';
        $('#name').on('input', function() {
            if ($('#slug').val() === '' || $('#slug').val() === originalSlug) {
                var slug = $(this).val()
                    .toLowerCase()
                    .replace(/ğ/g, 'g')
                    .replace(/ü/g, 'u')
                    .replace(/ş/g, 's')
                    .replace(/ı/g, 'i')
                    .replace(/ö/g, 'o')
                    .replace(/ç/g, 'c')
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-')
                    .trim('-');
                $('#slug').val(slug);
            }
        });
        
        // İkon önizleme
        $('#icon').on('input', function() {
            var iconClass = $(this).val() || 'fas fa-question';
            $('#icon-display').attr('class', iconClass);
        });
        
        // İkon önizleme butonu
        $('#icon-preview').click(function() {
            var iconClass = $('#icon').val();
            if (iconClass) {
                $('#icon-display').attr('class', iconClass);
            }
        });
    });
</script>
@endpush 