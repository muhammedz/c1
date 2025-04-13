@extends('layouts.front')

@section('title', 'Sayfalar - ' . config('app.name'))

@section('meta_description', 'Tüm sayfaları keşfedin. En güncel içerikler, makaleler ve daha fazlası burada.')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Sayfa Başlığı -->
        <div class="mb-10 text-center">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Sayfalar</h1>
            <p class="text-gray-600 max-w-3xl mx-auto">Tüm içeriklerimizi keşfedin. İlginizi çeken konular hakkında detaylı bilgilere ulaşabilirsiniz.</p>
        </div>
        
        <!-- Kategori ve Filtre Bölümü -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 md:p-6 mb-8">
            <!-- Popüler Kategoriler -->
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Kategoriler</h2>
                <div class="flex flex-wrap gap-2">
                    @foreach(\App\Models\PageCategory::where('is_active', true)->withCount(['pages' => function($query) {
                        $query->published();
                    }])->having('pages_count', '>', 0)->orderBy('pages_count', 'desc')->take(10)->get() as $category)
                        <a href="{{ route('pages.category', $category->slug) }}" class="px-3 py-1.5 text-sm bg-gray-50 hover:bg-primary hover:text-white rounded-full border border-gray-200 transition-colors flex items-center">
                            @if($category->icon)
                                <i class="{{ $category->icon }} mr-1.5"></i>
                            @endif
                            {{ $category->name }}
                            <span class="ml-1.5 text-xs opacity-70">({{ $category->pages_count }})</span>
                        </a>
                    @endforeach
                    
                    <a href="#all-categories" class="px-3 py-1.5 text-sm text-primary hover:bg-primary hover:text-white rounded-full border border-primary border-opacity-30 transition-colors">
                        Tüm Kategoriler <i class="fas fa-chevron-down ml-1 text-xs"></i>
                    </a>
                </div>
            </div>
            
            <!-- Filtreleme ve Arama -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Arama Formu -->
                <div class="md:col-span-2">
                    <form action="{{ route('pages.index') }}" method="GET" class="flex">
                        <div class="relative flex-grow">
                            <input type="text" name="search" value="{{ request('search') }}" 
                                placeholder="Sayfalarda ara..." 
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-l focus:ring-primary focus:border-primary">
                            <button type="submit" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                        <button type="submit" class="bg-primary text-white px-4 py-2 rounded-r hover:bg-primary-dark transition">
                            Ara
                        </button>
                    </form>
                </div>
                
                <!-- Sıralama -->
                <div class="flex items-center justify-end gap-2">
                    <span class="text-sm text-gray-700 whitespace-nowrap">Sırala:</span>
                    <div class="flex-grow">
                        <select name="sort" id="sort" onchange="window.location.href=this.value" class="w-full pl-3 pr-8 py-2 border border-gray-300 rounded focus:ring-primary focus:border-primary appearance-none bg-white">
                            <option value="{{ route('pages.index', ['sort' => 'newest', 'search' => request('search')]) }}" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>
                                En Yeni
                            </option>
                            <option value="{{ route('pages.index', ['sort' => 'oldest', 'search' => request('search')]) }}" {{ request('sort') == 'oldest' ? 'selected' : '' }}>
                                En Eski
                            </option>
                            <option value="{{ route('pages.index', ['sort' => 'popular', 'search' => request('search')]) }}" {{ request('sort') == 'popular' ? 'selected' : '' }}>
                                En Popüler
                            </option>
                            <option value="{{ route('pages.index', ['sort' => 'a-z', 'search' => request('search')]) }}" {{ request('sort') == 'a-z' ? 'selected' : '' }}>
                                A-Z
                            </option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Arama Sonuçları -->
        @if(request('search'))
            <div class="mb-6 bg-blue-50 border-l-4 border-blue-400 p-4 rounded-md">
                <p class="text-blue-700">
                    <span class="font-medium">"{{ request('search') }}"</span> araması için 
                    <span class="font-medium">{{ $pages->total() }}</span> sonuç bulundu.
                    <a href="{{ route('pages.index') }}" class="underline hover:no-underline ml-1">Filtreleri temizle</a>
                </p>
            </div>
        @endif
        
        <!-- Öne Çıkan İçerik (Eğer arama yapılmadıysa) -->
        @if(!request('search') && !request('sort') && isset($featuredPage) && $featuredPage)
            <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden mb-10">
                <div class="grid grid-cols-1 md:grid-cols-2">
                    <!-- Sol Taraf - Görsel -->
                    <div class="bg-gray-100 h-64 md:h-auto overflow-hidden relative">
                        @if($featuredPage->image)
                            <img src="{{ asset($featuredPage->image) }}" alt="{{ $featuredPage->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                <i class="fas fa-file-alt text-5xl"></i>
                            </div>
                        @endif
                        
                        <!-- Kategori Etiketleri -->
                        <div class="absolute top-3 left-3 flex flex-wrap gap-2">
                            @foreach($featuredPage->categories->take(2) as $category)
                                <a href="{{ route('pages.category', $category->slug) }}" class="bg-white bg-opacity-90 text-primary text-xs px-3 py-1 rounded-full hover:bg-primary hover:text-white transition-colors">
                                    {{ $category->name }}
                                </a>
                            @endforeach
                        </div>
                        
                        <!-- Öne Çıkan İşareti -->
                        <div class="absolute top-3 right-3">
                            <span class="bg-primary text-white text-xs px-3 py-1 rounded-full flex items-center">
                                <i class="fas fa-star mr-1"></i> Öne Çıkan
                            </span>
                        </div>
                    </div>
                    
                    <!-- Sağ Taraf - İçerik -->
                    <div class="p-6 md:p-8 flex flex-col">
                        <h2 class="text-2xl font-bold text-gray-800 mb-4 hover:text-primary transition-colors">
                            <a href="{{ route('pages.show', $featuredPage->slug) }}">
                                {{ $featuredPage->title }}
                            </a>
                        </h2>
                        
                        @if($featuredPage->summary)
                            <p class="text-gray-600 mb-6">
                                {{ Str::limit($featuredPage->summary, 200) }}
                            </p>
                        @else
                            <p class="text-gray-600 mb-6">
                                {{ Str::limit(strip_tags($featuredPage->content), 200) }}
                            </p>
                        @endif
                        
                        <div class="flex items-center text-sm text-gray-500 mb-6 mt-auto">
                            <span>
                                <i class="far fa-calendar-alt mr-1"></i>
                                {{ $featuredPage->published_at ? $featuredPage->published_at->format('d.m.Y') : $featuredPage->created_at->format('d.m.Y') }}
                            </span>
                            
                            <span class="mx-2">•</span>
                            
                            <span>
                                <i class="far fa-eye mr-1"></i>
                                {{ $featuredPage->view_count ?? 0 }} görüntülenme
                            </span>
                        </div>
                        
                        <a href="{{ route('pages.show', $featuredPage->slug) }}" class="inline-flex items-center px-5 py-2 bg-primary text-white rounded hover:bg-primary-dark transition-colors self-start">
                            Devamını Oku <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        @endif
        
        <!-- Sayfalar Listesi -->
        @if($pages->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
                @foreach($pages as $page)
                    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-md transition-shadow group">
                        <!-- Sayfa Görseli -->
                        <a href="{{ route('pages.show', $page->slug) }}" class="block aspect-[3/2] overflow-hidden bg-gray-100">
                            @if($page->image)
                                <img src="{{ asset($page->image) }}" alt="{{ $page->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    <i class="fas fa-file-alt text-4xl"></i>
                                </div>
                            @endif
                        </a>
                        
                        <div class="p-5">
                            <!-- Kategori -->
                            @if($page->categories->count() > 0)
                                <div class="flex flex-wrap gap-2 mb-3">
                                    @foreach($page->categories->take(2) as $category)
                                        <a href="{{ route('pages.category', $category->slug) }}" class="text-xs text-primary hover:underline">
                                            #{{ $category->name }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                            
                            <!-- Başlık -->
                            <h2 class="text-xl font-bold text-gray-800 mb-3 line-clamp-2 group-hover:text-primary transition-colors">
                                <a href="{{ route('pages.show', $page->slug) }}">
                                    {{ $page->title }}
                                </a>
                            </h2>
                            
                            <!-- Özet -->
                            @if($page->summary)
                                <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                    {{ $page->summary }}
                                </p>
                            @endif
                            
                            <!-- Alt Bilgiler -->
                            <div class="flex justify-between items-center text-xs text-gray-500 mt-auto">
                                <span>
                                    <i class="far fa-calendar-alt mr-1"></i>
                                    {{ $page->published_at ? $page->published_at->format('d.m.Y') : $page->created_at->format('d.m.Y') }}
                                </span>
                                
                                <span>
                                    <i class="far fa-eye mr-1"></i>
                                    {{ $page->view_count ?? 0 }} görüntülenme
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Sayfalama -->
            <div class="mt-8">
                {{ $pages->withQueryString()->links() }}
            </div>
        @else
            <div class="bg-gray-50 rounded-lg p-8 text-center">
                <div class="text-gray-400 text-6xl mb-4">
                    <i class="far fa-folder-open"></i>
                </div>
                
                <h3 class="text-2xl font-bold text-gray-700 mb-2">İçerik Bulunamadı</h3>
                
                @if(request('search'))
                    <p class="text-gray-600 mb-6">
                        "{{ request('search') }}" aramanıza uygun içerik bulunamadı. Farklı bir arama terimi deneyebilir veya tüm içerikleri görmek için aramayı temizleyebilirsiniz.
                    </p>
                    
                    <a href="{{ route('pages.index') }}" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded hover:bg-primary-dark transition">
                        <i class="fas fa-sync-alt mr-2"></i> Tüm İçerikleri Göster
                    </a>
                @else
                    <p class="text-gray-600">
                        Henüz içerik bulunmuyor. Lütfen daha sonra tekrar kontrol edin.
                    </p>
                @endif
            </div>
        @endif
        
        <!-- Tüm Kategoriler Bölümü -->
        <div id="all-categories" class="mt-16 mb-8">
            <h3 class="text-2xl font-bold text-gray-800 mb-6 text-center">Tüm Kategoriler</h3>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach(\App\Models\PageCategory::where('is_active', true)->withCount(['pages' => function($query) {
                    $query->published();
                }])->orderBy('name')->get() as $category)
                    <a href="{{ route('pages.category', $category->slug) }}" class="bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md hover:border-primary transition-all text-center">
                        @if($category->icon)
                            <div class="text-2xl text-primary mb-2">
                                <i class="{{ $category->icon }}"></i>
                            </div>
                        @endif
                        
                        <h4 class="font-medium text-gray-800">{{ $category->name }}</h4>
                        
                        <div class="text-xs text-gray-500 mt-1">
                            {{ $category->pages_count }} içerik
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection 