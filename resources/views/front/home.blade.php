@extends('layouts.front')

@section('title', 'Ana Sayfa')

@section('content')
    <div class="relative z-0">
        @include('front.sections.slider')
    </div>
    <div class="w-full relative z-50 -mt-11">
        <div class="container max-w-[1235px] mx-auto px-4">
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
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
@endsection

@section('after_styles')
    <!-- Özel CSS dosyaları buraya -->
@endsection

@section('before_scripts')
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        // Hero Slider inicializasyonu slider.blade.php dosyasının içinde yapılıyor.
        // Çakışma olmaması için buradan kaldırıldı.

        // News Thumbs Swiper önce başlatılmalı
        var newsThumbsSwiper = new Swiper(".newsThumbsSwiper", {
            slidesPerView: 2,
            grid: {
                rows: 2,
            },
            spaceBetween: 10,
            navigation: {
                nextEl: '.news-next-btn',
                prevEl: '.news-prev-btn',
            },
        });

        // Haber Slider Inicializasyonu
        var newsMainSwiper = new Swiper(".newsMainSwiper", {
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            thumbs: {
                swiper: newsThumbsSwiper
            }
        });

        // Proje Slider Inicializasyonu
        var projectSwipers = {};
        document.querySelectorAll('.projectSwiper').forEach(function(element) {
            var category = element.dataset.category;
            projectSwipers[category] = new Swiper(element, {
                slidesPerView: 1,
                spaceBetween: 20,
                navigation: {
                    nextEl: '.swiper-next',
                    prevEl: '.swiper-prev',
                },
            });
        });

        // Etkinlikler Timeline Slider Inicializasyonu
        var timelineSwiper = new Swiper(".timelineSwiper", {
            slidesPerView: 1,
            spaceBetween: 20,
            navigation: {
                nextEl: '.timeline-next',
                prevEl: '.timeline-prev',
            },
            breakpoints: {
                640: {
                    slidesPerView: 1,
                },
                768: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 3,
                },
            },
        });

        // Kategori Değiştirme İşlevi
        document.querySelectorAll('.category-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                var category = this.dataset.category;
                
                // Aktif kategori butonunu güncelle
                document.querySelectorAll('.category-btn').forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                // Başlığı güncelle
                document.getElementById('categoryTitle').textContent = this.textContent.trim();
                
                // Sliderleri gizle/göster
                document.querySelectorAll('.projectSwiper').forEach(slider => {
                    if (slider.dataset.category === category) {
                        slider.classList.remove('hidden');
                        projectSwipers[category].update();
                    } else {
                        slider.classList.add('hidden');
                    }
                });
            });
        });

        // Mobil Menü Toggle İşlevi
        document.addEventListener('DOMContentLoaded', function() {
            const menuItems = document.querySelectorAll('.md\\:hidden .group');
            menuItems.forEach(item => {
                const link = item.querySelector('a');
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    item.classList.toggle('active');
                });
            });
        });
    </script>
@endsection

@section('after_scripts')
    <!-- Ek JavaScript dosyaları buraya -->
@endsection 