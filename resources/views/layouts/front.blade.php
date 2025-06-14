<!DOCTYPE html>
<html lang="tr" class="overflow-x-hidden">

<head>
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
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Ana CSS -->
    <link href="{{ asset('assets/css/styles.css') }}" rel="stylesheet">
    
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    
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
    
    <!-- Ana JS -->
    <script src="{{ asset('assets/js/script.js') }}"></script>
    
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    
    @yield('after_scripts')
</body>
</html> 