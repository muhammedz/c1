@extends('layouts.front')

@section('title', 'Müdürlüklerimiz')

@section('meta_description', 'Belediyemizin müdürlükleri ve sundukları hizmetler hakkında bilgi alın')

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
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 md:py-10 relative z-10">
        <div class="flex flex-col md:flex-row items-center justify-between">
            <!-- Sol taraf - başlık ve açıklama -->
            <div class="w-full md:w-1/2 mb-6 md:mb-0 md:pr-8">
                <div class="inline-flex items-center px-3 py-1 rounded-full bg-white/10 text-white/90 text-sm border border-white/10 mb-3">
                    <span class="material-icons text-xs mr-1">business</span>
                    <span>Belediye Müdürlükleri</span>
                </div>
                
                <h1 class="text-3xl md:text-4xl font-extrabold text-white leading-tight mb-3">
                    <span class="text-[#e6a23c]">Müdürlüklerimiz</span> ve Hizmetleri
                </h1>
                
                <p class="text-white/80 text-base mb-0 max-w-lg">
                    Belediyemizin organizasyon yapısını oluşturan müdürlüklerimiz ve sundukları hizmetler hakkında detaylı bilgi alın.
                </p>
            </div>
            
            <!-- Sağ taraf - istatistik kutusu -->
            <div class="w-full md:w-1/2">
                <div class="bg-white/10 backdrop-blur-md p-6 rounded-xl border border-white/20">
                    <h3 class="text-white text-xl font-bold mb-4">Organizasyon Yapımız</h3>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-[#e6a23c] mb-1">{{ $mudurlukler->total() }}</div>
                            <div class="text-white/80 text-sm">Müdürlük</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-[#e6a23c] mb-1">{{ $mudurlukler->where('is_active', true)->count() }}</div>
                            <div class="text-white/80 text-sm">Aktif Birim</div>
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-4 border-t border-white/10">
                        <div class="flex items-center justify-center text-white/80 text-sm">
                            <span class="material-icons mr-2 text-[#e6a23c]">info</span>
                            <span>Tüm müdürlüklerimizin detaylarını aşağıda bulabilirsiniz</span>
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
        @if($mudurlukler->isEmpty())
            <div class="bg-white p-8 rounded-lg shadow-md text-center">
                <span class="material-icons text-gray-400 text-5xl mb-4">info</span>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Henüz müdürlük bilgisi bulunmamaktadır</h3>
                <p class="text-gray-600">Lütfen daha sonra tekrar kontrol ediniz.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($mudurlukler as $mudurluk)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow border-l-4 border-[#00352b]">
                    <!-- Görsel -->
                    <div class="h-48 overflow-hidden">
                        @if($mudurluk->image)
                            <img src="{{ asset('storage/' . $mudurluk->image) }}" alt="{{ $mudurluk->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-[#00352b] to-[#20846c]">
                                <span class="material-icons text-white text-4xl">business</span>
                            </div>
                        @endif
                    </div>
                    
                    <!-- İçerik -->
                    <div class="p-5">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $mudurluk->name }}</h3>
                        
                        @if($mudurluk->summary)
                            <p class="text-gray-600 mb-4 line-clamp-3">{{ $mudurluk->summary }}</p>
                        @endif
                        
                        <!-- İstatistikler -->
                        <div class="flex items-center justify-between mb-4 text-sm text-gray-500">
                            <div class="flex items-center">
                                <span class="material-icons text-xs mr-1">visibility</span>
                                <span>{{ $mudurluk->view_count ?? 0 }} görüntülenme</span>
                            </div>
                            @if($mudurluk->files_count > 0)
                                <div class="flex items-center">
                                    <span class="material-icons text-xs mr-1">description</span>
                                    <span>{{ $mudurluk->files_count }} dosya</span>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Detay Butonu -->
                        <a href="{{ route('mudurlukler.show', $mudurluk->slug) }}" class="inline-flex items-center text-[#00352b] hover:text-[#20846c] font-medium transition-colors">
                            Detaylı Bilgi
                            <span class="material-icons text-sm ml-1">arrow_forward</span>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Sayfalama -->
            @if($mudurlukler->hasPages())
                <div class="mt-12">
                    {{ $mudurlukler->links() }}
                </div>
            @endif
        @endif
    </div>
</section>

<!-- İletişim Bölümü -->
<section class="py-12 bg-white border-t border-slate-200">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Daha Fazla Bilgi İçin</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Müdürlüklerimiz hakkında daha detaylı bilgi almak veya özel durumlarınız için doğrudan iletişime geçebilirsiniz.
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center p-6 bg-slate-50 rounded-lg">
                <div class="w-12 h-12 bg-[#00352b] rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="material-icons text-white">phone</span>
                </div>
                <h3 class="font-bold text-gray-800 mb-2">Telefon</h3>
                <p class="text-gray-600">444 06 01</p>
            </div>
            
            <div class="text-center p-6 bg-slate-50 rounded-lg">
                <div class="w-12 h-12 bg-[#00352b] rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="material-icons text-white">email</span>
                </div>
                <h3 class="font-bold text-gray-800 mb-2">E-posta</h3>
                <p class="text-gray-600">info@belediye.gov.tr</p>
            </div>
            
            <div class="text-center p-6 bg-slate-50 rounded-lg">
                <div class="w-12 h-12 bg-[#00352b] rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="material-icons text-white">location_on</span>
                </div>
                <h3 class="font-bold text-gray-800 mb-2">Adres</h3>
                <p class="text-gray-600">Belediye Binası, Merkez</p>
            </div>
        </div>
    </div>
</section>
@endsection

@section('css')
<style>
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection 