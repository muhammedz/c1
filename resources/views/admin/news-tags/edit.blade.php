@extends('adminlte::page')

@section('title', 'Haber Etiketi Düzenle')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Haber Etiketi Düzenle</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.news-tags.index') }}">Haber Etiketleri</a></li>
        <li class="breadcrumb-item active">Haber Etiketi Düzenle</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-edit me-1"></i> {{ $newsTag->name }} Haber Etiketini Düzenle
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
            
            <form action="{{ route('admin.news-tags.update', $newsTag->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="name" class="form-label">Haber Etiketi Adı <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $newsTag->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Haber Etiketi Bilgileri</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label">Slug</label>
                                <div class="col-sm-9">
                                    <p class="form-control-plaintext">{{ $newsTag->slug }}</p>
                                </div>
                            </div>
                            
                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label">Kullanım Sayısı</label>
                                <div class="col-sm-9">
                                    <p class="form-control-plaintext">
                                        <span class="badge bg-secondary">{{ $newsTag->usage_count }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">İlişkili Haberler</h5>
                        </div>
                        <div class="card-body">
                            @if($newsTag->news->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm table-striped">
                                        <thead>
                                            <tr>
                                                <th>Başlık</th>
                                                <th class="text-center">Durum</th>
                                                <th class="text-center">İşlem</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($newsTag->news->take(5) as $news)
                                                <tr>
                                                    <td>{{ $news->title }}</td>
                                                    <td class="text-center">
                                                        <span class="badge bg-{{ $news->status === 'published' ? 'success' : 'warning' }}">
                                                            {{ $news->status === 'published' ? 'Yayında' : 'Taslak' }}
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="{{ route('admin.news.edit', $news->id) }}" class="btn btn-sm btn-primary">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                
                                @if($newsTag->news->count() > 5)
                                    <div class="text-center mt-2">
                                        <span class="text-muted">{{ $newsTag->news->count() - 5 }} haber daha...</span>
                                    </div>
                                @endif
                            @else
                                <div class="alert alert-info mb-0">
                                    Bu haber etiketine ait haber bulunmamaktadır.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.news-tags.index') }}" class="btn btn-secondary me-2">İptal</a>
                    <button type="submit" class="btn btn-primary">Güncelle</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 