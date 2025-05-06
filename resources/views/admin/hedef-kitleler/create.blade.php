@extends('adminlte::page')

@section('title', 'Yeni Hedef Kitle')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">Yeni Hedef Kitle</h1>
            <p class="mb-0 text-muted">Haberleriniz için yeni bir hedef kitle oluşturun</p>
        </div>
        <a href="{{ route('admin.hedef-kitleler.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Listeye Dön
        </a>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <!-- Ana Bilgiler -->
            <div class="card card-primary card-outline mb-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle mr-1"></i>
                        Temel Bilgiler
                    </h3>
                </div>
                
                <form action="{{ route('admin.hedef-kitleler.store') }}" method="POST" id="hedefKitleForm">
                    @csrf
                    <div class="card-body">
                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        
                        <div class="form-group">
                            <label for="name" class="font-weight-bold">
                                <i class="fas fa-users text-primary mr-1"></i>
                                Hedef Kitle Adı <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                id="name" name="name" value="{{ old('name') }}" 
                                placeholder="Örn: Öğrenciler" required>
                            <small class="form-text text-muted">
                                Hedef kitle adı, haberlerinizi belirli kitlelere göre sınıflandırmak için kullanılır. Net ve anlaşılır bir isim seçin.
                            </small>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="description" class="font-weight-bold">
                                <i class="fas fa-align-left text-primary mr-1"></i>
                                Açıklama
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                id="description" name="description" rows="3" 
                                placeholder="Hedef kitle hakkında kısa bir açıklama (isteğe bağlı)">{{ old('description') }}</textarea>
                            <small class="form-text text-muted">
                                Bu açıklama, hedef kitlenin özelliklerini ve hangi içerik türlerine ilgi duyabileceğini tanımlar.
                            </small>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                
                    <!-- Ayarlar Bölümü -->
                    <div class="card-header border-top">
                        <h3 class="card-title">
                            <i class="fas fa-cogs mr-1"></i>
                            Hedef Kitle Ayarları
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="order" class="font-weight-bold">
                                        <i class="fas fa-sort-numeric-down text-primary mr-1"></i>
                                        Sıralama Önceliği
                                    </label>
                                    <input type="number" class="form-control @error('order') is-invalid @enderror" 
                                        id="order" name="order" value="{{ old('order', 0) }}" min="0">
                                    <small class="form-text text-muted">
                                        Hedef kitlenin görüntülenme sırasını belirler. Küçük değerler üstte görünür.
                                    </small>
                                    @error('order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label class="font-weight-bold d-block">
                                        <i class="fas fa-toggle-on text-primary mr-1"></i>
                                        Durum
                                    </label>
                                    <div class="custom-control custom-switch custom-switch-lg">
                                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_active">Hedef Kitleyi Aktif Et</label>
                                    </div>
                                    <small class="form-text text-muted">
                                        Pasif hedef kitleler web sitesinde görünmez ve kullanılamaz.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.hedef-kitleler.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times mr-1"></i> İptal
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save mr-1"></i> Hedef Kitleyi Kaydet
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Yardım Kutusu -->
            <div class="card bg-light mb-4">
                <div class="card-header bg-light">
                    <h3 class="card-title">
                        <i class="fas fa-question-circle mr-1"></i>
                        Hızlı Yardım
                    </h3>
                </div>
                <div class="card-body">
                    <div class="callout callout-info">
                        <h5><i class="fas fa-info mr-1"></i> Bilgi</h5>
                        <p class="mb-0">Hedef kitleler, içeriklerinizi belirli kullanıcı gruplarına yönlendirmenizi sağlar. Doğru hedef kitle tanımlamaları, içeriklerinizin ilgili kişilere ulaşmasını kolaylaştırır.</p>
                    </div>
                    
                    <div class="callout callout-warning">
                        <h5><i class="fas fa-lightbulb mr-1"></i> İpuçları</h5>
                        <ul class="pl-3 mb-0">
                            <li>Net ve anlaşılır hedef kitle adları kullanın</li>
                            <li>İçeriklerinizi ilgili hedef kitlelere bağlayın</li>
                            <li>Çok genel veya çok spesifik tanımlamalardan kaçının</li>
                            <li>Hedef kitleye özel içerik oluşturmayı planlayın</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Form gönderilmeden önce kontrol
        $('#hedefKitleForm').on('submit', function(e) {
            const name = $('#name').val().trim();
            
            if (!name) {
                e.preventDefault();
                toastr.error('Lütfen hedef kitle adını giriniz.');
                $('#name').addClass('is-invalid').focus();
                return false;
            }
            
            // Gönderme butonunu devre dışı bırak
            $('#submitBtn').attr('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Kaydediliyor...');
        });
    });
</script>
@endpush 