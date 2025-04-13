@extends('adminlte::page')

@section('title', 'Etkinlikler Modülü Ayarları')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Etkinlikler Modülü Ayarları</h1>
        <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Etkinliklere Dön
        </a>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Genel Ayarlar</h3>
                    
                    <div class="card-tools">
                        <button type="button" id="toggle-visibility-btn" 
                                class="btn {{ $settings->is_active ? 'btn-success' : 'btn-danger' }} btn-sm">
                            <i class="fas {{ $settings->is_active ? 'fa-eye' : 'fa-eye-slash' }} mr-1"></i>
                            {{ $settings->is_active ? 'Aktif' : 'Pasif' }}
                        </button>
                    </div>
                </div>
                
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fas fa-check"></i> Başarılı!</h5>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fas fa-ban"></i> Hata!</h5>
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <form action="{{ route('admin.events.settings.update') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Bölüm Başlığı -->
                                <div class="form-group">
                                    <label for="title">Etkinlikler Başlığı</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" 
                                           value="{{ old('title', $settings->title) }}" required>
                                    <small class="form-text text-muted">Etkinlikler sayfasında görüntülenecek olan başlık.</small>
                                    @error('title')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <!-- Bölüm Açıklaması -->
                                <div class="form-group">
                                    <label for="description">Açıklama</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="3">{{ old('description', $settings->description) }}</textarea>
                                    <small class="form-text text-muted">Etkinlikler sayfasında görüntülenecek açıklama (opsiyonel).</small>
                                    @error('description')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <!-- Ana Sayfa Bölüm Başlığı -->
                                <div class="form-group">
                                    <label for="section_title">Ana Sayfa Bölüm Başlığı</label>
                                    <input type="text" class="form-control @error('section_title') is-invalid @enderror" 
                                           id="section_title" name="section_title" 
                                           value="{{ old('section_title', $settings->section_title) }}">
                                    <small class="form-text text-muted">Ana sayfada görüntülenecek bölüm başlığı.</small>
                                    @error('section_title')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <!-- Ana Sayfa Bölüm Alt Başlığı -->
                                <div class="form-group">
                                    <label for="section_subtitle">Ana Sayfa Bölüm Alt Başlığı</label>
                                    <input type="text" class="form-control @error('section_subtitle') is-invalid @enderror" 
                                           id="section_subtitle" name="section_subtitle" 
                                           value="{{ old('section_subtitle', $settings->section_subtitle) }}">
                                    <small class="form-text text-muted">Ana sayfada görüntülenecek bölüm alt başlığı.</small>
                                    @error('section_subtitle')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <!-- Ana Sayfa Etkinlik Sayısı -->
                                <div class="form-group">
                                    <label for="homepage_limit">Ana Sayfada Gösterilecek Etkinlik Sayısı</label>
                                    <input type="number" class="form-control @error('homepage_limit') is-invalid @enderror" 
                                           id="homepage_limit" name="homepage_limit" min="1" max="20" 
                                           value="{{ old('homepage_limit', $settings->homepage_limit) }}" required>
                                    <small class="form-text text-muted">Ana sayfada kaç etkinlik gösterileceği.</small>
                                    @error('homepage_limit')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <!-- Görünürlük Ayarları -->
                                <div class="card card-outline card-success">
                                    <div class="card-header">
                                        <h3 class="card-title">Görünürlük Ayarları</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" 
                                                       id="is_active" name="is_active" value="1" 
                                                       {{ old('is_active', $settings->is_active) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="is_active">Etkinlikler Modülünü Aktifleştir</label>
                                                <small class="form-text text-muted d-block">Bu ayar kapatılırsa, etkinlikler bölümü web sitesinde görüntülenmez.</small>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" 
                                                       id="show_past_events" name="show_past_events" value="1" 
                                                       {{ old('show_past_events', $settings->show_past_events) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="show_past_events">Geçmiş Etkinlikleri Göster</label>
                                                <small class="form-text text-muted d-block">Geçmiş etkinlikleri web sitesinde göster/gizle.</small>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" 
                                                       id="show_category_filter" name="show_category_filter" value="1" 
                                                       {{ old('show_category_filter', $settings->show_category_filter) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="show_category_filter">Kategori Filtrelerini Göster</label>
                                                <small class="form-text text-muted d-block">Etkinlik sayfalarında kategori filtreleme özelliğini göster/gizle.</small>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" 
                                                       id="show_map" name="show_map" value="1" 
                                                       {{ old('show_map', $settings->show_map) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="show_map">Haritayı Göster</label>
                                                <small class="form-text text-muted d-block">Etkinlik detay sayfasında, etkinlik konumunun haritasını göster/gizle.</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i> Ayarları Kaydet
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(function() {
            // Modül görünürlüğünü değiştir
            $('#toggle-visibility-btn').click(function() {
                $.ajax({
                    url: '{{ route("admin.events.toggle-module-visibility") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        const btn = $('#toggle-visibility-btn');
                        if (btn.hasClass('btn-success')) {
                            btn.removeClass('btn-success').addClass('btn-danger');
                            btn.find('i').removeClass('fa-eye').addClass('fa-eye-slash');
                            btn.contents().filter(function() {
                                return this.nodeType === 3;
                            }).replaceWith(' Pasif');
                        } else {
                            btn.removeClass('btn-danger').addClass('btn-success');
                            btn.find('i').removeClass('fa-eye-slash').addClass('fa-eye');
                            btn.contents().filter(function() {
                                return this.nodeType === 3;
                            }).replaceWith(' Aktif');
                        }
                        
                        // Sayfayı yenile
                        toastr.success('Modül görünürlüğü güncellendi.');
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    },
                    error: function(xhr) {
                        toastr.error('Bir hata oluştu: ' + xhr.responseJSON.message);
                    }
                });
            });
        });
    </script>
@stop 