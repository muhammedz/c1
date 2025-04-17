@extends('adminlte::page')

@section('title', 'Projeyi Düzenle')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Projeyi Düzenle: {{ $project->title }}</h1>
        <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Projelere Dön
        </a>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <form action="{{ route('admin.projects.update', $project->id) }}" method="POST" enctype="multipart/form-data" id="project-form">
                @csrf
                @method('POST')
                
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Proje Bilgileri</h3>
                    </div>
                    <div class="card-body">
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        
                        <div class="row">
                            <div class="col-md-8">
                                <!-- Proje Adı -->
                                <div class="form-group">
                                    <label for="title">Proje Adı <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $project->title) }}" required>
                                    @error('title')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <!-- Slug -->
                                <div class="form-group">
                                    <label for="slug">Slug <small class="text-muted">(Otomatik oluşturulur)</small></label>
                                    <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $project->slug) }}">
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
                                            <option value="{{ $category->id }}" {{ old('category_id', $project->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <!-- Durum (Tamamlanma Yüzdesi) -->
                                <div class="form-group">
                                    <label for="completion_percentage">Tamamlanma Durumu (%)</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('completion_percentage') is-invalid @enderror" id="completion_percentage" name="completion_percentage" value="{{ old('completion_percentage', $project->completion_percentage) }}" min="0" max="100">
                                        <div class="input-group-append">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                    @error('completion_percentage')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <!-- Tarih -->
                                <div class="form-group">
                                    <label for="project_date">Proje Tarihi</label>
                                    <input type="date" class="form-control @error('project_date') is-invalid @enderror" id="project_date" name="project_date" value="{{ old('project_date', $project->project_date ? $project->project_date->format('Y-m-d') : date('Y-m-d')) }}">
                                    @error('project_date')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <!-- Sıralama -->
                                <div class="form-group">
                                    <label for="order">Sıralama</label>
                                    <input type="number" class="form-control @error('order') is-invalid @enderror" id="order" name="order" value="{{ old('order', $project->order) }}" min="0">
                                    @error('order')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <!-- Proje Açıklaması -->
                                <div class="form-group">
                                    <label for="description">Proje Açıklaması <span class="text-danger">*</span></label>
                                    <textarea class="form-control tinymce @error('description') is-invalid @enderror" id="description" name="description" rows="5">{{ old('description', $project->description) }}</textarea>
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
                        <!-- Kapak Görseli -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Kapak Görseli</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="cover_image">Kapak Görseli Seçin</label>
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <a id="lfm" data-input="cover_image" data-preview="cover-preview-holder" class="btn btn-primary">
                                                <i class="fas fa-image"></i> Görsel Seç
                                            </a>
                                        </span>
                                        <input type="text" class="form-control @error('cover_image') is-invalid @enderror" id="cover_image" name="cover_image" value="{{ old('cover_image', asset('storage/' . $project->cover_image)) }}">
                                    </div>
                                    <small class="form-text text-muted">Önerilen boyut: 800x600px</small>
                                    @error('cover_image')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="mt-3">
                                    <div id="cover-preview-holder" class="img-preview text-center">
                                        @if($project->cover_image)
                                            <img src="{{ asset($project->cover_image) }}" style="max-width: 100%; max-height: 300px;">
                                        @else
                                            <p class="text-muted">Görsel bulunamadı</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <!-- Galeri Görselleri -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Proje Galerisi</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Mevcut Galeri Görselleri</label>
                                    
                                    @if($project->images && $project->images->count() > 0)
                                        <div class="row mb-3">
                                            @foreach($project->images as $image)
                                                <div class="col-md-6 mb-3">
                                                    <div class="card">
                                                        <div class="card-body p-2">
                                                            <img src="{{ asset($image->image_path) }}" class="img-fluid" style="max-height: 150px; margin: 0 auto; display: block;">
                                                            <div class="d-flex justify-content-between mt-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="delete_gallery_{{ $image->id }}" name="delete_gallery[]" value="{{ $image->id }}">
                                                                    <label class="custom-control-label" for="delete_gallery_{{ $image->id }}">Kaldır</label>
                                                                </div>
                                                                <span class="badge badge-secondary">Sıra: {{ $image->order }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="alert alert-info">
                                            Henüz galeriye görsel eklenmemiş.
                                        </div>
                                    @endif
                                    
                                    <hr>
                                    
                                    <label>Yeni Galeri Görselleri Ekle</label>
                                    <div class="gallery-container">
                                        <div class="row" id="gallery-items">
                                            <!-- Galeri öğeleri buraya eklenecek -->
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
                            <div class="col-md-6">
                                <!-- Aktif/Pasif -->
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', $project->is_active) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_active">Proje aktif olarak yayınlansın</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- Anasayfada Göster -->
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="show_on_homepage" name="show_on_homepage" value="1" {{ old('show_on_homepage', $project->show_on_homepage) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="show_on_homepage">Anasayfada göster</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Değişiklikleri Kaydet
                        </button>
                        <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> İptal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop

@section('css')
    <style>
        .gallery-preview {
            gap: 10px;
        }
        
        .gallery-preview-item {
            width: 120px;
            height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            border: 1px solid #ddd;
            border-radius: 4px;
            overflow: hidden;
        }
        
        .gallery-preview-item img {
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
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
                plugins: 'preview importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons',
                toolbar: 'undo redo | bold italic underline strikethrough | fontfamily fontsize blocks | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl',
                menubar: 'file edit view insert format tools table help',
                toolbar_sticky: true,
                autosave_ask_before_unload: true,
                autosave_interval: '30s',
                autosave_prefix: '{path}{query}-{id}-',
                autosave_restore_when_empty: false,
                autosave_retention: '2m',
                image_advtab: true,
                height: 400,
                image_caption: true,
                quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
                noneditable_class: 'mceNonEditable',
                toolbar_mode: 'sliding',
                contextmenu: 'link image table',
                skin: 'oxide',
                content_css: 'default',
                content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }',
                relative_urls: false,
                remove_script_host: false,
                convert_urls: true,
                images_upload_url: '{{ route("admin.tinymce.upload") }}',
                images_upload_credentials: true,
                branding: false,
                promotion: false,
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
        };
        
        document.head.appendChild(script);
    
        $(document).ready(function() {
            // Slug oluşturma
            $('#title').on('blur', function() {
                if ($('#slug').val() === '') {
                    var title = $(this).val();
                    var slug = title.toLowerCase()
                        .replace(/[^\w ]+/g, '')
                        .replace(/ +/g, '-');
                    $('#slug').val(slug);
                }
            });
            
            // Laravel FileManager
            $('#lfm').filemanager('image');
            
            // Galeri öğeleri sayacı
            let galleryItemCount = 0;
            
            // Galeri öğesi ekle
            $('#add-gallery-item').click(function() {
                const index = galleryItemCount++;
                const galleryItem = `
                    <div class="col-md-6 mb-3 gallery-item" id="gallery-item-${index}">
                        <div class="card">
                            <div class="card-body p-2">
                                <div class="input-group">
                                    <span class="input-group-prepend">
                                        <a data-input="gallery-${index}" data-preview="gallery-preview-${index}" class="btn btn-primary gallery-btn">
                                            <i class="fas fa-image"></i>
                                        </a>
                                    </span>
                                    <input type="text" class="form-control" id="gallery-${index}" name="gallery_images[]">
                                    <span class="input-group-append">
                                        <button type="button" class="btn btn-danger remove-gallery-item" data-index="${index}">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </span>
                                </div>
                                <div class="mt-2">
                                    <div id="gallery-preview-${index}" class="gallery-preview text-center" style="max-height: 150px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                $('#gallery-items').append(galleryItem);
                $(`#gallery-item-${index} .gallery-btn`).filemanager('image');
            });
            
            // Galeri öğesi kaldır
            $(document).on('click', '.remove-gallery-item', function() {
                const index = $(this).data('index');
                $(`#gallery-item-${index}`).remove();
            });
        });
    </script>
@stop 