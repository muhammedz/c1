@extends('layouts.front')

@section('title', 'Hedef Kitleler - ' . config('app.name'))

@section('meta_description', 'Tüm hedef kitleleri keşfedin. Size özel içerikler ve daha fazlası burada.')

@include('helpers.functions')

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
            <div class="w-full md:w-2/3 mb-6 md:mb-0 md:pr-8">
                <div class="inline-flex items-center px-3 py-1 rounded-full bg-white/10 text-white/90 text-sm border border-white/10 mb-3">
                    <span class="material-icons text-xs mr-1">group</span>
                    <span>Hedef Kitleler</span>
                </div>
                
                <h1 class="text-3xl md:text-4xl font-extrabold text-white leading-tight mb-3">
                    Hedefe Yönelik <span class="text-[#e6a23c]">Özel İçerikler</span>
                </h1>
                
                <p class="text-white/80 text-base mb-0 max-w-lg">
                    Farklı hedef kitlelere yönelik içeriklerimizi keşfedin. Sizin için özel olarak hazırlanan haberlere göz atın.
                </p>
            </div>
            
            <!-- Sağ taraf - arama kutusu -->
            <div class="w-full md:w-1/3 md:pl-8">
                <div class="bg-white/10 backdrop-blur-md rounded-xl p-4 border border-white/20 shadow-xl">
                    <h3 class="text-lg text-white font-medium mb-2">Hızlı İstatistikler</h3>
                    
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 bg-white/10 rounded-lg border border-white/10">
                            <div class="flex items-center">
                                <span class="material-icons text-white/90 mr-2">people_alt</span>
                                <span class="text-white/90">Toplam Hedef Kitle</span>
                            </div>
                            <span class="text-white font-bold">{{ $hedefKitleler->count() }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between p-3 bg-white/10 rounded-lg border border-white/10">
                            <div class="flex items-center">
                                <span class="material-icons text-white/90 mr-2">article</span>
                                <span class="text-white/90">Toplam İçerik</span>
                            </div>
                            <span class="text-white font-bold">{{ \App\Models\News::where('status', 'published')->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Ana İçerik - Hedef Kitleler -->
<section class="py-12 bg-slate-100">
    <div class="container max-w-7xl mx-auto px-4">
        @if($hedefKitleler->isEmpty())
            <div class="bg-white p-8 rounded-lg shadow-md text-center">
                <span class="material-icons text-gray-400 text-5xl mb-4">info</span>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Henüz hedef kitle bulunmamaktadır</h3>
                <p class="text-gray-600">Lütfen daha sonra tekrar kontrol ediniz.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($hedefKitleler as $hedefKitle)
                <a href="{{ route('hedefkitleler.show', $hedefKitle->slug) }}" 
                   class="group block bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1 border border-slate-100 overflow-hidden relative">
                    
                    <!-- Arka plan renk geçişi -->
                    <div class="absolute inset-0 bg-gradient-to-br from-[#00352b]/5 to-[#20846c]/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    
                    <!-- İçerik -->
                    <div class="p-6 relative z-10">
                        <div class="w-12 h-12 rounded-full bg-[#00352b]/10 flex items-center justify-center mb-4 group-hover:bg-[#00352b]/20 transition-colors duration-300">
                            <span class="material-icons text-[#00352b]">{{ $loop->index % 3 === 0 ? 'people' : ($loop->index % 3 === 1 ? 'groups' : 'diversity_3') }}</span>
                        </div>
                        
                        <h3 class="text-xl font-bold text-gray-800 mb-3 group-hover:text-[#00352b] transition-colors duration-300">{{ $hedefKitle->name }}</h3>
                        
                        @if($hedefKitle->description)
                        <p class="text-gray-600 mb-6 line-clamp-3">{{ $hedefKitle->description }}</p>
                        @else
                        <p class="text-gray-600 mb-6 line-clamp-3">{{ $hedefKitle->name }} hedef kitlesine yönelik içerikleri keşfedin.</p>
                        @endif
                        
                        <div class="flex items-center justify-between mt-auto pt-4 border-t border-slate-100">
                            <div class="flex items-center text-[#00352b]">
                                <span class="material-icons mr-1 text-sm">article</span>
                                <span class="text-sm font-medium">{{ $hedefKitle->news()->count() }} Haber</span>
                            </div>
                            
                            <div class="inline-flex items-center text-[#00352b] group-hover:translate-x-1 transition-transform duration-300">
                                <span class="text-sm font-medium mr-1">İncele</span>
                                <span class="material-icons text-sm">arrow_forward</span>
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        @endif
    </div>
</section>

<!-- Bilgi Bölümü -->
<section class="py-12 bg-white border-t border-slate-200">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4">Hedef Kitleler Nedir?</h2>
                <p class="text-gray-600 mb-4">Hedef kitleler, içeriklerin belirli kullanıcı gruplarına yönelik olarak sınıflandırılmasını sağlar. Her hedef kitle için özel olarak hazırlanmış içeriklerimize göz atabilirsiniz.</p>
                <p class="text-gray-600 mb-4">İlgi alanlarınıza ve ihtiyaçlarınıza göre sınıflandırılmış içeriklerimiz sayesinde, size en uygun haberleri kolayca bulabilirsiniz.</p>
                <div class="flex items-center space-x-2 text-[#00352b]">
                    <span class="material-icons">info</span>
                    <p class="font-medium">Her hedef kitle, kendine has içeriklerle donatılmıştır.</p>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div class="p-4 rounded-lg bg-slate-50 border border-slate-100 hover:shadow-md transition-shadow duration-300">
                    <div class="w-10 h-10 rounded-full bg-[#00352b]/10 flex items-center justify-center mb-3">
                        <span class="material-icons text-[#00352b]">tune</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Özelleştirilmiş</h3>
                    <p class="text-gray-600 text-sm">Her hedef kitle için özel içerikler</p>
                </div>
                
                <div class="p-4 rounded-lg bg-slate-50 border border-slate-100 hover:shadow-md transition-shadow duration-300">
                    <div class="w-10 h-10 rounded-full bg-[#00352b]/10 flex items-center justify-center mb-3">
                        <span class="material-icons text-[#00352b]">query_stats</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Kullanışlı</h3>
                    <p class="text-gray-600 text-sm">İlgili içerikleri kolay bulma</p>
                </div>
                
                <div class="p-4 rounded-lg bg-slate-50 border border-slate-100 hover:shadow-md transition-shadow duration-300">
                    <div class="w-10 h-10 rounded-full bg-[#00352b]/10 flex items-center justify-center mb-3">
                        <span class="material-icons text-[#00352b]">update</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Güncel</h3>
                    <p class="text-gray-600 text-sm">Düzenli güncellenen içerikler</p>
                </div>
                
                <div class="p-4 rounded-lg bg-slate-50 border border-slate-100 hover:shadow-md transition-shadow duration-300">
                    <div class="w-10 h-10 rounded-full bg-[#00352b]/10 flex items-center justify-center mb-3">
                        <span class="material-icons text-[#00352b]">category</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Kategorize</h3>
                    <p class="text-gray-600 text-sm">İçerikler özenle sınıflandırılmış</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection 