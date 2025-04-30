@extends('layouts.front')

@section('content')
<!-- Hero Bölümü - Küçültülmüş ve Kompakt -->
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
    <div class="absolute -right-20 -bottom-20 w-32 h-32 rounded-full bg-[#e6a23c]/10 blur-2xl"></div>
    <div class="absolute -left-10 top-10 w-20 h-20 rounded-full bg-white/5 blur-xl"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 relative z-10">
        <div class="flex items-center gap-4 text-center sm:text-left sm:flex-row">
            <!-- İkon -->
            <div class="w-14 h-14 rounded-full overflow-hidden bg-white/10 flex items-center justify-center border-2 border-white/30 hidden sm:flex">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            
            <div>
                <span class="inline-block px-3 py-0.5 bg-white/20 text-white text-xs rounded-full mb-1">Çankaya Belediyesi</span>
                <h1 class="text-xl md:text-3xl font-bold text-white">İletişim Bilgileri</h1>
            </div>
        </div>
    </div>
</div>

<!-- İletişim Bilgileri Bölümü -->
<div class="bg-white py-8">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 -mt-8 relative z-10">
            <!-- Adres Kutusu -->
            <div class="bg-white rounded-xl shadow-lg p-6 flex flex-col items-center text-center hover:shadow-xl transition-shadow duration-300">
                <div class="bg-primary/10 rounded-full p-4 mb-4">
                    <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-3">Adres Bilgilerimiz</h3>
                <p class="text-gray-600 mb-1">Ziya Gökalp Caddesi</p>
                <p class="text-gray-600 mb-1">No: 11</p>
                <p class="text-gray-600">Kızılay/Ankara</p>
            </div>

            <!-- Telefon Kutusu -->
            <div class="bg-white rounded-xl shadow-lg p-6 flex flex-col items-center text-center hover:shadow-xl transition-shadow duration-300">
                <div class="bg-primary/10 rounded-full p-4 mb-4">
                    <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-3">Telefon Numaralarımız</h3>
                <p class="text-primary text-xl font-bold mb-2">444 06 01</p>
                <p class="text-gray-500 text-sm">Çağrı Merkezi</p>
                
                <div class="w-12 h-[1px] bg-gray-200 my-3"></div>
                
                <p class="text-gray-600 mb-1">WhatsApp Hattı:</p>
                <p class="text-gray-700 font-semibold mb-2">0(505) 167 19 67</p>
                
                <p class="text-gray-600 mb-1">Santral:</p>
                <p class="text-gray-700 font-semibold">(0 312) 458 89 00</p>
            </div>

            <!-- E-posta Kutusu -->
            <div class="bg-white rounded-xl shadow-lg p-6 flex flex-col items-center text-center hover:shadow-xl transition-shadow duration-300">
                <div class="bg-primary/10 rounded-full p-4 mb-4">
                    <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-3">E-posta Adreslerimiz</h3>
                <a href="mailto:iletisimmerkezi@cankaya.bel.tr" class="text-primary hover:underline text-base mb-1">
                    iletisimmerkezi@cankaya.bel.tr
                </a>
                <p class="text-gray-500 text-sm mb-3">E-posta Adresi</p>
                
                <div class="w-12 h-[1px] bg-gray-200 my-2"></div>
                
                <a href="mailto:cankayabelediyesi@hs01.kep.tr" class="text-primary hover:underline text-base mt-1">
                    cankayabelediyesi@hs01.kep.tr
                </a>
                <p class="text-gray-500 text-sm">KEP Adresi</p>
            </div>
        </div>
    </div>
</div>

<!-- Harita Bölümü -->
<div class="bg-gray-50 py-8">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-3">Bizi Ziyaret Edin</h2>
            <div class="w-24 h-1 bg-primary mx-auto"></div>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="aspect-w-16 aspect-h-7">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d6119.7682733055735!2d32.856083!3d39.921609!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14d34e53ca5fd1d1%3A0xc4fb4eb09f3cf975!2zw4dhbmtheWEgQmVsZWRpeWUgQmHFn2thbmzEscSfxLE!5e0!3m2!1str!2sus!4v1746007365239!5m2!1str!2sus"
                    class="w-full h-full"
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </div>
</div>
@endsection 