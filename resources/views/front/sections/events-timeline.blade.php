<!-- #### Events Timeline Section -->
{{-- Debug info: isAktif: {{ $eventSettings->is_active ?? 'undefined' }}, upcomingEventsCount: {{ count($upcomingEvents ?? []) }} --}}
@php
// Türkçe karakter destekli ilk harf büyütme fonksiyonu
function titleCase($string) {
    $string = mb_convert_case($string, MB_CASE_TITLE, 'UTF-8');
    return $string;
}
@endphp

<!-- #### Alternatif Etkinlikler Bölümü - Featured Card Tasarımı -->
@if(isset($eventSettings) && $eventSettings->is_active && count($upcomingEvents) > 0)
<section id="events-alternative-section" class="container max-w-7xl mx-auto px-4 py-12">
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-2xl font-bold text-gray-800">{{ $eventSettings->section_title ?? 'Etkinlikler' }}</h2>
        <a href="https://kultursanat.cankaya.bel.tr/" target="_blank" class="text-[#004d2e] font-medium text-sm hover:underline flex items-center gap-1">
            Tümünü Gör
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
    </div>
    
    <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-6">
        @foreach($upcomingEvents->take(4) as $event)
        <div>
            <a href="https://kultursanat.cankaya.bel.tr/" target="_blank" class="event-featured-card">
                <!-- Etkinlik Görseli -->
                <div class="event-featured-image">
                    @if($event->cover_image)
                        <img src="{{ $event->cover_image_url }}" alt="{{ titleCase($event->title) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-to-r from-[#004d2e] to-[#006a3e] flex items-center justify-center text-white p-4">
                            <span class="text-xl font-semibold text-center">{{ titleCase($event->title) }}</span>
                        </div>
                    @endif
                    
                    <!-- Kategori Etiketi -->
                    <div class="event-featured-category">
                        {{ titleCase($event->category->name ?? 'Genel') }}
                    </div>
                </div>
                
                <!-- Etkinlik Bilgileri -->
                <div class="event-featured-content">
                    <h3 class="event-featured-title line-clamp-2">{{ titleCase($event->title) }}</h3>
                    
                    <div class="space-y-2 mt-auto">
                        <div class="event-featured-meta">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                            </svg>
                            <span class="line-clamp-1">{{ titleCase($event->location) }}</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</section>
@elseif(isset($eventSettings) && $eventSettings->is_active)
<section id="events-alternative-section" class="container max-w-7xl mx-auto px-4 py-12">
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-2xl font-bold text-gray-800">{{ $eventSettings->section_title ?? 'Etkinlikler' }}</h2>
    </div>
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <p class="text-center text-gray-500">Şu anda gösterilecek yaklaşan etkinlik bulunmamaktadır.</p>
        <p class="text-center mt-2">
            <a href="https://kultursanat.cankaya.bel.tr/" target="_blank" class="text-[#004d2e] font-medium hover:underline">Tüm etkinlikleri görüntüle</a>
        </p>
    </div>
</section>
@endif 