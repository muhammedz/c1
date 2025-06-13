@extends('adminlte::page')

@section('title', 'Yeni İçerik Ekle')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        Yeni 
                        @switch($type)
                            @case('story')
                                Hikaye
                                @break
                            @case('agenda')
                                Gündem
                                @break
                            @case('value')
                                Değer
                                @break
                            @case('gallery')
                                Fotoğraf
                                @break
                            @default
                                İçerik
                        @endswitch
                        Ekle
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Ana Sayfa</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.mayor.index') }}">Başkan</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.mayor-content.index', ['type' => $type]) }}">
                            @switch($type)
                                @case('story')
                                    Hikayeler
                                    @break
                                @case('agenda')
                                    Gündem
                                    @break
                                @case('value')
                                    Değerler
                                    @break
                                @case('gallery')
                                    Galeri
                                    @break
                            @endswitch
                        </a></li>
                        <li class="breadcrumb-item active">Yeni Ekle</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{ session('error') }}
                </div>
            @endif

            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas 
                            @switch($type)
                                @case('story')
                                    fa-book
                                    @break
                                @case('agenda')
                                    fa-calendar
                                    @break
                                @case('value')
                                    fa-star
                                    @break
                                @case('gallery')
                                    fa-images
                                    @break
                            @endswitch
                            mr-2"></i>
                        Yeni 
                        @switch($type)
                            @case('story')
                                Hikaye
                                @break
                            @case('agenda')
                                Gündem Maddesi
                                @break
                            @case('value')
                                Değer
                                @break
                            @case('gallery')
                                Fotoğraf
                                @break
                        @endswitch
                        Ekle
                    </h3>
                </div>
                
                <form action="{{ route('admin.mayor-content.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="type" value="{{ $type }}">
                    
                    <div class="card-body">
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

                                <!-- Açıklama -->
                                <div class="form-group">
                                    <label for="description">Açıklama</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="6">{{ old('description') }}</textarea>
                                    @error('description')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                @if($type == 'agenda')
                                    <!-- Tarih ve Saat (Gündem için) -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="event_date">Etkinlik Tarihi</label>
                                                <input type="date" class="form-control" 
                                                       id="event_date" name="extra_data[event_date]" 
                                                       value="{{ old('extra_data.event_date') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="event_time">Etkinlik Saati</label>
                                                <input type="time" class="form-control" 
                                                       id="event_time" name="extra_data[event_time]" 
                                                       value="{{ old('extra_data.event_time') }}">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="event_location">Etkinlik Yeri</label>
                                        <input type="text" class="form-control" 
                                               id="event_location" name="extra_data[event_location]" 
                                               value="{{ old('extra_data.event_location') }}"
                                               placeholder="Etkinlik yapılacak yer">
                                    </div>
                                @endif

                                @if($type == 'value')
                                    <!-- İkon (Değerler için) -->
                                    <div class="form-group">
                                        <label for="icon">İkon</label>
                                        <input type="text" class="form-control" 
                                               id="icon" name="extra_data[icon]" 
                                               value="{{ old('extra_data.icon') }}"
                                               placeholder="fas fa-heart (FontAwesome icon class)">
                                        <small class="form-text text-muted">
                                            FontAwesome icon sınıfı (örn: fas fa-heart, fas fa-star)
                                        </small>
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-4">
                                <!-- Görsel -->
                                <div class="form-group">
                                    <label for="image">
                                        @switch($type)
                                            @case('story')
                                                Hikaye Görseli
                                                @break
                                            @case('agenda')
                                                Etkinlik Görseli
                                                @break
                                            @case('value')
                                                Değer Görseli
                                                @break
                                            @case('gallery')
                                                Fotoğraf <span class="text-danger">*</span>
                                                @break
                                        @endswitch
                                    </label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input @error('image') is-invalid @enderror" 
                                                   id="image" name="image" accept="image/*" 
                                                   {{ $type == 'gallery' ? 'required' : '' }}>
                                            <label class="custom-file-label" for="image">Dosya seçin</label>
                                        </div>
                                    </div>
                                    @error('image')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Desteklenen formatlar: JPEG, PNG, JPG, GIF, WebP (Max: 2MB)
                                    </small>
                                </div>

                                <!-- Sıra -->
                                <div class="form-group">
                                    <label for="sort_order">Sıra</label>
                                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                           id="sort_order" name="sort_order" value="{{ old('sort_order') }}" min="1">
                                    @error('sort_order')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Boş bırakılırsa otomatik sıra verilir
                                    </small>
                                </div>

                                <!-- Durum -->
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" 
                                               id="is_active" name="is_active" value="1" 
                                               {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_active">Aktif</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i>
                            Kaydet
                        </button>
                        <a href="{{ route('admin.mayor-content.index', ['type' => $type]) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i>
                            Geri Dön
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </section>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // TinyMCE for description
    if (typeof tinymce !== 'undefined') {
        tinymce.init({
            selector: '#description',
            height: 300,
            plugins: 'advlist autolink lists link image charmap print preview anchor searchreplace visualblocks code fullscreen insertdatetime media table paste code help wordcount',
            toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
        });
    }

    // File input labels
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });
});
</script>
@endpush
@endsection 