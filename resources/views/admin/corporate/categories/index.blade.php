@extends('adminlte::page')

@section('title', 'Kurumsal Kadro Kategorileri')

@section('content_header')
    <h1>Kurumsal Kadro Kategorileri</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Kurumsal Kadro Kategorileri</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.corporate.categories.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Yeni Kategori Ekle
                        </a>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                    @endif

                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th style="width: 50px">#</th>
                                <th style="width: 80px">Görsel</th>
                                <th>Kategori Adı</th>
                                <th>Slug</th>
                                <th>Sıralama</th>
                                <th>Durum</th>
                                <th>Üye Sayısı</th>
                                <th style="width: 250px">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody id="sortable-categories">
                            @foreach($categories as $category)
                            <tr class="category-row" data-id="{{ $category->id }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    @if($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}" class="img-thumbnail" alt="{{ $category->name }}" style="max-width: 50px;">
                                    @else
                                    <span class="text-muted">Görsel yok</span>
                                    @endif
                                </td>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->slug }}</td>
                                <td>{{ $category->order }}</td>
                                <td>
                                    @if($category->status)
                                    <span class="badge badge-success">Aktif</span>
                                    @else
                                    <span class="badge badge-danger">Pasif</span>
                                    @endif
                                </td>
                                <td>{{ $category->members->count() }}</td>
                                <td>
                                    <a href="{{ route('corporate.category', $category->slug) }}" class="btn btn-primary btn-sm" target="_blank">
                                        <i class="fas fa-eye"></i> Görüntüle
                                    </a>
                                    <a href="{{ route('admin.corporate.members.index', $category->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-users"></i> Üyeler ({{ $category->members->count() }})
                                    </a>
                                    <a href="{{ route('admin.corporate.categories.edit', $category->id) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-pencil-alt"></i> Düzenle
                                    </a>
                                    <form action="{{ route('admin.corporate.categories.destroy', $category->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bu kategoriyi silmek istediğinize emin misiniz?')">
                                            <i class="fas fa-trash"></i> Sil
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $(function() {
        $("#sortable-categories").sortable({
            items: "tr",
            cursor: 'move',
            opacity: 0.6,
            update: function() {
                sendOrderToServer();
            }
        });

        function sendOrderToServer() {
            var order = [];
            $('tr.category-row').each(function(index, element) {
                order.push({
                    id: $(this).attr('data-id'),
                    position: index + 1
                });
            });

            $.ajax({
                type: "POST",
                dataType: "json",
                url: "{{ route('admin.corporate.categories.update-order') }}",
                data: {
                    order: order,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        console.log(response);
                        toastr.success('Kategori sıralaması güncellendi');
                    } else {
                        console.log(response);
                        toastr.error('Bir hata oluştu');
                    }
                }
            });
        }
    });
</script>
@stop 