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
            

        </div>
    </div>
</div> 