<!-- Header kısmı buraya gelecek -->
<!-- #### Header Section -->
<section id="header-section" class="header-section relative w-full h-24 pt-1 bg-white" style="z-index: 999997;">
    <div class="head w-full h-24 bg-white relative" style="z-index: 999997;">
        <nav class="container max-w-7xl h-full mx-auto px-4">
            <div class="flex items-center justify-between h-24">
                <!-- Logo Alanı -->
                <div class="flex items-center text-[#00352b] space-x-10 md:space-x-10 space-x-2">
                    <div class="text-xl font-bold">
                        <a href="/">
                            <img src="{{ asset('images/logo-cankaya.png') }}" alt="Çankaya Belediyesi Logo" class="h-16 md:h-16 h-12">
                        </a>
                    </div>
                </div>

                <!-- Desktop Menü -->
                <div class="hidden md:flex items-center h-full space-x-1">
                    @if(isset($mainMenuItems) && $mainMenuItems->count() > 0)
                        @foreach($mainMenuItems as $menu)
                            @if($menu->type == 1)
                                <!-- Küçük menü için sadece link göster -->
                                <div class="relative h-full flex items-center">
                                    <a href="{{ $menu->url ?? '#' }}"
                                        class="text-[#00352b] h-full px-3 font-semibold hover:text-gray-900 text-md flex items-center">
                                        {{ $menu->name }}
                                    </a>
                                </div>
                            @else
                                <!-- Büyük menü için mega menü göster -->
                                <div class="group relative h-full flex items-center">
                                    <a href="{{ $menu->url ?? '#' }}"
                                        class="text-[#00352b] h-full px-3 font-semibold hover:text-gray-900 text-md flex items-center">
                                        {{ $menu->name }}
                                        <span class="material-icons text-sm ml-1">expand_more</span>
                                    </a>
                                    <div class="mega-menu">
                                        <div class="mega-menu-content mega-menu-kurumsal">
                                            <div class="py-2 px-1">
                                                <div class="flex mb-2">
                                                    <div class="w-full">
                                                        @if($menu->type == 3)
                                                        <!-- Modern Yatay Buton Tipi Menü Görünümü -->
                                                        <div class="grid grid-cols-2 gap-3 py-3">
                                                            @php
                                                                $menuItems = app(\App\Services\HeaderService::class)->getMenuItems($menu->id);
                                                                $iconMapping = [
                                                                    'Adalet' => 'gavel',
                                                                    'Çevre, Tarım ve Hayvancılık' => 'agriculture',
                                                                    'Devlet ve Mevzuat' => 'account_balance',
                                                                    'Eğitim' => 'school',
                                                                    'Genel Bilgiler' => 'menu_book',
                                                                    'Güvenlik' => 'security',
                                                                    'İş ve Kariyer' => 'business',
                                                                    'Kişisel Bilgiler' => 'badge',
                                                                    'Sağlık' => 'favorite',
                                                                    'Sosyal Güvenlik ve Sigorta' => 'umbrella',
                                                                    'Şikayet ve Bilgi Edinme' => 'contact_support',
                                                                    'Telekomünikasyon' => 'settings_phone',
                                                                    'Trafik ve Ulaşım' => 'directions_car',
                                                                    'Vergi, Harç ve Cezalar' => 'payments'
                                                                ];
                                                            @endphp
                                                            
                                                            @foreach($menuItems as $index => $item)
                                                                <!-- Modern Kategori Buton Tasarımı -->
                                                                <a href="{{ $item->url ?? '#' }}" class="category-button-modern group relative overflow-hidden bg-gradient-to-br from-gray-50 to-gray-100 hover:from-gray-100 hover:to-gray-150 border-gray-200 flex items-center p-3 rounded-lg border-2 shadow-sm hover:shadow-md transition-all duration-300 transform hover:scale-102 hover:-translate-y-0.5">
                                                                    <div class="icon-container w-10 h-10 flex items-center justify-center rounded-full mr-3 flex-shrink-0 bg-white/70 group-hover:bg-white/90 transition-all duration-300 group-hover:rotate-3 group-hover:scale-105">
                                                                        <i class="{{ $item->icon ?? 'fas fa-file-alt' }} text-[#007b32] text-lg group-hover:text-[#00352b] transition-colors duration-300"></i>
                                                                    </div>
                                                                    <div class="flex flex-col flex-1 min-w-0">
                                                                        <span class="font-semibold text-[#00352b] text-sm group-hover:text-[#007b32] transition-colors duration-300 leading-tight">{{ $item->title }}</span>
                                                                        <span class="text-xs text-gray-600 mt-0.5 opacity-0 group-hover:opacity-100 transition-opacity duration-300">Detayları görüntüle</span>
                                                                    </div>
                                                                    <!-- Hover overlay efekti -->
                                                                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 transform -skew-x-12 translate-x-full group-hover:translate-x-[-100%] transition-transform duration-700"></div>
                                                                    <!-- Sağ ok ikonu -->
                                                                    <div class="ml-2 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-1 group-hover:translate-x-0">
                                                                        <i class="fas fa-arrow-right text-[#007b32] text-base"></i>
                                                                    </div>
                                                                </a>
                                                            @endforeach
                                                        </div>
                                                        @else
                                                        <!-- Normal Liste Tipi Menü -->
                                                        <div class="grid grid-cols-1 gap-3">
                                                            @php
                                                                $menuItems = app(\App\Services\HeaderService::class)->getMenuItems($menu->id);
                                                            @endphp
                                                            
                                                            @foreach($menuItems as $item)
                                                                <!-- BELEDİYE MAKAMLARI -->
                                                                <div class="mega-menu-category">
                                                                    <h3>{{ $item->title }}</h3>
                                                                    @if($item->children && $item->children->count() > 0)
                                                                        <ul class="space-y-0.5">
                                                                            @foreach($item->children as $subItem)
                                                                                <li>
                                                                                    <a href="{{ $subItem->url ?? '#' }}" class="mega-menu-link" {{ $subItem->new_tab ? 'target="_blank"' : '' }}>
                                                                                        <i class="{{ $subItem->icon ?? 'fas fa-user' }} mega-menu-link-icon"></i>
                                                                                        <span>{{ $subItem->title }}</span>
                                                                                    </a>
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="mt-2 pt-2 border-t border-gray-200">
                                                    <div class="flex justify-between items-center">
                                                        <p class="text-xs text-gray-500">{{ $menu->description ?? $menu->footer_text ?? 'Açıklama Yazısı' }}</p>
                                                        <a href="{{ $menu->footer_link ?? '#' }}" class="text-[#00352b] hover:text-[#007b32] text-sm font-medium flex items-center gap-1 transition-all hover:gap-2">
                                                            {{ $menu->name }} 
                                                            <i class="fas fa-arrow-right text-sm"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @else
                        <!-- Örnek Sabit Menüler -->
                        <div class="relative h-full flex items-center">
                            <a href="/haberler" class="text-[#00352b] h-full px-3 font-semibold hover:text-gray-900 text-md flex items-center">
                                Haberler
                            </a>
                        </div>
                        <div class="relative h-full flex items-center">
                            <a href="/sayfalar" class="text-[#00352b] h-full px-3 font-semibold hover:text-gray-900 text-md flex items-center">
                                Sayfalar
                            </a>
                        </div>
                        <div class="relative h-full flex items-center">
                            <a href="/hedefkitleler" class="text-[#00352b] h-full px-3 font-semibold hover:text-gray-900 text-md flex items-center">
                                Hedef Kitleler
                            </a>
                        </div>
                        <div class="relative h-full flex items-center">
                            <a href="/iletisim" class="text-[#00352b] h-full px-3 font-semibold hover:text-gray-900 text-md flex items-center">
                                İletişim
                            </a>
                        </div>
                        
                        <!-- Kurumsal Mega Menü -->
                        <div class="group relative h-full flex items-center">
                            <a href="#"
                                class="text-[#00352b] h-full px-3 font-semibold hover:text-gray-900 text-md flex items-center">
                                Menü Adı
                                <i class="fas fa-chevron-down text-sm ml-1"></i>
                            </a>
                            <div class="mega-menu">
                                <div class="mega-menu-content mega-menu-kurumsal">
                                    <div class="py-2 px-1">
                                        <div class="flex mb-2">
                                            <div class="w-full">
                                                <div class="grid grid-cols-1 gap-3">
                                                    <!-- BELEDİYE MAKAMLARI -->
                                                    <div class="mega-menu-category">
                                                        <h3>Alt Başlık</h3>
                                                        <ul class="space-y-0.5">
                                                            <li>
                                                                <a href="#" class="mega-menu-link">
                                                                    <i class="fas fa-user mega-menu-link-icon"></i>
                                                                    <span>Alt Menü 1</span>
                                                                </a>
                                                            </li>
                                                            
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-2 pt-2 border-t border-gray-200">
                                            <div class="flex justify-between items-center">
                                                <p class="text-xs text-gray-500">Örnek açıklama yazısı</p>
                                                <a href="#" class="text-[#00352b] hover:text-[#007b32] text-sm font-medium flex items-center gap-1 transition-all hover:gap-2">
                                                    Menü Adı 
                                                    <i class="fas fa-arrow-right text-sm"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                
                <!-- Arama İkonu, Slogan ve Atatürk Simgesi -->
                <div class="flex items-center md:space-x-4 space-x-2">
                    <!-- Arama butonu - sadece desktop'ta görünür -->
                    <button id="searchButton" class="hidden md:flex w-11 h-11 bg-[#007b32] rounded-full items-center justify-center text-white shadow-md hover:bg-[#00352b] hover:scale-105 transition-all">
                        <i class="fas fa-search text-white text-xl"></i>
                    </button>
                    
                    <!-- Slogan - sadece desktop'ta görünür -->
                    <div class="text-md text-[#00352b] font-bold hidden md:block">
                        <img src="{{ asset('images/slogan.png') }}" alt="Çankaya Belediyesi Slogan" class="h-12">
                    </div>
                    
                    <!-- Mobil Menü Butonu - sadece mobilde görünür -->
                    <button class="md:hidden flex items-center justify-center bg-gray-100 rounded-md p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-200 focus:outline-none transition-all"
                        id="mobileMenuButton">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <!-- Atatürk simgesi - her zaman görünür, mobilde menünün sağında -->
                    <div class="text-xl font-bold relative flex items-end pb-1 z-10">
                        <img src="{{ asset('images/simge.png') }}" alt="Atatürk" class="md:h-24 h-20">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobil Menü (Küçük ekranlarda görünür) -->
    <div class="md:hidden mobile-menu-hidden w-full absolute bg-white shadow-lg rounded-b-lg" id="mobileMenu" style="z-index: 9999; top: 100%;">
        <!-- Mobil Menü İçeriği -->
        <div class="mobile-menu-content">
            @if(isset($mainMenuItems) && $mainMenuItems->count() > 0)
                @foreach($mainMenuItems as $menu)
                    <!-- Sadece ana menü linkleri göster -->
                    <a href="{{ $menu->url ?? '#' }}" class="mobile-menu-item">
                        <i class="fas fa-circle text-[#007b32] text-xs mr-3"></i>
                        <span>{{ $menu->name }}</span>
                        <i class="fas fa-chevron-right text-gray-400 text-sm ml-auto"></i>
                    </a>
                @endforeach
            @else
                <!-- Varsayılan menü öğeleri -->
                <a href="{{ route('front.home') }}" class="mobile-menu-item">
                    <i class="fas fa-home text-[#007b32] text-sm mr-3"></i>
                    <span>Ana Sayfa</span>
                    <i class="fas fa-chevron-right text-gray-400 text-sm ml-auto"></i>
                </a>
                <a href="#" class="mobile-menu-item">
                    <i class="fas fa-building text-[#007b32] text-sm mr-3"></i>
                    <span>Kurumsal</span>
                    <i class="fas fa-chevron-right text-gray-400 text-sm ml-auto"></i>
                </a>
                <a href="#" class="mobile-menu-item">
                    <i class="fas fa-cogs text-[#007b32] text-sm mr-3"></i>
                    <span>Hizmetler</span>
                    <i class="fas fa-chevron-right text-gray-400 text-sm ml-auto"></i>
                </a>
                <a href="#" class="mobile-menu-item">
                    <i class="fas fa-newspaper text-[#007b32] text-sm mr-3"></i>
                    <span>Haberler</span>
                    <i class="fas fa-chevron-right text-gray-400 text-sm ml-auto"></i>
                </a>
                <a href="#" class="mobile-menu-item">
                    <i class="fas fa-phone text-[#007b32] text-sm mr-3"></i>
                    <span>İletişim</span>
                    <i class="fas fa-chevron-right text-gray-400 text-sm ml-auto"></i>
                </a>
            @endif
        </div>
    </div>

    <style>
        /* Menü için aktif durum stili - yeşil çizgi */
        .active-nav, .group:hover > a {
            position: relative;
        }
        
        .active-nav::after, .group:hover > a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background-color: #007b32;
        }
        
        /* Mega menü stillerini düzenle */
        .mega-menu {
            top: 96px !important;
            z-index: 60 !important;
        }
        
        /* Mega Menü için geçiş alanı */
        .group::before {
            content: '';
            display: block;
            position: absolute;
            width: 100%;
            height: 20px;
            bottom: -20px;
            left: 0;
            z-index: 100;
        }
        
        /* Mega Menü için üst köprü alanı */
        .mega-menu::before {
            content: '';
            display: block;
            position: absolute;
            width: 100%;
            height: 20px;
            top: -20px;
            left: 0;
        }
        
        /* Header yüksekliği */
        .header-section {
            height: 100px !important; /* 96px + 4px (pt-1) */
            position: relative;
            z-index: 100;
        }
        
        .head {
            height: 96px !important;
        }
        
        /* Yeşil çizgi */
        .w-full.h-1.bg-\[\#007b32\] {
            position: relative;
            z-index: 50;
        }
        
        /* Yatay buton hover efekti */
        .category-button:hover {
            border-color: #e6f7ec;
            background-color: #f0f9f6;
        }
        
        .category-button:hover .material-icons {
            color: #007b32;
        }
        
        /* Metni kesme */
        .truncate {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        /* Atatürk simgesi pozisyonu */
        .text-xl.font-bold.relative.flex.items-end.pb-1.z-10 {
            position: relative;
            bottom: -10px;
        }
        
        /* Atatürk simgesi için ayrı stil */
        .text-xl.font-bold.relative.flex.items-end.pb-1.z-10 img {
            height: 80px;
        }

        /* Mobil menü için özel class'lar */
        .mobile-menu-hidden {
            display: none !important;
        }
        
        .mobile-menu-visible {
            display: block !important;
            animation: slideDown 0.3s ease-out;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Mobil menü içerik stili */
        .mobile-menu-content {
            padding: 0;
            background: linear-gradient(135deg, #ffffff 0%, #f8fffe 100%);
            border-top: 3px solid #007b32;
        }
        
        .mobile-menu-item {
            display: flex;
            align-items: center;
            padding: 16px 20px;
            color: #1f2937;
            text-decoration: none;
            border-bottom: 1px solid #e5e7eb;
            transition: all 0.2s ease;
            font-weight: 500;
            font-size: 16px;
        }
        
        .mobile-menu-item:hover {
            background: linear-gradient(90deg, #f0fdf9 0%, #ecfdf5 100%);
            color: #00352b;
            transform: translateX(5px);
            border-left: 4px solid #007b32;
            padding-left: 16px;
        }
        
        .mobile-menu-item:active {
            background: #e6fffa;
            transform: scale(0.98);
        }
        
        .mobile-menu-item:last-child {
            border-bottom: none;
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
        }

        @media (max-width: 768px) {
            .header-section, .head {
                height: 90px !important;
            }
            
            .flex.items-center.justify-between {
                height: 90px !important;
            }
            
            /* Mobil menü container */
            #mobileMenu {
                display: none !important;
                position: absolute !important;
                top: 100% !important;
                left: 0 !important;
                right: 0 !important;
                z-index: 9999 !important;
                background: white !important;
                box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
                border-bottom-left-radius: 8px !important;
                border-bottom-right-radius: 8px !important;
                overflow: hidden !important;
            }
            
            #mobileMenu.mobile-menu-visible {
                display: block !important;
            }
            
            /* Mobil Atatürk simgesi */
            .text-xl.font-bold.relative.flex.items-end.pb-1.z-10 {
                bottom: -10px; /* Biraz daha aşağıda */
                padding-right: 5px; /* Sağ tarafta biraz boşluk */
            }
            
            .text-xl.font-bold.relative.flex.items-end.pb-1.z-10 img {
                height: 70px; /* Biraz daha büyük */
            }
            
            /* Mobil menü butonu tasarımı */
            #mobileMenuButton {
                margin-right: 5px;
                background: linear-gradient(135deg, #f3f4f6, #ffffff) !important;
                border: 2px solid #e5e7eb !important;
                border-radius: 8px !important;
                width: 44px !important;
                height: 44px !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                transition: all 0.2s ease !important;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
            }
            
            #mobileMenuButton:hover {
                background: linear-gradient(135deg, #007b32, #00652a) !important;
                border-color: #007b32 !important;
                transform: translateY(-1px) !important;
                box-shadow: 0 4px 8px rgba(0, 123, 50, 0.2) !important;
            }
            
            #mobileMenuButton:hover i {
                color: white !important;
            }
            
            #mobileMenuButton:active {
                transform: translateY(0) scale(0.95) !important;
            }
            
            #mobileMenuButton i {
                font-size: 18px !important;
                color: #374151 !important;
                transition: color 0.2s ease !important;
            }
        }
    </style>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mega menü davranışını iyileştirme
        const menuGroups = document.querySelectorAll('.group');
        let activeMenu = null;
        let timeoutId = null;
        
        menuGroups.forEach(group => {
            const megaMenu = group.querySelector('.mega-menu');
            if (!megaMenu) return;
            
            // Mouse menü üzerine geldiğinde
            group.addEventListener('mouseenter', () => {
                if (timeoutId) {
                    clearTimeout(timeoutId);
                    timeoutId = null;
                }
                
                // Aktif menüyü ayarla
                if (activeMenu && activeMenu !== megaMenu) {
                    activeMenu.style.display = 'none';
                    activeMenu.style.opacity = '0';
                    activeMenu.style.visibility = 'hidden';
                }
                
                megaMenu.style.display = 'block';
                // Küçük bir gecikme ile görünürlüğü ayarla (CSS transition için)
                setTimeout(() => {
                    megaMenu.style.opacity = '1';
                    megaMenu.style.visibility = 'visible';
                }, 50);
                
                activeMenu = megaMenu;
            });
            
            // Mouse menüden çıktığında
            group.addEventListener('mouseleave', () => {
                timeoutId = setTimeout(() => {
                    if (megaMenu) {
                        megaMenu.style.opacity = '0';
                        megaMenu.style.visibility = 'hidden';
                        // CSS transition tamamlandıktan sonra display'i none yap
                        setTimeout(() => {
                            if (megaMenu.style.opacity === '0') {
                                megaMenu.style.display = 'none';
                            }
                        }, 300);
                    }
                    activeMenu = null;
                }, 200); // Çıkış için 200ms gecikme
            });
        });
        
        // Mobil menü toggle - YENİ ÇÖZÜM
        const mobileMenuButton = document.getElementById('mobileMenuButton');
        const mobileMenu = document.getElementById('mobileMenu');
        
        function closeMobileMenu() {
            if (mobileMenu) {
                mobileMenu.classList.remove('mobile-menu-visible');
                mobileMenu.classList.add('mobile-menu-hidden');
            }
        }
        
        if (mobileMenuButton && mobileMenu) {
            // Varolan event listener'ları temizle
            mobileMenuButton.replaceWith(mobileMenuButton.cloneNode(true));
            const newMobileMenuButton = document.getElementById('mobileMenuButton');
            
            newMobileMenuButton.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                if (mobileMenu.classList.contains('mobile-menu-visible')) {
                    closeMobileMenu();
                } else {
                    mobileMenu.classList.remove('mobile-menu-hidden');
                    mobileMenu.classList.add('mobile-menu-visible');
                }
            });
            
            // Başka yere tıklanınca menüyü kapat
            document.addEventListener('click', function(e) {
                if (!mobileMenu.contains(e.target) && !newMobileMenuButton.contains(e.target)) {
                    closeMobileMenu();
                }
            });
            
            // ESC tuşu ile menüyü kapat
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeMobileMenu();
                }
            });
            
            // Mobil menü linklerine tıklanınca menüyü kapat
            const mobileMenuLinks = mobileMenu.querySelectorAll('a[href]:not([href="#"])');
            mobileMenuLinks.forEach(link => {
                link.addEventListener('click', function() {
                    closeMobileMenu();
                });
            });
        }
    });
</script>

<!-- Yeşil sınır çizgisi - Navbar altı, slider üstü -->
<div class="w-full h-1 bg-[#007b32]" style="position: relative; z-index: 50;"></div> 