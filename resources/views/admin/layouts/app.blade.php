@extends('adminlte::page')

@section('adminlte_css_pre')
    <!-- Dinamik Favicon -->
    @if(isset($siteFavicon) && $siteFavicon && $siteFavicon->value)
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('uploads/' . $siteFavicon->value) }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('uploads/' . $siteFavicon->value) }}">
        <link rel="shortcut icon" href="{{ asset('uploads/' . $siteFavicon->value) }}">
        <style>
            /* AdminLTE'nin varsayılan favicon'unu gizle */
            link[rel="shortcut icon"][href*="favicons/favicon.ico"] {
                display: none !important;
            }
        </style>
    @endif
@stop 