@extends('adminlte::page')

@section('title', 'Yeni Kurs Ekle')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Yeni Kurs Ekle</h1>
        <a href="{{ route('admin.cankaya-house-courses.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kurs Listesine Dön
        </a>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Kurs Bilgileri</h3>
                </div>
                
                <form action="{{ route('admin.cankaya-house-courses.store') }}" method="POST">
                    @csrf
                    
                    <div class="card-body">
                        <div class="row">
                            <!-- Çankaya Evi Seçimi -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cankaya_house_id">Çankaya Evi <span class="text-danger">*</span></label>
                                    <select name="cankaya_house_id" id="cankaya_house_id" class="form-control @error('cankaya_house_id') is-invalid @enderror" required>
                                        <option value="">Çankaya Evi Seçin</option>
                                        @foreach($cankayaHouses as $house)
                                            <option value="{{ $house->id }}" {{ old('cankaya_house_id') == $house->id ? 'selected' : '' }}>
                                                {{ $house->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('cankaya_house_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Kurs Adı -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Kurs Adı <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                                           value="{{ old('name') }}" required>
                                    @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Açıklama -->
                        <div class="form-group">
                            <label for="description">Açıklama</label>
                            <textarea name="description" id="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <!-- Başlangıç Tarihi -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_date">Başlangıç Tarihi <span class="text-danger">*</span></label>
                                    <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror" 
                                           value="{{ old('start_date') }}" required>
                                    @error('start_date')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Bitiş Tarihi -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_date">Bitiş Tarihi <span class="text-danger">*</span></label>
                                    <input type="date" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror" 
                                           value="{{ old('end_date') }}" required>
                                    @error('end_date')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <!-- Eğitmen -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="instructor">Eğitmen</label>
                                    <input type="text" name="instructor" id="instructor" class="form-control @error('instructor') is-invalid @enderror" 
                                           value="{{ old('instructor') }}">
                                    @error('instructor')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Kapasite -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="capacity">Kapasite</label>
                                    <input type="number" name="capacity" id="capacity" class="form-control @error('capacity') is-invalid @enderror" 
                                           value="{{ old('capacity') }}" min="1">
                                    @error('capacity')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Fiyat -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="price">Fiyat (₺)</label>
                                    <input type="number" name="price" id="price" class="form-control @error('price') is-invalid @enderror" 
                                           value="{{ old('price') }}" min="0" step="0.01">
                                    @error('price')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">Ücretsiz kurslar için boş bırakın</small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Durum -->
                        <div class="form-group">
                            <label for="status">Durum</label>
                            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Pasif</option>
                            </select>
                            @error('status')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Kaydet
                        </button>
                        <a href="{{ route('admin.cankaya-house-courses.index') }}" class="btn btn-secondary ml-2">
                            <i class="fas fa-times"></i> İptal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
$(document).ready(function() {
    // Tarih validasyonu
    $('#start_date, #end_date').on('change', function() {
        var startDate = $('#start_date').val();
        var endDate = $('#end_date').val();
        
        if (startDate && endDate) {
            if (new Date(startDate) > new Date(endDate)) {
                toastr.error('Başlangıç tarihi bitiş tarihinden sonra olamaz!');
                $(this).val('');
            }
        }
    });
});
</script>
@stop 