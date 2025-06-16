@extends('layouts.front')

@section('title', $pageTitle)
@section('meta_description', $pageDescription)

@section('css')
<style>
    .search-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .search-results-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        margin-bottom: 20px;
    }
    
    .search-results-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }
    
    .result-image {
        width: 120px;
        height: 120px;
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        border-radius: 12px;
        overflow: hidden;
        flex-shrink: 0;
    }
    
    .result-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .category-result {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        border-left: 4px solid #3b82f6;
    }
    
    .category-result:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }
    
    .search-stats {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        padding: 16px 24px;
        margin-top: 20px;
    }
    
    .search-filter {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        margin-bottom: 24px;
    }
    
    .filter-button {
        padding: 8px 16px;
        border-radius: 20px;
        border: 2px solid #e5e7eb;
        background: white;
        color: #6b7280;
        font-weight: 500;
        transition: all 0.2s ease;
        cursor: pointer;
        margin: 4px;
    }
    
    .filter-button.active {
        background: #3b82f6;
        border-color: #3b82f6;
        color: white;
    }
    
    .filter-button:hover:not(.active) {
        border-color: #3b82f6;
        color: #3b82f6;
    }
    
    .highlight {
        background: #fef3c7;
        padding: 2px 4px;
        border-radius: 4px;
        font-weight: 600;
    }
    
    .no-results {
        text-align: center;
        padding: 60px 20px;
    }
    
    .no-results-icon {
        width: 80px;
        height: 80px;
        background: #f3f4f6;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 24px;
        font-size: 2rem;
        color: #9ca3af;
    }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<section class="search-hero py-16 text-white">
    <div class="container max-w-[1235px] mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="mb-8">
            <ol class="flex items-center space-x-2 text-sm opacity-80">
                <li><a href="{{ route('home') }}" class="hover:text-white">Ana Sayfa</a></li>
                <li><i class="fas fa-chevron-right mx-2"></i></li>
                <li><a href="{{ route('guide.index') }}" class="hover:text-white">Rehber</a></li>
                <li><i class="fas fa-chevron-right mx-2"></i></li>
                <li class="text-white font-medium">Arama Sonuçları</li>
            </ol>
        </nav>
        
        <div class="text-center">
            <h1 class="text-3xl md:text-4xl font-bold mb-4">Arama Sonuçları</h1>
            <p class="text-lg md:text-xl opacity-90 mb-8">
                "<span class="font-semibold">{{ $query }}</span>" için bulunan sonuçlar
            </p>
            
            <!-- Yeni Arama -->
            <div class="max-w-2xl mx-auto">
                <form action="{{ route('guide.search') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" 
                               name="q" 
                               value="{{ $query }}"
                               placeholder="Yeni arama yapın..." 
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-800">
                    </div>
                    <button type="submit" 
                            class="px-8 py-3 bg-white bg-opacity-20 hover:bg-opacity-30 text-white rounded-lg font-semibold transition-all duration-200 backdrop-filter backdrop-blur-sm">
                        <i class="fas fa-search mr-2"></i>Ara
                    </button>
                </form>
            </div>
            
            <!-- Arama İstatistikleri -->
            <div class="search-stats">
                <div class="flex flex-col md:flex-row items-center justify-center gap-6 text-sm">
                    <div class="flex items-center">
                        <i class="fas fa-map-marker-alt mr-2"></i>
                        <span>{{ $places->total() }} yer bulundu</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-layer-group mr-2"></i>
                        <span>{{ $categories->count() }} kategori bulundu</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-clock mr-2"></i>
                        <span>{{ number_format(microtime(true) - LARAVEL_START, 3) }} saniye</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Arama Sonuçları -->
<section class="py-16 bg-gray-50">
    <div class="container max-w-[1235px] mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sol Kolon - Filtreler -->
            <div class="lg:col-span-1">
                <div class="search-filter">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                        <i class="fas fa-filter mr-2 text-blue-600"></i>Filtreler
                    </h3>
                    
                    <div class="mb-6">
                        <h4 class="font-semibold text-gray-700 mb-3">Sonuç Türü</h4>
                        <div class="space-y-2">
                            <button class="filter-button active w-full text-left" data-filter="all">
                                <i class="fas fa-list mr-2"></i>Tümü ({{ $places->total() + $categories->count() }})
                            </button>
                            <button class="filter-button w-full text-left" data-filter="places">
                                <i class="fas fa-map-marker-alt mr-2"></i>Yerler ({{ $places->total() }})
                            </button>
                            <button class="filter-button w-full text-left" data-filter="categories">
                                <i class="fas fa-layer-group mr-2"></i>Kategoriler ({{ $categories->count() }})
                            </button>
                        </div>
                    </div>
                    
                    @if($categories->count() > 0)
                        <div class="mb-6">
                            <h4 class="font-semibold text-gray-700 mb-3">Kategoriler</h4>
                            <div class="space-y-2">
                                @foreach($categories as $category)
                                    <a href="{{ route('guide.category', $category->slug) }}" 
                                       class="block p-2 rounded-lg hover:bg-gray-100 transition-colors">
                                        <div class="flex items-center">
                                            @if($category->icon)
                                                <i class="{{ $category->icon }} mr-2 text-blue-600"></i>
                                            @else
                                                <i class="fas fa-folder mr-2 text-blue-600"></i>
                                            @endif
                                            <span class="text-sm">{{ $category->name }}</span>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Sağ Kolon - Sonuçlar -->
            <div class="lg:col-span-3">
                @if($categories->count() > 0 || $places->count() > 0)
                    <!-- Kategori Sonuçları -->
                    @if($categories->count() > 0)
                        <div class="category-results mb-8" data-result-type="categories">
                            <h2 class="text-2xl font-bold text-gray-800 mb-6">
                                <i class="fas fa-layer-group mr-2 text-blue-600"></i>
                                Bulunan Kategoriler ({{ $categories->count() }})
                            </h2>
                            
                            <div class="space-y-4">
                                @foreach($categories as $category)
                                    <div class="category-result">
                                        <div class="flex items-start gap-4">
                                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                                @if($category->icon)
                                                    <i class="{{ $category->icon }} text-blue-600 text-xl"></i>
                                                @else
                                                    <i class="fas fa-folder text-blue-600 text-xl"></i>
                                                @endif
                                            </div>
                                            
                                            <div class="flex-1">
                                                <h3 class="text-lg font-bold text-gray-800 mb-2">
                                                    <a href="{{ route('guide.category', $category->slug) }}" 
                                                       class="hover:text-blue-600 transition-colors">
                                                        {!! str_ireplace($query, '<span class="highlight">'.$query.'</span>', $category->name) !!}
                                                    </a>
                                                </h3>
                                                
                                                @if($category->description)
                                                    <p class="text-gray-600 mb-3">
                                                        {!! str_ireplace($query, '<span class="highlight">'.$query.'</span>', Str::limit($category->description, 150)) !!}
                                                    </p>
                                                @endif
                                                
                                                <div class="flex items-center gap-4 text-sm text-gray-500">
                                                    <span>
                                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                                        {{ $category->active_places_count }} yer
                                                    </span>
                                                    <a href="{{ route('guide.category', $category->slug) }}" 
                                                       class="text-blue-600 hover:text-blue-800 font-medium">
                                                        Yerleri Gör <i class="fas fa-arrow-right ml-1"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    <!-- Yer Sonuçları -->
                    @if($places->count() > 0)
                        <div class="place-results" data-result-type="places">
                            <h2 class="text-2xl font-bold text-gray-800 mb-6">
                                <i class="fas fa-map-marker-alt mr-2 text-blue-600"></i>
                                Bulunan Yerler ({{ $places->total() }})
                            </h2>
                            
                            <div class="space-y-6">
                                @foreach($places as $place)
                                    <div class="search-results-card">
                                        <div class="p-6">
                                            <div class="flex flex-col md:flex-row gap-6">
                                                <!-- Yer Resmi -->
                                                <div class="result-image">
                                                    @if($place->featured_image_url)
                                                        <img src="{{ $place->featured_image_url }}" alt="{{ $place->title }}">
                                                    @else
                                                        <div class="w-full h-full flex items-center justify-center text-white text-2xl">
                                                            <i class="fas fa-building"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                
                                                <!-- Yer Bilgileri -->
                                                <div class="flex-1">
                                                    <div class="flex items-start justify-between mb-3">
                                                        <div>
                                                            <div class="text-sm text-blue-600 font-medium mb-1">
                                                                {{ $place->category->name }}
                                                            </div>
                                                            <h3 class="text-xl font-bold text-gray-800 mb-2">
                                                                <a href="{{ route('guide.place', [$place->category->slug, $place->slug]) }}" 
                                                                   class="hover:text-blue-600 transition-colors">
                                                                    {!! str_ireplace($query, '<span class="highlight">'.$query.'</span>', $place->title) !!}
                                                                </a>
                                                            </h3>
                                                        </div>
                                                    </div>
                                                    
                                                    @if($place->excerpt)
                                                        <p class="text-gray-600 mb-4 line-clamp-2">
                                                            {!! str_ireplace($query, '<span class="highlight">'.$query.'</span>', $place->excerpt) !!}
                                                        </p>
                                                    @endif
                                                    
                                                    <!-- İletişim Bilgileri -->
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
                                                        @if($place->address)
                                                            <div class="flex items-center text-sm text-gray-600">
                                                                <i class="fas fa-map-marker-alt mr-2 text-blue-600"></i>
                                                                <span class="line-clamp-1">{{ Str::limit($place->address, 40) }}</span>
                                                            </div>
                                                        @endif
                                                        
                                                        @if($place->phone)
                                                            <div class="flex items-center text-sm text-gray-600">
                                                                <i class="fas fa-phone mr-2 text-blue-600"></i>
                                                                <a href="tel:{{ $place->phone }}" class="hover:text-blue-600">{{ $place->phone }}</a>
                                                            </div>
                                                        @endif
                                                        
                                                        @if($place->working_hours)
                                                            <div class="flex items-center text-sm text-gray-600">
                                                                <i class="fas fa-clock mr-2 text-blue-600"></i>
                                                                <span class="line-clamp-1">{{ $place->working_hours }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    
                                                    <!-- Aksiyonlar -->
                                                    <div class="flex flex-wrap gap-3">
                                                        <a href="{{ route('guide.place', [$place->category->slug, $place->slug]) }}" 
                                                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                                                            Detaylar <i class="fas fa-arrow-right ml-2"></i>
                                                        </a>
                                                        
                                                        @if($place->phone)
                                                            <a href="tel:{{ $place->phone }}" 
                                                               class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors">
                                                                <i class="fas fa-phone mr-2"></i>Ara
                                                            </a>
                                                        @endif
                                                        
                                                        @if($place->maps_link)
                                                            <a href="{{ $place->maps_link }}" 
                                                               target="_blank"
                                                               class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-medium transition-colors">
                                                                <i class="fas fa-map mr-2"></i>Harita
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <!-- Pagination -->
                            @if($places->hasPages())
                                <div class="mt-8 flex justify-center">
                                    {{ $places->appends(['q' => $query])->links() }}
                                </div>
                            @endif
                        </div>
                    @endif
                @else
                    <!-- Sonuç Bulunamadı -->
                    <div class="no-results">
                        <div class="no-results-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-4">Sonuç Bulunamadı</h3>
                        <p class="text-gray-600 mb-8 max-w-md mx-auto">
                            "<strong>{{ $query }}</strong>" araması için herhangi bir sonuç bulunamadı. 
                            Farklı anahtar kelimeler deneyebilir veya tüm kategorilere göz atabilirsiniz.
                        </p>
                        
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <a href="{{ route('guide.index') }}" 
                               class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition-colors">
                                <i class="fas fa-layer-group mr-2"></i>Tüm Kategoriler
                            </a>
                            <button onclick="document.querySelector('input[name=q]').focus()" 
                                    class="inline-flex items-center px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-semibold transition-colors">
                                <i class="fas fa-search mr-2"></i>Yeni Arama
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection

@section('after_scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filtre butonları
    const filterButtons = document.querySelectorAll('.filter-button');
    const categoryResults = document.querySelector('.category-results');
    const placeResults = document.querySelector('.place-results');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.dataset.filter;
            
            // Aktif butonu güncelle
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Sonuçları filtrele
            if (filter === 'all') {
                if (categoryResults) categoryResults.style.display = 'block';
                if (placeResults) placeResults.style.display = 'block';
            } else if (filter === 'categories') {
                if (categoryResults) categoryResults.style.display = 'block';
                if (placeResults) placeResults.style.display = 'none';
            } else if (filter === 'places') {
                if (categoryResults) categoryResults.style.display = 'none';
                if (placeResults) placeResults.style.display = 'block';
            }
        });
    });
    
    // Arama formu geliştirmeleri
    const searchForm = document.querySelector('form[action*="search"]');
    const searchInput = searchForm.querySelector('input[name="q"]');
    
    // Arama geçmişi (localStorage)
    const searchHistory = JSON.parse(localStorage.getItem('guideSearchHistory') || '[]');
    
    // Arama yapıldığında geçmişe ekle
    searchForm.addEventListener('submit', function() {
        const query = searchInput.value.trim();
        if (query && !searchHistory.includes(query)) {
            searchHistory.unshift(query);
            if (searchHistory.length > 5) {
                searchHistory.pop();
            }
            localStorage.setItem('guideSearchHistory', JSON.stringify(searchHistory));
        }
    });
    
    // Arama önerileri (basit implementasyon)
    searchInput.addEventListener('focus', function() {
        if (searchHistory.length > 0) {
            // Burada arama geçmişini gösterebiliriz
            console.log('Arama geçmişi:', searchHistory);
        }
    });
    
    // Sonuç kartlarına hover efekti
    const resultCards = document.querySelectorAll('.search-results-card, .category-result');
    
    resultCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Telefon numarası tıklama analizi
    const phoneLinks = document.querySelectorAll('a[href^="tel:"]');
    phoneLinks.forEach(link => {
        link.addEventListener('click', function() {
            // Analytics için telefon tıklama kaydı
            console.log('Telefon tıklandı:', this.textContent);
        });
    });
    
    // Harita linki tıklama analizi
    const mapLinks = document.querySelectorAll('a[href*="maps"]');
    mapLinks.forEach(link => {
        link.addEventListener('click', function() {
            // Analytics için harita tıklama kaydı
            console.log('Harita tıklandı:', this.href);
        });
    });
});
</script>
@endsection
</rewritten_file>