@php
    $footerSettings = \App\Models\FooterSetting::first();
    $footerMenus = \App\Models\FooterMenu::active()->ordered()->with('activeLinks')->get();
@endphp

<!-- Footer kısmı -->
<footer id="footer-section" class="footer-section bg-[#065a28] text-white py-12 mt-12" style="font-family: 'TT Norms Pro', sans-serif;">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-8">
            @foreach($footerMenus as $menu)
                <div>
                    <h3 class="text-xl font-bold mb-6 text-white">{{ $menu->title }}</h3>
                    @if($menu->activeLinks->count() > 0)
                        <ul class="space-y-2 text-sm">
                            @foreach($menu->activeLinks as $link)
                                <li><a href="{{ $link->url }}" class="hover:underline">{{ $link->title }}</a></li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endforeach

            <!-- İLETİŞİM VE LOGO -->
            <div class="text-center">
                <!-- Logo -->
                <div class="mb-6">
                    <div class="flex justify-center items-center mb-4">
                        @if($footerSettings && $footerSettings->logo)
                            <img src="{{ $footerSettings->logo_url }}" alt="{{ $footerSettings->company_name }}" class="w-48 h-48 object-contain">
                        @else
                            <div class="w-48 h-48 bg-white rounded-full flex items-center justify-center">
                                <svg class="w-32 h-32 text-[#065a28]" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2L2 7v10c0 5.55 3.84 9.74 9 11 5.16-1.26 9-5.45 9-11V7l-10-5z"/>
                                </svg>
                            </div>
                        @endif
                    </div>
                    @if($footerSettings)
                        @if($footerSettings->company_name)
                            <h4 class="text-lg font-bold text-white">{{ $footerSettings->company_name }}</h4>
                        @else
                            <h4 class="text-lg font-bold text-white">Çankaya</h4>
                        @endif
                        @if($footerSettings->company_subtitle)
                            <p class="text-sm text-white/80">{{ $footerSettings->company_subtitle }}</p>
                        @else
                            <p class="text-sm text-white/80">BELEDİYESİ</p>
                        @endif
                    @else
                        <h4 class="text-lg font-bold text-white">Çankaya</h4>
                        <p class="text-sm text-white/80">BELEDİYESİ</p>
                    @endif
                </div>

                <!-- İletişim Bilgileri -->
                <div class="text-sm space-y-3 mb-6">
                    @if($footerSettings)
                        @if($footerSettings->address_line1 || $footerSettings->address_line2)
                        <div>
                            @if($footerSettings->address_line1)
                                <p class="font-semibold text-white">{{ $footerSettings->address_line1 }}</p>
                            @endif
                            @if($footerSettings->address_line2)
                                <p class="text-white/80">{{ $footerSettings->address_line2 }}</p>
                            @endif
                        </div>
                        @endif
                        
                        @if($footerSettings->contact_center_title || $footerSettings->contact_center_phone)
                        <div>
                            @if($footerSettings->contact_center_title)
                                <p class="font-semibold text-white">{{ $footerSettings->contact_center_title }}</p>
                            @endif
                            @if($footerSettings->contact_center_phone)
                                <p class="text-white/80">{{ $footerSettings->contact_center_phone }}</p>
                            @endif
                        </div>
                        @endif
                        
                        @if($footerSettings->whatsapp_title || $footerSettings->whatsapp_number)
                        <div>
                            @if($footerSettings->whatsapp_title)
                                <p class="font-semibold text-white">{{ $footerSettings->whatsapp_title }}</p>
                            @endif
                            @if($footerSettings->whatsapp_number)
                                <p class="text-white/80">{{ $footerSettings->whatsapp_number }}</p>
                            @endif
                        </div>
                        @endif
                        
                        @if($footerSettings->email_title || $footerSettings->email_address)
                        <div>
                            @if($footerSettings->email_title)
                                <p class="font-semibold text-white">{{ $footerSettings->email_title }}</p>
                            @endif
                            @if($footerSettings->email_address)
                                <p class="text-white/80 break-all">{{ $footerSettings->email_address }}</p>
                            @endif
                        </div>
                        @endif
                        
                        @if($footerSettings->kep_title || $footerSettings->kep_address)
                        <div>
                            @if($footerSettings->kep_title)
                                <p class="font-semibold text-white">{{ $footerSettings->kep_title }}</p>
                            @endif
                            @if($footerSettings->kep_address)
                                <p class="text-white/80 break-all">{{ $footerSettings->kep_address }}</p>
                            @endif
                        </div>
                        @endif
                    @else
                        <div>
                            <p class="font-semibold text-white">Ziya Gökalp Caddesi</p>
                            <p class="text-white/80">No: 11 Kızılay/Ankara</p>
                        </div>
                        
                        <div>
                            <p class="font-semibold text-white">Çankaya İletişim Merkezi</p>
                            <p class="text-white/80">444 06 01</p>
                        </div>
                        
                        <div>
                            <p class="font-semibold text-white">Whatsapp Hattı</p>
                            <p class="text-white/80">0(505) 167 19 67</p>
                        </div>
                        
                        <div>
                            <p class="font-semibold text-white">E-Posta</p>
                            <p class="text-white/80 break-all">iletisimmerkezi@cankaya.bel.tr</p>
                        </div>
                        
                        <div>
                            <p class="font-semibold text-white">Kep Adresi</p>
                            <p class="text-white/80 break-all">cankayabelediyesi@hs01.kep.tr</p>
                        </div>
                    @endif
                </div>

                <!-- Sosyal Medya -->
                <div class="flex justify-center gap-3">
                    @if($footerSettings && $footerSettings->facebook_url)
                        <a href="{{ $footerSettings->facebook_url }}" target="_blank" class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-white/20 transition-colors">
                            <i class="fab fa-facebook-f text-white text-lg"></i>
                        </a>
                    @endif
                    
                    @if($footerSettings && $footerSettings->instagram_url)
                        <a href="{{ $footerSettings->instagram_url }}" target="_blank" class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-white/20 transition-colors">
                            <i class="fab fa-instagram text-white text-lg"></i>
                        </a>
                    @endif
                    
                    @if($footerSettings && $footerSettings->twitter_url)
                        <a href="{{ $footerSettings->twitter_url }}" target="_blank" class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-white/20 transition-colors">
                            <i class="fab fa-x-twitter text-white text-lg"></i>
                        </a>
                    @endif
                    
                    @if($footerSettings && $footerSettings->youtube_url)
                        <a href="{{ $footerSettings->youtube_url }}" target="_blank" class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-white/20 transition-colors">
                            <i class="fab fa-youtube text-white text-lg"></i>
                        </a>
                    @endif
                    
                    @if($footerSettings && $footerSettings->linkedin_url)
                        <a href="{{ $footerSettings->linkedin_url }}" target="_blank" class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-white/20 transition-colors">
                            <i class="fab fa-linkedin text-white text-lg"></i>
                        </a>
                    @endif
                    
                    {{-- Eğer hiç sosyal medya linki yoksa varsayılan görünüm --}}
                    @if(!$footerSettings || (!$footerSettings->facebook_url && !$footerSettings->instagram_url && !$footerSettings->twitter_url && !$footerSettings->youtube_url && !$footerSettings->linkedin_url))
                        <a href="#" class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-white/20 transition-colors">
                            <i class="fab fa-facebook-f text-white text-lg"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-white/20 transition-colors">
                            <i class="fab fa-instagram text-white text-lg"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-white/20 transition-colors">
                            <i class="fab fa-x-twitter text-white text-lg"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-white/20 transition-colors">
                            <i class="fab fa-youtube text-white text-lg"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Copyright -->
        <div class="border-t border-white/20 mt-8 pt-6">
            <div class="flex flex-col md:flex-row justify-between items-center">
                @if($footerSettings && $footerSettings->copyright_left)
                    <p class="text-white/80 text-sm mb-2 md:mb-0">
                        {{ $footerSettings->copyright_left }}
                    </p>
                @else
                    <p class="text-white/80 text-sm mb-2 md:mb-0">
                        © {{ date('Y') }} Çankaya Belediyesi. Tüm hakları saklıdır.
                    </p>
                @endif
                
                @if($footerSettings && $footerSettings->copyright_right)
                    <p class="text-white/80 text-sm">
                        {{ $footerSettings->copyright_right }}
                    </p>
                @endif
            </div>
        </div>
    </div>
</footer>

<!-- JavaScript yüklemeleri layout dosyasında yapılıyor - çakışmayı önlemek için kaldırıldı -->