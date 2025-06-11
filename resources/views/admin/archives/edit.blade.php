@extends('adminlte::page')

@section('title', 'Arşiv Düzenle')

@section('content_header')
    <h1>Arşiv Düzenle: {{ $archive->title }}</h1>
@stop

@section('content')
    <div class="row">
        <!-- Sol Kolon - Arşiv Bilgileri -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Arşiv Bilgileri</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.archives.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Geri Dön
                        </a>
                    </div>
                </div>
                
                <form method="POST" action="{{ route('admin.archives.update', $archive) }}">
                    @csrf
                    @method('PUT')
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

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                {{ session('success') }}
                            </div>
                        @endif

                        <!-- Başlık -->
                        <div class="form-group">
                            <label for="title">Başlık <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title', $archive->title) }}" required>
                            @error('title')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Özet -->
                        <div class="form-group">
                            <label for="excerpt">Kısa Açıklama</label>
                            <textarea class="form-control @error('excerpt') is-invalid @enderror" 
                                      id="excerpt" name="excerpt" rows="3" 
                                      placeholder="Arşiv hakkında kısa bir açıklama yazın...">{{ old('excerpt', $archive->excerpt) }}</textarea>
                            @error('excerpt')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- İçerik -->
                        <div class="form-group">
                            <label for="content">İçerik</label>
                            <textarea class="form-control tinymce @error('content') is-invalid @enderror" 
                                      id="content" name="content">{{ old('content', $archive->content) }}</textarea>
                            @error('content')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Durum -->
                        <div class="form-group">
                            <label for="status">Durum <span class="text-danger">*</span></label>
                            <select class="form-control @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                <option value="draft" {{ old('status', $archive->status) == 'draft' ? 'selected' : '' }}>Taslak</option>
                                <option value="published" {{ old('status', $archive->status) == 'published' ? 'selected' : '' }}>Yayında</option>
                                <option value="archived" {{ old('status', $archive->status) == 'archived' ? 'selected' : '' }}>Arşivlenmiş</option>
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
                                       {{ old('is_featured', $archive->is_featured) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_featured">
                                    Öne Çıkan Arşiv
                                </label>
                            </div>
                        </div>

                        <!-- Yayın Tarihi -->
                        <div class="form-group">
                            <label for="published_at">Yayın Tarihi</label>
                            <input type="datetime-local" class="form-control @error('published_at') is-invalid @enderror" 
                                   id="published_at" name="published_at" 
                                   value="{{ old('published_at', $archive->published_at ? $archive->published_at->format('Y-m-d\TH:i') : '') }}">
                            @error('published_at')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Güncelle
                        </button>
                        <a href="{{ route('admin.archives.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> İptal
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sağ Kolon - Belgeler -->
        <div class="col-md-4">
            <!-- Belgeler -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Belgeler ({{ $archive->allDocuments->count() }})</h3>
                </div>
                <div class="card-body">
                    <!-- Belge Yükleme Formu -->
                    <form id="document-upload-form" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="document_name">Belge Adı <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="document_name" name="name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="document_description">Açıklama</label>
                            <textarea class="form-control" id="document_description" name="description" rows="2"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="document_file">Dosya <span class="text-danger">*</span></label>
                            <input type="file" class="form-control-file" id="document_file" name="file" required
                                   accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip,.rar">
                            <small class="form-text text-muted">
                                Desteklenen formatlar: PDF, Word, Excel, PowerPoint, TXT, ZIP, RAR (Max: 50MB)
                            </small>
                        </div>
                        
                        <div class="form-group">
                            <label for="document_sort_order">Sıra</label>
                            <input type="number" class="form-control" id="document_sort_order" name="sort_order" value="0" min="0">
                        </div>
                        
                        <button type="submit" class="btn btn-success btn-block">
                            <i class="fas fa-upload"></i> Belge Yükle
                        </button>
                    </form>

                    <hr>

                    <!-- Belgeler Listesi -->
                    <div id="documents-list">
                        @forelse($archive->allDocuments->sortBy('sort_order') as $document)
                            <div class="document-item border rounded p-2 mb-2" data-id="{{ $document->id }}">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">
                                            <i class="{{ $document->icon_class }}"></i>
                                            {{ $document->name }}
                                        </h6>
                                        @if($document->description)
                                            <p class="text-muted small mb-1">{{ $document->description }}</p>
                                        @endif
                                        <small class="text-muted">
                                            {{ $document->file_name }} ({{ $document->formatted_size }})
                                        </small>
                                    </div>
                                    <div class="btn-group-vertical btn-group-sm">
                                        <button type="button" class="btn btn-outline-primary btn-xs edit-document" 
                                                data-id="{{ $document->id }}" title="Düzenle">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-danger btn-xs delete-document" 
                                                data-id="{{ $document->id }}" title="Sil">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary btn-xs toggle-status" 
                                                data-id="{{ $document->id }}" title="Durum">
                                            <i class="fas fa-{{ $document->is_active ? 'eye' : 'eye-slash' }}"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-3" id="no-documents">
                                <i class="fas fa-folder-open fa-2x mb-2"></i>
                                <p>Henüz belge yüklenmemiş.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Belge Düzenleme Modal -->
    <div class="modal fade" id="editDocumentModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Belge Düzenle</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form id="edit-document-form">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <input type="hidden" id="edit_document_id">
                        
                        <div class="form-group">
                            <label for="edit_document_name">Belge Adı <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_document_name" name="name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_document_description">Açıklama</label>
                            <textarea class="form-control" id="edit_document_description" name="description" rows="2"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_document_sort_order">Sıra</label>
                            <input type="number" class="form-control" id="edit_document_sort_order" name="sort_order" min="0">
                        </div>
                        
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="edit_document_is_active" name="is_active" value="1">
                                <label class="custom-control-label" for="edit_document_is_active">Aktif</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">İptal</button>
                        <button type="submit" class="btn btn-primary">Güncelle</button>
                    </div>
                </form>
            </div>
        </div>
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

$(document).ready(function() {
    // Belge yükleme formu
    $('#document-upload-form').on('submit', function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        var submitBtn = $(this).find('button[type="submit"]');
        var originalText = submitBtn.html();
        
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Yükleniyor...');
        
        $.ajax({
            url: '{{ route("admin.archives.documents.store", $archive) }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    // Formu temizle
                    $('#document-upload-form')[0].reset();
                    
                    // Başarı mesajı
                    toastr.success(response.message);
                    
                    // Belge listesini güncelle
                    location.reload();
                }
            },
            error: function(xhr) {
                var errors = xhr.responseJSON.errors;
                if (errors) {
                    var errorMsg = '';
                    $.each(errors, function(key, value) {
                        errorMsg += value[0] + '\n';
                    });
                    toastr.error(errorMsg);
                } else {
                    toastr.error('Bir hata oluştu.');
                }
            },
            complete: function() {
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });
    
    // Belge düzenleme
    $(document).on('click', '.edit-document', function() {
        var documentId = $(this).data('id');
        var documentItem = $(this).closest('.document-item');
        
        // Modal'ı doldur (basit bir yaklaşım - gerçek uygulamada AJAX ile veri çek)
        $('#edit_document_id').val(documentId);
        $('#editDocumentModal').modal('show');
    });
    
    // Belge düzenleme formu
    $('#edit-document-form').on('submit', function(e) {
        e.preventDefault();
        
        var documentId = $('#edit_document_id').val();
        var formData = $(this).serialize();
        
        $.ajax({
            url: '/admin/archive-documents/' + documentId,
            type: 'PUT',
            data: formData,
            success: function(response) {
                if (response.success) {
                    $('#editDocumentModal').modal('hide');
                    toastr.success(response.message);
                    location.reload();
                }
            },
            error: function(xhr) {
                toastr.error('Bir hata oluştu.');
            }
        });
    });
    
    // Belge silme
    $(document).on('click', '.delete-document', function() {
        if (!confirm('Bu belgeyi silmek istediğinizden emin misiniz?')) {
            return;
        }
        
        var documentId = $(this).data('id');
        var documentItem = $(this).closest('.document-item');
        
        $.ajax({
            url: '/admin/archive-documents/' + documentId,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    documentItem.fadeOut(function() {
                        $(this).remove();
                        
                        // Eğer hiç belge kalmadıysa "belge yok" mesajını göster
                        if ($('.document-item').length === 0) {
                            $('#documents-list').html(`
                                <div class="text-center text-muted py-3" id="no-documents">
                                    <i class="fas fa-folder-open fa-2x mb-2"></i>
                                    <p>Henüz belge yüklenmemiş.</p>
                                </div>
                            `);
                        }
                    });
                    toastr.success(response.message);
                }
            },
            error: function(xhr) {
                toastr.error('Bir hata oluştu.');
            }
        });
    });
    
    // Belge durumu değiştirme
    $(document).on('click', '.toggle-status', function() {
        var documentId = $(this).data('id');
        var button = $(this);
        
        $.ajax({
            url: '/admin/archive-documents/' + documentId + '/toggle-status',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    var icon = button.find('i');
                    if (response.is_active) {
                        icon.removeClass('fa-eye-slash').addClass('fa-eye');
                    } else {
                        icon.removeClass('fa-eye').addClass('fa-eye-slash');
                    }
                    toastr.success(response.message);
                }
            },
            error: function(xhr) {
                toastr.error('Bir hata oluştu.');
            }
        });
    });
});
</script>
@stop 