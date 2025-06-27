<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | Here you can change the default title of your admin panel.
    |
    | For detailed instructions you can look the title section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'title' => 'Yönetim Paneli',
    'title_prefix' => '',
    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    |
    | Here you can activate the favicon.
    |
    | For detailed instructions you can look the favicon section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_ico_only' => true,
    'use_full_favicon' => false,

    /*
    |--------------------------------------------------------------------------
    | Google Fonts
    |--------------------------------------------------------------------------
    |
    | Here you can allow or not the use of external google fonts. Disabling the
    | google fonts may be useful if your admin panel internet access is
    | restricted somehow.
    |
    | For detailed instructions you can look the google fonts section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'google_fonts' => [
        'allowed' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Logo
    |--------------------------------------------------------------------------
    |
    | Here you can change the logo of your admin panel.
    |
    | For detailed instructions you can look the logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'logo' => '<b>Yönetim</b>Paneli',
    'logo_img' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
    'logo_img_class' => 'brand-image img-circle elevation-3',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'Admin Logo',

    /*
    |--------------------------------------------------------------------------
    | Authentication Logo
    |--------------------------------------------------------------------------
    |
    | Here you can setup an alternative logo to use on your login and register
    | screens. When disabled, the admin panel logo will be used instead.
    |
    | For detailed instructions you can look the auth logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'auth_logo' => [
        'enabled' => false,
        'img' => [
            'path' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
            'alt' => 'Auth Logo',
            'class' => '',
            'width' => 50,
            'height' => 50,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Preloader Animation
    |--------------------------------------------------------------------------
    |
    | Here you can change the preloader animation configuration. Currently, two
    | modes are supported: 'fullscreen' for a fullscreen preloader animation
    | and 'cwrapper' to attach the preloader animation into the content-wrapper
    | element and avoid overlapping it with the sidebars and the top navbar.
    |
    | For detailed instructions you can look the preloader section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'preloader' => [
        'enabled' => false,
        'mode' => 'fullscreen',
        'img' => [
            'path' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
            'alt' => 'AdminLTE Preloader Image',
            'effect' => 'animation__shake',
            'width' => 60,
            'height' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    |
    | Here you can activate and change the user menu.
    |
    | For detailed instructions you can look the user menu section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'usermenu_enabled' => true,
    'usermenu_header' => false,
    'usermenu_header_class' => 'bg-primary',
    'usermenu_image' => false,
    'usermenu_desc' => false,
    'usermenu_profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Here we change the layout of your admin panel.
    |
    | For detailed instructions you can look the layout section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => null,
    'layout_fixed_navbar' => null,
    'layout_fixed_footer' => null,
    'layout_dark_mode' => null,

    /*
    |--------------------------------------------------------------------------
    | Authentication Views Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the authentication views.
    |
    | For detailed instructions you can look the auth classes section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_auth_card' => 'card-outline card-primary',
    'classes_auth_header' => '',
    'classes_auth_body' => '',
    'classes_auth_footer' => '',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-flat btn-primary',

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the admin panel.
    |
    | For detailed instructions you can look the admin panel classes here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_body' => '',
    'classes_brand' => '',
    'classes_brand_text' => '',
    'classes_content_wrapper' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-dark-primary elevation-4',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-white navbar-light',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container',

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar of the admin panel.
    |
    | For detailed instructions you can look the sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'sidebar_mini' => 'lg',
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => false,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,

    /*
    |--------------------------------------------------------------------------
    | Control Sidebar (Right Sidebar)
    |--------------------------------------------------------------------------
    |
    | Here we can modify the right sidebar aka control sidebar of the admin panel.
    |
    | For detailed instructions you can look the right sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'right_sidebar' => false,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Here we can modify the url settings of the admin panel.
    |
    | For detailed instructions you can look the urls section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_route_url' => false,
    'dashboard_url' => 'admin',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'register_url' => 'register',
    'password_reset_url' => 'password/reset',
    'password_email_url' => 'password/email',
    'profile_url' => 'admin/profile',
    'disable_darkmode_routes' => false,

    /*
    |--------------------------------------------------------------------------
    | Laravel Asset Bundling
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Laravel Asset Bundling option for the admin panel.
    | Currently, the next modes are supported: 'mix', 'vite' and 'vite_js_only'.
    | When using 'vite_js_only', it's expected that your CSS is imported using
    | JavaScript. Typically, in your application's 'resources/js/app.js' file.
    | If you are not using any of these, leave it as 'false'.
    |
    | For detailed instructions you can look the asset bundling section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'laravel_asset_bundling' => false,
    'laravel_css_path' => 'css/app.css',
    'laravel_js_path' => 'js/app.js',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar/top navigation of the admin panel.
    |
    | For detailed instructions you can look the menu section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'menu' => [
        // Navbar items:
        [
            'type'         => 'navbar-search',
            'text'         => 'search',
            'topnav_right' => true,
        ],
        [
            'text'         => 'SİTEYİ GÖR',
            'url'          => '/',
            'icon'         => 'fas fa-fw fa-external-link-alt',
            'topnav_right' => true,
            'target'       => '_blank',
            'classes'      => 'btn btn-success btn-sm mr-2',
        ],
        [
            'text'         => '404 Takip',
            'url'          => 'admin/404-management',
            'icon'         => 'fas fa-fw fa-exclamation-triangle',
            'topnav_right' => true,
            'classes'      => 'btn btn-warning btn-sm mr-2',
        ],
        [
            'text'         => 'Yönlendirmeler',
            'url'          => 'admin/redirects',
            'icon'         => 'fas fa-fw fa-route',
            'topnav_right' => true,
            'classes'      => 'btn btn-info btn-sm mr-2',
        ],
        [
            'type'         => 'fullscreen-widget',
            'topnav_right' => true,
        ],

        // Sidebar items:
        [
            'type' => 'sidebar-menu-search',
            'text' => 'search',
        ],
        [
            'text'        => 'Dashboard',
            'url'         => 'admin',
            'icon'        => 'fas fa-fw fa-tachometer-alt',
        ],
        ['header' => 'İÇERİK YÖNETİMİ'],
        [
            'text'    => 'Kurumsal Kadro',
            'icon'    => 'fas fa-fw fa-users',
            'url'     => 'admin/corporate/categories',
            'active'  => ['admin/corporate/categories', 'admin/corporate/categories/*', 'admin/corporate/members/*'],
        ],
        [
            'text'    => 'Haberler',
            'icon'    => 'fas fa-fw fa-newspaper',
            'submenu' => [
                [
                    'text' => 'Tüm Haberler',
                    'url'  => 'admin/news',
                    'icon' => 'fas fa-fw fa-list',
                    'active' => ['admin/news', 'admin/news/*/edit']
                ],
                [
                    'text' => 'Yeni Haber',
                    'url'  => 'admin/news/create',
                    'icon' => 'fas fa-fw fa-plus',
                    'active' => ['admin/news/create']
                ],
                [
                    'text' => 'Haber Kategorileri',
                    'url'  => 'admin/news-categories',
                    'icon' => 'fas fa-fw fa-folder',
                    'active' => ['admin/news-categories', 'admin/news-categories/*']
                ],
                [
                    'text' => 'Haber Etiketleri',
                    'url'  => 'admin/news-tags',
                    'icon' => 'fas fa-fw fa-tags',
                    'active' => ['admin/news-tags', 'admin/news-tags/*']
                ],
            ],
        ],
        [
            'text'    => 'Sayfalar',
            'icon'    => 'fas fa-fw fa-file-alt',
            'submenu' => [
                [
                    'text' => 'Tüm Sayfalar',
                    'url'  => 'admin/pages',
                    'icon' => 'fas fa-fw fa-list',
                    'active' => ['admin/pages', 'admin/pages/*/edit']
                ],
                [
                    'text' => 'Yeni Sayfa',
                    'url'  => 'admin/pages/create',
                    'icon' => 'fas fa-fw fa-plus',
                    'active' => ['admin/pages/create']
                ],
                [
                    'text' => 'Sayfa Kategorileri',
                    'url'  => 'admin/page-categories',
                    'icon' => 'fas fa-fw fa-folder',
                    'active' => ['admin/page-categories', 'admin/page-categories/*']
                ],
                [
                    'text' => 'Sayfa Etiketleri',
                    'url'  => 'admin/page-tags',
                    'icon' => 'fas fa-fw fa-tags',
                    'active' => ['admin/page-tags', 'admin/page-tags/*']
                ],
                        ],
        ],
        [
            'text' => 'Genel Görünüm',
            'url'  => 'admin/homepage',
            'icon' => 'fas fa-fw fa-tachometer-alt',
            'active' => ['admin/homepage', 'admin/homepage/*']
        ],
        [
            'text'    => 'Dosya Yönetim Sistemi',
            'icon'    => 'fas fa-fw fa-folder-open',
            'active'  => ['admin/filemanagersystem', 'admin/filemanagersystem/*', 'admin/file-manager-page', 'admin/file-manager/*'],
            'submenu' => [
                [
                    'text' => 'Dosya Yöneticisi',
                    'url'  => 'admin/filemanagersystem',
                    'icon' => 'fas fa-fw fa-folder',
                    'active' => ['admin/filemanagersystem', 'admin/filemanagersystem/*']
                ],
                [
                    'text' => 'Klasörler',
                    'url'  => 'admin/filemanagersystem/folders',
                    'icon' => 'fas fa-fw fa-folder-open',
                    'active' => ['admin/filemanagersystem/folders', 'admin/filemanagersystem/folders/*']
                ],
                [
                    'text' => 'Kategoriler',
                    'url' => 'admin/filemanagersystem/categories',
                    'icon' => 'fas fa-fw fa-tags',
                    'active' => ['admin/filemanagersystem/categories', 'admin/filemanagersystem/categories/*']
                ],
                [
                    'text' => 'Arşivler',
                    'url' => 'admin/archives',
                    'icon' => 'fas fa-fw fa-archive',
                    'active' => ['admin/archives', 'admin/archives/*']
                ],
            ],
        ],

        // Projeler Modülü
        [
            'text'    => 'Projeler',
            'icon'    => 'fas fa-project-diagram',
            'submenu' => [
                [
                    'text' => 'Tüm Projeler',
                    'url'  => 'admin/projects',
                    'icon' => 'fas fa-list',
                ],
                [
                    'text' => 'Yeni Proje Ekle',
                    'url'  => 'admin/projects/create',
                    'icon' => 'fas fa-plus',
                ],
                [
                    'text' => 'Kategoriler',
                    'url'  => 'admin/projects/categories',
                    'icon' => 'fas fa-tags',
                ],
                [
                    'text' => 'Ayarlar',
                    'url'  => 'admin/projects/settings',
                    'icon' => 'fas fa-cog',
                ],
            ],
        ],
        // Etkinlikler Modülü
        [
            'text'    => 'Etkinlikler',
            'icon'    => 'far fa-calendar-alt',
            'submenu' => [
                [
                    'text' => 'Tüm Etkinlikler',
                    'url'  => 'admin/events',
                    'icon' => 'fas fa-list',
                    'active' => ['admin/events', 'admin/events/*/edit'],
                ],
                [
                    'text' => 'Yeni Etkinlik Ekle',
                    'url'  => 'admin/events/create',
                    'icon' => 'fas fa-plus',
                    'active' => ['admin/events/create'],
                ],
                [
                    'text' => 'Kategoriler',
                    'url'  => 'admin/events/categories',
                    'icon' => 'fas fa-tags',
                    'active' => ['admin/events/categories', 'admin/events/categories/*'],
                ],
                [
                    'text' => 'Ayarlar',
                    'url'  => 'admin/events/settings',
                    'icon' => 'fas fa-cog',
                    'active' => ['admin/events/settings'],
                ],
            ],
        ],
        // Hizmetler Modülü
        [
            'text'    => 'Hizmetler',
            'icon'    => 'fas fa-fw fa-concierge-bell',
            'submenu' => [
                [
                    'text' => 'Tüm Hizmetler',
                    'url'  => 'admin/services',
                    'icon' => 'fas fa-fw fa-list',
                    'active' => ['admin/services', 'admin/services/create', 'admin/services/*/edit']
                ],
                [
                    'text' => 'Birimler',
                    'url'  => 'admin/services/units',
                    'icon' => 'fas fa-fw fa-building',
                    'active' => ['admin/services/units', 'admin/services/units/*']
                ],
                [
                    'text' => 'Müdürlükler Kategorisi',
                    'url'  => 'admin/mudurlukler-kategorisi',
                    'icon' => 'fas fa-fw fa-tags',
                    'active' => ['admin/mudurlukler-kategorisi', 'admin/mudurlukler-kategorisi/*']
                ],
                [
                    'text' => 'Etiketler',
                    'url'  => 'admin/service-tags',
                    'icon' => 'fas fa-fw fa-tag',
                    'active' => ['admin/service-tags', 'admin/service-tags/*']
                ],
                [
                    'text' => 'Hizmet Konuları',
                    'url'  => 'admin/hizmet-konulari',
                    'icon' => 'fas fa-fw fa-list-ul',
                    'active' => ['admin/hizmet-konulari', 'admin/hizmet-konulari/*']
                ],
            ],
        ],

        // Çankaya Evleri Modülü
        [
            'text'    => 'Çankaya Evleri',
            'icon'    => 'fas fa-home',
            'submenu' => [
                [
                    'text' => 'Tüm Çankaya Evleri',
                    'url'  => 'admin/cankaya-houses',
                    'icon' => 'fas fa-list',
                    'active' => ['admin/cankaya-houses', 'admin/cankaya-houses/*/edit', 'admin/cankaya-houses/*/show']
                ],
                [
                    'text' => 'Yeni Çankaya Evi',
                    'url'  => 'admin/cankaya-houses/create',
                    'icon' => 'fas fa-plus',
                    'active' => ['admin/cankaya-houses/create']
                ],
                // Kurslar - Geçici olarak kapatıldı
                // [
                //     'text' => 'Kurslar',
                //     'url'  => 'admin/cankaya-house-courses',
                //     'icon' => 'fas fa-graduation-cap',
                //     'active' => ['admin/cankaya-house-courses', 'admin/cankaya-house-courses/*']
                // ],
            ],
        ],
        // İhaleler Modülü
        [
            'text'    => 'İhaleler',
            'icon'    => 'fas fa-gavel',
            'submenu' => [
                [
                    'text' => 'Tüm İhaleler',
                    'url'  => 'admin/tenders',
                    'icon' => 'fas fa-list',
                    'active' => ['admin/tenders', 'admin/tenders/*/edit', 'admin/tenders/*/show']
                ],
                [
                    'text' => 'Yeni İhale',
                    'url'  => 'admin/tenders/create',
                    'icon' => 'fas fa-plus',
                    'active' => ['admin/tenders/create']
                ],
            ],
        ],
        // Rehber Modülü
        [
            'text'    => 'Rehber',
            'icon'    => 'fas fa-map-marker-alt',
            'submenu' => [
                [
                    'text' => 'Kategoriler',
                    'url'  => 'admin/guide-categories',
                    'icon' => 'fas fa-folder',
                    'active' => ['admin/guide-categories', 'admin/guide-categories/*']
                ],
                [
                    'text' => 'Yerler',
                    'url'  => 'admin/guide-places',
                    'icon' => 'fas fa-map-pin',
                    'active' => ['admin/guide-places', 'admin/guide-places/*']
                ],
            ],
        ],
        // Müdürlükler Modülü
        [
            'text'    => 'Müdürlükler',
            'icon'    => 'fas fa-building',
            'submenu' => [
                [
                    'text' => 'Tüm Müdürlükler',
                    'url'  => 'admin/mudurlukler',
                    'icon' => 'fas fa-list',
                    'active' => ['admin/mudurlukler', 'admin/mudurlukler/*/edit', 'admin/mudurlukler/*/show']
                ],
                [
                    'text' => 'Yeni Müdürlük',
                    'url'  => 'admin/mudurlukler/create',
                    'icon' => 'fas fa-plus',
                    'active' => ['admin/mudurlukler/create']
                ],
            ],
        ],
        
        ['header' => 'SİSTEM YÖNETİMİ'],
        [
            'text' => 'Aktivite Günlükleri',
            'url'  => 'admin/activity-logs',
            'icon' => 'fas fa-fw fa-history',
            'active' => ['admin/activity-logs', 'admin/activity-logs/*']
        ],
        [
            'text' => 'Ayarlar',
            'url'  => 'admin/settings',
            'icon' => 'fas fa-fw fa-cogs',
            'active' => ['admin/settings', 'admin/settings/*']
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Here we can modify the menu filters of the admin panel.
    |
    | For detailed instructions you can look the menu filters section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Here we can modify the plugins used inside the admin panel.
    |
    | For detailed instructions you can look the plugins section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Plugins-Configuration
    |
    */

    'plugins' => [
        'CustomCssFixAlert' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'css/custom-fixes.css',
                ],
            ],
        ],
        'Toastr' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js',
                ],
            ],
        ],
        'Datatables' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/buttons/1.7.1/js/buttons.bootstrap4.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/buttons/1.7.1/js/buttons.colVis.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/buttons/1.7.1/css/buttons.bootstrap4.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css',
                ],
            ],
        ],
        'Select2' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css',
                ],
            ],
        ],
        'Chartjs' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js',
                ],
            ],
        ],
        'Sweetalert2' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/sweetalert2@11',
                ],
            ],
        ],
        'Pace' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | IFrame
    |--------------------------------------------------------------------------
    |
    | Here we change the IFrame mode configuration. Note these changes will
    | only apply to the view that extends and enable the IFrame mode.
    |
    | For detailed instructions you can look the iframe mode section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/IFrame-Mode-Configuration
    |
    */

    'iframe' => [
        'default_tab' => [
            'url' => null,
            'title' => null,
        ],
        'buttons' => [
            'close' => true,
            'close_all' => true,
            'close_all_other' => true,
            'scroll_left' => true,
            'scroll_right' => true,
            'fullscreen' => true,
        ],
        'options' => [
            'loading_screen' => 1000,
            'auto_show_new_tab' => true,
            'use_navbar_items' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Livewire support.
    |
    | For detailed instructions you can look the livewire here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'livewire' => false,
];
