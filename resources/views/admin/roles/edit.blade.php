@extends('adminlte::page')

@section('title', 'Rol Düzenle')

@section('content_header')
    <h1>Rol Düzenle: {{ $role->name }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="name">Rol Adı <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $role->name) }}" required {{ $role->name === 'admin' ? 'readonly' : '' }}>
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label>İzinler</label>
                    <div class="row">
                        @foreach($permissions as $permission)
                            <div class="col-md-3 mb-2">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="permission{{ $permission->id }}" name="permissions[]" value="{{ $permission->id }}" {{ in_array($permission->id, old('permissions', $rolePermissions)) ? 'checked' : '' }}>
                                    <label for="permission{{ $permission->id }}" class="custom-control-label">{{ ucwords(str_replace('_', ' ', $permission->name)) }}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @error('permissions')
                        <span class="text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Güncelle</button>
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">İptal</a>
                </div>
            </form>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Select/Deselect All işlevi buraya eklenebilir
        });
    </script>
@stop 