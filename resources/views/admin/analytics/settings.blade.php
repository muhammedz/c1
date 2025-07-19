@extends('adminlte::page')

@section('title', 'Google Analytics Ayarları')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1><i class="fab fa-google mr-2"></i>Google Analytics Ayarları</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Panel</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.analytics.index') }}">İstatistikler</a></li>
                <li class="breadcrumb-item active">Ayarlar</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="container-fluid">
    <!-- Bağlantı Durumu -->
    <div class="row">
        <div class="col-12">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-link mr-2"></i>API Bağlantı Durumu
                    </h3>
                    <div class="card-tools">
                        <button class="btn btn-sm btn-info" onclick="testConnection()">
                            <i class="fas fa-sync-alt"></i> Bağlantıyı Test Et
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="connection-status">
                        @if(isset($connectionStatus))
                            @if($connectionStatus['status'] === 'success')
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    <strong>Bağlantı Başarılı!</strong> Google Analytics API'sine erişim sağlandı.
                                    <br><small class="text-muted">Son test: {{ now()->format('d.m.Y H:i') }}</small>
                                </div>
                            @else
                                <div class="alert alert-danger">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    <strong>Bağlantı Hatası:</strong> {{ $connectionStatus['message'] ?? 'Bilinmeyen hata' }}
                                </div>
                            @endif
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-clock mr-2"></i>
                                Bağlantı durumu kontrol edilmedi. "Bağlantıyı Test Et" butonuna tıklayın.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mevcut Konfigürasyon -->
    <div class="row">
        <div class="col-md-8">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-cog mr-2"></i>Mevcut Konfigürasyon
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <dl>
                                <dt>Property ID:</dt>
                                <dd>
                                    <code>{{ $currentConfig['property_id'] ?: 'Belirtilmemiş' }}</code>
                                    @if($currentConfig['property_id'])
                                        <span class="badge badge-success ml-2">✓ Tanımlı</span>
                                    @else
                                        <span class="badge badge-danger ml-2">✗ Eksik</span>
                                    @endif
                                </dd>

                                <dt>Cache Süresi:</dt>
                                <dd>
                                    <code>{{ $currentConfig['cache_lifetime'] }} dakika</code>
                                    <span class="badge badge-info ml-2">{{ $currentConfig['cache_lifetime'] / 60 }} saat</span>
                                </dd>
                            </dl>
                        </div>
                        <div class="col-md-6">
                            <dl>
                                <dt>Credentials Dosyası:</dt>
                                <dd>
                                    <small class="text-muted d-block mb-1">{{ $currentConfig['credentials_path'] }}</small>
                                    @if($currentConfig['credentials_exists'])
                                        <span class="badge badge-success">✓ Mevcut</span>
                                        <small class="text-muted ml-2">
                                            {{ date('d.m.Y H:i', filemtime($currentConfig['credentials_path'])) }}
                                        </small>
                                    @else
                                        <span class="badge badge-danger">✗ Dosya bulunamadı</span>
                                    @endif
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hızlı İşlemler -->
        <div class="col-md-4">
            <div class="card card-outline card-warning">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-tools mr-2"></i>Hızlı İşlemler
                    </h3>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-warning btn-sm mb-2" onclick="clearCache()">
                            <i class="fas fa-trash mr-2"></i>Cache Temizle
                        </button>
                        
                        <a href="https://analytics.google.com" target="_blank" class="btn btn-danger btn-sm">
                            <i class="fab fa-google mr-2"></i>Google Analytics
                            <i class="fas fa-external-link-alt ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Credentials Güncelleme -->
    <div class="row">
        <div class="col-12">
            <div class="card card-outline card-success">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-key mr-2"></i>Service Account JSON Güncelleme
                    </h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Not:</strong> Google Cloud Console'dan aldığınız Service Account JSON dosyasının içeriğini buraya yapıştırın.
                    </div>
                    
                    <form id="credentials-form">
                        @csrf
                        <div class="form-group">
                            <label for="json_content">Service Account JSON İçeriği:</label>
                            <textarea 
                                class="form-control font-monospace" 
                                id="json_content" 
                                name="json_content" 
                                rows="15" 
                                placeholder='{
  "type": "service_account",
  "project_id": "your-project-id",
  "private_key_id": "...",
  "private_key": "-----BEGIN PRIVATE KEY-----\n...\n-----END PRIVATE KEY-----\n",
  "client_email": "analytics-service@your-project.iam.gserviceaccount.com",
  "client_id": "...",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  ...
}'></textarea>
                            <small class="form-text text-muted">
                                JSON formatında olmalı ve "type": "service_account" içermelidir.
                            </small>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save mr-2"></i>JSON Dosyasını Güncelle
                            </button>
                            <button type="button" class="btn btn-secondary ml-2" onclick="validateJson()">
                                <i class="fas fa-check mr-2"></i>JSON Doğrula
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Cache İstatistikleri -->
    <div class="row">
        <div class="col-12">
            <div class="card card-outline card-secondary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-database mr-2"></i>Cache İstatistikleri
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="info-box bg-info">
                                <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Cache Süresi</span>
                                    <span class="info-box-number">{{ $currentConfig['cache_lifetime'] }}dk</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-success">
                                <span class="info-box-icon"><i class="fas fa-memory"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Cache Driver</span>
                                    <span class="info-box-number">{{ config('cache.default') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-warning">
                                <span class="info-box-icon"><i class="fas fa-server"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Queue Driver</span>
                                    <span class="info-box-number">{{ config('queue.default') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-danger">
                                <span class="info-box-icon"><i class="fas fa-sync-alt"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Son Güncelleme</span>
                                    <span class="info-box-number text-sm">Manual</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
/**
 * API bağlantısını test et
 */
function testConnection() {
    const statusDiv = document.getElementById('connection-status');
    
    // Loading göster
    statusDiv.innerHTML = `
        <div class="alert alert-info">
            <i class="fas fa-spinner fa-spin mr-2"></i>
            Bağlantı test ediliyor...
        </div>
    `;
    
    fetch('{{ route('admin.analytics.test-connection') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            statusDiv.innerHTML = `
                <div class="alert alert-success">
                    <i class="fas fa-check-circle mr-2"></i>
                    <strong>Bağlantı Başarılı!</strong> ${data.message}
                    <br><small class="text-muted">Test zamanı: ${new Date().toLocaleString('tr-TR')}</small>
                </div>
            `;
        } else {
            statusDiv.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <strong>Bağlantı Hatası:</strong> ${data.message}
                    ${data.details ? '<br><small class="text-muted">' + data.details + '</small>' : ''}
                </div>
            `;
        }
    })
    .catch(error => {
        statusDiv.innerHTML = `
            <div class="alert alert-danger">
                <i class="fas fa-times-circle mr-2"></i>
                <strong>İstek Hatası:</strong> Sunucuya erişilemiyor.
                <br><small class="text-muted">${error.message}</small>
            </div>
        `;
    });
}

/**
 * Cache temizle
 */
function clearCache() {
    if (!confirm('Analytics cache temizlensin mi? Bu işlem birkaç dakika sürebilir.')) {
        return;
    }
    
    fetch('{{ route('admin.analytics.clear-cache') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            toastr.success(data.message);
        } else {
            toastr.error(data.message);
        }
    })
    .catch(error => {
        toastr.error('Cache temizleme hatası: ' + error.message);
    });
}

/**
 * JSON doğrula
 */
function validateJson() {
    const jsonContent = document.getElementById('json_content').value.trim();
    
    if (!jsonContent) {
        toastr.warning('JSON içeriği boş olamaz.');
        return;
    }
    
    try {
        const parsed = JSON.parse(jsonContent);
        
        // Service Account kontrolleri
        const requiredFields = ['type', 'project_id', 'private_key', 'client_email'];
        const missingFields = requiredFields.filter(field => !parsed[field]);
        
        if (missingFields.length > 0) {
            toastr.error('Eksik alanlar: ' + missingFields.join(', '));
            return;
        }
        
        if (parsed.type !== 'service_account') {
            toastr.error('Bu bir Service Account JSON dosyası değil!');
            return;
        }
        
        if (parsed.web && parsed.web.client_id) {
            toastr.error('Bu OAuth 2.0 İstemci JSON\'u! Service Account JSON gerekli.');
            return;
        }
        
        toastr.success('✅ JSON formatı geçerli ve Service Account dosyası!');
        
    } catch (error) {
        toastr.error('Geçersiz JSON formatı: ' + error.message);
    }
}

/**
 * Credentials form submit
 */
document.getElementById('credentials-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const jsonContent = document.getElementById('json_content').value.trim();
    
    if (!jsonContent) {
        toastr.warning('JSON içeriği boş olamaz.');
        return;
    }
    
    // JSON doğrula
    try {
        JSON.parse(jsonContent);
    } catch (error) {
        toastr.error('Geçersiz JSON formatı!');
        return;
    }
    
    // Loading göster
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Kaydediliyor...';
    submitBtn.disabled = true;
    
    fetch('{{ route('admin.analytics.save-credentials') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            json_content: jsonContent
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            toastr.success(data.message);
            // Sayfayı yenile
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        } else {
            toastr.error(data.message);
        }
    })
    .catch(error => {
        toastr.error('Kaydetme hatası: ' + error.message);
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});
</script>
@stop 