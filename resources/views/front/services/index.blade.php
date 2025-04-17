@extends('layouts.front')

@section('title', 'Hizmetlerimiz')

@section('meta_description', 'Profesyonel hizmetlerimiz hakkında bilgi alın')

@include('helpers.functions')

@php
    $serviceSettings = \App\Models\ServiceSetting::getSettings();
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
                @if(!empty($serviceSettings->hero_badge_text))
                <div class="inline-flex items-center px-3 py-1 rounded-full bg-white/10 text-white/90 text-sm border border-white/10 mb-3">
                    <span class="material-icons text-xs mr-1">handyman</span>
                    <span>{{ $serviceSettings->hero_badge_text }}</span>
                </div>
                @endif
                
                @if(!empty($serviceSettings->hero_title))
                <h1 class="text-3xl md:text-4xl font-extrabold text-white leading-tight mb-3">
                    @if(!empty($serviceSettings->hero_title_highlight))
                        {!! str_replace($serviceSettings->hero_title_highlight, '<span class="text-[#e6a23c]">' . $serviceSettings->hero_title_highlight . '</span>', $serviceSettings->hero_title) !!}
                    @else
                        {{ $serviceSettings->hero_title }}
                    @endif
                </h1>
                @endif
                
                @if(!empty($serviceSettings->hero_description))
                <p class="text-white/80 text-base mb-0 max-w-lg">
                    {{ $serviceSettings->hero_description }}
                </p>
                @endif
            </div>
            
            <!-- Sağ taraf - arama kutusu -->
            <div class="w-full md:w-1/2">
                <div class="bg-white/10 backdrop-blur-md p-6 rounded-xl border border-white/20">
                    @if(!empty($serviceSettings->search_title))
                    <h3 class="text-white text-xl font-bold mb-4">{{ $serviceSettings->search_title }}</h3>
                    @else
                    <h3 class="text-white text-xl font-bold mb-4">Hangi hizmeti arıyorsunuz?</h3>
                    @endif
                    
                    <form action="{{ route('services.index') }}" method="GET" class="mb-4">
                        <div class="flex items-center">
                            <div class="relative flex-grow">
                                <input 
                                    type="text" 
                                    name="search" 
                                    placeholder="{{ $serviceSettings->search_placeholder ?? 'Anahtar kelime yazın...' }}" 
                                    class="w-full bg-white/20 text-white placeholder-white/60 rounded-lg py-3 pl-4 pr-12 focus:outline-none focus:ring-2 focus:ring-white/30 transition-all"
                                    value="{{ request()->get('search') }}"
                                >
                                <span class="absolute right-4 top-1/2 transform -translate-y-1/2 material-icons text-white/60">search</span>
                            </div>
                            <button type="submit" class="ml-2 bg-[#e6a23c] hover:bg-[#d69935] text-white px-5 py-3 rounded-lg transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-[#e6a23c]/50">
                                {{ $serviceSettings->search_button_text ?? 'Ara' }}
                            </button>
                        </div>
                    </form>
                    
                    @if(!empty($serviceSettings->popular_searches) && is_array($serviceSettings->popular_searches) && count($serviceSettings->popular_searches) > 0)
                    <div class="flex items-center flex-wrap gap-2">
                        @if(!empty($serviceSettings->popular_searches_title))
                        <span class="text-white/70 text-sm">{{ $serviceSettings->popular_searches_title }}</span>
                        @else
                        <span class="text-white/70 text-sm">Popüler aramalar:</span>
                        @endif
                        
                        @foreach($serviceSettings->popular_searches as $search)
                        <a href="{{ route('services.index', ['search' => $search['search']]) }}" class="text-white/90 hover:text-white text-sm bg-white/10 hover:bg-white/20 px-3 py-1 rounded-full transition duration-150 ease-in-out">
                            {{ $search['text'] }}
                        </a>
                        @endforeach
                    </div>
                    @endif
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
                    <a href="{{ route('services.index') }}" 
                        class="relative px-5 py-2.5 rounded-lg flex items-center {{ request()->routeIs('services.index') && !request()->query('search') ? 'bg-gradient-to-r from-[#00352b] to-[#005540] text-white font-medium shadow-md' : 'bg-white hover:bg-slate-50 text-gray-700 border border-slate-200 hover:border-[#00352b]/30' }} transition-all duration-300 group">
                        <i class="fas fa-layer-group {{ request()->routeIs('services.index') && !request()->query('search') ? 'text-white' : 'text-[#00352b] group-hover:text-[#00352b]' }} mr-2"></i>
                        <span class="font-medium">Tüm Hizmetler</span>
                        
                        @if(request()->routeIs('services.index') && !request()->query('search'))
                        <span class="absolute top-0 right-0 h-2 w-2 bg-white rounded-full transform -translate-y-1 translate-x-1 shadow"></span>
                        @endif
                    </a>
                    
                    @foreach($categories as $category)
                    <a href="{{ route('services.category', $category->slug) }}" 
                        class="relative px-5 py-2.5 rounded-lg flex items-center {{ request()->is('hizmetler/kategori/'.$category->slug) ? 'bg-gradient-to-r from-[#00352b] to-[#005540] text-white font-medium shadow-md' : 'bg-white hover:bg-slate-50 text-gray-700 border border-slate-200 hover:border-[#00352b]/30' }} transition-all duration-300 group">
                        @if($category->icon)
                            <i class="{{ $category->icon }} {{ request()->is('hizmetler/kategori/'.$category->slug) ? 'text-white' : 'text-[#00352b] group-hover:text-[#00352b]' }} mr-2"></i>
                        @endif
                        <span class="font-medium">{{ $category->name }}</span>
                        
                        @if(request()->is('hizmetler/kategori/'.$category->slug))
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
                <h3 class="text-xl font-bold text-gray-800 mb-2">Henüz hizmet bulunmamaktadır</h3>
                <p class="text-gray-600">Lütfen daha sonra tekrar kontrol ediniz.</p>
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
                            @foreach($service->categories as $category)
                            <a href="{{ route('services.category', $category->slug) }}" class="text-xs bg-slate-100 hover:bg-[#00352b] hover:text-white px-3 py-1 rounded-full transition-colors text-gray-700">
                                {{ $category->name }}
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