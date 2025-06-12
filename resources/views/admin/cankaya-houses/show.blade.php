@extends('adminlte::page')

@section('title', $cankayaHouse->name . ' - Detay')

@section('content_header')
    <style>
        .content-header {
            display: none;
        }
    </style>
@stop

@section('plugins.Sweetalert2', true)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-home mr-2"></i>
                        {{ $cankayaHouse->name }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.cankaya-houses.edit', $cankayaHouse) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit mr-1"></i>
                            Düzenle
                        </a>
                        <a href="{{ route('admin.cankaya-houses.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left mr-1"></i>
                            Geri Dön
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- Sol Kolon - Temel Bilgiler -->
                        <div class="col-md-8">
                            <!-- Temel Bilgiler -->
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Temel Bilgiler</h3>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td width="150"><strong>Ev Adı:</strong></td>
                                            <td>{{ $cankayaHouse->name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Slug:</strong></td>
                                            <td><code>{{ $cankayaHouse->slug }}</code></td>
                                        </tr>
                                        @if($cankayaHouse->description)
                                        <tr>
                                            <td><strong>Açıklama:</strong></td>
                                            <td>{{ $cankayaHouse->description }}</td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <td><strong>Adres:</strong></td>
                                            <td>{{ $cankayaHouse->address }}</td>
                                        </tr>
                                        @if($cankayaHouse->phone)
                                        <tr>
                                            <td><strong>Telefon:</strong></td>
                                            <td>{{ $cankayaHouse->phone }}</td>
                                        </tr>
                                        @endif
                                        @if($cankayaHouse->location_link)
                                        <tr>
                                            <td><strong>Konum Linki:</strong></td>
                                            <td>
                                                <a href="{{ $cankayaHouse->location_link }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                                    Haritada Görüntüle
                                                </a>
                                            </td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <td><strong>Durum:</strong></td>
                                            <td>
                                                <span class="badge {{ $cankayaHouse->status === 'active' ? 'badge-success' : 'badge-secondary' }}">
                                                    {{ $cankayaHouse->status === 'active' ? 'Aktif' : 'Pasif' }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Sıralama:</strong></td>
                                            <td>{{ $cankayaHouse->order }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Oluşturulma:</strong></td>
                                            <td>{{ $cankayaHouse->created_at->format('d.m.Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Güncellenme:</strong></td>
                                            <td>{{ $cankayaHouse->updated_at->format('d.m.Y H:i') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <!-- Resimler -->
                            @if($cankayaHouse->images && count($cankayaHouse->images) > 0)
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Resimler ({{ count($cankayaHouse->images) }})</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @foreach($cankayaHouse->images as $image)
                                        <div class="col-md-4 mb-3">
                                            <div class="card">
                                                <img src="{{ $image }}" class="card-img-top" alt="Çankaya Evi Resmi" style="height: 200px; object-fit: cover;">
                                                <div class="card-body p-2">
                                                    <a href="{{ $image }}" target="_blank" class="btn btn-sm btn-primary btn-block">
                                                        <i class="fas fa-external-link-alt mr-1"></i>
                                                        Büyük Görüntüle
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Kurslar -->
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Kurslar ({{ $cankayaHouse->courses->count() }})</h3>
                                    <div class="card-tools">
                                        <a href="{{ route('admin.cankaya-house-courses.create', ['cankaya_house_id' => $cankayaHouse->id]) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-plus mr-1"></i>
                                            Yeni Kurs Ekle
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    @if($cankayaHouse->courses->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Kurs Adı</th>
                                                    <th>Eğitmen</th>
                                                    <th>Başlangıç</th>
                                                    <th>Bitiş</th>
                                                    <th>Durum</th>
                                                    <th width="100">İşlemler</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($cankayaHouse->courses as $course)
                                                <tr>
                                                    <td>{{ $course->name }}</td>
                                                    <td>{{ $course->instructor ?? '-' }}</td>
                                                    <td>{{ $course->start_date ? $course->start_date->format('d.m.Y') : '-' }}</td>
                                                    <td>{{ $course->end_date ? $course->end_date->format('d.m.Y') : '-' }}</td>
                                                    <td>
                                                        <span class="badge {{ $course->status === 'active' ? 'badge-success' : 'badge-secondary' }}">
                                                            {{ $course->status === 'active' ? 'Aktif' : 'Pasif' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group" role="group">
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
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-graduation-cap fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Bu Çankaya Evi'nde henüz kurs bulunmamaktadır.</p>
                                        <a href="{{ route('admin.cankaya-house-courses.create', ['cankaya_house_id' => $cankayaHouse->id]) }}" class="btn btn-primary">
                                            <i class="fas fa-plus mr-1"></i>
                                            İlk Kursu Ekle
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Sağ Kolon - İstatistikler ve Hızlı İşlemler -->
                        <div class="col-md-4">
                            <!-- İstatistikler -->
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">İstatistikler</h3>
                                </div>
                                <div class="card-body">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-info">
                                            <i class="fas fa-graduation-cap"></i>
                                        </span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Toplam Kurs</span>
                                            <span class="info-box-number">{{ $cankayaHouse->courses->count() }}</span>
                                        </div>
                                    </div>

                                    <div class="info-box">
                                        <span class="info-box-icon bg-success">
                                            <i class="fas fa-play"></i>
                                        </span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Aktif Kurs</span>
                                            <span class="info-box-number">{{ $cankayaHouse->activeCourses->count() }}</span>
                                        </div>
                                    </div>

                                    <div class="info-box">
                                        <span class="info-box-icon bg-warning">
                                            <i class="fas fa-images"></i>
                                        </span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Resim Sayısı</span>
                                            <span class="info-box-number">{{ $cankayaHouse->image_count }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Hızlı İşlemler -->
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Hızlı İşlemler</h3>
                                </div>
                                <div class="card-body">
                                    <a href="{{ route('admin.cankaya-houses.edit', $cankayaHouse) }}" class="btn btn-warning btn-block">
                                        <i class="fas fa-edit mr-1"></i>
                                        Düzenle
                                    </a>
                                    
                                    <a href="{{ route('admin.cankaya-house-courses.create', ['cankaya_house_id' => $cankayaHouse->id]) }}" class="btn btn-success btn-block">
                                        <i class="fas fa-plus mr-1"></i>
                                        Yeni Kurs Ekle
                                    </a>

                                    @if($cankayaHouse->location_link)
                                    <a href="{{ $cankayaHouse->location_link }}" target="_blank" class="btn btn-info btn-block">
                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                        Haritada Görüntüle
                                    </a>
                                    @endif

                                    <form method="POST" action="{{ route('admin.cankaya-houses.toggle-status', $cankayaHouse) }}" style="display: inline;" class="w-100">
                                        @csrf
                                        <button type="submit" class="btn {{ $cankayaHouse->status === 'active' ? 'btn-secondary' : 'btn-success' }} btn-block">
                                            <i class="fas {{ $cankayaHouse->status === 'active' ? 'fa-pause' : 'fa-play' }} mr-1"></i>
                                            {{ $cankayaHouse->status === 'active' ? 'Pasif Yap' : 'Aktif Yap' }}
                                        </button>
                                    </form>

                                    <hr>

                                    <button type="button" class="btn btn-danger btn-block" onclick="deleteHouse({{ $cankayaHouse->id }})">
                                        <i class="fas fa-trash mr-1"></i>
                                        Sil
                                    </button>
                                </div>
                            </div>

                            <!-- Frontend Linki -->
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Frontend</h3>
                                </div>
                                <div class="card-body">
                                    <a href="{{ route('cankaya-houses.show', $cankayaHouse->slug) }}" target="_blank" class="btn btn-primary btn-block">
                                        <i class="fas fa-external-link-alt mr-1"></i>
                                        Önizleme
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@push('js')
<script>
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
            // Delete form oluştur ve gönder
            let form = document.createElement('form');
            form.method = 'POST';
            form.action = '/admin/cankaya-houses/' + id;
            
            let methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            
            let tokenInput = document.createElement('input');
            tokenInput.type = 'hidden';
            tokenInput.name = '_token';
            tokenInput.value = '{{ csrf_token() }}';
            
            form.appendChild(methodInput);
            form.appendChild(tokenInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

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
            // Delete form oluştur ve gönder
            let form = document.createElement('form');
            form.method = 'POST';
            form.action = '/admin/cankaya-house-courses/' + id;
            
            let methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            
            let tokenInput = document.createElement('input');
            tokenInput.type = 'hidden';
            tokenInput.name = '_token';
            tokenInput.value = '{{ csrf_token() }}';
            
            form.appendChild(methodInput);
            form.appendChild(tokenInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
@endpush