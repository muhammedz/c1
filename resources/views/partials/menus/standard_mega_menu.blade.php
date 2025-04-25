{{-- Standard Mega Menu Template (Kurumsal, Duyurular, Nerede Ne Var için) --}}
<div class="mega-menu">
    <div class="mega-menu-content mega-menu-standard">
        <div class="py-2 px-1">
            <div class="flex mb-2">
                <div class="w-full">
                    <div class="grid grid-cols-{{ count($menuItem->children->groupBy('id')) > 4 ? '4' : count($menuItem->children->groupBy('id')) }} gap-3">
                        @foreach($menuItem->children as $category)
                            @if($category->is_active)
                                <div class="mega-menu-category">
                                    <h3>{{ $category->name }}</h3>
                                    <ul class="space-y-0.5">
                                        @foreach($category->children as $link)
                                            @if($link->is_active)
                                                <li>
                                                    <a href="{{ $link->url }}" class="mega-menu-link" {{ $link->target == '_blank' ? 'target="_blank"' : '' }}>
                                                        @if($link->icon)
                                                            <span class="material-icons mega-menu-link-icon">{{ $link->icon }}</span>
                                                        @endif
                                                        <span>{{ $link->name }}</span>
                                                    </a>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
            
            @if(isset($menuItem->layout_settings['show_bottom_section']) && $menuItem->layout_settings['show_bottom_section'])
                <div class="mt-2 pt-2 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <p class="text-xs text-gray-500">{{ $menuItem->layout_settings['bottom_text'] ?? 'Daha fazla bilgi için iletişime geçin.' }}</p>
                        @if(isset($menuItem->layout_settings['action_link']) && isset($menuItem->layout_settings['action_text']))
                            <a href="{{ $menuItem->layout_settings['action_link'] }}" class="text-[#00352b] hover:text-[#007b32] text-sm font-medium flex items-center gap-1 transition-all hover:gap-2">
                                {{ $menuItem->layout_settings['action_text'] }}
                                <span class="material-icons text-sm">arrow_forward</span>
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div> 