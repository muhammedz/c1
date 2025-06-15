@extends('adminlte::page')

@section('title', 'Yeni Arşiv Ekle')

@section('content_header')
    <h1>Yeni Arşiv Ekle</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Arşiv Bilgileri</h3>
            <div class="card-tools">
                <a href="{{ route('admin.archives.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Geri Dön
                </a>
            </div>
        </div>
        
        <form method="POST" action="{{ route('admin.archives.store') }}">
            @csrf
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

                <div class="row">
                    <div class="col-md-8">
                        <!-- Başlık -->
                        <div class="form-group">
                            <label for="title">Başlık <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Slug -->
                        <div class="form-group">
                            <label for="slug">URL Slug</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{ url('/') }}/archives/</span>
                                </div>
                                <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                                       id="slug" name="slug" value="{{ old('slug') }}">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary" id="generate-slug" title="Başlıktan otomatik oluştur">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </div>
                            </div>
                            <small class="form-text text-muted">
                                URL'de görünecek kısa isim. Boş bırakılırsa başlıktan otomatik oluşturulur.
                            </small>
                            @error('slug')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Özet -->
                        <div class="form-group">
                            <label for="excerpt">Kısa Açıklama</label>
                            <textarea class="form-control @error('excerpt') is-invalid @enderror" 
                                      id="excerpt" name="excerpt" rows="3" 
                                      placeholder="Arşiv hakkında kısa bir açıklama yazın...">{{ old('excerpt') }}</textarea>
                            @error('excerpt')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- İçerik -->
                        <div class="form-group">
                            <label for="content">İçerik</label>
                            <textarea class="form-control tinymce @error('content') is-invalid @enderror" 
                                      id="content" name="content">{{ old('content') }}</textarea>
                            @error('content')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <!-- Durum -->
                        <div class="form-group">
                            <label for="status">Durum <span class="text-danger">*</span></label>
                            <select class="form-control @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Taslak</option>
                                <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Yayında</option>
                                <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Arşivlenmiş</option>
                            </select>
                            @error('status')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Öne Çıkan -->
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" 
                                       id="is_featured" name="is_featured" value="1" 
                                       {{ old('is_featured') ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_featured">
                                    Öne Çıkan Arşiv
                                </label>
                            </div>
                            <small class="form-text text-muted">
                                Öne çıkan arşivler ana sayfada gösterilir.
                            </small>
                        </div>

                        <!-- Yayın Tarihi -->
                        <div class="form-group">
                            <label for="published_at">Yayın Tarihi</label>
                            <input type="datetime-local" class="form-control @error('published_at') is-invalid @enderror" 
                                   id="published_at" name="published_at" value="{{ old('published_at') }}">
                            @error('published_at')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">
                                Boş bırakılırsa yayınlandığında otomatik olarak ayarlanır.
                            </small>
                        </div>

                        <!-- Bilgi Kutusu -->
                        <div class="info-box bg-info">
                            <span class="info-box-icon"><i class="fas fa-info"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Bilgi</span>
                                <span class="info-box-number">
                                    Arşivi oluşturduktan sonra belgeler ekleyebilirsiniz.
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Kaydet
                </button>
                <a href="{{ route('admin.archives.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> İptal
                </a>
            </div>
        </form>
    </div>
@stop

@section('js')
<script>
// TinyMCE'yi dinamik olarak yükle
var script = document.createElement('script');
script.type = 'text/javascript';
script.src = '{{ asset("vendor/tinymce/tinymce/js/tinymce/tinymce.min.js") }}';
document.head.appendChild(script);

script.onload = function() {
    console.log('TinyMCE yüklendi');
    
    // Slug otomatik oluşturma
    function generateSlug(text) {
        return text
            .toLowerCase()
            .replace(/ğ/g, 'g')
            .replace(/ü/g, 'u')
            .replace(/ş/g, 's')
            .replace(/ı/g, 'i')
            .replace(/ö/g, 'o')
            .replace(/ç/g, 'c')
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .replace(/^-|-$/g, '');
    }

    // Başlık değiştiğinde slug'ı otomatik güncelle (sadece slug boşsa)
    $('#title').on('input', function() {
        var title = $(this).val();
        var currentSlug = $('#slug').val();
        
        // Eğer slug boşsa otomatik güncelle
        if (!currentSlug) {
            $('#slug').val(generateSlug(title));
        }
    });

    // Slug oluştur butonu
    $('#generate-slug').on('click', function() {
        var title = $('#title').val();
        if (title) {
            $('#slug').val(generateSlug(title));
            $(this).find('i').addClass('fa-spin');
            setTimeout(() => {
                $(this).find('i').removeClass('fa-spin');
            }, 500);
        } else {
            alert('Önce başlık alanını doldurun.');
        }
    });

    // Slug input'unu temizle
    $('#slug').on('input', function() {
        var slug = $(this).val();
        $(this).val(generateSlug(slug));
    });
    
    // TinyMCE Editör
    tinymce.init({
        selector: '.tinymce',
        plugins: 'preview importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons',
        menubar: 'file edit view insert format tools table help',
        toolbar: 'undo redo | bold italic underline strikethrough | fontfamily image fontsize blocks | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen preview save print | insertfile media template link anchor codesample | ltr rtl',
        toolbar_sticky: true,
        image_advtab: false,
        height: 400,
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
        images_upload_url: '{{ route("admin.tinymce.upload") }}',
        images_upload_credentials: true,
        branding: false,
        promotion: false,
        relative_urls: false,
        remove_script_host: false,
        convert_urls: false
    });
};
</script>
@stop 