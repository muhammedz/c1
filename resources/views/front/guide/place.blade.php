@extends('layouts.front')

@section('title', $guidePlace->title . ' - ' . $category->name . ' - ' . $pageTitle)
@section('meta_description', $guidePlace->meta_description ?: $guidePlace->excerpt)

@section('css')
<style>
    .gallery-thumb.active {
        border-color: #004d2e !important;
        transform: scale(1.05);
    }
    
    .contact-item {
        padding: 1rem;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        margin-bottom: 1rem;
        background: white;
        transition: all 0.2s ease;
    }
    
    .contact-item:hover {
        border-color: #004d2e;
        box-shadow: 0 2px 8px rgba(0, 77, 46, 0.1);
    }
    
    .contact-item.border-dashed {
        border-style: dashed;
        border-color: #d1d5db;
    }
    
    .contact-item.border-dashed:hover {
        border-color: #9ca3af;
        box-shadow: none;
    }
    
    .contact-label {
        font-weight: 600;
        color: #374151;
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
    }
    
    .contact-value {
        color: #4b5563;
        font-size: 0.875rem;
        line-height: 1.5;
    }
    
    .phone-link {
        color: #004d2e;
        text-decoration: none;
        font-weight: 500;
    }
    
    .phone-link:hover {
        text-decoration: underline;
    }
</style>
@endsection

@section('content')
<!-- Hero Bölümü -->
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
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-10 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
            <div class="md:col-span-2">
                <div class="inline-block bg-white/10 backdrop-blur-sm px-4 py-1 rounded-full text-white/90 text-sm mb-3 border border-white/10">
                    <span class="material-icons text-xs mr-1">place</span>
                    <span>{{ $category->name }}</span>
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">{{ $guidePlace->title }}</h1>
                @if($guidePlace->address)
                    <p class="text-white/80 text-lg mb-5">{{ $guidePlace->address }}</p>
                @else
                    <p class="text-white/80 text-lg mb-5">Bu lokasyon ile ilgili tüm iletişim bilgilerini ve detayları aşağıda bulabilirsiniz.</p>
                @endif
                
                <div class="flex flex-wrap gap-4">
                    @if($guidePlace->phone)
                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $guidePlace->phone) }}" class="inline-flex items-center px-5 py-2.5 bg-[#e6a23c] text-white rounded-md hover:bg-[#e6a23c]/90 transition-colors font-medium shadow-lg shadow-[#e6a23c]/20">
                            <span class="material-icons mr-2 text-sm">phone</span>
                            Ara
                        </a>
                    @endif
                    @if($guidePlace->maps_link)
                        <a href="{{ $guidePlace->maps_link }}" target="_blank" class="inline-flex items-center px-5 py-2.5 bg-white/10 text-white border border-white/20 rounded-md hover:bg-white/20 transition-colors shadow-lg shadow-black/5">
                            <span class="material-icons mr-2 text-sm">map</span>
                            Haritada Göster
                        </a>
                    @endif
                    <a href="{{ route('guide.category', $category->slug) }}" class="inline-flex items-center px-5 py-2.5 bg-white/10 text-white border border-white/20 rounded-md hover:bg-white/20 transition-colors shadow-lg shadow-black/5">
                        <span class="material-icons mr-2 text-sm">arrow_back</span>
                        {{ $category->name }} Listesi
                    </a>
                </div>
            </div>
            <div class="hidden md:flex justify-end">
                <div class="bg-white/10 backdrop-blur-sm p-6 rounded-lg border border-white/20 shadow-lg">
                    <div class="text-white text-center">
                        @if($guidePlace->phone || $guidePlace->email || $guidePlace->website)
                            <span class="material-icons text-4xl text-[#e6a23c] mb-2">check_circle</span>
                            <h3 class="text-xl font-semibold mb-2">İletişim Mevcut</h3>
                            <p class="text-sm text-white/80">Bu mekan için iletişim bilgileri mevcuttur.</p>
                        @else
                            <span class="material-icons text-4xl text-yellow-400 mb-2">schedule</span>
                            <h3 class="text-xl font-semibold mb-2">Bilgiler Eklenecek</h3>
                            <p class="text-sm text-white/80">İletişim bilgileri yakında eklenecektir.</p>
                        @endif
                        <div class="mt-4 pt-4 border-t border-white/10">
                            <div class="flex items-center justify-center text-[#e6a23c]">
                                <span class="material-icons mr-2">phone</span>
                                <span class="font-bold">444 06 01</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Ana İçerik -->
<div class="py-8 bg-white">
    <div class="max-w-6xl mx-auto px-4">
        <!-- Fotoğraf Galerisi -->
        @if($guidePlace->images->count() > 0)
            <div class="mb-8">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Fotoğraflar</h2>
                
                <!-- Ana Resim -->
                <div class="rounded-lg overflow-hidden mb-4 shadow-lg">
                    <img id="mainImage" 
                         src="{{ $guidePlace->featured_image_url ?: $guidePlace->images->first()->image_url }}" 
                         alt="{{ $guidePlace->title }}"
                         class="w-full h-80 object-cover">
                </div>
                
                <!-- Küçük Resimler -->
                @if($guidePlace->images->count() > 1)
                    <div class="grid grid-cols-4 md:grid-cols-6 gap-2">
                        @foreach($guidePlace->images as $index => $image)
                            <div class="gallery-thumb {{ $index === 0 ? 'active' : '' }} rounded-lg overflow-hidden cursor-pointer transition-all duration-300 hover:scale-105 border-2 {{ $index === 0 ? 'border-[#004d2e]' : 'border-transparent hover:border-[#004d2e]/50' }}" 
                                 onclick="changeMainImage('{{ $image->image_url }}', this)">
                                <img src="{{ $image->image_url }}" 
                                     alt="{{ $guidePlace->title }}"
                                     class="w-full h-16 object-cover">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif
        
        <!-- İletişim Bilgileri - Geniş Layout -->
        <div class="bg-gray-50 rounded-lg p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-6">İletişim Bilgileri</h3>
                     
            <!-- İletişim Bilgileri Grid - Her bilgi için ayrı kontrol -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Adres -->
                @if($guidePlace->address)
                    <div class="contact-item md:col-span-2 lg:col-span-3">
                        <div class="contact-label">📍 Adres</div>
                        <div class="contact-value">{{ $guidePlace->address }}</div>
                    </div>
                @else
                    <div class="contact-item md:col-span-2 lg:col-span-3 bg-gray-100 border-dashed">
                        <div class="contact-label text-gray-500">📍 Adres</div>
                        <div class="contact-value text-gray-500 italic">Adres bilgisi eklenecektir</div>
                    </div>
                @endif
                         
                         <!-- Telefon -->
                         @if($guidePlace->phone)
                             <div class="contact-item">
                                 <div class="contact-label">📞 Telefon</div>
                                 <div class="contact-value">
                                     @php
                                         // Telefon numarasını düzenle - boşlukları ve gereksiz karakterleri temizle
                                         $phone = preg_replace('/[^0-9+]/', '', $guidePlace->phone);
                                         
                                         // Eğer 10 haneli ise (0xxx xxx xx xx formatında) düzenle
                                         if (strlen($phone) == 11 && substr($phone, 0, 1) == '0') {
                                             $formatted_phone = substr($phone, 0, 4) . ' ' . substr($phone, 4, 3) . ' ' . substr($phone, 7, 2) . ' ' . substr($phone, 9, 2);
                                         } 
                                         // Eğer 10 haneli ise (xxx xxx xx xx formatında) düzenle
                                         elseif (strlen($phone) == 10) {
                                             $formatted_phone = '0' . substr($phone, 0, 3) . ' ' . substr($phone, 3, 3) . ' ' . substr($phone, 6, 2) . ' ' . substr($phone, 8, 2);
                                         }
                                         // Diğer durumlar için orijinal halini kullan
                                         else {
                                             $formatted_phone = $guidePlace->phone;
                                         }
                                     @endphp
                                     <a href="tel:{{ $phone }}" class="phone-link">{{ $formatted_phone }}</a>
                                 </div>
                             </div>
                         @else
                             <div class="contact-item bg-gray-100 border-dashed">
                                 <div class="contact-label text-gray-500">📞 Telefon</div>
                                 <div class="contact-value text-gray-500 italic">Telefon bilgisi eklenecektir</div>
                             </div>
                         @endif
                         
                         <!-- E-posta -->
                         @if($guidePlace->email)
                             <div class="contact-item">
                                 <div class="contact-label">✉️ E-posta</div>
                                 <div class="contact-value">
                                     <a href="mailto:{{ $guidePlace->email }}" class="phone-link">{{ $guidePlace->email }}</a>
                                 </div>
                             </div>
                         @else
                             <div class="contact-item bg-gray-100 border-dashed">
                                 <div class="contact-label text-gray-500">✉️ E-posta</div>
                                 <div class="contact-value text-gray-500 italic">E-posta bilgisi eklenecektir</div>
                             </div>
                         @endif
                         
                         <!-- Website -->
                         @if($guidePlace->website)
                             <div class="contact-item">
                                 <div class="contact-label">🌐 Website</div>
                                 <div class="contact-value">
                                     <a href="{{ $guidePlace->website }}" target="_blank" class="phone-link">
                                         {{ parse_url($guidePlace->website, PHP_URL_HOST) }}
                                     </a>
                                 </div>
                             </div>
                         @else
                             <div class="contact-item bg-gray-100 border-dashed">
                                 <div class="contact-label text-gray-500">🌐 Website</div>
                                 <div class="contact-value text-gray-500 italic">Website bilgisi eklenecektir</div>
                             </div>
                         @endif
                         
                <!-- Çalışma Saatleri -->
                @if($guidePlace->working_hours)
                    <div class="contact-item md:col-span-2 lg:col-span-3">
                        <div class="contact-label">⏰ Çalışma Saatleri</div>
                        <div class="contact-value">{{ $guidePlace->working_hours }}</div>
                    </div>
                @else
                    <div class="contact-item md:col-span-2 lg:col-span-3 bg-gray-100 border-dashed">
                        <div class="contact-label text-gray-500">⏰ Çalışma Saatleri</div>
                        <div class="contact-value text-gray-500 italic">Çalışma saatleri bilgisi eklenecektir</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- İlgili Yerler -->
@if($relatedPlaces->count() > 0)
<div class="py-16 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4">
        <h2 class="text-2xl font-bold text-gray-900 mb-8 text-center">{{ $category->name }} Kategorisindeki Diğer Yerler</h2>
        
        <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($relatedPlaces as $relatedPlace)
                <a href="{{ route('guide.place', [$category->slug, $relatedPlace->slug]) }}" 
                   class="block bg-white rounded-lg overflow-hidden shadow-sm border border-gray-200 hover:shadow-md hover:border-[#004d2e] transition-all duration-300">
                    @if($relatedPlace->featured_image_url)
                        <div class="aspect-video overflow-hidden">
                            <img src="{{ $relatedPlace->featured_image_url }}" 
                                 alt="{{ $relatedPlace->title }}"
                                 class="w-full h-full object-cover">
                        </div>
                    @else
                        <div class="aspect-video overflow-hidden">
                            <img src="{{ asset('images/cankaya-default.jpeg') }}" 
                                 alt="{{ $relatedPlace->title }}"
                                 class="w-full h-full object-cover">
                        </div>
                    @endif
                    
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 mb-2">{{ $relatedPlace->title }}</h3>
                        @if($relatedPlace->address)
                            <p class="text-gray-600 text-sm">{{ Str::limit($relatedPlace->address, 60) }}</p>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>
@endif
@endsection

@section('after_scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Galeri işlevselliği
    window.changeMainImage = function(imageSrc, thumbElement) {
        document.getElementById('mainImage').src = imageSrc;
        
        // Aktif thumb'ı güncelle
        document.querySelectorAll('.gallery-thumb').forEach(thumb => {
            thumb.classList.remove('active', 'border-[#004d2e]');
            thumb.classList.add('border-transparent');
        });
        thumbElement.classList.remove('border-transparent');
        thumbElement.classList.add('active', 'border-[#004d2e]');
    };
});
</script>
@endsection