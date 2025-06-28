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