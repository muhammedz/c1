@extends('layouts.front')

@section('title', $guidePlace->title . ' - ' . $category->name . ' - ' . $pageTitle)
@section('meta_description', $guidePlace->meta_description ?: $guidePlace->excerpt)

@section('css')
<style>
    .gallery-thumb.active {
        border-color: #004d2e !important;
        transform: scale(1.05);
    }
</style>
@endsection

@section('content')
<!-- Hero Bölümü - Ana Rehber Sayfası ile Aynı -->
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
        <div class="flex flex-col items-center text-center">
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">
                {{ $guidePlace->title }}
            </h1>
        </div>
    </div>
</div>

<!-- Hızlı Aksiyonlar -->
<section class="py-8 bg-gray-50">
    <div class="container max-w-6xl mx-auto px-4">
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ route('guide.place', [$category->slug, $guidePlace->slug]) }}" 
               class="inline-flex items-center px-6 py-3 bg-[#004d2e] hover:bg-[#006b3f] text-white rounded-2xl font-medium transition-all duration-300 hover:-translate-y-1 shadow-lg hover:shadow-xl">
                <span class="material-icons mr-2">visibility</span>Detaylar
            </a>
            
            @if($guidePlace->phone)
                <a href="tel:{{ $guidePlace->phone }}" 
                   class="inline-flex items-center px-6 py-3 bg-white hover:bg-gray-50 text-gray-700 rounded-2xl font-medium transition-all duration-300 hover:-translate-y-1 shadow-lg hover:shadow-xl border border-gray-200">
                    <span class="material-icons mr-2">phone</span>Ara
                </a>
            @endif
            
            @if($guidePlace->maps_link)
                <a href="{{ $guidePlace->maps_link }}" target="_blank" 
                   class="inline-flex items-center px-6 py-3 bg-white hover:bg-gray-50 text-gray-700 rounded-2xl font-medium transition-all duration-300 hover:-translate-y-1 shadow-lg hover:shadow-xl border border-gray-200">
                    <span class="material-icons mr-2">map</span>Konum
                </a>
            @endif
            
            @if($guidePlace->email)
                <a href="mailto:{{ $guidePlace->email }}" 
                   class="inline-flex items-center px-6 py-3 bg-white hover:bg-gray-50 text-gray-700 rounded-2xl font-medium transition-all duration-300 hover:-translate-y-1 shadow-lg hover:shadow-xl border border-gray-200">
                    <span class="material-icons mr-2">email</span>E-posta
                </a>
            @endif
        </div>
    </div>
</section>

<!-- Ana İçerik -->
<section class="py-20 bg-white">
    <div class="container max-w-6xl mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Sol Kolon - Ana İçerik -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Fotoğraf Galerisi -->
                @if($guidePlace->images->count() > 0)
                    <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                        <h2 class="text-2xl font-bold text-gray-900 mb-8 flex items-center">
                            <span class="material-icons mr-3 text-[#004d2e]">photo_library</span>
                            Fotoğraflar
                        </h2>
                        
                        <!-- Ana Resim -->
                        <div class="rounded-2xl overflow-hidden mb-6 shadow-lg">
                            <img id="mainImage" 
                                 src="{{ $guidePlace->featured_image_url ?: $guidePlace->images->first()->image_url }}" 
                                 alt="{{ $guidePlace->title }}"
                                 class="w-full h-96 object-cover">
                        </div>
                        
                        <!-- Küçük Resimler -->
                        @if($guidePlace->images->count() > 1)
                            <div class="grid grid-cols-4 md:grid-cols-6 gap-4">
                                @foreach($guidePlace->images as $index => $image)
                                    <div class="gallery-thumb {{ $index === 0 ? 'active' : '' }} rounded-xl overflow-hidden cursor-pointer transition-all duration-300 hover:scale-105 border-2 {{ $index === 0 ? 'border-[#004d2e]' : 'border-transparent hover:border-[#004d2e]/50' }}" 
                                         onclick="changeMainImage('{{ $image->image_url }}', this)">
                                        <img src="{{ $image->image_url }}" 
                                             alt="{{ $guidePlace->title }}"
                                             class="w-full h-20 object-cover">
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif
                
                <!-- İçerik -->
                @if($guidePlace->content)
                    <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                        <h2 class="text-2xl font-bold text-gray-900 mb-8 flex items-center">
                            <span class="material-icons mr-3 text-[#004d2e]">description</span>
                            Detaylar
                        </h2>
                        <div class="prose prose-lg max-w-none prose-headings:text-gray-900 prose-p:text-gray-700 prose-a:text-[#004d2e] prose-a:no-underline hover:prose-a:underline">
                            {!! $guidePlace->content !!}
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sağ Kolon - Konum Bilgileri -->
            <div class="space-y-8">
                <!-- Konum Bilgileri -->
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 sticky top-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-8 flex items-center">
                        <span class="material-icons mr-3 text-[#004d2e]">place</span>
                        Konum Bilgileri
                    </h3>
                    
                    <div class="space-y-6">
                        @if($guidePlace->address)
                            <div class="flex items-start p-4 bg-gray-50 rounded-2xl hover:bg-gray-100 transition-colors duration-200">
                                <div class="w-12 h-12 bg-[#004d2e]/10 rounded-xl flex items-center justify-center mr-4 flex-shrink-0">
                                    <span class="material-icons text-[#004d2e]">location_on</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-semibold text-gray-900 mb-1">Adres</h4>
                                    <p class="text-gray-600 text-sm leading-relaxed">{{ $guidePlace->address }}</p>
                                </div>
                            </div>
                        @endif
                        
                        @if($guidePlace->phone)
                            <div class="flex items-start p-4 bg-gray-50 rounded-2xl hover:bg-gray-100 transition-colors duration-200 cursor-pointer group" onclick="copyToClipboard('{{ $guidePlace->phone }}', 'Telefon numarası kopyalandı!')">
                                <div class="w-12 h-12 bg-[#004d2e]/10 group-hover:bg-[#004d2e] rounded-xl flex items-center justify-center mr-4 flex-shrink-0 transition-colors duration-200">
                                    <span class="material-icons text-[#004d2e] group-hover:text-white transition-colors duration-200">phone</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-semibold text-gray-900 mb-1">Telefon</h4>
                                    <p class="text-gray-600 text-sm">{{ $guidePlace->phone }}</p>
                                    <p class="text-xs text-gray-400 mt-1">Kopyalamak için tıklayın</p>
                                </div>
                            </div>
                        @endif
                        
                        @if($guidePlace->email)
                            <div class="flex items-start p-4 bg-gray-50 rounded-2xl hover:bg-gray-100 transition-colors duration-200 cursor-pointer group" onclick="copyToClipboard('{{ $guidePlace->email }}', 'E-posta adresi kopyalandı!')">
                                <div class="w-12 h-12 bg-[#004d2e]/10 group-hover:bg-[#004d2e] rounded-xl flex items-center justify-center mr-4 flex-shrink-0 transition-colors duration-200">
                                    <span class="material-icons text-[#004d2e] group-hover:text-white transition-colors duration-200">email</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-semibold text-gray-900 mb-1">E-posta</h4>
                                    <p class="text-gray-600 text-sm break-all">{{ $guidePlace->email }}</p>
                                    <p class="text-xs text-gray-400 mt-1">Kopyalamak için tıklayın</p>
                                </div>
                            </div>
                        @endif
                        
                        @if($guidePlace->website)
                            <div class="flex items-start p-4 bg-gray-50 rounded-2xl hover:bg-gray-100 transition-colors duration-200">
                                <div class="w-12 h-12 bg-[#004d2e]/10 rounded-xl flex items-center justify-center mr-4 flex-shrink-0">
                                    <span class="material-icons text-[#004d2e]">language</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-semibold text-gray-900 mb-1">Website</h4>
                                    <a href="{{ $guidePlace->website }}" target="_blank" class="text-[#004d2e] hover:text-[#006b3f] text-sm hover:underline transition-colors duration-200">
                                        {{ parse_url($guidePlace->website, PHP_URL_HOST) }}
                                    </a>
                                </div>
                            </div>
                        @endif
                        
                        @if($guidePlace->working_hours)
                            <div class="flex items-start p-4 bg-gray-50 rounded-2xl hover:bg-gray-100 transition-colors duration-200">
                                <div class="w-12 h-12 bg-[#004d2e]/10 rounded-xl flex items-center justify-center mr-4 flex-shrink-0">
                                    <span class="material-icons text-[#004d2e]">schedule</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-semibold text-gray-900 mb-1">Çalışma Saatleri</h4>
                                    <p class="text-gray-600 text-sm leading-relaxed">{{ $guidePlace->working_hours }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- İlgili Yerler -->
@if($relatedPlaces->count() > 0)
<section class="py-20 bg-gray-50">
    <div class="container max-w-6xl mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">İlgili Yerler</h2>
            <div class="w-20 h-1 bg-[#004d2e] mx-auto mb-6"></div>
            <p class="text-lg text-gray-600">{{ $category->name }} kategorisindeki diğer yerler</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($relatedPlaces as $relatedPlace)
                <a href="{{ route('guide.place', [$category->slug, $relatedPlace->slug]) }}" 
                   class="group block bg-white rounded-3xl overflow-hidden shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-2 transition-all duration-300">
                    @if($relatedPlace->featured_image_url)
                        <div class="aspect-video overflow-hidden">
                            <img src="{{ $relatedPlace->featured_image_url }}" 
                                 alt="{{ $relatedPlace->title }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>
                    @else
                        <div class="aspect-video bg-gray-50 flex items-center justify-center">
                            <span class="material-icons text-gray-300 text-4xl">business</span>
                        </div>
                    @endif
                    
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-[#004d2e] transition-colors duration-300">{{ $relatedPlace->title }}</h3>
                        @if($relatedPlace->address)
                            <p class="text-gray-600 text-sm mb-4 leading-relaxed">{{ Str::limit($relatedPlace->address, 60) }}</p>
                        @endif
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-[#004d2e] group-hover:text-[#006b3f] transition-colors duration-300">
                                Detayları Gör
                            </span>
                            <div class="w-8 h-8 bg-[#004d2e]/10 group-hover:bg-[#004d2e] rounded-full flex items-center justify-center transition-all duration-300 group-hover:translate-x-1">
                                <span class="material-icons text-sm text-[#004d2e] group-hover:text-white transition-colors duration-300">arrow_forward</span>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
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
            thumb.classList.remove('active');
        });
        thumbElement.classList.add('active');
    };
    
    // Kopyalama işlevi
    window.copyToClipboard = function(text, message) {
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text).then(function() {
                showToast(message || 'Kopyalandı!');
            });
        } else {
            // Fallback for older browsers
            const textArea = document.createElement('textarea');
            textArea.value = text;
            document.body.appendChild(textArea);
            textArea.select();
            try {
                document.execCommand('copy');
                showToast(message || 'Kopyalandı!');
            } catch (err) {
                console.error('Kopyalama başarısız:', err);
            }
            document.body.removeChild(textArea);
        }
    };
    
    // Toast bildirimi
    function showToast(message) {
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-4 right-4 bg-[#004d2e] text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-all duration-300';
        toast.textContent = message;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(20px)';
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 300);
        }, 3000);
    }
    
    // Sağ tık ile kopyalama
    document.querySelectorAll('.cursor-pointer[onclick*="copyToClipboard"]').forEach(item => {
        item.addEventListener('contextmenu', function(e) {
            e.preventDefault();
            this.click();
        });
    });
});
</script>
@endsection