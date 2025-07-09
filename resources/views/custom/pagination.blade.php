@if ($paginator->hasPages())
    <div style="display: flex !important; align-items: center !important; justify-content: center !important; flex-wrap: nowrap !important; gap: 4px !important;">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
            <span style="display: inline-block !important; padding: 8px 12px !important; font-size: 14px !important; font-weight: 500 !important; color: #9ca3af !important; background-color: #f3f4f6 !important; border: 1px solid #e5e7eb !important; border-radius: 8px !important; cursor: not-allowed !important; text-decoration: none !important; white-space: nowrap !important;">
                Önceki
            </span>
            @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" 
               style="display: inline-block !important; padding: 8px 12px !important; font-size: 14px !important; font-weight: 500 !important; color: #374151 !important; background-color: white !important; border: 1px solid #e5e7eb !important; border-radius: 8px !important; text-decoration: none !important; white-space: nowrap !important; transition: all 0.2s ease !important;"
               onmouseover="this.style.backgroundColor='#f3f4f6'; this.style.color='#00352b'; this.style.borderColor='#00352b';"
               onmouseout="this.style.backgroundColor='white'; this.style.color='#374151'; this.style.borderColor='#e5e7eb';">
                Önceki
            </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                <span style="display: inline-block !important; padding: 8px 12px !important; font-size: 14px !important; font-weight: 500 !important; color: #9ca3af !important; background-color: #f3f4f6 !important; border: 1px solid #e5e7eb !important; border-radius: 8px !important; text-decoration: none !important; white-space: nowrap !important;">
                    {{ $element }}
                </span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                        <span style="display: inline-block !important; padding: 8px 12px !important; font-size: 14px !important; font-weight: 600 !important; color: white !important; background-color: #00352b !important; border: 1px solid #00352b !important; border-radius: 8px !important; text-decoration: none !important; white-space: nowrap !important; box-shadow: 0 2px 4px rgba(0,53,43,0.3) !important;">
                            {{ $page }}
                        </span>
                        @else
                        <a href="{{ $url }}" 
                           style="display: inline-block !important; padding: 8px 12px !important; font-size: 14px !important; font-weight: 500 !important; color: #374151 !important; background-color: white !important; border: 1px solid #e5e7eb !important; border-radius: 8px !important; text-decoration: none !important; white-space: nowrap !important; transition: all 0.2s ease !important;"
                           onmouseover="this.style.backgroundColor='#f3f4f6'; this.style.color='#00352b'; this.style.borderColor='#00352b';"
                           onmouseout="this.style.backgroundColor='white'; this.style.color='#374151'; this.style.borderColor='#e5e7eb';">
                            {{ $page }}
                        </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" 
               style="display: inline-block !important; padding: 8px 12px !important; font-size: 14px !important; font-weight: 500 !important; color: #374151 !important; background-color: white !important; border: 1px solid #e5e7eb !important; border-radius: 8px !important; text-decoration: none !important; white-space: nowrap !important; transition: all 0.2s ease !important;"
               onmouseover="this.style.backgroundColor='#f3f4f6'; this.style.color='#00352b'; this.style.borderColor='#00352b';"
               onmouseout="this.style.backgroundColor='white'; this.style.color='#374151'; this.style.borderColor='#e5e7eb';">
                Sonraki
            </a>
            @else
            <span style="display: inline-block !important; padding: 8px 12px !important; font-size: 14px !important; font-weight: 500 !important; color: #9ca3af !important; background-color: #f3f4f6 !important; border: 1px solid #e5e7eb !important; border-radius: 8px !important; cursor: not-allowed !important; text-decoration: none !important; white-space: nowrap !important;">
                Sonraki
            </span>
            @endif
    </div>
@endif 