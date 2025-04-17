@extends('adminlte::page')

@section('title', 'Sayfa Ayarları')

@section('content_header')
    <h1>Sayfa Ayarları</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Sayfa Listesi (Frontend) Ayarları</h3>
                </div>
                
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fas fa-check"></i> Başarılı!</h5>
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fas fa-ban"></i> Hata!</h5>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form action="{{ route('admin.pages.settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Hero Bölümü Ayarları</h3>
                                    </div>
                                    
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="hero_badge_text">Badge Metni</label>
                                            <input type="text" class="form-control" id="hero_badge_text" name="hero_badge_text" 
                                                value="{{ old('hero_badge_text', $settings->hero_badge_text) }}">
                                            <small class="form-text text-muted">Hero bölümünde üstte yer alan etiket metni.</small>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="hero_title">Ana Başlık</label>
                                            <input type="text" class="form-control" id="hero_title" name="hero_title" 
                                                value="{{ old('hero_title', $settings->hero_title) }}">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="hero_title_highlight">Vurgulanan Kelime</label>
                                            <input type="text" class="form-control" id="hero_title_highlight" name="hero_title_highlight" 
                                                value="{{ old('hero_title_highlight', $settings->hero_title_highlight) }}">
                                            <small class="form-text text-muted">Ana başlıkta farklı renkle vurgulanan kelime.</small>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="hero_description">Açıklama Metni</label>
                                            <textarea class="form-control" id="hero_description" name="hero_description" rows="3">{{ old('hero_description', $settings->hero_description) }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card card-info">
                                    <div class="card-header">
                                        <h3 class="card-title">Arama Bölümü Ayarları</h3>
                                    </div>
                                    
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="search_title">Arama Başlığı</label>
                                            <input type="text" class="form-control" id="search_title" name="search_title" 
                                                value="{{ old('search_title', $settings->search_title) }}">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="search_placeholder">Arama Kutusu Placeholder</label>
                                            <input type="text" class="form-control" id="search_placeholder" name="search_placeholder" 
                                                value="{{ old('search_placeholder', $settings->search_placeholder) }}">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="search_button_text">Arama Butonu Metni</label>
                                            <input type="text" class="form-control" id="search_button_text" name="search_button_text" 
                                                value="{{ old('search_button_text', $settings->search_button_text) }}">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card card-warning">
                                    <div class="card-header">
                                        <h3 class="card-title">Popüler Aramalar Ayarları</h3>
                                    </div>
                                    
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="popular_searches_title">Popüler Aramalar Başlığı</label>
                                            <input type="text" class="form-control" id="popular_searches_title" name="popular_searches_title" 
                                                value="{{ old('popular_searches_title', $settings->popular_searches_title) }}">
                                        </div>
                                        
                                        <h5 class="mt-4 mb-3">Popüler Arama Bağlantıları</h5>
                                        
                                        <div id="popular-searches-container">
                                            @if(is_array($settings->popular_searches) && count($settings->popular_searches) > 0)
                                                @foreach($settings->popular_searches as $index => $search)
                                                    <div class="row search-item mb-2">
                                                        <div class="col-md-5">
                                                            <input type="text" class="form-control" name="popular_search_text[]" 
                                                                placeholder="Bağlantı Metni" value="{{ $search['text'] }}">
                                                        </div>
                                                        <div class="col-md-5">
                                                            <input type="text" class="form-control" name="popular_search_query[]" 
                                                                placeholder="Arama Sorgusu" value="{{ $search['search'] }}">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <button type="button" class="btn btn-danger btn-sm remove-search">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="row search-item mb-2">
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control" name="popular_search_text[]" 
                                                            placeholder="Bağlantı Metni" value="">
                                                    </div>
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control" name="popular_search_query[]" 
                                                            placeholder="Arama Sorgusu" value="">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button type="button" class="btn btn-danger btn-sm remove-search">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <button type="button" id="add-search" class="btn btn-sm btn-success mt-2">
                                            <i class="fas fa-plus"></i> Arama Ekle
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Ayarları Kaydet
                                </button>
                                <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Sayfalar Listesine Dön
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
    $(document).ready(function() {
        // Yeni arama ekle
        $('#add-search').click(function() {
            const searchItemHTML = `
                <div class="row search-item mb-2">
                    <div class="col-md-5">
                        <input type="text" class="form-control" name="popular_search_text[]" 
                            placeholder="Bağlantı Metni" value="">
                    </div>
                    <div class="col-md-5">
                        <input type="text" class="form-control" name="popular_search_query[]" 
                            placeholder="Arama Sorgusu" value="">
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger btn-sm remove-search">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            
            $('#popular-searches-container').append(searchItemHTML);
        });
        
        // Arama öğesini kaldır
        $(document).on('click', '.remove-search', function() {
            $(this).closest('.search-item').remove();
        });
    });
</script>
@stop 