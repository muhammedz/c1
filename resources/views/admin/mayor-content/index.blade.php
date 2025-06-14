@extends('adminlte::page')

@section('title', 'Başkan İçerikleri')

@section('adminlte_css_pre')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
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
                                    <label for="bulk_images" class="font-weight-bold">
                                        <i class="fas fa-images mr-1"></i>
                                        Fotoğrafları Seçin
                                    </label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="bulk_images" name="images[]" multiple accept="image/*" required>
                                        <label class="custom-file-label" for="bulk_images">Birden fazla fotoğraf seçin...</label>
                                    </div>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Birden fazla fotoğraf seçebilirsiniz. Desteklenen formatlar: JPEG, PNG, JPG, GIF, WebP (Her biri max: 2MB)
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">&nbsp;</label>
                                    <div>
                                        <button type="submit" class="btn btn-info btn-lg btn-block">
                                            <i class="fas fa-upload mr-2"></i>
                                            Fotoğrafları Yükle
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div id="image-preview" class="mt-3" style="display: none;">
                            <div class="alert alert-info">
                                <h5 class="alert-heading">
                                    <i class="fas fa-eye mr-2"></i>
                                    Seçilen Fotoğraflar:
                                </h5>
                                <div id="preview-container" class="row mt-3"></div>
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
                                    <img src="{{ asset('uploads/' . $content->image) }}" 
                                         alt="{{ $content->title }}" 
                                         class="gallery-image">
                                    
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

@push('scripts')
<script>
console.log('Script loaded!');

$(document).ready(function() {
    console.log('Document ready!');
    console.log('jQuery version:', $.fn.jquery);
    console.log('Looking for delete buttons...');
    console.log('Delete buttons found:', $('.delete-image-btn').length);
    
    // Test butonu ekle
    if ($('.delete-image-btn').length > 0) {
        console.log('First button data-id:', $('.delete-image-btn').first().data('id'));
        console.log('First button HTML:', $('.delete-image-btn').first()[0].outerHTML);
    }
    
    // Basit test
    $('.delete-image-btn').each(function(index) {
        console.log('Button ' + index + ' ID:', $(this).data('id'));
    });
    
    // Test click event
    $('.delete-image-btn').on('click', function() {
        alert('Button clicked! ID: ' + $(this).data('id'));
        console.log('Direct click event triggered!');
    });
    
    // Basit resim silme
    $(document).on('click', '.delete-image-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        console.log('Delete button clicked!');
        alert('Delete button clicked!');
        
        var button = $(this);
        var contentId = button.data('id');
        
        console.log('Content ID:', contentId);
        console.log('Button element:', button);
        
        if (!contentId) {
            alert('Content ID bulunamadı!');
            console.error('Content ID is missing');
            return;
        }
        
        if (confirm('Bu resmi silmek istediğiniz emin misiniz?')) {
            console.log('User confirmed deletion');
            
            // Button'u disable et
            button.prop('disabled', true);
            button.html('<i class="fas fa-spinner fa-spin"></i>');
            
            $.ajax({
                url: '/admin/mayor-content/' + contentId,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                beforeSend: function() {
                    console.log('AJAX request starting...');
                },
                success: function(response) {
                    console.log('Delete success:', response);
                    location.reload();
                },
                error: function(xhr, status, error) {
                    console.error('Delete error:', xhr.responseText);
                    console.error('Status:', status);
                    console.error('Error:', error);
                    alert('Silme işlemi başarısız oldu: ' + error);
                    
                    // Button'u eski haline getir
                    button.prop('disabled', false);
                    button.html('<i class="fas fa-times"></i>');
                }
            });
        } else {
            console.log('User cancelled deletion');
        }
    });

    // Toplu fotoğraf yükleme önizleme
    $('#bulk_images').on('change', function() {
        var files = this.files;
        var preview = $('#image-preview');
        var container = $('#preview-container');
        
        container.empty();
        
        if (files.length > 0) {
            preview.show();
            
            for (var i = 0; i < files.length; i++) {
                var file = files[i];
                var reader = new FileReader();
                
                reader.onload = function(e) {
                    var col = $('<div class="col-md-3 col-sm-4 col-6 mb-3"></div>');
                    var img = $('<img class="img-thumbnail" style="width: 100%; height: 120px; object-fit: cover; border-radius: 8px;">');
                    img.attr('src', e.target.result);
                    col.append(img);
                    container.append(col);
                };
                
                reader.readAsDataURL(file);
            }
            
            // Dosya label'ını güncelle
            var fileCount = files.length;
            $('.custom-file-label').text(fileCount + ' fotoğraf seçildi');
        } else {
            preview.hide();
            $('.custom-file-label').text('Birden fazla fotoğraf seçin...');
        }
    });

    // Form submit
    $('#bulk-upload-form').on('submit', function(e) {
        var files = $('#bulk_images')[0].files;
        if (files.length === 0) {
            e.preventDefault();
            alert('Lütfen en az bir fotoğraf seçin.');
            return false;
        }
        
        // Loading göster
        var submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true);
        submitBtn.html('<i class="fas fa-spinner fa-spin mr-2"></i> Yükleniyor...');
    });
});




</script>
@endpush
@endsection 