@extends('adminlte::page')

@section('title', 'Birimler')

@section('content_header')
    <h1>Birimler</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h3 class="card-title">Birimler</h3>
                        <a href="{{ route('admin.services.units.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Yeni Birim
                        </a>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th style="width: 50px">#</th>
                                    <th>Birim Adı</th>
                                    <th>Slug</th>
                                    <th>Sıralama</th>
                                    <th>Durum</th>
                                    <th style="width: 200px">İşlemler</th>
                                </tr>
                            </thead>
                            <tbody id="sortable-units">
                                @foreach($units as $unit)
                                    <tr class="unit-row" data-id="{{ $unit->id }}">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $unit->name }}</td>
                                        <td>{{ $unit->slug }}</td>
                                        <td>{{ $unit->order }}</td>
                                        <td>
                                            @if($unit->status)
                                                <span class="badge badge-success">Aktif</span>
                                            @else
                                                <span class="badge badge-danger">Pasif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.services.units.edit', $unit) }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-edit"></i> Düzenle
                                            </a>
                                            <form action="{{ route('admin.services.units.destroy', $unit) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bu birimi silmek istediğinize emin misiniz?')">
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

@section('css')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@stop

@section('js')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $(function() {
            $("#sortable-units").sortable({
                items: "tr",
                cursor: "move",
                opacity: 0.6,
                update: function() {
                    let order = [];
                    $('.unit-row').each(function(index, element) {
                        order.push($(element).attr('data-id'));
                    });
                    
                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: '{{ route('admin.services.units.update-order') }}',
                        data: {
                            _token: '{{ csrf_token() }}',
                            units: order
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success('Sıralama başarıyla güncellendi');
                            }
                        }
                    });
                }
            });
        });
    </script>
@stop 