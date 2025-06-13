@extends('adminlte::page')

@section('title', 'Başkan İçerikleri')

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
                            @case('value')
                                Değerler
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
                                @case('value')
                                    Değerler
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
                        <a href="{{ route('admin.mayor-content.index', ['type' => 'value']) }}" 
                           class="btn {{ $type == 'value' ? 'btn-success' : 'btn-outline-success' }}">
                            <i class="fas fa-star mr-1"></i>
                            Değerler
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
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-images mr-2"></i>
                        Toplu Fotoğraf Yükleme
                    </h3>
                </div>
                <div class="card-body">
                    <form id="bulk-upload-form" action="{{ route('admin.mayor-content.bulk-upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="type" value="gallery">
                        
                        <div class="form-group">
                            <label for="bulk_images">Fotoğrafları Seçin</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="bulk_images" name="images[]" multiple accept="image/*" required>
                                <label class="custom-file-label" for="bulk_images">Birden fazla fotoğraf seçin...</label>
                            </div>
                            <small class="form-text text-muted">
                                Birden fazla fotoğraf seçebilirsiniz. Desteklenen formatlar: JPEG, PNG, JPG, GIF, WebP (Her biri max: 2MB)
                            </small>
                        </div>
                        
                        <div id="image-preview" class="row" style="display: none;">
                            <div class="col-12">
                                <h5>Seçilen Fotoğraflar:</h5>
                                <div id="preview-container" class="row"></div>
                            </div>
                        </div>
                        
                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload mr-1"></i>
                                Fotoğrafları Yükle
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif

            @if($type == 'gallery')
            <!-- Basit Foto Galerisi -->
            @if($contents->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-images mr-2"></i>
                        Foto Galerisi ({{ $contents->count() }} fotoğraf)
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($contents as $content)
                        <div class="col-md-2 col-sm-3 col-4 mb-3">
                            <div class="position-relative">
                                <img src="{{ asset('uploads/' . $content->image) }}" 
                                     alt="{{ $content->title }}" 
                                     class="img-thumbnail w-100" 
                                     style="height: 120px; object-fit: cover; cursor: pointer;"
                                     onclick="showImageModal('{{ asset('uploads/' . $content->image) }}', '{{ $content->title }}')">
                                
                                <!-- Silme butonu -->
                                <button type="button" 
                                        class="btn btn-danger btn-sm position-absolute" 
                                        style="top: 5px; right: 5px; padding: 2px 6px;"
                                        onclick="deleteImage({{ $content->id }})"
                                        title="Sil">
                                    <i class="fas fa-times"></i>
                                </button>
                                
                                <!-- Durum göstergesi -->
                                @if(!$content->is_active)
                                <span class="badge badge-secondary position-absolute" style="bottom: 5px; left: 5px; font-size: 10px;">
                                    Pasif
                                </span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Pagination -->
                    @if($contents->hasPages())
                    <div class="d-flex justify-content-center mt-3">
                        {{ $contents->appends(['type' => $type])->links() }}
                    </div>
                    @endif
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
                            @case('value')
                                <i class="fas fa-star mr-2"></i>
                                Değerler Listesi
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
                                    @case('value')
                                        İlk değerinizi eklemek için "Yeni Ekle" butonuna tıklayın.
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

@push('scripts')
<script>
$(document).ready(function() {
    // Durum değiştirme
    $('.toggle-status').on('click', function() {
        var button = $(this);
        var contentId = button.data('id');
        
        $.ajax({
            url: '/admin/mayor-content/' + contentId + '/toggle-status',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Bir hata oluştu: ' + response.message);
                }
            },
            error: function() {
                alert('Bir hata oluştu. Lütfen tekrar deneyin.');
            }
        });
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
                    var col = $('<div class="col-md-2 col-sm-3 col-4 mb-3"></div>');
                    var img = $('<img class="img-thumbnail" style="width: 100%; height: 120px; object-fit: cover;">');
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
        submitBtn.html('<i class="fas fa-spinner fa-spin mr-1"></i> Yükleniyor...');
    });
});

// Fotoğraf modal gösterme
function showImageModal(imageSrc, title) {
    var modal = `
        <div class="modal fade" id="imageModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">${title}</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <img src="${imageSrc}" class="img-fluid" alt="${title}">
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Eski modal'ı kaldır
    $('#imageModal').remove();
    
    // Yeni modal'ı ekle ve göster
    $('body').append(modal);
    $('#imageModal').modal('show');
}

// Fotoğraf silme
function deleteImage(contentId) {
    if (confirm('Bu fotoğrafı silmek istediğinizden emin misiniz?')) {
        $.ajax({
            url: '/admin/mayor-content/' + contentId,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                location.reload();
            },
            error: function() {
                alert('Fotoğraf silinirken bir hata oluştu.');
            }
        });
    }
}
</script>
@endpush
@endsection 