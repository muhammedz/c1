@extends('adminlte::page')

@section('title', 'Hizmetler Ayarları')

@section('content_header')
    <h1>Hizmetler Ayarları</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Hizmetler Genel Ayarları</h3>
                </div>
                
                <form method="POST" action="{{ route('admin.services.settings.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        
                        <div class="form-group">
                            <label for="per_page">Sayfa Başına Gösterilecek Hizmet Sayısı</label>
                            <input type="number" class="form-control" id="per_page" name="per_page" value="{{ old('per_page', 10) }}" min="1" max="50">
                        </div>
                        
                        <div class="form-group">
                            <label for="default_sort">Varsayılan Sıralama</label>
                            <select class="form-control" id="default_sort" name="default_sort">
                                <option value="created_at_desc">Oluşturma Tarihi (Yeni-Eski)</option>
                                <option value="created_at_asc">Oluşturma Tarihi (Eski-Yeni)</option>
                                <option value="title_asc">Başlık (A-Z)</option>
                                <option value="title_desc">Başlık (Z-A)</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="show_featured" name="show_featured" value="1" checked>
                                <label class="custom-control-label" for="show_featured">Öne Çıkan Hizmetleri Göster</label>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <h4>SEO Ayarları</h4>
                        
                        <div class="form-group">
                            <label for="meta_title">Meta Başlık</label>
                            <input type="text" class="form-control" id="meta_title" name="meta_title" value="{{ old('meta_title', 'Hizmetlerimiz') }}">
                        </div>
                        
                        <div class="form-group">
                            <label for="meta_description">Meta Açıklama</label>
                            <textarea class="form-control" id="meta_description" name="meta_description" rows="3">{{ old('meta_description', 'Kurumumuz tarafından sunulan tüm hizmetler.') }}</textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="banner_image">Banner Görseli</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="banner_image" name="banner_image">
                                    <label class="custom-file-label" for="banner_image">Dosya Seç</label>
                                </div>
                            </div>
                            <small class="form-text text-muted">Önerilen boyut: 1920x400 piksel.</small>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Ayarları Kaydet</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    /* Custom CSS */
</style>
@stop

@section('js')
<script>
    $(document).ready(function() {
        // Custom JS
        $('.custom-file-input').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $(this).siblings('.custom-file-label').addClass('selected').html(fileName);
        });
    });
</script>
@stop 