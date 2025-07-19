@extends('adminlte::page')

@section('title', 'Etkinlik Yönetimi')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Etkinlik Yönetimi</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Anasayfa</a></li>
                <li class="breadcrumb-item active">Etkinlikler</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Etkinlikler</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.events.check') }}" class="btn btn-info btn-sm mr-2">
                            <i class="fas fa-sync-alt"></i> Etkinlik Kontrol Et
                        </a>
                        <!-- Yeni Etkinlik Ekle butonu kaldırıldı - tekrar eklenecek -->
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fas fa-check"></i> Başarılı!</h5>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fas fa-ban"></i> Hata!</h5>
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.events.bulk-actions') }}" method="POST" id="events-form">
                        @csrf
                        <table id="events-table" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 20px">
                                        <div class="icheck-primary">
                                            <input type="checkbox" id="check-all">
                                            <label for="check-all"></label>
                                        </div>
                                    </th>
                                    <th style="width: 10px">#</th>
                                    <th style="width: 100px">Görsel</th>
                                    <th>Başlık</th>
                                    <th>Kategori</th>
                                    <th>Tarih</th>
                                    <th>Durum</th>
                                    <th style="width: 150px">İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($events as $event)
                                    <tr>
                                        <td>
                                            <div class="icheck-primary">
                                                <input type="checkbox" name="event_ids[]" id="check-{{ $event->id }}" value="{{ $event->id }}" class="event-checkbox">
                                                <label for="check-{{ $event->id }}"></label>
                                            </div>
                                        </td>
                                        <td>{{ $event->id }}</td>
                                        <td>
                                            <img src="{{ $event->cover_image_url }}" alt="{{ $event->title }}" class="img-thumbnail" style="max-width: 80px;">
                                        </td>
                                        <td>{{ $event->title }}</td>
                                        <td>{{ $event->category->name ?? 'Kategori Yok' }}</td>
                                        <td>
                                            <strong>Başlangıç:</strong> {{ $event->formatted_start_date }}
                                            @if($event->end_date)
                                                <br>
                                                <strong>Bitiş:</strong> {{ $event->formatted_end_date }}
                                            @endif
                                        </td>
                                        <td>
                                            {!! $event->status_badge !!}
                                            <div class="mt-2">
                                                <button class="btn btn-sm {{ $event->is_active ? 'btn-success' : 'btn-secondary' }} toggle-status" 
                                                    data-toggle="modal" 
                                                    data-target="#toggle-status-modal" 
                                                    data-id="{{ $event->id }}" 
                                                    data-title="{{ $event->title }}" 
                                                    data-status="{{ $event->is_active ? 'aktif' : 'pasif' }}">
                                                    <i class="fas {{ $event->is_active ? 'fa-check-circle' : 'fa-times-circle' }}"></i> {{ $event->is_active ? 'Aktif' : 'Pasif' }}
                                                </button>
                                            </div>
                                            <div class="mt-2">
                                                <button class="btn btn-sm {{ $event->show_on_homepage ? 'btn-info' : 'btn-secondary' }} toggle-homepage" 
                                                    data-toggle="modal" 
                                                    data-target="#toggle-homepage-modal" 
                                                    data-id="{{ $event->id }}" 
                                                    data-title="{{ $event->title }}" 
                                                    data-status="{{ $event->show_on_homepage ? 'gösteriliyor' : 'gizli' }}">
                                                    <i class="fas {{ $event->show_on_homepage ? 'fa-home' : 'fa-home' }}"></i> Ana Sayfa: {{ $event->show_on_homepage ? 'Evet' : 'Hayır' }}
                                                </button>
                                            </div>
                                            <div class="mt-2">
                                                <button class="btn btn-sm {{ $event->is_featured ? 'btn-warning' : 'btn-secondary' }} toggle-featured" 
                                                    data-toggle="modal" 
                                                    data-target="#toggle-featured-modal" 
                                                    data-id="{{ $event->id }}" 
                                                    data-title="{{ $event->title }}" 
                                                    data-status="{{ $event->is_featured ? 'öne çıkarılıyor' : 'normal' }}">
                                                    <i class="fas {{ $event->is_featured ? 'fa-star' : 'fa-star' }}"></i> Öne Çıkan: {{ $event->is_featured ? 'Evet' : 'Hayır' }}
                                                </button>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('admin.events.edit', $event->id) }}" class="btn btn-info btn-sm">
                                                    <i class="fas fa-edit"></i> Düzenle
                                                </a>
                                                <button type="button" class="btn btn-danger btn-sm delete-event" 
                                                    data-toggle="modal" 
                                                    data-target="#delete-event-modal" 
                                                    data-id="{{ $event->id }}" 
                                                    data-title="{{ $event->title }}">
                                                    <i class="fas fa-trash"></i> Sil
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Henüz etkinlik eklenmemiş.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        
                        <!-- Toplu İşlem Formu -->
                        <div class="mt-3 bulk-actions bg-light p-2 rounded" style="display: none;">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group mb-0">
                                        <select name="bulk_action" class="form-control" id="bulk-action-select">
                                            <option value="">Seçilen etkinlikler için işlem seçin</option>
                                            <option value="change_status">Durum Değiştir</option>
                                            <option value="change_homepage">Ana Sayfa Görünürlüğünü Değiştir</option>
                                            <option value="delete">Sil</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <!-- Durum Değiştirme Seçenekleri -->
                                <div class="col-md-3 bulk-option" id="status-options" style="display: none;">
                                    <div class="form-group mb-0">
                                        <select name="status" class="form-control">
                                            <option value="active">Aktif Yap</option>
                                            <option value="passive">Pasif Yap</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <!-- Ana Sayfa Görünürlüğü Seçenekleri -->
                                <div class="col-md-3 bulk-option" id="homepage-options" style="display: none;">
                                    <div class="form-group mb-0">
                                        <select name="homepage_status" class="form-control">
                                            <option value="show">Ana Sayfada Göster</option>
                                            <option value="hide">Ana Sayfada Gizle</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary btn-block" id="apply-bulk-action">
                                        Uygula
                                    </button>
                                </div>
                            </div>
                            <div class="mt-2 text-danger" id="bulk-action-warning"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toggle Status Modal -->
<div class="modal fade" id="toggle-status-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Etkinlik Durumunu Değiştir</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Bu etkinliği <span id="status-text"></span> durumuna getirmek istediğinize emin misiniz?</p>
                <h5 id="event-title" class="text-center"></h5>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">İptal</button>
                <form id="toggle-status-form" method="POST" action="">
                    @csrf
                    <button type="submit" class="btn btn-primary">Evet, Değiştir</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Toggle Homepage Modal -->
<div class="modal fade" id="toggle-homepage-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Ana Sayfa Görünürlüğünü Değiştir</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Bu etkinliği ana sayfada gösterme durumunu değiştirmek istediğinize emin misiniz?</p>
                <h5 id="homepage-event-title" class="text-center"></h5>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">İptal</button>
                <form id="toggle-homepage-form" method="POST" action="">
                    @csrf
                    <button type="submit" class="btn btn-primary">Evet, Değiştir</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Toggle Featured Modal -->
<div class="modal fade" id="toggle-featured-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Öne Çıkarma Durumunu Değiştir</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Bu etkinliği öne çıkarma durumunu değiştirmek istediğinize emin misiniz?</p>
                <h5 id="featured-event-title" class="text-center"></h5>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">İptal</button>
                <form id="toggle-featured-form" method="POST" action="">
                    @csrf
                    <button type="submit" class="btn btn-primary">Evet, Değiştir</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Event Modal -->
<div class="modal fade" id="delete-event-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Etkinlik Sil</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Bu etkinliği silmek istediğinize emin misiniz? Bu işlem geri alınamaz.</p>
                <h5 id="delete-event-title" class="text-center"></h5>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">İptal</button>
                <form id="delete-event-form" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Evet, Sil</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@stop

@section('js')
<script>
    $(function () {
        $('#events-table').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Turkish.json"
            }
        });
        
        // Toggle Status
        $('.toggle-status').click(function () {
            var id = $(this).data('id');
            var title = $(this).data('title');
            var status = $(this).data('status');
            
            $('#toggle-status-form').attr('action', '{{ route("admin.events.toggle-visibility", "") }}/' + id);
            $('#event-title').text(title);
            
            if (status === 'aktif') {
                $('#status-text').text('pasif');
            } else {
                $('#status-text').text('aktif');
            }
        });
        
        // Toggle Homepage
        $('.toggle-homepage').click(function () {
            var id = $(this).data('id');
            var title = $(this).data('title');
            
            $('#toggle-homepage-form').attr('action', '{{ route("admin.events.toggle-homepage", "") }}/' + id);
            $('#homepage-event-title').text(title);
        });
        
        // Toggle Featured
        $('.toggle-featured').click(function () {
            var id = $(this).data('id');
            var title = $(this).data('title');
            
            $('#toggle-featured-form').attr('action', '{{ route("admin.events.toggle-featured", "") }}/' + id);
            $('#featured-event-title').text(title);
        });
        
        // Delete Event
        $('.delete-event').click(function () {
            var id = $(this).data('id');
            var title = $(this).data('title');
            
            $('#delete-event-form').attr('action', '{{ route("admin.events.delete", "") }}/' + id);
            $('#delete-event-title').text(title);
        });
        
        // Toplu İşlem - Tümünü Seç/Kaldır
        $('#check-all').change(function() {
            $('.event-checkbox').prop('checked', $(this).prop('checked'));
            updateBulkActionVisibility();
        });
        
        // Toplu İşlem - Checkbox Değişimi
        $('.event-checkbox').change(function() {
            updateBulkActionVisibility();
            
            // Eğer tüm checkboxlar seçili değilse, "tümünü seç" checkbox'ını temizle
            if ($('.event-checkbox:checked').length < $('.event-checkbox').length) {
                $('#check-all').prop('checked', false);
            } else if ($('.event-checkbox:checked').length === $('.event-checkbox').length) {
                $('#check-all').prop('checked', true);
            }
        });
        
        // Bulk Action Seçimi
        $('#bulk-action-select').change(function() {
            // Tüm ek seçenekleri gizle
            $('.bulk-option').hide();
            $('#bulk-action-warning').text('');
            
            // Seçilen işleme göre ilgili seçenekleri göster
            var selectedAction = $(this).val();
            if (selectedAction === 'change_status') {
                $('#status-options').show();
            } else if (selectedAction === 'change_homepage') {
                $('#homepage-options').show();
            } else if (selectedAction === 'delete') {
                $('#bulk-action-warning').text('DİKKAT: Bu işlem seçili tüm etkinlikleri ve ilişkili görselleri kalıcı olarak silecektir!');
            }
        });
        
        // Apply Bulk Action Butonu
        $('#apply-bulk-action').click(function(e) {
            var selectedAction = $('#bulk-action-select').val();
            var checkedCount = $('.event-checkbox:checked').length;
            
            // İşlem seçilmediyse veya etkinlik seçilmediyse uyarı ver
            if (!selectedAction) {
                e.preventDefault();
                alert('Lütfen bir işlem seçin.');
                return;
            }
            
            if (checkedCount === 0) {
                e.preventDefault();
                alert('Lütfen en az bir etkinlik seçin.');
                return;
            }
            
            // Silme işlemi için onay iste
            if (selectedAction === 'delete') {
                if (!confirm(checkedCount + ' etkinliği silmek istediğinize emin misiniz? Bu işlem geri alınamaz!')) {
                    e.preventDefault();
                }
            }
        });
        
        // Seçili etkinlik sayısına göre toplu işlem formunu göster/gizle
        function updateBulkActionVisibility() {
            if ($('.event-checkbox:checked').length > 0) {
                $('.bulk-actions').slideDown();
            } else {
                $('.bulk-actions').slideUp();
                // Seçenekleri sıfırla
                $('#bulk-action-select').val('');
                $('.bulk-option').hide();
                $('#bulk-action-warning').text('');
            }
        }
    });
</script>
@stop 