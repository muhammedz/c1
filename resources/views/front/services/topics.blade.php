@extends('layouts.front')

@section('title', 'Hizmet Konuları - Çankaya Belediyesi')

@section('meta_description', 'Çankaya Belediyesi hizmetlerini konularına göre keşfedin')

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
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-10 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
            <div class="md:col-span-2">
                <div class="inline-block bg-white/10 backdrop-blur-sm px-4 py-1 rounded-full text-white/90 text-sm mb-3 border border-white/10">
                    <span class="material-icons text-xs align-middle mr-1">category</span>
                    <span>Hizmet Konuları</span>
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">Hizmet Konuları</h1>
                <p class="text-white/80 text-lg mb-5">Çankaya Belediyesi hizmetlerini konularına göre keşfedin. Aradığınız hizmeti kolayca bulun.</p>
            </div>
            
            <!-- Sağ Taraf: İstatistikler -->
            <div class="hidden md:flex justify-end">
                <div class="bg-white/10 backdrop-blur-sm p-6 rounded-lg border border-white/20 shadow-lg">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-white mb-1">{{ $serviceTopics->count() }}</div>
                            <div class="text-white/70 text-xs">Konu</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-white mb-1">{{ $serviceTopics->sum('services_count') }}</div>
                            <div class="text-white/70 text-xs">Toplam Hizmet</div>
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

<!-- Ana İçerik -->
<section class="py-12 bg-slate-100">
    <div class="container max-w-7xl mx-auto px-4">
        @if($serviceTopics->isEmpty())
            <div class="bg-white p-8 rounded-lg shadow-md text-center">
                <span class="material-icons text-gray-400 text-5xl mb-4">info</span>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Henüz hizmet konusu bulunmamaktadır</h3>
                <p class="text-gray-600">Lütfen daha sonra tekrar kontrol ediniz.</p>
            </div>
        @else
            <!-- Hizmet Konuları Sayısı Bilgisi -->
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-gray-800">Hizmet Konuları</h2>
                    <span class="text-sm text-gray-600 bg-white px-3 py-1 rounded-full border border-gray-200">
                        {{ $serviceTopics->count() }} konu bulundu
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($serviceTopics as $topic)
                <a href="{{ route('services.topics.show', $topic->slug) }}" class="topic-card-modern group relative overflow-hidden bg-white hover:bg-gray-50 border border-gray-200 hover:border-gray-300 p-6 rounded-lg shadow-sm hover:shadow-md transition-all duration-300 transform hover:scale-102 hover:-translate-y-1">
                    <!-- İkon ve Başlık -->
                    <div class="flex items-start mb-4">
                        <div class="icon-container w-12 h-12 flex items-center justify-center rounded-full mr-4 flex-shrink-0 transition-all duration-300 group-hover:rotate-3 group-hover:scale-110 shadow-sm" style="background-color: {{ $topic->color }}20; border: 2px solid {{ $topic->color }}40;">
                            @if($topic->icon)
                                <i class="{{ $topic->icon }} text-xl transition-colors duration-300" style="color: {{ $topic->color }};"></i>
                            @else
                                <span class="material-icons text-xl transition-colors duration-300" style="color: {{ $topic->color }};">extension</span>
                            @endif
                        </div>
                        
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-[#00352b] text-lg group-hover:text-[#007b32] transition-colors duration-300 leading-tight mb-1">{{ $topic->name }}</h3>
                            @if($topic->description)
                                <p class="text-sm text-gray-600 line-clamp-2">{{ Str::limit($topic->description, 80) }}</p>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Hizmet Sayısı -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center text-sm text-gray-500">
                            <span class="material-icons text-base mr-1">list_alt</span>
                            <span>{{ $topic->services_count }} hizmet</span>
                        </div>
                        
                        @if($topic->services_count > 0)
                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Aktif</span>
                        @else
                            <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-full">Hizmet Yok</span>
                        @endif
                    </div>
                    
                    <!-- Alt kısım: Buton -->
                    <div class="flex items-center justify-between">
                        @if($topic->services_count > 0)
                            <span class="text-sm font-medium text-[#007b32] group-hover:text-[#00352b] transition-colors duration-300">
                                Hizmetleri Görüntüle
                            </span>
                            <div class="opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-1 group-hover:translate-x-0">
                                <span class="material-icons text-[#007b32] text-base">arrow_forward</span>
                            </div>
                        @else
                            <span class="text-sm text-gray-500">
                                Hizmet bulunmuyor
                            </span>
                            <span class="material-icons text-gray-400 text-base">info</span>
                        @endif
                    </div>
                    
                    <!-- Hover overlay efekti -->
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 transform -skew-x-12 translate-x-full group-hover:translate-x-[-100%] transition-transform duration-700"></div>
                    
                    <!-- Sol kenar vurgu çizgisi -->
                    <div class="absolute left-0 top-0 bottom-0 w-0 group-hover:w-1 transition-all duration-300 rounded-l-lg" style="background-color: {{ $topic->color }};"></div>
                </a>
                @endforeach
            </div>
            
            <!-- Alt bilgi alanı -->
            <div class="mt-8 bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <div class="text-center">
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Aradığınız hizmeti bulamadınız mı?</h3>
                    <p class="text-gray-600 mb-4">Tüm hizmetlerimizi görüntülemek için hizmetler sayfasını ziyaret edebilirsiniz.</p>
                    <a href="{{ route('services.index') }}" class="inline-flex items-center px-6 py-3 bg-[#007b32] hover:bg-[#00352b] text-white font-medium rounded-lg transition-colors duration-300">
                        <span class="material-icons mr-2">list</span>
                        Tüm Hizmetler
                    </a>
                </div>
            </div>
        @endif
    </div>
</section>

<style>
.topic-card-modern {
    min-height: 180px;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection 