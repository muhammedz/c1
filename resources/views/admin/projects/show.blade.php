@extends('adminlte::page')

@section('title', 'Proje Detayı')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="m-0 text-dark">Proje Detayı: {{ $project->title }}</h1>
        <div>
            <a href="{{ route('admin.projects.edit', $project->id) }}" class="btn btn-primary">
                <i class="fas fa-edit mr-1"></i> Düzenle
            </a>
            <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary ml-2">
                <i class="fas fa-arrow-left mr-1"></i> Projelere Dön
            </a>
        </div>
    </div>
@stop

@section('content')
<div class="row">
    <div class="col-lg-8">
        <!-- Proje Bilgileri -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Proje Bilgileri</h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-lg-12">
                        @if($project->cover_image)
                            <img src="{{ asset('storage/' . $project->cover_image) }}" alt="{{ $project->title }}" class="img-fluid rounded mb-3">
                        @endif
                    </div>
                </div>
                
                <div class="mb-4">
                    <h3>{{ $project->title }}</h3>
                    <p class="text-muted">{{ $project->slug }}</p>
                </div>
                
                <div class="mb-4">
                    {!! $project->description !!}
                </div>
                
                @if($project->images && $project->images->count() > 0)
                    <div class="mb-4">
                        <h4>Proje Galerisi</h4>
                        <div class="row">
                            @foreach($project->images as $image)
                                <div class="col-md-3 mb-3">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" class="img-fluid rounded" alt="Proje Görseli">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Proje Detayları -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Detaylar</h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th style="width: 40%">Kategori</th>
                        <td>{{ $project->category ? $project->category->name : 'Belirtilmemiş' }}</td>
                    </tr>
                    <tr>
                        <th>Tamamlanma</th>
                        <td>
                            <div class="progress">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $project->completion_percentage }}%" aria-valuenow="{{ $project->completion_percentage }}" aria-valuemin="0" aria-valuemax="100">{{ $project->completion_percentage }}%</div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>Proje Tarihi</th>
                        <td>{{ $project->project_date ? $project->project_date->format('d.m.Y') : 'Belirtilmemiş' }}</td>
                    </tr>
                    <tr>
                        <th>Sıralama</th>
                        <td>{{ $project->order }}</td>
                    </tr>
                    <tr>
                        <th>Durum</th>
                        <td>
                            @if($project->is_active)
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-danger">Pasif</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Anasayfada Göster</th>
                        <td>
                            @if($project->show_on_homepage)
                                <span class="badge badge-success">Evet</span>
                            @else
                                <span class="badge badge-danger">Hayır</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@stop 