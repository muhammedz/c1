@extends('adminlte::page')

@section('title', 'Duyurular Yönetimi')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Duyurular Yönetimi</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Kontrol Paneli</a></li>
                    <li class="breadcrumb-item active">Duyurular</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Duyurular Listesi</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Yeni Duyuru Ekle
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th style="width: 50px">#</th>
                                <th>Başlık</th>
                                <th>Pozisyon</th>
                                <th>Renk</th>
                                <th>Max Görüntüleme</th>
                                <th style="width: 120px">Durum</th>
                                <th style="width: 150px">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($announcements as $announcement)
                                <tr>
                                    <td>{{ $announcement->id }}</td>
                                    <td>{{ $announcement->title }}</td>
                                    <td>
                                        @if($announcement->position == 'top')
                                            <span class="badge badge-info">Üst</span>
                                        @elseif($announcement->position == 'bottom')
                                            <span class="badge badge-info">Alt</span>
                                        @elseif($announcement->position == 'left')
                                            <span class="badge badge-info">Sol</span>
                                        @elseif($announcement->position == 'right')
                                            <span class="badge badge-info">Sağ</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div style="width: 20px; height: 20px; background-color: {{ $announcement->bg_color }}; border: 1px solid #ddd; margin-right: 5px;"></div>
                                            <small>{{ $announcement->bg_color }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        @if($announcement->max_views_per_user == 0)
                                            <span class="badge badge-secondary">Sınırsız</span>
                                        @else
                                            <span class="badge badge-primary">{{ $announcement->max_views_per_user }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <form action="{{ route('admin.announcements.toggle-active', $announcement->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm {{ $announcement->active ? 'btn-success' : 'btn-danger' }}">
                                                {{ $announcement->active ? 'Aktif' : 'Pasif' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.announcements.edit', $announcement->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-edit"></i> Düzenle
                                        </a>
                                        <form action="{{ route('admin.announcements.destroy', $announcement->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Emin misiniz?')">
                                                <i class="fas fa-trash"></i> Sil
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Henüz duyuru bulunmuyor.</td>
                                </tr>
                            @endforelse
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
    .card-title {
        font-weight: 600;
    }
</style>
@stop

@section('js')
<script>
    $(function() {
        // Sayfa özellikleri
    });
</script>
@stop 