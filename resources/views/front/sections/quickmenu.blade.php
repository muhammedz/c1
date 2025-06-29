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
                        @if($category->icon && Str::startsWith($category->icon, 'data:image/'))
                            <img src="{{ $category->icon }}" alt="İkon" style="width: 32px; height: 32px; object-fit: contain;">
                        @elseif(Str::startsWith($category->icon, 'fas ') || Str::startsWith($category->icon, 'far ') || Str::startsWith($category->icon, 'fab '))
                            <i class="{{ $category->icon }} text-2xl"></i>
                        @else
                            <span class="material-icons text-2xl">{{ $category->icon ?? 'menu' }}</span>
                        @endif
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
    <div class="mobile-menu-container mobile-quickmenu-container">
        <div class="flex flex-col divide-y divide-white/20">
            @if(isset($quickMenuCategories) && $quickMenuCategories->count() > 0)
                @foreach($quickMenuCategories->take(4) as $category)
                    @if($category->activeItems->count() > 0)
                    <div class="group relative">
                        <a href="#" class="flex items-center p-4 space-x-3">
                            @if($category->icon && Str::startsWith($category->icon, 'data:image/'))
                                <img src="{{ $category->icon }}" alt="İkon" style="width: 32px; height: 32px; object-fit: contain;">
                            @elseif(Str::startsWith($category->icon, 'fas ') || Str::startsWith($category->icon, 'far ') || Str::startsWith($category->icon, 'fab '))
                                <i class="{{ $category->icon }} text-2xl"></i>
                            @else
                                <span class="material-icons text-2xl">{{ $category->icon ?? 'menu' }}</span>
                            @endif
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