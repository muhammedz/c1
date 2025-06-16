@extends('adminlte::page')

@section('title', 'Tüm Bilgiler - Rehber Yerleri')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Tüm Bilgiler - Rehber Yerleri</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.guide-places.index') }}">Rehber Yerleri</a></li>
        <li class="breadcrumb-item active">Tüm Bilgiler</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div><i class="fas fa-table me-1"></i> Tüm Rehber Yerleri Bilgileri</div>
            <div>
                <a href="{{ route('admin.guide-places.index') }}" class="btn btn-secondary btn-sm me-2">
                    <i class="fas fa-arrow-left"></i> Geri Dön
                </a>
                <a href="{{ route('admin.guide-places.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Yeni Yer Ekle
                </a>
            </div>
        </div>
        <div class="card-body">
            @if($places->count() > 0)
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> 
                    Toplam <strong>{{ $places->count() }}</strong> aktif yer bulunmaktadır.
                </div>
                
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="placesTable">
                        <thead class="table-dark">
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>Yer İsmi</th>
                                <th>Kategori</th>
                                <th>Frontend Linki</th>
                                <th>Adres</th>
                                <th>İletişim</th>
                                <th style="width: 100px;">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($places as $index => $place)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>
                                    <strong>{{ $place->title }}</strong>
                                    @if($place->images->count() > 0)
                                        <br>
                                        <small class="text-info">
                                            <i class="fas fa-images"></i> {{ $place->images->count() }} resim
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    @if($place->category->icon)
                                        <i class="{{ $place->category->icon }} text-primary me-1"></i>
                                    @endif
                                    {{ $place->category->name }}
                                </td>
                                <td>
                                    <a href="{{ route('guide.place', [$place->category->slug, $place->slug]) }}" 
                                       target="_blank" 
                                       class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-external-link-alt"></i> Görüntüle
                                    </a>
                                    <br>
                                    <small class="text-muted mt-1 d-block">
                                        /rehber/{{ $place->category->slug }}/{{ $place->slug }}
                                    </small>
                                </td>
                                <td>
                                    @if($place->address)
                                        <small>{{ Str::limit($place->address, 80) }}</small>
                                        @if($place->maps_link)
                                            <br>
                                            <a href="{{ $place->maps_link }}" target="_blank" class="text-success">
                                                <i class="fas fa-map-marker-alt"></i> Haritada Gör
                                            </a>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($place->phone)
                                        <div class="mb-1">
                                            <i class="fas fa-phone text-success"></i> 
                                            <a href="tel:{{ $place->phone }}">{{ $place->phone }}</a>
                                        </div>
                                    @endif
                                    @if($place->email)
                                        <div class="mb-1">
                                            <i class="fas fa-envelope text-info"></i> 
                                            <a href="mailto:{{ $place->email }}">{{ $place->email }}</a>
                                        </div>
                                    @endif
                                    @if($place->website)
                                        <div class="mb-1">
                                            <i class="fas fa-globe text-primary"></i> 
                                            <a href="{{ $place->website }}" target="_blank">Website</a>
                                        </div>
                                    @endif
                                    @if(!$place->phone && !$place->email && !$place->website)
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group-vertical" role="group">
                                        <a href="{{ route('admin.guide-places.edit', $place) }}" 
                                           class="btn btn-primary btn-sm mb-1" 
                                           title="Düzenle">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('guide.place', [$place->category->slug, $place->slug]) }}" 
                                           target="_blank" 
                                           class="btn btn-info btn-sm" 
                                           title="Önizle">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="text-muted">
                        <i class="fas fa-inbox fa-3x mb-3"></i>
                        <p>Henüz aktif yer bulunmuyor.</p>
                        <a href="{{ route('admin.guide-places.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> İlk Yeri Oluştur
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    #placesTable {
        font-size: 0.9rem;
    }
    
    #placesTable td {
        vertical-align: middle;
    }
    
    .btn-group-vertical .btn {
        border-radius: 0.25rem !important;
    }
    
    .table-responsive {
        max-height: 80vh;
        overflow-y: auto;
    }
    
    .table thead th {
        position: sticky;
        top: 0;
        z-index: 10;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // DataTable ile tablo özelliklerini aktifleştir
        if ($.fn.DataTable) {
            $('#placesTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Turkish.json"
                },
                "pageLength": 25,
                "order": [[ 1, "asc" ]],
                "columnDefs": [
                    { "orderable": false, "targets": [6] }
                ]
            });
        }
        
        // Link kopyalama fonksiyonu
        $('.copy-link').click(function(e) {
            e.preventDefault();
            var link = $(this).data('link');
            navigator.clipboard.writeText(window.location.origin + link).then(function() {
                alert('Link kopyalandı!');
            });
        });
    });
</script>
@endpush 