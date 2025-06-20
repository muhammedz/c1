<!-- #### Logo and Plans Section -->
@if(isset($logoPlans) && $logoPlans->is_active)

<section id="logo-and-plans-section" class="logo-and-plans-section container max-w-7xl mx-auto px-4 py-8 md:py-12">
    <div class="grid grid-cols-2 md:flex md:flex-row gap-3 md:gap-8">
        <!-- 1. Kart -->
        @if($logoPlans->card1_url)
            <a href="{{ $logoPlans->card1_url }}" class="bg-white rounded-2xl w-full h-40 md:h-48 md:w-1/4 shadow-sm p-4 md:p-8 flex items-center justify-center hover:shadow-md transition-shadow duration-200 cursor-pointer">
                <div class="text-center">
                    <div class="w-12 h-12 md:w-24 md:h-24 mx-auto mb-2 md:mb-4">
                        @if($logoPlans->card1_icon)
                            <i class="fas fa-{{ $logoPlans->card1_icon }} w-full h-full text-[#004d2e]" style="font-size: 2.5rem;"></i>
                        @else
                            <svg class="w-full h-full text-[#004d2e]" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 7V3H2v18h20V7H12zM6 19H4v-2h2v2zm0-4H4v-2h2v2zm0-4H4V9h2v2zm0-4H4V5h2v2zm4 12H8v-2h2v2zm0-4H8v-2h2v2zm0-4H8V9h2v2zm0-4H8V5h2v2zm10 12h-8v-2h2v-2h-2v-2h2v-2h-2V9h8v10zm-2-8h-2v2h2v-2zm0 4h-2v2h2v-2z" />
                            </svg>
                        @endif
                    </div>
                    <p class="text-gray-800 font-medium text-xs md:text-base leading-tight">
                        {{ $logoPlans->card1_title ?? 'Yazı Sayfaya Gidecek' }}
                    </p>
                </div>
            </a>
        @else
            <div class="bg-white rounded-2xl w-full h-40 md:h-48 md:w-1/4 shadow-sm p-4 md:p-8 flex items-center justify-center">
                <div class="text-center">
                    <div class="w-12 h-12 md:w-24 md:h-24 mx-auto mb-2 md:mb-4">
                        @if($logoPlans->card1_icon)
                            <i class="fas fa-{{ $logoPlans->card1_icon }} w-full h-full text-[#004d2e]" style="font-size: 2.5rem;"></i>
                        @else
                            <svg class="w-full h-full text-[#004d2e]" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 7V3H2v18h20V7H12zM6 19H4v-2h2v2zm0-4H4v-2h2v2zm0-4H4V9h2v2zm0-4H4V5h2v2zm4 12H8v-2h2v2zm0-4H8v-2h2v2zm0-4H8V9h2v2zm0-4H8V5h2v2zm10 12h-8v-2h2v-2h-2v-2h2v-2h-2V9h8v10zm-2-8h-2v2h2v-2zm0 4h-2v2h2v-2z" />
                            </svg>
                        @endif
                    </div>
                    <p class="text-gray-800 font-medium text-xs md:text-base leading-tight">
                        {{ $logoPlans->card1_title ?? 'Yazı Sayfaya Gidecek' }}
                    </p>
                </div>
            </div>
        @endif

        <!-- 2. Kart -->
        @if($logoPlans->card2_url)
            <a href="{{ $logoPlans->card2_url }}" class="bg-white rounded-2xl w-full h-40 md:h-48 md:w-1/4 shadow-sm p-4 md:p-8 flex items-center justify-center hover:shadow-md transition-shadow duration-200 cursor-pointer">
                <div class="text-center">
                    @if($logoPlans->card2_image)
                        <img src="{{ asset($logoPlans->card2_image) }}" alt="{{ $logoPlans->card2_title ?? 'Stratejik Plan' }}" class="w-16 h-16 md:w-32 md:h-32 mx-auto mb-2 md:mb-4 object-contain">
                    @else
                        <img src="https://cdn-icons-png.flaticon.com/512/1087/1087927.png" alt="{{ $logoPlans->card2_title ?? 'Stratejik Plan' }}" class="w-16 h-16 md:w-32 md:h-32 mx-auto mb-2 md:mb-4 object-contain">
                    @endif
                    <p class="text-gray-800 font-medium text-xs md:text-base leading-tight">
                        {{ $logoPlans->card2_title ?? 'Stratejik Plan' }}
                    </p>
                </div>
            </a>
        @else
            <div class="bg-white rounded-2xl w-full h-40 md:h-48 md:w-1/4 shadow-sm p-4 md:p-8 flex items-center justify-center">
                <div class="text-center">
                    @if($logoPlans->card2_image)
                        <img src="{{ asset($logoPlans->card2_image) }}" alt="{{ $logoPlans->card2_title ?? 'Stratejik Plan' }}" class="w-16 h-16 md:w-32 md:h-32 mx-auto mb-2 md:mb-4 object-contain">
                    @else
                        <img src="https://cdn-icons-png.flaticon.com/512/1087/1087927.png" alt="{{ $logoPlans->card2_title ?? 'Stratejik Plan' }}" class="w-16 h-16 md:w-32 md:h-32 mx-auto mb-2 md:mb-4 object-contain">
                    @endif
                    <p class="text-gray-800 font-medium text-xs md:text-base leading-tight">
                        {{ $logoPlans->card2_title ?? 'Stratejik Plan' }}
                    </p>
                </div>
            </div>
        @endif

        <!-- 3. Kart - Büyük Logo -->
        <div class="bg-[{{ $logoPlans->logo_bg_color ?? '#004d2e' }}] w-full md:w-2/4 h-40 md:h-48 rounded-2xl p-4 md:p-8 flex items-center justify-center col-span-2 md:col-span-1" style="background-color: {{ $logoPlans->logo_bg_color ?? '#004d2e' }}">
            <div class="text-center text-white">
                @if($logoPlans->logo_image)
                    <img src="{{ asset($logoPlans->logo_image) }}" alt="{{ $logoPlans->logo_title ?? 'Logo' }}" class="max-h-28 md:max-h-36 max-w-full mx-auto">
                @else
                    <p class="text-lg md:text-2xl font-medium">{{ $logoPlans->logo_title ?? 'Farklı Logo gelecek' }}<br><span class="text-sm md:text-base">Önerilen boyut: 300x100 piksel, PNG formatı</span></p>
                @endif
            </div>
        </div>
    </div>
</section>
@endif 