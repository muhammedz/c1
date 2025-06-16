@extends('adminlte::page')

@section('title', 'Yeni Rehber Yeri')

@section('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css" rel="stylesheet">
<style>
    .content-wrapper {
        background-color: #f4f6f9;
    }
    
    .form-label {
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #2c3e50;
    }
    
    .card {
        border: none;
        box-shadow: 0 0 20px rgba(0,0,0,.08);
        margin-bottom: 1.5rem;
    }
    
    .card-header {
        background-color: #fff;
        border-bottom: 1px solid #edf2f7;
        padding: 1rem 1.25rem;
    }
    
    .card-header h5 {
        margin: 0;
        font-size: 1rem;
        font-weight: 600;
        color: #2c3e50;
    }
    
    .card-header i {
        color: #3490dc;
        margin-right: 0.5rem;
    }
    
    .card-body {
        padding: 1.5rem;
    }
    
    .form-control, .form-select {
        border-color: #e2e8f0;
        padding: 0.6rem 1rem;
        border-radius: 0.5rem;
        transition: all 0.3s ease;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #3490dc;
        box-shadow: 0 0 0 0.2rem rgba(52,144,220,.25);
    }
    
    .btn-primary {
        background-color: #3490dc;
        border-color: #3490dc;
        padding: 0.6rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 600;
    }
    
    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
        padding: 0.6rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 600;
    }
    
    .dropzone {
        border: 2px dashed #3490dc;
        border-radius: 8px;
        background: #f8fafc;
        min-height: 150px;
        padding: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .dropzone:hover {
        border-color: #2779bd;
        background: #f1f7fe;
    }
    
    .gallery-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 15px;
        margin-top: 1rem;
    }
    
    .gallery-item {
        position: relative;
        aspect-ratio: 1;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0,0,0,.1);
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
    }
    
    .gallery-item img {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important;
        max-height: none !important;
    }
    
    .gallery-item .remove-btn {
        position: absolute;
        top: 8px;
        right: 8px;
        background: rgba(255,255,255,.9);
        border-radius: 50%;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        border: none;
        color: #e3342f;
    }
    
    .gallery-item .remove-btn:hover {
        background: #fff;
        transform: scale(1.1);
    }
    
    .help-tooltip {
        color: #6c757d;
        cursor: help;
        margin-left: 0.5rem;
    }
    
    .help-tooltip:hover {
        color: #495057;
    }
    
    .required-field {
        color: #e3342f;
    }
    
    .form-text {
        color: #6c757d;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
    
    .alert {
        border-radius: 0.5rem;
        border: none;
        padding: 1rem 1.25rem;
    }
    
    .breadcrumb {
        background: transparent;
        padding: 0;
        margin-bottom: 1.5rem;
    }
    
    .breadcrumb-item a {
        color: #3490dc;
        text-decoration: none;
    }
    
    .breadcrumb-item.active {
        color: #6c757d;
    }
    
    .page-title {
        color: #2c3e50;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    
    .sticky-actions {
        position: sticky;
        top: 20px;
        z-index: 100;
    }
    
    .form-check-input:checked {
        background-color: #3490dc;
        border-color: #3490dc;
    }
    
    .input-group-text {
        background-color: #f8f9fa;
        border-color: #e2e8f0;
        color: #6c757d;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4">
    <h1 class="page-title">Yeni Rehber Yeri</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.guide-places.index') }}">Rehber Yerleri</a></li>
            <li class="breadcrumb-item active">Yeni Yer</li>
        </ol>
    </nav>
    
    <form action="{{ route('admin.guide-places.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="row">
            <div class="col-lg-8">
                <!-- Temel Bilgiler -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5><i class="fas fa-info-circle"></i> Temel Bilgiler</h5>
                    </div>
                    <div class="card-body">
                        @if(session('error'))
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                            </div>
                        @endif
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="title" class="form-label">
                                        Yer Başlığı <span class="required-field">*</span>
                                        <i class="fas fa-question-circle help-tooltip" title="Rehber yerinin görüntülenecek başlığı"></i>
                                    </label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title') }}" required
                                           placeholder="Örn: Ankara Büyükşehir Belediyesi Zabıta Müdürlüğü">
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="sort_order" class="form-label">
                                        Sıra Numarası
                                        <i class="fas fa-question-circle help-tooltip" title="Listeleme sırası (küçük sayı önce görünür)"></i>
                                    </label>
                                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                           id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        

                        
                        <div class="mb-3">
                            <label for="slug" class="form-label">
                                URL Slug
                                <i class="fas fa-question-circle help-tooltip" title="SEO dostu URL için kullanılır. Boş bırakılırsa otomatik oluşturulur"></i>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">{{ url('/') }}/rehber/</span>
                                <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                                       id="slug" name="slug" value="{{ old('slug') }}"
                                       placeholder="ankara-buyuksehir-zabita">
                            </div>
                            <div class="form-text">Boş bırakılırsa başlıktan otomatik oluşturulur</div>
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="content" class="form-label">
                                Detaylı İçerik
                                <i class="fas fa-question-circle help-tooltip" title="Yer hakkında detaylı bilgi, hizmetler, özellikler vb."></i>
                            </label>
                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                      id="content" name="content" rows="15">{{ old('content') }}</textarea>
                            <div class="form-text">Zengin metin editörü ile detaylı içerik oluşturabilirsiniz</div>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- İletişim Bilgileri -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5><i class="fas fa-address-book"></i> İletişim Bilgileri</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="address" class="form-label">
                                Adres
                                <i class="fas fa-question-circle help-tooltip" title="Tam adres bilgisi"></i>
                            </label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="3" 
                                      placeholder="Örn: Atatürk Bulvarı No:123 Çankaya/Ankara">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">
                                        Telefon
                                        <i class="fas fa-question-circle help-tooltip" title="İletişim telefon numarası"></i>
                                    </label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone') }}"
                                           placeholder="0312 123 45 67">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">
                                        E-posta
                                        <i class="fas fa-question-circle help-tooltip" title="İletişim e-posta adresi"></i>
                                    </label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}"
                                           placeholder="info@example.com">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="website" class="form-label">
                                Web Sitesi
                                <i class="fas fa-question-circle help-tooltip" title="Resmi web sitesi adresi"></i>
                            </label>
                            <input type="url" class="form-control @error('website') is-invalid @enderror" 
                                   id="website" name="website" value="{{ old('website') }}" 
                                   placeholder="https://www.example.com">
                            @error('website')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="working_hours" class="form-label">
                                Çalışma Saatleri
                                <i class="fas fa-question-circle help-tooltip" title="Hizmet verilen saatler"></i>
                            </label>
                            <textarea class="form-control @error('working_hours') is-invalid @enderror" 
                                      id="working_hours" name="working_hours" rows="2" 
                                      placeholder="Pazartesi-Cuma: 08:00-17:00&#10;Cumartesi: 09:00-13:00">{{ old('working_hours') }}</textarea>
                            @error('working_hours')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Konum Bilgileri -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5><i class="fas fa-map-marker-alt"></i> Konum Bilgileri</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="maps_link" class="form-label">
                                Google Maps Linki veya Kentrehberi linki
                                <i class="fas fa-question-circle help-tooltip" title="Google Maps'ten alınan paylaşım linki veya Kentrehberi linki"></i>
                            </label>
                            <input type="url" class="form-control @error('maps_link') is-invalid @enderror" 
                                   id="maps_link" name="maps_link" value="{{ old('maps_link') }}" 
                                   placeholder="https://maps.google.com/...">
                            <div class="form-text">Google Maps'te konumu bulup "Paylaş" butonundan linki kopyalayın veya Kentrehberi linkini ekleyin</div>
                            @error('maps_link')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        

                    </div>
                </div>
                
                <!-- Fotoğraf Galerisi -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5><i class="fas fa-images"></i> Fotoğraf Galerisi</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="images" class="form-label">
                                Fotoğraflar
                                <i class="fas fa-question-circle help-tooltip" title="Birden fazla fotoğraf seçebilirsiniz"></i>
                            </label>
                            <input type="file" class="form-control @error('images') is-invalid @enderror" 
                                   id="images" name="images[]" multiple accept="image/*">
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                JPG, PNG formatlarında birden fazla fotoğraf seçebilirsiniz. İlk fotoğraf vitrin fotoğrafı olarak kullanılacaktır.
                            </div>
                            @error('images')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div id="image-preview" class="gallery-container" style="display: none;">
                            <!-- Seçilen fotoğrafların önizlemesi burada görünecek -->
                        </div>
                    </div>
                </div>
                
                <!-- SEO Bilgileri -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5><i class="fas fa-search"></i> SEO Bilgileri</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="meta_title" class="form-label">
                                Meta Başlık
                                <i class="fas fa-question-circle help-tooltip" title="Arama motorlarında görünecek başlık"></i>
                            </label>
                            <input type="text" class="form-control @error('meta_title') is-invalid @enderror" 
                                   id="meta_title" name="meta_title" value="{{ old('meta_title') }}"
                                   maxlength="60" placeholder="Arama motorları için başlık">
                            <div class="form-text">Boş bırakılırsa sayfa başlığı kullanılır (Önerilen: 50-60 karakter)</div>
                            @error('meta_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="meta_description" class="form-label">
                                Meta Açıklama
                                <i class="fas fa-question-circle help-tooltip" title="Arama motorlarında görünecek açıklama"></i>
                            </label>
                            <textarea class="form-control @error('meta_description') is-invalid @enderror" 
                                      id="meta_description" name="meta_description" rows="3" 
                                      maxlength="160" placeholder="Arama motorları için açıklama">{{ old('meta_description') }}</textarea>
                            <div class="form-text">Önerilen: 150-160 karakter</div>
                            @error('meta_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="meta_keywords" class="form-label">
                                Anahtar Kelimeler
                                <i class="fas fa-question-circle help-tooltip" title="Virgülle ayrılmış anahtar kelimeler"></i>
                            </label>
                            <input type="text" class="form-control @error('meta_keywords') is-invalid @enderror" 
                                   id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords') }}"
                                   placeholder="zabıta, ankara, belediye, hizmet">
                            <div class="form-text">Virgülle ayırarak yazın (Örn: zabıta, ankara, belediye)</div>
                            @error('meta_keywords')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sağ Sidebar -->
            <div class="col-lg-4">
                <div class="sticky-actions">
                    <!-- Kategori Seçimi -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5><i class="fas fa-folder"></i> Kategori</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="guide_category_id" class="form-label">
                                    Kategori Seçin <span class="required-field">*</span>
                                </label>
                                <select class="form-select @error('guide_category_id') is-invalid @enderror" 
                                        id="guide_category_id" name="guide_category_id" required>
                                    <option value="">-- Kategori Seçin --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('guide_category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('guide_category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Bu yerin hangi kategoriye ait olduğunu belirtin
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Yayınlama Ayarları -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5><i class="fas fa-cog"></i> Yayınlama Ayarları</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                           {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        <strong>Aktif</strong>
                                        <div class="form-text">Bu yer sitede görüntülensin mi?</div>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Kaydet
                                </button>
                                <a href="{{ route('admin.guide-places.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-2"></i>İptal
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Yardım -->
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-question-circle"></i> Yardım</h5>
                        </div>
                        <div class="card-body">
                            <div class="small text-muted">
                                <p><strong>İpuçları:</strong></p>
                                <ul class="ps-3">
                                    <li>Başlık ve kategori zorunlu alanlardır</li>
                                    <li>İçerik editöründe resim ve medya ekleyebilirsiniz</li>
                                    <li>Google Maps linkini paylaş butonundan alabilirsiniz</li>
                                    <li>SEO bilgileri arama motorları için önemlidir</li>
                                    <li>İlk yüklenen fotoğraf vitrin fotoğrafı olur</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- MediaPicker Modal -->
<div class="modal fade" id="mediapickerModal" tabindex="-1" aria-labelledby="mediapickerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediapickerModalLabel">Medya Seç</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <iframe id="mediapickerFrame" src="" style="width: 100%; height: 600px; border: none;"></iframe>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{ asset('js/slug-helper.js') }}"></script>

<!-- TinyMCE Editör -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.4.2/tinymce.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // TinyMCE Editör
    tinymce.init({
        selector: '#content',
        plugins: 'preview importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons',
        menubar: 'file edit view insert format tools table help',
        toolbar: 'undo redo | bold italic underline strikethrough | fontfamily image fontsize blocks | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen preview save print | insertfile media template link anchor codesample | ltr rtl',
        toolbar_sticky: true,
        image_advtab: false,
        height: 500,
        quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
        quickbars_insert_toolbar: 'quickimage | quicktable quicklink hr',
        quickbars_insert_toolbar_hover: false,
        quickbars_image_toolbar: false,
        noneditable_class: 'mceNonEditable',
        language: 'tr',
        language_url: '/js/tinymce/langs/tr.js',
        toolbar_mode: 'sliding',
        contextmenu: 'link table',
        skin: 'oxide',
        content_css: 'default',
        content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 16px; }',
        relative_urls: false,
        remove_script_host: false,
        convert_urls: true,
        branding: false,
        promotion: false,
        paste_data_images: true,
        automatic_uploads: false,
        object_resizing: 'img',
        file_picker_types: 'file media',
        
        images_upload_handler: function (blobInfo, success, failure) {
            failure('Görsel yükleme devre dışı.');
        },
        
        image_title: false,
        image_description: false, 
        image_advtab: false,
        image_uploadtab: false,
        
        file_picker_callback: function (callback, value, meta) {
            if (meta.filetype === 'image') {
                const tempId = Date.now();
                const relatedType = 'guide_place_content';
                
                const mediapickerUrl = '/admin/filemanagersystem/mediapicker?type=image&filter=all&related_type=' + relatedType + '&related_id=' + tempId;
                
                $('#mediapickerFrame').attr('src', mediapickerUrl);
                
                var modal = new bootstrap.Modal(document.getElementById('mediapickerModal'));
                modal.show();
                
                function handleFilePickerSelection(event) {
                    try {
                        if (event.data && event.data.type === 'mediaSelected') {
                            let mediaUrl = '';
                            let altText = event.data.mediaAlt || '';
                            
                            if (event.data.mediaUrl) {
                                mediaUrl = event.data.mediaUrl;
                                
                                if (mediaUrl && mediaUrl.startsWith('/')) {
                                    const baseUrl = window.location.protocol + '//' + window.location.host;
                                    mediaUrl = baseUrl + mediaUrl;
                                }
                            } else if (event.data.mediaId) {
                                const previewUrl = '/admin/filemanagersystem/media/preview/' + event.data.mediaId;
                                mediaUrl = previewUrl;
                            }
                            
                            if (mediaUrl) {
                                callback(mediaUrl, { alt: altText });
                                modal.hide();
                                window.removeEventListener('message', handleFilePickerSelection);
                            }
                        } else if (event.data && event.data.type === 'mediapickerError') {
                            console.error('FileManagerSystem hatası:', event.data.message);
                            alert('Medya seçici hatası: ' + event.data.message);
                            modal.hide();
                            window.removeEventListener('message', handleFilePickerSelection);
                        }
                    } catch (error) {
                        console.error('Medya seçimi işlenirken hata oluştu:', error);
                        alert('Medya seçimi işlenirken bir hata oluştu.');
                        window.removeEventListener('message', handleFilePickerSelection);
                    }
                }
                
                window.removeEventListener('message', handleFilePickerSelection);
                window.addEventListener('message', handleFilePickerSelection);
                
                return false;
            }
            
            if (meta.filetype === 'file' || meta.filetype === 'media') {
                window.open('/filemanager/dialog.php?type=' + meta.filetype + '&field_id=tinymce-file', 'filemanager', 'width=900,height=600');
                window.SetUrl = function (url, width, height, alt) {
                    callback(url, {alt: alt});
                };
            }
        },
        
        setup: function (editor) {
            editor.on('PreInit', function() {
                editor.on('BeforeExecCommand', function(e) {
                    if (e.command === 'mceImage') {
                        openFileManagerSystemPicker(editor);
                        e.preventDefault();
                        return false;
                    }
                });
            });
            
            editor.on('init', function() {
                editor.ui.registry.addButton('customimage', {
                    icon: 'image',
                    tooltip: 'Resim Ekle/Düzenle',
                    onAction: function() {
                        openFileManagerSystemPicker(editor);
                    }
                });
                
                editor.ui.registry.addButton('quickimage', {
                    icon: 'image',
                    tooltip: 'Hızlı Resim Ekle',
                    onAction: function() {
                        openFileManagerSystemPicker(editor);
                    }
                });
            });
        }
    });
    
    function openFileManagerSystemPicker(editor) {
        const tempId = Date.now();
        const relatedType = 'guide_place_content';
        const mediapickerUrl = '/admin/filemanagersystem/mediapicker?type=image&filter=all&related_type=' + relatedType + '&related_id=' + tempId;
        
        $('#mediapickerFrame').attr('src', mediapickerUrl);
        var modal = new bootstrap.Modal(document.getElementById('mediapickerModal'));
        modal.show();
        
        function handleSelection(event) {
            try {
                if (event.data && event.data.type === 'mediaSelected') {
                    let mediaUrl = '';
                    let altText = event.data.mediaAlt || '';
                    
                    if (event.data.mediaUrl) {
                        mediaUrl = event.data.mediaUrl;
                        if (mediaUrl && mediaUrl.startsWith('/')) {
                            const baseUrl = window.location.protocol + '//' + window.location.host;
                            mediaUrl = baseUrl + mediaUrl;
                        }
                    } else if (event.data.mediaId) {
                        mediaUrl = '/admin/filemanagersystem/media/preview/' + event.data.mediaId;
                    }
                    
                    if (mediaUrl) {
                        editor.insertContent('<img src="' + mediaUrl + '" alt="' + altText + '" />');
                        modal.hide();
                        window.removeEventListener('message', handleSelection);
                    }
                }
            } catch (error) {
                console.error('Medya seçimi hatası:', error);
                window.removeEventListener('message', handleSelection);
            }
        }
        
        window.removeEventListener('message', handleSelection);
        window.addEventListener('message', handleSelection);
    }
    
    // Slug otomatik oluşturma
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');
    
    if (titleInput && slugInput) {
        titleInput.addEventListener('input', function() {
            if (!slugInput.value || slugInput.dataset.manual !== 'true') {
                slugInput.value = createSlug(this.value);
            }
        });
        
        slugInput.addEventListener('input', function() {
            this.dataset.manual = 'true';
        });
    }
    
    // Fotoğraf önizleme
    const imageInput = document.getElementById('images');
    const imagePreview = document.getElementById('image-preview');
    
    if (imageInput && imagePreview) {
        imageInput.addEventListener('change', function() {
            const files = this.files;
            imagePreview.innerHTML = '';
            
            if (files.length > 0) {
                imagePreview.style.display = 'grid';
                
                Array.from(files).forEach((file, index) => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const div = document.createElement('div');
                            div.className = 'gallery-item';
                            div.innerHTML = `
                                <img src="${e.target.result}" alt="Önizleme ${index + 1}">
                                ${index === 0 ? '<div class="badge bg-primary position-absolute top-0 start-0 m-2">Vitrin</div>' : ''}
                            `;
                            imagePreview.appendChild(div);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            } else {
                imagePreview.style.display = 'none';
            }
        });
    }
    
    // Karakter sayacı
    const metaTitleInput = document.getElementById('meta_title');
    const metaDescInput = document.getElementById('meta_description');
    
    if (metaTitleInput) {
        metaTitleInput.addEventListener('input', function() {
            const length = this.value.length;
            const maxLength = 60;
            const color = length > maxLength ? 'text-danger' : (length > 50 ? 'text-warning' : 'text-success');
            
            let counter = this.parentNode.querySelector('.char-counter');
            if (!counter) {
                counter = document.createElement('div');
                counter.className = 'char-counter form-text';
                this.parentNode.appendChild(counter);
            }
            counter.className = `char-counter form-text ${color}`;
            counter.textContent = `${length}/${maxLength} karakter`;
        });
    }
    
    if (metaDescInput) {
        metaDescInput.addEventListener('input', function() {
            const length = this.value.length;
            const maxLength = 160;
            const color = length > maxLength ? 'text-danger' : (length > 150 ? 'text-warning' : 'text-success');
            
            let counter = this.parentNode.querySelector('.char-counter');
            if (!counter) {
                counter = document.createElement('div');
                counter.className = 'char-counter form-text';
                this.parentNode.appendChild(counter);
            }
            counter.className = `char-counter form-text ${color}`;
            counter.textContent = `${length}/${maxLength} karakter`;
        });
    }
    
    // Tooltip'leri başlat
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// Slug oluşturma fonksiyonu
function createSlug(text) {
    const turkishMap = {
        'ç': 'c', 'ğ': 'g', 'ı': 'i', 'ö': 'o', 'ş': 's', 'ü': 'u',
        'Ç': 'c', 'Ğ': 'g', 'I': 'i', 'İ': 'i', 'Ö': 'o', 'Ş': 's', 'Ü': 'u'
    };
    
    return text
        .split('')
        .map(char => turkishMap[char] || char)
        .join('')
        .toLowerCase()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .replace(/^-|-$/g, '');
}
</script>
@endsection 