@extends('adminlte::page')

@section('title', 'Roller')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Roller</h1>
        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Yeni Rol Ekle
        </a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
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
            
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th width="10">ID</th>
                        <th>Rol Adı</th>
                        <th>İzin Sayısı</th>
                        <th>Oluşturulma Tarihi</th>
                        <th width="180">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $role)
                        <tr>
                            <td>{{ $role->id }}</td>
                            <td>{{ $role->name }}</td>
                            <td>{{ $role->permissions->count() }}</td>
                            <td>{{ $role->created_at->format('d.m.Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.roles.show', $role->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($role->name !== 'admin')
                                <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bu rolü silmek istediğinizden emin misiniz?');">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Henüz hiç rol bulunmuyor.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Datatable uygulaması eklenebilir
        });
    </script>
@stop 