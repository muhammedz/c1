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
        
        /* Services Grid Base Styles */
        .services-grid {
            display: grid;
            gap: 1rem;
            grid-template-columns: repeat(2, 1fr); /* Varsayılan: mobil için 2 sütun */
        }
        
        /* Tablet için */
        @media (min-width: 641px) {
            .services-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1.5rem;
            }
        }
        
        /* Orta ekranlar için */
        @media (min-width: 769px) {
            .services-grid {
                grid-template-columns: repeat(4, 1fr);
                gap: 1.5rem;
            }
        }
        
        /* Büyük ekranlar için */
        @media (min-width: 1025px) {
            .services-grid {
                grid-template-columns: repeat(5, 1fr);
                gap: 1.5rem;
            }
        }
        
        /* Mobilde sadece ilk 4 elementi göster */
        @media (max-width: 640px) {
            .service-item:nth-child(n+5) {
                display: none;
            }
        }
        
        /* Tablet için sadece ilk 4 elementi göster */
        @media (min-width: 641px) and (max-width: 768px) {
            .service-item:nth-child(n+5) {
                display: none;
            }
        }
    </style>

    <!-- Services Grid Container -->
    <div class="services-grid">
        @forelse($featuredServices as $service)
        <!-- Hizmet {{ $loop->iteration }} -->
        <div class="service-item">
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
        <div class="service-item">
            <div class="featured-service-card">
                <div class="featured-service-icon">
                    <i class="fas fa-home"></i>
                </div>
                <h3 class="featured-service-title">Örnek Hizmet 1</h3>
            </div>
        </div>
        
        <div class="service-item">
            <div class="featured-service-card">
                <div class="featured-service-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h3 class="featured-service-title">Örnek Hizmet 2</h3>
            </div>
        </div>
        
        <div class="service-item">
            <div class="featured-service-card">
                <div class="featured-service-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3 class="featured-service-title">Örnek Hizmet 3</h3>
            </div>
        </div>
        
        <div class="service-item">
            <div class="featured-service-card">
                <div class="featured-service-icon">
                    <i class="fas fa-cog"></i>
                </div>
                <h3 class="featured-service-title">Örnek Hizmet 4</h3>
            </div>
        </div>
        
        <div class="service-item">
            <div class="featured-service-card">
                <div class="featured-service-icon">
                    <i class="fas fa-phone"></i>
                </div>
                <h3 class="featured-service-title">Örnek Hizmet 5</h3>
            </div>
        </div>
        
        <div class="service-item">
            <div class="featured-service-card">
                <div class="featured-service-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <h3 class="featured-service-title">Örnek Hizmet 6</h3>
            </div>
        </div>
        @endforelse
    </div>
</section>
@endif

