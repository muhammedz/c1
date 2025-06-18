@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')

<a href="https://www.epoxsoft.com/" target="_blank"
    @if($layoutHelper->isLayoutTopnavEnabled())
        class="navbar-brand {{ config('adminlte.classes_brand') }}"
    @else
        class="brand-link {{ config('adminlte.classes_brand') }}"
    @endif>

    {{-- EPOXSOFT Logo --}}
    <img src="{{ asset('images/epoxsoft-logo.png') }}"
         alt="EPOXSOFT"
         class="brand-image"
         style="max-height: 35px; width: auto;">

</a> 