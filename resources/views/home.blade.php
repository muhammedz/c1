@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Dashboard') }}</h3>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <p>{{ __('Başarıyla giriş yaptınız!') }}</p>
                    
                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ count($headlines ?? []) }}</h3>
                                    <p>Manşet Haberler</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-newspaper"></i>
                                </div>
                                <a href="{{ route('admin.news.index') }}" class="small-box-footer">
                                    Haberleri Yönet <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ count($normalNews ?? []) }}</h3>
                                    <p>Normal Haberler</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <a href="{{ route('admin.news.index') }}" class="small-box-footer">
                                    Haberleri Yönet <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="small-box bg-primary">
                                <div class="inner">
                                    <h3>Anasayfa</h3>
                                    <p>Anasayfa Yönetimi</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-home"></i>
                                </div>
                                <a href="{{ route('admin.homepage.index') }}" class="small-box-footer">
                                    Anasayfayı Yönet <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .small-box {
            margin-bottom: 20px;
        }
        
        .small-box .icon {
            font-size: 70px;
            color: rgba(0,0,0,0.15);
        }
        
        .small-box h3 {
            font-size: 38px;
            font-weight: bold;
            margin: 0 0 10px 0;
        }
    </style>
@stop

@section('js')
    <script></script>
@stop
