@extends('adminlte::page')

@section('title', 'Google Analytics API Kurulumu')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1><i class="fab fa-google mr-2"></i>Google Analytics API Kurulumu</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.analytics.index') }}">İstatistikler</a></li>
                <li class="breadcrumb-item active">API Kurulumu</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <!-- Bağlantı Durumu -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-wifi mr-2"></i>Bağlantı Durumu</h3>
                    <div class="card-tools">
                        <button class="btn btn-sm btn-primary" onclick="testConnection()">
                            <i class="fas fa-sync mr-1"></i>Bağlantıyı Test Et
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="connectionStatus">
                        @if($connectionStatus['status'] === 'success')
                            <div class="alert alert-success">
                                <h5><i class="fas fa-check mr-2"></i>Bağlantı Başarılı!</h5>
                                <p>{{ $connectionStatus['message'] }}</p>
                                <a href="{{ route('admin.analytics.index') }}" class="btn btn-success">
                                    <i class="fas fa-chart-line mr-2"></i>İstatistikleri Görüntüle
                                </a>
                            </div>
                        @else
                            <div class="alert alert-danger">
                                <h5><i class="fas fa-exclamation-triangle mr-2"></i>Bağlantı Hatası</h5>
                                <p>{{ $connectionStatus['message'] }}</p>
                                
                                @if(isset($connectionStatus['details']) && is_array($connectionStatus['details']))
                                    <ul class="mb-0">
                                        @foreach($connectionStatus['details'] as $key => $value)
                                            <li><strong>{{ ucfirst($key) }}:</strong> 
                                                <span class="badge badge-{{ $value === 'OK' ? 'success' : 'danger' }}">{{ $value }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kurulum Özeti -->
    <div class="row">
        <div class="col-12">
            <div class="alert alert-info">
                <h5><i class="fas fa-info-circle mr-2"></i>Kurulum Özeti</h5>
                <p class="mb-2">Google Analytics API kurulumu için <strong>2 ana işlem</strong> gerekli:</p>
                <ol class="mb-0">
                    <li><strong>Google Cloud Console:</strong> Service Account oluştur ve JSON al</li>
                    <li><strong>Google Analytics:</strong> Service Account'u property'ne ekle (İzin ver)</li>
                </ol>
            </div>
        </div>
    </div>

    <!-- Kurulum Adımları -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-list-ol mr-2"></i>API Kurulum Adımları</h3>
                </div>
                <div class="card-body">
                    
                    <!-- Adım 1: Google Cloud Console -->
                    <div class="timeline">
                        <div class="time-label">
                            <span class="bg-red">Adım 1</span>
                        </div>
                        <div>
                            <i class="fab fa-google bg-blue"></i>
                            <div class="timeline-item">
                                <h3 class="timeline-header">Google Cloud Console Projesi Oluşturun</h3>
                                <div class="timeline-body">
                                    <p>Google Analytics API'sını kullanmak için önce bir Google Cloud Console projesi oluşturmanız gerekir.</p>
                                    
                                    <ol>
                                        <li><strong>Google Cloud Console'a gidin:</strong><br>
                                            <a href="https://console.cloud.google.com" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-external-link-alt mr-1"></i>Google Cloud Console
                                            </a>
                                        </li>
                                        <li><strong>Yeni proje oluşturun</strong> veya mevcut bir projeyi seçin</li>
                                        <li><strong>Proje adı:</strong> Web Sitesi Analytics gibi açıklayıcı bir ad verin</li>
                                    </ol>
                                    
                                                        <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Not:</strong> Google hesabınızın yönetici yetkisine sahip olması gerekir.
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Önemli:</strong> Universal Analytics (UA) 1 Temmuz 2024'te kapandı. 
                        Sadece <strong>Google Analytics 4 (GA4)</strong> properties kullanılabilir.
                    </div>
                                </div>
                            </div>
                        </div>
                        
                                        <!-- Adım 2: Analytics API Etkinleştirme -->
                <div class="time-label">
                    <span class="bg-yellow">Adım 2</span>
                </div>
                <div>
                    <i class="fas fa-toggle-on bg-yellow"></i>
                    <div class="timeline-item">
                        <h3 class="timeline-header">Google Analytics API'lerini Etkinleştirin</h3>
                                <div class="timeline-body">
                                    <ol>
                                        <li><strong>API ve Hizmetler → Kütüphane</strong> bölümüne gidin</li>
                                                                <li><strong>"Google Analytics Data API"</strong> araması yapın (Ana veri çekme API'si)</li>
                        <li><strong>"ETKİNLEŞTİR"</strong> butonuna tıklayın</li>
                        <li>Ayrıca <strong>"Google Analytics Admin API"</strong> için de aynı işlemi yapın (Yönetim için)</li>
                                    </ol>
                                    
                                                        <a href="https://console.cloud.google.com/apis/library/analyticsdata.googleapis.com" target="_blank" class="btn btn-sm btn-outline-warning">
                        <i class="fas fa-external-link-alt mr-1"></i>Analytics Data API
                    </a>
                    <a href="https://console.cloud.google.com/apis/library/analyticsadmin.googleapis.com" target="_blank" class="btn btn-sm btn-outline-warning ml-2">
                        <i class="fas fa-external-link-alt mr-1"></i>Analytics Admin API
                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Adım 3: Service Account -->
                        <div class="time-label">
                            <span class="bg-green">Adım 3</span>
                        </div>
                        <div>
                            <i class="fas fa-user-cog bg-green"></i>
                            <div class="timeline-item">
                                <h3 class="timeline-header">Service Account Oluşturun</h3>
                                <div class="timeline-body">
                                    <ol>
                                        <li><strong>API ve Hizmetler → Kimlik Bilgileri</strong> bölümüne gidin</li>
                                        <li><strong>"+ KİMLİK BİLGİSİ OLUŞTUR"</strong> → <strong>"Service Account"</strong> seçin (OAuth 2.0 İstemci Kimliği DEĞİL!)</li>
                                                                <li><strong>Service Account Detayları:</strong>
                            <ul>
                                <li><strong>Ad:</strong> analytics-service</li>
                                <li><strong>Açıklama:</strong> Web sitesi analytics verilerini çekmek için</li>
                            </ul>
                        </li>
                        <li><strong>Rol:</strong> "Analytics Viewer" rolünü verin</li>
                        <li><strong>"BİTİR"</strong> butonuna tıklayın</li>
                        
                        <div class="alert alert-warning mt-2">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            <strong>Çok Önemli:</strong> Kimlik Bilgileri sayfasında 2 tip vardır:
                            <br>✅ <strong>Service Account</strong> (Doğru - Sunucu uygulamaları için)
                            <br>❌ <strong>OAuth 2.0 İstemci Kimliği</strong> (Yanlış - Web uygulamaları için)
                        </div>
                                    </ol>
                                    
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>
                                        <strong>Önemli:</strong> Service Account oluşturduktan sonra JSON anahtar dosyasını indirmeniz gerekecek.
                                    </div>
                                    
                                    <div class="alert alert-danger">
                                        <i class="fas fa-ban mr-2"></i>
                                        <strong>DİKKAT:</strong> <u>OAuth 2.0 İstemci Kimliği</u> seçmeyin! 
                                        Sadece <strong>Service Account</strong> seçin. OAuth JSON'u şu şekilde başlar: 
                                        <code>{"web":{"client_id"...}}</code> - Bu yanlış tip!
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Adım 4: JSON Key -->
                        <div class="time-label">
                            <span class="bg-purple">Adım 4</span>
                        </div>
                        <div>
                            <i class="fas fa-key bg-purple"></i>
                            <div class="timeline-item">
                                <h3 class="timeline-header">JSON Anahtar Dosyasını İndirin</h3>
                                <div class="timeline-body">
                                    <ol>
                                        <li><strong>Oluşturulan Service Account'a tıklayın</strong></li>
                                        <li><strong>"Anahtarlar" (Keys) sekmesine gidin</strong></li>
                                        <li><strong>"ANAHTAR EKLE" → "Yeni anahtar oluştur"</strong></li>
                                        <li><strong>Tür olarak "JSON" seçin</strong></li>
                                        <li><strong>"OLUŞTUR" butonuna tıklayın</strong></li>
                                        <li><strong>İndirilen dosyayı güvenli bir yere kaydedin</strong></li>
                                    </ol>
                                    
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <div class="alert alert-success">
                                                <h6><i class="fas fa-check mr-2"></i>Dosya Konumu:</h6>
                                                <code>{{ config('analytics.service_account_credentials_json') }}</code>
                                                <br><small class="text-muted">Uploads klasörü kullanılıyor</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="alert alert-info">
                                                <h6><i class="fas fa-info-circle mr-2"></i>Dosya Durumu:</h6>
                                                @if(file_exists(config('analytics.service_account_credentials_json')))
                                                    <span class="badge badge-success">✅ Mevcut</span>
                                                    <br><small class="text-muted">Son güncelleme: {{ date('d.m.Y H:i', filemtime(config('analytics.service_account_credentials_json'))) }}</small>
                                                @else
                                                    <span class="badge badge-danger">❌ Bulunamadı</span>
                                                    <br><small class="text-muted">JSON içeriğini yapıştırıp kaydedin</small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Adım 5: Analytics Property -->
                        <div class="time-label">
                            <span class="bg-info">Adım 5</span>
                        </div>
                        <div>
                            <i class="fas fa-chart-line bg-info"></i>
                            <div class="timeline-item">
                                <h3 class="timeline-header">Google Analytics'te Property ID'yi Bulun</h3>
                                <div class="timeline-body">
                                    <ol>
                                        <li><strong>Google Analytics hesabınıza gidin:</strong><br>
                                            <a href="https://analytics.google.com" target="_blank" class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-external-link-alt mr-1"></i>Google Analytics
                                            </a>
                                        </li>
                                        <li><strong>Yönetici (Admin) bölümüne gidin</strong></li>
                                        <li><strong>Property ayarlarını açın</strong></li>
                                        <li><strong>Property ID'yi kopyalayın</strong> (GA4 için örnek: 123456789)</li>
                                                                <li><strong>🔑 Service Account'u Analytics Property'sine Ekleyin (ÇOK ÖNEMLİ!):</strong>
                            <ol class="mt-2">
                                <li><strong>Google Analytics'e gidin:</strong> <a href="https://analytics.google.com" target="_blank">analytics.google.com</a></li>
                                <li><strong>Sol alt köşedeki "Yönetici" (Admin) ⚙️ simgesine tıklayın</strong></li>
                                <li><strong>"Property access management" seçin</strong></li>
                                <li><strong>Sağ üstte "+" butonuna tıklayın</strong></li>
                                <li><strong>Service Account email'ini yapıştırın:</strong> 
                                    <br><small class="text-muted">(JSON dosyasındaki "client_email" değeri - örnek: analytics-service@analytics-466315.iam.gserviceaccount.com)</small>
                                </li>
                                <li><strong>Rol seçin:</strong> "Viewer" yeterli (raporlama için)</li>
                                <li><strong>"Add" butonuna tıklayın</strong></li>
                            </ol>
                            
                            <div class="alert alert-danger mt-2">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <strong>Bu adım yapılmazsa:</strong> "PERMISSION_DENIED" hatası alırsınız!
                                <br>Service Account sadece Google Cloud'da tanımlı olması yetmez, 
                                Analytics property'sine de eklenmelidir.
                            </div>
                        </li>
                                    </ol>
                                    
                                    <div class="form-group mt-3">
                                        <label>Mevcut Property ID:</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" value="{{ config('analytics.property_id') ?: 'Belirtilmemiş' }}" readonly>
                                            <div class="input-group-append">
                                                @if(config('analytics.property_id'))
                                                    <span class="input-group-text bg-success text-white">
                                                        <i class="fas fa-check"></i>
                                                    </span>
                                                @else
                                                    <span class="input-group-text bg-danger text-white">
                                                        <i class="fas fa-times"></i>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <small class="form-text text-muted">
                                            Property ID'yi .env dosyasındaki ANALYTICS_PROPERTY_ID değişkenine ekleyin.
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Adım 6: Konfigürasyon -->
                        <div class="time-label">
                            <span class="bg-dark">Adım 6</span>
                        </div>
                        <div>
                            <i class="fas fa-cog bg-dark"></i>
                            <div class="timeline-item">
                                <h3 class="timeline-header">Laravel Konfigürasyonu</h3>
                                <div class="timeline-body">
                                    <h6>1. .env dosyasını düzenleyin:</h6>
                                    <pre class="bg-light p-3"><code>ANALYTICS_PROPERTY_ID=123456789</code></pre>
                                    
                                    <h6>2. JSON credentials'ları yapıştırın:</h6>
                                    <p>İndirdiğiniz JSON dosyasının içeriğini aşağıdaki alana yapıştırın:</p>
                                    
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="card-title mb-0">
                                                <i class="fas fa-paste mr-2"></i>Service Account JSON İçeriği
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <form id="credentialsForm">
                                                @csrf
                                                <div class="form-group">
                                                    <label for="jsonContent">JSON İçeriğini Buraya Yapıştırın:</label>
                                                    <textarea 
                                                        id="jsonContent" 
                                                        name="json_content" 
                                                        class="form-control" 
                                                        rows="10" 
                                                        placeholder='{
  "type": "service_account",
  "project_id": "analytics-466315",
  "private_key_id": "abc123...",
  "private_key": "-----BEGIN PRIVATE KEY-----\n...\n-----END PRIVATE KEY-----\n",
  "client_email": "analytics-service@analytics-466315.iam.gserviceaccount.com",
  "client_id": "123456789...",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/..."
}'
                                                        style="font-family: monospace; font-size: 12px;"
                                                    ></textarea>
                                                    <small class="form-text text-muted">
                                                        Google Cloud Console'dan indirdiğiniz JSON dosyasının tüm içeriğini kopyalayıp buraya yapıştırın.
                                                    </small>
                                                </div>
                                                
                                                <button type="button" class="btn btn-primary" onclick="saveCredentials()">
                                                    <i class="fas fa-save mr-2"></i>Credentials'ları Kaydet
                                                </button>
                                                
                                                <button type="button" class="btn btn-secondary ml-2" onclick="validateJson()">
                                                    <i class="fas fa-check mr-2"></i>JSON Formatını Kontrol Et
                                                </button>
                                            </form>
                                            
                                            <div id="credentialsResult" class="mt-3" style="display: none;"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="alert alert-success mt-3">
                                        <i class="fas fa-lightbulb mr-2"></i>
                                        <strong>Doğru JSON Formatı:</strong> Service Account JSON şu şekilde başlamalı:
                                        <br><code>{"type": "service_account", "project_id": "..."}</code>
                                        <br><small>OAuth JSON'u şöyle başlar: <code>{"web": {"client_id": "..."}}</code> ❌</small>
                                    </div>
                                    
                                    <h6>3. Otomatik İşlemler:</h6>
                                    <div class="alert alert-info">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        <strong>Otomatik yapılacaklar:</strong>
                                        <ul class="mb-0 mt-2">
                                            <li>JSON formatı kontrol edilecek</li>
                                            <li>Dosya otomatik olarak kaydedilecek</li>
                                            <li>Dosya izinleri (644) otomatik ayarlanacak</li>
                                            <li>Bağlantı testi otomatik çalışacak</li>
                                        </ul>
                                    </div>
                                    
                                    <div class="alert alert-warning">
                                        <i class="fas fa-shield-alt mr-2"></i>
                                        <strong>Güvenlik:</strong> Credentials dosyası .gitignore ile korunuyor.
                                    </div>
                                    
                                    <div class="alert alert-success mt-3">
                                        <i class="fas fa-lightbulb mr-2"></i>
                                        <strong>İpucu:</strong> Kurulum tamamlandıktan sonra "Bağlantıyı Test Et" butonunu kullanarak API'nin çalışıp çalışmadığını kontrol edin.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sorun Giderme -->
    <div class="row">
        <div class="col-12">
            <div class="card card-warning collapsed-card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-tools mr-2"></i>Sorun Giderme</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Yaygın Hatalar:</h5>
                            <ul>
                                <li><strong>PERMISSION_DENIED:</strong> Service Account Analytics property'sine eklenmemiş</li>
                                <li><strong>403 Forbidden:</strong> Service Account'a yeterli izin verilmemiş</li>
                                <li><strong>404 Not Found:</strong> Property ID yanlış</li>
                                <li><strong>JSON dosyası bulunamadı:</strong> Dosya yolu yanlış</li>
                                <li><strong>Invalid credentials:</strong> JSON dosyası bozuk</li>
                                <li><strong>OAuth JSON hatası:</strong> Service Account yerine OAuth JSON kullanılmış</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h5>Kontrol Listesi:</h5>
                            <ul>
                                <li>✅ Analytics Data API etkinleştirildi mi?</li>
                                <li>✅ Analytics Admin API etkinleştirildi mi?</li>
                                <li>✅ Service Account oluşturuldu mu?</li>
                                <li>✅ Service Account JSON (OAuth değil) mi?</li>
                                <li>✅ JSON credentials kaydedildi mi?</li>
                                <li>✅ Property ID doğru mu?</li>
                                <li>🔑 <strong>Service Account Analytics property'sine eklendi mi?</strong></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        function testConnection() {
            const button = event.target;
            const originalText = button.innerHTML;
            
            // Button'u devre dışı bırak ve loading göster
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Test Ediliyor...';
            
            fetch('{{ route('admin.analytics.test-connection') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                updateConnectionStatus(data);
                
                // Button'u eski haline getir
                button.disabled = false;
                button.innerHTML = originalText;
            })
            .catch(error => {
                console.error('Test bağlantısı hatası:', error);
                
                const statusDiv = document.getElementById('connectionStatus');
                statusDiv.innerHTML = `
                    <div class="alert alert-danger">
                        <h5><i class="fas fa-exclamation-triangle mr-2"></i>Test Hatası</h5>
                        <p>Bağlantı testi sırasında bir hata oluştu. Konsolu kontrol edin.</p>
                    </div>
                `;
                
                // Button'u eski haline getir
                button.disabled = false;
                button.innerHTML = originalText;
            });
        }
        
        function updateConnectionStatus(data) {
            const statusDiv = document.getElementById('connectionStatus');
            
            if (data.status === 'success') {
                statusDiv.innerHTML = `
                    <div class="alert alert-success">
                        <h5><i class="fas fa-check mr-2"></i>Bağlantı Başarılı!</h5>
                        <p>${data.message}</p>
                        <a href="{{ route('admin.analytics.index') }}" class="btn btn-success">
                            <i class="fas fa-chart-line mr-2"></i>İstatistikleri Görüntüle
                        </a>
                    </div>
                `;
            } else {
                let detailsHtml = '';
                if (data.details && Object.keys(data.details).length > 0) {
                    detailsHtml = '<ul class="mb-0">';
                    for (const [key, value] of Object.entries(data.details)) {
                        const badgeClass = value === 'OK' ? 'success' : 'danger';
                        detailsHtml += `<li><strong>${key.charAt(0).toUpperCase() + key.slice(1)}:</strong> <span class="badge badge-${badgeClass}">${value}</span></li>`;
                    }
                    detailsHtml += '</ul>';
                }
                
                statusDiv.innerHTML = `
                    <div class="alert alert-danger">
                        <h5><i class="fas fa-exclamation-triangle mr-2"></i>Bağlantı Hatası</h5>
                        <p>${data.message}</p>
                        ${detailsHtml}
                    </div>
                `;
            }
        }
        
        // JSON formatını kontrol et
        function validateJson() {
            const jsonContent = document.getElementById('jsonContent').value.trim();
            const resultDiv = document.getElementById('credentialsResult');
            
            if (!jsonContent) {
                showResult('warning', 'JSON içeriği boş!');
                return;
            }
            
            try {
                const parsed = JSON.parse(jsonContent);
                
                // OAuth2 client JSON kontrolü (yaygın hata)
                if (parsed.web && parsed.web.client_id) {
                    showResult('danger', `❌ Bu OAuth 2.0 İstemci Kimliği JSON'u!<br>
                        Google Cloud Console'da <strong>"Service Account"</strong> seçmelisiniz,<br>
                        <strong>"OAuth 2.0 İstemci Kimliği"</strong> değil!`);
                    return;
                }
                
                // Gerekli alanları kontrol et
                const requiredFields = ['type', 'project_id', 'private_key', 'client_email'];
                const missingFields = requiredFields.filter(field => !parsed[field]);
                
                if (missingFields.length > 0) {
                    showResult('danger', `Eksik alanlar: ${missingFields.join(', ')}<br>
                        <small>Bu bir Service Account JSON dosyası olmalı!</small>`);
                    return;
                }
                
                if (parsed.type !== 'service_account') {
                    showResult('danger', 'Bu bir Service Account JSON dosyası değil!<br>Google Cloud Console\'da "Service Account" oluşturun!');
                    return;
                }
                
                showResult('success', `✅ JSON formatı geçerli!<br>
                    <strong>Project ID:</strong> ${parsed.project_id}<br>
                    <strong>Service Account:</strong> ${parsed.client_email}`);
                    
            } catch (error) {
                showResult('danger', `JSON format hatası: ${error.message}`);
            }
        }
        
        // Credentials'ları kaydet
        function saveCredentials() {
            const jsonContent = document.getElementById('jsonContent').value.trim();
            const resultDiv = document.getElementById('credentialsResult');
            
            if (!jsonContent) {
                showResult('warning', 'JSON içeriği boş!');
                return;
            }
            
            // Önce JSON'ı validate et
            try {
                JSON.parse(jsonContent);
            } catch (error) {
                showResult('danger', `JSON format hatası: ${error.message}`);
                return;
            }
            
            // Kaydetme animasyonu
            showResult('info', '<i class="fas fa-spinner fa-spin"></i> Kaydediliyor...');
            
            fetch('{{ route('admin.analytics.save-credentials') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    json_content: jsonContent
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    showResult('success', `✅ ${data.message}<br>
                        <strong>Project ID:</strong> ${data.details.project_id}<br>
                        <strong>Service Account:</strong> ${data.details.client_email}<br>
                        <small class="text-muted">Dosya: ${data.details.file_path}</small>`);
                    
                    // 3 saniye sonra bağlantı testini otomatik çalıştır
                    setTimeout(() => {
                        testConnection();
                    }, 3000);
                } else {
                    showResult('danger', `❌ ${data.message}`);
                }
            })
            .catch(error => {
                showResult('danger', `Kaydetme hatası: ${error.message}`);
            });
        }
        
        // Sonuç gösterme fonksiyonu
        function showResult(type, message) {
            const resultDiv = document.getElementById('credentialsResult');
            resultDiv.style.display = 'block';
            resultDiv.innerHTML = `
                <div class="alert alert-${type}">
                    ${message}
                </div>
            `;
        }
    </script>
@stop 