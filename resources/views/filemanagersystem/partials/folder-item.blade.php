@php
    $hasChildren = $folder->children->isNotEmpty();
    $isActive = request()->routeIs('admin.filemanagersystem.folders.*') && request()->folder == $folder->id;
@endphp

<div class="nav-item">
    <a href="{{ route('admin.filemanagersystem.folders.show', $folder) }}" 
       class="nav-link {{ $isActive ? 'active' : '' }}"
       data-folder-id="{{ $folder->id }}">
        <i class="fas fa-folder{{ $hasChildren ? '-open' : '' }} mr-2"></i>
        {{ $folder->name }}
        @if($hasChildren)
            <i class="fas fa-angle-left right"></i>
        @endif
    </a>
    
    @if($hasChildren)
        <div class="nav nav-treeview ml-3" style="display: {{ $isActive ? 'block' : 'none' }};">
            @foreach($folder->children as $child)
                @include('filemanagersystem.partials.folder-item', ['folder' => $child])
            @endforeach
        </div>
    @endif
</div> 