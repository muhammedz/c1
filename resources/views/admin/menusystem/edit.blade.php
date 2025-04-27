@extends('adminlte::page')

@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
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
                    <h5 class="mb-0">Buton Alt Menü Öğeleri</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <button type="button" class="btn btn-primary" data-toggle="collapse" data-target="#newButtonForm">
                            <i class="fas fa-plus-circle mr-1"></i> Yeni Buton Ekle
                        </button>
                        
                        <div class="collapse mt-3" id="newButtonForm">
                            <div class="card card-body bg-light">
                                <form action="{{ url('/admin/menusystem/items/store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                                    <input type="hidden" name="item_type" value="2">
                                    
                                    <div class="form-group">
                                        <label>Başlık <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" required>
                                        @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>URL <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('url') is-invalid @enderror" name="url" required>
                                        @error('url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>İkon (FontAwesome)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-icons"></i></span>
                                            </div>
                                            <input type="text" class="form-control" name="icon" placeholder="fa-home, fa-user, fa-cog" value="fa-link">
                                        </div>
                                        <small class="form-text text-muted">FontAwesome ikon adı girin (ör: fa-home, fa-user)</small>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Buton Stili</label>
                                                <select class="form-control" name="button_style">
                                                    <option value="primary">Mavi (Primary)</option>
                                                    <option value="success">Yeşil (Success)</option>
                                                    <option value="danger">Kırmızı (Danger)</option>
                                                    <option value="warning">Sarı (Warning)</option>
                                                    <option value="info">Açık Mavi (Info)</option>
                                                    <option value="secondary">Gri (Secondary)</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Sıralama</label>
                                                <input type="number" class="form-control" name="order" value="0" min="0">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="custom-control custom-switch mb-3">
                                        <input type="checkbox" class="custom-control-input" id="newButtonStatus" name="status" value="1" checked>
                                        <label class="custom-control-label" for="newButtonStatus">Aktif</label>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-success">Kaydet</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Buton Kartları -->
                    <div class="row">
                        @if(isset($buttonItems) && count($buttonItems) > 0)
                            @foreach($buttonItems as $item)
                                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                                    <div class="card h-100 border-{{ $item->button_style ?? 'primary' }}">
                                        <div class="card-header bg-{{ $item->button_style ?? 'primary' }} text-white d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0">
                                                <i class="fas {{ $item->icon ?? 'fa-link' }} mr-2"></i>
                                                {{ $item->title }}
                                            </h5>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light" type="button" data-toggle="dropdown">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <button class="dropdown-item edit-button" type="button" data-toggle="collapse" data-target="#editForm{{ $item->id }}">
                                                        <i class="fas fa-edit mr-2"></i> Düzenle
                                                    </button>
                                                    <div class="dropdown-divider"></div>
                                                    <form action="{{ url('/admin/menusystem/items/'.$item->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Bu öğeyi silmek istediğinize emin misiniz?')">
                                                            <i class="fas fa-trash mr-2"></i> Sil
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <p><strong>URL:</strong> <a href="{{ $item->url }}" target="_blank">{{ $item->url }}</a></p>
                                            <p><strong>Sıra:</strong> {{ $item->order }}</p>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="statusSwitch{{ $item->id }}" 
                                                    {{ $item->status ? 'checked' : '' }} disabled>
                                                <label class="custom-control-label" for="statusSwitch{{ $item->id }}">
                                                    {{ $item->status ? 'Aktif' : 'Pasif' }}
                                                </label>
                                            </div>
                                            
                                            <div class="collapse mt-3" id="editForm{{ $item->id }}">
                                                <hr>
                                                <h6 class="mb-3">Düzenle</h6>
                                                <form action="{{ url('/admin/menusystem/items/'.$item->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                                                    <input type="hidden" name="item_type" value="2">
                                                    
                                                    <div class="form-group">
                                                        <label>Başlık <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ $item->title }}" required>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label>URL <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control @error('url') is-invalid @enderror" name="url" value="{{ $item->url }}" required>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label>İkon</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas {{ $item->icon ?? 'fa-link' }}"></i></span>
                                                            </div>
                                                            <input type="text" class="form-control" name="icon" value="{{ $item->icon }}">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Buton Stili</label>
                                                                <select class="form-control" name="button_style">
                                                                    <option value="primary" {{ $item->button_style == 'primary' ? 'selected' : '' }}>Mavi (Primary)</option>
                                                                    <option value="success" {{ $item->button_style == 'success' ? 'selected' : '' }}>Yeşil (Success)</option>
                                                                    <option value="danger" {{ $item->button_style == 'danger' ? 'selected' : '' }}>Kırmızı (Danger)</option>
                                                                    <option value="warning" {{ $item->button_style == 'warning' ? 'selected' : '' }}>Sarı (Warning)</option>
                                                                    <option value="info" {{ $item->button_style == 'info' ? 'selected' : '' }}>Açık Mavi (Info)</option>
                                                                    <option value="secondary" {{ $item->button_style == 'secondary' ? 'selected' : '' }}>Gri (Secondary)</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Sıralama</label>
                                                                <input type="number" class="form-control" name="order" value="{{ $item->order }}" min="0">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="custom-control custom-switch mb-3">
                                                        <input type="checkbox" class="custom-control-input" id="editStatusSwitch{{ $item->id }}" name="status" value="1" {{ $item->status ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="editStatusSwitch{{ $item->id }}">Aktif</label>
                                                    </div>
                                                    
                                                    <button type="submit" class="btn btn-primary btn-sm">Güncelle</button>
                                                    <button type="button" class="btn btn-secondary btn-sm" data-toggle="collapse" data-target="#editForm{{ $item->id }}">İptal</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-12">
                                <div class="alert alert-info">
                                    Henüz buton menü öğesi bulunmuyor. Yukarıdaki "Yeni Buton Ekle" butonunu kullanarak buton ekleyebilirsiniz.
                                </div>
                            </div>
                        @endif
                    </div>

                    @if($errors->any())
                    <div class="alert alert-danger mt-3">
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Hata!</h5>
                        <ul>
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
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
        console.log('Document ready - JavaScript yüklendi');
        
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
    });
</script>
@endsection 