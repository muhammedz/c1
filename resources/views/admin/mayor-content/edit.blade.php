@extends('adminlte::page')

@section('title', 'İçerik Düzenle')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">İçerik Düzenle</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Ana Sayfa</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.mayor.index') }}">Başkan</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.mayor-content.index', ['type' => $mayorContent->type]) }}">İçerikler</a></li>
                        <li class="breadcrumb-item active">Düzenle</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{ session('error') }}
                </div>
            @endif

            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">İçerik Düzenle</h3>
                </div>
                
                <form action="{{ route('admin.mayor-content.update', $mayorContent) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <!-- Başlık -->
                                <div class="form-group">
                                    <label for="title">Başlık <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title', $mayorContent->title) }}" required>
                                    @error('title')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Açıklama -->
                                <div class="form-group">
                                    <label for="description">Açıklama</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="6">{{ old('description', $mayorContent->description) }}</textarea>
                                    @error('description')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                @if($mayorContent->type == 'agenda')
                                    <!-- Tarih ve Saat (Gündem için) -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="event_date">Etkinlik Tarihi</label>
                                                <input type="date" class="form-control" 
                                                       id="event_date" name="extra_data[event_date]" 
                                                       value="{{ old('extra_data.event_date', $mayorContent->extra_data['event_date'] ?? '') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="event_time">Etkinlik Saati</label>
                                                <input type="time" class="form-control" 
                                                       id="event_time" name="extra_data[event_time]" 
                                                       value="{{ old('extra_data.event_time', $mayorContent->extra_data['event_time'] ?? '') }}">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="event_location">Etkinlik Yeri</label>
                                        <input type="text" class="form-control" 
                                               id="event_location" name="extra_data[event_location]" 
                                               value="{{ old('extra_data.event_location', $mayorContent->extra_data['event_location'] ?? '') }}"
                                               placeholder="Etkinlik yapılacak yer">
                                    </div>
                                @endif

                                @if($mayorContent->type == 'value')
                                    <!-- İkon (Değerler için) -->
                                    <div class="form-group">
                                        <label for="icon">İkon</label>
                                        <input type="text" class="form-control" 
                                               id="icon" name="extra_data[icon]" 
                                               value="{{ old('extra_data.icon', $mayorContent->extra_data['icon'] ?? '') }}"
                                               placeholder="fas fa-heart (FontAwesome icon class)">
                                        <small class="form-text text-muted">
                                            FontAwesome icon sınıfı (örn: fas fa-heart, fas fa-star)
                                        </small>
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-4">
                                <!-- FileManagerSystem Görsel -->
                                <div class="form-group">
                                    <label for="filemanagersystem_image">Görsel</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control @error('filemanagersystem_image') is-invalid @enderror" 
                                               id="filemanagersystem_image" name="filemanagersystem_image" 
                                               value="{{ old('filemanagersystem_image', $mayorContent->filemanagersystem_image) }}" readonly>
                                        <button type="button" class="btn btn-primary" id="filemanagersystem_image_button">
                                            <i class="fas fa-image"></i> Görsel Seç
                                        </button>
                                        <button type="button" class="btn btn-danger" id="filemanagersystem_image_clear">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    @error('filemanagersystem_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div id="filemanagersystem_image_preview" class="mt-2" 
                                         style="{{ $mayorContent->filemanagersystem_image ? '' : 'display: none;' }}">
                                        <img src="{{ $mayorContent->filemanagersystem_image_url ?? '' }}" 
                                             alt="Seçilen görsel" class="img-thumbnail" style="max-width: 200px;">
                                    </div>
                                    <small class="form-text text-muted">
                                        FileManagerSystem üzerinden görsel seçin
                                    </small>
                                </div>

                                <!-- Görsel Alt Metni -->
                                <div class="form-group">
                                    <label for="filemanagersystem_image_alt">Görsel Alt Metni</label>
                                    <input type="text" class="form-control @error('filemanagersystem_image_alt') is-invalid @enderror" 
                                           id="filemanagersystem_image_alt" name="filemanagersystem_image_alt" 
                                           value="{{ old('filemanagersystem_image_alt', $mayorContent->filemanagersystem_image_alt) }}">
                                    @error('filemanagersystem_image_alt')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Görselin HTML alt özelliği için kullanılır (SEO ve erişilebilirlik için önemlidir)</small>
                                </div>

                                <!-- Görsel Başlığı -->
                                <div class="form-group">
                                    <label for="filemanagersystem_image_title">Görsel Başlığı</label>
                                    <input type="text" class="form-control @error('filemanagersystem_image_title') is-invalid @enderror" 
                                           id="filemanagersystem_image_title" name="filemanagersystem_image_title" 
                                           value="{{ old('filemanagersystem_image_title', $mayorContent->filemanagersystem_image_title) }}">
                                    @error('filemanagersystem_image_title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Görselin HTML title özelliği için kullanılır</small>
                                </div>

                                <!-- Sıra -->
                                <div class="form-group">
                                    <label for="sort_order">Sıra</label>
                                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                           id="sort_order" name="sort_order" value="{{ old('sort_order', $mayorContent->sort_order) }}" min="1">
                                    @error('sort_order')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Durum -->
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" 
                                               id="is_active" name="is_active" value="1" 
                                               {{ old('is_active', $mayorContent->is_active) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_active">Aktif</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i>
                            Güncelle
                        </button>
                        <a href="{{ route('admin.mayor-content.index', ['type' => $mayorContent->type]) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i>
                            Geri Dön
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </section>
</div>

@push('scripts')
<!-- FileManagerSystem Modal -->
<div class="modal fade" id="mediapickerModal" tabindex="-1" role="dialog" aria-labelledby="mediapickerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediapickerModalLabel">
                    <i class="fas fa-image me-2"></i>
                    Medya Seç
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <iframe id="mediapickerFrame" src="" style="width: 100%; height: 600px; border: none;"></iframe>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // TinyMCE for description
    if (typeof tinymce !== 'undefined') {
        tinymce.init({
            selector: '#description',
            height: 300,
            plugins: 'advlist autolink lists link image charmap print preview anchor searchreplace visualblocks code fullscreen insertdatetime media table paste code help wordcount',
            toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
        });
    }

    // FileManagerSystem entegrasyonu
    $('#filemanagersystem_image_button').on('click', function() {
        const input = $('#filemanagersystem_image');
        const preview = $('#filemanagersystem_image_preview');
        const previewImg = preview.find('img');
        
        // Geçici bir ID oluştur
        const tempId = Date.now();
        const relatedType = 'mayor_content';
        
        // MediaPicker URL
        const mediapickerUrl = '/admin/filemanagersystem/mediapicker?type=image&filter=all&related_type=' + relatedType + '&related_id=' + tempId;
        
        console.log('FileManagerSystem açılıyor:', mediapickerUrl);
        
        // iFrame'i güncelle
        $('#mediapickerFrame').attr('src', mediapickerUrl);
        
        // Modal'ı göster
        $('#mediapickerModal').modal('show');
        
        // iframe'den mesaj dinleme
        window.addEventListener('message', function(event) {
            console.log('Mesaj alındı:', event.data);
            
            if (event.data && typeof event.data === 'object') {
                if (event.data.type === 'media-selected' && event.data.media) {
                    const media = event.data.media;
                    console.log('Seçilen medya:', media);
                    
                    // Input'a URL'yi yaz
                    let imageUrl = media.url;
                    if (!imageUrl.startsWith('http')) {
                        imageUrl = '/uploads/media/' + media.id;
                    }
                    
                    input.val(imageUrl);
                    
                    // Önizlemeyi göster
                    previewImg.attr('src', media.url);
                    preview.show();
                    
                    // Alt text ve title alanlarını doldur
                    if (media.alt_text) {
                        $('#filemanagersystem_image_alt').val(media.alt_text);
                    }
                    if (media.title) {
                        $('#filemanagersystem_image_title').val(media.title);
                    }
                    
                    // Modal'ı kapat
                    $('#mediapickerModal').modal('hide');
                    
                    console.log('Görsel seçimi tamamlandı');
                } else if (event.data.type === 'close-modal') {
                    $('#mediapickerModal').modal('hide');
                }
            }
        });
    });

    // Görsel temizleme butonu
    $('#filemanagersystem_image_clear').on('click', function() {
        $('#filemanagersystem_image').val('');
        $('#filemanagersystem_image_preview').hide();
        $('#filemanagersystem_image_alt').val('');
        $('#filemanagersystem_image_title').val('');
    });

    // Modal kapandığında iframe'i temizle
    $('#mediapickerModal').on('hidden.bs.modal', function () {
        $('#mediapickerFrame').attr('src', '');
    });

    // Sayfa yüklendiğinde mevcut görsel varsa önizlemeyi göster
    const initialImageValue = $('#filemanagersystem_image').val();
    if (initialImageValue) {
        $('#filemanagersystem_image_preview').show();
    }
});
</script>
@endpush
@endsection 