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

@section('before_scripts')
    <!-- Swiper JS layout'ta yükleniyor - çakışmayı önlemek için kaldırıldı -->
    <script>
        // Hero Slider inicializasyonu slider.blade.php dosyasının içinde yapılıyor.
        // Çakışma olmaması için buradan kaldırıldı.

        // Haber Slider Inicializasyonu - Sadece ana slider
        var newsMainSwiper = new Swiper(".newsMainSwiper", {
            loop: false,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            effect: 'fade',
            fadeEffect: {
                crossFade: true
            },
            on: {
                slideChange: function() {
                    updateActiveCard(this.activeIndex);
                }
            }
        });

        // Aktif kart styling fonksiyonu
        function updateActiveCard(activeIndex) {
            const cards = document.querySelectorAll('.news-card');
            cards.forEach((card, index) => {
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

        // İlk yüklemede aktif kartı güncelle
        updateActiveCard(0);

        // Haber kartlarına tıklama olayı ekle
        document.querySelectorAll('.news-card').forEach((card, index) => {
            card.addEventListener('click', function(e) {
                if (!e.target.closest('a')) { // Eğer link değilse
                    e.preventDefault();
                    newsMainSwiper.slideTo(index);
                    updateActiveCard(index);
                }
            });
        });

        // Navigation butonlarına event delegation ile event listener ekle
        document.addEventListener('click', function(e) {
            if (e.target.closest('.news-next-btn')) {
                e.preventDefault();
                e.stopPropagation();
                if (newsMainSwiper) {
                    const currentIndex = newsMainSwiper.activeIndex;
                    const nextIndex = Math.min(currentIndex + 1, newsMainSwiper.slides.length - 1);
                    newsMainSwiper.slideTo(nextIndex);
                    updateActiveCard(nextIndex);
                }
            }
            
            if (e.target.closest('.news-prev-btn')) {
                e.preventDefault();
                e.stopPropagation();
                if (newsMainSwiper) {
                    const currentIndex = newsMainSwiper.activeIndex;
                    const prevIndex = Math.max(currentIndex - 1, 0);
                    newsMainSwiper.slideTo(prevIndex);
                    updateActiveCard(prevIndex);
                }
            }
        });

        // Etkinlikler Timeline Slider Inicializasyonu script.js dosyasında yapılıyor



        // Mobil Menü Toggle İşlevi - quickmenu.blade.php dosyasında tanımlandı
    </script>
@endsection

@section('after_scripts')
    <!-- Ek JavaScript dosyaları buraya -->
@endsection 