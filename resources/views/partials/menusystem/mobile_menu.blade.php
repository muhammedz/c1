{{-- Mobil Menü Şablonu --}}
@if(isset($menu) && $menu->status)
<div class="mobile-menu-item">
    @if($menu->type == 1)
    {{-- Küçük menü için basit link --}}
    <a href="{{ $menu->url ?? '#' }}" class="block py-2 px-4 text-gray-700 hover:bg-gray-100 border-b border-gray-200">
        {{ $menu->name }}
    </a>
    @else
    {{-- Büyük menü için açılır accordion yapısı --}}
    <div class="mobile-menu-group border-b border-gray-200">
        <div class="mobile-menu-header flex items-center justify-between py-2 px-4 hover:bg-gray-100 cursor-pointer">
            <span class="text-gray-700">{{ $menu->name }}</span>
            <button class="mobile-menu-toggle text-gray-500 focus:outline-none">
                <span class="material-icons">expand_more</span>
            </button>
        </div>
        
        <div class="mobile-submenu hidden px-2">
            @foreach($menu->activeCategories as $category)
            <div class="mobile-category border-t border-gray-100">
                <div class="mobile-category-header flex items-center justify-between py-2 px-2 hover:bg-gray-50 cursor-pointer">
                    @if($category->url)
                    <a href="{{ $category->url }}" class="text-gray-600 font-medium">{{ $category->name }}</a>
                    @else
                    <span class="text-gray-600 font-medium">{{ $category->name }}</span>
                    @endif
                    
                    @if($category->activeItems->count() > 0)
                    <button class="mobile-category-toggle text-gray-400 focus:outline-none">
                        <span class="material-icons">expand_more</span>
                    </button>
                    @endif
                </div>
                
                @if($category->activeItems->count() > 0)
                <div class="mobile-category-items hidden pl-3">
                    <ul class="py-1">
                        @foreach($category->activeItems as $item)
                        <li>
                            <a href="{{ $item->url }}" class="flex items-center py-1 px-2 text-sm text-gray-600 hover:bg-gray-50 rounded" {{ $item->target_attribute }}>
                                @if($item->icon)
                                    @if(Str::startsWith($item->icon, 'data:image/'))
                                        <img src="{{ $item->icon }}" alt="İkon" class="mr-2" style="width: 16px; height: 16px; object-fit: contain;">
                                    @elseif(Str::startsWith($item->icon, 'fas '))
                                        <i class="{{ $item->icon }} mr-2 text-xs"></i>
                                    @else
                                        <span class="material-icons mr-2 text-xs">{{ $item->icon }}</span>
                                    @endif
                                @endif
                                <span>{{ $item->name }}</span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
            @endforeach
            
            @if($menu->description)
            <div class="mobile-menu-description p-3 text-sm text-gray-500 bg-gray-50 mt-2 rounded">
                {{ $menu->description->description }}
                
                @if($menu->description->link_url && $menu->description->link_text)
                <a href="{{ $menu->description->link_url }}" class="block mt-2 text-primary-600 hover:text-primary-700 font-medium">
                    {{ $menu->description->link_text }} &rarr;
                </a>
                @endif
            </div>
            @endif
        </div>
    </div>
    @endif
</div>
@endif 