@extends('adminlte::page')

@section('title', 'Kurumsal Kadro Üyesi Düzenle')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-user-edit text-primary mr-2"></i> {{ $member->name }}</h1>
        <div>
            <a href="{{ route('admin.corporate.members.index', ['category' => $member->corporate_category_id]) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left mr-1"></i> Listeye Dön
            </a>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <form action="{{ route('admin.corporate.members.update', $member->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <input type="hidden" name="corporate_category_id" value="{{ $member->corporate_category_id }}">
        
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
                                    <label for="name">Ad Soyad <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        </div>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $member->name) }}" required>
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
                                        <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $member->slug) }}">
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
                                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $member->title) }}">
                                    </div>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="short_description">Kısa Açıklama</label>
                                    <textarea class="form-control @error('short_description') is-invalid @enderror" id="short_description" name="short_description" rows="2">{{ old('short_description', $member->short_description) }}</textarea>
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
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="10">{{ old('description', $member->description) }}</textarea>
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
                                                <input type="url" class="form-control @error('facebook') is-invalid @enderror" id="facebook" name="facebook" value="{{ old('facebook', $member->facebook) }}" placeholder="https://facebook.com/...">
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
                                                <input type="url" class="form-control @error('twitter') is-invalid @enderror" id="twitter" name="twitter" value="{{ old('twitter', $member->twitter) }}" placeholder="https://twitter.com/...">
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
                                                <input type="url" class="form-control @error('instagram') is-invalid @enderror" id="instagram" name="instagram" value="{{ old('instagram', $member->instagram) }}" placeholder="https://instagram.com/...">
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
                                                <input type="url" class="form-control @error('linkedin') is-invalid @enderror" id="linkedin" name="linkedin" value="{{ old('linkedin', $member->linkedin) }}" placeholder="https://linkedin.com/in/...">
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
                                                <input type="url" class="form-control @error('website') is-invalid @enderror" id="website" name="website" value="{{ old('website', $member->website) }}" placeholder="https://...">
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
                                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $member->email) }}">
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
                                                <input type="number" class="form-control @error('order') is-invalid @enderror" id="order" name="order" value="{{ old('order', $member->order) }}">
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
                                                <input type="checkbox" class="custom-control-input" id="status" name="status" {{ old('status', $member->status) ? 'checked' : '' }}>
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
                                                <input type="checkbox" class="custom-control-input" id="show_detail" name="show_detail" {{ old('show_detail', $member->show_detail ?? true) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="show_detail">Tıklanabilir (Detay sayfası göster)</label>
                                            </div>
                                            <small class="form-text text-muted">Kapalı olursa üye kartına tıklandığında detay sayfası açılmaz</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="d-block">Manuel Link</label>
                                            <div class="custom-control custom-switch custom-switch-lg mt-2">
                                                <input type="checkbox" class="custom-control-input" id="use_custom_link" name="use_custom_link" {{ old('use_custom_link', $member->use_custom_link) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="use_custom_link">Özel link kullan</label>
                                            </div>
                                            <small class="form-text text-muted">Aktif olursa varsayılan detay sayfası yerine custom link açılır</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row" id="custom_link_row" style="{{ old('use_custom_link', $member->use_custom_link) ? '' : 'display: none;' }}">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="custom_link">Özel Link URL</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-external-link-alt"></i></span>
                                                </div>
                                                <input type="url" class="form-control @error('custom_link') is-invalid @enderror" id="custom_link" name="custom_link" value="{{ old('custom_link', $member->custom_link) }}" placeholder="https://...">
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
                            <i class="fas fa-save mr-1"></i> Değişiklikleri Kaydet
                        </button>
                        <a href="{{ route('admin.corporate.members.index', ['category' => $member->corporate_category_id]) }}" class="btn btn-secondary ml-2">
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
                                @if($member->filemanagersystem_image)
                                    <img src="{{ asset($member->filemanagersystem_image_url ?? $member->filemanagersystem_image) }}" style="max-width: 100%; max-height: 100%;">
                                @elseif($member->image)
                                    @php
                                        $imagePath = $member->image;
                                        if (!Str::startsWith($imagePath, ['http://', 'https://']) && !Str::startsWith($imagePath, '/storage/')) {
                                            $imagePath = '/storage/' . $imagePath;
                                        }
                                    @endphp
                                    <img src="{{ $imagePath }}" style="max-width: 100%; max-height: 100%;">
                                @else
                                    <div class="text-center">
                                        <i class="fas fa-user fa-4x text-secondary mb-2"></i>
                                        <p class="text-muted">Görsel seçilmemiş</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- FileManagerSystem Görsel -->
                        <div class="form-group">
                            <label for="filemanagersystem_image">Profil Görseli</label>
                            <div class="input-group">
                                <input type="hidden" id="filemanagersystem_image" name="filemanagersystem_image" value="{{ old('filemanagersystem_image', $member->filemanagersystem_image) }}">
                                <input type="text" class="form-control" id="filemanagersystem_image_display" value="{{ asset($member->filemanagersystem_image_url ?? $member->filemanagersystem_image) }}" readonly>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-primary" id="filemanagersystem_image_button">
                                        <i class="fas fa-image"></i> Görsel Seç
                                    </button>
                                </div>
                            </div>
                            <div id="filemanagersystem_image_preview" class="mt-2" style="{{ $member->filemanagersystem_image ? '' : 'display: none;' }}">
                                <img src="{{ asset($member->filemanagersystem_image_url ?? $member->filemanagersystem_image) }}" alt="Önizleme" class="img-thumbnail" style="max-height: 200px;">
                            </div>
                            @error('filemanagersystem_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="filemanagersystem_image_alt">Görsel Alt Metni</label>
                                    <input type="text" class="form-control @error('filemanagersystem_image_alt') is-invalid @enderror" id="filemanagersystem_image_alt" name="filemanagersystem_image_alt" value="{{ old('filemanagersystem_image_alt', $member->filemanagersystem_image_alt) }}">
                                    <small class="text-muted">Görsel yüklenemediğinde gösterilecek metin.</small>
                                    @error('filemanagersystem_image_alt')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="filemanagersystem_image_title">Görsel Başlığı</label>
                                    <input type="text" class="form-control @error('filemanagersystem_image_title') is-invalid @enderror" id="filemanagersystem_image_title" name="filemanagersystem_image_title" value="{{ old('filemanagersystem_image_title', $member->filemanagersystem_image_title) }}">
                                    <small class="text-muted">Görsel üzerine gelindiğinde gösterilecek metin.</small>
                                    @error('filemanagersystem_image_title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Bilgi Kartı -->
                <div class="card card-outline card-info mt-3">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i> Üye Bilgileri</h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped">
                            <tr>
                                <td><i class="fas fa-calendar-alt text-muted mr-1"></i> Oluşturulma</td>
                                <td>{{ $member->created_at->format('d.m.Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-edit text-muted mr-1"></i> Son Güncelleme</td>
                                <td>{{ $member->updated_at->format('d.m.Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-tag text-muted mr-1"></i> Kategori</td>
                                <td>{{ $member->category->name ?? 'Belirsiz' }}</td>
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
                            <li class="mb-2">Üye bilgilerini düzenledikten sonra <strong>Değişiklikleri Kaydet</strong> butonuna tıklayın.</li>
                            <li class="mb-2">Profil görseli için <strong>Görsel Seç</strong> butonunu kullanın.</li>
                            <li class="mb-2">Biyografi alanında zengin metin düzenleyicisi kullanabilirsiniz.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- MediaPicker Modal -->
<div class="modal fade" id="mediapickerModal" tabindex="-1" role="dialog" aria-labelledby="mediapickerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document" style="max-width: 90%;">
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
@stop

@section('css')
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
    <script src="{{ asset('js/slug-helper.js') }}"></script>
    
    <!-- TinyMCE Editör -->
    <script src="{{ asset('js/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>
    
    <script>
        $(document).ready(function() {
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
            
            // FileManagerSystem entegrasyonu
            $('#filemanagersystem_image_button').on('click', function() {
                try {
                    const input = $('#filemanagersystem_image');
                    const displayInput = $('#filemanagersystem_image_display');
                    const preview = $('#filemanagersystem_image_preview');
                    const previewImg = preview.find('img');
                    
                    console.log('🔍 MediaPicker açılıyor...');
                    
                    // Önemli: MediaPicker özel parametreleri
                    const memberId = {{ $member->id }};
                    const relatedType = 'corporate_member';
                    
                    // MediaPicker URL - ID ve tip parametreleri ile
                    const mediapickerUrl = '/admin/filemanagersystem/mediapicker?type=image&related_type=' + 
                        encodeURIComponent(relatedType) + '&related_id=' + encodeURIComponent(memberId);
                    
                    console.log('🔍 MediaPicker URL:', mediapickerUrl);
                    
                    // Modal açma ve iframe yükleme
                    $('#mediapickerModal').modal('show');
                    $('#mediapickerFrame').attr('src', mediapickerUrl);
                    
                    // Önceki mesaj dinleyiciyi temizleme
                    window.removeEventListener('message', handleMediaPickerMessage);
                    
                    // Medya seçici mesaj dinleme fonksiyonu
                    function handleMediaPickerMessage(event) {
                        try {
                            console.log('🔍 Medya mesajı alındı:');
                            console.log('- Kaynak origin:', event.origin);
                            console.log('- Ham veri:', event.data);
                            
                            // Boş mesaj kontrolü
                            if (!event.data) {
                                console.log('❌ Boş mesaj - işlenmedi');
                                return;
                            }
                            
                            // Medya mesajı türüne göre işlem
                            if (event.data.type === 'mediaSelected') {
                                console.log('✅ Medya seçildi:', event.data);
                                
                                // Media ID veya URL kontrolü
                                let mediaValue = '';
                                let previewUrl = '';
                                
                                // Önce mediaUrl kontrolü yap, bu en doğrudan yol
                                if (event.data.mediaUrl) {
                                    console.log('🔗 Medya URL alındı:', event.data.mediaUrl);
                                    mediaValue = event.data.mediaUrl.trim();
                                    previewUrl = mediaValue;
                                    processMedia(true);
                                } 
                                // MediaID ile işlem yap
                                else if (event.data.mediaId) {
                                    console.log('📋 Medya ID alındı:', event.data.mediaId);
                                    const mediaId = event.data.mediaId;
                                    
                                    // MediaID'den gerçek dosya yolunu almak için AJAX isteği
                                    // ancak hızlı çalışması için önce aşağıdaki kontrolü yapalım
                                    
                                    // Eğer mediaRealPath varsa onu kullanalım (en doğrudan yol)
                                    if (event.data.mediaRealPath) {
                                        mediaValue = event.data.mediaRealPath;
                                        previewUrl = event.data.mediaRealPath;
                                        processMedia(true);
                                    } 
                                    // Dosya adı varsa dosya adını kullanıyoruz
                                    else if (event.data.mediaFileName) {
                                        mediaValue = '/uploads/images/' + event.data.mediaFileName;
                                        previewUrl = '/uploads/images/' + event.data.mediaFileName;
                                        processMedia(true);
                                    } 
                                    // Hiçbiri yoksa AJAX isteği yapalım
                                    else {
                                        // AJAX ile sorgulamaya çalış
                                        $.ajax({
                                            url: '/admin/filemanagersystem/media/get-file-path/' + mediaId,
                                            type: 'GET',
                                            success: function(response) {
                                                if (response.success && response.file_path) {
                                                    mediaValue = response.file_path;
                                                    previewUrl = response.file_path;
                                                    processMedia(true);
                                                } else {
                                                    // AJAX başarılı oldu ama dosya yolu alınamadı
                                                    console.error('❌ AJAX ile dosya yolu alınamadı');
                                                    
                                                    // ID'yi olduğu gibi kullan
                                                    mediaValue = mediaId;
                                                    previewUrl = '/uploads/images/media-' + mediaId + '.webp';
                                                    processMedia(true);
                                                }
                                            },
                                            error: function(xhr, status, error) {
                                                console.error('❌ AJAX hatası:', status, error);
                                                
                                                // ID'yi olduğu gibi kullan
                                                mediaValue = mediaId;
                                                previewUrl = '/uploads/images/media-' + mediaId + '.webp';
                                                processMedia(true);
                                            }
                                        });
                                    }
                                } 
                                // Son olarak, hiçbir bilgi yok mu kontrol et
                                else {
                                    console.error('❌ Medya bilgisi bulunamadı');
                                    alert('Medya bilgisi alınamadı. Lütfen tekrar deneyin.');
                                }
                                
                                // Değerleri işleyip DOM'a uygulayan fonksiyon
                                function processMedia(skipError) {
                                    // Medya değeri yoksa işlem yapma
                                    if (!mediaValue) {
                                        if (!skipError) {
                                            console.error('❌ Medya değeri alınamadı');
                                            alert('Medya bilgisi alınamadı. Lütfen tekrar deneyin.');
                                        }
                                        return;
                                    }
                                    
                                    // URL string değilse ya da göreceli bir URL ise, tam URL haline getir
                                    let fullUrl = previewUrl;
                                    if (fullUrl && !fullUrl.startsWith('http')) {
                                        // Göreceli URL'yi tam URL'ye çevir
                                        const baseUrl = window.location.origin;
                                        if (!fullUrl.startsWith('/')) {
                                            fullUrl = '/' + fullUrl;
                                        }
                                        fullUrl = baseUrl + fullUrl;
                                    }
                                    
                                    // Değerleri güncelle
                                    input.val(mediaValue);
                                    displayInput.val(fullUrl);
                                    
                                    // Önizleme göster
                                    previewImg.attr('src', fullUrl);
                                    preview.show();
                                    
                                    // Ana önizleme alanını da güncelle
                                    $('#image_preview').empty().html(`<img src="${fullUrl}" style="max-width: 100%; max-height: 100%;">`);
                                    
                                    console.log('✓ Medya değeri işlendi:', mediaValue);
                                    console.log('✓ Tam görsel URL:', fullUrl);
                                    
                                    // İşlem tamam, modalı kapat
                                    $('#mediapickerModal').modal('hide');
                                }
                            }
                            // Hata mesajı
                            else if (event.data.type === 'mediapickerError') {
                                console.error('❌ Medya seçici hatası:', event.data);
                                alert('Medya seçici hatası: ' + (event.data.message || 'Bilinmeyen hata'));
                                $('#mediapickerModal').modal('hide');
                            }
                            // Diğer mesaj türleri
                            else if (event.data.type === 'mediapickerLoaded') {
                                console.log('ℹ️ Medya seçici yüklendi');
                            } else {
                                console.log('ℹ️ Bilinmeyen medya mesajı:', event.data);
                            }
                        } catch (error) {
                            console.error('❌ Mesaj işleme hatası:', error.message);
                            alert('Medya işleme hatası: ' + error.message);
                        }
                    }
                    
                    // Mesaj dinleyiciyi ekle
                    window.addEventListener('message', handleMediaPickerMessage);
                    
                    // iframe yükleme olayları
                    $('#mediapickerFrame')
                        .off('load error') // Önceki olayları temizle
                        .on('load', function() {
                            console.log('🟢 Medya iframe yüklendi');
                        })
                        .on('error', function(error) {
                            console.error('🔴 Medya iframe yüklenme hatası:', error);
                            alert('Medya yüklenirken hata oluştu!');
                            $('#mediapickerModal').modal('hide');
                        });
                    
                    // Modal kapatıldığında temizlik
                    $('#mediapickerModal').off('hidden.bs.modal').on('hidden.bs.modal', function() {
                        $('#mediapickerFrame').attr('src', 'about:blank');
                        console.log('🔴 Medya seçici kapatıldı');
                    });
                } catch (error) {
                    console.error('❌ Medya seçici hatası:', error.message);
                    alert('Medya seçici hatası: ' + error.message);
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
        });
    </script>
@stop 