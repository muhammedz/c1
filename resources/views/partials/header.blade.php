<!-- Header kısmı buraya gelecek -->
<!-- #### Header Section -->
<section id="header-section" class="header-section relative w-full h-24 pt-1 bg-white" style="z-index: 100;">
    <div class="head w-full h-24 bg-white relative" style="z-index: 100;">
        <nav class="container max-w-7xl h-full mx-auto px-4">
            <div class="flex items-center justify-between h-24">
                <!-- Logo Alanı -->
                <div class="flex items-center text-[#00352b] space-x-10">
                    <div class="text-xl font-bold">
                        <a href="/">
                            <img src="{{ asset('images/logo-cankaya.png') }}" alt="Çankaya Belediyesi Logo" class="h-16">
                        </a>
                    </div>
                    <div class="text-xl font-bold relative h-full flex items-end pb-1">
                        <img src="{{ asset('images/simge.png') }}" alt="Atatürk" class="h-24">
                    </div>
                </div>

                <!-- Mobil Menü Butonu -->
                <button class="md:hidden text-gray-600 hover:text-gray-900 focus:outline-none"
                    id="mobileMenuButton">
                    <span class="material-icons">menu</span>
                </button>

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
                                                        <!-- Minimal Yatay Buton Tipi Menü Görünümü -->
                                                        <div class="grid grid-cols-2 gap-2 py-2">
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
                                                            
                                                            @foreach($menuItems as $item)
                                                                <!-- Minimal Yatay Kategori Buton Tasarımı -->
                                                                <a href="{{ $item->url ?? '#' }}" class="category-button flex items-center p-2 bg-white hover:bg-gray-50 rounded border border-gray-100 shadow-sm transition-colors">
                                                                    <div class="w-8 h-8 flex items-center justify-center rounded-full mr-3 flex-shrink-0" style="background-color: #f8f9fa;">
                                                                        <span class="material-icons text-[#00352b] text-sm">{{ $item->icon ?? $iconMapping[$item->title] ?? 'article' }}</span>
                                                                    </div>
                                                                    <span class="font-medium text-[#00352b] text-sm truncate">{{ $item->title }}</span>
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
                                                                                        <span class="material-icons mega-menu-link-icon">{{ $subItem->icon ?? 'person' }}</span>
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
                                                            <span class="material-icons text-sm">arrow_forward</span>
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
                                <span class="material-icons text-sm ml-1">expand_more</span>
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
                                                                    <span class="material-icons mega-menu-link-icon">person</span>
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
                                                    <span class="material-icons text-sm">arrow_forward</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                
                <!-- Arama İkonu ve Slogan -->
                <div class="flex items-center space-x-4">
                    <button id="searchButton"   
                        class="w-10 h-10 bg-gray-50 rounded-lg flex items-center justify-center hover:text-white hover:bg-[#00352b] transition-colors">
                        <span class="material-icons hover:text-white group-hover:text-white">search</span>
                    </button>
                    <div class="text-md text-[#00352b] font-bold">
                        <img src="{{ asset('images/slogan.png') }}" alt="Çankaya Belediyesi Slogan" class="h-12">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobil Menü (Küçük ekranlarda görünür) -->
    <div class="md:hidden hidden w-full z-50 absolute bg-white shadow-lg" id="mobileMenu">
        <!-- Mobil Menü İçeriği -->
        <div class="px-4 py-2">
            @if(isset($mainMenuItems) && $mainMenuItems->count() > 0)
                @foreach($mainMenuItems as $menu)
                    @if($menu->type == 1)
                        <!-- Küçük menü için direkt link göster -->
                        <div class="group border-b border-gray-200">
                            <a href="{{ $menu->url ?? '#' }}" class="py-3 px-2 block text-gray-800 font-medium">
                                {{ $menu->name }}
                            </a>
                        </div>
                    @else
                        <!-- Büyük menü için dropdown göster -->
                        <div class="group border-b border-gray-200">
                            <a href="#" class="py-3 px-2 block text-gray-800 font-medium flex justify-between items-center">
                                {{ $menu->name }}
                                <span class="material-icons text-sm">expand_more</span>
                            </a>
                            <div class="quick-menu-dropdown hidden">
                                @if($menu->type == 3)
                                <!-- Minimal Mobil Buton Menü -->
                                <div class="grid grid-cols-1 gap-1 p-2">
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
                                    
                                    @foreach($menuItems as $item)
                                    <a href="{{ $item->url ?? '#' }}" class="flex items-center p-2 hover:bg-gray-50 border-b border-gray-100">
                                        <span class="material-icons text-[#00352b] text-base mr-3">{{ $item->icon ?? $iconMapping[$item->title] ?? 'article' }}</span>
                                        <span class="text-sm">{{ $item->title }}</span>
                                    </a>
                                    @endforeach
                                </div>
                                @else
                                <!-- Mobil dropdown içeriği -->
                                @php
                                    $menuItems = app(\App\Services\HeaderService::class)->getMenuItems($menu->id);
                                @endphp
                                
                                @foreach($menuItems as $item)
                                    <div class="border-t border-gray-100 py-2 px-4">
                                        <p class="text-gray-700 font-medium">{{ $item->title }}</p>
                                    </div>
                                @endforeach
                                @endif
                            </div>
                        </div>
                    @endif
                @endforeach
            @else
                <!-- Kurumsal -->
                <div class="group border-b border-gray-200">
                    <a href="#" class="py-3 px-2 block text-gray-800 font-medium flex justify-between items-center">
                        Kurumsal
                        <span class="material-icons text-sm">expand_more</span>
                    </a>
                    <div class="quick-menu-dropdown hidden">
                        <!-- Mobil dropdown içeriği -->
                    </div>
                </div>
                
                <!-- Diğer mobil menü öğeleri -->
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
            z-index: 100;
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
        
        // Mobil menü toggle
        const mobileMenuButton = document.getElementById('mobileMenuButton');
        const mobileMenu = document.getElementById('mobileMenu');
        
        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
            
            // Mobil menü dropdown'ları
            const mobileDropdownButtons = document.querySelectorAll('.quick-menu-dropdown');
            mobileDropdownButtons.forEach(dropdown => {
                const parent = dropdown.previousElementSibling;
                if (parent) {
                    parent.addEventListener('click', (e) => {
                        e.preventDefault();
                        dropdown.classList.toggle('hidden');
                    });
                }
            });
        }
    });
</script>

<!-- Yeşil sınır çizgisi - Navbar altı, slider üstü -->
<div class="w-full h-1 bg-[#007b32]" style="position: relative; z-index: 100;"></div> 