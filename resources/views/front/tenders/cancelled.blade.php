@extends('layouts.front')

@section('title', 'İptal Edilen İhaleler')
@section('meta_description', 'İptal edilen ihaleler listesi')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-9">
            <div class="page-header mb-4">
                <h1>İptal Edilen İhaleler</h1>
            </div>
            
            <div class="tender-nav mb-4">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('tenders.index') }}">Aktif İhaleler</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('tenders.completed') }}">Tamamlanan İhaleler</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('tenders.cancelled') }}">İptal Edilen İhaleler</a>
                    </li>
                </ul>
            </div>
            
            @if($tenders->count() > 0)
                <div class="tenders-list">
                    @foreach($tenders as $tender)
                        <div class="card mb-4 tender-card">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <a href="{{ route('tenders.show', $tender->slug) }}">{{ $tender->title }}</a>
                                </h5>
                                <div class="tender-meta mb-3">
                                    <span class="badge bg-info">{{ $tender->unit }}</span>
                                    <span class="badge bg-danger">İptal Edildi</span>
                                    @if($tender->kik_no)
                                        <span class="text-muted ms-3">KİK No: {{ $tender->kik_no }}</span>
                                    @endif
                                    @if($tender->tender_datetime)
                                        <span class="text-muted ms-3">
                                            <i class="far fa-calendar-alt"></i> 
                                            {{ $tender->tender_datetime->format('d.m.Y H:i') }}
                                        </span>
                                    @endif
                                </div>
                                <p class="card-text">{{ Str::limit($tender->summary, 150) }}</p>
                                <a href="{{ route('tenders.show', $tender->slug) }}" class="btn btn-outline-primary btn-sm">
                                    Detayları Görüntüle
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="pagination-container">
                    {{ $tenders->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    İptal edilen ihale bulunmamaktadır.
                </div>
            @endif
        </div>
        
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h5>İhale Durumları</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <a href="{{ route('tenders.index') }}">Aktif İhaleler</a>
                        </li>
                        <li class="list-group-item">
                            <a href="{{ route('tenders.completed') }}">Tamamlanan İhaleler</a>
                        </li>
                        <li class="list-group-item">
                            <a href="{{ route('tenders.cancelled') }}">İptal Edilen İhaleler</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
    .tender-card {
        transition: all 0.3s;
    }
    .tender-card:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .tender-card .card-title a {
        color: #333;
        text-decoration: none;
    }
    .tender-card .card-title a:hover {
        color: #007bff;
    }
    .tender-meta {
        font-size: 0.9rem;
    }
</style>
@endsection