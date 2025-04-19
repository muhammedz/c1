@php
    $hasChildren = $category->children->isNotEmpty();
    $isActive = request()->routeIs('admin.filemanagersystem.categories.*') && request()->category == $category->id;
@endphp

<div class="nav-item">
    <a href="{{ route('admin.filemanagersystem.categories.show', $category) }}" 
       class="nav-link {{ $isActive ? 'active' : '' }}"
       data-category-id="{{ $category->id }}">
        <i class="fas fa-tag mr-2" style="color: {{ $category->color ?? '#3498db' }}"></i>
        {{ $category->name }}
        @if($hasChildren)
            <i class="fas fa-angle-left right"></i>
        @endif
    </a>
    
    @if($hasChildren)
        <div class="nav nav-treeview ml-3" style="display: {{ $isActive ? 'block' : 'none' }};">
            @foreach($category->children as $child)
                @include('filemanagersystem.partials.category-item', ['category' => $child])
            @endforeach
        </div>
    @endif
</div> 