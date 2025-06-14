@extends('adminlte::page')

@section('title', 'Yönlendirme Düzenle')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Yönlendirme Düzenle</h1>
        <div>
            <a href="{{ route('admin.redirects.show', $redirect) }}" class="btn btn-info">
                <i class="fas fa-eye"></i> Detay
            </a>
            <a href="{{ route('admin.redirects.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Geri Dön
            </a>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Yönlendirme Bilgileri</h3>
                </div>
                <form action="{{ route('admin.redirects.update', $redirect) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="from_url">Kaynak URL <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('from_url') is-invalid @enderror" 
                                   id="from_url" 
                                   name="from_url" 
                                   value="{{ old('from_url', $redirect->from_url) }}" 
                                   placeholder="/eski-sayfa"
                                   required>
                            <small class="form-text text-muted">
                                404 hatası veren URL. Örnek: /eski-sayfa veya /kategori/eski-makale
                            </small>
                            @error('from_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="to_url">Hedef URL <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('to_url') is-invalid @enderror" 
                                   id="to_url" 
                                   name="to_url" 
                                   value="{{ old('to_url', $redirect->to_url) }}" 
                                   placeholder="/yeni-sayfa veya https://example.com"
                                   required>
                            <small class="form-text text-muted">
                                Yönlendirilecek hedef URL. İç sayfa için / ile başlayın, dış link için tam URL yazın.
                            </small>
                            @error('to_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="redirect_type">Yönlendirme Tipi <span class="text-danger">*</span></label>
                            <select class="form-control @error('redirect_type') is-invalid @enderror" 
                                    id="redirect_type" 
                                    name="redirect_type" 
                                    required>
                                <option value="301" {{ old('redirect_type', $redirect->redirect_type) == '301' ? 'selected' : '' }}>
                                    301 - Kalıcı Yönlendirme (Önerilen)
                                </option>
                                <option value="302" {{ old('redirect_type', $redirect->redirect_type) == '302' ? 'selected' : '' }}>
                                    302 - Geçici Yönlendirme
                                </option>
                            </select>
                            <small class="form-text text-muted">
                                <strong>301:</strong> Kalıcı yönlendirme, SEO için önerilen.<br>
                                <strong>302:</strong> Geçici yönlendirme, test amaçlı kullanılır.
                            </small>
                            @error('redirect_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Açıklama</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="3" 
                                      placeholder="Bu yönlendirme neden oluşturuldu? (Opsiyonel)">{{ old('description', $redirect->description) }}</textarea>
                            <small class="form-text text-muted">
                                Yönlendirme sebebini açıklayın (opsiyonel).
                            </small>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" 
                                       class="custom-control-input" 
                                       id="is_active" 
                                       name="is_active" 
                                       value="1" 
                                       {{ old('is_active', $redirect->is_active) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">
                                    Yönlendirme Aktif
                                </label>
                            </div>
                            <small class="form-text text-muted">
                                Pasif yönlendirmeler çalışmaz.
                            </small>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Değişiklikleri Kaydet
                        </button>
                        <a href="{{ route('admin.redirects.show', $redirect) }}" class="btn btn-info">
                            <i class="fas fa-eye"></i> Detay
                        </a>
                        <a href="{{ route('admin.redirects.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> İptal
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Mevcut Bilgiler -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle"></i> Mevcut Bilgiler
                    </h3>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th>Hit Sayısı:</th>
                            <td>
                                <span class="badge badge-{{ $redirect->hit_count > 10 ? 'danger' : ($redirect->hit_count > 0 ? 'warning' : 'secondary') }}">
                                    {{ $redirect->hit_count }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Durum:</th>
                            <td>
                                @if($redirect->is_active)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-secondary">Pasif</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Oluşturan:</th>
                            <td>
                                @if($redirect->creator)
                                    {{ $redirect->creator->name }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Oluşturma:</th>
                            <td>{{ $redirect->created_at->format('d.m.Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Güncelleme:</th>
                            <td>{{ $redirect->updated_at->format('d.m.Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Önizleme Kartı -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-eye"></i> Önizleme
                    </h3>
                </div>
                <div class="card-body">
                    <div id="preview-content">
                        <p class="text-muted">URL'leri girin, önizleme burada görünecek.</p>
                    </div>
                </div>
            </div>

            <!-- Test Kartı -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-flask"></i> Test
                    </h3>
                </div>
                <div class="card-body">
                    <button type="button" class="btn btn-info btn-block" id="test-redirect">
                        <i class="fas fa-external-link-alt"></i> Yönlendirmeyi Test Et
                    </button>
                    <small class="text-muted">Test, yeni sekmede açılacak.</small>
                </div>
            </div>

            <!-- Hızlı İşlemler -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-bolt"></i> Hızlı İşlemler
                    </h3>
                </div>
                <div class="card-body">
                    <button type="button" class="btn btn-{{ $redirect->is_active ? 'secondary' : 'success' }} btn-block toggle-btn" 
                            data-id="{{ $redirect->id }}">
                        <i class="fas fa-{{ $redirect->is_active ? 'pause' : 'play' }}"></i> 
                        {{ $redirect->is_active ? 'Pasif Yap' : 'Aktif Yap' }}
                    </button>
                    
                    <button type="button" class="btn btn-danger btn-block delete-btn" data-id="{{ $redirect->id }}">
                        <i class="fas fa-trash"></i> Sil
                    </button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .form-text {
            font-size: 0.875rem;
        }
        code {
            background-color: #f8f9fa;
            padding: 2px 4px;
            border-radius: 3px;
            font-size: 0.875em;
        }
        #preview-content {
            font-family: monospace;
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #dee2e6;
        }
    </style>
@stop

@section('js')
<script>
$(document).ready(function() {
    // URL önizleme
    function updatePreview() {
        const fromUrl = $('#from_url').val();
        const toUrl = $('#to_url').val();
        const redirectType = $('#redirect_type').val();
        const isActive = $('#is_active').is(':checked');
        
        if (fromUrl && toUrl) {
            const previewHtml = `
                <strong>Kaynak:</strong> <code>${fromUrl}</code><br>
                <strong>Hedef:</strong> <code>${toUrl}</code><br>
                <strong>Tip:</strong> <span class="badge badge-${redirectType === '301' ? 'success' : 'info'}">${redirectType}</span><br>
                <strong>Durum:</strong> <span class="badge badge-${isActive ? 'success' : 'secondary'}">${isActive ? 'Aktif' : 'Pasif'}</span>
                <br><br>
                <small class="text-muted">
                    ${fromUrl} adresine gelen ziyaretçiler ${toUrl} adresine yönlendirilecek.
                </small>
            `;
            $('#preview-content').html(previewHtml);
        } else {
            $('#preview-content').html('<p class="text-muted">URL\'leri girin, önizleme burada görünecek.</p>');
        }
    }

    // URL değişikliklerini dinle
    $('#from_url, #to_url, #redirect_type, #is_active').on('input change', updatePreview);

    // Test butonu
    $('#test-redirect').click(function() {
        const fromUrl = $('#from_url').val();
        if (fromUrl) {
            const testUrl = `{{ url('') }}${fromUrl}`;
            window.open(testUrl, '_blank');
            toastr.info('Test URL\'si yeni sekmede açıldı');
        } else {
            toastr.warning('Önce kaynak URL\'yi girin!');
        }
    });

    // Toggle status
    $('.toggle-btn').click(function() {
        const id = $(this).data('id');
        
        $.post(`{{ route('admin.redirects.toggle', '') }}/${id}`, {
            _token: '{{ csrf_token() }}'
        })
        .done(function(response) {
            if (response.success) {
                toastr.success(response.message);
                location.reload();
            }
        })
        .fail(function() {
            toastr.error('Bir hata oluştu!');
        });
    });

    // Delete redirect
    $('.delete-btn').click(function() {
        const id = $(this).data('id');
        
        if (confirm('Bu yönlendirme kuralı silinsin mi?')) {
            $.ajax({
                url: `{{ route('admin.redirects.destroy', '') }}/${id}`,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                }
            })
            .done(function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    window.location.href = '{{ route('admin.redirects.index') }}';
                }
            })
            .fail(function() {
                toastr.error('Bir hata oluştu!');
            });
        }
    });

    // Form validasyonu
    $('form').on('submit', function(e) {
        const fromUrl = $('#from_url').val();
        const toUrl = $('#to_url').val();

        // URL formatı kontrolü
        if (fromUrl && !fromUrl.startsWith('/')) {
            toastr.warning('Kaynak URL / ile başlamalıdır!');
            e.preventDefault();
            return false;
        }

        // Aynı URL kontrolü
        if (fromUrl === toUrl) {
            toastr.warning('Kaynak ve hedef URL aynı olamaz!');
            e.preventDefault();
            return false;
        }
    });

    // Sayfa yüklendiğinde önizleme güncelle
    updatePreview();
});
</script>
@stop 