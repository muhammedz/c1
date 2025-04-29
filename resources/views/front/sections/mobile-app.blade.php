<!-- #### Mobile App Section -->
@if($mobileAppSettings->is_active ?? true)
<section id="mobile-app-section" class="mobile-app-section bg-white px-4 py-0">
    <div class="container max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-center">
            <!-- Sol Kısım - Logo ve Store Butonları -->
            <div class="flex flex-col items-center lg:items-start gap-6">
                <div class="flex items-center justify-between gap-4">
                    <!-- App Icon -->
                    <div class="w-32 h-32 rounded-[24px] flex items-center justify-center">
                        @if($mobileAppSettings->app_logo)
                            @if(strpos($mobileAppSettings->app_logo, '/uploads/') !== false)
                                <img src="{{ $mobileAppSettings->app_logo }}" alt="{{ $mobileAppSettings->app_name ?? 'Uygulama' }}" class="w-full h-full object-contain p-2">
                            @else
                                <img src="{{ asset('storage/' . $mobileAppSettings->app_logo) }}" alt="{{ $mobileAppSettings->app_name ?? 'Uygulama' }}" class="w-full h-full object-contain p-2">
                            @endif
                        @else
                            <span class="text-white text-2xl">icon</span>
                        @endif
                    </div>

                    <!-- Header Image (Uygulama Adı ve Alt Başlığı yerine) -->
                    @if($mobileAppSettings->app_header_image)
                        <div class="flex items-center justify-center overflow-hidden">
                            @if(strpos($mobileAppSettings->app_header_image, '/uploads/') !== false)
                                <img src="{{ $mobileAppSettings->app_header_image }}" alt="{{ $mobileAppSettings->app_name ?? 'Uygulama' }} Görsel" 
                                    style="width: {{ $mobileAppSettings->app_header_image_width ?? 320 }}px; height: {{ $mobileAppSettings->app_header_image_height ?? 200 }}px; object-fit: cover;">
                            @else
                                <img src="{{ asset('storage/' . $mobileAppSettings->app_header_image) }}" alt="{{ $mobileAppSettings->app_name ?? 'Uygulama' }} Görsel" 
                                    style="width: {{ $mobileAppSettings->app_header_image_width ?? 320 }}px; height: {{ $mobileAppSettings->app_header_image_height ?? 200 }}px; object-fit: cover;">
                            @endif
                        </div>
                    @else
                        <!-- Logo Text (Eğer app_header_image yoksa göster) -->
                        <div class="text-center lg:text-left">
                            <h3 class="text-xl font-bold text-gray-800 mb-1">{{ $mobileAppSettings->app_name ?? 'Farklı Logo gelecek' }}</h3>
                            <p class="text-gray-600">{{ $mobileAppSettings->app_subtitle ?? 'Png formatında' }}</p>
                        </div>
                    @endif
                </div>

                <!-- Store Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 w-full max-w-xs">
                    <!-- App Store Button -->
                    @if($mobileAppSettings->app_store_link)
                        <a href="{{ $mobileAppSettings->app_store_link }}" target="_blank" class="flex items-center gap-2 px-4 py-2 border border-[#004d2e] rounded-xl">
                            <svg class="w-6 h-6 text-[#007b32]" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18.71,19.5C17.88,20.74 17,21.95 15.66,21.97C14.32,22 13.89,21.18 12.37,21.18C10.84,21.18 10.37,21.95 9.1,22C7.79,22.05 6.8,20.68 5.96,19.47C4.25,17 2.94,12.45 4.7,9.39C5.57,7.87 7.13,6.91 8.82,6.88C10.1,6.86 11.32,7.75 12.11,7.75C12.89,7.75 14.37,6.68 15.92,6.84C16.57,6.87 18.39,7.1 19.56,8.82C19.47,8.88 17.39,10.1 17.41,12.63C17.44,15.65 20.06,16.66 20.09,16.67C20.06,16.74 19.67,18.11 18.71,19.5M13,3.5C13.73,2.67 14.94,2.04 15.94,2C16.07,3.17 15.6,4.35 14.9,5.19C14.21,6.04 13.07,6.7 11.95,6.61C11.8,5.46 12.36,4.26 13,3.5Z" />
                            </svg>
                            <div class="flex flex-col items-start">
                                <span class="text-xs text-[#007b32]">Şimdi İndir</span>
                                <span class="text-sm font-bold text-[#004d2e]">App Store</span>
                            </div>
                        </a>
                    @else
                        <a href="#" class="flex items-center gap-2 px-4 py-2 border border-[#004d2e] rounded-xl">
                            <svg class="w-6 h-6 text-[#007b32]" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18.71,19.5C17.88,20.74 17,21.95 15.66,21.97C14.32,22 13.89,21.18 12.37,21.18C10.84,21.18 10.37,21.95 9.1,22C7.79,22.05 6.8,20.68 5.96,19.47C4.25,17 2.94,12.45 4.7,9.39C5.57,7.87 7.13,6.91 8.82,6.88C10.1,6.86 11.32,7.75 12.11,7.75C12.89,7.75 14.37,6.68 15.92,6.84C16.57,6.87 18.39,7.1 19.56,8.82C19.47,8.88 17.39,10.1 17.41,12.63C17.44,15.65 20.06,16.66 20.09,16.67C20.06,16.74 19.67,18.11 18.71,19.5M13,3.5C13.73,2.67 14.94,2.04 15.94,2C16.07,3.17 15.6,4.35 14.9,5.19C14.21,6.04 13.07,6.7 11.95,6.61C11.8,5.46 12.36,4.26 13,3.5Z" />
                            </svg>
                            <div class="flex flex-col items-start">
                                <span class="text-xs text-[#007b32]">Şimdi İndir</span>
                                <span class="text-sm font-bold text-[#004d2e]">App Store</span>
                            </div>
                        </a>
                    @endif

                    <!-- Google Play Button -->
                    @if($mobileAppSettings->google_play_link)
                        <a href="{{ $mobileAppSettings->google_play_link }}" target="_blank" class="flex items-center gap-2 px-4 py-2 border border-[#004d2e] rounded-xl">
                            <svg class="w-6 h-6 text-[#004d2e]" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M3,20.5V3.5C3,2.91 3.34,2.39 3.84,2.15L13.69,12L3.84,21.85C3.34,21.6 3,21.09 3,20.5M16.81,15.12L6.05,21.34L14.54,12.85L16.81,15.12M20.16,10.81C20.5,11.08 20.75,11.5 20.75,12C20.75,12.5 20.5,12.92 20.16,13.19L17.89,14.5L15.39,12L17.89,9.5L20.16,10.81M6.05,2.66L16.81,8.88L14.54,11.15L6.05,2.66Z" />
                            </svg>
                            <div class="flex flex-col items-start">
                                <span class="text-xs text-[#004d2e]">Şimdi İndir</span>
                                <span class="text-sm font-bold text-[#004d2e]">Google Play</span>
                            </div>
                        </a>
                    @else
                        <a href="#" class="flex items-center gap-2 px-4 py-2 border border-[#004d2e] rounded-xl">
                            <svg class="w-6 h-6 text-[#004d2e]" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M3,20.5V3.5C3,2.91 3.34,2.39 3.84,2.15L13.69,12L3.84,21.85C3.34,21.6 3,21.09 3,20.5M16.81,15.12L6.05,21.34L14.54,12.85L16.81,15.12M20.16,10.81C20.5,11.08 20.75,11.5 20.75,12C20.75,12.5 20.5,12.92 20.16,13.19L17.89,14.5L15.39,12L17.89,9.5L20.16,10.81M6.05,2.66L16.81,8.88L14.54,11.15L6.05,2.66Z" />
                            </svg>
                            <div class="flex flex-col items-start">
                                <span class="text-xs text-[#004d2e]">Şimdi İndir</span>
                                <span class="text-sm font-bold text-[#004d2e]">Google Play</span>
                            </div>
                        </a>
                    @endif
                </div>

                <!-- Description -->
                <p class="text-[#004d2e] text-center lg:text-left">{{ $mobileAppSettings->app_description ?? 'Açıklama Yazısı Açıklama yazısı açıklama yazısı Açıklama yazısı, Açıklama Yazısı' }}</p>
            </div>

            <!-- Orta Kısım - Telefon Görseli -->
            <div class="hidden lg:flex justify-center">
                @if($mobileAppSettings->phone_image)
                    @if(strpos($mobileAppSettings->phone_image, '/uploads/') !== false)
                        <img src="{{ $mobileAppSettings->phone_image }}" alt="Mobile App Screenshot" class="w-auto h-[500px] object-cover">
                    @else
                        <img src="{{ asset('storage/' . $mobileAppSettings->phone_image) }}" alt="Mobile App Screenshot" class="w-auto h-[500px] object-cover">
                    @endif
                @else
                    <img src="{{ asset('assets/image/mobile-app.png') }}" alt="Mobile App Screenshot" class="w-auto h-[500px] object-cover">
                @endif
            </div>

            <!-- Sağ Kısım - Linkli Başlıklar -->
            <div class="flex flex-col gap-4">
                <!-- Link Card 1 -->
                @if($mobileAppSettings->link_card_1_title && $mobileAppSettings->link_card_1_url)
                    <a href="{{ $mobileAppSettings->link_card_1_url }}" target="_blank" class="bg-white rounded-2xl p-4 shadow-lg hover:shadow-xl transition-all flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-8 h-8 text-[#007b32]" viewBox="0 0 24 24" fill="currentColor">
                                @if($mobileAppSettings->link_card_1_icon == 'envelope')
                                    <path d="M20,8L12,13L4,8V6L12,11L20,6M20,4H4C2.89,4 2,4.89 2,6V18A2,2 0 0,0 4,20H20A2,2 0 0,0 22,18V6C22,4.89 21.1,4 20,4Z" />
                                @elseif($mobileAppSettings->link_card_1_icon == 'phone')
                                    <path d="M6.62,10.79C8.06,13.62 10.38,15.94 13.21,17.38L15.41,15.18C15.69,14.9 16.08,14.82 16.43,14.93C17.55,15.3 18.75,15.5 20,15.5A1,1 0 0,1 21,16.5V20A1,1 0 0,1 20,21A17,17 0 0,1 3,4A1,1 0 0,1 4,3H7.5A1,1 0 0,1 8.5,4C8.5,5.25 8.7,6.45 9.07,7.57C9.18,7.92 9.1,8.31 8.82,8.59L6.62,10.79Z" />
                                @else
                                    <path d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z" />
                                @endif
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-base font-semibold text-gray-800">{{ $mobileAppSettings->link_card_1_title }}</h3>
                        </div>
                        <svg class="w-6 h-6 border border-[#007b32] rounded-full text-[#007b32]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                @else
                    <a href="#" class="bg-white rounded-2xl p-4 shadow-lg hover:shadow-xl transition-all flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-8 h-8 text-[#007b32]" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-base font-semibold text-gray-800">Başlık Yazısı Linkle Gidecek</h3>
                        </div>
                        <svg class="w-6 h-6 border border-[#007b32] rounded-full text-[#007b32]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                @endif

                <!-- Link Card 2 -->
                @if($mobileAppSettings->link_card_2_title && $mobileAppSettings->link_card_2_url)
                    <a href="{{ $mobileAppSettings->link_card_2_url }}" target="_blank" class="bg-white rounded-2xl p-4 shadow-lg hover:shadow-xl transition-all flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-8 h-8 text-[#007b32]" viewBox="0 0 24 24" fill="currentColor">
                                @if($mobileAppSettings->link_card_2_icon == 'envelope')
                                    <path d="M20,8L12,13L4,8V6L12,11L20,6M20,4H4C2.89,4 2,4.89 2,6V18A2,2 0 0,0 4,20H20A2,2 0 0,0 22,18V6C22,4.89 21.1,4 20,4Z" />
                                @elseif($mobileAppSettings->link_card_2_icon == 'phone')
                                    <path d="M6.62,10.79C8.06,13.62 10.38,15.94 13.21,17.38L15.41,15.18C15.69,14.9 16.08,14.82 16.43,14.93C17.55,15.3 18.75,15.5 20,15.5A1,1 0 0,1 21,16.5V20A1,1 0 0,1 20,21A17,17 0 0,1 3,4A1,1 0 0,1 4,3H7.5A1,1 0 0,1 8.5,4C8.5,5.25 8.7,6.45 9.07,7.57C9.18,7.92 9.1,8.31 8.82,8.59L6.62,10.79Z" />
                                @else
                                    <path d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z" />
                                @endif
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-base font-semibold text-gray-800">{{ $mobileAppSettings->link_card_2_title }}</h3>
                        </div>
                        <svg class="w-6 h-6 border border-gray-500 rounded-full text-[#004d2e]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                @else
                    <a href="#" class="bg-white rounded-2xl p-4 shadow-lg hover:shadow-xl transition-all flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-8 h-8 text-[#007b32]" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-base font-semibold text-gray-800">Başlık Yazısı Linkle Gidecek</h3>
                        </div>
                        <svg class="w-6 h-6 border border-gray-500 rounded-full text-[#004d2e]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                @endif

                <!-- Link Card 3 -->
                @if($mobileAppSettings->link_card_3_title && $mobileAppSettings->link_card_3_url)
                    <a href="{{ $mobileAppSettings->link_card_3_url }}" target="_blank" class="bg-white rounded-2xl p-4 shadow-lg hover:shadow-xl transition-all flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-8 h-8 text-[#004d2e]" viewBox="0 0 24 24" fill="currentColor">
                                @if($mobileAppSettings->link_card_3_icon == 'envelope')
                                    <path d="M20,8L12,13L4,8V6L12,11L20,6M20,4H4C2.89,4 2,4.89 2,6V18A2,2 0 0,0 4,20H20A2,2 0 0,0 22,18V6C22,4.89 21.1,4 20,4Z" />
                                @elseif($mobileAppSettings->link_card_3_icon == 'phone')
                                    <path d="M6.62,10.79C8.06,13.62 10.38,15.94 13.21,17.38L15.41,15.18C15.69,14.9 16.08,14.82 16.43,14.93C17.55,15.3 18.75,15.5 20,15.5A1,1 0 0,1 21,16.5V20A1,1 0 0,1 20,21A17,17 0 0,1 3,4A1,1 0 0,1 4,3H7.5A1,1 0 0,1 8.5,4C8.5,5.25 8.7,6.45 9.07,7.57C9.18,7.92 9.1,8.31 8.82,8.59L6.62,10.79Z" />
                                @else
                                    <path d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z" />
                                @endif
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-base font-semibold text-gray-800">{{ $mobileAppSettings->link_card_3_title }}</h3>
                        </div>
                        <svg class="w-6 h-6 border border-gray-500 rounded-full text-[#004d2e]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                @else
                    <a href="#" class="bg-white rounded-2xl p-4 shadow-lg hover:shadow-xl transition-all flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-8 h-8 text-[#004d2e]" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-base font-semibold text-gray-800">Başlık Yazısı Linkle Gidecek</h3>
                        </div>
                        <svg class="w-6 h-6 border border-gray-500 rounded-full text-[#004d2e]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>
@endif 