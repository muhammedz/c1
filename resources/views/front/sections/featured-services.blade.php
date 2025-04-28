<!-- #### Featured Services Section -->
@if(isset($featuredServiceSettings) && $featuredServiceSettings->is_active)
<section id="featured-services-section" class="featured-services-section container max-w-7xl mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-8">{{ $featuredServiceSettings->title }}</h2>

    <style>
        .featured-service-card {
            height: 180px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            background-color: white;
            border-radius: 24px;
            box-shadow: 0 2px 20px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }
        
        .featured-service-card:hover {
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
            transform: translateY(-5px);
        }
        
        .featured-service-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 80px;
            height: 80px;
            margin-bottom: 0.5rem;
        }
        
        .featured-service-icon i {
            font-size: 36px !important;
            color: #004d2e;
        }
        
        .featured-service-icon svg {
            width: 100%;
            height: 100%;
            max-width: 64px;
            max-height: 64px;
            color: #004d2e;
        }
        
        .featured-service-title {
            font-size: 1rem;
            font-weight: 600;
            color: #333;
            text-align: center;
            margin-top: 0.25rem;
            font-family: 'Poppins', sans-serif;
        }
    </style>

    <!-- Swiper Container -->
    <div class="swiper servicesSwiper">
        <div class="swiper-wrapper">
            @forelse($featuredServices as $service)
            <!-- Hizmet {{ $loop->iteration }} -->
            <div class="swiper-slide">
                <div class="featured-service-card">
                    <div class="featured-service-icon">
                        {!! $service->icon_html !!}
                    </div>
                    @if($service->url)
                        <a href="{{ $service->url }}" class="featured-service-title">{{ $service->title }}</a>
                    @else
                        <h3 class="featured-service-title">{{ $service->title }}</h3>
                    @endif
                </div>
            </div>
            @empty
            <!-- Örnek Hizmetler (Veri yoksa gösterilir) -->
            <div class="swiper-slide">
                <div class="featured-service-card">
                    <div class="featured-service-icon">
                        <i class="fas fa-home"></i>
                    </div>
                    <h3 class="featured-service-title">Örnek Hizmet 1</h3>
                </div>
            </div>
            
            <div class="swiper-slide">
                <div class="featured-service-card">
                    <div class="featured-service-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h3 class="featured-service-title">Örnek Hizmet 2</h3>
                </div>
            </div>
            
            <div class="swiper-slide">
                <div class="featured-service-card">
                    <div class="featured-service-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="featured-service-title">Örnek Hizmet 3</h3>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>
@endif

<!-- Services Slider Initialize -->
@if(isset($featuredServiceSettings) && $featuredServiceSettings->is_active)
<script>
document.addEventListener('DOMContentLoaded', function() {
    new Swiper('.servicesSwiper', {
        slidesPerView: {{ $featuredServiceSettings->swiper_items_per_view ?? 4 }},
        spaceBetween: 20,
        @if($featuredServiceSettings->swiper_autoplay)
        autoplay: {
            delay: {{ $featuredServiceSettings->swiper_autoplay }},
            disableOnInteraction: false,
        },
        @endif
        breakpoints: {
            320: {
                slidesPerView: 1,
                spaceBetween: 15
            },
            640: {
                slidesPerView: 2,
                spaceBetween: 15
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 20
            },
            1024: {
                slidesPerView: {{ $featuredServiceSettings->swiper_items_per_view ?? 4 }},
                spaceBetween: 20
            }
        }
    });
});
</script>
@else
<script>
document.addEventListener('DOMContentLoaded', function() {
    new Swiper('.servicesSwiper', {
        slidesPerView: 4,
        spaceBetween: 20,
        breakpoints: {
            320: {
                slidesPerView: 1,
                spaceBetween: 15
            },
            640: {
                slidesPerView: 2,
                spaceBetween: 15
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 20
            },
            1024: {
                slidesPerView: 4,
                spaceBetween: 20
            }
        }
    });
});
</script>
@endif