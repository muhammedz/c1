@extends('adminlte::page')

@section('title', 'Müdürlük Görüntüle')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>{{ $mudurluk->name }}</h1>
        <div>
            @if($mudurluk->slug)
                <a href="{{ route('mudurlukler.show', $mudurluk->slug) }}" class="btn btn-info" target="_blank">
                    <i class="fas fa-external-link-alt"></i> Sitede Görüntüle
                </a>
            @endif
            <a href="{{ route('admin.mudurlukler.edit', $mudurluk) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Düzenle
            </a>
            <a href="{{ route('admin.mudurlukler.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Geri Dön
            </a>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sol Kolon -->
        <div class="col-lg-8">
            <!-- Temel Bilgiler -->
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-info-circle"></i> Temel Bilgiler</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Müdürlük Adı:</strong><br>
                            {{ $mudurluk->name }}
                        </div>
                        <div class="col-md-6">
                            <strong>URL Slug:</strong><br>
                            <code>{{ $mudurluk->slug }}</code>
                        </div>
                    </div>
                    
                    @if($mudurluk->summary)
                        <div class="mt-3">
                            <strong>Kısa Açıklama:</strong><br>
                            {{ $mudurluk->summary }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Görev Tanımı ve Faaliyet Alanı -->
            @if($mudurluk->gorev_tanimi_ve_faaliyet_alani)
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-tasks"></i> Görev Tanımı ve Faaliyet Alanı</h5>
                    </div>
                    <div class="card-body">
                        {!! $mudurluk->gorev_tanimi_ve_faaliyet_alani !!}
                    </div>
                </div>
            @endif

            <!-- Yetki ve Sorumluluklar -->
            @if($mudurluk->yetki_ve_sorumluluklar)
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-user-shield"></i> Yetki ve Sorumluluklar</h5>
                    </div>
                    <div class="card-body">
                        {!! $mudurluk->yetki_ve_sorumluluklar !!}
                    </div>
                </div>
            @endif

            <!-- PDF Dosyaları -->
            @if($mudurluk->files->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-file-pdf"></i> PDF Dosyaları</h5>
                    </div>
                    <div class="card-body">
                        <!-- Hizmet Standartları -->
                        @php
                            $hizmetStandartlari = $mudurluk->files->where('type', 'hizmet_standartlari')->where('is_active', true);
                        @endphp
                        @if($hizmetStandartlari->count() > 0)
                            <div class="mb-4">
                                <h6 class="text-primary"><i class="fas fa-clipboard-list"></i> Hizmet Standartları</h6>
                                <div class="list-group">
                                    @foreach($hizmetStandartlari as $file)
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="fas fa-file-pdf text-danger me-2"></i>
                                                <strong>{{ $file->title }}</strong><br>
                                                <small class="text-muted">{{ $file->file_name }} ({{ number_format($file->file_size / 1024, 2) }} KB)</small>
                                            </div>
                                            <a href="{{ route('mudurlukler.download-file', [$mudurluk->slug, $file]) }}" 
                                               class="btn btn-sm btn-primary" target="_blank">
                                                <i class="fas fa-download"></i> İndir
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Yönetim Şemaları -->
                        @php
                            $yonetimSemalari = $mudurluk->files->where('type', 'yonetim_semalari')->where('is_active', true);
                        @endphp
                        @if($yonetimSemalari->count() > 0)
                            <div class="mb-3">
                                <h6 class="text-success"><i class="fas fa-sitemap"></i> Yönetim Şemaları</h6>
                                <div class="list-group">
                                    @foreach($yonetimSemalari as $file)
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="fas fa-file-pdf text-danger me-2"></i>
                                                <strong>{{ $file->title }}</strong><br>
                                                <small class="text-muted">{{ $file->file_name }} ({{ number_format($file->file_size / 1024, 2) }} KB)</small>
                                            </div>
                                            <a href="{{ route('mudurlukler.download-file', [$mudurluk->slug, $file]) }}" 
                                               class="btn btn-sm btn-primary" target="_blank">
                                                <i class="fas fa-download"></i> İndir
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Sağ Kolon -->
        <div class="col-lg-4">
            <!-- Durum ve İstatistikler -->
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-chart-bar"></i> Durum ve İstatistikler</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="bg-light p-3 rounded">
                                @if($mudurluk->is_active)
                                    <span class="badge badge-success badge-lg">Aktif</span>
                                @else
                                    <span class="badge badge-warning badge-lg">Pasif</span>
                                @endif
                                <br><small>Durum</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light p-3 rounded">
                                <strong>{{ $mudurluk->order_column }}</strong><br>
                                <small>Sıralama</small>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="bg-light p-3 rounded">
                                <strong>{{ $mudurluk->view_count ?? 0 }}</strong><br>
                                <small>Görüntülenme</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light p-3 rounded">
                                <strong>{{ $mudurluk->files->count() }}</strong><br>
                                <small>Toplam Dosya</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ana Görsel -->
            @if($mudurluk->image)
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-image"></i> Ana Görsel</h5>
                    </div>
                    <div class="card-body text-center">
                        <img src="{{ asset('storage/' . $mudurluk->image) }}" 
                             alt="{{ $mudurluk->name }}" 
                             class="img-fluid rounded" 
                             style="max-height: 300px;">
                    </div>
                </div>
            @endif

            <!-- SEO Bilgileri -->
            @if($mudurluk->meta_title || $mudurluk->meta_description)
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-search"></i> SEO Bilgileri</h5>
                    </div>
                    <div class="card-body">
                        @if($mudurluk->meta_title)
                            <div class="mb-3">
                                <strong>Meta Başlık:</strong><br>
                                {{ $mudurluk->meta_title }}
                            </div>
                        @endif
                        
                        @if($mudurluk->meta_description)
                            <div class="mb-3">
                                <strong>Meta Açıklama:</strong><br>
                                {{ $mudurluk->meta_description }}
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Tarih Bilgileri -->
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-calendar"></i> Tarih Bilgileri</h5>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <strong>Oluşturulma:</strong><br>
                        {{ $mudurluk->created_at->format('d.m.Y H:i') }}
                    </div>
                    <div class="mb-2">
                        <strong>Son Güncelleme:</strong><br>
                        {{ $mudurluk->updated_at->format('d.m.Y H:i') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .card {
        margin-bottom: 1.5rem;
        box-shadow: 0 0 10px rgba(0,0,0,.1);
    }
    
    .badge-lg {
        font-size: 1rem;
        padding: 0.5rem 1rem;
    }
    
    .list-group-item {
        border-left: 4px solid #007bff;
    }
    
    .list-group-item:hover {
        background-color: #f8f9fa;
    }
</style>
@stop 