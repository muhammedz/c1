@extends('adminlte::page')

@section('title', 'Yeni Haber Etiketi')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Yeni Haber Etiketi</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.news-tags.index') }}">Haber Etiketleri</a></li>
        <li class="breadcrumb-item active">Yeni Haber Etiketi</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-plus me-1"></i> Yeni Haber Etiketi Ekle
        </div>
        <div class="card-body">
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            
            <form action="{{ route('admin.news-tags.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="name" class="form-label">Haber Etiketi Adı <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Etiket adı otomatik olarak küçük harfe çevrilecek ve özel karakterler kaldırılacaktır.</div>
                </div>
                
                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.news-tags.index') }}" class="btn btn-secondary me-2">İptal</a>
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 