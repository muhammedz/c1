@extends('layouts.front')

@section('title', 'Projelerimiz')

@section('css')
<style>
    /* Hero Bölümü */
    .projects-header {
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
    
    .projects-container {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 1rem;
        position: relative;
        z-index: 5;
    }
    
    .content-container {
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
    
    .projects-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: white;
        margin-bottom: 0.5rem;
        line-height: 1.2;
    }

    .projects-description {
        color: rgba(255, 255, 255, 0.8);
        font-size: 1rem;
        max-width: 700px;
    }
    
    .projects-category-filter {
        background-color: white;
        border-radius: 0.75rem;
        padding: 1.25rem;
        margin-top: 3rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }
    
    .category-filter-title {
        color: #1f2937;
        font-size: 0.875rem;
        margin-bottom: 0.75rem;
        font-weight: 500;
    }
    
    .category-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .category-item {
        background-color: #f3f4f6;
        padding: 0.5rem 1rem;
        border-radius: 2rem;
        font-size: 0.875rem;
        color: #4b5563;
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }
    
    .category-item:hover {
        background-color: #e5e7eb;
    }
    
    .category-item.active {
        background-color: #00352b;
        color: white;
    }
    
    .projects-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 2rem;
    }
    
    .project-card {
        background-color: white;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        transition: all 0.3s ease;
    }
    
    .project-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }
    
    .project-image-container {
        position: relative;
        aspect-ratio: 16 / 9;
        overflow: hidden;
    }
    
    .project-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: all 0.5s ease;
    }
    
    .project-card:hover .project-image {
        transform: scale(1.05);
    }
    
    .project-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0));
        color: white;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 1.5rem;
    }
    
    .project-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }
    
    .project-status {
        font-size: 0.875rem;
        opacity: 0.9;
    }
    
    .project-info {
        padding: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .project-category {
        font-size: 0.875rem;
        color: #6b7280;
    }
    
    .project-link {
        font-size: 0.875rem;
        font-weight: 500;
        color: #00352b;
    }
    
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background-color: white;
        border-radius: 1rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
    }
    
    .empty-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }
    
    .empty-text {
        color: #6b7280;
    }
    
    @media (max-width: 1024px) {
        .projects-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
    
    @media (max-width: 768px) {
        .projects-title {
            font-size: 1.5rem;
        }
        
        .projects-header {
            padding: 1.5rem 0;
        }
    }
    
    @media (max-width: 640px) {
        .projects-grid {
            grid-template-columns: minmax(0, 1fr);
        }
    }
</style>
@endsection

@section('content')
<!-- Projeler Başlık Alanı -->
<header class="projects-header">
    <div class="pattern-overlay"></div>
    <div class="decorative-shape-1"></div>
    <div class="decorative-shape-2"></div>
    <div class="projects-container">
        <nav class="breadcrumb">
                                    <a href="{{ route('front.home') }}" class="breadcrumb-link">Ana Sayfa</a>
            <span class="breadcrumb-separator">/</span>
            <span class="breadcrumb-item">Projeler</span>
        </nav>
        
        <h1 class="projects-title">Projelerimiz</h1>
        <p class="projects-description">Belediyemizin gerçekleştirdiği ve devam eden tüm projeler hakkında ayrıntılı bilgi edinebilirsiniz.</p>
    </div>
</header>

<!-- Projeler İçerik Alanı -->
<div class="projects-container content-container">
    <!-- Projeler Listesi -->
    @if(isset($projects) && $projects->count() > 0)
        <!-- Kategori Filtreleme -->
        @if(isset($categories) && $categories->count() > 0)
        <div class="projects-category-filter">
            <div class="category-filter-title">Kategoriye göre filtrele:</div>
            <div class="category-list">
                <a href="{{ route('front.projects') }}" class="category-item {{ !isset($selectedCategory) ? 'active' : '' }}">
                    Tümü
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('front.projects', ['category' => $category->id]) }}" 
                       class="category-item {{ isset($selectedCategory) && $selectedCategory->id == $category->id ? 'active' : '' }}">
                        {{ $category->name }}
                        <span class="ml-1">({{ $category->activeProjects->count() }})</span>
                    </a>
                @endforeach
            </div>
        </div>
        @endif

        <div class="projects-grid">
            @foreach($projects as $project)
                <a href="{{ route('front.projects.detail', $project->slug) }}" class="project-card">
                    <div class="project-image-container">
                        <img src="{{ $project->cover_image_url }}" alt="{{ $project->title }} proje görseli" class="project-image" @if($lazyLoadingEnabled == '1') loading="lazy" @endif>
                        <div class="project-overlay">
                            <h3 class="project-title">{{ $project->title }}</h3>
                            <p class="project-status">{{ $project->status_text }}</p>
                        </div>
                    </div>
                    <div class="project-info">
                        <span class="project-category">
                            {{ $project->category ? $project->category->name : 'Genel' }}
                        </span>
                        <span class="project-link">Detaylar</span>
                    </div>
                </a>
            @endforeach
        </div>
        
        <!-- Pagination -->
        @if($projects->hasPages())
            <div class="mt-10">
                {{ $projects->links() }}
            </div>
        @endif
    @else
        <div class="empty-state">
            <h2 class="empty-title">Proje bulunamadı</h2>
            <p class="empty-text">Seçili kategoride henüz proje bulunmuyor.</p>
        </div>
    @endif
</div>
@endsection 