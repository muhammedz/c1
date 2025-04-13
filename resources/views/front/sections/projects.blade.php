<!-- #### Projects Section -->
@if(isset($projectSettings) && $projectSettings->is_active)
<section id="projects-section" class="projects-section bg-white w-full px-4 py-12">
    <div class="container max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sol Kısım - Kategoriler -->
        <div class="space-y-3">
            <!-- Kategoriler -->
            @forelse($projectCategories as $category)
                <button data-category="{{ $category->slug }}" 
                    class="w-full text-left block bg-gray-200 hover:bg-gray-300 rounded-md p-4 font-medium category-btn {{ $loop->first ? 'active' : 'text-gray-800' }}">
                    {{ $category->name }}
                </button>
            @empty
                <!-- Varsayılan kategoriler -->
                <button data-category="gerceklesen" class="w-full text-left block bg-gray-200 hover:bg-gray-300 rounded-md p-4 font-medium category-btn active">
                    Gerçekleşen Projeler
                </button>
                <button data-category="planlanan" class="w-full text-left block bg-gray-200 hover:bg-gray-300 rounded-md p-4 font-medium text-gray-800 category-btn">
                    Planlanan Projeler
                </button>
                <button data-category="devam-eden" class="w-full text-left block bg-gray-200 hover:bg-gray-300 rounded-md p-4 font-medium text-gray-800 category-btn">
                    Devam Eden Projeler
                </button>
                <button data-category="uluslararasi" class="w-full text-left block bg-gray-200 hover:bg-gray-300 rounded-md p-4 font-medium text-gray-800 category-btn">
                    Uluslararası Projeler
                </button>
                <button data-category="sosyal" class="w-full text-left block bg-gray-200 hover:bg-gray-300 rounded-md p-4 font-medium text-gray-800 category-btn">
                    Sosyal Projeler
                </button>
            @endforelse
        </div>

        <!-- Sağ Kısım - Projeler -->
        <div class="lg:col-span-3">
            <!-- Başlık ve Tümünü Gör -->
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800" id="categoryTitle" style="font-family: 'TT Norms Pro', sans-serif; font-weight: 700;">
                    @if(count($projectCategories) > 0)
                        {{ $projectCategories->first()->name }}
                    @else
                        Gerçekleşen Projeler
                    @endif
                </h2>
                @if($projectSettings->show_view_all_button)
                    <a href="{{ $projectSettings->view_all_url ?? route('front.projects') }}" class="text-gray-600 hover:text-gray-800">
                        {{ $projectSettings->view_all_text ?? 'Tümünü Gör' }}
                    </a>
                @endif
            </div>

            <!-- Proje Sliderları -->
            <div class="relative flex items-center gap-5">
                <!-- Slider Navigation -->
                <button class="swiper-prev absolute left-0 top-1/2 -translate-y-1/2 -mx-4 -translate-x-4 z-10 w-8 h-8 bg-white rounded-full shadow-lg border border-gray-800 flex items-center justify-center">
                    <svg class="w-5 h-5 text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <button class="swiper-next absolute right-0 top-1/2 -translate-y-1/2 -mx-4 translate-x-4 z-10 w-8 h-8 bg-white rounded-full shadow-lg border border-gray-800 flex items-center justify-center">
                    <svg class="w-5 h-5 text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <!-- Kategori Slider'ları -->
                @foreach($projectCategories as $category)
                    <div class="swiper projectSwiper {{ !$loop->first ? 'hidden' : '' }}" data-category="{{ $category->slug }}">
                        <div class="swiper-wrapper">
                            @php
                                $categoryProjects = \App\Models\Project::where('category_id', $category->id)
                                    ->where('is_active', true)
                                    ->orderBy('order')
                                    ->get();
                            @endphp
                            
                            @forelse($categoryProjects as $project)
                                <div class="swiper-slide">
                                    <a href="{{ route('front.projects.detail', $project->slug) }}" class="block aspect-video rounded-2xl overflow-hidden relative group">
                                        <img src="{{ $project->cover_image_url }}" alt="{{ $project->title }}" class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-black/60"></div>
                                        <div class="absolute bottom-0 left-0 right-0 p-6">
                                            <h3 class="text-white text-xl font-bold" style="font-family: 'TT Norms Pro', sans-serif; font-weight: 700;">{{ $project->title }}</h3>
                                            <p class="text-white/80 mt-2" style="font-family: 'TT Norms Pro', sans-serif; font-weight: 400;">{{ $project->status_text }}</p>
                                        </div>
                                    </a>
                                </div>
                            @empty
                                <div class="swiper-slide">
                                    <div class="block aspect-video rounded-2xl overflow-hidden relative group bg-gray-100 flex items-center justify-center">
                                        <p class="text-gray-600 text-center p-6">Bu kategoride henüz proje bulunmuyor.</p>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endforeach

                <!-- Eğer veritabanında hiç kategori yoksa varsayılan olarak eski görünümü göster -->
                @if(count($projectCategories) == 0)
                    <!-- Gerçekleşen Projeler Slider -->
                    <div class="swiper projectSwiper" data-category="gerceklesen">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <a href="#" class="block aspect-video rounded-2xl overflow-hidden relative group">
                                    <img src="https://images.unsplash.com/photo-1587764379873-97837921fd44" alt="HAYVAN EVİ" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/60"></div>
                                    <div class="absolute bottom-0 left-0 right-0 p-6">
                                        <h3 class="text-white text-xl font-bold" style="font-family: 'TT Norms Pro', sans-serif; font-weight: 700;">HAYVAN EVİ</h3>
                                        <p class="text-white/80 mt-2" style="font-family: 'TT Norms Pro', sans-serif; font-weight: 400;">2024'te Tamamlandı</p>
                                    </div>
                                </a>
                            </div>
                            <div class="swiper-slide">
                                <a href="#" class="block aspect-video rounded-2xl overflow-hidden relative group">
                                    <img src="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4" alt="ÇOCUK EVİ" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/60"></div>
                                    <div class="absolute bottom-0 left-0 right-0 p-6">
                                        <h3 class="text-white text-xl font-bold" style="font-family: 'TT Norms Pro', sans-serif; font-weight: 700;">ÇOCUK EVİ</h3>
                                        <p class="text-white/80 mt-2" style="font-family: 'TT Norms Pro', sans-serif; font-weight: 400;">2023'te Tamamlandı</p>
                                    </div>
                                </a>
                            </div>
                            <div class="swiper-slide">
                                <a href="#" class="block aspect-video rounded-2xl overflow-hidden relative group">
                                    <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab" alt="SPOR SALONU" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/60"></div>
                                    <div class="absolute bottom-0 left-0 right-0 p-6">
                                        <h3 class="text-white text-xl font-bold" style="font-family: 'TT Norms Pro', sans-serif; font-weight: 700;">SPOR SALONU</h3>
                                        <p class="text-white/80 mt-2" style="font-family: 'TT Norms Pro', sans-serif; font-weight: 400;">2024'te Tamamlandı</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Planlanan Projeler Slider -->
                    <div class="swiper projectSwiper hidden" data-category="planlanan">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <a href="#" class="block aspect-video rounded-2xl overflow-hidden relative group">
                                    <img src="https://images.unsplash.com/photo-1531834685032-c34bf0d84c77" alt="YEŞİL ENERJİ" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/60"></div>
                                    <div class="absolute bottom-0 left-0 right-0 p-6">
                                        <h3 class="text-white text-xl font-bold">YEŞİL ENERJİ SANTRALİ</h3>
                                        <p class="text-white/80 mt-2">2025'te Başlayacak</p>
                                    </div>
                                </a>
                            </div>
                            <div class="swiper-slide">
                                <a href="#" class="block aspect-video rounded-2xl overflow-hidden relative group">
                                    <img src="https://images.unsplash.com/photo-1517048676732-d65bc937f952" alt="AKILLI ULAŞIM" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/60"></div>
                                    <div class="absolute bottom-0 left-0 right-0 p-6">
                                        <h3 class="text-white text-xl font-bold">AKILLI ULAŞIM SİSTEMİ</h3>
                                        <p class="text-white/80 mt-2">2025'te Başlayacak</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Devam Eden Projeler Slider -->
                    <div class="swiper projectSwiper hidden" data-category="devam-eden">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <a href="#" class="block aspect-video rounded-2xl overflow-hidden relative group">
                                    <img src="https://images.unsplash.com/photo-1497366216548-37526070297c" alt="AKILLI ŞEHİR" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/60"></div>
                                    <div class="absolute bottom-0 left-0 right-0 p-6">
                                        <h3 class="text-white text-xl font-bold">AKILLI ŞEHİR PROJESİ</h3>
                                        <p class="text-white/80 mt-2">%60 Tamamlandı</p>
                                    </div>
                                </a>
                            </div>
                            <div class="swiper-slide">
                                <a href="#" class="block aspect-video rounded-2xl overflow-hidden relative group">
                                    <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab" alt="METRO HATTI" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/60"></div>
                                    <div class="absolute bottom-0 left-0 right-0 p-6">
                                        <h3 class="text-white text-xl font-bold">METRO HATTI</h3>
                                        <p class="text-white/80 mt-2">%75 Tamamlandı</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Uluslararası Projeler Slider -->
                    <div class="swiper projectSwiper hidden" data-category="uluslararasi">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <a href="#" class="block aspect-video rounded-2xl overflow-hidden relative group">
                                    <img src="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4" alt="GLOBAL İŞ BİRLİĞİ" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/60"></div>
                                    <div class="absolute bottom-0 left-0 right-0 p-6">
                                        <h3 class="text-white text-xl font-bold">GLOBAL İŞ BİRLİĞİ</h3>
                                        <p class="text-white/80 mt-2">AB Destekli Proje</p>
                                    </div>
                                </a>
                            </div>
                            <div class="swiper-slide">
                                <a href="#" class="block aspect-video rounded-2xl overflow-hidden relative group">
                                    <img src="https://images.unsplash.com/photo-1497366216548-37526070297c" alt="TEKNOLOJİ TRANSFERİ" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/60"></div>
                                    <div class="absolute bottom-0 left-0 right-0 p-6">
                                        <h3 class="text-white text-xl font-bold">TEKNOLOJİ TRANSFERİ</h3>
                                        <p class="text-white/80 mt-2">Japonya İş Birliği</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Sosyal Projeler Slider -->
                    <div class="swiper projectSwiper hidden" data-category="sosyal">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <a href="#" class="block aspect-video rounded-2xl overflow-hidden relative group">
                                    <img src="https://images.unsplash.com/photo-1517048676732-d65bc937f952" alt="TOPLUM MERKEZİ" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/60"></div>
                                    <div class="absolute bottom-0 left-0 right-0 p-6">
                                        <h3 class="text-white text-xl font-bold">TOPLUM MERKEZİ</h3>
                                        <p class="text-white/80 mt-2">Sosyal Sorumluluk</p>
                                    </div>
                                </a>
                            </div>
                            <div class="swiper-slide">
                                <a href="#" class="block aspect-video rounded-2xl overflow-hidden relative group">
                                    <img src="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4" alt="EĞİTİM MERKEZİ" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/60"></div>
                                    <div class="absolute bottom-0 left-0 right-0 p-6">
                                        <h3 class="text-white text-xl font-bold">EĞİTİM MERKEZİ</h3>
                                        <p class="text-white/80 mt-2">Gençlik Projesi</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Projects JS -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Kategori Butonlarını Yönet
    const categoryButtons = document.querySelectorAll('.category-btn');
    const categoryTitle = document.getElementById('categoryTitle');
    
    // Kategori butonuna tıklandığında ilgili slider'ı göster
    categoryButtons.forEach(button => {
        button.addEventListener('click', function() {
            const category = this.getAttribute('data-category');
            
            // Aktif kategori butonunu güncelle
            categoryButtons.forEach(btn => {
                btn.classList.remove('active');
                btn.classList.add('text-gray-800');
            });
            this.classList.add('active');
            this.classList.remove('text-gray-800');
            
            // Kategori başlığını güncelle
            categoryTitle.textContent = this.textContent.trim();
            
            // İlgili slider'ı göster, diğerlerini gizle
            document.querySelectorAll('.projectSwiper').forEach(slider => {
                if (slider.getAttribute('data-category') === category) {
                    slider.classList.remove('hidden');
                } else {
                    slider.classList.add('hidden');
                }
            });
        });
    });
    
    // Her bir kategori için Swiper başlat
    document.querySelectorAll('.projectSwiper').forEach(slider => {
        new Swiper(slider, {
            slidesPerView: 1,
            spaceBetween: 20,
            navigation: {
                nextEl: '.swiper-next',
                prevEl: '.swiper-prev',
            },
            breakpoints: {
                640: {
                    slidesPerView: 1,
                    spaceBetween: 20,
                },
                768: {
                    slidesPerView: 2,
                    spaceBetween: 30,
                },
                1024: {
                    slidesPerView: 2,
                    spaceBetween: 30,
                },
            }
        });
    });
});
</script>
@endif 