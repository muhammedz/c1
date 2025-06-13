@extends('layouts.front')

@section('title', 'Çankaya Evleri')

@section('css')
<style>
    /* Hero Bölümü */
    .category-header {
        position: relative;
        background: linear-gradient(to right, #00352b, #20846c);
        padding: 3rem 0;
        overflow: hidden;
    }

    .pattern-overlay {
        position: absolute;
        inset: 0;
        opacity: 0.08;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60' viewBox='0 0 60 60'%3E%3Cg fill='none' stroke='white' stroke-width='1'%3E%3Cpath d='M30 0v60M0 30h60'/%3E%3Ccircle cx='30' cy='30' r='20'/%3E%3C/g%3E%3C/svg%3E");
        pointer-events: none;
        z-index: 1;
    }
    
    .decorative-shape-1 {
        position: absolute;
        right: -5rem;
        bottom: -5rem;
        width: 16rem;
        height: 16rem;
        border-radius: 9999px;
        background: rgba(230, 162, 60, 0.1);
        filter: blur(24px);
        z-index: 1;
    }

    .decorative-shape-2 {
        position: absolute;
        left: -2.5rem;
        top: 2.5rem;
        width: 10rem;
        height: 10rem;
        border-radius: 9999px;
        background: rgba(255, 255, 255, 0.05);
        filter: blur(16px);
        z-index: 1;
    }

    .category-container {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 1rem;
        position: relative;
        z-index: 5;
    }

    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
        font-size: 0.75rem;
        position: relative;
        z-index: 20;
    }

    .breadcrumb-item {
        color: rgba(255, 255, 255, 0.8);
    }
    
    .breadcrumb-separator {
        color: rgba(255, 255, 255, 0.6);
    }
    
    .breadcrumb-link {
        color: rgba(255, 255, 255, 0.9);
        transition: all 0.2s ease;
        text-decoration: none;
        padding: 0.2rem 0.4rem;
        border-radius: 0.25rem;
        position: relative;
        z-index: 30;
        cursor: pointer;
        background-color: rgba(255, 255, 255, 0.05);
    }

    .breadcrumb-link:hover {
        color: white;
        background-color: rgba(255, 255, 255, 0.15);
        text-decoration: underline;
    }
    
    .category-title {
        font-size: 2.25rem;
        font-weight: 700;
        color: white;
        margin: 0;
        line-height: 1.2;
        position: relative;
        z-index: 10;
    }

    .category-description {
        color: rgba(255, 255, 255, 0.8);
        font-size: 1.125rem;
        margin-top: 0.75rem;
        max-width: 36rem;
        line-height: 1.5;
        position: relative;
        z-index: 10;
    }

    /* İçerik Bölümü */
    .houses-portal {
        padding: 3rem 0;
        background-color: #f8f9fa;
    }
    
    /* Çankaya Evleri Grid */
    .houses-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
        margin-bottom: 4rem;
    }
    
    @media (max-width: 1200px) {
        .houses-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    
    @media (max-width: 992px) {
        .houses-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .houses-grid {
            grid-template-columns: 1fr;
        }
    }

    .house-card-link {
        text-decoration: none;
        color: inherit;
        display: block;
        height: 100%;
    }
    
    .house-card-link:hover {
        text-decoration: none;
        color: inherit;
    }
    
    .house-card {
        background-color: white;
        border-radius: 0.75rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        border: 1px solid #e5e7eb;
        position: relative;
        cursor: pointer;
    }
    
    .house-card-link:hover .house-card {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        border-color: #00352b;
    }

    .house-card-image-container {
        width: 100%;
        height: 200px;
        overflow: hidden;
        position: relative;
        background-color: #f3f4f6;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .house-card-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        transition: transform 0.5s ease;
    }

    .house-card-link:hover .house-card-image {
        transform: scale(1.05);
    }

    .house-card-content {
        padding: 1.5rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .house-card-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1f2937;
        margin: 0 0 0.75rem 0;
        line-height: 1.3;
    }

    .house-card-address {
        color: #6b7280;
        font-size: 0.875rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: flex-start;
        gap: 0.5rem;
    }

    .house-card-stats {
        display: flex;
        gap: 1rem;
        margin-bottom: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #e5e7eb;
    }

    .house-stat {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        font-size: 0.875rem;
        color: #6b7280;
    }

    .house-stat-number {
        font-weight: 600;
        color: #00352b;
    }



    .house-card-footer {
        padding: 1rem 1.5rem;
        background-color: #f9fafb;
        border-top: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .house-contact-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        color: #6b7280;
    }

    .view-house-btn {
        background: linear-gradient(135deg, #00352b 0%, #20846c 100%);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        border: none;
        box-shadow: 0 2px 8px rgba(0, 53, 43, 0.2);
        position: relative;
        overflow: hidden;
    }

    .view-house-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s ease;
    }

    .house-card-link:hover .view-house-btn {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 53, 43, 0.3);
        background: linear-gradient(135deg, #20846c 0%, #00352b 100%);
    }

    .house-card-link:hover .view-house-btn::before {
        left: 100%;
    }

    .view-house-btn i {
        transition: transform 0.3s ease;
    }

    .house-card-link:hover .view-house-btn i {
        transform: translateX(3px);
    }

    /* Son Eklenen Kurslar Bölümü */
    .recent-courses-section {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        padding: 4rem 0;
        border-top: 1px solid #e5e7eb;
        position: relative;
    }

    .recent-courses-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, #00352b, transparent);
    }

    .section-title {
        font-size: 2.25rem;
        font-weight: 800;
        color: #00352b;
        margin: 0 0 3rem 0;
        text-align: center;
        position: relative;
        letter-spacing: -0.025em;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: -0.75rem;
        left: 50%;
        transform: translateX(-50%);
        width: 5rem;
        height: 4px;
        background: linear-gradient(90deg, #00352b, #20846c, #e6a23c);
        border-radius: 2px;
    }

    .courses-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 2rem;
    }

    .course-card {
        background-color: white;
        border: 1px solid rgba(0, 53, 43, 0.08);
        border-radius: 1rem;
        padding: 2rem;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .course-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #00352b, #20846c, #e6a23c);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .course-card:hover {
        border-color: rgba(0, 53, 43, 0.2);
        box-shadow: 0 12px 35px rgba(0, 53, 43, 0.12);
        transform: translateY(-4px);
    }

    .course-card:hover::before {
        transform: scaleX(1);
    }

    .course-card-header {
        display: flex;
        justify-content: between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .course-house-badge {
        background: linear-gradient(135deg, #e6f3ff 0%, #cce7ff 100%);
        color: #0369a1;
        padding: 0.375rem 1rem;
        border-radius: 9999px;
        font-size: 0.8rem;
        font-weight: 600;
        margin-left: auto;
        border: 1px solid rgba(3, 105, 161, 0.1);
        box-shadow: 0 2px 4px rgba(3, 105, 161, 0.1);
    }

    .course-card-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #00352b;
        margin: 0 0 0.75rem 0;
        flex: 1;
        line-height: 1.3;
        letter-spacing: -0.025em;
    }

    .course-card-dates {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #6b7280;
        font-size: 0.875rem;
        margin-bottom: 0.75rem;
    }

    .course-card-instructor {
        color: #374151;
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }

    .course-card-price {
        font-weight: 600;
        color: #00352b;
        font-size: 1rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .category-title {
            font-size: 1.875rem;
        }
        
        .courses-grid {
            grid-template-columns: 1fr;
        }
        
        .house-card-stats {
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .house-card-content {
            padding: 1rem;
        }
    }

    /* Boş durum */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #6b7280;
    }

    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        color: #d1d5db;
    }

    .empty-state h3 {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #374151;
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
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-10 relative z-10">
        <!-- Breadcrumb -->
        <nav class="mb-4">
            <div class="flex items-center space-x-2 text-white/80 text-sm">
                <a href="{{ route('front.home') }}" class="hover:text-white transition-colors">
                    <i class="fas fa-home"></i>
                    Anasayfa
                </a>
                <span>/</span>
                <span class="text-white">Çankaya Evleri</span>
            </div>
        </nav>
        
        <div class="flex flex-col md:flex-row items-center justify-between">
            <!-- Sol taraf - başlık ve açıklama -->
            <div class="w-full md:w-1/2 mb-6 md:mb-0 md:pr-8">
                <div class="inline-block bg-white/10 backdrop-blur-sm px-4 py-1 rounded-full text-white/90 text-sm mb-3 border border-white/10">
                    <span class="material-icons text-xs align-middle mr-1">home</span>
                    <span>Eğitim Merkezleri</span>
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">
                    <span class="text-[#e6a23c]">Çankaya</span> Evleri
                </h1>
                <p class="text-white/80 text-lg mb-0">
                    Çankaya Belediyesi'nin farklı lokasyonlardaki eğitim merkezlerinde düzenlenen 
                    kurslar ve etkinlikler hakkında bilgi alın.
                </p>
            </div>
            
            <!-- Sağ taraf - arama kutusu -->
            <div class="w-full md:w-1/2 md:pl-8">
                <div class="bg-white/10 backdrop-blur-md rounded-xl p-4 border border-white/20 shadow-xl">
                    <h3 class="text-lg text-white font-medium mb-3">Çankaya Evi Ara</h3>
                    
                    <form action="{{ route('cankaya-houses.index') }}" method="GET">
                        <div class="mb-3">
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-white/60">
                                    <span class="material-icons">search</span>
                                </span>
                                <input 
                                    type="text" 
                                    name="search" 
                                    placeholder="Çankaya Evi adı yazın..." 
                                    class="w-full bg-white/10 border border-white/20 text-white placeholder-white/60 pl-10 pr-4 py-3 rounded-xl focus:ring-2 focus:ring-white/30 outline-none"
                                    value="{{ request()->get('search') }}"
                                >
                            </div>
                        </div>
                        <button type="submit" class="w-full bg-white hover:bg-gray-100 text-[#00352b] font-semibold py-3 px-5 rounded-xl transition-all duration-300 flex items-center justify-center">
                            <span class="material-icons mr-2">search</span>
                            Ara
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Ana İçerik -->
<section class="houses-portal">
    <div class="container">
        @if($cankayaHouses->count() > 0)
            <!-- Arama Sonuçları Bilgisi -->
            @if(request()->get('search'))
                <div class="mb-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-gray-800">
                            "{{ request()->get('search') }}" için Arama Sonuçları
                        </h2>
                        <span class="text-sm text-gray-600 bg-white px-3 py-1 rounded-full border border-gray-200">
                            {{ $cankayaHouses->count() }} ev bulundu
                        </span>
                    </div>
                </div>
            @endif
            
            <!-- Çankaya Evleri Grid -->
            <div class="houses-grid">
                @foreach($cankayaHouses as $house)
                <a href="{{ route('cankaya-houses.show', $house) }}" class="house-card-link">
                    <div class="house-card">
                        <div class="house-card-image-container">
                            @if($house->first_image)
                                <img src="{{ $house->first_image }}" 
                                     alt="{{ $house->name }}" 
                                     class="house-card-image">
                            @else
                                <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                                    <i class="fas fa-home fa-3x"></i>
                                </div>
                            @endif
                        </div>
                        
                        <div class="house-card-content">
                            <h3 class="house-card-title">{{ $house->name }}</h3>
                            
                            <div class="mt-auto">
                                <span class="view-house-btn w-100 text-center">
                                    <i class="fas fa-eye"></i>
                                    İncele
                                    <i class="fas fa-arrow-right"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-home"></i>
                <h3>Henüz Çankaya Evi Eklenmemiş</h3>
                <p>Şu anda görüntülenecek Çankaya Evi bulunmamaktadır.</p>
            </div>
        @endif
    </div>
</section>

<!-- Son Eklenen Kurslar -->
@if($recentCourses->count() > 0)
<section class="recent-courses-section">
    <div class="container">
        <h2 class="section-title">Son Eklenen Kurslar</h2>
        
        <div class="courses-grid">
            @foreach($recentCourses as $course)
            <div class="course-card">
                <div class="course-card-header">
                    <h4 class="course-card-title">{{ $course->name }}</h4>
                    <span class="course-house-badge">{{ $course->cankayaHouse->name }}</span>
                </div>
                
                <div class="course-card-dates">
                    <i class="fas fa-calendar-alt"></i>
                    <span>{{ $course->start_date->format('d.m.Y') }} - {{ $course->end_date->format('d.m.Y') }}</span>
                </div>
                
                @if($course->instructor)
                    <div class="course-card-instructor">
                        <i class="fas fa-user-tie"></i>
                        Eğitmen: {{ $course->instructor }}
                    </div>
                @endif
                
                <div class="course-card-price">
                    @if($course->price)
                        {{ number_format($course->price, 0, ',', '.') }} ₺
                    @else
                        Ücretsiz
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection 