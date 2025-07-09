@extends('layouts.front')

@section('title', $page->meta_title ?? $page->title)

@section('meta_description', $page->meta_description ?? Str::limit(strip_tags($page->content), 160))

@if($page->meta_keywords)
    @section('meta_keywords', $page->meta_keywords)
@endif

@if($page->image)
    @section('meta_image', $page->image)
@endif

@section('css')
<style>
    :root {
        --header-height: 96px; /* Varsayılan header yüksekliği */
        --primary-color: #00352b; /* Ana renk */
        --primary-light: rgba(0, 53, 43, 0.1); /* Ana rengin açık tonu */
        --primary-dark: #002a22; /* Ana rengin koyu tonu */
        --secondary-color: #20846c; /* İkincil renk */
        --accent-color: #e6a23c; /* Vurgu rengi */
    }
    
    .page-layout-container {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 1rem;
    }
    
    .page-grid-layout {
        display: grid;
        grid-template-columns: 300px 1fr;
        gap: 2rem;
        margin-top: -20px;
        align-items: flex-start; /* İki sütunun da üstten başlamasını sağlar */
    }
    
    .page-grid-layout::before {
        content: "";
        display: block;
        grid-column: 1;
        min-height: 1px;
    }
    
    .page-content-area {
        grid-column: 2;
        padding-top: 0; /* Üst padding'i kaldırdık */
    }
    
    .page-content-section {
        background-color: white;
        border-radius: 12px;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
        padding: 30px;
        margin-bottom: 30px;
        border-top: 4px solid var(--primary-color);
    }
    
    .page-content-section h2 {
        font-size: 24px;
        font-weight: 700;
        color: var(--primary-color);
        margin-top: 0;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #e5e7eb;
    }
    
    @media (max-width: 768px) {
        .page-grid-layout {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .page-content-area {
            grid-column: 1;
        }
        
        .page-grid-layout::before {
            display: none;
        }
    }

    /* İçindekiler stillerini sol tarafta sticky olacak şekilde düzenle */
    #sidebar {
        grid-column: 1;
        align-self: flex-start; /* Üst hizalama */
        position: relative;
        width: 300px; /* Sabit genişlik tanımı */
        margin-top: 0; /* İçindekiler menüsünün üst marjinini sıfırla */
    }

    #sidebar .sticky-container {
        width: 100%;
        position: relative;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        transition: none; /* Animasyonları kaldır */
        max-height: calc(100vh - 120px);
        overflow-y: auto;
        padding: 30px; /* Sağdaki içerik bölümü ile aynı padding değeri */
    }

    #sidebar.scrolledDown .sticky-container {
        position: fixed;
        top: 20px;
        width: 300px; /* Sabit genişlik */
        z-index: 40;
    }

    #sidebar .sticky-container::-webkit-scrollbar {
        width: 4px;
    }

    #sidebar .sticky-container::-webkit-scrollbar-track {
        background: #f5f5f5;
        border-radius: 10px;
    }

    #sidebar .sticky-container::-webkit-scrollbar-thumb {
        background: #00352b;
        border-radius: 10px;
    }

    @media (max-width: 768px) {
        #sidebar {
            display: none;
        }
        
        #sidebar.active {
            display: block;
            position: fixed;
            top: 15px;
            left: 15px;
            right: 15px;
            width: auto;
            z-index: 100;
        }
        
        #sidebar.scrolledDown .sticky-container {
            position: relative;
            top: 0;
            left: 0 !important; /* Mobilde varsayılan pozisyon, JS'in eklediği style'ı ezmek için !important kullanıldı */
            width: 100% !important; /* Tam genişlik */
            transform: none; /* Transform değerini kaldırdık */
        }
    }
    
    /* Genel Tablo Stilleri */
    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin: 25px 0;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }
    
    table thead {
        background-color: var(--primary-color);
        color: white;
    }
    
    table th {
        font-weight: 600;
        text-align: left;
        padding: 14px 16px;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    table td {
        padding: 12px 16px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        font-size: 14px;
        vertical-align: middle;
    }
    
    table tr:last-child td {
        border-bottom: none;
    }
    
    table tr:nth-child(even) {
        background-color: rgba(0, 0, 0, 0.02);
    }
    
    table tr:hover {
        background-color: rgba(0, 53, 43, 0.03);
    }
    
    /* Tablo başlık stillemesi */
    table caption {
        background-color: var(--primary-color);
        color: white;
        padding: 12px 16px;
        font-weight: 600;
        font-size: 16px;
        text-align: left;
        caption-side: top;
    }
    
    /* ========================================
       MOBİL TAM EKRAN TABLO OPTİMİZASYONU
       ======================================== */
    
    /* Tablet ve mobil için tam ekran tablolar */
    @media (max-width: 768px) {
        table {
            margin: 20px -15px !important; /* Sayfa padding'ini iptal et */
            width: calc(100% + 30px) !important; /* Tam genişlik */
            border-radius: 0 !important; /* Köşe yuvarlama kaldır */
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) !important; /* Daha hafif gölge */
            table-layout: fixed !important; /* Sütunları eşit genişlikte yap */
        }
        
        table th {
            padding: 10px 8px !important;
            font-size: 12px !important;
            white-space: nowrap !important;
            text-overflow: ellipsis !important;
            overflow: hidden !important;
        }
        
        table td {
            padding: 10px 8px !important;
            font-size: 12px !important;
            white-space: nowrap !important;
            text-overflow: ellipsis !important;
            overflow: hidden !important;
            word-break: break-word !important;
        }
        
        table caption {
            margin: 0 15px !important; /* Caption için kenarlık */
            border-radius: 0 !important;
        }
    }
    
    /* En küçük mobil ekranlar için */
    @media (max-width: 480px) {
        table {
            margin: 15px -15px !important;
            width: calc(100% + 30px) !important;
            font-size: 11px !important;
        }
        
        table th {
            padding: 8px 6px !important;
            font-size: 10px !important;
        }
        
        table td {
            padding: 8px 6px !important;
            font-size: 10px !important;
        }
    }
</style>
@endsection

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
                <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">{{ $page->title }}</h1>
                @if($page->summary)
                <p class="text-white/80 text-base mb-2">{{ $page->summary }}</p>
                @endif
                
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3 text-white/70">
                        <li class="inline-flex items-center">
                            <a href="{{ route('front.home') }}" class="inline-flex items-center text-sm hover:text-white">
                                <span class="material-icons text-xs mr-1">home</span>
                                Anasayfa
                            </a>
                        </li>
                        @if($page->categories->count() > 0)
                            <li>
                                <div class="flex items-center">
                                    <span class="material-icons text-xs mx-1">chevron_right</span>
                                    <a href="{{ route('pages.category', $page->categories->first()->slug) }}" class="inline-flex items-center text-sm hover:text-white">
                                        {{ $page->categories->first()->name }}
                                    </a>
                                </div>
                            </li>
                        @endif
                        <li aria-current="page">
                            <div class="flex items-center">
                                <span class="material-icons text-xs mx-1">chevron_right</span>
                                <span class="ml-1 text-sm font-medium">{{ $page->title }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- Ana İçerik Grid Düzeni -->
<div class="page-layout-container page-grid-layout py-12">
    
    <!-- Sol Taraf - İçindekiler Menüsü -->
    <div id="sidebar" class="relative">
        <div class="sticky-container">
            <div class="mb-6 border-b border-gray-200 pb-4">
                <h3 class="text-xl font-bold text-[#00352b] mb-2">Kategoriler</h3>
                <div class="space-y-2">
                    @foreach($categories as $category)
                        <a href="{{ route('pages.category', $category->slug) }}" 
                           class="flex items-center justify-between px-3 py-2 rounded-lg {{ $page->categories->contains('id', $category->id) ? 'bg-[#00352b] text-white' : 'text-gray-700 hover:bg-gray-100' }} transition-all">
                            <div class="flex items-center">
                                @if($category->icon)
                                    <i class="{{ $category->icon }} mr-2.5 {{ $page->categories->contains('id', $category->id) ? 'text-white' : 'text-gray-500' }}"></i>
                                @endif
                                <span>{{ $category->name }}</span>
                            </div>
                            <span class="text-xs py-1 px-2.5 rounded-full {{ $page->categories->contains('id', $category->id) ? 'bg-white text-[#00352b]' : 'bg-gray-100 text-gray-600' }}">
                                {{ $category->pages_count }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
            
            <!-- İletişim Bölümü -->
            <div class="mb-6 border-b border-gray-200 pb-4">
                <h3 class="text-xl font-bold text-[#00352b] mb-2">İletişim</h3>
                <p class="text-sm text-gray-600 mb-4">Sorularınız için bizimle iletişime geçebilirsiniz.</p>
                <a href="#" class="inline-flex items-center justify-center w-full px-4 py-2.5 bg-[#00352b] text-white rounded-lg hover:bg-[#002a22] transition-all shadow-sm hover:shadow">
                    <i class="fas fa-phone-alt mr-2"></i> İletişime Geç
                </a>
            </div>
            

        </div>
    </div>
    
    <!-- Sağ Taraf - Ana İçerik Alanı -->
    <div class="page-content-area">
        <!-- İçerik Bölümü -->
        <div class="page-content-section">
            <h2 class="mb-6">İçerik</h2>
            
            <!-- Özet -->
            @if($page->summary)
                <div class="text-lg text-gray-600 mb-8 font-medium border-l-4 border-[#00352b] pl-5 py-4 bg-slate-50 rounded-r-lg">
                    {{ $page->summary }}
                </div>
            @endif
            
            <!-- İçerik -->
            <article class="prose prose-lg max-w-none mb-8 prose-headings:text-gray-800 prose-p:text-gray-700 prose-a:text-[#00352b] prose-a:no-underline hover:prose-a:text-[#20846c] hover:prose-a:underline prose-img:rounded-lg">
                {!! $page->content !!}
            </article>
            
            <!-- Mobil kullanıcılar için tablo uyarısı -->
            <div class="mobile-table-warning d-block d-md-none" style="background: linear-gradient(135deg, #e3f2fd 0%, #f8f9fa 100%); border: 1px solid #2196f3; border-radius: 8px; padding: 12px; margin: 15px 0; font-size: 12px; color: #1976d2;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-info-circle" style="color: #2196f3;"></i>
                    <span><strong>Mobil Görüntüleme İpucu:</strong> Tablolarda detay görmek için cihazınızı yaklaştırabilir veya yatay kaydırma yapabilirsiniz.</span>
                </div>
            </div>
        </div>
        
        <!-- Galeri Bölümü -->
        @if(isset($page->gallery) && count($page->gallery) > 0)
            <div class="page-content-section">
                <h2 class="mb-6">Galeri</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($page->gallery as $image)
                        <a href="{{ asset($image) }}" data-fancybox="gallery" class="gallery-item block rounded-lg overflow-hidden shadow-md border-0 aspect-square bg-white hover:shadow-lg transition-all">
                            <img src="{{ asset($image) }}" alt="{{ $page->title }} sayfa görseli {{ $loop->iteration }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
        

    </div>
</div>

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const stickyContainer = sidebar.querySelector('.sticky-container');
        const headerHeight = 96; // Header yüksekliğini ayarlayın
        
        function updateSidebarPosition() {
            const rect = sidebar.getBoundingClientRect();
            const stickyTop = rect.top;
            
            if (stickyTop <= headerHeight) {
                sidebar.classList.add('scrolledDown');
                stickyContainer.style.width = rect.width + 'px';
                stickyContainer.style.left = rect.left + 'px';
            } else {
                sidebar.classList.remove('scrolledDown');
                stickyContainer.style.width = '';
                stickyContainer.style.left = '';
            }
        }
        
        window.addEventListener('scroll', updateSidebarPosition);
        window.addEventListener('resize', updateSidebarPosition);
        updateSidebarPosition();
    });
</script>
@endsection

@endsection 