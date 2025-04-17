@extends('adminlte::page')

@section('title', 'Yeni Kurumsal Kadro Üyesi Ekle')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-user-plus text-primary mr-2"></i> Yeni Kurumsal Kadro Üyesi Ekle</h1>
        <a href="{{ route('admin.corporate.members.index', $selectedCategory) }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Üye Listesine Dön
        </a>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <form action="{{ route('admin.corporate.members.store', $selectedCategory) }}" method="POST" enctype="multipart/form-data" id="member-form">
        @csrf
        
        <input type="hidden" name="selected_image" id="selected_image">
        
        <div class="row">
            <div class="col-md-8">
                <div class="card card-outline card-primary">
                    <div class="card-header p-2">
                        <ul class="nav nav-tabs" id="member-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="general-tab" data-toggle="pill" href="#general" role="tab" aria-controls="general" aria-selected="true">
                                    <i class="fas fa-info-circle mr-1"></i> Genel Bilgiler
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="biography-tab" data-toggle="pill" href="#biography" role="tab" aria-controls="biography" aria-selected="false">
                                    <i class="fas fa-file-alt mr-1"></i> Biyografi
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="social-tab" data-toggle="pill" href="#social" role="tab" aria-controls="social" aria-selected="false">
                                    <i class="fas fa-share-alt mr-1"></i> Sosyal Medya
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="settings-tab" data-toggle="pill" href="#settings" role="tab" aria-controls="settings" aria-selected="false">
                                    <i class="fas fa-cog mr-1"></i> Ayarlar
                                </a>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        <div class="tab-content" id="member-tabs-content">
                            <!-- Genel Bilgiler Tab -->
                            <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                                <div class="form-group">
                                    <label for="corporate_category_id">Kategori <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-folder"></i></span>
                                        </div>
                                        <select class="form-control select2 @error('corporate_category_id') is-invalid @enderror" id="corporate_category_id" name="corporate_category_id" required>
                                            @foreach($categories as $id => $name)
                                                <option value="{{ $id }}" {{ (old('corporate_category_id', $selectedCategory) == $id) ? 'selected' : '' }}>{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('corporate_category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="name">Ad Soyad <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        </div>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                    </div>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="slug">Slug</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-link"></i></span>
                                        </div>
                                        <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug') }}">
                                    </div>
                                    <small class="form-text text-muted">Boş bırakırsanız, ad soyad bilgisinden otomatik oluşturulacaktır.</small>
                                    @error('slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="title">Ünvan/Pozisyon</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-id-badge"></i></span>
                                        </div>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}">
                                    </div>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="short_description">Kısa Açıklama</label>
                                    <textarea class="form-control @error('short_description') is-invalid @enderror" id="short_description" name="short_description" rows="2">{{ old('short_description') }}</textarea>
                                    <small class="form-text text-muted">Kişinin listelenirken görünen kısa tanımı, özeti</small>
                                    @error('short_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Biyografi Tab -->
                            <div class="tab-pane fade" id="biography" role="tabpanel" aria-labelledby="biography-tab">
                                <div class="form-group">
                                    <label for="description">Detaylı Biyografi</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="10">{{ old('description') }}</textarea>
                                    <small class="form-text text-muted">Kişinin detay sayfasında görüntülenecek biyografisi</small>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Sosyal Medya Tab -->
                            <div class="tab-pane fade" id="social" role="tabpanel" aria-labelledby="social-tab">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="facebook"><i class="fab fa-facebook-f text-primary mr-1"></i> Facebook</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fab fa-facebook-f"></i></span>
                                                </div>
                                                <input type="url" class="form-control @error('facebook') is-invalid @enderror" id="facebook" name="facebook" value="{{ old('facebook') }}" placeholder="https://facebook.com/...">
                                            </div>
                                            @error('facebook')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="twitter"><i class="fab fa-twitter text-info mr-1"></i> Twitter/X</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fab fa-twitter"></i></span>
                                                </div>
                                                <input type="url" class="form-control @error('twitter') is-invalid @enderror" id="twitter" name="twitter" value="{{ old('twitter') }}" placeholder="https://twitter.com/...">
                                            </div>
                                            @error('twitter')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="instagram"><i class="fab fa-instagram text-danger mr-1"></i> Instagram</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fab fa-instagram"></i></span>
                                                </div>
                                                <input type="url" class="form-control @error('instagram') is-invalid @enderror" id="instagram" name="instagram" value="{{ old('instagram') }}" placeholder="https://instagram.com/...">
                                            </div>
                                            @error('instagram')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="linkedin"><i class="fab fa-linkedin-in text-primary mr-1"></i> LinkedIn</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fab fa-linkedin-in"></i></span>
                                                </div>
                                                <input type="url" class="form-control @error('linkedin') is-invalid @enderror" id="linkedin" name="linkedin" value="{{ old('linkedin') }}" placeholder="https://linkedin.com/in/...">
                                            </div>
                                            @error('linkedin')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="website"><i class="fas fa-globe text-success mr-1"></i> Kişisel Web Sitesi</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-globe"></i></span>
                                                </div>
                                                <input type="url" class="form-control @error('website') is-invalid @enderror" id="website" name="website" value="{{ old('website') }}" placeholder="https://...">
                                            </div>
                                            @error('website')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email"><i class="fas fa-envelope text-warning mr-1"></i> E-posta Adresi</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                </div>
                                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
                                            </div>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Ayarlar Tab -->
                            <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="settings-tab">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="order">Sıralama</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-sort-numeric-down"></i></span>
                                                </div>
                                                <input type="number" class="form-control @error('order') is-invalid @enderror" id="order" name="order" value="{{ old('order', 0) }}">
                                            </div>
                                            <small class="form-text text-muted">Düşük değerler önce gösterilir</small>
                                            @error('order')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="d-block">Durum</label>
                                            <div class="custom-control custom-switch custom-switch-lg mt-2">
                                                <input type="checkbox" class="custom-control-input" id="status" name="status" {{ old('status', 1) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="status">Aktif</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="d-block">Detay Sayfası</label>
                                            <div class="custom-control custom-switch custom-switch-lg mt-2">
                                                <input type="checkbox" class="custom-control-input" id="show_detail" name="show_detail" {{ old('show_detail', 1) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="show_detail">Tıklanabilir (Detay sayfası göster)</label>
                                            </div>
                                            <small class="form-text text-muted">Kapalı olursa üye kartına tıklandığında detay sayfası açılmaz</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="alert alert-info mt-3">
                                    <i class="fas fa-info-circle mr-1"></i> Bu bölümdeki ayarlar, üyenin listedeki görünürlüğünü ve sıralamasını etkiler.
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Kaydet
                        </button>
                        <a href="{{ route('admin.corporate.members.index', $selectedCategory) }}" class="btn btn-secondary ml-2">
                            <i class="fas fa-times mr-1"></i> İptal
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <!-- Profil Görseli Kartı -->
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-image mr-1"></i> Profil Görseli</h3>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <div id="image_preview" style="width: 200px; height: 200px; margin: 0 auto 15px; border-radius: 5px; border: 1px solid #ddd; display: flex; align-items: center; justify-content: center; overflow: hidden; background-color: #f8f9fa;">
                                <div class="text-center">
                                    <i class="fas fa-user fa-4x text-secondary mb-2"></i>
                                    <p class="text-muted">Görsel seçilmemiş</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center mb-3">
                            <button id="lfm_image" data-input="selected_image" data-preview="image_preview" class="btn btn-primary btn-block">
                                <i class="fas fa-image mr-1"></i> Görsel Seç
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Bilgi Kartı -->
                <div class="card card-outline card-info mt-3">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i> Bilgiler</h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped">
                            <tr>
                                <td><i class="fas fa-folder text-muted mr-1"></i> Kategori</td>
                                <td>
                                    @if($selectedCategory && isset($categories[$selectedCategory]))
                                        {{ $categories[$selectedCategory] }}
                                    @else
                                        Seçilmemiş
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-clock text-muted mr-1"></i> Oluşturulma</td>
                                <td>{{ date('d.m.Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <!-- Yardım Kartı -->
                <div class="card card-outline card-success mt-3">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-question-circle mr-1"></i> Yardım</h3>
                    </div>
                    <div class="card-body">
                        <ul class="pl-3">
                            <li class="mb-2">Üye bilgilerini ekledikten sonra <strong>Kaydet</strong> butonuna tıklayın.</li>
                            <li class="mb-2">Görsel seçmek için <strong>Görsel Seç</strong> butonunu kullanın.</li>
                            <li class="mb-2">Biyografi alanında zengin metin düzenleyicisi kullanabilirsiniz.</li>
                            <li class="mb-2">Tüm sosyal medya alanları isteğe bağlıdır.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css">
    <style>
        .custom-switch-lg .custom-control-label {
            padding-left: 10px;
            padding-top: 5px;
        }
        .custom-switch-lg .custom-control-label::before {
            width: 3rem;
            height: 1.5rem;
            border-radius: 1rem;
        }
        .custom-switch-lg .custom-control-label::after {
            width: calc(1.5rem - 4px);
            height: calc(1.5rem - 4px);
            border-radius: 50%;
        }
        .custom-switch-lg .custom-control-input:checked ~ .custom-control-label::after {
            transform: translateX(1.5rem);
        }
    </style>
@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/slugify@1.6.5/slugify.min.js"></script>
    <script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
    
    <!-- TinyMCE Editör -->
    <script src="{{ asset('js/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>
    
    <script>
        $(document).ready(function() {
            // Select2 initialization
            $('.select2').select2({
                theme: "bootstrap"
            });
            
            // FileManager butonunu başlat
            $('#lfm_image').filemanager('image', {prefix: '/admin/filemanager'});
            
            // Seçilen görsel değiştiğinde
            $('#selected_image').on('change', function() {
                var imagePath = $(this).val();
                
                // Görsel önizleme güncelleme
                var imagePreview = $('#image_preview');
                imagePreview.empty();
                
                if(imagePath) {
                    // URL kontrolü (http ile başlamıyorsa ve /storage ile başlamıyorsa)
                    if (!imagePath.startsWith('http') && !imagePath.startsWith('/storage') && !imagePath.startsWith('storage')) {
                        imagePath = '/storage/' + imagePath;
                    }
                    
                    var img = $('<img>').attr('src', imagePath).css({
                        'max-width': '100%',
                        'max-height': '100%'
                    });
                    imagePreview.append(img);
                } else {
                    imagePreview.html('<div class="text-center"><i class="fas fa-user fa-4x text-secondary mb-2"></i><p class="text-muted">Görsel seçilmemiş</p></div>');
                }
            });
            
            // Slug oluşturma
            $('#name').change(function() {
                if($('#slug').val() == '') {
                    $('#slug').val(slugify($(this).val(), {
                        lower: true,
                        locale: 'tr',
                        remove: /[*+~.()'"!:@]/g
                    }));
                }
            });
            
            // TinyMCE için düzenleyici ayarları
            tinymce.init({
                selector: '#description',
                plugins: 'preview importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons',
                menubar: 'file edit view insert format tools table help',
                toolbar: 'undo redo | bold italic underline strikethrough | fontfamily fontsize blocks | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media link anchor codesample | ltr rtl',
                toolbar_sticky: true,
                image_advtab: true,
                height: 500,
                quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
                noneditable_class: 'mceNonEditable',
                toolbar_mode: 'sliding',
                contextmenu: 'link image table',
                skin: 'oxide',
                content_css: 'default',
                content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 16px; }',
                language: 'tr',
                language_url: '/js/tinymce/langs/tr.js', // Türkçe dil dosyası
                relative_urls: false,
                remove_script_host: false,
                convert_urls: true,
                branding: false,
                promotion: false,
                file_picker_callback: function (callback, value, meta) {
                    // Dosya seçicisi için özel entegrasyon
                    if (meta.filetype === 'file' || meta.filetype === 'image') {
                        window.open('/filemanager/dialog.php?type=' + meta.filetype + '&field_id=tinymce-file', 'filemanager', 'width=900,height=600');
                        window.SetUrl = function (url, width, height, alt) {
                            callback(url, {alt: alt});
                        };
                    }
                }
            });
            
            // URL input alanlarında http:// ekle
            $('input[type="url"]').on('blur', function() {
                var url = $(this).val();
                if(url && url.trim() !== '' && !url.match(/^https?:\/\//)) {
                    $(this).val('https://' + url);
                }
            });
            
            // Sekme hatırlaması
            var hash = window.location.hash;
            if (hash) {
                $('#member-tabs a[href="' + hash + '"]').tab('show');
            }
            
            // Sekme URL'i
            $('#member-tabs a').on('click', function (e) {
                $(this).tab('show');
                var scrollmem = $('body').scrollTop();
                window.location.hash = this.hash;
                $('html,body').scrollTop(scrollmem);
            });
        });
    </script>
@stop 