@extends('adminlte::page')

@section('title', 'Hizmetler Modülü Debug')

@section('content_header')
    <h1>Hizmetler Modülü Debug Bilgileri</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card bg-warning">
                <div class="card-header">
                    <h3 class="card-title">Debug Sayfası Erişim Bilgileri</h3>
                </div>
                
                <div class="card-body">
                    <div class="alert alert-info">
                        <h5><i class="icon fas fa-info"></i> Mevcut Sayfa Bilgileri</h5>
                        <p><strong>Mevcut URL:</strong> {{ $debugRoutes['current_url'] }}</p>
                        
                        <hr>
                        
                        <h5>Bu Sayfaya Erişim Rotaları:</h5>
                        <ul>
                            <li><strong>Doğrudan URL:</strong> {{ $debugRoutes['direct'] }}</li>
                            <li><strong>Named Route (direct.services.debug):</strong> {{ $debugRoutes['named_direct'] }}</li>
                            <li><strong>Admin Prefix ile URL:</strong> {{ $debugRoutes['admin_prefix'] }}</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Hizmetler Modülü Rotaları</h3>
                </div>
                
                <div class="card-body">
                    <div class="mb-4">
                        <h4>Hizmetler Modülü için Önemli Bağlantılar</h4>
                        <ul class="list-group">
                            <li class="list-group-item">
                                <a href="{{ route('admin.services.index') }}" target="_blank">Hizmetler Ana Sayfası</a>
                                <code>{{ route('admin.services.index') }}</code>
                            </li>
                            
                            <li class="list-group-item">
                                <!-- Kaldırıldı: Hizmetler Ayarları Sayfası -->
                                <del>Hizmetler Ayarları Sayfası (Kaldırılmıştır)</del>
                            </li>
                            
                            <li class="list-group-item">
                                <a href="{{ route('admin.services.debug') }}" target="_blank">Debug Sayfası (Route)</a>
                                <code>{{ route('admin.services.debug') }}</code>
                            </li>
                        </ul>
                    </div>
                    
                    <h5>Tüm Hizmetler Rotaları</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>URI</th>
                                    <th>Rota Adı</th>
                                    <th>HTTP Metodları</th>
                                    <th>Controller</th>
                                    <th>Middleware</th>
                                    <th>URL</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($serviceRoutes as $route)
                                    <tr>
                                        <td>{{ $route['uri'] }}</td>
                                        <td>{{ $route['name'] }}</td>
                                        <td>{{ implode(', ', $route['methods']) }}</td>
                                        <td>{{ $route['controller'] }}</td>
                                        <td>{{ $route['middleware'] ?? 'none' }}</td>
                                        <td>
                                            @if($route['name'])
                                                @php
                                                try {
                                                    $url = route($route['name']);
                                                    echo '<a href="'.$url.'" target="_blank">'.$url.'</a>';
                                                } catch (\Exception $e) {
                                                    echo 'Rota parametreleri eksik';
                                                }
                                                @endphp
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">Hizmetler Modülü View Dosyaları</h3>
                </div>
                
                <div class="card-body">
                    <ul class="list-group">
                        @foreach($views as $view)
                            <li class="list-group-item">{{ $view }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">Controller Dosyaları</h3>
                </div>
                
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <strong>ServiceController:</strong> 
                            <code>App\Http\Controllers\Admin\ServiceController</code>
                        </li>
                        <li class="list-group-item">
                            <strong>ServiceSettingsController (Kaldırılmıştır):</strong>
                            <code><del>App\Http\Controllers\Admin\ServiceSettingsController</del></code>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    code {
        display: block;
        background: #f8f9fa;
        padding: 5px;
        margin-top: 5px;
        border-radius: 4px;
    }
</style>
@stop 