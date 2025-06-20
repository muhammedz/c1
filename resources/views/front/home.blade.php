@extends('layouts.front')

@section('content')
    <div class="relative z-0">
        @include('front.sections.slider')
    </div>
    <div class="w-full relative z-50 md:-mt-16 -mt-0">
        <div class="container max-w-[1235px] mx-auto md:px-4 px-0">
            <div class="flex items-center justify-center">
                @include('front.sections.quickmenu')
            </div>
        </div>
    </div>
    @include('front.sections.profile-info')
    @include('front.sections.news')
    @include('front.sections.featured-services')
    @include('front.sections.mobile-app')
    @include('front.sections.events-timeline')
    @include('front.sections.logo-and-plans')
    @include('front.sections.projects')
    <!-- Ana sayfa içeriği buraya gelecek -->
@endsection

@section('before_styles')
    <!-- Swiper CSS layout'ta yükleniyor - çakışmayı önlemek için kaldırıldı -->
@endsection

@section('after_styles')
    <!-- Özel CSS dosyaları buraya -->
@endsection

@section('before_styles')
    <!-- Swiper CSS layout'ta yükleniyor - çakışmayı önlemek için kaldırıldı -->
@endsection

@section('after_scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Custom News Slider - Swiper'a alternatif
            const newsSlider = document.querySelector('.newsMainSwiper .swiper-wrapper');
            const newsCards = document.querySelectorAll('.news-card');
            const prevBtn = document.querySelector('.news-prev-btn');
            const nextBtn = document.querySelector('.news-next-btn');
            
            if (!newsSlider || !newsCards.length) return;
            
            const slides = newsSlider.querySelectorAll('.swiper-slide');
            const totalSlides = slides.length;
            let currentIndex = 0;
            let autoSlideInterval;
            
            // Slider'ı güncelle
            function updateSlider() {
                // Fade efekti için tüm slide'ları gizle
                slides.forEach((slide, index) => {
                    slide.style.opacity = index === currentIndex ? '1' : '0';
                    slide.style.transition = 'opacity 0.5s ease-in-out';
                    slide.style.position = 'absolute';
                    slide.style.top = '0';
                    slide.style.left = '0';
                    slide.style.width = '100%';
                    slide.style.height = '100%';
                });
                
                // Aktif kartı güncelle
                updateActiveCard(currentIndex);
                
                // Buton durumlarını güncelle
                if (prevBtn) {
                    prevBtn.disabled = currentIndex === 0;
                    prevBtn.style.opacity = currentIndex === 0 ? '0.5' : '1';
                }
                if (nextBtn) {
                    nextBtn.disabled = currentIndex >= totalSlides - 1;
                    nextBtn.style.opacity = currentIndex >= totalSlides - 1 ? '0.5' : '1';
                }
            }
            
            // Aktif kart styling fonksiyonu
            function updateActiveCard(activeIndex) {
                newsCards.forEach((card, index) => {
                    const cardLink = card.querySelector('a') || card.querySelector('div');
                    const title = cardLink.querySelector('h3');
                    if (index === activeIndex) {
                        cardLink.classList.remove('bg-white', 'hover:bg-gray-50');
                        cardLink.classList.add('bg-[#004d2e]', 'hover:brightness-90');
                        title.classList.remove('text-gray-800');
                        title.classList.add('text-white');
                        card.classList.add('active');
                    } else {
                        cardLink.classList.remove('bg-[#004d2e]', 'hover:brightness-90');
                        cardLink.classList.add('bg-white', 'hover:bg-gray-50');
                        title.classList.remove('text-white');
                        title.classList.add('text-gray-800');
                        card.classList.remove('active');
                    }
                });
            }
            
            // Sonraki slide
            function nextSlide() {
                if (currentIndex < totalSlides - 1) {
                    currentIndex++;
                    updateSlider();
                }
            }
            
            // Önceki slide
            function prevSlide() {
                if (currentIndex > 0) {
                    currentIndex--;
                    updateSlider();
                }
            }
            
            // Belirli slide'a git
            function goToSlide(index) {
                if (index >= 0 && index < totalSlides) {
                    currentIndex = index;
                    updateSlider();
                }
            }
            
            // Otomatik kaydırma
            function startAutoSlide() {
                autoSlideInterval = setInterval(() => {
                    if (currentIndex >= totalSlides - 1) {
                        currentIndex = 0;
                    } else {
                        currentIndex++;
                    }
                    updateSlider();
                }, 5000);
            }
            
            function stopAutoSlide() {
                clearInterval(autoSlideInterval);
            }
            
            // Event listeners
            if (nextBtn) {
                nextBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    nextSlide();
                    stopAutoSlide();
                    setTimeout(startAutoSlide, 10000); // 10 saniye sonra otomatik kaydırmayı tekrar başlat
                });
            }
            
            if (prevBtn) {
                prevBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    prevSlide();
                    stopAutoSlide();
                    setTimeout(startAutoSlide, 10000); // 10 saniye sonra otomatik kaydırmayı tekrar başlat
                });
            }
            
            // Haber kartlarına tıklama olayı
            newsCards.forEach((card, index) => {
                card.addEventListener('click', function(e) {
                    if (!e.target.closest('a')) { // Eğer link değilse
                        e.preventDefault();
                        goToSlide(index);
                        stopAutoSlide();
                        setTimeout(startAutoSlide, 10000); // 10 saniye sonra otomatik kaydırmayı tekrar başlat
                    }
                });
            });
            
            // Mouse hover'da otomatik kaydırmayı durdur
            const newsSection = document.getElementById('news-section');
            if (newsSection) {
                newsSection.addEventListener('mouseenter', stopAutoSlide);
                newsSection.addEventListener('mouseleave', startAutoSlide);
            }
            
            // İlk yükleme
            updateSlider();
            startAutoSlide();
        });
    </script>
@endsection 