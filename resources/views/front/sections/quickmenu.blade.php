<!-- #### Quick Menu Section -->
<div id="quick-menu-section" class="quick-menu-section w-full rounded-md bg-gradient-radial text-white mt-[-10px] md:mt-[-15px] relative" style="z-index: 10 !important;">
    <!-- Desktop Menu -->
    <div class="hidden md:flex ">
        @if(isset($quickMenuCategories) && $quickMenuCategories->count() > 0)
            @php
                // Maksimum 4 kategori göster
                $displayCategories = $quickMenuCategories->take(4);
                $categoryCount = $displayCategories->count();
                // Eğer 4'ten az kategori varsa, genişlikleri ayarla
                $widthClass = ($categoryCount > 0) ? "w-[" . (1200 / $categoryCount) . "px]" : "w-[300px]";
            @endphp
            
            @foreach($displayCategories as $index => $category)
                @if($category->activeItems->count() > 0)
                <div class="group relative {{ $widthClass }} {{ $index < $categoryCount - 1 ? 'border-r border-white/20' : '' }}" style="padding: 0 5px;">
                    <a href="#" class="group flex items-center h-full px-6 py-4 space-x-3 transition-colors duration-300">
                        <span class="material-icons text-2xl">
                            @if(Str::startsWith($category->icon, 'fas ') || Str::startsWith($category->icon, 'far ') || Str::startsWith($category->icon, 'fab '))
                                menu
                            @else
                                {{ $category->icon ?? 'menu' }}
                            @endif
                        </span>
                        <div class="flex flex-col">
                            <span class="text-base font-semibold">{{ strtoupper($category->name) }}</span>
                            <span class="group-hover:text-black text-xs text-white/60">{{ $category->description ?? 'Hızlı Menü' }}</span>
                        </div>
                        <span class="material-icons rot text-base transition-transform duration-300 group-hover:rotate-180 ml-auto">expand_more</span>
                    </a>
                    <div class="quick-menu-dropdown" style="position: absolute; left: 0; bottom: 100%; width: 100%; background: white; z-index: 100; display: none;">
                        <div class="py-1">
                            @foreach($category->activeItems as $item)
                            <a href="{{ $item->url }}" target="{{ $item->target ?? '_self' }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                @if(Str::startsWith($item->icon, 'fas ') || Str::startsWith($item->icon, 'far ') || Str::startsWith($item->icon, 'fab '))
                                    <i class="{{ $item->icon }} mr-2 text-gray-400"></i>
                                @else
                                    <span class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
                                @endif
                                {{ $item->title }}
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            @endforeach
        @else
        <!-- Online Hizmetler -->
        <div class="group relative w-[300px] border-r border-white/20" style="padding: 0 5px;">
            <a href="#" class=" group flex items-center h-full px-6 py-4 space-x-3 transition-colors duration-300">
                <span class="material-icons text-2xl">language</span>
                <div class="flex flex-col">
                    <span class="text-base font-semibold">ONLINE HİZMETLER</span>
                    <span class=" group-hover:text-black text-xs text-white/60">Büyükşehir Online İşlemler</span>
                </div>
                <span class="material-icons rot text-base transition-transform duration-300 group-hover:rotate-180 ml-auto">expand_more</span>
            </a>
            <div class="quick-menu-dropdown" style="position: absolute; left: 0; bottom: 100%; width: 100%; background: white; z-index: 100; display: none;">
                <div class="py-2">
                    <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <span class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
                        Mebis
                    </a>
                    <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <span class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
                        Ulaşım Bilgi Sistemi
                    </a>
                    <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <span class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
                        EBYS Evrak Doğrulama
                    </a>
                    <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <span class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
                        E-Posta
                    </a>
                    <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <span class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
                        E-Bordro
                    </a>
                    <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <span class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
                        E-Belediye
                    </a>
                </div>
            </div>
        </div>
        <!-- Sosyal Hizmetler -->
        <div class="group relative w-[300px] border-r border-white/20" style="padding: 0 5px;">
            <a href="#" class=" group flex items-center h-full px-6 py-4 space-x-3 transition-colors duration-300">
                <span class="material-icons text-2xl">people</span>
                <div class="flex flex-col">
                    <span class="text-base font-semibold">SOSYAL HİZMETLER</span>
                    <span class=" group-hover:text-black text-xs text-white/60">Sosyal yardımlar, faaliyetler</span>
                </div>
                <span class="material-icons rot text-base transition-transform duration-300 group-hover:rotate-180 ml-auto">expand_more</span>
            </a>
            <div class="quick-menu-dropdown" style="position: absolute; left: 0; bottom: 100%; width: 100%; background: white; z-index: 100; display: none;">
                <div class="py-2">
                    <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <span class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
                        Sosyal Yardımlar
                    </a>
                    <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <span class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
                        Etkinlikler
                    </a>
                    <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <span class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
                        Kurslar
                    </a>
                </div>
            </div>
        </div>
        <!-- E-Belediye -->
        <div class="group relative w-[300px] border-r border-white/20" style="padding: 0 5px;">
            <a href="#" class="group flex items-center h-full px-6 py-4 space-x-3 transition-colors duration-300">
                <span class="material-icons text-2xl">computer</span>
                <div class="flex flex-col">
                    <span class="text-base font-semibold">E-BELEDİYE</span>
                    <span class=" group-hover:text-black text-xs text-white/60">Büyükşehir e-Belediye</span>
                </div>
                <span class="material-icons rot text-base transition-transform duration-300 group-hover:rotate-180 ml-auto">expand_more</span>
            </a>
            <div class="quick-menu-dropdown" style="position: absolute; left: 0; bottom: 100%; width: 100%; background: white; z-index: 100; display: none;">
                <div class="py-2">
                    <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <span class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
                        Online İşlemler
                    </a>
                    <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <span class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
                        Ödemeler
                    </a>
                    <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <span class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
                        Başvurular
                    </a>
                </div>
            </div>
        </div>
        <!-- Belediye Rehberi -->
        <div class="group relative w-[300px]" style="padding: 0 5px;">
            <a href="#" class=" group flex items-center h-full px-6 py-4 space-x-3 transition-colors duration-300">
                <span class="material-icons text-2xl">menu_book</span>
                <div class="flex flex-col">
                    <span class="text-base font-semibold">BELEDİYE REHBERİ</span>
                    <span class=" group-hover:text-black text-xs text-white/60">Belediyeniz Faaliyetleri</span>
                </div>
                <span class="material-icons rot text-base transition-transform duration-300 group-hover:rotate-180 ml-auto">expand_more</span>
            </a>
            <div class="quick-menu-dropdown" style="position: absolute; left: 0; bottom: 100%; width: 100%; background: white; z-index: 100; display: none;">
                <div class="py-2">
                    <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <span class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
                        Hizmetler
                    </a>
                    <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <span class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
                        Projeler
                    </a>
                    <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <span class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
                        İletişim
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Mobile Menu -->
    <div class="mobile-menu-container md:hidden">
        <div class="flex flex-col divide-y divide-white/20">
            @if(isset($quickMenuCategories) && $quickMenuCategories->count() > 0)
                @foreach($quickMenuCategories->take(4) as $category)
                    @if($category->activeItems->count() > 0)
                    <div class="group relative">
                        <a href="#" class="flex items-center p-4 space-x-3">
                            <span class="material-icons text-2xl">
                                @if(Str::startsWith($category->icon, 'fas ') || Str::startsWith($category->icon, 'far ') || Str::startsWith($category->icon, 'fab '))
                                    menu
                                @else
                                    {{ $category->icon ?? 'menu' }}
                                @endif
                            </span>
                            <div class="flex flex-col flex-1">
                                <span class="text-base font-semibold">{{ strtoupper($category->name) }}</span>
                                <span class="text-xs text-white/60">{{ $category->description ?? 'Hızlı Menü' }}</span>
                            </div>
                            <span class="material-icons text-base">expand_more</span>
                        </a>
                        <div class="quick-menu-dropdown bg-white">
                            <div class="py-2">
                                @foreach($category->activeItems as $item)
                                <a href="{{ $item->url }}" target="{{ $item->target ?? '_self' }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    @if(Str::startsWith($item->icon, 'fas ') || Str::startsWith($item->icon, 'far ') || Str::startsWith($item->icon, 'fab '))
                                        <i class="{{ $item->icon }} mr-2 text-gray-400"></i>
                                    @else
                                        <span class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
                                    @endif
                                    {{ $item->title }}
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
            @else
            <!-- Online Hizmetler -->
            <div class="group relative">
                <a href="#" class="flex items-center p-4 space-x-3">
                    <span class="material-icons text-2xl">language</span>
                    <div class="flex flex-col flex-1">
                        <span class="text-base font-semibold">ONLINE HİZMETLER</span>
                        <span class="text-xs text-white/60">Büyükşehir Online İşlemler</span>
                    </div>
                    <span class="material-icons text-base">expand_more</span>
                </a>
                <div class="quick-menu-dropdown bg-white">
                    <div class="py-2">
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <span class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
                            Mebis
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <span class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
                            Ulaşım Bilgi Sistemi
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <span class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
                            EBYS Evrak Doğrulama
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <span class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
                            E-Posta
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <span class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
                            E-Bordro
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <span class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
                            E-Belediye
                        </a>
                    </div>
                </div>
            </div>

            <!-- Sosyal Hizmetler -->
            <div class="group relative">
                <a href="#" class="flex items-center p-4 space-x-3">
                    <span class="material-icons text-2xl">people</span>
                    <div class="flex flex-col flex-1">
                        <span class="text-base font-semibold">SOSYAL HİZMETLER</span>
                        <span class="text-xs text-white/60">Sosyal yardımlar, faaliyetler</span>
                    </div>
                    <span class="material-icons text-base">expand_more</span>
                </a>
                <div class="quick-menu-dropdown bg-white">
                    <div class="py-2">
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <span class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
                            Sosyal Yardımlar
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <span class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
                            Etkinlikler
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <span class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
                            Kurslar
                        </a>
                    </div>
                </div>
            </div>

            <!-- E-Belediye -->
            <div class="group relative">
                <a href="#" class="flex items-center p-4 space-x-3">
                    <span class="material-icons text-2xl">computer</span>
                    <div class="flex flex-col flex-1">
                        <span class="text-base font-semibold">E-BELEDİYE</span>
                        <span class="text-xs text-white/60">Büyükşehir e-Belediye</span>
                    </div>
                    <span class="material-icons text-base">expand_more</span>
                </a>
                <div class="quick-menu-dropdown bg-white">
                    <div class="py-2">
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <span class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
                            Online İşlemler
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <span class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
                            Ödemeler
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <span class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
                            Başvurular
                        </a>
                    </div>
                </div>
            </div>

            <!-- Belediye Rehberi -->
            <div class="group relative">
                <a href="#" class="flex items-center p-4 space-x-3">
                    <span class="material-icons text-2xl">menu_book</span>
                    <div class="flex flex-col flex-1">
                        <span class="text-base font-semibold">BELEDİYE REHBERİ</span>
                        <span class="text-xs text-white/60">Belediyeniz Faaliyetleri</span>
                    </div>
                    <span class="material-icons text-base">expand_more</span>
                </a>
                <div class="quick-menu-dropdown bg-white">
                    <div class="py-2">
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <span class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
                            Hizmetler
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <span class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
                            Projeler
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <span class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
                            İletişim
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    /* Quick menu için ek stiller */
    #quick-menu-section {
        position: relative;
        z-index: 50;
    }
    
    /* Desktop dropdown styles */
    .hidden .quick-menu-dropdown {
        position: absolute !important;
        left: 4px !important;
        bottom: 100% !important;
        top: auto !important;
        width: calc(100% - 8px) !important;
        background: rgba(255, 255, 255, 0.98) !important;
        z-index: 100 !important;
        box-shadow: 0 -4px 12px -3px rgba(0, 0, 0, 0.08) !important;
        display: none !important;
        border-radius: 12px 12px 0 0 !important;
        overflow: visible !important;
        transform-origin: bottom center !important;
        transform: translateY(-5px) !important;
        transition: transform 0.2s ease-out, opacity 0.2s ease-out !important;
        opacity: 0 !important;
        border: 1px solid rgba(0, 0, 0, 0.04) !important;
        border-bottom: none !important;
        margin-bottom: 3px !important;
        min-width: 220px !important;
        backdrop-filter: blur(5px) !important;
    }
    
    /* Hover alanını genişletmek için invisible padding */
    .hidden .quick-menu-dropdown::before {
        content: '' !important;
        position: absolute !important;
        top: -10px !important;
        left: -10px !important;
        right: -10px !important;
        bottom: -10px !important;
        z-index: -1 !important;
    }
    
    .hidden .group:hover .quick-menu-dropdown,
    .hidden .group .quick-menu-dropdown:hover {
        display: block !important;
        transform: translateY(0) !important;
        opacity: 1 !important;
        transition-delay: 0ms !important;
    }
    
    .hidden .group .quick-menu-dropdown {
        transition-delay: 800ms !important;
    }
    
    /* Dropdown'a hover edildiğinde gecikmesiz görünür kal */
    .hidden .group .quick-menu-dropdown:hover {
        transition-delay: 0ms !important;
    }
    
    .quick-menu-dropdown .py-2 {
        padding-top: 0.25rem !important;
        padding-bottom: 0.25rem !important;
    }
    
    .quick-menu-dropdown .py-1 {
        padding-top: 0.2rem !important;
        padding-bottom: 0.2rem !important;
    }
    
    .quick-menu-dropdown a {
        transition: all 0.2s ease !important;
        border-left: 2px solid transparent !important;
        position: relative !important;
        padding-top: 0.35rem !important;
        padding-bottom: 0.35rem !important;
        padding-left: 0.8rem !important;
        padding-right: 0.8rem !important;
        font-size: 0.75rem !important;
        line-height: 1.3 !important;
        display: flex !important;
        align-items: center !important;
        margin-bottom: 0 !important;
        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        font-weight: 300 !important;
        letter-spacing: 0.02em !important;
        color: #333 !important;
    }
    
    .quick-menu-dropdown a .material-icons,
    .quick-menu-dropdown a i {
        font-size: 0.8rem !important;
        margin-right: 0.4rem !important;
        min-width: 18px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        color: rgba(0, 125, 50, 0.7) !important;
    }
    
    .quick-menu-dropdown a:hover {
        background-color: rgba(0, 77, 46, 0.05) !important;
        border-left: 2px solid #007d32 !important;
        color: #007d32 !important;
        transform: translateX(2px) !important;
    }
    
    .quick-menu-dropdown a:hover .material-icons,
    .quick-menu-dropdown a:hover i {
        color: #007d32 !important;
        transform: translateX(1px) !important;
    }

    /* Mobile Menu Styles */
    @media (max-width: 768px) {
        /* Mobilde hızlı menü için özel margin */
        #quick-menu-section {
            margin-top: 0 !important;
            margin-bottom: 1rem;
            border-radius: 0 !important;
            position: relative !important;
        }
        
        /* Mobilde container padding'i sıfırla */
        .w-full.relative.z-50.-mt-11.md\\:-mt-11.mt-0 .container {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
        
        /* Mobil dropdown konumlandırması */
        #quick-menu-section .quick-menu-dropdown {
            position: static !important;
            width: 100% !important;
            background: white !important;
            z-index: 1 !important;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1) !important;
            margin: 0 !important;
            transform: none !important;
            border-radius: 0 !important;
            border-left: 3px solid #007d32 !important;
            border-right: 1px solid rgba(0, 0, 0, 0.1) !important;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1) !important;
            display: none !important;
            opacity: 1 !important;
            max-height: none !important;
            overflow-y: visible !important;
        }

        #quick-menu-section .group.active .quick-menu-dropdown {
            display: block !important;
            visibility: visible !important;
            height: auto !important;
        }

        #quick-menu-section .quick-menu-dropdown a {
            color: black !important;
            padding: 12px 16px !important;
            font-size: 0.875rem !important;
            white-space: normal !important;
            line-height: 1.4 !important;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05) !important;
            transition: all 0.2s ease !important;
        }
        
        #quick-menu-section .quick-menu-dropdown a:last-child {
            border-bottom: none !important;
        }

        #quick-menu-section .quick-menu-dropdown a:hover {
            background-color: rgba(0, 77, 46, 0.1) !important;
            color: #007d32 !important;
        }
        
        /* Mobil menü ok ikonları için animasyon */
        #quick-menu-section .group a .material-icons:last-child {
            transition: transform 0.3s ease !important;
        }
        
        #quick-menu-section .group.active a .material-icons:last-child {
            transform: rotate(180deg) !important;
        }
        
        /* Mobil menü tıklanabilirlik için - W3C uyumlu seçici */
        #quick-menu-section .mobile-menu-container .group > a {
            cursor: pointer !important;
            user-select: none !important;
            -webkit-tap-highlight-color: rgba(0,0,0,0.1) !important;
            transition: all 0.3s ease !important;
        }
        
        /* Normal durumda yazı renklerini belirle - W3C uyumlu seçici */
        #quick-menu-section .mobile-menu-container .group > a .font-semibold {
            color: white !important;
            transition: color 0.3s ease !important;
        }
        
        #quick-menu-section .mobile-menu-container .group > a .text-white\/60,
        #quick-menu-section .mobile-menu-container .group > a .text-xs {
            color: rgba(255, 255, 255, 0.6) !important;
            transition: color 0.3s ease !important;
        }
        
        /* Açıklama yazıları için özel seçici - text-white/60 sınıfı için */
        #quick-menu-section .mobile-menu-container .group > a span.text-white\/60 {
            color: rgba(255, 255, 255, 0.6) !important;
            transition: color 0.3s ease !important;
        }
        
        #quick-menu-section .mobile-menu-container .group > a .group-hover\:text-black {
            color: rgba(255, 255, 255, 0.6) !important;
            transition: color 0.3s ease !important;
        }
        
        /* Sadece gerçek hover destekleyen cihazlarda hover efekti - W3C uyumlu seçici */
        @media (hover: hover) and (pointer: fine) {
            #quick-menu-section .mobile-menu-container .group > a:hover {
                background-color: white !important;
                border-radius: 8px !important;
            }
            
            #quick-menu-section .mobile-menu-container .group > a:hover .font-semibold {
                color: #333 !important;
            }
            
            #quick-menu-section .mobile-menu-container .group > a:hover .text-white\/60,
            #quick-menu-section .mobile-menu-container .group > a:hover .text-xs {
                color: #666 !important;
            }
            
            /* Açıklama yazıları için özel hover seçici */
            #quick-menu-section .mobile-menu-container .group > a:hover span.text-white\/60 {
                color: #666 !important;
            }
            
            #quick-menu-section .mobile-menu-container .group > a:hover .group-hover\:text-black {
                color: #333 !important;
            }
        }
        
        /* Touch cihazlarda sadece active efekti - W3C uyumlu seçici */
        #quick-menu-section .mobile-menu-container .group > a:active {
            background-color: white !important;
            border-radius: 8px !important;
        }
        
        #quick-menu-section .mobile-menu-container .group > a:active .font-semibold {
            color: #333 !important;
        }
        
        #quick-menu-section .mobile-menu-container .group > a:active .text-white\/60,
        #quick-menu-section .mobile-menu-container .group > a:active .text-xs {
            color: #666 !important;
        }
        
        /* Açıklama yazıları için özel active seçici */
        #quick-menu-section .mobile-menu-container .group > a:active span.text-white\/60 {
            color: #666 !important;
        }
        
        #quick-menu-section .mobile-menu-container .group > a:active .group-hover\:text-black {
            color: #333 !important;
        }
        
        /* Desktop menüyü mobilde gizle */
        #quick-menu-section .hidden {
            display: none !important;
        }
        
        /* Mobilde tüm hover efektlerini devre dışı bırak - W3C uyumlu seçici */
        @media (hover: none) and (pointer: coarse) {
            #quick-menu-section .mobile-menu-container .group > a:hover {
                background-color: transparent !important;
            }
            
            #quick-menu-section .mobile-menu-container .group > a:hover .group-hover\:text-black {
                color: rgba(255, 255, 255, 0.6) !important;
            }
        }


    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Desktop hover functionality - basit CSS hover kullanıyoruz, JavaScript'e gerek yok
    
    // Mobile menu functionality - Daha spesifik seçici kullan
    const quickMenuSection = document.getElementById('quick-menu-section');
    if (quickMenuSection) {
        const mobileContainer = quickMenuSection.querySelector('.mobile-menu-container');
        if (mobileContainer) {
            const mobileGroups = mobileContainer.querySelectorAll('.group');
            
            mobileGroups.forEach((group, index) => {
                const link = group.querySelector('a');
                
                if (link) {
                    
                    // Çakışmayı önlemek için event'i sadece bir kez ekle
                    if (!link.hasAttribute('data-quick-menu-handled')) {
                        link.setAttribute('data-quick-menu-handled', 'true');
                        
                        link.addEventListener('click', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            
                            // Diğer tüm açık olan menüleri kapat
                            mobileGroups.forEach(otherGroup => {
                                if (otherGroup !== group) {
                                    otherGroup.classList.remove('active');
                                }
                            });
                            
                            // Bu menüyü aç/kapat
                            group.classList.toggle('active');
                        });
                        
                        // Touch event de ekle
                        link.addEventListener('touchstart', function(e) {
                            // Touch event handling
                        });
                    }
                }
            });
        }
    }
    
    // Sayfa dışına tıklandığında dropdown'ları kapat
    document.addEventListener('click', function(e) {
        const quickMenuSection = document.getElementById('quick-menu-section');
        if (quickMenuSection && !quickMenuSection.contains(e.target)) {
            const activeGroups = quickMenuSection.querySelectorAll('.group.active');
            if (activeGroups.length > 0) {
                activeGroups.forEach(group => group.classList.remove('active'));
            }
        }
    });
    
    // ESC tuşu ile dropdown'ları kapat
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const quickMenuSection = document.getElementById('quick-menu-section');
            if (quickMenuSection) {
                const activeGroups = quickMenuSection.querySelectorAll('.group.active');
                if (activeGroups.length > 0) {
                    activeGroups.forEach(group => group.classList.remove('active'));
                }
            }
        }
    });
});
</script> 