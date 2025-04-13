@extends('adminlte::page')

@section('title', 'Yeni Sayfa Kategorisi')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">Yeni Sayfa Kategorisi</h1>
            <p class="mb-0 text-muted">Sayfalarınız için yeni bir kategori oluşturun</p>
        </div>
        <a href="{{ route('admin.page-categories.index') }}" class="btn btn-outline-secondary">
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
                
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form action="{{ route('admin.page-categories.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Kategori Adı <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Slug (URL) bu isimden otomatik oluşturulacaktır.</small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="parent_id">Üst Kategori</label>
                                    <select class="form-control @error('parent_id') is-invalid @enderror" id="parent_id" name="parent_id">
                                        <option value="">Ana Kategori</option>
                                        @foreach($pageCategories as $category)
                                            <option value="{{ $category->id }}" {{ old('parent_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('parent_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Açıklama</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="icon">İkon (FontAwesome)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-icons"></i></span>
                                        </div>
                                        <input type="text" class="form-control @error('icon') is-invalid @enderror" id="icon" name="icon" value="{{ old('icon') }}" placeholder="örn: fas fa-folder">
                                    </div>
                                    @error('icon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        <a href="https://fontawesome.com/icons" target="_blank">FontAwesome</a> ikon sınıfını girin (örn: fas fa-folder)
                                    </small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="order">Sıralama</label>
                                    <input type="number" class="form-control @error('order') is-invalid @enderror" id="order" name="order" value="{{ old('order', 0) }}" min="0">
                                    @error('order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Aktif</label>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i> Kaydet
                            </button>
                            <a href="{{ route('admin.page-categories.index') }}" class="btn btn-secondary">
                                İptal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Yardım & İpuçları -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle mr-1 text-info"></i>
                        Yardım & İpuçları
                    </h5>
                </div>
                <div class="card-body">
                    <h6>Kategori Adı</h6>
                    <p>Kategori adı, sayfalarınızın organizasyonu için önemlidir. Kullanıcılarınızın kolayca anlayabileceği açıklayıcı isimler kullanın.</p>
                    
                    <h6>Üst Kategori</h6>
                    <p>Kategorileri hiyerarşik düzende organize etmek için üst kategori seçebilirsiniz. Boş bırakırsanız, bu bir ana kategori olacaktır.</p>
                    
                    <h6>İkonlar</h6>
                    <p>Kategoriniz için görsel bir ikon ekleyebilirsiniz. FontAwesome kütüphanesinden bir ikon sınıfı girin. Örnek: <code>fas fa-book</code></p>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-light-bulb mr-1"></i>
                        <strong>İpucu:</strong> Düzenli olarak kategorilerinizi gözden geçirin ve organize edin. Çok fazla kategori kullanıcıları karıştırabilir.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        // İkon önizleme
        $('#icon').on('input', function() {
            var iconClass = $(this).val();
            if (iconClass) {
                $(this).closest('.input-group').find('.input-group-text i').attr('class', iconClass);
            } else {
                $(this).closest('.input-group').find('.input-group-text i').attr('class', 'fas fa-icons');
            }
        });
    });
</script>
@endsection 