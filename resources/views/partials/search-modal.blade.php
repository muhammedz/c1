@php
    $searchSettings = App\Models\SearchSetting::getSettings();
    $quickLinks = App\Models\SearchQuickLink::getActiveLinks($searchSettings->max_quick_links);
    $popularQueries = App\Models\SearchPopularQuery::getActiveQueries($searchSettings->max_popular_queries);
@endphp

<!-- Arama Modal -->
<div id="searchModal" class="fixed inset-0 bg-black/60 z-[200] hidden">
    <div class="flex items-start justify-center pt-28 px-4">
        <div class="w-full max-w-2xl bg-white rounded-lg shadow-xl">
            <!-- Arama Başlığı ve Kapat -->
            <div class="flex items-center justify-between p-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-[#00352b]">{{ $searchSettings->title }}</h3>
                <button id="closeSearch" class="p-1.5 hover:bg-gray-100 rounded-lg transition-colors">
                    <span class="material-icons text-gray-400">close</span>
                </button>
            </div>

            <!-- Arama Formu -->
            <div class="p-5">
                <form action="{{ route('search') }}" method="GET">
                    <div class="relative">
                        <input type="text" 
                            name="q" 
                            placeholder="{{ $searchSettings->placeholder }}"
                            class="w-full pl-12 pr-14 py-4 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00352b] focus:border-transparent transition-all text-md" 
                            autofocus>
                        <span class="material-icons absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-xl">search</span>
                        <button type="submit" class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-[#00352b] text-white px-3 py-2 rounded-lg hover:bg-[#004c3f] transition-colors">
                            <span class="material-icons">search</span>
                        </button>
                    </div>
                </form>

                @if($searchSettings->show_quick_links && $quickLinks->count() > 0)
                <!-- Hızlı Aramalar -->
                <div class="mt-6">
                    <h4 class="text-sm font-medium text-gray-600 mb-3 flex items-center">
                        <span class="material-icons text-[#00352b] mr-1 text-sm">bolt</span>
                        Hızlı Aramalar
                    </h4>
                    <div class="flex flex-wrap gap-2">
                        @foreach($quickLinks as $link)
                        <a href="{{ $link->url }}" class="px-4 py-2 bg-gray-50 text-gray-700 rounded-lg hover:bg-gray-100 text-sm transition-colors">{{ $link->title }}</a>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($searchSettings->show_popular_queries && $popularQueries->count() > 0)
                <!-- Popüler Aramalar -->
                <div class="mt-6">
                    <h4 class="text-sm font-medium text-gray-600 mb-3 flex items-center">
                        <span class="material-icons text-[#00352b] mr-1 text-sm">trending_up</span>
                        Popüler Aramalar
                    </h4>
                    <div class="space-y-2">
                        @foreach($popularQueries as $query)
                        <a href="{{ $query->url }}" class="flex items-center space-x-3 p-2 hover:bg-gray-50 rounded-lg group">
                            <span class="material-icons text-[#00352b] group-hover:text-[#007b32]">{{ $query->icon ?? 'search' }}</span>
                            <span class="text-gray-700 group-hover:text-[#00352b]">{{ $query->title }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    /* Modal animasyon efektleri */
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes slideDown {
        from { transform: translateY(-10px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    
    #searchModal:not(.hidden) {
        animation: fadeIn 0.2s ease-out forwards;
    }
    
    #searchModal:not(.hidden) > div > div {
        animation: slideDown 0.2s ease-out forwards;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchButton = document.getElementById('searchButton');
        const searchModal = document.getElementById('searchModal');
        const closeSearch = document.getElementById('closeSearch');
        
        if (searchButton && searchModal && closeSearch) {
            // Arama modali açma
            searchButton.addEventListener('click', () => {
                searchModal.classList.remove('hidden');
                // Otomatik olarak input'a odaklan
                setTimeout(() => {
                    const searchInput = searchModal.querySelector('input[name="q"]');
                    if (searchInput) searchInput.focus();
                }, 100);
            });
            
            // Arama modali kapatma
            closeSearch.addEventListener('click', () => {
                searchModal.classList.add('hidden');
            });
            
            // Arka plana tıklayınca modal kapansın
            searchModal.addEventListener('click', (e) => {
                if (e.target === searchModal) {
                    searchModal.classList.add('hidden');
                }
            });
            
            // ESC tuşuna basılınca modal kapansın
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && !searchModal.classList.contains('hidden')) {
                    searchModal.classList.add('hidden');
                }
            });
        }
    });
</script> 