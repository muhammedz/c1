<!-- Header kısmı buraya gelecek -->
<!-- #### Header Section -->
<section id="header-section" class="header-section relative w-full h-24 pt-1 bg-white" style="z-index: 100;">
    <div class="head w-full h-24 bg-white relative" style="z-index: 100;">
        <nav class="container max-w-7xl h-full mx-auto px-4">
            <div class="flex items-center justify-between h-24">
                <!-- Logo Alanı -->
                <div class="flex items-center text-[#00352b] space-x-10">
                    <div class="text-xl font-bold">
                        <a href="/">
                            <img src="{{ asset('images/logo-cankaya.png') }}" alt="Çankaya Belediyesi Logo" class="h-16">
                        </a>
                    </div>
                    <div class="text-xl font-bold relative h-full flex items-end pb-1">
                        <img src="{{ asset('images/simge.png') }}" alt="Atatürk" class="h-24">
                    </div>
                </div>

                <!-- Mobil Menü Butonu -->
                <button class="md:hidden text-gray-600 hover:text-gray-900 focus:outline-none"
                    id="mobileMenuButton">
                    <span class="material-icons">menu</span>
                </button>

                <!-- Desktop Menü -->
                <div class="hidden md:flex items-center h-full space-x-1">
                    <!-- Kurumsal Mega Menü -->
                    <div class="group relative h-full flex items-center">
                        <a href="#"
                            class="text-[#00352b] h-full px-3 font-semibold hover:text-gray-900 text-md flex items-center">
                            Kurumsal
                            <span class="material-icons text-sm ml-1">expand_more</span>
                        </a>
                        <div class="mega-menu">
                            <div class="mega-menu-content mega-menu-kurumsal">
                                <div class="py-2 px-1">
                                    <div class="flex mb-2">
                                        <div class="w-full">
                                            <div class="grid grid-cols-4 gap-3">
                                                <!-- BELEDİYE MAKAMLARI -->
                                                <div class="mega-menu-category">
                                                    <h3>Belediye Makamları</h3>
                                                    <ul class="space-y-0.5">
                                                        <li>
                                                            <a href="#" class="mega-menu-link">
                                                                <span class="material-icons mega-menu-link-icon">person</span>
                                                                <span>Belediye Başkanı</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="mega-menu-link">
                                                                <span class="material-icons mega-menu-link-icon">groups</span>
                                                                <span>Belediye Meclisi</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="mega-menu-link">
                                                                <span class="material-icons mega-menu-link-icon">table_chart</span>
                                                                <span>Belediye Encümeni</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="mega-menu-link">
                                                                <span class="material-icons mega-menu-link-icon">supervisor_account</span>
                                                                <span>Başkan Yardımcıları</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="mega-menu-link">
                                                                <span class="material-icons mega-menu-link-icon">assignment_ind</span>
                                                                <span>Çalışma Komisyonları</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="mega-menu-link">
                                                                <span class="material-icons mega-menu-link-icon">account_balance</span>
                                                                <span>Müdürlükler</span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>

                                                <!-- KURUMSAL POLİTİKALARIMIZ -->
                                                <div class="mega-menu-category">
                                                    <h3>Kurumsal Politikalarımız</h3>
                                                    <ul class="space-y-0.5">
                                                        <li>
                                                            <a href="#" class="mega-menu-link">
                                                                <span class="material-icons mega-menu-link-icon">verified</span>
                                                                <span>Hizmet Standartlarımız</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="mega-menu-link">
                                                                <span class="material-icons mega-menu-link-icon">security</span>
                                                                <span>Bilgi Güvenliği</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="mega-menu-link">
                                                                <span class="material-icons mega-menu-link-icon">public</span>
                                                                <span>Uluslararası İş Birlikleri</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="mega-menu-link">
                                                                <span class="material-icons mega-menu-link-icon">accessible</span>
                                                                <span>Engelsiz İş Yerleri</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="mega-menu-link">
                                                                <span class="material-icons mega-menu-link-icon">restore_from_trash</span>
                                                                <span>Sıfır Atık</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="mega-menu-link">
                                                                <span class="material-icons mega-menu-link-icon">wb_sunny</span>
                                                                <span>İklim Değişikliği</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="mega-menu-link">
                                                                <span class="material-icons mega-menu-link-icon">lightbulb</span>
                                                                <span>Misyonumuz ve Vizyonumuz</span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>

                                                <!-- BELEDİYE İŞTİRAKLERİ -->
                                                <div class="mega-menu-category">
                                                    <h3>Belediye İştirakleri</h3>
                                                    <ul class="space-y-0.5">
                                                        <li>
                                                            <a href="#" class="mega-menu-link">
                                                                <span class="material-icons mega-menu-link-icon">business</span>
                                                                <span>Çankaya İmar A.Ş.</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="mega-menu-link">
                                                                <span class="material-icons mega-menu-link-icon">business</span>
                                                                <span>Çankaya BELDE A.Ş.</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="mega-menu-link">
                                                                <span class="material-icons mega-menu-link-icon">business</span>
                                                                <span>ÇANPAŞ</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="mega-menu-link">
                                                                <span class="material-icons mega-menu-link-icon">business</span>
                                                                <span>BELPET</span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>

                                                <!-- KİMLİK -->
                                                <div class="mega-menu-category">
                                                    <h3>Kimlik</h3>
                                                    <ul class="space-y-0.5">
                                                        <li>
                                                            <a href="#" class="mega-menu-link">
                                                                <span class="material-icons mega-menu-link-icon">badge</span>
                                                                <span>Kurumsal Kimlik</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="mega-menu-link">
                                                                <span class="material-icons mega-menu-link-icon">bar_chart</span>
                                                                <span>Rakamlarla Çankaya</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="mega-menu-link">
                                                                <span class="material-icons mega-menu-link-icon">history</span>
                                                                <span>Tarihçe</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="mega-menu-link">
                                                                <span class="material-icons mega-menu-link-icon">museum</span>
                                                                <span>Antik Tarih</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="mega-menu-link">
                                                                <span class="material-icons mega-menu-link-icon">theater_comedy</span>
                                                                <span>Kültürel Yaşam</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="mega-menu-link">
                                                                <span class="material-icons mega-menu-link-icon">location_city</span>
                                                                <span>Anıtkabir'in Tarihi</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="mega-menu-link">
                                                                <span class="material-icons mega-menu-link-icon">landscape</span>
                                                                <span>Doğal Yapı</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="mega-menu-link">
                                                                <span class="material-icons mega-menu-link-icon">savings</span>
                                                                <span>Ekonomik Yaşam</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="mega-menu-link">
                                                                <span class="material-icons mega-menu-link-icon">palette</span>
                                                                <span>Kültürel Miras</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="mega-menu-link">
                                                                <span class="material-icons mega-menu-link-icon">label</span>
                                                                <span>Çankaya'nın Adı</span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-2 pt-2 border-t border-gray-200">
                                        <div class="flex justify-between items-center">
                                            <p class="text-xs text-gray-500">Belediyemizin kurumsal hizmetleri hakkında daha fazla bilgi almak için iletişime geçin.</p>
                                            <a href="#" class="text-[#00352b] hover:text-[#007b32] text-sm font-medium flex items-center gap-1 transition-all hover:gap-2">
                                                İletişim 
                                                <span class="material-icons text-sm">arrow_forward</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Hizmetler Mega Menü -->
                    <div class="group relative h-full flex items-center">
                        <a href="#"
                            class="text-[#00352b] h-full px-3 font-semibold hover:text-gray-900 text-md flex items-center {{ request()->is('pages/hizmetler*') ? 'active-nav' : '' }}">
                            Hizmetler
                            <span class="material-icons text-sm ml-1">expand_more</span>
                        </a>
                        <div class="mega-menu">
                            <div class="container">
                                <div class="bg-white rounded-lg shadow-lg p-6 mega-menu-content">
                                    <!-- İlgili Gruplar -->
                                    <div class="mb-4">
                                        <h3 class="text-lg font-semibold mb-3 text-gray-700">İlgili Gruplar:</h3>
                                        <div class="flex flex-wrap gap-2">
                                            <a href="#" class="py-1 px-3 text-sm bg-blue-100 hover:bg-blue-200 text-blue-800 rounded-full transition">Öğrenciler</a>
                                            <a href="#" class="py-1 px-3 text-sm bg-pink-100 hover:bg-pink-200 text-pink-800 rounded-full transition">Anneler</a>
                                            <a href="#" class="py-1 px-3 text-sm bg-purple-100 hover:bg-purple-200 text-purple-800 rounded-full transition">Engelliler</a>
                                            <a href="#" class="py-1 px-3 text-sm bg-yellow-100 hover:bg-yellow-200 text-yellow-800 rounded-full transition">Emekliler</a>
                                            <a href="#" class="py-1 px-3 text-sm bg-green-100 hover:bg-green-200 text-green-800 rounded-full transition">İş Arayanlar</a>
                                        </div>
                                    </div>

                                    <!-- Hizmet Kategorileri Kartları -->
                                    <div class="grid grid-cols-6 gap-3">
                                        <!-- İş Yerleri -->
                                        <a href="#" data-category="isyerleri" class="p-4 bg-white hover:bg-gray-50 rounded-lg border border-gray-200 flex flex-col items-center text-center transition transform hover:translate-y-[-5px] hover:shadow-md">
                                            <span class="material-icons text-blue-600 text-2xl mb-2">store</span>
                                            <span class="text-sm font-medium text-gray-700">İş Yerleri</span>
                                        </a>
                                        
                                        <!-- Kültür -->
                                        <a href="#" data-category="kultur" class="p-4 bg-white hover:bg-gray-50 rounded-lg border border-gray-200 flex flex-col items-center text-center transition transform hover:translate-y-[-5px] hover:shadow-md">
                                            <span class="material-icons text-purple-600 text-2xl mb-2">theater_comedy</span>
                                            <span class="text-sm font-medium text-gray-700">Kültür</span>
                                        </a>
                                        
                                        <!-- Sağlık -->
                                        <a href="#" data-category="saglik" class="p-4 bg-white hover:bg-gray-50 rounded-lg border border-gray-200 flex flex-col items-center text-center transition transform hover:translate-y-[-5px] hover:shadow-md">
                                            <span class="material-icons text-red-600 text-2xl mb-2">local_hospital</span>
                                            <span class="text-sm font-medium text-gray-700">Sağlık</span>
                                        </a>
                                        
                                        <!-- İmar -->
                                        <a href="#" data-category="imar" class="p-4 bg-white hover:bg-gray-50 rounded-lg border border-gray-200 flex flex-col items-center text-center transition transform hover:translate-y-[-5px] hover:shadow-md">
                                            <span class="material-icons text-amber-600 text-2xl mb-2">apartment</span>
                                            <span class="text-sm font-medium text-gray-700">İmar</span>
                                        </a>
                                        
                                        <!-- Sosyal Yardım -->
                                        <a href="#" data-category="sosyalyardim" class="p-4 bg-white hover:bg-gray-50 rounded-lg border border-gray-200 flex flex-col items-center text-center transition transform hover:translate-y-[-5px] hover:shadow-md">
                                            <span class="material-icons text-teal-600 text-2xl mb-2">volunteer_activism</span>
                                            <span class="text-sm font-medium text-gray-700">Sosyal Yardım</span>
                                        </a>
                                        
                                        <!-- Çevre -->
                                        <a href="#" data-category="cevre" class="p-4 bg-white hover:bg-gray-50 rounded-lg border border-gray-200 flex flex-col items-center text-center transition transform hover:translate-y-[-5px] hover:shadow-md">
                                            <span class="material-icons text-green-600 text-2xl mb-2">nature</span>
                                            <span class="text-sm font-medium text-gray-700">Çevre</span>
                                        </a>
                                        
                                        <!-- Veterinerlik -->
                                        <a href="#" data-category="veterinerlik" class="p-4 bg-white hover:bg-gray-50 rounded-lg border border-gray-200 flex flex-col items-center text-center transition transform hover:translate-y-[-5px] hover:shadow-md">
                                            <span class="material-icons text-orange-600 text-2xl mb-2">pets</span>
                                            <span class="text-sm font-medium text-gray-700">Veterinerlik</span>
                                        </a>
                                        
                                        <!-- Temizlik -->
                                        <a href="#" data-category="temizlik" class="p-4 bg-white hover:bg-gray-50 rounded-lg border border-gray-200 flex flex-col items-center text-center transition transform hover:translate-y-[-5px] hover:shadow-md">
                                            <span class="material-icons text-cyan-600 text-2xl mb-2">cleaning_services</span>
                                            <span class="text-sm font-medium text-gray-700">Temizlik</span>
                                        </a>
                                        
                                        <!-- Kamu Emlak ve İstimlak İşlemleri -->
                                        <a href="#" data-category="kamuEmlak" class="p-4 bg-white hover:bg-gray-50 rounded-lg border border-gray-200 flex flex-col items-center text-center transition transform hover:translate-y-[-5px] hover:shadow-md">
                                            <span class="material-icons text-indigo-600 text-2xl mb-2">real_estate_agent</span>
                                            <span class="text-sm font-medium text-gray-700">Kamu Emlak ve İstimlak</span>
                                        </a>
                                        
                                        <!-- Park -->
                                        <a href="#" data-category="park" class="p-4 bg-white hover:bg-gray-50 rounded-lg border border-gray-200 flex flex-col items-center text-center transition transform hover:translate-y-[-5px] hover:shadow-md">
                                            <span class="material-icons text-lime-600 text-2xl mb-2">park</span>
                                            <span class="text-sm font-medium text-gray-700">Park</span>
                                        </a>
                                        
                                        <!-- Fen İşleri -->
                                        <a href="#" data-category="fenisleri" class="p-4 bg-white hover:bg-gray-50 rounded-lg border border-gray-200 flex flex-col items-center text-center transition transform hover:translate-y-[-5px] hover:shadow-md">
                                            <span class="material-icons text-gray-700 text-2xl mb-2">construction</span>
                                            <span class="text-sm font-medium text-gray-700">Fen İşleri</span>
                                        </a>
                                    </div>
                                    
                                    <!-- Kategori İçerikleri (Başlangıçta gizli) -->
                                    <div class="hidden">
                                        <!-- İş Yerleri İçeriği -->
                                        <div id="isyerleri" class="tab-pane hidden">
                                            <h3 class="text-xl font-semibold mb-4 text-blue-600">İş Yerleri</h3>
                                            <div class="grid grid-cols-4 gap-4">
                                                <a href="{{ route('hizmet',['slug' => 'ruhsat-islemleri']) }}" class="flex items-center p-3 bg-white hover:bg-blue-50 rounded-lg border border-gray-200 transition">
                                                    <span class="material-icons text-blue-500 mr-2">assignment</span>
                                                    <span class="text-sm">Ruhsat İşlemleri</span>
                                                </a>
                                                <a href="{{ route('hizmet',['slug' => 'ruhsat-basvuru-takibi']) }}" class="flex items-center p-3 bg-white hover:bg-blue-50 rounded-lg border border-gray-200 transition">
                                                    <span class="material-icons text-blue-500 mr-2">search</span>
                                                    <span class="text-sm">Ruhsat Başvuru Takibi</span>
                                                </a>
                                                <a href="{{ route('hizmet',['slug' => 'is-yeri-acma-islemleri']) }}" class="flex items-center p-3 bg-white hover:bg-blue-50 rounded-lg border border-gray-200 transition">
                                                    <span class="material-icons text-blue-500 mr-2">store</span>
                                                    <span class="text-sm">İş Yeri Açma İşlemleri</span>
                                                </a>
                                            </div>
                                        </div>
                                        
                                        <!-- Kültür İçeriği -->
                                        <div id="kultur" class="tab-pane hidden">
                                            <h3 class="text-xl font-semibold mb-4 text-purple-600">Kültür</h3>
                                            <div class="grid grid-cols-4 gap-4">
                                                <a href="{{ route('hizmet',['slug' => 'kutuphane-hizmetleri']) }}" class="flex items-center p-3 bg-white hover:bg-purple-50 rounded-lg border border-gray-200 transition">
                                                    <span class="material-icons text-purple-500 mr-2">menu_book</span>
                                                    <span class="text-sm">Kütüphane Hizmetleri</span>
                                                </a>
                                                <a href="{{ route('hizmet',['slug' => 'kultur-merkezleri']) }}" class="flex items-center p-3 bg-white hover:bg-purple-50 rounded-lg border border-gray-200 transition">
                                                    <span class="material-icons text-purple-500 mr-2">festival</span>
                                                    <span class="text-sm">Kültür Merkezleri</span>
                                                </a>
                                                <a href="{{ route('hizmet',['slug' => 'sanat-egitimi']) }}" class="flex items-center p-3 bg-white hover:bg-purple-50 rounded-lg border border-gray-200 transition">
                                                    <span class="material-icons text-purple-500 mr-2">palette</span>
                                                    <span class="text-sm">Sanat Eğitimi</span>
                                                </a>
                                            </div>
                                        </div>
                                        
                                        <!-- Sağlık İçeriği -->
                                        <div id="saglik" class="tab-pane hidden">
                                            <h3 class="text-xl font-semibold mb-4 text-red-600">Sağlık</h3>
                                            <div class="grid grid-cols-4 gap-4">
                                                <a href="{{ route('hizmet',['slug' => 'saglik-taramalari']) }}" class="flex items-center p-3 bg-white hover:bg-red-50 rounded-lg border border-gray-200 transition">
                                                    <span class="material-icons text-red-500 mr-2">monitor_heart</span>
                                                    <span class="text-sm">Sağlık Taramaları</span>
                                                </a>
                                                <a href="{{ route('hizmet',['slug' => 'ambulans-hizmetleri']) }}" class="flex items-center p-3 bg-white hover:bg-red-50 rounded-lg border border-gray-200 transition">
                                                    <span class="material-icons text-red-500 mr-2">emergency</span>
                                                    <span class="text-sm">Ambulans Hizmetleri</span>
                                                </a>
                                                <a href="{{ route('hizmet',['slug' => 'cenaze-hizmetleri']) }}" class="flex items-center p-3 bg-white hover:bg-red-50 rounded-lg border border-gray-200 transition">
                                                    <span class="material-icons text-red-500 mr-2">church</span>
                                                    <span class="text-sm">Cenaze Hizmetleri</span>
                                                </a>
                                            </div>
                                        </div>
                                        
                                        <!-- İmar İçeriği -->
                                        <div id="imar" class="tab-pane hidden">
                                            <h3 class="text-xl font-semibold mb-4 text-amber-600">İmar</h3>
                                            <div class="grid grid-cols-4 gap-4">
                                                <a href="{{ route('hizmet',['slug' => 'imar-durumu-sorgulama']) }}" class="flex items-center p-3 bg-white hover:bg-amber-50 rounded-lg border border-gray-200 transition">
                                                    <span class="material-icons text-amber-500 mr-2">search</span>
                                                    <span class="text-sm">İmar Durumu Sorgulama</span>
                                                </a>
                                                <a href="{{ route('hizmet',['slug' => 'yapi-ruhsati']) }}" class="flex items-center p-3 bg-white hover:bg-amber-50 rounded-lg border border-gray-200 transition">
                                                    <span class="material-icons text-amber-500 mr-2">badge</span>
                                                    <span class="text-sm">Yapı Ruhsatı</span>
                                                </a>
                                                <a href="{{ route('hizmet',['slug' => 'numarataj-islemleri']) }}" class="flex items-center p-3 bg-white hover:bg-amber-50 rounded-lg border border-gray-200 transition">
                                                    <span class="material-icons text-amber-500 mr-2">tag</span>
                                                    <span class="text-sm">Numarataj İşlemleri</span>
                                                </a>
                                            </div>
                                        </div>
                                        
                                        <!-- Sosyal Yardım İçeriği -->
                                        <div id="sosyalyardim" class="tab-pane hidden">
                                            <h3 class="text-xl font-semibold mb-4 text-teal-600">Sosyal Yardım</h3>
                                            <div class="grid grid-cols-4 gap-4">
                                                <a href="{{ route('hizmet',['slug' => 'sosyal-yardim-basvurusu']) }}" class="flex items-center p-3 bg-white hover:bg-teal-50 rounded-lg border border-gray-200 transition">
                                                    <span class="material-icons text-teal-500 mr-2">redeem</span>
                                                    <span class="text-sm">Sosyal Yardım Başvurusu</span>
                                                </a>
                                                <a href="{{ route('hizmet',['slug' => 'engelli-hizmetleri']) }}" class="flex items-center p-3 bg-white hover:bg-teal-50 rounded-lg border border-gray-200 transition">
                                                    <span class="material-icons text-teal-500 mr-2">accessible</span>
                                                    <span class="text-sm">Engelli Hizmetleri</span>
                                                </a>
                                                <a href="{{ route('hizmet',['slug' => 'asevi-hizmetleri']) }}" class="flex items-center p-3 bg-white hover:bg-teal-50 rounded-lg border border-gray-200 transition">
                                                    <span class="material-icons text-teal-500 mr-2">restaurant</span>
                                                    <span class="text-sm">Aşevi Hizmetleri</span>
                                                </a>
                                            </div>
                                        </div>
                                        
                                        <!-- Çevre İçeriği -->
                                        <div id="cevre" class="tab-pane hidden">
                                            <h3 class="text-xl font-semibold mb-4 text-green-600">Çevre</h3>
                                            <div class="grid grid-cols-4 gap-4">
                                                <a href="{{ route('hizmet',['slug' => 'geri-donusum']) }}" class="flex items-center p-3 bg-white hover:bg-green-50 rounded-lg border border-gray-200 transition">
                                                    <span class="material-icons text-green-500 mr-2">recycling</span>
                                                    <span class="text-sm">Geri Dönüşüm</span>
                                                </a>
                                                <a href="{{ route('hizmet',['slug' => 'cevre-denetimi']) }}" class="flex items-center p-3 bg-white hover:bg-green-50 rounded-lg border border-gray-200 transition">
                                                    <span class="material-icons text-green-500 mr-2">eco</span>
                                                    <span class="text-sm">Çevre Denetimi</span>
                                                </a>
                                                <a href="{{ route('hizmet',['slug' => 'hava-kalitesi']) }}" class="flex items-center p-3 bg-white hover:bg-green-50 rounded-lg border border-gray-200 transition">
                                                    <span class="material-icons text-green-500 mr-2">air</span>
                                                    <span class="text-sm">Hava Kalitesi</span>
                                                </a>
                                            </div>
                                        </div>
                                        
                                        <!-- Veterinerlik İçeriği -->
                                        <div id="veterinerlik" class="tab-pane hidden">
                                            <h3 class="text-xl font-semibold mb-4 text-orange-600">Veterinerlik</h3>
                                            <div class="grid grid-cols-4 gap-4">
                                                <a href="{{ route('hizmet',['slug' => 'hayvan-rehabilitasyon']) }}" class="flex items-center p-3 bg-white hover:bg-orange-50 rounded-lg border border-gray-200 transition">
                                                    <span class="material-icons text-orange-500 mr-2">healing</span>
                                                    <span class="text-sm">Hayvan Rehabilitasyon</span>
                                                </a>
                                                <a href="{{ route('hizmet',['slug' => 'asi-kampanyalari']) }}" class="flex items-center p-3 bg-white hover:bg-orange-50 rounded-lg border border-gray-200 transition">
                                                    <span class="material-icons text-orange-500 mr-2">vaccines</span>
                                                    <span class="text-sm">Aşı Kampanyaları</span>
                                                </a>
                                                <a href="{{ route('hizmet',['slug' => 'hayvan-sahiplendirme']) }}" class="flex items-center p-3 bg-white hover:bg-orange-50 rounded-lg border border-gray-200 transition">
                                                    <span class="material-icons text-orange-500 mr-2">pets</span>
                                                    <span class="text-sm">Hayvan Sahiplendirme</span>
                                                </a>
                                            </div>
                                        </div>
                                        
                                        <!-- Temizlik İçeriği -->
                                        <div id="temizlik" class="tab-pane hidden">
                                            <h3 class="text-xl font-semibold mb-4 text-cyan-600">Temizlik</h3>
                                            <div class="grid grid-cols-4 gap-4">
                                                <a href="{{ route('hizmet',['slug' => 'cop-toplama-saatleri']) }}" class="flex items-center p-3 bg-white hover:bg-cyan-50 rounded-lg border border-gray-200 transition">
                                                    <span class="material-icons text-cyan-500 mr-2">schedule</span>
                                                    <span class="text-sm">Çöp Toplama Saatleri</span>
                                                </a>
                                                <a href="{{ route('hizmet',['slug' => 'sokak-temizligi']) }}" class="flex items-center p-3 bg-white hover:bg-cyan-50 rounded-lg border border-gray-200 transition">
                                                    <span class="material-icons text-cyan-500 mr-2">cleaning_services</span>
                                                    <span class="text-sm">Sokak Temizliği</span>
                                                </a>
                                                <a href="{{ route('hizmet',['slug' => 'atik-bertarafi']) }}" class="flex items-center p-3 bg-white hover:bg-cyan-50 rounded-lg border border-gray-200 transition">
                                                    <span class="material-icons text-cyan-500 mr-2">delete</span>
                                                    <span class="text-sm">Atık Bertarafı</span>
                                                </a>
                                            </div>
                                        </div>
                                        
                                        <!-- Kamu Emlak ve İstimlak İşlemleri İçeriği -->
                                        <div id="kamuEmlak" class="tab-pane hidden">
                                            <h3 class="text-xl font-semibold mb-4 text-indigo-600">Kamu Emlak ve İstimlak İşlemleri</h3>
                                            <div class="grid grid-cols-4 gap-4">
                                                <a href="{{ route('hizmet',['slug' => 'tasinmaz-mal-islemleri']) }}" class="flex items-center p-3 bg-white hover:bg-indigo-50 rounded-lg border border-gray-200 transition">
                                                    <span class="material-icons text-indigo-500 mr-2">house</span>
                                                    <span class="text-sm">Taşınmaz Mal İşlemleri</span>
                                                </a>
                                                <a href="{{ route('hizmet',['slug' => 'istimlak-islemleri']) }}" class="flex items-center p-3 bg-white hover:bg-indigo-50 rounded-lg border border-gray-200 transition">
                                                    <span class="material-icons text-indigo-500 mr-2">real_estate_agent</span>
                                                    <span class="text-sm">İstimlak İşlemleri</span>
                                                </a>
                                                <a href="{{ route('hizmet',['slug' => 'kira-islemleri']) }}" class="flex items-center p-3 bg-white hover:bg-indigo-50 rounded-lg border border-gray-200 transition">
                                                    <span class="material-icons text-indigo-500 mr-2">payments</span>
                                                    <span class="text-sm">Kira İşlemleri</span>
                                                </a>
                                            </div>
                                        </div>
                                        
                                        <!-- Park İçeriği -->
                                        <div id="park" class="tab-pane hidden">
                                            <h3 class="text-xl font-semibold mb-4 text-lime-600">Park</h3>
                                            <div class="grid grid-cols-4 gap-4">
                                                <a href="{{ route('hizmet',['slug' => 'parklar-ve-bahceler']) }}" class="flex items-center p-3 bg-white hover:bg-lime-50 rounded-lg border border-gray-200 transition">
                                                    <span class="material-icons text-lime-500 mr-2">park</span>
                                                    <span class="text-sm">Parklar ve Bahçeler</span>
                                                </a>
                                                <a href="{{ route('hizmet',['slug' => 'cocuk-oyun-alanlari']) }}" class="flex items-center p-3 bg-white hover:bg-lime-50 rounded-lg border border-gray-200 transition">
                                                    <span class="material-icons text-lime-500 mr-2">child_care</span>
                                                    <span class="text-sm">Çocuk Oyun Alanları</span>
                                                </a>
                                                <a href="{{ route('hizmet',['slug' => 'spor-alanlari']) }}" class="flex items-center p-3 bg-white hover:bg-lime-50 rounded-lg border border-gray-200 transition">
                                                    <span class="material-icons text-lime-500 mr-2">sports_soccer</span>
                                                    <span class="text-sm">Spor Alanları</span>
                                                </a>
                                            </div>
                                        </div>
                                        
                                        <!-- Fen İşleri İçeriği -->
                                        <div id="fenisleri" class="tab-pane hidden">
                                            <h3 class="text-xl font-semibold mb-4 text-gray-600">Fen İşleri</h3>
                                            <div class="grid grid-cols-4 gap-4">
                                                <a href="{{ route('hizmet',['slug' => 'altyapi-calismalari']) }}" class="flex items-center p-3 bg-white hover:bg-gray-50 rounded-lg border border-gray-200 transition">
                                                    <span class="material-icons text-gray-500 mr-2">plumbing</span>
                                                    <span class="text-sm">Altyapı Çalışmaları</span>
                                                </a>
                                                <a href="{{ route('hizmet',['slug' => 'asfalt-calismalari']) }}" class="flex items-center p-3 bg-white hover:bg-gray-50 rounded-lg border border-gray-200 transition">
                                                    <span class="material-icons text-gray-500 mr-2">road</span>
                                                    <span class="text-sm">Asfalt Çalışmaları</span>
                                                </a>
                                                <a href="{{ route('hizmet',['slug' => 'insaat-projeleri']) }}" class="flex items-center p-3 bg-white hover:bg-gray-50 rounded-lg border border-gray-200 transition">
                                                    <span class="material-icons text-gray-500 mr-2">architecture</span>
                                                    <span class="text-sm">İnşaat Projeleri</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Duyurular -->
                    <div class="group relative h-full flex items-center">
                        <a href="#"
                            class="text-[#00352b] h-full px-3 font-semibold hover:text-gray-900 text-md flex items-center">
                            Duyurular
                            <span class="material-icons text-sm ml-1">expand_more</span>
                        </a>
                        <div class="mega-menu">
                            <div class="mega-menu-content mega-menu-kurumsal">
                                <div class="py-2 px-1">
                                    <div class="flex mb-2">
                                        <div class="w-full">
                                            <div class="grid grid-cols-3 gap-3">
                                                <!-- DUYURULAR -->
                                                <div class="mega-menu-category">
                                                    <h3>Belediye Duyuruları</h3>
                                                    <ul class="space-y-0.5">
                                                        <li>
                                                            <a href="#" class="mega-menu-link">
                                                                <span class="material-icons mega-menu-link-icon">announcement</span>
                                                                <span>Tüm Etkinlikler</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="mega-menu-link">
                                                                <span class="material-icons mega-menu-link-icon">flag</span>
                                                                <span>Askıdaki Planlar</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="mega-menu-link">
                                                                <span class="material-icons mega-menu-link-icon">gavel</span>
                                                                <span>Güncel Meclis Kararları</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="mega-menu-link">
                                                                <span class="material-icons mega-menu-link-icon">shopping_bag</span>
                                                                <span>İhaleler</span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>

                                                <!-- PLANLAR -->
                                                <div class="mega-menu-category">
                                                    <h3>Planlar ve Projeler</h3>
                                                    <ul class="space-y-0.5">
                                                        <li>
                                                            <a href="#" class="mega-menu-link">
                                                                <span class="material-icons mega-menu-link-icon">description</span>
                                                                <span>Yürürlükteki Stratejik Plan</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="mega-menu-link">
                                                                <span class="material-icons mega-menu-link-icon">construction</span>
                                                                <span>Fen İşleri Müdürlüğü Çalışma Planı</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="mega-menu-link">
                                                                <span class="material-icons mega-menu-link-icon">architecture</span>
                                                                <span>İmar Planları</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="mega-menu-link">
                                                                <span class="material-icons mega-menu-link-icon">apartment</span>
                                                                <span>Kentsel Dönüşüm Projeleri</span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>

                                                <!-- BASINA DUYURULAR -->
                                                <div class="mega-menu-category">
                                                    <h3>Basına Duyurular</h3>
                                                    <ul class="space-y-0.5">
                                                        <li>
                                                            <a href="#" class="mega-menu-link">
                                                                <span class="material-icons mega-menu-link-icon">campaign</span>
                                                                <span>Basın Bültenleri</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="mega-menu-link">
                                                                <span class="material-icons mega-menu-link-icon">newspaper</span>
                                                                <span>Haberler</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="mega-menu-link">
                                                                <span class="material-icons mega-menu-link-icon">event</span>
                                                                <span>Etkinlik Takvimi</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="mega-menu-link">
                                                                <span class="material-icons mega-menu-link-icon">photo_library</span>
                                                                <span>Fotoğraf Galerisi</span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-2 pt-2 border-t border-gray-200">
                                        <div class="flex justify-between items-center">
                                            <p class="text-xs text-gray-500">Belediyemizin güncel duyuruları ve etkinlikleri hakkında bilgi alın.</p>
                                            <a href="#" class="text-[#00352b] hover:text-[#007b32] text-sm font-medium flex items-center gap-1 transition-all hover:gap-2">
                                                Tüm Duyurular
                                                <span class="material-icons text-sm">arrow_forward</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ne Nerede -->
                    <div class="group relative h-full flex items-center">
                        <a href="#"
                            class="text-[#00352b] h-full px-3 font-semibold hover:text-gray-900 text-md flex items-center">
                            Ne Nerede
                            <span class="material-icons text-sm ml-1">expand_more</span>
                        </a>
                        <div class="mega-menu">
                            <div class="mega-menu-content mega-menu-kurumsal">
                                <div class="py-2 px-1">
                                    <div class="flex mb-2">
                                        <div class="w-full">
                                            <div class="grid grid-cols-3 gap-3">
                                                <!-- KAMU TESİSLERİ -->
                                                <div class="mega-menu-category">
                                                    <h3>Kamu Tesisleri</h3>
                                                    <ul class="space-y-0.5">
                                                        <li>
                                                            <a href="#" class="mega-menu-link">
                                                                <span class="material-icons mega-menu-link-icon">home</span>
                                                                <span>Çankaya Evlerimiz</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="mega-menu-link">
                                                                <span class="material-icons mega-menu-link-icon">policy</span>
                                                                <span>Zabıta Karakollarımız</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="mega-menu-link">
                                                                <span class="material-icons mega-menu-link-icon">location_city</span>
                                                                <span>Muhtarlıklar</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="mega-menu-link">
                                                                <span class="material-icons mega-menu-link-icon">wifi</span>
                                                                <span>Ücretsiz İnternet Alanlarımız</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="mega-menu-link">
                                                                <span class="material-icons mega-menu-link-icon">warning</span>
                                                                <span>Acil Toplanma Alanları</span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>

                                                <!-- EĞİTİM VE KÜLTÜR -->
                                                <div class="mega-menu-category">
                                                    <h3>Eğitim ve Kültür</h3>
                                                    <ul class="space-y-0.5">
                                                        <li>
                                                            <a href="#" class="mega-menu-link">
                                                                <span class="material-icons mega-menu-link-icon">child_care</span>
                                                                <span>Kreşlerimiz</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="mega-menu-link">
                                                                <span class="material-icons mega-menu-link-icon">menu_book</span>
                                                                <span>Kütüphanelerimiz</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="mega-menu-link">
                                                                <span class="material-icons mega-menu-link-icon">palette</span>
                                                                <span>Kültür ve Sanat Merkezlerimiz</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="mega-menu-link">
                                                                <span class="material-icons mega-menu-link-icon">school</span>
                                                                <span>Eğitim Merkezlerimiz</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="mega-menu-link">
                                                                <span class="material-icons mega-menu-link-icon">theater_comedy</span>
                                                                <span>Tiyatro ve Konser Alanlarımız</span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>

                                                <!-- SPOR VE ALTYAPI -->
                                                <div class="mega-menu-category">
                                                    <h3>Spor ve Altyapı</h3>
                                                    <ul class="space-y-0.5">
                                                        <li>
                                                            <a href="#" class="mega-menu-link">
                                                                <span class="material-icons mega-menu-link-icon">pool</span>
                                                                <span>Spor Tesisi ve Havuzlarımız</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="mega-menu-link">
                                                                <span class="material-icons mega-menu-link-icon">construction</span>
                                                                <span>Yol ve Kaldırım Çalışmalarımız</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="mega-menu-link">
                                                                <span class="material-icons mega-menu-link-icon">recycling</span>
                                                                <span>Geri Dönüşüm Merkezleri</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="mega-menu-link">
                                                                <span class="material-icons mega-menu-link-icon">map</span>
                                                                <span>Tüm Harita Katmanları</span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-2 pt-2 border-t border-gray-200">
                                        <div class="flex justify-between items-center">
                                            <p class="text-xs text-gray-500">Çankaya'daki tesislerimiz, hizmet noktalarımız ve altyapı çalışmalarımız hakkında bilgi alın.</p>
                                            <a href="#" class="text-[#00352b] hover:text-[#007b32] text-sm font-medium flex items-center gap-1 transition-all hover:gap-2">
                                                Haritada Göster
                                                <span class="material-icons text-sm">place</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- İletişim -->
                    <a href="#" class="text-[#00352b] font-semibold px-3 hover:text-[#007b32] text-md flex items-center h-full">İletişim</a>
                    <div class="h-4 w-px bg-gray-300 mx-1"></div>
                </div>
                
                <!-- Arama İkonu ve Slogan -->
                <div class="flex items-center space-x-4">
                    <button id="searchButton"   
                        class="w-10 h-10 bg-gray-50 rounded-lg flex items-center justify-center hover:text-white hover:bg-[#00352b] transition-colors">
                        <span class="material-icons hover:text-white group-hover:text-white">search</span>
                    </button>
                    <div class="text-md text-[#00352b] font-bold">
                        <img src="{{ asset('images/slogan.png') }}" alt="Çankaya Belediyesi Slogan" class="h-12">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobil Menü (Küçük ekranlarda görünür) -->
    <div class="md:hidden hidden w-full z-50 absolute bg-white shadow-lg" id="mobileMenu">
        <!-- Mobil Menü İçeriği -->
        <div class="px-4 py-2">
            <!-- Kurumsal -->
            <div class="group border-b border-gray-200">
                <a href="#" class="py-3 px-2 block text-gray-800 font-medium flex justify-between items-center">
                    Kurumsal
                    <span class="material-icons text-sm">expand_more</span>
                </a>
                <div class="quick-menu-dropdown hidden">
                    <!-- Mobil dropdown içeriği -->
                </div>
            </div>
            
            <!-- Diğer mobil menü öğeleri -->
            
        </div>
    </div>

    <style>
        /* Menü için aktif durum stili - yeşil çizgi */
        .active-nav, .group:hover > a {
            position: relative;
        }
        
        .active-nav::after, .group:hover > a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background-color: #007b32;
        }
        
        /* Mega menü stillerini düzenle */
        .mega-menu {
            top: 96px !important;
            z-index: 60 !important;
        }
        
        /* Header yüksekliği */
        .header-section {
            height: 100px !important; /* 96px + 4px (pt-1) */
            position: relative;
            z-index: 100;
        }
        
        .head {
            height: 96px !important;
        }
        
        /* Yeşil çizgi */
        .w-full.h-1.bg-\[\#007b32\] {
            position: relative;
            z-index: 100;
        }
    </style>
</section>

<!-- Yeşil sınır çizgisi - Navbar altı, slider üstü -->
<div class="w-full h-1 bg-[#007b32]" style="position: relative; z-index: 100;"></div> 