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
                    <h3 class="card-title">Anasayfa Yönetimi</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- İlk Satır -->
                        <div class="col-md-4">
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-primary btn-lg btn-block mb-3">
                                <i class="fas fa-tachometer-alt mr-2"></i> Genel Görünüm
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('admin.profile') }}" class="btn btn-secondary btn-lg btn-block mb-3">
                                <i class="fas fa-user mr-2"></i> Profil Bilgileri
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="#" class="btn btn-info btn-lg btn-block mb-3">
                                <i class="fas fa-mobile-alt mr-2"></i> Mobil Uygulama
                            </a>
                        </div>
                        
                        <!-- İkinci Satır -->
                        <div class="col-md-4">
                            <a href="#" class="btn btn-warning btn-lg btn-block mb-3">
                                <i class="fas fa-image mr-2"></i> Logo ve Planlar
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="#" class="btn btn-success btn-lg btn-block mb-3">
                                <i class="fas fa-star mr-2"></i> Öne Çıkan Hizmetler
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('admin.homepage.header') }}" class="btn btn-dark btn-lg btn-block mb-3">
                                <i class="fas fa-heading mr-2"></i> Header Yönetimi
                            </a>
                        </div>
                        
                        <!-- Üçüncü Satır -->
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
                        <div class="col-md-4">
                            <a href="{{ route('admin.menusystem.index') }}" class="btn btn-warning btn-lg btn-block mb-3">
                                <i class="fas fa-sitemap mr-2"></i> Menü Sistemi
                            </a>
                        </div>
                        
                        <!-- Dördüncü Satır -->
                        <div class="col-md-4">
                            <a href="{{ route('admin.footer.index') }}" class="btn btn-info btn-lg btn-block mb-3">
                                <i class="fas fa-layer-group mr-2"></i> Footer Yönetimi
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