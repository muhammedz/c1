@extends('adminlte::page')

@section('title', 'Yönlendirme Yönetimi')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Yönlendirme Yönetimi</h1>
        <div>
            <a href="{{ route('admin.redirects.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Yeni Yönlendirme
            </a>
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#bulkActionsModal">
                <i class="fas fa-tasks"></i> Toplu İşlemler
            </button>
        </div>
    </div>
@stop

@section('content')
    <!-- İstatistik Kartları -->
    <div class="row mb-3">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $stats['total_redirects'] }}</h3>
                    <p>Toplam Yönlendirme</p>
                </div>
                <div class="icon">
                    <i class="fas fa-share"></i>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $stats['active_redirects'] }}</h3>
                    <p>Aktif Yönlendirme</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ number_format($stats['total_hits']) }}</h3>
                    <p>Toplam Hit</p>
                </div>
                <div class="icon">
                    <i class="fas fa-mouse-pointer"></i>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $stats['top_redirects']->count() }}</h3>
                    <p>En Çok Kullanılan</p>
                </div>
                <div class="icon">
                    <i class="fas fa-fire"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtreleme Formu -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Filtreler</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.redirects.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>URL Arama</label>
                            <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Kaynak veya hedef URL ara...">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Durum</label>
                            <select name="status" class="form-control">
                                <option value="">Tümü</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Pasif</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Yönlendirme Tipi</label>
                            <select name="redirect_type" class="form-control">
                                <option value="">Tümü</option>
                                <option value="301" {{ request('redirect_type') == '301' ? 'selected' : '' }}>301 (Kalıcı)</option>
                                <option value="302" {{ request('redirect_type') == '302' ? 'selected' : '' }}>302 (Geçici)</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div class="d-flex">
                                <button type="submit" class="btn btn-primary mr-2">
                                    <i class="fas fa-search"></i> Filtrele
                                </button>
                                <a href="{{ route('admin.redirects.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Temizle
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Yönlendirmeler Tablosu -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Yönlendirme Kuralları ({{ $redirects->total() }} kayıt)</h3>
        </div>
        <div class="card-body p-0">
            @if($redirects->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th width="30">
                                    <input type="checkbox" id="select-all">
                                </th>
                                <th>
                                    <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'from_url', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}">
                                        Kaynak URL
                                        @if(request('sort_by') == 'from_url')
                                            <i class="fas fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'to_url', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}">
                                        Hedef URL
                                        @if(request('sort_by') == 'to_url')
                                            <i class="fas fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'redirect_type', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}">
                                        Tip
                                        @if(request('sort_by') == 'redirect_type')
                                            <i class="fas fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'hit_count', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}">
                                        Hit Sayısı
                                        @if(request('sort_by') == 'hit_count')
                                            <i class="fas fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>Durum</th>
                                <th>Oluşturan</th>
                                <th width="200">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($redirects as $redirect)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="redirect-checkbox" value="{{ $redirect->id }}">
                                    </td>
                                    <td>
                                        <code>{{ $redirect->from_url }}</code>
                                    </td>
                                    <td>
                                        <code>{{ Str::limit($redirect->to_url, 50) }}</code>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $redirect->redirect_type == '301' ? 'success' : 'info' }}">
                                            {{ $redirect->redirect_type_text }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $redirect->hit_count > 10 ? 'danger' : ($redirect->hit_count > 0 ? 'warning' : 'secondary') }}">
                                            {{ $redirect->hit_count }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($redirect->is_active)
                                            <span class="badge badge-success">Aktif</span>
                                        @else
                                            <span class="badge badge-secondary">Pasif</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($redirect->creator)
                                            <small>{{ $redirect->creator->name }}</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.redirects.show', $redirect) }}" class="btn btn-sm btn-info" title="Detay">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.redirects.edit', $redirect) }}" class="btn btn-sm btn-warning" title="Düzenle">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-{{ $redirect->is_active ? 'secondary' : 'success' }} toggle-btn" 
                                                    data-id="{{ $redirect->id }}" title="{{ $redirect->is_active ? 'Pasif Yap' : 'Aktif Yap' }}">
                                                <i class="fas fa-{{ $redirect->is_active ? 'pause' : 'play' }}"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-primary test-btn" data-id="{{ $redirect->id }}" title="Test Et">
                                                <i class="fas fa-external-link-alt"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="{{ $redirect->id }}" title="Sil">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="card-footer">
                    {{ $redirects->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-share fa-3x text-muted mb-3"></i>
                    <h5>Yönlendirme kuralı bulunamadı</h5>
                    <p class="text-muted">Henüz hiç yönlendirme kuralı oluşturulmamış veya filtrelere uygun kayıt yok.</p>
                    <a href="{{ route('admin.redirects.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> İlk Yönlendirmeyi Oluştur
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Toplu İşlemler Modal -->
    <div class="modal fade" id="bulkActionsModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Toplu İşlemler</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Seçili kayıtlar için yapmak istediğiniz işlemi seçin:</p>
                    <div class="form-group">
                        <button type="button" class="btn btn-success btn-block" id="bulk-activate">
                            <i class="fas fa-play"></i> Aktif Yap
                        </button>
                        <button type="button" class="btn btn-secondary btn-block" id="bulk-deactivate">
                            <i class="fas fa-pause"></i> Pasif Yap
                        </button>
                        <button type="button" class="btn btn-danger btn-block" id="bulk-delete">
                            <i class="fas fa-trash"></i> Seçilenleri Sil
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .table code {
            background-color: #f8f9fa;
            padding: 2px 4px;
            border-radius: 3px;
            font-size: 0.875em;
            word-break: break-all;
        }
        .small-box .inner h3 {
            font-size: 2.2rem;
        }
    </style>
@stop

@section('js')
<script>
$(document).ready(function() {
    // Select all checkbox
    $('#select-all').change(function() {
        $('.redirect-checkbox').prop('checked', $(this).prop('checked'));
    });

    // Toggle status
    $('.toggle-btn').click(function() {
        const id = $(this).data('id');
        const btn = $(this);
        
        $.post(`{{ route('admin.redirects.toggle', '') }}/${id}`, {
            _token: '{{ csrf_token() }}'
        })
        .done(function(response) {
            if (response.success) {
                toastr.success(response.message);
                location.reload();
            }
        })
        .fail(function() {
            toastr.error('Bir hata oluştu!');
        });
    });

    // Test redirect
    $('.test-btn').click(function() {
        const id = $(this).data('id');
        
        $.post(`{{ route('admin.redirects.test', '') }}/${id}`, {
            _token: '{{ csrf_token() }}'
        })
        .done(function(response) {
            if (response.success) {
                window.open(response.test_url, '_blank');
                toastr.info('Test URL\'si yeni sekmede açıldı');
            }
        })
        .fail(function() {
            toastr.error('Test edilemedi!');
        });
    });

    // Delete redirect
    $('.delete-btn').click(function() {
        const id = $(this).data('id');
        
        if (confirm('Bu yönlendirme kuralı silinsin mi?')) {
            $.ajax({
                url: `{{ route('admin.redirects.destroy', '') }}/${id}`,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                }
            })
            .done(function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    location.reload();
                }
            })
            .fail(function() {
                toastr.error('Bir hata oluştu!');
            });
        }
    });

    // Bulk activate
    $('#bulk-activate').click(function() {
        bulkToggle(true, 'aktif');
    });

    // Bulk deactivate
    $('#bulk-deactivate').click(function() {
        bulkToggle(false, 'pasif');
    });

    // Bulk delete
    $('#bulk-delete').click(function() {
        const selectedIds = $('.redirect-checkbox:checked').map(function() {
            return $(this).val();
        }).get();

        if (selectedIds.length === 0) {
            toastr.warning('Lütfen en az bir kayıt seçin!');
            return;
        }

        if (confirm(`${selectedIds.length} adet yönlendirme kuralı silinsin mi? Bu işlem geri alınamaz!`)) {
            $.post('{{ route('admin.redirects.bulk-delete') }}', {
                _token: '{{ csrf_token() }}',
                ids: selectedIds
            })
            .done(function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    location.reload();
                }
            })
            .fail(function() {
                toastr.error('Bir hata oluştu!');
            });
        }
    });

    function bulkToggle(status, statusText) {
        const selectedIds = $('.redirect-checkbox:checked').map(function() {
            return $(this).val();
        }).get();

        if (selectedIds.length === 0) {
            toastr.warning('Lütfen en az bir kayıt seçin!');
            return;
        }

        if (confirm(`${selectedIds.length} adet kayıt ${statusText} duruma getirilsin mi?`)) {
            $.post('{{ route('admin.redirects.bulk-toggle') }}', {
                _token: '{{ csrf_token() }}',
                ids: selectedIds,
                status: status
            })
            .done(function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    location.reload();
                }
            })
            .fail(function() {
                toastr.error('Bir hata oluştu!');
            });
        }
    }
});
</script>
@stop 