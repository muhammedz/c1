@extends('layouts.front')

@section('title', $archive_date->format('F Y') . ' Arşivi')

@section('meta_description', $archive_date->format('F Y') . ' ayına ait tüm haberler ve gelişmeler.')

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
    
    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 0.5rem;
    }
    
    .calendar-day {
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.875rem;
        color: #6b7280;
        border-radius: 0.5rem;
    }
    
    .calendar-day.has-news {
        background-color: var(--primary-light);
        color: var(--primary-color);
        font-weight: 600;
    }
    
    .calendar-day.today {
        border: 2px solid var(--primary-color);
        color: var(--primary-color);
        font-weight: 600;
    }
    
    .calendar-day.other-month {
        opacity: 0.3;
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
<div class="news-layout-container">
    <!-- Hero Bölümü -->
    <div class="relative bg-gradient-to-r from-[#00352b] to-[#20846c] rounded-xl overflow-hidden mb-8">
        <div class="absolute inset-0 opacity-20">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" preserveAspectRatio="none">
                <defs>
                    <pattern id="hero-pattern" width="40" height="40" patternUnits="userSpaceOnUse">
                        <path d="M0 20 L40 20 M20 0 L20 40" stroke="currentColor" stroke-width="1" fill="none" />
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#hero-pattern)" />
            </svg>
        </div>
        
        <div class="relative z-10 px-8 py-12 text-white">
            <div class="flex items-center gap-2 text-sm mb-2">
                <a href="{{ route('front.news.index') }}" class="text-white/80 hover:text-white transition-colors">Haberler</a>
                <span class="text-white/60">/</span>
                <span>Arşiv</span>
                <span class="text-white/60">/</span>
                <span>{{ $archive_date->format('F Y') }}</span>
            </div>
            <h1 class="text-4xl font-bold mb-4">{{ $archive_date->format('F Y') }} Arşivi</h1>
            <p class="text-white/80 max-w-2xl">{{ $archive_date->format('F Y') }} ayına ait tüm haberler ve gelişmeler.</p>
        </div>
    </div>
    
    <div class="news-grid-layout">
        <div class="news-content-area">
            <!-- Filtreler -->
            <div class="news-filters">
                <div class="flex flex-wrap items-center gap-4">
                    <div class="flex-1">
                        <label for="sort" class="block text-sm font-medium text-gray-700 mb-1">Sıralama</label>
                        <select id="sort" name="sort" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-color focus:ring-primary-color">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>En Yeni</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>En Eski</option>
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>En Popüler</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Haberler Grid -->
            <div class="news-grid">
                @foreach($news as $item)
                <article class="news-card">
                    @if($item->image)
                    <img src="{{ asset($item->image) }}" alt="{{ $item->title }}" class="news-card-image">
                    @endif
                    
                    <div class="news-card-content">
                        @if($item->category)
                        <span class="news-card-category">{{ $item->category->name }}</span>
                        @endif
                        
                        <h2 class="news-card-title">
                            <a href="{{ route('front.news.show', $item->slug) }}" class="hover:text-primary-color transition-colors">
                                {{ $item->title }}
                            </a>
                        </h2>
                        
                        <p class="news-card-excerpt">
                            {{ Str::limit(strip_tags($item->content), 150) }}
                        </p>
                        
                        <div class="news-card-meta">
                            <div class="flex items-center gap-2">
                                <span class="material-icons text-gray-400 text-base">calendar_today</span>
                                <span>{{ $item->created_at->format('d.m.Y') }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="material-icons text-gray-400 text-base">visibility</span>
                                <span>{{ $item->views ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                </article>
                @endforeach
            </div>
            
            <!-- Sayfalama -->
            <div class="mt-8">
                {{ $news->links() }}
            </div>
        </div>
        
        <aside class="news-sidebar">
            <!-- Takvim -->
            <div class="news-sidebar-section">
                <h3 class="news-sidebar-title">{{ $archive_date->format('F Y') }} Takvimi</h3>
                <div class="calendar-grid mb-2">
                    <div class="calendar-day font-medium">Pt</div>
                    <div class="calendar-day font-medium">Sa</div>
                    <div class="calendar-day font-medium">Ça</div>
                    <div class="calendar-day font-medium">Pe</div>
                    <div class="calendar-day font-medium">Cu</div>
                    <div class="calendar-day font-medium">Ct</div>
                    <div class="calendar-day font-medium">Pa</div>
                    
                    @foreach($calendar as $day)
                    <div class="calendar-day {{ $day['classes'] }}">
                        {{ $day['day'] }}
                    </div>
                    @endforeach
                </div>
                
                <div class="flex items-center justify-between mt-4">
                    <a href="{{ route('front.news.archive', ['year' => $prev_month->year, 'month' => $prev_month->month]) }}" 
                       class="flex items-center gap-1 text-sm text-gray-600 hover:text-primary-color transition-colors">
                        <span class="material-icons text-base">chevron_left</span>
                        <span>{{ $prev_month->format('F Y') }}</span>
                    </a>
                    
                    <a href="{{ route('front.news.archive', ['year' => $next_month->year, 'month' => $next_month->month]) }}"
                       class="flex items-center gap-1 text-sm text-gray-600 hover:text-primary-color transition-colors">
                        <span>{{ $next_month->format('F Y') }}</span>
                        <span class="material-icons text-base">chevron_right</span>
                    </a>
                </div>
            </div>
            
            <!-- Kategoriler -->
            <div class="news-sidebar-section">
                <h3 class="news-sidebar-title">Haber Kategorileri</h3>
                <div class="space-y-2">
                    @foreach($categories as $category)
                    <a href="{{ route('front.news.category', $category->slug) }}" class="flex items-center justify-between py-2 px-3 rounded-lg hover:bg-gray-50 transition-colors">
                        <span class="text-gray-700">{{ $category->name }}</span>
                        <span class="bg-primary-light text-primary-dark px-2 py-1 rounded-full text-sm">
                            {{ $category->news_count }}
                        </span>
                    </a>
                    @endforeach
                </div>
            </div>
            
            <!-- Arşiv -->
            <div class="news-sidebar-section">
                <h3 class="news-sidebar-title">Haber Arşivi</h3>
                <div class="space-y-2">
                    @foreach($archives as $archive)
                    <a href="{{ route('front.news.archive', ['year' => $archive->year, 'month' => $archive->month]) }}" 
                       class="flex items-center justify-between py-2 px-3 rounded-lg hover:bg-gray-50 transition-colors {{ $archive->year == $archive_date->year && $archive->month == $archive_date->month ? 'bg-primary-light' : '' }}">
                        <span class="text-gray-700">{{ $archive->month_name }} {{ $archive->year }}</span>
                        <span class="bg-primary-light text-primary-dark px-2 py-1 rounded-full text-sm">
                            {{ $archive->total }}
                        </span>
                    </a>
                    @endforeach
                </div>
            </div>
        </aside>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sıralama değişikliklerini dinle
    const sortSelect = document.getElementById('sort');
    
    sortSelect.addEventListener('change', function() {
        const params = new URLSearchParams(window.location.search);
        
        if (this.value) {
            params.set('sort', this.value);
        } else {
            params.delete('sort');
        }
        
        window.location.href = `${window.location.pathname}?${params.toString()}`;
    });
});
</script>
@endpush
@endsection 