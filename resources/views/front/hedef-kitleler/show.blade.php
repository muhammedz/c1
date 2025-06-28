@extends('layouts.front')

@section('title', $hedefKitle->name . ' - Hedef Kitle')

@section('meta_description', $hedefKitle->description ?? $hedefKitle->name . ' hedef kitlesine yönelik özel içeriklerimizi keşfedin.')

@include('helpers.functions')

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
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-14 relative z-10">
        <!-- Breadcrumb -->
        <div class="flex items-center space-x-1 text-sm text-white/70 mb-4">
            <a href="{{ route('front.home') }}" class="hover:text-white transition-colors duration-200">Ana Sayfa</a>
            <span class="material-icons text-xs">chevron_right</span>
            <a href="{{ route('hedefkitleler.index') }}" class="hover:text-white transition-colors duration-200">Hedef Kitleler</a>
            <span class="material-icons text-xs">chevron_right</span>
            <span class="text-white/90">{{ $hedefKitle->name }}</span>
        </div>
        
        <div class="flex flex-col items-start">
            <div class="inline-flex items-center px-3 py-1 rounded-full bg-white/10 text-white/90 text-sm border border-white/10 mb-3">
                <span class="material-icons text-xs mr-1">group</span>
                <span>Hedef Kitle</span>
            </div>
            
            <h1 class="text-3xl md:text-4xl font-extrabold text-white leading-tight mb-3">{{ $hedefKitle->name }}</h1>
            
            @if($hedefKitle->description)
            <p class="text-white/80 text-base mb-0 max-w-2xl">{{ $hedefKitle->description }}</p>
            @endif
            
            <div class="flex items-center space-x-4 mt-6">
                <div class="flex items-center bg-white/10 px-4 py-2 rounded-lg">
                    <span class="material-icons text-white/80 mr-2">article</span>
                    <span class="text-white font-medium">{{ $news->total() }} Haber</span>
                </div>
                
                <div class="flex items-center bg-white/10 px-4 py-2 rounded-lg">
                    <span class="material-icons text-white/80 mr-2">miscellaneous_services</span>
                    <span class="text-white font-medium">{{ $services->total() }} Hizmet</span>
                </div>
                
                <a href="{{ route('hedefkitleler.index') }}" class="inline-flex items-center bg-white/10 hover:bg-white/20 px-4 py-2 rounded-lg transition-colors duration-300">
                    <span class="material-icons text-white/80 mr-2">view_list</span>
                    <span class="text-white font-medium">Tüm Hedef Kitleler</span>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Ana İçerik - Haberler -->
<section class="py-12 bg-slate-100">
    <div class="container max-w-7xl mx-auto px-4">
        <!-- Haberler Başlığı -->
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                <span class="material-icons text-[#00352b] mr-2">newspaper</span>
                İlgili Haberler
            </h2>
            
            <!-- Mobilde gizlenir -->
            <div class="hidden md:block">
                <div class="flex items-center text-gray-500 text-sm">
                    <span class="material-icons text-xs mr-1">info</span>
                    <span>{{ $hedefKitle->name }} hedef kitlesine ait {{ $news->total() }} haber bulundu</span>
                </div>
            </div>
        </div>
        
        @if($news->isEmpty())
            <div class="bg-white p-8 rounded-lg shadow-md text-center">
                <span class="material-icons text-gray-400 text-5xl mb-4">info</span>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Bu hedef kitleye ait haber bulunmamaktadır</h3>
                <p class="text-gray-600">Lütfen daha sonra tekrar kontrol ediniz veya başka bir hedef kitle seçiniz.</p>
                <a href="{{ route('hedefkitleler.index') }}" class="inline-flex items-center mt-4 text-[#00352b] font-medium hover:underline">
                    <span class="material-icons text-sm mr-1">arrow_back</span>
                    Hedef Kitlelere Dön
                </a>
            </div>
        @else
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-4 border border-slate-100">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="px-5 py-4 text-sm font-semibold text-gray-700">Haber</th>
                                <th class="px-5 py-4 text-sm font-semibold text-gray-700 hidden md:table-cell">Kategori</th>
                                <th class="px-5 py-4 text-sm font-semibold text-gray-700 hidden md:table-cell">Tarih</th>
                                <th class="px-5 py-4 text-sm font-semibold text-gray-700 text-right">İşlem</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($news as $item)
                            <tr class="hover:bg-slate-50 transition-colors duration-200">
                                <td class="px-5 py-4">
                                    <div class="flex items-center">
                                        <!-- Mini resim -->
                                        <div class="flex-shrink-0 w-14 h-14 mr-4 rounded-md overflow-hidden bg-slate-100 shadow-sm border border-slate-200">
                                            @if($item->filemanagersystem_image)
                                                <img src="{{ asset($item->filemanagersystem_image) }}" alt="Haber görseli" class="w-full h-full object-cover">
                                            @elseif($item->image)
                                                <img src="{{ asset($item->image) }}" alt="Haber görseli" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center bg-slate-200">
                                                    <span class="material-icons text-slate-400">article</span>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <!-- Başlık -->
                                        <div class="max-w-md">
                                            <h3 class="text-base font-semibold text-gray-800 line-clamp-1 hover:text-[#00352b] transition-colors">{{ $item->title }}</h3>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-sm text-gray-600 hidden md:table-cell">
                                    @if($item->category)
                                        <span class="inline-flex items-center px-2.5 py-1.5 rounded-md text-xs font-medium bg-[#00352b]/10 text-[#00352b] border border-[#00352b]/10">
                                            {{ $item->category->name }}
                                        </span>
                                    @elseif($item->categories && $item->categories->count() > 0)
                                        <span class="inline-flex items-center px-2.5 py-1.5 rounded-md text-xs font-medium bg-[#00352b]/10 text-[#00352b] border border-[#00352b]/10">
                                            {{ $item->categories->first()->name }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-sm text-gray-600 hidden md:table-cell">
                                    <div class="flex items-center">
                                        <span class="material-icons text-gray-400 text-sm mr-1.5">calendar_today</span>
                                        <span>{{ $item->published_at ? $item->published_at->format('d.m.Y') : $item->created_at->format('d.m.Y') }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <a href="{{ route('news.show', $item->slug) }}" class="inline-flex items-center px-3.5 py-2 rounded-md text-sm font-medium text-white bg-[#00352b] hover:bg-[#00453a] transition-colors">
                                        <span class="whitespace-nowrap">Detaylar</span>
                                        <span class="material-icons text-sm ml-1.5">arrow_forward</span>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Sayfalama -->
            <div class="mt-6">
                {{ $news->links() }}
            </div>
        @endif
    </div>
</section>

<!-- İlişkili Hizmetler -->
<section class="py-12 bg-white border-t border-slate-200">
    <div class="container max-w-7xl mx-auto px-4">
        <!-- Hizmetler Başlığı -->
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                <span class="material-icons text-[#00352b] mr-2">miscellaneous_services</span>
                İlgili Hizmetler
            </h2>
            
            <!-- Mobilde gizlenir -->
            <div class="hidden md:block">
                <div class="flex items-center text-gray-500 text-sm">
                    <span class="material-icons text-xs mr-1">info</span>
                    <span>{{ $hedefKitle->name }} hedef kitlesine ait {{ $services->total() }} hizmet bulundu</span>
                </div>
            </div>
        </div>
        
        @if($services->isEmpty())
            <div class="bg-white p-8 rounded-lg shadow-md text-center border border-slate-100">
                <span class="material-icons text-gray-400 text-5xl mb-4">info</span>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Bu hedef kitleye ait hizmet bulunmamaktadır</h3>
                <p class="text-gray-600">Lütfen daha sonra tekrar kontrol ediniz veya tüm hizmetlerimizi görüntüleyin.</p>
                <a href="{{ route('services.index') }}" class="inline-flex items-center mt-4 text-[#00352b] font-medium hover:underline">
                    <span class="material-icons text-sm mr-1">view_list</span>
                    Tüm Hizmetleri Görüntüle
                </a>
            </div>
        @else
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-4 border border-slate-100">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="px-5 py-4 text-sm font-semibold text-gray-700">Hizmet</th>
                                <th class="px-5 py-4 text-sm font-semibold text-gray-700 hidden md:table-cell">Kategori</th>
                                <th class="px-5 py-4 text-sm font-semibold text-gray-700 text-right">İşlem</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($services as $service)
                            <tr class="hover:bg-slate-50 transition-colors duration-200">
                                <td class="px-5 py-4">
                                    <div class="flex items-center">
                                        <!-- Mini resim -->
                                        <div class="flex-shrink-0 w-14 h-14 mr-4 rounded-md overflow-hidden bg-slate-100 shadow-sm border border-slate-200">
                                            @if($service->image)
                                                <img src="{{ asset($service->image) }}" alt="{{ $service->title }} hizmet görseli" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center bg-slate-200">
                                                    <span class="material-icons text-slate-400">business</span>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <!-- Başlık -->
                                        <div class="max-w-md">
                                            <h3 class="text-base font-semibold text-gray-800 line-clamp-1 hover:text-[#00352b] transition-colors">{{ $service->title }}</h3>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-sm text-gray-600 hidden md:table-cell">
                                    @if($service->categories->count() > 0)
                                        <span class="inline-flex items-center px-2.5 py-1.5 rounded-md text-xs font-medium bg-[#00352b]/10 text-[#00352b] border border-[#00352b]/10">
                                            {{ $service->categories->first()->name }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <a href="{{ route('services.show', $service->slug) }}" class="inline-flex items-center px-3.5 py-2 rounded-md text-sm font-medium text-white bg-[#00352b] hover:bg-[#00453a] transition-colors">
                                        <span class="whitespace-nowrap">Detaylar</span>
                                        <span class="material-icons text-sm ml-1.5">arrow_forward</span>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Sayfalama -->
            <div class="mt-6">
                {{ $services->links() }}
            </div>
        @endif
    </div>
</section>

<!-- İlgili Hedef Kitleler -->
<section class="py-12 bg-slate-50 border-t border-slate-200">
    <div class="container max-w-7xl mx-auto px-4">
        <h2 class="text-2xl font-bold text-gray-800 mb-8 flex items-center">
            <span class="material-icons text-[#00352b] mr-2">groups</span>
            Diğer Hedef Kitleler
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach(\App\Models\HedefKitle::where('id', '!=', $hedefKitle->id)->where('is_active', true)->inRandomOrder()->take(3)->get() as $otherHedefKitle)
            <a href="{{ route('hedefkitleler.show', $otherHedefKitle->slug) }}" 
               class="group flex items-center p-5 bg-slate-50 hover:bg-slate-100 rounded-lg border border-slate-100 transition-colors duration-300">
                <div class="w-12 h-12 rounded-full bg-[#00352b]/10 flex items-center justify-center mr-4 group-hover:bg-[#00352b]/20 transition-colors duration-300">
                    <span class="material-icons text-[#00352b]">{{ ['people', 'groups', 'diversity_3'][array_rand(['people', 'groups', 'diversity_3'])] }}</span>
                </div>
                
                <div class="flex-1 min-w-0">
                    <h3 class="text-lg font-semibold text-gray-800 mb-1 truncate group-hover:text-[#00352b] transition-colors duration-300">{{ $otherHedefKitle->name }}</h3>
                    <p class="text-gray-500 text-sm truncate">{{ $otherHedefKitle->news()->count() }} haber</p>
                </div>
                
                <div class="ml-4">
                    <span class="material-icons text-[#00352b] group-hover:translate-x-1 transition-transform duration-300">arrow_forward</span>
                </div>
            </a>
            @endforeach
        </div>
        
        <div class="flex justify-center mt-8">
            <a href="{{ route('hedefkitleler.index') }}" class="inline-flex items-center px-6 py-3 bg-[#00352b] hover:bg-[#00453a] text-white rounded-lg transition-colors duration-300">
                <span class="material-icons mr-2">view_list</span>
                Tüm Hedef Kitleleri Görüntüle
            </a>
        </div>
    </div>
</section>
@endsection 