<li class="nav-item">
    <a class="nav-link" data-widget="pushmenu" href="#"
        @if(config('adminlte.sidebar_collapse_remember'))
            data-enable-remember="true"
        @endif
        @if(!config('adminlte.sidebar_collapse_remember_no_transition'))
            data-no-transition-after-reload="false"
        @endif
        @if(config('adminlte.sidebar_collapse_auto_size'))
            data-auto-collapse-size="{{ config('adminlte.sidebar_collapse_auto_size') }}"
        @endif>
        <i class="fas fa-bars"></i>
        <span class="sr-only">{{ __('adminlte::adminlte.toggle_navigation') }}</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ url('admin/users') }}">
        <i class="fas fa-users"></i>
        <span class="ml-1">Kullanıcılar</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ url('admin/search-settings') }}">
        <i class="fas fa-search"></i>
        <span class="ml-1">Arama Ayarları</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ url('admin/hedef-kitleler') }}">
        <i class="fas fa-bullseye"></i>
        <span class="ml-1">Hedef Kitleler</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ url('admin/announcements') }}">
        <i class="fas fa-bullhorn"></i>
        <span class="ml-1">Duyurular</span>
    </a>
</li>