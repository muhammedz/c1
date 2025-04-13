@extends('adminlte::page')

@section('title', 'Hızlı Menü Kategorisi Düzenle')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Hızlı Menü Kategorisi Düzenle: {{ $category->name }}</h1>
        <a href="{{ route('admin.homepage.quick-menus.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kategori Listesine Dön
        </a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Kategori Bilgileri</h3>
        </div>
        
        <form action="{{ route('admin.homepage.quick-menus.update', $category->id) }}" method="POST" id="category-form">
            @csrf
            @method('POST')
            
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <h5><i class="icon fas fa-ban"></i> Hata!</h5>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <div class="row">
                    <div class="col-md-6">
                        <!-- Kategori Adı -->
                        <div class="form-group">
                            <label for="name">Kategori Adı <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $category->name) }}" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <!-- Açıklama -->
                        <div class="form-group">
                            <label for="description">Açıklama</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $category->description) }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <!-- Sıralama -->
                        <div class="form-group">
                            <label for="order">Sıralama</label>
                            <input type="number" class="form-control @error('order') is-invalid @enderror" id="order" name="order" value="{{ old('order', $category->order) }}" min="0">
                            @error('order')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <!-- Durum -->
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Aktif olarak yayınla</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <!-- İkon Seçimi -->
                        <div class="form-group">
                            <label for="icon">Kategori İkonu</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i id="selected-icon" class="{{ old('icon', $category->icon) }}"></i></span>
                                </div>
                                <input type="text" class="form-control @error('icon') is-invalid @enderror" id="icon" name="icon" value="{{ old('icon', $category->icon) }}" placeholder="İkon sınıfı örn: fas fa-home">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary" id="icon-picker-btn">
                                        <i class="fas fa-icons"></i> İkon Seç
                                    </button>
                                </div>
                            </div>
                            <small class="form-text text-muted">Font Awesome ikonları kullanılmaktadır. Örnek: fas fa-home</small>
                            @error('icon')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <!-- İkon Önizleme -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">İkon Önizleme</h3>
                            </div>
                            <div class="card-body text-center">
                                <div id="icon-preview" class="py-4">
                                    <i class="{{ old('icon', $category->icon) }} fa-3x"></i>
                                    <div class="mt-2">{{ old('name', $category->name) }}</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Hızlı Erişim Bağlantısı -->
                        <div class="card mt-3">
                            <div class="card-header">
                                <h3 class="card-title">Menü Öğeleri</h3>
                            </div>
                            <div class="card-body">
                                <p>Bu kategoriye ait <strong>{{ $category->items->count() }}</strong> menü öğesi bulunmaktadır.</p>
                                <a href="{{ route('admin.homepage.quick-menus.items', $category->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-list"></i> Menü Öğelerini Yönet
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Güncelle
                </button>
                <a href="{{ route('admin.homepage.quick-menus.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> İptal
                </a>
            </div>
        </form>
    </div>
    
    <!-- İkon Seçim Modalı -->
    <div class="modal fade" id="icon-picker-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">İkon Seçin</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" class="form-control" id="icon-search" placeholder="İkon ara...">
                    </div>
                    <div class="icon-picker-container">
                        <!-- Burada dinamik olarak ikonlar yüklenecek -->
                        <div class="row" id="icon-list">
                            <div class="col-md-3 col-sm-4 mb-3">
                                <div class="icon-item p-2 text-center" data-icon="fas fa-home">
                                    <i class="fas fa-home fa-2x"></i>
                                    <div class="mt-1 small">fa-home</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-4 mb-3">
                                <div class="icon-item p-2 text-center" data-icon="fas fa-user">
                                    <i class="fas fa-user fa-2x"></i>
                                    <div class="mt-1 small">fa-user</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-4 mb-3">
                                <div class="icon-item p-2 text-center" data-icon="fas fa-folder">
                                    <i class="fas fa-folder fa-2x"></i>
                                    <div class="mt-1 small">fa-folder</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-4 mb-3">
                                <div class="icon-item p-2 text-center" data-icon="fas fa-file">
                                    <i class="fas fa-file fa-2x"></i>
                                    <div class="mt-1 small">fa-file</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-4 mb-3">
                                <div class="icon-item p-2 text-center" data-icon="fas fa-cog">
                                    <i class="fas fa-cog fa-2x"></i>
                                    <div class="mt-1 small">fa-cog</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-4 mb-3">
                                <div class="icon-item p-2 text-center" data-icon="fas fa-list">
                                    <i class="fas fa-list fa-2x"></i>
                                    <div class="mt-1 small">fa-list</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-4 mb-3">
                                <div class="icon-item p-2 text-center" data-icon="fas fa-check">
                                    <i class="fas fa-check fa-2x"></i>
                                    <div class="mt-1 small">fa-check</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-4 mb-3">
                                <div class="icon-item p-2 text-center" data-icon="fas fa-phone">
                                    <i class="fas fa-phone fa-2x"></i>
                                    <div class="mt-1 small">fa-phone</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-4 mb-3">
                                <div class="icon-item p-2 text-center" data-icon="fas fa-envelope">
                                    <i class="fas fa-envelope fa-2x"></i>
                                    <div class="mt-1 small">fa-envelope</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-4 mb-3">
                                <div class="icon-item p-2 text-center" data-icon="fas fa-image">
                                    <i class="fas fa-image fa-2x"></i>
                                    <div class="mt-1 small">fa-image</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-4 mb-3">
                                <div class="icon-item p-2 text-center" data-icon="fas fa-search">
                                    <i class="fas fa-search fa-2x"></i>
                                    <div class="mt-1 small">fa-search</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-4 mb-3">
                                <div class="icon-item p-2 text-center" data-icon="fas fa-calendar">
                                    <i class="fas fa-calendar fa-2x"></i>
                                    <div class="mt-1 small">fa-calendar</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // İkon önizleme güncelleme
            $('#icon, #name').on('input', function() {
                updateIconPreview();
            });
            
            function updateIconPreview() {
                const iconClass = $('#icon').val();
                const categoryName = $('#name').val() || 'Kategori Adı';
                
                $('#selected-icon').attr('class', iconClass);
                $('#icon-preview i').attr('class', iconClass + ' fa-3x');
                $('#icon-preview div').text(categoryName);
            }
            
            // İkon seçici modal
            $('#icon-picker-btn').click(function() {
                $('#icon-picker-modal').modal('show');
            });
            
            // İkon arama
            $('#icon-search').on('input', function() {
                const searchTerm = $(this).val().toLowerCase();
                $('.icon-item').each(function() {
                    const iconName = $(this).data('icon').toLowerCase();
                    if (iconName.includes(searchTerm)) {
                        $(this).parent().show();
                    } else {
                        $(this).parent().hide();
                    }
                });
            });
            
            // İkon seçme
            $('.icon-item').click(function() {
                const selectedIcon = $(this).data('icon');
                $('#icon').val(selectedIcon);
                updateIconPreview();
                $('#icon-picker-modal').modal('hide');
            });
        });
    </script>
@stop

@section('css')
    <style>
        .icon-item {
            cursor: pointer;
            border-radius: 4px;
            transition: all 0.2s;
        }
        
        .icon-item:hover {
            background-color: #f8f9fa;
            transform: scale(1.05);
        }
        
        .icon-picker-container {
            max-height: 300px;
            overflow-y: auto;
        }
        
        #icon-preview {
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 4px;
        }
    </style>
@stop 