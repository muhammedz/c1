@extends('adminlte::page')

@section('title', 'Yeni Menü Oluştur')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Yeni Menü Oluştur</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Panel</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.menusystem.index') }}">Menü Yönetimi</a></li>
                <li class="breadcrumb-item active">Yeni Menü</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.menusystem.store') }}" method="POST" id="menu-form">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Menü Adı <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type">Menü Tipi <span class="text-danger">*</span></label>
                                    <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                                        <option value="">Seçiniz</option>
                                        <option value="1" {{ old('type') == '1' ? 'selected' : '' }}>Küçük Menü (Header/Footer)</option>
                                        <option value="2" {{ old('type') == '2' ? 'selected' : '' }}>Büyük Menü (Kategori Alt Başlıklı)</option>
                                        <option value="3" {{ old('type') == '3' ? 'selected' : '' }}>Buton Menü</option>
                                    </select>
                                    @error('type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="url">URL (Opsiyonel)</label>
                                    <input type="text" class="form-control @error('url') is-invalid @enderror" id="url" name="url" value="{{ old('url') }}">
                                    <small class="form-text text-muted">Menü direkt bir bağlantıya yönlendirilecekse doldurun</small>
                                    @error('url')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="order">Sıralama</label>
                                    <input type="number" class="form-control @error('order') is-invalid @enderror" id="order" name="order" value="{{ old('order', 0) }}" min="0">
                                    @error('order')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Durumu</label>
                                    <div class="custom-control custom-switch mt-2">
                                        <input type="checkbox" class="custom-control-input" id="status" name="status" value="1" {{ old('status', '1') == '1' ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="status">Aktif</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Açıklama (Opsiyonel)</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        
                        <div class="text-right">
                            <button type="submit" class="btn btn-success waves-effect waves-light">Kaydet</button>
                            <a href="{{ route('admin.menusystem.index') }}" class="btn btn-secondary waves-effect">İptal</a>
                        </div>
                    </form>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Menü tipi değiştiğinde özel alanları göster/gizle
        $('#type').change(function() {
            const menuType = $(this).val();
            
            if (menuType === '1') {
                // Küçük menü için URL alanını göster
                $('#url').closest('.form-group').show();
            } else if (menuType === '2') {
                // Büyük menü için URL alanını temizle ve gizle
                $('#url').val('').closest('.form-group').hide();
            } else if (menuType === '3') {
                // Buton menü için URL alanını göster
                $('#url').closest('.form-group').show();
                
                // Buton menü bilgisi göster
                if (!$('.button-menu-info').length) {
                    const infoHTML = `
                    <div class="alert alert-info button-menu-info">
                        <i class="fas fa-info-circle mr-1"></i> Buton menü seçtiniz. Menüyü oluşturduktan sonra, buton öğelerini ekleyebileceksiniz.
                        <ul class="mt-2 mb-0">
                            <li>Her butona ikon ekleyebilirsiniz</li>
                            <li>Butonlar için kısa açıklamalar ekleyebilirsiniz</li>
                            <li>Butonlar kare şeklinde görüntülenecektir</li>
                        </ul>
                    </div>`;
                    
                    $('.form-group').last().after(infoHTML);
                }
            } else {
                // Menü tipi seçilmediğinde buton menü bilgisini kaldır
                $('.button-menu-info').remove();
            }
        });
        
        // Sayfa yüklendiğinde mevcut seçime göre düzenle
        $('#type').trigger('change');
        
        // Form gönderimi
        $('#menu-form').submit(function(e) {
            const menuType = $('#type').val();
            
            // Minimum gerekli alanları kontrol et
            if (!$('#name').val()) {
                e.preventDefault();
                Swal.fire({
                    title: 'Uyarı!',
                    text: 'Menü adı alanı zorunludur.',
                    icon: 'warning',
                    confirmButtonText: 'Tamam'
                });
                return;
            }
            
            if (!menuType) {
                e.preventDefault();
                Swal.fire({
                    title: 'Uyarı!',
                    text: 'Menü tipi seçilmelidir.',
                    icon: 'warning',
                    confirmButtonText: 'Tamam'
                });
                return;
            }
        });
    });
</script>
@endsection 