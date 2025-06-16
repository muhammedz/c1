@extends('layouts.front')

@section('title', 'Arama Sonuçları - ' . $pageTitle)
@section('meta_description', $pageDescription)

@section('css')
<style>
    .search-hero {
        background: linear-gradient(135deg, #004d2e 0%, #006b3f 100%);
    }
    
    .search-box {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }
    
    .result-card {
        background: white;
        border-radius: 24px;
        box-shadow: 0 2px 20px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        overflow: hidden;
    }
    
    .result-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 2px 20px rgba(0,0,0,0.1);
    }
    
    .category-result {
        border-left: 4px solid #004d2e;
    }
    
    .place-result {
        border-left: 4px solid #6c757d;
    }
    
    .filter-sidebar {
        background: white;
        border-radius: 24px;
        box-shadow: 0 2px 20px rgba(0,0,0,0.05);
    }
    
    .filter-item {
        padding: 0.75rem 1rem;
        border-radius: 12px;
        transition: all 0.2s ease;
        cursor: pointer;
        border: 1px solid transparent;
    }
    
    .filter-item:hover {
        background-color: #f8f9fa;
        border-color: #e9ecef;
    }
    
    .filter-item.active {
        background-color: #004d2e;
        color: white;
        border-color: #004d2e;
    }
    
    .highlight {
        background-color: #fff3cd;
        padding: 0.125rem 0.25rem;
        border-radius: 0.25rem;
        font-weight: 600;
    }
    
    .breadcrumb-item {
        color: rgba(255, 255, 255, 0.8);
    }
    
    .breadcrumb-item:hover {
        color: white;
    }
    
    .breadcrumb-separator {
        color: rgba(255, 255, 255, 0.6);
        margin: 0 0.5rem;
    }
    
    .action-btn {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s ease;
        text-decoration: none;
    }
    
    .action-btn-primary {
        background-color: #004d2e;
        color: white;
    }
    
    .action-btn-primary:hover {
        background-color: #006b3f;
        color: white;
    }
    
    .action-btn-secondary {
        background-color: #f8f9fa;
        color: #6c757d;
        border: 1px solid #e9ecef;
    }
    
    .action-btn-secondary:hover {
        background-color: #e9ecef;
        color: #495057;
    }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<section class="search-hero py-16 text-white">
    <div class="container max-w-7xl mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="mb-8">
            <ol class="flex items-center space-x-2 text-sm">
                <li><a href="{{ route('guide.index') }}" class="breadcrumb-item hover:underline">Rehber</a></li>
                <li><span class="breadcrumb-separator">></span></li>
                <li class="text-white font-medium">Arama Sonuçları</li>
            </ol>
        </nav>
        
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Arama Sonuçları</h1>
            @if(request('q'))
                <p class="text-xl md:text-2xl mb-6 opacity-90">
                    "<strong>{{ request('q') }}</strong>" için sonuçlar
                </p>
                <div class="inline-flex items-center bg-white/20 rounded-full px-4 py-2">
                    <span class="material-icons mr-2">search</span>
                    <span class="font-medium">{{ $totalResults }} sonuç bulundu</span>
                </div>
            @else
                <p class="text-xl md:text-2xl mb-6 opacity-90">
                    Arama yapmak için aşağıdaki kutuyu kullanın
                </p>
            @endif
        </div>
        
        <!-- Arama Kutusu -->
        <div class="max-w-2xl mx-auto mt-8">
            <form action="{{ route('guide.search') }}" method="GET" class="search-box p-6">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" 
                               name="q" 
                               value="{{ request('q') }}"
                               placeholder="Aradığınız hizmet, kurum veya yer..." 
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#004d2e] focus:border-transparent text-gray-800"
                               required>
                    </div>
                    <button type="submit" 
                            class="action-btn action-btn-primary px-8 py-3">
                        <span class="material-icons mr-2">search</span>Ara
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

@if(request('q'))
<!-- Sonuçlar -->
<section class="py-12 bg-gray-50">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Filtre Sidebar -->
            <div class="lg:col-span-1">
                <div class="filter-sidebar p-6 sticky top-8">
                    <h3 class="text-lg font-bold text-gray-800 mb-6">Sonuç Türü</h3>
                    
                    <div class="space-y-2">
                        <div class="filter-item {{ !request('type') ? 'active' : '' }}" 
                             onclick="filterResults('')">
                            <div class="flex items-center justify-between">
                                <span>Tümü</span>
                                <span class="text-sm">{{ $totalResults }}</span>
                            </div>
                        </div>
                        
                        @if($categories->count() > 0)
                            <div class="filter-item {{ request('type') === 'categories' ? 'active' : '' }}" 
                                 onclick="filterResults('categories')">
                                <div class="flex items-center justify-between">
                                    <span>Kategoriler</span>
                                    <span class="text-sm">{{ $categories->count() }}</span>
                                </div>
                            </div>
                        @endif
                        
                        @if($places->count() > 0)
                            <div class="filter-item {{ request('type') === 'places' ? 'active' : '' }}" 
                                 onclick="filterResults('places')">
                                <div class="flex items-center justify-between">
                                    <span>Yerler</span>
                                    <span class="text-sm">{{ $places->count() }}</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Ana İçerik -->
            <div class="lg:col-span-3">
                @if($totalResults > 0)
                    <!-- Kategori Sonuçları -->
                    @if($categories->count() > 0 && (!request('type') || request('type') === 'categories'))
                        <div class="mb-12" id="category-results">
                            <h2 class="text-2xl font-bold text-gray-800 mb-6">
                                <span class="material-icons mr-2 text-[#004d2e]">category</span>
                                Kategoriler ({{ $categories->count() }})
                            </h2>
                            
                            <div class="space-y-4">
                                @foreach($categories as $category)
                                    <div class="result-card category-result p-6">
                                        <div class="flex items-start gap-4">
                                            <div class="w-16 h-16 bg-[#004d2e] rounded-full flex items-center justify-center flex-shrink-0">
                                                @if($category->icon)
                                                    @if(Str::startsWith($category->icon, 'fas ') || Str::startsWith($category->icon, 'far ') || Str::startsWith($category->icon, 'fab '))
                                                        <i class="{{ $category->icon }} text-white text-xl"></i>
                                                    @else
                                                        <span class="material-icons text-white text-xl">{{ $category->icon }}</span>
                                                    @endif
                                                @else
                                                    <span class="material-icons text-white text-xl">location_on</span>
                                                @endif
                                            </div>
                                            
                                            <div class="flex-1">
                                                <h3 class="text-xl font-bold text-gray-800 mb-2">
                                                    {!! highlightSearchTerm($category->name, request('q')) !!}
                                                </h3>
                                                
                                                @if($category->description)
                                                    <p class="text-gray-600 mb-4">
                                                        {!! highlightSearchTerm(Str::limit($category->description, 150), request('q')) !!}
                                                    </p>
                                                @endif
                                                
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center text-sm text-gray-500">
                                                        <span class="material-icons mr-1 text-sm">location_on</span>
                                                        <span>{{ $category->active_places_count }} yer</span>
                                                    </div>
                                                    
                                                    <a href="{{ route('guide.category', $category->slug) }}" 
                                                       class="action-btn action-btn-primary">
                                                        <span class="material-icons mr-2">visibility</span>Görüntüle
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
                    @if($places->count() > 0 && (!request('type') || request('type') === 'places'))
                        <div id="place-results">
                            <h2 class="text-2xl font-bold text-gray-800 mb-6">
                                <span class="material-icons mr-2 text-[#004d2e]">location_on</span>
                                Yerler ({{ $places->count() }})
                            </h2>
                            
                            <div class="space-y-4">
                                @foreach($places as $place)
                                    <div class="result-card place-result p-6">
                                        <div class="flex flex-col md:flex-row gap-4">
                                            <!-- Resim -->
                                            <div class="md:w-32 md:h-32 w-full h-48 flex-shrink-0">
                                                @if($place->featured_image_url)
                                                    <img src="{{ $place->featured_image_url }}" 
                                                         alt="{{ $place->title }}"
                                                         class="w-full h-full object-cover rounded-lg">
                                                @else
                                                    <div class="w-full h-full bg-gray-100 rounded-lg flex items-center justify-center">
                                                        <span class="material-icons text-gray-400 text-3xl">business</span>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <!-- İçerik -->
                                            <div class="flex-1">
                                                <div class="flex items-start justify-between mb-2">
                                                    <h3 class="text-xl font-bold text-gray-800">
                                                        {!! highlightSearchTerm($place->title, request('q')) !!}
                                                    </h3>
                                                    <span class="text-sm text-gray-500 bg-gray-100 px-2 py-1 rounded-full ml-2">
                                                        {{ $place->category->name }}
                                                    </span>
                                                </div>
                                                
                                                @if($place->excerpt)
                                                    <p class="text-gray-600 mb-4">
                                                        {!! highlightSearchTerm(Str::limit($place->excerpt, 150), request('q')) !!}
                                                    </p>
                                                @endif
                                                
                                                <!-- İletişim Bilgileri -->
                                                <div class="space-y-2 mb-4">
                                                    @if($place->address)
                                                        <div class="flex items-start text-sm text-gray-600">
                                                            <span class="material-icons text-gray-400 mr-2 mt-0.5 text-base">location_on</span>
                                                            <span class="flex-1">{!! highlightSearchTerm($place->address, request('q')) !!}</span>
                                                        </div>
                                                    @endif
                                                    
                                                    @if($place->phone)
                                                        <div class="flex items-center text-sm text-gray-600">
                                                            <span class="material-icons text-gray-400 mr-2 text-base">phone</span>
                                                            <span>{{ $place->phone }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                                
                                                <!-- Aksiyon Butonları -->
                                                <div class="flex flex-wrap gap-2">
                                                    <a href="{{ route('guide.place', [$place->category->slug, $place->slug]) }}" 
                                                       class="action-btn action-btn-primary">
                                                        <span class="material-icons mr-2">visibility</span>Detaylar
                                                    </a>
                                                    
                                                    @if($place->phone)
                                                        <a href="tel:{{ $place->phone }}" 
                                                           class="action-btn action-btn-secondary">
                                                            <span class="material-icons mr-2">phone</span>Ara
                                                        </a>
                                                    @endif
                                                    
                                                    @if($place->maps_link)
                                                        <a href="{{ $place->maps_link }}" 
                                                           target="_blank"
                                                           class="action-btn action-btn-secondary">
                                                            <span class="material-icons mr-2">map</span>Konum
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @else
                    <!-- Sonuç Bulunamadı -->
                    <div class="text-center py-16">
                        <div class="w-24 h-24 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
                            <span class="material-icons text-gray-400 text-3xl">search_off</span>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">Sonuç bulunamadı</h3>
                        <p class="text-gray-500 mb-6">
                            "<strong>{{ request('q') }}</strong>" için sonuç bulunamadı. Farklı kelimeler deneyin.
                        </p>
                        
                        <!-- Öneriler -->
                        <div class="max-w-md mx-auto">
                            <h4 class="text-lg font-semibold text-gray-700 mb-4">Arama önerileri:</h4>
                            <ul class="text-left text-gray-600 space-y-2">
                                <li>• Daha genel kelimeler kullanın</li>
                                <li>• Yazım hatalarını kontrol edin</li>
                                <li>• Farklı kelime kombinasyonları deneyin</li>
                                <li>• Kategori adlarını arayın (örn: zabıta, sağlık)</li>
                            </ul>
                        </div>
                        
                        <div class="mt-8">
                            <a href="{{ route('guide.index') }}" 
                               class="action-btn action-btn-primary">
                                <span class="material-icons mr-2">arrow_back</span>Ana Sayfaya Dön
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endif
@endsection

@section('after_scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filtre işlevselliği
    window.filterResults = function(type) {
        const currentUrl = new URL(window.location);
        if (type) {
            currentUrl.searchParams.set('type', type);
        } else {
            currentUrl.searchParams.delete('type');
        }
        window.location.href = currentUrl.toString();
    };
    
    // Arama geçmişi
    const searchInput = document.querySelector('input[name="q"]');
    if (searchInput) {
        // Arama geçmişini localStorage'dan yükle
        const searchHistory = JSON.parse(localStorage.getItem('guideSearchHistory') || '[]');
        
        // Arama yapıldığında geçmişe ekle
        const searchForm = searchInput.closest('form');
        if (searchForm) {
            searchForm.addEventListener('submit', function() {
                const query = searchInput.value.trim();
                if (query && !searchHistory.includes(query)) {
                    searchHistory.unshift(query);
                    // Maksimum 10 arama sakla
                    if (searchHistory.length > 10) {
                        searchHistory.pop();
                    }
                    localStorage.setItem('guideSearchHistory', JSON.stringify(searchHistory));
                }
            });
        }
        
        // Arama kutusuna odaklan
        if (!searchInput.value) {
            searchInput.focus();
        }
    }
    
    // Kart hover efektleri
    const resultCards = document.querySelectorAll('.result-card');
    
    resultCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Smooth scroll for filter results
    const filterItems = document.querySelectorAll('.filter-item');
    filterItems.forEach(item => {
        item.addEventListener('click', function() {
            // Aktif durumu güncelle
            filterItems.forEach(f => f.classList.remove('active'));
            this.classList.add('active');
        });
    });
});

// Arama terimini vurgulama fonksiyonu (PHP'den gelen veri için)
@if(request('q'))
    // Sayfa yüklendikten sonra vurgulamaları kontrol et
    setTimeout(function() {
        const highlights = document.querySelectorAll('.highlight');
        highlights.forEach(highlight => {
            highlight.style.backgroundColor = '#fff3cd';
            highlight.style.fontWeight = '600';
        });
    }, 100);
@endif
</script>
@endsection

@php
// Arama terimini vurgulama helper fonksiyonu
if (!function_exists('highlightSearchTerm')) {
    function highlightSearchTerm($text, $searchTerm) {
        if (!$searchTerm) return $text;
        
        $pattern = '/(' . preg_quote($searchTerm, '/') . ')/iu';
        return preg_replace($pattern, '<span class="highlight">$1</span>', $text);
    }
}
@endphp