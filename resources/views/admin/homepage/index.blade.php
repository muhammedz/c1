@extends('adminlte::page')

@section('title', 'Anasayfa Yönetimi')

@section('content_header')
    <h1 class="m-0 text-dark">Anasayfa Yönetimi</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Anasayfa Bileşenleri</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Slider Yönetimi Kartı -->
                        <div class="col-md-6 col-lg-4">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $sliderCount }}</h3>
                                    <p>Slider Görseli</p>
                                    <p class="text-sm mb-0">{{ $activeSliderCount }} aktif görsel</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-images"></i>
                                </div>
                                <a href="{{ route('admin.homepage.sliders') }}" class="small-box-footer">
                                    Yönet <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                        
                        <!-- Quick Menu Yönetimi Kartı -->
                        <div class="col-md-6 col-lg-4">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ $menuCategoryCount }}</h3>
                                    <p>Hızlı Menü Kategorisi</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-bars"></i>
                                </div>
                                <a href="{{ route('admin.homepage.quick-menus.index') }}" class="small-box-footer">
                                    Yönet <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Yönetim Bağlantıları</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <a href="{{ route('admin.homepage.sliders') }}" class="btn btn-primary btn-lg btn-block mb-3">
                                <i class="fas fa-images mr-2"></i> Slider Yönetimi
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('admin.homepage.quick-menus.index') }}" class="btn btn-success btn-lg btn-block mb-3">
                                <i class="fas fa-bars mr-2"></i> Hızlı Menü Yönetimi
                            </a>
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
    <script>
        // Burada JavaScript kodları olacak
    </script>
@stop 