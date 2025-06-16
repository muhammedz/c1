@extends('layouts.front')

@section('title', $pageTitle)
@section('meta_description', $pageDescription)

@section('css')
<style>
    .place-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        position: relative;
        overflow: hidden;
    }
    
    .place-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.3);
        z-index: 1;
    }
    
    .place-hero-content {
        position: relative;
        z-index: 2;
    }
    
    .place-gallery {
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }
    
    .gallery-main {
        height: 400px;
        position: relative;
        overflow: hidden;
    }
    
    .gallery-main img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .gallery-main:hover img {
        transform: scale(1.05);
    }
    
    .gallery-thumbs {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 8px;
        margin-top: 12px;
    }
    
    .gallery-thumb {
        height: 80px;
        border-radius: 8px;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.2s ease;
        border: 2px solid transparent;
    }
    
    .gallery-thumb:hover,
    .gallery-thumb.active {
        border-color: #3b82f6;
        transform: scale(1.05);
    }
    
    .gallery-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .info-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        margin-bottom: 24px;
    }
    
    .contact-item {
        display: flex;
        align-items: flex-start;
        padding: 16px;
        background: #f8fafc;
        border-radius: 12px;
        margin-bottom: 12px;
        transition: all 0.2s ease;
    }
    
    .contact-item:hover {
        background: #e2e8f0;
        transform: translateX(4px);
    }
    
    .contact-icon {
        width: 40px;
        height: 40px;
        background: #3b82f6;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        margin-right: 16px;
        flex-shrink: 0;
    }
    
    .contact-content {
        flex: 1;
    }
    
    .contact-label {
        font-size: 0.875rem;
        color: #64748b;
        margin-bottom: 4px;
    }
    
    .contact-value {
        font-weight: 600;
        color: #1e293b;
        word-break: break-word;
    }
    
    .action-button {
        display: inline-flex;
        align-items: center;
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
        margin: 4px;
    }
    
    .action-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px -8px rgba(0, 0, 0, 0.3);
    }
    
    .action-primary {
        background: #3b82f6;
        color: white;
    }
    
    .action-primary:hover {
        background: #2563eb;
        color: white;
    }
    
    .action-secondary {
        background: #10b981;
        color: white;
    }
    
    .action-secondary:hover {
        background: #059669;
        color: white;
    }
    
    .action-tertiary {
        background: #f59e0b;
        color: white;
    }
    
    .action-tertiary:hover {
        background: #d97706;
        color: white;
    }
    
    .related-place {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    
    .related-place:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }
    
    .related-image {
        height: 120px;
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        position: relative;
        overflow: hidden;
    }
    
    .related-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .content-section {
        background: white;
        border-radius: 16px;
        padding: 32px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    
    .content-section h2 {
        color: #1e293b;
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 2px solid #e2e8f0;
    }
    
    .content-section p {
        color: #475569;
        line-height: 1.7;
        margin-bottom: 16px;
    }
    
    .content-section ul,
    .content-section ol {
        color: #475569;
        line-height: 1.7;
        margin-bottom: 16px;
        padding-left: 24px;
    }
    
    .content-section li {
        margin-bottom: 8px;
    }
    
    .breadcrumb-item {
        color: rgba(255, 255, 255, 0.8);
    }
    
    .breadcrumb-item:hover {
        color: white;
    }
    
    .breadcrumb-separator {
        margin: 0 8px;
        color: rgba(255, 255, 255, 0.6);
    }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<section class="place-hero py-16 text-white">
    <div class="container max-w-[1235px] mx-auto px-4">
        <div class="place-hero-content">
            <!-- Breadcrumb -->
            <nav class="mb-8">
                <ol class="flex items-center space-x-2 text-sm">
                    @if(isset($breadcrumbs))
                        @foreach($breadcrumbs as $breadcrumb)
                            <li>
                                @if($breadcrumb['url'])
                                    <a href="{{ $breadcrumb['url'] }}" class="breadcrumb-item">{{ $breadcrumb['title'] }}</a>
                                @else
                                    <span class="text-white font-medium">{{ $breadcrumb['title'] }}</span>
                                @endif
                            </li>
                            @if(!$loop->last)
                                <li class="breadcrumb-separator"><i class="fas fa-chevron-right"></i></li>
                            @endif
                        @endforeach
                    @endif
                </ol>
            </nav>
            
            <!-- Yer Bilgileri -->
            <div class="flex flex-col lg:flex-row items-start gap-8">
                <div class="flex-1">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-2xl">
                            @if($category->icon)
                                <i class="{{ $category->icon }}"></i>
                            @else
                                <i class="fas fa-building"></i>
                            @endif
                        </div>
                        <div>
                            <div class="text-sm opacity-80 mb-1">{{ $category->name }}</div>
                            <h1 class="text-3xl md:text-4xl font-bold">{{ $place->title }}</h1>
                        </div>
                    </div>
                    
                    @if($place->excerpt)
                        <p class="text-lg md:text-xl opacity-90 mb-6">{{ $place->excerpt }}</p>
                    @endif
                    
                    <!-- Hızlı Aksiyonlar -->
                    <div class="flex flex-wrap gap-3">
                        @if($place->phone)
                            <a href="tel:{{ $place->phone }}" class="action-button action-secondary">
                                <i class="fas fa-phone mr-2"></i>Ara
                            </a>
                        @endif
                        
                        @if($place->maps_link)
                            <a href="{{ $place->maps_link }}" target="_blank" class="action-button action-primary">
                                <i class="fas fa-map-marker-alt mr-2"></i>Haritada Gör
                            </a>
                        @endif
                        
                        @if($place->website)
                            <a href="{{ $place->website }}" target="_blank" class="action-button action-tertiary">
                                <i class="fas fa-globe mr-2"></i>Web Sitesi
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Ana İçerik -->
<section class="py-16 bg-gray-50">
    <div class="container max-w-[1235px] mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Sol Kolon - Ana İçerik -->
            <div class="lg:col-span-2">
                <!-- Fotoğraf Galerisi -->
                @if($place->images->count() > 0)
                    <div class="place-gallery mb-8">
                        <div class="gallery-main" id="galleryMain">
                            <img src="{{ $place->images->first()->image_url }}" 
                                 alt="{{ $place->title }}" 
                                 id="mainImage">
                        </div>
                        
                        @if($place->images->count() > 1)
                            <div class="gallery-thumbs">
                                @foreach($place->images->take(4) as $image)
                                    <div class="gallery-thumb {{ $loop->first ? 'active' : '' }}" 
                                         onclick="changeMainImage('{{ $image->image_url }}', this)">
                                        <img src="{{ $image->image_url }}" alt="{{ $place->title }}">
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif
                
                <!-- Detaylı İçerik -->
                @if($place->content)
                    <div class="content-section mb-8">
                        <h2><i class="fas fa-info-circle mr-3 text-blue-600"></i>Detaylı Bilgi</h2>
                        <div class="prose max-w-none">
                            {!! $place->content !!}
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Sağ Kolon - İletişim ve Bilgiler -->
            <div class="lg:col-span-1">
                <!-- İletişim Bilgileri -->
                <div class="info-card">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">
                        <i class="fas fa-address-book mr-2 text-blue-600"></i>İletişim Bilgileri
                    </h3>
                    
                    @if($place->address)
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="contact-content">
                                <div class="contact-label">Adres</div>
                                <div class="contact-value">{{ $place->address }}</div>
                            </div>
                        </div>
                    @endif
                    
                    @if($place->phone)
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="contact-content">
                                <div class="contact-label">Telefon</div>
                                <div class="contact-value">
                                    <a href="tel:{{ $place->phone }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $place->phone }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    @if($place->email)
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="contact-content">
                                <div class="contact-label">E-posta</div>
                                <div class="contact-value">
                                    <a href="mailto:{{ $place->email }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $place->email }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    @if($place->website)
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-globe"></i>
                            </div>
                            <div class="contact-content">
                                <div class="contact-label">Web Sitesi</div>
                                <div class="contact-value">
                                    <a href="{{ $place->website }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                        {{ parse_url($place->website, PHP_URL_HOST) }}
                                        <i class="fas fa-external-link-alt ml-1 text-xs"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    @if($place->working_hours)
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="contact-content">
                                <div class="contact-label">Çalışma Saatleri</div>
                                <div class="contact-value">{{ $place->working_hours }}</div>
                            </div>
                        </div>
                    @endif
                </div>
                
                <!-- Paylaşım -->
                <div class="info-card">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">
                        <i class="fas fa-share-alt mr-2 text-blue-600"></i>Paylaş
                    </h3>
                    
                    <div class="flex flex-wrap gap-3">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}" 
                           target="_blank"
                           class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fab fa-facebook-f mr-2"></i>Facebook
                        </a>
                        
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}&text={{ urlencode($place->title) }}" 
                           target="_blank"
                           class="flex items-center px-4 py-2 bg-sky-500 text-white rounded-lg hover:bg-sky-600 transition-colors">
                            <i class="fab fa-twitter mr-2"></i>Twitter
                        </a>
                        
                        <a href="https://wa.me/?text={{ urlencode($place->title . ' - ' . request()->fullUrl()) }}" 
                           target="_blank"
                           class="flex items-center px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                            <i class="fab fa-whatsapp mr-2"></i>WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- İlgili Yerler -->
@if($relatedPlaces->count() > 0)
    <section class="py-16 bg-white">
        <div class="container max-w-[1235px] mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">İlgili Yerler</h2>
                <p class="text-gray-600">{{ $category->name }} kategorisindeki diğer yerler</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($relatedPlaces as $relatedPlace)
                    <div class="related-place">
                        <div class="related-image">
                            @if($relatedPlace->featured_image_url)
                                <img src="{{ $relatedPlace->featured_image_url }}" alt="{{ $relatedPlace->title }}">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-white text-2xl">
                                    <i class="fas fa-building"></i>
                                </div>
                            @endif
                        </div>
                        
                        <div class="p-4">
                            <h3 class="font-bold text-gray-800 mb-2 line-clamp-2">{{ $relatedPlace->title }}</h3>
                            
                            @if($relatedPlace->address)
                                <p class="text-sm text-gray-600 mb-3 line-clamp-1">
                                    <i class="fas fa-map-marker-alt mr-1"></i>{{ $relatedPlace->address }}
                                </p>
                            @endif
                            
                            <a href="{{ route('guide.place', [$category->slug, $relatedPlace->slug]) }}" 
                               class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
                                Detayları Gör
                                <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
@endsection

@section('after_scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Galeri fonksiyonları
    window.changeMainImage = function(imageUrl, thumbElement) {
        const mainImage = document.getElementById('mainImage');
        if (mainImage) {
            mainImage.src = imageUrl;
            
            // Aktif thumb'ı güncelle
            document.querySelectorAll('.gallery-thumb').forEach(thumb => {
                thumb.classList.remove('active');
            });
            thumbElement.classList.add('active');
        }
    };
    
    // Telefon numarası kopyalama
    const phoneLinks = document.querySelectorAll('a[href^="tel:"]');
    phoneLinks.forEach(link => {
        link.addEventListener('contextmenu', function(e) {
            e.preventDefault();
            
            // Telefon numarasını panoya kopyala
            const phoneNumber = this.textContent.trim();
            if (navigator.clipboard) {
                navigator.clipboard.writeText(phoneNumber).then(() => {
                    // Başarılı kopyalama bildirimi
                    showNotification('Telefon numarası kopyalandı: ' + phoneNumber);
                });
            }
        });
    });
    
    // E-posta kopyalama
    const emailLinks = document.querySelectorAll('a[href^="mailto:"]');
    emailLinks.forEach(link => {
        link.addEventListener('contextmenu', function(e) {
            e.preventDefault();
            
            const email = this.textContent.trim();
            if (navigator.clipboard) {
                navigator.clipboard.writeText(email).then(() => {
                    showNotification('E-posta adresi kopyalandı: ' + email);
                });
            }
        });
    });
    
    // Bildirim gösterme fonksiyonu
    function showNotification(message) {
        // Basit bir bildirim sistemi
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-all duration-300';
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        // 3 saniye sonra kaldır
        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transform = 'translateY(-20px)';
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }
    
    // Smooth scroll için anchor linkler
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    anchorLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);
            
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Lazy loading için intersection observer
    const images = document.querySelectorAll('img[data-src]');
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
                observer.unobserve(img);
            }
        });
    });
    
    images.forEach(img => imageObserver.observe(img));
});
</script>
@endsection