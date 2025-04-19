@extends('adminlte::page')

@section('title', 'Dosya Yönetim Sistemi - Ayarlar')

@section('content_header')
    <h1>Dosya Yönetim Sistemi Ayarları</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Sistem Ayarları</h3>
                </div>
                <form action="{{ route('admin.filemanagersystem.settings.update') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <!-- İzin Verilen Dosya Tipleri -->
                        <div class="form-group">
                            <label for="allowed_file_types">İzin Verilen Dosya Tipleri</label>
                            <select class="form-control select2" name="allowed_file_types[]" id="allowed_file_types" multiple>
                                <option value="image/jpeg" {{ in_array('image/jpeg', $settings['allowed_file_types']) ? 'selected' : '' }}>JPEG Resimler</option>
                                <option value="image/png" {{ in_array('image/png', $settings['allowed_file_types']) ? 'selected' : '' }}>PNG Resimler</option>
                                <option value="image/gif" {{ in_array('image/gif', $settings['allowed_file_types']) ? 'selected' : '' }}>GIF Resimler</option>
                                <option value="application/pdf" {{ in_array('application/pdf', $settings['allowed_file_types']) ? 'selected' : '' }}>PDF Dosyaları</option>
                                <option value="application/msword" {{ in_array('application/msword', $settings['allowed_file_types']) ? 'selected' : '' }}>Word Dosyaları</option>
                                <option value="application/vnd.ms-excel" {{ in_array('application/vnd.ms-excel', $settings['allowed_file_types']) ? 'selected' : '' }}>Excel Dosyaları</option>
                                <option value="application/vnd.ms-powerpoint" {{ in_array('application/vnd.ms-powerpoint', $settings['allowed_file_types']) ? 'selected' : '' }}>PowerPoint Dosyaları</option>
                                <option value="text/plain" {{ in_array('text/plain', $settings['allowed_file_types']) ? 'selected' : '' }}>Metin Dosyaları</option>
                                <option value="video/mp4" {{ in_array('video/mp4', $settings['allowed_file_types']) ? 'selected' : '' }}>MP4 Videolar</option>
                                <option value="audio/mpeg" {{ in_array('audio/mpeg', $settings['allowed_file_types']) ? 'selected' : '' }}>MP3 Ses Dosyaları</option>
                            </select>
                            <small class="form-text text-muted">Birden fazla dosya tipi seçebilirsiniz.</small>
                        </div>

                        <!-- Maksimum Dosya Boyutu -->
                        <div class="form-group">
                            <label for="max_file_size">Maksimum Dosya Boyutu (MB)</label>
                            <input type="number" class="form-control" name="max_file_size" id="max_file_size" value="{{ $settings['max_file_size'] / 1024 / 1024 }}" min="1">
                            <small class="form-text text-muted">Dosya yükleme boyut sınırı (megabayt cinsinden).</small>
                        </div>

                        <!-- Depolama Yolu -->
                        <div class="form-group">
                            <label for="storage_path">Depolama Yolu</label>
                            <input type="text" class="form-control" name="storage_path" id="storage_path" value="{{ $settings['storage_path'] }}">
                            <small class="form-text text-muted">Dosyaların depolanacağı dizin yolu.</small>
                        </div>

                        <!-- Thumbnail Boyutları -->
                        <div class="form-group">
                            <label for="thumbnail_sizes">Thumbnail Boyutları</label>
                            <div id="thumbnail-sizes-container">
                                @foreach($settings['thumbnail_sizes'] as $size)
                                    <div class="row mb-2">
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" name="thumbnail_sizes[name][]" value="{{ $size['name'] }}" placeholder="Boyut Adı">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="number" class="form-control" name="thumbnail_sizes[width][]" value="{{ $size['width'] }}" placeholder="Genişlik">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="number" class="form-control" name="thumbnail_sizes[height][]" value="{{ $size['height'] }}" placeholder="Yükseklik">
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" class="btn btn-danger btn-sm remove-size">×</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-primary btn-sm mt-2" id="add-thumbnail-size">Yeni Boyut Ekle</button>
                            <small class="form-text text-muted">Resimler için oluşturulacak thumbnail boyutları.</small>
                        </div>

                        <!-- Varsayılan Klasör -->
                        <div class="form-group">
                            <label for="default_folder">Varsayılan Klasör</label>
                            <input type="text" class="form-control" name="default_folder" id="default_folder" value="{{ $settings['default_folder'] }}">
                            <small class="form-text text-muted">Yeni dosyaların yükleneceği varsayılan klasör.</small>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Ayarları Kaydet</button>
                    </div>
                </form>
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
            // Select2 başlatma
            $('.select2').select2({
                theme: 'bootstrap4',
                width: '100%'
            });

            // Yeni thumbnail boyutu ekleme
            $('#add-thumbnail-size').click(function() {
                const template = `
                    <div class="row mb-2">
                        <div class="col-md-5">
                            <input type="text" class="form-control" name="thumbnail_sizes[name][]" placeholder="Boyut Adı">
                        </div>
                        <div class="col-md-3">
                            <input type="number" class="form-control" name="thumbnail_sizes[width][]" placeholder="Genişlik">
                        </div>
                        <div class="col-md-3">
                            <input type="number" class="form-control" name="thumbnail_sizes[height][]" placeholder="Yükseklik">
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-danger btn-sm remove-size">×</button>
                        </div>
                    </div>
                `;
                $('#thumbnail-sizes-container').append(template);
            });

            // Thumbnail boyutu silme
            $(document).on('click', '.remove-size', function() {
                $(this).closest('.row').remove();
            });
        });
    </script>
@stop 