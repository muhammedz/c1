<!-- Header kısmı buraya gelecek -->
<!-- #### Header Section -->
@php
    $headerSettings = app(\App\Services\HeaderService::class)->getHeaderSettings();
@endphp
<section id="header-section" class="header-section relative w-full pt-1" 
    style="z-index: 999997; height: {{ $headerSettings->header_height + 4 }}px; background-color: {{ $headerSettings->header_bg_color }};">
    <div class="head w-full relative" 
        style="z-index: 999997; height: {{ $headerSettings->header_height }}px; background-color: {{ $headerSettings->header_bg_color }}; color: {{ $headerSettings->header_text_color }};"
        @if($headerSettings->sticky_header) data-sticky="true" @endif>
        <nav class="container max-w-7xl h-full mx-auto px-4">
            <div class="flex items-center justify-between" style="height: {{ $headerSettings->header_height }}px;">
                <!-- Logo Alanı -->
                <div class="flex items-center space-x-10 md:space-x-10 space-x-2" style="color: {{ $headerSettings->header_text_color }};">
                    <div class="text-xl font-bold">
                        <a href="/">
                            <img src="{{ $headerSettings->logo_path }}" alt="Logo" style="height: {{ min(64, $headerSettings->header_height - 20) }}px;">
                        </a>
                    </div>
                </div>

                <!-- Desktop Menü -->
                <div class="hidden lg:flex items-center h-full space-x-1">
                    @if(isset($mainMenuItems) && $mainMenuItems->count() > 0)
                        @foreach($mainMenuItems as $menu)
                            @if($menu->type == 1)
                                <!-- Küçük menü için sadece link göster -->
                                <div class="relative h-full flex items-center">
                                    <a href="{{ $menu->url ?? '#' }}"
                                        class="h-full px-3 font-semibold hover:opacity-80 text-md flex items-center"
                                        style="color: {{ $headerSettings->header_text_color }};">
                                        {{ $menu->name }}
                                    </a>
                                </div>
                            @else
                                <!-- Büyük menü için mega menü göster -->
                                <div class="group relative h-full flex items-center">
                                    <a href="{{ $menu->url ?? '#' }}"
                                        class="h-full px-3 font-semibold hover:opacity-80 text-md flex items-center"
                                        style="color: {{ $headerSettings->header_text_color }};">
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
                                                                        @if($item->icon && str_starts_with($item->icon, 'data:image/'))
                                                                            <img src="{{ $item->icon }}" alt="İkon" style="width: 24px; height: 24px; object-fit: contain;">
                                                                        @elseif($item->icon && str_starts_with($item->icon, 'fas '))
                                                                            <i class="{{ $item->icon }} text-[#007b32] text-lg group-hover:text-[#00352b] transition-colors duration-300"></i>
                                                                        @elseif($item->icon)
                                                                            <span class="material-icons text-[#007b32] text-lg group-hover:text-[#00352b] transition-colors duration-300">{{ $item->icon }}</span>
                                                                        @else
                                                                            <i class="fas fa-file-alt text-[#007b32] text-lg group-hover:text-[#00352b] transition-colors duration-300"></i>
                                                                        @endif
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
                                                                    <h3 class="flex items-center">
                                                                        @if($item->icon && str_starts_with($item->icon, 'data:image/'))
                                                                            <img src="{{ $item->icon }}" alt="İkon" class="mr-2" style="width: 20px; height: 20px; object-fit: contain; display: inline-block; vertical-align: middle;">
                                                                        @elseif($item->icon && str_starts_with($item->icon, 'fas '))
                                                                            <i class="{{ $item->icon }} mr-2"></i>
                                                                        @elseif($item->icon)
                                                                            <span class="material-icons mr-2 text-sm">{{ $item->icon }}</span>
                                                                        @endif
                                                                        {{ $item->title }}
                                                                    </h3>
                                                                    @if($item->children && $item->children->count() > 0)
                                                                        <ul class="space-y-0.5">
                                                                            @foreach($item->children as $subItem)
                                                                                <li>
                                                                                    <a href="{{ $subItem->url ?? '#' }}" class="mega-menu-link" {{ $subItem->new_tab ? 'target="_blank"' : '' }}>
                                                                                        @if($subItem->icon && str_starts_with($subItem->icon, 'data:image/'))
                                                                                            <img src="{{ $subItem->icon }}" alt="İkon" class="mega-menu-link-icon" style="width: 16px; height: 16px; object-fit: contain;">
                                                                                        @else
                                                                                            <i class="{{ $subItem->icon ?? 'fas fa-user' }} mega-menu-link-icon"></i>
                                                                                        @endif
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
                            <a href="/haberler" class="h-full px-3 font-semibold hover:opacity-80 text-md flex items-center" style="color: {{ $headerSettings->header_text_color }};">
                                Haberler
                            </a>
                        </div>
                        <div class="relative h-full flex items-center">
                            <a href="/sayfalar" class="h-full px-3 font-semibold hover:opacity-80 text-md flex items-center" style="color: {{ $headerSettings->header_text_color }};">
                                Sayfalar
                            </a>
                        </div>
                        <div class="relative h-full flex items-center">
                            <a href="/hedefkitleler" class="h-full px-3 font-semibold hover:opacity-80 text-md flex items-center" style="color: {{ $headerSettings->header_text_color }};">
                                Hedef Kitleler
                            </a>
                        </div>
                        <div class="relative h-full flex items-center">
                            <a href="/iletisim" class="h-full px-3 font-semibold hover:opacity-80 text-md flex items-center" style="color: {{ $headerSettings->header_text_color }};">
                                İletişim
                            </a>
                        </div>
                        
                        <!-- Kurumsal Mega Menü -->
                        <div class="group relative h-full flex items-center">
                            <a href="#"
                                class="h-full px-3 font-semibold hover:opacity-80 text-md flex items-center"
                                style="color: {{ $headerSettings->header_text_color }};">
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
                    @if($headerSettings->show_search_button)
                    <button id="searchButton" class="hidden lg:flex w-11 h-11 bg-[#007b32] rounded-lg items-center justify-center text-white shadow-md hover:bg-[#00352b] hover:scale-105 transition-all" aria-label="Arama modalını aç">
                        <i class="fas fa-search text-white text-xl"></i>
                    </button>
                    @endif
                    
                    <!-- Slogan - sadece desktop'ta görünür -->
                    <div class="font-bold hidden lg:block" style="color: {{ $headerSettings->header_text_color }};">
                        <img src="{{ $headerSettings->slogan_path }}" alt="Slogan" style="height: {{ min(48, $headerSettings->header_height - 30) }}px;">
                    </div>
                    
                    <!-- Mobil + Tablet Arama ve Menü Butonları - desktop hariç her yerde görünür -->
                    <div class="mobile-header-controls mobile-header-controls-only flex items-center space-x-3">
                        <!-- Mobil Arama Butonu -->
                        @if($headerSettings->show_search_button)
                        <button id="mobileSearchButton" class="w-11 h-11 bg-[#007b32] rounded-lg flex items-center justify-center text-white shadow-md hover:bg-[#00352b] hover:scale-105 transition-all" aria-label="Arama modalını aç">
                            <i class="fas fa-search text-white text-xl"></i>
                        </button>
                        @endif
                        
                        <!-- Mobil Menü Butonu -->
                        <button id="mobileMenuButton" aria-label="Mobil menüyü aç">
                            <span style="font-size: 18px; line-height: 1; display: inline-block;">☰</span>
                        </button>
                    </div>
                    
                    <!-- Atatürk simgesi - her zaman görünür, mobilde menünün sağında -->
                    <div class="text-xl font-bold relative flex items-end pb-1 z-10">
                        <img src="{{ $headerSettings->secondary_logo_path }}" alt="Atatürk" class="md:h-24 h-20">
                    </div>
                </div>
            </div>
        </nav>
    </div>

    <!-- Mobil Menü (Küçük ekranlarda görünür) -->
    <!-- Modern Side Menu Overlay -->
    <!-- Basit Side Menu -->
            <div id="sideMenuOverlay" class="mobile-menu-overlay fixed inset-0 mobile-menu-overlay-only" style="z-index: 999999 !important; position: fixed !important;">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black bg-opacity-70 transition-opacity duration-200" id="sideMenuBackdrop" style="z-index: 999998;"></div>
        
        <!-- Menu Container -->
        <div id="sideMenuContainer" class="fixed top-0 left-0 h-full w-96 bg-white shadow-2xl transform -translate-x-full transition-transform duration-300 ease-out" style="z-index: 999999 !important; position: fixed !important; font-family: 'TT Norms Pro', sans-serif; -webkit-font-feature-settings: 'liga' 1; font-feature-settings: 'liga' 1; text-rendering: optimizeLegibility; -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale;">
            <!-- Header -->
                          <div class="flex items-center justify-between px-4 bg-[#004d2e] text-white" style="padding-top: 12px !important; padding-bottom: 12px !important; min-height: 48px !important; height: 48px !important;">
                <button id="sideMenuBack" class="text-white text-lg hidden" style="padding: 4px 6px !important;" aria-label="Önceki menüye dön">
                    <i class="fas fa-chevron-left" style="font-size: 16px !important;"></i>
                </button>
                <h3 id="sideMenuTitle" class="font-medium text-base tracking-wide" style="font-size: 15px !important; line-height: 1.4 !important;">Menü</h3>
                <button id="sideMenuClose" class="text-white text-lg" style="padding: 4px 6px !important;" aria-label="Menüyü kapat">
                    <i class="fas fa-times" style="font-size: 16px !important;"></i>
                </button>
            </div>
            
            <!-- Content -->
            <div id="sideMenuContent" class="h-full overflow-y-auto bg-white">
                <!-- Dinamik içerik buraya gelecek -->
            </div>
        </div>
    </div>
</section>

<!-- CSS kodu W3C uyumluluğu için layout dosyasına taşındı -->

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
        
        // Basit Side Menu Sistemi
        class SimpleSideMenu {
            constructor() {
                this.overlay = document.getElementById('sideMenuOverlay');
                this.backdrop = document.getElementById('sideMenuBackdrop');
                this.container = document.getElementById('sideMenuContainer');
                this.backBtn = document.getElementById('sideMenuBack');
                this.closeBtn = document.getElementById('sideMenuClose');
                this.title = document.getElementById('sideMenuTitle');
                this.content = document.getElementById('sideMenuContent');
                this.mobileMenuButton = document.getElementById('mobileMenuButton');
                
                this.currentLevel = 0;
                this.menuHistory = [];
                
                this.init();
            }
            
            init() {
                // Event listeners
                if (this.mobileMenuButton) {
                    this.mobileMenuButton.addEventListener('click', (e) => {
                        e.preventDefault();
                        this.open();
                    });
                }
                
                if (this.closeBtn) {
                    this.closeBtn.addEventListener('click', () => this.close());
                }
                
                if (this.backBtn) {
                    this.backBtn.addEventListener('click', () => this.goBack());
                }
                
                if (this.backdrop) {
                    this.backdrop.addEventListener('click', () => this.close());
                }
                
                // ESC tuşu
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && this.overlay.classList.contains('active')) {
                        this.close();
                    }
                });
                
                // İlk menüyü render et
                this.renderMainMenu();
            }
            
            getMenuData() {
                // PHP'den gelen menü verilerini al
                const menuData = {
                    mainMenu: [
                        {
                            id: 'home',
                            name: 'Ana Sayfa',
                            icon: 'fas fa-home',
                            url: '{{ route("front.home") }}',
                            hasChildren: false
                        }
                        @if(isset($mainMenuItems) && $mainMenuItems->count() > 0)
                            @foreach($mainMenuItems as $menu)
                                ,{
                                    id: '{{ $menu->id }}',
                                    name: '{{ $menu->name }}',
                                    icon: 'fas fa-{{ $menu->type == 1 ? "link" : "folder" }}',
                                    url: '{{ $menu->url ?? "#" }}',
                                    hasChildren: {{ $menu->type != 1 ? 'true' : 'false' }},
                                    type: {{ $menu->type }}
                                }
                            @endforeach
                        @else
                            ,{
                                id: 'kurumsal',
                                name: 'Kurumsal',
                                icon: 'fas fa-building',
                                url: '#',
                                hasChildren: true,
                                type: 2
                            },
                            {
                                id: 'hizmetler',
                                name: 'Hizmetler',
                                icon: 'fas fa-cogs',
                                url: '#',
                                hasChildren: true,
                                type: 2
                            },
                            {
                                id: 'haberler',
                                name: 'Haberler',
                                icon: 'fas fa-newspaper',
                                url: '/haberler',
                                hasChildren: false,
                                type: 1
                            },
                            {
                                id: 'iletisim',
                                name: 'İletişim',
                                icon: 'fas fa-phone',
                                url: '/iletisim',
                                hasChildren: false,
                                type: 1
                            }
                        @endif
                    ]
                };
                return menuData;
            }
            
            open() {
                try {
                    if (!this.overlay) {
                        return;
                    }
                    
                    if (!this.container) {
                        return;
                    }
                    
                    // Mevcut scroll pozisyonunu kaydet
                    this.scrollPosition = window.pageYOffset || document.documentElement.scrollTop;
                    
                    // CSS !important kuralını ezmek için inline style kullan
                    this.overlay.style.setProperty('display', 'block', 'important');
                    this.overlay.classList.add('active');
                    
                    // Container'ı da force et
                    this.container.style.setProperty('transform', 'translateX(0)', 'important');
                    
                    // Body scroll'unu engelle
                    document.body.classList.add('mobile-menu-open');
                    document.body.style.top = `-${this.scrollPosition}px`;
                    
                    // Quick menu'yu gizle
                    const quickMenu = document.getElementById('quick-menu-section');
                    if (quickMenu) {
                        quickMenu.style.display = 'none';
                        quickMenu.style.visibility = 'hidden';
                        quickMenu.style.zIndex = '1';
                    }
                    
                    // Yeşil çizgiyi gizle
                    const greenLine = document.querySelector('.w-full.h-1.bg-\\[\\#007b32\\]');
                    if (greenLine) {
                        greenLine.style.display = 'none';
                        greenLine.style.visibility = 'hidden';
                        greenLine.style.zIndex = '1';
                    }
                } catch (error) {
                    console.error('Menü açma hatası:', error);
                }
            }
            
            close() {
                try {
                    // Overlay'i gizle
                    this.overlay.style.setProperty('display', 'none', 'important');
                    this.overlay.classList.remove('active');
                    
                    // Body scroll'unu geri yükle
                    document.body.classList.remove('mobile-menu-open');
                    document.body.style.top = '';
                    
                    // Scroll pozisyonunu geri yükle
                    if (this.scrollPosition !== undefined) {
                        window.scrollTo(0, this.scrollPosition);
                    }
                    
                    // Quick menu'yu geri göster
                    const quickMenu = document.getElementById('quick-menu-section');
                    if (quickMenu) {
                        quickMenu.style.display = '';
                        quickMenu.style.visibility = '';
                        quickMenu.style.zIndex = '10';
                    }
                    
                    // Yeşil çizgiyi geri göster
                    const greenLine = document.querySelector('.w-full.h-1.bg-\\[\\#007b32\\]');
                    if (greenLine) {
                        greenLine.style.display = '';
                        greenLine.style.visibility = '';
                        greenLine.style.zIndex = '10';
                    }
                    
                    // Menüyü ana seviyeye sıfırla
                    setTimeout(() => {
                        this.resetToMainMenu();
                    }, 300);
                } catch (error) {
                    // Sessizce hata yönetimi
                }
            }
            
            goBack() {
                if (this.menuHistory.length > 0) {
                    const previousMenu = this.menuHistory.pop();
                    this.currentLevel--;
                    
                    this.title.textContent = previousMenu.title;
                    this.content.innerHTML = previousMenu.content;
                    this.attachMenuItemEvents();
                    
                    if (this.currentLevel === 0) {
                        this.backBtn.classList.add('hidden');
                    }
                }
            }
            
            resetToMainMenu() {
                this.currentLevel = 0;
                this.menuHistory = [];
                this.title.textContent = '';
                this.backBtn.classList.add('hidden');
                this.renderMainMenu();
            }
            
            renderMainMenu() {
                // Ana menüde başlık gösterme
                this.title.textContent = '';
                
                const mainMenuItems = [
                    @if(isset($mainMenuItems) && count($mainMenuItems) > 0)
                        @foreach($mainMenuItems as $index => $menu)
                        @if($index > 0),@endif{
                            id: '{{ $menu->id }}',
                            name: {!! json_encode($menu->name) !!},
                            url: '{{ $menu->url ?? "#" }}',
                            hasChildren: {{ $menu->type == 2 || $menu->type == 3 ? 'true' : 'false' }},
                            type: {{ $menu->type }},
                            menuName: {!! json_encode($menu->name) !!} // Alt menü başlığı için
                        }
                        @endforeach
                    @endif
                ];
                
                this.content.innerHTML = this.createMenuList(mainMenuItems);
                this.attachMenuItemEvents();
            }
            
            createMenuList(items) {
                return `
                    <div>
                        ${items.map(item => this.createMenuItem(item)).join('')}
                    </div>
                `;
            }
            
            createMenuItem(item, level = 1) {
                const hasChildren = item.hasChildren;
                const arrow = hasChildren ? '<i class="fas fa-chevron-right text-gray-400 text-base"></i>' : '';
                
                const menuElement = document.createElement('div');
                menuElement.className = 'menu-item flex items-center px-5 py-4 border-b border-gray-200 cursor-pointer hover:bg-gray-50 active:bg-gray-100 transition-all duration-200';
                menuElement.setAttribute('data-menu-id', item.id);
                menuElement.setAttribute('data-url', item.url || '#');
                menuElement.setAttribute('data-has-children', hasChildren);
                menuElement.setAttribute('data-type', item.type || 1);
                menuElement.style.fontFamily = "'TT Norms Pro', sans-serif";
                
                const contentDiv = document.createElement('div');
                contentDiv.className = 'flex items-center flex-1';
                
                // İkon ekleme
                if (item.icon) {
                    const iconContainer = document.createElement('div');
                    iconContainer.className = 'mr-3';
                    
                    if (item.icon.startsWith('data:image/')) {
                        // PNG ikon
                        const iconImg = document.createElement('img');
                        iconImg.src = item.icon;
                        iconImg.alt = 'İkon';
                        iconImg.style.width = '20px';
                        iconImg.style.height = '20px';
                        iconImg.style.objectFit = 'contain';
                        iconContainer.appendChild(iconImg);
                    } else if (item.icon.startsWith('fas ') || item.icon.startsWith('far ') || item.icon.startsWith('fab ')) {
                        // FontAwesome ikon
                        const iconElement = document.createElement('i');
                        iconElement.className = item.icon + ' text-gray-600';
                        iconContainer.appendChild(iconElement);
                    } else {
                        // Material Icon
                        const iconElement = document.createElement('span');
                        iconElement.className = 'material-icons text-gray-600';
                        iconElement.textContent = item.icon;
                        iconContainer.appendChild(iconElement);
                    }
                    
                    contentDiv.appendChild(iconContainer);
                }
                
                const textSpan = document.createElement('span');
                textSpan.className = 'text-gray-800 font-medium text-base tracking-wide';
                textSpan.textContent = item.name; // textContent Türkçe karakterleri düzgün handle eder
                
                contentDiv.appendChild(textSpan);
                menuElement.appendChild(contentDiv);
                
                if (hasChildren) {
                    const arrowElement = document.createElement('i');
                    arrowElement.className = 'fas fa-chevron-right text-gray-400 text-base';
                    menuElement.appendChild(arrowElement);
                }
                
                return menuElement.outerHTML;
            }
            
                        attachMenuItemEvents() {
                const menuItems = this.content.querySelectorAll('.menu-item');
                
                menuItems.forEach(item => {
                    item.addEventListener('click', (e) => {
                e.preventDefault();
                        
                        // Visual feedback
                        item.style.backgroundColor = '#f9fafb';
                        setTimeout(() => item.style.backgroundColor = '', 200);
                        
                        const hasChildren = item.getAttribute('data-has-children') === 'true';
                        const menuId = item.getAttribute('data-menu-id');
                        const url = item.getAttribute('data-url');
                        const type = parseInt(item.getAttribute('data-type')) || 1;
                        const menuName = item.querySelector('span').textContent; // Menü adını al
                        
                        if (hasChildren) {
                            this.navigateToSubmenu(menuId, type, menuName);
                        } else if (url && url !== '#') {
                            this.close();
                            window.location.href = url;
                        }
                    });
                });
            }
            
            findItemInApiData(itemId, items) {
                for (let item of items) {
                    if (item.id == itemId) {
                        return item;
                    }
                    if (item.children) {
                        const found = this.findItemInApiData(itemId, item.children);
                        if (found) return found;
                    }
                }
                return null;
            }
            
            getCurrentMenuId() {
                // Şu anki menü ID'sini bul
                if (this.currentApiData) {
                    // API'den gelen data varsa, menü ID'sini bul
                    return this.currentMenuId || 'kurumsal';
                }
                return 'kurumsal'; // Varsayılan
            }
            
            navigateToLevel(items, title, menuType = 2) {
                // Şu anki menüyü history'e kaydet
                this.menuHistory.push({
                    title: this.title.textContent,
                    content: this.content.innerHTML
                });
                
                this.currentLevel++;
                this.backBtn.classList.remove('hidden');
                
                // Menüyü render et
                this.renderSubmenu(items, title, menuType);
            }
            
            async navigateToSubmenu(menuId, menuType = 2, menuName = '') {
                // Şu anki menüyü history'e kaydet
                this.menuHistory.push({
                    title: this.title.textContent,
                    content: this.content.innerHTML
                });
                
                this.currentLevel++;
                this.backBtn.classList.remove('hidden');
                
                // Loading göster
                this.content.innerHTML = '<div class="flex items-center justify-center py-8"><div class="text-white">Yükleniyor...</div></div>';
                
                // Mevcut menü ID'lerini kontrol et
                const validMenuSystemIds = [5, 6, 7, 8, 9]; // MenuSystem ID'leri
                const validMenuItemIds = [25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 40, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 79, 80, 82, 83, 85, 117, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 153, 154, 156, 157, 158, 159, 160, 161, 162, 163, 164, 165, 166, 167, 168, 169, 170, 174, 176, 177, 178, 179, 180, 182, 183, 184, 185, 186, 187, 188, 189, 190, 191, 192, 193, 194, 195, 196, 197, 198]; // MenuSystemItem ID'leri
                
                const menuIdInt = parseInt(menuId);
                if (!validMenuSystemIds.includes(menuIdInt) && !validMenuItemIds.includes(menuIdInt)) {
                    // Fallback veriler kullan
                    const submenuData = this.getSubmenuData(menuId);
                    const submenuTitle = this.getSubmenuTitle(menuId);
                    this.renderSubmenu(submenuData, submenuTitle, menuType);
                    return;
                }
                
                try {
                    // API'den alt menü verilerini çek
                    const response = await fetch(`/api/menu-items/${menuId}`);
                    
                    if (response.ok) {
                        const data = await response.json();
                        if (data.success && data.items) {
                            this.renderSubmenu(data.items, menuName || 'Alt Menü', data.menuType || menuType);
                            return;
                        }
                    }
                } catch (error) {
                    // Sessizce hata yönetimi
                }
                
                // Fallback veriler kullan
                const submenuData = this.getSubmenuData(menuId);
                const submenuTitle = menuName || this.getSubmenuTitle(menuId);
                this.renderSubmenu(submenuData, submenuTitle, menuType);
            }
            
            getSubmenuTitle(menuId) {
                const titles = {
                    '5': 'Kurumsal',
                    '6': 'Hizmetler', 
                    '7': 'Duyurular',
                    '8': 'Ne Nerede?',
                    '9': 'İletişim',
                    'baskan': 'Başkan',
                    'kurumsal': 'Kurumsal',
                    'atasehir': 'Ataşehir',
                    'hizmetler': 'Hizmetler',
                    'guncel': 'Güncel',
                    'iletisim': 'İletişim'
                };
                return titles[menuId] || 'Alt Menü';
            }
            
            getSubmenuData(menuId) {
                const submenuData = {
                    // ID bazlı veriler (gerçek veritabanı ID'leri)
                    '5': [ // Kurumsal
                        { id: 'meclis-kararlari', name: 'Meclis Kararları', hasChildren: false },
                        { id: 'encumen-uyeleri', name: 'Encümen Üyeleri', hasChildren: false },
                        { id: 'belediye-meclis-uyeleri', name: 'Belediye Meclis Üyeleri', hasChildren: false },
                        { id: 'organizasyon-semasi', name: 'Organizasyon Şeması', hasChildren: false }
                    ],
                    '6': [ // Hizmetler
                        { id: 'sosyal-hizmetler', name: 'Sosyal Hizmetler', hasChildren: false },
                        { id: 'teknik-hizmetler', name: 'Teknik Hizmetler', hasChildren: false },
                        { id: 'kultur-sanat', name: 'Kültür ve Sanat', hasChildren: false },
                        { id: 'cevre-hizmetleri', name: 'Çevre Hizmetleri', hasChildren: false }
                    ],
                    '7': [ // Duyurular (Type 3 - Button Grid'den Liste'ye)
                        { id: 'tum-etkinlikler', name: 'Tüm Etkinlikler', hasChildren: false, url: '#' },
                        { id: 'askidaki-planlar', name: 'Askıdaki Planlar', hasChildren: false, url: '#' },
                        { id: 'ihaleler', name: 'İhaleler', hasChildren: false, url: '#' },
                        { id: 'yururlukteki-stratejik-plan', name: 'Yürürlükteki Stratejik Plan', hasChildren: false, url: '#' },
                        { id: 'karanfil-dergisi', name: 'Karanfil Dergisi', hasChildren: false, url: '#' },
                        { id: 'kadin-bulteni', name: 'Kadın Bülteni', hasChildren: false, url: '#' },
                        { id: 'meclis-gundemi', name: 'Meclis Gündemi', hasChildren: false, url: '#' },
                        { id: 'meclis-kararlari', name: 'Meclis Kararları', hasChildren: false, url: '#' }
                    ],
                    '8': [ // Ne Nerede?
                        { id: 'hizmet-noktalari', name: 'Hizmet Noktaları', hasChildren: false },
                        { id: 'parklar', name: 'Parklar', hasChildren: false },
                        { id: 'sosyal-tesisler', name: 'Sosyal Tesisler', hasChildren: false }
                    ],
                    '9': [ // İletişim
                        { id: 'iletisim-bilgileri', name: 'İletişim Bilgileri', hasChildren: false },
                        { id: 'bize-ulasin', name: 'Bize Ulaşın', hasChildren: false },
                        { id: 'sosyal-medya', name: 'Sosyal Medya', hasChildren: false }
                    ],
                    
                    // String bazlı fallback veriler (eski sistem uyumluluğu için)
                    'kurumsal': [
                        { id: 'meclis-kararlari', name: 'Meclis Kararları', hasChildren: false },
                        { id: 'encumen-uyeleri', name: 'Encümen Üyeleri', hasChildren: false },
                        { id: 'belediye-meclis-uyeleri', name: 'Belediye Meclis Üyeleri', hasChildren: false }
                    ],
                    'hizmetler': [
                        { id: 'sosyal-hizmetler', name: 'Sosyal Hizmetler', hasChildren: false },
                        { id: 'teknik-hizmetler', name: 'Teknik Hizmetler', hasChildren: false },
                        { id: 'kultur-sanat', name: 'Kültür ve Sanat', hasChildren: false }
                    ]
                };
                
                return submenuData[menuId] || [];
            }
            
            renderSubmenu(items, title, menuType = 2) {
                this.title.textContent = title;
                
                // Mobilde tüm menüler aynı liste formatında görünsün
                // Type 3 (button grid) menüler de normal liste olarak render edilsin
                this.content.innerHTML = this.createMenuList(items.map(item => ({
                    ...item,
                    description: item.hasChildren ? `${item.children?.length || 0} alt öğe` : null
                })));
                
                this.attachMenuItemEvents();
            }
            
            createButtonGrid(items) {
                return `
                    <div class="p-4">
                        <div class="grid grid-cols-2 gap-3">
                            ${items.map(item => this.createButtonMenuItem(item)).join('')}
                        </div>
                    </div>
                `;
            }
            
            createButtonMenuItem(item) {
                return `
                    <div class="menu-item group bg-white rounded-xl p-4 border border-gray-200 hover:border-green-300 hover:shadow-lg transition-all duration-200 cursor-pointer text-center" 
                         data-menu-id="${item.id}" 
                         data-url="${item.url}" 
                         data-has-children="${item.hasChildren || false}">
                        <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                            <i class="${item.icon} text-white text-xl"></i>
                        </div>
                        <div class="font-medium text-gray-800 text-sm leading-tight">${item.name}</div>
                    </div>
                `;
            }
            
            createMenuList(items) {
                return `
                    <div>
                        ${items.map(item => this.createMenuItem(item)).join('')}
                    </div>
                `;
            }
        }
        
        // Basit Side Menu'yu başlat
        const sideMenu = new SimpleSideMenu();
    });
</script>

<!-- Yeşil sınır çizgisi - Navbar altı, slider üstü -->
<div class="w-full h-1 bg-[#007b32]" style="position: relative; z-index: 50;"></div> 