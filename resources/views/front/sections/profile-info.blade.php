<!-- Profil Bilgileri Bölümü -->
<div id="profile-info-section" class="profile-info-section container max-w-7xl mx-auto px-4 py-6 mt-1">
    <div class="flex flex-col md:flex-row gap-8">
        <!-- Profil Kartı -->
        <div class="bg-white rounded-3xl shadow-lg overflow-hidden md:w-1/2">
            <div class="p-0">
                <div class="flex flex-col md:flex-row">
                    <!-- Profil Fotoğrafı ve Sosyal Medya İkonları Yan Yana -->
                    <div class="w-full relative">
                        @if($profileSettings->filemanagersystem_profile_photo)
                            <div class="flex">
                                <div class="w-3/4">
                                    <img src="{{ asset($profileSettings->filemanagersystem_profile_photo) }}" alt="{{ $profileSettings->filemanagersystem_profile_photo_alt ?? $profileSettings->name }}" class="w-full">
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
                                            <i class="fab fa-twitter text-white text-xs"></i>
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
                                            <i class="fab fa-twitter text-white text-xs"></i>
                                        </a>
                                        <a href="#" class="w-8 h-8 bg-[#007d32] rounded-lg flex items-center justify-center hover:bg-[#003d24] transition-colors">
                                            <i class="fab fa-youtube text-white text-xs"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @elseif($profileSettings->profile_photo)
                            <div class="flex">
                                <div class="w-3/4">
                                    <img src="{{ asset('storage/' . $profileSettings->profile_photo) }}" alt="{{ $profileSettings->name }}" class="w-full">
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
                                            <i class="fab fa-twitter text-white text-xs"></i>
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
                                            <i class="fab fa-twitter text-white text-xs"></i>
                                        </a>
                                        <a href="#" class="w-8 h-8 bg-[#007d32] rounded-lg flex items-center justify-center hover:bg-[#003d24] transition-colors">
                                            <i class="fab fa-youtube text-white text-xs"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="flex">
                                <div class="w-3/4">
                                    <img src="{{ asset('assets/image/person.png') }}" alt="{{ $profileSettings->name ?? 'Profil' }}" class="w-full">
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
                                            <i class="fab fa-twitter text-white text-xs"></i>
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
                                            <i class="fab fa-twitter text-white text-xs"></i>
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
                        <div class="flex h-full">
                            <div class="w-1/3 bg-white p-3 flex flex-col justify-center items-center" style="padding-top: 80px">
                                <div class="text-center">
                                    <h2 class="text-3xl font-bold leading-tight" style="font-family: 'TT Norms Pro', sans-serif;">İletişim</h2>
                                    <h3 class="text-2xl leading-tight mb-3" style="font-family: 'TT Norms Pro', sans-serif;">Merkezi</h3>
                                    
                                    <div class="flex items-center justify-center mt-3">
                                        <a href="https://wa.me/905321234567" target="_blank" class="flex items-center">
                                            <span class="w-8 h-8 bg-[#25D366] rounded-full flex items-center justify-center mr-1">
                                                <i class="fab fa-whatsapp text-white text-sm"></i>
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="w-2/3 flex items-center" style="margin-bottom: -4px;">
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