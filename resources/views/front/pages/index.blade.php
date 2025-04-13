@extends('layouts.front')

@section('title', 'Sayfalar - ' . config('app.name'))

@section('meta_description', 'Tüm sayfaları keşfedin. En güncel içerikler, makaleler ve daha fazlası burada.')

@include('helpers.functions')

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
                <h1 class="text-3xl md:text-5xl font-bold mb-4 text-white leading-tight">Sayfalar</h1>
                <p class="text-lg text-white/80 mb-6 max-w-xl">Tüm içeriklerimizi keşfedin. İlginizi çeken konular hakkında detaylı bilgilere ulaşabilirsiniz.</p>
                
                <!-- Modern Arama Kutusu -->
                <form action="{{ route('pages.index') }}" method="GET" class="mt-8">
                    <div class="bg-white/10 backdrop-blur-md rounded-xl p-1 flex items-center shadow-lg hover:bg-white/20 transition duration-300 border border-white/20">
                        <input 
                            type="text" 
                            name="search" 
                            placeholder="Ne arıyorsunuz?" 
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
                            <div class="text-3xl font-bold text-white mb-1">{{ $pages->total() }}</div>
                            <div class="text-white/70 text-sm">Aktif Sayfa</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-white mb-1">{{ \App\Models\PageCategory::where('is_active', true)->count() }}</div>
                            <div class="text-white/70 text-sm">Sayfa Kategorisi</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-white mb-1">100%</div>
                            <div class="text-white/70 text-sm">Bilgi Güvenliği</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-white mb-1">7/24</div>
                            <div class="text-white/70 text-sm">Erişim</div>
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
                    <a href="{{ route('pages.index') }}" 
                        class="relative px-5 py-2.5 rounded-lg flex items-center {{ request()->routeIs('pages.index') && !request()->query('search') ? 'bg-gradient-to-r from-[#00352b] to-[#005540] text-white font-medium shadow-md' : 'bg-white hover:bg-slate-50 text-gray-700 border border-slate-200 hover:border-[#00352b]/30' }} transition-all duration-300 group">
                        <i class="fas fa-layer-group {{ request()->routeIs('pages.index') && !request()->query('search') ? 'text-white' : 'text-[#00352b] group-hover:text-[#00352b]' }} mr-2"></i>
                        <span class="font-medium">Tüm Sayfalar</span>
                        
                        @if(request()->routeIs('pages.index') && !request()->query('search'))
                        <span class="absolute top-0 right-0 h-2 w-2 bg-white rounded-full transform -translate-y-1 translate-x-1 shadow"></span>
                        @endif
                    </a>
                    
                    @foreach(\App\Models\PageCategory::where('is_active', true)->withCount(['pages' => function($query) {
                        $query->published();
                    }])->having('pages_count', '>', 0)->orderBy('pages_count', 'desc')->take(10)->get() as $category)
                    <a href="{{ route('pages.category', $category->slug) }}" 
                        class="relative px-5 py-2.5 rounded-lg flex items-center {{ request()->is('sayfalar/kategori/'.$category->slug) ? 'bg-gradient-to-r from-[#00352b] to-[#005540] text-white font-medium shadow-md' : 'bg-white hover:bg-slate-50 text-gray-700 border border-slate-200 hover:border-[#00352b]/30' }} transition-all duration-300 group">
                        @if($category->icon)
                            <i class="{{ $category->icon }} {{ request()->is('sayfalar/kategori/'.$category->slug) ? 'text-white' : 'text-[#00352b] group-hover:text-[#00352b]' }} mr-2"></i>
                        @endif
                        <span class="font-medium">{{ $category->name }}</span>
                        
                        @if(request()->is('sayfalar/kategori/'.$category->slug))
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
        @if($pages->isEmpty())
            <div class="bg-white p-8 rounded-lg shadow-md text-center">
                <span class="material-icons text-gray-400 text-5xl mb-4">info</span>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Henüz sayfa bulunmamaktadır</h3>
                <p class="text-gray-600">Lütfen daha sonra tekrar kontrol ediniz.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($pages as $page)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                    <!-- Görsel -->
                    <div class="h-48 overflow-hidden">
                        @if($page->image)
                            <img src="{{ asset($page->image) }}" alt="{{ $page->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-slate-100">
                                <span class="material-icons text-gray-400 text-4xl">article</span>
                            </div>
                        @endif
                    </div>
                    
                    <!-- İçerik -->
                    <div class="p-5">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $page->title }}</h3>
                        <p class="text-gray-600 mb-4 line-clamp-2">{{ $page->summary ?? Str::limit(strip_tags($page->content), 120) }}</p>
                        
                        <!-- Kategoriler -->
                        @if($page->categories->isNotEmpty())
                        <div class="mb-4 flex flex-wrap gap-2">
                            @foreach($page->categories as $category)
                            <a href="{{ route('pages.category', $category->slug) }}" class="text-xs bg-slate-100 hover:bg-[#00352b] hover:text-white px-3 py-1 rounded-full transition-colors text-gray-700">
                                {{ $category->name }}
                            </a>
                            @endforeach
                        </div>
                        @endif
                        
                        <!-- Detay Butonu -->
                        <a href="{{ route('pages.show', $page->slug) }}" class="inline-flex items-center text-[#00352b] hover:underline">
                            Detaylı Bilgi
                            <span class="material-icons text-sm ml-1">arrow_forward</span>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Sayfalama -->
            <div class="mt-12">
                {{ $pages->withQueryString()->links() }}
            </div>
        @endif
    </div>
</section>
@endsection 