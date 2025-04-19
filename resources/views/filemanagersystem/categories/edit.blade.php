@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">Kategori Düzenle: {{ $category->name }}</h5>
                        </div>
                        <div>
                            <a href="{{ route('filemanagersystem.categories.index') }}" class="btn btn-sm btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kategorilere Dön
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('filemanagersystem.categories.update', $category) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Kategori Adı <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $category->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="slug" class="form-label">Kategori Slug</label>
                            <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $category->slug) }}">
                            <div class="form-text">Boş bırakırsanız kategori adından otomatik oluşturulacaktır.</div>
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="parent_id" class="form-label">Üst Kategori</label>
                            <select class="form-select @error('parent_id') is-invalid @enderror" id="parent_id" name="parent_id">
                                <option value="">Ana Kategori</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('parent_id', $category->parent_id) == $cat->id ? 'selected' : '' }}>
                                        @if($cat->parent)
                                            {{ $cat->parent->name }} &raquo; 
                                        @endif
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">Bir kategori kendisini veya kendi alt kategorilerinden birini üst kategori olarak seçemez.</div>
                            @error('parent_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Açıklama</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $category->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Aktif</label>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-primary">Kategoriyi Güncelle</button>
                                
                                <form action="{{ route('filemanagersystem.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Bu kategoriyi silmek istediğinizden emin misiniz?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash"></i> Kategoriyi Sil
                                    </button>
                                </form>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Kategori adı değiştiğinde slug otomatik oluşturma
    document.getElementById('name').addEventListener('keyup', function() {
        const categoryName = this.value;
        const slugField = document.getElementById('slug');
        
        // Eğer slug alanı boşsa veya kullanıcı henüz manuel değiştirmediyse
        if (!slugField.dataset.originalValue) {
            slugField.dataset.originalValue = "{{ $category->slug }}";
        }
        
        if (slugField.value === slugField.dataset.originalValue || 
            slugField.value === slugify(this.value.substring(0, this.value.length - 1))) {
            slugField.value = slugify(categoryName);
        }
    });

    // Basit bir slugify fonksiyonu
    function slugify(text) {
        return text
            .toString()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .toLowerCase()
            .trim()
            .replace(/\s+/g, '-')
            .replace(/[^\w-]+/g, '')
            .replace(/--+/g, '-');
    }
</script>
@endsection 