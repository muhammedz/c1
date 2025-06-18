<footer class="main-footer">
    @hasSection('footer')
        @yield('footer')
    @else
        <div class="float-right d-none d-sm-block">
            
        </div>
        <strong>Copyright &copy; {{ date('Y') }} <a href="https://www.epoxsoft.com" target="_blank">EPOXSOFT CMS YÖNETİM PANELİ</a>.</strong>
        Tüm hakları saklıdır.
    @endif
</footer> 