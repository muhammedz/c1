@extends('adminlte::page')

@section('title', 'Yeni Footer Linki')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1>{{ $menu->title }} - Yeni Link</h1>
            <small class="text-muted">Footer menüsü için yeni link oluşturun</small>
        </div>
        <a href="{{ route('admin.footer.menus.links.index', $menu) }}" class="btn btn-secondary">
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
                        <i class="fas fa-plus"></i> Link Bilgileri
                    </h3>
                </div>
                <form action="{{ route('admin.footer.menus.links.store', $menu) }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="title">Link Başlığı <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title') }}" 
                                   placeholder="Örn: Hakkımızda, İletişim" required>
                            @error('title')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">
                                Bu başlık footer'da link metni olarak görünecektir.
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="url">URL <span class="text-danger">*</span></label>
                            <input type="url" class="form-control @error('url') is-invalid @enderror" 
                                   id="url" name="url" value="{{ old('url') }}" 
                                   placeholder="https://example.com/sayfa" required>
                            @error('url')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">
                                Tam URL adresi girin (http:// veya https:// ile başlamalı).
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
                                Linkin menü içindeki görünme sırası. Küçük sayılar önce görünür.
                            </small>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" 
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Aktif</label>
                            </div>
                            <small class="form-text text-muted">
                                Pasif linkler footer'da görünmez.
                            </small>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Kaydet
                        </button>
                        <a href="{{ route('admin.footer.menus.links.index', $menu) }}" class="btn btn-secondary">
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
                        <i class="fas fa-info-circle"></i> Menü Bilgileri
                    </h3>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Menü:</strong></td>
                            <td>{{ $menu->title }}</td>
                        </tr>
                        <tr>
                            <td><strong>Mevcut Link Sayısı:</strong></td>
                            <td>{{ $menu->links->count() }}</td>
                        </tr>
                        <tr>
                            <td><strong>Aktif Link Sayısı:</strong></td>
                            <td>{{ $menu->activeLinks->count() }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-lightbulb"></i> İpuçları
                    </h3>
                </div>
                <div class="card-body">
                    <h6>Link Oluşturma İpuçları:</h6>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check text-success"></i> Kısa ve anlaşılır başlıklar kullanın</li>
                        <li><i class="fas fa-check text-success"></i> URL'nin doğru olduğundan emin olun</li>
                        <li><i class="fas fa-check text-success"></i> Sıralama numarasını dikkatli seçin</li>
                        <li><i class="fas fa-check text-success"></i> Test etmek için önizleme yapın</li>
                    </ul>

                    <hr>

                    <h6>URL Örnekleri:</h6>
                    <ul class="list-unstyled text-sm">
                        <li><code>https://example.com</code></li>
                        <li><code>https://example.com/hakkimizda</code></li>
                        <li><code>mailto:info@example.com</code></li>
                        <li><code>tel:+905551234567</code></li>
                    </ul>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-list"></i> Mevcut Linkler
                    </h3>
                </div>
                <div class="card-body">
                    @forelse($menu->links->take(10) as $link)
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-truncate" style="max-width: 150px;">{{ $link->title }}</span>
                            <span class="badge badge-secondary">{{ $link->order }}</span>
                        </div>
                    @empty
                        <p class="text-muted">Henüz link yok</p>
                    @endforelse

                    @if($menu->links->count() > 10)
                        <small class="text-muted">ve {{ $menu->links->count() - 10 }} link daha...</small>
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
                let url = $('#url').val().trim();
                
                if (title === '') {
                    e.preventDefault();
                    $('#title').addClass('is-invalid');
                    toastr.error('Link başlığı gereklidir');
                    return false;
                }
                
                if (url === '') {
                    e.preventDefault();
                    $('#url').addClass('is-invalid');
                    toastr.error('URL gereklidir');
                    return false;
                }
            });

            // URL validasyonu
            $('#url').on('blur', function() {
                let url = $(this).val().trim();
                if (url && !url.match(/^https?:\/\//) && !url.match(/^mailto:/) && !url.match(/^tel:/)) {
                    $(this).val('https://' + url);
                }
            });

            // URL önizleme
            $('#url').on('input', function() {
                let url = $(this).val().trim();
                if (url && url.match(/^https?:\/\//)) {
                    $('#url-preview').remove();
                    $(this).after('<small id="url-preview" class="form-text text-info"><i class="fas fa-external-link-alt"></i> <a href="' + url + '" target="_blank">Önizleme</a></small>');
                } else {
                    $('#url-preview').remove();
                }
            });
        });
    </script>
@stop