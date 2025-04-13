@extends('layouts.front')

@section('title', $category->name . ' - ' . config('app.name'))
@section('meta_description', $category->description ?? 'Bu kategorideki tüm içerikleri keşfedin. ' . $category->name . ' kategorisindeki sayfalar, makaleler ve daha fazlası.')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Kategori Başlığı -->
        <div class="mb-10 text-center">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
                @if($category->icon)
                    <i class="{{ $category->icon }} mr-2 text-primary"></i>
                @endif
                {{ $category->name }}
            </h1>
            
            @if($category->description)
                <p class="text-gray-600 max-w-3xl mx-auto">{{ $category->description }}</p>
            @endif
            
            <div class="mt-4 flex justify-center items-center text-sm text-gray-500">
                <span class="bg-primary bg-opacity-10 text-primary px-3 py-1 rounded-full">
                    {{ $pages->total() }} içerik
                </span>
            </div>
        </div>
        
        <!-- Filtre ve Sıralama -->
        <div class="mb-8 flex flex-col md:flex-row justify-between items-center bg-gray-50 rounded-lg p-4">
            <!-- Arama Formu -->
            <form action="{{ route('pages.category', $category->slug) }}" method="GET" class="w-full md:w-auto mb-4 md:mb-0">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Kategori içinde ara..." 
                           class="w-full md:w-80 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                    <button type="submit" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                        <i class="fas fa-search"></i>
                    </button>
                    @if(request('search'))
                        <a href="{{ route('pages.category', $category->slug) }}" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </div>
            </form>
            
            <!-- Sıralama Seçenekleri -->
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-700">Sırala:</span>
                <a href="{{ route('pages.category', ['slug' => $category->slug, 'sort' => 'newest', 'search' => request('search')]) }}" 
                   class="text-sm px-3 py-1.5 rounded {{ request('sort', 'newest') == 'newest' ? 'bg-primary text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
                    En Yeni
                </a>
                <a href="{{ route('pages.category', ['slug' => $category->slug, 'sort' => 'oldest', 'search' => request('search')]) }}" 
                   class="text-sm px-3 py-1.5 rounded {{ request('sort') == 'oldest' ? 'bg-primary text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
                    En Eski
                </a>
                <a href="{{ route('pages.category', ['slug' => $category->slug, 'sort' => 'popular', 'search' => request('search')]) }}" 
                   class="text-sm px-3 py-1.5 rounded {{ request('sort') == 'popular' ? 'bg-primary text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
                    Popüler
                </a>
            </div>
        </div>
        
        <!-- Arama Sonuçları Bildirimi -->
        @if(request('search'))
            <div class="mb-6 bg-blue-50 border-l-4 border-blue-400 p-4 rounded-md">
                <p class="text-blue-700">
                    <span class="font-medium">"{{ request('search') }}"</span> araması için 
                    <span class="font-medium">{{ $pages->total() }}</span> sonuç bulundu.
                </p>
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
                            <!-- Kategori İsmi (Alt kategorileri gösterme) -->
                            @if($page->categories->count() > 0 && $page->categories->where('id', '!=', $category->id)->count() > 0)
                                <div class="flex flex-wrap gap-2 mb-3">
                                    @foreach($page->categories->where('id', '!=', $category->id)->take(2) as $cat)
                                        <a href="{{ route('pages.category', $cat->slug) }}" class="text-xs text-primary hover:underline">
                                            #{{ $cat->name }}
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
                    
                    <a href="{{ route('pages.category', $category->slug) }}" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded hover:bg-primary-dark transition">
                        <i class="fas fa-sync-alt mr-2"></i> Tüm İçerikleri Göster
                    </a>
                @else
                    <p class="text-gray-600">
                        Bu kategoride henüz içerik bulunmuyor. Lütfen daha sonra tekrar kontrol edin.
                    </p>
                @endif
            </div>
        @endif
        
        <!-- Diğer Kategoriler -->
        <div class="mt-16 mb-8">
            <h3 class="text-2xl font-bold text-gray-800 mb-6 text-center">Diğer Kategoriler</h3>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach(\App\Models\PageCategory::where('id', '!=', $category->id)->where('is_active', true)->orderBy('name')->get() as $otherCategory)
                    <a href="{{ route('pages.category', $otherCategory->slug) }}" class="bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md hover:border-primary transition-all text-center">
                        @if($otherCategory->icon)
                            <div class="text-2xl text-primary mb-2">
                                <i class="{{ $otherCategory->icon }}"></i>
                            </div>
                        @endif
                        
                        <h4 class="font-medium text-gray-800">{{ $otherCategory->name }}</h4>
                        
                        @php
                            $pageCount = \App\Models\Page::published()->whereHas('categories', function($query) use ($otherCategory) {
                                $query->where('category_id', $otherCategory->id);
                            })->count();
                        @endphp
                        
                        <div class="text-xs text-gray-500 mt-1">
                            {{ $pageCount }} içerik
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection 