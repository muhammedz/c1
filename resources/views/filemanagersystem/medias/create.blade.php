@extends('adminlte::page')

@section('title', 'Dosya Yönetim Sistemi - Dosya Yükle')

@section('content_header')
    <h1>Dosya Yükle</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.filemanagersystem.dashboard') }}">Ana Klasör</a></li>
                    
                    @if($folder)
                        @php
                        $breadcrumbs = [];
                        $parent = $folder;
                        
                        while($parent) {
                            $breadcrumbs[] = $parent;
                            $parent = $parent->parent;
                        }
                        
                        $breadcrumbs = array_reverse($breadcrumbs);
                        @endphp
                        
                        @foreach($breadcrumbs as $breadcrumb)
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.filemanagersystem.folders.index', ['parent_id' => $breadcrumb->id]) }}">
                                    {{ $breadcrumb->folder_name }}
                                </a>
                            </li>
                        @endforeach
                    @endif
                    
                    <li class="breadcrumb-item active" aria-current="page">Dosya Yükle</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ $folder ? route('admin.filemanagersystem.media.index', ['folder_id' => $folder->id]) : route('admin.filemanagersystem.media.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Geri Dön
            </a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-header">Hızlı Erişim</div>
                <div class="card-body">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.filemanagersystem.folders.index') }}">
                                <i class="fas fa-folder"></i> Ana Klasör
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.filemanagersystem.media.index') }}">
                                <i class="fas fa-file"></i> Tüm Dosyalar
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Resim Sıkıştırma Ayarları Kartı -->
            <div class="card mb-4" id="compressionSettingsCard">
                <div class="card-header">Resim Sıkıştırma Ayarları</div>
                <div class="card-body">
                    <p class="card-text small text-muted mb-3">
                        Bu ayarlar sadece yüklenen resim dosyaları için uygulanacaktır. Diğer dosya türleri etkilenmez.
                    </p>
                    
                    <div class="mb-3">
                        <label for="compression_quality" class="form-label">Sıkıştırma Kalitesi</label>
                        <select class="form-select" id="compression_quality" name="compression_quality">
                            <option value="none">Sıkıştırma Yapma</option>
                            <option value="low">Düşük (Daha Küçük Dosya)</option>
                            <option value="medium" selected>Orta</option>
                            <option value="high">Yüksek (Daha İyi Kalite)</option>
                        </select>
                        <div class="form-text">Resim kalitesini ve sıkıştırma oranını belirler.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="max_size" class="form-label">Maksimum Boyut</label>
                        <select class="form-select" id="max_size" name="max_size">
                            <option value="small">Küçük (1280x720)</option>
                            <option value="medium" selected>Orta (1920x1080)</option>
                            <option value="large">Büyük (2560x1440)</option>
                            <option value="original">Orijinal (Boyutu Koru)</option>
                        </select>
                        <div class="form-text">Bu boyuttan daha büyük resimler otomatik olarak küçültülecektir.</div>
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="keep_original" name="keep_original" value="1" checked>
                        <label class="form-check-label" for="keep_original">Orijinal formatı da koru</label>
                        <div class="form-text">Devre dışı bırakırsanız, sadece WebP formatı kaydedilir.</div>
                </div>
            </div>
        </div>
        
            <!-- Yükleme ayarları -->
            <div class="card mb-4">
                <div class="card-header">Yükleme Ayarları</div>
                <div class="card-body">
                        <div class="mb-3">
                            <label for="folder_id" class="form-label">Klasör</label>
                            <select class="form-select @error('folder_id') is-invalid @enderror" id="folder_id" name="folder_id">
                                <option value="">Ana Klasör</option>
                                @foreach($folders as $folderItem)
                                    <option value="{{ $folderItem->id }}" {{ (old('folder_id') ?? ($folder ? $folder->id : null)) == $folderItem->id ? 'selected' : '' }}>
                                        {{ $folderItem->folder_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('folder_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_public" name="is_public" value="1" {{ old('is_public') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_public">Herkese Açık</label>
                            <div class="form-text">Herkese açık dosyalar kimlik doğrulama olmadan da erişilebilir.</div>
                        </div>
                </div>
            </div>
                        </div>
                        
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Dosya Yükleme</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-sm btn-success" id="start-upload" disabled>
                            <i class="fas fa-upload"></i> Tüm Dosyaları Yükle
                        </button>
                        <button type="button" class="btn btn-sm btn-danger" id="clear-queue">
                            <i class="fas fa-trash"></i> Listeyi Temizle
                        </button>
                    </div>
                </div>
                
                <div class="card-body">
                    <form id="upload-form" action="{{ route('admin.filemanagersystem.media.store') }}" method="POST" enctype="multipart/form-data" class="dropzone">
                        @csrf
                        <div class="fallback">
                            <input name="files[]" type="file" multiple />
                        </div>
                        <div class="dz-message" data-dz-message>
                            <i class="fas fa-cloud-upload-alt fa-4x mb-3"></i>
                            <h4>Dosyaları buraya sürükleyip bırakın</h4>
                            <span>veya tıklayarak seçin</span>
                            <p class="text-muted mt-2">
                                <small>İzin verilen maksimum dosya boyutu: 50MB</small>
                            </p>
                        </div>
                    </form>
                    
                    <div class="mt-4">
                        <h5>Yükleme Listesi <span class="badge bg-primary" id="file-count">0</span></h5>
                        <div class="table-responsive" id="file-list-container">
                            <table class="table table-striped table-hover" id="file-list">
                                <thead>
                                    <tr>
                                        <th style="width: 40px">#</th>
                                        <th>Dosya Adı</th>
                                        <th style="width: 100px">Boyut</th>
                                        <th style="width: 100px">Tür</th>
                                        <th style="width: 120px">Durum</th>
                                        <th style="width: 100px">İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- JavaScript ile doldurulacak -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css">
<style>
    .dropzone {
        border: 2px dashed #0087F7;
        border-radius: 5px;
        background: #f8f9fa;
        min-height: 250px;
        padding: 20px;
        position: relative;
        text-align: center;
    }
    
    .dropzone .dz-message {
        padding: 40px 0;
        color: #666;
    }
    
    .dropzone .dz-preview {
        display: none; /* Dropzone önizlemesini gizle, kendi önizlememizi kullanacağız */
    }
    
    .upload-progress {
        height: 4px;
    }
    
    #file-list-container {
        max-height: 400px;
        overflow-y: auto;
    }
    
    .file-preview-image {
        max-width: 40px;
        max-height: 40px;
        object-fit: contain;
    }
    
    .file-preview {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        background-color: #f8f9fa;
        color: #6c757d;
        border-radius: 4px;
    }
</style>
@stop

@section('js')
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
<script>
    // File icons according to file type
    const fileIcons = {
        'image': 'fa-file-image',
        'audio': 'fa-file-audio',
        'video': 'fa-file-video',
        'pdf': 'fa-file-pdf',
        'word': 'fa-file-word',
        'excel': 'fa-file-excel',
        'powerpoint': 'fa-file-powerpoint',
        'archive': 'fa-file-archive',
        'code': 'fa-file-code',
        'text': 'fa-file-alt',
        'default': 'fa-file'
    };
    
    // Get file icon based on mime type
    function getFileIcon(mimeType) {
        if (mimeType.startsWith('image/')) return fileIcons.image;
        if (mimeType.startsWith('audio/')) return fileIcons.audio;
        if (mimeType.startsWith('video/')) return fileIcons.video;
        if (mimeType === 'application/pdf') return fileIcons.pdf;
        if (mimeType.includes('word') || mimeType.includes('msword')) return fileIcons.word;
        if (mimeType.includes('excel') || mimeType.includes('spreadsheetml')) return fileIcons.excel;
        if (mimeType.includes('powerpoint') || mimeType.includes('presentation')) return fileIcons.powerpoint;
        if (mimeType.includes('zip') || mimeType.includes('rar') || mimeType.includes('tar') || mimeType.includes('gzip')) return fileIcons.archive;
        if (mimeType.includes('html') || mimeType.includes('javascript') || mimeType.includes('xml') || mimeType.includes('json')) return fileIcons.code;
        if (mimeType.startsWith('text/')) return fileIcons.text;
        return fileIcons.default;
    }
    
    // Format file size
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    // Initialize Dropzone
    Dropzone.autoDiscover = false;
    
    $(document).ready(function() {
        let fileId = 0;
        let fileCount = 0;
        const fileList = {};
        
        // Initialize Dropzone
        const dropzone = new Dropzone("#upload-form", {
            url: "{{ route('admin.filemanagersystem.media.store') }}",
            paramName: "files",
            maxFilesize: 50, // MB
            parallelUploads: 2,
            uploadMultiple: true,
            autoProcessQueue: false,
            addRemoveLinks: false,
            acceptedFiles: null,
            timeout: 180000, // 3 minutes
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            init: function() {
                const dz = this;
                
                // When a file is added
                this.on("addedfile", function(file) {
                    // Check if file already exists in the list
                    if (fileList[file.name]) {
                        // Remove file from dropzone
                        dz.removeFile(file);
                        
                        // Show error message
                        toastr.warning(`Dosya "${file.name}" zaten yükleme listesinde.`);
                        return;
                    }
                    
                    // Add to our file list
                    fileId++;
                    fileCount++;
                    file.fileId = fileId;
                    fileList[file.name] = file;
                    
                    // Update the file count
                    $("#file-count").text(fileCount);
                    
                    // Create a preview row in our table
                    const row = $(`
                        <tr id="file-row-${file.fileId}" data-file-id="${file.fileId}">
                            <td>${file.fileId}</td>
                            <td class="file-name-cell">
                                <div class="d-flex align-items-center">
                                    <div class="file-preview mr-2"></div>
                                    <span>${file.name}</span>
                                </div>
                            </td>
                            <td>${formatFileSize(file.size)}</td>
                            <td>${file.type || 'Bilinmiyor'}</td>
                            <td>
                                <span class="badge bg-secondary status-badge">Bekliyor</span>
                                <div class="progress upload-progress mt-1 d-none">
                                    <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                                </div>
                            </td>
                            <td>
                                <button type="button" class="btn btn-xs btn-danger remove-file" data-file-id="${file.fileId}">
                                    <i class="fas fa-times"></i>
                                </button>
                            </td>
                        </tr>
                    `);
                    
                    // Add row to table
                    $("#file-list tbody").append(row);
                    
                    // Create file preview
                    const previewContainer = $(`#file-row-${file.fileId} .file-preview`);
                    
                    if (file.type && file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            previewContainer.html(`<img src="${e.target.result}" class="file-preview-image" alt="${file.name}">`);
                        }
                        reader.readAsDataURL(file);
                    } else {
                        const icon = getFileIcon(file.type);
                        previewContainer.html(`<i class="fas ${icon}"></i>`);
                    }
                    
                    // Enable start upload button if we have files
                    $("#start-upload").prop("disabled", false);
                    
                    // Check for image files and show settings if needed
                    updateCompressionSettingsVisibility();
                });
                
                // When upload progress updates
                this.on("uploadprogress", function(file, progress) {
                    const progressBar = $(`#file-row-${file.fileId} .progress-bar`);
                    const progressContainer = $(`#file-row-${file.fileId} .upload-progress`);
                    
                    // Show progress bar
                    progressContainer.removeClass('d-none');
                    
                    // Update progress
                    progressBar.css("width", Math.round(progress) + "%");
                });
                
                // When a file starts uploading
                this.on("sending", function(file) {
                    const statusBadge = $(`#file-row-${file.fileId} .status-badge`);
                    statusBadge.removeClass('bg-secondary').addClass('bg-info').text('Yükleniyor');
                    
                    // Add form parameters
                    const paramInputs = {
                        'folder_id': $('#folder_id').val(),
                        'is_public': $('#is_public').is(':checked') ? 1 : 0,
                    };
                    
                    // Add image compression parameters if needed
                    if (file.type && file.type.startsWith('image/')) {
                        paramInputs['compression_quality'] = $('#compression_quality').val();
                        paramInputs['max_size'] = $('#max_size').val();
                        paramInputs['keep_original'] = $('#keep_original').is(':checked') ? 1 : 0;
                    }
                    
                    // Set the parameters on the form
                    for (const [key, value] of Object.entries(paramInputs)) {
                        if (!dz.options.params) dz.options.params = {};
                        dz.options.params[key] = value;
                    }
                });
                
                // When a file is uploaded successfully
                this.on("success", function(file) {
                    const statusBadge = $(`#file-row-${file.fileId} .status-badge`);
                    statusBadge.removeClass('bg-info').addClass('bg-success').text('Tamamlandı');
                });
                
                // When there's an error
                this.on("error", function(file, errorMessage) {
                    const statusBadge = $(`#file-row-${file.fileId} .status-badge`);
                    statusBadge.removeClass('bg-info').addClass('bg-danger').text('Hata');
                    
                    // Show error message
                    console.error(`Dosya "${file.name}" yüklenirken hata: ${errorMessage}`);
                    toastr.error(`Dosya "${file.name}" yüklenirken hata oluştu.`);
                });
                
                // When all files have been processed
                this.on("queuecomplete", function() {
                    // Disable button and show complete message
                    $("#start-upload").prop("disabled", true).html('<i class="fas fa-check"></i> Tamamlandı');
                    
                    // Show success message
                    if (!this.getUploadingFiles().length && !this.getQueuedFiles().length) {
                        toastr.success('Tüm dosyalar başarıyla yüklendi.');
                        
                        // Redirect after 2 seconds
                        setTimeout(function() {
                            window.location.href = "{{ route('admin.filemanagersystem.media.index') }}";
                        }, 2000);
                    }
                });
            }
        });
        
        // Start upload button click
        $("#start-upload").on("click", function() {
            // Change status to Processing
            $(this).html('<i class="fas fa-spinner fa-spin"></i> Yükleniyor...').prop("disabled", true);
            
            // Process the queue
            dropzone.processQueue();
        });
        
        // Clear queue button click
        $("#clear-queue").on("click", function() {
            // Ask for confirmation
            if (!fileCount || confirm('Yükleme listesindeki tüm dosyaları silmek istediğinizden emin misiniz?')) {
                // Clear dropzone
                dropzone.removeAllFiles(true);
                
                // Clear file list
                $("#file-list tbody").empty();
                fileCount = 0;
                fileId = 0;
                Object.keys(fileList).forEach(key => delete fileList[key]);
                
                // Update the file count
                $("#file-count").text(fileCount);
                
                // Disable start upload button
                $("#start-upload").prop("disabled", true).html('<i class="fas fa-upload"></i> Tüm Dosyaları Yükle');
                
                // Update compression settings visibility
                updateCompressionSettingsVisibility();
            }
        });
        
        // Remove individual file
        $(document).on('click', '.remove-file', function() {
            const fileId = $(this).data('file-id');
            const row = $(`#file-row-${fileId}`);
            
            // Find the file in our list
            let fileToRemove = null;
            Object.values(fileList).forEach(file => {
                if (file.fileId === fileId) {
                    fileToRemove = file;
                }
            });
            
            if (fileToRemove) {
                // Remove file from dropzone
                dropzone.removeFile(fileToRemove);
                
                // Remove from our list
                delete fileList[fileToRemove.name];
                
                // Remove row from table
                row.remove();
                
                // Update counters
                fileCount--;
                $("#file-count").text(fileCount);
                
                // Disable start upload button if no files
                if (fileCount === 0) {
                    $("#start-upload").prop("disabled", true);
                }
                
                // Update compression settings visibility
                updateCompressionSettingsVisibility();
            }
        });
        
        // Function to check if there are image files in the queue
        function updateCompressionSettingsVisibility() {
            let hasImage = false;
            
            Object.values(fileList).forEach(file => {
                if (file.type && file.type.startsWith('image/')) {
                    hasImage = true;
                }
            });
            
            // Show/hide compression settings
            if (hasImage) {
                $('#compressionSettingsCard').show();
            } else {
                $('#compressionSettingsCard').hide();
            }
        }
        
        // Hide compression settings by default
        $('#compressionSettingsCard').hide();
    });
</script>
@stop 