@extends('layouts.front')

@section('title', 'Sayfalar - ' . config('app.name'))

@section('meta_description', 'Tüm sayfaları keşfedin. En güncel içerikler, makaleler ve daha fazlası burada.')

@include('helpers.functions')

@php
    $pageSettings = \App\Models\PageSetting::getSettings();
@endphp

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
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 md:py-10 relative z-10">
        <div class="flex flex-col md:flex-row items-center justify-between">
            <!-- Sol taraf - başlık ve açıklama -->
            <div class="w-full md:w-1/2 mb-6 md:mb-0 md:pr-8">
                @if(!empty($pageSettings->hero_badge_text))
                <div class="inline-flex items-center px-3 py-1 rounded-full bg-white/10 text-white/90 text-sm border border-white/10 mb-3">
                    <span class="material-icons text-xs mr-1">auto_stories</span>
                    <span>{{ $pageSettings->hero_badge_text }}</span>
                </div>
                @endif
                
                @if(!empty($pageSettings->hero_title))
                <h1 class="text-3xl md:text-4xl font-extrabold text-white leading-tight mb-3">
                    @if(!empty($pageSettings->hero_title_highlight))
                        {!! str_replace($pageSettings->hero_title_highlight, '<span class="text-[#e6a23c]">' . $pageSettings->hero_title_highlight . '</span>', $pageSettings->hero_title) !!}
                    @else
                        {{ $pageSettings->hero_title }}
                    @endif
                </h1>
                @endif
                
                @if(!empty($pageSettings->hero_description))
                <p class="text-white/80 text-base mb-0 max-w-lg">
                    {{ $pageSettings->hero_description }}
                </p>
                @endif
            </div>
            
            <!-- Sağ taraf - arama kutusu -->
            <div class="w-full md:w-1/2 md:pl-8">
                <div class="bg-white/10 backdrop-blur-md rounded-xl p-4 border border-white/20 shadow-xl">
                    @if(!empty($pageSettings->search_title))
                    <h3 class="text-lg text-white font-medium mb-3">{{ $pageSettings->search_title }}</h3>
                    @endif
                    
                    <form action="{{ route('pages.index') }}" method="GET">
                        <div class="mb-3">
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-white/60">
                                    <span class="material-icons">search</span>
                                </span>
                                <input 
                                    type="text" 
                                    name="search" 
                                    placeholder="{{ !empty($pageSettings->search_placeholder) ? $pageSettings->search_placeholder : 'Ara...' }}" 
                                    class="w-full bg-white/10 border border-white/20 text-white placeholder-white/60 pl-10 pr-4 py-3 rounded-xl focus:ring-2 focus:ring-white/30 outline-none"
                                    value="{{ request()->get('search') }}"
                                >
                            </div>
                        </div>
                        <button type="submit" class="w-full bg-white hover:bg-gray-100 text-[#00352b] font-semibold py-3 px-5 rounded-xl transition-all duration-300 flex items-center justify-center">
                            <span class="material-icons mr-2">search</span>
                            {{ !empty($pageSettings->search_button_text) ? $pageSettings->search_button_text : 'Ara' }}
                        </button>
                        
                        @if(!empty($pageSettings->popular_searches_title) && is_array($pageSettings->popular_searches) && count($pageSettings->popular_searches) > 0)
                            <div class="mt-2 text-white/60 text-xs text-center">
                                {{ $pageSettings->popular_searches_title }}
                                @foreach($pageSettings->popular_searches as $index => $search)
                                    <a href="{{ route('pages.index', ['search' => $search['search']]) }}" class="text-white hover:underline mx-1">{{ $search['text'] }}</a>
                                    @if($index < count($pageSettings->popular_searches) - 1) • @endif
                                @endforeach
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

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
                <a href="{{ route('pages.show', $page->slug) }}" class="block bg-white rounded-lg shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1 border border-slate-100">
                    <!-- İçerik -->
                    <div class="p-5">
                        <h3 class="text-xl font-bold text-gray-800 mb-2 truncate">{{ $page->title }}</h3>
                        
                        @if($page->summary)
                        <p class="text-gray-600 mb-4 line-clamp-2 text-sm">{{ $page->summary }}</p>
                        @else
                        <p class="text-gray-600 mb-4 line-clamp-2 text-sm">{{ Str::limit(html_entity_decode(strip_tags($page->content)), 120) }}</p>
                        @endif
                        
                        <!-- Kategoriler -->
                        @if($page->categories->isNotEmpty())
                        <div class="flex flex-wrap gap-1.5 mt-auto pt-1 border-t border-slate-100">
                            @foreach($page->categories as $category)
                            <span class="inline-block text-xs bg-slate-50 px-2 py-0.5 rounded-full text-gray-500">
                                @if($category->icon)
                                    <i class="{{ $category->icon }} mr-1 text-xs"></i>
                                @endif
                                {{ $category->name }}
                            </span>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </a>
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