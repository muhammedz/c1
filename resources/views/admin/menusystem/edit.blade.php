@extends('adminlte::page')

@section('css')
<style>
    .button-menu-item {
        transition: all 0.3s;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .button-menu-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
    }
    
    .button-menu-container {
        background-color: #f8f9fa;
        border-radius: 8px;
    }
    
    .button-menu-item .card-body {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 160px;
    }
</style>
@endsection

@section('title', 'Menü Düzenle')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Menü Düzenle: {{ $menu->name }}</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Panel</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.menusystem.index') }}">Menü Yönetimi</a></li>
                <li class="breadcrumb-item active">Menü Düzenle</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.menusystem.update', $menu->id) }}" method="POST" id="menu-form">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Menü Adı <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $menu->name) }}" required>
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type">Menü Tipi <span class="text-danger">*</span></label>
                                    <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                                        <option value="">Seçiniz</option>
                                        <option value="1" {{ old('type', $menu->type) == '1' ? 'selected' : '' }}>Küçük Menü (Header/Footer)</option>
                                        <option value="2" {{ old('type', $menu->type) == '2' ? 'selected' : '' }}>Büyük Menü (Kategori Alt Başlıklı)</option>
                                        <option value="3" {{ old('type', $menu->type) == '3' ? 'selected' : '' }}>Buton Menü</option>
                                    </select>
                                    @error('type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="url">URL (Opsiyonel)</label>
                                    <input type="text" class="form-control @error('url') is-invalid @enderror" id="url" name="url" value="{{ old('url', $menu->url) }}">
                                    <small class="form-text text-muted">Menü direkt bir bağlantıya yönlendirilecekse doldurun</small>
                                    @error('url')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="order">Sıralama</label>
                                    <input type="number" class="form-control @error('order') is-invalid @enderror" id="order" name="order" value="{{ old('order', $menu->order) }}" min="0">
                                    @error('order')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Durumu</label>
                                    <div class="custom-control custom-switch mt-2">
                                        <input type="checkbox" class="custom-control-input" id="status" name="status" value="1" {{ old('status', $menu->status) == '1' ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="status">Aktif</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Açıklama (Opsiyonel)</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $menu->description) }}</textarea>
                            @error('description')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        
                        <div class="text-right">
                            <button type="submit" class="btn btn-success waves-effect waves-light">Güncelle</button>
                            <a href="{{ route('admin.menusystem.index') }}" class="btn btn-secondary waves-effect">İptal</a>
                        </div>
                    </form>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
    
    <!-- Menü Öğeleri Bölümü (Büyük Menü için) -->
    @if($menu->type == 2)
    <div class="row mt-3 menu-items-section">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Menü Öğeleri</h5>
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addMenuItemModal">
                            <i class="mdi mdi-plus-circle mr-1"></i> Yeni Öğe Ekle
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width: 5%">#</th>
                                    <th style="width: 30%">Başlık</th>
                                    <th style="width: 25%">URL</th>
                                    <th style="width: 15%">Alt Öğeler</th>
                                    <th style="width: 10%">Sıralama</th>
                                    <th style="width: 10%">Durum</th>
                                    <th style="width: 15%">İşlemler</th>
                                </tr>
                            </thead>
                            <tbody id="menuItemsContainer">
                                @if(isset($menuItems) && count($menuItems) > 0)
                                    @foreach($menuItems as $item)
                                    <tr id="item-{{ $item->id }}">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->title }}</td>
                                        <td>{{ $item->url }}</td>
                                        <td>{{ $item->children_count ?? 0 }}</td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm item-order" 
                                                data-id="{{ $item->id }}" 
                                                value="{{ $item->order }}" min="0">
                                        </td>
                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input item-status" 
                                                    id="itemStatus{{ $item->id }}" 
                                                    data-id="{{ $item->id }}" 
                                                    {{ $item->status ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="itemStatus{{ $item->id }}"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.menusystem.items.edit', $item->id) }}" class="btn btn-sm btn-info">
                                                <i class="mdi mdi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger delete-item" 
                                                data-id="{{ $item->id }}">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                <tr>
                                    <td colspan="7" class="text-center">Henüz menü öğesi bulunmuyor</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Menü Öğesi Ekleme Modal -->
    <div class="modal fade" id="addMenuItemModal" tabindex="-1" role="dialog" aria-labelledby="addMenuItemModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addMenuItemModalLabel">Yeni Menü Öğesi Ekle</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addMenuItemForm">
                    <div class="modal-body">
                        <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                        <div class="form-group">
                            <label for="itemTitle">Başlık <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="itemTitle" name="title" required>
                        </div>
                        <div class="form-group">
                            <label for="itemUrl">URL <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="itemUrl" name="url" required>
                        </div>
                        <div class="form-group">
                            <label for="itemOrder">Sıralama</label>
                            <input type="number" class="form-control" id="itemOrder" name="order" value="0" min="0">
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="itemStatusCheck" name="status" value="1" checked>
                                <label class="custom-control-label" for="itemStatusCheck">Aktif</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">İptal</button>
                        <button type="submit" class="btn btn-primary">Kaydet</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Menü Öğesi Düzenleme Modal -->
    <div class="modal fade" id="editMenuItemModal" tabindex="-1" role="dialog" aria-labelledby="editMenuItemModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editMenuItemModalLabel">Menü Öğesini Düzenle</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editMenuItemForm">
                    <div class="modal-body">
                        <input type="hidden" id="editItemId" name="item_id">
                        <div class="form-group">
                            <label for="editItemTitle">Başlık <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editItemTitle" name="title" required>
                        </div>
                        <div class="form-group">
                            <label for="editItemUrl">URL <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editItemUrl" name="url" required>
                        </div>
                        <div class="form-group">
                            <label for="editItemOrder">Sıralama</label>
                            <input type="number" class="form-control" id="editItemOrder" name="order" value="0" min="0">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">İptal</button>
                        <button type="submit" class="btn btn-primary">Güncelle</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Buton Menü Bölümü -->
    @if($menu->type == 3)
    <div class="row mt-3 button-menu-section">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Buton Menü Öğeleri</h5>
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addButtonMenuItemModal">
                            <i class="mdi mdi-plus-circle mr-1"></i> Yeni Buton Ekle
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row" id="buttonMenuPreview">
                        <div class="col-12 mb-4">
                            <h5>Önizleme</h5>
                            <div class="button-menu-container p-3 border rounded">
                                <div class="row">
                                    @if(isset($menuItems) && count($menuItems) > 0)
                                        @foreach($menuItems as $item)
                                        <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                                            <div class="card h-100 button-menu-item">
                                                <div class="card-body text-center">
                                                    @if($item->icon)
                                                    <div class="mb-3">
                                                        <i class="{{ $item->icon }} fa-3x"></i>
                                                    </div>
                                                    @endif
                                                    <h5 class="card-title">{{ $item->title }}</h5>
                                                    @if($item->description)
                                                    <p class="card-text small">{{ $item->description }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    @else
                                        <div class="col-12">
                                            <p class="text-center">Henüz buton öğesi bulunmuyor</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width: 5%">#</th>
                                    <th style="width: 20%">Başlık</th>
                                    <th style="width: 15%">İkon</th>
                                    <th style="width: 20%">URL</th>
                                    <th style="width: 20%">Açıklama</th>
                                    <th style="width: 10%">Sıralama</th>
                                    <th style="width: 10%">Durum</th>
                                    <th style="width: 15%">İşlemler</th>
                                </tr>
                            </thead>
                            <tbody id="buttonMenuItemsContainer">
                                @if(isset($menuItems) && count($menuItems) > 0)
                                    @foreach($menuItems as $item)
                                    <tr id="button-item-{{ $item->id }}">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->title }}</td>
                                        <td>
                                            @if($item->icon)
                                            <i class="{{ $item->icon }}"></i> {{ $item->icon }}
                                            @else
                                            <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>{{ $item->url }}</td>
                                        <td>{{ Str::limit($item->description, 30) }}</td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm item-order" 
                                                data-id="{{ $item->id }}" 
                                                value="{{ $item->order }}" min="0">
                                        </td>
                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input item-status" 
                                                    id="buttonItemStatus{{ $item->id }}" 
                                                    data-id="{{ $item->id }}" 
                                                    {{ $item->status ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="buttonItemStatus{{ $item->id }}"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-info edit-button-item" 
                                                data-id="{{ $item->id }}"
                                                data-title="{{ $item->title }}"
                                                data-icon="{{ $item->icon }}"
                                                data-url="{{ $item->url }}"
                                                data-description="{{ $item->description }}"
                                                data-order="{{ $item->order }}"
                                                data-status="{{ $item->status }}"
                                                data-new-tab="{{ $item->new_tab }}">
                                                <i class="mdi mdi-pencil"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger delete-button-item" 
                                                data-id="{{ $item->id }}">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                <tr>
                                    <td colspan="8" class="text-center">Henüz buton öğesi bulunmuyor</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Buton Menü Öğesi Ekleme Modal -->
    <div class="modal fade" id="addButtonMenuItemModal" tabindex="-1" role="dialog" aria-labelledby="addButtonMenuItemModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addButtonMenuItemModalLabel"><i class="fas fa-plus-circle mr-2"></i>Yeni Buton Öğesi Ekle</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
                <form action="{{ route('admin.menusystem.items.store') }}" method="POST" id="directSubmitForm">
                    @csrf
                    <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                    
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="buttonItemTitle">Başlık <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="buttonItemTitle" name="title" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="buttonItemIcon">İkon (Font Awesome)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-icons"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="buttonItemIcon" name="icon" placeholder="örn: fas fa-home">
                                    </div>
                                    <small class="form-text text-muted">
                                        <a href="https://fontawesome.com/icons" target="_blank">Font Awesome</a> ikonları kullanabilirsiniz
                                    </small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="buttonItemUrl">URL <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-link"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="buttonItemUrl" name="url" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="buttonItemDescription">Kısa Açıklama</label>
                                    <textarea class="form-control" id="buttonItemDescription" name="description" rows="3"></textarea>
                                </div>
                                
                                <div class="form-group">
                                    <label for="buttonItemOrder">Sıralama</label>
                                    <input type="number" class="form-control" id="buttonItemOrder" name="order" value="0" min="0">
                                </div>
                                
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="buttonItemStatus" name="status" value="1" checked>
                                        <label class="custom-control-label" for="buttonItemStatus">Aktif</label>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="buttonItemNewTab" name="new_tab" value="1">
                                        <label class="custom-control-label" for="buttonItemNewTab">Yeni sekmede aç</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times mr-1"></i>İptal</button>
                        <button type="submit" class="btn btn-success"><i class="fas fa-save mr-1"></i>Kaydet</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Buton Menü Öğesi Düzenleme Modal -->
    <div class="modal fade" id="editButtonMenuItemModal" tabindex="-1" role="dialog" aria-labelledby="editButtonMenuItemModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editButtonMenuItemModalLabel">Buton Öğesi Düzenle</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editButtonMenuItemForm">
                        @csrf
                        <input type="hidden" name="item_id" id="editButtonItemId">
                        
                        <div class="form-group">
                            <label for="editButtonItemTitle">Başlık <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editButtonItemTitle" name="title" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="editButtonItemIcon">İkon (Font Awesome)</label>
                            <input type="text" class="form-control" id="editButtonItemIcon" name="icon" placeholder="örn: fas fa-home">
                            <small class="form-text text-muted">
                                <a href="https://fontawesome.com/icons" target="_blank">Font Awesome</a> ikonları kullanabilirsiniz
                            </small>
                        </div>
                        
                        <div class="form-group">
                            <label for="editButtonItemUrl">URL <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editButtonItemUrl" name="url" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="editButtonItemDescription">Kısa Açıklama</label>
                            <textarea class="form-control" id="editButtonItemDescription" name="description" rows="2"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="editButtonItemOrder">Sıralama</label>
                            <input type="number" class="form-control" id="editButtonItemOrder" name="order" value="0" min="0">
                        </div>
                        
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input status-toggle" id="editButtonItemStatus">
                                <input type="hidden" name="status" value="0" id="editButtonItemStatusHidden">
                                <label class="custom-control-label" for="editButtonItemStatus">Aktif</label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input new-tab-toggle" id="editButtonItemNewTab">
                                <input type="hidden" name="new_tab" value="0" id="editButtonItemNewTabHidden">
                                <label class="custom-control-label" for="editButtonItemNewTab">Yeni sekmede aç</label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">İptal</button>
                    <button type="button" class="btn btn-primary" id="updateButtonMenuItem">Güncelle</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Menü tipi değiştiğinde özel alanları göster/gizle
        $('#type').change(function() {
            updateMenuTypeUI();
        });
        
        // Sayfa yüklendiğinde mevcut seçime göre düzenle
        updateMenuTypeUI();
        
        // Menü tipine göre UI elemanlarını güncelle
        function updateMenuTypeUI() {
            const menuType = $('#type').val();
            
            // Şartlı olarak menü öğeleri bölümünü göster/gizle
            if (menuType === '1') {
                // Küçük menü için URL alanını göster
                $('#url').closest('.form-group').show();
                $('.menu-items-section').hide();
                $('.button-menu-section').hide();
            } else if (menuType === '2') {
                // Büyük menü için URL alanını temizle ve gizle
                $('#url').val('').closest('.form-group').hide();
                $('.menu-items-section').show();
                $('.button-menu-section').hide();
            } else if (menuType === '3') {
                // Buton menü için URL alanını göster
                $('#url').closest('.form-group').show();
                $('.menu-items-section').hide();
                $('.button-menu-section').show();
            }
        }
        
        // Form gönderimi
        $('#menu-form').submit(function(e) {
            const menuType = $('#type').val();
            
            // Minimum gerekli alanları kontrol et
            if (!$('#name').val()) {
                e.preventDefault();
                Swal.fire({
                    title: 'Uyarı!',
                    text: 'Menü adı alanı zorunludur.',
                    icon: 'warning',
                    confirmButtonText: 'Tamam'
                });
                return;
            }
            
            if (!menuType) {
                e.preventDefault();
                Swal.fire({
                    title: 'Uyarı!',
                    text: 'Menü tipi seçilmelidir.',
                    icon: 'warning',
                    confirmButtonText: 'Tamam'
                });
                return;
            }
        });
        
        @if($menu->type == 2)
        // Büyük menü için öğelerin AJAX işlemleri
        $('.item-order').change(function() {
            const itemId = $(this).data('id');
            const newOrder = $(this).val();
            
            $.ajax({
                url: '{{ route("admin.menusystem.items.order") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    item_id: itemId,
                    order: newOrder
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success('Sıralama güncellendi');
                    } else {
                        toastr.error('Sıralama güncellenirken hata oluştu');
                    }
                },
                error: function() {
                    toastr.error('Sıralama güncellenirken hata oluştu');
                }
            });
        });
        
        // Menü öğesi durum değişikliği
        $('.item-status').change(function() {
            const itemId = $(this).data('id');
            const newStatus = $(this).prop('checked') ? 1 : 0;
            
            $.ajax({
                url: '{{ route("admin.menusystem.items.update_status") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    item_id: itemId,
                    status: newStatus
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success('Durum güncellendi');
                    } else {
                        toastr.error('Durum güncellenirken hata oluştu');
                    }
                },
                error: function() {
                    toastr.error('Durum güncellenirken hata oluştu');
                }
            });
        });
        
        // Yeni menü öğesi ekleme
        $('#addMenuItemForm').submit(function(e) {
            e.preventDefault();
            
            const formData = $(this).serialize();
            
            $.ajax({
                url: '{{ route("admin.menusystem.items.store") }}',
                method: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        $('#addMenuItemModal').modal('hide');
                        
                        // Sayfa yenileme
                        toastr.success('Menü öğesi eklendi');
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    } else {
                        toastr.error(response.message || 'Menü öğesi eklenirken hata oluştu');
                    }
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        const errors = xhr.responseJSON.errors;
                        for (const key in errors) {
                            toastr.error(errors[key][0]);
                        }
                    } else {
                        toastr.error('Menü öğesi eklenirken hata oluştu');
                    }
                }
            });
        });
        
        // Menü öğesi düzenleme modalını aç
        $('.edit-item').click(function() {
            const itemId = $(this).data('id');
            const title = $(this).data('title');
            const url = $(this).data('url');
            const order = $(this).data('order');
            
            $('#editItemId').val(itemId);
            $('#editItemTitle').val(title);
            $('#editItemUrl').val(url);
            $('#editItemOrder').val(order);
            
            $('#editMenuItemModal').modal('show');
        });
        
        // Menü öğesi güncelleme
        $('#editMenuItemForm').submit(function(e) {
            e.preventDefault();
            
            const formData = $(this).serialize();
            const itemId = $('#editItemId').val();
            
            $.ajax({
                url: '{{ route("admin.menusystem.items.update", ":id") }}'.replace(':id', itemId),
                method: 'POST',
                data: formData + '&_method=PUT',
                success: function(response) {
                    if (response.success) {
                        $('#editMenuItemModal').modal('hide');
                        
                        // Sayfa yenileme
                        toastr.success('Menü öğesi güncellendi');
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    } else {
                        toastr.error('Menü öğesi güncellenirken hata oluştu');
                    }
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        const errors = xhr.responseJSON.errors;
                        for (const key in errors) {
                            toastr.error(errors[key][0]);
                        }
                    } else {
                        toastr.error('Menü öğesi güncellenirken hata oluştu');
                    }
                }
            });
        });
        
        // Menü öğesi silme
        $('.delete-item').click(function() {
            const itemId = $(this).data('id');
            
            Swal.fire({
                title: 'Emin misiniz?',
                text: 'Bu menü öğesini silmek istediğinize emin misiniz?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Evet, sil!',
                cancelButtonText: 'İptal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("admin.menusystem.items.destroy", ":id") }}'.replace(':id', itemId),
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'DELETE'
                        },
                        success: function(response) {
                            if (response.success) {
                                $('#item-' + itemId).fadeOut(300, function() {
                                    $(this).remove();
                                    
                                    if ($('#menuItemsContainer tr').length === 0) {
                                        $('#menuItemsContainer').html('<tr><td colspan="7" class="text-center">Henüz menü öğesi bulunmuyor</td></tr>');
                                    }
                                });
                                
                                toastr.success('Menü öğesi silindi');
                            } else {
                                toastr.error('Menü öğesi silinirken hata oluştu');
                            }
                        },
                        error: function() {
                            toastr.error('Menü öğesi silinirken hata oluştu');
                        }
                    });
                }
            });
        });
        @endif
        
        @if($menu->type == 3)
        // Sayfa yüklendiğinde
        $(document).ready(function() {
            // Buton menü öğesi ekleme
            $('#saveButtonMenuItem').click(function() {
                // Form verilerini topla
                const form = $('#addButtonMenuItemForm');
                
                // Form doğrulama
                if (!$('#buttonItemTitle').val()) {
                    toastr.error('Başlık alanı zorunludur');
                    return;
                }
                
                if (!$('#buttonItemUrl').val()) {
                    toastr.error('URL alanı zorunludur');
                    return;
                }
                
                // Checkbox durumlarını kontrol et ve hidden input olarak ekle
                if ($('#buttonItemStatus').is(':checked') && !form.find('input[name="status"]').length) {
                    form.append('<input type="hidden" name="status" value="1">');
                }
                
                if ($('#buttonItemNewTab').is(':checked') && !form.find('input[name="new_tab"]').length) {
                    form.append('<input type="hidden" name="new_tab" value="1">');
                }
                
                // Form verilerini serialize et
                const formData = form.serialize();
                
                $.ajax({
                    url: '{{ route("admin.menusystem.items.store") }}',
                    method: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            // Modal'ı kapat
                            $('#addButtonMenuItemModal').modal('hide');
                            
                            // Form alanlarını temizle
                            form[0].reset();
                            
                            // Başarı mesajı göster
                            toastr.success('Buton öğesi başarıyla eklendi');
                            
                            // Sayfayı yenile
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                        } else {
                            toastr.error('Buton öğesi eklenirken bir hata oluştu');
                        }
                    },
                    error: function(xhr) {
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            const errors = xhr.responseJSON.errors;
                            for (const key in errors) {
                                toastr.error(errors[key][0]);
                            }
                        } else {
                            toastr.error('Buton öğesi eklenirken bir hata oluştu');
                        }
                    }
                });
            });
            
            // Formun doğrudan sunucuya gönderilmesini engelle, JS ile işle
            $('#directSubmitForm').on('submit', function(e) {
                e.preventDefault();
                
                // Form verilerini topla
                var formData = $(this).serialize();
                
                // AJAX ile gönder
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            // Modal'ı kapat
                            $('#addButtonMenuItemModal').modal('hide');
                            
                            // Başarı mesajı göster
                            toastr.success('Buton öğesi başarıyla eklendi');
                            
                            // Sayfayı yenile
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                        } else {
                            toastr.error('Buton öğesi eklenirken bir hata oluştu');
                        }
                    },
                    error: function(xhr) {
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            var errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                toastr.error(value[0]);
                            });
                        } else {
                            toastr.error('Buton öğesi eklenirken bir hata oluştu');
                        }
                    }
                });
            });
        });
        
        // Buton menü öğesi düzenleme modalını aç
        $('.edit-button-item').click(function() {
            const itemId = $(this).data('id');
            const title = $(this).data('title');
            const icon = $(this).data('icon');
            const url = $(this).data('url');
            const description = $(this).data('description');
            const order = $(this).data('order');
            const status = $(this).data('status');
            const newTab = $(this).data('new-tab');
            
            $('#editButtonItemId').val(itemId);
            $('#editButtonItemTitle').val(title);
            $('#editButtonItemIcon').val(icon);
            $('#editButtonItemUrl').val(url);
            $('#editButtonItemDescription').val(description);
            $('#editButtonItemOrder').val(order);
            
            // Checkbox durumlarını ayarla
            $('#editButtonItemStatus').prop('checked', status === '1' || status === true);
            $('#editButtonItemNewTab').prop('checked', newTab === '1' || newTab === true);
            
            $('#editButtonMenuItemModal').modal('show');
        });
        
        // Buton menü öğesi güncelleme
        $('#updateButtonMenuItem').click(function() {
            const form = $('#editButtonMenuItemForm');
            
            // Checkbox durumlarını kontrol et ve hidden input olarak ekle
            if ($('#editButtonItemStatus').is(':checked') && !$('input[name="status"]').length) {
                form.append('<input type="hidden" name="status" value="1">');
            }
            
            if ($('#editButtonItemNewTab').is(':checked') && !$('input[name="new_tab"]').length) {
                form.append('<input type="hidden" name="new_tab" value="1">');
            }
            
            const formData = form.serialize();
            const itemId = $('#editButtonItemId').val();
            
            // Form doğrulama
            if (!$('#editButtonItemTitle').val()) {
                toastr.error('Başlık alanı zorunludur');
                return;
            }
            
            if (!$('#editButtonItemUrl').val()) {
                toastr.error('URL alanı zorunludur');
                return;
            }
            
            $.ajax({
                url: '{{ route("admin.menusystem.items.update", ":id") }}'.replace(':id', itemId),
                method: 'POST',
                data: formData + '&_method=PUT',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        $('#editButtonMenuItemModal').modal('hide');
                        
                        // Başarı mesajı göster
                        toastr.success('Buton öğesi başarıyla güncellendi');
                        
                        // Sayfayı yenile
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    } else {
                        toastr.error('Buton öğesi güncellenirken bir hata oluştu');
                    }
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        const errors = xhr.responseJSON.errors;
                        for (const key in errors) {
                            toastr.error(errors[key][0]);
                        }
                    } else {
                        toastr.error('Buton öğesi güncellenirken bir hata oluştu');
                    }
                }
            });
        });
        
        // Buton menü öğesi silme
        $('.delete-button-item').click(function() {
            const itemId = $(this).data('id');
            
            Swal.fire({
                title: 'Emin misiniz?',
                text: 'Bu buton öğesini silmek istediğinize emin misiniz?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Evet, sil!',
                cancelButtonText: 'İptal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("admin.menusystem.items.destroy", ":id") }}'.replace(':id', itemId),
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'DELETE'
                        },
                        success: function(response) {
                            if (response.success) {
                                $('#button-item-' + itemId).fadeOut(300, function() {
                                    $(this).remove();
                                    
                                    // Eğer kalan öğe yoksa mesaj göster
                                    if ($('#buttonMenuItemsContainer tr').length === 0) {
                                        $('#buttonMenuItemsContainer').html('<tr><td colspan="8" class="text-center">Henüz buton öğesi bulunmuyor</td></tr>');
                                    }
                                    
                                    // Önizlemedeki butonları da güncelle
                                    updateButtonMenuPreview();
                                });
                                
                                toastr.success('Buton öğesi silindi');
                            } else {
                                toastr.error('Buton öğesi silinirken hata oluştu');
                            }
                        },
                        error: function() {
                            toastr.error('Buton öğesi silinirken hata oluştu');
                        }
                    });
                }
            });
        });
        
        // Buton menü önizlemesini güncelle
        function updateButtonMenuPreview() {
            // Ajax ile önizleme verilerini çek
            $.ajax({
                url: '{{ route("admin.menusystem.items.getItems", $menu->id) }}',
                method: 'GET',
                success: function(response) {
                    if (response.success && response.items) {
                        let html = '<div class="row">';
                        
                        if (response.items.length > 0) {
                            response.items.forEach(function(item) {
                                html += `
                                <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                                    <div class="card h-100 button-menu-item">
                                        <div class="card-body text-center">`;
                                
                                if (item.icon) {
                                    html += `<div class="mb-3"><i class="${item.icon} fa-3x"></i></div>`;
                                }
                                
                                html += `<h5 class="card-title">${item.title}</h5>`;
                                
                                if (item.description) {
                                    html += `<p class="card-text small">${item.description}</p>`;
                                }
                                
                                html += `
                                        </div>
                                    </div>
                                </div>`;
                            });
                        } else {
                            html += '<div class="col-12"><p class="text-center">Henüz buton öğesi bulunmuyor</p></div>';
                        }
                        
                        html += '</div>';
                        
                        $('.button-menu-container').html(html);
                    }
                }
            });
        }
        
        // Status/new_tab toggle olayları
        $('.status-toggle').change(function() {
            const isChecked = $(this).is(':checked');
            $(this).siblings('input[type="hidden"]').val(isChecked ? '1' : '0');
        });
        
        $('.new-tab-toggle').change(function() {
            const isChecked = $(this).is(':checked');
            $(this).siblings('input[type="hidden"]').val(isChecked ? '1' : '0');
        });
        
        // Sıralama veya durum değiştiğinde önizlemeyi güncelle
        $('.item-order, .item-status').change(function() {
            const id = $(this).data('id');
            const value = $(this).val();
            const isStatus = $(this).hasClass('item-status');
            
            const data = {
                _token: '{{ csrf_token() }}',
                item_id: id
            };
            
            if (isStatus) {
                data.status = $(this).prop('checked') ? 1 : 0;
            } else {
                data.order = value;
            }
            
            $.ajax({
                url: isStatus ? '{{ route("admin.menusystem.items.update_status") }}' : '{{ route("admin.menusystem.items.order") }}',
                method: 'POST',
                data: data,
                success: function(response) {
                    if (response.success) {
                        toastr.success(isStatus ? 'Durum güncellendi' : 'Sıralama güncellendi');
                        
                        // Önizlemeyi güncelle
                        updateButtonMenuPreview();
                    }
                }
            });
        });
        @endif
    });
</script>
@endsection 