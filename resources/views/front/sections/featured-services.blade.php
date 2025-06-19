<!-- #### Featured Services Section -->
@if(isset($featuredServiceSettings) && $featuredServiceSettings->is_active)
<section id="featured-services-section" class="featured-services-section container max-w-7xl mx-auto px-4 py-8">
    <!-- Başlık ve Navigation -->
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-2xl font-bold text-gray-800">{{ $featuredServiceSettings->title }}</h2>
        
        <!-- Desktop Navigation Buttons -->
        <div class="hidden md:flex items-center space-x-2">
            <button id="services-prev-btn" class="w-10 h-10 rounded-full bg-white border-2 border-gray-200 flex items-center justify-center hover:border-[#004d2e] hover:text-[#004d2e] transition-all duration-300 shadow-sm hover:shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <button id="services-next-btn" class="w-10 h-10 rounded-full bg-white border-2 border-gray-200 flex items-center justify-center hover:border-[#004d2e] hover:text-[#004d2e] transition-all duration-300 shadow-sm hover:shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>
    </div>

    <style>
        .featured-service-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            background-color: white;
            border-radius: 16px;
            box-shadow: 0 2px 20px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            text-decoration: none !important;
            color: inherit !important;
            height: 140px;
        }
        
        .featured-service-card:hover {
            box-shadow: 0 4px 25px rgba(0,0,0,0.1);
            transform: translateY(-3px);
            text-decoration: none !important;
            color: inherit !important;
        }
        
        .featured-service-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            margin-bottom: 0.5rem;
        }
        
        .featured-service-icon i {
            font-size: 24px !important;
            color: #004d2e;
        }
        
        .featured-service-icon svg {
            width: 100%;
            height: 100%;
            max-width: 40px;
            max-height: 40px;
            color: #004d2e;
        }
        
        .featured-service-title {
            font-size: 0.75rem;
            font-weight: 600;
            color: #333;
            text-align: center;
            margin-top: 0.25rem;
            font-family: 'Poppins', sans-serif;
            line-height: 1.2;
        }
        
        /* Mobil: 3x3 Statik Grid */
        @media (max-width: 767px) {
            .mobile-services-grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 8px;
            }
            
            .mobile-services-grid .service-item:nth-child(n+10) {
                display: none;
            }
            
            .desktop-services-slider {
                display: none;
            }
            
            .featured-service-card {
                height: 120px;
                padding: 0.75rem;
                border-radius: 12px;
            }
            
            .featured-service-icon {
                width: 40px;
                height: 40px;
            }
            
            .featured-service-icon i {
                font-size: 20px !important;
            }
            
            .featured-service-title {
                font-size: 0.7rem;
            }
        }
        
        /* Desktop: Kaydırılabilir Grid */
        @media (min-width: 768px) {
            .mobile-services-grid {
                display: none;
            }
            
            .desktop-services-slider {
                display: block;
                overflow: hidden;
                position: relative;
            }
            
            .services-track {
                display: flex;
                transition: transform 0.5s ease;
                gap: 20px;
            }
            
            .service-slide {
                flex: 0 0 auto;
                width: calc(20% - 16px); /* 5 items per view */
            }
            
            .featured-service-card {
                height: 160px;
                padding: 1.25rem;
                border-radius: 20px;
            }
            
            .featured-service-icon {
                width: 60px;
                height: 60px;
            }
            
            .featured-service-icon i {
                font-size: 28px !important;
            }
            
            .featured-service-title {
                font-size: 0.9rem;
            }
        }
        
        @media (min-width: 1024px) {
            .featured-service-card {
                height: 180px;
                padding: 1.5rem;
                border-radius: 24px;
            }
            
            .featured-service-icon {
                width: 70px;
                height: 70px;
            }
            
            .featured-service-icon i {
                font-size: 32px !important;
            }
            
            .featured-service-title {
                font-size: 1rem;
            }
        }
        
        /* Tablet için 4'lü */
        @media (min-width: 768px) and (max-width: 1023px) {
            .service-slide {
                width: calc(25% - 15px); /* 4 items per view */
            }
        }
        
        /* Mobil tablet için 3'lü */
        @media (min-width: 640px) and (max-width: 767px) {
            .service-slide {
                width: calc(33.333% - 14px); /* 3 items per view */
            }
        }
    </style>

    <!-- Mobil: 3x3 Statik Grid -->
    <div class="mobile-services-grid md:hidden">
        @forelse($featuredServices as $service)
        <div class="service-item">
            @if($service->url)
                <a href="{{ $service->url }}" class="featured-service-card">
                    <div class="featured-service-icon">
                        {!! $service->icon_html !!}
                    </div>
                    <h3 class="featured-service-title">{{ $service->title }}</h3>
                </a>
            @else
                <div class="featured-service-card">
                    <div class="featured-service-icon">
                        {!! $service->icon_html !!}
                    </div>
                    <h3 class="featured-service-title">{{ $service->title }}</h3>
                </div>
            @endif
        </div>
        @empty
        <!-- Örnek Hizmetler (Mobil) -->
        <div class="service-item">
            <div class="featured-service-card">
                <div class="featured-service-icon">
                    <i class="fas fa-home"></i>
                </div>
                <h3 class="featured-service-title">Emlak Hizmetleri</h3>
            </div>
        </div>
        <div class="service-item">
            <div class="featured-service-card">
                <div class="featured-service-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h3 class="featured-service-title">Eğitim Hizmetleri</h3>
            </div>
        </div>
        <div class="service-item">
            <div class="featured-service-card">
                <div class="featured-service-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3 class="featured-service-title">Sosyal Hizmetler</h3>
            </div>
        </div>
        <div class="service-item">
            <div class="featured-service-card">
                <div class="featured-service-icon">
                    <i class="fas fa-cog"></i>
                </div>
                <h3 class="featured-service-title">Teknik Hizmetler</h3>
            </div>
        </div>
        <div class="service-item">
            <div class="featured-service-card">
                <div class="featured-service-icon">
                    <i class="fas fa-phone"></i>
                </div>
                <h3 class="featured-service-title">İletişim Hizmetleri</h3>
            </div>
        </div>
        <div class="service-item">
            <div class="featured-service-card">
                <div class="featured-service-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <h3 class="featured-service-title">Posta Hizmetleri</h3>
            </div>
        </div>
        <div class="service-item">
            <div class="featured-service-card">
                <div class="featured-service-icon">
                    <i class="fas fa-car"></i>
                </div>
                <h3 class="featured-service-title">Ulaşım Hizmetleri</h3>
            </div>
        </div>
        <div class="service-item">
            <div class="featured-service-card">
                <div class="featured-service-icon">
                    <i class="fas fa-medkit"></i>
                </div>
                <h3 class="featured-service-title">Sağlık Hizmetleri</h3>
            </div>
        </div>
        <div class="service-item">
            <div class="featured-service-card">
                <div class="featured-service-icon">
                    <i class="fas fa-leaf"></i>
                </div>
                <h3 class="featured-service-title">Çevre Hizmetleri</h3>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Desktop: Kaydırılabilir Slider -->
    <div class="desktop-services-slider hidden md:block">
        <div class="services-track" id="services-track">
            @forelse($featuredServices as $service)
            <div class="service-slide">
                @if($service->url)
                    <a href="{{ $service->url }}" class="featured-service-card">
                        <div class="featured-service-icon">
                            {!! $service->icon_html !!}
                        </div>
                        <h3 class="featured-service-title">{{ $service->title }}</h3>
                    </a>
                @else
                    <div class="featured-service-card">
                        <div class="featured-service-icon">
                            {!! $service->icon_html !!}
                        </div>
                        <h3 class="featured-service-title">{{ $service->title }}</h3>
                    </div>
                @endif
            </div>
            @empty
            <!-- Örnek Hizmetler (Desktop) -->
            <div class="service-slide">
                <div class="featured-service-card">
                    <div class="featured-service-icon">
                        <i class="fas fa-home"></i>
                    </div>
                    <h3 class="featured-service-title">Emlak Hizmetleri</h3>
                </div>
            </div>
            <div class="service-slide">
                <div class="featured-service-card">
                    <div class="featured-service-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h3 class="featured-service-title">Eğitim Hizmetleri</h3>
                </div>
            </div>
            <div class="service-slide">
                <div class="featured-service-card">
                    <div class="featured-service-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="featured-service-title">Sosyal Hizmetler</h3>
                </div>
            </div>
            <div class="service-slide">
                <div class="featured-service-card">
                    <div class="featured-service-icon">
                        <i class="fas fa-cog"></i>
                    </div>
                    <h3 class="featured-service-title">Teknik Hizmetler</h3>
                </div>
            </div>
            <div class="service-slide">
                <div class="featured-service-card">
                    <div class="featured-service-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <h3 class="featured-service-title">İletişim Hizmetleri</h3>
                </div>
            </div>
            <div class="service-slide">
                <div class="featured-service-card">
                    <div class="featured-service-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h3 class="featured-service-title">Posta Hizmetleri</h3>
                </div>
            </div>
            <div class="service-slide">
                <div class="featured-service-card">
                    <div class="featured-service-icon">
                        <i class="fas fa-car"></i>
                    </div>
                    <h3 class="featured-service-title">Ulaşım Hizmetleri</h3>
                </div>
            </div>
            <div class="service-slide">
                <div class="featured-service-card">
                    <div class="featured-service-icon">
                        <i class="fas fa-medkit"></i>
                    </div>
                    <h3 class="featured-service-title">Sağlık Hizmetleri</h3>
                </div>
            </div>
            <div class="service-slide">
                <div class="featured-service-card">
                    <div class="featured-service-icon">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <h3 class="featured-service-title">Çevre Hizmetleri</h3>
                </div>
            </div>
            @endforelse
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const track = document.getElementById('services-track');
            const prevBtn = document.getElementById('services-prev-btn');
            const nextBtn = document.getElementById('services-next-btn');
            
            if (!track || !prevBtn || !nextBtn) return;
            
            const slides = track.querySelectorAll('.service-slide');
            const totalSlides = slides.length;
            
            // Ekran boyutuna göre görünür slide sayısını belirle
            function getVisibleSlides() {
                const width = window.innerWidth;
                if (width >= 1024) return 5; // Desktop: 5 slide
                if (width >= 768) return 4;  // Tablet: 4 slide
                return 3; // Küçük tablet: 3 slide
            }
            
            let currentIndex = 0;
            let visibleSlides = getVisibleSlides();
            let maxIndex = Math.max(0, totalSlides - visibleSlides);
            
            function updateSlider() {
                const slideWidth = 100 / visibleSlides;
                const translateX = -(currentIndex * slideWidth);
                track.style.transform = `translateX(${translateX}%)`;
                
                // Buton durumlarını güncelle
                prevBtn.disabled = currentIndex === 0;
                nextBtn.disabled = currentIndex >= maxIndex;
                
                prevBtn.style.opacity = currentIndex === 0 ? '0.5' : '1';
                nextBtn.style.opacity = currentIndex >= maxIndex ? '0.5' : '1';
            }
            
            function nextSlide() {
                if (currentIndex < maxIndex) {
                    currentIndex++;
                    updateSlider();
                }
            }
            
            function prevSlide() {
                if (currentIndex > 0) {
                    currentIndex--;
                    updateSlider();
                }
            }
            
            // Event listeners
            nextBtn.addEventListener('click', nextSlide);
            prevBtn.addEventListener('click', prevSlide);
            
            // Otomatik kaydırma
            let autoSlideInterval = setInterval(() => {
                if (currentIndex >= maxIndex) {
                    currentIndex = 0;
                } else {
                    currentIndex++;
                }
                updateSlider();
            }, 4000);
            
            // Mouse hover'da otomatik kaydırmayı durdur
            track.addEventListener('mouseenter', () => {
                clearInterval(autoSlideInterval);
            });
            
            track.addEventListener('mouseleave', () => {
                autoSlideInterval = setInterval(() => {
                    if (currentIndex >= maxIndex) {
                        currentIndex = 0;
                    } else {
                        currentIndex++;
                    }
                    updateSlider();
                }, 4000);
            });
            
            // Resize event
            window.addEventListener('resize', () => {
                visibleSlides = getVisibleSlides();
                maxIndex = Math.max(0, totalSlides - visibleSlides);
                if (currentIndex > maxIndex) {
                    currentIndex = maxIndex;
                }
                updateSlider();
            });
            
            // İlk yükleme
            updateSlider();
        });
    </script>
</section>
@endif

