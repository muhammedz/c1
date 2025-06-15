@extends('layouts.front')

@section('title', $mayor->page_title ?? 'Başkanımız')
@section('meta_description', $mayor->meta_description ?? 'Başkanımızın biyografisi, faaliyetleri ve duyuruları')

@section('content')
<!-- Hero Bölümü - Daha Geniş ve Etkili -->
<div class="relative bg-gradient-to-r from-[{{ $mayor->hero_bg_color ?? '#00352b' }}] to-[#20846c] overflow-hidden"
     @if($mayor->hero_bg_image_url) style="background-image: url('{{ $mayor->hero_bg_image_url }}'); background-size: cover; background-position: center;" @endif>
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
                <img src="{{ $mayor->profile_image_url ?? 'https://cankaya.epoxsoft.net.tr/uploads/images/iyu1AIj1YVxWUezQXpVwYyco4PDjuOkHYVmtkPmH.webp' }}" alt="Başkanımız {{ $mayor->name }}" class="w-full h-full object-cover">
            </div>
            
            <div>
                <span class="inline-block px-3 py-0.5 bg-white/20 text-white text-xs rounded-full mb-2">Çankaya Belediyesi</span>
                <h1 class="text-2xl md:text-4xl font-bold text-white mb-1">{{ $mayor->name }}</h1>
                <h2 class="text-white/90 text-lg md:text-xl mb-4">{{ $mayor->title }}</h2>
                
                <!-- Sosyal Medya Linkleri -->
                <div class="flex gap-2">
                    @if($mayor->social_twitter)
                    <a href="{{ $mayor->social_twitter }}" target="_blank" class="bg-white/10 hover:bg-white/20 text-white p-1.5 rounded-full transition-colors flex items-center justify-center w-8 h-8">
                        <i class="fab fa-x-twitter text-base"></i>
                    </a>
                    @endif
                    @if($mayor->social_instagram)
                    <a href="{{ $mayor->social_instagram }}" target="_blank" class="bg-white/10 hover:bg-white/20 text-white p-1.5 rounded-full transition-colors flex items-center justify-center w-8 h-8">
                        <i class="fab fa-instagram text-base"></i>
                    </a>
                    @endif
                    @if($mayor->social_facebook)
                    <a href="{{ $mayor->social_facebook }}" target="_blank" class="bg-white/10 hover:bg-white/20 text-white p-1.5 rounded-full transition-colors flex items-center justify-center w-8 h-8">
                        <i class="fab fa-facebook text-base"></i>
                    </a>
                    @endif
                    @if($mayor->social_linkedin)
                    <a href="{{ $mayor->social_linkedin }}" target="_blank" class="bg-white/10 hover:bg-white/20 text-white p-1.5 rounded-full transition-colors flex items-center justify-center w-8 h-8">
                        <i class="fab fa-linkedin text-base"></i>
                    </a>
                    @endif
                    @if($mayor->social_email)
                    <a href="mailto:{{ $mayor->social_email }}" class="bg-white/10 hover:bg-white/20 text-white p-1.5 rounded-full transition-colors flex items-center justify-center w-8 h-8">
                        <span class="material-icons text-sm">email</span>
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Basit Hikaye Çerçeveleri - GEÇİCİ OLARAK GİZLENDİ -->
{{-- 
@if($stories->count() > 0)
<div class="py-8 bg-slate-50 shadow-sm">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="flex overflow-x-auto gap-6 pb-3 justify-between">
            @foreach($stories as $index => $story)
            <!-- Hikaye {{ $index + 1 }} -->
            <div class="hikaye-item flex-shrink-0 text-center" onclick="acLightbox({{ $index }})">
                <div class="w-20 h-20 rounded-full p-1 bg-gradient-to-r {{ $story->getExtraValue('gradient', 'from-blue-500 to-cyan-400') }} mx-auto cursor-pointer">
                    <div class="w-full h-full rounded-full overflow-hidden border-2 border-white">
                        <img src="{{ $story->image_url ?? 'https://cankaya.epoxsoft.net.tr/images/huseyincanguner/huseyincanguner' . ($index + 1) . '.jpeg' }}" alt="{{ $story->title }}" class="w-full h-full object-cover">
                    </div>
                </div>
                <p class="text-xs font-medium mt-2 text-gray-700">{{ $story->title }}</p>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif
--}}

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
        <!-- Video ve Resim Bölümü -->
        <div class="bg-white shadow-md rounded-xl overflow-hidden mb-8">
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Sol Video -->
                    <div class="relative rounded-lg overflow-hidden shadow-lg">
                        <div class="aspect-video">
                            <iframe 
                                width="100%" 
                                height="100%" 
                                src="https://www.youtube.com/embed/mbsG4TDQjqA?si=DX7e-_puTrfmSIQ4" 
                                title="YouTube video player" 
                                frameborder="0" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                                referrerpolicy="strict-origin-when-cross-origin" 
                                allowfullscreen
                                class="rounded-lg">
                            </iframe>
                        </div>
                    </div>
                    
                    <!-- Sağ Resim -->
                    <div class="relative rounded-lg overflow-hidden shadow-lg">
                        <div class="aspect-video">
                            <a href="https://cankaya.bel.tr/uploads/documents/dijital_1749997259_JMPX5Xpn.pdf" target="_blank" class="block w-full h-full">
                                <img 
                                    src="https://cankaya.bel.tr/uploads/images/neleryaptik_1749920348_uHQvvq4j.webp" 
                                    alt="Neler Yaptık - Projelerimiz" 
                                    class="w-full h-full object-cover rounded-lg hover:scale-105 transition-transform duration-500"
                                >
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Biyografi Kartı -->
        <div class="bg-white shadow-md rounded-xl overflow-hidden mb-8">
            <div class="px-6 py-4 border-b border-slate-100">
                <h2 class="text-2xl font-bold text-gray-800">Biyografi</h2>
            </div>
            
            <div class="p-6">
                <div class="prose prose-lg max-w-none prose-headings:text-gray-800 prose-p:text-gray-700 prose-a:text-[#00352b] prose-a:no-underline hover:prose-a:text-[#20846c] hover:prose-a:underline prose-img:rounded-lg">
                    {!! $mayor->biography ? nl2br(e($mayor->biography)) : '<p>Biyografi bilgisi henüz eklenmemiş.</p>' !!}
                </div>
            </div>
        </div>
        
        <!-- Gündem ve Etkinlikler Kartı -->
        @if($agenda->count() > 0)
        <div class="bg-white shadow-md rounded-xl overflow-hidden mb-8">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-800">Başkanın Gündemi</h2>
                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Güncel</span>
            </div>
            
            <div class="p-6">
                <div class="divide-y divide-gray-100">
                    @foreach($agenda as $item)
                    <!-- Gündem Öğesi -->
                    <div class="py-4 first:pt-0 last:pb-0 hover:bg-slate-50 transition-colors rounded-lg p-2 -mx-2">
                        <div class="flex items-center mb-2">
                            <span class="bg-[#00352b] text-white text-sm px-3 py-1 rounded-full mr-3">
                                {{ $item->getExtraValue('event_date') ? \Carbon\Carbon::parse($item->getExtraValue('event_date'))->format('d/m/Y') : 'Tarih belirtilmemiş' }}
                            </span>
                            @if($item->getExtraValue('event_time'))
                            <span class="text-sm text-gray-500">{{ $item->getExtraValue('event_time') }}</span>
                            @endif
                            <span class="ml-auto px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full capitalize">
                                {{ $item->getExtraValue('event_type', 'etkinlik') }}
                            </span>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $item->title }}</h3>
                        <p class="text-gray-600">{{ $item->description }}</p>
                        <div class="flex items-center mt-3 text-sm">
                            @if($item->getExtraValue('location'))
                            <span class="flex items-center text-gray-500 mr-4">
                                <span class="material-icons text-xs mr-1">location_on</span>
                                {{ $item->getExtraValue('location') }}
                            </span>
                            @endif
                            @if($item->getExtraValue('participants'))
                            <span class="flex items-center text-gray-500">
                                <span class="material-icons text-xs mr-1">people</span>
                                {{ $item->getExtraValue('participants') }}
                            </span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <div class="px-6 py-4 bg-gray-50">
                <div class="flex items-center text-sm text-gray-500">
                    <span class="material-icons text-base mr-1">calendar_today</span>
                    <span>{{ now()->format('m/Y') }}</span>
                </div>
            </div>
        </div>
        @endif

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
                    Haberler
                </h2>
                <a href="{{ route('news.index') }}" class="inline-flex items-center text-[#00352b] hover:text-[#20846c] font-medium transition-colors">
                    <span>Tüm Haberler</span>
                    <i class="material-icons text-sm ml-1">arrow_forward</i>
                </a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($mayorNews as $news)
                <a href="{{ route('news.show', $news->slug) }}" class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 block">
                    <div class="aspect-video overflow-hidden">
                        @if($news->image)
                            <img src="{{ str_replace('/storage/storage/', '/storage/', $news->image) }}" alt="{{ $news->title }}" class="w-full h-full object-cover transition-transform duration-500 hover:scale-105">
                        @else
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                <i class="material-icons text-gray-400 text-4xl">article</i>
                            </div>
                        @endif
                    </div>
                    <div class="p-5">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2 line-clamp-2">{{ $news->title }}</h3>
                    </div>
                </a>
                @empty
                <div class="col-span-4 bg-white p-8 rounded-lg shadow-md text-center">
                    <i class="material-icons text-gray-400 text-5xl mb-4">info</i>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Henüz haber bulunmamaktadır</h3>
                    <p class="text-gray-600">Başkan haberleri yakında yayınlanacaktır.</p>
                </div>
                @endforelse
            </div>
        </div>
        
        <!-- Fotoğraf Galerisi -->
        @if($gallery->count() > 0)
        <div>
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800">Fotoğraf Galerisi</h2>
                <a href="#" class="inline-flex items-center text-[#00352b] hover:text-[#20846c] font-medium transition-colors">
                    <span>Tüm Fotoğraflar</span>
                    <span class="material-icons ml-1">arrow_forward</span>
                </a>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($gallery as $photo)
                <!-- Fotoğraf -->
                <div class="relative overflow-hidden rounded-lg group h-48 md:h-64 cursor-pointer" onclick="acFotoLightbox('{{ $photo->image_url ?? 'https://cankaya.epoxsoft.net.tr/images/huseyincanguner/huseyincanguner5.jpeg' }}', '{{ $photo->title }}')">
                    <img src="{{ $photo->image_url ?? 'https://cankaya.epoxsoft.net.tr/images/huseyincanguner/huseyincanguner5.jpeg' }}" alt="{{ $photo->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-50 transition-opacity"></div>
                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <span class="material-icons text-white text-3xl">zoom_in</span>
                    </div>
                    @if($photo->getExtraValue('category'))
                    <div class="absolute top-2 left-2 bg-black/50 text-white text-xs px-2 py-1 rounded">
                        {{ $photo->getExtraValue('category') }}
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>
@endsection

<!-- Fotoğraf Lightbox -->
<div id="fotoLightbox" class="fixed inset-0 bg-black bg-opacity-90 z-50 items-center justify-center p-4" style="display: none;" onclick="kapatFotoLightbox()">
    <!-- Kapatma butonu - siyah alanda üstte -->
    <button onclick="kapatFotoLightbox()" class="absolute top-6 right-6 text-white hover:text-gray-300 z-20 text-4xl">
        <span class="material-icons text-4xl">close</span>
    </button>
    
    <div class="relative max-w-4xl max-h-full" onclick="event.stopPropagation()">
        <!-- Fotoğraf -->
        <img id="lightboxFoto" src="" alt="" class="max-w-full max-h-[90vh] object-contain rounded-lg">
    </div>
</div>

<script>
    // Hikaye verileri
    const hikayeler = [
        @foreach($stories as $story)
        {
            baslik: "{{ $story->title }}",
            metin: "{{ $story->description }}",
            resim: "{{ $story->image_url ?? 'https://cankaya.epoxsoft.net.tr/images/huseyincanguner/huseyincanguner1.jpeg' }}"
        }@if(!$loop->last),@endif
        @endforeach
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
    
    // Fotoğraf Lightbox fonksiyonları
    function acFotoLightbox(fotoUrl, baslik) {
        document.getElementById('lightboxFoto').src = fotoUrl;
        document.getElementById('fotoLightbox').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    
    function kapatFotoLightbox() {
        document.getElementById('fotoLightbox').style.display = 'none';
        document.body.style.overflow = '';
    }
    
    // Fotoğraf lightbox için ESC tuşu desteği
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            if (document.getElementById('fotoLightbox').style.display === 'flex') {
                kapatFotoLightbox();
            } else if (!document.getElementById('lightbox').classList.contains('hidden')) {
                kapatLightbox();
            }
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
    

</script> 