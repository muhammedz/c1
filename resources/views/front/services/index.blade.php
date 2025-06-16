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
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-10 relative z-10">
        <div class="flex flex-col items-center text-center">
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">
                @if(!empty($serviceSettings->hero_title))
                    @if(!empty($serviceSettings->hero_title_highlight))
                        {!! str_replace($serviceSettings->hero_title_highlight, '<span class="text-[#e6a23c]">' . $serviceSettings->hero_title_highlight . '</span>', $serviceSettings->hero_title) !!}
                    @else
                        {{ $serviceSettings->hero_title }}
                    @endif
                @else
                    Tüm Hizmetlerimiz
                @endif
            </h1>
            <p class="text-white/80 text-lg mb-5 max-w-4xl">
                {{ $serviceSettings->hero_description ?? 'Belediyemiz tarafından sunulan tüm hizmetleri inceleyebilir, ihtiyacınız olan hizmeti bulabilirsiniz.' }}
            </p>
            
            <!-- Arama Kutusu -->
            <form action="{{ route('services.index') }}" method="GET" class="mt-6 w-full max-w-2xl">
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-1 flex items-center shadow-lg hover:bg-white/20 transition duration-300 border border-white/20">
                    <input 
                        type="text" 
                        name="search" 
                        placeholder="{{ $serviceSettings->search_placeholder ?? 'Hangi hizmeti arıyorsunuz?' }}" 
                        class="w-full bg-transparent px-4 py-3 text-white placeholder-white/70 outline-none"
                        value="{{ request()->get('search') }}"
                    >
                    <button type="submit" class="bg-[#e6a23c] hover:bg-[#e6a23c]/90 text-white font-medium px-6 py-3 rounded-md transition duration-300 flex items-center shadow-lg shadow-[#e6a23c]/20">
                        <span class="material-icons mr-1">search</span>
                        {{ $serviceSettings->search_button_text ?? 'Ara' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Ana İçerik -->
<section class="py-12 bg-slate-100">
    <div class="container max-w-7xl mx-auto px-4">
        @if($services->isEmpty())
            <div class="bg-white p-8 rounded-lg shadow-md text-center">
                <span class="material-icons text-gray-400 text-5xl mb-4">info</span>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Henüz hizmet bulunmamaktadır</h3>
                <p class="text-gray-600">Lütfen daha sonra tekrar kontrol ediniz.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($services as $service)
                <a href="{{ route('services.show', $service->slug) }}" class="service-card-modern group relative overflow-hidden bg-gradient-to-br from-gray-50 to-gray-100 hover:from-gray-100 hover:to-gray-150 border-gray-200 flex items-center p-3 rounded-lg border-2 shadow-sm hover:shadow-md transition-all duration-300 transform hover:scale-102 hover:-translate-y-0.5">
                    <!-- İkon -->
                    <div class="icon-container w-10 h-10 flex items-center justify-center rounded-full mr-3 flex-shrink-0 bg-white/80 group-hover:bg-white transition-all duration-300 group-hover:rotate-3 group-hover:scale-105 shadow-sm">
                        @if($service->categories->isNotEmpty() && $service->categories->first()->icon)
                            <i class="{{ $service->categories->first()->icon }} text-[#007b32] text-lg group-hover:text-[#00352b] transition-colors duration-300"></i>
                        @else
                            <span class="material-icons text-[#007b32] text-lg group-hover:text-[#00352b] transition-colors duration-300">extension</span>
                        @endif
                    </div>
                    
                    <!-- Başlık ve Açıklama -->
                    <div class="flex flex-col flex-1 min-w-0">
                        <h3 class="font-semibold text-[#00352b] text-sm group-hover:text-[#007b32] transition-colors duration-300 leading-tight mb-0.5 capitalize">{{ $service->title }}</h3>
                        <p class="text-xs text-gray-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300 line-clamp-1">{{ Str::limit($service->summary, 50) }}</p>
                    </div>
                    
                    <!-- Sağ Ok İkonu -->
                    <div class="ml-2 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-1 group-hover:translate-x-0">
                        <span class="material-icons text-[#007b32] text-base">arrow_forward</span>
                    </div>
                    
                    <!-- Hover overlay efekti -->
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/15 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 transform -skew-x-12 translate-x-full group-hover:translate-x-[-100%] transition-transform duration-700"></div>
                    
                    <!-- Sol kenar vurgu çizgisi -->
                    <div class="absolute left-0 top-0 bottom-0 w-0 bg-[#007b32] group-hover:w-1 transition-all duration-300 rounded-l-lg"></div>
                </a>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection 