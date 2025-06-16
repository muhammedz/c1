@extends('adminlte::page')

@section('title', 'Çankaya Evleri')

@section('content_header')
    <style>
        .content-header {
            display: none;
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('plugins.Toastr', true)
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)
@section('plugins.Sweetalert2', true)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">
                            <i class="fas fa-home mr-2"></i>
                            Çankaya Evleri Yönetimi
                        </h3>
                        <div>
                            <a href="{{ route('admin.cankaya-houses.all-info') }}" class="btn btn-info mr-2">
                                <i class="fas fa-table mr-1"></i>
                                Tüm Bilgiler
                            </a>
                            <a href="{{ route('admin.cankaya-houses.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus mr-1"></i>
                                Yeni Çankaya Evi Ekle
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Filtreler -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <form method="GET" action="{{ route('admin.cankaya-houses.index') }}">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" 
                                           placeholder="Ev adı veya adres ara..." 
                                           value="{{ request('search') }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="submit">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-3">
                            <form method="GET" action="{{ route('admin.cankaya-houses.index') }}">
                                <input type="hidden" name="search" value="{{ request('search') }}">
                                <select name="status" class="form-control" onchange="this.form.submit()">
                                    <option value="">Tüm Durumlar</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Pasif</option>
                                </select>
                            </form>
                        </div>
                    </div>

                    <!-- Tablo -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Ev Adı</th>
                                    <th>Adres</th>
                                    <th>Telefon</th>
                                    <th width="80">Kurs Sayısı</th>
                                    <th width="80">Durum</th>
                                    <th width="80">Sıra</th>
                                    <th width="150">İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($cankayaHouses as $house)
                                <tr>
                                    <td>
                                        <strong>{{ $house->name }}</strong>
                                        @if($house->images && count($house->images) > 0)
                                            <small class="text-muted d-block">{{ count($house->images) }} resim</small>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ Str::limit($house->address, 50) }}</small>
                                    </td>
                                    <td>
                                        {{ $house->phone ?? '-' }}
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-info">{{ $house->courses_count }}</span>
                                    </td>
                                    <td>
                                        <form method="POST" action="{{ route('admin.cankaya-houses.toggle-status', $house) }}" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm {{ $house->status === 'active' ? 'btn-success' : 'btn-secondary' }}">
                                                {{ $house->status === 'active' ? 'Aktif' : 'Pasif' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td class="text-center">
                                        {{ $house->order }}
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.cankaya-houses.show', $house) }}" 
                                               class="btn btn-sm btn-info" title="Görüntüle">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.cankaya-houses.edit', $house) }}" 
                                               class="btn btn-sm btn-warning" title="Düzenle">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            {{-- Kurs ekleme butonu geçici olarak kapatıldı --}}
                                            {{-- <a href="{{ route('admin.cankaya-house-courses.create', ['cankaya_house_id' => $house->id]) }}" 
                                               class="btn btn-sm btn-success" title="Kurs Ekle">
                                                <i class="fas fa-plus"></i>
                                            </a> --}}
                                            <button type="button" class="btn btn-sm btn-danger" 
                                                    onclick="deleteHouse({{ $house->id }})" title="Sil">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">
                                        <div class="py-4">
                                            <i class="fas fa-home fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">Henüz hiç Çankaya Evi eklenmemiş.</p>
                                            <a href="{{ route('admin.cankaya-houses.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus mr-1"></i>
                                                İlk Çankaya Evini Ekle
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($cankayaHouses->hasPages())
                        <div class="d-flex justify-content-center">
                            {{ $cankayaHouses->appends(request()->query())->links('custom.pagination') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@push('js')
<script>
$(document).ready(function() {
    // Toastr ayarları
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "3000"
    };

    // CSRF token ayarı
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Success mesajı
    @if(session('success'))
        toastr.success('{{ session('success') }}');
    @endif

    // Error mesajı
    @if(session('error'))
        toastr.error('{{ session('error') }}');
    @endif
});

function deleteHouse(id) {
    Swal.fire({
        title: 'Emin misiniz?',
        text: "Bu Çankaya Evi ve tüm kursları silinecek!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Evet, Sil!',
        cancelButtonText: 'İptal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Form oluştur ve gönder
            let form = document.createElement('form');
            form.method = 'POST';
            form.action = '/admin/cankaya-houses/' + id;
            
            let csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            
            let methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            
            form.appendChild(csrfToken);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
@endpush 