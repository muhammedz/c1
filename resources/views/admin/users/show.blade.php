@extends('adminlte::page')

@section('title', 'Kullanıcı Detayları')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Kullanıcı Detayları</h1>
        <div>
            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Düzenle
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Geri
            </a>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        @if($user->avatar)
                            <img class="profile-user-img img-fluid img-circle" 
                                 src="{{ asset('storage/' . $user->avatar) }}" 
                                 alt="{{ $user->name }}">
                        @else
                            <img class="profile-user-img img-fluid img-circle"
                                 src="{{ asset('vendor/adminlte/dist/img/user2-160x160.jpg') }}"
                                 alt="{{ $user->name }}">
                        @endif
                    </div>

                    <h3 class="profile-username text-center">{{ $user->name }}</h3>

                    <p class="text-muted text-center">
                        {{ $user->roles->pluck('name')->implode(', ') ?: 'Rol Atanmamış' }}
                    </p>

                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>E-posta</b> <a class="float-right">{{ $user->email }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Durum</b> <a class="float-right">
                                @if($user->is_active)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-danger">Pasif</span>
                                @endif
                            </a>
                        </li>
                        <li class="list-group-item">
                            <b>Kayıt Tarihi</b> <a class="float-right">{{ $user->created_at->format('d.m.Y H:i') }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Son Güncelleme</b> <a class="float-right">{{ $user->updated_at->format('d.m.Y H:i') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#about" data-toggle="tab">Hakkında</a></li>
                        <li class="nav-item"><a class="nav-link" href="#activity" data-toggle="tab">Aktivite</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="active tab-pane" id="about">
                            <div class="card-body">
                                <strong><i class="fas fa-book mr-1"></i> Biyografi</strong>
                                <p class="text-muted">
                                    {!! $user->bio ? nl2br(html_entity_decode($user->bio, ENT_QUOTES, 'UTF-8')) : '<em>Biyografi bilgisi girilmemiş.</em>' !!}
                                </p>
                                <hr>
                                
                                <strong><i class="fas fa-user-tag mr-1"></i> Roller</strong>
                                <p class="text-muted">
                                    @if($user->roles->count() > 0)
                                        @foreach($user->roles as $role)
                                            <span class="badge badge-info">{{ $role->name }}</span>
                                        @endforeach
                                    @else
                                        <em>Rol atanmamış.</em>
                                    @endif
                                </p>
                                <hr>
                                
                                <strong><i class="fas fa-key mr-1"></i> İzinler</strong>
                                <p class="text-muted">
                                    @php
                                        $permissions = $user->getAllPermissions();
                                    @endphp
                                    
                                    @if($permissions->count() > 0)
                                        <div class="row">
                                            @foreach($permissions as $permission)
                                                <div class="col-md-4 mb-1">
                                                    <span class="badge badge-secondary">{{ $permission->name }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <em>Hiçbir özel izin atanmamış.</em>
                                    @endif
                                </p>
                            </div>
                        </div>
                        
                        <div class="tab-pane" id="activity">
                            <div class="post">
                                <!-- Burada kullanıcının aktivite geçmişi gösterilebilir -->
                                <p>Aktivite kaydı henüz uygulanmadı. Daha sonra eklenecek.</p>
                            </div>
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
            // Tab switching için jQuery
            $('a[data-toggle="tab"]').on('click', function(e) {
                e.preventDefault();
                $(this).tab('show');
            });
        });
    </script>
@stop 