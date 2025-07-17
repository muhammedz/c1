@extends('adminlte::page')

@section('title', 'Yapılan Aramalar')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <!-- Yapılan Aramalar -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Yapılan Aramalar</h3>
                </div>
                <div class="card-body">
                    <!-- İstatistikler -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-search"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Toplam Arama</span>
                                    <span class="info-box-number">{{ $searchStats['total_searches'] ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-list"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Farklı Kelime</span>
                                    <span class="info-box-number">{{ $searchStats['unique_queries'] ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-chart-line"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Ort. Sonuç</span>
                                    <span class="info-box-number">{{ number_format($searchStats['avg_results'] ?? 0, 1) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <!-- En Çok Aranan Kelimeler -->
                        <div class="col-md-6">
                            <h5>En Çok Aranan Kelimeler (Son 30 Gün)</h5>
                            <div class="table-responsive">
                                <table class="table table-sm table-striped">
                                    <thead>
                                        <tr>
                                            <th>Arama Kelimesi</th>
                                            <th>Arama Sayısı</th>
                                            <th>Son Arama</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($popularSearches as $search)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('search') }}?q={{ urlencode($search->query) }}" target="_blank" class="text-decoration-none">
                                                        {{ $search->query }}
                                                        <i class="fas fa-external-link-alt fa-xs ml-1"></i>
                                                    </a>
                                                </td>
                                                <td><span class="badge badge-primary">{{ $search->search_count }}</span></td>
                                                <td><small class="text-muted">{{ $search->last_searched->diffForHumans() }}</small></td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center text-muted">Henüz arama yapılmamış.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Son Aramalar -->
                        <div class="col-md-6">
                            <h5>Son Aramalar</h5>
                            <div class="table-responsive">
                                <table class="table table-sm table-striped">
                                    <thead>
                                        <tr>
                                            <th>Arama Kelimesi</th>
                                            <th>Sonuç</th>
                                            <th>Tarih</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentSearches as $search)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('search') }}?q={{ urlencode($search->query) }}" target="_blank" class="text-decoration-none">
                                                        {{ Str::limit($search->query, 30) }}
                                                        <i class="fas fa-external-link-alt fa-xs ml-1"></i>
                                                    </a>
                                                </td>
                                                <td>
                                                    @if($search->results_count > 0)
                                                        <span class="badge badge-success">{{ $search->results_count }}</span>
                                                    @else
                                                        <span class="badge badge-danger">0</span>
                                                    @endif
                                                </td>
                                                <td><small class="text-muted">{{ $search->searched_at->diffForHumans() }}</small></td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center text-muted">Henüz arama yapılmamış.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop 