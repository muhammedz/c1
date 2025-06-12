@extends('adminlte::page')

@section('title', 'Hizmet Konuları')

@section('content_header')
    <h1>Hizmet Konuları</h1>
@stop

@section('content')

<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-list-ul me-2"></i>Hizmet Konuları Listesi
            </h3>
            <div class="card-tools">
                <a href="{{ route('services.topics.index') }}" class="btn btn-success btn-sm me-2" target="_blank">
                    <i class="fas fa-external-link-alt me-1"></i> Sitede Görüntüle
                </a>
                <a href="{{ route('admin.service-topics.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i> Yeni Hizmet Konusu
                </a>
            </div>
        </div>
        <div class="card-body">
            @if($serviceTopics->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th width="80">Sıra</th>
                                <th width="60">İkon</th>
                                <th>Ad</th>
                                <th>Açıklama</th>
                                <th width="120">Hizmet Sayısı</th>
                                <th width="80">Durum</th>
                                <th width="160">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody id="sortable-list">
                            @foreach($serviceTopics as $serviceTopic)
                            <tr data-id="{{ $serviceTopic->id }}">
                                <td>
                                    <span class="sort-handle" style="cursor: move;">
                                        <i class="fas fa-grip-vertical text-muted"></i>
                                    </span>
                                    <span class="ms-2">{{ $serviceTopic->order }}</span>
                                </td>
                                <td class="text-center">
                                    @if($serviceTopic->icon)
                                        <i class="{{ $serviceTopic->icon }}" style="color: {{ $serviceTopic->color }}; font-size: 1.2em;"></i>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $serviceTopic->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $serviceTopic->slug }}</small>
                                </td>
                                <td>
                                    @if($serviceTopic->description)
                                        {{ Str::limit($serviceTopic->description, 100) }}
                                    @else
                                        <span class="text-muted">Açıklama yok</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-{{ $serviceTopic->services_count > 0 ? 'primary' : 'secondary' }}">
                                        {{ $serviceTopic->services_count }} hizmet
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($serviceTopic->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-danger">Pasif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('services.topics.show', $serviceTopic->slug) }}" class="btn btn-success btn-sm" target="_blank" title="Sitede Görüntüle">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                        <a href="{{ route('admin.service-topics.edit', $serviceTopic->id) }}" 
                                           class="btn btn-primary btn-sm" title="Düzenle">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.service-topics.destroy', $serviceTopic->id) }}" method="POST" class="delete-form d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Sil"
                                                    onclick="return confirm('Bu hizmet konusunu silmek istediğinizden emin misiniz?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $serviceTopics->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Henüz hizmet konusu bulunmamaktadır. <a href="{{ route('admin.service-topics.create') }}">Yeni konu ekleyin</a>.
                </div>
            @endif
        </div>
    </div>
</div>

@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sıralama işlevi
    const sortable = new Sortable(document.getElementById('sortable-list'), {
        handle: '.sort-handle',
        animation: 150,
        onEnd: function(evt) {
            const items = Array.from(evt.to.children).map((item, index) => ({
                id: item.dataset.id,
                order: index + 1
            }));
            
            // AJAX ile sıralama güncelle
            fetch('{{ route("admin.service-topics.update-order") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ items: items })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Başarı mesajı göster
                    toastr.success('Sıralama başarıyla güncellendi.');
                }
            })
            .catch(error => {
                console.error('Sıralama güncellenirken hata oluştu:', error);
                toastr.error('Sıralama güncellenirken hata oluştu.');
            });
        }
    });
});
</script>
@stop 