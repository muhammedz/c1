@extends('adminlte::page')

@section('title', 'TinyMCE Editör')

@section('content_header')
    <h1>TinyMCE Editör</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <textarea class="tinymce-editor" name="content"></textarea>
        </div>
    </div>

    <!-- Dosya Seçici Modal -->
    <div class="modal fade" id="filePickerModal" tabindex="-1" role="dialog" aria-labelledby="filePickerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filePickerModalLabel">Dosya Seç</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Klasörler</h3>
                                </div>
                                <div class="card-body p-0">
                                    <div id="folderTree"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default" id="gridViewBtn">
                                                <i class="fas fa-th"></i>
                                            </button>
                                            <button type="button" class="btn btn-default" id="listViewBtn">
                                                <i class="fas fa-list"></i>
                                            </button>
                                        </div>
                                        <div class="input-group" style="width: 300px;">
                                            <input type="text" class="form-control" id="searchInput" placeholder="Dosya ara...">
                                            <div class="input-group-append">
                                                <button class="btn btn-default" type="button">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div id="fileList" class="row">
                                        <!-- Dosyalar buraya yüklenecek -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">İptal</button>
                    <button type="button" class="btn btn-primary" id="selectFilesBtn">Seç</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Dosya Yükleme Modal -->
    <div class="modal fade" id="fileUploadModal" tabindex="-1" role="dialog" aria-labelledby="fileUploadModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fileUploadModalLabel">Dosya Yükle</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="fileUploadForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="file">Dosya</label>
                            <input type="file" class="form-control-file" id="file" name="file" required>
                        </div>
                        <div class="form-group">
                            <label for="folder_id">Klasör</label>
                            <select class="form-control" id="folder_id" name="folder_id">
                                <option value="">Varsayılan Klasör</option>
                                @foreach($folders as $folder)
                                    <option value="{{ $folder->id }}">{{ $folder->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">İptal</button>
                        <button type="submit" class="btn btn-primary">Yükle</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/tinymce.css') }}">
    <style>
        #folderTree {
            max-height: 500px;
            overflow-y: auto;
        }
        .file-item {
            margin-bottom: 15px;
            text-align: center;
        }
        .file-item img {
            max-width: 100%;
            height: auto;
        }
        .file-item.selected {
            background-color: #f8f9fa;
            border: 2px solid #007bff;
        }
        .list-view .file-item {
            display: flex;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #dee2e6;
        }
        .list-view .file-item img {
            width: 50px;
            height: 50px;
            margin-right: 10px;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="{{ asset('js/tinymce.js') }}"></script>
    <script>
        let selectedFiles = [];
        let currentFolder = null;
        let viewMode = 'grid';

        function loadFiles(folderId = null) {
            $.get('/filemanagersystem/search', { folder_id: folderId }, function(response) {
                $('#fileList').empty();
                
                response.data.forEach(function(file) {
                    let fileItem = `
                        <div class="col-md-3 file-item" data-id="${file.id}">
                            <div class="card">
                                <img src="${file.file_url}" class="card-img-top" alt="${file.file_name}">
                                <div class="card-body">
                                    <h6 class="card-title">${file.file_name}</h6>
                                    <p class="card-text">
                                        <small class="text-muted">${formatFileSize(file.file_size)}</small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    `;
                    $('#fileList').append(fileItem);
                });
            });
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        $(document).ready(function() {
            // Klasör ağacını yükle
            $.get('/filemanagersystem/folders', function(folders) {
                $('#folderTree').jstree({
                    'core': {
                        'data': folders
                    }
                });
            });

            // Dosya seçimi
            $(document).on('click', '.file-item', function() {
                $(this).toggleClass('selected');
                let fileId = $(this).data('id');
                
                if ($(this).hasClass('selected')) {
                    selectedFiles.push(fileId);
                } else {
                    selectedFiles = selectedFiles.filter(id => id !== fileId);
                }
            });

            // Klasör seçimi
            $('#folderTree').on('select_node.jstree', function(e, data) {
                currentFolder = data.node.id;
                loadFiles(currentFolder);
            });

            // Görünüm modu değiştirme
            $('#gridViewBtn').click(function() {
                viewMode = 'grid';
                $('#fileList').removeClass('list-view').addClass('grid-view');
            });

            $('#listViewBtn').click(function() {
                viewMode = 'list';
                $('#fileList').removeClass('grid-view').addClass('list-view');
            });

            // Dosya seçme
            $('#selectFilesBtn').click(function() {
                if (selectedFiles.length > 0) {
                    // TinyMCE callback fonksiyonunu çağır
                    if (window.tinymceFilePickerCallback) {
                        window.tinymceFilePickerCallback(selectedFiles);
                    }
                    $('#filePickerModal').modal('hide');
                }
            });

            // Arama
            $('#searchInput').on('keyup', function() {
                let query = $(this).val();
                $.get('/filemanagersystem/search', { q: query }, function(response) {
                    $('#fileList').empty();
                    
                    response.data.forEach(function(file) {
                        let fileItem = `
                            <div class="col-md-3 file-item" data-id="${file.id}">
                                <div class="card">
                                    <img src="${file.file_url}" class="card-img-top" alt="${file.file_name}">
                                    <div class="card-body">
                                        <h6 class="card-title">${file.file_name}</h6>
                                        <p class="card-text">
                                            <small class="text-muted">${formatFileSize(file.file_size)}</small>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        `;
                        $('#fileList').append(fileItem);
                    });
                });
            });
        });
    </script>
@stop 