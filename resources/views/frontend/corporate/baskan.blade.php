@extends('layouts.front')

@section('title', 'Başkanımız')
@section('meta_description', 'Başkanımızın biyografisi, faaliyetleri ve duyuruları')

@section('content')
<!-- Hero Bölümü - Daha Geniş ve Etkili -->
<div class="relative bg-gradient-to-r from-[#00352b] to-[#20846c] overflow-hidden">
    <div class="absolute inset-0 opacity-20">
        <!-- Pattern overlay -->
        <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" preserveAspectRatio="none">
            <defs>
                <pattern id="hero-pattern" width="40" height="40" patternUnits="userSpaceOnUse">
                    <path d="M0 20 L40 20 M20 0 L20 40" stroke="currentColor" stroke-width="1" fill="none" />
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#hero-pattern)" />
        </svg>
    </div>
    
    <!-- Dekoratif şekiller -->
    <div class="absolute -right-20 -bottom-20 w-64 h-64 rounded-full bg-[#e6a23c]/10 blur-3xl"></div>
    <div class="absolute -left-10 top-10 w-40 h-40 rounded-full bg-white/5 blur-2xl"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16 relative z-10">
        <div class="flex flex-col md:flex-row items-center gap-6">
            <!-- Başkan Fotoğrafı -->
            <div class="w-36 h-36 md:w-44 md:h-44 rounded-full overflow-hidden border-4 border-white shadow-xl flex-shrink-0">
                <img src="https://cankaya.epoxsoft.net.tr/uploads/images/iyu1AIj1YVxWUezQXpVwYyco4PDjuOkHYVmtkPmH.webp" alt="Başkanımız Av. Hüseyin Can Güner" class="w-full h-full object-cover">
            </div>
            
            <div>
                <span class="inline-block px-3 py-0.5 bg-white/20 text-white text-xs rounded-full mb-2">Çankaya Belediyesi</span>
                <h1 class="text-2xl md:text-4xl font-bold text-white mb-1">Av. Hüseyin Can Güner</h1>
                <h2 class="text-white/90 text-lg md:text-xl mb-4">Belediye Başkanı</h2>
                
                <!-- Sosyal Medya Linkleri -->
                <div class="flex gap-2">
                    <a href="https://x.com/hcanguner" target="_blank" class="bg-white/10 hover:bg-white/20 text-white p-1.5 rounded-full transition-colors flex items-center justify-center w-8 h-8">
                        <i class="fab fa-twitter text-base"></i>
                    </a>
                    <a href="https://www.instagram.com/hcanguner/" target="_blank" class="bg-white/10 hover:bg-white/20 text-white p-1.5 rounded-full transition-colors flex items-center justify-center w-8 h-8">
                        <i class="fab fa-instagram text-base"></i>
                    </a>
                    <a href="mailto:huseyincanguner@cankaya.bel.tr" class="bg-white/10 hover:bg-white/20 text-white p-1.5 rounded-full transition-colors flex items-center justify-center w-8 h-8">
                        <span class="material-icons text-sm">email</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Basit Hikaye Çerçeveleri -->
<div class="py-8 bg-slate-50 shadow-sm">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="flex overflow-x-auto gap-6 pb-3 justify-between">
            <!-- Hikaye 1 -->
            <div class="hikaye-item flex-shrink-0 text-center" onclick="acLightbox(0)">
                <div class="w-20 h-20 rounded-full p-1 bg-gradient-to-r from-amber-500 to-green-500 mx-auto cursor-pointer">
                    <div class="w-full h-full rounded-full overflow-hidden border-2 border-white">
                        <img src="https://cankaya.epoxsoft.net.tr/images/huseyincanguner/huseyincanguner1.jpeg" alt="Projeler" class="w-full h-full object-cover">
                    </div>
                </div>
                <p class="text-xs font-medium mt-2 text-gray-700">Projeler</p>
            </div>
            
            <!-- Hikaye 2 -->
            <div class="hikaye-item flex-shrink-0 text-center" onclick="acLightbox(1)">
                <div class="w-20 h-20 rounded-full p-1 bg-gradient-to-r from-purple-500 to-pink-500 mx-auto cursor-pointer">
                    <div class="w-full h-full rounded-full overflow-hidden border-2 border-white">
                        <img src="https://cankaya.epoxsoft.net.tr/images/huseyincanguner/huseyincanguner2.jpeg" alt="Etkinlikler" class="w-full h-full object-cover">
                    </div>
                </div>
                <p class="text-xs font-medium mt-2 text-gray-700">Etkinlikler</p>
            </div>
            
            <!-- Hikaye 3 -->
            <div class="hikaye-item flex-shrink-0 text-center" onclick="acLightbox(2)">
                <div class="w-20 h-20 rounded-full p-1 bg-gradient-to-r from-blue-500 to-cyan-400 mx-auto cursor-pointer">
                    <div class="w-full h-full rounded-full overflow-hidden border-2 border-white">
                        <img src="https://cankaya.epoxsoft.net.tr/images/huseyincanguner/huseyincanguner3.jpeg" alt="Buluşmalar" class="w-full h-full object-cover">
                    </div>
                </div>
                <p class="text-xs font-medium mt-2 text-gray-700">Buluşmalar</p>
            </div>
            
            <!-- Hikaye 4 -->
            <div class="hikaye-item flex-shrink-0 text-center" onclick="acLightbox(3)">
                <div class="w-20 h-20 rounded-full p-1 bg-gradient-to-r from-green-500 to-emerald-400 mx-auto cursor-pointer">
                    <div class="w-full h-full rounded-full overflow-hidden border-2 border-white">
                        <img src="https://cankaya.epoxsoft.net.tr/images/huseyincanguner/huseyincanguner4.jpeg" alt="Kent Tarımı" class="w-full h-full object-cover">
                    </div>
                </div>
                <p class="text-xs font-medium mt-2 text-gray-700">Kent Tarımı</p>
            </div>
            
            <!-- Hikaye 5 -->
            <div class="hikaye-item flex-shrink-0 text-center" onclick="acLightbox(4)">
                <div class="w-20 h-20 rounded-full p-1 bg-gradient-to-r from-red-500 to-orange-400 mx-auto cursor-pointer">
                    <div class="w-full h-full rounded-full overflow-hidden border-2 border-white">
                        <img src="https://cankaya.epoxsoft.net.tr/images/huseyincanguner/huseyincanguner5.jpeg" alt="Eğitim" class="w-full h-full object-cover">
                    </div>
                </div>
                <p class="text-xs font-medium mt-2 text-gray-700">Eğitim</p>
            </div>
            
            <!-- Hikaye 6 -->
            <div class="hikaye-item flex-shrink-0 text-center" onclick="acLightbox(5)">
                <div class="w-20 h-20 rounded-full p-1 bg-gradient-to-r from-indigo-500 to-purple-500 mx-auto cursor-pointer">
                    <div class="w-full h-full rounded-full overflow-hidden border-2 border-white">
                        <img src="https://cankaya.epoxsoft.net.tr/images/huseyincanguner/huseyincanguner6.jpeg" alt="Spor" class="w-full h-full object-cover">
                    </div>
                </div>
                <p class="text-xs font-medium mt-2 text-gray-700">Spor</p>
            </div>
            
            <!-- Hikaye 7 -->
            <div class="hikaye-item flex-shrink-0 text-center" onclick="acLightbox(6)">
                <div class="w-20 h-20 rounded-full p-1 bg-gradient-to-r from-pink-500 to-rose-500 mx-auto cursor-pointer">
                    <div class="w-full h-full rounded-full overflow-hidden border-2 border-white">
                        <img src="https://cankaya.epoxsoft.net.tr/images/huseyincanguner/huseyincanguner7.jpeg" alt="Kültür" class="w-full h-full object-cover">
                    </div>
                </div>
                <p class="text-xs font-medium mt-2 text-gray-700">Kültür Sanat</p>
            </div>
            
            <!-- Hikaye 8 -->
            <div class="hikaye-item flex-shrink-0 text-center" onclick="acLightbox(7)">
                <div class="w-20 h-20 rounded-full p-1 bg-gradient-to-r from-blue-600 to-blue-400 mx-auto cursor-pointer">
                    <div class="w-full h-full rounded-full overflow-hidden border-2 border-white">
                        <img src="https://cankaya.epoxsoft.net.tr/images/huseyincanguner/huseyincanguner8.jpeg" alt="Dijital" class="w-full h-full object-cover">
                    </div>
                </div>
                <p class="text-xs font-medium mt-2 text-gray-700">Dijital</p>
            </div>
            
            <!-- Hikaye 9 (Yeni) -->
            <div class="hikaye-item flex-shrink-0 text-center" onclick="acLightbox(8)">
                <div class="w-20 h-20 rounded-full p-1 bg-gradient-to-r from-amber-600 to-amber-400 mx-auto cursor-pointer">
                    <div class="w-full h-full rounded-full overflow-hidden border-2 border-white">
                        <img src="https://cankaya.epoxsoft.net.tr/images/huseyincanguner/huseyincanguner9.jpeg" alt="Ulaşım" class="w-full h-full object-cover">
                    </div>
                </div>
                <p class="text-xs font-medium mt-2 text-gray-700">Ulaşım</p>
            </div>
            
            <!-- Hikaye 10 (Yeni) -->
            <div class="hikaye-item flex-shrink-0 text-center" onclick="acLightbox(9)">
                <div class="w-20 h-20 rounded-full p-1 bg-gradient-to-r from-teal-500 to-teal-300 mx-auto cursor-pointer">
                    <div class="w-full h-full rounded-full overflow-hidden border-2 border-white">
                        <img src="https://cankaya.epoxsoft.net.tr/images/huseyincanguner/huseyincanguner10.jpeg" alt="Çevre" class="w-full h-full object-cover">
                    </div>
                </div>
                <p class="text-xs font-medium mt-2 text-gray-700">Çevre</p>
            </div>
        </div>
    </div>
</div>

<!-- Basit Lightbox -->
<div id="lightbox" class="fixed inset-0 w-full h-full z-50 hidden bg-black/90" onclick="kapatLightbox()">
    <div class="flex items-center justify-center h-full">
        <div class="relative max-w-sm w-full bg-black rounded-xl overflow-hidden shadow-2xl" onclick="event.stopPropagation()">
            <!-- Görsel -->
            <div class="h-[600px] relative">
                <img id="lightboxImage" src="" alt="" class="w-full h-full object-cover">
                
                <!-- Başlık (üst overlay) -->
                <div class="absolute top-0 left-0 right-0 bg-gradient-to-b from-black/70 to-transparent p-4">
                    <h3 id="lightboxTitle" class="text-xl font-bold text-white"></h3>
                </div>
                
                <!-- Açıklama (alt overlay) -->
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-4">
                    <p id="lightboxText" class="text-white"></p>
                </div>
                
                <!-- Sol ok -->
                <button id="prevButton" class="absolute left-2 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-black/60 text-white w-8 h-8 rounded-full flex items-center justify-center" onclick="oncekiHikaye(event)">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                
                <!-- Sağ ok -->
                <button id="nextButton" class="absolute right-2 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-black/60 text-white w-8 h-8 rounded-full flex items-center justify-center" onclick="sonrakiHikaye(event)">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
            
            <!-- İlerleme barı (Instagram benzeri) -->
            <div id="progressBars" class="flex gap-1 p-2 bg-black">
                <!-- Progress bars buraya dinamik olarak eklenecek -->
            </div>
            
            <!-- Kapat Butonu -->
            <button class="absolute top-3 right-3 bg-black/40 hover:bg-black/60 text-white p-1.5 rounded-full" onclick="kapatLightbox()">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>
</div>

<!-- İçerik Bölümü -->
<section class="py-12 bg-slate-100">
    <div class="container max-w-7xl mx-auto px-4">
        <!-- Biyografi Kartı -->
        <div class="bg-white shadow-md rounded-xl overflow-hidden mb-8">
            <div class="px-6 py-4 border-b border-slate-100">
                <h2 class="text-2xl font-bold text-gray-800">Biyografi</h2>
            </div>
            
            <div class="p-6">
                <div class="prose prose-lg max-w-none prose-headings:text-gray-800 prose-p:text-gray-700 prose-a:text-[#00352b] prose-a:no-underline hover:prose-a:text-[#20846c] hover:prose-a:underline prose-img:rounded-lg">
                    <p>1993 yılında Ankara'da doğdu.</p>
                    
                    <p>Avukat, Ankara Üniversitesi Hukuk Fakültesi'nden 2015 yılında mezun oldu.</p>
                    
                    <p>Cumhuriyet Halk Partisi'ne 2012 yılında kaydoldu. Ankara İl Gençlik Kolları Üniversite Komisyonunda, Sosyal Demokrat Öğrenciler örgütlenmesinde ve Çankaya İlçe Gençlik Kolunda çalışmalar yürüttü.</p>
                    
                    <p>2013-2014 yıllarında Etimesgut Gençlik Kolu Başkanlığı görevinde bulundu. 2014-2016 yılları arasında Genel Merkez Yerel Yönetimler Komisyonunda görev aldı.</p>
                    
                    <p>2018-2020 yılları arasında CHP Çankaya İlçe Başkan Yardımcısı olarak görev yaptı. 2020-2023 yılları arasında CHP'yi temsilen Çankaya İlçe Seçim Kurulu üyeliğinde bulundu.</p>
                    
                    <p>14 Mayıs 2023 Milletvekili Genel Seçimlerinde Ankara 1. Bölge 11. Sıra Milletvekili Adayı oldu. 4-5 Kasım 2023 tarihinde gerçekleştirilen 38. Olağan Kurultay'da CHP Parti Meclisi Üyesi seçildi.</p>
                    
                    <p>İkinci Yüzyıl Dergisi Yayın Kurulu Üyeliği ve Türkiye Tenis Federasyonu Disiplin Kurulu Üyeliği görevlerinde bulundu.</p>
                    
                    <p>Sosyal Demokrat Belediyeler Derneği'nin örgütlenme ve gelişiminde ve Sosyal Demokrat Kamu İşverenleri Sendikası'nın kuruluşunda görev aldı. 2021-2023 yılları arasında SODEMSEN Hukuk Müşavirliği görevinde bulundu. 2024 yılından itibaren SODEMSEN Başkanlığı görevini yürütmektedir.</p>
                    
                    <p>Sosyal Demokrasi Derneği ve Sosyal Demokrat Avukatlar Derneği üyesidir.</p>
                </div>
            </div>
        </div>
        
        <!-- Gündem ve Etkinlikler Kartı -->
        <div class="bg-white shadow-md rounded-xl overflow-hidden mb-8">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-800">Başkanın Gündemi</h2>
                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Güncel</span>
            </div>
            
            <div class="p-6">
                <div class="divide-y divide-gray-100">
                    <!-- Gündem Öğesi 1 -->
                    <div class="py-4 first:pt-0 last:pb-0 hover:bg-slate-50 transition-colors rounded-lg p-2 -mx-2">
                        <div class="flex items-center mb-2">
                            <span class="bg-[#00352b] text-white text-sm px-3 py-1 rounded-full mr-3">24 Haziran 2024</span>
                            <span class="text-sm text-gray-500">10:00</span>
                            <span class="ml-auto px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">Toplantı</span>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Belediye Meclis Toplantısı</h3>
                        <p class="text-gray-600">Çankaya Belediyesi meclis toplantısında başkanlık yapacak ve bütçe görüşmelerini yönetecek.</p>
                        <div class="flex items-center mt-3 text-sm">
                            <span class="flex items-center text-gray-500 mr-4">
                                <span class="material-icons text-xs mr-1">location_on</span>
                                Çankaya Belediyesi Meclis Salonu
                            </span>
                            <span class="flex items-center text-gray-500">
                                <span class="material-icons text-xs mr-1">people</span>
                                Meclis Üyeleri
                            </span>
                        </div>
                    </div>
                    
                    <!-- Gündem Öğesi 2 -->
                    <div class="py-4 first:pt-0 last:pb-0 hover:bg-slate-50 transition-colors rounded-lg p-2 -mx-2">
                        <div class="flex items-center mb-2">
                            <span class="bg-[#00352b] text-white text-sm px-3 py-1 rounded-full mr-3">25 Haziran 2024</span>
                            <span class="text-sm text-gray-500">14:30</span>
                            <span class="ml-auto px-2 py-1 bg-amber-100 text-amber-800 text-xs rounded-full">Açılış</span>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Kent Tarımı Projesi Açılışı</h3>
                        <p class="text-gray-600">Çankaya Belediyesi'nin yürüttüğü Kent Tarımı Projesi'nin açılış törenine katılacak ve konuşma yapacak.</p>
                        <div class="flex items-center mt-3 text-sm">
                            <span class="flex items-center text-gray-500 mr-4">
                                <span class="material-icons text-xs mr-1">location_on</span>
                                Çankaya Kent Tarım Alanı
                            </span>
                            <span class="flex items-center text-gray-500">
                                <span class="material-icons text-xs mr-1">people</span>
                                Halka Açık
                            </span>
                        </div>
                    </div>
                    
                    <!-- Gündem Öğesi 3 -->
                    <div class="py-4 first:pt-0 last:pb-0 hover:bg-slate-50 transition-colors rounded-lg p-2 -mx-2">
                        <div class="flex items-center mb-2">
                            <span class="bg-[#00352b] text-white text-sm px-3 py-1 rounded-full mr-3">27 Haziran 2024</span>
                            <span class="text-sm text-gray-500">09:00</span>
                            <span class="ml-auto px-2 py-1 bg-purple-100 text-purple-800 text-xs rounded-full">Ziyaret</span>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Mahalle Ziyaretleri</h3>
                        <p class="text-gray-600">Çankaya'daki mahalleleri ziyaret ederek vatandaşların sorunlarını dinleyecek ve çözüm önerileri sunacak.</p>
                        <div class="flex items-center mt-3 text-sm">
                            <span class="flex items-center text-gray-500 mr-4">
                                <span class="material-icons text-xs mr-1">location_on</span>
                                Çukurambar ve 100.Yıl Mahalleleri
                            </span>
                            <span class="flex items-center text-gray-500">
                                <span class="material-icons text-xs mr-1">people</span>
                                Mahalle Sakinleri
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="px-6 py-4 bg-gray-50 flex justify-between items-center">
                <div class="flex items-center text-sm text-gray-500">
                    <span class="material-icons text-base mr-1">calendar_today</span>
                    <span>Haziran 2024</span>
                </div>
                <a href="#" class="inline-flex items-center text-[#00352b] hover:text-[#20846c] font-medium transition-colors">
                    <span>Tüm Etkinlikler</span>
                    <span class="material-icons ml-1">arrow_forward</span>
                </a>
            </div>
        </div>
        
        <!-- Başkandan Mesajlar Kartı -->
        <div class="bg-white shadow-md rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-800">Başkandan Mesajlar</h2>
                <span class="text-xs font-medium text-gray-500">Son güncelleme: 20 Haziran 2024</span>
            </div>
            
            <div class="p-6">
                <div class="relative">
                    <div class="absolute top-0 left-0 h-full w-1 bg-gradient-to-b from-[#00352b] to-[#20846c] rounded-full"></div>
                    <div class="pl-6 prose prose-lg max-w-none prose-headings:text-gray-800 prose-p:text-gray-700">
                        <blockquote class="italic text-gray-700 bg-slate-50 p-6 rounded-lg shadow-sm border-l-4 border-[#00352b]">
                            "Çankaya'mızı örnek bir belediye yapmak için çalışıyoruz. Katılımcı, şeffaf ve hesap verebilir bir yönetim anlayışıyla, sosyal demokrat değerleri yerel yönetimde uygulamaya çalışıyoruz. Amacımız Çankaya'da yaşayan herkesin yaşam kalitesini artırmak, eşit hizmet götürmek ve sürdürülebilir projelerle geleceğe yatırım yapmaktır. Tarım alanındaki çalışmalarımız, kent kimliğine katkı sağlayan projelerimiz ve sosyal dayanışma programlarımızla fark yaratmaya devam edeceğiz."
                        </blockquote>
                    </div>
                </div>
                
                <div class="flex items-center mt-6 ml-6">
                    <img src="https://cankaya.epoxsoft.net.tr/images/huseyincanguner/huseyincanguner6.jpeg" alt="Av. Hüseyin Can Güner" class="w-12 h-12 rounded-full mr-4 border-2 border-[#00352b]">
                    <div>
                        <p class="font-bold text-gray-900">Av. Hüseyin Can Güner</p>
                        <p class="text-gray-600 text-sm">Çankaya Belediye Başkanı</p>
                    </div>
                </div>
                
                <div class="mt-6 ml-6 pt-6 border-t border-slate-100">
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Öne Çıkan Değerlerimiz</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex items-start">
                            <span class="flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-full bg-green-100 text-green-800 mr-3">
                                <span class="material-icons text-sm">eco</span>
                            </span>
                            <span class="text-gray-700">Sürdürülebilirlik</span>
                        </div>
                        <div class="flex items-start">
                            <span class="flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-800 mr-3">
                                <span class="material-icons text-sm">people</span>
                            </span>
                            <span class="text-gray-700">Katılımcılık</span>
                        </div>
                        <div class="flex items-start">
                            <span class="flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-full bg-purple-100 text-purple-800 mr-3">
                                <span class="material-icons text-sm">balance</span>
                            </span>
                            <span class="text-gray-700">Sosyal Adalet</span>
                        </div>
                        <div class="flex items-start">
                            <span class="flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-full bg-amber-100 text-amber-800 mr-3">
                                <span class="material-icons text-sm">visibility</span>
                            </span>
                            <span class="text-gray-700">Şeffaflık</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Başkandan Haberler ve Fotoğraf Galerisi Bölümü -->
<section class="py-12 bg-white">
    <div class="container max-w-7xl mx-auto px-4">
        <!-- Başkandan Haberler -->
        <div class="mb-12">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-3xl font-bold text-gray-800 flex items-center gap-2">
                    <i class="material-icons text-[#00352b]">newspaper</i>
                    Başkandan Haberler
                </h2>
                <a href="#" class="inline-flex items-center text-[#00352b] hover:text-[#20846c] font-medium transition-colors">
                    <span>Tüm Haberler</span>
                    <i class="material-icons text-sm ml-1">arrow_forward</i>
                </a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Haber Kartı 1 -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <div class="aspect-video overflow-hidden">
                        <img src="https://cankaya.epoxsoft.net.tr/images/huseyincanguner/huseyincanguner1.jpeg" alt="Başkan Mahalle Ziyaretinde" class="w-full h-full object-cover transition-transform duration-500 hover:scale-105">
                    </div>
                    <div class="p-5">
                        <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full mb-3">Ziyaret</span>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2 line-clamp-2">Başkan Güner, Çukurambar Mahallesi'nde Vatandaşlarla Buluştu</h3>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">Başkan Güner, Çukurambar Mahallesi'nde vatandaşları ziyaret ederek talep ve önerilerini dinledi.</p>
                        <div class="flex justify-between items-center pt-3 border-t border-gray-100">
                            <div class="flex items-center text-gray-500 text-xs">
                                <i class="material-icons text-xs mr-1">calendar_today</i>
                                <span>18 Haziran 2024</span>
                            </div>
                            <a href="#" class="inline-flex items-center text-xs font-medium text-white bg-[#00352b] px-3 py-1.5 rounded-md hover:bg-[#20846c] transition-colors">
                                <span>Detaylar</span>
                                <i class="material-icons text-xs ml-1">arrow_forward</i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Haber Kartı 2 -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <div class="aspect-video overflow-hidden">
                        <img src="https://cankaya.epoxsoft.net.tr/images/huseyincanguner/huseyincanguner2.jpeg" alt="Başkan Toplantıda" class="w-full h-full object-cover transition-transform duration-500 hover:scale-105">
                    </div>
                    <div class="p-5">
                        <span class="inline-block px-3 py-1 bg-purple-100 text-purple-800 text-xs font-medium rounded-full mb-3">Toplantı</span>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2 line-clamp-2">Belediye Meclis Toplantısında Önemli Kararlar Alındı</h3>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">Çankaya Belediye Meclisi'nin Haziran ayı toplantısında kentin geleceğini ilgilendiren önemli kararlar alındı.</p>
                        <div class="flex justify-between items-center pt-3 border-t border-gray-100">
                            <div class="flex items-center text-gray-500 text-xs">
                                <i class="material-icons text-xs mr-1">calendar_today</i>
                                <span>15 Haziran 2024</span>
                            </div>
                            <a href="#" class="inline-flex items-center text-xs font-medium text-white bg-[#00352b] px-3 py-1.5 rounded-md hover:bg-[#20846c] transition-colors">
                                <span>Detaylar</span>
                                <i class="material-icons text-xs ml-1">arrow_forward</i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Haber Kartı 3 -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <div class="aspect-video overflow-hidden">
                        <img src="https://cankaya.epoxsoft.net.tr/images/huseyincanguner/huseyincanguner3.jpeg" alt="Başkan Projede" class="w-full h-full object-cover transition-transform duration-500 hover:scale-105">
                    </div>
                    <div class="p-5">
                        <span class="inline-block px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full mb-3">Proje</span>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2 line-clamp-2">Kent Tarımı Projesi'nin İlk Etabı Tamamlandı</h3>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">Sürdürülebilir tarım ve ekolojik dengenin korunması için başlatılan Kent Tarımı Projesi'nin ilk etabı tamamlandı.</p>
                        <div class="flex justify-between items-center pt-3 border-t border-gray-100">
                            <div class="flex items-center text-gray-500 text-xs">
                                <i class="material-icons text-xs mr-1">calendar_today</i>
                                <span>10 Haziran 2024</span>
                            </div>
                            <a href="#" class="inline-flex items-center text-xs font-medium text-white bg-[#00352b] px-3 py-1.5 rounded-md hover:bg-[#20846c] transition-colors">
                                <span>Detaylar</span>
                                <i class="material-icons text-xs ml-1">arrow_forward</i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Haber Kartı 4 -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <div class="aspect-video overflow-hidden">
                        <img src="https://cankaya.epoxsoft.net.tr/images/huseyincanguner/huseyincanguner4.jpeg" alt="Başkan Konuşma Yaparken" class="w-full h-full object-cover transition-transform duration-500 hover:scale-105">
                    </div>
                    <div class="p-5">
                        <span class="inline-block px-3 py-1 bg-amber-100 text-amber-800 text-xs font-medium rounded-full mb-3">Etkinlik</span>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2 line-clamp-2">Çankaya Festivali Büyük İlgi Gördü</h3>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">Çankaya Belediyesi tarafından düzenlenen festival, binlerce vatandaşın katılımıyla gerçekleşti.</p>
                        <div class="flex justify-between items-center pt-3 border-t border-gray-100">
                            <div class="flex items-center text-gray-500 text-xs">
                                <i class="material-icons text-xs mr-1">calendar_today</i>
                                <span>5 Haziran 2024</span>
                            </div>
                            <a href="#" class="inline-flex items-center text-xs font-medium text-white bg-[#00352b] px-3 py-1.5 rounded-md hover:bg-[#20846c] transition-colors">
                                <span>Detaylar</span>
                                <i class="material-icons text-xs ml-1">arrow_forward</i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Fotoğraf Galerisi -->
        <div>
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800">Fotoğraf Galerisi</h2>
                <a href="#" class="inline-flex items-center text-[#00352b] hover:text-[#20846c] font-medium transition-colors">
                    <span>Tüm Fotoğraflar</span>
                    <span class="material-icons ml-1">arrow_forward</span>
                </a>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <!-- Fotoğraf 1 -->
                <div class="relative overflow-hidden rounded-lg group h-48 md:h-64">
                    <img src="https://cankaya.epoxsoft.net.tr/images/huseyincanguner/huseyincanguner5.jpeg" alt="Galeri Fotoğrafı 1" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-50 transition-opacity"></div>
                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <span class="material-icons text-white text-3xl">zoom_in</span>
                    </div>
                </div>
                
                <!-- Fotoğraf 2 -->
                <div class="relative overflow-hidden rounded-lg group h-48 md:h-64">
                    <img src="https://cankaya.epoxsoft.net.tr/images/huseyincanguner/huseyincanguner6.jpeg" alt="Galeri Fotoğrafı 2" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-50 transition-opacity"></div>
                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <span class="material-icons text-white text-3xl">zoom_in</span>
                    </div>
                </div>
                
                <!-- Fotoğraf 3 -->
                <div class="relative overflow-hidden rounded-lg group h-48 md:h-64">
                    <img src="https://cankaya.epoxsoft.net.tr/images/huseyincanguner/huseyincanguner7.jpeg" alt="Galeri Fotoğrafı 3" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-50 transition-opacity"></div>
                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <span class="material-icons text-white text-3xl">zoom_in</span>
                    </div>
                </div>
                
                <!-- Fotoğraf 4 -->
                <div class="relative overflow-hidden rounded-lg group h-48 md:h-64">
                    <img src="https://cankaya.epoxsoft.net.tr/images/huseyincanguner/huseyincanguner8.jpeg" alt="Galeri Fotoğrafı 4" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-50 transition-opacity"></div>
                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <span class="material-icons text-white text-3xl">zoom_in</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

<script>
    // Hikaye verileri
    const hikayeler = [
        {
            baslik: "Belediye Projeleri",
            metin: "Başkan Güner, sürdürülebilir belediyecilik anlayışıyla yeni projeleri hayata geçiriyor.",
            resim: "https://cankaya.epoxsoft.net.tr/images/huseyincanguner/huseyincanguner1.jpeg"
        },
        {
            baslik: "Etkinlikler",
            metin: "Çankaya'da kültürel ve sanatsal etkinliklerle dolu bir yaz sezonu başlıyor.",
            resim: "https://cankaya.epoxsoft.net.tr/images/huseyincanguner/huseyincanguner2.jpeg"
        },
        {
            baslik: "Mahalle Buluşmaları",
            metin: "Başkan Güner, mahalle sakinleriyle bir araya geliyor, sorunları yerinde tespit ediyor.",
            resim: "https://cankaya.epoxsoft.net.tr/images/huseyincanguner/huseyincanguner3.jpeg"
        },
        {
            baslik: "Kent Tarımı",
            metin: "Çankaya'da kent tarımı projeleriyle organik tarım yaygınlaşıyor, yerel üretim destekleniyor.",
            resim: "https://cankaya.epoxsoft.net.tr/images/huseyincanguner/huseyincanguner4.jpeg"
        },
        {
            baslik: "Eğitim Destekleri",
            metin: "Gençlere ve çocuklara yönelik eğitim destekleri artarak devam ediyor.",
            resim: "https://cankaya.epoxsoft.net.tr/images/huseyincanguner/huseyincanguner5.jpeg"
        },
        {
            baslik: "Spor Faaliyetleri",
            metin: "Çankaya'da spor tesisleri ve etkinliklerle her yaştan vatandaşlar spora teşvik ediliyor.",
            resim: "https://cankaya.epoxsoft.net.tr/images/huseyincanguner/huseyincanguner6.jpeg"
        },
        {
            baslik: "Kültür Sanat",
            metin: "Çankaya'da kültür sanat etkinlikleri tüm hızıyla devam ediyor.",
            resim: "https://cankaya.epoxsoft.net.tr/images/huseyincanguner/huseyincanguner7.jpeg"
        },
        {
            baslik: "Dijital Hizmetler",
            metin: "Çankaya Belediyesi dijital dönüşüm ile hizmetlerini daha erişilebilir hale getiriyor.",
            resim: "https://cankaya.epoxsoft.net.tr/images/huseyincanguner/huseyincanguner8.jpeg"
        },
        {
            baslik: "Ulaşım Projeleri",
            metin: "Çankaya'da ulaşım altyapısı yenileniyor, daha sürdürülebilir ulaşım çözümleri sunuluyor.",
            resim: "https://cankaya.epoxsoft.net.tr/images/huseyincanguner/huseyincanguner9.jpeg"
        },
        {
            baslik: "Çevre Dönüşümü",
            metin: "Çevre dostu projelerle Çankaya daha yeşil bir ilçe haline geliyor.",
            resim: "https://cankaya.epoxsoft.net.tr/images/huseyincanguner/huseyincanguner10.jpeg"
        }
    ];
    
    let aktifHikayeIndex = 0;
    
    // Lightbox fonksiyonları
    function acLightbox(index) {
        aktifHikayeIndex = index;
        hikayeGoster(index);
        
        document.getElementById('lightbox').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // İlerleme çubuklarını oluştur
        olusturIlerlemeCubugu();
    }
    
    function kapatLightbox() {
        document.getElementById('lightbox').classList.add('hidden');
        document.body.style.overflow = '';
    }
    
    function hikayeGoster(index) {
        const hikaye = hikayeler[index];
        document.getElementById('lightboxTitle').textContent = hikaye.baslik;
        document.getElementById('lightboxText').textContent = hikaye.metin;
        document.getElementById('lightboxImage').src = hikaye.resim;
        
        // İlerleme çubuğunu güncelle
        guncelleIlerlemeCubugu(index);
    }
    
    function sonrakiHikaye(event) {
        event.stopPropagation();
        aktifHikayeIndex = (aktifHikayeIndex + 1) % hikayeler.length;
        hikayeGoster(aktifHikayeIndex);
    }
    
    function oncekiHikaye(event) {
        event.stopPropagation();
        aktifHikayeIndex = (aktifHikayeIndex - 1 + hikayeler.length) % hikayeler.length;
        hikayeGoster(aktifHikayeIndex);
    }
    
    function olusturIlerlemeCubugu() {
        const progressBarsContainer = document.getElementById('progressBars');
        progressBarsContainer.innerHTML = '';
        
        for (let i = 0; i < hikayeler.length; i++) {
            const progressBar = document.createElement('div');
            progressBar.className = `h-1 rounded-full flex-1 ${i === aktifHikayeIndex ? 'bg-white/80' : 'bg-white/20'}`;
            progressBar.id = `progressBar-${i}`;
            progressBarsContainer.appendChild(progressBar);
        }
    }
    
    function guncelleIlerlemeCubugu(activeIndex) {
        for (let i = 0; i < hikayeler.length; i++) {
            const progressBar = document.getElementById(`progressBar-${i}`);
            if (progressBar) {
                if (i === activeIndex) {
                    progressBar.className = 'h-1 bg-white/80 rounded-full flex-1';
                } else {
                    progressBar.className = 'h-1 bg-white/20 rounded-full flex-1';
                }
            }
        }
    }
    
    // ESC tuşuna basınca kapat
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            kapatLightbox();
        } else if (event.key === 'ArrowRight') {
            if (!document.getElementById('lightbox').classList.contains('hidden')) {
                sonrakiHikaye(event);
            }
        } else if (event.key === 'ArrowLeft') {
            if (!document.getElementById('lightbox').classList.contains('hidden')) {
                oncekiHikaye(event);
            }
        }
    });
    
    // Dokunmatik kaydırma desteği
    let touchStartX = 0;
    let touchEndX = 0;
    
    document.getElementById('lightbox').addEventListener('touchstart', function(e) {
        touchStartX = e.changedTouches[0].screenX;
    }, false);
    
    document.getElementById('lightbox').addEventListener('touchend', function(e) {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    }, false);
    
    function handleSwipe() {
        if (touchEndX < touchStartX) {
            // Sola kaydırma
            sonrakiHikaye(new Event('touch'));
        } else if (touchEndX > touchStartX) {
            // Sağa kaydırma
            oncekiHikaye(new Event('touch'));
        }
    }
</script> 