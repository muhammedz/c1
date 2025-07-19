@extends('layouts.front')

@section('title', 'Çankaya Evleri')

@section('css')
<style>
    /* İçerik Bölümü */
    .houses-portal {
        padding: 3rem 0;
        background-color: #f8f9fa;
    }
    
    /* Çankaya Evleri Grid */
    .houses-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 1.5rem;
        margin-bottom: 4rem;
    }
    
    @media (max-width: 768px) {
        .houses-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }
        
        /* Mobilde padding'leri küçült */
        .houses-portal {
            padding: 2rem 0;
        }
    }

    @media (min-width: 769px) and (max-width: 1024px) {
        .houses-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    /* Line clamp utility */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background-color: white;
        border-radius: 1rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
    }
    
    .empty-state i {
        font-size: 3rem;
        color: #d1d5db;
        margin-bottom: 1rem;
    }
    
    .empty-state h3 {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }
    
    .empty-state p {
        color: #6b7280;
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
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-10 relative z-10">
        <!-- Breadcrumb -->
        <nav class="mb-4">
            <div class="flex items-center space-x-2 text-white/80 text-sm">
                <a href="{{ route('front.home') }}" class="hover:text-white transition-colors">
                    <i class="fas fa-home"></i>
                    Anasayfa
                </a>
                <span>/</span>
                <span class="text-white">Çankaya Evleri</span>
            </div>
        </nav>
        
        <div class="flex flex-col md:flex-row items-center justify-between">
            <!-- Sol taraf - başlık ve açıklama -->
            <div class="w-full md:w-1/2 mb-6 md:mb-0 md:pr-8">
                <div class="inline-block bg-white/10 backdrop-blur-sm px-4 py-1 rounded-full text-white/90 text-sm mb-3 border border-white/10">
                    <span class="material-icons text-xs align-middle mr-1">home</span>
                    <span>Eğitim Merkezleri</span>
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">
                    <span class="text-[#e6a23c]">Çankaya</span> Evleri
                </h1>
                <p class="text-white/80 text-lg mb-0">
                    Çankaya Belediyesi'nin farklı lokasyonlardaki eğitim merkezlerinde düzenlenen 
                    kurslar ve etkinlikler hakkında bilgi alın.
                </p>
            </div>
            
            <!-- Sağ taraf - arama kutusu -->
            <div class="w-full md:w-1/2 md:pl-8">
                <div class="bg-white/10 backdrop-blur-md rounded-xl p-4 border border-white/20 shadow-xl">
                    <h3 class="text-lg text-white font-medium mb-3">Çankaya Evi Ara</h3>
                    
                    <form action="{{ route('cankaya-houses.index') }}" method="GET">
                        <div class="mb-3">
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-white/60">
                                    <span class="material-icons">search</span>
                                </span>
                                <input 
                                    type="text" 
                                    name="search" 
                                    placeholder="Çankaya Evi adı yazın..." 
                                    class="w-full bg-white/10 border border-white/20 text-white placeholder-white/60 pl-10 pr-4 py-3 rounded-xl focus:ring-2 focus:ring-white/30 outline-none"
                                    value="{{ request()->get('search') }}"
                                >
                            </div>
                        </div>
                        <button type="submit" class="w-full bg-white hover:bg-gray-100 text-[#00352b] font-semibold py-3 px-5 rounded-xl transition-all duration-300 flex items-center justify-center">
                            <span class="material-icons mr-2">search</span>
                            Ara
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Ana İçerik -->
<section class="houses-portal">
    <div class="container">
        @if($cankayaHouses->count() > 0)
            <!-- Arama Sonuçları Bilgisi -->
            @if(request()->get('search'))
                <div class="mb-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-gray-800">
                            "{{ request()->get('search') }}" için Arama Sonuçları
                        </h2>
                        <span class="text-sm text-gray-600 bg-white px-3 py-1 rounded-full border border-gray-200">
                            {{ $cankayaHouses->count() }} ev bulundu
                        </span>
                    </div>
                </div>
            @endif
            
            <!-- Çankaya Evleri Grid -->
            <div class="houses-grid">
                @foreach($cankayaHouses as $house)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 hover:shadow-lg hover:border-green-200 transition-all duration-300 overflow-hidden group">
                        <!-- Resim -->
                        <div class="aspect-video relative overflow-hidden">
                            <a href="{{ route('cankaya-houses.show', $house) }}" class="relative z-10 block w-full h-full">
                                @if($house->first_image)
                                    <img src="{{ $house->first_image }}" 
                                         alt="{{ $house->name }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 cursor-pointer">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-[#00352b] to-[#20846c] flex items-center justify-center">
                                        <i class="fas fa-home text-white text-4xl"></i>
                                    </div>
                                @endif
                            </a>
                            <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                        </div>
                        
                        <!-- İçerik -->
                        <div class="p-3 md:p-5">
                            <!-- Başlık -->
                            <a href="{{ route('cankaya-houses.show', $house) }}">
                                <h3 class="text-sm md:text-lg font-bold text-gray-900 mb-2 md:mb-3 line-clamp-2 hover:text-green-700 transition-colors duration-300 cursor-pointer">
                                    {{ $house->name }}
                                </h3>
                            </a>
                            
                            <!-- İletişim Bilgileri -->
                            <div class="space-y-1 md:space-y-2 mb-3 md:mb-4">
                                @if($house->address)
                                    <div class="flex items-start text-xs md:text-sm text-gray-600">
                                        <i class="fas fa-map-marker-alt text-green-600 mt-1 mr-2 md:mr-3 text-xs"></i>
                                        <span class="flex-1 leading-relaxed">{{ $house->address }}</span>
                                    </div>
                                @endif
                                
                                @if($house->phone)
                                    <div class="flex items-center text-xs md:text-sm text-gray-600">
                                        <i class="fas fa-phone text-green-600 mr-2 md:mr-3 text-xs"></i>
                                        <span>{{ $house->phone }}</span>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Aksiyon Butonları -->
                            <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                                <a href="{{ route('cankaya-houses.show', $house) }}" 
                                   class="inline-flex items-center text-xs md:text-sm font-medium text-green-700 hover:text-green-800 transition-colors">
                                    <span>Detaylar</span>
                                    <i class="fas fa-arrow-right ml-1 md:ml-2 text-xs"></i>
                                </a>
                                
                                <div class="flex items-center gap-1 md:gap-2">
                                    @if($house->location_link)
                                        <a href="{{ $house->location_link }}" 
                                           target="_blank" 
                                           class="inline-flex items-center justify-center w-7 h-7 md:w-9 md:h-9 bg-green-50 hover:bg-green-100 text-green-700 hover:text-green-800 rounded-lg transition-all duration-200 hover:scale-105"
                                           title="Haritada Göster">
                                            <i class="fas fa-map-marker-alt text-xs"></i>
                                        </a>
                                    @endif
                                    
                                    @if($house->phone)
                                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $house->phone) }}" 
                                           class="inline-flex items-center justify-center w-7 h-7 md:w-9 md:h-9 bg-blue-50 hover:bg-blue-100 text-blue-700 hover:text-blue-800 rounded-lg transition-all duration-200 hover:scale-105"
                                           title="Telefon Aç">
                                            <i class="fas fa-phone text-xs"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-home"></i>
                <h3>Henüz Çankaya Evi Eklenmemiş</h3>
                <p>Şu anda görüntülenecek Çankaya Evi bulunmamaktadır.</p>
            </div>
        @endif
    </div>
</section>


@endsection 