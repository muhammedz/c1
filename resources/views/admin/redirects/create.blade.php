@extends('adminlte::page')

@section('title', 'Yeni Yönlendirme Oluştur')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Yeni Yönlendirme Oluştur</h1>
        <a href="{{ route('admin.redirects.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Geri Dön
        </a>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Yönlendirme Bilgileri</h3>
                </div>
                <form action="{{ route('admin.redirects.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="from_url">Kaynak URL <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('from_url') is-invalid @enderror" 
                                   id="from_url" 
                                   name="from_url" 
                                   value="{{ old('from_url', $fromUrl) }}" 
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
                                   value="{{ old('to_url') }}" 
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
                                <option value="301" {{ old('redirect_type', '301') == '301' ? 'selected' : '' }}>
                                    301 - Kalıcı Yönlendirme (Önerilen)
                                </option>
                                <option value="302" {{ old('redirect_type') == '302' ? 'selected' : '' }}>
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
                                      placeholder="Bu yönlendirme neden oluşturuldu? (Opsiyonel)">{{ old('description') }}</textarea>
                            <small class="form-text text-muted">
                                Yönlendirme sebebini açıklayın (opsiyonel).
                            </small>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Yönlendirme Oluştur
                        </button>
                        <a href="{{ route('admin.redirects.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> İptal
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Yardım Kartı -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-question-circle"></i> Yardım
                    </h3>
                </div>
                <div class="card-body">
                    <h6><strong>URL Formatları:</strong></h6>
                    <ul class="list-unstyled">
                        <li><code>/sayfa</code> - İç sayfa</li>
                        <li><code>/kategori/sayfa</code> - Alt sayfa</li>
                        <li><code>https://example.com</code> - Dış link</li>
                    </ul>

                    <h6><strong>Yönlendirme Tipleri:</strong></h6>
                    <ul class="list-unstyled">
                        <li><span class="badge badge-success">301</span> Kalıcı - SEO dostu</li>
                        <li><span class="badge badge-info">302</span> Geçici - Test amaçlı</li>
                    </ul>

                    <h6><strong>İpuçları:</strong></h6>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-lightbulb text-warning"></i> 301 kullanın (SEO için)</li>
                        <li><i class="fas fa-lightbulb text-warning"></i> URL'leri / ile başlatın</li>
                        <li><i class="fas fa-lightbulb text-warning"></i> Test edin</li>
                    </ul>
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
            <div class="card" id="test-card" style="display: none;">
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
        
        if (fromUrl && toUrl) {
            const previewHtml = `
                <strong>Kaynak:</strong> <code>${fromUrl}</code><br>
                <strong>Hedef:</strong> <code>${toUrl}</code><br>
                <strong>Tip:</strong> <span class="badge badge-${redirectType === '301' ? 'success' : 'info'}">${redirectType}</span>
                <br><br>
                <small class="text-muted">
                    ${fromUrl} adresine gelen ziyaretçiler ${toUrl} adresine yönlendirilecek.
                </small>
            `;
            $('#preview-content').html(previewHtml);
            $('#test-card').show();
        } else {
            $('#preview-content').html('<p class="text-muted">URL\'leri girin, önizleme burada görünecek.</p>');
            $('#test-card').hide();
        }
    }

    // URL değişikliklerini dinle
    $('#from_url, #to_url, #redirect_type').on('input change', updatePreview);

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