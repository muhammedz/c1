@extends('adminlte::page')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Arama Ayarları</h3>
                </div>
                
                <!-- Bildirimler -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        {{ session('success') }}
                    </div>
                @endif
                
                <!-- Genel Ayarlar -->
                <div class="card-body">
                    <form action="{{ route('admin.search-settings.update') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Arama Başlığı</label>
                                    <input type="text" name="title" class="form-control" value="{{ $settings->title }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Arama Placeholder</label>
                                    <input type="text" name="placeholder" class="form-control" value="{{ $settings->placeholder }}" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Maksimum Hızlı Arama Sayısı</label>
                                    <input type="number" name="max_quick_links" class="form-control" value="{{ $settings->max_quick_links }}" min="0" max="10" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Maksimum Popüler Arama Sayısı</label>
                                    <input type="number" name="max_popular_queries" class="form-control" value="{{ $settings->max_popular_queries }}" min="0" max="10" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mt-4">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="showQuickLinks" name="show_quick_links" {{ $settings->show_quick_links ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="showQuickLinks">Hızlı Aramaları Göster</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mt-4">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="showPopularQueries" name="show_popular_queries" {{ $settings->show_popular_queries ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="showPopularQueries">Popüler Aramaları Göster</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Ayarları Kaydet</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Hızlı Aramalar -->
            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Hızlı Aramalar</h3>
                    <a href="{{ route('admin.search-quick-links.create') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> Yeni Ekle
                    </a>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Başlık</th>
                                <th>URL</th>
                                <th style="width: 80px">Sıra</th>
                                <th style="width: 80px">Durum</th>
                                <th style="width: 120px">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody id="quickLinksTable">
                            @forelse($quickLinks as $link)
                                <tr data-id="{{ $link->id }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $link->title }}</td>
                                    <td><code>{{ $link->url }}</code></td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm order-input" min="0" value="{{ $link->order }}" data-id="{{ $link->id }}" style="width: 60px">
                                    </td>
                                    <td>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input toggle-active" id="quickLinkActive{{ $link->id }}" data-id="{{ $link->id }}" {{ $link->is_active ? 'checked' : '' }} data-url="{{ route('admin.search-quick-links.toggle-active', $link->id) }}">
                                            <label class="custom-control-label" for="quickLinkActive{{ $link->id }}"></label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.search-quick-links.edit', $link->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.search-quick-links.destroy', $link->id) }}" method="POST" class="d-inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Emin misiniz?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Henüz hızlı arama bağlantısı eklenmemiş.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Popüler Aramalar -->
            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Popüler Aramalar</h3>
                    <a href="{{ route('admin.search-popular-queries.create') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> Yeni Ekle
                    </a>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Başlık</th>
                                <th>URL</th>
                                <th style="width: 80px">İkon</th>
                                <th style="width: 80px">Sıra</th>
                                <th style="width: 80px">Durum</th>
                                <th style="width: 120px">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody id="popularQueriesTable">
                            @forelse($popularQueries as $query)
                                <tr data-id="{{ $query->id }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $query->title }}</td>
                                    <td><code>{{ $query->url }}</code></td>
                                    <td class="text-center">
                                        @if($query->icon)
                                            <span class="material-icons">{{ $query->icon }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm order-input" min="0" value="{{ $query->order }}" data-id="{{ $query->id }}" style="width: 60px">
                                    </td>
                                    <td>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input toggle-active" id="queryActive{{ $query->id }}" data-id="{{ $query->id }}" {{ $query->is_active ? 'checked' : '' }} data-url="{{ route('admin.search-popular-queries.toggle-active', $query->id) }}">
                                            <label class="custom-control-label" for="queryActive{{ $query->id }}"></label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.search-popular-queries.edit', $query->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.search-popular-queries.destroy', $query->id) }}" method="POST" class="d-inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Emin misiniz?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Henüz popüler arama sorgusu eklenmemiş.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Yapılan Aramalar -->
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">Yapılan Aramalar</h3>
                </div>
                <div class="card-body">
                    <!-- İstatistikler -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-search"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Toplam Arama</span>
                                    <span class="info-box-number">{{ $searchStats['total_searches'] ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-list"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Farklı Kelime</span>
                                    <span class="info-box-number">{{ $searchStats['unique_queries'] ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-chart-line"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Ort. Sonuç</span>
                                    <span class="info-box-number">{{ number_format($searchStats['avg_results'] ?? 0, 1) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-danger"><i class="fas fa-exclamation-triangle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Sonuçsuz</span>
                                    <span class="info-box-number">{{ $searchStats['zero_results'] ?? 0 }}</span>
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
@endsection

@section('scripts')
<script>
    $(function() {
        // Sıralama değiştiğinde AJAX isteği gönder (Quick Links)
        let quickLinksTimer;
        $('#quickLinksTable .order-input').on('change', function() {
            clearTimeout(quickLinksTimer);
            
            const items = [];
            $('#quickLinksTable .order-input').each(function() {
                items.push({
                    id: $(this).data('id'),
                    order: $(this).val()
                });
            });
            
            quickLinksTimer = setTimeout(function() {
                $.ajax({
                    url: '{{ route("admin.search-quick-links.order") }}',
                    type: 'POST',
                    data: {
                        items: items,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success('Sıralama güncellendi');
                        }
                    },
                    error: function() {
                        toastr.error('Sıralama güncellenirken bir hata oluştu');
                    }
                });
            }, 500);
        });
        
        // Sıralama değiştiğinde AJAX isteği gönder (Popular Queries)
        let popularQueriesTimer;
        $('#popularQueriesTable .order-input').on('change', function() {
            clearTimeout(popularQueriesTimer);
            
            const items = [];
            $('#popularQueriesTable .order-input').each(function() {
                items.push({
                    id: $(this).data('id'),
                    order: $(this).val()
                });
            });
            
            popularQueriesTimer = setTimeout(function() {
                $.ajax({
                    url: '{{ route("admin.search-popular-queries.order") }}',
                    type: 'POST',
                    data: {
                        items: items,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success('Sıralama güncellendi');
                        }
                    },
                    error: function() {
                        toastr.error('Sıralama güncellenirken bir hata oluştu');
                    }
                });
            }, 500);
        });
        
        // Aktiflik durumu değiştiğinde AJAX isteği gönder
        $('.toggle-active').on('change', function() {
            const url = $(this).data('url');
            const id = $(this).data('id');
            
            $.ajax({
                url: url,
                type: 'PATCH',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success('Durum güncellendi');
                    }
                },
                error: function() {
                    toastr.error('Durum güncellenirken bir hata oluştu');
                }
            });
        });
    });
</script>
@endsection 