@extends('adminlte::page')

@section('title', 'Dosya Yönetim Sistemi - Dashboard')

@section('content_header')
    <h1>Dosya Yönetim Sistemi Dashboard</h1>
@stop

@section('content')
    <div class="row">
        <!-- İstatistik Kartları -->
        <div class="col-md-3">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $stats['total_files'] }}</h3>
                    <p>Toplam Dosya</p>
                </div>
                <div class="icon">
                    <i class="fas fa-file"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $stats['total_folders'] }}</h3>
                    <p>Toplam Klasör</p>
                </div>
                <div class="icon">
                    <i class="fas fa-folder"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $stats['total_categories'] }}</h3>
                    <p>Toplam Kategori</p>
                </div>
                <div class="icon">
                    <i class="fas fa-tags"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ number_format($stats['total_size'] / 1024 / 1024, 2) }} MB</h3>
                    <p>Toplam Boyut</p>
                </div>
                <div class="icon">
                    <i class="fas fa-hdd"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Son Yüklenen Dosyalar -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Son Yüklenen Dosyalar</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Dosya</th>
                                    <th>Boyut</th>
                                    <th>Tarih</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stats['recent_uploads'] as $file)
                                    <tr>
                                        <td>
                                            <i class="fas fa-file mr-2"></i>
                                            {{ $file->name }}
                                        </td>
                                        <td>{{ number_format($file->size / 1024, 2) }} KB</td>
                                        <td>{{ $file->created_at->format('d.m.Y H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- En Çok Dosya İçeren Klasörler -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">En Çok Dosya İçeren Klasörler</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Klasör</th>
                                    <th>Dosya Sayısı</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stats['top_folders'] as $folder)
                                    <tr>
                                        <td>
                                            <i class="fas fa-folder mr-2"></i>
                                            {{ $folder->name }}
                                        </td>
                                        <td>{{ $folder->media_count }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <!-- En Çok Dosya İçeren Kategoriler -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">En Çok Dosya İçeren Kategoriler</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Kategori</th>
                                    <th>Dosya Sayısı</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stats['top_categories'] as $category)
                                    <tr>
                                        <td>
                                            <i class="fas fa-tag mr-2" style="color: {{ $category->color }}"></i>
                                            {{ $category->name }}
                                        </td>
                                        <td>{{ $category->media_count }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .small-box {
            border-radius: 0.25rem;
            box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
            display: block;
            margin-bottom: 20px;
            position: relative;
        }
        .small-box > .inner {
            padding: 10px;
        }
        .small-box h3 {
            font-size: 2.2rem;
            font-weight: 700;
            margin: 0 0 10px 0;
            padding: 0;
            white-space: nowrap;
        }
        .small-box p {
            font-size: 1rem;
        }
        .small-box .icon {
            color: rgba(0,0,0,.15);
            z-index: 0;
        }
        .small-box .icon > i {
            font-size: 70px;
            position: absolute;
            right: 15px;
            top: 15px;
            transition: transform .3s linear;
        }
    </style>
@stop 