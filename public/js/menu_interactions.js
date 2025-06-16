/**
 * Mega Menü Etkileşimleri
 * Menü açılıp kapanması, filtreleme ve diğer etkileşimler için JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    // Mobil menü açma/kapama
    initMobileMenu();
    
    // Menü kartları filtreleme
    initCardFiltering();
    
    // Arama butonu etkileşimi
    initSearchButton();
    
    // Dokunmatik tarama desteği
    initTouchSupport();
});

/**
 * Mobil menü açma/kapama işlevselliği
 */
// KAPALI - header.blade.php'te aktif
function initMobileMenu() {
    // Bu fonksiyon şu anda pasif - header.blade.php'te aktif olan versiyon kullanılıyor
    return;
    /*
    const mobileMenuButton = document.getElementById('mobileMenuButton');
    const mobileMenu = document.getElementById('mobileMenu');
    
    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });
    }
    */
}
    
    // Ana menü toggle
    const mobileMenuToggles = document.querySelectorAll('.mobile-menu-toggle');
    mobileMenuToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const parent = this.closest('.group');
            const submenu = parent.querySelector('.mobile-submenu');
            
            if (submenu) {
                submenu.classList.toggle('hidden');
                
                // İkon değiştirme
                if (this.innerHTML === 'expand_more') {
                    this.innerHTML = 'expand_less';
                } else {
                    this.innerHTML = 'expand_more';
                }
            }
        });
    });
    
    // Kategori toggle
    const categoryToggles = document.querySelectorAll('.mobile-category-toggle');
    categoryToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const items = this.nextElementSibling;
            items.classList.toggle('hidden');
            
            // İkon değiştirme
            const icon = this.querySelector('.material-icons');
            if (icon) {
                if (icon.innerHTML === 'expand_more') {
                    icon.innerHTML = 'expand_less';
                } else {
                    icon.innerHTML = 'expand_more';
                }
            }
        });
    });
}

/**
 * Menü kartları filtreleme işlevselliği
 */
function initCardFiltering() {
    const tagLinks = document.querySelectorAll('[data-category]');
    const menuCards = document.querySelectorAll('.menu-card');
    
    if (tagLinks.length > 0 && menuCards.length > 0) {
        tagLinks.forEach(tag => {
            tag.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Aktif etiket sınıfını kaldır
                tagLinks.forEach(t => t.classList.remove('tag-active'));
                
                // Tıklanan etikete aktif sınıfını ekle
                this.classList.add('tag-active');
                
                const category = this.getAttribute('data-category');
                
                // Kartları filtrele
                menuCards.forEach(card => {
                    const cardCategory = card.getAttribute('data-category');
                    
                    if (category === 'all' || cardCategory === category) {
                        card.style.display = 'flex';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
    }
}

/**
 * Arama butonu işlevselliği
 */
function initSearchButton() {
    const searchButton = document.getElementById('searchButton');
    const searchModal = document.getElementById('searchModal');
    
    if (searchButton && searchModal) {
        searchButton.addEventListener('click', function() {
            searchModal.classList.remove('hidden');
            
            // Arama kutusuna odaklanma
            const searchInput = searchModal.querySelector('input[type="search"]');
            if (searchInput) {
                setTimeout(() => {
                    searchInput.focus();
                }, 100);
            }
        });
        
        // Kapatma butonu
        const closeButton = searchModal.querySelector('.close-button');
        if (closeButton) {
            closeButton.addEventListener('click', function() {
                searchModal.classList.add('hidden');
            });
        }
        
        // ESC tuşu ile kapatma
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !searchModal.classList.contains('hidden')) {
                searchModal.classList.add('hidden');
            }
        });
    }
}

/**
 * Dokunmatik cihazlar için gerekli davranışlar
 */
function initTouchSupport() {
    // Masaüstünde hover ile çalışan mega menüler için dokunmatik desteği
    const isTouchDevice = ('ontouchstart' in window) || (navigator.maxTouchPoints > 0) || (navigator.msMaxTouchPoints > 0);
    
    if (isTouchDevice) {
        const menuItems = document.querySelectorAll('.nav-desktop-menu .group > a');
        
        menuItems.forEach(item => {
            item.addEventListener('click', function(e) {
                const parent = this.parentElement;
                const hasMegaMenu = parent.querySelector('.mega-menu') !== null;
                const hasSubmenu = parent.querySelector('.submenu') !== null;
                
                if (hasMegaMenu || hasSubmenu) {
                    // Zaten açıksa kapatma
                    if (parent.classList.contains('touch-open')) {
                        parent.classList.remove('touch-open');
                        return;
                    }
                    
                    // Tüm açık olanları kapat
                    document.querySelectorAll('.touch-open').forEach(el => {
                        el.classList.remove('touch-open');
                    });
                    
                    // Bu elemanı aç
                    parent.classList.add('touch-open');
                    e.preventDefault();
                }
            });
        });
        
        // Doküman tıklaması ile açık menüleri kapatma
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.nav-desktop-menu')) {
                document.querySelectorAll('.touch-open').forEach(el => {
                    el.classList.remove('touch-open');
                });
            }
        });
    }
}

// Menüleri belirli bir pencere genişliğinde yeniden düzenleme
window.addEventListener('resize', function() {
    // Ekran boyutu değiştikçe işlemler
    const mobileMenu = document.getElementById('mobileMenu');
    
    if (window.innerWidth >= 768 && mobileMenu && !mobileMenu.classList.contains('hidden')) {
        mobileMenu.classList.add('hidden');
    }
}); 