@extends('adminlte::page')

@section('title', 'Menü Öğeleri')

@section('css')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<style>
    .icon-item {
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .icon-item:hover {
        background-color: #f8f9fa;
        border-color: #007bff !important;
        transform: translateY(-2px);
        box-shadow: 0 3px 5px rgba(0,0,0,0.1);
    }
    
    .icon-item.selected {
        background-color: #e9ecef;
        border-color: #007bff !important;
        color: #007bff;
    }
    
    .icon-grid {
        max-height: 300px;
        overflow-y: auto;
    }
</style>
@stop

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Menü Öğeleri: {{ $menu->name }}</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Panel</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.menusystem.index') }}">Menü Yönetimi</a></li>
                <li class="breadcrumb-item active">Menü Öğeleri</li>
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
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h5>Menü Bilgileri</h5>
                            <p><strong>Adı:</strong> {{ $menu->name }}</p>
                            <p><strong>Tipi:</strong> 
                                @if($menu->type == 1)
                                    <span class="badge badge-info">Küçük Menü</span>
                                @elseif($menu->type == 2)
                                    <span class="badge badge-primary">Büyük Menü</span>
                                @endif
                            </p>
                            <p><strong>Konumu:</strong> 
                                @if($menu->position == 'header')
                                    <span class="badge badge-success">Üst Menü</span>
                                @elseif($menu->position == 'footer')
                                    <span class="badge badge-secondary">Alt Menü</span>
                                @elseif($menu->position == 'sidebar')
                                    <span class="badge badge-warning">Yan Menü</span>
                                @elseif($menu->position == 'mobile')
                                    <span class="badge badge-dark">Mobil Menü</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="button" class="btn btn-primary" id="add-item-btn">
                                <i class="fas fa-plus-circle mr-1"></i> Yeni Öğe Ekle
                            </button>
                            <a href="{{ route('admin.menusystem.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-1"></i> Geri Dön
                            </a>
                        </div>
                    </div>

                    <!-- Açıklama Yazısı ve Linki Yönetimi -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Menü Açıklama Yazısı ve Linki</h5>
                                </div>
                                <div class="card-body">
                                    <form id="menu-footer-form">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="footer_text">Açıklama Yazısı</label>
                                                    <input type="text" class="form-control" id="footer_text" name="footer_text" 
                                                        value="{{ $menu->footer_text ?? 'Açıklama Yazısı' }}">
                                                    <small class="form-text text-muted">Bu yazı menü açıldığında alt kısımda görünecektir.</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="footer_link">Açıklama Linki</label>
                                                    <input type="text" class="form-control" id="footer_link" name="footer_link" 
                                                        value="{{ $menu->footer_link ?? '#' }}">
                                                    <small class="form-text text-muted">Bu link menü açıldığında alt kısımdaki linkle yönlendirecektir.</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 text-right">
                                                <button type="button" class="btn btn-primary" id="save-footer-btn">
                                                    <i class="fas fa-save"></i> Kaydet
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-centered table-striped dt-responsive nowrap w-100" id="items-table">
                            <thead>
                                <tr>
                                    <th style="width: 5%">ID</th>
                                    <th style="width: 25%">Öğe Adı</th>
                                    <th style="width: 15%">İkon</th>
                                    <th style="width: 25%">URL</th>
                                    <th style="width: 5%">Sıralama</th>
                                    <th style="width: 10%">Durum</th>
                                    <th style="width: 15%">İşlemler</th>
                                </tr>
                            </thead>
                            <tbody id="menu-items-body">
                                @php
                                    $parentItems = $menu->items->whereNull('parent_id')->sortBy('order');
                                @endphp
                                
                                @forelse($parentItems as $item)
                                <tr data-id="{{ $item->id }}" class="parent-item">
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->title }}</td>
                                    <td><i class="fas {{ $item->icon ?? 'fa-home' }}"></i> {{ $item->icon ?? 'fa-home' }}</td>
                                    <td>{{ $item->url }}</td>
                                    <td>{{ $item->order }}</td>
                                    <td>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input toggle-item-status" 
                                                id="itemStatus{{ $item->id }}" 
                                                data-id="{{ $item->id }}" 
                                                {{ $item->status ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="itemStatus{{ $item->id }}"></label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-primary add-child-item" data-parent-id="{{ $item->id }}" title="Alt menü ekle">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-info edit-item" 
                                                data-id="{{ $item->id }}" 
                                                data-title="{{ $item->title }}" 
                                                data-url="{{ $item->url }}" 
                                                data-order="{{ $item->order }}"
                                                data-status="{{ $item->status }}"
                                                data-icon="{{ $item->icon }}"
                                                data-parent-id="{{ $item->parent_id }}">
                                                <i class="fas fa-pencil-alt"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger delete-item" data-id="{{ $item->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                
                                @php
                                    $childItems = $menu->items->where('parent_id', $item->id)->sortBy('order');
                                @endphp
                                
                                @foreach($childItems as $childItem)
                                <tr data-id="{{ $childItem->id }}" class="child-item">
                                    <td>{{ $childItem->id }}</td>
                                    <td><span class="ml-4">└ {{ $childItem->title }}</span></td>
                                    <td><i class="fas {{ $childItem->icon ?? 'fa-home' }}"></i> {{ $childItem->icon ?? 'fa-home' }}</td>
                                    <td>{{ $childItem->url }}</td>
                                    <td>{{ $childItem->order }}</td>
                                    <td>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input toggle-item-status" 
                                                id="itemStatus{{ $childItem->id }}" 
                                                data-id="{{ $childItem->id }}" 
                                                {{ $childItem->status ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="itemStatus{{ $childItem->id }}"></label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-primary add-child-item" data-parent-id="{{ $childItem->id }}" title="Alt menü ekle">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-info edit-item" 
                                                data-id="{{ $childItem->id }}" 
                                                data-title="{{ $childItem->title }}" 
                                                data-url="{{ $childItem->url }}" 
                                                data-order="{{ $childItem->order }}"
                                                data-status="{{ $childItem->status }}"
                                                data-icon="{{ $childItem->icon }}"
                                                data-parent-id="{{ $childItem->parent_id }}">
                                                <i class="fas fa-pencil-alt"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger delete-item" data-id="{{ $childItem->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                
                                @php
                                    $subChildItems = $menu->items->where('parent_id', $childItem->id)->sortBy('order');
                                @endphp
                                
                                @foreach($subChildItems as $subChildItem)
                                <tr data-id="{{ $subChildItem->id }}" class="sub-child-item">
                                    <td>{{ $subChildItem->id }}</td>
                                    <td><span class="ml-5">└─ {{ $subChildItem->title }}</span></td>
                                    <td><i class="fas {{ $subChildItem->icon ?? 'fa-home' }}"></i> {{ $subChildItem->icon ?? 'fa-home' }}</td>
                                    <td>{{ $subChildItem->url }}</td>
                                    <td>{{ $subChildItem->order }}</td>
                                    <td>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input toggle-item-status" 
                                                id="itemStatus{{ $subChildItem->id }}" 
                                                data-id="{{ $subChildItem->id }}" 
                                                {{ $subChildItem->status ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="itemStatus{{ $subChildItem->id }}"></label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-info edit-item" 
                                                data-id="{{ $subChildItem->id }}" 
                                                data-title="{{ $subChildItem->title }}" 
                                                data-url="{{ $subChildItem->url }}" 
                                                data-order="{{ $subChildItem->order }}"
                                                data-status="{{ $subChildItem->status }}"
                                                data-icon="{{ $subChildItem->icon }}"
                                                data-parent-id="{{ $subChildItem->parent_id }}">
                                                <i class="fas fa-pencil-alt"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger delete-item" data-id="{{ $subChildItem->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                @endforeach
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">Bu menüye ait öğe bulunmuyor</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Menü Öğesi Ekleme/Düzenleme Modal -->
<div class="modal fade" id="menu-item-modal" tabindex="-1" role="dialog" aria-labelledby="menuItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="menuItemModalLabel">Yeni Menü Öğesi Ekle</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Kapat">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="menu-item-form">
                    <input type="hidden" id="item_id" name="item_id">
                    <input type="hidden" id="menu_id" name="menu_id" value="{{ $menu->id }}">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title">Öğe Adı <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="url">URL</label>
                                <input type="text" class="form-control" id="url" name="url">
                                <small class="form-text text-muted">Örnek: /hakkimizda, https://example.com</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="icon">İkon</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i id="selected-icon" class="material-icons">home</i></span>
                                    </div>
                                    <input type="text" class="form-control" id="icon" name="icon">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-secondary" id="test-icon-btn">
                                            <i class="material-icons">format_paint</i> İKON
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="parent_id">Üst Menü</label>
                                <select class="form-control" id="parent_id" name="parent_id">
                                    <option value="">Ana Menü (Üst Menü Yok)</option>
                                    @foreach($menu->items->where('parent_id', null)->sortBy('id') as $parentItem)
                                        <option value="{{ $parentItem->id }}">{{ $parentItem->title }} (ID: {{ $parentItem->id }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="order">Sıralama</label>
                                <input type="number" class="form-control" id="order" name="order" min="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mt-4 pt-2">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="status" name="status" checked>
                                    <label class="custom-control-label" for="status">Aktif</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> İptal</button>
                <button type="button" class="btn btn-primary" id="save-item-btn"><i class="fas fa-save"></i> Kaydet</button>
            </div>
        </div>
    </div>
</div>

<!-- İkon Test Modal -->
<div class="modal fade" id="icon-test-modal" tabindex="-1" role="dialog" aria-labelledby="iconTestModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="iconTestModalLabel">Material İkon Seçici</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Kapat">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="material-icons">search</i></span>
                            </div>
                            <input type="text" class="form-control" id="icon-search" placeholder="İkon ara...">
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Seçilen İkon</h5>
                            </div>
                            <div class="card-body text-center">
                                <i id="icon-test-preview" class="material-icons display-1">home</i>
                                <div class="mt-2">
                                    <code id="selected-icon-code">home</code>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Material İkonlar</h5>
                            </div>
                            <div class="card-body">
                                <div class="row icon-grid" id="icon-list">
                                    <!-- İkonlar dinamik olarak buraya yüklenecek -->
                                    <div class="col-md-2 col-sm-3 col-4 mb-3 icon-item-container">
                                        <div class="icon-item p-2 text-center border rounded" data-icon="home">
                                            <i class="material-icons">home</i>
                                            <p class="small text-muted mb-0">home</p>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-3 col-4 mb-3 icon-item-container">
                                        <div class="icon-item p-2 text-center border rounded" data-icon="person">
                                            <i class="material-icons">person</i>
                                            <p class="small text-muted mb-0">person</p>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-3 col-4 mb-3 icon-item-container">
                                        <div class="icon-item p-2 text-center border rounded" data-icon="settings">
                                            <i class="material-icons">settings</i>
                                            <p class="small text-muted mb-0">settings</p>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-3 col-4 mb-3 icon-item-container">
                                        <div class="icon-item p-2 text-center border rounded" data-icon="mail">
                                            <i class="material-icons">mail</i>
                                            <p class="small text-muted mb-0">mail</p>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-3 col-4 mb-3 icon-item-container">
                                        <div class="icon-item p-2 text-center border rounded" data-icon="phone">
                                            <i class="material-icons">phone</i>
                                            <p class="small text-muted mb-0">phone</p>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-3 col-4 mb-3 icon-item-container">
                                        <div class="icon-item p-2 text-center border rounded" data-icon="info">
                                            <i class="material-icons">info</i>
                                            <p class="small text-muted mb-0">info</p>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-3 col-4 mb-3 icon-item-container">
                                        <div class="icon-item p-2 text-center border rounded" data-icon="warning">
                                            <i class="material-icons">warning</i>
                                            <p class="small text-muted mb-0">warning</p>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-3 col-4 mb-3 icon-item-container">
                                        <div class="icon-item p-2 text-center border rounded" data-icon="error">
                                            <i class="material-icons">error</i>
                                            <p class="small text-muted mb-0">error</p>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-3 col-4 mb-3 icon-item-container">
                                        <div class="icon-item p-2 text-center border rounded" data-icon="add">
                                            <i class="material-icons">add</i>
                                            <p class="small text-muted mb-0">add</p>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-3 col-4 mb-3 icon-item-container">
                                        <div class="icon-item p-2 text-center border rounded" data-icon="remove">
                                            <i class="material-icons">remove</i>
                                            <p class="small text-muted mb-0">remove</p>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-3 col-4 mb-3 icon-item-container">
                                        <div class="icon-item p-2 text-center border rounded" data-icon="edit">
                                            <i class="material-icons">edit</i>
                                            <p class="small text-muted mb-0">edit</p>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-3 col-4 mb-3 icon-item-container">
                                        <div class="icon-item p-2 text-center border rounded" data-icon="delete">
                                            <i class="material-icons">delete</i>
                                            <p class="small text-muted mb-0">delete</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="material-icons" style="vertical-align: middle; font-size: 18px;">close</i> Kapat</button>
                <button type="button" class="btn btn-primary" id="select-icon-btn"><i class="material-icons" style="vertical-align: middle; font-size: 18px;">check</i> Seç</button>
            </div>
        </div>
    </div>
</div>

<!-- İkon Seçicisi için Style -->
<style>
    /* Menü öğeleri stilleri */
    .parent-item {
        background-color: #ffffff;
    }
    .child-item {
        background-color: #f8f9fa;
    }
    .sub-child-item {
        background-color: #f0f0f0;
    }
    
    /* Ağaç görünümü için çizgiler */
    .child-item td:nth-child(2)::before {
        content: "└─ ";
        color: #aaa;
    }
    
    .sub-child-item td:nth-child(2)::before {
        content: "└── ";
        color: #aaa;
    }
    
    /* Alt menü gösterim stilii */
    .ml-4 {
        margin-left: 1rem;
    }
    .ml-5 {
        margin-left: 1.5rem;
    }
    
    /* Modal içeriği için scroll ayarı */
    .modal-dialog.modal-lg .modal-body {
        max-height: 70vh;
        overflow-y: auto;
    }
    
    /* Üst menü seçimi için iyileştirmeler */
    #parent_id {
        color: #495057;
        background-color: #fff;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
    }
    
    #parent_id:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
</style>
@endsection

@section('js')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script>
    // Modalların doğru çalıştığını kontrol edelim
    $(function() {
        console.log('Document ready event tetiklendi');
        
        // Mevcut tüm modalları kontrol edelim
        var modals = document.querySelectorAll('.modal');
        console.log('Sayfada bulunan modal sayısı:', modals.length);
        modals.forEach(function(modal, index) {
            console.log('Modal #' + index + ' ID:', modal.id);
        });
        
        // Modal eventlerini dinleyelim
        $(document).on('show.bs.modal', '.modal', function() {
            console.log('Modal gösteriliyor:', this.id);
        });
        
        $(document).on('shown.bs.modal', '.modal', function() {
            console.log('Modal gösterildi:', this.id);
        });
        
        $(document).on('hide.bs.modal', '.modal', function() {
            console.log('Modal gizleniyor:', this.id);
        });
        
        $(document).on('hidden.bs.modal', '.modal', function() {
            console.log('Modal gizlendi:', this.id);
        });
        
        // TEST butonuna tıklama olayı
        $('#test-icon-btn').click(function() {
            console.log('İKON butonuna tıklandı, modal açmayı deniyorum...');
            
            var iconValue = $('#icon').val().trim() || 'home';
            
            // Seçilen ikonu güncelle
            $('#icon-test-preview').text(iconValue);
            $('#selected-icon-code').text(iconValue);
            
            // Tüm icon-item'leri temizle ve mevcut ikonu seçili yap
            $('.icon-item').removeClass('selected');
            $('.icon-item[data-icon="' + iconValue + '"]').addClass('selected');
            
            // Modalı açalım
            $('#icon-test-modal').modal('show');
            
            // Kontrol edelim modal elementi var mı?
            var modal = document.getElementById('icon-test-modal');
            console.log('Modal element bulundu mu?', modal !== null);
        });
        
        // İkon arama
        $('#icon-search').on('input', function() {
            var searchText = $(this).val().toLowerCase();
            $('.icon-item-container').each(function() {
                var iconName = $(this).find('.icon-item').data('icon').toLowerCase();
                if (iconName.indexOf(searchText) > -1) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
        
        // İkon seçme işlemi
        $(document).on('click', '.icon-item', function() {
            var iconName = $(this).data('icon');
            
            // Tüm item'leri deselect yap
            $('.icon-item').removeClass('selected');
            
            // Seçilen item'i select yap
            $(this).addClass('selected');
            
            // Önizleme
            $('#icon-test-preview').text(iconName);
            $('#selected-icon-code').text(iconName);
        });
        
        // Seçim butonuna tıklama
        $('#select-icon-btn').click(function() {
            var selectedIcon = $('#selected-icon-code').text();
            
            // İkon alanını güncelle
            $('#icon').val(selectedIcon);
            
            // Önizleme ikonunu güncelle - material icons için text içeriği güncellenmeli
            $('#selected-icon').attr('class', 'material-icons').text(selectedIcon);
            
            // Modalı kapat
            $('#icon-test-modal').modal('hide');
            
            console.log('İkon seçildi:', selectedIcon);
        });
        
        // İkon alanına manuel değer girildiğinde önizleme güncelle
        $('#icon').on('input', function() {
            var iconValue = $(this).val();
            if (iconValue) {
                $('#selected-icon').attr('class', 'material-icons').text(iconValue);
            }
        });
        
        // Eski kodu da çalıştıralım
        $(document).ready(function() {
            // Menü öğesi ekleme butonu
            $('#add-item-btn').click(function() {
                $('#menuItemModalLabel').text('Yeni Menü Öğesi Ekle');
                $('#menu-item-form')[0].reset();
                $('#item_id').val('');
                $('#parent_id').val('');
                
                // Parent seçeneklerini yeniden yükle
                refreshParentOptions();
                
                $('#menu-item-modal').modal('show');
            });
            
            // Alt menü ekleme butonu
            $(document).on('click', '.add-child-item', function() {
                $('#menuItemModalLabel').text('Alt Menü Ekle');
                $('#menu-item-form')[0].reset();
                $('#item_id').val('');
                
                // Üst menü id'sini ayarla
                var parentId = $(this).data('parent-id');
                $('#parent_id').val(parentId);
                
                // Parent seçeneklerini yeniden yükle
                refreshParentOptions();
                
                $('#menu-item-modal').modal('show');
            });
            
            // Parent seçeneklerini yeniden yükleme fonksiyonu
            function refreshParentOptions() {
                // Güncel menü ID'sini al
                var menuId = {{ $menu->id }};
                
                // AJAX ile güncel parent seçeneklerini getir
                $.ajax({
                    url: '{{ route("admin.menusystem.get-parent-items") }}',
                    method: 'GET',
                    data: { menu_id: menuId },
                    success: function(response) {
                        if (response.success && response.items) {
                            var options = '<option value="">Ana Menü (Üst Menü Yok)</option>';
                            
                            // Tüm items'ları döngüye al
                            $.each(response.items, function(index, item) {
                                options += '<option value="' + item.id + '">' + item.title + ' (ID: ' + item.id + ')</option>';
                            });
                            
                            // Select'i güncelle
                            $('#parent_id').html(options);
                        }
                    },
                    error: function() {
                        console.error('Üst menü seçenekleri yüklenirken hata oluştu');
                    }
                });
            }
            
            // Menü öğesi düzenleme butonu
            $('.edit-item').click(function() {
                $('#menuItemModalLabel').text('Menü Öğesi Düzenle');
                
                var id = $(this).data('id');
                var title = $(this).data('title');
                var url = $(this).data('url');
                var order = $(this).data('order');
                var status = $(this).data('status');
                var icon = $(this).data('icon') || 'fa-home';
                var parentId = $(this).data('parent-id');
                
                $('#item_id').val(id);
                $('#title').val(title);
                $('#url').val(url);
                $('#order').val(order);
                $('#status').prop('checked', status == 1);
                $('#icon').val(icon);
                $('#selected-icon').attr('class', 'fas ' + icon);
                $('#parent_id').val(parentId);
                
                $('#menu-item-modal').modal('show');
            });
            
            // Menü öğesi kaydetme butonu
            $('#save-item-btn').click(function() {
                var formData = {
                    _token: '{{ csrf_token() }}',
                    menu_id: $('#menu_id').val(),
                    title: $('#title').val().trim(),
                    url: $('#url').val().trim() || '',
                    order: $('#order').val() || 0,
                    icon: $('#icon').val().trim(),
                    parent_id: $('#parent_id').val(),
                    status: $('#status').is(':checked') ? 1 : 0
                };
                
                // Debug bilgilerini ekrana yazdır
                console.log('Form Verileri:', formData);

                // Debug div'ini oluştur ve önceki debug bilgilerini temizle
                $('.debug-info').remove();
                var debugInfo = '<div class="alert alert-info mt-3 debug-info" role="alert">';
                debugInfo += '<h5>Gönderilen Veriler:</h5>';
                for (var key in formData) {
                    debugInfo += '<strong>' + key + ':</strong> ' + formData[key] + '<br>';
                }
                debugInfo += '</div>';
                
                // Debug bilgilerini modal'a ekle
                $('#menu-item-form').after(debugInfo);
                
                var itemId = $('#item_id').val();
                var url, method;
                
                if (itemId) {
                    url = '{{ url("admin/menusystem/items/" . ":id") }}'.replace(':id', itemId);
                    method = 'PUT';
                } else {
                    url = '{{ url("admin/menusystem/items/store") }}';
                    method = 'POST';
                }
                
                // Form validasyonu
                if (!formData.title) {
                    toastr.error('Öğe adı zorunludur');
                    return;
                }
                
                // Icon validasyonu kaldırıldı
                
                // AJAX isteğini debug et
                console.log('AJAX İsteği:');
                console.log('URL:', url);
                console.log('Method:', method);
                
                $.ajax({
                    url: url,
                    method: method,
                    data: formData,
                    success: function(response) {
                        // Response debug
                        console.log('Başarılı Response:', response);
                        
                        if (response.success) {
                            $('#menu-item-modal').modal('hide');
                            toastr.success('Menü öğesi başarıyla kaydedildi');
                            window.location.reload();
                        } else {
                            toastr.error(response.message || 'Menü öğesi kaydedilirken hata oluştu');
                        }
                    },
                    error: function(xhr) {
                        // Hata debug
                        console.log('Hata Response:', xhr.responseJSON);
                        
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            if (errors) {
                                $.each(errors, function(key, value) {
                                    toastr.error(value[0]);
                                });
                            }
                        } else {
                            toastr.error('Menü öğesi kaydedilirken hata oluştu');
                        }
                    }
                });
            });
            
            // Menü öğesi silme butonu
            $('.delete-item').click(function() {
                var itemId = $(this).data('id');
                
                Swal.fire({
                    title: 'Emin misiniz?',
                    text: 'Bu menü öğesini silmek istediğinize emin misiniz? Bu işlem geri alınamaz!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Evet, sil!',
                    cancelButtonText: 'İptal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Debug için URL konsola yazdırma
                        console.log('Silme URL:', '/admin/menusystem/items/' + itemId);
                        
                        $.ajax({
                            url: '/admin/menusystem/items/' + itemId,
                            method: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    toastr.success('Menü öğesi başarıyla silindi');
                                    $('tr[data-id="' + itemId + '"]').remove();
                                    if ($('#menu-items-body tr').length === 0) {
                                        $('#menu-items-body').html('<tr><td colspan="7" class="text-center">Bu menüye ait öğe bulunmuyor</td></tr>');
                                    }
                                } else {
                                    toastr.error('Menü öğesi silinirken hata oluştu');
                                }
                            },
                            error: function(xhr) {
                                console.error('Silme hatası:', xhr.status, xhr.responseText);
                                toastr.error('Menü öğesi silinirken hata oluştu: ' + xhr.status);
                            }
                        });
                    }
                });
            });
            
            // Menü öğesi durum değiştirme
            $('.toggle-item-status').change(function() {
                var itemId = $(this).data('id');
                var status = $(this).prop('checked') ? 1 : 0;
                
                $.ajax({
                    url: '{{ route("admin.menusystem.items.update_status") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: itemId,
                        status: status
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success('Menü öğesi durumu güncellendi');
                        } else {
                            toastr.error('Menü öğesi durumu güncellenirken hata oluştu');
                            // Hata durumunda switch'i eski haline getir
                            $('#itemStatus' + itemId).prop('checked', !status);
                        }
                    },
                    error: function() {
                        toastr.error('Menü öğesi durumu güncellenirken hata oluştu');
                        // Hata durumunda switch'i eski haline getir
                        $('#itemStatus' + itemId).prop('checked', !status);
                    }
                });
            });

            // Sıralama işlemi - jQuery UI kontrol edildi
            try {
                if (typeof $.fn.sortable === 'function') {
                    console.log('Sortable fonksiyonu mevcut, sıralama aktif edildi');
                    $('#items-table tbody').sortable({
                        handle: 'tr',
                        update: function(event, ui) {
                            var items = [];
                            $('#items-table tbody tr').each(function(index) {
                                items.push({
                                    id: $(this).data('id'),
                                    order: index
                                });
                            });
                            
                            $.ajax({
                                url: '{{ route("admin.menusystem.items.order") }}',
                                method: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    items: items
                                },
                                success: function(response) {
                                    if (response.success) {
                                        toastr.success('Sıralama başarıyla güncellendi');
                                        // Sıra numaralarını güncelle
                                        $('#items-table tbody tr').each(function(index) {
                                            $(this).find('td:eq(4)').text(index);
                                        });
                                    } else {
                                        toastr.error(response.message || 'Sıralama güncellenirken hata oluştu');
                                    }
                                },
                                error: function(xhr) {
                                    toastr.error('Sıralama güncellenirken hata oluştu');
                                }
                            });
                        }
                    });
                } else {
                    console.error('jQuery UI Sortable yüklenmemiş. Sıralama işlevi devre dışı bırakıldı.');
                }
            } catch (e) {
                console.error('Sortable başlatma hatası:', e);
            }

            // Menü açıklama yazısı ve linkini kaydetme
            $('#save-footer-btn').click(function() {
                var formData = {
                    _token: '{{ csrf_token() }}',
                    footer_text: $('#footer_text').val(),
                    footer_link: $('#footer_link').val()
                };
                
                $.ajax({
                    url: '{{ route("admin.menusystem.update-footer-info", $menu->id) }}',
                    method: 'PUT',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            toastr.success('Menü açıklama bilgileri başarıyla güncellendi');
                        } else {
                            toastr.error(response.message || 'Menü açıklama bilgileri güncellenirken hata oluştu');
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            if (errors) {
                                $.each(errors, function(key, value) {
                                    toastr.error(value[0]);
                                });
                            }
                        } else {
                            toastr.error('Menü açıklama bilgileri güncellenirken hata oluştu');
                        }
                    }
                });
            });
        });
    });
</script>
@endsection 