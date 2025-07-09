@extends('adminlte::page')

@section('title')
    @isset($categoryObject)
        "{{ $categoryObject->name }}" Kategorisi Üyeleri
    @else
        Tüm Kurumsal Kadro Üyeleri
    @endisset
@stop

@section('content_header')
    <h1>
        @isset($categoryObject)
            "{{ $categoryObject->name }}" Kategorisi Üyeleri
        @else
            Tüm Kurumsal Kadro Üyeleri
        @endisset
    </h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        @isset($categoryObject)
                            "{{ $categoryObject->name }}" Kategorisi Üyeleri
                        @else
                            Tüm Kurumsal Kadro Üyeleri
                        @endisset
                    </h3>
                    <div class="card-tools">
                        @isset($categoryObject)
                        <a href="{{ route('admin.corporate.members.create', $categoryObject->id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Yeni Üye Ekle
                        </a>
                        <a href="{{ route('admin.corporate.categories.index') }}" class="btn btn-default btn-sm ml-2">
                            <i class="fas fa-list"></i> Tüm Kategoriler
                        </a>
                        @else
                        <a href="{{ route('admin.corporate.categories.index') }}" class="btn btn-default btn-sm">
                            <i class="fas fa-list"></i> Kategorilere Git
                        </a>
                        @endisset
                    </div>
                </div>
                
                <div class="card-body p-0">
                    <!-- Arama Formu -->
                    <div class="p-3 bg-light border-bottom">
                        <form action="{{ isset($categoryObject) ? route('admin.corporate.members.index', $categoryObject->id) : route('admin.corporate.members.index') }}" method="GET" class="form-inline">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Üye ara..." value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i> Ara
                                    </button>
                                </div>
                            </div>
                            @if(request()->filled('search'))
                            <a href="{{ isset($categoryObject) ? route('admin.corporate.members.index', $categoryObject->id) : route('admin.corporate.members.index') }}" class="btn btn-outline-secondary ml-2">
                                <i class="fas fa-times"></i> Filtreyi Temizle
                            </a>
                            @endif
                        </form>
                    </div>
                
                <div class="table-responsive p-0">
                    @if(session('success'))
                    <div class="alert alert-success m-3">
                        {{ session('success') }}
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger m-3">
                        {{ session('error') }}
                    </div>
                    @endif

                    @if(count($members) > 0)
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th style="width: 50px">#</th>
                                <th style="width: 80px">Görsel</th>
                                <th>Ad Soyad</th>
                                <th>Unvan</th>
                                <th>Kategori</th>
                                <th>Sıralama</th>
                                <th>Durum</th>
                                <th style="width: 200px">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody id="sortable-members">
                            @foreach($members as $member)
                            <tr data-id="{{ $member->id }}" class="member-row">
                                <td>{{ ($members->currentPage() - 1) * $members->perPage() + $loop->iteration }}</td>
                                <td>
                                    @if($member->image)
                                    <img src="{{ asset($member->image) }}" class="img-thumbnail" alt="{{ $member->name }}" style="max-width: 50px;">
                                    @else
                                    <span class="text-muted">Görsel yok</span>
                                    @endif
                                </td>
                                <td>{{ $member->name }}</td>
                                <td>{{ $member->title }}</td>
                                <td>{{ $member->category->name }}</td>
                                <td>{{ $member->order }}</td>
                                <td>
                                    @if($member->status)
                                    <span class="badge badge-success">Aktif</span>
                                    @else
                                    <span class="badge badge-danger">Pasif</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.corporate.members.edit', $member->id) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-pencil-alt"></i> Düzenle
                                    </a>
                                    <form action="{{ route('admin.corporate.members.destroy', $member->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bu üyeyi silmek istediğinize emin misiniz?')">
                                            <i class="fas fa-trash"></i> Sil
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    <!-- Laravel Pagination Linkleri -->
                    <div class="pagination-wrapper">
                        <div class="d-flex justify-content-center">
                            {{ $members->links('custom.pagination') }}
                        </div>
                    </div>
                    @else
                    <div class="alert alert-info m-3">
                        Henüz kayıtlı üye bulunmamaktadır.
                        @isset($categoryObject)
                        <a href="{{ route('admin.corporate.members.create', $categoryObject->id) }}" class="btn btn-primary btn-sm ml-2">
                            <i class="fas fa-plus"></i> Yeni Üye Ekle
                        </a>
                        @endisset
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    /* Pagination Stilleri */
    .pagination {
        gap: 5px;
        margin: 0;
    }
    
    .pagination .page-item .page-link {
        border: 1px solid #dee2e6;
        color: #495057;
        font-size: 0.875rem;
        padding: 8px 12px;
        border-radius: 6px;
        transition: all 0.2s ease;
        background-color: #fff;
        margin: 0 2px;
    }
    
    .pagination .page-item .page-link:hover {
        background-color: #e9ecef;
        border-color: #adb5bd;
        color: #495057;
    }
    
    .pagination .page-item.active .page-link {
        background-color: #007bff;
        border-color: #007bff;
        color: #fff;
        font-weight: 500;
    }
    
    .pagination .page-item.disabled .page-link {
        color: #6c757d;
        background-color: #fff;
        border-color: #dee2e6;
        cursor: not-allowed;
    }
    
    /* Sadece SVG ok işaretlerini gizle, yazıları koru */
    .pagination .page-link svg,
    .pagination .page-link path,
    .pagination .page-item .page-link svg,
    .pagination .page-item .page-link path {
        display: none !important;
        visibility: hidden !important;
        opacity: 0 !important;
    }
    
    /* Previous/Next butonlarını küçük ve şık yap */
    .pagination .page-item:first-child .page-link,
    .pagination .page-item:last-child .page-link {
        font-size: 0.875rem;
        padding: 8px 12px;
        font-weight: 500;
    }
    
    /* Pagination container */
    .pagination-wrapper {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        border-top: 1px solid #dee2e6;
    }
</style>
@stop

@section('js')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $(function() {
        $("#sortable-members").sortable({
            handle: 'td:first',
            update: function(event, ui) {
                var members = [];
                $('.member-row').each(function() {
                    members.push($(this).data('id'));
                });
                
                $.ajax({
                    url: "{{ route('admin.corporate.members.order') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        members: members
                    },
                    success: function(response) {
                        if (response.success) {
                            // Sıralama güncellendi, yenilemeye gerek yok
                            $('.member-row').each(function(index) {
                                $(this).find('td:first').text(index + 1);
                                $(this).find('td:nth-child(6)').text(index);
                            });
                        }
                    }
                });
            }
        });
    });
</script>
@stop 