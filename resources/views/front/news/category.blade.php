@extends('layouts.front')

@section('title', $category->name . ' Haberleri - ' . config('app.name'))

@section('meta_description', $category->description ?? $category->name . ' kategorisindeki en güncel haberler ve gelişmeler.')

@section('css')
<style>
    /* Hero Bölümü */
    .category-header {
        position: relative;
        background: linear-gradient(to right, #00352b, #20846c);
        padding: 3rem 0;
        overflow: hidden;
    }
    
    .pattern-overlay {
        position: absolute;
        inset: 0;
        opacity: 0.1;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='40' height='40' viewBox='0 0 40 40'%3E%3Cpath d='M0 20 L40 20 M20 0 L20 40' stroke='white' stroke-width='1' fill='none' /%3E%3C/svg%3E");
        pointer-events: none;
        z-index: 1;
    }
    
    .decorative-shape-1 {
        position: absolute;
        right: -5rem;
        bottom: -5rem;
        width: 16rem;
        height: 16rem;
        border-radius: 9999px;
        background: rgba(230, 162, 60, 0.1);
        filter: blur(24px);
        z-index: 1;
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
        z-index: 1;
    }
    
    .category-container {
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
    
    .category-title {
        font-size: 2.25rem;
        font-weight: 700;
        color: white;
        margin: 0;
        line-height: 1.2;
        position: relative;
        z-index: 10;
    }
    
    .category-description {
        color: rgba(255, 255, 255, 0.8);
        font-size: 1.125rem;
        margin-top: 0.75rem;
        max-width: 36rem;
        line-height: 1.5;
        position: relative;
        z-index: 10;
    }
    
    /* İçerik Bölümü */
    .category-content {
        padding: 3rem 0;
        background-color: #f8f9fa;
    }
    
    .news-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
    }
    
    .news-card {
        background-color: white;
        border-radius: 1rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        overflow: hidden;
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .news-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }
    
    .news-card-image-container {
        width: 100%;
        aspect-ratio: 16/9;
        min-height: 220px;
        overflow: hidden;
        position: relative;
        background-color: #f3f4f6;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    
    .news-card-image-container a {
        position: absolute;
        width: 100%;
        height: 100%;
        display: block;
    }
    
    .news-card-image {
        width: 100%;
        height: 100%;
        min-width: 100%;
        min-height: 100%;
        object-fit: cover;
        object-position: center;
        transition: transform 0.5s ease;
    }
    
    .news-card-image-container i.fas {
        font-size: 3rem;
    }
    
    .news-card:hover .news-card-image {
        transform: scale(1.05);
    }
    
    .news-card-content {
        padding: 1.5rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    
    .news-card-category {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        background-color: rgba(0, 53, 43, 0.1);
        color: #00352b;
        border-radius: 9999px;
        font-size: 0.75rem;
        margin-bottom: 0.75rem;
        font-weight: 500;
    }
    
    .news-card-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.75rem;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .news-card-excerpt {
        color: #6b7280;
        font-size: 0.875rem;
        margin-bottom: 1rem;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        flex: 1;
    }
    
    .news-card-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        color: #9ca3af;
        font-size: 0.75rem;
        padding-top: 1rem;
        border-top: 1px solid #f3f4f6;
        margin-top: auto;
    }
    
    .news-card-date {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .news-card-button {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.375rem 0.75rem;
        background-color: #00352b;
        color: white;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .news-card-button:hover {
        background-color: #004d2e;
    }
    
    /* Filtreleme ve Sayfalama */
    .category-filters {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .filter-options {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .filter-option {
        padding: 0.5rem 1rem;
        background-color: white;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        color: #6b7280;
        cursor: pointer;
        transition: all 0.2s ease;
        border: 1px solid #e5e7eb;
    }
    
    .filter-option.active {
        background-color: #00352b;
        color: white;
        border-color: #00352b;
    }
    
    .filter-option:hover:not(.active) {
        background-color: #f9fafb;
        color: #1f2937;
    }
    
    .pagination-container {
        display: flex;
        justify-content: center;
        margin-top: 3rem;
    }
    
    /* Responsive */
    @media (max-width: 1024px) {
        .news-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .category-title {
            font-size: 1.75rem;
        }
        
        .category-description {
            font-size: 1rem;
        }
        
        .news-grid {
            grid-template-columns: 1fr;
        }
        
        .category-filters {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .filter-options {
            flex-wrap: wrap;
        }
    }
</style>
@endsection

@section('content')
<!-- Kategori Başlık Alanı -->
<header class="category-header">
    <div class="pattern-overlay"></div>
    <div class="decorative-shape-1"></div>
    <div class="decorative-shape-2"></div>
    <div class="category-container">
        <nav class="breadcrumb">
                                    <a href="{{ route('front.home') }}" class="breadcrumb-link">Ana Sayfa</a>
            <span class="breadcrumb-separator">/</span>
            <a href="{{ route('news.index') }}" class="breadcrumb-link">Haberler</a>
            <span class="breadcrumb-separator">/</span>
            <span class="breadcrumb-item">{{ $category->name }}</span>
        </nav>
        
        <h1 class="category-title">{{ $category->name }} Haberleri</h1>
        @if($category->description)
            <p class="category-description">{{ $category->description }}</p>
        @endif
    </div>
</header>

<!-- İçerik Alanı -->
<section class="category-content">
    <div class="category-container">
        <div class="category-filters">
            <div class="results-count">
                Toplam {{ $news->total() }} haber bulundu
            </div>
        </div>
        
        <div class="news-grid">
            @forelse($news as $item)
                <article class="news-card">
                    @if($item->image)
                        <div class="news-card-image-container">
                            <a href="{{ route('news.show', $item->slug) }}">
                                <img src="{{ str_replace('/storage/storage/', '/storage/', $item->image) }}" class="news-card-image" alt="{{ $item->title }}">
                            </a>
                        </div>
                    @else
                        <div class="news-card-image-container">
                            <i class="fas fa-newspaper text-4xl text-gray-300"></i>
                        </div>
                    @endif
                    
                    <div class="news-card-content">
                        <span class="news-card-category">{{ $category->name }}</span>
                        <h3 class="news-card-title">
                            <a href="{{ route('news.show', $item->slug) }}" class="text-inherit hover:text-[#00352b]">
                                {!! html_entity_decode($item->title) !!}
                            </a>
                        </h3>
                        
                        <p class="news-card-excerpt">
                            {!! Str::limit(html_entity_decode(strip_tags($item->content)), 150) !!}
                        </p>
                        
                        <div class="news-card-meta">
                            <div class="news-card-date">
                                <i class="fas fa-calendar-alt"></i>
                                <span>{{ \Carbon\Carbon::parse($item->published_at)->format('d.m.Y') }}</span>
                            </div>
                            
                            <a href="{{ route('news.show', $item->slug) }}" class="news-card-button">
                                Devamını Oku
                                <i class="fas fa-arrow-right text-xs"></i>
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-3 bg-white p-8 rounded-lg text-center">
                    <i class="fas fa-newspaper text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">Haber Bulunamadı</h3>
                    <p class="text-gray-500">Bu kategoride henüz haber bulunmamaktadır.</p>
                </div>
            @endforelse
        </div>
        
        <div class="pagination-container">
            {{ $news->links() }}
        </div>
    </div>
</section>
@endsection 