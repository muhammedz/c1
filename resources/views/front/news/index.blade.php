@extends('layouts.front')

@section('title', 'Haberler')

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
    /* Hero Section */
    .page-header {
        position: relative;
        background: linear-gradient(to right, #00352b, #20846c);
        overflow: hidden;
    }

    .pattern-overlay {
        position: absolute;
        inset: 0;
        opacity: 0.2;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='40' height='40' viewBox='0 0 40 40'%3E%3Cpath d='M0 20 L40 20 M20 0 L20 40' stroke='currentColor' stroke-width='1' fill='none' /%3E%3C/svg%3E");
    }

    /* Dekoratif şekiller */
    .decorative-shape-1 {
        position: absolute;
        right: -5rem;
        bottom: -5rem;
        width: 16rem;
        height: 16rem;
        border-radius: 9999px;
        background: rgba(230, 162, 60, 0.1);
        filter: blur(24px);
    }

    .decorative-shape-2 {
        position: absolute;
        left: -2.5rem;
        top: 2.5rem;
        width: 10rem;
        height: 10rem;
        border-radius: 9999px;
        background: rgba(255, 255, 255, 0.05);
        filter: blur(16px);
    }

    .hero-content {
        max-width: 7xl;
        margin: 0 auto;
        padding: 0 1rem;
        position: relative;
        z-index: 10;
    }

    .hero-grid {
        display: grid;
        grid-template-columns: 1fr auto;
        align-items: center;
        gap: 2rem;
    }

    .hero-text h1 {
        font-size: 2.25rem;
        font-weight: 800;
        color: white;
        margin-bottom: 0.75rem;
        line-height: 1.2;
    }

    .hero-text p {
        color: rgba(255, 255, 255, 0.8);
        font-size: 1rem;
        margin-bottom: 0;
        max-width: 32rem;
        line-height: 1.5;
    }

    .hero-search {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        width: 320px;
    }

    .search-title {
        color: white;
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1rem;
        text-align: center;
    }

    .search-input-group {
        position: relative;
        margin-bottom: 1rem;
    }

    .search-input-group input {
        width: 100%;
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
        padding: 0.75rem 1rem 0.75rem 2.5rem;
        border-radius: 0.75rem;
        outline: none;
        font-size: 0.875rem;
        transition: all 0.3s ease;
    }

    .search-input-group input:focus {
        border-color: rgba(230, 162, 60, 0.5);
        background: rgba(255, 255, 255, 0.2);
    }

    .search-input-group input::placeholder {
        color: rgba(255, 255, 255, 0.6);
    }

    .search-input-group i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: rgba(255, 255, 255, 0.7);
        font-size: 0.875rem;
    }

    .search-button {
        width: 100%;
        background: #e6a23c;
        color: white;
        font-weight: 600;
        padding: 0.75rem 1rem;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .search-button:hover {
        background: #d48b1f;
        transform: translateY(-2px);
    }

    /* News Section */
    .news-section {
        padding: 2rem 0;
        background-color: #f8f9fa;
    }

    .container {
        max-width: 1400px !important;
        margin: 0 auto;
        padding: 0 1.5rem;
    }

    /* Ana Haber Grid */
    .main-news-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    /* Manşet Haber */
    .featured-news {
        position: relative;
        height: 600px;
        border-radius: 12px;
        overflow: hidden;
    }

    .featured-news-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .featured-news-content {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 2rem;
        background: linear-gradient(to top, rgba(0,0,0,0.9), transparent);
        color: white;
    }

    /* Yan Haberler */
    .side-news {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .side-news-item {
        position: relative;
        display: flex;
        gap: 1rem;
        background: white;
        padding: 0.75rem;
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        transition: transform 0.2s;
    }

    .side-news-item:hover {
        transform: translateY(-2px);
    }

    .side-news-img {
        width: 100px;
        height: 70px;
        object-fit: cover;
        border-radius: 0.25rem;
    }

    /* Alt Haberler Grid */
    .news-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 1rem;
    }

    .news-card {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        transition: all 0.3s ease;
        position: relative;
        height: 100%;
    }

    .news-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }

    .news-card-img {
        width: 100%;
        height: 160px;
        object-fit: cover;
    }

    .news-card-content {
        padding: 1rem;
    }

    .news-category {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        background: var(--primary-color, #00352b);
        color: white;
        font-size: 0.7rem;
        font-weight: 600;
        border-radius: 20px;
        margin-bottom: 0.5rem;
    }

    .news-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 0.5rem;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .news-excerpt {
        color: #666;
        font-size: 0.8rem;
        margin-bottom: 0.75rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .news-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 0.75rem;
        border-top: 1px solid #eee;
        font-size: 0.7rem;
        color: #666;
    }

    /* Pagination */
    .pagination {
        margin-top: 3rem;
        display: flex;
        justify-content: center;
        gap: 0.5rem;
    }

    .page-link {
        padding: 0.5rem 1rem;
        border-radius: 6px;
        background: white;
        color: #333;
        transition: all 0.3s ease;
    }

    .page-link:hover,
    .page-link.active {
        background: var(--primary-color, #00352b);
        color: white;
    }

    /* Responsive */
    @media (max-width: 1400px) {
        .news-grid {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    @media (max-width: 1200px) {
        .news-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 1024px) {
        .main-news-grid {
            grid-template-columns: 1fr;
        }

        .featured-news {
            height: 400px;
        }

        .side-news {
            grid-template-columns: repeat(2, 1fr);
        }

        .news-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .side-news {
            grid-template-columns: 1fr;
        }

        .news-grid {
            grid-template-columns: 1fr;
        }

        .main-news-grid {
            grid-template-columns: 1fr;
        }

        .slider-image {
            height: 300px;
        }

        .sub-news-grid {
            grid-template-columns: 1fr;
        }

        .categories-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Slider Styles */
    .slider-section {
        position: relative;
        border-radius: 0;
        overflow: hidden;
        background: #000;
        margin-bottom: 2rem;
    }

    .slider-item {
        display: none;
        position: relative;
    }

    .slider-item.active {
        display: block;
    }

    .slider-image {
        width: 100%;
        height: 500px;
        object-fit: cover;
        opacity: 0.8;
    }

    .slider-content {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 2rem;
        background: linear-gradient(to top, rgba(0,0,0,0.9), transparent);
        color: white;
    }

    .slider-title {
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 1rem;
        line-height: 1.2;
    }

    .slider-excerpt {
        font-size: 1.1rem;
        margin-bottom: 1rem;
        opacity: 0.9;
    }

    .slider-meta {
        font-size: 1rem;
        opacity: 0.8;
    }

    /* Slider Pagination */
    .slider-pagination {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        display: flex;
        justify-content: center;
        gap: 0.5rem;
        padding: 1rem;
        background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
    }

    .slider-page {
        width: 2.5rem;
        height: 2.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255,255,255,0.2);
        color: white;
        border-radius: 0;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .slider-page.active {
        background: #fff;
        color: #000;
    }

    @media (max-width: 768px) {
        .slider-image {
            height: 300px;
        }

        .slider-title {
            font-size: 1.5rem;
        }

        .slider-excerpt {
            font-size: 1rem;
            display: none;
        }

        .slider-page {
            width: 2rem;
            height: 2rem;
            font-size: 0.9rem;
        }
    }

    /* Kategori Başlıkları */
    .category-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        border-bottom: 1px solid #e9ecef;
    }

    .category-title {
        font-size: 1.25rem;
        font-weight: bold;
        color: #00352b;
    }

    .category-link {
        color: #00352b;
        text-decoration: none;
        font-weight: 500;
    }

    /* Kategori Bölümleri */
    .categories-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 2rem;
    }

    .category-section {
        background: white;
        border-radius: 0.5rem;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    /* Kategori Kartları */
    .category-main-news {
        padding: 1rem;
    }

    .main-news-card {
        position: relative;
        border-radius: 0.5rem;
        overflow: hidden;
        margin-bottom: 1rem;
    }

    .main-news-card img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .main-news-content {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 1rem;
        background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
        color: white;
    }

    .sub-news-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }

    .sub-news-card {
        position: relative;
        background: #f8f9fa;
        border-radius: 0.5rem;
        overflow: hidden;
        transition: transform 0.2s;
    }

    .sub-news-card:hover {
        transform: translateY(-2px);
    }

    .sub-news-image {
        width: 100%;
        height: 100px;
        object-fit: cover;
    }

    .sub-news-content {
        padding: 0.75rem;
    }

    /* Parallax Bölümü */
    .parallax-section {
        position: relative;
        background-attachment: fixed;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
        min-height: 400px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 4rem 0;
    }

    .parallax-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to right, rgba(0,53,43,0.9), rgba(32,132,108,0.9));
    }

    .parallax-content {
        position: relative;
        z-index: 2;
        text-align: center;
        max-width: 800px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .parallax-title {
        font-size: 3rem;
        font-weight: 800;
        color: #fff;
        margin-bottom: 1.5rem;
        line-height: 1.2;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .parallax-description {
        font-size: 1.25rem;
        color: rgba(255,255,255,0.9);
        margin-bottom: 2rem;
        line-height: 1.6;
    }

    .parallax-button {
        display: inline-flex;
        align-items: center;
        padding: 1rem 2rem;
        background: #fff;
        color: #00352b;
        font-weight: 600;
        border-radius: 50px;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .parallax-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        background: #00352b;
        color: #fff;
    }

    /* Etkinlikler Bölümü */
    .events-section {
        background-color: #fff;
        padding: 4rem 0;
    }

    .timelineSwiper {
        overflow: visible;
        padding: 20px 0;
    }

    .timelineSwiper .swiper-slide {
        width: 400px;
        opacity: 0.5;
        transform: scale(0.9);
        transition: all 0.3s ease;
    }

    .timelineSwiper .swiper-slide-active {
        opacity: 1;
        transform: scale(1);
    }

    .event-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .event-card:hover {
        transform: translateY(-5px);
    }

    .event-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .event-content {
        padding: 1.5rem;
    }

    .event-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 1rem;
    }

    .event-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
        color: #666;
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }

    .event-meta i {
        color: #00352b;
    }

    @media (max-width: 768px) {
        .timelineSwiper .swiper-slide {
            width: 300px;
        }

        .parallax-title {
            font-size: 2rem;
        }

        .parallax-description {
            font-size: 1rem;
        }
    }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<div class="relative bg-gradient-to-r from-[#00352b] to-[#20846c] overflow-hidden">
    <div class="absolute inset-0 opacity-20">
        <!-- Pattern overlay -->
        <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" preserveAspectRatio="none">
            <defs>
                <pattern id="hero-pattern" width="40" height="40" patternUnits="userSpaceOnUse">
                    <path d="M0 20 L40 20 M20 0 L20 40" stroke="currentColor" stroke-width="1" fill="none" />
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#hero-pattern)" />
        </svg>
    </div>
    
    <!-- Dekoratif şekiller -->
    <div class="absolute -right-20 -bottom-20 w-64 h-64 rounded-full bg-[#e6a23c]/10 blur-3xl"></div>
    <div class="absolute -left-10 top-10 w-40 h-40 rounded-full bg-white/5 blur-2xl"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 md:py-10 relative z-10">
        <div class="flex flex-col md:flex-row items-center justify-between">
            <!-- Sol taraf - başlık ve açıklama -->
            <div class="w-full md:w-1/2 mb-6 md:mb-0 md:pr-8">
                <div class="inline-flex items-center px-3 py-1 rounded-full bg-white/10 text-white/90 text-sm border border-white/10 mb-3">
                    <span class="material-icons text-xs mr-1">article</span>
                    <span>Güncel Haberler</span>
                </div>
                
                <h1 class="text-3xl md:text-4xl font-extrabold text-white leading-tight mb-3">
                    <span class="text-[#e6a23c]">Güncel</span> Haberler ve Gelişmeler
                </h1>
                
                <p class="text-white/80 text-base mb-0 max-w-lg">
                    En güncel haberler ve gelişmeler burada. Sektörden son dakika haberleri, önemli gelişmeler ve daha fazlası...
                </p>
            </div>
            
            <!-- Sağ taraf - arama kutusu -->
            <div class="w-full md:w-1/2 md:pl-8">
                <div class="bg-white/10 backdrop-blur-md rounded-xl p-4 border border-white/20 shadow-xl">
                    <h3 class="text-lg text-white font-medium mb-3">Haber Ara</h3>
                    
                    <form action="{{ route('news.index') }}" method="GET">
                        <div class="mb-3">
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-white/60">
                                    <span class="material-icons">search</span>
                                </span>
                                <input 
                                    type="text" 
                                    name="search" 
                                    placeholder="Haber ara..." 
                                    class="w-full bg-white/10 border border-white/20 text-white placeholder-white/60 pl-10 pr-4 py-3 rounded-xl focus:ring-2 focus:ring-white/30 outline-none"
                                    value="{{ request()->get('search') }}"
                                >
                            </div>
                        </div>
                        <button type="submit" class="w-full bg-white hover:bg-gray-100 text-[#00352b] font-semibold py-3 px-5 rounded-xl transition-all duration-300 flex items-center justify-center">
                            <span class="material-icons mr-2">search</span>
                            Ara
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="news-section">
    <div class="container">
        <!-- Ana Grid (Slider ve Yan Haberler) -->
        <div class="main-news-grid">
            <!-- Slider Section -->
            <div class="slider-section" style="margin-bottom: 0;">
                @foreach($news->take(10) as $index => $item)
                <div class="slider-item {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}">
                    <img src="{{ asset($item->image ?? 'images/default-news.jpg') }}" alt="{{ $item->title }}" class="slider-image">
                    <div class="slider-content">
                        <h2 class="slider-title">{{ $item->title }}</h2>
                        <p class="slider-excerpt">{{ Str::limit(strip_tags($item->content), 200) }}</p>
                        <div class="slider-meta">
                            <span class="flex items-center">
                                {{ \Carbon\Carbon::parse($item->published_at)->format('d.m.Y') }}
                            </span>
                        </div>
                        <a href="{{ route('news.show', $item->slug) }}" class="absolute inset-0" aria-label="Habere Git"></a>
                    </div>
                </div>
                @endforeach

                <!-- Slider Pagination -->
                <div class="slider-pagination">
                    @for($i = 1; $i <= min(10, count($news)); $i++)
                        <div class="slider-page {{ $i === 1 ? 'active' : '' }}" data-index="{{ $i }}">{{ $i }}</div>
                    @endfor
                </div>
            </div>

            <!-- Yan Haberler -->
            <div class="side-news">
                @foreach($news->skip(1)->take(4) as $item)
                <div class="side-news-item">
                    <img src="{{ asset($item->image ?? 'images/default-news.jpg') }}" alt="{{ $item->title }}" class="side-news-img">
                    <div>
                        @if($item->categories->isNotEmpty())
                        <span class="text-primary text-xs font-semibold">{{ $item->categories->first()->name }}</span>
                        @endif
                        <h3 class="text-sm font-semibold mt-1 line-clamp-2">{{ $item->title }}</h3>
                        <span class="text-xs text-gray-500 mt-1 block">
                            {{ \Carbon\Carbon::parse($item->published_at)->format('d.m.Y') }}
                        </span>
                    </div>
                    <a href="{{ route('news.show', $item->slug) }}" class="absolute inset-0" aria-label="Habere Git"></a>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Kategorize Edilmiş Haberler -->
        <div class="categories-grid">
            @foreach($categories as $category)
            @php
                $categoryNews = $news->filter(function($item) use ($category) {
                    return $item->categories->contains('id', $category->id);
                });
                $mainNews = $categoryNews->first();
                $subNews = $categoryNews->skip(1)->take(3);
            @endphp
            
            @if($mainNews)
            <div class="category-section">
                <div class="category-header">
                    <h2 class="category-title">{{ $category->name }}</h2>
                    <a href="{{ route('news.category', $category->slug) }}" class="category-link">Tümünü Gör →</a>
                </div>
                <div class="category-main-news">
                    <div class="main-news-card">
                        <img src="{{ asset($mainNews->image ?? 'images/default-news.jpg') }}" alt="{{ $mainNews->title }}">
                        <div class="main-news-content">
                            <span class="news-category">{{ $category->name }}</span>
                            <h3 class="text-xl font-bold mb-2">{{ $mainNews->title }}</h3>
                            <p class="text-sm text-white/80 mb-3 line-clamp-2">{{ Str::limit(strip_tags($mainNews->content), 150) }}</p>
                            <div class="flex items-center gap-4 text-sm text-white/60">
                                <span class="flex items-center gap-1">
                                    <i class="fas fa-clock"></i>
                                    {{ \Carbon\Carbon::parse($mainNews->published_at)->diffForHumans() }}
                                </span>
                                @if($mainNews->views)
                                <span class="flex items-center gap-1">
                                    <i class="fas fa-eye"></i>
                                    {{ number_format($mainNews->views) }}
                                </span>
                                @endif
                            </div>
                            <a href="{{ route('news.show', $mainNews->slug) }}" class="absolute inset-0" aria-label="Habere Git"></a>
                        </div>
                    </div>

                    <div class="sub-news-grid">
                        @foreach($subNews as $subNews)
                        <div class="sub-news-card">
                            <img src="{{ asset($subNews->image ?? 'images/default-news.jpg') }}" alt="{{ $subNews->title }}" class="sub-news-image">
                            <div class="sub-news-content">
                                <h4 class="text-sm font-semibold mb-1">{{ $subNews->title }}</h4>
                                <span class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($subNews->published_at)->format('d.m.Y') }}
                                </span>
                            </div>
                            <a href="{{ route('news.show', $subNews->slug) }}" class="absolute inset-0" aria-label="Habere Git"></a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
            @endforeach
        </div>

        <!-- Parallax Bölümü -->
        <div class="parallax-section" style="background-image: url('{{ asset('images/parallax-bg.jpg') }}');">
            <div class="parallax-content">
                <h2 class="parallax-title">Güncel Gelişmelerden<br>Haberdar Olun</h2>
                <p class="parallax-description">Belediyemizin faaliyetleri, projeler ve duyurular hakkında güncel bilgilere ulaşın. Size özel içeriklerle her zaman bilgi sahibi olun.</p>
                <a href="{{ route('news.index') }}" class="parallax-button">
                    Tüm Haberleri Görüntüle
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
            </div>
        </div>

        <!-- Etkinlikler Bölümü -->
        <div class="events-section">
            <div class="container">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-4">
                        <h2 class="text-3xl font-bold text-gray-800">Yaklaşan Etkinlikler</h2>
                        <div class="flex items-center gap-2">
                            <button class="timeline-prev bg-white rounded-full w-10 h-10 flex items-center justify-center shadow-lg border border-gray-100 hover:bg-gray-50 transition-all">
                                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>
                            <button class="timeline-next bg-white rounded-full w-10 h-10 flex items-center justify-center shadow-lg border border-gray-100 hover:bg-gray-50 transition-all">
                                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <a href="{{ route('events.index') }}" class="inline-flex items-center text-[#00352b] font-semibold hover:underline">
                        Tüm Etkinlikler
                        <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>

                <div class="swiper timelineSwiper">
                    <div class="swiper-wrapper">
                        @foreach($upcomingEvents ?? [] as $event)
                        <div class="swiper-slide">
                            <div class="event-card">
                                <div class="relative">
                                    @if($event->cover_image)
                                        <img src="{{ asset($event->cover_image) }}" alt="{{ $event->title }}" class="event-image">
                                    @else
                                        <div class="event-image bg-gray-100 flex items-center justify-center">
                                            <span class="text-gray-400 text-sm">Görsel Mevcut Değil</span>
                                        </div>
                                    @endif
                                    <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-sm font-medium text-[#00352b]">
                                        {{ $event->category ? $event->category->name : 'Genel' }}
                                    </div>
                                </div>
                                <div class="event-content">
                                    <h3 class="event-title">{{ $event->title }}</h3>
                                    <div class="event-meta">
                                        <span class="flex items-center gap-2">
                                            <i class="fas fa-calendar"></i>
                                            {{ \Carbon\Carbon::parse($event->start_date)->format('d.m.Y') }}
                                        </span>
                                        <span class="flex items-center gap-2">
                                            <i class="fas fa-clock"></i>
                                            {{ \Carbon\Carbon::parse($event->start_date)->format('H:i') }}
                                        </span>
                                    </div>
                                    <div class="event-meta">
                                        <span class="flex items-center gap-2">
                                            <i class="fas fa-map-marker-alt"></i>
                                            {{ $event->location }}
                                        </span>
                                    </div>
                                    <div class="mt-4">
                                        <a href="{{ route('events.show', $event->slug) }}" class="inline-flex items-center text-[#00352b] font-semibold hover:underline">
                                            Detayları Görüntüle
                                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Sayfalama -->
        <div class="mt-8">
            {{ $news->links() }}
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sliderItems = document.querySelectorAll('.slider-item');
    const sliderPages = document.querySelectorAll('.slider-page');
    let currentSlide = 0;
    const totalSlides = sliderItems.length;

    function showSlide(index) {
        sliderItems.forEach(item => item.classList.remove('active'));
        sliderPages.forEach(page => page.classList.remove('active'));
        
        sliderItems[index].classList.add('active');
        sliderPages[index].classList.add('active');
    }

    function nextSlide() {
        currentSlide = (currentSlide + 1) % totalSlides;
        showSlide(currentSlide);
    }

    // Sayfalandırma tıklama olayları
    sliderPages.forEach((page, index) => {
        page.addEventListener('click', () => {
            currentSlide = index;
            showSlide(currentSlide);
        });
    });

    // Otomatik geçiş
    setInterval(nextSlide, 5000);

    // Dokunma olayları için
    let touchStartX = 0;
    let touchEndX = 0;

    document.querySelector('.slider-section').addEventListener('touchstart', e => {
        touchStartX = e.changedTouches[0].screenX;
    }, false);

    document.querySelector('.slider-section').addEventListener('touchend', e => {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    }, false);

    function handleSwipe() {
        const swipeThreshold = 50;
        const diff = touchEndX - touchStartX;

        if (Math.abs(diff) > swipeThreshold) {
            if (diff > 0) {
                // Sağa kaydırma
                currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
            } else {
                // Sola kaydırma
                currentSlide = (currentSlide + 1) % totalSlides;
            }
            showSlide(currentSlide);
        }
    }

    // Etkinlikler Slider
    const timelineSwiper = new Swiper('.timelineSwiper', {
        slidesPerView: 'auto',
        centeredSlides: true,
        spaceBetween: 30,
        grabCursor: true,
        loop: true,
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
});
</script>
@endsection 