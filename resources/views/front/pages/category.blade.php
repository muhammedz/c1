@extends('layouts.front')

@section('title', $category->name)
@section('meta_description', $category->description ?? 'Bu kategorideki tüm içerikleri keşfedin. ' . $category->name . ' kategorisindeki sayfalar, makaleler ve daha fazlası.')

@include('helpers.functions')

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
        <div class="flex flex-col items-center text-center">
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">{{ $category->name }}</h1>
            <p class="text-white/80 text-lg mb-5 max-w-2xl">{{ $category->description ?? 'Bu kategorideki içerikleri inceleyebilirsiniz.' }}</p>
            
            <!-- Modern Arama Kutusu -->
            <form action="{{ route('pages.category', $category->slug) }}" method="GET" class="mt-6 w-full max-w-2xl">
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-1 flex items-center shadow-lg hover:bg-white/20 transition duration-300 border border-white/20">
                    <input 
                        type="text" 
                        name="search" 
                        placeholder="Bu kategorideki hangi sayfayı arıyorsunuz?" 
                        class="w-full bg-transparent px-4 py-3 text-white placeholder-white/70 outline-none"
                        value="{{ request()->get('search') }}"
                    >
                    <button type="submit" class="bg-[#e6a23c] hover:bg-[#e6a23c]/90 text-white font-medium px-6 py-3 rounded-md transition duration-300 flex items-center shadow-lg shadow-[#e6a23c]/20">
                        <span class="material-icons mr-1">search</span>
                        Ara
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- Ana İçerik -->
<section class="py-12 bg-slate-100">
    <div class="container max-w-7xl mx-auto px-4">
        @if($pages->isEmpty())
            <div class="bg-white p-8 rounded-lg shadow-md text-center">
                <span class="material-icons text-gray-400 text-5xl mb-4">info</span>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Bu kategoride henüz içerik bulunmamaktadır</h3>
                <p class="text-gray-600 mb-4">{{ $category->name }} kategorisinde henüz sayfa eklenmemiştir. Lütfen daha sonra tekrar kontrol ediniz veya başka bir kategori seçiniz.</p>
                <a href="{{ route('pages.index') }}" class="inline-flex items-center px-4 py-2 bg-[#007b32] hover:bg-[#00352b] text-white font-medium rounded-lg transition-colors duration-300">
                    <span class="material-icons mr-2">arrow_back</span>
                    Diğer Sayfalara Gözat
                </a>
            </div>
        @else


            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($pages as $page)
                <a href="{{ route('pages.show', $page->slug) }}" class="page-card-enhanced group block">
                    <div class="bg-white hover:bg-gradient-to-br hover:from-white hover:to-gray-50 border border-gray-200 hover:border-[#00352b] p-6 rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 min-h-[120px] flex items-center justify-center relative overflow-hidden">
                        <!-- Dekoratif köşe elementi -->
                        <div class="absolute top-0 right-0 w-8 h-8 bg-gradient-to-br from-[#00352b]/10 to-transparent rounded-bl-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        
                        <!-- Başlık -->
                        <div class="text-center relative z-10">
                            <div class="flex items-center justify-center mb-2">
                                <span class="material-icons text-[#00352b] group-hover:text-[#007b32] text-xl transition-colors duration-300">description</span>
                            </div>
                            <h3 class="font-semibold text-[#00352b] group-hover:text-[#007b32] text-base leading-snug transition-colors duration-300 px-2">{{ $page->title }}</h3>
                        </div>
                        
                        <!-- Hover efekti için arka plan -->
                        <div class="absolute inset-0 bg-gradient-to-r from-[#00352b]/0 to-[#007b32]/0 group-hover:from-[#00352b]/5 group-hover:to-[#007b32]/5 transition-all duration-300 rounded-xl"></div>
                    </div>
                </a>
                @endforeach
            </div>
            
            <!-- Alt bilgi alanı -->
            <div class="mt-8 bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <div class="text-center">
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Aradığınız sayfayı bulamadınız mı?</h3>
                    <p class="text-gray-600 mb-4">Tüm sayfalarımızı görüntülemek için sayfalar ana bölümünü ziyaret edebilirsiniz.</p>
                    <a href="{{ route('pages.index') }}" class="inline-flex items-center px-6 py-3 bg-[#007b32] hover:bg-[#00352b] text-white font-medium rounded-lg transition-colors duration-300">
                        <span class="material-icons mr-2">list</span>
                        Tüm Sayfalar
                    </a>
                </div>
            </div>
        @endif
    </div>
</section>

<style>
.page-card-enhanced {
    min-height: 120px;
}

.page-card-enhanced:hover {
    box-shadow: 0 20px 25px -5px rgba(0, 53, 43, 0.1), 0 10px 10px -5px rgba(0, 53, 43, 0.04);
}

.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}

.scrollbar-hide::-webkit-scrollbar {
    display: none;
}

/* Subtle animation for icons */
@keyframes gentle-pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

.page-card-enhanced:hover .material-icons {
    animation: gentle-pulse 2s ease-in-out infinite;
}
</style>
@endsection 