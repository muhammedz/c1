@extends('adminlte::page')

@section('title', 'Kullanıcılar')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Kullanıcılar</h1>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Yeni Kullanıcı Ekle
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
                        <th>Ad Soyad</th>
                        <th>E-posta</th>
                        <th>Roller</th>
                        <th>Durum</th>
                        <th>Kayıt Tarihi</th>
                        <th width="180">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>
                                @if($user->avatar)
                                    <img src="{{ asset($user->avatar) }}" alt="{{ $user->name }}" class="img-circle mr-2" width="30" height="30">
                                @else
                                    <img src="{{ asset('vendor/adminlte/dist/img/user2-160x160.jpg') }}" alt="{{ $user->name }}" class="img-circle mr-2" width="30" height="30">
                                @endif
                                {{ $user->name }}
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @foreach($user->roles as $role)
                                    <span class="badge badge-primary">{{ $role->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                @if($user->is_active)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-danger">Pasif</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('d.m.Y') }}</td>
                            <td>
                                <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bu kullanıcıyı silmek istediğinizden emin misiniz?');">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Henüz hiç kullanıcı bulunmuyor.</td>
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