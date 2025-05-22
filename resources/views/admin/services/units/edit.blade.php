@extends('adminlte::page')

@section('title', 'Birim Düzenle')

@section('content_header')
    <h1>Birim Düzenle</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Birim Düzenle</h3>
                    </div>
                    <form action="{{ route('admin.services.units.update', $unit) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div class="form-group">
                                <label for="name">Birim Adı</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $unit->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="description">Açıklama</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $unit->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="status" name="status" value="1" {{ old('status', $unit->status) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="status">Aktif</label>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Güncelle</button>
                            <a href="{{ route('admin.services.units.index') }}" class="btn btn-secondary">İptal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop 