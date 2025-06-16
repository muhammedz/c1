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
<!-- Breadcrumb ve Başlık -->
<div class="bg-gray-50 py-6">
    <div class="max-w-6xl mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="mb-4">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li><a href="{{ route('front.home') }}" class="hover:text-[#004d2e]">Ana Sayfa</a></li>
                <li><span class="mx-2">/</span></li>
                <li><a href="{{ route('guide.index') }}" class="hover:text-[#004d2e]">Rehber</a></li>
                <li><span class="mx-2">/</span></li>
                <li><a href="{{ route('guide.category', $category->slug) }}" class="hover:text-[#004d2e]">{{ $category->name }}</a></li>
                <li><span class="mx-2">/</span></li>
                <li class="text-gray-900">{{ $guidePlace->title }}</li>
            </ol>
        </nav>
        
        <!-- Başlık -->
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">{{ $guidePlace->title }}</h1>
        <p class="text-gray-600">{{ $category->name }}</p>
    </div>
</div>

<!-- Ana İçerik -->
<div class="py-8 bg-white">
    <div class="max-w-6xl mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Sol Kolon - Ana İçerik -->
            <div class="lg:col-span-2">
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
                
                <!-- İçerik -->
                @if($guidePlace->content)
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Detaylar</h2>
                        <div class="prose max-w-none text-gray-700">
                            {!! $guidePlace->content !!}
                        </div>
                    </div>
                @endif
            </div>

                         <!-- Sağ Kolon - İletişim Bilgileri -->
             <div class="lg:col-span-1">
                 <div class="bg-gray-50 rounded-lg p-6">
                     <h3 class="text-lg font-bold text-gray-900 mb-6">İletişim Bilgileri</h3>
                     
                     <!-- İletişim Bilgileri Grid -->
                     <div class="grid grid-cols-2 gap-4">
                         <!-- Adres -->
                         @if($guidePlace->address)
                             <div class="contact-item col-span-2">
                                 <div class="contact-label">📍 Adres</div>
                                 <div class="contact-value">{{ $guidePlace->address }}</div>
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
                         @endif
                         
                         <!-- E-posta -->
                         @if($guidePlace->email)
                             <div class="contact-item">
                                 <div class="contact-label">✉️ E-posta</div>
                                 <div class="contact-value">
                                     <a href="mailto:{{ $guidePlace->email }}" class="phone-link">{{ $guidePlace->email }}</a>
                                 </div>
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
                         @endif
                         
                         <!-- Çalışma Saatleri -->
                         @if($guidePlace->working_hours)
                             <div class="contact-item">
                                 <div class="contact-label">⏰ Çalışma Saatleri</div>
                                 <div class="contact-value">{{ $guidePlace->working_hours }}</div>
                             </div>
                         @endif
                     </div>
                     
                     <!-- Harita Butonu -->
                     @if($guidePlace->maps_link)
                         <div class="mt-6">
                             <a href="{{ $guidePlace->maps_link }}" target="_blank" 
                                class="w-full bg-[#004d2e] text-white py-3 px-4 rounded-lg hover:bg-[#006b3f] transition-colors duration-300 flex items-center justify-center">
                                 <span class="material-icons mr-2">map</span>
                                 Haritada Göster
                             </a>
                         </div>
                     @endif
                 </div>
             </div>
        </div>
    </div>
</div>

<!-- İlgili Yerler -->
@if($relatedPlaces->count() > 0)
<div class="py-16 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4">
        <h2 class="text-2xl font-bold text-gray-900 mb-8 text-center">{{ $category->name }} Kategorisindeki Diğer Yerler</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
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