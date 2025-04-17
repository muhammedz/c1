@extends('adminlte::page')

@section('title', 'Yeni Kurumsal Kadro Kategorisi Ekle')

@section('content_header')
    <h1>Yeni Kurumsal Kadro Kategorisi Ekle</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Yeni Kurumsal Kadro Kategorisi Ekle</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.corporate.categories.index') }}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Listeye Dön
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.corporate.categories.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="name">Kategori Adı <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="slug">Slug</label>
                            <input type="text" class="form-control" id="slug" name="slug" value="{{ old('slug') }}">
                            <small class="text-muted">Boş bırakırsanız, isimden otomatik oluşturulacaktır.</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Açıklama</label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="image">Kategori Görseli</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="image" name="image">
                                    <label class="custom-file-label" for="image">Dosya Seç</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="order">Sıralama</label>
                            <input type="number" class="form-control" id="order" name="order" value="{{ old('order', 0) }}">
                        </div>
                        
                        <div class="form-group">
                            <label for="status">Durum</label>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="status" name="status" value="1" {{ old('status', '1') == '1' ? 'checked' : '' }}>
                                <label class="custom-control-label" for="status">Aktif</label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Kaydet</button>
                            <a href="{{ route('admin.corporate.categories.index') }}" class="btn btn-secondary">İptal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
    $(document).ready(function () {
        // Slug oluşturucu
        $('#name').on('blur', function () {
            const name = $(this).val();
            const slug = $('#slug').val();
            
            if (!slug && name) {
                const slugified = name.toLowerCase()
                    .replace(/[^\w ]+/g, '')
                    .replace(/ +/g, '-');
                $('#slug').val(slugified);
            }
        });
        
        // Input file görselleştirme
        bsCustomFileInput.init();
    });
</script>
@stop 