@extends('adminlte::page')

@section('title', 'Sayfa Kategorisi Düzenle')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">Sayfa Kategorisi Düzenle</h1>
            <p class="mb-0 text-muted">{{ $pageCategory->name }} kategorisini düzenleyin</p>
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
                    
                    <form action="{{ route('admin.page-categories.update', $pageCategory->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Kategori Adı <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $pageCategory->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Slug (URL) bu isimden otomatik güncellenir: <strong>{{ $pageCategory->slug }}</strong></small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="parent_id">Üst Kategori</label>
                                    <select class="form-control @error('parent_id') is-invalid @enderror" id="parent_id" name="parent_id">
                                        <option value="">Ana Kategori</option>
                                        @foreach($pageCategories as $category)
                                            <option value="{{ $category->id }}" {{ old('parent_id', $pageCategory->parent_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('parent_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Dikkat: Kendisini veya alt kategorilerini üst kategori olarak seçemezsiniz.</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Açıklama</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $pageCategory->description) }}</textarea>
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
                                            <span class="input-group-text"><i class="{{ $pageCategory->icon ?: 'fas fa-icons' }}"></i></span>
                                        </div>
                                        <input type="text" class="form-control @error('icon') is-invalid @enderror" id="icon" name="icon" value="{{ old('icon', $pageCategory->icon) }}" placeholder="örn: fas fa-folder">
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
                                    <input type="number" class="form-control @error('order') is-invalid @enderror" id="order" name="order" value="{{ old('order', $pageCategory->order) }}" min="0">
                                    @error('order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" {{ old('is_active', $pageCategory->is_active) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Aktif</label>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i> Değişiklikleri Kaydet
                            </button>
                            <a href="{{ route('admin.page-categories.index') }}" class="btn btn-secondary">
                                İptal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="mb-3">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">İlişkili Sayfalar</h5>
                    </div>
                    <div class="card-body">
                        @if($pageCategory->pages->count() > 0)
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
                                        @foreach($pageCategory->pages as $page)
                                            <tr>
                                                <td>{{ $page->title }}</td>
                                                <td class="text-center">
                                                    @if($page->status == 'published')
                                                        <span class="badge bg-success">Yayında</span>
                                                    @elseif($page->status == 'draft')
                                                        <span class="badge bg-warning">Taslak</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ $page->status }}</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('admin.pages.edit', $page->id) }}" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-edit"></i> Düzenle
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                
                                @if($pageCategory->pages->count() > 10)
                                    <div class="text-center mt-3">
                                        <a href="{{ route('admin.pages.index', ['category' => $pageCategory->id]) }}" class="btn btn-sm btn-outline-primary">
                                            Tüm Sayfaları Görüntüle
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-info-circle mr-1"></i>
                                Bu kategoriye ait sayfa bulunmamaktadır.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Kategori Bilgileri -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle mr-1 text-info"></i>
                        Kategori Bilgileri
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Oluşturulma Tarihi:</strong>
                        <p>{{ $pageCategory->created_at->format('d.m.Y H:i') }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Son Güncelleme:</strong>
                        <p>{{ $pageCategory->updated_at->format('d.m.Y H:i') }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Slug:</strong>
                        <p class="text-monospace">{{ $pageCategory->slug }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Sayfa Sayısı:</strong>
                        <p>{{ $pageCategory->pages->count() }}</p>
                    </div>
                    
                    <div class="alert alert-warning">
                        <h6><i class="fas fa-exclamation-triangle"></i> Kategoriyi Sil</h6>
                        <p class="mb-2">Bu işlem geri alınamaz ve kategoriye bağlı sayfalar güncellenmeden kalır!</p>
                        
                        @if($pageCategory->pages->count() > 0)
                            <button class="btn btn-sm btn-outline-danger" disabled>
                                <i class="fas fa-trash"></i> Bu kategori kullanımda
                            </button>
                            <small class="d-block mt-2">İlişkili sayfalar nedeniyle silinemez. Önce ilişkili sayfaları başka kategorilere taşıyın.</small>
                        @else
                            <form action="{{ route('admin.page-categories.destroy', $pageCategory->id) }}" method="POST" onsubmit="return confirm('Bu kategoriyi silmek istediğinize emin misiniz?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash"></i> Kategoriyi Sil
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Yardım & İpuçları -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-lightbulb mr-1 text-warning"></i>
                        İpuçları
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="mb-0 pl-3">
                        <li>Kategori adını değiştirdiğinizde slug otomatik olarak güncellenecektir.</li>
                        <li>Kategoriyi pasif yaparsanız, ön yüzde sayfalar listelenirken bu kategori görünmez.</li>
                        <li>Kategori silme işlemi geri alınamaz.</li>
                    </ul>
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