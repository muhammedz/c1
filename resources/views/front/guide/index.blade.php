@extends('layouts.front')

@section('title', $pageTitle)
@section('meta_description', $pageDescription)

@section('css')
<style>
    .stats-card {
        background: white;
        border-radius: 24px;
        box-shadow: 0 2px 20px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }

    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 2px 20px rgba(0,0,0,0.1);
    }
</style>
@endsection

@section('content')
<!-- Hero Bölümü - Split Layout -->
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
                Çankaya <span class="text-[#e6a23c]">Rehberi</span>
            </h1>
            <p class="text-white/80 text-lg mb-5 max-w-4xl">
                İhtiyacınız olan tüm hizmet ve kurumlara kolayca ulaşın. Zabıta noktalarından sağlık merkezlerine, eğitim kurumlarından kamu hizmetlerine kadar her şey burada.
            </p>
            
            <!-- Arama Kutusu -->
            <form action="{{ route('guide.search') }}" method="GET" class="mt-6 w-full max-w-2xl">
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-1 flex items-center shadow-lg hover:bg-white/20 transition duration-300 border border-white/20">
                    <input 
                        type="text" 
                        name="q" 
                        placeholder="Hangi hizmeti veya kurumu arıyorsunuz?" 
                        class="w-full bg-transparent px-4 py-3 text-white placeholder-white/70 outline-none"
                        value="{{ request('search') }}"
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

<!-- Kategoriler -->
<section class="py-20 bg-white">
    <div class="container max-w-6xl mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Hizmet Kategorileri</h2>
            <div class="w-20 h-1 bg-[#004d2e] mx-auto mb-6"></div>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">Aradığınız hizmeti kolayca bulun</p>
        </div>
        
        @if($categories->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($categories as $category)
                    <a href="{{ route('guide.category', $category->slug) }}" 
                       class="group block bg-white rounded-3xl p-8 border border-gray-100 hover:border-[#004d2e]/20 transition-all duration-300 hover:shadow-xl hover:-translate-y-2">
                        
                        <!-- İkon -->
                        <div class="mb-6">
                            @if($category->icon)
                                @if(Str::startsWith($category->icon, 'fas ') || Str::startsWith($category->icon, 'far ') || Str::startsWith($category->icon, 'fab '))
                                    <div class="w-16 h-16 bg-[#004d2e]/10 group-hover:bg-[#004d2e] rounded-2xl flex items-center justify-center transition-all duration-300">
                                        <i class="{{ $category->icon }} text-2xl text-[#004d2e] group-hover:text-white transition-colors duration-300"></i>
                                    </div>
                                @else
                                    <div class="w-16 h-16 bg-[#004d2e]/10 group-hover:bg-[#004d2e] rounded-2xl flex items-center justify-center transition-all duration-300">
                                        <span class="material-icons text-2xl text-[#004d2e] group-hover:text-white transition-colors duration-300">{{ $category->icon }}</span>
                                    </div>
                                @endif
                            @else
                                <div class="w-16 h-16 bg-[#004d2e]/10 group-hover:bg-[#004d2e] rounded-2xl flex items-center justify-center transition-all duration-300">
                                    <span class="material-icons text-2xl text-[#004d2e] group-hover:text-white transition-colors duration-300">location_on</span>
                                </div>
                            @endif
                        </div>
                        
                        <!-- İçerik -->
                        <div class="mb-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-[#004d2e] transition-colors duration-300">
                                {{ $category->name }}
                            </h3>
                            @if($category->description)
                                <p class="text-gray-600 text-sm leading-relaxed mb-3">
                                    {{ Str::limit($category->description, 80) }}
                                </p>
                            @endif
                            <div class="flex items-center text-sm text-gray-500">
                                <span class="material-icons text-sm mr-1">location_on</span>
                                {{ $category->active_places_count }} konum
                            </div>
                        </div>
                        
                        <!-- Ok İkonu -->
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-[#004d2e] group-hover:text-[#006b3f] transition-colors duration-300">
                                Keşfet
                            </span>
                            <div class="w-8 h-8 bg-[#004d2e]/10 group-hover:bg-[#004d2e] rounded-full flex items-center justify-center transition-all duration-300 group-hover:translate-x-1">
                                <span class="material-icons text-sm text-[#004d2e] group-hover:text-white transition-colors duration-300">arrow_forward</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            
            <!-- Pagination -->
            @if($categories->hasPages())
                <div class="mt-16 flex justify-center">
                    {{ $categories->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-16">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="material-icons text-gray-400 text-3xl">search</span>
                </div>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Henüz kategori bulunmuyor</h3>
                <p class="text-gray-500">Yakında yeni kategoriler eklenecek.</p>
            </div>
        @endif
    </div>
</section>



<!-- İstatistikler -->
<section class="py-16 bg-slate-100">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="stats-card p-8 text-center">
                <div class="w-16 h-16 bg-[#004d2e] rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="material-icons text-white text-2xl">category</span>
                </div>
                <h3 class="text-3xl font-bold text-gray-800 mb-2">{{ $categories->total() }}</h3>
                <p class="text-gray-600 font-medium">Kategori</p>
            </div>
            
            <div class="stats-card p-8 text-center">
                <div class="w-16 h-16 bg-[#004d2e] rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="material-icons text-white text-2xl">location_on</span>
                </div>
                <h3 class="text-3xl font-bold text-gray-800 mb-2">{{ $categories->sum('active_places_count') }}</h3>
                <p class="text-gray-600 font-medium">Kayıtlı Yer</p>
            </div>
            
            <div class="stats-card p-8 text-center">
                <div class="w-16 h-16 bg-[#004d2e] rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="material-icons text-white text-2xl">schedule</span>
                </div>
                <h3 class="text-3xl font-bold text-gray-800 mb-2">7/24</h3>
                <p class="text-gray-600 font-medium">Hizmet</p>
            </div>
        </div>
    </div>
</section>
@endsection

@section('after_scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
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