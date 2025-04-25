{{-- Büyük Menü (Mega Menü) Şablonu --}}
@if(isset($menu) && $menu->status)
<div class="mega-menu-wrapper group relative">
    <a href="{{ $menu->url ?? '#' }}" class="mega-menu-toggle flex items-center space-x-1 py-2 px-4 text-gray-700 hover:text-primary-600">
        <span>{{ $menu->name }}</span>
        <span class="material-icons text-sm">expand_more</span>
    </a>
    
    <div class="mega-menu hidden absolute z-10 left-0 right-0 bg-white shadow-lg rounded-b-lg pt-4 pb-6 px-6 mt-1">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-{{ min(count($menu->categories), 4) }} gap-6">
                @foreach($menu->activeCategories as $category)
                <div class="mega-menu-category">
                    @if($category->url)
                    <a href="{{ $category->url }}" class="block font-bold text-lg text-gray-800 hover:text-primary-600 mb-3">
                        {{ $category->name }}
                    </a>
                    @else
                    <h3 class="font-bold text-lg text-gray-800 mb-3">{{ $category->name }}</h3>
                    @endif
                    
                    @if($category->activeItems->count() > 0)
                    <ul class="space-y-2">
                        @foreach($category->activeItems as $item)
                        <li>
                            <a href="{{ $item->url }}" class="flex items-center text-gray-600 hover:text-primary-600 transition" {{ $item->target_attribute }}>
                                @if($item->icon)
                                <span class="material-icons mr-2 text-sm">{{ $item->icon }}</span>
                                @endif
                                <span>{{ $item->name }}</span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </div>
                @endforeach
            </div>
            
            @if($menu->description)
            <div class="mt-6 pt-4 border-t border-gray-200">
                <div class="flex items-start">
                    <div class="text-sm text-gray-500">
                        {{ $menu->description->description }}
                        
                        @if($menu->description->link_url && $menu->description->link_text)
                        <a href="{{ $menu->description->link_url }}" class="inline-block mt-2 text-primary-600 hover:text-primary-700 font-medium">
                            {{ $menu->description->link_text }} &rarr;
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endif 