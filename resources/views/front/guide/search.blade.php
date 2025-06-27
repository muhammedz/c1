@extends('layouts.front')

@section('title', 'Arama Sonuçları - ' . $pageTitle)
@section('meta_description', $pageDescription)

@section('css')
<style>
    .search-hero {
        background: linear-gradient(135deg, #00352b 0%, #20846c 100%);
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
<!-- Hero Bölümü -->
<div class="relative bg-gradient-to-r from-[#00352b] to-[#20846c] overflow-hidden">
    <div class="absolute inset-0 opacity-20">
        <!-- Pattern overlay -->
        <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" preserveAspectRatio="none">
            <defs>
                <pattern id="hero-pattern" width="40" height="40" patternUnits="userSpaceOnUse">
                    <path d="M0 20 L40 20 M20 0 L20 40" stroke="currentColor" stroke-width="1" fill="none" />
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#hero-pattern)" />
        </svg>
    </div>
    
    <!-- Dekoratif şekiller -->
    <div class="absolute -right-20 -bottom-20 w-64 h-64 rounded-full bg-[#e6a23c]/10 blur-3xl"></div>
    <div class="absolute -left-10 top-10 w-40 h-40 rounded-full bg-white/5 blur-2xl"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 md:py-4 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-1 gap-4 items-center">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">Rehber Arama Sonuçları</h1>
                @if(request('q'))
                <p class="text-white/80 text-base mb-2">
                    "<span class="font-medium">{{ request('q') }}</span>" için {{ $totalResults }} sonuç bulundu
                </p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="bg-gray-50 py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto">
            <!-- Sol Taraf Bilgi ve Filtreler -->
            <div class="flex flex-col lg:flex-row gap-6 lg:gap-8">
                <!-- Sol Kenar - Bilgi ve Filtreler -->
                <div class="w-full lg:w-64 flex-shrink-0 order-2 lg:order-1">
                    <div class="bg-gray-100 p-4 md:p-5 rounded-lg mb-4 md:mb-6">
                        <div class="flex items-start mb-3 md:mb-4">
                            <span class="material-icons text-gray-400 mr-2 mt-1 text-lg md:text-xl">info</span>
                            <p class="text-gray-600 text-sm">Rehber içerisinde arama yaparak ihtiyacınız olan hizmet ve kurumları kolayca bulabilirsiniz.</p>
                        </div>
                    </div>
                    
                    <div class="border border-gray-200 rounded-lg overflow-hidden mb-4 md:mb-6">
                        <div class="bg-white p-3 md:p-4 border-b border-gray-200">
                            <h3 class="font-medium text-gray-700 text-sm md:text-base">Tüm Sonuçlar ({{ $totalResults }})</h3>
                        </div>
                        
                        <div class="bg-white p-3 md:p-4 border-b border-gray-200">
                            <a href="javascript:void(0)" onclick="filterResults('')" class="text-[#004d2e] hover:underline flex justify-between text-sm md:text-base {{ !request('type') ? 'font-semibold' : '' }}">
                                <span>Tümü</span>
                                <span>{{ $totalResults }}</span>
                            </a>
                        </div>
                        
                        @if($categories->count() > 0)
                        <div class="bg-white p-3 md:p-4 border-b border-gray-200">
                            <a href="javascript:void(0)" onclick="filterResults('categories')" class="text-[#004d2e] hover:underline flex justify-between text-sm md:text-base {{ request('type') === 'categories' ? 'font-semibold' : '' }}">
                                <span>Kategoriler</span>
                                <span>{{ $categories->count() }}</span>
                            </a>
                        </div>
                        @endif
                        
                        @if($places->count() > 0)
                        <div class="bg-white p-3 md:p-4">
                            <a href="javascript:void(0)" onclick="filterResults('places')" class="text-[#004d2e] hover:underline flex justify-between text-sm md:text-base {{ request('type') === 'places' ? 'font-semibold' : '' }}">
                                <span>Yerler</span>
                                <span>{{ $places->count() }}</span>
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Sağ Taraf - Arama ve Sonuçlar -->
                <div class="flex-1 order-1 lg:order-2">
                    <!-- Arama Formu -->
                    <div class="bg-white p-4 md:p-6 rounded-lg shadow-sm mb-6 border border-gray-200">
                        <div class="mb-4">
                            <label for="search_query" class="block text-gray-700 font-medium mb-3 text-sm md:text-base">Aranan Kelime</label>
                            <form action="{{ route('guide.search') }}" method="GET" class="space-y-4">
                                <div class="flex flex-col sm:flex-row gap-3">
                                    <div class="flex-1">
                                        <input 
                                            type="text" 
                                            id="search_query"
                                            name="q" 
                                            value="{{ request('q') }}" 
                                            placeholder="Rehber içinde aramak istediğiniz kelimeyi girin..." 
                                            class="w-full px-4 py-3 md:py-2 text-base md:text-sm rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#004d2e] focus:border-transparent"
                                        >
                                    </div>
                                    
                                    <div class="w-full sm:w-auto">
                                        <button 
                                            type="submit" 
                                            class="w-full sm:w-auto bg-[#004d2e] hover:bg-green-800 text-white py-3 md:py-2 px-6 rounded-md transition-colors font-medium text-base md:text-sm"
                                        >
                                            Ara
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    @if(!request('q'))
                        <!-- Boş arama başlangıcı -->
                        <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 md:p-5 mb-4 md:mb-6 flex items-center">
                            <span class="material-icons text-amber-400 mr-2 md:mr-3 text-xl md:text-2xl">warning</span>
                            <p class="text-amber-800 text-sm md:text-base">Lütfen arama yapmak istediğiniz kelimeyi/kelimeleri giriniz.</p>
                        </div>
                        
                        <div class="bg-white border border-gray-200 p-4 md:p-6 rounded-lg">
                            <h3 class="text-base md:text-lg font-medium text-gray-700 mb-2">Toplam <span class="font-bold">0</span> kayıt bulunmuştur.</h3>
                        </div>
                    @elseif($totalResults == 0)
                        <!-- Sonuç Bulunamadı -->
                        <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 md:p-5 mb-4 md:mb-6 flex items-center">
                            <span class="material-icons text-amber-400 mr-2 md:mr-3 text-xl md:text-2xl">search_off</span>
                            <p class="text-amber-800 text-sm md:text-base">
                                "<strong>{{ request('q') }}</strong>" için sonuç bulunamadı. Farklı kelimeler deneyin.
                            </p>
                        </div>
                        
                        <!-- Öneriler -->
                        <div class="bg-white border border-gray-200 p-4 md:p-6 rounded-lg mb-6">
                            <h4 class="text-lg font-semibold text-gray-700 mb-4">Arama önerileri:</h4>
                            <ul class="text-gray-600 space-y-2">
                                <li>• Daha genel kelimeler kullanın</li>
                                <li>• Yazım hatalarını kontrol edin</li>
                                <li>• Farklı kelime kombinasyonları deneyin</li>
                                <li>• Kategori adlarını arayın (örn: zabıta, sağlık)</li>
                            </ul>
                        </div>
                        
                        <div class="bg-white border border-gray-200 p-4 md:p-6 rounded-lg">
                            <h3 class="text-base md:text-lg font-medium text-gray-700">Toplam <span class="font-bold">0</span> kayıt bulunmuştur.</h3>
                        </div>
                    @else
                        <!-- Kategori Sonuçları -->
                        @if($categories->count() > 0 && (!request('type') || request('type') === 'categories'))
                            <div id="categories" class="mb-4 md:mb-6">
                                <h2 class="text-lg md:text-xl font-bold text-gray-900 mb-2 md:mb-3 border-b border-gray-200 pb-2">Kategoriler ({{ $categories->count() }})</h2>
                                <div class="space-y-2">
                                    @foreach($categories as $category)
                                        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden transition-all hover:shadow-md">
                                            <div class="p-3 md:p-4">
                                                <div class="flex-1">
                                                    <a href="{{ route('guide.category', $category->slug) }}" class="text-sm md:text-base font-medium text-[#004d2e] hover:underline block">
                                                        {!! highlightSearchTerm($category->name, request('q')) !!}
                                                    </a>
                                                    <span class="text-xs text-gray-400 block italic hidden md:block">{{ route('guide.category', $category->slug) }}</span>
                                                    
                                                    @if($category->description)
                                                        <p class="text-xs text-gray-600 mt-1">
                                                            {!! highlightSearchTerm(Str::limit($category->description, 100), request('q')) !!}
                                                        </p>
                                                    @endif
                                                    
                                                    <div class="flex items-center justify-between mt-2">
                                                        <div class="flex items-center text-xs text-gray-500">
                                                            <span class="material-icons text-xs mr-1">location_on</span>
                                                            <span>{{ $category->active_places_count }} yer</span>
                                                        </div>
                                                        <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Kategori</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        
                        <!-- Yerler Sonuçları -->
                        @if($places->count() > 0 && (!request('type') || request('type') === 'places'))
                            <div id="places" class="mb-4 md:mb-6">
                                <h2 class="text-lg md:text-xl font-bold text-gray-900 mb-2 md:mb-3 border-b border-gray-200 pb-2">Yerler ({{ $places->count() }})</h2>
                                <div class="space-y-2">
                                    @foreach($places as $place)
                                        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden transition-all hover:shadow-md">
                                            <div class="p-3 md:p-4">
                                                <div class="flex-1">
                                                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-1 sm:gap-2">
                                                        <a href="{{ route('guide.place', [$place->category->slug, $place->slug]) }}" class="text-sm md:text-base font-medium text-[#004d2e] hover:underline block">
                                                            {!! highlightSearchTerm($place->title, request('q')) !!}
                                                        </a>
                                                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full whitespace-nowrap">
                                                            {{ $place->category->name }}
                                                        </span>
                                                    </div>
                                                    <span class="text-xs text-gray-400 block italic hidden md:block">{{ route('guide.place', [$place->category->slug, $place->slug]) }}</span>
                                                    
                                                    @if($place->excerpt)
                                                        <p class="text-xs text-gray-600 mt-1">
                                                            {!! highlightSearchTerm(Str::limit($place->excerpt, 100), request('q')) !!}
                                                        </p>
                                                    @endif
                                                    
                                                    @if($place->address)
                                                        <p class="text-xs text-gray-600 flex items-center mt-1">
                                                            <span class="material-icons text-xs mr-1">place</span>
                                                            {!! highlightSearchTerm(Str::limit($place->address, 60), request('q')) !!}
                                                        </p>
                                                    @endif
                                                    
                                                    @if($place->phone)
                                                        <p class="text-xs text-gray-600 flex items-center mt-1">
                                                            <span class="material-icons text-xs mr-1">phone</span>
                                                            {{ $place->phone }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        
                        <!-- Toplam Sonuç -->
                        <div class="bg-white border border-gray-200 p-4 md:p-6 rounded-lg">
                            <h3 class="text-base md:text-lg font-medium text-gray-700">Toplam <span class="font-bold">{{ $totalResults }}</span> kayıt bulunmuştur.</h3>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
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
});
</script>
@endsection

