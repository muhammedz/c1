@extends('adminlte::page')

@section('title', 'Dosya Yükle')

@section('content_header')
    <h1>Dosya Yükle</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.filemanagersystem.media.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                @csrf
                <div class="form-group">
                    <label for="file">Dosya</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="file" name="file" required>
                        <label class="custom-file-label" for="file">Dosya seçin</label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="folder_id">Klasör</label>
                    <select class="form-control" id="folder_id" name="folder_id">
                        <option value="">Klasör seçin</option>
                        @foreach($folders as $folder)
                            <option value="{{ $folder->id }}">{{ $folder->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="categories">Kategoriler</label>
                    <select class="form-control select2" id="categories" name="categories[]" multiple>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="title">Başlık</label>
                    <input type="text" class="form-control" id="title" name="title" placeholder="Dosya başlığı">
                </div>

                <div class="form-group">
                    <label for="description">Açıklama</label>
                    <textarea class="form-control" id="description" name="description" rows="3" placeholder="Dosya açıklaması"></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Yükle</button>
            </form>
        </div>
    </div>

    <!-- Yükleme İlerleme Modal -->
    <div class="modal fade" id="uploadProgressModal" tabindex="-1" role="dialog" aria-labelledby="uploadProgressModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadProgressModalLabel">Dosya Yükleniyor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
                    </div>
                    <div class="text-center mt-3">
                        <span id="uploadStatus">Dosya yükleniyor...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Select2 başlat
            $('.select2').select2({
                theme: 'bootstrap4',
                placeholder: 'Kategori seçin'
            });

            // Dosya seçildiğinde
            $('.custom-file-input').on('change', function() {
                var fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').html(fileName);
            });

            // Form gönderildiğinde
            $('#uploadForm').on('submit', function(e) {
                e.preventDefault();

                var formData = new FormData(this);
                var $progressBar = $('.progress-bar');
                var $uploadStatus = $('#uploadStatus');

                // Modal'ı göster
                $('#uploadProgressModal').modal('show');

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    xhr: function() {
                        var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function(evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = evt.loaded / evt.total;
                                percentComplete = parseInt(percentComplete * 100);
                                $progressBar.css('width', percentComplete + '%');
                                $uploadStatus.text('Yükleniyor: %' + percentComplete);
                            }
                        }, false);
                        return xhr;
                    },
                    success: function(response) {
                        $progressBar.css('width', '100%');
                        $uploadStatus.text('Yükleme tamamlandı!');
                        setTimeout(function() {
                            $('#uploadProgressModal').modal('hide');
                            window.location.href = response.redirect;
                        }, 1000);
                    },
                    error: function(xhr) {
                        $uploadStatus.text('Hata: ' + xhr.responseJSON.message);
                        $progressBar.addClass('bg-danger');
                    }
                });
            });
        });
    </script>
@stop 