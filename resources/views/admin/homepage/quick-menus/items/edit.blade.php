@extends('adminlte::page')

@section('title', $category->name . ' - Menü Öğesi Düzenle')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>{{ $category->name }} - Menü Öğesi Düzenle</h1>
        <a href="{{ route('admin.homepage.quick-menus.items', $category->id) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Menü Öğelerine Dön
        </a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Menü Öğesi Düzenle</h3>
            <div class="card-tools">
                <a href="{{ route('admin.homepage.quick-menus.items', $category->id) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Geri Dön
                </a>
            </div>
        </div>
        
        <form action="{{ route('admin.homepage.quick-menus.items.update', ['category_id' => $category->id, 'id' => $item->id]) }}" method="POST" id="menu-item-form">
            @csrf
            @method('PUT')
            
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
                
                <div class="category-info mb-4">
                    <div class="d-flex align-items-center">
                        @if($category->icon)
                            <i class="{{ $category->icon }} fa-2x mr-3"></i>
                        @endif
                        <div>
                            <h4 class="mb-1">{{ $category->name }}</h4>
                            @if($category->description)
                                <p class="text-muted mb-0">{{ $category->description }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <!-- Başlık -->
                        <div class="form-group">
                            <label for="title">Başlık <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $item->title) }}" required>
                            @error('title')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <!-- URL -->
                        <div class="form-group">
                            <label for="url">URL <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('url') is-invalid @enderror" id="url" name="url" value="{{ old('url', $item->url) }}" required placeholder="Örn: /services veya https://example.com">
                            @error('url')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">
                                Site içi linkler için sadece yolu belirtebilirsiniz. (Örn: /hakkimizda)
                            </small>
                        </div>
                        
                        <!-- Link Açılma Türü -->
                        <div class="form-group">
                            <label for="target">Link Açılma Türü</label>
                            <select class="form-control @error('target') is-invalid @enderror" id="target" name="target">
                                <option value="_self" {{ old('target', $item->target) == '_self' ? 'selected' : '' }}>
                                    Aynı sekmede aç
                                </option>
                                <option value="_blank" {{ old('target', $item->target) == '_blank' ? 'selected' : '' }}>
                                    Yeni sekmede aç
                                </option>
                            </select>
                            @error('target')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">
                                Linkin nasıl açılacağını belirleyin
                            </small>
                        </div>
                        
                        <!-- Sıralama -->
                        <div class="form-group">
                            <label for="order">Sıralama</label>
                            <input type="number" class="form-control @error('order') is-invalid @enderror" id="order" name="order" value="{{ old('order', $item->order) }}" min="0">
                            @error('order')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <!-- Durum -->
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', $item->is_active) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Aktif olarak yayınla</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <!-- İkon Seçimi -->
                        <div class="form-group">
                            <label for="icon">Menü Öğesi İkonu</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i id="selected-icon" class="{{ old('icon', $item->icon) }}"></i></span>
                                </div>
                                <input type="text" class="form-control @error('icon') is-invalid @enderror" id="icon" name="icon" value="{{ old('icon', $item->icon) }}" placeholder="İkon sınıfı örn: fas fa-link">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary" id="icon-picker-btn">
                                        <i class="fas fa-icons"></i> İkon Seç
                                    </button>
                                </div>
                            </div>
                            <small class="form-text text-muted">Font Awesome ikonları kullanılmaktadır. Örnek: fas fa-link</small>
                            @error('icon')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <!-- İkon Önizleme -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Menü Öğesi Önizleme</h3>
                            </div>
                            <div class="card-body text-center">
                                <div id="icon-preview" class="py-4">
                                    <i class="{{ old('icon', $item->icon) }} fa-3x"></i>
                                    <div class="mt-2">{{ old('title', $item->title) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Güncelle
                </button>
                <a href="{{ route('admin.homepage.quick-menus.items', $category->id) }}" class="btn btn-secondary">
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
                                <div class="icon-item p-2 text-center" data-icon="fas fa-link">
                                    <i class="fas fa-link fa-2x"></i>
                                    <div class="mt-1 small">fa-link</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-4 mb-3">
                                <div class="icon-item p-2 text-center" data-icon="fas fa-external-link-alt">
                                    <i class="fas fa-external-link-alt fa-2x"></i>
                                    <div class="mt-1 small">fa-external-link-alt</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-4 mb-3">
                                <div class="icon-item p-2 text-center" data-icon="fas fa-info-circle">
                                    <i class="fas fa-info-circle fa-2x"></i>
                                    <div class="mt-1 small">fa-info-circle</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-4 mb-3">
                                <div class="icon-item p-2 text-center" data-icon="fas fa-question-circle">
                                    <i class="fas fa-question-circle fa-2x"></i>
                                    <div class="mt-1 small">fa-question-circle</div>
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
                                <div class="icon-item p-2 text-center" data-icon="fas fa-map-marker-alt">
                                    <i class="fas fa-map-marker-alt fa-2x"></i>
                                    <div class="mt-1 small">fa-map-marker-alt</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-4 mb-3">
                                <div class="icon-item p-2 text-center" data-icon="fas fa-shopping-cart">
                                    <i class="fas fa-shopping-cart fa-2x"></i>
                                    <div class="mt-1 small">fa-shopping-cart</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-4 mb-3">
                                <div class="icon-item p-2 text-center" data-icon="fas fa-user">
                                    <i class="fas fa-user fa-2x"></i>
                                    <div class="mt-1 small">fa-user</div>
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
            $('#icon, #title').on('input', function() {
                updateIconPreview();
            });
            
            function updateIconPreview() {
                const iconClass = $('#icon').val();
                const itemTitle = $('#title').val() || 'Menü Öğesi';
                
                $('#selected-icon').attr('class', iconClass);
                $('#icon-preview i').attr('class', iconClass + ' fa-3x');
                $('#icon-preview div').text(itemTitle);
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
        
        .category-info {
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
            border-left: 4px solid #007bff;
        }
    </style>
@stop 