    <!-- #### News Section -->
    <section id="news-section" class="news-section container max-w-7xl mx-auto px-4 py-6 mt-5">
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Ana Haber Slider -->
            <div class="w-full md:w-[55%] ">
                <div class="swiper newsMainSwiper h-[250px] md:h-[350px] rounded-2xl overflow-hidden bg-black">
                    <div class="swiper-wrapper">
                        @forelse($headlines as $headline)
                            <div class="swiper-slide relative">
                                <a href="{{ route('news.show', $headline->slug) }}" class="absolute inset-0 z-10">
                                    <span class="sr-only">{{ $headline->title }}</span>
                                </a>
                                <img src="{{ $headline->image }}" alt="{{ $headline->title }}" class="w-full h-full object-cover opacity-80">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-black/20 to-transparent"></div>
                                <div class="absolute bottom-0 left-0 right-0 p-4 md:p-8 z-0">
                                    <div class="inline-block py-1 px-3 bg-black/60 rounded backdrop-blur-sm">
                                        <h3 class="text-lg md:text-2xl font-bold text-white line-clamp-2">{{ $headline->title }}</h3>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="swiper-slide relative">
                                <img src="https://images.unsplash.com/photo-1504384308090-c894fdcc538d?w=1200&h=800&fit=crop" alt="Varsayılan Görsel" class="w-full h-full object-cover opacity-80">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-black/20 to-transparent"></div>
                                <div class="absolute bottom-0 left-0 right-0 p-4 md:p-8">
                                    <div class="inline-block py-1 px-3 bg-black/60 rounded backdrop-blur-sm">
                                        <h3 class="text-lg md:text-2xl font-bold text-white">Henüz manşet haber eklenmemiş</h3>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Sağ Taraf Haber Kartları -->
            <div class="swiper newsThumbsSwiper h-full w-full md:w-[45%] md:pr-3">
                <!-- Başlık ve Navigasyon -->
                <div class="flex justify-between items-center mb-4 md:mb-8">
                    <div class="flex items-center gap-2 md:gap-4">
                        <h2 class="text-xl md:text-2xl font-bold text-gray-800">Haberler</h2>
                        <a href="/haberler" class="text-xs md:text-sm text-[#004d2e] hover:underline">Tüm Haberler</a>
                    </div>
                    <div class="flex gap-2">
                        <button class="news-prev-btn w-8 h-8 md:w-10 md:h-10 rounded-full border border-gray-200 flex items-center justify-center hover:border-[#004d2e] hover:text-[#004d2e] transition-colors">
                            <span class="material-icons text-lg md:text-xl">chevron_left</span>
                        </button>
                        <button class="news-next-btn w-8 h-8 md:w-10 md:h-10 rounded-full border border-gray-200 flex items-center justify-center hover:border-[#004d2e] hover:text-[#004d2e] transition-colors">
                            <span class="material-icons text-lg md:text-xl">chevron_right</span>
                        </button>
                    </div>
                </div>

                <div class="swiper-wrapper grid grid-cols-2 gap-1">
                    @forelse($headlines as $index => $headline)
                        <div class="swiper-slide !h-[100px] md:!h-[120px] cursor-pointer">
                            <a href="{{ route('news.show', $headline->slug) }}" class="w-full h-full transparent rounded-xl md:rounded-2xl overflow-hidden transition-all block border border-gray-100">
                                <div class="p-2 md:p-4 h-full flex items-center">
                                    <h3 class="text-xs md:text-sm font-medium text-gray-800 line-clamp-3 leading-tight">{{ $headline->title }}</h3>
                                </div>
                            </a>
                        </div>
                    @empty
                        <div class="swiper-slide !h-[100px] md:!h-[120px] cursor-pointer">
                            <div class="w-full h-full transparent rounded-xl md:rounded-2xl overflow-hidden transition-all block border border-gray-100">
                                <div class="p-2 md:p-4 h-full flex items-center">
                                    <h3 class="text-xs md:text-sm font-medium text-gray-800 line-clamp-3 leading-tight">Manşet haber bulunamadı</h3>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    <!-- News Slider Initialize -->
  