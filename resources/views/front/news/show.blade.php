@extends('layouts.front')

@section('title', $news->title)

@section('meta_description', $news->meta_description ?? Str::limit(strip_tags($news->content), 160))

@if($news->meta_keywords)
    @section('meta_keywords', $news->meta_keywords)
@endif

@if($news->image)
    @section('meta_image', $news->image)
@endif

@section('css')
<style>
    .news-header {
        position: relative;
        background: linear-gradient(to right, #00352b, #20846c);
        padding: 2rem 0;
        overflow: visible;
    }
    
    .pattern-overlay {
        position: absolute;
        inset: 0;
        opacity: 0.1;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='40' height='40' viewBox='0 0 40 40'%3E%3Cpath d='M0 20 L40 20 M20 0 L20 40' stroke='white' stroke-width='1' fill='none' /%3E%3C/svg%3E");
        pointer-events: none;
        z-index: 1;
    }
    
    .news-detail-container {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 1rem;
        position: relative;
        z-index: 5;
    }
    
    .news-detail-grid {
        display: grid;
        grid-template-columns: 3fr 1fr;
        gap: 2rem;
        margin-top: 2rem;
        position: relative;
        z-index: 10;
    }
    
    .news-main-content {
        background-color: white;
        border-radius: 1rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        overflow: hidden;
        position: relative;
    }
    
    .news-image-container {
        width: 100%;
        height: auto;
        position: relative;
        overflow: hidden;
        max-height: 500px;
    }
    
    .news-image {
        width: 100%;
        height: auto;
        object-fit: cover;
        max-height: 500px;
    }
    
    .news-image-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 1.5rem;
        background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
        color: white;
    }
    
    .news-content-wrapper {
        padding: 2rem;
    }
    
    .news-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #eaeaea;
        color: #6b7280;
        font-size: 0.875rem;
    }
    
    .news-meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .news-content {
        line-height: 1.8;
        color: #374151;
        font-size: 1.125rem;
    }
    
    .news-content p {
        margin-bottom: 1.5rem;
    }
    
    .news-content h2 {
        font-size: 1.75rem;
        font-weight: 600;
        color: #00352b;
        margin: 2rem 0 1rem;
    }
    
    .news-content img {
        max-width: 100%;
        height: auto;
        border-radius: 0.5rem;
        margin: 1.5rem 0;
    }
    
    .news-sidebar {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
        margin-top: 0;
    }
    
    .news-sidebar-section {
        background-color: white;
        border-radius: 1rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }
    
    .sidebar-header {
        padding: 1.25rem;
        border-bottom: 1px solid #eaeaea;
    }
    
    .sidebar-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #00352b;
        margin: 0;
    }
    
    .sidebar-content {
        padding: 1.25rem;
    }
    
    .related-news-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .related-news-item {
        display: flex;
        gap: 1rem;
    }
    
    .related-news-image {
        width: 80px;
        height: 60px;
        object-fit: cover;
        border-radius: 0.5rem;
    }
    
    .related-news-info {
        flex: 1;
    }
    
    .related-news-title {
        font-size: 0.875rem;
        font-weight: 500;
        color: #1f2937;
        margin-bottom: 0.25rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .related-news-date {
        font-size: 0.75rem;
        color: #6b7280;
    }
    
    .category-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
    }
    
    .category-item {
        background-color: #f3f4f6;
        padding: 0.5rem 1rem;
        border-radius: 2rem;
        font-size: 0.875rem;
        color: #374151;
        transition: all 0.3s ease;
    }
    
    .category-item:hover {
        background-color: #00352b;
        color: white;
    }
    
    .tag-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .tag-item {
        background-color: #f3f4f6;
        padding: 0.25rem 0.75rem;
        border-radius: 2rem;
        font-size: 0.75rem;
        color: #6b7280;
        transition: all 0.3s ease;
    }
    
    .tag-item:hover {
        background-color: #e6a23c;
        color: white;
    }
    
    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.75rem;
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
    
    .news-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: white;
        margin-bottom: 0;
        line-height: 1.2;
    }
    
    /* Belgeler Bölümü Stilleri */
    .documents-section {
        border-top: 2px solid #f3f4f6;
        padding-top: 2rem;
        margin-top: 2rem;
    }
    
    .documents-header {
        margin-bottom: 1.5rem;
    }
    
    .documents-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #00352b;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
    }
    
    .documents-count {
        background-color: #e6a23c;
        color: white;
        font-size: 0.875rem;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        margin-left: 0.75rem;
        font-weight: 500;
    }
    
    .documents-subtitle {
        color: #6b7280;
        font-size: 0.875rem;
        margin: 0;
    }
    
    .documents-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1rem;
    }
    
    .document-card {
        background-color: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 0.75rem;
        padding: 1.25rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .document-card:hover {
        background-color: #e9ecef;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        border-color: #e6a23c;
    }
    
    .document-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.5rem;
        background-color: white;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        flex-shrink: 0;
    }
    
    .document-icon i {
        font-size: 1.5rem;
    }
    
    .document-info {
        flex: 1;
        min-width: 0;
    }
    
    .document-name {
        font-size: 1rem;
        font-weight: 600;
        color: #1f2937;
        margin: 0 0 0.25rem 0;
        line-height: 1.3;
    }
    
    .document-description {
        font-size: 0.875rem;
        color: #6b7280;
        margin: 0 0 0.5rem 0;
        line-height: 1.4;
    }
    
    .document-meta {
        display: flex;
        flex-direction: column;
        gap: 0.125rem;
    }
    
    .document-filename,
    .document-size {
        font-size: 0.75rem;
        color: #9ca3af;
    }
    
    .document-actions {
        flex-shrink: 0;
    }
    
    .document-download-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background-color: #e6a23c;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }
    
    .document-download-btn:hover {
        background-color: #d69e2e;
        color: white;
        text-decoration: none;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(230, 162, 60, 0.3);
    }
    
    /* Dosya türü renkleri */
    .fa-file-pdf { color: #dc3545; }
    .fa-file-word { color: #2b579a; }
    .fa-file-excel { color: #217346; }
    .fa-file-powerpoint { color: #d24726; }
    .fa-file-alt { color: #6c757d; }
    .fa-file-archive { color: #ffc107; }

    @media (max-width: 768px) {
        .news-detail-grid {
            grid-template-columns: 1fr;
        }
        
        .news-title {
            font-size: 1.5rem;
        }
        
        .news-content-wrapper {
            padding: 1.5rem;
        }
        
        .news-header {
            padding: 1.5rem 0;
        }
        
        .documents-grid {
            grid-template-columns: 1fr;
        }
        
        .document-card {
            flex-direction: column;
            text-align: center;
            gap: 0.75rem;
        }
        
        .document-info {
            text-align: center;
        }
        
        .document-meta {
            align-items: center;
        }
    }
</style>
@endsection

@section('content')
<!-- Haber Başlık Alanı -->
<header class="news-header">
    <div class="pattern-overlay"></div>
    <div class="news-detail-container">
        <nav class="breadcrumb">
                                    <a href="{{ route('front.home') }}" class="breadcrumb-link">Ana Sayfa</a>
            <span class="breadcrumb-separator">/</span>
            <a href="{{ route('news.index') }}" class="breadcrumb-link">Haberler</a>
            @if($news->category)
                <span class="breadcrumb-separator">/</span>
                <a href="{{ route('news.category', $news->category->slug) }}" class="breadcrumb-link">{{ $news->category->name }}</a>
            @endif
            <span class="breadcrumb-separator">/</span>
            <span class="breadcrumb-item">{!! html_entity_decode($news->title) !!}</span>
        </nav>
        
        <h1 class="news-title">{!! html_entity_decode($news->title) !!}</h1>
    </div>
</header>

<!-- Haber İçerik Alanı -->
<div class="news-detail-container">
    <div class="news-detail-grid">
        <!-- Ana İçerik -->
        <div>
            <div class="news-main-content">
                @if($news->image)
                <div class="news-image-container">
                    <img src="{{ str_replace('/storage/storage/', '/storage/', $news->image) }}" class="news-image" alt="{{ $news->title }}">
                </div>
                @endif
                
                <div class="news-content-wrapper">
                    <div class="news-meta">
                        <div class="news-meta-item">
                            <i class="fas fa-calendar-alt"></i>
                            <span>{{ \Carbon\Carbon::parse($news->published_at)->format('d.m.Y H:i') }}</span>
                        </div>
                        
                        @if($news->category)
                        <div class="news-meta-item">
                            <i class="fas fa-folder"></i>
                            <a href="{{ route('news.category', $news->category->slug) }}">{{ $news->category->name }}</a>
                        </div>
                        @endif
                        
                        <div class="news-meta-item">
                            <i class="fas fa-eye"></i>
                            <span>{{ $news->views ?? 0 }} görüntülenme</span>
                        </div>
                    </div>
                    
                    <div class="news-content">
                        {!! html_entity_decode($news->content) !!}
                    </div>

                    <!-- Belgeler Bölümü -->
                    @if($news->documents && $news->documents->count() > 0)
                    <div class="documents-section mt-8">
                        <div class="documents-header">
                            <h3 class="documents-title">
                                <i class="fas fa-file-alt mr-2"></i>
                                İlgili Belgeler
                                <span class="documents-count">{{ $news->documents->count() }}</span>
                            </h3>
                            <p class="documents-subtitle">Bu haber ile ilgili belgeleri indirebilirsiniz.</p>
                        </div>
                        
                        <div class="documents-grid">
                            @foreach($news->documents as $document)
                            <div class="document-card">
                                <div class="document-icon">
                                    <i class="{{ $document->icon_class }}"></i>
                                </div>
                                <div class="document-info">
                                    <h4 class="document-name">{{ $document->name }}</h4>
                                    @if($document->description)
                                        <p class="document-description">{{ $document->description }}</p>
                                    @endif
                                    <div class="document-meta">
                                        <span class="document-filename">{{ $document->file_name }}</span>
                                        <span class="document-size">{{ $document->formatted_size }}</span>
                                    </div>
                                </div>
                                <div class="document-actions">
                                    <a href="{{ route('news.documents.download', [$news->slug, $document->id]) }}" 
                                       class="document-download-btn">
                                        <i class="fas fa-download"></i>
                                        İndir
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    @if($news->meta_keywords)
                    <div class="tag-list mt-6">
                        @foreach(explode(',', $news->meta_keywords) as $tag)
                            <span class="tag-item">{{ trim($tag) }}</span>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Yan Sütun -->
        <div class="news-sidebar">
            <!-- Kategoriler -->
            <div class="news-sidebar-section">
                <div class="sidebar-header">
                    <h3 class="sidebar-title">Kategoriler</h3>
                </div>
                <div class="sidebar-content">
                    <div class="category-list">
                        @php
                            $categories = \App\Models\NewsCategory::where('is_active', true)->get();
                        @endphp
                        
                        @foreach($categories as $category)
                            <a href="{{ route('news.category', $category->slug) }}" class="category-item">{{ $category->name }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <!-- Popüler Haberler -->
            <div class="news-sidebar-section">
                <div class="sidebar-header">
                    <h3 class="sidebar-title">Popüler Haberler</h3>
                </div>
                <div class="sidebar-content">
                    <div class="related-news-list">
                        @php
                            $popularNews = \App\Models\News::published()->orderBy('views', 'desc')->take(5)->get();
                        @endphp
                        
                        @foreach($popularNews as $item)
                            <a href="{{ route('news.show', $item->slug) }}" class="related-news-item">
                                @if($item->image)
                                    <img src="{{ str_replace('/storage/storage/', '/storage/', $item->image) }}" class="related-news-image" alt="{{ $item->title }}">
                                @else
                                    <div class="related-news-image bg-gray-200 flex items-center justify-center">
                                        <i class="fas fa-newspaper text-gray-400"></i>
                                    </div>
                                @endif
                                
                                <div class="related-news-info">
                                    <h4 class="related-news-title">{{ $item->title }}</h4>
                                    <p class="related-news-date">{{ \Carbon\Carbon::parse($item->published_at)->format('d.m.Y') }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <!-- İlgili Haberler -->
            <div class="news-sidebar-section">
                <div class="sidebar-header">
                    <h3 class="sidebar-title">İlgili Haberler</h3>
                </div>
                <div class="sidebar-content">
                    <div class="related-news-list">
                        @php
                            $relatedNews = \App\Models\News::published()
                                ->where('id', '!=', $news->id)
                                ->where(function($query) use ($news) {
                                    if ($news->category_id) {
                                        $query->where('category_id', $news->category_id);
                                    }
                                })
                                ->orderBy('published_at', 'desc')
                                ->take(3)
                                ->get();
                        @endphp
                        
                        @foreach($relatedNews as $item)
                            <a href="{{ route('news.show', $item->slug) }}" class="related-news-item">
                                @if($item->image)
                                    <img src="{{ str_replace('/storage/storage/', '/storage/', $item->image) }}" class="related-news-image" alt="{{ $item->title }}">
                                @else
                                    <div class="related-news-image bg-gray-200 flex items-center justify-center">
                                        <i class="fas fa-newspaper text-gray-400"></i>
                                    </div>
                                @endif
                                
                                <div class="related-news-info">
                                    <h4 class="related-news-title">{!! html_entity_decode($item->title) !!}</h4>
                                    <p class="related-news-date">{{ \Carbon\Carbon::parse($item->published_at)->format('d.m.Y') }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 