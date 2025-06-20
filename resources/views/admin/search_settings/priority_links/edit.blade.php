@extends('adminlte::page')

@section('title', 'Öncelik Linki Düzenle')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit"></i> Öncelik Linki Düzenle
                    </h3>
                </div>
                
                <form action="{{ route('admin.search-priority-links.update', $priorityLink) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="search_keywords">Arama Anahtar Kelimeleri <span class="text-danger">*</span></label>
                                    <textarea name="search_keywords" id="search_keywords" class="form-control @error('search_keywords') is-invalid @enderror" rows="3" placeholder="projeler,proje,project,belediye projeleri" required>{{ old('search_keywords', $priorityLink->search_keywords) }}</textarea>
                                    <small class="form-text text-muted">Bu kelimeler arandığında link gösterilecek. Virgülle ayırın.</small>
                                    @error('search_keywords')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">Başlık <span class="text-danger">*</span></label>
                                    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $priorityLink->title) }}" placeholder="Projeler Sayfası" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="url">URL <span class="text-danger">*</span></label>
                                    <input type="text" name="url" id="url" class="form-control @error('url') is-invalid @enderror" value="{{ old('url', $priorityLink->url) }}" placeholder="/projeler" required>
                                    <small class="form-text text-muted">Örnek: /projeler, /haberler, https://example.com</small>
                                    @error('url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="icon">İkon (Font Awesome)</label>
                                    <input type="text" name="icon" id="icon" class="form-control @error('icon') is-invalid @enderror" value="{{ old('icon', $priorityLink->icon) }}" placeholder="fas fa-building">
                                    <small class="form-text text-muted">Örnek: fas fa-building, fas fa-newspaper</small>
                                    @error('icon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Açıklama</label>
                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="2" placeholder="Tüm belediye projelerini görüntüle">{{ old('description', $priorityLink->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="priority">Öncelik <span class="text-danger">*</span></label>
                                    <input type="number" name="priority" id="priority" class="form-control @error('priority') is-invalid @enderror" value="{{ old('priority', $priorityLink->priority) }}" min="1" max="100" required>
                                    @error('priority')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mt-4">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="isActive" name="is_active" value="1" {{ $priorityLink->is_active ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="isActive">Aktif</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Önizleme -->
                        <div class="form-group">
                            <label>Önizleme</label>
                            <div id="preview" class="bg-gradient-to-r from-gray-600 to-gray-700 text-white rounded-lg p-4" style="background: linear-gradient(to right, #4a5568, #2d3748);">
                                <div class="d-flex align-items-start">
                                    <div class="mr-3">
                                        <div class="bg-white bg-opacity-20 rounded p-2" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                            <i id="preview-icon" class="fas fa-link text-white"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 id="preview-title" class="text-white mb-1">Başlık</h5>
                                        <p id="preview-description" class="text-white-50 mb-2 small">Açıklama</p>
                                        <div class="d-flex align-items-center text-white-50 small">
                                            <i class="fas fa-external-link-alt mr-1"></i>
                                            <span id="preview-url">/url</span>
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <i class="fas fa-arrow-right text-white-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <div class="text-right">
                            <a href="{{ route('admin.search-settings.index') }}" class="btn btn-secondary">İptal</a>
                            <button type="submit" class="btn btn-primary">Güncelle</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(function() {
    // Önizleme güncelleme
    function updatePreview() {
        const title = $('#title').val() || 'Başlık';
        const description = $('#description').val() || 'Açıklama';
        const url = $('#url').val() || '/url';
        const icon = $('#icon').val() || 'fas fa-link';
        
        $('#preview-title').text(title);
        $('#preview-description').text(description);
        $('#preview-url').text(url);
        $('#preview-icon').attr('class', icon + ' text-white');
    }
    
    // Form alanları değiştiğinde önizlemeyi güncelle
    $('#title, #description, #url, #icon').on('input', updatePreview);
    
    // Sayfa yüklendiğinde önizlemeyi güncelle
    updatePreview();
});
</script>
@endsection 