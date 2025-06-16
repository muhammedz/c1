@extends('layouts.front')

@section('title', $mudurluk->name)

@section('meta_description', $mudurluk->meta_description ?? Str::limit(strip_tags($mudurluk->summary), 160))

@section('css')
<style>
    :root {
        --header-height: 96px;
        --primary-color: #00352b;
        --primary-light: rgba(0, 53, 43, 0.1);
        --primary-dark: #002a22;
        --secondary-color: #20846c;
        --accent-color: #e6a23c;
    }
    
    .service-layout-container {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 1rem;
    }
    
    .service-grid-layout {
        display: grid;
        grid-template-columns: 300px 1fr;
        gap: 2rem;
        margin-top: -20px;
        align-items: flex-start;
    }
    
    .service-grid-layout::before {
        content: "";
        display: block;
        grid-column: 1;
        min-height: 1px;
    }
    
    .service-content-area {
        grid-column: 2;
        padding-top: 0;
    }
    
    .service-content-section {
        background-color: white;
        border-radius: 12px;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
        padding: 30px;
        margin-bottom: 30px;
        border-top: 4px solid var(--primary-color);
    }
    
    .service-content-section h2 {
        font-size: 24px;
        font-weight: 700;
        color: var(--primary-color);
        margin-top: 0;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #e5e7eb;
    }
    
    @media (max-width: 768px) {
        .service-grid-layout {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .service-content-area {
            grid-column: 1;
        }
        
        .service-grid-layout::before {
            display: none;
        }
    }

    /* İçindekiler stillerini sol tarafta sticky olacak şekilde düzenle */
    #sidebar {
        grid-column: 1;
        align-self: flex-start;
        position: relative;
        width: 300px;
        margin-top: 0;
    }

    #sidebar .sticky-container {
        width: 100%;
        position: relative;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        transition: none;
        max-height: calc(100vh - 120px);
        overflow-y: auto;
        padding: 30px;
    }

    #sidebar.scrolledDown .sticky-container {
        position: fixed;
        top: 20px;
        width: 300px;
        z-index: 40;
    }

    #sidebar .sticky-container::-webkit-scrollbar {
        width: 4px;
    }

    #sidebar .sticky-container::-webkit-scrollbar-track {
        background: #f5f5f5;
        border-radius: 10px;
    }

    #sidebar .sticky-container::-webkit-scrollbar-thumb {
        background: #00352b;
        border-radius: 10px;
    }

    @media (max-width: 768px) {
        #sidebar {
            display: none;
        }
        
        #sidebar.active {
            display: block;
            position: fixed;
            top: 15px;
            left: 15px;
            right: 15px;
            width: auto;
            z-index: 100;
        }
        
        #sidebar.scrolledDown .sticky-container {
            position: relative;
            top: 0;
            left: 0 !important;
            width: 100% !important;
            transform: none;
        }
    }

    /* File item styles */
    .file-item {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 15px;
        transition: all 0.3s ease;
    }

    .file-item:hover {
        border-color: var(--primary-color);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 53, 43, 0.1);
    }

    .stats-card {
        background: white;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        border: 1px solid #e5e7eb;
    }

    .stats-number {
        font-size: 2rem;
        font-weight: bold;
        color: var(--primary-color);
    }

    .stats-label {
        color: #6b7280;
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }
</style>
@endsection

@section('content')
<!-- Hero Bölümü -->
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
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
            <div class="md:col-span-2">
                <div class="inline-block bg-white/10 backdrop-blur-sm px-4 py-1 rounded-full text-white/90 text-sm mb-3 border border-white/10">
                    <span class="material-icons text-xs align-middle mr-1">account_balance</span>
                    <span>Belediye Müdürlükleri</span>
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">{{ $mudurluk->name }}</h1>
                <p class="text-white/80 text-lg mb-5">{{ $mudurluk->summary ?? 'Belediyemizin önemli müdürlüklerinden biri olan bu birim ile ilgili tüm detayları bu sayfada bulabilirsiniz.' }}</p>
                
                <div class="flex flex-wrap gap-4">
                    @if($mudurluk->gorev_tanimi_ve_faaliyet_alani)
                    <a href="#gorev-tanimi" class="inline-flex items-center px-5 py-2.5 bg-[#e6a23c] text-white rounded-md hover:bg-[#e6a23c]/90 transition-colors font-medium shadow-lg shadow-[#e6a23c]/20">
                        <span class="material-icons mr-2 text-sm">assignment</span>
                        Görev Tanımı
                    </a>
                    @endif
                    @if($mudurluk->files->where('is_active', true)->count() > 0)
                    <a href="#dosyalar" class="inline-flex items-center px-5 py-2.5 bg-white/10 text-white border border-white/20 rounded-md hover:bg-white/20 transition-colors shadow-lg shadow-black/5">
                        <span class="material-icons mr-2 text-sm">download</span>
                        Dosyaları İndir
                    </a>
                    @endif
                </div>
            </div>
            <div class="hidden md:flex justify-end">
                <div class="bg-white/10 backdrop-blur-sm p-6 rounded-lg border border-white/20 shadow-lg">
                    <div class="text-white text-center">
                        <span class="material-icons text-4xl text-[#e6a23c] mb-2">business</span>
                        <h3 class="text-xl font-semibold mb-2">Müdürlük Bilgileri</h3>
                        <p class="text-sm text-white/80">Bilgi almak için bizi arayabilirsiniz.</p>
                        <div class="mt-4 pt-4 border-t border-white/10">
                            <div class="flex items-center justify-center text-[#e6a23c]">
                                <span class="material-icons mr-2">phone</span>
                                <span class="font-bold">444 06 01</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Ana İçerik Bölümü -->
<div class="service-layout-container pt-2">
    <div class="service-grid-layout">
        <!-- Sol Sidebar Menü (İçindekiler) -->
        <div id="sidebar">
            <div class="sticky-container bg-white rounded-lg border-t-4 border-[#00352b]">
                <h3 class="text-2xl font-bold mb-4 text-[#00352b] pb-4 border-b border-gray-200">İçindekiler</h3>
                <nav class="flex flex-col space-y-2">
                    @if($mudurluk->gorev_tanimi_ve_faaliyet_alani)
                    <a href="#gorev-tanimi" class="flex items-center px-4 py-2 rounded-lg text-gray-600 hover:bg-[#00352b]/10 hover:text-[#00352b] font-medium transition-colors">
                        <i class="material-icons mr-3 text-lg">assignment</i>
                        Görev Tanımı ve Faaliyet Alanı
                    </a>
                    @endif
                    @if($mudurluk->yetki_ve_sorumluluklar)
                    <a href="#yetki-sorumluluk" class="flex items-center px-4 py-2 rounded-lg text-gray-600 hover:bg-[#00352b]/10 hover:text-[#00352b] font-medium transition-colors">
                        <i class="material-icons mr-3 text-lg">security</i>
                        Yetki ve Sorumluluklar
                    </a>
                    @endif
                    @if($mudurluk->files->where('is_active', true)->count() > 0)
                    <a href="#dosyalar" class="flex items-center px-4 py-2 rounded-lg text-gray-600 hover:bg-[#00352b]/10 hover:text-[#00352b] font-medium transition-colors">
                        <i class="material-icons mr-3 text-lg">description</i>
                        Dosyalar ve Dökümanlar
                    </a>
                    @endif
                    @if($relatedServices->count() > 0)
                    <a href="#ilgili-hizmetler" class="flex items-center px-4 py-2 rounded-lg text-gray-600 hover:bg-[#00352b]/10 hover:text-[#00352b] font-medium transition-colors">
                        <i class="material-icons mr-3 text-lg">room_service</i>
                        İlgili Hizmetler
                    </a>
                    @endif
                </nav>
            </div>
        </div>
        
        <!-- Sağ İçerik Alanı -->
        <div class="service-content-area">
            <!-- Görev Tanımı ve Faaliyet Alanı -->
            @if($mudurluk->gorev_tanimi_ve_faaliyet_alani)
            <section id="gorev-tanimi" class="service-content-section">
                <h2>Görev Tanımı ve Faaliyet Alanı</h2>
                {!! $mudurluk->gorev_tanimi_ve_faaliyet_alani !!}
            </section>
            @endif
            
            <!-- Yetki ve Sorumluluklar -->
            @if($mudurluk->yetki_ve_sorumluluklar)
            <section id="yetki-sorumluluk" class="service-content-section">
                <h2>Yetki ve Sorumluluklar</h2>
                {!! $mudurluk->yetki_ve_sorumluluklar !!}
            </section>
            @endif
            
            <!-- Dosyalar ve Dökümanlar -->
            @if($mudurluk->files->where('is_active', true)->count() > 0)
            <section id="dosyalar" class="service-content-section">
                <h2>Dosyalar ve Dökümanlar</h2>
                <p class="mb-5">{{ $mudurluk->name }} müdürlüğüne ait resmi belgeler ve dökümanları aşağıdan indirebilirsiniz:</p>
                
                                                <!-- Hizmet Standartları -->
                @php
                    $hizmetStandartlari = $mudurluk->files->where('type', 'hizmet_standartlari')->where('is_active', true);
                @endphp
                @if($hizmetStandartlari->count() > 0)
                    <div class="mt-6">
                        <h3 class="font-semibold text-lg text-gray-800 mb-4">Hizmet Standartları</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            @foreach($hizmetStandartlari as $file)
                            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-all">
                                <div class="p-5 flex flex-col h-full">
                                    <div class="flex items-center mb-3">
                                        <span class="material-icons text-[#00352b] mr-2">description</span>
                                        <h3 class="font-semibold text-gray-800">{{ $file->title }}</h3>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-4">{{ $file->file_name }} • {{ number_format($file->file_size / 1024, 1) }} KB</p>
                                    <div class="mt-auto flex items-center">
                                        <a href="{{ route('mudurlukler.download-file', [$mudurluk->slug, $file]) }}" class="inline-flex items-center text-[#00352b] hover:text-[#20846c] transition-colors">
                                            <span class="material-icons mr-1 text-sm">file_download</span>
                                            <span class="text-sm font-medium">İndir</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Yönetim Şemaları -->
                @php
                    $yonetimSemalari = $mudurluk->files->where('type', 'yonetim_semalari')->where('is_active', true);
                @endphp
                @if($yonetimSemalari->count() > 0)
                    <div class="mt-6">
                        <h3 class="font-semibold text-lg text-gray-800 mb-4">Yönetim Şemaları</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            @foreach($yonetimSemalari as $file)
                            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-all">
                                <div class="p-5 flex flex-col h-full">
                                    <div class="flex items-center mb-3">
                                        <span class="material-icons text-[#00352b] mr-2">description</span>
                                        <h3 class="font-semibold text-gray-800">{{ $file->title }}</h3>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-4">{{ $file->file_name }} • {{ number_format($file->file_size / 1024, 1) }} KB</p>
                                    <div class="mt-auto flex items-center">
                                        <a href="{{ route('mudurlukler.download-file', [$mudurluk->slug, $file]) }}" class="inline-flex items-center text-[#00352b] hover:text-[#20846c] transition-colors">
                                            <span class="material-icons mr-1 text-sm">file_download</span>
                                            <span class="text-sm font-medium">İndir</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Diğer Belgeler -->
                @php
                    $digerBelgeler = $mudurluk->files->where('type', 'document')->where('is_active', true);
                @endphp
                @if($digerBelgeler->count() > 0)
                    <div class="mt-6">
                        <h3 class="font-semibold text-lg text-gray-800 mb-4">Diğer Belgeler</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            @foreach($digerBelgeler as $file)
                            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-all">
                                <div class="p-5 flex flex-col h-full">
                                    <div class="flex items-center mb-3">
                                        <span class="material-icons text-[#00352b] mr-2">description</span>
                                        <h3 class="font-semibold text-gray-800">{{ $file->title }}</h3>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-4">{{ $file->file_name }} • {{ number_format($file->file_size / 1024, 1) }} KB</p>
                                    <div class="mt-auto flex items-center">
                                        <a href="{{ route('mudurlukler.download-file', [$mudurluk->slug, $file]) }}" class="inline-flex items-center text-[#00352b] hover:text-[#20846c] transition-colors">
                                            <span class="material-icons mr-1 text-sm">file_download</span>
                                            <span class="text-sm font-medium">İndir</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </section>
            @endif
            
            <!-- İlgili Hizmetler -->
            @if($relatedServices->count() > 0)
            <section id="ilgili-hizmetler" class="service-content-section">
                <h2>İlgili Hizmetler</h2>
                <p class="mb-5">{{ $mudurluk->name }} müdürlüğümüzün sunduğu hizmetler ve vatandaşlarımıza sağladığı imkanlar:</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($relatedServices as $service)
                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-all">
                        <div class="p-5 flex flex-col h-full">
                            <div class="flex items-center mb-3">
                                <span class="material-icons text-[#00352b] mr-2">room_service</span>
                                <h3 class="font-semibold text-gray-800">{{ $service->title }}</h3>
                            </div>
                            @if($service->summary)
                            <p class="text-sm text-gray-600 mb-4 flex-grow">{{ Str::limit($service->summary, 120) }}</p>
                            @endif
                            <div class="mt-auto flex items-center justify-between">
                                <a href="{{ route('services.show', $service->slug) }}" class="inline-flex items-center text-[#00352b] hover:text-[#20846c] transition-colors">
                                    <span class="text-sm font-medium">Detayları Gör</span>
                                    <span class="material-icons ml-1 text-sm">arrow_forward</span>
                                </a>
                                @if($service->published_at)
                                <span class="text-xs text-gray-500">{{ $service->published_at->format('d.m.Y') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>
            @endif
            

        </div>
    </div>
</div>
@endsection

@section('after_scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Basit smooth scroll davranışı için
        const menuLinks = document.querySelectorAll('#sidebar nav a');
        const sidebarElement = document.getElementById('sidebar');
        const contentArea = document.querySelector('.service-content-area');
        const footer = document.querySelector('footer') || document.querySelector('.footer');
        const lastSection = contentArea.querySelector('.service-content-section:last-child');
        
        // Sidebar offset pozisyonunu hesapla
        let sidebarOffset = sidebarElement.getBoundingClientRect().top + window.scrollY;
        
        // Sidebar'ın pozisyon ve boyutlarını güncelleyen fonksiyon
        function updateSidebarMetrics() {
            // scrolledDown sınıfını geçici olarak kaldır
            const wasScrolledDown = sidebarElement.classList.contains('scrolledDown');
            if (wasScrolledDown) {
                sidebarElement.classList.remove('scrolledDown');
                
                // Inline stilleri temizle
                const stickyContainer = sidebarElement.querySelector('.sticky-container');
                if (stickyContainer) {
                    stickyContainer.removeAttribute('style'); 
                }
            }
            
            // Ölçümleri yeniden hesapla
            sidebarOffset = sidebarElement.getBoundingClientRect().top + window.scrollY;
            
            // scrolledDown sınıfını geri ekle
            if (wasScrolledDown && window.scrollY > sidebarOffset - 20) {
                // scrolledDown ekle
                sidebarElement.classList.add('scrolledDown');
            }
        }
        
        // Sayfa yüklendiğinde ve pencere boyutu değiştiğinde metrikleri güncelle
        updateSidebarMetrics();
        
        // Pencere boyutu değiştiğinde offset'i yeniden hesapla
        window.addEventListener('resize', function() {
            updateSidebarMetrics();
        });
        
        menuLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                const targetSection = document.querySelector(targetId);
                
                if (targetSection) {
                    // Sayfı hedef bölüme doğru kaydır
                    window.scrollTo({
                        top: targetSection.offsetTop - 20, 
                        behavior: 'smooth'
                    });
                }
            });
        });
        
        // Sidebar'ın sayfa kaydırıldıkça davranışı
        window.addEventListener('scroll', function() {
            const currentScrollY = window.scrollY;
            const windowHeight = window.innerHeight;
            const stickyContainer = sidebarElement.querySelector('.sticky-container');
            
            // Son bölümün bitimini tam olarak hesapla (varsa)
            let exactContentBottom = 0;
            if (lastSection) {
                const lastSectionRect = lastSection.getBoundingClientRect();
                exactContentBottom = lastSectionRect.bottom + window.scrollY;
            } else {
                // Son bölüm yoksa genel içerik alanı bitimini kullan
                exactContentBottom = contentArea.offsetTop + contentArea.offsetHeight;
            }
            
            // Sayfa sonuna yaklaştık mı kontrol et
            const stickyContainerHeight = stickyContainer ? stickyContainer.offsetHeight : 0;
            
            // Sidebar'ın hizalı olması gereken pozisyonu hesapla
            const targetPosition = exactContentBottom - stickyContainerHeight;
            
            // Sidebar'ın içerik alanı bitiminde durması gereken koşulu belirle
            const shouldStopSticky = currentScrollY > targetPosition - 20;
            
            // Footer'ın pozisyonunu hesapla (eğer footer varsa)
            let footerTop = Number.MAX_SAFE_INTEGER;
            if (footer) {
                footerTop = footer.getBoundingClientRect().top + window.scrollY;
            }
            
            if (currentScrollY > sidebarOffset - 20) {
                if (!sidebarElement.classList.contains('scrolledDown')) {
                    sidebarElement.classList.add('scrolledDown');
                }
                
                if (stickyContainer) {
                    // Sticky container'ın sol pozisyonunu güncelle
                    const sidebarRect = sidebarElement.getBoundingClientRect();
                    
                    // İçerik alanı bittiyse içindekiler menüsünü durdur
                    if (shouldStopSticky) {
                        // İçerik alanı sonunda sabit kal
                        sidebarElement.classList.remove('scrolledDown');
                        stickyContainer.style.position = 'absolute';
                        
                        // Hassas hizalama için direkt son bölümün alt konumunu kullan 
                        // (sidebarOffset değerine göre olan mesafe hesaplanır)
                        const exactOffset = exactContentBottom - stickyContainerHeight - sidebarOffset;
                        stickyContainer.style.top = exactOffset + 'px';
                        stickyContainer.style.left = '0';
                    } else {
                        // Footer'a yaklaşırken konumu ayarla (footer varmak ve çakışma olacaksa)
                        const footerDistance = footerTop - (currentScrollY + stickyContainerHeight + 20);
                        
                        if (footerDistance < 0 && footerTop < exactContentBottom) {
                            // Footer görünür olduğunda, içindekiler menüsünün bottom pozisyonunu ayarla
                            stickyContainer.style.position = 'fixed';
                            stickyContainer.style.top = 'auto';
                            stickyContainer.style.bottom = Math.abs(footerDistance) + 20 + 'px';
                            stickyContainer.style.left = sidebarRect.left + 'px';
                        } else {
                            // Normal sticky pozisyon
                            stickyContainer.style.position = 'fixed';
                            stickyContainer.style.top = '20px';
                            stickyContainer.style.bottom = 'auto';
                            stickyContainer.style.left = sidebarRect.left + 'px';
                        }
                    }
                }
            } else {
                // Sticky davranışını deaktif et
                if (sidebarElement.classList.contains('scrolledDown')) {
                    sidebarElement.classList.remove('scrolledDown');
                    
                    if (stickyContainer) {
                        stickyContainer.removeAttribute('style');
                    }
                }
            }
            
            // Mobil görünümde sidebar davranışı
            if (window.innerWidth <= 768) {
                if (currentScrollY > 200) {
                    // Aşağı kaydırıldıysa sidebar'ı göster
                    sidebarElement.classList.add('active');
                } else {
                    // Sayfanın üst kısmındaysa sidebar'ı gizle
                    sidebarElement.classList.remove('active');
                }
            }
        });

        // View count increment (silent)
        fetch('{{ route("mudurlukler.show", $mudurluk->slug) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'increment_view'
            })
        }).catch(() => {});
    });
</script>
@endsection 