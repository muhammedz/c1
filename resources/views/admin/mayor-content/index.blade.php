@extends('adminlte::page')

@section('title', 'Başkan İçerikleri')

@section('adminlte_css_pre')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    /* Pagination Stilleri */
    .pagination {
        gap: 5px;
        margin: 0;
    }
    
    .pagination .page-item .page-link {
        border: 1px solid #dee2e6;
        color: #495057;
        font-size: 0.875rem;
        padding: 8px 12px;
        border-radius: 6px;
        transition: all 0.2s ease;
        background-color: #fff;
        margin: 0 2px;
    }
    
    .pagination .page-item .page-link:hover {
        background-color: #e9ecef;
        border-color: #adb5bd;
        color: #495057;
    }
    
    .pagination .page-item.active .page-link {
        background-color: #007bff;
        border-color: #007bff;
        color: #fff;
        font-weight: 500;
    }
    
    .pagination .page-item.disabled .page-link {
        color: #6c757d;
        background-color: #fff;
        border-color: #dee2e6;
        cursor: not-allowed;
    }
    
    /* Pagination container */
    .pagination-wrapper {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        border-top: 1px solid #dee2e6;
        margin-top: 20px;
    }
    
    /* Gallery kartları için stiller */
    .gallery-item-card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        overflow: hidden;
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .gallery-item-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    
    .gallery-image-container {
        position: relative;
        height: 200px;
        overflow: hidden;
    }
    
    .gallery-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .delete-image-btn {
        position: absolute;
        top: 8px;
        right: 8px;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(220, 53, 69, 0.9);
        border: none;
        color: white;
        font-size: 12px;
        transition: all 0.2s ease;
    }
    
    .delete-image-btn:hover {
        background: #dc3545;
        transform: scale(1.1);
    }
    
    .gallery-info {
        padding: 15px;
    }
    
    .gallery-title {
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 8px;
        color: #333;
    }
    
    .gallery-description {
        font-size: 13px;
        color: #666;
        margin-bottom: 8px;
    }
</style>
@endsection

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        @switch($type)
                            @case('story')
                                Hikayeler
                                @break
                            @case('agenda')
                                Gündem
                                @break

                            @case('gallery')
                                Galeri
                                @break
                            @default
                                İçerikler
                        @endswitch
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Ana Sayfa</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.mayor.index') }}">Başkan</a></li>
                        <li class="breadcrumb-item active">
                            @switch($type)
                                @case('story')
                                    Hikayeler
                                    @break
                                @case('agenda')
                                    Gündem
                                    @break

                                @case('gallery')
                                    Galeri
                                    @break
                                @default
                                    İçerikler
                            @endswitch
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{ session('error') }}
                </div>
            @endif

            <!-- İçerik Türü Seçimi -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">İçerik Türü</h3>
                </div>
                <div class="card-body">
                    <div class="btn-group" role="group">
                        <a href="{{ route('admin.mayor-content.index', ['type' => 'story']) }}" 
                           class="btn {{ $type == 'story' ? 'btn-warning' : 'btn-outline-warning' }}">
                            <i class="fas fa-book mr-1"></i>
                            Hikayeler
                        </a>
                        <a href="{{ route('admin.mayor-content.index', ['type' => 'agenda']) }}" 
                           class="btn {{ $type == 'agenda' ? 'btn-info' : 'btn-outline-info' }}">
                            <i class="fas fa-calendar mr-1"></i>
                            Gündem
                        </a>

                        <a href="{{ route('admin.mayor-content.index', ['type' => 'gallery']) }}" 
                           class="btn {{ $type == 'gallery' ? 'btn-purple' : 'btn-outline-purple' }}">
                            <i class="fas fa-images mr-1"></i>
                            Galeri
                        </a>
                    </div>
                </div>
            </div>

            @if($type == 'gallery')
            <!-- Toplu Fotoğraf Yükleme -->
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-cloud-upload-alt mr-2"></i>
                        Toplu Fotoğraf Yükleme
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form id="bulk-upload-form" action="{{ route('admin.mayor-content.bulk-upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="type" value="gallery">
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label class="font-weight-bold">
                                        <i class="fas fa-images mr-1"></i>
                                        Fotoğrafları Seçin
                                    </label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="bulk_images_display" readonly placeholder="FileManagerSystem'den fotoğraf seçin...">
                                        <button type="button" class="btn btn-primary" id="bulk_filemanager_button">
                                            <i class="fas fa-images mr-1"></i>
                                            Fotoğraf Seç
                                        </button>
                                    </div>
                                    <input type="hidden" id="selected_bulk_images" name="selected_images" value="">
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        FileManagerSystem'den birden fazla fotoğraf seçebilirsiniz
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">&nbsp;</label>
                                    <div>
                                        <button type="button" id="bulk_save_button" class="btn btn-info btn-lg btn-block" disabled>
                                            <i class="fas fa-save mr-2"></i>
                                            Seçilen Fotoğrafları Kaydet
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div id="bulk-image-preview" class="mt-3" style="display: none;">
                            <div class="alert alert-info">
                                <h5 class="alert-heading">
                                    <i class="fas fa-eye mr-2"></i>
                                    Seçilen Fotoğraflar:
                                    <span class="badge badge-primary ml-2" id="selected-count">0</span>
                                </h5>
                                <div id="bulk-preview-container" class="row mt-3"></div>
                                <div class="mt-3">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" id="clear-bulk-selection">
                                        <i class="fas fa-trash mr-1"></i>
                                        Seçimi Temizle
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @endif

            @if($type == 'gallery')
            <!-- Foto Galerisi -->
            @if($contents->count() > 0)
            <div class="card card-outline card-purple">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-images mr-2"></i>
                        Foto Galerisi
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-purple">{{ $contents->total() }} fotoğraf</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($contents as $content)
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-4">
                            <div class="gallery-item-card">
                                <div class="gallery-image-container">
                                    <img src="{{ $content->image_url ?: asset('images/mayor/gallery-default.jpg') }}?v={{ time() }}" 
                                         alt="{{ $content->filemanagersystem_image_alt ?: $content->title }}" 
                                         title="{{ $content->filemanagersystem_image_title ?: $content->title }}"
                                         class="gallery-image"
                                         data-content-id="{{ $content->id }}"
                                         data-debug-url="{{ $content->image_url }}">
                                    
                                    <!-- Basit silme butonu -->
                                    <button type="button" 
                                            class="btn btn-danger btn-sm delete-image-btn" 
                                            data-id="{{ $content->id }}"
                                            title="Sil">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                
                                <div class="gallery-info">
                                    <h6 class="gallery-title">{{ $content->title }}</h6>
                                    @if($content->description)
                                    <p class="gallery-description">{{ Str::limit($content->description, 50) }}</p>
                                    @endif
                                    <small class="text-muted">
                                        <i class="fas fa-calendar mr-1"></i>
                                        {{ $content->created_at->format('d.m.Y') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Gallery Pagination -->
                    @if($contents->hasPages())
                    <div class="pagination-wrapper">
                        <div class="d-flex justify-content-center">
                            {{ $contents->appends(['type' => $type])->links() }}
                        </div>
                    </div>
                    @endif

                </div>
            </div>
            @else
            <!-- Boş galeri durumu -->
            <div class="card card-outline card-purple">
                <div class="card-body text-center py-5">
                    <i class="fas fa-images fa-4x text-muted mb-4"></i>
                    <h4 class="text-muted">Henüz fotoğraf eklenmemiş</h4>
                    <p class="text-muted mb-4">
                        Galeri boş görünüyor. İlk fotoğraflarınızı eklemek için yukarıdaki toplu yükleme özelliğini kullanabilirsiniz.
                    </p>
                    <a href="{{ route('admin.mayor-content.create', ['type' => 'gallery']) }}" class="btn btn-purple">
                        <i class="fas fa-plus mr-2"></i>
                        İlk Fotoğrafı Ekle
                    </a>
                </div>
            </div>
            @endif
            @else
            <!-- Diğer İçerik Türleri için Liste -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        @switch($type)
                            @case('story')
                                <i class="fas fa-book mr-2"></i>
                                Hikayeler Listesi
                                @break
                            @case('agenda')
                                <i class="fas fa-calendar mr-2"></i>
                                Gündem Listesi
                                @break

                        @endswitch
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.mayor-content.create', ['type' => $type]) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus mr-1"></i>
                            Yeni Ekle
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($contents->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">#</th>
                                        <th>Başlık</th>
                                        <th style="width: 200px">Açıklama</th>
                                        <th style="width: 80px">Sıra</th>
                                        <th style="width: 80px">Durum</th>
                                        <th style="width: 150px">İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($contents as $content)
                                        <tr>
                                            <td>{{ $content->id }}</td>
                                            <td>{{ $content->title }}</td>
                                            <td>
                                                @if($content->description)
                                                    {{ Str::limit($content->description, 100) }}
                                                @else
                                                    <span class="text-muted">Açıklama yok</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge badge-secondary">{{ $content->sort_order }}</span>
                                            </td>
                                            <td>
                                                @if($content->is_active)
                                                    <span class="badge badge-success">Aktif</span>
                                                @else
                                                    <span class="badge badge-danger">Pasif</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.mayor-content.edit', $content) }}" 
                                                       class="btn btn-sm btn-warning" title="Düzenle">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-sm {{ $content->is_active ? 'btn-secondary' : 'btn-success' }} toggle-status" 
                                                            data-id="{{ $content->id }}" 
                                                            title="{{ $content->is_active ? 'Pasif Yap' : 'Aktif Yap' }}">
                                                        <i class="fas {{ $content->is_active ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                                    </button>
                                                    <form action="{{ route('admin.mayor-content.destroy', $content) }}" 
                                                          method="POST" 
                                                          style="display: inline-block;"
                                                          onsubmit="return confirm('Bu içeriği silmek istediğinizden emin misiniz?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Sil">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $contents->appends(['type' => $type])->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Henüz içerik eklenmemiş</h5>
                            <p class="text-muted">
                                @switch($type)
                                    @case('story')
                                        İlk hikayenizi eklemek için "Yeni Ekle" butonuna tıklayın.
                                        @break
                                    @case('agenda')
                                        İlk gündem maddenizi eklemek için "Yeni Ekle" butonuna tıklayın.
                                        @break
                                @endswitch
                            </p>
                            <a href="{{ route('admin.mayor-content.create', ['type' => $type]) }}" class="btn btn-primary">
                                <i class="fas fa-plus mr-1"></i>
                                İlk İçeriği Ekle
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            @endif

        </div>
    </section>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- FileManagerSystem Modal -->
<div class="modal fade" id="bulkMediapickerModal" tabindex="-1" role="dialog" aria-labelledby="bulkMediapickerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulkMediapickerModalLabel">
                    <i class="fas fa-images me-2"></i>
                    Fotoğrafları Seç
                    <span class="badge badge-primary ml-2" id="selected-media-count">0</span>
                </h5>
                <div class="ml-auto">
                    <button type="button" class="btn btn-success btn-sm mr-2" id="finish-selection-btn" style="display: none;">
                        <i class="fas fa-check mr-1"></i>
                        Seçimi Bitir
                    </button>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <div class="modal-body p-0">
                <iframe id="bulkMediapickerFrame" src="" style="width: 100%; height: 600px; border: none;"></iframe>
            </div>
        </div>
    </div>
</div>

<!-- Delete Script -->
<script>
console.log('Inline script loaded!');

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded!');
    
    // jQuery kontrolü
    if (typeof $ !== 'undefined') {
        console.log('jQuery available:', $.fn.jquery);
        
        $(document).ready(function() {
            console.log('jQuery ready!');
            
            var deleteButtons = $('.delete-image-btn');
            console.log('Delete buttons found:', deleteButtons.length);
            
            if (deleteButtons.length > 0) {
                console.log('First button:', deleteButtons.first()[0]);
                console.log('First button ID:', deleteButtons.first().data('id'));
            }
            
            // Click event with SweetAlert
            $(document).on('click', '.delete-image-btn', function(e) {
                e.preventDefault();
                
                var button = $(this);
                var contentId = button.data('id');
                
                console.log('Delete button clicked! ID:', contentId);
                
                Swal.fire({
                    title: 'Emin misiniz?',
                    text: "Bu resmi silmek istediğinizden emin misiniz?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Evet, sil!',
                    cancelButtonText: 'İptal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        console.log('User confirmed deletion');
                        
                        // Loading göster
                        Swal.fire({
                            title: 'Siliniyor...',
                            text: 'Lütfen bekleyin',
                            icon: 'info',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading()
                            }
                        });
                        
                        // Button'u disable et
                        button.prop('disabled', true);
                        button.html('<i class="fas fa-spinner fa-spin"></i>');
                        
                        $.ajax({
                            url: '/admin/mayor-content/' + contentId,
                            type: 'DELETE',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                console.log('Delete success:', response);
                                
                                Swal.fire({
                                    title: 'Başarılı!',
                                    text: 'Resim başarıyla silindi.',
                                    icon: 'success',
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            },
                            error: function(xhr, status, error) {
                                console.error('Delete error:', xhr.responseText);
                                
                                Swal.fire({
                                    title: 'Hata!',
                                    text: 'Silme işlemi başarısız: ' + error,
                                    icon: 'error',
                                    confirmButtonText: 'Tamam'
                                });
                                
                                // Button'u eski haline getir
                                button.prop('disabled', false);
                                button.html('<i class="fas fa-times"></i>');
                            }
                        });
                    } else {
                        console.log('User cancelled deletion');
                    }
                });
            });

            // =========================
            // FILEMANAGERSYSTEM - BULK UPLOAD
            // =========================
            
            // Sayfa türünü kontrol et
            const urlParams = new URLSearchParams(window.location.search);
            const pageType = urlParams.get('type');
            console.log('Page type:', pageType);
            
            if (pageType === 'gallery') {
                console.log('Gallery sayfasında - FileManagerSystem script yükleniyor...');
                
                // Debug: Sayfa içeriğini konsola logla
                console.log('=== GALLERY DEBUG INFO ===');
                const galleryImages = document.querySelectorAll('.gallery-image');
                console.log('Gallery images found:', galleryImages.length);
                galleryImages.forEach((img, index) => {
                    console.log(`Image ${index + 1}:`, {
                        contentId: img.dataset.contentId,
                        src: img.src,
                        debugUrl: img.dataset.debugUrl,
                        alt: img.alt
                    });
                });
                
                // FileManagerSystem - Toplu fotoğraf seçimi
                let selectedBulkImages = [];

                // Button kontrolü
                const $bulkButton = $('#bulk_filemanager_button');
                console.log('Bulk filemanager button count:', $bulkButton.length);
                
                if ($bulkButton.length === 0) {
                    console.error('❌ BULK_FILEMANAGER_BUTTON BULUNAMADI!');
                } else {
                    console.log('✅ Bulk FileManager button bulundu!', $bulkButton[0]);
                    
                    // jQuery click event
                    $bulkButton.on('click', function(e) {
                        e.preventDefault();
                        console.log('🎯 Bulk filemanager button clicked!');
                        
                        // Modal'ı aç
                        $('#bulkMediapickerModal').modal('show');
                        console.log('Modal show komutu verildi');
                        
                        // URL'i sadece modal açıldıktan sonra yükleyelim
                        setTimeout(function() {
                            const tempId = Date.now();
                            const relatedType = 'mayor_content_bulk';
                            const mediapickerUrl = '/admin/filemanagersystem/mediapicker?type=image&filter=all&related_type=' + relatedType + '&related_id=' + tempId + '&multiple=true';
                            
                            console.log('Loading URL:', mediapickerUrl);
                            $('#bulkMediapickerFrame').attr('src', mediapickerUrl);
                        }, 500);
                    });
                }

                // FileManagerSystem'den gelen mesajları dinle
                window.addEventListener('message', function(event) {
                    console.log('FileManager mesaj alındı:', event.data);
                    console.log('Mesaj tipi:', event.data?.type);
                    
                    if (event.data && typeof event.data === 'object') {
                        // Çoklu seçim
                        if (event.data.type === 'multiple-media-selected' && event.data.mediaList) {
                            selectedBulkImages = event.data.mediaList;
                            console.log('Çoklu medya seçildi:', selectedBulkImages);
                            
                            updateBulkImageDisplay();
                            $('#bulkMediapickerModal').modal('hide');
                            
                        } 
                        // Tek medya seçimi - eski format
                        else if (event.data.type === 'media-selected' && event.data.media) {
                            const media = event.data.media;
                            selectedBulkImages = [media];
                            console.log('Tek medya seçildi (eski format):', media);
                            
                            updateBulkImageDisplay();
                            $('#bulkMediapickerModal').modal('hide');
                            
                        }
                        // Tek medya seçimi - yeni format (mediaSelected)
                        else if (event.data.type === 'mediaSelected') {
                            console.log('MediaSelected mesajı yakalandı!');
                            
                            // URL'i düzelt - yanlış domain'i kaldır
                            let mediaUrl = event.data.mediaUrl;
                            if (mediaUrl && mediaUrl.includes('cankaya.epoxsoft.net.tr')) {
                                // Yanlış domain'i kaldır ve mevcut site domain'i ile değiştir
                                mediaUrl = mediaUrl.replace('https://cankaya.epoxsoft.net.tr', window.location.origin);
                                console.log('URL düzeltildi:', event.data.mediaUrl, '->', mediaUrl);
                            }
                            
                            // Medya objesini oluştur
                            const media = {
                                id: event.data.mediaId,
                                url: mediaUrl,
                                title: event.data.mediaTitle || event.data.mediaAlt || '',
                                alt: event.data.mediaAlt || '',
                                path: event.data.mediaPath || ''
                            };
                            
                            // Eğer aynı medya zaten seçilmişse ekleme
                            const existingIndex = selectedBulkImages.findIndex(img => img.id === media.id);
                            if (existingIndex === -1) {
                                selectedBulkImages.push(media);
                                console.log('Yeni medya eklendi:', media);
                                console.log('Toplam seçili medya:', selectedBulkImages.length);
                                
                                // Modal header'daki sayacı güncelle
                                $('#selected-media-count').text(selectedBulkImages.length);
                                $('#finish-selection-btn').show();
                                
                                updateBulkImageDisplay();
                            } else {
                                console.log('Bu medya zaten seçili, tekrar eklenmedi');
                            }
                            
                            // Modal'ı kapatma - kullanıcı birden fazla seçim yapabilsin
                            // $('#bulkMediapickerModal').modal('hide');
                            
                        }
                        // Modal kapama
                        else if (event.data.type === 'close-modal') {
                            $('#bulkMediapickerModal').modal('hide');
                        }
                        // Bitti mesajı - modal'ı kapat
                        else if (event.data.type === 'selection-finished' || event.data.type === 'done') {
                            $('#bulkMediapickerModal').modal('hide');
                        }
                    }
                });

                function updateBulkImageDisplay() {
                    const count = selectedBulkImages.length;
                    
                    if (count > 0) {
                        $('#bulk_images_display').val(count + ' fotoğraf seçildi');
                        $('#selected_bulk_images').val(JSON.stringify(selectedBulkImages));
                        $('#bulk_save_button').prop('disabled', false);
                        $('#bulk-image-preview').show();
                        $('#selected-count').text(count);
                        
                        // Önizleme container'ını güncelle
                        const container = $('#bulk-preview-container');
                        container.empty();
                        
                        selectedBulkImages.forEach(function(media, index) {
                            const col = $('<div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-6 mb-3"></div>');
                            const imageCard = $(`
                                <div class="position-relative">
                                    <img src="${media.url}" class="img-thumbnail" style="width: 100%; height: 100px; object-fit: cover;">
                                    <button type="button" class="btn btn-danger btn-sm position-absolute" 
                                            style="top: 2px; right: 2px; width: 25px; height: 25px; padding: 0; border-radius: 50%;"
                                            onclick="removeBulkImage(${index})">
                                        <i class="fas fa-times" style="font-size: 10px;"></i>
                                    </button>
                                    <div class="text-center mt-1">
                                        <small class="text-muted">${media.title || 'Fotoğraf ' + (index + 1)}</small>
                                    </div>
                                </div>
                            `);
                            col.append(imageCard);
                            container.append(col);
                        });
                    } else {
                        $('#bulk_images_display').val('');
                        $('#selected_bulk_images').val('');
                        $('#bulk_save_button').prop('disabled', true);
                        $('#bulk-image-preview').hide();
                    }
                }

                // Global function for removing images
                window.removeBulkImage = function(index) {
                    selectedBulkImages.splice(index, 1);
                    updateBulkImageDisplay();
                };

                // Seçimi temizle
                $('#clear-bulk-selection').on('click', function() {
                    selectedBulkImages = [];
                    updateBulkImageDisplay();
                });

                // Toplu kaydetme
                $('#bulk_save_button').on('click', function() {
                    if (selectedBulkImages.length === 0) {
                        alert('Lütfen en az bir fotoğraf seçin.');
                        return;
                    }

                    const button = $(this);
                    button.prop('disabled', true);
                    button.html('<i class="fas fa-spinner fa-spin mr-2"></i> Kaydediliyor...');

                    // Seçilen medyaları JSON string olarak hazırla
                    const imageDataArray = selectedBulkImages.map(media => JSON.stringify(media));

                    $.ajax({
                        url: '{{ route("admin.mayor-content.bulk-save-filemanager") }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            selected_images: imageDataArray
                        },
                        success: function(response) {
                            console.log('Bulk save success:', response);
                            alert(response.message);
                            location.reload(); // Sayfayı yenile
                        },
                        error: function(xhr, status, error) {
                            console.error('Bulk save error:', xhr.responseText);
                            alert('Kaydetme işlemi başarısız oldu: ' + (xhr.responseJSON?.message || error));
                            
                            // Button'u eski haline getir
                            button.prop('disabled', false);
                            button.html('<i class="fas fa-save mr-2"></i> Seçilen Fotoğrafları Kaydet');
                        }
                    });
                });

                // Modal kapandığında iframe'i temizle
                $('#bulkMediapickerModal').on('hidden.bs.modal', function () {
                    $('#bulkMediapickerFrame').attr('src', '');
                    $('#selected-media-count').text('0');
                    $('#finish-selection-btn').hide();
                });

                // Seçimi bitir butonu
                $('#finish-selection-btn').on('click', function() {
                    $('#bulkMediapickerModal').modal('hide');
                });
                
                console.log('FileManagerSystem script yüklendi!');
                
            } else {
                console.log('Gallery sayfası değil, FileManagerSystem script yüklenmedi');
            }
        });
    } else {
        console.log('jQuery not available');
        alert('jQuery yüklenmemiş!');
    }
});
</script>

@push('css')
<style>
.btn-purple {
    color: #fff;
    background-color: #6f42c1;
    border-color: #6f42c1;
}
.btn-purple:hover {
    color: #fff;
    background-color: #5a32a3;
    border-color: #5a32a3;
}
.btn-outline-purple {
    color: #6f42c1;
    border-color: #6f42c1;
}
.btn-outline-purple:hover {
    color: #fff;
    background-color: #6f42c1;
    border-color: #6f42c1;
}
.badge-purple {
    color: #fff;
    background-color: #6f42c1;
}
.card-purple {
    border-color: #6f42c1;
}
.card-outline.card-purple {
    border-color: #6f42c1;
}
.card-outline.card-purple .card-header {
    background-color: transparent;
    border-color: #6f42c1;
}
.shadow-sm {
    box-shadow: 0 .125rem .25rem rgba(0,0,0,.075)!important;
}

/* Gallery Styles */
.gallery-item-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    overflow: hidden;
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
}

.gallery-item-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(0,0,0,0.15);
}

.gallery-image-container {
    position: relative;
    overflow: hidden;
}

.gallery-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.delete-image-btn {
    position: absolute;
    top: 8px;
    right: 8px;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0.8;
    transition: opacity 0.3s ease;
}

.delete-image-btn:hover {
    opacity: 1;
}



.gallery-info {
    padding: 15px;
}

.gallery-title {
    font-weight: 600;
    margin-bottom: 8px;
    color: #333;
    font-size: 14px;
}

.gallery-description {
    color: #666;
    font-size: 12px;
    margin-bottom: 8px;
    line-height: 1.4;
}


</style>
@endpush


@endsection 