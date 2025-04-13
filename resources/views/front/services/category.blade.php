@extends('layouts.front')

@section('title', $category->name . ' - Hizmetlerimiz')

@section('meta_description', $category->name . ' kategorisindeki profesyonel hizmetlerimiz hakkında bilgi alın')

@section('content')
<!-- Hero Bölümü - Yeniden Tasarlandı -->
<section class="relative bg-[#00352b] pt-28 pb-16 overflow-hidden">
    <!-- Arka Plan Desenleri -->
    <div class="absolute inset-0 overflow-hidden opacity-10">
        <div class="absolute -right-10 -top-10 w-64 h-64 rounded-full bg-white"></div>
        <div class="absolute left-1/3 bottom-0 w-96 h-96 rounded-full bg-white"></div>
    </div>
    
    <div class="container max-w-7xl mx-auto px-4 relative z-10">
        <div class="flex flex-col md:flex-row justify-between gap-8 items-center">
            <!-- Sol Taraf: Başlık ve Açıklama -->
            <div class="md:w-1/2">
                <h1 class="text-3xl md:text-5xl font-bold mb-4 text-white leading-tight">{{ $category->name }}</h1>
                <p class="text-lg text-white/80 mb-6 max-w-xl">{{ $category->description ?? 'Bu kategorideki hizmetlerimizi inceleyebilirsiniz' }}</p>
                
                <!-- Modern Arama Kutusu -->
                <form action="{{ route('services.category', $category->slug) }}" method="GET" class="mt-8">
                    <div class="bg-white/10 backdrop-blur-md rounded-xl p-1 flex items-center shadow-lg hover:bg-white/20 transition duration-300 border border-white/20">
                        <input 
                            type="text" 
                            name="search" 
                            placeholder="Hangi hizmeti arıyorsunuz?" 
                            class="w-full bg-transparent px-4 py-3 text-white placeholder-white/70 outline-none"
                            value="{{ request()->get('search') }}"
                        >
                        <button type="submit" class="bg-white hover:bg-gray-100 text-[#00352b] font-medium px-6 py-3 rounded-lg transition duration-300 flex items-center">
                            <span class="material-icons mr-1">search</span>
                            Ara
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Sağ Taraf: İstatistikler veya Görsel -->
            <div class="md:w-1/2 flex justify-end">
                <div class="bg-white/10 backdrop-blur-md p-6 rounded-xl border border-white/20 shadow-lg">
                    <div class="grid grid-cols-2 gap-6">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-white mb-1">{{ $services->total() }}</div>
                            <div class="text-white/70 text-sm">Aktif Hizmet</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-white mb-1">{{ $categories->count() }}</div>
                            <div class="text-white/70 text-sm">Hizmet Kategorisi</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-white mb-1">100%</div>
                            <div class="text-white/70 text-sm">Müşteri Memnuniyeti</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-white mb-1">7/24</div>
                            <div class="text-white/70 text-sm">Destek</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Kategori Filtreleme - Geliştirilmiş Tasarım -->
<section class="py-5 bg-gradient-to-r from-white to-slate-50 shadow-md border-b border-slate-200">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <span class="hidden md:inline-block text-[#00352b] font-semibold mr-4">Kategoriler:</span>
            </div>
            
            <div class="overflow-x-auto scrollbar-hide flex-grow">
                <div class="flex items-center space-x-3 min-w-max py-1">
                    <a href="{{ route('services.index') }}" 
                        class="relative px-5 py-2.5 rounded-lg flex items-center {{ request()->routeIs('services.index') && !request()->query('search') ? 'bg-gradient-to-r from-[#00352b] to-[#005540] text-white font-medium shadow-md' : 'bg-white hover:bg-slate-50 text-gray-700 border border-slate-200 hover:border-[#00352b]/30' }} transition-all duration-300 group">
                        <i class="fas fa-layer-group {{ request()->routeIs('services.index') && !request()->query('search') ? 'text-white' : 'text-[#00352b] group-hover:text-[#00352b]' }} mr-2"></i>
                        <span class="font-medium">Tüm Hizmetler</span>
                        
                        @if(request()->routeIs('services.index') && !request()->query('search'))
                        <span class="absolute top-0 right-0 h-2 w-2 bg-white rounded-full transform -translate-y-1 translate-x-1 shadow"></span>
                        @endif
                    </a>
                    
                    @foreach($categories as $cat)
                    <a href="{{ route('services.category', $cat->slug) }}" 
                        class="relative px-5 py-2.5 rounded-lg flex items-center {{ $cat->id == $category->id ? 'bg-gradient-to-r from-[#00352b] to-[#005540] text-white font-medium shadow-md' : 'bg-white hover:bg-slate-50 text-gray-700 border border-slate-200 hover:border-[#00352b]/30' }} transition-all duration-300 group">
                        @if($cat->icon)
                            <i class="{{ $cat->icon }} {{ $cat->id == $category->id ? 'text-white' : 'text-[#00352b] group-hover:text-[#00352b]' }} mr-2"></i>
                        @endif
                        <span class="font-medium">{{ $cat->name }}</span>
                        
                        @if($cat->id == $category->id)
                        <span class="absolute top-0 right-0 h-2 w-2 bg-white rounded-full transform -translate-y-1 translate-x-1 shadow"></span>
                        @endif
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Ana İçerik - Mevcut tasarıma uygun kart yapısı -->
<section class="py-12 bg-slate-100">
    <div class="container max-w-7xl mx-auto px-4">
        @if($services->isEmpty())
            <div class="bg-white p-8 rounded-lg shadow-md text-center">
                <span class="material-icons text-gray-400 text-5xl mb-4">info</span>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Bu kategoride henüz hizmet bulunmamaktadır</h3>
                <p class="text-gray-600">Lütfen daha sonra tekrar kontrol ediniz veya başka bir kategori seçiniz.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($services as $service)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                    <!-- Görsel -->
                    <div class="h-48 overflow-hidden">
                        @if($service->image)
                            <img src="{{ asset('storage/' . str_replace('/storage/', '', $service->image)) }}" alt="{{ $service->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-slate-100">
                                <span class="material-icons text-gray-400 text-4xl">extension</span>
                            </div>
                        @endif
                    </div>
                    
                    <!-- İçerik -->
                    <div class="p-5">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $service->title }}</h3>
                        <p class="text-gray-600 mb-4 line-clamp-2">{{ $service->summary }}</p>
                        
                        <!-- Kategoriler -->
                        @if($service->categories->isNotEmpty())
                        <div class="mb-4 flex flex-wrap gap-2">
                            @foreach($service->categories as $cat)
                            <a href="{{ route('services.category', $cat->slug) }}" class="text-xs bg-slate-100 hover:bg-[#00352b] hover:text-white px-3 py-1 rounded-full transition-colors text-gray-700 {{ $cat->id == $category->id ? 'bg-[#00352b] text-white' : '' }}">
                                {{ $cat->name }}
                            </a>
                            @endforeach
                        </div>
                        @endif
                        
                        <!-- Detay Butonu -->
                        <a href="{{ route('services.show', $service->slug) }}" class="inline-flex items-center text-[#00352b] hover:underline">
                            Detaylı Bilgi
                            <span class="material-icons text-sm ml-1">arrow_forward</span>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Sayfalama -->
            <div class="mt-12">
                {{ $services->links() }}
            </div>
        @endif
    </div>
</section>
@endsection 