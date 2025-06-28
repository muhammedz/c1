<!-- #### Hero Slider Section -->
<section id="hero-slider-section" class="hero-slider-section relative w-full overflow-hidden" style="margin-top: -100px;">
    <h2 class="sr-only">Ana Sayfa Slider</h2>
    <!-- Mobil Görsel -->
    <div class="block md:hidden w-full bg-white" style="margin-top: 0;">
        <div class="w-full">
            <a href="/baskan" class="block w-full">
                <img src="https://cankaya.bel.tr/uploads/images/cankayamobil_1749978637_aJd14YaH.jpg" 
                     alt="Çankaya Belediyesi Mobil" 
                     class="w-full aspect-square object-cover cursor-pointer">
            </a>
        </div>
    </div>
    
    <!-- Desktop Slider -->
    <div class="swiper heroSwiper h-screen hidden md:block">
        <div class="swiper-wrapper">
            @php
            // Aktif sliderları sıralı bir şekilde getir
            $sliders = \App\Models\Slider::where('is_active', true)->orderBy('order')->get();
            
            // URL'deki yinelenen /storage/ yolunu düzelten yardımcı fonksiyon
            // Dosya içinde tanımlamak yerine global fonksiyon olarak kullanıyoruz
            if (!function_exists('fixStoragePathSlider')) {
                function fixStoragePathSlider($url) {
                    if (empty($url)) {
                        return $url;
                    }
                    
                    // Yinelenen /storage/ yolunu düzelt
                    if (strpos($url, '/storage//storage/') !== false) {
                        return str_replace('/storage//storage/', '/storage/', $url);
                    }
                    
                    if (strpos($url, '/storage/storage/') !== false) {
                        return str_replace('/storage/storage/', '/storage/', $url);
                    }
                    
                    return $url;
                }
            }
            @endphp
            
            @forelse ($sliders as $slider)
            <!-- Slider {{ $loop->iteration }} -->
            <div class="swiper-slide relative @if($slider->direct_link) cursor-pointer @endif" @if($slider->direct_link) onclick="window.location.href='{{ $slider->direct_link }}'" @endif>
                
                <!-- Slide image - Placeholder görsel kullan -->
                <div class="w-full h-full bg-gray-800">
                    @if(!empty($slider->filemanagersystem_image))
                        @php
                            // Önce görsel URL'sini oluşturalım
                            $imageUrl = $slider->filemanagersystemImageUrl;

                            // WebP kontrolü için dosya yolunu alalım
                            $imagePath = $slider->filemanagersystem_image;
                            $imagePathInfo = pathinfo($imagePath);
                            $extension = strtolower($imagePathInfo['extension'] ?? '');
                            $hasWebp = false;
                            $webpUrl = null;

                            // Eğer görsel bir resim dosyasıysa ve webp desteği varsa
                            if (in_array($extension, ['jpg', 'jpeg', 'png']) && 
                                (strpos($imagePath, '/images/') !== false || 
                                strpos($imageUrl, '/images/') !== false)) {
                                // WebP dosya yolunu oluştur
                                $webpPath = str_replace('.' . $extension, '.webp', $imagePath);
                                $webpUrl = str_replace('.' . $extension, '.webp', $imageUrl);
                                
                                // WebP dosyasının varlığını kontrol et
                                if (file_exists(public_path($webpPath)) || 
                                    file_exists(public_path('uploads/' . $webpPath))) {
                                    $hasWebp = true;
                                }
                            }
                        @endphp

                        @if($hasWebp)
                            <picture>
                                <source srcset="{{ $webpUrl }}" type="image/webp">
                                <source srcset="{{ $imageUrl }}" type="image/{{ $extension }}">
                                <img src="{{ $imageUrl }}" alt="{{ $slider->filemanagersystem_image_alt ?? $slider->title }}" 
                                    title="{{ $slider->filemanagersystem_image_title ?? $slider->title }}" 
                                    class="w-full h-full object-cover">
                            </picture>
                        @else
                            <img src="{{ $imageUrl }}" alt="{{ $slider->filemanagersystem_image_alt ?? $slider->title }}" 
                                title="{{ $slider->filemanagersystem_image_title ?? $slider->title }}" 
                                class="w-full h-full object-cover">
                        @endif
                    @else
                        <div class="flex items-center justify-center h-full">
                            <div class="text-gray-500 text-xl">
                                <i class="fas fa-image fa-3x"></i>
                                <p class="mt-2">Görsel eklenemedi</p>
                            </div>
                        </div>
                    @endif
                </div>
                
                <!-- Content -->
                <div class="absolute inset-x-0 bottom-0 z-20 text-white p-8 md:p-16 lg:p-24">
                    <div class="container mx-auto">
                        <div class="max-w-3xl slide-content opacity-0 translate-y-8">
                            @if($slider->title)
                            <h2 class="text-3xl md:text-5xl lg:text-6xl font-bold mb-3 text-white leading-tight">
                                {{ $slider->title }}
                            </h2>
                            @endif
                            
                            @if($slider->subtitle)
                            <p class="text-xl md:text-2xl text-white/90 mb-8 max-w-2xl">
                                {{ $slider->subtitle }}
                            </p>
                            @endif
                            
                            @if($slider->button_text && $slider->button_url)
                            <a href="{{ $slider->button_url }}" 
                               class="group inline-flex items-center px-8 py-4 bg-primary hover:bg-primary-dark text-white font-medium rounded-lg transition-all duration-300 shadow-lg hover:shadow-primary/30 transform hover:-translate-y-1">
                                <span>{{ $slider->button_text }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <!-- Fallback slider eğer veritabanında slider yoksa -->
            <div class="swiper-slide relative">
                
                <!-- Slide image - Placeholder görsel kullan -->
                <div class="w-full h-full bg-gray-800">
                    <div class="flex items-center justify-center h-full">
                        <div class="text-gray-500 text-xl">
                            <i class="fas fa-image fa-3x"></i>
                            <p class="mt-2">Görsel eklenemedi</p>
                        </div>
                    </div>
                </div>
                
                <!-- Content -->
                <div class="absolute inset-x-0 bottom-0 z-20 text-white p-8 md:p-16 lg:p-24">
                    <div class="container mx-auto">
                        <div class="max-w-3xl slide-content opacity-0 translate-y-8">
                            <h2 class="text-3xl md:text-5xl lg:text-6xl font-bold mb-3 text-white leading-tight">
                                Sitemize Hoş Geldiniz
                            </h2>
                            <p class="text-xl md:text-2xl text-white/90 mb-8 max-w-2xl">
                                Daha fazla bilgi için bizimle iletişime geçin
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endforelse
        </div>
        
        <!-- Pagination dots -->
        <div class="swiper-pagination !bottom-8"></div>
        
        <!-- Navigation buttons -->
        <div class="swiper-button-next !right-8 !text-white opacity-0 hover:opacity-100 transition-opacity duration-300 after:!text-2xl md:after:!text-3xl !w-12 !h-12 md:!w-14 md:!h-14 hover:!bg-primary/80 !bg-black/20 backdrop-blur-sm rounded-full"></div>
        <div class="swiper-button-prev !left-8 !text-white opacity-0 hover:opacity-100 transition-opacity duration-300 after:!text-2xl md:after:!text-3xl !w-12 !h-12 md:!w-14 md:!h-14 hover:!bg-primary/80 !bg-black/20 backdrop-blur-sm rounded-full"></div>
    </div>
    
    <!-- Slider Inicializasyon Kodu - Sorun: Sayfa içinde bu kod var, ayrıca layout dosyasında ve main.js dosyasında benzer kodlar var.
         Bu durum slider'ın birden fazla kez initialize edilmesine ve doğru çalışmamasına sebep olabilir. -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sayfa yüklendiğinde scroll pozisyonunu en üste getir
            window.scrollTo(0, 0);
            
            // Sadece desktop görünümde slider'ı başlat
            if (window.innerWidth >= 768) {
                // Eğer zaten heroSwiper initialize edilmişse temizle (çakışmayı önlemek için)
                if (window.heroSwiperInstance) {
                    window.heroSwiperInstance.destroy(true, true);
                }
                
                // Slide sayısını kontrol et
                const slideCount = document.querySelectorAll('.heroSwiper .swiper-slide').length;
                const swiperContainer = document.querySelector('.heroSwiper');
                
                // Tek slide durumunda CSS class ekle
                if (slideCount <= 1) {
                    swiperContainer.classList.add('single-slide');
                }
                
                // Yeni swiper oluştur ve global değişkene kaydet
                window.heroSwiperInstance = new Swiper('.heroSwiper', {
                loop: slideCount > 1, // Sadece 1'den fazla slide varsa loop aktif
                effect: 'fade', // Fade efekti kullanıyoruz
                fadeEffect: {
                  crossFade: true // Geçişlerin daha pürüzsüz olması için
                },
                speed: 1500, // Geçiş hızı
                autoplay: slideCount > 1 ? {
                    delay: 6000, // 6 saniyede bir geçiş
                    disableOnInteraction: false, // Kullanıcı etkileşiminde bile autoplay devam etsin
                } : false, // Tek slide varsa autoplay kapalı
                lazy: {
                  loadPrevNext: true, // Önceki ve sonraki slider'ları da yükle
                },
                pagination: slideCount > 1 ? {
                    el: '.swiper-pagination',
                    clickable: true, // Tıklanabilir pagination noktaları
                } : false, // Tek slide varsa pagination gizli
                navigation: slideCount > 1 ? {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                } : false, // Tek slide varsa navigation gizli
                on: {
                    init: function() {
                        // İlk slide'ın içeriğini animasyonla göster
                        let activeSlide = document.querySelector('.swiper-slide-active');
                        if (activeSlide) {
                            let content = activeSlide.querySelector('.slide-content');
                            if (content) {
                                setTimeout(function() {
                                    content.classList.add('animate-slide-up');
                                    content.classList.remove('opacity-0', 'translate-y-8');
                                }, 500);
                            }
                        }
                    },
                    slideChange: function() {
                        // Görüntülenen slide'ın içeriğini animasyonla göster
                        let slides = document.querySelectorAll('.swiper-slide');
                        slides.forEach(slide => {
                            let content = slide.querySelector('.slide-content');
                            if (content) {
                                content.classList.remove('animate-slide-up');
                                content.classList.add('opacity-0', 'translate-y-8');
                            }
                        });
                        
                        setTimeout(function() {
                            let activeSlide = document.querySelector('.swiper-slide-active');
                            if (activeSlide) {
                                let content = activeSlide.querySelector('.slide-content');
                                if (content) {
                                    content.classList.add('animate-slide-up');
                                    content.classList.remove('opacity-0', 'translate-y-8');
                                }
                            }
                        }, 500);
                    }
                }
            });
            
            // Resim yakınlaştırma animasyonu
            window.heroSwiperInstance.on('slideChangeTransitionStart', function() {
                let activeSlide = document.querySelector('.swiper-slide-active img');
                let nextSlide = document.querySelector('.swiper-slide-next img');
                
                if (activeSlide) activeSlide.style.transform = 'scale(1.05)';
                
                setTimeout(function() {
                    if (activeSlide) activeSlide.style.transform = 'scale(1)';
                    if (nextSlide) nextSlide.style.transform = 'scale(1.05)';
                }, 6000); // Autoplay süresine eşit
            });
            }
        });
    </script>
    
    <style>
        .animate-slide-up {
            animation: slide-up 1s ease forwards;
        }
        
        @keyframes slide-up {
            from { opacity: 0; transform: translateY(2rem); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Slider tam ekran sığması için ek stiller */
        .hero-slider-section {
            height: 100vh;
            max-height: 100vh;
            min-height: 500px;
            overflow: hidden;
            position: relative;
            margin-top: -100px; /* Header yüksekliği kadar negatif margin */
        }
        
        .hero-slider-section .swiper {
            height: 100% !important;
            max-height: 100% !important;
        }
        
        @media (max-height: 800px) {
            .hero-slider-section {
                height: 100vh;
                min-height: 500px;
            }
        }
        
        /* Mobil görsel için özel stiller */
        @media (max-width: 767px) {
            .hero-slider-section {
                height: auto !important;
                min-height: auto !important;
                margin-top: 0 !important;
                padding-top: 0 !important;
            }
            
            /* Mobil resim için kare format */
            .hero-slider-section .aspect-square {
                aspect-ratio: 1 / 1;
                width: 100vw !important;
                max-width: none !important;
            }
            
            /* Mobil layout düzenlemesi - padding'leri kaldır */
            .hero-slider-section > div:first-child {
                margin-top: 0 !important;
                padding: 0 !important;
            }
            
            /* Mobil container'ı tam genişlik yap */
            .hero-slider-section > div:first-child > div {
                padding: 0 !important;
                margin: 0 !important;
            }
        }
        
        /* Quick menu geçişi için ek stil */
        #hero-slider-section::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 20px;
            background: linear-gradient(to bottom, transparent, rgba(0,0,0,0.1));
            z-index: 5;
        }
        
        /* Tek slide durumunda navigation ve pagination gizle */
        .heroSwiper.single-slide .swiper-pagination,
        .heroSwiper.single-slide .swiper-button-next,
        .heroSwiper.single-slide .swiper-button-prev {
            display: none !important;
        }
    </style>
</section> 