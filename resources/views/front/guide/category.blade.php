@extends('layouts.front')

@section('title', $pageTitle)
@section('meta_description', $pageDescription)

@section('css')
<style>
    .category-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .place-card {
        transition: all 0.3s ease;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    
    .place-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    .place-image {
        height: 200px;
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        position: relative;
        overflow: hidden;
    }
    
    .place-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .place-card:hover .place-image img {
        transform: scale(1.05);
    }
    
    .place-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        color: #374151;
    }
    
    .contact-info {
        display: flex;
        align-items: center;
        padding: 8px 12px;
        background: #f8fafc;
        border-radius: 8px;
        margin: 4px 0;
        font-size: 0.875rem;
        color: #64748b;
    }
    
    .contact-info i {
        width: 16px;
        margin-right: 8px;
        color: #3b82f6;
    }
    
    .filter-tabs {
        background: white;
        border-radius: 12px;
        padding: 8px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    
    .filter-tab {
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    
    .filter-tab.active {
        background: #3b82f6;
        color: white;
    }
    
    .filter-tab:not(.active):hover {
        background: #f1f5f9;
    }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<section class="category-hero py-16 text-white">
    <div class="container max-w-[1235px] mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="mb-8">
            <ol class="flex items-center space-x-2 text-sm opacity-80">
                <li><a href="{{ route('home') }}" class="hover:text-white">Ana Sayfa</a></li>
                <li><i class="fas fa-chevron-right mx-2"></i></li>
                <li><a href="{{ route('guide.index') }}" class="hover:text-white">Rehber</a></li>
                <li><i class="fas fa-chevron-right mx-2"></i></li>
                <li class="text-white font-medium">{{ $category->name }}</li>
            </ol>
        </nav>
        
        <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
            <!-- Kategori İkonu -->
            <div class="w-20 h-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-3xl">
                @if($category->icon)
                    <i class="{{ $category->icon }}"></i>
                @else
                    <i class="fas fa-map-marker-alt"></i>
                @endif
            </div>
            
            <!-- Kategori Bilgileri -->
            <div class="flex-1">
                <h1 class="text-3xl md:text-4xl font-bold mb-4">{{ $category->name }}</h1>
                @if($category->description)
                    <p class="text-lg md:text-xl opacity-90 mb-4">{{ $category->description }}</p>
                @endif
                
                <div class="flex items-center gap-6 text-sm">
                    <div class="flex items-center">
                        <i class="fas fa-map-marker-alt mr-2"></i>
                        <span>{{ $places->total() }} yer</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-clock mr-2"></i>
                        <span>7/24 Erişim</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Arama ve Filtreler -->
        <div class="mt-8">
            <form action="{{ route('guide.category', $category->slug) }}" method="GET" class="max-w-2xl">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Bu kategori içinde ara..." 
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-800">
                    </div>
                    <button type="submit" 
                            class="px-6 py-3 bg-white bg-opacity-20 hover:bg-opacity-30 text-white rounded-lg font-semibold transition-all duration-200 backdrop-filter backdrop-blur-sm">
                        <i class="fas fa-search mr-2"></i>Ara
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Yerler Listesi -->
<section class="py-16 bg-gray-50">
    <div class="container max-w-[1235px] mx-auto px-4">
        @if($places->count() > 0)
            <!-- Sonuç Bilgisi -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">
                        @if(request('search'))
                            "{{ request('search') }}" için sonuçlar
                        @else
                            Tüm Yerler
                        @endif
                    </h2>
                    <p class="text-gray-600">{{ $places->total() }} yer bulundu</p>
                </div>
                
                <!-- Sıralama -->
                <div class="filter-tabs mt-4 md:mt-0">
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-600 mr-2">Sırala:</span>
                        <div class="filter-tab active">
                            <i class="fas fa-sort-alpha-down mr-1"></i>A-Z
                        </div>
                        <div class="filter-tab">
                            <i class="fas fa-map-marker-alt mr-1"></i>Konum
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Yerler Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($places as $place)
                    <div class="place-card bg-white">
                        <!-- Yer Resmi -->
                        <div class="place-image">
                            @if($place->featured_image_url)
                                <img src="{{ $place->featured_image_url }}" alt="{{ $place->title }}">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-white text-4xl">
                                    <i class="fas fa-building"></i>
                                </div>
                            @endif
                            
                            @if($place->is_featured)
                                <div class="place-badge">
                                    <i class="fas fa-star text-yellow-500 mr-1"></i>Öne Çıkan
                                </div>
                            @endif
                        </div>
                        
                        <!-- Yer Bilgileri -->
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-800 mb-3 line-clamp-2">{{ $place->title }}</h3>
                            
                            @if($place->excerpt)
                                <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ $place->excerpt }}</p>
                            @endif
                            
                            <!-- İletişim Bilgileri -->
                            <div class="space-y-2 mb-6">
                                @if($place->address)
                                    <div class="contact-info">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span class="line-clamp-1">{{ $place->address }}</span>
                                    </div>
                                @endif
                                
                                @if($place->phone)
                                    <div class="contact-info">
                                        <i class="fas fa-phone"></i>
                                        <a href="tel:{{ $place->phone }}" class="hover:text-blue-600">{{ $place->phone }}</a>
                                    </div>
                                @endif
                                
                                @if($place->working_hours)
                                    <div class="contact-info">
                                        <i class="fas fa-clock"></i>
                                        <span class="line-clamp-1">{{ $place->working_hours }}</span>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Aksiyonlar -->
                            <div class="flex items-center justify-between">
                                <a href="{{ route('guide.place', [$category->slug, $place->slug]) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors duration-200">
                                    Detaylar
                                    <i class="fas fa-arrow-right ml-2"></i>
                                </a>
                                
                                <div class="flex items-center gap-2">
                                    @if($place->maps_link)
                                        <a href="{{ $place->maps_link }}" 
                                           target="_blank"
                                           class="w-10 h-10 bg-gray-100 hover:bg-gray-200 rounded-lg flex items-center justify-center text-gray-600 hover:text-gray-800 transition-colors duration-200"
                                           title="Haritada Göster">
                                            <i class="fas fa-map"></i>
                                        </a>
                                    @endif
                                    
                                    @if($place->phone)
                                        <a href="tel:{{ $place->phone }}" 
                                           class="w-10 h-10 bg-green-100 hover:bg-green-200 rounded-lg flex items-center justify-center text-green-600 hover:text-green-800 transition-colors duration-200"
                                           title="Ara">
                                            <i class="fas fa-phone"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            @if($places->hasPages())
                <div class="mt-12 flex justify-center">
                    {{ $places->appends(request()->query())->links() }}
                </div>
            @endif
        @else
            <!-- Boş Durum -->
            <div class="text-center py-16">
                <div class="w-24 h-24 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-search text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">
                    @if(request('search'))
                        Arama sonucu bulunamadı
                    @else
                        Bu kategoride henüz yer bulunmuyor
                    @endif
                </h3>
                <p class="text-gray-500 mb-6">
                    @if(request('search'))
                        Farklı anahtar kelimeler deneyebilirsiniz.
                    @else
                        Yakında yeni yerler eklenecek.
                    @endif
                </p>
                
                @if(request('search'))
                    <a href="{{ route('guide.category', $category->slug) }}" 
                       class="inline-block px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition-colors duration-200">
                        Tüm Yerleri Gör
                    </a>
                @else
                    <a href="{{ route('guide.index') }}" 
                       class="inline-block px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition-colors duration-200">
                        Diğer Kategoriler
                    </a>
                @endif
            </div>
        @endif
    </div>
</section>
@endsection

@section('after_scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Yer kartlarına hover efekti
    const placeCards = document.querySelectorAll('.place-card');
    
    placeCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-4px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Filtre tabları
    const filterTabs = document.querySelectorAll('.filter-tab');
    
    filterTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Aktif tab'ı kaldır
            filterTabs.forEach(t => t.classList.remove('active'));
            // Yeni tab'ı aktif yap
            this.classList.add('active');
            
            // Burada sıralama işlemi yapılabilir
            // Şimdilik sadece görsel değişiklik
        });
    });
    
    // Telefon linklerini formatla
    const phoneLinks = document.querySelectorAll('a[href^="tel:"]');
    phoneLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Mobil cihazlarda telefon uygulamasını aç
            if (!/Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                e.preventDefault();
                alert('Telefon numarası: ' + this.textContent);
            }
        });
    });
});
</script>
@endsection