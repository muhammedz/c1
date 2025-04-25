/**
 * Menu System JS
 * Bu dosya, menü sistemi için gerekli tüm JavaScript fonksiyonlarını içerir.
 */

// Sayfa yüklendiğinde çalışacak fonksiyonlar
document.addEventListener('DOMContentLoaded', function() {
    initDesktopMenus();
    initMobileMenu();
});

/**
 * Desktop menüleri için hover işlemlerini başlatır
 */
function initDesktopMenus() {
    const menuGroups = document.querySelectorAll('.mega-menu-wrapper');
    
    menuGroups.forEach(group => {
        // Mouse üzerine geldiğinde menüyü göster
        group.addEventListener('mouseenter', function() {
            const megaMenu = this.querySelector('.mega-menu');
            if (megaMenu) {
                megaMenu.classList.remove('hidden');
                megaMenu.classList.add('block');
            }
        });
        
        // Mouse ayrıldığında menüyü gizle
        group.addEventListener('mouseleave', function() {
            const megaMenu = this.querySelector('.mega-menu');
            if (megaMenu) {
                megaMenu.classList.add('hidden');
                megaMenu.classList.remove('block');
            }
        });
    });
}

/**
 * Mobil menü için toggle işlemlerini başlatır
 */
function initMobileMenu() {
    // Ana mobil menü toggle butonu
    const mobileMenuButton = document.getElementById('mobileMenuButton');
    const mobileMenu = document.getElementById('mobileMenu');
    
    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });
    }
    
    // Mobil menü grup toggle
    const menuToggles = document.querySelectorAll('.mobile-menu-toggle');
    menuToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            
            // En yakın grup elemanını bul
            const group = this.closest('.mobile-menu-group');
            if (!group) return;
            
            // Menüyü bul ve durumunu değiştir
            const submenu = group.querySelector('.mobile-submenu');
            if (submenu) {
                submenu.classList.toggle('hidden');
                
                // İkonu değiştir
                const icon = this.querySelector('.material-icons');
                if (icon) {
                    icon.textContent = submenu.classList.contains('hidden') 
                        ? 'expand_more' 
                        : 'expand_less';
                }
            }
        });
    });
    
    // Mobil kategori toggle
    const categoryToggles = document.querySelectorAll('.mobile-category-toggle');
    categoryToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            
            // En yakın kategori elemanını bul
            const category = this.closest('.mobile-category');
            if (!category) return;
            
            // Kategori öğelerini bul ve durumunu değiştir
            const items = category.querySelector('.mobile-category-items');
            if (items) {
                items.classList.toggle('hidden');
                
                // İkonu değiştir
                const icon = this.querySelector('.material-icons');
                if (icon) {
                    icon.textContent = items.classList.contains('hidden') 
                        ? 'expand_more' 
                        : 'expand_less';
                }
            }
        });
    });
}

/**
 * Aktif menü öğesini belirler ve vurgular
 */
function highlightActiveMenuItem() {
    const currentPath = window.location.pathname;
    
    // Tüm menü linklerini kontrol et
    const menuLinks = document.querySelectorAll('a[href]');
    
    menuLinks.forEach(link => {
        const href = link.getAttribute('href');
        
        // Boş veya # olan linkleri atla
        if (!href || href === '#') return;
        
        // Tam eşleşme veya alt sayfa kontrolü
        if (href === currentPath || (href !== '/' && currentPath.startsWith(href))) {
            link.classList.add('active', 'text-primary-600');
            
            // Ebeveyn menü gruplarını da işaretle
            const megaMenuWrapper = link.closest('.mega-menu-wrapper');
            if (megaMenuWrapper) {
                const toggle = megaMenuWrapper.querySelector('.mega-menu-toggle');
                if (toggle) {
                    toggle.classList.add('text-primary-600');
                }
            }
            
            // Mobil menüdeki ebeveyn kategoriyi de işaretle
            const mobileCategory = link.closest('.mobile-category');
            if (mobileCategory) {
                const header = mobileCategory.querySelector('.mobile-category-header');
                if (header) {
                    header.classList.add('text-primary-600');
                }
            }
        }
    });
} 