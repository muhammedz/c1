@extends('adminlte::page')

@section('title', 'Profil')

@section('content_header')
    <h1>Profil</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        @if(auth()->user()->avatar)
                            <img class="profile-user-img img-fluid img-circle" src="{{ asset(auth()->user()->avatar) }}" alt="User profile picture">
                        @else
                            <img class="profile-user-img img-fluid img-circle" src="{{ asset('vendor/adminlte/dist/img/user2-160x160.jpg') }}" alt="User profile picture">
                        @endif
                    </div>

                    <h3 class="profile-username text-center">{{ auth()->user()->name }}</h3>

                    <p class="text-muted text-center">
                        @foreach(auth()->user()->roles as $role)
                            <span class="badge badge-primary">{{ $role->name }}</span>
                        @endforeach
                    </p>

                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>E-posta</b> <a class="float-right">{{ auth()->user()->email }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Son giriş</b> <a class="float-right">{{ auth()->user()->last_login_at ? auth()->user()->last_login_at->format('d.m.Y H:i') : 'Bilgi yok' }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Üyelik tarihi</b> <a class="float-right">{{ auth()->user()->created_at->format('d.m.Y') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#profile" data-toggle="tab">Profil Bilgileri</a></li>
                        <li class="nav-item"><a class="nav-link" href="#password" data-toggle="tab">Şifre Değiştir</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="active tab-pane" id="profile">
                            @if(session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            
                            <form class="form-horizontal" method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                
                                <div class="form-group row">
                                    <label for="name" class="col-sm-2 col-form-label">Ad Soyad</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', auth()->user()->name) }}">
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label for="email" class="col-sm-2 col-form-label">E-posta</label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', auth()->user()->email) }}">
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label for="bio" class="col-sm-2 col-form-label">Biyografi</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control @error('bio') is-invalid @enderror" id="bio" name="bio" rows="3">{{ old('bio', auth()->user()->bio) }}</textarea>
                                        @error('bio')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label for="avatar" class="col-sm-2 col-form-label">Profil Resmi</label>
                                    <div class="col-sm-10">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input @error('avatar') is-invalid @enderror" id="avatar" name="avatar">
                                            <label class="custom-file-label" for="avatar">Resim Seç</label>
                                        </div>
                                        @error('avatar')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <div class="offset-sm-2 col-sm-10">
                                        <button type="submit" class="btn btn-primary">Kaydet</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <div class="tab-pane" id="password">
                            <form class="form-horizontal" method="POST" action="{{ route('password.update') }}">
                                @csrf
                                
                                <div class="form-group row">
                                    <label for="current_password" class="col-sm-3 col-form-label">Mevcut Şifre</label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password">
                                        @error('current_password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label for="password" class="col-sm-3 col-form-label">Yeni Şifre</label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label for="password_confirmation" class="col-sm-3 col-form-label">Şifre Tekrar</label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <div class="offset-sm-3 col-sm-9">
                                        <button type="submit" class="btn btn-primary">Şifreyi Değiştir</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Dosya yükleme alanı için görsel ismi gösterme
            $(document).on('change', '.custom-file-input', function() {
                let fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').addClass("selected").html(fileName);
            });
        });
    </script>
@stop 