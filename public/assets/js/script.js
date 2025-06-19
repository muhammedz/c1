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

document.addEventListener('DOMContentLoaded', function () {
    const servicesSwiper = new Swiper('.servicesSwiper', {
        slidesPerView: 1,
        spaceBetween: 24,
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },
        breakpoints: {
            640: {
                slidesPerView: 2,
            },
            768: {
                slidesPerView: 3,
            },
            1024: {
                slidesPerView: 4,
            },
            1280: {
                slidesPerView: 5,
            }
        }
    });
});

document.addEventListener('DOMContentLoaded', function () {
    // Tüm slider'ları başlat
    const swipers = {};
    document.querySelectorAll('.projectSwiper').forEach(function (element) {
        const category = element.dataset.category;
        swipers[category] = new Swiper(element, {
            slidesPerView: 1,
            spaceBetween: 24,
            navigation: {
                nextEl: '.swiper-next',
                prevEl: '.swiper-prev',
            },
            breakpoints: {
                640: {
                    slidesPerView: 1,
                },
                768: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 2,
                }
            }
        });
    });

    // Kategori değiştirme işlevi
    const categoryButtons = document.querySelectorAll('.category-btn');
    const categoryTitle = document.getElementById('categoryTitle');
    const projectSliders = document.querySelectorAll('.projectSwiper');

    categoryButtons.forEach(button => {
        button.addEventListener('click', function () {
            const category = this.dataset.category;

            // Aktif kategori butonunu güncelle
            categoryButtons.forEach(btn => {
                btn.classList.remove('bg-[#004d2e]', 'text-white', 'hover:bg-[#003d24]');
                btn.classList.add('bg-gray-200', 'text-gray-800', 'hover:bg-gray-300');
            });
            this.classList.remove('bg-gray-200', 'text-gray-800', 'hover:bg-gray-300');
            this.classList.add('bg-[#004d2e]', 'text-white', 'hover:bg-[#003d24]');

            // Başlığı güncelle
            categoryTitle.textContent = this.textContent.trim();

            // Slider'ları göster/gizle
            projectSliders.forEach(slider => {
                if (slider.dataset.category === category) {
                    slider.classList.remove('hidden');
                    swipers[category].update();
                } else {
                    slider.classList.add('hidden');
                }
            });
        });
    });
});

// News Slider ve Cards Interaction kodları home.blade.php dosyasında yapılıyor - çakışmayı önlemek için kaldırıldı

// Proje kategorileri ve başlıkları
const projectCategories = document.querySelectorAll('.project-category');
const projectSliders = document.querySelectorAll('.project-slider');
const projectTitle = document.getElementById('projectTitle');

// Her kategori için ayrı Swiper örneği oluştur
const swipers = {};

// Her slider için Swiper örneği oluştur
projectSliders.forEach(slider => {
    const category = slider.dataset.category;
    swipers[category] = new Swiper(slider.querySelector('.projectSwiper'), {
        slidesPerView: 2,
        spaceBetween: 30,
        loop: true,

        pagination: {
            el: slider.querySelector('.swiper-pagination'),
            clickable: true,
        },
        navigation: {
            nextEl: slider.querySelector('.swiper-button-next'),
            prevEl: slider.querySelector('.swiper-button-prev'),
        },
        breakpoints: {
            640: {
                slidesPerView: 1,
                spaceBetween: 20,
            },
            768: {
                slidesPerView: 2,
                spaceBetween: 30,
            },
        },
    });
});

// Kategori başlıkları
const categoryTitles = {
    'gerceklesen': 'Gerçekleşen Projeler',
    'planlanan': 'Planlanan Projeler',
    'devam-eden': 'Devam Eden Projeler',
    'uluslararasi': 'Uluslararası Projeler',
    'sosyal': 'Sosyal Projeler'
};

// Eski projeler sistemi - Yeni sistem tarafından devre dışı bırakıldı
// Bu kodlar artık çalışmayacak, yeni izole sistem kullanılıyor

/*
// Kategori değiştirme işlevi - DEVRE DIŞI
function changeCategory(category) {
    console.log('Bu fonksiyon yeni projeler sistemi tarafından devre dışı bırakıldı');
    return false;
}

// Kategori tıklama olaylarını ekle - DEVRE DIŞI
if (typeof projectCategories !== 'undefined') {
    projectCategories.forEach(category => {
        // Bu event listener'lar artık çalışmayacak
    });
}

// Sayfa yüklendiğinde varsayılan kategoriyi göster - DEVRE DIŞI
document.addEventListener('DOMContentLoaded', () => {
    // Bu kod artık çalışmayacak
});
*/

// Mevcut script içeriğine eklenecek
const timelineSwiper = new Swiper('.timelineSwiper', {
    slidesPerView: 3,
    spaceBetween: 30,
    navigation: {
        nextEl: '.timeline-next',
        prevEl: '.timeline-prev',
    },
    breakpoints: {
        320: {
            slidesPerView: 1,
            spaceBetween: 20
        },
        768: {
            slidesPerView: 2,
            spaceBetween: 30
        },
        1024: {
            slidesPerView: 3,
            spaceBetween: 30
        }
    }
});
const servicesSwiper = new Swiper('.servicesSwiper', {
    slidesPerView: 3,
    spaceBetween: 30,
    navigation: {
        nextEl: '.service-next',
        prevEl: '.service-prev',
    },
    breakpoints: {
        320: {
            slidesPerView: 1,
            spaceBetween: 20
        },
        768: {
            slidesPerView: 2,
            spaceBetween: 30
        },
        1024: {
            slidesPerView: 3,
            spaceBetween: 30
        }
    }
});
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

// Mobil dropdown menüler
const mobileDropdowns = document.querySelectorAll('.mobile-dropdown');

mobileDropdowns.forEach(dropdown => {
    const button = dropdown.querySelector('button');
    const content = dropdown.querySelector('div');
    const icon = button.querySelector('.material-icons');

    button.addEventListener('click', () => {
        content.classList.toggle('hidden');
        icon.style.transform = content.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
    });
});

// Quick Mobile Menu Dropdowns
const quickMobileDropdowns = document.querySelectorAll('.quick-mobile-dropdown');

quickMobileDropdowns.forEach(dropdown => {
    const button = dropdown.querySelector('button');
    const content = dropdown.querySelector('div');
    const icon = button.querySelector('.material-icons:last-child');

    button.addEventListener('click', () => {
        content.classList.toggle('hidden');
        icon.style.transform = content.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const quickMobileDropdowns = document.querySelectorAll('.quick-mobile-dropdown');

    quickMobileDropdowns.forEach(dropdown => {
        const button = dropdown.querySelector('button');
        const content = dropdown.querySelector('div');
        const icon = button.querySelector('.material-icons:last-child');

        button.addEventListener('click', () => {
            // Diğer tüm dropdown'ları kapat
            quickMobileDropdowns.forEach(otherDropdown => {
                if (otherDropdown !== dropdown) {
                    const otherContent = otherDropdown.querySelector('div');
                    const otherIcon = otherDropdown.querySelector('.material-icons:last-child');
                    otherContent.classList.add('hidden');
                    if (otherIcon) {
                        otherIcon.style.transform = 'rotate(0deg)';
                    }
                }
            });

            // Tıklanan dropdown'ı aç/kapat
            content.classList.toggle('hidden');
            if (icon) {
                icon.style.transform = content.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
            }
        });
    });
});

// Quick Mobile Menu için yeni JavaScript kodu
document.addEventListener('DOMContentLoaded', function () {
    const mobileQuickMenus = document.querySelectorAll('.mobile-quick-menu');

    mobileQuickMenus.forEach(menu => {
        const button = menu.querySelector('button');
        const content = menu.querySelector('div');
        const icon = button.querySelector('.material-icons:last-child');

        button.addEventListener('click', () => {
            // Diğer tüm menüleri kapat
            mobileQuickMenus.forEach(otherMenu => {
                if (otherMenu !== menu) {
                    const otherContent = otherMenu.querySelector('div');
                    const otherIcon = otherMenu.querySelector('.material-icons:last-child');
                    otherContent.classList.add('hidden');
                    otherIcon.style.transform = 'rotate(0deg)';
                }
            });

            // Tıklanan menüyü aç/kapat
            content.classList.toggle('hidden');
            icon.style.transform = content.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
        });
    });
});

var projectSwipers = {};

function initProjectSwiper(category) {
    if (projectSwipers[category]) {
        projectSwipers[category].destroy();
    }

    projectSwipers[category] = new Swiper(`.project-slider[data-category="${category}"] .projectSwiper`, {
        slidesPerView: 1,
        spaceBetween: 16,
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        breakpoints: {
            640: {
                slidesPerView: 2,
                spaceBetween: 16,
            },
            1024: {
                slidesPerView: 3,
                spaceBetween: 24,
            },
        },
    });
}

document.addEventListener('DOMContentLoaded', function () {
    const categories = document.querySelectorAll('.project-category');
    const projectTitle = document.getElementById('projectTitle');
    const projectSliders = document.querySelectorAll('.project-slider');

    // İlk slider'ı başlat
    initProjectSwiper('gerceklesen');

    categories.forEach(category => {
        category.addEventListener('click', function () {
            const selectedCategory = this.dataset.category;

            // Aktif kategoriyi güncelle
            categories.forEach(c => c.classList.remove('active'));
            this.classList.add('active');

            // Başlığı güncelle
            projectTitle.textContent = this.querySelector('h3, p').textContent;

            // Slider'ları güncelle
            projectSliders.forEach(slider => {
                if (slider.dataset.category === selectedCategory) {
                    slider.classList.remove('hidden');
                    initProjectSwiper(selectedCategory);
                } else {
                    slider.classList.add('hidden');
                }
            });
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileMenuContent = document.getElementById('mobileMenuContent');
    const mobileMenuIcon = document.getElementById('mobileMenuIcon');

    mobileMenuBtn.addEventListener('click', function () {
        mobileMenuContent.classList.toggle('hidden');
        mobileMenuIcon.style.transform = mobileMenuContent.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const mobileMenuBtns = document.querySelectorAll('.mobile-menu-btn');

    mobileMenuBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            const content = this.nextElementSibling;
            const icon = this.querySelector('.material-icons:last-child');

            // Diğer tüm menüleri kapat
            document.querySelectorAll('.mobile-menu-content').forEach(menu => {
                if (menu !== content && !menu.classList.contains('hidden')) {
                    menu.classList.add('hidden');
                    const btnIcon = menu.previousElementSibling.querySelector('.material-icons:last-child');
                    btnIcon.style.transform = 'rotate(0deg)';
                }
            });

            // Seçili menüyü aç/kapat
            content.classList.toggle('hidden');
            icon.style.transform = content.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const searchButton = document.getElementById('searchButton');
    const searchModal = document.getElementById('searchModal');
    const closeSearch = document.getElementById('closeSearch');

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

// Yatay kaydırma için ok tuşlarına tepki
const tabContainer = document.querySelector('.hide-scrollbar');
if (tabContainer) {
    tabContainer.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowRight') {
            tabContainer.scrollLeft += 100;
        } else if (e.key === 'ArrowLeft') {
            tabContainer.scrollLeft -= 100;
        }
    });
    
    // Dokunmatik kaydırma sonrası tıklama için düzeltme
    tabContainer.addEventListener('scroll', () => {
        const scrollLeft = tabContainer.scrollLeft;
        localStorage.setItem('tabScrollPosition', scrollLeft);
    });
    
    // Sayfa yüklendiğinde son kaydırma pozisyonunu geri yükle
    const savedScrollPosition = localStorage.getItem('tabScrollPosition');
    if (savedScrollPosition) {
        tabContainer.scrollLeft = parseInt(savedScrollPosition);
    }
}

// Hizmetler Mega Menü
document.addEventListener('DOMContentLoaded', function() {
    // Tüm hizmet kartlarını seçelim
    const serviceCards = document.querySelectorAll('.hover-mega-menu a[data-category]');
    const categoryContainer = document.querySelector('.hover-mega-menu .mega-menu-content');
    
    if (serviceCards.length > 0 && categoryContainer) {
        // Her bir karta tıklama olayı ekleyelim
        serviceCards.forEach(card => {
            card.addEventListener('click', function(e) {
                e.preventDefault(); // Varsayılan link davranışını engelle
                
                // Tıklanan kategori adını al
                const categoryId = this.getAttribute('data-category');
                
                // Tüm kategorileri gizle
                document.querySelectorAll('.tab-pane').forEach(tab => {
                    tab.classList.add('hidden');
                });
                
                // İlgili kategoriyi göster
                const activeCategory = document.getElementById(categoryId);
                if (activeCategory) {
                    // Kategori kartları konteynerini gizle
                    const cardsContainer = document.querySelector('.hover-mega-menu .grid');
                    cardsContainer.classList.add('hidden');
                    
                    // İlgili grupları gizle
                    const relatedGroups = document.querySelector('.hover-mega-menu .mb-4');
                    if (relatedGroups) {
                        relatedGroups.classList.add('hidden');
                    }
                    
                    // İçerik konteynerini göster
                    const contentContainer = document.querySelector('.hover-mega-menu .hidden.mt-4');
                    contentContainer.classList.remove('hidden');
                    
                    // Seçilen kategoriyi göster
                    activeCategory.classList.remove('hidden');
                    
                    // Eğer yoksa, geri dönüş butonu ekle
                    if (!document.querySelector('.back-to-categories')) {
                        const backButton = document.createElement('div');
                        backButton.className = 'back-to-categories flex items-center text-blue-600 mb-4 font-medium';
                        backButton.innerHTML = '<span class="material-icons mr-1">arrow_back</span> Tüm Kategorilere Dön';
                        
                        // Geri butonu tıklama
                        backButton.addEventListener('click', function() {
                            // İçerik konteynerini gizle
                            contentContainer.classList.add('hidden');
                            
                            // Tüm kategorileri gizle
                            document.querySelectorAll('.tab-pane').forEach(tab => {
                                tab.classList.add('hidden');
                            });
                            
                            // Kategori kartlarını göster
                            cardsContainer.classList.remove('hidden');
                            
                            // İlgili grupları göster
                            if (relatedGroups) {
                                relatedGroups.classList.remove('hidden');
                            }
                            
                            // Geri butonunu kaldır
                            this.remove();
                        });
                        
                        // Geri butonunu içerik konteynerinin başına ekle
                        contentContainer.insertBefore(backButton, contentContainer.firstChild);
                    }
                }
            });
        });
    }
});

// Hizmetler Menüsü Kart Fonksiyonalitesi
document.addEventListener('DOMContentLoaded', function() {
    // Tüm hizmet kartlarını seç
    const serviceCards = document.querySelectorAll('[data-category]');
    
    // Her bir karta tıklama olayı ekle
    serviceCards.forEach(card => {
        card.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Tıklanan kartın kategorisini al
            const category = this.getAttribute('data-category');
            
            // Tüm kategori içeriklerinin bulunduğu ana container'ı bul
            const cardContainer = this.closest('.grid');
            const contentContainer = cardContainer.nextElementSibling;
            
            // Kart container'ı gizle
            cardContainer.classList.add('hidden');
            
            // "İlgili Gruplar" bölümünü gizle
            const relatedGroupsSection = cardContainer.previousElementSibling;
            if (relatedGroupsSection && relatedGroupsSection.querySelector('h3') && relatedGroupsSection.querySelector('h3').textContent.includes('İlgili Gruplar')) {
                relatedGroupsSection.classList.add('hidden');
            }
            
            // İçerik container'ı göster
            contentContainer.classList.remove('hidden');
            
            // Seçilen kategori içeriğini göster
            const targetContent = document.getElementById(category);
            if (targetContent) {
                // Önce tüm içerikleri gizle
                const allContents = contentContainer.querySelectorAll('.tab-pane');
                allContents.forEach(content => {
                    content.classList.add('hidden');
                });
                
                // Sonra seçilen içeriği göster
                targetContent.classList.remove('hidden');
                
                // Geri butonu oluştur
                const backButton = document.createElement('button');
                backButton.innerHTML = '<span class="material-icons mr-2">arrow_back</span> Geri';
                backButton.className = 'flex items-center mb-4 px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg text-gray-700 text-sm transition';
                
                // Eğer zaten bir geri butonu varsa, onu kaldır
                const existingBackButton = targetContent.querySelector('.back-button');
                if (existingBackButton) {
                    existingBackButton.remove();
                }
                
                // Geri butonuna sınıf ekle
                backButton.classList.add('back-button');
                
                // Geri butonuna tıklama olayı ekle
                backButton.addEventListener('click', function() {
                    // İçerik container'ı gizle
                    contentContainer.classList.add('hidden');
                    
                    // Kart container'ı göster
                    cardContainer.classList.remove('hidden');
                    
                    // "İlgili Gruplar" bölümünü göster
                    if (relatedGroupsSection) {
                        relatedGroupsSection.classList.remove('hidden');
                    }
                });
                
                // Geri butonunu içeriğin başına ekle
                targetContent.insertBefore(backButton, targetContent.firstChild);
            }
        });
    });
});


