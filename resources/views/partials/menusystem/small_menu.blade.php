{{-- Küçük Menü Şablonu --}}
@if(isset($menu) && $menu->status)
<div class="small-menu-item">
    <a href="{{ $menu->url ?? '#' }}" class="block py-2 px-4 text-gray-700 hover:text-primary-600 transition">
        {{ $menu->name }}
    </a>
</div>
@endif 