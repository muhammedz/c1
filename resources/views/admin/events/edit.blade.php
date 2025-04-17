@extends('adminlte::page')

@section('title', 'Etkinlik Düzenle')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Etkinlik Düzenle</h1>
        <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Etkinliklere Dön
        </a>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <form action="{{ route('admin.events.update', $event->id) }}" method="POST" enctype="multipart/form-data" id="event-form">
                @csrf
                
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Etkinlik Bilgileri</h3>
                    </div>
                    <div class="card-body">
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        
                        <div class="row">
                            <div class="col-md-8">
                                <!-- Etkinlik Adı -->
                                <div class="form-group">
                                    <label for="title">Etkinlik Adı <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $event->title) }}" required>
                                    @error('title')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <!-- Slug -->
                                <div class="form-group">
                                    <label for="slug">Slug <small class="text-muted">(Otomatik oluşturulur)</small></label>
                                    <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $event->slug) }}">
                                    @error('slug')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <!-- Kategori -->
                                <div class="form-group">
                                    <label for="category_id">Kategori</label>
                                    <select class="form-control @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
                                        <option value="">Kategori Seçin</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id', $event->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Organizatör -->
                                <div class="form-group">
                                    <label for="organizer">Organizatör</label>
                                    <input type="text" class="form-control @error('organizer') is-invalid @enderror" id="organizer" name="organizer" value="{{ old('organizer', $event->organizer) }}">
                                    @error('organizer')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <!-- Sıralama -->
                                <div class="form-group">
                                    <label for="order">Sıralama</label>
                                    <input type="number" class="form-control @error('order') is-invalid @enderror" id="order" name="order" value="{{ old('order', $event->order) }}" min="0">
                                    @error('order')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <!-- Etkinlik Açıklaması -->
                                <div class="form-group">
                                    <label for="description">Etkinlik Açıklaması <span class="text-danger">*</span></label>
                                    <textarea class="form-control tinymce @error('description') is-invalid @enderror" id="description" name="description" rows="5">{{ old('description', $event->description) }}</textarea>
                                    @error('description')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <!-- Tarih ve Konum Bilgileri -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Tarih ve Konum Bilgileri</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="start_date">Başlangıç Tarihi ve Saati <span class="text-danger">*</span></label>
                                    <input type="datetime-local" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date', $event->start_date->format('Y-m-d\TH:i')) }}" required>
                                    @error('start_date')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="end_date">Bitiş Tarihi ve Saati</label>
                                    <input type="datetime-local" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ old('end_date', $event->end_date ? $event->end_date->format('Y-m-d\TH:i') : '') }}">
                                    <small class="form-text text-muted">Boş bırakılırsa sadece başlangıç tarihi gösterilir.</small>
                                    @error('end_date')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="location">Konum</label>
                                    <input type="text" class="form-control @error('location') is-invalid @enderror" id="location" name="location" value="{{ old('location', $event->location) }}" placeholder="Örn: Konferans Salonu">
                                    @error('location')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="address">Adres</label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address', $event->address) }}</textarea>
                                    @error('address')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Kayıt Bilgileri -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Kayıt Bilgileri</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="register_required" name="register_required" value="1" {{ old('register_required', $event->register_required) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="register_required">Kayıt gerekiyor</label>
                                    </div>
                                </div>

                                <div class="form-group register-settings" id="register-url-group">
                                    <label for="register_url">Kayıt Linki</label>
                                    <input type="url" class="form-control @error('register_url') is-invalid @enderror" id="register_url" name="register_url" value="{{ old('register_url', $event->register_url) }}" placeholder="https://...">
                                    <small class="form-text text-muted">Harici bir kayıt sayfanız varsa link ekleyebilirsiniz.</small>
                                    @error('register_url')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group register-settings" id="max-participants-group">
                                    <label for="max_participants">Maksimum Katılımcı</label>
                                    <input type="number" class="form-control @error('max_participants') is-invalid @enderror" id="max_participants" name="max_participants" value="{{ old('max_participants', $event->max_participants) }}" min="0">
                                    <small class="form-text text-muted">Sıfır veya boş bırakılırsa sınırsız olarak kabul edilir.</small>
                                    @error('max_participants')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <!-- Kapak Görseli -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Kapak Görseli</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="cover_image">Kapak Görseli Seçin <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <a id="lfm" data-input="cover_image" data-preview="cover-preview-holder" class="btn btn-primary">
                                                <i class="fas fa-image"></i> Görsel Seç
                                            </a>
                                        </span>
                                        <input type="text" class="form-control @error('cover_image') is-invalid @enderror" id="cover_image" name="cover_image" value="{{ old('cover_image', $event->cover_image) }}" required>
                                    </div>
                                    <small class="form-text text-muted">Önerilen boyut: 800x600px</small>
                                    @error('cover_image')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="mt-3">
                                    <div id="cover-preview-holder" class="img-preview text-center">
                                        @if($event->cover_image)
                                            <img src="{{ $event->cover_image_url }}" style="max-width: 100%; max-height: 300px;">
                                        @else
                                            <p class="text-muted">Önizleme için görsel seçin</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                        <!-- Galeri Görselleri -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Etkinlik Galerisi</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Galeri Görselleri <small class="text-muted">(İsteğe bağlı)</small></label>
                                    <div class="gallery-container">
                                        <div class="row" id="gallery-items">
                                            <!-- Mevcut galeri görselleri -->
                                            @if($event->images && $event->images->count() > 0)
                                                @foreach($event->images as $image)
                                                    <div class="col-md-4 gallery-item" id="gallery-item-{{ $image->id }}">
                                                        <div class="card">
                                                            <button type="button" class="btn btn-danger btn-sm rounded-circle remove-gallery-item" 
                                                                    data-id="{{ $image->id }}" data-existing="true">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                            <div class="card-body">
                                                                <div class="form-group">
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control" name="existing_images[]" value="{{ $image->image_path }}" readonly>
                                                                        <input type="hidden" name="existing_image_ids[]" value="{{ $image->id }}">
                                                                    </div>
                                                                </div>
                                                                <div class="img-preview">
                                                                    <img src="{{ $image->image_url }}" style="max-width: 100%; max-height: 150px;">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                        <button type="button" id="add-gallery-item" class="btn btn-success mt-2">
                                            <i class="fas fa-plus"></i> Galeri Görseli Ekle
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Görünürlük Ayarları</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <!-- Aktif/Pasif -->
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', $event->is_active) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_active">Etkinlik aktif olarak yayınlansın</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <!-- Anasayfada Göster -->
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="show_on_homepage" name="show_on_homepage" value="1" {{ old('show_on_homepage', $event->show_on_homepage) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="show_on_homepage">Anasayfada göster</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <!-- Öne Çıkar -->
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $event->is_featured) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_featured">Öne çıkarılmış etkinlik</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i> Kaydet
                            </button>
                            <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times mr-1"></i> İptal
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Silinecek galeri resimleri için input alanı -->
                <div id="delete-gallery-container"></div>
            </form>
        </div>
    </div>
@stop

@section('css')
    <style>
        .gallery-item {
            position: relative;
            margin-bottom: 20px;
        }
        
        .gallery-item .remove-gallery-item {
            position: absolute;
            top: -10px;
            right: -10px;
            z-index: 10;
        }
        
        .img-preview {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px;
            min-height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .register-settings {
            margin-left: 20px;
        }
    </style>
@stop

@section('js')
    <script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
    <script>
        // TinyMCE'yi dinamik olarak yükle
        var script = document.createElement('script');
        script.type = 'text/javascript';
        script.src = '{{ asset("vendor/tinymce/tinymce/js/tinymce/tinymce.min.js") }}';
        
        script.onload = function() {
            console.log('TinyMCE yüklendi');
            
            // TinyMCE entegrasyonu
            tinymce.init({
                selector: '.tinymce',
                language: 'tr',
                height: 400,
                menubar: true,
                plugins: [
                    'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                    'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                    'insertdatetime', 'media', 'table', 'help', 'wordcount'
                ],
                toolbar: 'undo redo | blocks | ' +
                    'bold italic backcolor | alignleft aligncenter ' +
                    'alignright alignjustify | bullist numlist outdent indent | ' +
                    'removeformat | image | help',
                images_upload_url: '{{ route("admin.tinymce.upload") }}',
                images_upload_credentials: true,
                branding: false,
                promotion: false,
                content_css: [
                    '{{ asset("vendor/adminlte/dist/css/adminlte.min.css") }}',
                    'https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700'
                ],
                file_picker_callback: function (callback, value, meta) {
                    let x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
                    let y = window.innerHeight || document.documentElement.clientHeight || document.getElementsByTagName('body')[0].clientHeight;

                    let type = meta.filetype;
                    let url = '/admin/filemanager?editor=tinymce5&type=' + type;

                    tinymce.activeEditor.windowManager.openUrl({
                        url: url,
                        title: 'Laravel File Manager',
                        width: x * 0.8,
                        height: y * 0.8,
                        resizable: 'yes',
                        close_previous: 'no',
                        onMessage: (api, message) => {
                            callback(message.content);
                        }
                    });
                }
            });
            
            // File Manager
            $('#lfm').filemanager('image', {prefix: '/admin/filemanager'});
            
            // Silinecek resimleri izleme
            const deleteGalleryContainer = $('#delete-gallery-container');
            
            // Galeri öğesi ekleme
            let galleryCounter = {{ $event->images->count() ?? 0 }};
            
            function addGalleryItem() {
                const galleryItem = `
                    <div class="col-md-4 gallery-item" id="gallery-item-new-${galleryCounter}">
                        <div class="card">
                            <button type="button" class="btn btn-danger btn-sm rounded-circle remove-gallery-item"
                                    data-id="new-${galleryCounter}">
                                <i class="fas fa-times"></i>
                            </button>
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <a id="lfm-gallery-${galleryCounter}" 
                                               data-input="gallery-image-${galleryCounter}" 
                                               data-preview="gallery-preview-${galleryCounter}" 
                                               class="btn btn-primary btn-sm">
                                                <i class="fas fa-image"></i> Seç
                                            </a>
                                        </span>
                                        <input type="text" class="form-control" 
                                               id="gallery-image-${galleryCounter}" 
                                               name="gallery_images[]">
                                    </div>
                                </div>
                                <div id="gallery-preview-${galleryCounter}" class="img-preview">
                                    <p class="text-muted">Önizleme için görsel seçin</p>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                $('#gallery-items').append(galleryItem);
                $(`#lfm-gallery-${galleryCounter}`).filemanager('image', {prefix: '/admin/filemanager'});
                
                galleryCounter++;
            }
            
            $('#add-gallery-item').click(function() {
                addGalleryItem();
            });
            
            // Galeri öğesi silme
            $(document).on('click', '.remove-gallery-item', function() {
                const id = $(this).data('id');
                const isExisting = $(this).data('existing');
                
                if (isExisting) {
                    // Mevcut resmin silinmesi için hidden input ekle
                    deleteGalleryContainer.append(`<input type="hidden" name="delete_gallery[]" value="${id}">`);
                }
                
                $(`#gallery-item-${id}`).remove();
            });
            
            // Slug oluşturma
            $('#title').on('blur', function() {
                if ($('#slug').val() === '') {
                    const slug = $(this).val()
                        .toString()
                        .toLowerCase()
                        .replace(/\s+/g, '-')
                        .replace(/[^\w\-]+/g, '')
                        .replace(/\-\-+/g, '-')
                        .replace(/^-+/, '')
                        .replace(/-+$/, '');
                    
                    $('#slug').val(slug);
                }
            });
            
            // Kayıt gerektiren alanları göster/gizle
            function toggleRegisterSettings() {
                if ($('#register_required').is(':checked')) {
                    $('.register-settings').show();
                } else {
                    $('.register-settings').hide();
                }
            }
            
            $('#register_required').change(toggleRegisterSettings);
            toggleRegisterSettings();
            
            // Form gönderilmeden önce kontrol
            $('#event-form').submit(function(e) {
                var coverImage = $('#cover_image').val();
                
                if (!coverImage) {
                    e.preventDefault();
                    alert('Lütfen bir kapak görseli seçin.');
                    return false;
                }
                
                return true;
            });
        };
        
        document.head.appendChild(script);
    </script>
@stop 