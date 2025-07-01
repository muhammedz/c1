@extends('layouts.front')

@section('title', 'Haberler')

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
    .news-portal {
        padding: 3rem 0;
        background-color: #f8f9fa;
    }
    
    /* Kategori Başlıkları */
    .category-section-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1f2937;
        margin: 0 0 1.5rem 0;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #e5e7eb;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .category-section-title i {
        color: #00352b;
    }
    
    .view-all-link {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        color: #00352b;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s ease;
        margin-left: auto;
    }

    .view-all-link:hover {
        text-decoration: underline;
        color: #004d2e;
    }

    /* Haber Grid Düzeni */
    .news-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
        margin-bottom: 3rem;
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
        min-width: 100%;
        min-height: 100%;
        object-fit: cover;
        object-position: center;
        transition: transform 0.5s ease;
    }

    .news-card:hover .news-card-image {
        transform: scale(1.05);
    }

    .news-card-image-container i.fas {
        font-size: 2.5rem;
        color: #d1d5db;
    }

    .news-card-content {
        padding: 1.25rem;
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
        font-size: 1.1rem;
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
        -webkit-line-clamp: 2;
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
        padding-top: 0.75rem;
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

    /* Arama Bölümü */
    .news-search-section {
        background-color: white;
        border-radius: 0.75rem;
        padding: 1.25rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
    }
    
    .search-form {
        display: flex;
        gap: 0.5rem;
    }

    .search-input {
        flex: 1;
        padding: 0.75rem 1rem;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        font-size: 0.875rem;
    }

    .search-button {
        background-color: #00352b;
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.2s;
    }
    
    .search-button:hover {
        background-color: #004d2e;
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .news-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 992px) {
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
            <span class="breadcrumb-item">Haberler</span>
        </nav>
        
        <h1 class="category-title">Haber Portalı</h1>
        <p class="category-description">En güncel haberler ve son gelişmeler burada. Belediyemizin faaliyetleri, projeler ve önemli duyurular hakkında bilgi sahibi olun.</p>
    </div>
</header>
    
<!-- İçerik Alanı -->
<section class="news-portal">
    <div class="category-container">
        <!-- Arama Bölümü -->
        <div class="news-search-section">
            <form action="{{ route('news.index') }}" method="GET" class="search-form">
                <input type="text" name="search" placeholder="Haber ara..." class="search-input" value="{{ request()->get('search') }}">
                <button type="submit" class="search-button">
                    <i class="fas fa-search mr-2"></i> Ara
                </button>
            </form>
                </div>
                
        <!-- Son Haberler Bölümü -->
        <div class="category-section">
            <h2 class="category-section-title">
                <i class="fas fa-newspaper"></i> Son Haberler
            </h2>
            
            @php
                // Son 8 haberi al
                $recentNews = \App\Models\News::published()
                    ->orderBy('published_at', 'desc')
                    ->take(8)
                    ->get();
            @endphp
            
            <div class="news-grid">
                @foreach($recentNews as $item)
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
                                <a href="{{ route('news.show', $item->slug) }}" class="text-inherit hover:text-[#00352b]">
                                    {!! html_entity_decode($item->title) !!}
                                </a>
                            </h3>
                            
                            <p class="news-card-excerpt">
                                {!! Str::limit(html_entity_decode(strip_tags($item->content)), 100) !!}
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
        </div>

        <!-- Her kategori için ayrı bölüm -->
            @foreach($categories as $category)
            @php
                // Her kategoriden son 8 haberi al
                $categoryNews = \App\Models\News::published()
                    ->whereHas('categories', function($query) use ($category) {
                        $query->where('news_categories.id', $category->id);
                    })
                    ->orderBy('published_at', 'desc')
                    ->take(8)
                    ->get();
            @endphp
            
            @if($categoryNews->count() > 0)
            <div class="category-section">
                    <h2 class="category-section-title">
                        <i class="fas fa-newspaper"></i> {{ $category->name }}
                        <a href="{{ route('news.category', $category->slug) }}" class="view-all-link">
                            Tümünü Gör <i class="fas fa-chevron-right text-xs"></i>
                        </a>
                    </h2>
                    
                    <div class="news-grid">
                        @foreach($categoryNews as $item)
                            <article class="news-card">
                                <div class="news-card-image-container">
                                    @if($item->image)
                                        <img src="{{ str_replace('/storage/storage/', '/storage/', $item->image) }}" class="news-card-image" alt="{{ $item->title }} haber görseli">
                                    @else
                                        <i class="fas fa-newspaper"></i>
                                    @endif
                                </div>
                                
                                <div class="news-card-content">
                                    <span class="news-card-category">{{ $category->name }}</span>
                                    
                                    <h3 class="news-card-title">
                                        <a href="{{ route('news.show', $item->slug) }}" class="text-inherit hover:text-[#00352b]">
                                            {!! html_entity_decode($item->title) !!}
                                        </a>
                                    </h3>
                                    
                                    <p class="news-card-excerpt">
                                        {!! Str::limit(html_entity_decode(strip_tags($item->content)), 100) !!}
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
                </div>
            @endif
        @endforeach
        
        <!-- Tüm kategorilerde haber yoksa -->
        @if($categories->isEmpty() || $news->isEmpty())
            <div class="bg-white p-8 rounded-lg text-center shadow-md">
                <i class="fas fa-newspaper text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Haber Bulunamadı</h3>
                <p class="text-gray-500">Henüz haber bulunmamaktadır.</p>
            </div>
        @endif
    </div>
</section>
@endsection 