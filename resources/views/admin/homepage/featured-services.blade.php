@extends('adminlte::page')

@section('title', 'Öne Çıkan Hizmetler')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Öne Çıkan Hizmetler</h1>
        <a href="{{ route('admin.homepage.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Ana Sayfa Yönetimine Dön
        </a>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Öne Çıkan Hizmetler Yönetimi</h3>
                    
                    <div class="card-tools">
                        <button type="button" class="btn btn-{{ $settings->is_visible ? 'success' : 'danger' }} btn-sm toggle-section-btn" 
                                data-url="{{ route('admin.homepage.toggle-featured-services-visibility') }}">
                            <i class="fas {{ $settings->is_visible ? 'fa-eye' : 'fa-eye-slash' }}"></i>
                            {{ $settings->is_visible ? 'Görünür' : 'Gizli' }}
                        </button>
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#settingsModal">
                            <i class="fas fa-cog"></i> Bölüm Ayarları
                        </button>
                        <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#addServiceModal">
                            <i class="fas fa-plus"></i> Yeni Hizmet Ekle
                        </button>
                    </div>
                </div>
                
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th style="width: 50px">#</th>
                                <th style="width: 80px">İkon</th>
                                <th>Başlık</th>
                                <th>URL</th>
                                <th style="width: 100px">Durum</th>
                                <th style="width: 150px">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody id="service-items">
                            @foreach($services as $service)
                                <tr data-id="{{ $service->id }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="text-center">
                                            {!! $service->icon_html !!}
                                        </div>
                                    </td>
                                    <td>{{ $service->title }}</td>
                                    <td>
                                        @if($service->url)
                                            <a href="{{ $service->url }}" target="_blank">{{ $service->url }}</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-{{ $service->is_active ? 'success' : 'danger' }} btn-sm toggle-visibility-btn" 
                                                data-url="{{ route('admin.homepage.toggle-featured-service-visibility', $service->id) }}">
                                            <i class="fas {{ $service->is_active ? 'fa-eye' : 'fa-eye-slash' }}"></i>
                                            {{ $service->is_active ? 'Aktif' : 'Pasif' }}
                                        </button>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-info btn-sm edit-service-btn" 
                                                data-id="{{ $service->id }}" 
                                                data-title="{{ $service->title }}"
                                                data-icon="{{ $service->icon }}" 
                                                data-url="{{ $service->url }}"
                                                data-svg-color="{{ $service->svg_color }}"
                                                data-svg-size="{{ $service->svg_size }}">
                                            <i class="fas fa-edit"></i> Düzenle
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm delete-service-btn" data-id="{{ $service->id }}">
                                            <i class="fas fa-trash"></i> Sil
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Ayarlar Modal -->
    <div class="modal fade" id="settingsModal" tabindex="-1" role="dialog" aria-labelledby="settingsModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="settingsModalLabel">Öne Çıkan Hizmetler Bölümü Ayarları</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.homepage.update-featured-service-settings') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="title">Bölüm Başlığı</label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ $settings->title ?? 'Öne Çıkan Hizmetler' }}">
                        </div>
                        <div class="form-group">
                            <label for="subtitle">Bölüm Alt Başlığı</label>
                            <input type="text" class="form-control" id="subtitle" name="subtitle" value="{{ $settings->subtitle ?? '' }}">
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_visible" name="is_visible" {{ $settings->is_visible ? 'checked' : '' }} value="1">
                                <label class="custom-control-label" for="is_visible">Bölüm Görünürlüğü</label>
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
    
    <!-- Yeni Hizmet Ekleme Modal -->
    <div class="modal fade" id="addServiceModal" tabindex="-1" role="dialog" aria-labelledby="addServiceModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addServiceModalLabel">Yeni Hizmet Ekle</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.homepage.store-featured-service') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="service_title">Hizmet Başlığı</label>
                            <input type="text" class="form-control" id="service_title" name="title" required>
                        </div>
                        
                        <div class="form-group">
                            <label>İkon</label>
                            <div class="d-flex">
                                <div class="icon-picker-wrapper w-100">
                                    <div class="input-group">
                                        <input type="text" class="form-control icon-picker" id="service_icon" name="icon" value="" required>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-secondary icon-picker-btn">
                                                <i class="fas fa-icons"></i> İkon Seç
                                            </button>
                                        </div>
                                    </div>
                                    <div class="icon-preview mt-2 text-center">
                                        <span></span>
                                    </div>
                                </div>
                            </div>
                            <small class="form-text text-muted">Font Awesome ikonu, SVG kodu veya resim dosyası (PNG, JPG) yükleyebilirsiniz.</small>
                        </div>
                        
                        <!-- SVG Ayarları -->
                        <div id="svg-settings" class="form-group" style="display: none;">
                            <label>SVG İkon Ayarları</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="service_svg_color">İkon Rengi</label>
                                    <div class="input-group">
                                        <input type="color" class="form-control" id="service_svg_color" name="svg_color" value="#004d2e" style="width: 60px;">
                                        <input type="text" class="form-control" id="service_svg_color_text" placeholder="#004d2e" maxlength="7" pattern="^#[0-9A-Fa-f]{6}$">
                                    </div>
                                    <small class="form-text text-muted">Renk seçici kullanın veya hex kod yazın (örn: #357a3b)</small>
                                </div>
                                <div class="col-md-6">
                                    <label for="service_svg_size">İkon Boyutu (px)</label>
                                    <input type="number" class="form-control" id="service_svg_size" name="svg_size" value="48" min="16" max="128">
                                </div>
                            </div>
                            <small class="form-text text-muted">Bu ayarlar sadece SVG ikonları için geçerlidir.</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="service_url">URL (İsteğe Bağlı)</label>
                            <input type="text" class="form-control" id="service_url" name="url">
                            <small class="form-text text-muted">Eğer ikona tıklandığında yönlendirilecek bir sayfa varsa URL'ini ekleyin.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">İptal</button>
                        <button type="submit" class="btn btn-success">Ekle</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Hizmet Düzenleme Modal -->
    <div class="modal fade" id="editServiceModal" tabindex="-1" role="dialog" aria-labelledby="editServiceModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editServiceModalLabel">Hizmet Düzenle</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editServiceForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit_service_title">Hizmet Başlığı</label>
                            <input type="text" class="form-control" id="edit_service_title" name="title" required>
                        </div>
                        
                        <div class="form-group">
                            <label>İkon</label>
                            <div class="d-flex">
                                <div class="icon-picker-wrapper w-100">
                                    <div class="input-group">
                                        <input type="text" class="form-control icon-picker" id="edit_service_icon" name="icon" value="" required>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-secondary icon-picker-btn">
                                                <i class="fas fa-icons"></i> İkon Seç
                                            </button>
                                        </div>
                                    </div>
                                    <div class="icon-preview mt-2 text-center">
                                        <span></span>
                                    </div>
                                </div>
                            </div>
                            <small class="form-text text-muted">Font Awesome ikonu, SVG kodu veya resim dosyası (PNG, JPG) yükleyebilirsiniz.</small>
                        </div>
                        
                        <!-- SVG Ayarları -->
                        <div id="edit-svg-settings" class="form-group" style="display: none;">
                            <label>SVG İkon Ayarları</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="edit_service_svg_color">İkon Rengi</label>
                                    <div class="input-group">
                                        <input type="color" class="form-control" id="edit_service_svg_color" name="svg_color" value="#004d2e" style="width: 60px;">
                                        <input type="text" class="form-control" id="edit_service_svg_color_text" placeholder="#004d2e" maxlength="7" pattern="^#[0-9A-Fa-f]{6}$">
                                    </div>
                                    <small class="form-text text-muted">Renk seçici kullanın veya hex kod yazın (örn: #357a3b)</small>
                                </div>
                                <div class="col-md-6">
                                    <label for="edit_service_svg_size">İkon Boyutu (px)</label>
                                    <input type="number" class="form-control" id="edit_service_svg_size" name="svg_size" value="48" min="16" max="128">
                                </div>
                            </div>
                            <small class="form-text text-muted">Bu ayarlar sadece SVG ikonları için geçerlidir.</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_service_url">URL (İsteğe Bağlı)</label>
                            <input type="text" class="form-control" id="edit_service_url" name="url">
                            <small class="form-text text-muted">Eğer ikona tıklandığında yönlendirilecek bir sayfa varsa URL'ini ekleyin.</small>
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
    
    <!-- Silme Onay Modal -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmModalLabel">Silmeyi Onayla</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Bu hizmeti silmek istediğinizden emin misiniz?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">İptal</button>
                    <form id="deleteServiceForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Evet, Sil</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- İkon Seçici Modal -->
    <div class="modal fade" id="iconPickerModal" tabindex="-1" role="dialog" aria-labelledby="iconPickerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="iconPickerModalLabel">İkon Seçin</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <input type="text" id="iconSearch" class="form-control" placeholder="İkon Ara...">
                        </div>
                        <div class="col-md-6">
                            <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                                <label class="btn btn-outline-primary active">
                                    <input type="radio" name="iconType" value="fas" checked> Solid
                                </label>
                                <label class="btn btn-outline-primary">
                                    <input type="radio" name="iconType" value="far"> Regular
                                </label>
                                <label class="btn btn-outline-primary">
                                    <input type="radio" name="iconType" value="fab"> Brands
                                </label>
                            </div>
                        </div>
                    </div>
                    <div id="iconsGrid" class="row overflow-auto" style="max-height: 300px;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">İptal</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script src="{{ asset('js/icon-picker.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
    <script>
        $(function () {
            // İkon seçici başlatma
            initIconPicker();
            
            // Draggable table rows
            var sortable = new Sortable(document.getElementById('service-items'), {
                handle: 'td:first-child',
                animation: 150,
                onEnd: function() {
                    // Sıralamayı güncelle
                    updateOrder();
                }
            });
            
            // Bölüm görünürlüğünü değiştirme
            $('.toggle-section-btn').click(function() {
                var btn = $(this);
                var url = btn.data('url');
                
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        // Buton ikonunu ve rengini değiştir
                        btn.toggleClass('btn-success btn-danger');
                        btn.find('i').toggleClass('fa-eye fa-eye-slash');
                        
                        if(btn.hasClass('btn-success')) {
                            btn.text(' Görünür');
                            btn.prepend('<i class="fas fa-eye"></i>');
                        } else {
                            btn.text(' Gizli');
                            btn.prepend('<i class="fas fa-eye-slash"></i>');
                        }
                        
                        // Mesaj göster
                        toastr.success('Görünürlük durumu güncellendi.');
                    }
                });
            });
            
            // Hizmet görünürlüğünü değiştirme
            $('.toggle-visibility-btn').click(function() {
                var btn = $(this);
                var url = btn.data('url');
                
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        // Buton ikonunu ve rengini değiştir
                        btn.toggleClass('btn-success btn-danger');
                        btn.find('i').toggleClass('fa-eye fa-eye-slash');
                        
                        if(btn.hasClass('btn-success')) {
                            btn.text(' Aktif');
                            btn.prepend('<i class="fas fa-eye"></i>');
                        } else {
                            btn.text(' Pasif');
                            btn.prepend('<i class="fas fa-eye-slash"></i>');
                        }
                        
                        // Mesaj göster
                        toastr.success('Hizmet durumu güncellendi.');
                    }
                });
            });
            
            // Düzenleme modalını açma
            $('.edit-service-btn').click(function() {
                var id = $(this).data('id');
                var title = $(this).data('title');
                var icon = $(this).data('icon');
                var url = $(this).data('url');
                var svgColor = $(this).data('svg-color') || '#004d2e';
                var svgSize = $(this).data('svg-size') || 48;
                
                $('#edit_service_title').val(title);
                $('#edit_service_icon').val(icon);
                $('#edit_service_url').val(url);
                $('#edit_service_svg_color').val(svgColor);
                $('#edit_service_svg_color_text').val(svgColor);
                $('#edit_service_svg_size').val(svgSize);
                
                // SVG ayarlarını göster/gizle
                var isSvg = icon.startsWith('<svg');
                $('#edit-svg-settings').toggle(isSvg);
                
                // İkon önizleme
                $('#editServiceModal .icon-preview span').html(isSvg ? icon : '<i class="' + icon + ' fa-2x"></i>');
                
                // Form action URL'sini güncelle
                $('#editServiceForm').attr('action', '{{ url("admin/homepage/featured-services") }}/' + id);
                
                // Modalı göster
                $('#editServiceModal').modal('show');
            });
            
            // Silme modalını açma
            $('.delete-service-btn').click(function() {
                var id = $(this).data('id');
                
                // Form action URL'sini güncelle
                $('#deleteServiceForm').attr('action', '{{ url("admin/homepage/featured-services") }}/' + id);
                
                // Modalı göster
                $('#deleteConfirmModal').modal('show');
            });
            
            // Sıralama güncelleme
            function updateOrder() {
                var items = [];
                
                $('#service-items tr').each(function(index) {
                    items.push({
                        id: $(this).data('id'),
                        order: index + 1
                    });
                });
                
                $.ajax({
                    url: '{{ route("admin.homepage.update-featured-services-order") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        items: items
                    },
                    success: function(response) {
                        // Mesaj göster
                        toastr.success('Sıralama güncellendi.');
                    }
                });
            }
            
            // İkon değiştiğinde ön izleme göster
            $('.icon-picker').on('change', function() {
                var iconValue = $(this).val();
                var previewElement = $(this).closest('.icon-picker-wrapper').find('.icon-preview span');
                var isSvg = iconValue.startsWith('<svg');
                
                // SVG ayarlarını göster/gizle
                var modalId = $(this).closest('.modal').attr('id');
                if (modalId === 'addServiceModal') {
                    $('#svg-settings').toggle(isSvg);
                } else if (modalId === 'editServiceModal') {
                    $('#edit-svg-settings').toggle(isSvg);
                }
                
                if (isSvg) {
                    // SVG içeriği
                    previewElement.html(iconValue);
                } else {
                    // Font Awesome ikonu - eski "fas fa-XXX" formatından veya tam "fas fa-XXX" formatından
                    if (iconValue.includes('fa-')) {
                        // Zaten tam sınıf adı var
                        previewElement.html('<i class="' + iconValue + ' fa-2x"></i>');
                    } else {
                        // Eski format (sadece ikon adı)
                        previewElement.html('<i class="fas fa-' + iconValue + ' fa-2x"></i>');
                        // Eski formatı yeni formata güncelle
                        $(this).val('fas fa-' + iconValue);
                        console.log('Eski format ikon güncellendi:', iconValue, '->', 'fas fa-' + iconValue);
                    }
                }
            });
            
            // Sayfa yüklendiğinde mevcut ikonları göster
            $('.icon-picker').each(function() {
                var iconValue = $(this).val();
                var previewElement = $(this).closest('.icon-picker-wrapper').find('.icon-preview span');
                
                if (iconValue) {
                    if (iconValue.startsWith('<svg')) {
                        // SVG içeriği
                        previewElement.html(iconValue);
                    } else {
                        // Font Awesome ikonu - eski "fas fa-XXX" formatından veya tam "fas fa-XXX" formatından
                        if (iconValue.includes('fa-')) {
                            // Zaten tam sınıf adı var
                            previewElement.html('<i class="' + iconValue + ' fa-2x"></i>');
                        } else {
                            // Eski format (sadece ikon adı)
                            previewElement.html('<i class="fas fa-' + iconValue + ' fa-2x"></i>');
                            // Eski formatı yeni formata güncelle
                            $(this).val('fas fa-' + iconValue);
                            console.log('Eski format ikon güncellendi:', iconValue, '->', 'fas fa-' + iconValue);
                        }
                    }
                }
            });
            
            // Hex kod ve renk seçici senkronizasyonu
            function syncColorInputs(colorInput, textInput) {
                colorInput.on('input', function() {
                    textInput.val($(this).val());
                    updateSvgPreview($(this));
                });
                
                textInput.on('input', function() {
                    var hexValue = $(this).val();
                    if (/^#[0-9A-Fa-f]{6}$/.test(hexValue)) {
                        colorInput.val(hexValue);
                        updateSvgPreview($(this));
                    }
                });
            }
            
            // Renk inputları senkronizasyonu
            syncColorInputs($('#service_svg_color'), $('#service_svg_color_text'));
            syncColorInputs($('#edit_service_svg_color'), $('#edit_service_svg_color_text'));
            
            // SVG önizleme güncelleme fonksiyonu
            function updateSvgPreview(triggerElement) {
                var modalId = triggerElement.closest('.modal').attr('id');
                var iconInput, colorInput, sizeInput, previewElement;
                
                if (modalId === 'addServiceModal') {
                    iconInput = $('#service_icon');
                    colorInput = $('#service_svg_color');
                    sizeInput = $('#service_svg_size');
                    previewElement = $('#addServiceModal .icon-preview span');
                } else if (modalId === 'editServiceModal') {
                    iconInput = $('#edit_service_icon');
                    colorInput = $('#edit_service_svg_color');
                    sizeInput = $('#edit_service_svg_size');
                    previewElement = $('#editServiceModal .icon-preview span');
                }
                
                var iconValue = iconInput.val();
                if (iconValue.startsWith('<svg')) {
                    var color = colorInput.val();
                    var size = sizeInput.val();
                    
                    // SVG içeriğini güncelle
                    var svgContent = iconValue;
                    
                    // Mevcut style'ı kaldır
                    svgContent = svgContent.replace(/style="[^"]*"/g, '');
                    
                    // Yeni style ekle
                    var style = 'width: ' + size + 'px; height: ' + size + 'px; color: ' + color + '; fill: ' + color + ';';
                    svgContent = svgContent.replace('<svg', '<svg style="' + style + '"');
                    
                    previewElement.html(svgContent);
                }
            }
            
            // SVG renk ve boyut değişikliklerini canlı önizleme
            $('#service_svg_color, #service_svg_size, #edit_service_svg_color, #edit_service_svg_size').on('input', function() {
                updateSvgPreview($(this));
            });
        });
    </script>
@stop

@section('css')
    <style>
        .icon-preview {
            padding: 10px;
            border-radius: 4px;
            background-color: #f8f9fa;
            min-height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .icon-preview img {
            max-width: 48px;
            max-height: 48px;
            object-fit: contain;
        }
        
        .icon-preview svg {
            max-width: 48px;
            max-height: 48px;
        }
        
        .icon-item {
            padding: 10px;
            text-align: center;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .icon-item:hover {
            background-color: #eee;
            transform: scale(1.1);
        }
        
        #service-items tr td:first-child {
            cursor: move;
        }
        
        .sortable-ghost {
            opacity: 0.5;
            background: #f0f0f0;
        }
        
        /* Admin panelde SVG ikonlarını küçült - güçlü CSS */
        #service-items td {
            width: 80px;
            text-align: center;
        }
        
        #service-items td svg,
        #service-items td svg * {
            max-width: 24px !important;
            max-height: 24px !important;
            width: 24px !important;
            height: 24px !important;
        }
        
        #service-items td .featured-service-icon,
        #service-items td i {
            width: 24px !important;
            height: 24px !important;
            font-size: 24px !important;
        }
        
        /* SVG'lerin CSS stillerini ezmek için */
        #service-items td [class*="svg-icon"] {
            width: 24px !important;
            height: 24px !important;
        }
        
        #service-items td [class*="svg-icon"] svg {
            width: 24px !important;
            height: 24px !important;
        }
    </style>
@stop 