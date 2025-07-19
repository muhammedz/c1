@php
    $preloaderEnabled = \App\Models\Setting::get('preloader_enabled', '1');
@endphp

<!DOCTYPE html>
<html lang="tr" class="overflow-x-hidden">

<head>
    <!-- Google tag (gtag.js) - Chrome Uyumlu -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-80L32HWP74"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        
        // Consent Mode v2 - Chrome'un yeni politikalarına uyumlu
        gtag('consent', 'default', {
            'analytics_storage': 'granted',
            'ad_storage': 'denied',
            'ad_user_data': 'denied',
            'ad_personalization': 'denied',
            'functionality_storage': 'granted',
            'personalization_storage': 'granted',
            'security_storage': 'granted'
        });
        
        gtag('js', new Date());
        gtag('config', 'G-80L32HWP74', {
            'anonymize_ip': true,
            'allow_google_signals': false,
            'allow_ad_personalization_signals': false,
            'cookie_domain': 'auto',
            'cookie_expires': 7776000, // 90 gün (daha kısa süre)
            'cookie_flags': 'SameSite=Lax;Secure',
            'cookie_update': false,
            'send_page_view': true,
            'transport_type': 'beacon',
            'custom_map': {},
            'page_title': document.title,
            'page_location': window.location.href
        });
    </script>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- DNS prefetch ve preconnect için performans optimizasyonu -->
    <link rel="dns-prefetch" href="//cdn.jsdelivr.net">
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="dns-prefetch" href="//www.googletagmanager.com">
    <link rel="preconnect" href="https://www.googletagmanager.com" crossorigin>
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
    
    <!-- Font Awesome Icons - Yerel -->
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap4.min.css">
    
    <!-- Frontend DataTables Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/frontend-datatables.css') }}?v={{ time() }}">
    
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
           /* SVG boyutları model tarafından ayarlanır, CSS ile ezilmez */
           max-width: 60px !important;
           max-height: 60px !important;
       }
       
       .featured-service-title {
           font-size: 14px !important;
           font-weight: 600 !important;
           color: #333 !important;
           text-align: center !important;
           display: grid !important;
           place-items: center !important;
           place-content: center !important;
           word-break: break-word !important;
           hyphens: auto !important;
           white-space: normal !important;
           line-height: 1.1 !important;
           overflow-wrap: anywhere !important;
           max-height: 40px !important;
           height: 40px !important;
           padding: 2px !important;
           width: 100% !important;
           grid-template-rows: 1fr !important;
           overflow: hidden !important;
       }
       
       /* Events Featured Card Styles */
       .event-featured-card {
           display: flex;
           flex-direction: column;
           background-color: white;
           border-radius: 24px;
           border: 1px solid #eee;
           transition: all 0.3s ease;
           overflow: hidden;
           height: 100%;
           cursor: pointer;
           text-decoration: none;
           color: inherit;
       }
       
       .event-featured-card:hover {
           transform: translateY(-5px);
           text-decoration: none;
           color: inherit;
       }
       
       .event-featured-image {
           width: 100%;
           aspect-ratio: 1/1;
           overflow: hidden;
           border-top-left-radius: 24px;
           border-top-right-radius: 24px;
           position: relative;
       }
       
       .event-featured-content {
           padding: 1.5rem;
           display: flex;
           flex-direction: column;
           flex: 1;
       }
       
       .event-featured-title {
           font-size: 1rem;
           font-weight: 600;
           color: #333;
           margin-bottom: 0.75rem;
           line-height: 1.4;
       }
       
       @media (max-width: 640px) {
           .event-featured-content {
               padding: 1rem;
           }
           
           .event-featured-title {
               font-size: 0.875rem;
               margin-bottom: 0.5rem;
           }
           
           .event-featured-meta {
               font-size: 0.75rem;
           }
           
           .event-featured-category {
               font-size: 0.625rem;
               padding: 0.25rem 0.5rem;
               top: 0.5rem;
               right: 0.5rem;
           }
       }
       
       .event-featured-meta {
           display: flex;
           align-items: center;
           gap: 0.5rem;
           color: #666;
           font-size: 0.875rem;
           margin-bottom: 0.5rem;
       }
       
       .event-featured-meta svg {
           color: #004d2e;
           flex-shrink: 0;
       }
       
       .event-featured-category {
           position: absolute;
           top: 1rem;
           right: 1rem;
           background-color: white;
           color: #004d2e;
           font-size: 0.75rem;
           font-weight: 600;
           padding: 0.5rem 1rem;
           border-radius: 20px;
           border: 1px solid rgba(0,77,46,0.1);
           text-align: center;
           margin-top: 0.25rem;
           font-family: 'Poppins', sans-serif;
           line-height: 1.2;
       }
       
       /* Quick Menu Styles */
       #quick-menu-section {
           position: relative;
           z-index: 50;
       }
       
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
       
       /* Mobile responsive visibility classes */
       @media (min-width: 768px) {
           .mobile-quickmenu-container,
           .mobile-slider-only,
           .mobile-profile-photo-only,
           .mobile-profile-fallback-only,
           .mobile-profile-default-only,
           .mobile-contact-only,
           .mobile-services-only {
               display: none !important;
           }
       }
       
       @media (min-width: 1024px) {
           .mobile-header-controls-only,
           .mobile-menu-overlay-only {
               display: none !important;
           }
       }
       
       /* Projects Section CSS */
       #projects-section {
           min-height: 400px !important;
           display: block !important;
           visibility: visible !important;
           opacity: 1 !important; 
           z-index: 10 !important;
           position: relative !important;
           pointer-events: auto !important;
       }
       
       /* Projeler bölümünün gizlenmesini önle */
       #projects-section.hidden,
       #projects-section[style*="display: none"],
       #projects-section[style*="visibility: hidden"],
       #projects-section[style*="opacity: 0"] {
           display: block !important;
           visibility: visible !important;
           opacity: 1 !important;
       }
       
       /* Proje panelleri için stiller */
       .project-panel {
           width: 100% !important;
           transition: opacity 0.3s ease-in-out !important;
       }
       
       .project-panel.hidden {
           display: none !important;
       }
       
       .project-panel:not(.hidden) {
           display: block !important;
           visibility: visible !important;
           opacity: 1 !important;
       }
       
       /* Proje slides için stiller */
       .project-slides {
           width: 100% !important;
           position: relative !important;
           overflow: hidden !important;
       }
       
       .project-slide {
           width: 100% !important;
           transition: all 0.3s ease-in-out !important;
           position: relative !important;
       }
       
       .project-slide.hidden {
           display: none !important;
           visibility: hidden !important;
           opacity: 0 !important;
           position: absolute !important;
           top: 0 !important;
           left: 0 !important;
           z-index: -1 !important;
       }
       
       .project-slide:not(.hidden) {
           display: block !important;
           visibility: visible !important;
           opacity: 1 !important;
           position: relative !important;
           z-index: 1 !important;
       }
       
       /* Proje grid sistemi */
       .project-slide .grid {
           display: grid !important;
           grid-template-columns: repeat(1, 1fr) !important;
           gap: 1rem !important;
       }
       
       @media (min-width: 640px) {
           .project-slide .grid {
               grid-template-columns: repeat(2, 1fr) !important;
           }
       }
       
       /* Proje item'ları */
       .project-item {
           width: 100% !important;
           display: block !important;
       }
       
       .project-link {
           display: block !important;
           width: 100% !important;
           aspect-ratio: 16/9 !important;
           border-radius: 1rem !important;
           overflow: hidden !important;
           position: relative !important;
           transition: transform 0.3s ease !important;
       }
       
       .project-link:hover {
           transform: scale(1.02) !important;
       }
       
       .project-link img {
           width: 100% !important;
           height: 100% !important;
           object-fit: cover !important;
       }
       
       /* Kategori radio butonları için stiller - Güçlendirilmiş */
       .category-radio-container {
           position: relative !important;
           display: block !important;
           visibility: visible !important;
           opacity: 1 !important;
           pointer-events: auto !important;
           user-select: none !important;
           z-index: 20 !important;
       }
       
       .category-radio-container.hidden,
       .category-radio-container[style*="display: none"],
       .category-radio-container[style*="visibility: hidden"] {
           display: block !important;
           visibility: visible !important;
           opacity: 1 !important;
       }
       
       /* Kategori butonları */
       .category-btn {
           display: block !important;
           width: 100% !important;
           text-align: left !important;
           padding: 1rem !important;
           border-radius: 0.375rem !important;
           background-color: #f3f4f6 !important;
           color: #1f2937 !important;
           cursor: pointer !important;
           transition: all 0.3s ease !important;
           user-select: none !important;
           pointer-events: auto !important;
           border: none !important;
           font-weight: 500 !important;
           font-size: 0.875rem !important;
           line-height: 1.25rem !important;
       }
       
       .category-btn:hover {
           background-color: #e5e7eb !important;
           transform: translateY(-1px) !important;
           box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
       }
       
       .category-btn.active {
           background-color: #4A5568 !important;
           color: white !important;
           font-weight: 600 !important;
       }
       
       /* Radio input'ları gizle */
       input[name="project-category"] {
           display: none !important;
       }
       
       /* Kategoriler container */
       #categories-container {
           display: block !important;
           visibility: visible !important;
           opacity: 1 !important;
           pointer-events: auto !important;
           z-index: 15 !important;
       }
       
       #categories-container.hidden,
       #categories-container[style*="display: none"],
       #categories-container[style*="visibility: hidden"] {
           display: block !important;
           visibility: visible !important;
           opacity: 1 !important;
       }
       
       /* Projeler container */
       .projects-container {
           width: 100% !important;
           position: relative !important;
           min-height: 300px !important;
       }
       
       /* Responsive düzenlemeler */
       @media (max-width: 640px) {
           .project-slide .grid {
               grid-template-columns: 1fr !important;
               gap: 0.75rem !important;
           }
           
           .category-btn {
               padding: 0.375rem 0.75rem !important;
               font-size: 0.875rem !important;
               margin: 0.125rem !important;
           }
       }
       
       /* Animasyonlar */
       @keyframes fadeIn {
           from {
               opacity: 0;
               transform: translateY(10px);
           }
           to {
               opacity: 1;
               transform: translateY(0);
           }
       }
       
       .project-panel:not(.hidden) {
           animation: fadeIn 0.3s ease-out !important;
       }
       
       /* Koruma stilleri */
       [data-protected="PROJECTS_PROTECTION_2024"] {
           display: block !important;
           visibility: visible !important;
           opacity: 1 !important;
       }
       
       /* Navigasyon butonları */
       .prev-button, .next-button {
           width: 2rem !important;
           height: 2rem !important;
           border: 1px solid #d1d5db !important;
           border-radius: 50% !important;
           background-color: white !important;
           color: #6b7280 !important;
           display: flex !important;
           align-items: center !important;
           justify-content: center !important;
           cursor: pointer !important;
           transition: all 0.2s ease !important;
           box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05) !important;
       }
       
       .prev-button:hover, .next-button:hover {
           background-color: #f9fafb !important;
           border-color: #9ca3af !important;
           color: #374151 !important;
           transform: translateY(-1px) !important;
           box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
       }
       
       .prev-button svg, .next-button svg {
           width: 1rem !important;
           height: 1rem !important;
       }
       
       /* Header CSS */
       /* Menü için aktif durum stili - yeşil çizgi */
       .active-nav, .group:hover > a {
           position: relative;
       }
       
       .active-nav::after, .group:hover > a::after {
           content: '';
           position: absolute;
           bottom: 0;
           left: 0;
           width: 100%;
           height: 3px;
           background-color: #007b32;
       }
       
       /* Mega menü stillerini düzenle */
       .mega-menu {
           top: 96px !important;
           z-index: 60 !important;
       }
       
       /* Mega Menü için geçiş alanı */
       .group::before {
           content: '';
           display: block;
           position: absolute;
           width: 100%;
           height: 20px;
           bottom: -20px;
           left: 0;
           z-index: 100;
       }
       
       /* Mega Menü için üst köprü alanı */
       .mega-menu::before {
           content: '';
           display: block;
           position: absolute;
           width: 100%;
           height: 20px;
           top: -20px;
           left: 0;
       }
       
       /* Header yüksekliği */
       .header-section {
           height: 100px !important; /* 96px + 4px (pt-1) */
           position: relative;
           z-index: 100;
       }
       
       .head {
           height: 96px !important;
       }
       
       /* Yeşil çizgi */
       .w-full.h-1.bg-\[\#007b32\] {
           position: relative;
           z-index: 10;
       }
       
       /* Yatay buton hover efekti */
       .category-button:hover {
           border-color: #e6f7ec;
           background-color: #f0f9f6;
       }
       
       .category-button:hover .material-icons {
           color: #007b32;
       }
       
       /* Metni kesme */
       .truncate {
           overflow: hidden;
           text-overflow: ellipsis;
           white-space: nowrap;
       }
       
       /* Atatürk simgesi pozisyonu */
       .text-xl.font-bold.relative.flex.items-end.pb-1.z-10 {
           position: relative;
           bottom: -10px;
       }
       
       /* Atatürk simgesi için ayrı stil */
       .text-xl.font-bold.relative.flex.items-end.pb-1.z-10 img {
           height: 80px;
       }

       /* Side Menu Stilleri */
       .side-menu-overlay {
           position: fixed;
           top: 0;
           left: 0;
           width: 100%;
           height: 100vh;
           background: rgba(0, 0, 0, 0.5);
           z-index: 99999;
           visibility: hidden;
           opacity: 0;
           transition: all 0.3s ease;
       }
       
       .side-menu-overlay.active {
           visibility: visible;
           opacity: 1;
       }

       /* Mobil menü açık olduğunda body scroll'unu engelle */
       body.mobile-menu-open {
           position: fixed !important;
           top: 0 !important;
           left: 0 !important;
           width: 100% !important;
           height: 100% !important;
           overflow: hidden !important;
           -webkit-overflow-scrolling: touch !important;
           overscroll-behavior: none !important;
       }

       /* Mobil menü içeriği scroll edilebilir olsun */
       #sideMenuContent {
           position: relative !important;
           height: calc(100vh - 48px) !important;
           overflow-y: auto !important;
           -webkit-overflow-scrolling: touch !important;
           overscroll-behavior: contain !important;
       }
       
       .side-menu-container {
           width: 85%;
           max-width: 350px;
           height: 100vh;
           background: white;
           transform: translateX(-100%);
           transition: transform 0.3s ease;
           display: flex;
           flex-direction: column;
           box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
       }
       
       .side-menu-overlay.active .side-menu-container {
           transform: translateX(0);
       }
       
       .side-menu-header {
           display: flex !important;
           align-items: center !important;
           padding: 12px 16px !important;
           background: linear-gradient(135deg, #004d2e, #003d24) !important;
           color: white !important;
           min-height: 48px !important;
           height: 48px !important;
           max-height: 48px !important;
           box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15) !important;
       }
       
       .side-menu-back-btn {
           background: none;
           border: none;
           color: white;
           font-size: 14px;
           padding: 2px 4px;
           margin-right: 6px;
           cursor: pointer;
           border-radius: 3px;
           transition: all 0.2s ease;
       }
       
       .side-menu-back-btn:hover {
           background: rgba(255, 255, 255, 0.1);
       }
       
       .side-menu-title {
           flex: 1;
           font-size: 12px;
           font-weight: 500;
           margin: 0;
           letter-spacing: 0.2px;
           font-family: 'TT Norms Pro', sans-serif;
       }
       
       .side-menu-close-btn {
           background: none;
           border: none;
           color: white;
           font-size: 14px;
           padding: 2px 4px;
           cursor: pointer;
           border-radius: 3px;
           transition: all 0.2s ease;
       }
       
       .side-menu-close-btn:hover {
           background: rgba(255, 255, 255, 0.1);
       }
       
       .side-menu-content {
           flex: 1;
           overflow-y: auto;
           padding: 0;
           background: white;
       }
       
       .side-menu-item {
           display: flex;
           align-items: center;
           padding: 16px 20px;
           color: #374151;
           text-decoration: none;
           border-bottom: 1px solid #e5e7eb;
           transition: all 0.2s ease;
           font-weight: 500;
           font-size: 16px;
           cursor: pointer;
           background: white;
           min-height: 60px;
           font-family: 'TT Norms Pro', sans-serif;
       }
       
       .side-menu-item:hover {
           background: linear-gradient(90deg, #f8fafc 0%, #f1f5f9 100%);
           color: #004d2e;
           transform: translateX(4px);
           box-shadow: 0 2px 4px rgba(0, 77, 46, 0.1);
       }
       
       .side-menu-item:active {
           background: #f3f4f6;
           transform: scale(0.98);
       }
       
       .side-menu-item-icon {
           width: 20px;
           height: 20px;
           margin-right: 12px;
           color: #004d2e;
           display: flex;
           align-items: center;
           justify-content: center;
       }
       
       .side-menu-item-text {
           flex: 1;
       }
       
       .side-menu-item-arrow {
           margin-left: auto;
           color: #6b7280;
           font-size: 14px;
       }
       
       .side-menu-item.has-children .side-menu-item-arrow {
           transform: rotate(0deg);
           transition: transform 0.2s ease;
       }
       
       /* Side menu animasyonları */
       @keyframes slideInLeft {
           from {
               transform: translateX(-100%);
           }
           to {
               transform: translateX(0);
           }
       }
       
       @keyframes slideOutLeft {
           from {
               transform: translateX(0);
           }
           to {
               transform: translateX(-100%);
           }
       }

       /* Overlay'i varsayılan olarak gizle - tüm boyutlarda */
       #sideMenuOverlay {
           display: none !important;
           z-index: 999999 !important;
       }
       
       /* Menü container'ı varsayılan olarak gizle */
       #sideMenuContainer {
           transform: translateX(-100%) !important;
       }
       
       /* Overlay aktif durumu - tüm boyutlarda */
       #sideMenuOverlay.active {
           display: block !important;
           z-index: 999999 !important;
       }
       
       #sideMenuOverlay.active #sideMenuBackdrop {
           opacity: 1;
           z-index: 999998 !important;
       }
       
       #sideMenuOverlay.active #sideMenuContainer {
           transform: translateX(0) !important;
           z-index: 999999 !important;
       }
       
       /* Quick menu z-index'ini düşür */
       #quick-menu-section {
           z-index: 10 !important;
       }
       
       .quick-menu-dropdown {
           z-index: 11 !important;
       }
       
       .quick-menu-section {
           z-index: 10 !important;
       }
       
       /* Mobil menü her zaman en üstte */
       #sideMenuOverlay,
       #sideMenuOverlay.active,
       #sideMenuContainer,
       #sideMenuBackdrop {
           z-index: 999999 !important;
           position: fixed !important;
       }
       
       /* Mobil menü butonu tasarımı - SADECE MOBİL VE TABLET */
       #mobileMenuButton {
           margin-right: 5px !important;
           background: linear-gradient(135deg, #f3f4f6, #ffffff) !important;
           border: 2px solid #e5e7eb !important;
           border-radius: 8px !important;
           width: 44px !important;
           height: 44px !important;
           min-width: 44px !important;
           min-height: 44px !important;
           max-width: 44px !important;
           max-height: 44px !important;
           display: flex !important;
           align-items: center !important;
           justify-content: center !important;
           transition: all 0.2s ease !important;
           box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
           position: relative !important;
           padding: 0 !important;
           outline: none !important;
           cursor: pointer !important;
       }

       /* Desktop'ta mobil menü butonunu gizle */
       @media (min-width: 1024px) {
           #mobileMenuButton {
               display: none !important;
           }
       }
       
       #mobileMenuButton:hover {
           background: linear-gradient(135deg, #004d2e, #003d24) !important;
           border-color: #004d2e !important;
           transform: translateY(-1px) !important;
           box-shadow: 0 4px 8px rgba(0, 77, 46, 0.2) !important;
       }
       
       #mobileMenuButton:hover i,
       #mobileMenuButton:hover span {
           color: white !important;
       }
       
       #mobileMenuButton:active {
           transform: translateY(0) scale(0.95) !important;
       }
       
       #mobileMenuButton:focus {
           outline: none !important;
       }
       
       #mobileMenuButton i,
       #mobileMenuButton span {
           font-size: 18px !important;
           color: #374151 !important;
           transition: color 0.2s ease !important;
           display: inline-block !important;
           text-align: center !important;
           vertical-align: middle !important;
           width: auto !important;
           height: auto !important;
           line-height: 1 !important;
           font-weight: 900 !important;
           font-style: normal !important;
       }
       
       #mobileMenuButton i {
           font-family: "Font Awesome 5 Free" !important;
       }
       
       @media (max-width: 768px) {
           .header-section, .head {
               height: 90px !important;
           }
           
           .flex.items-center.justify-between {
               height: 90px !important;
           }
           
           /* Mobil Atatürk simgesi */
           .text-xl.font-bold.relative.flex.items-end.pb-1.z-10 {
               bottom: -10px; /* Biraz daha aşağıda */
               padding-right: 5px; /* Sağ tarafta biraz boşluk */
           }
           
           .text-xl.font-bold.relative.flex.items-end.pb-1.z-10 img {
               height: 70px; /* Biraz daha büyük */
           }
       }
       
       /* Search Modal CSS */
       /* Modal animasyon efektleri */
       @keyframes fadeIn {
           from { opacity: 0; }
           to { opacity: 1; }
       }
       
       @keyframes slideDown {
           from { transform: translateY(-10px); opacity: 0; }
           to { transform: translateY(0); opacity: 1; }
       }
       
       #searchModal:not(.hidden) {
           animation: fadeIn 0.2s ease-out forwards;
       }
       
       #searchModal:not(.hidden) > div > div {
           animation: slideDown 0.2s ease-out forwards;
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
               font-size: 24px !important;
           }
           
           .featured-service-icon svg {
               /* Mobilde SVG boyutları model tarafından ayarlanır, maksimum sınır */
               max-width: 40px !important;
               max-height: 40px !important;
           }
           
           .featured-service-icon img {
               max-width: 40px !important;
               max-height: 40px !important;
           }
           
           .featured-service-title {
               font-size: 12px !important;
               text-align: center !important;
               display: grid !important;
               place-items: center !important;
               place-content: center !important;
               word-break: break-word !important;
               overflow-wrap: anywhere !important;
               height: 36px !important;
               max-height: 36px !important;
               line-height: 1.1 !important;
               padding: 2px !important;
               grid-template-rows: 1fr !important;
               overflow: hidden !important;
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
               font-size: 14px !important;
               text-align: center !important;
               display: grid !important;
               place-items: center !important;
               place-content: center !important;
               word-break: break-word !important;
               overflow-wrap: anywhere !important;
               height: 40px !important;
               max-height: 40px !important;
               line-height: 1.1 !important;
               padding: 2px !important;
               grid-template-rows: 1fr !important;
               overflow: hidden !important;
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
               font-size: 14px !important;
               text-align: center !important;
               display: grid !important;
               place-items: center !important;
               place-content: center !important;
               word-break: break-word !important;
               overflow-wrap: anywhere !important;
               height: 40px !important;
               max-height: 40px !important;
               line-height: 1.1 !important;
               padding: 2px !important;
               grid-template-rows: 1fr !important;
               overflow: hidden !important;
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
    

    
    <!-- Dinamik Header CSS -->
    @php
        $headerSettings = app(\App\Services\HeaderService::class)->getHeaderSettings();
    @endphp
    @if($headerSettings->custom_css)
    <style>
        {!! $headerSettings->custom_css !!}
    </style>
    @endif
    
    @yield('css')
    
    <!-- Ek SEO meta etiketleri -->
    @yield('additional_head')
    
    <!-- jQuery (DataTables için gerekli) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- DataTables JavaScript -->
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap4.min.js"></script>
    
    <!-- Frontend DataTables Custom JavaScript -->
    <script src="{{ asset('js/frontend-datatables.js') }}?v={{ time() }}" defer></script>
</head>

<body class="bg-slate-100 overflow-x-hidden">
    @if($preloaderEnabled == '1')
        <!-- Preloader -->
        <div id="preloader">
        </div>
    @endif

    <!-- Header Alanı -->
    @include('partials.header')
    
    <!-- Dinamik Header HTML -->
    @if($headerSettings->custom_header_html)
        {!! $headerSettings->custom_header_html !!}
    @endif
    
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
    
    <!-- Dinamik Header JavaScript -->
    @if($headerSettings->sticky_header)
    <script>
        // Sticky Header İşlevi
        document.addEventListener('DOMContentLoaded', function() {
            const header = document.getElementById('header-section');
            const headerHeight = {{ $headerSettings->header_height + 4 }};
            
            if (header) {
                window.addEventListener('scroll', function() {
                    if (window.scrollY > headerHeight) {
                        header.classList.add('fixed', 'top-0', 'left-0', 'right-0');
                        header.style.position = 'fixed';
                        header.style.top = '0';
                        header.style.left = '0';
                        header.style.right = '0';
                        header.style.width = '100%';
                        header.style.zIndex = '999999';
                        
                        // Body'ye padding ekle
                        document.body.style.paddingTop = headerHeight + 'px';
                    } else {
                        header.classList.remove('fixed', 'top-0', 'left-0', 'right-0');
                        header.style.position = 'relative';
                        header.style.top = 'auto';
                        header.style.left = 'auto';
                        header.style.right = 'auto';
                        header.style.width = 'auto';
                        
                        // Body'den padding kaldır
                        document.body.style.paddingTop = '0';
                    }
                });
            }
        });
    </script>
    @endif
    
    @if($headerSettings->additional_scripts)
    <script>
        {!! $headerSettings->additional_scripts !!}
    </script>
    @endif
    
    @yield('after_scripts')
</body>
</html> 