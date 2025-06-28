<!-- Profil Bilgileri Bölümü -->
<div id="profile-info-section" class="profile-info-section container max-w-7xl mx-auto px-4 py-6 mt-5">
    <div class="flex flex-col md:flex-row gap-8">
        <!-- Profil Kartı -->
        <div class="bg-white rounded-3xl shadow-lg overflow-hidden md:w-1/2">
            <div class="p-0">
                <div class="flex flex-col md:flex-row">
                    <!-- Profil Fotoğrafı ve Sosyal Medya İkonları -->
                    <div class="w-full relative">
                        @if($profileSettings->filemanagersystem_profile_photo)
                            <!-- Mobil görünüm -->
                            <div class="mobile-profile-photo md:hidden relative">
                                <a href="/baskan">
                                    <img src="{{ asset($profileSettings->filemanagersystem_profile_photo) }}" alt="{{ $profileSettings->filemanagersystem_profile_photo_alt ?? $profileSettings->name }}" class="w-full">
                                </a>
                                <!-- Sosyal medya ikonları sağ alt köşede -->
                                <div class="absolute bottom-2 right-2 flex flex-row gap-1">
                                    @if($profileSettings->facebook_url)
                                        <a href="{{ $profileSettings->facebook_url }}" target="_blank" class="w-6 h-6 bg-[#007d32] rounded-md flex items-center justify-center hover:bg-[#003d24] transition-colors">
                                            <i class="fab fa-facebook-f text-white text-xs"></i>
                                        </a>
                                    @endif
                                    
                                    @if($profileSettings->instagram_url)
                                        <a href="{{ $profileSettings->instagram_url }}" target="_blank" class="w-6 h-6 bg-[#007d32] rounded-md flex items-center justify-center hover:bg-[#003d24] transition-colors">
                                            <i class="fab fa-instagram text-white text-xs"></i>
                                        </a>
                                    @endif
                                    
                                    @if($profileSettings->twitter_url)
                                        <a href="{{ $profileSettings->twitter_url }}" target="_blank" class="w-6 h-6 bg-[#007d32] rounded-md flex items-center justify-center hover:bg-[#003d24] transition-colors">
                                            <i class="fab fa-x-twitter text-white text-xs"></i>
                                        </a>
                                    @endif
                                    
                                    @if($profileSettings->youtube_url)
                                        <a href="{{ $profileSettings->youtube_url }}" target="_blank" class="w-6 h-6 bg-[#007d32] rounded-md flex items-center justify-center hover:bg-[#003d24] transition-colors">
                                            <i class="fab fa-youtube text-white text-xs"></i>
                                        </a>
                                    @endif
                                    
                                    @if(!$profileSettings->facebook_url && !$profileSettings->instagram_url && !$profileSettings->twitter_url && !$profileSettings->youtube_url)
                                        <a href="#" class="w-6 h-6 bg-[#007d32] rounded-md flex items-center justify-center hover:bg-[#003d24] transition-colors">
                                            <i class="fab fa-facebook-f text-white text-xs"></i>
                                        </a>
                                        <a href="#" class="w-6 h-6 bg-[#007d32] rounded-md flex items-center justify-center hover:bg-[#003d24] transition-colors">
                                            <i class="fab fa-instagram text-white text-xs"></i>
                                        </a>
                                        <a href="#" class="w-6 h-6 bg-[#007d32] rounded-md flex items-center justify-center hover:bg-[#003d24] transition-colors">
                                            <i class="fab fa-x-twitter text-white text-xs"></i>
                                        </a>
                                        <a href="#" class="w-6 h-6 bg-[#007d32] rounded-md flex items-center justify-center hover:bg-[#003d24] transition-colors">
                                            <i class="fab fa-youtube text-white text-xs"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <!-- Desktop görünüm -->
                            <div class="hidden md:flex">
                                <div class="w-3/4">
                                    <a href="/baskan">
                                        <img src="{{ asset($profileSettings->filemanagersystem_profile_photo) }}" alt="{{ $profileSettings->filemanagersystem_profile_photo_alt ?? $profileSettings->name }}" class="w-full">
                                    </a>
                                </div>
                                <div class="w-1/4 bg-white p-3 flex flex-col justify-center items-center gap-2">
                                    @if($profileSettings->facebook_url)
                                        <a href="{{ $profileSettings->facebook_url }}" target="_blank" class="w-8 h-8 bg-[#007d32] rounded-lg flex items-center justify-center hover:bg-[#003d24] transition-colors">
                                            <i class="fab fa-facebook-f text-white text-xs"></i>
                                        </a>
                                    @endif
                                    
                                    @if($profileSettings->instagram_url)
                                        <a href="{{ $profileSettings->instagram_url }}" target="_blank" class="w-8 h-8 bg-[#007d32] rounded-lg flex items-center justify-center hover:bg-[#003d24] transition-colors">
                                            <i class="fab fa-instagram text-white text-xs"></i>
                                        </a>
                                    @endif
                                    
                                    @if($profileSettings->twitter_url)
                                        <a href="{{ $profileSettings->twitter_url }}" target="_blank" class="w-8 h-8 bg-[#007d32] rounded-lg flex items-center justify-center hover:bg-[#003d24] transition-colors">
                                            <i class="fab fa-x-twitter text-white text-xs"></i>
                                        </a>
                                    @endif
                                    
                                    @if($profileSettings->youtube_url)
                                        <a href="{{ $profileSettings->youtube_url }}" target="_blank" class="w-8 h-8 bg-[#007d32] rounded-lg flex items-center justify-center hover:bg-[#003d24] transition-colors">
                                            <i class="fab fa-youtube text-white text-xs"></i>
                                        </a>
                                    @endif
                                    
                                    @if(!$profileSettings->facebook_url && !$profileSettings->instagram_url && !$profileSettings->twitter_url && !$profileSettings->youtube_url)
                                        <a href="#" class="w-8 h-8 bg-[#007d32] rounded-lg flex items-center justify-center hover:bg-[#003d24] transition-colors">
                                            <i class="fab fa-facebook-f text-white text-xs"></i>
                                        </a>
                                        <a href="#" class="w-8 h-8 bg-[#007d32] rounded-lg flex items-center justify-center hover:bg-[#003d24] transition-colors">
                                            <i class="fab fa-instagram text-white text-xs"></i>
                                        </a>
                                        <a href="#" class="w-8 h-8 bg-[#007d32] rounded-lg flex items-center justify-center hover:bg-[#003d24] transition-colors">
                                            <i class="fab fa-x-twitter text-white text-xs"></i>
                                        </a>
                                        <a href="#" class="w-8 h-8 bg-[#007d32] rounded-lg flex items-center justify-center hover:bg-[#003d24] transition-colors">
                                            <i class="fab fa-youtube text-white text-xs"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @elseif($profileSettings->profile_photo)
                            <!-- Mobil görünüm -->
                            <div class="mobile-profile-fallback md:hidden relative">
                                <a href="/baskan">
                                    <img src="{{ asset('storage/' . $profileSettings->profile_photo) }}" alt="{{ $profileSettings->name }}" class="w-full">
                                </a>
                                <!-- Sosyal medya ikonları sağ alt köşede -->
                                <div class="absolute bottom-2 right-2 flex flex-row gap-1">
                                    @if($profileSettings->facebook_url)
                                        <a href="{{ $profileSettings->facebook_url }}" target="_blank" class="w-6 h-6 bg-[#007d32] rounded-md flex items-center justify-center hover:bg-[#003d24] transition-colors">
                                            <i class="fab fa-facebook-f text-white text-xs"></i>
                                        </a>
                                    @endif
                                    
                                    @if($profileSettings->instagram_url)
                                        <a href="{{ $profileSettings->instagram_url }}" target="_blank" class="w-6 h-6 bg-[#007d32] rounded-md flex items-center justify-center hover:bg-[#003d24] transition-colors">
                                            <i class="fab fa-instagram text-white text-xs"></i>
                                        </a>
                                    @endif
                                    
                                    @if($profileSettings->twitter_url)
                                        <a href="{{ $profileSettings->twitter_url }}" target="_blank" class="w-6 h-6 bg-[#007d32] rounded-md flex items-center justify-center hover:bg-[#003d24] transition-colors">
                                            <i class="fab fa-x-twitter text-white text-xs"></i>
                                        </a>
                                    @endif
                                    
                                    @if($profileSettings->youtube_url)
                                        <a href="{{ $profileSettings->youtube_url }}" target="_blank" class="w-6 h-6 bg-[#007d32] rounded-md flex items-center justify-center hover:bg-[#003d24] transition-colors">
                                            <i class="fab fa-youtube text-white text-xs"></i>
                                        </a>
                                    @endif
                                    
                                    @if(!$profileSettings->facebook_url && !$profileSettings->instagram_url && !$profileSettings->twitter_url && !$profileSettings->youtube_url)
                                        <a href="#" class="w-6 h-6 bg-[#007d32] rounded-md flex items-center justify-center hover:bg-[#003d24] transition-colors">
                                            <i class="fab fa-facebook-f text-white text-xs"></i>
                                        </a>
                                        <a href="#" class="w-6 h-6 bg-[#007d32] rounded-md flex items-center justify-center hover:bg-[#003d24] transition-colors">
                                            <i class="fab fa-instagram text-white text-xs"></i>
                                        </a>
                                        <a href="#" class="w-6 h-6 bg-[#007d32] rounded-md flex items-center justify-center hover:bg-[#003d24] transition-colors">
                                            <i class="fab fa-x-twitter text-white text-xs"></i>
                                        </a>
                                        <a href="#" class="w-6 h-6 bg-[#007d32] rounded-md flex items-center justify-center hover:bg-[#003d24] transition-colors">
                                            <i class="fab fa-youtube text-white text-xs"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <!-- Desktop görünüm -->
                            <div class="hidden md:flex">
                                <div class="w-3/4">
                                    <a href="/baskan">
                                        <img src="{{ asset('storage/' . $profileSettings->profile_photo) }}" alt="{{ $profileSettings->name }}" class="w-full">
                                    </a>
                                </div>
                                <div class="w-1/4 bg-white p-3 flex flex-col justify-center items-center gap-2">
                                    @if($profileSettings->facebook_url)
                                        <a href="{{ $profileSettings->facebook_url }}" target="_blank" class="w-8 h-8 bg-[#007d32] rounded-lg flex items-center justify-center hover:bg-[#003d24] transition-colors">
                                            <i class="fab fa-facebook-f text-white text-xs"></i>
                                        </a>
                                    @endif
                                    
                                    @if($profileSettings->instagram_url)
                                        <a href="{{ $profileSettings->instagram_url }}" target="_blank" class="w-8 h-8 bg-[#007d32] rounded-lg flex items-center justify-center hover:bg-[#003d24] transition-colors">
                                            <i class="fab fa-instagram text-white text-xs"></i>
                                        </a>
                                    @endif
                                    
                                    @if($profileSettings->twitter_url)
                                        <a href="{{ $profileSettings->twitter_url }}" target="_blank" class="w-8 h-8 bg-[#007d32] rounded-lg flex items-center justify-center hover:bg-[#003d24] transition-colors">
                                            <i class="fab fa-x-twitter text-white text-xs"></i>
                                        </a>
                                    @endif
                                    
                                    @if($profileSettings->youtube_url)
                                        <a href="{{ $profileSettings->youtube_url }}" target="_blank" class="w-8 h-8 bg-[#007d32] rounded-lg flex items-center justify-center hover:bg-[#003d24] transition-colors">
                                            <i class="fab fa-youtube text-white text-xs"></i>
                                        </a>
                                    @endif
                                    
                                    @if(!$profileSettings->facebook_url && !$profileSettings->instagram_url && !$profileSettings->twitter_url && !$profileSettings->youtube_url)
                                        <a href="#" class="w-8 h-8 bg-[#007d32] rounded-lg flex items-center justify-center hover:bg-[#003d24] transition-colors">
                                            <i class="fab fa-facebook-f text-white text-xs"></i>
                                        </a>
                                        <a href="#" class="w-8 h-8 bg-[#007d32] rounded-lg flex items-center justify-center hover:bg-[#003d24] transition-colors">
                                            <i class="fab fa-instagram text-white text-xs"></i>
                                        </a>
                                        <a href="#" class="w-8 h-8 bg-[#007d32] rounded-lg flex items-center justify-center hover:bg-[#003d24] transition-colors">
                                            <i class="fab fa-x-twitter text-white text-xs"></i>
                                        </a>
                                        <a href="#" class="w-8 h-8 bg-[#007d32] rounded-lg flex items-center justify-center hover:bg-[#003d24] transition-colors">
                                            <i class="fab fa-youtube text-white text-xs"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @else
                            <!-- Mobil görünüm -->
                            <div class="mobile-profile-default md:hidden relative">
                                <a href="/baskan">
                                    <img src="{{ asset('assets/image/person.png') }}" alt="{{ $profileSettings->name ?? 'Profil' }}" class="w-full">
                                </a>
                                <!-- Sosyal medya ikonları sağ alt köşede -->
                                <div class="absolute bottom-2 right-2 flex flex-row gap-1">
                                    @if($profileSettings->facebook_url)
                                        <a href="{{ $profileSettings->facebook_url }}" target="_blank" class="w-6 h-6 bg-[#007d32] rounded-md flex items-center justify-center hover:bg-[#003d24] transition-colors">
                                            <i class="fab fa-facebook-f text-white text-xs"></i>
                                        </a>
                                    @endif
                                    
                                    @if($profileSettings->instagram_url)
                                        <a href="{{ $profileSettings->instagram_url }}" target="_blank" class="w-6 h-6 bg-[#007d32] rounded-md flex items-center justify-center hover:bg-[#003d24] transition-colors">
                                            <i class="fab fa-instagram text-white text-xs"></i>
                                        </a>
                                    @endif
                                    
                                    @if($profileSettings->twitter_url)
                                        <a href="{{ $profileSettings->twitter_url }}" target="_blank" class="w-6 h-6 bg-[#007d32] rounded-md flex items-center justify-center hover:bg-[#003d24] transition-colors">
                                            <i class="fab fa-x-twitter text-white text-xs"></i>
                                        </a>
                                    @endif
                                    
                                    @if($profileSettings->youtube_url)
                                        <a href="{{ $profileSettings->youtube_url }}" target="_blank" class="w-6 h-6 bg-[#007d32] rounded-md flex items-center justify-center hover:bg-[#003d24] transition-colors">
                                            <i class="fab fa-youtube text-white text-xs"></i>
                                        </a>
                                    @endif
                                    
                                    @if(!$profileSettings->facebook_url && !$profileSettings->instagram_url && !$profileSettings->twitter_url && !$profileSettings->youtube_url)
                                        <a href="#" class="w-6 h-6 bg-[#007d32] rounded-md flex items-center justify-center hover:bg-[#003d24] transition-colors">
                                            <i class="fab fa-facebook-f text-white text-xs"></i>
                                        </a>
                                        <a href="#" class="w-6 h-6 bg-[#007d32] rounded-md flex items-center justify-center hover:bg-[#003d24] transition-colors">
                                            <i class="fab fa-instagram text-white text-xs"></i>
                                        </a>
                                        <a href="#" class="w-6 h-6 bg-[#007d32] rounded-md flex items-center justify-center hover:bg-[#003d24] transition-colors">
                                            <i class="fab fa-x-twitter text-white text-xs"></i>
                                        </a>
                                        <a href="#" class="w-6 h-6 bg-[#007d32] rounded-md flex items-center justify-center hover:bg-[#003d24] transition-colors">
                                            <i class="fab fa-youtube text-white text-xs"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <!-- Desktop görünüm -->
                            <div class="hidden md:flex">
                                <div class="w-3/4">
                                    <a href="/baskan">
                                        <img src="{{ asset('assets/image/person.png') }}" alt="{{ $profileSettings->name ?? 'Profil' }}" class="w-full">
                                    </a>
                                </div>
                                <div class="w-1/4 bg-white p-3 flex flex-col justify-center items-center gap-2">
                                    @if($profileSettings->facebook_url)
                                        <a href="{{ $profileSettings->facebook_url }}" target="_blank" class="w-8 h-8 bg-[#007d32] rounded-lg flex items-center justify-center hover:bg-[#003d24] transition-colors">
                                            <i class="fab fa-facebook-f text-white text-xs"></i>
                                        </a>
                                    @endif
                                    
                                    @if($profileSettings->instagram_url)
                                        <a href="{{ $profileSettings->instagram_url }}" target="_blank" class="w-8 h-8 bg-[#007d32] rounded-lg flex items-center justify-center hover:bg-[#003d24] transition-colors">
                                            <i class="fab fa-instagram text-white text-xs"></i>
                                        </a>
                                    @endif
                                    
                                    @if($profileSettings->twitter_url)
                                        <a href="{{ $profileSettings->twitter_url }}" target="_blank" class="w-8 h-8 bg-[#007d32] rounded-lg flex items-center justify-center hover:bg-[#003d24] transition-colors">
                                            <i class="fab fa-x-twitter text-white text-xs"></i>
                                        </a>
                                    @endif
                                    
                                    @if($profileSettings->youtube_url)
                                        <a href="{{ $profileSettings->youtube_url }}" target="_blank" class="w-8 h-8 bg-[#007d32] rounded-lg flex items-center justify-center hover:bg-[#003d24] transition-colors">
                                            <i class="fab fa-youtube text-white text-xs"></i>
                                        </a>
                                    @endif
                                    
                                    @if(!$profileSettings->facebook_url && !$profileSettings->instagram_url && !$profileSettings->twitter_url && !$profileSettings->youtube_url)
                                        <a href="#" class="w-8 h-8 bg-[#007d32] rounded-lg flex items-center justify-center hover:bg-[#003d24] transition-colors">
                                            <i class="fab fa-facebook-f text-white text-xs"></i>
                                        </a>
                                        <a href="#" class="w-8 h-8 bg-[#007d32] rounded-lg flex items-center justify-center hover:bg-[#003d24] transition-colors">
                                            <i class="fab fa-instagram text-white text-xs"></i>
                                        </a>
                                        <a href="#" class="w-8 h-8 bg-[#007d32] rounded-lg flex items-center justify-center hover:bg-[#003d24] transition-colors">
                                            <i class="fab fa-x-twitter text-white text-xs"></i>
                                        </a>
                                        <a href="#" class="w-8 h-8 bg-[#007d32] rounded-lg flex items-center justify-center hover:bg-[#003d24] transition-colors">
                                            <i class="fab fa-youtube text-white text-xs"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- İletişim Merkezi Kartı -->
        <div class="bg-white rounded-3xl shadow-lg overflow-hidden md:w-1/2">
            <div class="p-0">
                <div class="flex flex-col md:flex-row h-full">
                    <div class="w-full">
                        <!-- Mobil görünüm -->
                        <div class="mobile-contact-section md:hidden flex h-full">
                            <div class="w-2/5 bg-white p-2 flex flex-col justify-center items-start pl-4" style="padding-top: 30px">
                                <div class="text-left">
                                    <div class="flex flex-col items-start">
                                        <h2 class="text-xl font-bold" style="font-family: 'TT Norms Pro', sans-serif;">İletişim</h2>
                                        <h2 class="text-lg" style="font-family: 'TT Norms Pro', sans-serif;">Merkezi</h2>
                                    </div>
                                    
                                    <div class="flex flex-col items-start justify-center mt-3 gap-2">
                                        <a href="tel:4440601" class="flex items-center">
                                            <span class="w-6 h-6 bg-[#0066cc] rounded-full flex items-center justify-center mr-2">
                                                <i class="fas fa-phone-alt text-white text-xs"></i>
                                            </span>
                                            <span class="text-sm font-bold">444 06 01</span>
                                        </a>
                                        
                                        <a href="https://wa.me/905051671967" target="_blank" class="flex items-center">
                                            <span class="w-6 h-6 bg-[#25D366] rounded-full flex items-center justify-center mr-2">
                                                <i class="fab fa-whatsapp text-white text-xs"></i>
                                            </span>
                                            <span class="text-xs font-medium">0505 167 19 67</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="w-3/5 flex items-end justify-end" style="margin-bottom: -4px;">
                                @if($profileSettings->filemanagersystem_contact_image)
                                    <img src="{{ asset($profileSettings->filemanagersystem_contact_image) }}" 
                                         alt="{{ $profileSettings->filemanagersystem_contact_image_alt ?? 'İletişim Merkezi' }}" 
                                         class="w-full h-auto object-contain">
                                @elseif($profileSettings->contact_image)
                                    <img src="{{ asset('storage/' . $profileSettings->contact_image) }}" 
                                         alt="İletişim Merkezi" 
                                         class="w-full h-auto object-contain">
                                @else
                                    <img src="{{ asset('assets/image/ilist.png') }}" 
                                         alt="İletişim Merkezi"
                                         class="w-full h-auto object-contain">
                                @endif
                            </div>
                        </div>
                        <!-- Desktop görünüm -->
                        <div class="hidden md:flex h-full">
                            <div class="w-1/3 bg-white p-3 flex flex-col justify-center items-center" style="padding-top: 50px">
                                <div class="text-center pl-4">
                                    <div class="flex justify-center items-center whitespace-nowrap">
                                        <h2 class="text-3xl font-bold" style="font-family: 'TT Norms Pro', sans-serif;">İletişim</h2>
                                        <h2 class="text-2xl ml-2" style="font-family: 'TT Norms Pro', sans-serif;">Merkezi</h2>
                                    </div>
                                    
                                    <div class="flex flex-col items-center justify-center mt-5 gap-2">
                                        <a href="tel:4440601" class="flex items-center">
                                            <span class="w-8 h-8 bg-[#0066cc] rounded-full flex items-center justify-center mr-2">
                                                <i class="fas fa-phone-alt text-white text-sm"></i>
                                            </span>
                                            <span class="text-lg font-bold">444 06 01</span>
                                        </a>
                                        
                                        <a href="https://wa.me/905051671967" target="_blank" class="flex items-center">
                                            <span class="w-8 h-8 bg-[#25D366] rounded-full flex items-center justify-center mr-2">
                                                <i class="fab fa-whatsapp text-white text-sm"></i>
                                            </span>
                                            <span class="text-sm font-medium">0505 167 19 67</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="w-2/3 flex items-end justify-end" style="margin-bottom: -4px; padding-right: 10px;">
                                @if($profileSettings->filemanagersystem_contact_image)
                                    <img src="{{ asset($profileSettings->filemanagersystem_contact_image) }}" 
                                         alt="{{ $profileSettings->filemanagersystem_contact_image_alt ?? 'İletişim Merkezi' }}" 
                                         class="w-full">
                                @elseif($profileSettings->contact_image)
                                    <img src="{{ asset('storage/' . $profileSettings->contact_image) }}" 
                                         alt="İletişim Merkezi" 
                                         class="w-full">
                                @else
                                    <img src="{{ asset('assets/image/ilist.png') }}" 
                                         alt="İletişim Merkezi"
                                         class="w-full">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 