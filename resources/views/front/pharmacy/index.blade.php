@extends('layouts.front')

@section('title', 'Çankaya Nöbetçi Eczaneler - Ankara')

@section('meta_description', 'Ankara Çankaya ilçesindeki nöbetçi eczaneleri sorgulayın. Güncel nöbetçi eczane listesi, adres ve telefon bilgileri.')

@section('css')
<style>
    :root {
        --primary-color: #00352b;
        --primary-light: rgba(0, 53, 43, 0.1);
        --primary-dark: #002a22;
        --secondary-color: #20846c;
        --accent-color: #e6a23c;
    }
    
    .pharmacy-container {
        max-width: 1280px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }
    
    .alert {
        padding: 1rem 1.5rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        font-weight: 500;
    }
    
    .alert-danger {
        background-color: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }
    
    .alert-info {
        background-color: #eff6ff;
        color: #2563eb;
        border: 1px solid #bfdbfe;
    }
    
    .pharmacy-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 1.5rem;
        margin-top: 2rem;
    }
    
    .pharmacy-card {
        background: white;
        border-radius: 8px;
        padding: 1.5rem;
        border: 1px solid #e2e8f0;
        border-left: 4px solid var(--primary-color);
    }
    
    .pharmacy-name {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .pharmacy-name i {
        font-size: 1.1rem;
        color: var(--secondary-color);
    }
    
    .pharmacy-info {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .info-item {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        font-size: 0.9rem;
        margin-bottom: 0.75rem;
    }
    
    .info-item:last-child {
        margin-bottom: 0;
    }
    
    .info-icon {
        color: var(--secondary-color);
        font-size: 1rem;
        margin-top: 0.1rem;
        flex-shrink: 0;
    }
    
    .info-text {
        color: #4a5568;
        line-height: 1.5;
    }
    
    .phone-link {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 500;
    }
    
    .phone-link:hover {
        color: var(--secondary-color);
        text-decoration: underline;
    }
    
    .address-link {
        color: #4a5568;
        text-decoration: none;
    }
    
    .address-link:hover {
        color: var(--primary-color);
        text-decoration: underline;
    }
    
    .address-link .fas.fa-external-link-alt {
        opacity: 0.6;
        margin-left: 0.3rem;
        font-size: 0.75rem;
    }
    
    .map-view-link {
        color: var(--secondary-color);
        text-decoration: none;
    }
    
    .map-view-link:hover {
        color: var(--primary-color);
        text-decoration: underline;
    }
    
    .no-results {
        text-align: center;
        padding: 3rem 2rem;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }
    
    .no-results-icon {
        font-size: 4rem;
        color: #9ca3af;
        margin-bottom: 1rem;
    }
    
    .no-results h3 {
        font-size: 1.5rem;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
    }
    
    .no-results p {
        color: #6b7280;
        font-size: 1.1rem;
    }
    
    @media (max-width: 768px) {
        .pharmacy-container {
            padding: 1rem;
        }
        
        .pharmacy-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .pharmacy-card {
            padding: 1.25rem;
        }
    }
</style>
@endsection

@section('content')
<!-- Hero Bölümü - Hizmetler sayfası tarzında -->
<div class="relative bg-gradient-to-r from-[#00352b] to-[#20846c] overflow-hidden">
    <div class="absolute inset-0 opacity-20">
        <!-- Pattern overlay -->
        <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" preserveAspectRatio="none">
            <defs>
                <pattern id="hero-pattern" width="40" height="40" patternUnits="userSpaceOnUse">
                    <path d="M0 20 L40 20 M20 0 L20 40" stroke="currentColor" stroke-width="1" fill="none" />
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#hero-pattern)" />
        </svg>
    </div>
    
    <!-- Dekoratif şekiller -->
    <div class="absolute -right-20 -bottom-20 w-64 h-64 rounded-full bg-[#e6a23c]/10 blur-3xl"></div>
    <div class="absolute -left-10 top-10 w-40 h-40 rounded-full bg-white/5 blur-2xl"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-10 relative z-10">
        <div class="flex flex-col items-center text-center">
            <div class="inline-block bg-white/10 backdrop-blur-sm px-4 py-1 rounded-full text-white/90 text-sm mb-3 border border-white/10">
                <span class="material-icons text-xs align-middle mr-1">local_pharmacy</span>
                <span>Sağlık Hizmetleri</span>
            </div>
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">
                Nöbetçi <span class="text-[#e6a23c]">Eczaneler</span>
            </h1>
            <p class="text-white/80 text-lg mb-5 max-w-4xl">
                Ankara Çankaya ilçesindeki nöbetçi eczanelerin güncel listesi, adres ve iletişim bilgileri.
            </p>
        </div>
    </div>
</div>

<div class="pharmacy-container">
    <!-- Debug Bilgileri Console'a yazdır -->
    @if(isset($debugInfo) && count($debugInfo) > 0)
        <script>
            console.group('🔍 Nöbetçi Eczane Debug Bilgileri');
            @foreach($debugInfo as $info)
                console.log('{{ addslashes($info) }}');
            @endforeach
            console.groupEnd();
        </script>
    @endif



    <!-- Hata Mesajları -->
    @if($error)
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle"></i>
            {{ $error }}
        </div>
    @endif

    <!-- Sonuçlar -->
    @if(count($pharmacies) > 0)
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            <strong>{{ count($pharmacies) }}</strong> adet nöbetçi eczane bulundu.
            <small>({{ $date }} tarihinde Ankara {{ $district }} ilçesi için)</small>
        </div>
        
        <div class="pharmacy-grid">
            @foreach($pharmacies as $pharmacy)
                <div class="pharmacy-card">
                    <div class="pharmacy-name">
                        <i class="fas fa-pills"></i>
                        {{ $pharmacy['name'] }}
                    </div>
                    
                    <div class="pharmacy-info">
                        @if($pharmacy['phone'])
                            <div class="info-item">
                                <i class="fas fa-phone info-icon"></i>
                                <a href="tel:{{ $pharmacy['phone'] }}" class="phone-link">
                                    {{ $pharmacy['phone'] }}
                                </a>
                            </div>
                        @endif
                        
                        @if($pharmacy['address'])
                            <div class="info-item">
                                <i class="fas fa-home info-icon"></i>
                                <a href="https://www.google.com/maps/search/{{ urlencode($pharmacy['address'] . ', ' . $pharmacy['district'] . ', Ankara') }}" 
                                   target="_blank" 
                                   class="info-text address-link"
                                   title="Google Haritalar'da görüntüle">
                                    {{ $pharmacy['address'] }}, {{ $pharmacy['district'] }}
                                    <i class="fas fa-external-link-alt" style="font-size: 0.8rem; margin-left: 0.5rem; opacity: 0.7;"></i>
                                </a>
                            </div>
                            
                            <div class="info-item">
                                <i class="fas fa-map-marked-alt info-icon"></i>
                                <a href="https://www.google.com/maps/search/{{ urlencode($pharmacy['address'] . ', ' . $pharmacy['district'] . ', Ankara') }}" 
                                   target="_blank" 
                                   class="info-text map-view-link"
                                   title="Haritada Görüntüle">
                                    Haritada Görüntüle
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @elseif(!$error)
        <div class="no-results">
            <div class="no-results-icon">
                <i class="fas fa-search"></i>
            </div>
            <h3>Sonuç Bulunamadı</h3>
            <p>{{ $date }} tarihinde Ankara {{ $district }} ilçesinde nöbetçi eczane bulunamadı.</p>
            <div style="margin-top: 1.5rem; padding: 1rem; background-color: #f8f9fa; border-radius: 8px; text-align: left;">
                <h4 style="color: var(--primary-color); margin-bottom: 0.5rem;">Olası Nedenler:</h4>
                <ul style="margin: 0; padding-left: 1.5rem; color: #6b7280;">
                    <li>Seçilen tarihte {{ $district }} ilçesinde nöbetçi eczane bulunmuyor olabilir</li>
                    <li>Resmi tatil günlerinde nöbetçi eczane dağılımı farklı olabilir</li>
                    <li>Veri kaynağından geçici olarak bilgi alınamıyor olabilir</li>
                </ul>
                <p style="margin-top: 1rem; margin-bottom: 0; color: #6b7280; font-size: 0.9rem;">
                    <i class="fas fa-lightbulb" style="color: #e6a23c;"></i>
                    <strong>Öneri:</strong> Farklı bir tarih seçerek tekrar deneyebilir veya diğer ilçelere bakabilirsiniz.
                </p>
            </div>
        </div>
        
        <!-- Cache temizleme ve sayfa yenileme script'i -->
        <script>
            // Sayfa yüklendiğinde cache temizleme işlemini başlat
            document.addEventListener('DOMContentLoaded', function() {
                // Cache temizleme API'sini çağır
                fetch('{{ url("/system/cache-clear/pharmacy-cache-clear-2025") }}', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Cache temizleme sonucu:', data);
                    
                    // 1 saniye bekle ve sayfayı yenile
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);
                })
                .catch(error => {
                    console.error('Cache temizleme hatası:', error);
                    
                    // Hata durumunda da sayfayı yenile
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);
                });
            });
        </script>
    @endif
</div>
@endsection 