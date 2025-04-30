@extends('layouts.front')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Başlık Alanı -->
    <div class="bg-gradient-to-r from-primary to-primary/80 text-white py-12">
        <div class="container mx-auto px-4">
            <h1 class="text-3xl md:text-4xl font-bold text-center">İletişim Bilgileri</h1>
        </div>
    </div>

    <!-- İletişim Bilgileri -->
    <div class="container mx-auto px-4 -mt-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Adres Kartı -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="text-center mb-4">
                    <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="h-8 w-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Adres</h3>
                </div>
                <div class="text-center text-gray-600">
                    <p class="leading-relaxed">
                        Ziya Gökalp Caddesi No: 11<br>
                        Kızılay/Ankara
                    </p>
                </div>
            </div>

            <!-- İletişim Merkezi Kartı -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="text-center mb-4">
                    <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="h-8 w-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">İletişim Merkezi</h3>
                </div>
                <div class="space-y-2 text-center">
                    <div class="text-gray-600">
                        <span class="font-semibold text-primary text-xl">444 06 01</span>
                        <p class="text-sm text-gray-500 mt-1">Çağrı Merkezi</p>
                    </div>
                    <div class="text-gray-600">
                        <span class="font-medium">0(505) 167 19 67</span>
                        <p class="text-sm text-gray-500 mt-1">WhatsApp Hattı</p>
                    </div>
                    <div class="text-gray-600">
                        <span class="font-medium">(0 312) 458 89 00</span>
                        <p class="text-sm text-gray-500 mt-1">Santral</p>
                    </div>
                </div>
            </div>

            <!-- E-posta Kartı -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="text-center mb-4">
                    <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="h-8 w-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">E-posta</h3>
                </div>
                <div class="space-y-4 text-center">
                    <div>
                        <a href="mailto:iletisimmerkezi@cankaya.bel.tr" 
                           class="text-primary hover:text-primary/80 transition-colors break-all">
                            iletisimmerkezi@cankaya.bel.tr
                        </a>
                        <p class="text-sm text-gray-500 mt-1">E-posta Adresi</p>
                    </div>
                    <div>
                        <a href="mailto:cankayabelediyesi@hs01.kep.tr" 
                           class="text-primary hover:text-primary/80 transition-colors break-all">
                            cankayabelediyesi@hs01.kep.tr
                        </a>
                        <p class="text-sm text-gray-500 mt-1">KEP Adresi</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Harita -->
    <div class="container mx-auto px-4 py-12">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="aspect-w-21 aspect-h-9">
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