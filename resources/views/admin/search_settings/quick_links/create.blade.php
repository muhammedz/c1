@extends('adminlte::page')

@section('title', 'Yeni Hızlı Arama')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Yeni Hızlı Arama Ekle</h3>
                </div>
                
                <div class="card-body">
                    <!-- Hata Mesajları -->
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fas fa-ban"></i> Hata!</h5>
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form action="/admin/search-quick-links" method="POST">
                        @csrf
                        
                        <div class="form-group">
                            <label for="title">Başlık</label>
                            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="url">URL</label>
                            <input type="text" name="url" id="url" class="form-control @error('url') is-invalid @enderror" value="{{ old('url') }}" required>
                            <small class="form-text text-muted">Örnek: <code>/search?q=E-Belediye</code></small>
                            @error('url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="order">Sıralama</label>
                                    <input type="number" name="order" id="order" class="form-control @error('order') is-invalid @enderror" value="{{ old('order') }}" min="0">
                                    <small class="form-text text-muted">Boş bırakırsanız otomatik olarak en sona eklenecektir.</small>
                                    @error('order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mt-4">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="isActive" name="is_active" value="1" checked>
                                        <label class="custom-control-label" for="isActive">Aktif</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-right">
                            <a href="{{ route('admin.search-settings.index') }}" class="btn btn-secondary">İptal</a>
                            <button type="submit" class="btn btn-primary">Kaydet</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        console.log('Form ready');
        
        // Form submit olayını izle
        $('form').on('submit', function(e) {
            console.log('Form submit triggered');
            console.log('Form data:', $(this).serialize());
            // Form normal şekilde submit edilsin
        });
        
        // Switch değişikliklerini izle
        $('#isActive').on('change', function() {
            console.log('Active state changed:', $(this).prop('checked'));
        });
    });
</script>
@endsection 