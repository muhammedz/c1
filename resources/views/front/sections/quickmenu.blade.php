<!-- #### Quick Menu Section -->
<div id="quick-menu-section" class="quick-menu-section w-full rounded-md bg-gradient-radial text-white mt-[-30px] relative z-50">
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
                <div class="group relative {{ $widthClass }} {{ $index < $categoryCount - 1 ? 'border-r border-white/20' : '' }}">
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
                    <div class="quick-menu-dropdown" style="position: absolute; left: 0; bottom: 100%; width: 100%; background: white; z-index: 999; display: none;">
                        <div class="py-1">
                            @foreach($category->activeItems as $item)
                            <a href="{{ $item->url }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
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
        <div class="group relative w-[300px] border-r border-white/20">
            <a href="#"
                class=" group flex items-center h-full px-6 py-4 space-x-3 transition-colors duration-300">
                <span class="material-icons text-2xl">language</span>
                <div class="flex flex-col">
                    <span class="text-base font-semibold">ONLINE HİZMETLER</span>
                    <span class=" group-hover:text-black text-xs text-white/60">Büyükşehir Online
                        İşlemler</span>
                </div>
                <span
                    class="material-icons rot text-base transition-transform duration-300 group-hover:rotate-180 ml-auto">expand_more</span>
            </a>
            <div class="quick-menu-dropdown" style="position: absolute; left: 0; bottom: 100%; width: 100%; background: white; z-index: 999; display: none;">
                <div class="py-2">
                    <a href="#"
                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <span
                            class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
                        Mebis
                    </a>
                    <a href="#"
                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <span
                            class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
                        Ulaşım Bilgi Sistemi
                    </a>
                    <a href="#"
                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <span
                            class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
                        EBYS Evrak Doğrulama
                    </a>
                    <a href="#"
                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <span
                            class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
                        E-Posta
                    </a>
                    <a href="#"
                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <span
                            class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
                        E-Bordro
                    </a>
                    <a href="#"
                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <span
                            class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
                        E-Belediye
                    </a>
                </div>
            </div>
        </div>
        <!-- Sosyal Hizmetler -->
        <div class="group relative w-[300px] border-r border-white/20">
            <a href="#"
                class=" group flex items-center h-full px-6 py-4 space-x-3 transition-colors duration-300">
                <span class="material-icons text-2xl">people</span>
                <div class="flex flex-col">
                    <span class="text-base font-semibold">SOSYAL HİZMETLER</span>
                    <span class=" group-hover:text-black text-xs text-white/60">Sosyal yardımlar,
                        faaliyetler</span>
                </div>
                <span
                    class="material-icons rot text-base transition-transform duration-300 group-hover:rotate-180 ml-auto">expand_more</span>
            </a>
            <div class="quick-menu-dropdown" style="position: absolute; left: 0; bottom: 100%; width: 100%; background: white; z-index: 999; display: none;">
                <div class="py-2">
                    <a href="#"
                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <span
                            class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
                        Sosyal Yardımlar
                    </a>
                    <a href="#"
                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <span
                            class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
                        Etkinlikler
                    </a>
                    <a href="#"
                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <span
                            class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
                        Kurslar
                    </a>
                </div>
            </div>
        </div>
        <!-- E-Belediye -->
        <div class="group relative w-[300px] border-r border-white/20">
            <a href="#"
                class="group flex items-center h-full px-6 py-4 space-x-3 transition-colors duration-300">
                <span class="material-icons text-2xl">computer</span>
                <div class="flex flex-col">
                    <span class="text-base font-semibold">E-BELEDİYE</span>
                    <span class=" group-hover:text-black text-xs text-white/60">Büyükşehir
                        e-Belediye</span>
                </div>
                <span
                    class="material-icons rot text-base transition-transform duration-300 group-hover:rotate-180 ml-auto">expand_more</span>
            </a>
            <div class="quick-menu-dropdown" style="position: absolute; left: 0; bottom: 100%; width: 100%; background: white; z-index: 999; display: none;">
                <div class="py-2">
                    <a href="#"
                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <span
                            class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
                        Online İşlemler
                    </a>
                    <a href="#"
                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <span
                            class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
                        Ödemeler
                    </a>
                    <a href="#"
                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <span
                            class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
                        Başvurular
                    </a>
                </div>
            </div>
        </div>
        <!-- Belediye Rehberi -->
        <div class="group relative w-[300px]">
            <a href="#"
                class=" group flex items-center h-full px-6 py-4 space-x-3 transition-colors duration-300">
                <span class="material-icons text-2xl">menu_book</span>
                <div class="flex flex-col">
                    <span class="text-base font-semibold">BELEDİYE REHBERİ</span>
                    <span class=" group-hover:text-black text-xs text-white/60">Belediyeniz
                        Faaliyetleri</span>
                </div>
                <span
                    class="material-icons rot text-base transition-transform duration-300 group-hover:rotate-180 ml-auto">expand_more</span>
            </a>
            <div class="quick-menu-dropdown" style="position: absolute; left: 0; bottom: 100%; width: 100%; background: white; z-index: 999; display: none;">
                <div class="py-2">
                    <a href="#"
                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <span
                            class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
                        Hizmetler
                    </a>
                    <a href="#"
                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <span
                            class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
                        Projeler
                    </a>
                    <a href="#"
                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <span
                            class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
                        İletişim
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Mobile Menu -->
    <div class="md:hidden">
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
                                <a href="{{ $item->url }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
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
                        <a href="#"
                            class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <span
                                class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
                            Mebis
                        </a>
                        <a href="#"
                            class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <span
                                class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
                            Ulaşım Bilgi Sistemi
                        </a>
                        <a href="#"
                            class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <span
                                class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
                            EBYS Evrak Doğrulama
                        </a>
                        <a href="#"
                            class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <span
                                class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
                            E-Posta
                        </a>
                        <a href="#"
                            class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <span
                                class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
                            E-Bordro
                        </a>
                        <a href="#"
                            class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <span
                                class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
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
                        <a href="#"
                            class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <span
                                class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
                            Sosyal Yardımlar
                        </a>
                        <a href="#"
                            class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <span
                                class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
                            Etkinlikler
                        </a>
                        <a href="#"
                            class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <span
                                class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
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
                        <a href="#"
                            class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <span
                                class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
                            Online İşlemler
                        </a>
                        <a href="#"
                            class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <span
                                class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
                            Ödemeler
                        </a>
                        <a href="#"
                            class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <span
                                class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
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
                        <a href="#"
                            class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <span
                                class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
                            Hizmetler
                        </a>
                        <a href="#"
                            class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <span
                                class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
                            Projeler
                        </a>
                        <a href="#"
                            class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <span
                                class="material-icons text-gray-400 mr-2 text-base">arrow_forward</span>
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
        z-index: 60;
    }
    
    .quick-menu-dropdown {
        position: absolute !important;
        left: 0 !important;
        bottom: 100% !important;
        top: auto !important;
        width: 100% !important;
        background: rgba(255, 255, 255, 0.98) !important;
        z-index: 999 !important;
        box-shadow: 0 -4px 12px -3px rgba(0, 0, 0, 0.08) !important;
        display: none !important;
        border-radius: 12px 12px 0 0 !important;
        overflow: hidden !important;
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
    
    .group:hover .quick-menu-dropdown {
        display: block !important;
        transform: translateY(0) !important;
        opacity: 1 !important;
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
        .quick-menu-dropdown {
            position: static !important;
            bottom: auto !important;
            width: 100% !important;
            box-shadow: none !important;
            margin: 0 !important;
            transform: none !important;
            border-radius: 0 !important;
            border: none !important;
            border-left: 2px solid #007d32 !important;
        }

        .group.active .quick-menu-dropdown {
            display: block !important;
            opacity: 1 !important;
        }

        .quick-menu-dropdown a {
            color: black !important;
            padding: 8px 12px !important;
            font-size: 0.7rem !important;
            white-space: normal !important;
        }

        .quick-menu-dropdown a:hover {
            background-color: rgba(0, 77, 46, 0.1) !important;
            color: #007d32 !important;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Desktop dropdown hover işlevselliği
        const desktopGroups = document.querySelectorAll('#quick-menu-section .md\\:flex .group');
        desktopGroups.forEach(group => {
            const dropdown = group.querySelector('.quick-menu-dropdown');
            
            if (dropdown) {
                // Hover işlevselliği - üzerine gelince dropdown'ı göster
                group.addEventListener('mouseenter', function() {
                    dropdown.style.display = 'block';
                });
                
                // Hover işlevselliği - üzerinden ayrılınca dropdown'ı gizle
                group.addEventListener('mouseleave', function() {
                    dropdown.style.display = 'none';
                });
            }
        });
        
        // Mobil menü dropdown işlevselliği
        const mobileGroups = document.querySelectorAll('#quick-menu-section .md\\:hidden .group');
        mobileGroups.forEach(group => {
            const link = group.querySelector('a');
            const dropdown = group.querySelector('.quick-menu-dropdown');
            const icon = link.querySelector('.material-icons:last-child');
            
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Diğer tüm açık olan menüleri kapat
                mobileGroups.forEach(otherGroup => {
                    if (otherGroup !== group && otherGroup.classList.contains('active')) {
                        otherGroup.classList.remove('active');
                        const otherDropdown = otherGroup.querySelector('.quick-menu-dropdown');
                        if (otherDropdown) otherDropdown.style.display = 'none';
                        const otherIcon = otherGroup.querySelector('a .material-icons:last-child');
                        if (otherIcon) otherIcon.style.transform = 'rotate(0deg)';
                    }
                });
                
                // Bu menüyü aç/kapat
                group.classList.toggle('active');
                if (dropdown) {
                    dropdown.style.display = group.classList.contains('active') ? 'block' : 'none';
                }
                if (icon) {
                    icon.style.transform = group.classList.contains('active') ? 'rotate(180deg)' : 'rotate(0deg)';
                }
            });
        });
    });
</script> 