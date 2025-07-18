@extends('adminlte::page')

@section('title', 'Yeni Hizmet Konusu')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Yeni Hizmet Konusu</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.service-topics.index') }}">Hizmet Konuları</a></li>
        <li class="breadcrumb-item active">Yeni Konu Ekle</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-plus me-1"></i> Yeni Hizmet Konusu Ekle
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
            
            <form action="{{ route('admin.service-topics.store') }}" method="POST">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Hizmet Konusu Adı <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Örnek: Eğitim, Sağlık, Çevre</small>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="slug" class="form-label">URL (Slug)</label>
                            <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                                id="slug" name="slug" value="{{ old('slug') }}">
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
                        id="description" name="description" rows="3">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="tooltip_text" class="form-label">
                        <i class="fas fa-info-circle text-primary"></i> Tooltip Metni
                    </label>
                    <input type="text" class="form-control @error('tooltip_text') is-invalid @enderror" 
                        id="tooltip_text" name="tooltip_text" value="{{ old('tooltip_text') }}" 
                        placeholder="Kategori başlığına hover edildiğinde gösterilecek metin">
                    @error('tooltip_text')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">
                        <i class="fas fa-lightbulb text-warning"></i> 
                        Bu alan boş bırakılırsa tooltip görünmez. Doldurulursa kategori başlığının üzerine gelindiğinde gösterilir.
                    </small>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="icon" class="form-label">İkon (Font Awesome Sınıfı)</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i id="icon-preview" class="fas fa-list"></i>
                                </span>
                                <input type="text" class="form-control @error('icon') is-invalid @enderror" 
                                    id="icon" name="icon" value="{{ old('icon', 'fas fa-list') }}" 
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
                                    id="color" name="color" value="{{ old('color', '#007bff') }}">
                                <input type="text" class="form-control" id="color-text" 
                                    value="{{ old('color', '#007bff') }}" readonly>
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
                                id="order" name="order" value="{{ old('order', 0) }}" min="0">
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
                                id="meta_title" name="meta_title" value="{{ old('meta_title') }}">
                            @error('meta_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="meta_description" class="form-label">Meta Açıklama (SEO)</label>
                            <textarea class="form-control @error('meta_description') is-invalid @enderror" 
                                id="meta_description" name="meta_description" rows="2">{{ old('meta_description') }}</textarea>
                            @error('meta_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" 
                        {{ old('is_active', 1) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Aktif</label>
                </div>
                
                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.service-topics.index') }}" class="btn btn-secondary me-2">İptal</a>
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Popüler İkonlar -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-star me-1"></i> Popüler İkonlar</h5>
        </div>
        <div class="card-body">
            <div class="row text-center">
                <div class="col-2 mb-3 icon-preview" data-icon="fas fa-graduation-cap">
                    <i class="fas fa-graduation-cap fa-2x mb-2 text-primary"></i>
                    <div class="small">Eğitim</div>
                </div>
                <div class="col-2 mb-3 icon-preview" data-icon="fas fa-heartbeat">
                    <i class="fas fa-heartbeat fa-2x mb-2 text-danger"></i>
                    <div class="small">Sağlık</div>
                </div>
                <div class="col-2 mb-3 icon-preview" data-icon="fas fa-leaf">
                    <i class="fas fa-leaf fa-2x mb-2 text-success"></i>
                    <div class="small">Çevre</div>
                </div>
                <div class="col-2 mb-3 icon-preview" data-icon="fas fa-building">
                    <i class="fas fa-building fa-2x mb-2 text-info"></i>
                    <div class="small">İmar</div>
                </div>
                <div class="col-2 mb-3 icon-preview" data-icon="fas fa-tree">
                    <i class="fas fa-tree fa-2x mb-2 text-success"></i>
                    <div class="small">Park</div>
                </div>
                <div class="col-2 mb-3 icon-preview" data-icon="fas fa-fire-extinguisher">
                    <i class="fas fa-fire-extinguisher fa-2x mb-2 text-warning"></i>
                    <div class="small">İtfaiye</div>
                </div>
                <div class="col-2 mb-3 icon-preview" data-icon="fas fa-gavel">
                    <i class="fas fa-gavel fa-2x mb-2 text-dark"></i>
                    <div class="small">Hukuk</div>
                </div>
                <div class="col-2 mb-3 icon-preview" data-icon="fas fa-music">
                    <i class="fas fa-music fa-2x mb-2 text-purple"></i>
                    <div class="small">Kültür</div>
                </div>
                <div class="col-2 mb-3 icon-preview" data-icon="fas fa-users">
                    <i class="fas fa-users fa-2x mb-2 text-secondary"></i>
                    <div class="small">Sosyal</div>
                </div>
                <div class="col-2 mb-3 icon-preview" data-icon="fas fa-car">
                    <i class="fas fa-car fa-2x mb-2 text-primary"></i>
                    <div class="small">Ulaşım</div>
                </div>
                <div class="col-2 mb-3 icon-preview" data-icon="fas fa-handshake">
                    <i class="fas fa-handshake fa-2x mb-2 text-warning"></i>
                    <div class="small">Yardım</div>
                </div>
                <div class="col-2 mb-3 icon-preview" data-icon="fas fa-shield-alt">
                    <i class="fas fa-shield-alt fa-2x mb-2 text-info"></i>
                    <div class="small">Güvenlik</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
    .icon-preview {
        cursor: pointer;
        padding: 10px;
        border-radius: 5px;
        transition: all 0.2s;
    }
    
    .icon-preview:hover {
        background-color: #f8f9fa;
        transform: scale(1.05);
    }
    
    .form-control-color {
        height: 38px;
        width: 60px;
        border-radius: 0.25rem 0 0 0.25rem;
    }
</style>
@endsection

@push('js')
<script>
$(document).ready(function() {
    // İkon önizleme
    $('#icon').on('input', function() {
        var iconClass = $(this).val();
        $('#icon-preview').attr('class', iconClass);
    });
    
    // Popüler ikonlara tıklama
    $('.icon-preview').on('click', function() {
        var iconClass = $(this).data('icon');
        $('#icon').val(iconClass);
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