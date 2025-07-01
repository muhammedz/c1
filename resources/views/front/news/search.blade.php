@extends('layouts.front')

@section('title', '"' . $searchTerm . '" Arama Sonuçları - Haberler')

@section('css')
<style>
    /* Hero Bölümü */
    .search-header {
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

    .search-container {
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
    
    .search-title {
        font-size: 2.25rem;
        font-weight: 700;
        color: white;
        margin: 0;
        line-height: 1.2;
        position: relative;
        z-index: 10;
    }

    .search-description {
        color: rgba(255, 255, 255, 0.8);
        font-size: 1.125rem;
        margin-top: 0.75rem;
        line-height: 1.5;
        position: relative;
        z-index: 10;
    }

    .search-term-highlight {
        background-color: rgba(230, 162, 60, 0.3);
        padding: 0.2rem 0.4rem;
        border-radius: 0.25rem;
        font-weight: 600;
    }

    /* İçerik Bölümü */
    .search-results {
        padding: 3rem 0;
        background-color: #f8f9fa;
    }

    /* Arama Formu */
    .search-form {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 2rem;
        max-width: 600px;
    }

    .search-input {
        flex: 1;
        padding: 0.75rem 1rem;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        font-size: 1rem;
        background-color: white;
    }

    .search-input:focus {
        outline: none;
        border-color: #00352b;
        box-shadow: 0 0 0 3px rgba(0, 53, 43, 0.1);
    }

    .search-button {
        padding: 0.75rem 1.5rem;
        background-color: #00352b;
        color: white;
        border: none;
        border-radius: 0.5rem;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.2s;
    }
    
    .search-button:hover {
        background-color: #004d2e;
    }

    /* Sonuç Bilgisi */
    .results-info {
        background-color: #e0f2fe;
        border: 1px solid #b3e5fc;
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 2rem;
        color: #01579b;
    }

    /* Haber Grid Düzeni */
    .news-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .news-card {
        background-color: white;
        border-radius: 0.75rem;
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
        min-height: 180px;
        overflow: hidden;
        position: relative;
        background-color: #f3f4f6;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .news-card-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        transition: transform 0.5s ease;
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
        background-color: rgba(0, 53, 43, 0.1);
        color: #00352b;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        width: fit-content;
        margin-bottom: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .news-card-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.75rem;
        line-height: 1.4;
    }

    .news-card-title a {
        color: inherit;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .news-card-title a:hover {
        color: #00352b;
    }

    .news-card-excerpt {
        color: #6b7280;
        font-size: 0.875rem;
        line-height: 1.6;
        margin-bottom: 1rem;
        flex: 1;
    }

    .news-card-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: auto;
    }

    .news-card-date {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #9ca3af;
        font-size: 0.875rem;
    }

    .news-card-button {
        background-color: #00352b;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .news-card-button:hover {
        background-color: #004d2e;
        color: white;
        text-decoration: none;
    }

    /* Pagination */
    .pagination-wrapper {
        margin-top: 3rem;
        display: flex;
        justify-content: center;
    }

    /* Sonuç bulunamadı */
    .no-results {
        text-align: center;
        padding: 3rem;
        background-color: white;
        border-radius: 0.75rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
    }

    .no-results-icon {
        font-size: 4rem;
        color: #d1d5db;
        margin-bottom: 1rem;
    }

    .no-results-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .no-results-text {
        color: #6b7280;
        margin-bottom: 1.5rem;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #00352b;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .back-link:hover {
        color: #004d2e;
        text-decoration: underline;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .search-title {
            font-size: 1.75rem;
        }
        
        .search-description {
            font-size: 1rem;
        }

        .search-form {
            flex-direction: column;
        }

        .news-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<!-- Arama Başlık Alanı -->
<header class="search-header">
    <div class="pattern-overlay"></div>
    <div class="decorative-shape-1"></div>
    <div class="decorative-shape-2"></div>
    <div class="search-container">
        <nav class="breadcrumb">
            <a href="{{ route('front.home') }}" class="breadcrumb-link">Ana Sayfa</a>
            <span class="breadcrumb-separator">/</span>
            <a href="{{ route('news.index') }}" class="breadcrumb-link">Haberler</a>
            <span class="breadcrumb-separator">/</span>
            <span class="breadcrumb-item">Arama Sonuçları</span>
        </nav>
        
        <h1 class="search-title">Arama Sonuçları</h1>
        <p class="search-description">
            <span class="search-term-highlight">"{{ $searchTerm }}"</span> için bulunan haberler
        </p>
    </div>
</header>

<!-- Arama Sonuçları -->
<section class="search-results">
    <div class="search-container">
        <!-- Arama Formu -->
        <form action="{{ route('news.index') }}" method="GET" class="search-form">
            <input type="text" name="search" placeholder="Haber ara..." class="search-input" value="{{ $searchTerm }}">
            <button type="submit" class="search-button">
                <i class="fas fa-search mr-2"></i> Ara
            </button>
        </form>

        <!-- Sonuç Bilgisi -->
        <div class="results-info">
            <i class="fas fa-info-circle mr-2"></i>
            <strong>{{ $news->total() }}</strong> adet haber bulundu
            @if($news->hasPages())
                (Sayfa {{ $news->currentPage() }} / {{ $news->lastPage() }})
            @endif
        </div>

        @if($news->count() > 0)
            <!-- Sonuç Listesi -->
            <div class="news-grid">
                @foreach($news as $item)
                    <article class="news-card">
                        <div class="news-card-image-container">
                            @if($item->image)
                                <img src="{{ str_replace('/storage/storage/', '/storage/', $item->image) }}" class="news-card-image" alt="{{ $item->title }} haber görseli">
                            @else
                                <i class="fas fa-newspaper"></i>
                            @endif
                        </div>
                        
                        <div class="news-card-content">
                            @if($item->categories->isNotEmpty())
                                <span class="news-card-category">{{ $item->categories->first()->name }}</span>
                            @endif
                            
                            <h3 class="news-card-title">
                                <a href="{{ route('news.show', $item->slug) }}">
                                    {!! html_entity_decode($item->title) !!}
                                </a>
                            </h3>
                            
                            <p class="news-card-excerpt">
                                {!! Str::limit(html_entity_decode(strip_tags($item->content)), 120) !!}
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
                @endforeach
            </div>

            <!-- Pagination -->
            @if($news->hasPages())
                <div class="pagination-wrapper">
                    {{ $news->withQueryString()->links() }}
                </div>
            @endif
        @else
            <!-- Sonuç Bulunamadı -->
            <div class="no-results">
                <i class="fas fa-search no-results-icon"></i>
                <h3 class="no-results-title">Aradığınız kriterlere uygun haber bulunamadı</h3>
                <p class="no-results-text">
                    "<strong>{{ $searchTerm }}</strong>" araması için sonuç bulunamadı. Lütfen farklı anahtar kelimeler deneyin.
                </p>
                <a href="{{ route('news.index') }}" class="back-link">
                    <i class="fas fa-arrow-left"></i>
                    Tüm Haberlere Dön
                </a>
            </div>
        @endif
    </div>
</section>
@endsection 