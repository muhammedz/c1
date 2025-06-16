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

                    <div class="mt-4 pt-4 border-t border-white/10">
                        <div class="text-center">
                            <h4 class="text-white font-semibold mb-2">Hizmet Bilgilendirme</h4>
                            <p class="text-white/80 text-sm mb-3">Bu konudaki hizmetlerimiz hakkında detaylı bilgi almak ve başvuru süreçleri için uzman ekibimizle iletişime geçebilirsiniz.</p>
                            <div class="flex items-center justify-center text-[#e6a23c]">
                                <span class="material-icons mr-2">support_agent</span>
                                <div class="text-center">
                                    <div class="font-bold">444 06 01</div>
                                </div>
                            </div>
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
                            <a href="{{ route('front.home') }}" class="text-gray-500 hover:text-gray-700">Anasayfa</a>
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

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($services as $service)
                <a href="{{ route('services.show', $service->slug) }}" class="group bg-white p-6 rounded-xl border border-gray-200 hover:border-[#00352b]/20 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-[#00352b] to-[#20846c] rounded-lg flex items-center justify-center">
                            <span class="material-icons text-white text-sm">description</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-[#00352b] group-hover:text-[#007b32] transition-colors duration-300 line-clamp-2">{{ $service->title }}</h3>
                            <p class="text-sm text-gray-600 mt-2 line-clamp-2">{{ Str::limit($service->summary, 80) }}</p>
                            <div class="flex items-center mt-3 text-xs text-gray-500">
                                <span class="material-icons text-xs mr-1">schedule</span>
                                <span>Hızlı İşlem</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-500">Detayları görüntüle</span>
                            <span class="material-icons text-[#00352b] group-hover:text-[#007b32] group-hover:translate-x-1 transition-all duration-300 text-sm">arrow_forward</span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
            
            <!-- Diğer hizmetlerimiz önerisi -->
            @if($serviceTopics->where('id', '!=', $serviceTopic->id)->count() > 0)
            <div class="mt-12 bg-gradient-to-r from-gray-50 to-white p-4 rounded-xl border border-gray-100">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-base font-semibold text-gray-700 flex items-center">
                        <span class="material-icons text-sm mr-2 text-[#00352b]">explore</span>
                        Diğer Hizmetlerimiz
                    </h3>
                    <a href="{{ route('services.topics.index') }}" class="text-xs text-[#00352b] hover:text-[#007b32] font-medium transition-colors duration-300">
                        Tümünü Gör →
                    </a>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3">
                    @foreach($serviceTopics->where('id', '!=', $serviceTopic->id)->take(6) as $otherTopic)
                    <a href="{{ route('services.topics.show', $otherTopic->slug) }}" class="group flex flex-col items-center p-3 border border-gray-100 rounded-lg hover:border-[#00352b]/20 hover:bg-white hover:shadow-sm transition-all duration-300">
                        <div class="w-8 h-8 rounded-full mb-2 flex items-center justify-center group-hover:scale-105 transition-transform duration-300" style="background-color: {{ $otherTopic->color }}15;">
                            @if($otherTopic->icon)
                                <i class="{{ $otherTopic->icon }} text-xs" style="color: {{ $otherTopic->color }};"></i>
                            @else
                                <span class="material-icons text-xs" style="color: {{ $otherTopic->color }};">extension</span>
                            @endif
                        </div>
                        <span class="text-xs font-medium text-gray-600 group-hover:text-gray-800 text-center line-clamp-2">{{ $otherTopic->name }}</span>
                        <span class="text-xs text-gray-400 mt-1">{{ $otherTopic->services_count }}</span>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        @endif
    </div>
</section>
@endsection 