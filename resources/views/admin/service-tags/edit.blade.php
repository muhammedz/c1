@extends('adminlte::page')

@section('title', 'Hizmet Etiketi Düzenle')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Hizmet Etiketi Düzenle</h1>
        <a href="{{ route('admin.service-tags.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Listeye Dön
        </a>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.service-tags.update', $serviceTag->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label for="name">Etiket Adı</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $serviceTag->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="slug">Slug</label>
                        <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $serviceTag->slug) }}">
                        @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Güncelle</button>
                </form>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(function() {
            $('#name').on('blur', function() {
                const name = $(this).val();
                if ($('#slug').val() === '' || $('#slug').val() === '{{ $serviceTag->slug }}') {
                    const slug = name.toString().toLowerCase()
                        .replace(/\s+/g, '-')           // Boşlukları tire ile değiştir
                        .replace(/[ğ]/g, 'g')           // Türkçe karakterleri dönüştür
                        .replace(/[ü]/g, 'u')
                        .replace(/[ş]/g, 's')
                        .replace(/[ı]/g, 'i')
                        .replace(/[ö]/g, 'o')
                        .replace(/[ç]/g, 'c')
                        .replace(/[^a-z0-9\-]/g, '')    // Alfanümerik ve tire dışındaki karakterleri kaldır
                        .replace(/\-\-+/g, '-')         // Birden fazla tireyi tek tireye dönüştür
                        .replace(/^-+/, '')             // Baştaki tireleri kaldır
                        .replace(/-+$/, '');            // Sondaki tireleri kaldır
                    
                    $('#slug').val(slug);
                }
            });
        });
    </script>
@stop 