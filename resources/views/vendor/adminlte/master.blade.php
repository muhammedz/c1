<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    {{-- Base Meta Tags --}}
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Custom Meta Tags --}}
    @yield('meta_tags')

    {{-- Title --}}
    <title>
        @yield('title_prefix', config('adminlte.title_prefix', ''))
        @yield('title', config('adminlte.title', 'AdminLTE 3'))
        @yield('title_postfix', config('adminlte.title_postfix', ''))
    </title>

    {{-- Custom stylesheets (pre AdminLTE) --}}
    @yield('adminlte_css_pre')

    {{-- Base Stylesheets (depends on Laravel asset bundling tool) --}}
    @if(config('adminlte.enabled_laravel_mix', false))
        <link rel="stylesheet" href="{{ mix(config('adminlte.laravel_mix_css_path', 'css/app.css')) }}">
    @else
        @switch(config('adminlte.laravel_asset_bundling', false))
            @case('mix')
                <link rel="stylesheet" href="{{ mix(config('adminlte.laravel_css_path', 'css/app.css')) }}">
            @break

            @case('vite')
                @vite([config('adminlte.laravel_css_path', 'resources/css/app.css'), config('adminlte.laravel_js_path', 'resources/js/app.js')])
            @break

            @case('vite_js_only')
                @vite(config('adminlte.laravel_js_path', 'resources/js/app.js'))
            @break

            @default
                <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
                <link rel="stylesheet" href="{{ asset('vendor/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
                <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">

                @if(config('adminlte.google_fonts.allowed', true))
                    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
                @endif
        @endswitch
    @endif

    {{-- Extra Configured Plugins Stylesheets --}}
    @include('adminlte::plugins', ['type' => 'css'])

    {{-- Livewire Styles --}}
    @if(config('adminlte.livewire'))
        @if(intval(app()->version()) >= 7)
            @livewireStyles
        @else
            <livewire:styles />
        @endif
    @endif

    {{-- Custom Stylesheets (post AdminLTE) --}}
    @yield('adminlte_css')

    {{-- EPOXSOFT Custom Styles --}}
    <style>
        /* Logo/marka alanının arka planını beyaz yap */
        .main-sidebar .brand-link {
            background-color: #ffffff !important;
            border-bottom: 1px solid #dee2e6;
            padding: 0.5rem 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 57px;
        }
        
        /* Logo boyutunu ayarla */
        .brand-image {
            max-height: 35px;
            width: auto;
            margin: 0;
        }
        
        /* Sidebar menü düzeni */
        .main-sidebar .sidebar {
            padding-top: 0;
        }
        
        /* Dinamik Favicon gizle */
        link[rel="shortcut icon"][href*="favicons/favicon.ico"] {
            display: none !important;
        }
    </style>

    {{-- Favicon --}}
    @if(config('adminlte.use_ico_only'))
        <link rel="shortcut icon" href="{{ asset('favicons/favicon.ico') }}" />
    @elseif(config('adminlte.use_full_favicon'))
        <link rel="shortcut icon" href="{{ asset('favicons/favicon.ico') }}" />
        <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('favicons/apple-icon-57x57.png') }}">
        <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('favicons/apple-icon-60x60.png') }}">
        <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('favicons/apple-icon-72x72.png') }}">
        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('favicons/apple-icon-76x76.png') }}">
        <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('favicons/apple-icon-114x114.png') }}">
        <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('favicons/apple-icon-120x120.png') }}">
        <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('favicons/apple-icon-144x144.png') }}">
        <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('favicons/apple-icon-152x152.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicons/apple-icon-180x180.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicons/favicon-16x16.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicons/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicons/favicon-96x96.png') }}">
        <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicons/android-icon-192x192.png') }}">
        <link rel="manifest" crossorigin="use-credentials" href="{{ asset('favicons/manifest.json') }}">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="{{ asset('favicon/ms-icon-144x144.png') }}">
    @endif

</head>

<body class="@yield('classes_body')" @yield('body_data')>

    {{-- Body Content --}}
    @yield('body')

    {{-- Base Scripts (depends on Laravel asset bundling tool) --}}
    @if(config('adminlte.enabled_laravel_mix', false))
        <script src="{{ mix(config('adminlte.laravel_mix_js_path', 'js/app.js')) }}"></script>
    @else
        @switch(config('adminlte.laravel_asset_bundling', false))
            @case('mix')
                <script src="{{ mix(config('adminlte.laravel_js_path', 'js/app.js')) }}"></script>
            @break

            @case('vite')
            @case('vite_js_only')
            @break

            @default
                <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
                <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
                <script src="{{ asset('vendor/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
                <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
        @endswitch
    @endif

    {{-- Extra Configured Plugins Scripts --}}
    @include('adminlte::plugins', ['type' => 'js'])

    {{-- Livewire Script --}}
    @if(config('adminlte.livewire'))
        @if(intval(app()->version()) >= 7)
            @livewireScripts
        @else
            <livewire:scripts />
        @endif
    @endif

    {{-- Custom Scripts --}}
    @yield('adminlte_js')

    {{-- Hızlı Etkinlik Çekme Script --}}
    <script>
        $(document).ready(function() {
            // Hızlı etkinlik çekme butonu
            $('#quick-scrape-btn').click(function(e) {
                e.preventDefault();
                
                var $btn = $(this);
                var originalText = $btn.html();
                var originalClasses = $btn.attr('class');
                
                // Buton durumunu değiştir
                $btn.html('<i class="fas fa-spinner fa-spin"></i> Çekiliyor...')
                    .removeClass('btn-primary')
                    .addClass('btn-warning')
                    .prop('disabled', true);
                
                // Toast bildirim göster
                if (typeof toastr !== 'undefined') {
                    toastr.info('Etkinlik çekme işlemi başlatıldı...', 'Bilgi');
                }
                
                // AJAX ile etkinlik çekme işlemini başlat
                $.ajax({
                    url: '{{ route("admin.events.scrape") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        url: 'https://kultursanat.cankaya.bel.tr/etkinlikler',
                        connection_type: 'normal',
                        page: 1
                    },
                    timeout: 60000, // 60 saniye timeout
                    success: function(response) {
                        console.log('Etkinlik çekme başarılı:', response);
                        
                        if (response.success) {
                            var message = response.newEvents + ' yeni etkinlik eklendi';
                            if (response.newEvents === 0) {
                                message = 'Yeni etkinlik bulunamadı (tüm etkinlikler zaten mevcut)';
                            }
                            
                            if (typeof toastr !== 'undefined') {
                                toastr.success(message, 'Başarılı!');
                            } else {
                                alert('✅ ' + message);
                            }
                            
                            // Buton durumunu başarılı olarak değiştir
                            $btn.html('<i class="fas fa-check"></i> Tamamlandı')
                                .removeClass('btn-warning')
                                .addClass('btn-success');
                                
                            // 3 saniye sonra orijinal haline döndür
                            setTimeout(function() {
                                $btn.html(originalText)
                                    .attr('class', originalClasses)
                                    .prop('disabled', false);
                            }, 3000);
                        } else {
                            throw new Error(response.error || 'Bilinmeyen hata');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Etkinlik çekme hatası:', xhr, status, error);
                        
                        var errorMsg = 'Etkinlik çekme işlemi başarısız';
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            errorMsg = xhr.responseJSON.error;
                        } else if (status === 'timeout') {
                            errorMsg = 'İşlem zaman aşımına uğradı';
                        }
                        
                        if (typeof toastr !== 'undefined') {
                            toastr.error(errorMsg, 'Hata!');
                        } else {
                            alert('❌ ' + errorMsg);
                        }
                        
                        // Buton durumunu hata olarak değiştir
                        $btn.html('<i class="fas fa-times"></i> Hata')
                            .removeClass('btn-warning')
                            .addClass('btn-danger');
                            
                        // 3 saniye sonra orijinal haline döndür
                        setTimeout(function() {
                            $btn.html(originalText)
                                .attr('class', originalClasses)
                                .prop('disabled', false);
                        }, 3000);
                    }
                });
            });
        });
    </script>

</body>

</html> 