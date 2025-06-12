@extends('layouts.app')

@section('title', 'Hizmet Konuları | Çankaya Belediyesi')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="page-header mb-5 text-center">
                <h1 class="display-4 mb-3">Hizmet Konuları</h1>
                <p class="lead text-muted">Çankaya Belediyesi hizmetlerini konularına göre keşfedin</p>
            </div>
        </div>
    </div>
    
    <div class="row">
        @forelse($serviceTopics as $topic)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 shadow-sm border-0 service-topic-card">
                    <div class="card-body d-flex flex-column">
                        <div class="text-center mb-3">
                            <div class="service-topic-icon mb-3" style="color: {{ $topic->color }};">
                                <i class="{{ $topic->icon }} fa-3x"></i>
                            </div>
                            <h5 class="card-title">{{ $topic->name }}</h5>
                        </div>
                        
                        @if($topic->description)
                            <p class="card-text text-muted mb-3">{{ Str::limit($topic->description, 100) }}</p>
                        @endif
                        
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <small class="text-muted">
                                    <i class="fas fa-list-ul"></i> 
                                    {{ $topic->services_count }} hizmet
                                </small>
                                @if($topic->services_count > 0)
                                    <span class="badge badge-primary">Aktif</span>
                                @else
                                    <span class="badge badge-secondary">Hizmet Yok</span>
                                @endif
                            </div>
                            
                            @if($topic->services_count > 0)
                                <a href="{{ route('services.topics.show', $topic->slug) }}" 
                                   class="btn btn-outline-primary btn-block">
                                    <i class="fas fa-arrow-right mr-1"></i>
                                    Hizmetleri Gör
                                </a>
                            @else
                                <button class="btn btn-outline-secondary btn-block" disabled>
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Hizmet Bulunmuyor
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle fa-2x mb-3"></i>
                    <h4>Henüz Hizmet Konusu Bulunmuyor</h4>
                    <p class="mb-0">Şu anda tanımlanmış hizmet konusu bulunmamaktadır. Lütfen daha sonra tekrar kontrol edin.</p>
                </div>
            </div>
        @endforelse
    </div>
    
    @if($serviceTopics->count() > 0)
        <div class="row mt-5">
            <div class="col-12 text-center">
                <div class="bg-light p-4 rounded">
                    <h5 class="mb-3">Aradığınız hizmeti bulamadınız mı?</h5>
                    <p class="text-muted mb-3">Tüm hizmetlerimizi görüntülemek için hizmetler sayfasını ziyaret edebilirsiniz.</p>
                    <a href="{{ route('services.index') }}" class="btn btn-primary">
                        <i class="fas fa-list mr-1"></i>
                        Tüm Hizmetler
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
.service-topic-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.service-topic-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
}

.service-topic-icon {
    transition: transform 0.2s ease-in-out;
}

.service-topic-card:hover .service-topic-icon {
    transform: scale(1.1);
}

.badge {
    font-size: 0.8em;
}
</style>
@endsection 