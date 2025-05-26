@extends('adminlte::page')

@section('title', 'İhaleler Yönetimi')

@section('content_header')
    <h1>İhaleler Yönetimi</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h3 class="card-title">İhaleler Listesi</h3>
                <a href="{{ route('admin.tenders.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Yeni İhale Ekle
                </a>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="width: 50px">ID</th>
                            <th>İhale Konusu</th>
                            <th>İhale Birimi</th>
                            <th>İhale Tarihi</th>
                            <th>Durum</th>
                            <th style="width: 150px">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tenders as $tender)
                            <tr>
                                <td>{{ $tender->id }}</td>
                                <td>{{ $tender->title }}</td>
                                <td>{{ $tender->unit }}</td>
                                <td>
                                    @if ($tender->tender_datetime)
                                        {{ $tender->tender_datetime->format('d.m.Y H:i') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $tender->status == 'active' ? 'bg-success' : ($tender->status == 'cancelled' ? 'bg-danger' : 'bg-info') }}">
                                        {{ $tender->status_text }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.tenders.edit', $tender->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('admin.tenders.show', $tender->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="{{ route('admin.tenders.toggle-status', $tender->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-warning">
                                                <i class="fas fa-exchange-alt"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.tenders.destroy', $tender->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bu ihaleyi silmek istediğinizden emin misiniz?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Henüz kayıtlı ihale bulunmamaktadır.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $tenders->links() }}
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .btn-group {
            display: flex;
        }
        .btn-group form {
            margin: 0 2px;
        }
    </style>
@stop 