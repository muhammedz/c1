@extends('adminlte::page')

@section('title', 'Yeni Footer Menüsü')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Yeni Footer Menüsü</h1>
        <a href="{{ route('admin.footer.menus.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Geri Dön
        </a>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plus"></i> Menü Bilgileri
                    </h3>
                </div>
                <form action="{{ route('admin.footer.menus.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="title">Menü Başlığı <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title') }}" 
                                   placeholder="Örn: KURUMSAL, HİZMETLERİMİZ" required>
                            @error('title')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">
                                Bu başlık footer'da menü başlığı olarak görünecektir.
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="order">Sıralama <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('order') is-invalid @enderror" 
                                   id="order" name="order" value="{{ old('order', 1) }}" 
                                   min="1" required>
                            @error('order')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">
                                Menünün footer'da görünme sırası. Küçük sayılar önce görünür.
                            </small>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" 
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Aktif</label>
                            </div>
                            <small class="form-text text-muted">
                                Pasif menüler footer'da görünmez.
                            </small>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Kaydet
                        </button>
                        <a href="{{ route('admin.footer.menus.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> İptal
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle"></i> Bilgi
                    </h3>
                </div>
                <div class="card-body">
                    <h5>Menü Oluşturma İpuçları:</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check text-success"></i> Menü başlığını büyük harflerle yazın</li>
                        <li><i class="fas fa-check text-success"></i> Anlaşılır ve kısa başlıklar kullanın</li>
                        <li><i class="fas fa-check text-success"></i> Sıralama numarasını dikkatli seçin</li>
                        <li><i class="fas fa-check text-success"></i> Menü oluşturduktan sonra linklerini ekleyin</li>
                    </ul>

                    <hr>

                    <h6>Mevcut Menüler:</h6>
                    @if($existingMenus = \App\Models\FooterMenu::ordered()->get())
                        @forelse($existingMenus as $menu)
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span>{{ $menu->title }}</span>
                                <span class="badge badge-secondary">{{ $menu->order }}</span>
                            </div>
                        @empty
                            <p class="text-muted">Henüz menü yok</p>
                        @endforelse
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Form validasyonu
            $('form').on('submit', function(e) {
                let title = $('#title').val().trim();
                if (title === '') {
                    e.preventDefault();
                    $('#title').addClass('is-invalid');
                    toastr.error('Menü başlığı gereklidir');
                    return false;
                }
            });

            // Başlık otomatik büyük harf
            $('#title').on('input', function() {
                $(this).val($(this).val().toUpperCase());
            });
        });
    </script>
@stop 