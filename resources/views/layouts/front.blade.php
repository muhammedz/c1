@php
    $preloaderEnabled = \App\Models\Setting::get('preloader_enabled', '1');
@endphp

<!DOCTYPE html>
<html lang="tr" class="overflow-x-hidden">

<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-80L32HWP74"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-80L32HWP74');
    </script>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if(View::hasSection('title'))
        <title>@yield('title') - {{ \App\Helpers\SeoHelper::getHomepageTitle() }}</title>
    @else
        <title>{{ \App\Helpers\SeoHelper::getHomepageTitle() }}</title>
    @endif
    
    @if(View::hasSection('meta_description'))
        <meta name="description" content="@yield('meta_description')">
    @else
        <meta name="description" content="{{ \App\Helpers\SeoHelper::getHomepageDescription() }}">
    @endif
    
    <!-- Favicon -->
    @php
        $siteFavicon = \App\Models\Setting::where('key', 'site_favicon')->first();
    @endphp
    @if($siteFavicon && $siteFavicon->value)
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('uploads/' . $siteFavicon->value) }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('uploads/' . $siteFavicon->value) }}">
        <link rel="shortcut icon" href="{{ asset('uploads/' . $siteFavicon->value) }}">
    @else
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    @endif
    
    <!-- Tailwind CSS -->
    <script>
        // Tailwind CDN uyarısını bastır
        const originalWarn = console.warn;
        console.warn = function(...args) {
            const message = args.join(' ');
            if (message.includes('cdn.tailwindcss.com should not be used in production')) {
                return; // Bu uyarıyı gösterme
            }
            originalWarn.apply(console, args);
        };
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Ana CSS -->
    <link href="{{ asset('assets/css/styles.css') }}" rel="stylesheet">
    
    @if($preloaderEnabled == '1')
        <!-- Preloader CSS -->
        <link href="{{ asset('css/preloader.css') }}" rel="stylesheet">
    @endif
    
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Font tanımlamaları -->
    <style>
       html, body {
           overflow-x: hidden;
           width: 100%;
           max-width: 100%;
       }
       
       /* News Section Slider Styles */
       .newsMainSwiper .swiper-wrapper {
           position: relative;
           width: 100%;
           height: 100%;
       }
       
       .newsMainSwiper .swiper-slide {
           position: absolute;
           top: 0;
           left: 0;
           width: 100%;
           height: 100%;
           opacity: 0;
           transition: opacity 0.5s ease-in-out;
       }
       
       .newsMainSwiper .swiper-slide:first-child {
           opacity: 1;
       }
       
       .news-card.active a,
       .news-card.active div {
           background-color: #004d2e !important;
       }
       
       .news-card.active h3 {
           color: white !important;
       }
       
       .news-prev-btn:disabled,
       .news-next-btn:disabled {
           opacity: 0.5;
           cursor: not-allowed;
       }
       
       /* Slider Styles */
       .animate-slide-up {
           animation: slide-up 1s ease forwards;
       }
       
       @keyframes slide-up {
           from { opacity: 0; transform: translateY(2rem); }
           to { opacity: 1; transform: translateY(0); }
       }
       
       /* Slider tam ekran sığması için ek stiller */
       .hero-slider-section {
           height: 100vh;
           max-height: 100vh;
           min-height: 500px;
           overflow: hidden;
           position: relative;
           margin-top: -100px; /* Header yüksekliği kadar negatif margin */
       }
       
       .hero-slider-section .swiper {
           height: 100% !important;
           max-height: 100% !important;
       }
       
       @media (max-height: 800px) {
           .hero-slider-section {
               height: 100vh;
               min-height: 500px;
           }
       }
       
       /* Mobil görsel için özel stiller */
       @media (max-width: 767px) {
           .hero-slider-section {
               height: auto !important;
               min-height: auto !important;
               margin-top: 0 !important;
               padding-top: 0 !important;
           }
           
           /* Mobil resim için kare format */
           .hero-slider-section .aspect-square {
               aspect-ratio: 1 / 1;
               width: 100vw !important;
               max-width: none !important;
           }
           
           /* Mobil layout düzenlemesi - padding'leri kaldır */
           .hero-slider-section > div:first-child {
               margin-top: 0 !important;
               padding: 0 !important;
           }
           
           /* Mobil container'ı tam genişlik yap */
           .hero-slider-section > div:first-child > div {
               padding: 0 !important;
               margin: 0 !important;
           }
       }
       
       /* Quick menu geçişi için ek stil */
       #hero-slider-section::after {
           content: '';
           position: absolute;
           bottom: 0;
           left: 0;
           right: 0;
           height: 20px;
           background: linear-gradient(to bottom, transparent, rgba(0,0,0,0.1));
           z-index: 5;
       }
       
       /* Tek slide durumunda navigation ve pagination gizle */
       .heroSwiper.single-slide .swiper-pagination,
       .heroSwiper.single-slide .swiper-button-next,
       .heroSwiper.single-slide .swiper-button-prev {
           display: none !important;
       }
       
       /* Featured Services Styles */
       .featured-service-card {
           display: flex;
           flex-direction: column;
           align-items: center;
           justify-content: center;
           padding: 1rem;
           background-color: white;
           border-radius: 16px;
           box-shadow: 0 2px 20px rgba(0,0,0,0.05);
           transition: all 0.3s ease;
           text-decoration: none !important;
           color: inherit !important;
           height: 140px;
       }
       
       .featured-service-card:hover {
           box-shadow: 0 4px 25px rgba(0,0,0,0.1);
           transform: translateY(-3px);
           text-decoration: none !important;
           color: inherit !important;
       }
       
       .featured-service-icon {
           display: flex;
           align-items: center;
           justify-content: center;
           width: 50px;
           height: 50px;
           margin-bottom: 0.5rem;
       }
       
       .featured-service-icon i {
           font-size: 24px !important;
           color: #004d2e;
       }
       
       .featured-service-icon svg {
           width: 100%;
           height: 100%;
           max-width: 40px;
           max-height: 40px;
           color: #004d2e;
       }
       
       .featured-service-title {
           font-size: 0.75rem;
           font-weight: 600;
           color: #333;
           text-align: center;
           margin-top: 0.25rem;
           font-family: 'Poppins', sans-serif;
           line-height: 1.2;
       }
       
       /* Mobil: 3x3 Statik Grid */
       @media (max-width: 767px) {
           .mobile-services-grid {
               display: grid;
               grid-template-columns: repeat(3, 1fr);
               gap: 8px;
           }
           
           .mobile-services-grid .service-item:nth-child(n+10) {
               display: none;
           }
           
           .desktop-services-slider {
               display: none;
           }
           
           .featured-service-card {
               height: 120px;
               padding: 0.75rem;
               border-radius: 12px;
           }
           
           .featured-service-icon {
               width: 40px;
               height: 40px;
           }
           
           .featured-service-icon i {
               font-size: 20px !important;
           }
           
           .featured-service-title {
               font-size: 0.7rem;
           }
       }
       
       /* Desktop: Kaydırılabilir Grid */
       @media (min-width: 768px) {
           .mobile-services-grid {
               display: none;
           }
           
           .desktop-services-slider {
               display: block;
               overflow: hidden;
               position: relative;
           }
           
           .services-track {
               display: flex;
               transition: transform 0.5s ease;
               gap: 20px;
           }
           
           .service-slide {
               flex: 0 0 auto;
               width: calc(20% - 16px); /* 5 items per view */
           }
           
           .featured-service-card {
               height: 160px;
               padding: 1.25rem;
               border-radius: 20px;
           }
           
           .featured-service-icon {
               width: 60px;
               height: 60px;
           }
           
           .featured-service-icon i {
               font-size: 28px !important;
           }
           
           .featured-service-title {
               font-size: 0.9rem;
           }
       }
       
       @media (min-width: 1024px) {
           .featured-service-card {
               height: 180px;
               padding: 1.5rem;
               border-radius: 24px;
           }
           
           .featured-service-icon {
               width: 70px;
               height: 70px;
           }
           
           .featured-service-icon i {
               font-size: 32px !important;
           }
           
           .featured-service-title {
               font-size: 1rem;
           }
       }
       
       /* Tablet için 4'lü */
       @media (min-width: 768px) and (max-width: 1023px) {
           .service-slide {
               width: calc(25% - 15px); /* 4 items per view */
           }
       }
       
       /* Mobil tablet için 3'lü */
       @media (min-width: 640px) and (max-width: 767px) {
           .service-slide {
               width: calc(33.333% - 14px); /* 3 items per view */
           }
       }
    </style>
    
    @yield('css')
</head>

<body class="bg-slate-100 overflow-x-hidden">
    @if($preloaderEnabled == '1')
        <!-- Preloader -->
        <div id="preloader">
        </div>
    @endif

    <!-- Header Alanı -->
    @include('partials.header')
    
    <!-- Ana İçerik -->
    <main class="overflow-x-hidden">
        @yield('content')
    </main>
    
    <!-- Footer Alanı -->
    @include('partials.footer')
    
    <!-- Arama Modal -->
    @include('partials.search-modal')
    
    <!-- Duyurular Bileşeni -->
    @include('components.announcement')
    
    <!-- JavaScript Dosyaları -->
    @yield('before_scripts')
    
    @if($preloaderEnabled == '1')
        <!-- Preloader JS -->
        <script src="{{ asset('js/preloader.js') }}"></script>
    @endif
    
    <!-- Ana JS -->
    <script src="{{ asset('assets/js/script.js') }}"></script>
    
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    
    @yield('after_scripts')
</body>
</html> 