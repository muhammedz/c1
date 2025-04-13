@extends('adminlte::page')

@section('title', 'Yeni Haber Kategorisi')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">Yeni Haber Kategorisi</h1>
            <p class="mb-0 text-muted">Haberleriniz için yeni bir kategori oluşturun</p>
        </div>
        <a href="{{ route('admin.news-categories.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Listeye Dön
        </a>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <!-- Ana Bilgiler -->
            <div class="card card-primary card-outline mb-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle mr-1"></i>
                        Temel Bilgiler
                    </h3>
                </div>
                
                <form action="{{ route('admin.news-categories.store') }}" method="POST" id="newsCategoryForm">
                    @csrf
                    <div class="card-body">
                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        
                        <div class="form-group">
                            <label for="name" class="font-weight-bold">
                                <i class="fas fa-tag text-primary mr-1"></i>
                                Kategori Adı <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                id="name" name="name" value="{{ old('name') }}" 
                                placeholder="Örn: Son Dakika" required>
                            <small class="form-text text-muted">
                                Kategori adı, haberlerinizi sınıflandırmak için kullanılır. Net ve anlaşılır bir isim seçin.
                            </small>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="description" class="font-weight-bold">
                                <i class="fas fa-align-left text-primary mr-1"></i>
                                Açıklama
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                id="description" name="description" rows="3" 
                                placeholder="Kategori hakkında kısa bir açıklama (isteğe bağlı)">{{ old('description') }}</textarea>
                            <small class="form-text text-muted">
                                Bu açıklama, kategorinin amacını ve içerdiği haber türlerini tanımlar.
                            </small>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="parent_id" class="font-weight-bold">
                                        <i class="fas fa-sitemap text-primary mr-1"></i>
                                        Üst Kategori
                                    </label>
                                    <select class="form-control @error('parent_id') is-invalid @enderror" id="parent_id" name="parent_id">
                                        <option value="">Ana Kategori Olarak Belirle</option>
                                        @foreach($newsCategories as $newsCategory)
                                            <option value="{{ $newsCategory->id }}" {{ old('parent_id') == $newsCategory->id ? 'selected' : '' }}>
                                                {{ $newsCategory->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">
                                        Kategoriyi bir üst kategorinin altına yerleştirmek için seçin. Boş bırakırsanız ana kategori olacaktır.
                                    </small>
                                    @error('parent_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="icon" class="font-weight-bold">
                                        <i class="fas fa-icons text-primary mr-1"></i>
                                        İkon
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light">
                                                <i id="icon-preview" class="{{ old('icon', 'fas fa-newspaper') }}"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control @error('icon') is-invalid @enderror" 
                                            id="icon" name="icon" value="{{ old('icon', 'fas fa-newspaper') }}" 
                                            placeholder="fas fa-newspaper">
                                    </div>
                                    <small class="form-text text-muted">
                                        Font Awesome ikon kodu (örn: fas fa-newspaper, fas fa-rss). 
                                        <a href="https://fontawesome.com/icons" target="_blank">İkon Galerisi</a>
                                    </small>
                                    @error('icon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                
                    <!-- Ayarlar Bölümü -->
                    <div class="card-header border-top">
                        <h3 class="card-title">
                            <i class="fas fa-cogs mr-1"></i>
                            Kategori Ayarları
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="order" class="font-weight-bold">
                                        <i class="fas fa-sort-numeric-down text-primary mr-1"></i>
                                        Sıralama Önceliği
                                    </label>
                                    <input type="number" class="form-control @error('order') is-invalid @enderror" 
                                        id="order" name="order" value="{{ old('order', 0) }}" min="0">
                                    <small class="form-text text-muted">
                                        Kategorinin görüntülenme sırasını belirler. Küçük değerler üstte görünür.
                                    </small>
                                    @error('order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label class="font-weight-bold d-block">
                                        <i class="fas fa-toggle-on text-primary mr-1"></i>
                                        Durum
                                    </label>
                                    <div class="custom-control custom-switch custom-switch-lg">
                                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_active">Kategoriyi Aktif Et</label>
                                    </div>
                                    <small class="form-text text-muted">
                                        Pasif kategoriler web sitesinde görünmez ve kullanılamaz.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.news-categories.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times mr-1"></i> İptal
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save mr-1"></i> Kategoriyi Kaydet
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Yardım Kutusu -->
            <div class="card bg-light mb-4">
                <div class="card-header bg-light">
                    <h3 class="card-title">
                        <i class="fas fa-question-circle mr-1"></i>
                        Hızlı Yardım
                    </h3>
                </div>
                <div class="card-body">
                    <div class="callout callout-info">
                        <h5><i class="fas fa-info mr-1"></i> Bilgi</h5>
                        <p class="mb-0">Kategoriler, haberlerinizi düzenli şekilde gruplandırmanızı sağlar. Mantıklı bir kategori yapısı, ziyaretçilerin ilgilendikleri haberleri daha kolay bulmasına yardımcı olur.</p>
                    </div>
                    
                    <div class="callout callout-warning">
                        <h5><i class="fas fa-lightbulb mr-1"></i> İpuçları</h5>
                        <ul class="pl-3 mb-0">
                            <li>Kısa ve açıklayıcı kategori adları kullanın</li>
                            <li>Kategorileri bir hiyerarşi içinde düzenleyin</li>
                            <li>İlgili bir ikon seçmek görsel ilgiyi artırır</li>
                            <li>Çok fazla alt kategori oluşturmaktan kaçının</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Popüler İkonlar -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-star mr-1"></i>
                        Popüler İkonlar
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-3 mb-3 icon-preview" data-icon="fas fa-newspaper">
                            <i class="fas fa-newspaper fa-2x mb-2"></i>
                            <div class="small">fa-newspaper</div>
                        </div>
                        <div class="col-3 mb-3 icon-preview" data-icon="fas fa-rss">
                            <i class="fas fa-rss fa-2x mb-2"></i>
                            <div class="small">fa-rss</div>
                        </div>
                        <div class="col-3 mb-3 icon-preview" data-icon="fas fa-globe">
                            <i class="fas fa-globe fa-2x mb-2"></i>
                            <div class="small">fa-globe</div>
                        </div>
                        <div class="col-3 mb-3 icon-preview" data-icon="fas fa-bullhorn">
                            <i class="fas fa-bullhorn fa-2x mb-2"></i>
                            <div class="small">fa-bullhorn</div>
                        </div>
                        <div class="col-3 mb-3 icon-preview" data-icon="fas fa-fire">
                            <i class="fas fa-fire fa-2x mb-2"></i>
                            <div class="small">fa-fire</div>
                        </div>
                        <div class="col-3 mb-3 icon-preview" data-icon="fas fa-calendar-alt">
                            <i class="fas fa-calendar-alt fa-2x mb-2"></i>
                            <div class="small">fa-calendar-alt</div>
                        </div>
                        <div class="col-3 mb-3 icon-preview" data-icon="fas fa-chart-line">
                            <i class="fas fa-chart-line fa-2x mb-2"></i>
                            <div class="small">fa-chart-line</div>
                        </div>
                        <div class="col-3 mb-3 icon-preview" data-icon="fas fa-bookmark">
                            <i class="fas fa-bookmark fa-2x mb-2"></i>
                            <div class="small">fa-bookmark</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
    .custom-switch-lg .custom-control-label::before {
        width: 3rem;
        height: 1.5rem;
        border-radius: 1rem;
    }
    
    .custom-switch-lg .custom-control-label::after {
        width: calc(1.5rem - 4px);
        height: calc(1.5rem - 4px);
        border-radius: 50%;
    }
    
    .custom-switch-lg .custom-control-input:checked ~ .custom-control-label::after {
        transform: translateX(1.5rem);
    }
    
    .custom-switch-lg .custom-control-label {
        padding-top: 0.35rem;
        padding-left: 0.5rem;
    }
    
    .form-control-lg {
        height: calc(1.5em + 1rem + 6px);
        padding: 0.5rem 1rem;
        font-size: 1.1rem;
        border-radius: 0.3rem;
    }
    
    .icon-preview {
        cursor: pointer;
        padding: 10px 5px;
        border-radius: 5px;
        transition: all 0.2s;
    }
    
    .icon-preview:hover {
        background-color: #f8f9fa;
        transform: scale(1.1);
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
});
</script>
@endpush 