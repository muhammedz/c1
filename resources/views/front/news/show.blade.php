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
    :root {
        --header-height: 96px;
        --primary-color: #00352b;
        --primary-light: rgba(0, 53, 43, 0.1);
        --primary-dark: #002a22;
        --secondary-color: #20846c;
        --accent-color: #e6a23c;
    }
    
    .news-layout-container {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 1rem;
    }
    
    .news-grid-layout {
        display: grid;
        grid-template-columns: 1fr 300px;
        gap: 2rem;
        margin-top: 2rem;
        align-items: flex-start;
    }
    
    .news-content-area {
        grid-column: 1;
    }
    
    .news-sidebar {
        grid-column: 2;
    }
    
    .news-content-section {
        background-color: white;
        border-radius: 12px;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
        padding: 30px;
        margin-bottom: 30px;
        border-top: 4px solid var(--primary-color);
    }
    
    .news-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
        color: #6b7280;
        font-size: 0.875rem;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .news-image {
        width: 100%;
        height: auto;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }
    
    .news-content {
        line-height: 1.8;
        color: #374151;
    }
    
    .news-content p {
        margin-bottom: 1rem;
    }
    
    .news-content h2 {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--primary-color);
        margin: 2rem 0 1rem;
    }
    
    .news-content img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 1.5rem 0;
    }
    
    .news-sidebar-section {
        background-color: white;
        border-radius: 12px;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .news-sidebar-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--primary-light);
    }
    
    .related-news-item {
        display: flex;
        gap: 1rem;
        padding: 0.75rem 0;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .related-news-item:last-child {
        border-bottom: none;
    }
    
    .related-news-image {
        width: 80px;
        height: 60px;
        object-fit: cover;
        border-radius: 6px;
    }
    
    .related-news-content {
        flex: 1;
    }
    
    .related-news-title {
        font-size: 0.875rem;
        font-weight: 500;
        color: #1f2937;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .related-news-date {
        font-size: 0.75rem;
        color: #6b7280;
        margin-top: 0.25rem;
    }
    
    @media (max-width: 768px) {
        .news-grid-layout {
            grid-template-columns: 1fr;
        }
        
        .news-sidebar {
            grid-column: 1;
        }
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('news.index') }}">Haberler</a></li>
                    @if($news->category)
                        <li class="breadcrumb-item"><a href="{{ route('news.category', $news->category->slug) }}">{{ $news->category->name }}</a></li>
                    @endif
                    <li class="breadcrumb-item active" aria-current="page">{{ $news->title }}</li>
                </ol>
            </nav>

            <article>
                <h1 class="mb-4">{{ $news->title }}</h1>

                <div class="mb-4 text-muted">
                    <small>
                        Yayınlanma Tarihi: {{ \Carbon\Carbon::parse($news->published_at)->format('d.m.Y H:i') }}
                    </small>
                </div>

                @if($news->image)
                    <img src="{{ asset('storage/' . $news->image) }}" class="img-fluid rounded mb-4" alt="{{ $news->title }}">
                @endif

                <div class="content">
                    {!! $news->content !!}
                </div>

                @if($news->meta_keywords)
                    <div class="mt-4">
                        <h5>Etiketler:</h5>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach(explode(',', $news->meta_keywords) as $tag)
                                <span class="badge bg-secondary">{{ trim($tag) }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </article>
        </div>
    </div>
</div>
@endsection 