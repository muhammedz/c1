@extends('adminlte::page')

@section('title', 'Hizmet Sayfası Ayarları')

@section('content_header')
    <h1>Hizmet Sayfası Ayarları</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Hizmetler Sayfası Genel Ayarları</h3>
            </div>
            
            <form action="{{ route('admin.services.services-settings.update') }}" method="POST">
                @csrf
                <div class="card-body">
                    
                    <!-- Hero Bölümü -->
                    <div class="form-group">
                        <label for="hero_title">Ana Başlık</label>
                        <input type="text" class="form-control @error('hero_title') is-invalid @enderror" 
                               id="hero_title" name="hero_title" 
                               value="{{ old('hero_title', $settings->hero_title) }}">
                        @error('hero_title')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="hero_title_highlight">Vurgulanan Kelime</label>
                        <input type="text" class="form-control @error('hero_title_highlight') is-invalid @enderror" 
                               id="hero_title_highlight" name="hero_title_highlight" 
                               value="{{ old('hero_title_highlight', $settings->hero_title_highlight) }}">
                        @error('hero_title_highlight')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="hero_subtitle">Alt Başlık</label>
                        <input type="text" class="form-control @error('hero_subtitle') is-invalid @enderror" 
                               id="hero_subtitle" name="hero_subtitle" 
                               value="{{ old('hero_subtitle', $settings->hero_subtitle) }}">
                        @error('hero_subtitle')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="hero_description">Açıklama</label>
                        <textarea class="form-control @error('hero_description') is-invalid @enderror" 
                                  id="hero_description" name="hero_description" rows="3">{{ old('hero_description', $settings->hero_description) }}</textarea>
                        @error('hero_description')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="hero_image">Hero Görseli URL</label>
                        <input type="text" class="form-control @error('hero_image') is-invalid @enderror" 
                               id="hero_image" name="hero_image" 
                               value="{{ old('hero_image', $settings->hero_image) }}">
                        @error('hero_image')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <hr>

                    <!-- SEO Bölümü -->
                    <h5>SEO Ayarları</h5>
                    
                    <div class="form-group">
                        <label for="meta_title">Meta Başlık</label>
                        <input type="text" class="form-control @error('meta_title') is-invalid @enderror" 
                               id="meta_title" name="meta_title" 
                               value="{{ old('meta_title', $settings->meta_title) }}">
                        @error('meta_title')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="meta_description">Meta Açıklama</label>
                        <textarea class="form-control @error('meta_description') is-invalid @enderror" 
                                  id="meta_description" name="meta_description" rows="3">{{ old('meta_description', $settings->meta_description) }}</textarea>
                        @error('meta_description')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="meta_keywords">Meta Anahtar Kelimeler</label>
                        <input type="text" class="form-control @error('meta_keywords') is-invalid @enderror" 
                               id="meta_keywords" name="meta_keywords" 
                               value="{{ old('meta_keywords', $settings->meta_keywords) }}"
                               placeholder="Virgülle ayırarak yazın">
                        @error('meta_keywords')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                </div>
                
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Kaydet
                    </button>
                    <a href="{{ route('admin.services.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Geri Dön
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .card-header h3 {
        margin: 0;
    }
</style>
@stop

@section('js')
<script>
    // Form submit edildiğinde loading göster
    $('form').on('submit', function() {
        $(this).find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Kaydediliyor...');
    });
</script>
@stop 