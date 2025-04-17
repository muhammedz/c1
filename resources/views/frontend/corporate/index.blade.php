@extends('layouts.front')

@section('title', 'Kurumsal Kadro')
@section('meta_description', 'Kurumsal kadro kategorileri ve yetkili üyelerimiz')

@section('content')
<!-- Hero Bölümü -->
<section class="relative bg-[#00352b] pt-28 pb-16 overflow-hidden">
    <!-- Arka Plan Desenleri -->
    <div class="absolute inset-0 overflow-hidden opacity-10">
        <div class="absolute -right-10 -top-10 w-64 h-64 rounded-full bg-white"></div>
        <div class="absolute left-1/3 bottom-0 w-96 h-96 rounded-full bg-white"></div>
    </div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-10 relative z-10">
        <div class="flex flex-col md:flex-row justify-between gap-8 items-center">
            <!-- Sol Taraf: Başlık ve Açıklama -->
            <div class="md:w-1/2">
                <h1 class="text-3xl md:text-5xl font-bold mb-4 text-white leading-tight">Kurumsal Kadro</h1>
                <p class="text-lg text-white/80 mb-6 max-w-xl">Kurumumuzun farklı birimlerinde görev alan profesyonel kadromuzu tanıyın. Deneyimli ekibimiz hakkında detaylı bilgiye buradan ulaşabilirsiniz.</p>
                
                <nav class="flex mb-5" aria-label="Breadcrumb">
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
                                <span class="ml-1 text-sm font-medium">Kurumsal Kadro</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
            
            <!-- Sağ Taraf: İstatistikler veya Görsel -->
            <div class="md:w-1/2 text-center">
                <div class="bg-white/10 backdrop-blur-sm p-6 rounded-xl border border-white/10 shadow-xl">
                    <span class="material-icons text-white text-5xl mb-3">groups</span>
                    <h3 class="text-white font-semibold text-xl mb-2">Profesyonel Kadrolar</h3>
                    <p class="text-white/80">Yetkin ekibimizle hizmetinizdeyiz</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Kategoriler Bölümü -->
<section class="py-12 bg-slate-100">
    <div class="container max-w-7xl mx-auto px-4">
        @if(count($categories) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($categories as $category)
            <a href="{{ route('corporate.category', $category->slug) }}" class="block bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-all duration-300 hover:-translate-y-1 group">
                <!-- Kategori Görseli -->
                <div class="relative h-48 overflow-hidden">
                    @if($category->image)
                    <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                    <div class="w-full h-full flex items-center justify-center bg-slate-50">
                        <span class="material-icons text-slate-300 text-5xl">groups</span>
                    </div>
                    @endif
                    
                    <!-- Overlay - Üye Sayısı -->
                    <div class="absolute top-3 right-3 bg-black/50 text-white text-xs px-2 py-1 rounded-full backdrop-blur-sm">
                        <span>{{ $category->members()->active()->count() }} üye</span>
                    </div>
                </div>
                
                <!-- Kategori Bilgileri -->
                <div class="p-5">
                    <h3 class="text-xl font-bold text-gray-800 mb-2 group-hover:text-[#00352b] transition-colors">{{ $category->name }}</h3>
                    
                    @if($category->description)
                    <p class="text-gray-600 mb-4 line-clamp-2">{{ Str::limit($category->description, 120) }}</p>
                    @endif
                    
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">{{ $category->members()->active()->count() }} aktif üye</span>
                        <span class="inline-flex items-center text-[#00352b] font-medium group-hover:translate-x-1 transition-transform">
                            Detaylar
                            <span class="material-icons text-sm ml-1">arrow_forward</span>
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @else
        <div class="bg-white p-8 rounded-lg shadow-md text-center">
            <span class="material-icons text-gray-400 text-5xl mb-4">info</span>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Henüz kurumsal kadro bilgisi eklenmemiştir</h3>
            <p class="text-gray-600">Lütfen daha sonra tekrar kontrol ediniz.</p>
        </div>
        @endif
    </div>
</section>
@endsection 