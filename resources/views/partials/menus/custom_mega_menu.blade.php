{{-- Custom Mega Menu Template (Özelleştirilebilir yapı için) --}}
<div class="mega-menu">
    <div class="mega-menu-content mega-menu-custom">
        <div class="py-2 px-1">
            @if(isset($menuItem->layout_settings) && is_array($menuItem->layout_settings))
                {{-- Özel başlık --}}
                @if(isset($menuItem->layout_settings['title']))
                    <div class="mb-3 pb-2 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-[#00352b]">{{ $menuItem->layout_settings['title'] }}</h2>
                        @if(isset($menuItem->layout_settings['subtitle']))
                            <p class="text-sm text-gray-600">{{ $menuItem->layout_settings['subtitle'] }}</p>
                        @endif
                    </div>
                @endif

                {{-- İçerik gövdesi --}}
                <div class="flex flex-col">
                    {{-- Özel sütunlar --}}
                    @if(isset($menuItem->layout_settings['columns']) && is_array($menuItem->layout_settings['columns']))
                        <div class="grid grid-cols-{{ count($menuItem->layout_settings['columns']) }} gap-4">
                            @foreach($menuItem->layout_settings['columns'] as $column)
                                <div class="mega-menu-custom-column">
                                    @if(isset($column['title']))
                                        <h3 class="text-md font-semibold mb-2 text-gray-700">{{ $column['title'] }}</h3>
                                    @endif
                                    
                                    @if(isset($column['items']) && is_array($column['items']))
                                        <ul class="space-y-1">
                                            @foreach($column['items'] as $item)
                                                <li>
                                                    <a href="{{ $item['url'] ?? '#' }}" class="mega-menu-link" {{ isset($item['target']) && $item['target'] == '_blank' ? 'target="_blank"' : '' }}>
                                                        @if(isset($item['icon']))
                                                            <span class="material-icons mega-menu-link-icon {{ isset($item['icon_class']) ? $item['icon_class'] : '' }}">{{ $item['icon'] }}</span>
                                                        @endif
                                                        <span>{{ $item['text'] ?? 'Link' }}</span>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Özel HTML --}}
                    @if(isset($menuItem->layout_settings['custom_html']))
                        <div class="mt-4 custom-html-container">
                            {!! $menuItem->layout_settings['custom_html'] !!}
                        </div>
                    @endif
                </div>


            @else
                <div class="text-center py-4">
                    <p class="text-gray-500">Bu menü için özel ayarlar yapılandırılmamış.</p>
                </div>
            @endif
        </div>
    </div>
</div> 