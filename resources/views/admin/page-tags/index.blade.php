@extends('adminlte::page')

@section('title', 'Sayfa Etiketleri')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Sayfa Etiketleri</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Sayfa Etiketleri</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div><i class="fas fa-tags me-1"></i> Sayfa Etiketi Listesi</div>
            <div>
                <a href="{{ route('admin.page-tags.cleanup') }}" class="btn btn-warning btn-sm me-2">
                    <i class="fas fa-broom"></i> Kullanılmayan Etiketleri Temizle
                </a>
                <a href="{{ route('admin.page-tags.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Yeni Sayfa Etiketi
                </a>
            </div>
        </div>
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
            
            <div class="mb-3">
                <form action="{{ route('admin.page-tags.index') }}" method="GET" class="row g-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Sayfa etiketi ara..." value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Ad</th>
                            <th>Slug</th>
                            <th style="width: 120px;" class="text-center">Kullanım</th>
                            <th style="width: 150px;">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pageTags as $pageTag)
                        <tr>
                            <td>{{ $pageTag->name }}</td>
                            <td>{{ $pageTag->slug }}</td>
                            <td class="text-center">
                                <span class="badge bg-secondary">{{ $pageTag->usage_count }}</span>
                            </td>
                            <td>
                                <div class="d-flex">
                                    <a href="{{ route('admin.page-tags.edit', $pageTag->id) }}" class="btn btn-primary btn-sm me-2">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.page-tags.destroy', $pageTag->id) }}" method="POST" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bu sayfa etiketini silmek istediğinize emin misiniz?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">Henüz sayfa etiketi bulunmamaktadır.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $pageTags->links() }}
            </div>
        </div>
    </div>
</div>
@endsection 