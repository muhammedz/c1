@extends('layouts.front')

@section('title', $serviceTopic->name . ' - Hizmet Konuları')

@section('meta_description', $serviceTopic->name . ' konusundaki hizmetlerimiz hakkında bilgi alın')

@section('content')
<!-- Hero Section -->
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
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
            <div class="md:col-span-2">
                <div class="inline-block bg-white/10 backdrop-blur-sm px-4 py-1 rounded-full text-white/90 text-sm mb-3 border border-white/10">
                    @if($serviceTopic->icon)
                        <i class="{{ $serviceTopic->icon }} text-xs align-middle mr-1"></i>
                    @else
                        <span class="material-icons text-xs align-middle mr-1">extension</span>
                    @endif
                    <span>Hizmet Konusu</span>
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">{{ $serviceTopic->name }}</h1>
                <p class="text-white/80 text-lg mb-5">{{ $serviceTopic->description ?? 'Bu konudaki hizmetlerimizi inceleyebilirsiniz.' }}</p>
                
                <!-- Modern Arama Kutusu -->
                <form action="{{ route('services.topics.show', $serviceTopic->slug) }}" method="GET" class="mt-6">
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-1 flex items-center shadow-lg hover:bg-white/20 transition duration-300 border border-white/20">
                        <input 
                            type="text" 
                            name="search" 
                            placeholder="Bu konudaki hangi hizmeti arıyorsunuz?" 
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
            
            <!-- Sağ Taraf: İstatistikler -->
            <div class="hidden md:flex justify-end">
                <div class="bg-white/10 backdrop-blur-sm p-6 rounded-lg border border-white/20 shadow-lg">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-white mb-1">{{ $services->count() }}</div>
                            <div class="text-white/70 text-xs">Bu Konuda</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-white mb-1">{{ $serviceTopics->count() }}</div>
                            <div class="text-white/70 text-xs">Toplam Konu</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-white mb-1">100%</div>
                            <div class="text-white/70 text-xs">Memnuniyet</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-white mb-1">7/24</div>
                            <div class="text-white/70 text-xs">Destek</div>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-white/10">
                        <div class="flex items-center justify-center text-[#e6a23c]">
                            <span class="material-icons mr-2">phone</span>
                            <span class="font-bold">444 1 234</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Breadcrumb -->
<div class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
        <nav class="flex items-center space-x-2 text-sm">
            <a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-700">Anasayfa</a>
            <span class="material-icons text-gray-400 text-sm">chevron_right</span>
            <a href="{{ route('services.index') }}" class="text-gray-500 hover:text-gray-700">Hizmetler</a>
            <span class="material-icons text-gray-400 text-sm">chevron_right</span>
            <a href="{{ route('services.topics.index') }}" class="text-gray-500 hover:text-gray-700">Konular</a>
            <span class="material-icons text-gray-400 text-sm">chevron_right</span>
            <span class="text-gray-900 font-medium">{{ $serviceTopic->name }}</span>
        </nav>
    </div>
</div>

<!-- Content -->
<section class="py-12 bg-slate-100">
    <div class="container max-w-7xl mx-auto px-4">
        @if($services->isEmpty())
            <div class="bg-white p-8 rounded-lg shadow-md text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center" style="background-color: {{ $serviceTopic->color }}20;">
                    @if($serviceTopic->icon)
                        <i class="{{ $serviceTopic->icon }} text-2xl" style="color: {{ $serviceTopic->color }};"></i>
                    @else
                        <span class="material-icons text-2xl" style="color: {{ $serviceTopic->color }};">extension</span>
                    @endif
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Bu konuda henüz hizmet bulunmamaktadır</h3>
                <p class="text-gray-600 mb-4">{{ $serviceTopic->name }} konusunda henüz hizmet eklenmemiştir. Lütfen daha sonra tekrar kontrol ediniz.</p>
                <a href="{{ route('services.topics.index') }}" class="inline-flex items-center px-4 py-2 bg-[#007b32] hover:bg-[#00352b] text-white font-medium rounded-lg transition-colors duration-300">
                    <span class="material-icons mr-2">arrow_back</span>
                    Diğer Konulara Gözat
                </a>
            </div>
        @else
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full mr-3 flex items-center justify-center" style="background-color: {{ $serviceTopic->color }}20;">
                            @if($serviceTopic->icon)
                                <i class="{{ $serviceTopic->icon }}" style="color: {{ $serviceTopic->color }};"></i>
                            @else
                                <span class="material-icons text-sm" style="color: {{ $serviceTopic->color }};">extension</span>
                            @endif
                        </div>
                        <h2 class="text-xl font-bold text-gray-800">{{ $serviceTopic->name }} Hizmetleri</h2>
                    </div>
                    <span class="text-sm text-gray-600 bg-white px-3 py-1 rounded-full border border-gray-200">
                        {{ $services->count() }} hizmet bulundu
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($services as $service)
                <a href="{{ route('services.show', $service->slug) }}" class="group bg-white p-4 rounded-lg border hover:shadow-md transition-all duration-300">
                    <h3 class="font-semibold text-[#00352b] group-hover:text-[#007b32]">{{ $service->title }}</h3>
                    <p class="text-sm text-gray-600 mt-1">{{ Str::limit($service->summary, 50) }}</p>
                </a>
                @endforeach
            </div>
            
            <!-- Diğer konular önerisi -->
            @if($serviceTopics->where('id', '!=', $serviceTopic->id)->count() > 0)
            <div class="mt-8 bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Diğer Hizmet Konuları</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
                    @foreach($serviceTopics->where('id', '!=', $serviceTopic->id)->take(5) as $otherTopic)
                    <a href="{{ route('services.topics.show', $otherTopic->slug) }}" class="group flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:border-gray-300 hover:shadow-sm transition-all duration-300">
                        <div class="w-10 h-10 rounded-full mb-2 flex items-center justify-center group-hover:scale-110 transition-transform duration-300" style="background-color: {{ $otherTopic->color }}20;">
                            @if($otherTopic->icon)
                                <i class="{{ $otherTopic->icon }}" style="color: {{ $otherTopic->color }};"></i>
                            @else
                                <span class="material-icons text-sm" style="color: {{ $otherTopic->color }};">extension</span>
                            @endif
                        </div>
                        <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900 text-center">{{ $otherTopic->name }}</span>
                        <span class="text-xs text-gray-500 mt-1">{{ $otherTopic->services_count }} hizmet</span>
                    </a>
                    @endforeach
                </div>
                <div class="text-center mt-4">
                    <a href="{{ route('services.topics.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors duration-300">
                        <span class="material-icons mr-2">apps</span>
                        Tüm Konuları Gör
                    </a>
                </div>
            </div>
            @endif
        @endif
    </div>
</section>
@endsection 