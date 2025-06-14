@extends('adminlte::page')

@section('title', 'Çankaya Evi Kursları')

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
                            <i class="fas fa-graduation-cap mr-2"></i>
                            Çankaya Evi Kursları
                        </h3>
                        <a href="{{ route('admin.cankaya-house-courses.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus mr-1"></i>
                            Yeni Kurs Ekle
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Filtreler -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <form method="GET" action="{{ route('admin.cankaya-house-courses.index') }}">
                                <input type="hidden" name="search" value="{{ request('search') }}">
                                <input type="hidden" name="status" value="{{ request('status') }}">
                                <select name="cankaya_house_id" class="form-control" onchange="this.form.submit()">
                                    <option value="">Tüm Çankaya Evleri</option>
                                    @foreach($cankayaHouses as $house)
                                        <option value="{{ $house->id }}" 
                                                {{ request('cankaya_house_id') == $house->id ? 'selected' : '' }}>
                                            {{ $house->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                        <div class="col-md-3">
                            <form method="GET" action="{{ route('admin.cankaya-house-courses.index') }}">
                                <input type="hidden" name="search" value="{{ request('search') }}">
                                <input type="hidden" name="cankaya_house_id" value="{{ request('cankaya_house_id') }}">
                                <select name="status" class="form-control" onchange="this.form.submit()">
                                    <option value="">Tüm Durumlar</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Pasif</option>
                                </select>
                            </form>
                        </div>
                        <div class="col-md-4">
                            <form method="GET" action="{{ route('admin.cankaya-house-courses.index') }}">
                                <input type="hidden" name="cankaya_house_id" value="{{ request('cankaya_house_id') }}">
                                <input type="hidden" name="status" value="{{ request('status') }}">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" 
                                           placeholder="Kurs adı ara..." 
                                           value="{{ request('search') }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="submit">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Tablo -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Kurs Adı</th>
                                    <th>Çankaya Evi</th>
                                    <th>Başlangıç</th>
                                    <th>Bitiş</th>
                                    <th>Eğitmen</th>
                                    <th width="80">Kapasite</th>
                                    <th width="80">Ücret</th>
                                    <th width="80">Durum</th>
                                    <th width="150">İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($courses as $course)
                                <tr>
                                    <td>
                                        <strong>{{ $course->name }}</strong>
                                        @if($course->description)
                                            <br><small class="text-muted">{{ Str::limit($course->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ $course->cankayaHouse->name }}</span>
                                    </td>
                                    <td>
                                        @if($course->start_date)
                                            {{ $course->start_date->format('d.m.Y') }}
                                            @if($course->is_upcoming)
                                                <span class="badge badge-warning badge-sm ml-1">Yaklaşan</span>
                                            @elseif($course->is_ongoing)
                                                <span class="badge badge-success badge-sm ml-1">Devam Ediyor</span>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($course->end_date)
                                            {{ $course->end_date->format('d.m.Y') }}
                                            @if($course->is_completed)
                                                <span class="badge badge-secondary badge-sm ml-1">Tamamlandı</span>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $course->instructor ?? '-' }}
                                    </td>
                                    <td class="text-center">
                                        {{ $course->capacity ?? '-' }}
                                    </td>
                                    <td class="text-center">
                                        @if($course->price)
                                            {{ number_format($course->price, 0, ',', '.') }} ₺
                                        @else
                                            <span class="text-muted">Ücretsiz</span>
                                        @endif
                                    </td>
                                    <td>
                                        <form method="POST" action="{{ route('admin.cankaya-house-courses.toggle-status', $course) }}" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm {{ $course->status === 'active' ? 'btn-success' : 'btn-secondary' }}">
                                                {{ $course->status === 'active' ? 'Aktif' : 'Pasif' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.cankaya-house-courses.show', $course) }}" 
                                               class="btn btn-sm btn-info" title="Görüntüle">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.cankaya-house-courses.edit', $course) }}" 
                                               class="btn btn-sm btn-warning" title="Düzenle">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" 
                                                    onclick="deleteCourse({{ $course->id }})" title="Sil">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">
                                        <div class="py-4">
                                            <i class="fas fa-graduation-cap fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">Henüz hiç kurs eklenmemiş.</p>
                                            <a href="{{ route('admin.cankaya-house-courses.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus mr-1"></i>
                                                İlk Kursu Ekle
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($courses->hasPages())
                        <div class="d-flex justify-content-center">
                            {{ $courses->appends(request()->query())->links() }}
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

function deleteCourse(id) {
    Swal.fire({
        title: 'Emin misiniz?',
        text: "Bu kurs silinecek!",
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
            form.action = '{{ route("admin.cankaya-house-courses.destroy", ":id") }}'.replace(':id', id);
            
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