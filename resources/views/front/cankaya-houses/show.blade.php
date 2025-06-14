@extends('layouts.front')

@section('title', $cankayaHouse->name)

@section('css')
<style>
    :root {
        --header-height: 96px;
        --primary-color: #00352b;
        --primary-light: rgba(0, 53, 43, 0.1);
        --primary-dark: #002a22;
        --secondary-color: #20846c;
        --accent-color: #e6a23c;
    }
    
    /* Hero Bölümü */
    .house-header {
        position: relative;
        background: linear-gradient(to right, #00352b, #20846c);
        padding: 3rem 0;
        overflow: hidden;
    }

    .pattern-overlay {
        position: absolute;
        inset: 0;
        opacity: 0.08;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60' viewBox='0 0 60 60'%3E%3Cg fill='none' stroke='white' stroke-width='1'%3E%3Cpath d='M30 0v60M0 30h60'/%3E%3Ccircle cx='30' cy='30' r='20'/%3E%3C/g%3E%3C/svg%3E");
        pointer-events: none;
        z-index: 1;
    }

    .house-container {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 1rem;
        position: relative;
        z-index: 5;
    }

    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
        font-size: 0.75rem;
        position: relative;
        z-index: 20;
    }

    .breadcrumb-item {
        color: rgba(255, 255, 255, 0.8);
    }
    
    .breadcrumb-separator {
        color: rgba(255, 255, 255, 0.6);
    }
    
    .breadcrumb-link {
        color: rgba(255, 255, 255, 0.9);
        transition: all 0.2s ease;
        text-decoration: none;
        padding: 0.2rem 0.4rem;
        border-radius: 0.25rem;
        position: relative;
        z-index: 30;
        cursor: pointer;
        background-color: rgba(255, 255, 255, 0.05);
    }

    .breadcrumb-link:hover {
        color: white;
        background-color: rgba(255, 255, 255, 0.15);
        text-decoration: underline;
    }

    .house-title {
        font-size: 2.25rem;
        font-weight: 700;
        color: white;
        margin: 0 0 1rem 0;
        line-height: 1.2;
        position: relative;
        z-index: 10;
    }

    .house-info {
        display: flex;
        gap: 2rem;
        margin-top: 1rem;
    }

    .house-info-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.875rem;
    }

    /* Layout Grid */
    .page-layout-container {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 1rem;
    }
    
    .page-grid-layout {
        display: grid;
        grid-template-columns: 300px 1fr;
        gap: 2rem;
        margin-top: -20px;
        align-items: flex-start;
    }
    
    .page-content-area {
        grid-column: 2;
        padding-top: 0;
    }
    
    .page-content-section {
        background-color: white;
        border-radius: 12px;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
        padding: 30px;
        margin-bottom: 30px;
        border-top: 4px solid var(--primary-color);
    }
    
    .page-content-section h2 {
        font-size: 24px;
        font-weight: 700;
        color: var(--primary-color);
        margin-top: 0;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #e5e7eb;
    }

    /* Sidebar */
    #sidebar {
        grid-column: 1;
        align-self: flex-start;
        position: relative;
        width: 300px;
        min-width: 300px;
        max-width: 300px;
        margin-top: 0;
        flex-shrink: 0;
    }

    #sidebar .sticky-container {
        width: 300px;
        min-width: 300px;
        max-width: 300px;
        position: relative;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        transition: all 0.2s ease-out;
        max-height: calc(100vh - 120px);
        overflow-y: auto;
        padding: 30px;
        box-sizing: border-box;
    }

    #sidebar.scrolledDown .sticky-container {
        position: fixed;
        top: 96px;
        width: 300px !important;
        min-width: 300px !important;
        max-width: 300px !important;
        z-index: 40;
        transition: all 0.2s ease-out;
    }

    #sidebar.bottom-fixed .sticky-container {
        position: fixed;
        bottom: 20px;
        top: auto !important;
        width: 300px !important;
        min-width: 300px !important;
        max-width: 300px !important;
        z-index: 40;
        transition: all 0.2s ease-out;
    }

    #sidebar .sticky-container::-webkit-scrollbar {
        width: 4px;
    }

    #sidebar .sticky-container::-webkit-scrollbar-track {
        background: #f5f5f5;
        border-radius: 10px;
    }

    #sidebar .sticky-container::-webkit-scrollbar-thumb {
        background: #00352b;
        border-radius: 10px;
    }

    /* Galeri */
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .gallery-item {
        aspect-ratio: 16/10;
        border-radius: 0.75rem;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .gallery-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    }

    .gallery-item:hover img {
        transform: scale(1.05);
    }

    /* Modal Stilleri */
    .image-modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.9);
        animation: fadeIn 0.3s ease;
    }

    .image-modal-content {
        position: relative;
        margin: auto;
        padding: 20px;
        width: 90%;
        max-width: 1200px;
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .image-modal img {
        max-width: 100%;
        max-height: 90vh;
        object-fit: contain;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
    }

    .image-modal-close {
        position: absolute;
        top: 20px;
        right: 35px;
        color: white;
        font-size: 40px;
        font-weight: bold;
        cursor: pointer;
        z-index: 10000;
        transition: color 0.3s ease;
    }

    .image-modal-close:hover {
        color: #ccc;
    }

    .image-modal-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: none;
        font-size: 24px;
        padding: 15px 20px;
        cursor: pointer;
        border-radius: 50%;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
    }

    .image-modal-nav:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-50%) scale(1.1);
    }

    .image-modal-prev {
        left: 20px;
    }

    .image-modal-next {
        right: 20px;
    }

    .image-modal-counter {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        color: white;
        background: rgba(0, 0, 0, 0.5);
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 14px;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    /* Kurs Kartları */
    .courses-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 1.5rem;
    }

    .course-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        padding: 1.5rem;
        transition: all 0.3s ease;
    }

    .course-card:hover {
        border-color: #00352b;
        box-shadow: 0 8px 25px rgba(0, 53, 43, 0.1);
        transform: translateY(-2px);
    }

    .course-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #00352b;
        margin-bottom: 0.75rem;
    }

    .course-dates {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #6b7280;
        font-size: 0.875rem;
        margin-bottom: 0.75rem;
    }

    .course-instructor {
        color: #374151;
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }

    .course-price {
        font-weight: 600;
        color: #00352b;
        font-size: 1rem;
    }

    .status-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .status-upcoming {
        background-color: #dbeafe;
        color: #1e40af;
    }

    .status-ongoing {
        background-color: #dcfce7;
        color: #166534;
    }

    .status-completed {
        background-color: #f3f4f6;
        color: #6b7280;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-grid-layout {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .page-content-area {
            grid-column: 1;
        }
        
        #sidebar {
            display: none;
        }
        
        #sidebar.active {
            display: block;
            position: fixed;
            top: 15px;
            left: 15px;
            right: 15px;
            width: auto;
            z-index: 100;
        }
        
        #sidebar.scrolledDown .sticky-container {
            position: relative;
            top: 0;
            left: 0 !important;
            width: 100% !important;
            transform: none;
        }
    }
</style>
@endsection

@section('after_scripts')
<script>
// Galeri resimleri
const galleryImages = @json($cankayaHouse->images ?? []);
let currentImageIndex = 0;

// Modal açma
function openImageModal(index) {
    currentImageIndex = index;
    const modal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    const imageCounter = document.getElementById('imageCounter');
    
    modalImage.src = galleryImages[currentImageIndex];
    imageCounter.textContent = currentImageIndex + 1;
    modal.style.display = 'block';
    
    // Body scroll'unu engelle
    document.body.style.overflow = 'hidden';
}

// Modal kapatma
function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.style.display = 'none';
    
    // Body scroll'unu geri aç
    document.body.style.overflow = 'auto';
}

// Resim değiştirme
function changeImage(direction) {
    currentImageIndex += direction;
    
    // Sınırları kontrol et
    if (currentImageIndex < 0) {
        currentImageIndex = galleryImages.length - 1;
    } else if (currentImageIndex >= galleryImages.length) {
        currentImageIndex = 0;
    }
    
    const modalImage = document.getElementById('modalImage');
    const imageCounter = document.getElementById('imageCounter');
    
    modalImage.src = galleryImages[currentImageIndex];
    imageCounter.textContent = currentImageIndex + 1;
}

// Klavye kontrolleri
document.addEventListener('keydown', function(e) {
    const modal = document.getElementById('imageModal');
    if (modal.style.display === 'block') {
        if (e.key === 'Escape') {
            closeImageModal();
        } else if (e.key === 'ArrowLeft') {
            changeImage(-1);
        } else if (e.key === 'ArrowRight') {
            changeImage(1);
        }
    }
});

// Modal dışına tıklama ile kapatma
document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});

// Sidebar sticky functionality
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const stickyContainer = sidebar.querySelector('.sticky-container');
    const headerHeight = 96;
    const footerMargin = 20; // Footer'dan olan minimum mesafe
    
    // Sidebar'ın orijinal boyutlarını ve pozisyonunu sakla
    let originalDimensions = null;
    let isSticky = false;
    
    function getOriginalDimensions() {
        // Sayfa yüklendiğinde ve sticky olmadığında orijinal boyutları al
        if (!isSticky) {
            const rect = sidebar.getBoundingClientRect();
            const computedStyle = window.getComputedStyle(sidebar);
            
            originalDimensions = {
                width: 300, // CSS'te tanımlanan sabit genişlik
                left: rect.left,
                marginLeft: parseFloat(computedStyle.marginLeft) || 0,
                marginRight: parseFloat(computedStyle.marginRight) || 0
            };
        }
        return originalDimensions;
    }
    
    function updateSidebarPosition() {
        // Orijinal boyutları al (sadece ilk kez)
        if (!originalDimensions) {
            getOriginalDimensions();
        }
        
        const rect = sidebar.getBoundingClientRect();
        const stickyTop = rect.top;
        
        // Footer elementini bul
        const footer = document.querySelector('footer');
        const footerRect = footer ? footer.getBoundingClientRect() : null;
        
        // Sidebar'ın yüksekliği
        const sidebarHeight = stickyContainer.offsetHeight;
        
        // Sayfa yüksekliği ve scroll pozisyonu
        const windowHeight = window.innerHeight;
        
        // Footer'a olan mesafe (footer'ın üst kısmına kadar)
        const footerTop = footerRect ? footerRect.top : windowHeight;
        
        // Sidebar'ın sticky olması gereken durumlar
        const shouldBeSticky = stickyTop <= headerHeight;
        
        // Footer'a çok yakın mı kontrol et
        const availableSpace = footerTop - headerHeight - footerMargin;
        const sidebarFitsInAvailableSpace = sidebarHeight <= availableSpace;
        
        if (shouldBeSticky && sidebarFitsInAvailableSpace) {
            // Normal sticky pozisyon - üstten sabitle
            if (!isSticky) {
                isSticky = true;
                sidebar.classList.add('scrolledDown');
                sidebar.classList.remove('bottom-fixed');
                
                // Sabit değerleri kullan
                stickyContainer.style.width = originalDimensions.width + 'px';
                stickyContainer.style.left = originalDimensions.left + 'px';
                stickyContainer.style.position = 'fixed';
                stickyContainer.style.top = headerHeight + 'px';
                stickyContainer.style.bottom = 'auto';
            }
        } else if (shouldBeSticky && !sidebarFitsInAvailableSpace && footerRect) {
            // Footer'a yakın - alttan sabitle
            if (!isSticky || !sidebar.classList.contains('bottom-fixed')) {
                isSticky = true;
                sidebar.classList.add('scrolledDown');
                sidebar.classList.add('bottom-fixed');
                
                // Sabit değerleri kullan
                stickyContainer.style.width = originalDimensions.width + 'px';
                stickyContainer.style.left = originalDimensions.left + 'px';
                stickyContainer.style.position = 'fixed';
                stickyContainer.style.top = 'auto';
                stickyContainer.style.bottom = Math.max(footerMargin, windowHeight - footerTop + footerMargin) + 'px';
            }
        } else {
            // Normal pozisyon - sticky değil
            if (isSticky) {
                isSticky = false;
                sidebar.classList.remove('scrolledDown');
                sidebar.classList.remove('bottom-fixed');
                
                // Tüm stilleri temizle
                stickyContainer.style.width = '';
                stickyContainer.style.left = '';
                stickyContainer.style.position = '';
                stickyContainer.style.top = '';
                stickyContainer.style.bottom = '';
            }
        }
    }
    
    // Throttle scroll events for better performance
    let ticking = false;
    function requestTick() {
        if (!ticking) {
            requestAnimationFrame(updateSidebarPosition);
            ticking = true;
            setTimeout(() => { ticking = false; }, 16); // ~60fps
        }
    }
    
    // Resize olduğunda orijinal boyutları yeniden hesapla
    function handleResize() {
        if (!isSticky) {
            originalDimensions = null;
            getOriginalDimensions();
        }
        updateSidebarPosition();
    }
    
    window.addEventListener('scroll', requestTick);
    window.addEventListener('resize', handleResize);
    
    // İlk yükleme
    setTimeout(() => {
        updateSidebarPosition();
    }, 100);
});
</script>
@endsection

@section('content')
<!-- Hero Bölümü -->
<section class="house-header">
    <div class="pattern-overlay"></div>
    
    <div class="house-container">
        <!-- Breadcrumb -->
        <nav class="breadcrumb">
            <a href="{{ route('front.home') }}" class="breadcrumb-link">
                <i class="fas fa-home"></i>
                Anasayfa
            </a>
            <span class="breadcrumb-separator">/</span>
            <a href="{{ route('cankaya-houses.index') }}" class="breadcrumb-link">
                Çankaya Evleri
            </a>
            <span class="breadcrumb-separator">/</span>
            <span class="breadcrumb-item">{{ $cankayaHouse->name }}</span>
        </nav>
        
        <!-- Başlık -->
        <h1 class="house-title">{{ $cankayaHouse->name }}</h1>
        
        @if($cankayaHouse->description)
            <p class="text-white/80 text-lg max-w-3xl">
                {{ $cankayaHouse->description }}
            </p>
        @endif
    </div>
</section>

<!-- Ana İçerik Grid Düzeni -->
<div class="page-layout-container page-grid-layout py-12">
    
    <!-- Sol Taraf - Çankaya Evleri Menüsü -->
    <div id="sidebar" class="relative">
        <div class="sticky-container">
            <div class="mb-6 border-b border-gray-200 pb-4">
                <h3 class="text-xl font-bold text-[#00352b] mb-2">Çankaya Evleri</h3>
                <div class="space-y-2">
                    <!-- Mevcut ev -->
                    <div class="flex items-center px-3 py-2 rounded-lg bg-[#00352b] text-white transition-all">
                        <div class="flex items-center">
                            <i class="fas fa-home mr-2.5 text-white"></i>
                            <span class="text-sm">{{ $cankayaHouse->name }}</span>
                        </div>
                    </div>
                    
                    @foreach($otherHouses as $house)
                        <a href="{{ route('cankaya-houses.show', $house) }}" 
                           class="flex items-center px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 transition-all">
                            <div class="flex items-center">
                                <i class="fas fa-home mr-2.5 text-gray-500"></i>
                                <span class="text-sm">{{ $house->name }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
            
            <!-- Tüm Çankaya Evleri -->
            <div class="mb-6">
                <a href="{{ route('cankaya-houses.index') }}" class="inline-flex items-center justify-center w-full px-4 py-2.5 bg-[#00352b] text-white rounded-lg hover:bg-[#002a22] transition-all shadow-sm hover:shadow">
                    <i class="fas fa-list mr-2"></i> Tüm Çankaya Evleri
                </a>
            </div>
        </div>
    </div>
    
    <!-- Sağ Taraf - Ana İçerik Alanı -->
    <div class="page-content-area">
        <!-- İletişim Bölümü -->
        @if($cankayaHouse->address || $cankayaHouse->phone || $cankayaHouse->location_link)
        <div class="page-content-section">
            <h2 class="mb-6">İletişim</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @if($cankayaHouse->address)
                <div class="flex items-start p-4 bg-gray-50 rounded-lg">
                    <div class="flex-shrink-0 w-12 h-12 bg-[#00352b] rounded-lg flex items-center justify-center">
                        <i class="fas fa-map-marker-alt text-white"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Adres</h3>
                        <p class="text-gray-600">{{ $cankayaHouse->address }}</p>
                    </div>
                </div>
                @endif
                
                @if($cankayaHouse->phone)
                <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                    <div class="flex-shrink-0 w-12 h-12 bg-[#00352b] rounded-lg flex items-center justify-center">
                        <i class="fas fa-phone text-white"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Telefon</h3>
                        <p class="text-gray-600">{{ $cankayaHouse->phone }}</p>
                    </div>
                </div>
                @endif
                
                @if($cankayaHouse->location_link)
                <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                    <div class="flex-shrink-0 w-12 h-12 bg-[#00352b] rounded-lg flex items-center justify-center">
                        <i class="fas fa-external-link-alt text-white"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Harita</h3>
                        <a href="{{ $cankayaHouse->location_link }}" target="_blank" class="text-[#00352b] hover:text-[#002a22] font-medium">
                            Haritada Görüntüle
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif
        
        <!-- Galeri Bölümü -->
        @if($cankayaHouse->images && count($cankayaHouse->images) > 0)
        <div class="page-content-section">
            <h2 class="mb-6">Galeri</h2>
            <div class="gallery-grid">
                @foreach($cankayaHouse->images as $index => $image)
                <div class="gallery-item" onclick="openImageModal({{ $index }})">
                    <img src="{{ $image }}" alt="{{ $cankayaHouse->name }}">
                </div>
                @endforeach
            </div>
        </div>

        <!-- Resim Modal -->
        <div id="imageModal" class="image-modal">
            <div class="image-modal-content">
                <span class="image-modal-close" onclick="closeImageModal()">&times;</span>
                <button class="image-modal-nav image-modal-prev" onclick="changeImage(-1)">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <img id="modalImage" src="" alt="{{ $cankayaHouse->name }}">
                <button class="image-modal-nav image-modal-next" onclick="changeImage(1)">
                    <i class="fas fa-chevron-right"></i>
                </button>
                <div class="image-modal-counter">
                    <span id="imageCounter">1</span> / {{ count($cankayaHouse->images) }}
                </div>
            </div>
        </div>
        @endif
        
        <!-- Açıklama Bölümü -->
        @if($cankayaHouse->description)
        <div class="page-content-section">
            <h2 class="mb-6">Hakkında</h2>
            <div class="text-lg text-gray-600 mb-4 font-medium border-l-4 border-[#00352b] pl-5 py-4 bg-slate-50 rounded-r-lg">
                {{ $cankayaHouse->description }}
            </div>
        </div>
        @endif
        
        <!-- Kurslar Bölümü -->
        <div class="page-content-section">
            <h2 class="mb-6">Kurslar</h2>
            
            @if($cankayaHouse->courses && $cankayaHouse->courses->where('status', 'active')->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($cankayaHouse->courses->where('status', 'active')->sortBy('order') as $course)
                    <div class="course-card-modern group relative overflow-hidden bg-gradient-to-br from-gray-50 to-gray-100 hover:from-gray-100 hover:to-gray-150 border-gray-200 flex items-center p-3 rounded-lg border-2 shadow-sm hover:shadow-md transition-all duration-300 transform hover:scale-102 hover:-translate-y-0.5 cursor-pointer">
                        <!-- İkon -->
                        <div class="icon-container w-10 h-10 flex items-center justify-center rounded-full mr-3 flex-shrink-0 bg-white/80 group-hover:bg-white transition-all duration-300 group-hover:rotate-3 group-hover:scale-105 shadow-sm">
                            @if($course->icon)
                                <i class="{{ $course->icon }} text-[#007b32] text-lg group-hover:text-[#00352b] transition-colors duration-300"></i>
                            @else
                                <i class="fas fa-graduation-cap text-[#007b32] text-lg group-hover:text-[#00352b] transition-colors duration-300"></i>
                            @endif
                        </div>
                        
                        <!-- Başlık -->
                        <div class="flex flex-col flex-1 min-w-0">
                            <h3 class="font-semibold text-[#00352b] text-sm group-hover:text-[#007b32] transition-colors duration-300 leading-tight">{{ $course->name }}</h3>
                        </div>
                        
                        <!-- Sağ Ok İkonu -->
                        <div class="ml-2 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-1 group-hover:translate-x-0">
                            <span class="material-icons text-[#007b32] text-base">arrow_forward</span>
                        </div>
                        
                        <!-- Hover overlay efekti -->
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/15 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 transform -skew-x-12 translate-x-full group-hover:translate-x-[-100%] transition-transform duration-700"></div>
                        
                        <!-- Sol kenar vurgu çizgisi -->
                        <div class="absolute left-0 top-0 bottom-0 w-0 bg-[#007b32] group-hover:w-1 transition-all duration-300 rounded-l-lg"></div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-graduation-cap fa-3x text-gray-400 mb-4"></i>
                    <h4 class="text-gray-600 text-lg font-medium mb-2">Henüz kurs bulunmamaktadır</h4>
                    <p class="text-gray-500">Bu Çankaya evinde henüz aktif kurs bulunmamaktadır.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 