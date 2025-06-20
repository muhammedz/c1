document.addEventListener('DOMContentLoaded', function () {
    /* Mega Menü Yönetimi - Tamamen Yeniden Yazıldı */
    
    // Scroll olayı için değişkenler
    let scrollTimeout;
    let isScrolling = false;
    const scrollThreshold = 10; // piksel
    let lastScrollPos = window.scrollY;
    
    // Mega menü gruplarını bul ve data-mega-menu özelliği ekle
    const menuGroups = document.querySelectorAll('.group');
    menuGroups.forEach((group, index) => {
        const menuId = `menu-${index}`;
        group.setAttribute('data-mega-menu', menuId);
        
        const megaMenu = group.querySelector('.mega-menu');
        if (megaMenu) {
            megaMenu.setAttribute('data-mega-menu-content', menuId);
            
            // Mega menü container genişliğini ayarla
            group.addEventListener('mouseenter', () => {
                adjustMegaMenuWidth(megaMenu);
                // Animasyon sıfırlama
                resetMegaMenuAnimations(megaMenu);
            });
            
            // Mega menü kapanırken animasyonu sıfırla
            group.addEventListener('mouseleave', () => {
                setTimeout(() => {
                    if (!group.matches(':hover')) {
                        resetMegaMenuAnimations(megaMenu);
                    }
                }, 100);
            });
        }
    });
    
    // Mega menü genişliğini ayarlama fonksiyonu
    function adjustMegaMenuWidth(megaMenu) {
        if (!megaMenu) return;
        
        // Mega menünün genişliğini tam olarak ayarla
        const headerWidth = document.querySelector('header')?.offsetWidth || document.body.offsetWidth;
        const headerLeft = document.querySelector('header')?.getBoundingClientRect().left || 0;
        
        megaMenu.style.width = headerWidth + 'px';
        megaMenu.style.left = -headerLeft + 'px';
        
        // İçerik genişliğini de ayarla
        const content = megaMenu.querySelector('.mega-menu-content');
        if (content) {
            content.style.maxWidth = '1280px';
            content.style.margin = '0 auto';
            content.style.width = '100%';
        }
    }
    
    // Mega menü animasyonlarını sıfırlama fonksiyonu (artık sadece kutu animasyonu olduğu için basitleştirdik)
    function resetMegaMenuAnimations(megaMenu) {
        if (!megaMenu) return;
        
        // Mega menü kutusu zaten CSS transition ile yönetiliyor
        // Ek bir sıfırlama işlemi gerekmiyor
    }
    
    // Sayfa boyutu değiştiğinde mega menü genişliklerini yeniden ayarla
    window.addEventListener('resize', () => {
        document.querySelectorAll('.mega-menu').forEach(menu => {
            if (menu.closest('.group:hover')) {
                adjustMegaMenuWidth(menu);
            }
        });
    });
    
    // Scroll olayını dinle
    window.addEventListener('scroll', function() {
        // Scroll miktarını kontrol et
        const currentScrollPos = window.scrollY;
        const scrollAmount = Math.abs(currentScrollPos - lastScrollPos);
        
        if (scrollAmount > scrollThreshold) {
            // Scroll belirli bir eşiği geçerse, menüleri gizle
            document.body.classList.add('is-scrolling');
            isScrolling = true;
            
            // Tüm mega menüleri gizle ve hover durumlarını sıfırla
            menuGroups.forEach(group => {
                // Grup içindeki menüyü bul
                const menuId = group.getAttribute('data-mega-menu');
                const megaMenu = document.querySelector(`[data-mega-menu-content="${menuId}"]`);
                
                if (megaMenu) {
                    megaMenu.classList.add('force-hide');
                }
            });
        }
        
        // Scroll durduğunda
        clearTimeout(scrollTimeout);
        scrollTimeout = setTimeout(() => {
            // Scroll durduğunda gizleme iptal
            document.body.classList.remove('is-scrolling');
            isScrolling = false;
            
            // Force-hide sınıfını kaldır
            document.querySelectorAll('.force-hide').forEach(menu => {
                menu.classList.remove('force-hide');
            });
            
            // Son scroll pozisyonunu güncelle
            lastScrollPos = window.scrollY;
        }, 150);
    });
    
    // Mobil menü kodları - Bu kod quickmenu.blade.php'deki kodla çakışıyor
    // Çakışmayı önlemek için daha spesifik seçici kullan veya kaldır
    const mobileMenuItems = document.querySelectorAll('.md\\:hidden .group:not([data-quick-menu-handled])');

    mobileMenuItems.forEach(item => {
        const link = item.querySelector('a');
        const dropdown = item.querySelector('.quick-menu-dropdown');
        const icon = link ? link.querySelector('.material-icons:last-child') : null;

        if (link && !link.hasAttribute('data-quick-menu-handled')) {
            link.addEventListener('click', (e) => {
                e.preventDefault();

                // Diğer tüm dropdownları kapat
                mobileMenuItems.forEach(otherItem => {
                    if (otherItem !== item) {
                        otherItem.classList.remove('active');
                        const otherIcon = otherItem.querySelector('.material-icons:last-child');
                        if (otherIcon) {
                            otherIcon.style.transform = 'rotate(0deg)';
                        }
                    }
                });

                // Tıklanan dropdownı aç/kapat
                item.classList.toggle('active');
                if (icon) {
                    icon.style.transform = item.classList.contains('active') ? 'rotate(180deg)' : 'rotate(0deg)';
                }
            });
        }
    });
    
    // Mega menü kategorileri için hover efekti
    const megaMenuCategories = document.querySelectorAll('.mega-menu-category');
    megaMenuCategories.forEach(category => {
        category.addEventListener('mouseenter', () => {
            if (!isScrolling) {
                category.classList.add('hover-active');
            }
        });
        
        category.addEventListener('mouseleave', () => {
            category.classList.remove('hover-active');
        });
    });
    
    // Mega menü linklerinde hover efekti için
    const megaMenuLinks = document.querySelectorAll('.mega-menu-link');
    megaMenuLinks.forEach(link => {
        const icon = link.querySelector('.material-icons');
        
        link.addEventListener('mouseenter', () => {
            if (!isScrolling && icon) {
                icon.style.transform = 'translateX(3px)';
                icon.style.opacity = '1';
            }
        });
        
        link.addEventListener('mouseleave', () => {
            if (icon) {
                icon.style.transform = 'translateX(0)';
                icon.style.opacity = '0.7';
            }
        });
    });
});

// Mobile Menu Toggle - Bu kod quickmenu.blade.php'de handle ediliyor
// Çakışmayı önlemek için kaldırıldı

// News slider tanımları home.blade.php dosyasında yapılıyor - çakışmayı önlemek için kaldırıldı

// servicesSwiper kaldırıldı - featured-services.blade.php dosyasında kendi JavaScript sistemi kullanılıyor

// Eski projeler slider kodu kaldırıldı - projects.blade.php dosyasında yeni sistem kullanılıyor

// News Slider ve Cards Interaction kodları home.blade.php dosyasında yapılıyor - çakışmayı önlemek için kaldırıldı

// Eski projeler sistemi tamamen kaldırıldı - projects.blade.php dosyasında yeni izole sistem kullanılıyor

// timelineSwiper ve servicesSwiper kaldırıldı - ilgili bölümler kendi JavaScript sistemlerini kullanıyor
// Mobil menü toggle - KAPALI (header.blade.php'te aktif)
/*
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuButton = document.getElementById('mobileMenuButton');
    const mobileMenu = document.getElementById('mobileMenu');

    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });
    }
});
*/

// Kullanılmayan mobil menü kodları kaldırıldı - quickmenu.blade.php ve header.blade.php kendi sistemlerini kullanıyor

// Eski projeler slider sistemi kaldırıldı - projects.blade.php dosyasında izole sistem kullanılıyor

// Kullanılmayan mobileMenuBtn ve mobile-menu-btn kodları kaldırıldı

document.addEventListener('DOMContentLoaded', function () {
    const searchButton = document.getElementById('searchButton');
    const searchModal = document.getElementById('searchModal');
    const closeSearch = document.getElementById('closeSearch');

    // Null kontrolleri ekle
    if (searchButton && searchModal && closeSearch) {
        // Arama modalını aç
        searchButton.addEventListener('click', () => {
            searchModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        });

        // Arama modalını kapat
        closeSearch.addEventListener('click', () => {
            searchModal.classList.add('hidden');
            document.body.style.overflow = '';
        });

        // Modal dışına tıklandığında kapat
        searchModal.addEventListener('click', (e) => {
            if (e.target === searchModal) {
                searchModal.classList.add('hidden');
                document.body.style.overflow = '';
            }
        });

        // ESC tuşu ile kapat
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !searchModal.classList.contains('hidden')) {
                searchModal.classList.add('hidden');
                document.body.style.overflow = '';
            }
        });
    }
});

// Tab butonları için olay dinleyici
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabPanes = document.querySelectorAll('.tab-pane');

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Aktif butonun stilini kaldır
            tabButtons.forEach(btn => {
                btn.classList.remove('tab-active');
                btn.classList.remove('text-white');
                btn.classList.add('bg-gray-50');
                btn.classList.add('text-gray-700');
                
                // İkon rengini güncelle
                const btnIcon = btn.querySelector('.material-icons');
                if (btnIcon) {
                    btnIcon.classList.remove('text-white');
                    btnIcon.classList.add('text-gray-500');
                }
                
                // Arka plan rengini değiştir
                btn.style.background = "";
            });

            // Tıklanan butona aktif stili ekle
            button.classList.add('tab-active');
            button.classList.add('text-white');
            button.classList.remove('bg-gray-50');
            button.classList.remove('text-gray-700');
            
            // Arka plan gradientini uygula
            button.style.background = "linear-gradient(135deg, #007b32, #006128)";
            
            // İkon rengini güncelle
            const icon = button.querySelector('.material-icons');
            if (icon) {
                icon.classList.remove('text-gray-500');
                icon.classList.add('text-white');
            }

            // Tab içeriğini göster/gizle
            const tabId = button.getAttribute('data-tab');
            tabPanes.forEach(pane => {
                pane.classList.add('hidden');
                if (pane.id === tabId) {
                    pane.classList.remove('hidden');
                }
            });
        });
    });

    // Hizmetler menüsü kartları için olay dinleyici
    const serviceCards = document.querySelectorAll('.mega-menu-content a[data-category]');
    const categoryPanes = document.querySelectorAll('.mega-menu-content .tab-pane');

    serviceCards.forEach(card => {
        card.addEventListener('click', function(e) {
            e.preventDefault(); // Link yönlendirmesini engelle
            
            // Aktif kartın stilini kaldır
            serviceCards.forEach(c => {
                c.classList.remove('bg-blue-50');
                c.classList.add('bg-gray-50');
                const icon = c.querySelector('.material-icons');
                if (icon) {
                    icon.classList.remove('text-blue-600');
                    icon.classList.add('text-blue-500');
                }
            });
            
            // Tıklanan karta aktif stil ekle
            this.classList.remove('bg-gray-50');
            this.classList.add('bg-blue-50');
            const cardIcon = this.querySelector('.material-icons');
            if (cardIcon) {
                cardIcon.classList.remove('text-blue-500');
                cardIcon.classList.add('text-blue-600');
            }
            
            // Kategori içeriğini göster
            const categoryId = this.getAttribute('data-category');
            
            // Önce kart konteyneri gizlensin ve içerik konteyneri gösterilsin
            const cardsContainer = document.querySelector('.mega-menu-content .grid');
            const contentContainer = document.querySelector('.mega-menu-content .hidden');
            
            if (cardsContainer && contentContainer) {
                cardsContainer.classList.add('hidden');
                contentContainer.classList.remove('hidden');
                
                // İlgili tab içeriğini göster
                categoryPanes.forEach(pane => {
                    pane.classList.add('hidden');
                    if (pane.id === categoryId) {
                        pane.classList.remove('hidden');
                    }
                });
            }
        });
    });
    
    // Geri dönüş butonu ekleyelim
    const contentContainer = document.querySelector('.mega-menu-content .hidden');
    if (contentContainer) {
        const backButton = document.createElement('button');
        backButton.innerHTML = '<span class="material-icons mr-1">arrow_back</span> Kategorilere Dön';
        backButton.className = 'flex items-center text-blue-600 hover:text-blue-800 font-medium mb-4';
        
        backButton.addEventListener('click', function() {
            // İçerik konteynerini gizle
            contentContainer.classList.add('hidden');
            
            // Kategori kartlarını göster
            const cardsContainer = document.querySelector('.mega-menu-content .grid');
            if (cardsContainer) {
                cardsContainer.classList.remove('hidden');
            }
            
            // Aktif kart stilini kaldır
            serviceCards.forEach(c => {
                c.classList.remove('bg-blue-50');
                c.classList.add('bg-gray-50');
                const icon = c.querySelector('.material-icons');
                if (icon) {
                    icon.classList.remove('text-blue-600');
                    icon.classList.add('text-blue-500');
                }
            });
        });
        
        contentContainer.prepend(backButton);
    }
});

// tabContainer kodu kaldırıldı - hide-scrollbar class'ı kullanılmıyor

// Hizmetler Mega Menü kodu kaldırıldı - card_grid_mega_menu.blade.php dosyasında kendi JavaScript sistemi kullanılıyor

// Hizmetler Menüsü Kart Fonksiyonalitesi kodu kaldırıldı - card_grid_mega_menu.blade.php dosyasında kendi JavaScript sistemi kullanılıyor


