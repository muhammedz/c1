@extends('adminlte::page')

@section('title', 'Çankaya Evi Düzenle')

@section('content_header')
    <style>
        .content-header {
            display: none;
        }
    </style>
@stop

@section('plugins.TempusDominusBs4', true)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit mr-2"></i>
                        Çankaya Evi Düzenle: {{ $cankayaHouse->name }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.cankaya-houses.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left mr-1"></i>
                            Geri Dön
                        </a>
                    </div>
                </div>

                <form action="{{ route('admin.cankaya-houses.update', $cankayaHouse) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <!-- Sol Kolon -->
                            <div class="col-md-8">
                                <!-- Temel Bilgiler -->
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Temel Bilgiler</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="name">Çankaya Evi Adı <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                                   id="name" name="name" value="{{ old('name', $cankayaHouse->name) }}" required>
                                            @error('name')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="description">Açıklama</label>
                                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                                      id="description" name="description" rows="4">{{ old('description', $cankayaHouse->description) }}</textarea>
                                            @error('description')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="address">Adres <span class="text-danger">*</span></label>
                                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                                      id="address" name="address" rows="3" required>{{ old('address', $cankayaHouse->address) }}</textarea>
                                            @error('address')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="phone">Telefon</label>
                                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                                           id="phone" name="phone" value="{{ old('phone', $cankayaHouse->phone) }}">
                                                    @error('phone')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="location_link">Konum Linki</label>
                                                    <input type="url" class="form-control @error('location_link') is-invalid @enderror" 
                                                           id="location_link" name="location_link" value="{{ old('location_link', $cankayaHouse->location_link) }}"
                                                           placeholder="https://maps.google.com/...">
                                                    @error('location_link')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Resimler -->
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Resimler</h3>
                                    </div>
                                    <div class="card-body">
                                        

                                        <!-- Yeni Resim Ekleme -->
                                        <div class="form-group">
                                            <label for="gallery">Resim Galerisi <small class="text-muted">(En fazla 10 resim)</small></label>
                                            <div class="d-flex">
                                                <button type="button" id="gallery-browser" class="btn btn-primary">
                                                    <i class="fas fa-images"></i> Resim Seç (<span id="gallery-count">0</span>/10)
                                                </button>
                                            </div>
                                            <input type="hidden" id="fake-gallery-input" class="fake-gallery-input">
                                            
                                            <div id="gallery-preview" class="mt-3" style="display: none;">
                                                <div id="gallery-items" class="d-flex flex-wrap gap-3"></div>
                                                <div id="gallery-inputs"></div>
                                            </div>
                                            @error('images')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            @error('images.*')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Kurslar -->
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Kurslar</h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-primary btn-sm" id="add-course">
                                                <i class="fas fa-plus mr-1"></i>
                                                Kurs Ekle
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div id="courses-container">
                                            @if($cankayaHouse->courses && $cankayaHouse->courses->count() > 0)
                                                @foreach($cankayaHouse->courses as $index => $course)
                                                <div class="course-item border rounded p-3 mb-3" data-index="{{ $index }}">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Kurs Adı <span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" name="courses[{{ $index }}][name]" 
                                                                       value="{{ $course->name }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>İkon (FontAwesome)</label>
                                                                <input type="text" class="form-control" name="courses[{{ $index }}][icon]" 
                                                                       value="{{ $course->icon }}" placeholder="fas fa-palette">
                                                                <small class="text-muted">Örnek: fas fa-palette, fas fa-music, fas fa-language</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label>Açıklama</label>
                                                                <textarea class="form-control" name="courses[{{ $index }}][description]" 
                                                                          rows="2">{{ $course->description }}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Durum</label>
                                                                <select class="form-control" name="courses[{{ $index }}][status]">
                                                                    <option value="active" {{ $course->status == 'active' ? 'selected' : '' }}>Aktif</option>
                                                                    <option value="inactive" {{ $course->status == 'inactive' ? 'selected' : '' }}>Pasif</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Sıralama</label>
                                                                <input type="number" class="form-control" name="courses[{{ $index }}][order]" 
                                                                       value="{{ $course->order ?? 0 }}" min="0">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="text-right">
                                                        <button type="button" class="btn btn-danger btn-sm remove-course">
                                                            <i class="fas fa-trash mr-1"></i>
                                                            Kaldır
                                                        </button>
                                                    </div>
                                                    <input type="hidden" name="courses[{{ $index }}][id]" value="{{ $course->id }}">
                                                </div>
                                                @endforeach
                                            @endif
                                        </div>
                                        
                                        <div id="no-courses" class="text-center text-muted py-4" style="{{ $cankayaHouse->courses && $cankayaHouse->courses->count() > 0 ? 'display: none;' : '' }}">
                                            <i class="fas fa-graduation-cap fa-3x mb-3"></i>
                                            <p>Henüz kurs eklenmemiş. Yukarıdaki "Kurs Ekle" butonunu kullanarak kurs ekleyebilirsiniz.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sağ Kolon -->
                            <div class="col-md-4">
                                <!-- Yayın Ayarları -->
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Yayın Ayarları</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="status">Durum</label>
                                            <select class="form-control @error('status') is-invalid @enderror" 
                                                    id="status" name="status" required>
                                                <option value="active" {{ old('status', $cankayaHouse->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                                                <option value="inactive" {{ old('status', $cankayaHouse->status) == 'inactive' ? 'selected' : '' }}>Pasif</option>
                                            </select>
                                            @error('status')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="order">Sıralama</label>
                                            <input type="number" class="form-control @error('order') is-invalid @enderror" 
                                                   id="order" name="order" value="{{ old('order', $cankayaHouse->order ?? 0) }}" min="0">
                                            <small class="form-text text-muted">
                                                Küçük sayılar önce görünür
                                            </small>
                                            @error('order')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- İstatistikler -->
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">İstatistikler</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="info-box">
                                            <span class="info-box-icon bg-info">
                                                <i class="fas fa-graduation-cap"></i>
                                            </span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Toplam Kurs</span>
                                                <span class="info-box-number">{{ $cankayaHouse->courses->count() }}</span>
                                            </div>
                                        </div>

                                        <div class="info-box">
                                            <span class="info-box-icon bg-success">
                                                <i class="fas fa-images"></i>
                                            </span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Resim Sayısı</span>
                                                <span class="info-box-number">{{ $cankayaHouse->images ? count($cankayaHouse->images) : 0 }}</span>
                                            </div>
                                        </div>

                                        <div class="info-box">
                                            <span class="info-box-icon bg-warning">
                                                <i class="fas fa-calendar"></i>
                                            </span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Oluşturulma</span>
                                                <span class="info-box-number">{{ $cankayaHouse->created_at->format('d.m.Y') }}</span>
                                            </div>
                                        </div>
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
                        <a href="{{ route('admin.cankaya-houses.show', $cankayaHouse) }}" class="btn btn-info">
                            <i class="fas fa-eye mr-1"></i>
                            Görüntüle
                        </a>
                        <a href="{{ route('admin.cankaya-houses.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times mr-1"></i>
                            İptal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

<!-- MediaPicker Modal -->
<div class="modal fade" id="mediapickerModal" tabindex="-1" role="dialog" aria-labelledby="mediapickerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediapickerModalLabel">Medya Seçici</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Kapat">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <iframe id="mediapickerFrame" style="width: 100%; height: 80vh; border: none;"></iframe>
            </div>
        </div>
    </div>
</div>

@push('js')
<script>
$(document).ready(function() {
    let galleryImages = [];
    
    // Mevcut resimleri galleryImages dizisine ekle
    @if($cankayaHouse->images && count($cankayaHouse->images) > 0)
        @foreach($cankayaHouse->images as $image)
            galleryImages.push('{{ $image }}');
        @endforeach
        updateGalleryDisplay();
    @endif



    // Galeri tarayıcısı
    $('#gallery-browser').on('click', function() {
        if (galleryImages.length >= 10) {
            toastr.warning('En fazla 10 resim ekleyebilirsiniz.');
            return;
        }
        
        // Geçici bir ID oluştur
        const tempId = Date.now();
        const relatedType = 'cankaya_house';
        
        // MediaPicker URL
        const mediapickerUrl = '/admin/filemanagersystem/mediapicker?type=image&filter=all&related_type=' + relatedType + '&related_id=' + tempId;
        
        // iFrame'i güncelle
        $('#mediapickerFrame').attr('src', mediapickerUrl);
        
        // Modal'ı göster
        $('#mediapickerModal').modal('show');
        
        // Medya seçimi mesaj dinleyicisi
        function handleMediaSelection(event) {
            try {
                if (event.data && event.data.type === 'mediaSelected') {
                    let mediaUrl = event.data.mediaUrl;
                    
                    if (mediaUrl) {
                        // Eğer URL göreceli ise tam URL'ye çevir
                        if (mediaUrl.startsWith('/')) {
                            const baseUrl = window.location.protocol + '//' + window.location.host;
                            mediaUrl = baseUrl + mediaUrl;
                        }
                        
                        // Resmi galeriye ekle
                        if (galleryImages.length < 10 && !galleryImages.includes(mediaUrl)) {
                            galleryImages.push(mediaUrl);
                            updateGalleryDisplay();
                        }
                    }
                    
                    // Modal'ı kapat
                    $('#mediapickerModal').modal('hide');
                    
                    // Event listener'ı kaldır
                    window.removeEventListener('message', handleMediaSelection);
                } else if (event.data && event.data.type === 'mediapickerError') {
                    toastr.error('Medya seçici hatası: ' + event.data.message);
                    $('#mediapickerModal').modal('hide');
                    window.removeEventListener('message', handleMediaSelection);
                }
            } catch (error) {
                toastr.error('Medya seçimi işlenirken bir hata oluştu: ' + error.message);
                window.removeEventListener('message', handleMediaSelection);
            }
        }
        
        // Event listener ekle
        window.removeEventListener('message', handleMediaSelection);
        window.addEventListener('message', handleMediaSelection);
    });

    // Galeri görüntüsünü güncelle
    function updateGalleryDisplay() {
        const galleryItems = $('#gallery-items');
        const galleryInputs = $('#gallery-inputs');
        const galleryCount = $('#gallery-count');
        const galleryPreview = $('#gallery-preview');
        
        // Temizle
        galleryItems.empty();
        galleryInputs.empty();
        
        // Sayacı güncelle
        galleryCount.text(galleryImages.length);
        
        if (galleryImages.length > 0) {
            galleryPreview.show();
            
            galleryImages.forEach(function(imageUrl, index) {
                // Görsel öğesi
                const imageItem = $(`
                    <div class="gallery-item" style="position: relative; width: 120px; height: 120px; border-radius: 8px; overflow: hidden; border: 2px solid #dee2e6;">
                        <img src="${imageUrl}" alt="Galeri Resmi" style="width: 100%; height: 100%; object-fit: cover;">
                        <button type="button" class="btn btn-danger btn-sm remove-gallery-item" 
                                data-index="${index}" 
                                style="position: absolute; top: 5px; right: 5px; width: 25px; height: 25px; padding: 0; border-radius: 50%;">
                            <i class="fas fa-times" style="font-size: 12px;"></i>
                        </button>
                    </div>
                `);
                
                galleryItems.append(imageItem);
                
                // Hidden input
                const hiddenInput = $(`<input type="hidden" name="images[]" value="${imageUrl}">`);
                galleryInputs.append(hiddenInput);
            });
        } else {
            galleryPreview.hide();
        }
    }

    // Galeri öğesi silme (event delegation)
    $(document).on('click', '.remove-gallery-item', function() {
        const index = $(this).data('index');
        galleryImages.splice(index, 1);
        updateGalleryDisplay();
    });

    // Kurs yönetimi
    let courseIndex = {{ $cankayaHouse->courses ? $cankayaHouse->courses->count() : 0 }};
    let deletedCourseIds = [];
    
    // Kurs ekleme
    $('#add-course').on('click', function() {
        const courseHtml = `
            <div class="course-item border rounded p-3 mb-3" data-index="${courseIndex}">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Kurs Adı <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="courses[${courseIndex}][name]" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>İkon (FontAwesome)</label>
                            <input type="text" class="form-control" name="courses[${courseIndex}][icon]" placeholder="fas fa-palette">
                            <small class="text-muted">Örnek: fas fa-palette, fas fa-music, fas fa-language</small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Açıklama</label>
                            <textarea class="form-control" name="courses[${courseIndex}][description]" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Durum</label>
                            <select class="form-control" name="courses[${courseIndex}][status]">
                                <option value="active" selected>Aktif</option>
                                <option value="inactive">Pasif</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Sıralama</label>
                            <input type="number" class="form-control" name="courses[${courseIndex}][order]" value="0" min="0">
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <button type="button" class="btn btn-danger btn-sm remove-course">
                        <i class="fas fa-trash mr-1"></i>
                        Kaldır
                    </button>
                </div>
            </div>
        `;
        
        $('#courses-container').append(courseHtml);
        $('#no-courses').hide();
        courseIndex++;
    });
    
    // Kurs silme (event delegation)
    $(document).on('click', '.remove-course', function() {
        const courseItem = $(this).closest('.course-item');
        const courseId = courseItem.find('input[name*="[id]"]').val();
        
        // Eğer mevcut bir kurs ise (ID'si varsa) silinen listesine ekle
        if (courseId) {
            deletedCourseIds.push(courseId);
            updateDeletedCoursesInput();
        }
        
        courseItem.remove();
        
        // Eğer hiç kurs kalmadıysa boş mesajı göster
        if ($('#courses-container .course-item').length === 0) {
            $('#no-courses').show();
        }
    });
    
    // Silinen kursları hidden input olarak güncelle
    function updateDeletedCoursesInput() {
        // Mevcut silinen kurs input'larını temizle
        $('input[name="deleted_course_ids[]"]').remove();
        
        // Yeni input'ları ekle
        deletedCourseIds.forEach(function(courseId) {
            $('<input>').attr({
                type: 'hidden',
                name: 'deleted_course_ids[]',
                value: courseId
            }).appendTo('form');
        });
    }

    // Form validasyonu
    $('form').on('submit', function(e) {
        let isValid = true;
        
        // Zorunlu alanları kontrol et
        const requiredFields = ['name', 'address'];
        requiredFields.forEach(function(field) {
            const input = $(`[name="${field}"]`);
            if (!input.val().trim()) {
                input.addClass('is-invalid');
                isValid = false;
            } else {
                input.removeClass('is-invalid');
            }
        });
        
        // Kurs adlarını kontrol et
        $('.course-item').each(function() {
            const nameInput = $(this).find('input[name*="[name]"]');
            if (!nameInput.val().trim()) {
                nameInput.addClass('is-invalid');
                isValid = false;
            } else {
                nameInput.removeClass('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            toastr.error('Lütfen zorunlu alanları doldurun.');
        }
    });
});
</script>
@endpush 