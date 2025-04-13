    <!-- #### News Section -->
    <section id="news-section" class="news-section container max-w-7xl mx-auto px-4 py-12">
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Ana Haber Slider -->
            <div class="w-full md:w-[55%] ">
                <div class="swiper newsMainSwiper h-[350px] rounded-[32px] overflow-hidden bg-black">
                    <div class="swiper-wrapper">
                        @forelse($headlines as $headline)
                            <div class="swiper-slide relative">
                                <img src="{{ $headline->image }}" alt="{{ $headline->title }}" class="w-full h-full object-cover opacity-70">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/50 to-transparent"></div>
                                <div class="absolute bottom-0 left-0 right-0 p-8">
                                    <h3 class="text-3xl font-bold text-white mb-4">{{ $headline->title }}</h3>
                                    <p class="text-base text-gray-200 line-clamp-3">{!! Str::limit(strip_tags($headline->content), 150) !!}</p>
                                </div>
                            </div>
                        @empty
                            <div class="swiper-slide relative">
                                <img src="https://images.unsplash.com/photo-1504384308090-c894fdcc538d?w=1200&h=800&fit=crop" alt="Varsayılan Görsel" class="w-full h-full object-cover opacity-70">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/50 to-transparent"></div>
                                <div class="absolute bottom-0 left-0 right-0 p-8">
                                    <h3 class="text-3xl font-bold text-white mb-4">Henüz manşet haber eklenmemiş</h3>
                                    <p class="text-base text-gray-200 line-clamp-3">Admin panelinden manşet haberler ekleyebilirsiniz.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Sağ Taraf Haber Kartları -->
            <div class="swiper newsThumbsSwiper h-full w-full md:w-[45%] pr-3">
                <!-- Başlık ve Navigasyon -->
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-800">Haberler</h2>
                    <div class="flex gap-2">
                        <button class="news-prev-btn w-10 h-10 rounded-full border border-gray-200 flex items-center justify-center hover:border-[#004d2e] hover:text-[#004d2e] transition-colors">
                            <span class="material-icons">chevron_left</span>
                        </button>
                        <button class="news-next-btn w-10 h-10 rounded-full border border-gray-200 flex items-center justify-center hover:border-[#004d2e] hover:text-[#004d2e] transition-colors">
                            <span class="material-icons">chevron_right</span>
                        </button>
                    </div>
                </div>

                <div class="swiper-wrapper grid grid-cols-2 gap-1 ">
                    @forelse($headlines as $index => $headline)
                        <div class="swiper-slide !h-[110px] cursor-pointer">
                            <div class="w-full block bg-white rounded-[32px] h-full overflow-hidden shadow-[0_2px_20px_rgba(0,0,0,0.05)] hover:shadow-[0_2px_20px_rgba(0,0,0,0.1)] transition-all hover:bg-gray-50">
                                <div class="p-6 h-full flex flex-col rounded-[32px]">
                                    <h3 class="text-md text-gray-800 line-clamp-2">{{ $headline->title }}</h3>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="swiper-slide !h-[110px] cursor-pointer">
                            <div class="w-full block bg-white rounded-[32px] h-full overflow-hidden shadow-[0_2px_20px_rgba(0,0,0,0.05)] hover:shadow-[0_2px_20px_rgba(0,0,0,0.1)] transition-all hover:bg-gray-50">
                                <div class="p-6 h-full flex flex-col rounded-[32px]">
                                    <h3 class="text-md text-gray-800 line-clamp-2">Manşet haber bulunamadı</h3>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    <!-- News Slider Initialize -->
  