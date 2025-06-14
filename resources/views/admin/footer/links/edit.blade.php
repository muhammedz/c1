@extends('adminlte::page')

@section('title', 'Footer Linki Düzenle')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1>{{ $menu->title }} - Link Düzenle</h1>
            <small class="text-muted">Footer menü linkini düzenleyin</small>
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
                        <i class="fas fa-edit"></i> Link Bilgileri
                    </h3>
                </div>
                <form id="updateForm" action="{{ route('admin.footer.menus.links.update', [$menu, $link]) }}" method="POST">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="title">Link Başlığı <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title', $link->title) }}" 
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
                                   id="url" name="url" value="{{ old('url', $link->url) }}" 
                                   placeholder="https://example.com/sayfa" required>
                            @error('url')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">
                                Tam URL adresi girin (http:// veya https:// ile başlamalı).
                                <br><strong>Debug:</strong> Mevcut değer: {{ $link->url }}
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="order">Sıralama <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('order') is-invalid @enderror" 
                                   id="order" name="order" value="{{ old('order', $link->order) }}" 
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
                                       {{ old('is_active', $link->is_active) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Aktif</label>
                            </div>
                            <small class="form-text text-muted">
                                Pasif linkler footer'da görünmez.
                            </small>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Güncelle
                        </button>
                        <a href="{{ route('admin.footer.menus.links.index', $menu) }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> İptal
                        </a>
                        <div class="float-right">
                            <!-- Silme butonu geçici olarak kaldırıldı -->
                            <span class="text-muted">Silme işlemi geçici olarak devre dışı</span>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle"></i> Link Bilgileri
                    </h3>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Menü:</strong></td>
                            <td>{{ $menu->title }}</td>
                        </tr>
                        <tr>
                            <td><strong>Oluşturulma:</strong></td>
                            <td>{{ $link->created_at->format('d.m.Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Son Güncelleme:</strong></td>
                            <td>{{ $link->updated_at->format('d.m.Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Durum:</strong></td>
                            <td>
                                @if($link->is_active)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-secondary">Pasif</span>
                                @endif
                            </td>
                        </tr>
                    </table>

                    <hr>

                    <h6>Mevcut URL:</h6>
                    <div class="mb-3">
                        <a href="{{ $link->url }}" target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-external-link-alt"></i> Linki Test Et
                        </a>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-lightbulb"></i> İpuçları
                    </h3>
                </div>
                <div class="card-body">
                    <h6>Link Düzenleme İpuçları:</h6>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check text-success"></i> Değişikliklerden önce linki test edin</li>
                        <li><i class="fas fa-check text-success"></i> URL'nin doğru olduğundan emin olun</li>
                        <li><i class="fas fa-check text-success"></i> Başlığı kısa ve anlaşılır tutun</li>
                        <li><i class="fas fa-check text-success"></i> Sıralama değişikliği diğer linkleri etkiler</li>
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
                        <i class="fas fa-list"></i> Aynı Menüdeki Diğer Linkler
                    </h3>
                </div>
                <div class="card-body">
                    @foreach($menu->links->where('id', '!=', $link->id)->take(10) as $otherLink)
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <a href="{{ route('admin.footer.menus.links.edit', [$menu, $otherLink]) }}" class="text-decoration-none text-truncate" style="max-width: 150px;">
                                {{ $otherLink->title }}
                            </a>
                            <span class="badge badge-secondary">{{ $otherLink->order }}</span>
                        </div>
                    @endforeach

                    @if($menu->links->where('id', '!=', $link->id)->count() > 10)
                        <small class="text-muted">ve {{ $menu->links->where('id', '!=', $link->id)->count() - 10 }} link daha...</small>
                    @endif

                    @if($menu->links->where('id', '!=', $link->id)->count() == 0)
                        <p class="text-muted">Bu menüde başka link yok</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Sayfa yüklendiğinde URL field'ının değerini logla
            console.log('Page loaded, URL field value:', $('#url').val());
            
            // Ana form'u seç (güncelleme formu)
            $('#updateForm').on('submit', function(e) {
                console.log('Update form submitted');
                
                let title = $('#title').val().trim();
                let url = $('#url').val().trim();
                
                console.log('Form submit - Title:', title, 'URL:', url);
                
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
                
                // Method spoofing kontrolü
                let methodField = $(this).find('input[name="_method"]');
                if (!methodField.length || methodField.val() !== 'PUT') {
                    console.error('PUT method spoofing field missing or incorrect!', methodField.val());
                    e.preventDefault();
                    toastr.error('Form method hatası - PUT method eksik');
                    return false;
                }
                
                console.log('Method field found:', methodField.val());
                
                console.log('Form validation passed, submitting...');
            });

            // URL validasyonu - sadece boş değilse ve geçerli protokol yoksa https ekle
            $('#url').on('blur', function() {
                let url = $(this).val().trim();
                console.log('URL blur event - Original URL:', url);
                if (url && url !== '#' && !url.match(/^https?:\/\//) && !url.match(/^mailto:/) && !url.match(/^tel:/) && !url.match(/^#/)) {
                    $(this).val('https://' + url);
                    console.log('URL blur event - Modified URL:', $(this).val());
                }
            });

            // URL önizleme - güvenli hale getirildi
            $('#url').on('input', function() {
                let url = $(this).val().trim();
                $('#url-preview').remove();
                if (url && url.match(/^https?:\/\//)) {
                    $(this).after('<small id="url-preview" class="form-text text-info"><i class="fas fa-external-link-alt"></i> <a href="' + url + '" target="_blank">Önizleme</a></small>');
                }
            });
        });
    </script>
@stop 