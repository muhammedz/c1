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
                                        <input type="hidden" name="show_quick_links" value="0">
                                        <input type="checkbox" class="custom-control-input" id="showQuickLinks" name="show_quick_links" value="1" {{ $settings->show_quick_links ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="showQuickLinks">Hızlı Aramaları Göster</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mt-4">
                                    <div class="custom-control custom-switch">
                                        <input type="hidden" name="show_popular_queries" value="0">
                                        <input type="checkbox" class="custom-control-input" id="showPopularQueries" name="show_popular_queries" value="1" {{ $settings->show_popular_queries ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="showPopularQueries">Popüler Aramaları Göster</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="hidden" name="search_in_mudurluk_files" value="0">
                                        <input type="checkbox" class="custom-control-input" id="searchInMudurlukFiles" name="search_in_mudurluk_files" value="1" {{ $settings->search_in_mudurluk_files ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="searchInMudurlukFiles">
                                            <i class="fas fa-file-alt mr-1"></i>
                                            Müdürlük Dosyalarında Arama Yapmayı Etkinleştir
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">
                                        Bu seçenek aktif edildiğinde, arama sonuçlarında müdürlüklerin dosya ve dokümanları da görüntülenir.
                                    </small>
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
            
            <!-- Öncelik Linkleri -->
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-link"></i> Öncelik Linkleri
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.search-priority-links.create') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus"></i> Yeni Ekle
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="priorityLinksTable">
                            <thead>
                                <tr>
                                    <th>Anahtar Kelimeler</th>
                                    <th>Başlık</th>
                                    <th>URL</th>
                                    <th>İkon</th>
                                    <th>Öncelik</th>
                                    <th>Tıklama Sayısı</th>
                                    <th>Durum</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($priorityLinks ?? [] as $link)
                                    <tr>
                                        <td>
                                            <small class="text-muted">{{ Str::limit($link->search_keywords, 50) }}</small>
                                        </td>
                                        <td>
                                            <strong>{{ $link->title }}</strong>
                                            @if($link->description)
                                                <br><small class="text-muted">{{ Str::limit($link->description, 60) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <code>{{ $link->url }}</code>
                                        </td>
                                        <td class="text-center">
                                            @if($link->icon)
                                                <i class="{{ $link->icon }}" title="{{ $link->icon }}"></i>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm priority-order-input" min="1" value="{{ $link->priority }}" data-id="{{ $link->id }}" style="width: 60px">
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-primary">{{ $link->click_count ?? 0 }}</span>
                                            <small class="text-muted d-block">tıklama</small>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input priority-toggle-active" id="priorityActive{{ $link->id }}" data-id="{{ $link->id }}" {{ $link->is_active ? 'checked' : '' }} data-url="{{ route('admin.search-priority-links.toggle-active', $link->id) }}">
                                                <label class="custom-control-label" for="priorityActive{{ $link->id }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.search-priority-links.edit', $link->id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.search-priority-links.destroy', $link->id) }}" method="POST" class="d-inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bu öncelik linkini silmek istediğinizden emin misiniz?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Henüz öncelik linki eklenmemiş.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- En Çok Tıklanan Öncelik Linkleri -->
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line"></i> En Çok Tıklanan Öncelik Linkleri
                    </h3>
                </div>
                <div class="card-body">
                    @if($priorityLinksByClicks->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Başlık</th>
                                        <th>URL</th>
                                        <th>Tıklama Sayısı</th>
                                        <th>Durum</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($priorityLinksByClicks as $index => $link)
                                        <tr>
                                            <td>
                                                <span class="badge badge-{{ $index < 3 ? 'success' : 'secondary' }}">{{ $index + 1 }}</span>
                                            </td>
                                            <td>
                                                <strong>{{ $link->title }}</strong>
                                                @if($link->description)
                                                    <br><small class="text-muted">{{ Str::limit($link->description, 40) }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <code>{{ $link->url }}</code>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-primary badge-lg">{{ $link->click_count }}</span>
                                            </td>
                                            <td class="text-center">
                                                @if($link->is_active)
                                                    <span class="badge badge-success">Aktif</span>
                                                @else
                                                    <span class="badge badge-secondary">Pasif</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted">
                            <p>Henüz tıklama verisi bulunmuyor.</p>
                        </div>
                    @endif
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
        
        // Priority Links için sıralama değişikliği
        let priorityLinksTimer;
        $('.priority-order-input').on('change', function() {
            clearTimeout(priorityLinksTimer);
            
            const items = [];
            $('.priority-order-input').each(function() {
                items.push({
                    id: $(this).data('id'),
                    priority: $(this).val()
                });
            });
            
            priorityLinksTimer = setTimeout(function() {
                $.ajax({
                    url: '{{ route("admin.search-priority-links.order") }}',
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
        
        // Priority Links için aktiflik durumu değiştirme
        $('.priority-toggle-active').on('change', function() {
            const url = $(this).data('url');
            
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