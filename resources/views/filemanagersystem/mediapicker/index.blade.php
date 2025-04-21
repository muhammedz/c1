<!DOCTYPE html>
<html lang="tr" style="width: 100%; height: 100%; margin: 0; padding: 0;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medya Seçici</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
    <!-- Custom Styles -->
    <style>
        html, body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .wrapper {
            width: 100%;
            padding: 0;
            margin: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .header {
            background-color: #fff;
            border-bottom: 1px solid #dee2e6;
            padding: 15px;
            flex-shrink: 0;
        }
        .header h1 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
        }
        .content {
            padding: 0 15px;
            flex-grow: 1;
            overflow-y: auto;
        }
        .card {
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
            border: none;
            margin-bottom: 20px;
        }
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid #e9ecef;
            padding: 15px;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .card-body {
            padding: 15px;
            max-height: calc(100vh - 180px);
            overflow-y: auto;
        }
        .media-item {
            margin-bottom: 15px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s;
            overflow: hidden;
        }
        
        .media-item:hover {
            border-color: #007bff;
            box-shadow: 0 0 8px rgba(0,123,255,.25);
        }
        
        .media-item.selected {
            border-color: #28a745;
            box-shadow: 0 0 8px rgba(40,167,69,.25);
            background-color: rgba(40,167,69,.05);
        }
        
        .media-thumbnail {
            height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
            overflow: hidden;
        }
        
        .media-thumbnail img {
            max-width: 100%;
            max-height: 110px;
            object-fit: contain;
        }
        
        .media-thumbnail .file-icon {
            font-size: 4em;
            color: #6c757d;
        }
        
        .media-info {
            padding: 10px;
            border-top: 1px solid #dee2e6;
        }
        
        .media-name {
            font-weight: 500;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .media-details {
            font-size: 0.8em;
            color: #6c757d;
        }
        
        /* Liste görünümü için stiller */
        .list-view .media-item {
            display: flex;
            margin-bottom: 8px;
        }
        
        .list-view .media-thumbnail {
            height: 50px;
            width: 50px;
            min-width: 50px;
        }
        
        .list-view .media-thumbnail img {
            max-height: 40px;
        }
        
        .list-view .media-thumbnail .file-icon {
            font-size: 1.5em;
        }
        
        .list-view .media-info {
            flex: 1;
            border-top: none;
            border-left: 1px solid #dee2e6;
            display: flex;
            align-items: center;
        }
        
        .list-view .media-details {
            margin-left: auto;
            text-align: right;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
    </style>
</head>
<body style="margin: 0; padding: 0;">
    <div class="wrapper">
        <div class="header">
            <div class="container-fluid">
                <h1>Medya Seçici</h1>
            </div>
        </div>
        
        <div class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="card-title">Medya Dosyaları</h3>
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary" id="uploadButton">
                                    <i class="fas fa-upload mr-1"></i> Yeni Dosya Yükle
                                </button>
                                <button type="button" class="btn btn-secondary" id="refreshButton">
                                    <i class="fas fa-sync-alt mr-1"></i> Yenile
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(isset($relatedId) && strpos($relatedId, 'temp_') === 0)
                        <div class="alert alert-info alert-dismissible fade show">
                            <h5><i class="icon fas fa-info"></i> Bilgi</h5>
                            <p>İçerik henüz kaydedilmemiş. Yeni görsel seçmek için <strong>"Tüm Dosyalar"</strong> sekmesini kullanabilirsiniz. Görsel seçtikten sonra içeriği kaydetmeyi unutmayın.</p>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Kapat">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @endif
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-secondary {{ request('filter') == 'all' || !request('filter') ? 'active' : '' }}" data-filter="all">
                                        Tüm Dosyalar
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary {{ request('filter') == 'related' ? 'active' : '' }}" data-filter="related">
                                        İlişkili Dosyalar
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary {{ request('filter') == 'type-related' ? 'active' : '' }}" data-filter="type-related">
                                        Bu Tür Dosyalar
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Ara..." id="searchInput">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" id="searchButton">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                    <label class="btn btn-sm btn-outline-primary active">
                                        <input type="radio" name="layout" value="grid" checked> <i class="fas fa-th-large"></i> Grid
                                    </label>
                                    <label class="btn btn-sm btn-outline-primary">
                                        <input type="radio" name="layout" value="list"> <i class="fas fa-list"></i> Liste
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div id="mediaList" class="row">
                            <div class="col-12 text-center py-5">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only">Yükleniyor...</span>
                                </div>
                                <p class="mt-2">Medya dosyaları yükleniyor...</p>
                            </div>
                        </div>

                        <div id="paginationContainer" class="mt-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dosya Yükleme Modal -->
    <div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadModalLabel">Yeni Dosya Yükle</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="uploadForm" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="file">Dosya Seçin</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="file" name="file">
                                <label class="custom-file-label" for="file">Dosya seçin...</label>
                            </div>
                        </div>
                        <input type="hidden" name="related_type" id="related_type" value="{{ $relatedType }}">
                        <input type="hidden" name="related_id" id="related_id" value="{{ $relatedId }}">
                        <input type="hidden" name="type" id="type" value="{{ $type }}">
                    </form>
                    <div class="progress mt-3 d-none" id="uploadProgress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="alert mt-3 d-none" id="uploadMessage"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">İptal</button>
                    <button type="button" class="btn btn-primary" id="startUploadButton">Yükle</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Dosya Önizleme Modal -->
    <div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="previewModalLabel">Dosya Önizleme</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div id="previewContent" class="text-center"></div>
                        </div>
                        <div class="col-md-4">
                            <h6 class="mt-3">Dosya Bilgileri</h6>
                            <table class="table table-sm">
                                <tr>
                                    <th>Adı:</th>
                                    <td id="previewName"></td>
                                </tr>
                                <tr>
                                    <th>Boyut:</th>
                                    <td id="previewSize"></td>
                                </tr>
                                <tr>
                                    <th>Tür:</th>
                                    <td id="previewType"></td>
                                </tr>
                                <tr>
                                    <th>Yüklenme:</th>
                                    <td id="previewDate"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
                    <button type="button" class="btn btn-primary" id="selectMediaButton">Seç</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Değişkenler
            var selectedMedia = null;
            var relatedType = '{{ $relatedType }}';
            var relatedId = '{{ $relatedId }}';
            // JavaScript'te boş string kontrolü yapıyoruz
            if (relatedId === '') {
                relatedId = null;
            }
            var type = '{{ $type }}';
            
            // URL'den filter parametresini oku
            var urlParams = new URLSearchParams(window.location.search);
            var filter = urlParams.get('filter') || 'related'; // Varsayılan olarak 'related' kullan, eğer URL'de filter varsa onu kullan
            
            // Geçici ID kontrolü yap, geçici ID varsa varsayılan filter'ı all yap
            if (relatedId && relatedId.indexOf('temp_') === 0) {
                filter = 'all';
            }
            
            var layout = 'grid';
            var page = 1;
            
            // İlk yükleme için doğru filtre butonunu aktif et
            $('.btn-group [data-filter="' + filter + '"]').addClass('active').siblings().removeClass('active');
            
            // İlk yükleme
            loadMedia();
            
            // Görünüm değiştirme
            $('input[name="layout"]').change(function() {
                layout = $(this).val();
                if (layout === 'grid') {
                    $('#mediaList').removeClass('list-view');
                } else {
                    $('#mediaList').addClass('list-view');
                }
            });
            
            // Filtre butonları
            $('.btn-group [data-filter]').click(function() {
                filter = $(this).data('filter');
                $(this).addClass('active').siblings().removeClass('active');
                page = 1;
                loadMedia();
            });
            
            // Yenile butonu
            $('#refreshButton').click(function() {
                loadMedia();
            });
            
            // Arama
            $('#searchButton').click(function() {
                page = 1;
                loadMedia();
            });
            
            $('#searchInput').keypress(function(e) {
                if (e.which === 13) {
                    page = 1;
                    loadMedia();
                }
            });
            
            // Sayfalama işlemi
            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                page = $(this).data('page');
                loadMedia();
            });
            
            // Medya dosyalarını listele
            function loadMedia() {
                $('#mediaList').html('<div class="col-12 text-center py-5"><div class="spinner-border text-primary" role="status"><span class="sr-only">Yükleniyor...</span></div><p class="mt-2">Medya dosyaları yükleniyor...</p></div>');
                
                // AJAX istek parametreleri
                var requestParams = {
                    related_type: relatedType,
                    related_id: relatedId,
                    filter: filter,
                    page: page,
                    search: $('#searchInput').val(),
                    type: type
                };
                
                // Manuel AJAX çağrısı yapalım
                try {
                    $.ajax({
                        url: '/admin/filemanagersystem/mediapicker/list',
                        type: 'GET',
                        data: requestParams,
                        success: function(response) {
                            if (response.success) {
                                $('#mediaList').html(response.html);
                                $('#paginationContainer').html(response.pagination);
                                
                                // Önceden seçilmiş dosyayı işaretle
                                if (selectedMedia) {
                                    $('.media-item[data-id="' + selectedMedia + '"]').addClass('selected');
                                }
                            } else {
                                $('#mediaList').html('<div class="col-12 text-center py-5"><div class="alert alert-danger">Hata: Medya dosyaları listelenirken bir sorun oluştu.</div></div>');
                            }
                        },
                        error: function(xhr, status, error) {
                            // Hata kontrolü
                            console.error("MediaPicker Hatası:", xhr, status, error);
                            $('#mediaList').html('<div class="col-12 text-center py-5"><div class="alert alert-danger">Hata: Sunucu ile iletişim sırasında bir sorun oluştu.</div></div>');
                        }
                    });
                } catch (e) {
                    console.error("AJAX Çağrısında Javascript Hatası:", e);
                    $('#mediaList').html('<div class="col-12 text-center py-5"><div class="alert alert-danger">Hata: Javascript hatası oluştu: ' + e.toString() + '</div></div>');
                }
            }
            
            // Dosya seçme işlemi
            $(document).on('click', '.media-item', function() {
                var mediaId = $(this).data('id');
                
                // Önizleme modalını aç
                var mediaUrl = $(this).data('url');
                var mediaName = $(this).data('name');
                var mediaSize = $(this).data('size');
                var mediaType = $(this).data('type');
                var mediaDate = $(this).data('date');
                var mediaExt = mediaName.split('.').pop().toLowerCase();
                
                $('#previewName').text(mediaName);
                $('#previewSize').text(mediaSize);
                $('#previewType').text(mediaType);
                $('#previewDate').text(mediaDate);
                
                var previewContent = '';
                
                // Dosya türüne göre önizleme içeriği
                if (['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'].includes(mediaExt)) {
                    previewContent = '<img src="' + mediaUrl + '" class="img-fluid" alt="' + mediaName + '" style="max-height: 300px;">';
                } else if (['mp4', 'webm', 'ogg'].includes(mediaExt)) {
                    previewContent = '<video src="' + mediaUrl + '" controls class="img-fluid" style="max-height: 300px;"></video>';
                } else if (['mp3', 'wav', 'ogg'].includes(mediaExt)) {
                    previewContent = '<audio src="' + mediaUrl + '" controls></audio>';
                } else if (mediaExt === 'pdf') {
                    previewContent = '<embed src="' + mediaUrl + '" type="application/pdf" width="100%" height="300px" />';
                } else {
                    previewContent = '<div class="text-center"><i class="far fa-file fa-5x text-secondary"></i><p class="mt-3">Bu dosya türü için önizleme bulunmuyor.</p></div>';
                }
                
                $('#previewContent').html(previewContent);
                
                // Seçim butonuna media ID'sini ekle
                $('#selectMediaButton').data('id', mediaId);
                
                // Önizleme modalını aç
                $('#previewModal').modal('show');
            });
            
            // Dosya seçme işlemi
            $('#selectMediaButton').click(function() {
                var mediaId = $(this).data('id');
                
                // Seçilen medyanın URL ve adını al
                var mediaItem = $('.media-item[data-id="' + mediaId + '"]');
                var mediaUrl = mediaItem.data('url');
                var mediaName = mediaItem.data('name');
                
                if (mediaId) {
                    // İlişkilendirme işlemi
                    $.ajax({
                        url: '/admin/filemanagersystem/mediapicker/relate',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            media_id: mediaId,
                            related_type: relatedType,
                            related_id: relatedId
                        },
                        success: function(response) {
                            if (response.success) {
                                // İşlem başarılı, modalı kapat
                                $('#previewModal').modal('hide');
                                
                                // Seçilen medyayı işaretle
                                selectedMedia = mediaId;
                                $('.media-item').removeClass('selected');
                                $('.media-item[data-id="' + mediaId + '"]').addClass('selected');
                                
                                // Seçilen medyayı bildir (parent window'a)
                                if (window.opener) {
                                    window.opener.postMessage({
                                        type: 'mediaSelected',
                                        mediaId: mediaId,
                                        relatedType: relatedType,
                                        relatedId: relatedId,
                                        mediaUrl: mediaUrl,
                                        mediaName: mediaName
                                    }, '*');
                                    
                                    // Pencereyi kapat
                                    window.close();
                                } else {
                                    // iframe içinde açılmışsa parent'a mesaj gönder
                                    window.parent.postMessage({
                                        type: 'mediaSelected',
                                        mediaId: mediaId,
                                        relatedType: relatedType,
                                        relatedId: relatedId,
                                        mediaUrl: mediaUrl,
                                        mediaName: mediaName
                                    }, '*');
                                }
                            } else {
                                alert('Hata: ' + response.message);
                            }
                        },
                        error: function() {
                            alert('Hata: Sunucu ile iletişim sırasında bir sorun oluştu.');
                        }
                    });
                }
            });
            
            // Dosya yükleme modalı
            $('#uploadButton').click(function() {
                $('#uploadModal').modal('show');
            });
            
            // Dosya yükleme
            $('#startUploadButton').click(function() {
                var formData = new FormData($('#uploadForm')[0]);
                
                // Form verilerini manuel olarak tekrar ekleyelim - related_id null olsa bile
                formData.set('related_type', relatedType);
                formData.set('related_id', relatedId || '');
                
                // Progress bar göster
                $('#uploadProgress').removeClass('d-none');
                $('#uploadMessage').removeClass('d-none alert-success alert-danger').addClass('d-none');
                
                $.ajax({
                    url: '/admin/filemanagersystem/mediapicker/upload',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    xhr: function() {
                        var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener('progress', function(e) {
                            if (e.lengthComputable) {
                                var percent = Math.round((e.loaded / e.total) * 100);
                                $('#uploadProgress .progress-bar').css('width', percent + '%').attr('aria-valuenow', percent);
                            }
                        });
                        return xhr;
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#uploadMessage').removeClass('d-none alert-danger').addClass('alert-success').html('Dosya başarıyla yüklendi.');
                            
                            // Formu sıfırla
                            $('#uploadForm')[0].reset();
                            $('.custom-file-label').text('Dosya seçin...');
                            
                            // Listeyi yenile
                            loadMedia();
                            
                            // Modalı kapat
                            setTimeout(function() {
                                $('#uploadModal').modal('hide');
                                $('#uploadProgress').addClass('d-none');
                                $('#uploadMessage').addClass('d-none');
                            }, 1500);
                        } else {
                            $('#uploadMessage').removeClass('d-none alert-success').addClass('alert-danger').html('Hata: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        var errorMessage = 'Dosya yüklenirken bir hata oluştu.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        $('#uploadMessage').removeClass('d-none alert-success').addClass('alert-danger').html('Hata: ' + errorMessage);
                    },
                    complete: function() {
                        $('#uploadProgress .progress-bar').css('width', '0%').attr('aria-valuenow', 0);
                    }
                });
            });
            
            // Custom file input
            $(document).on('change', '.custom-file-input', function() {
                var fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').html(fileName);
            });

            // Medya yüklendiğinde grid düzenini modern şekilde ayarla
            function adjustMediaGrid() {
                if ($('#mediaList').find('.media-item').length > 0) {
                    $('#mediaList').find('.media-item').parent().removeClass('col-md-4 col-md-3 col-md-2').addClass('col-md-2 col-sm-3 col-6');
                }
            }
            
            // AJAX yüklemesi sonrası grid düzenini ayarla
            $(document).ajaxComplete(function(event, xhr, settings) {
                if (settings.url.includes('/admin/filemanagersystem/mediapicker/list')) {
                    adjustMediaGrid();
                }
            });
            
            // Sayfa ilk yüklendiğinde de grid düzenini ayarla
            $(window).on('load', function() {
                setTimeout(adjustMediaGrid, 100);
            });

            // Yükleme hatasını kontrol et ve işle
            window.onerror = function(message, source, lineno, colno, error) {
                console.error("Medya Seçici Hata:", message, error);
                showErrorMessage("Sayfada bir JavaScript hatası oluştu: " + message);
                return true;
            };
            
            // AJAX hatalarını yakalama
            $(document).ajaxError(function(event, jqXHR, settings, error) {
                console.error("Medya AJAX Hatası:", error, jqXHR.status, jqXHR.responseText);
                
                let errorMsg = "Sunucu ile iletişim sırasında bir hata oluştu.";
                if (jqXHR.status === 500) {
                    errorMsg = "Sunucu hatası oluştu. Lütfen daha sonra tekrar deneyin.";
                } else if (jqXHR.status === 404) {
                    errorMsg = "İstenen sayfa bulunamadı.";
                } else if (jqXHR.status === 403) {
                    errorMsg = "Bu işlemi yapmaya yetkiniz yok.";
                } else if (jqXHR.status === 401) {
                    errorMsg = "Lütfen önce giriş yapın.";
                } else if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
                    errorMsg = jqXHR.responseJSON.message;
                }
                
                showErrorMessage(errorMsg);
            });
            
            function showErrorMessage(message) {
                const errorHtml = `
                    <div class="alert alert-danger alert-dismissible fade show">
                        <h5><i class="icon fas fa-ban"></i> Hata!</h5>
                        <p>${message}</p>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Kapat">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                `;
                
                $('.content .container-fluid').prepend(errorHtml);
                
                // Üst pencereye de bildir
                try {
                    window.parent.postMessage({
                        type: 'mediapickerError',
                        message: message
                    }, '*');
                } catch (e) {
                    console.error("Hata bildirme hatası:", e);
                }
            }
            
            // Sayfanın başarıyla yüklendiğini bildir
            try {
                window.parent.postMessage({
                    type: 'mediapickerLoaded'
                }, '*');
                console.log("MediaPicker sayfası yüklendi, ebeveyn pencereye bildirildi");
            } catch (e) {
                console.error("Pencereler arası iletişim hatası:", e);
            }
        });
    </script>
</body>
</html> 