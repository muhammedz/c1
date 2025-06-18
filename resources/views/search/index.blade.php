@extends('layouts.front')

@section('content')
<!-- Hero Bölümü -->
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
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 md:py-4 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-1 gap-4 items-center">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">Arama Sonuçları</h1>
                @if(isset($query))
                <p class="text-white/80 text-base mb-2">
                    "<span class="font-medium">{{ $query }}</span>" için {{ isset($results['total']) ? $results['total'] : 0 }} sonuç bulundu
                </p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="bg-gray-50 py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto">
            <!-- Sol Taraf Bilgi ve Filtreler -->
            <div class="flex flex-col lg:flex-row gap-6 lg:gap-8">
                <!-- Sol Kenar - Bilgi ve Filtreler -->
                <div class="w-full lg:w-64 flex-shrink-0 order-2 lg:order-1">
                    <div class="bg-gray-100 p-4 md:p-5 rounded-lg mb-4 md:mb-6">
                        <div class="flex items-start mb-3 md:mb-4">
                            <span class="material-icons text-gray-400 mr-2 mt-1 text-lg md:text-xl">info</span>
                            <p class="text-gray-600 text-sm">Arama kutusuna anahtar kelimeler yazarak aradığınız bilgiye kolayca ulaşabilirsiniz.</p>
                        </div>
                    </div>
                    
                    <div class="border border-gray-200 rounded-lg overflow-hidden mb-4 md:mb-6">
                        <div class="bg-white p-3 md:p-4 border-b border-gray-200">
                            <h3 class="font-medium text-gray-700 text-sm md:text-base">Tüm Sonuçlar ({{ isset($results['total']) ? $results['total'] : 0 }})</h3>
                        </div>
                        
                        @if(isset($results['services']) && $results['services']->count() > 0)
                        <div class="bg-white p-3 md:p-4 border-b border-gray-200">
                            <a href="#services" class="text-[#004d2e] hover:underline flex justify-between text-sm md:text-base">
                                <span>Hizmetler</span>
                                <span>{{ $results['services']->count() }}</span>
                            </a>
                        </div>
                        @endif
                        
                        @if(isset($results['news']) && $results['news']->count() > 0)
                        <div class="bg-white p-3 md:p-4 border-b border-gray-200">
                            <a href="#news" class="text-[#004d2e] hover:underline flex justify-between text-sm md:text-base">
                                <span>Haberler</span>
                                <span>{{ $results['news']->count() }}</span>
                            </a>
                        </div>
                        @endif
                        
                        @if(isset($results['projects']) && $results['projects']->count() > 0)
                        <div class="bg-white p-3 md:p-4 border-b border-gray-200">
                            <a href="#projects" class="text-[#004d2e] hover:underline flex justify-between text-sm md:text-base">
                                <span>Projeler</span>
                                <span>{{ $results['projects']->count() }}</span>
                            </a>
                        </div>
                        @endif
                        
                        @if(isset($results['guides']) && $results['guides']->count() > 0)
                        <div class="bg-white p-3 md:p-4 border-b border-gray-200">
                            <a href="#guides" class="text-[#004d2e] hover:underline flex justify-between text-sm md:text-base">
                                <span>Rehber Yerleri</span>
                                <span>{{ $results['guides']->count() }}</span>
                            </a>
                        </div>
                        @endif
                        
                        @if(isset($results['cankaya_houses']) && $results['cankaya_houses']->count() > 0)
                        <div class="bg-white p-3 md:p-4 border-b border-gray-200">
                            <a href="#cankaya_houses" class="text-[#004d2e] hover:underline flex justify-between text-sm md:text-base">
                                <span>Çankaya Evleri</span>
                                <span>{{ $results['cankaya_houses']->count() }}</span>
                            </a>
                        </div>
                        @endif
                        
                        @if(isset($results['mudurlukler']) && $results['mudurlukler']->count() > 0)
                        <div class="bg-white p-3 md:p-4">
                            <a href="#mudurlukler" class="text-[#004d2e] hover:underline flex justify-between text-sm md:text-base">
                                <span>Müdürlükler</span>
                                <span>{{ $results['mudurlukler']->count() }}</span>
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Sağ Taraf - Arama ve Sonuçlar -->
                <div class="flex-1 order-1 lg:order-2">
                    <!-- Arama Formu -->
                    <div class="bg-white p-4 md:p-6 rounded-lg shadow-sm mb-6 border border-gray-200">
                        <div class="mb-4">
                            <label for="search_query" class="block text-gray-700 font-medium mb-3 text-sm md:text-base">Aranan Kelime</label>
                            <form action="{{ route('search') }}" method="GET" class="space-y-4">
                                <div class="flex flex-col sm:flex-row gap-3">
                                <div class="flex-1">
                                    <input 
                                        type="text" 
                                        id="search_query"
                                        name="q" 
                                        value="{{ $query ?? '' }}" 
                                        placeholder="Aramak istediğiniz kelimeyi girin..." 
                                            class="w-full px-4 py-3 md:py-2 text-base md:text-sm rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#004d2e] focus:border-transparent"
                                    >
                                </div>
                                
                                    <div class="w-full sm:w-auto">
                                    <button 
                                        type="submit" 
                                            class="w-full sm:w-auto bg-[#004d2e] hover:bg-green-800 text-white py-3 md:py-2 px-6 rounded-md transition-colors font-medium text-base md:text-sm"
                                    >
                                        Ara
                                    </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    @if(!isset($query) || (isset($query) && empty($results['total'])))
                        <!-- Boş sonuç veya arama başlangıcı -->
                        <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 md:p-5 mb-4 md:mb-6 flex items-center">
                            <span class="material-icons text-amber-400 mr-2 md:mr-3 text-xl md:text-2xl">warning</span>
                            <p class="text-amber-800 text-sm md:text-base">Lütfen arama yapmak istediğiniz kelimeyi/kelimeleri giriniz.</p>
                        </div>
                        
                        <div class="bg-white border border-gray-200 p-4 md:p-6 rounded-lg">
                            <h3 class="text-base md:text-lg font-medium text-gray-700 mb-2">Toplam <span class="font-bold">0</span> kayıt bulunmuştur.</h3>
                        </div>
                    @else
                        <!-- Servisler Sonuçları -->
                        @if(isset($results['services']) && $results['services']->count() > 0)
                            <div id="services" class="mb-4 md:mb-6">
                                <h2 class="text-lg md:text-xl font-bold text-gray-900 mb-2 md:mb-3 border-b border-gray-200 pb-2">Hizmetler ({{ $results['services']->count() }})</h2>
                                <div class="space-y-2">
                                    @foreach($results['services'] as $service)
                                        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden transition-all hover:shadow-md">
                                            <div class="p-3 md:p-2">
                                                <div class="flex-1">
                                                    <a href="{{ route('services.show', $service->slug) }}" class="text-sm md:text-base font-medium text-[#004d2e] hover:underline block">{{ $service->original_title ?? $service->title }}</a>
                                                    <span class="text-xs text-gray-400 block italic hidden md:block">{{ route('services.show', $service->slug) }}</span>
                                                    <p class="text-xs text-gray-600 mt-1">{{ Str::limit($service->summary, 80) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        
                        <!-- Haberler Sonuçları -->
                        @if(isset($results['news']) && $results['news']->count() > 0)
                            <div id="news" class="mb-4 md:mb-6">
                                <h2 class="text-lg md:text-xl font-bold text-gray-900 mb-2 md:mb-3 border-b border-gray-200 pb-2">Haberler ({{ $results['news']->count() }})</h2>
                                <div class="space-y-2">
                                    @foreach($results['news'] as $newsItem)
                                        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden transition-all hover:shadow-md">
                                            <div class="p-3 md:p-2">
                                                <div class="flex-1">
                                                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-1 sm:gap-2">
                                                        <a href="{{ route('news.show', $newsItem->slug) }}" class="text-sm md:text-sm font-medium text-[#004d2e] hover:underline block">{{ $newsItem->original_title ?? $newsItem->title }}</a>
                                                        <div class="flex items-center text-gray-500 text-xs whitespace-nowrap">
                                                            <span class="material-icons text-xs mr-1">calendar_today</span>
                                                            <span>{{ $newsItem->published_at ? $newsItem->published_at->format('d.m.Y') : '' }}</span>
                                                        </div>
                                                    </div>
                                                    <span class="text-xs text-gray-400 block italic hidden md:block">{{ route('news.show', $newsItem->slug) }}</span>
                                                    <p class="text-xs text-gray-600 mt-1">{{ Str::limit($newsItem->summary, 80) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        
                        <!-- Projeler Sonuçları -->
                        @if(isset($results['projects']) && $results['projects']->count() > 0)
                            <div id="projects" class="mb-4 md:mb-6">
                                <h2 class="text-lg md:text-xl font-bold text-gray-900 mb-2 md:mb-3 border-b border-gray-200 pb-2">Projeler ({{ $results['projects']->count() }})</h2>
                                <div class="space-y-2">
                                    @foreach($results['projects'] as $project)
                                        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden transition-all hover:shadow-md">
                                            <div class="p-3 md:p-2">
                                                <div class="flex-1">
                                                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-1 sm:gap-2">
                                                        <a href="{{ route('front.projects.detail', $project->slug) }}" class="text-sm md:text-base font-medium text-[#004d2e] hover:underline block">{{ $project->title }}</a>
                                                        @if($project->completion_percentage)
                                                            <div class="flex items-center text-gray-500 text-xs whitespace-nowrap">
                                                                <span class="material-icons text-xs mr-1">trending_up</span>
                                                                <span>%{{ $project->completion_percentage }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <span class="text-xs text-gray-400 block italic hidden md:block">{{ route('front.projects.detail', $project->slug) }}</span>
                                                    <p class="text-xs text-gray-600 mt-1">{{ Str::limit(strip_tags($project->description), 80) }}</p>
                                                    @if($project->category)
                                                        <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full mt-1">{{ $project->category->name }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        
                        <!-- Rehber Yerleri Sonuçları -->
                        @if(isset($results['guides']) && $results['guides']->count() > 0)
                            <div id="guides" class="mb-4 md:mb-6">
                                <h2 class="text-lg md:text-xl font-bold text-gray-900 mb-2 md:mb-3 border-b border-gray-200 pb-2">Rehber Yerleri ({{ $results['guides']->count() }})</h2>
                                <div class="space-y-2">
                                    @foreach($results['guides'] as $guide)
                                        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden transition-all hover:shadow-md">
                                            <div class="p-3 md:p-2">
                                                <div class="flex-1">
                                                    <a href="{{ route('guide.place', [$guide->category->slug, $guide->slug]) }}" class="text-sm md:text-base font-medium text-[#004d2e] hover:underline block">{{ $guide->title }}</a>
                                                    <span class="text-xs text-gray-400 block italic hidden md:block">{{ route('guide.place', [$guide->category->slug, $guide->slug]) }}</span>
                                                    @if($guide->address)
                                                        <p class="text-xs text-gray-600 flex items-center mt-1">
                                                            <span class="material-icons text-xs mr-1">place</span>
                                                            {{ Str::limit($guide->address, 60) }}
                                                        </p>
                                                    @endif
                                                    @if($guide->category)
                                                        <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full mt-1">{{ $guide->category->name }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        
                        <!-- Çankaya Evleri Sonuçları -->
                        @if(isset($results['cankaya_houses']) && $results['cankaya_houses']->count() > 0)
                            <div id="cankaya_houses" class="mb-4 md:mb-6">
                                <h2 class="text-lg md:text-xl font-bold text-gray-900 mb-2 md:mb-3 border-b border-gray-200 pb-2">Çankaya Evleri ({{ $results['cankaya_houses']->count() }})</h2>
                                <div class="space-y-2">
                                    @foreach($results['cankaya_houses'] as $house)
                                        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden transition-all hover:shadow-md">
                                            <div class="p-3 md:p-2">
                                                <div class="flex-1">
                                                    <a href="{{ route('cankaya-houses.show', $house) }}" class="text-sm md:text-base font-medium text-[#004d2e] hover:underline block">{{ $house->name }}</a>
                                                    <span class="text-xs text-gray-400 block italic hidden md:block">{{ route('cankaya-houses.show', $house) }}</span>
                                                    @if($house->address)
                                                        <p class="text-xs text-gray-600 flex items-center mt-1">
                                                            <span class="material-icons text-xs mr-1">place</span>
                                                            {{ Str::limit($house->address, 60) }}
                                                        </p>
                                                    @endif
                                                    @if($house->description)
                                                        <p class="text-xs text-gray-600 mt-1">{{ Str::limit($house->description, 80) }}</p>
                                                    @endif
                                                    <span class="inline-block bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded-full mt-1">Çankaya Evi</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        
                        <!-- Müdürlükler Sonuçları -->
                        @if(isset($results['mudurlukler']) && $results['mudurlukler']->count() > 0)
                            <div id="mudurlukler" class="mb-4 md:mb-6">
                                <h2 class="text-lg md:text-xl font-bold text-gray-900 mb-2 md:mb-3 border-b border-gray-200 pb-2">Müdürlükler ({{ $results['mudurlukler']->count() }})</h2>
                                <div class="space-y-2">
                                    @foreach($results['mudurlukler'] as $mudurluk)
                                        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden transition-all hover:shadow-md">
                                            <div class="p-3 md:p-2">
                                                <div class="flex-1">
                                                    <a href="{{ route('mudurlukler.show', $mudurluk->slug) }}" class="text-sm md:text-base font-medium text-[#004d2e] hover:underline block">{{ $mudurluk->name }}</a>
                                                    <span class="text-xs text-gray-400 block italic hidden md:block">{{ route('mudurlukler.show', $mudurluk->slug) }}</span>
                                                    @if($mudurluk->summary)
                                                        <p class="text-xs text-gray-600 mt-1">{{ Str::limit($mudurluk->summary, 80) }}</p>
                                                    @endif
                                                    <span class="inline-block bg-orange-100 text-orange-800 text-xs px-2 py-1 rounded-full mt-1">Müdürlük</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        
                        <!-- Toplam Sonuç -->
                        <div class="bg-white border border-gray-200 p-4 md:p-6 rounded-lg">
                            <h3 class="text-base md:text-lg font-medium text-gray-700">Toplam <span class="font-bold">{{ isset($results['total']) ? $results['total'] : 0 }}</span> kayıt bulunmuştur.</h3>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 