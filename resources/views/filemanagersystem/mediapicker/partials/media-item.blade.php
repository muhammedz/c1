<div class="col-md-3 col-sm-6 col-12">
    <div class="media-item" 
        data-id="{{ $item->id }}" 
        data-url="{{ $item->url }}" 
        data-name="{{ $item->original_name ?? $item->name }}" 
        data-size="{{ $item->getFormattedSize() }}" 
        data-type="{{ $item->mime_type }}" 
        data-date="{{ $item->created_at->format('d.m.Y H:i') }}">
        <div class="media-thumbnail">
            @if(strpos($item->mime_type, 'image/') === 0)
                <img src="{{ $item->url }}" alt="{{ $item->original_name ?? $item->name }}">
            @else
                <div class="file-icon">
                    <i class="fas {{ $item->getIconClassAttribute() }}"></i>
                </div>
            @endif
        </div>
        <div class="media-info">
            <div class="media-name" title="{{ $item->original_name ?? $item->name }}">
                {{ Str::limit($item->original_name ?? $item->name, 20) }}
            </div>
            <div class="media-details">
                <span>{{ $item->getFormattedSize() }}</span>
                <span class="ml-2">{{ $item->created_at->format('d.m.Y') }}</span>
            </div>
        </div>
    </div>
</div> 