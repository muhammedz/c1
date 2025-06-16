@extends('layouts.front')

@section('title', isset($project) ? $project->title : 'Proje Detayı')

@section('css')
<style>
    /* Hero Bölümü */
    .project-header {
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
    
    .project-detail-container {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 1rem;
        position: relative;
        z-index: 5;
    }
    
    .project-detail-grid {
        display: grid;
        grid-template-columns: 1fr 3fr;
        gap: 2rem;
        margin-top: 2rem;
        position: relative;
        z-index: 10;
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
    
    .project-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: white;
        margin-bottom: 0;
        line-height: 1.2;
    }

    .project-meta {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.875rem;
    }

    .project-meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Ana İçerik Bölümü */
    .project-main-content {
        background-color: white;
        border-radius: 1rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        overflow: hidden;
        position: relative;
    }
    
    .project-image-container {
        width: 100%;
        height: auto;
        position: relative;
        overflow: hidden;
        max-height: 500px;
    }
    
    .project-image {
        width: 100%;
        height: auto;
        object-fit: cover;
        max-height: 500px;
    }
    
    .project-content-wrapper {
        padding: 2rem;
    }
    
    .project-meta-details {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #eaeaea;
        color: #6b7280;
        font-size: 0.875rem;
    }
    
    .project-meta-details-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .project-content {
        line-height: 1.8;
        color: #374151;
        font-size: 1.125rem;
    }
    
    .project-content p {
        margin-bottom: 1.5rem;
    }
    
    .project-content h2 {
        font-size: 1.75rem;
        font-weight: 600;
        color: #00352b;
        margin: 2rem 0 1rem;
    }
    
    .project-content img {
        max-width: 100%;
        height: auto;
        border-radius: 0.5rem;
        margin: 1.5rem 0;
    }

    /* Yan Menü Bölümü */
    .project-sidebar {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
        margin-top: 0;
    }
    
    .project-sidebar-section {
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
    
    .sidebar-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .sidebar-item {
        display: flex;
        align-items: center;
        padding: 0.75rem;
        border-radius: 0.5rem;
        transition: all 0.2s ease;
        color: #4b5563;
        background-color: #f9fafb;
    }
    
    .sidebar-item:hover {
        background-color: #f3f4f6;
        color: #00352b;
    }
    
    .sidebar-item.active {
        background-color: #00352b;
        color: white;
    }
    
    .sidebar-item-icon {
        margin-right: 0.75rem;
        width: 1.25rem;
        font-size: 0.875rem;
        text-align: center;
    }
    
    .related-projects-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .related-project-item {
        display: flex;
        gap: 1rem;
    }
    
    .related-project-image {
        width: 80px;
        height: 60px;
        object-fit: cover;
        border-radius: 0.5rem;
    }
    
    .related-project-info {
        flex: 1;
    }
    
    .related-project-title {
        font-size: 0.875rem;
        font-weight: 500;
        color: #1f2937;
        margin-bottom: 0.25rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .related-project-category {
        font-size: 0.75rem;
        color: #6b7280;
    }

    /* Galeri Bölümü */
    .gallery-section {
        margin-top: 2.5rem;
    }

    .gallery-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #00352b;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #f3f4f6;
    }

    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1rem;
    }

    .gallery-item {
        position: relative;
        aspect-ratio: 16 / 9;
        overflow: hidden;
        border-radius: 0.5rem;
    }

    .gallery-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .gallery-item:hover .gallery-image {
        transform: scale(1.05);
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
    
    .not-found {
        text-align: center;
        padding: 4rem 2rem;
        background-color: white;
        border-radius: 1rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
    }
    
    .not-found-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 1rem;
    }
    
    .not-found-text {
        color: #6b7280;
        margin-bottom: 2rem;
    }
    
    .not-found-button {
        display: inline-block;
        background-color: #00352b;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .not-found-button:hover {
        background-color: #00483c;
    }
    
    @media (max-width: 1024px) {
        .gallery-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
    
    @media (max-width: 768px) {
        .project-detail-grid {
            grid-template-columns: 1fr;
        }
        
        .project-title {
            font-size: 1.5rem;
        }
        
        .project-header {
            padding: 1.5rem 0;
        }
        
        .project-content-wrapper {
            padding: 1.5rem;
        }
    }
    
    @media (max-width: 640px) {
        .gallery-grid {
            grid-template-columns: minmax(0, 1fr);
        }
        
        .project-meta {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
    }
</style>
@endsection

@section('content')
@if(isset($project))
    <!-- Proje Başlık Alanı -->
    <header class="project-header">
        <div class="pattern-overlay"></div>
        <div class="decorative-shape-1"></div>
        <div class="decorative-shape-2"></div>
        <div class="project-detail-container">
            <nav class="breadcrumb">
                                        <a href="{{ route('front.home') }}" class="breadcrumb-link">Ana Sayfa</a>
                <span class="breadcrumb-separator">/</span>
                <a href="{{ route('front.projects') }}" class="breadcrumb-link">Projeler</a>
                @if($project->category)
                    <span class="breadcrumb-separator">/</span>
                    <a href="{{ route('front.projects', ['category' => $project->category->id]) }}" class="breadcrumb-link">{{ $project->category->name }}</a>
                @endif
                <span class="breadcrumb-separator">/</span>
                <span class="breadcrumb-item">{{ $project->title }}</span>
            </nav>
            
            <h1 class="project-title">{{ $project->title }}</h1>
        </div>
    </header>

    <!-- Proje İçerik Alanı -->
    <div class="project-detail-container">
        <div class="project-detail-grid">
            <!-- Yan Menü - Sol tarafta -->
            <div class="project-sidebar">
                <!-- Proje Bilgileri -->
                <div class="project-sidebar-section">
                    <div class="sidebar-header">
                        <h3 class="sidebar-title">Proje Bilgileri</h3>
                    </div>
                    <div class="sidebar-content">
                        <div class="sidebar-list">
                            <div class="sidebar-item">
                                <span class="sidebar-item-icon"><i class="fas fa-tag"></i></span>
                                <span>Durum: {{ $project->status_text }}</span>
                            </div>
                            @if($project->category)
                            <div class="sidebar-item">
                                <span class="sidebar-item-icon"><i class="fas fa-folder"></i></span>
                                <span>Kategori: {{ $project->category->name }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Kategoriler -->
                <div class="project-sidebar-section">
                    <div class="sidebar-header">
                        <h3 class="sidebar-title">Kategoriler</h3>
                    </div>
                    <div class="sidebar-content">
                        <div class="category-list">
                            @php
                                $categories = \App\Models\ProjectCategory::where('is_active', true)->get();
                            @endphp
                            
                            @foreach($categories as $category)
                                <a href="{{ route('front.projects', ['category' => $category->id]) }}" class="category-item">{{ $category->name }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <!-- Benzer Projeler -->
                @if(isset($relatedProjects) && $relatedProjects->count() > 0)
                <div class="project-sidebar-section">
                    <div class="sidebar-header">
                        <h3 class="sidebar-title">Benzer Projeler</h3>
                    </div>
                    <div class="sidebar-content">
                        <div class="related-projects-list">
                            @foreach($relatedProjects->take(3) as $relatedProject)
                                <a href="{{ route('front.projects.detail', $relatedProject->slug) }}" class="related-project-item">
                                    <img src="{{ $relatedProject->cover_image_url }}" class="related-project-image" alt="{{ $relatedProject->title }}">
                                    <div class="related-project-info">
                                        <h4 class="related-project-title">{{ $relatedProject->title }}</h4>
                                        <p class="related-project-category">{{ $relatedProject->category ? $relatedProject->category->name : 'Genel' }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>
            
            <!-- Ana İçerik - Sağ tarafta -->
            <div>
                <div class="project-main-content">
                    @if($project->cover_image_url)
                    <div class="project-image-container">
                        <img src="{{ $project->cover_image_url }}" class="project-image" alt="{{ $project->title }}">
                    </div>
                    @endif
                    
                    <div class="project-content-wrapper">
                        <div class="project-meta-details">
                            <div class="project-meta-details-item">
                                <i class="fas fa-calendar-alt"></i>
                                <span>Durum: {{ $project->status_text }}</span>
                            </div>
                            
                            @if($project->category)
                            <div class="project-meta-details-item">
                                <i class="fas fa-folder"></i>
                                <span>Kategori: {{ $project->category->name }}</span>
                            </div>
                            @endif
                        </div>
                        
                        <div class="project-content">
                            {!! $project->description !!}
                        </div>
                    </div>
                </div>
                
                @if($project->gallery && $project->gallery->count() > 0)
                <div class="gallery-section">
                    <h2 class="gallery-title">Proje Galerisi</h2>
                    <div class="gallery-grid">
                        @foreach($project->gallery as $image)
                        <div class="gallery-item">
                            <img src="{{ $image->image_url }}" alt="{{ $project->title }} görseli" class="gallery-image">
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
@else
    <div class="project-detail-container py-12">
        <div class="not-found">
            <h1 class="not-found-title">Proje bulunamadı</h1>
            <p class="not-found-text">Aradığınız proje bulunamadı veya kaldırılmış olabilir.</p>
            <a href="{{ route('front.projects') }}" class="not-found-button">Tüm Projeleri Görüntüle</a>
        </div>
    </div>
@endif
@endsection 