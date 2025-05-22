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
                
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3 text-white/70">
                        <li class="inline-flex items-center">
                            <a href="{{ route('front.home') }}" class="inline-flex items-center text-sm hover:text-white">
                                <span class="material-icons text-xs mr-1">home</span>
                                Anasayfa
                            </a>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <span class="material-icons text-xs mx-1">chevron_right</span>
                                <span class="ml-1 text-sm font-medium">Arama</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="bg-gray-50 py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto">
            <!-- Sol Taraf Bilgi ve Filtreler -->
            <div class="flex flex-col md:flex-row gap-8">
                <!-- Sol Kenar - Bilgi ve Filtreler -->
                <div class="w-full md:w-64 flex-shrink-0">
                    <div class="bg-gray-100 p-5 rounded-lg mb-6">
                        <div class="flex items-start mb-4">
                            <span class="material-icons text-gray-400 mr-2 mt-1">info</span>
                            <p class="text-gray-600 text-sm">Arama kutusuna bir şehir ismi gibi sorgular girerek, doğrudan ilgili hizmete ulaşabilirsiniz.</p>
                        </div>
                    </div>
                    
                    <div class="border border-gray-200 rounded-lg overflow-hidden mb-6">
                        <div class="bg-white p-4 border-b border-gray-200">
                            <h3 class="font-medium text-gray-700">Tüm Sonuçlar ({{ isset($results['total']) ? $results['total'] : 0 }})</h3>
                        </div>
                        
                        @if(isset($results['services']) && $results['services']->count() > 0)
                        <div class="bg-white p-4 border-b border-gray-200">
                            <a href="#services" class="text-[#004d2e] hover:underline flex justify-between">
                                <span>Hizmetler</span>
                                <span>{{ $results['services']->count() }}</span>
                            </a>
                        </div>
                        @endif
                        
                        @if(isset($results['news']) && $results['news']->count() > 0)
                        <div class="bg-white p-4">
                            <a href="#news" class="text-[#004d2e] hover:underline flex justify-between">
                                <span>Haberler</span>
                                <span>{{ $results['news']->count() }}</span>
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Sağ Taraf - Arama ve Sonuçlar -->
                <div class="flex-1">
                    <!-- Arama Formu -->
                    <div class="bg-white p-6 rounded-lg shadow-sm mb-6 border border-gray-200">
                        <div class="mb-5">
                            <label for="search_query" class="block text-gray-700 font-medium mb-2">Aranan Kelime</label>
                            <form action="{{ route('search') }}" method="GET" class="flex gap-4 items-end">
                                <div class="flex-1">
                                    <input 
                                        type="text" 
                                        id="search_query"
                                        name="q" 
                                        value="{{ $query ?? '' }}" 
                                        placeholder="Aramak istediğiniz kelimeyi girin..." 
                                        class="w-full px-4 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#004d2e] focus:border-transparent"
                                    >
                                </div>
                                
                                <div class="w-64">
                                    <label for="sort_by" class="block text-gray-700 font-medium mb-2">Sıralama</label>
                                    <select id="sort_by" name="sort" class="w-full px-4 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#004d2e] focus:border-transparent appearance-none bg-white">
                                        <option value="date_desc" {{ request('sort') == 'date_desc' ? 'selected' : '' }}>En Yeniler</option>
                                        <option value="date_asc" {{ request('sort') == 'date_asc' ? 'selected' : '' }}>En Eskiler</option>
                                        <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>Başlığa Göre (A-Z)</option>
                                        <option value="title_desc" {{ request('sort') == 'title_desc' ? 'selected' : '' }}>Başlığa Göre (Z-A)</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <button 
                                        type="submit" 
                                        class="bg-[#004d2e] hover:bg-green-800 text-white py-2 px-6 rounded-md transition-colors font-medium"
                                    >
                                        Ara
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    @if(!isset($query) || (isset($query) && empty($results['total'])))
                        <!-- Boş sonuç veya arama başlangıcı -->
                        <div class="bg-amber-50 border border-amber-200 rounded-lg p-5 mb-6 flex items-center">
                            <span class="material-icons text-amber-400 mr-3 text-2xl">warning</span>
                            <p class="text-amber-800">Lütfen arama yapmak istediğiniz kelimeyi/kelimeleri giriniz.</p>
                        </div>
                        
                        <div class="bg-white border border-gray-200 p-6 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-700 mb-2">Toplam <span class="font-bold">0</span> kayıt bulunmuştur.</h3>
                        </div>
                    @else
                        <!-- Servisler Sonuçları -->
                        @if(isset($results['services']) && $results['services']->count() > 0)
                            <div id="services" class="mb-6">
                                <h2 class="text-xl font-bold text-gray-900 mb-3 border-b border-gray-200 pb-2">Hizmetler ({{ $results['services']->count() }})</h2>
                                <div class="space-y-2">
                                    @foreach($results['services'] as $service)
                                        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden transition-all hover:shadow-md">
                                            <div class="p-2">
                                                <div class="flex-1">
                                                    <a href="{{ route('services.show', $service->slug) }}" class="text-base font-medium text-[#004d2e] hover:underline block">{{ $service->original_title ?? $service->title }}</a>
                                                    <span class="text-xs text-gray-400 block italic">{{ route('services.show', $service->slug) }}</span>
                                                    <p class="text-xs text-gray-600">{{ Str::limit($service->summary, 80) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        
                        <!-- Haberler Sonuçları -->
                        @if(isset($results['news']) && $results['news']->count() > 0)
                            <div id="news" class="mb-6">
                                <h2 class="text-xl font-bold text-gray-900 mb-3 border-b border-gray-200 pb-2">Haberler ({{ $results['news']->count() }})</h2>
                                <div class="space-y-2">
                                    @foreach($results['news'] as $newsItem)
                                        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden transition-all hover:shadow-md">
                                            <div class="p-2">
                                                <div class="flex-1">
                                                    <div class="flex justify-between items-start">
                                                        <a href="{{ route('news.show', $newsItem->slug) }}" class="text-sm font-medium text-[#004d2e] hover:underline block">{{ $newsItem->original_title ?? $newsItem->title }}</a>
                                                        <div class="flex items-center text-gray-500 text-xs ml-2 whitespace-nowrap">
                                                            <span class="material-icons text-xs mr-1">calendar_today</span>
                                                            <span>{{ $newsItem->published_at ? $newsItem->published_at->format('d.m.Y') : '' }}</span>
                                                        </div>
                                                    </div>
                                                    <span class="text-xs text-gray-400 block italic">{{ route('news.show', $newsItem->slug) }}</span>
                                                    <p class="text-xs text-gray-600">{{ Str::limit($newsItem->summary, 80) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        
                        <!-- Toplam Sonuç -->
                        <div class="bg-white border border-gray-200 p-6 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-700">Toplam <span class="font-bold">{{ isset($results['total']) ? $results['total'] : 0 }}</span> kayıt bulunmuştur.</h3>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 