@extends('adminlte::page')

@section('title', 'Projeler Modülü Ayarları')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Projeler Modülü Ayarları</h1>
        <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Projelere Dön
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
                    
                    <form action="{{ route('admin.projects.settings.update') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Bölüm Başlığı -->
                                <div class="form-group">
                                    <label for="section_title">Bölüm Başlığı</label>
                                    <input type="text" class="form-control @error('section_title') is-invalid @enderror" 
                                           id="section_title" name="section_title" 
                                           value="{{ old('section_title', $settings->section_title) }}" required>
                                    <small class="form-text text-muted">Anasayfada görüntülenecek olan başlık.</small>
                                    @error('section_title')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <!-- Bölüm Açıklaması -->
                                <div class="form-group">
                                    <label for="section_description">Bölüm Açıklaması</label>
                                    <textarea class="form-control @error('section_description') is-invalid @enderror" 
                                              id="section_description" name="section_description" rows="3">{{ old('section_description', $settings->section_description) }}</textarea>
                                    <small class="form-text text-muted">Bölüm başlığının altında görüntülenecek açıklama (opsiyonel).</small>
                                    @error('section_description')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <!-- Sayfa Başına Öğe Sayısı -->
                                <div class="form-group">
                                    <label for="items_per_page">Sayfa Başına Öğe Sayısı</label>
                                    <input type="number" class="form-control @error('items_per_page') is-invalid @enderror" 
                                           id="items_per_page" name="items_per_page" min="1" max="24" 
                                           value="{{ old('items_per_page', $settings->items_per_page) }}" required>
                                    <small class="form-text text-muted">Kategori sayfalarında sayfa başına kaç proje gösterileceği.</small>
                                    @error('items_per_page')
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
                                                <label class="custom-control-label" for="is_active">Projeler Modülünü Aktifleştir</label>
                                                <small class="form-text text-muted d-block">Bu ayar kapatılırsa, projeler bölümü web sitesinde görüntülenmez.</small>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" 
                                                       id="show_categories" name="show_categories" value="1" 
                                                       {{ old('show_categories', $settings->show_categories) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="show_categories">Kategori Filtrelerini Göster</label>
                                                <small class="form-text text-muted d-block">Proje listesinde kategori filtrelerini göster/gizle.</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- "Tümünü Gör" Düğmesi Ayarları -->
                                <div class="card card-outline card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">"Tümünü Gör" Düğmesi</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" 
                                                       id="show_view_all_button" name="show_view_all_button" value="1" 
                                                       {{ old('show_view_all_button', $settings->show_view_all_button) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="show_view_all_button">"Tümünü Gör" Düğmesini Göster</label>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="view_all_text">Düğme Metni</label>
                                            <input type="text" class="form-control @error('view_all_text') is-invalid @enderror" 
                                                   id="view_all_text" name="view_all_text" 
                                                   value="{{ old('view_all_text', $settings->view_all_text) }}">
                                            @error('view_all_text')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="view_all_url">Düğme URL</label>
                                            <input type="text" class="form-control @error('view_all_url') is-invalid @enderror" 
                                                   id="view_all_url" name="view_all_url" 
                                                   value="{{ old('view_all_url', $settings->view_all_url) }}" 
                                                   placeholder="{{ route('front.projects') }}">
                                            <small class="form-text text-muted">Boş bırakırsanız varsayılan olarak tüm projelerin listelendiği sayfa kullanılır.</small>
                                            @error('view_all_url')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
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
        $(document).ready(function() {
            // Görünürlük değiştirme
            $('#toggle-visibility-btn').click(function() {
                $.ajax({
                    url: '{{ route('admin.projects.toggle-module-visibility') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            const button = $('#toggle-visibility-btn');
                            
                            if (response.is_active) {
                                button.removeClass('btn-danger').addClass('btn-success');
                                button.find('i').removeClass('fa-eye-slash').addClass('fa-eye');
                                button.text(' Aktif');
                                button.prepend('<i class="fas fa-eye mr-1"></i>');
                                $('#is_active').prop('checked', true);
                            } else {
                                button.removeClass('btn-success').addClass('btn-danger');
                                button.find('i').removeClass('fa-eye').addClass('fa-eye-slash');
                                button.text(' Pasif');
                                button.prepend('<i class="fas fa-eye-slash mr-1"></i>');
                                $('#is_active').prop('checked', false);
                            }
                            
                            toastr.success('Görünürlük durumu değiştirildi.');
                        } else {
                            toastr.error('Bir hata oluştu.');
                        }
                    },
                    error: function() {
                        toastr.error('Bir hata oluştu.');
                    }
                });
            });
            
            // "Tümünü Gör" düğmesi ayarları
            $('#show_view_all_button').change(function() {
                const isChecked = $(this).is(':checked');
                $('#view_all_text, #view_all_url').prop('disabled', !isChecked);
                
                if (!isChecked) {
                    $('#view_all_text, #view_all_url').closest('.form-group').addClass('text-muted');
                } else {
                    $('#view_all_text, #view_all_url').closest('.form-group').removeClass('text-muted');
                }
            });
            
            // Sayfa yüklendiğinde "Tümünü Gör" düğmesi ayarları kontrolü
            if (!$('#show_view_all_button').is(':checked')) {
                $('#view_all_text, #view_all_url').prop('disabled', true);
                $('#view_all_text, #view_all_url').closest('.form-group').addClass('text-muted');
            }
        });
    </script>
@stop 