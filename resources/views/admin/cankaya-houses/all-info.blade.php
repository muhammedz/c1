@extends('adminlte::page')

@section('title', 'Tüm Bilgiler - Çankaya Evleri')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Tüm Bilgiler - Çankaya Evleri</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.cankaya-houses.index') }}">Çankaya Evleri</a></li>
        <li class="breadcrumb-item active">Tüm Bilgiler</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div><i class="fas fa-list me-1"></i> Tüm Çankaya Evleri Bilgileri</div>
            <div>
                <a href="{{ route('admin.cankaya-houses.index') }}" class="btn btn-secondary btn-sm me-2">
                    <i class="fas fa-arrow-left"></i> Geri Dön
                </a>
                <button onclick="copyAllText()" class="btn btn-success btn-sm me-2">
                    <i class="fas fa-copy"></i> Tümünü Kopyala
                </button>
                <a href="{{ route('admin.cankaya-houses.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Yeni Ev Ekle
                </a>
            </div>
        </div>
        <div class="card-body">
            @if($cankayaHouses->count() > 0)
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> 
                    Toplam <strong>{{ $cankayaHouses->count() }}</strong> aktif Çankaya Evi bulunmaktadır.
                </div>
                
                <div class="bg-light p-3 rounded" style="font-family: 'Courier New', monospace; white-space: pre-line;" id="copyContent">
=== ÇANKAYA EVLERİ ===

@foreach($cankayaHouses as $house)
{{ $house->name }} - Çankaya Evi - {{ url('/cankaya-evleri/' . $house->slug) }}
@endforeach

                </div>
                
                <div class="mt-3">
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i> 
                        Yukarıdaki metni seçip kopyalayabilir veya "Tümünü Kopyala" butonunu kullanabilirsiniz.
                    </small>
                </div>
                
                <!-- Detaylı Tablo (İsteğe Bağlı) -->
                <div class="mt-4">
                    <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#detailedTable" aria-expanded="false">
                        <i class="fas fa-table"></i> Detaylı Tablo Görünümü
                    </button>
                </div>
                
                <div class="collapse mt-3" id="detailedTable">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-sm">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Ev Adı</th>
                                    <th>URL</th>
                                    <th>Adres</th>
                                    <th>İletişim</th>
                                    <th>Kurs Sayısı</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cankayaHouses as $index => $house)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $house->name }}</td>
                                    <td>
                                        <code class="user-select-all">{{ url('/cankaya-evleri/' . $house->slug) }}</code>
                                        <button class="btn btn-sm btn-outline-secondary ms-1" onclick="copyText('{{ url('/cankaya-evleri/' . $house->slug) }}')">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </td>
                                    <td>
                                        @if($house->address)
                                            <small>{{ Str::limit($house->address, 80) }}</small>
                                            @if($house->location_link)
                                                <br>
                                                <a href="{{ $house->location_link }}" target="_blank" class="text-success">
                                                    <i class="fas fa-map-marker-alt"></i> Haritada Gör
                                                </a>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($house->phone)
                                            <div class="mb-1">
                                                <i class="fas fa-phone text-success"></i> 
                                                <a href="tel:{{ $house->phone }}">{{ $house->phone }}</a>
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $house->courses->count() }} kurs</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.cankaya-houses.edit', $house) }}" class="btn btn-primary btn-sm" title="Düzenle">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('cankaya-houses.show', $house->slug) }}" target="_blank" class="btn btn-info btn-sm" title="Görüntüle">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
            @else
                <div class="text-center py-5">
                    <div class="text-muted">
                        <i class="fas fa-inbox fa-3x mb-3"></i>
                        <p>Henüz aktif Çankaya Evi bulunmuyor.</p>
                        <a href="{{ route('admin.cankaya-houses.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> İlk Evi Oluştur
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
    .user-select-all {
        user-select: all;
        cursor: pointer;
    }
    
    #copyContent {
        max-height: 70vh;
        overflow-y: auto;
        font-size: 14px;
        line-height: 1.4;
        border: 1px solid #ddd;
    }
    
    code {
        background-color: #f8f9fa;
        padding: 2px 4px;
        border-radius: 3px;
        font-size: 12px;
    }
</style>
@endpush

@push('scripts')
<script>
    function copyAllText() {
        const content = document.getElementById('copyContent').innerText;
        navigator.clipboard.writeText(content).then(function() {
            // Toast bildirimi göster
            showToast('Tüm liste kopyalandı!', 'success');
        }).catch(function(err) {
            console.error('Kopyalama hatası: ', err);
            showToast('Kopyalama başarısız!', 'error');
        });
    }
    
    function copyText(text) {
        navigator.clipboard.writeText(text).then(function() {
            showToast('URL kopyalandı!', 'success');
        }).catch(function(err) {
            console.error('Kopyalama hatası: ', err);
            showToast('Kopyalama başarısız!', 'error');
        });
    }
    
    function showToast(message, type) {
        // Basit toast bildirimi
        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} position-fixed`;
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 250px;';
        toast.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check' : 'exclamation-triangle'}"></i> 
            ${message}
        `;
        
        document.body.appendChild(toast);
        
        // 3 saniye sonra kaldır
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }
    
    // Sayfa yüklendiğinde text alanını seçilebilir yap
    document.addEventListener('DOMContentLoaded', function() {
        const copyContent = document.getElementById('copyContent');
        if (copyContent) {
            copyContent.addEventListener('click', function() {
                const selection = window.getSelection();
                const range = document.createRange();
                range.selectNodeContents(copyContent);
                selection.removeAllRanges();
                selection.addRange(range);
            });
        }
    });
</script>
@endpush 