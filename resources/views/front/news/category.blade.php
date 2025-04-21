@extends('layouts.front')

@section('title', $category->name . ' Haberleri - ' . config('app.name'))

@section('meta_description', $category->description ?? $category->name . ' kategorisindeki en güncel haberler ve gelişmeler.')

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
    }
    
    .news-content-area {
        grid-column: 1;
    }
    
    .news-sidebar {
        grid-column: 2;
    }
    
    .news-filters {
        background-color: white;
        border-radius: 12px;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
        padding: 20px;
        margin-bottom: 30px;
    }
    
    .news-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }
    
    .news-card {
        background-color: white;
        border-radius: 12px;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        transition: transform 0.2s ease-in-out;
    }
    
    .news-card:hover {
        transform: translateY(-5px);
    }
    
    .news-card-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }
    
    .news-card-content {
        padding: 1.5rem;
    }
    
    .news-card-category {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        background-color: var(--primary-light);
        color: var(--primary-color);
        border-radius: 9999px;
        font-size: 0.875rem;
        margin-bottom: 0.75rem;
    }
    
    .news-card-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.75rem;
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
    }
    
    .news-card-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
        color: #9ca3af;
        font-size: 0.875rem;
        padding-top: 1rem;
        border-top: 1px solid #e5e7eb;
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
    
    @media (max-width: 1024px) {
        .news-grid {
            grid-template-columns: 1fr;
        }
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
        <div class="col-12">
            <h1 class="mb-4">{{ $category->name }} Haberleri</h1>
        </div>
    </div>

    <div class="row">
        @forelse($news as $item)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" class="card-img-top" alt="{{ $item->title }}">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $item->title }}</h5>
                        <p class="card-text text-muted">
                            {{ \Carbon\Carbon::parse($item->published_at)->format('d.m.Y') }}
                        </p>
                        <a href="{{ route('news.show', $item->slug) }}" class="btn btn-primary">Devamını Oku</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    Bu kategoride henüz haber bulunmamaktadır.
                </div>
            </div>
        @endforelse
    </div>

    <div class="row">
        <div class="col-12">
            {{ $news->links() }}
        </div>
    </div>
</div>
@endsection 