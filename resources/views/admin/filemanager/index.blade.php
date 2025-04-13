@extends('adminlte::page')

@section('title', 'Dosya Yöneticisi')

@section('content_header')
    <h1>Dosya Yöneticisi</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <iframe src="{{ url('admin/file-manager') }}" style="width: 100%; height: 700px; overflow: hidden; border: none;"></iframe>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        // iframe yüksekliğini pencere boyutuna göre ayarla
        function resizeIframe() {
            const iframe = document.querySelector('iframe');
            const windowHeight = window.innerHeight;
            const offset = iframe.getBoundingClientRect().top;
            iframe.style.height = (windowHeight - offset - 50) + 'px';
        }

        window.addEventListener('load', resizeIframe);
        window.addEventListener('resize', resizeIframe);
    </script>
@stop 