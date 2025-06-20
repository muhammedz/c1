@extends('layouts.front')

@section('title', $category->name . ' - ' . $pageTitle)
@section('meta_description', $category->meta_description ?: $category->description)

@section('css')
<style>
    .place-card {
        background: white;
        border-radius: 24px;
        box-shadow: 0 2px 20px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        overflow: hidden;
    }
    
    .place-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 2px 20px rgba(0,0,0,0.1);
    }
    
    .search-filter-box {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 20px rgba(0,0,0,0.05);
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
</style>
@endsection

@section('content')
<!-- Hero Bölümü - Ana Rehber Sayfası ile Aynı -->
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
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-10 relative z-10">
        <div class="flex flex-col items-center text-center">
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">
                {{ $category->name }} <span class="text-[#e6a23c]">Rehberi</span>
            </h1>
            
            <!-- Arama Kutusu -->
            <form method="GET" class="mt-6 w-full max-w-2xl">
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-1 flex items-center shadow-lg hover:bg-white/20 transition duration-300 border border-white/20">
                    <input 
                        type="text" 
                        name="search" 
                        placeholder="Bu kategori içinde ara..." 
                        class="w-full bg-transparent px-4 py-3 text-white placeholder-white/70 outline-none"
                        value="{{ request('search') }}"
                    >
                    <button type="submit" class="bg-[#e6a23c] hover:bg-[#e6a23c]/90 text-white font-medium px-6 py-3 rounded-md transition duration-300 flex items-center shadow-lg shadow-[#e6a23c]/20">
                        <span class="material-icons mr-1">search</span>
                        Ara
                    </button>
                </div>
                @if(request('search'))
                    <div class="text-center mt-4">
                        <a href="{{ route('guide.category', $category->slug) }}" 
                           class="inline-flex items-center text-white/80 hover:text-white transition-colors">
                            <span class="material-icons mr-1 text-sm">clear</span>
                            Aramayı temizle
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>

<!-- Yerler Listesi -->
<section class="py-20 bg-gray-50">
    <div class="container max-w-6xl mx-auto px-4">
        @if($places->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-8">
                @foreach($places as $place)
                    <a href="{{ route('guide.place', [$category->slug, $place->slug]) }}" 
                       class="group block bg-white rounded-3xl overflow-hidden shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-2 transition-all duration-300">
                        <!-- Resim -->
                        @if($place->featured_image_url)
                            <div class="aspect-video overflow-hidden">
                                <img src="{{ $place->featured_image_url }}" 
                                     alt="{{ $place->title }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            </div>
                        @else
                            <div class="aspect-video overflow-hidden">
                                <img src="{{ asset('images/cankaya-default.jpeg') }}" 
                                     alt="{{ $place->title }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            </div>
                        @endif
                        
                        <!-- İçerik -->
                        <div class="p-4 md:p-6">
                            <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-2 md:mb-3 group-hover:text-[#004d2e] transition-colors duration-300 leading-tight">{{ $place->title }}</h3>
                            
                            <!-- İletişim Bilgileri -->
                            <div class="space-y-2 md:space-y-3 mb-4 md:mb-6">
                                @if($place->address)
                                    <div class="flex items-start text-xs md:text-sm text-gray-600">
                                        <span class="material-icons text-[#004d2e] mr-2 md:mr-3 mt-0.5 text-sm md:text-base">location_on</span>
                                        <span class="flex-1 leading-relaxed">{{ $place->address }}</span>
                                    </div>
                                @endif
                                
                                @if($place->phone)
                                    <div class="flex items-center text-xs md:text-sm text-gray-600">
                                        <span class="material-icons text-[#004d2e] mr-2 md:mr-3 text-sm md:text-base">phone</span>
                                        <span>{{ $place->phone }}</span>
                                    </div>
                                @endif
                                
                                @if($place->working_hours)
                                    <div class="flex items-center text-xs md:text-sm text-gray-600">
                                        <span class="material-icons text-[#004d2e] mr-2 md:mr-3 text-sm md:text-base">schedule</span>
                                        <span>{{ $place->working_hours }}</span>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Aksiyon Butonları -->
                            <div class="flex items-center justify-between">
                                <span class="text-xs md:text-sm font-medium text-[#004d2e] group-hover:text-[#006b3f] transition-colors duration-300">
                                    Detayları Gör
                                </span>
                                <div class="w-6 h-6 md:w-8 md:h-8 bg-[#004d2e]/10 group-hover:bg-[#004d2e] rounded-full flex items-center justify-center transition-all duration-300 group-hover:translate-x-1">
                                    <span class="material-icons text-xs md:text-sm text-[#004d2e] group-hover:text-white transition-colors duration-300">arrow_forward</span>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            
            <!-- Pagination -->
            @if($places->hasPages())
                <div class="mt-16 flex justify-center">
                    {{ $places->appends(request()->query())->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-20">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="material-icons text-gray-400 text-3xl">
                        @if(request('search'))
                            search_off
                        @else
                            location_off
                        @endif
                    </span>
                </div>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">
                    @if(request('search'))
                        Arama sonucu bulunamadı
                    @else
                        Bu kategoride henüz yer bulunmuyor
                    @endif
                </h3>
                <p class="text-gray-500 mb-8">
                    @if(request('search'))
                        "{{ request('search') }}" için sonuç bulunamadı. Farklı kelimeler deneyin.
                    @else
                        Yakında bu kategoriye yerler eklenecek.
                    @endif
                </p>
                @if(request('search'))
                    <a href="{{ route('guide.category', $category->slug) }}" 
                       class="inline-flex items-center px-6 py-3 bg-[#004d2e] hover:bg-[#006b3f] text-white rounded-2xl font-medium transition-all duration-300 hover:-translate-y-1 shadow-lg hover:shadow-xl">
                        <span class="material-icons mr-2">arrow_back</span>Tüm Yerleri Gör
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
    // Kart hover efektleri
    const placeCards = document.querySelectorAll('.place-card');
    
    placeCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Arama formu odaklanma
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput && !searchInput.value) {
        searchInput.focus();
    }
});
</script>
@endsection