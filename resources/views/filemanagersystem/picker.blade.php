@extends('adminlte::page')

@section('title', 'Dosya Seç')

@section('content_header')
    <h1>Dosya Seç</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <a class="nav-link active" id="v-pills-folders-tab" data-toggle="pill" href="#v-pills-folders" role="tab" aria-controls="v-pills-folders" aria-selected="true">
                            <i class="fas fa-folder"></i> Klasörler
                        </a>
                        <a class="nav-link" id="v-pills-categories-tab" data-toggle="pill" href="#v-pills-categories" role="tab" aria-controls="v-pills-categories" aria-selected="false">
                            <i class="fas fa-tags"></i> Kategoriler
                        </a>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="tab-content" id="v-pills-tabContent">
                        <div class="tab-pane fade show active" id="v-pills-folders" role="tabpanel" aria-labelledby="v-pills-folders-tab">
                            <div class="nav flex-column">
                                @foreach($folders as $folder)
                                    @include('filemanagersystem.partials.folder-item', ['folder' => $folder])
                                @endforeach
                            </div>
                        </div>
                        <div class="tab-pane fade" id="v-pills-categories" role="tabpanel" aria-labelledby="v-pills-categories-tab">
                            <div class="nav flex-column">
                                @foreach($categories as $category)
                                    @include('filemanagersystem.partials.category-item', ['category' => $category])
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="btn-group">
                                <button type="button" class="btn btn-outline-secondary" id="gridViewBtn">
                                    <i class="fas fa-th-large"></i>
                                </button>
                                <button type="button" class="btn btn-outline-secondary" id="listViewBtn">
                                    <i class="fas fa-list"></i>
                                </button>
                            </div>
                            <div class="input-group" style="width: 300px;">
                                <input type="text" class="form-control" id="searchInput" placeholder="Dosya ara...">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" id="searchButton">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div id="fileList" class="row">
                            <!-- Dosyalar buraya yüklenecek -->
                            <div class="col-12 text-center p-3">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only">Yükleniyor...</span>
                                </div>
                                <p class="mt-2">Dosyalar yükleniyor...</p>
                            </div>
                        </div>
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
            cursor: pointer;
            transition: all 0.2s;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
            overflow: hidden;
        }

        .file-item:hover {
            background-color: #f8f9fa;
            border-color: #adb5bd;
        }

        .file-item.selected {
            background-color: #e9ecef;
            border-color: #007bff;
        }

        .file-item .file-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
            border-radius: 4px;
            font-size: 24px;
        }

        .file-item .file-info {
            flex: 1;
            padding-left: 1rem;
        }

        .file-item .file-name {
            font-weight: 500;
            margin-bottom: 0.25rem;
        }

        .file-item .file-meta {
            font-size: 0.875rem;
            color: #6c757d;
        }

        .list-view .file-item {
            display: flex;
            align-items: center;
            padding: 0.5rem;
        }

        .grid-view .file-item {
            text-align: center;
            padding: 1rem;
        }

        .grid-view .file-item .file-icon {
            width: 100%;
            height: 100px;
            margin-bottom: 0.5rem;
        }

        .grid-view .file-item .file-info {
            padding-left: 0;
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            var currentView = 'grid';
            var selectedFiles = [];

            // Görünüm değiştirme
            $('#gridViewBtn').on('click', function() {
                currentView = 'grid';
                $('#fileList').removeClass('list-view').addClass('grid-view');
                $(this).addClass('active').siblings().removeClass('active');
                loadFiles();
            });

            $('#listViewBtn').on('click', function() {
                currentView = 'list';
                $('#fileList').removeClass('grid-view').addClass('list-view');
                $(this).addClass('active').siblings().removeClass('active');
                loadFiles();
            });

            // Dosya yükleme
            function loadFiles() {
                console.log("loadFiles fonksiyonu çağrıldı");
                var search = $('#searchInput').val();

                console.log("Parametreler:", {
                    search: search,
                    type: "{{ request()->get('type') }}"
                });

                $('#fileList').html(`
                    <div class="col-12 text-center p-3">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Yükleniyor...</span>
                        </div>
                        <p class="mt-2">Dosyalar yükleniyor...</p>
                    </div>
                `);

                // Basitleştirilmiş AJAX isteği, sadece temel parametrelerle
                $.ajax({
                    url: '/admin/filemanagersystem/search',
                    type: 'GET',
                    data: {
                        search: search,
                        type: "{{ request()->get('type') }}"
                    },
                    success: function(response) {
                        console.log("AJAX başarılı:", response);
                        
                        var html = '';
                        
                        if (!response || !response.success || !response.data || response.data.length === 0) {
                            html = `
                                <div class="col-12 text-center p-5">
                                    <i class="fas fa-info-circle fa-3x text-muted mb-3"></i>
                                    <p>Görüntülenecek dosya bulunamadı</p>
                                </div>
                            `;
                        } else {
                            response.data.forEach(function(file) {
                                html += `
                                    <div class="col-md-${currentView === 'grid' ? '3' : '12'}">
                                        <div class="file-item" 
                                            data-id="${file.id}" 
                                            data-url="${file.url}" 
                                            data-path="${file.path}"
                                            data-name="${file.file_name}">
                                            <div class="file-icon">
                                                <i class="fas fa-${getFileIcon(getFileTypeFromPath(file.name))}"></i>
                                            </div>
                                            <div class="file-info">
                                                <div class="file-name">${file.file_name}</div>
                                                <div class="file-meta">
                                                    ${file.created_at ? file.created_at : ''}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `;
                            });
                        }
                        
                        $('#fileList').html(html);
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX hatası:", error);
                        console.log("Status:", status);
                        console.log("XHR:", xhr);
                        
                        // Daha ayrıntılı hata mesajı göster
                        let errorMsg = "Dosyalar yüklenirken bir hata oluştu.";
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            errorMsg += "<br><small>Hata: " + xhr.responseJSON.error + "</small>";
                        } else {
                            errorMsg += "<br><small>Hata: " + error + "</small>";
                        }
                        
                        $('#fileList').html(`
                            <div class="col-12 text-center p-5">
                                <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                                <p>${errorMsg}</p>
                            </div>
                        `);
                    }
                });
            }

            // Dosya türünü yoldan belirleme
            function getFileTypeFromPath(path) {
                if (!path) return 'unknown';
                
                var extension = path.split('.').pop().toLowerCase();
                
                var imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'];
                var videoExtensions = ['mp4', 'webm', 'ogg', 'mov', 'avi', 'wmv', 'flv'];
                var audioExtensions = ['mp3', 'wav', 'ogg', 'aac', 'flac'];
                var documentExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt'];
                
                if (imageExtensions.includes(extension)) return 'image';
                if (videoExtensions.includes(extension)) return 'video';
                if (audioExtensions.includes(extension)) return 'audio';
                if (documentExtensions.includes(extension)) return 'document';
                
                return 'unknown';
            }

            // Dosya ikonu belirleme
            function getFileIcon(type) {
                switch(type) {
                    case 'image':
                        return 'fa-image';
                    case 'video':
                        return 'fa-video';
                    case 'audio':
                        return 'fa-music';
                    case 'document':
                        return 'fa-file-alt';
                    default:
                        return 'fa-file';
                }
            }

            // Klasör veya kategori tıklaması
            $(document).on('click', '[data-folder-id], [data-category-id]', function(e) {
                loadFiles();
            });

            // Dosya seçildiğinde çalışacak fonksiyon
            $(document).on('click', '.file-item', function() {
                const fileUrl = $(this).data('url');
                const filePath = $(this).data('path');
                const fileName = $(this).data('name');
                
                // Seçilen dosyayı belirt
                $('.file-item').removeClass('selected');
                $(this).addClass('selected');
                
                console.log("Seçilen dosya:", fileUrl, filePath, fileName);
                
                // Ebeveyn penceredeki fonksiyonu çağır
                if (window.opener && window.opener.setFileToElement) {
                    // Doğrudan dosya yolunu gönder (relatif yol)
                    if (filePath) {
                        window.opener.setFileToElement(fileUrl, 'uploads/' + filePath);
                    } else {
                        // Geriye uyumluluk için URL'den yol çıkarma
                        const relativePath = getRelativePathFromUrl(fileUrl);
                        window.opener.setFileToElement(fileUrl, relativePath);
                    }
                    window.close();
                } else {
                    console.error("Ebeveyn pencerede setFileToElement fonksiyonu bulunamadı");
                }
            });

            // URL'den yol oluşturma
            function getRelativePathFromUrl(url) {
                if (url) {
                    let relativePath = url;
                    
                    if (url.includes('/uploads/')) {
                        // URL'yi parçala
                        const urlObj = new URL(url);
                        const pathParts = urlObj.pathname.split('/');
                        
                        // '/uploads/' sonrası tüm yolu al
                        const uploadsIndex = pathParts.indexOf('uploads');
                        if (uploadsIndex !== -1) {
                            relativePath = pathParts.slice(uploadsIndex).join('/');
                        }
                    }
                    
                    return relativePath;
                }
                return '';
            }

            // Arama
            $('#searchInput').on('keyup', function(e) {
                if (e.keyCode === 13) { // Enter tuşu
                    loadFiles();
                }
            });
            
            $('#searchButton').on('click', function() {
                loadFiles();
            });

            // İlk yükleme
            loadFiles();
        });
    </script>
@stop 