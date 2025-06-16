@extends('layouts.front')

@section('title', $pageTitle)
@section('meta_description', $pageDescription)

@section('css')
<style>
    .guide-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .category-card {
        transition: all 0.3s ease;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    
    .category-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    .category-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: white;
        margin: 0 auto 1rem;
    }
    
    .place-preview {
        border-radius: 8px;
        overflow: hidden;
        transition: transform 0.2s ease;
    }
    
    .place-preview:hover {
        transform: scale(1.02);
    }
    
    .search-box {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<section class="guide-hero py-16 text-white">
    <div class="container max-w-[1235px] mx-auto px-4">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Çankaya Rehberi</h1>
            <p class="text-xl md:text-2xl mb-8 opacity-90">
                İhtiyacınız olan tüm hizmet ve kurumlara kolayca ulaşın
            </p>
            
            <!-- Arama Kutusu -->
            <div class="max-w-2xl mx-auto">
                <form action="{{ route('guide.search') }}" method="GET" class="search-box p-6">
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1">
                            <input type="text" 
                                   name="q" 
                                   value="{{ request('search') }}"
                                   placeholder="Aradığınız hizmet, kurum veya yer..." 
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-800">
                        </div>
                        <button type="submit" 
                                class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition-colors duration-200">
                            <i class="fas fa-search mr-2"></i>Ara
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Kategoriler -->
<section class="py-16 bg-gray-50">
    <div class="container max-w-[1235px] mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Rehber Kategorileri</h2>
            <p class="text-lg text-gray-600">İhtiyacınıza uygun kategoriyi seçin</p>
        </div>
        
        @if($categories->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($categories as $category)
                    <div class="category-card bg-white p-8">
                        <!-- Kategori İkonu -->
                        <div class="category-icon">
                            @if($category->icon)
                                <i class="{{ $category->icon }}"></i>
                            @else
                                <i class="fas fa-map-marker-alt"></i>
                            @endif
                        </div>
                        
                        <!-- Kategori Bilgileri -->
                        <div class="text-center">
                            <h3 class="text-xl font-bold text-gray-800 mb-3">{{ $category->name }}</h3>
                            @if($category->description)
                                <p class="text-gray-600 mb-4">{{ Str::limit($category->description, 100) }}</p>
                            @endif
                            
                            <div class="flex items-center justify-center text-sm text-gray-500 mb-6">
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                <span>{{ $category->active_places_count }} yer</span>
                            </div>
                            
                            <a href="{{ route('guide.category', $category->slug) }}" 
                               class="inline-block px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition-colors duration-200">
                                Yerleri Gör
                                <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        </div>
                        
                        <!-- Örnek Yerler -->
                        @if($category->activePlaces->count() > 0)
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <h4 class="text-sm font-semibold text-gray-700 mb-3">Örnek Yerler:</h4>
                                <div class="space-y-2">
                                    @foreach($category->activePlaces->take(3) as $place)
                                        <div class="place-preview bg-gray-50 p-3 rounded-lg">
                                            <div class="flex items-center">
                                                @if($place->featured_image_url)
                                                    <img src="{{ $place->featured_image_url }}" 
                                                         alt="{{ $place->title }}"
                                                         class="w-10 h-10 rounded-full object-cover mr-3">
                                                @else
                                                    <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center mr-3">
                                                        <i class="fas fa-building text-gray-600 text-sm"></i>
                                                    </div>
                                                @endif
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-800 truncate">{{ $place->title }}</p>
                                                    @if($place->address)
                                                        <p class="text-xs text-gray-500 truncate">{{ Str::limit($place->address, 30) }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            @if($categories->hasPages())
                <div class="mt-12 flex justify-center">
                    {{ $categories->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-16">
                <div class="w-24 h-24 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-search text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Henüz kategori bulunmuyor</h3>
                <p class="text-gray-500">Yakında yeni kategoriler eklenecek.</p>
            </div>
        @endif
    </div>
</section>

<!-- İstatistikler -->
<section class="py-16 bg-white">
    <div class="container max-w-[1235px] mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
            <div class="p-6">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-layer-group text-blue-600 text-2xl"></i>
                </div>
                <h3 class="text-3xl font-bold text-gray-800 mb-2">{{ $categories->total() }}</h3>
                <p class="text-gray-600">Kategori</p>
            </div>
            
            <div class="p-6">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-map-marker-alt text-green-600 text-2xl"></i>
                </div>
                <h3 class="text-3xl font-bold text-gray-800 mb-2">{{ $categories->sum('active_places_count') }}</h3>
                <p class="text-gray-600">Kayıtlı Yer</p>
            </div>
            
            <div class="p-6">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-users text-purple-600 text-2xl"></i>
                </div>
                <h3 class="text-3xl font-bold text-gray-800 mb-2">7/24</h3>
                <p class="text-gray-600">Hizmet</p>
            </div>
        </div>
    </div>
</section>
@endsection

@section('after_scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Kategori kartlarına hover efekti
    const categoryCards = document.querySelectorAll('.category-card');
    
    categoryCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Arama formu validasyonu
    const searchForm = document.querySelector('form[action*="search"]');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            const searchInput = this.querySelector('input[name="q"]');
            if (searchInput.value.trim().length < 2) {
                e.preventDefault();
                alert('Lütfen en az 2 karakter girin.');
                searchInput.focus();
            }
        });
    }
});
</script>
@endsection