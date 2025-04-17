@extends('adminlte::page')

@section('title', 'Test Sayfası')

@section('content_header')
    <h1>Test Sayfası</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Test Sayfası İçeriği</h3>
            </div>
            <div class="card-body">
                <p>Bu bir test sayfasıdır.</p>
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
        $(document).ready(function() {
            // Test sayfası JS kodları
        });
    </script>
@stop 