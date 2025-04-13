@extends('adminlte::page')

@section('title', 'Rol Detayı')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Rol Detayı: {{ $role->name }}</h1>
        <div>
            <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Düzenle
            </a>
            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Geri
            </a>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Rol Bilgileri</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th width="150">ID</th>
                            <td>{{ $role->id }}</td>
                        </tr>
                        <tr>
                            <th>Rol Adı</th>
                            <td>{{ $role->name }}</td>
                        </tr>
                        <tr>
                            <th>Oluşturulma Tarihi</th>
                            <td>{{ $role->created_at->format('d.m.Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Güncelleme Tarihi</th>
                            <td>{{ $role->updated_at->format('d.m.Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Bu Rolle Atanmış Kullanıcılar</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table">
                        <thead>
                            <tr>
                                <th width="10">ID</th>
                                <th>Ad Soyad</th>
                                <th>E-posta</th>
                                <th width="120">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($role->users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Bu role sahip kullanıcı bulunmuyor.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">İzinler ({{ $role->permissions->count() }})</h3>
        </div>
        <div class="card-body">
            <div class="row">
                @forelse($role->permissions as $permission)
                    <div class="col-md-3 mb-2">
                        <span class="badge badge-primary p-2">
                            {{ ucwords(str_replace('_', ' ', $permission->name)) }}
                        </span>
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-muted">Bu role atanmış izin bulunmuyor.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        // İhtiyaç durumunda JS eklenebilir
    </script>
@stop 