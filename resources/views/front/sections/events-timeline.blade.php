<!-- #### Events Timeline Section -->
{{-- Debug info: isAktif: {{ $eventSettings->is_active ?? 'undefined' }}, upcomingEventsCount: {{ count($upcomingEvents ?? []) }} --}}
@if(isset($eventSettings) && $eventSettings->is_active && count($upcomingEvents) > 0)
<section id="events-timeline-section" class="events-timeline-section container max-w-7xl mx-auto px-4 py-12">
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-2">
            <h2 class="text-2xl font-medium text-gray-800">{{ $eventSettings->section_title ?? 'Etkinlikler Bölümü' }}</h2>
            <div class="flex items-center gap-2">
                <button class="timeline-prev bg-white rounded-full w-8 h-8 flex items-center justify-center shadow-sm border border-gray-100 hover:bg-gray-50">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <button class="timeline-next bg-white rounded-full w-8 h-8 flex items-center justify-center shadow-sm border border-gray-100 hover:bg-gray-50">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <a href="{{ route('events.index') }}" class="text-[#004d2e] font-medium text-sm hover:underline">Tümünü Gör</a>
        </div>
    </div>

    <div class="swiper timelineSwiper">
        <div class="swiper-wrapper">
            @foreach($upcomingEvents as $event)
            <div class="swiper-slide">
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="flex items-start gap-6">
                        <div class="w-32 h-32 bg-gray-100 rounded-xl overflow-hidden flex-shrink-0">
                            @if($event->cover_image)
                                <img src="{{ $event->cover_image_url }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400 text-sm text-center">
                                    Etkinlik<br>Görseli<br>Gelecek
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <div class="flex flex-col items-start ">
                                <h3 class="text-[#004d2e] font-semibold !p-0 !mb-0">{{ $event->title }}</h3>
                                <span class="text-gray-400 text-sm">{{ $event->category->name ?? 'Genel' }}</span>
                            </div>
                            <div class="space-y-3 mt-2">
                                <div class="flex items-center gap-2 text-sm text-gray-500">
                                    <svg class="w-4 h-4 text-[#004d2e] flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" />
                                    </svg>
                                    <span class="flex-1">
                                        {{ $event->turkish_start_date }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-2 text-sm text-gray-500">
                                    <svg class="w-4 h-4 text-[#004d2e] flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V5z" />
                                    </svg>
                                    <span class="flex-1">{{ $event->start_date->format('H:i') }}
                                    @if($event->end_date)
                                     - {{ $event->end_date->format('H:i') }}
                                    @endif
                                    </span>
                                </div>
                                <div class="flex items-center gap-2 text-sm text-gray-500">
                                    <svg class="w-4 h-4 text-[#004d2e] flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="flex-1">{{ $event->location }}</span>
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('events.show', $event->slug) }}" class="text-[#004d2e] text-sm font-medium hover:underline">Detaylar &rarr;</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@elseif(isset($eventSettings) && $eventSettings->is_active)
<section id="events-timeline-section" class="events-timeline-section container max-w-7xl mx-auto px-4 py-12">
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-2">
            <h2 class="text-2xl font-medium text-gray-800">{{ $eventSettings->section_title ?? 'Etkinlikler Bölümü' }}</h2>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <p class="text-center text-gray-500">Şu anda gösterilecek yaklaşan etkinlik bulunmamaktadır.</p>
        <p class="text-center mt-2">
            <a href="{{ route('events.index') }}" class="text-[#004d2e] font-medium hover:underline">Tüm etkinlikleri görüntüle</a>
        </p>
    </div>
</section>
@endif 