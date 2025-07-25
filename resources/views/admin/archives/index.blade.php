@extends('adminlte::page')

@section('title', 'Arşivler Yönetimi')

@section('content_header')
    <h1>Arşivler Yönetimi</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title">Arşivler Listesi</h3>
                <a href="{{ route('admin.archives.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Yeni Arşiv Ekle
                </a>
            </div>
        </div>
        
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Filtreleme -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <form method="GET" action="{{ route('admin.archives.index') }}" class="form-inline">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Arşiv ara..." value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-6">
                    <form method="GET" action="{{ route('admin.archives.index') }}" class="form-inline float-right">
                        <select name="status" class="form-control mr-2" onchange="this.form.submit()">
                            <option value="">Tüm Durumlar</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Taslak</option>
                            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Yayında</option>
                            <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Arşivlenmiş</option>
                        </select>
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    </form>
                </div>
            </div>

            <!-- Toplu İşlemler -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <form id="bulk-form" method="POST" action="{{ route('admin.archives.bulk-action') }}">
                        @csrf
                        <div class="input-group">
                            <select name="action" class="form-control" required>
                                <option value="">Toplu İşlem Seçin</option>
                                <option value="publish">Yayınla</option>
                                <option value="unpublish">Taslağa Al</option>
                                <option value="feature">Öne Çıkar</option>
                                <option value="unfeature">Öne Çıkarmayı Kaldır</option>
                                <option value="delete">Sil</option>
                            </select>
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-secondary" onclick="return confirm('Bu işlemi yapmak istediğinizden emin misiniz?')">
                                    Uygula
                                </button>
                            </div>
                        </div>
                        <!-- Hidden input'lar JavaScript ile eklenecek -->
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="30">
                                <input type="checkbox" id="select-all">
                            </th>
                            <th>Başlık</th>
                            <th width="120">Durum</th>
                            <th width="100">Belgeler</th>
                            <th width="100">Öne Çıkan</th>
                            <th width="120">Oluşturan</th>
                            <th width="120">Tarih</th>
                            <th width="150">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($archives as $archive)
                            <tr>
                                <td>
                                    <input type="checkbox" name="selected[]" value="{{ $archive->id }}" class="select-item">
                                </td>
                                <td>
                                    <strong>{{ $archive->title }}</strong>
                                    @if($archive->excerpt)
                                        <br><small class="text-muted">{{ Str::limit($archive->excerpt, 100) }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($archive->status == 'published')
                                        <span class="badge badge-success">{{ $archive->status_text }}</span>
                                    @elseif($archive->status == 'draft')
                                        <span class="badge badge-warning">{{ $archive->status_text }}</span>
                                    @else
                                        <span class="badge badge-secondary">{{ $archive->status_text }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-info">{{ $archive->documents_count }}</span>
                                </td>
                                <td>
                                    @if($archive->is_featured)
                                        <span class="badge badge-primary">Öne Çıkan</span>
                                    @else
                                        <span class="badge badge-light">Normal</span>
                                    @endif
                                </td>
                                <td>{{ $archive->user->name ?? 'Bilinmiyor' }}</td>
                                <td>{{ $archive->formatted_date_time }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        @if($archive->status == 'published')
                                            <a href="{{ route('archives.show', $archive->slug) }}" class="btn btn-sm btn-success" title="Sitede Gör" target="_blank">
                                                <i class="fas fa-external-link-alt"></i>
                                            </a>
                                        @endif
                                        <a href="{{ route('admin.archives.show', $archive) }}" class="btn btn-sm btn-info" title="Görüntüle">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.archives.edit', $archive) }}" class="btn btn-sm btn-primary" title="Düzenle">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.archives.destroy', $archive) }}" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Sil" 
                                                    onclick="return confirm('Bu arşivi silmek istediğinizden emin misiniz?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Henüz arşiv bulunmamaktadır.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Sayfalama -->
            <div class="d-flex justify-content-center">
                {{ $archives->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
@stop

@section('js')
<script>
$(document).ready(function() {
    // Tümünü seç/seçme
    $('#select-all').change(function() {
        $('.select-item').prop('checked', $(this).prop('checked'));
    });
    
    // Tek item seçimi
    $('.select-item').change(function() {
        if (!$(this).prop('checked')) {
            $('#select-all').prop('checked', false);
        }
        
        if ($('.select-item:checked').length === $('.select-item').length) {
            $('#select-all').prop('checked', true);
        }
    });
    
    // Toplu işlem formu
    $('#bulk-form').submit(function(e) {
        // Seçili öğeleri kontrol et
        var selectedItems = $('.select-item:checked');
        if (selectedItems.length === 0) {
            e.preventDefault();
            alert('Lütfen en az bir arşiv seçin.');
            return false;
        }
        
        // Seçili öğelerin ID'lerini forma ekle
        $('#bulk-form input[name="selected[]"]').remove(); // Önceki hidden input'ları temizle
        selectedItems.each(function() {
            var hiddenInput = $('<input>').attr({
                type: 'hidden',
                name: 'selected[]',
                value: $(this).val()
            });
            $('#bulk-form').append(hiddenInput);
        });
    });
});
</script>
@stop 