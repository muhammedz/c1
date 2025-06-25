@extends('adminlte::page')

@section('title', 'Dosya Yönetim Sistemi')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Dosya Yönetim Sistemi</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Dosya Yönetim Sistemi</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <!-- Sol Panel - Klasör ve Kategori Ağacı -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Klasörler</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="nav flex-column nav-pills" id="folder-tree">
                        <!-- Tüm Dosyalar seçeneği -->
                        <div class="nav-item">
                            <a href="#" class="nav-link active" data-folder-id="">
                                <i class="fas fa-folder-open mr-2"></i>
                                Tüm Dosyalar
                            </a>
                        </div>
                        @foreach($folders as $folder)
                            @include('filemanagersystem.partials.folder-item', ['folder' => $folder])
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">Kategoriler</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="nav flex-column nav-pills" id="category-tree">
                        @foreach($categories as $category)
                            @include('filemanagersystem.partials.category-item', ['category' => $category])
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Sağ Panel - Dosya Listesi -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Dosyalar</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.filemanagersystem.media.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-upload"></i> Dosya Yükle
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Gelişmiş Arama ve Filtreleme -->
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <div class="input-group mb-2">
                                <input type="text" class="form-control" id="search-input" placeholder="Dosya ara...">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" id="search-button">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <select class="form-control form-control-sm" id="filter-type">
                                        <option value="">Tüm Dosya Tipleri</option>
                                        <option value="image">Resimler</option>
                                        <option value="document">Dökümanlar</option>
                                        <option value="video">Videolar</option>
                                        <option value="audio">Ses Dosyaları</option>
                                        <option value="archive">Arşivler</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-control form-control-sm" id="filter-date">
                                        <option value="">Tüm Tarihler</option>
                                        <option value="today">Bugün</option>
                                        <option value="yesterday">Dün</option>
                                        <option value="last_week">Son Hafta</option>
                                        <option value="last_month">Son Ay</option>
                                        <option value="last_year">Son Yıl</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-control form-control-sm" id="filter-size">
                                        <option value="">Tüm Boyutlar</option>
                                        <option value="tiny">Çok Küçük (<100KB)</option>
                                        <option value="small">Küçük (<1MB)</option>
                                        <option value="medium">Orta (1-10MB)</option>
                                        <option value="large">Büyük (10-100MB)</option>
                                        <option value="huge">Çok Büyük (>100MB)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-7">
                                    <select class="form-control form-control-sm" id="sort-by">
                                        <option value="newest">En Yeni</option>
                                        <option value="oldest">En Eski</option>
                                        <option value="name_asc">A-Z</option>
                                        <option value="name_desc">Z-A</option>
                                        <option value="size_asc">En Küçük Boyut</option>
                                        <option value="size_desc">En Büyük Boyut</option>
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <div class="btn-group float-right">
                                        <button type="button" class="btn btn-sm btn-default view-mode" data-mode="grid" title="Izgara Görünümü">
                                            <i class="fas fa-th-large"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-default view-mode" data-mode="list" title="Liste Görünümü">
                                            <i class="fas fa-list"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Dosya Listesi -->
                    <div class="row" id="file-list">
                        @foreach($recentFiles as $file)
                            <div class="col-md-3 col-sm-6">
                                <div class="card file-item">
                                    <div class="card-body text-center">
                                        @if($file->isImage())
                                            @if($file->has_webp)
                                                <picture>
                                                    <source srcset="{{ asset($file->webp_url) }}" type="image/webp">
                                                    <source srcset="{{ asset($file->url) }}" type="{{ $file->mime_type }}">
                                                    <img src="{{ asset($file->url) }}" alt="{{ $file->name }}" class="img-fluid mb-2" style="max-height: 120px;">
                                                </picture>
                                            @else
                                                <img src="{{ asset($file->url) }}" alt="{{ $file->name }}" class="img-fluid mb-2" style="max-height: 120px;">
                                            @endif
                                        @else
                                            <i class="fas {{ $file->getIconClassAttribute() }} fa-3x mb-2"></i>
                                        @endif
                                        <h6 class="card-title">{{ $file->original_name ?? $file->name }}</h6>
                                        <p class="card-text small text-muted">
                                            {{ $file->getHumanReadableSizeAttribute() }}
                                            @if($file->isImage() && $file->has_webp)
                                                @php
                                                    $webpFilePath = public_path('uploads/' . $file->webp_path);
                                                    $webpSize = file_exists($webpFilePath) ? round(filesize($webpFilePath) / 1024, 2) . ' KB' : 'Dosya bulunamadı';
                                                @endphp
                                                <br><span class="badge badge-success">WebP: {{ $file->formattedWebpSize ?? $webpSize }}</span>
                                            @endif
                                        </p>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.filemanagersystem.preview', $file->id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.filemanagersystem.media.edit', $file->id) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="deleteFile({{ $file->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Yükleniyor göstergesi -->
                    <div id="loading-indicator" class="text-center d-none">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Yükleniyor...</span>
                        </div>
                    </div>
                    
                    <!-- Sayfalama -->
                    <div class="mt-3" id="pagination-container">
                        <!-- AJAX ile doldurulacak -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .file-item {
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            height: 100%;
        }
        .file-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .file-item .card-body {
            padding: 1rem;
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .file-item .card-title {
            margin-top: 0.5rem;
            font-size: 0.9rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .file-item .btn-group {
            margin-top: auto;
        }
        .file-item img {
            object-fit: contain;
            width: 100%;
            height: 120px;
            margin: 0 auto;
        }
        .file-item i.fas {
            color: #6c757d;
        }
        
        /* Opacity sınıfı */
        .opacity-50 {
            opacity: 0.5;
            pointer-events: none;
        }
        
        /* Liste görünümü */
        .list-view .file-item {
            margin-bottom: 0.5rem;
        }
        .list-view .file-item .card-body {
            padding: 0.75rem;
            flex-direction: row;
            align-items: center;
            text-align: left;
        }
        .list-view .file-item img,
        .list-view .file-item i.fas {
            margin-right: 1rem;
            margin-bottom: 0;
            width: 40px;
            height: 40px;
            object-fit: cover;
        }
        .list-view .file-item .card-title {
            margin: 0;
            flex: 1;
        }
        .list-view .file-item .btn-group {
            margin-top: 0;
            margin-left: auto;
        }
        
        /* Grid görünümü (varsayılan) */
        .grid-view .file-item .card-body {
            text-align: center;
            flex-direction: column;
        }
    </style>
@stop

@section('js')
    <script>
        // Global değişkenler
        let currentPage = 1;
        let isLoading = false;

        // Sayfa yüklendiğinde
        $(document).ready(function() {
            // Varsayılan görünüm modunu ayarla
            $('#file-list').addClass('grid-view');
            $('.view-mode[data-mode="grid"]').removeClass('btn-default').addClass('btn-primary');
            
            // Arama butonu tıklama
            $('#search-button').on('click', function() {
                performSearch();
            });

            // Enter tuşu ile arama
            $('#search-input').on('keypress', function(e) {
                if (e.which === 13) {
                    performSearch();
                }
            });

            // Filtreleme değişikliklerini dinle
            $('#filter-type, #filter-date, #filter-size, #sort-by').on('change', function() {
                performSearch();
            });

            // Görünüm modu değiştirme
            $('.view-mode').on('click', function() {
                const mode = $(this).data('mode');
                $('.view-mode').removeClass('btn-primary').addClass('btn-default');
                $(this).removeClass('btn-default').addClass('btn-primary');
                
                if (mode === 'grid') {
                    $('#file-list').removeClass('list-view').addClass('grid-view');
                } else {
                    $('#file-list').removeClass('grid-view').addClass('list-view');
                }
            });

            // Klasör ve kategori tıklama
            $(document).on('click', '.nav-link[data-folder-id], .nav-link[data-category-id]', function(e) {
                e.preventDefault();
                $('.nav-link').removeClass('active');
                $(this).addClass('active');
                performSearch();
            });
        });

        // Arama fonksiyonu
        function performSearch() {
            if (isLoading) return;
            
            isLoading = true;
            currentPage = 1;
            
            // Loading göster
            $('#loading-indicator').removeClass('d-none');
            $('#file-list').addClass('opacity-50');

            // Parametreleri topla
            const params = {
                search: $('#search-input').val(),
                type: $('#filter-type').val(),
                date_filter: $('#filter-date').val(),
                size_filter: $('#filter-size').val(),
                sort: $('#sort-by').val(),
                folder_id: $('.nav-link.active[data-folder-id]').data('folder-id'),
                category_id: $('.nav-link.active[data-category-id]').data('category-id'),
                page: currentPage
            };

            // Boş parametreleri temizle
            Object.keys(params).forEach(key => {
                if (params[key] === '' || params[key] === undefined) {
                    delete params[key];
                }
            });

            // AJAX isteği
            $.ajax({
                url: '{{ route("admin.filemanagersystem.search") }}',
                type: 'GET',
                data: params,
                success: function(response) {
                    if (response.success) {
                        renderFiles(response.data);
                        renderPagination(response.pagination);
                    } else {
                        showError('Arama sırasında bir hata oluştu: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', xhr.responseText);
                    showError('Arama sırasında bir hata oluştu. Lütfen tekrar deneyin.');
                },
                complete: function() {
                    isLoading = false;
                    $('#loading-indicator').addClass('d-none');
                    $('#file-list').removeClass('opacity-50');
                }
            });
        }

        // Dosyaları render et
        function renderFiles(files) {
            const fileList = $('#file-list');
            fileList.empty();

            if (files.length === 0) {
                fileList.html(`
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Dosya bulunamadı</h5>
                        <p class="text-muted">Arama kriterlerinizi değiştirip tekrar deneyin.</p>
                    </div>
                `);
                return;
            }

            files.forEach(file => {
                const fileHtml = createFileCard(file);
                fileList.append(fileHtml);
            });
        }

        // Dosya kartı oluştur
        function createFileCard(file) {
            const isImage = file.mime_type && file.mime_type.startsWith('image/');
            const fileIcon = getFileIcon(file.mime_type);
            
            return `
                <div class="col-md-3 col-sm-6">
                    <div class="card file-item">
                        <div class="card-body text-center">
                            ${isImage ? 
                                `<img src="${file.url}" alt="${file.name}" class="img-fluid mb-2" style="max-height: 120px;">` :
                                `<i class="fas ${fileIcon} fa-3x mb-2"></i>`
                            }
                            <h6 class="card-title" title="${file.file_name || file.name}">${truncateText(file.file_name || file.name, 20)}</h6>
                            <p class="card-text small text-muted">
                                ${file.human_readable_size || 'N/A'}
                                ${file.formatted_date ? '<br>' + file.formatted_date : ''}
                            </p>
                            <div class="btn-group">
                                <a href="/admin/filemanagersystem/preview/${file.id}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="/admin/filemanagersystem/media/${file.id}/edit" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger" onclick="deleteFile(${file.id})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        // Sayfalama render et
        function renderPagination(pagination) {
            const container = $('#pagination-container');
            
            if (pagination.last_page <= 1) {
                container.empty();
                return;
            }

            let paginationHtml = '<nav><ul class="pagination justify-content-center">';
            
            // Önceki sayfa
            if (pagination.current_page > 1) {
                paginationHtml += `<li class="page-item"><a class="page-link" href="#" data-page="${pagination.current_page - 1}">Önceki</a></li>`;
            }
            
            // Sayfa numaraları
            const startPage = Math.max(1, pagination.current_page - 2);
            const endPage = Math.min(pagination.last_page, pagination.current_page + 2);
            
            for (let i = startPage; i <= endPage; i++) {
                const isActive = i === pagination.current_page ? 'active' : '';
                paginationHtml += `<li class="page-item ${isActive}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
            }
            
            // Sonraki sayfa
            if (pagination.current_page < pagination.last_page) {
                paginationHtml += `<li class="page-item"><a class="page-link" href="#" data-page="${pagination.current_page + 1}">Sonraki</a></li>`;
            }
            
            paginationHtml += '</ul></nav>';
            
            container.html(paginationHtml);
            
            // Sayfalama tıklama eventi
            container.find('.page-link').on('click', function(e) {
                e.preventDefault();
                currentPage = parseInt($(this).data('page'));
                performSearch();
            });
        }

        // Yardımcı fonksiyonlar
        function getFileIcon(mimeType) {
            if (!mimeType) return 'fa-file';
            
            if (mimeType.startsWith('image/')) return 'fa-image';
            if (mimeType.startsWith('video/')) return 'fa-video';
            if (mimeType.startsWith('audio/')) return 'fa-music';
            if (mimeType === 'application/pdf') return 'fa-file-pdf';
            if (mimeType.includes('word')) return 'fa-file-word';
            if (mimeType.includes('excel') || mimeType.includes('spreadsheet')) return 'fa-file-excel';
            if (mimeType.includes('powerpoint') || mimeType.includes('presentation')) return 'fa-file-powerpoint';
            if (mimeType.includes('zip') || mimeType.includes('rar') || mimeType.includes('archive')) return 'fa-file-archive';
            
            return 'fa-file';
        }

        function truncateText(text, maxLength) {
            if (text.length <= maxLength) return text;
            return text.substring(0, maxLength) + '...';
        }

        function showError(message) {
            // Basit bir hata mesajı göster
            alert(message);
        }

        function deleteFile(id) {
            if (confirm('Bu dosyayı silmek istediğinizden emin misiniz?')) {
                // CSRF token al
                var token = '{{ csrf_token() }}';
                
                // Form oluştur
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = '/admin/filemanagersystem/media/' + id;
                form.style.display = 'none';
                
                // CSRF token input
                var csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = token;
                form.appendChild(csrfInput);
                
                // Method override input
                var methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);
                
                // Formu sayfaya ekle ve gönder
                document.body.appendChild(form);
                form.submit();
                
                return false;
            }
        }
    </script>
@stop 