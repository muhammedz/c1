{{-- Card Grid Mega Menu Template (Hizmetler menüsü için) --}}
<div class="mega-menu">
    <div class="container">
        <div class="bg-white rounded-lg shadow-lg p-6 mega-menu-content">
            {{-- Etiket Filtreleri --}}
            @if($menuItem->menuTags->where('is_active', 1)->count() > 0)
                <div class="mb-4">
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="text-lg font-semibold text-gray-700">{{ $menuItem->layout_settings['tags_title'] ?? 'İlgili Gruplar:' }}</h3>
                        @if(isset($menuItem->layout_settings['action_link']) && isset($menuItem->layout_settings['action_text']))
                            <a href="{{ $menuItem->layout_settings['action_link'] }}" class="bg-gradient-to-r from-[#007b32] to-[#00352b] text-white font-medium py-2 px-4 rounded-md shadow-sm transition duration-300 flex items-center gap-2 hover:shadow-md hover:translate-y-[-2px]">
                                <span class="material-icons text-sm">{{ $menuItem->layout_settings['action_icon'] ?? 'category' }}</span>
                                <span>{{ $menuItem->layout_settings['action_text'] }}</span>
                                <span class="material-icons text-sm">arrow_forward</span>
                            </a>
                        @endif
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <a href="#" data-category="all" class="py-1 px-3 text-sm bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-full transition tag-active">Tümü</a>
                        @foreach($menuItem->menuTags->where('is_active', 1)->sortBy('order') as $tag)
                            <a href="{{ $tag->url ?? '#' }}" data-category="{{ \Illuminate\Support\Str::slug($tag->name) }}" class="py-1 px-3 text-sm bg-{{ $tag->color_class }}-100 hover:bg-{{ $tag->color_class }}-200 text-{{ $tag->color_class }}-800 rounded-full transition">{{ $tag->name }}</a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Hizmet Kartları Grid --}}
            <div class="grid grid-cols-6 gap-3">
                @foreach($menuItem->menuCards->where('is_active', 1)->sortBy('order') as $card)
                    <a href="{{ $card->url }}" data-category="{{ $card->data_category ?? 'all' }}" class="p-4 bg-white hover:bg-gray-50 rounded-lg border border-gray-200 flex flex-col items-center text-center transition transform hover:translate-y-[-5px] hover:shadow-md menu-card">
                        <span class="material-icons text-{{ $card->color ?? '[#007b32]' }} text-2xl mb-2">{{ $card->icon }}</span>
                        <span class="text-sm font-medium text-gray-700">{{ $card->title }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Filtreleme işlevselliği
        const tagLinks = document.querySelectorAll('[data-category]');
        const menuCards = document.querySelectorAll('.menu-card');
        
        tagLinks.forEach(tag => {
            tag.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Aktif etiket sınıfını kaldır
                tagLinks.forEach(t => t.classList.remove('tag-active'));
                
                // Tıklanan etikete aktif sınıfını ekle
                this.classList.add('tag-active');
                
                const category = this.getAttribute('data-category');
                
                // Kartları filtrele
                menuCards.forEach(card => {
                    const cardCategory = card.getAttribute('data-category');
                    
                    if (category === 'all' || cardCategory === category) {
                        card.style.display = 'flex';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
    });
</script>
@endpush 