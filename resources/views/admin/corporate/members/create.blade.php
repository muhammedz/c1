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
    <form action="{{ route('admin.corporate.members.store', $selectedCategory ?: 1) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="corporate_category_id" value="{{ $selectedCategory ?: old('corporate_category_id') }}">
        
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
                                            <label for="twitter"><span class="text-white font-bold" style="font-family: serif; font-size: 14px;">𝕏</span> Twitter/X</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><span class="text-white font-bold" style="font-family: serif; font-size: 14px;">𝕏</span></span>
                                                </div>
                                                <input type="url" class="form-control @error('twitter') is-invalid @enderror" id="twitter" name="twitter" value="{{ old('twitter') }}" placeholder="https://x.com/...">
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
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="d-block">Manuel Link</label>
                                            <div class="custom-control custom-switch custom-switch-lg mt-2">
                                                <input type="checkbox" class="custom-control-input" id="use_custom_link" name="use_custom_link" {{ old('use_custom_link') ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="use_custom_link">Özel link kullan</label>
                                            </div>
                                            <small class="form-text text-muted">Aktif olursa varsayılan detay sayfası yerine custom link açılır</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row" id="custom_link_row" style="{{ old('use_custom_link') ? '' : 'display: none;' }}">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="custom_link">Özel Link URL</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-external-link-alt"></i></span>
                                                </div>
                                                <input type="url" class="form-control @error('custom_link') is-invalid @enderror" id="custom_link" name="custom_link" value="{{ old('custom_link') }}" placeholder="https://...">
                                            </div>
                                            <small class="form-text text-muted">Üye kartına tıklandığında açılacak link. Örn: https://example.com/detay-sayfa</small>
                                            @error('custom_link')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
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
                                <div class="text-center" id="default_image">
                                    <i class="fas fa-user fa-4x text-secondary mb-2"></i>
                                    <p class="text-muted">Görsel seçilmemiş</p>
                                </div>
                            </div>
                        </div>

                        <!-- Direct File Upload -->
                        <div class="form-group">
                            <label for="profile_image">Profil Görseli</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('profile_image') is-invalid @enderror" id="profile_image" name="profile_image" accept="image/*">
                                    <label class="custom-file-label" for="profile_image">Görsel Seç...</label>
                                </div>
                            </div>
                            <small class="form-text text-muted">JPG, PNG, GIF formatları desteklenir. Maksimum 2MB.</small>
                            @error('profile_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                            <li class="mb-2">Profil görseli için <strong>Görsel Seç</strong> butonunu kullanın.</li>
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
    <script src="{{ asset('js/slug-helper.js') }}"></script>
    
    <!-- TinyMCE Editör -->
    <script src="{{ asset('js/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>
    
    <script>
        $(document).ready(function() {
            // Select2 initialization
            $('.select2').select2({
                theme: "bootstrap"
            });
            
            // Slug oluşturma - Yeni SlugHelper kullanımı
            SlugHelper.autoSlug('#name', '#slug');
            
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
                images_upload_handler: function (blobInfo, success, failure) {
                    // Base64 olarak resim verisini döndürür
                    success('data:' + blobInfo.blob().type + ';base64,' + blobInfo.base64());
                },
                file_picker_callback: function(callback, value, meta) {
                    // Varsayılan dosya seçici penceresi kullanılır
                    var input = document.createElement('input');
                    input.setAttribute('type', 'file');
                    
                    if (meta.filetype === 'image') {
                        input.setAttribute('accept', 'image/*');
                    }
                    
                    input.onchange = function() {
                        var file = this.files[0];
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            callback(e.target.result, {
                                alt: file.name
                            });
                        };
                        reader.readAsDataURL(file);
                    };
                    
                    input.click();
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
            
            // Custom link toggle işlevi
            $('#use_custom_link').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#custom_link_row').show();
                } else {
                    $('#custom_link_row').hide();
                    $('#custom_link').val('');
                }
            });

            // File input preview sistemi
            $('#profile_image').on('change', function() {
                const file = this.files[0];
                const preview = $('#image_preview');
                
                if (file) {
                    // Dosya türü kontrolü
                    if (!file.type.startsWith('image/')) {
                        alert('Lütfen sadece görsel dosyaları seçin.');
                        $(this).val('');
                        return;
                    }
                    
                    // Dosya boyutu kontrolü (2MB)
                    if (file.size > 2 * 1024 * 1024) {
                        alert('Görsel dosyası 2MB\'den küçük olmalıdır.');
                        $(this).val('');
                        return;
                    }
                    
                    // Dosya adını göster
                    $('.custom-file-label').text(file.name);
                    
                    // Önizleme göster
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.html('<img src="' + e.target.result + '" style="max-width: 100%; max-height: 100%; border-radius: 5px;">');
                    };
                    reader.readAsDataURL(file);
                } else {
                    // Dosya seçilmemişse
                    $('.custom-file-label').text('Görsel Seç...');
                    preview.html('<div class="text-center" id="default_image"><i class="fas fa-user fa-4x text-secondary mb-2"></i><p class="text-muted">Görsel seçilmemiş</p></div>');
                }
            });
        });
    </script>
@stop 