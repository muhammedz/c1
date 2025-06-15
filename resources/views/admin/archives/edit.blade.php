@extends('adminlte::page')

@section('title', 'Arşiv Düzenle')

@section('css')
<style>
.sortable-ghost {
    opacity: 0.4;
    background: #f8f9fa;
}

.sortable-chosen {
    background: #e3f2fd;
    border-color: #2196f3;
}

.sortable-drag {
    background: #fff;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transform: rotate(5deg);
}

.category-item {
    transition: all 0.2s ease;
}

.category-item:hover {
    background-color: #f8f9fa;
    border-color: #dee2e6;
}

.drag-handle:hover {
    color: #495057 !important;
}
</style>
@stop

@section('content_header')
    <h1>Arşiv Düzenle: {{ $archive->title }}</h1>
@stop

@section('content')
    <div class="row">
        <!-- Sol Kolon - Arşiv Bilgileri -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Arşiv Bilgileri</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.archives.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Geri Dön
                        </a>
                    </div>
                </div>
                
                <form method="POST" action="{{ route('admin.archives.update', $archive) }}">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                {{ session('success') }}
                            </div>
                        @endif

                        <!-- Başlık -->
                        <div class="form-group">
                            <label for="title">Başlık <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title', $archive->title) }}" required>
                            @error('title')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Slug -->
                        <div class="form-group">
                            <label for="slug">URL Slug <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{ url('/') }}/archives/</span>
                                </div>
                                <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                                       id="slug" name="slug" value="{{ old('slug', $archive->slug) }}" required>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary" id="generate-slug" title="Başlıktan otomatik oluştur">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </div>
                            </div>
                            <small class="form-text text-muted">
                                URL'de görünecek kısa isim. Sadece harf, rakam ve tire (-) kullanın.
                            </small>
                            @error('slug')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Özet -->
                        <div class="form-group">
                            <label for="excerpt">Kısa Açıklama</label>
                            <textarea class="form-control @error('excerpt') is-invalid @enderror" 
                                      id="excerpt" name="excerpt" rows="3" 
                                      placeholder="Arşiv hakkında kısa bir açıklama yazın...">{{ old('excerpt', $archive->excerpt) }}</textarea>
                            @error('excerpt')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- İçerik -->
                        <div class="form-group">
                            <label for="content">İçerik</label>
                            <textarea class="form-control tinymce @error('content') is-invalid @enderror" 
                                      id="content" name="content">{{ old('content', $archive->content) }}</textarea>
                            @error('content')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Durum -->
                        <div class="form-group">
                            <label for="status">Durum <span class="text-danger">*</span></label>
                            <select class="form-control @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                <option value="draft" {{ old('status', $archive->status) == 'draft' ? 'selected' : '' }}>Taslak</option>
                                <option value="published" {{ old('status', $archive->status) == 'published' ? 'selected' : '' }}>Yayında</option>
                                <option value="archived" {{ old('status', $archive->status) == 'archived' ? 'selected' : '' }}>Arşivlenmiş</option>
                            </select>
                            @error('status')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Öne Çıkan -->
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" 
                                       id="is_featured" name="is_featured" value="1" 
                                       {{ old('is_featured', $archive->is_featured) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_featured">
                                    Öne Çıkan Arşiv
                                </label>
                            </div>
                        </div>

                        <!-- Yayın Tarihi -->
                        <div class="form-group">
                            <label for="published_at">Yayın Tarihi</label>
                            <input type="datetime-local" class="form-control @error('published_at') is-invalid @enderror" 
                                   id="published_at" name="published_at" 
                                   value="{{ old('published_at', $archive->published_at ? $archive->published_at->format('Y-m-d\TH:i') : '') }}">
                            @error('published_at')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Güncelle
                        </button>
                        <a href="{{ route('admin.archives.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> İptal
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sağ Kolon - Belgeler -->
        <div class="col-md-4">
            <!-- Belge Kategorileri -->
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">Belge Kategorileri</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary btn-sm" id="add-category-btn">
                            <i class="fas fa-plus"></i> Kategori Ekle
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="categories-list" class="sortable-categories">
                        @forelse($archive->documentCategories as $category)
                            <div class="category-item border rounded p-2 mb-2" data-id="{{ $category->id }}" data-order="{{ $category->order }}">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-grip-vertical text-muted mr-2 drag-handle" style="cursor: move;" title="Sürükle"></i>
                                        @if($category->icon)
                                            <i class="{{ $category->icon }} mr-2" style="color: {{ $category->color }}"></i>
                                        @endif
                                        <span class="category-name">{{ $category->name }}</span>
                                        <span class="badge badge-info ml-2">{{ $category->documents->count() }}</span>
                                        <small class="text-muted ml-2">(Sıra: {{ $category->order }})</small>
                                    </div>
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-primary edit-category" 
                                                data-id="{{ $category->id }}" title="Düzenle">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-danger delete-category" 
                                                data-id="{{ $category->id }}" title="Sil">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-3" id="no-categories">
                                <i class="fas fa-folder-open fa-2x mb-2"></i>
                                <p>Henüz kategori oluşturulmamış.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Belgeler -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Belgeler ({{ $archive->allDocuments->count() }})</h3>
                </div>
                <div class="card-body">
                    <!-- Toplu Belge Yükleme Formu -->
                    <form id="bulk-document-upload-form" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="bulk_category_id">Kategori</label>
                            <select class="form-control" id="bulk_category_id" name="category_id">
                                <option value="">Kategorisiz</option>
                                @foreach($archive->documentCategories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="bulk_document_files">Belgeler <span class="text-danger">*</span></label>
                            <input type="file" class="form-control-file" id="bulk_document_files" name="files[]" multiple required
                                   accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip,.rar">
                            <small class="form-text text-muted">
                                Birden fazla dosya seçebilirsiniz. Desteklenen formatlar: PDF, Word, Excel, PowerPoint, TXT, ZIP, RAR (Max: 50MB/dosya)
                            </small>
                        </div>
                        
                        <div id="selected-files-preview" class="mb-3" style="display: none;">
                            <h6>Seçilen Dosyalar:</h6>
                            <div id="files-list"></div>
                            <div id="upload-progress" class="mt-3" style="display: none;">
                                <h6>Yükleme Durumu:</h6>
                                <div id="progress-list"></div>
                                <div class="progress mt-2">
                                    <div id="overall-progress" class="progress-bar progress-bar-striped progress-bar-animated" 
                                         role="progressbar" style="width: 0%">0%</div>
                                </div>
                                <div id="error-summary" class="mt-3" style="display: none;">
                                    <h6 class="text-danger">
                                        <i class="fas fa-exclamation-triangle"></i> Hata Oluşan Dosyalar:
                                    </h6>
                                    <div id="error-list" class="alert alert-danger"></div>
                                </div>
                                <div id="success-summary" class="mt-3" style="display: none;">
                                    <h6 class="text-success">
                                        <i class="fas fa-check-circle"></i> Başarıyla Yüklenen Dosyalar:
                                    </h6>
                                    <div id="success-list" class="alert alert-success"></div>
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-success btn-block">
                            <i class="fas fa-upload"></i> Belgeleri Yükle
                        </button>
                    </form>

                    <hr>

                    <!-- Tek Belge Yükleme (Opsiyonel) -->
                    <div class="text-center mb-3">
                        <button type="button" class="btn btn-outline-primary btn-sm" id="toggle-single-upload">
                            <i class="fas fa-plus"></i> Tek Belge Ekle
                        </button>
                    </div>

                    <div id="single-upload-form" style="display: none;">
                        <form id="document-upload-form" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="document_category_id">Kategori</label>
                            <select class="form-control" id="document_category_id" name="category_id">
                                <option value="">Kategorisiz</option>
                                @foreach($archive->documentCategories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="document_name">Belge Adı <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="document_name" name="name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="document_description">Açıklama</label>
                            <textarea class="form-control" id="document_description" name="description" rows="2"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="document_file">Dosya <span class="text-danger">*</span></label>
                            <input type="file" class="form-control-file" id="document_file" name="file" required
                                   accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip,.rar">
                        </div>
                        
                        <div class="form-group">
                            <label for="document_sort_order">Sıra</label>
                            <input type="number" class="form-control" id="document_sort_order" name="sort_order" value="0" min="0">
                        </div>
                        
                            <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-upload"></i> Belge Yükle
                        </button>
                    </form>
                    </div>

                    <hr>

                    <!-- Toplu İşlemler -->
                    @if($archive->allDocuments->count() > 0)
                    <div class="mb-3" id="bulk-actions" style="display: none;">
                        <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                            <span id="selected-count" class="text-muted">0 belge seçildi</span>
                            <div>
                                <button type="button" class="btn btn-sm btn-danger" id="bulk-delete-btn">
                                    <i class="fas fa-trash"></i> Seçilenleri Sil
                                </button>
                                <button type="button" class="btn btn-sm btn-secondary" id="clear-selection-btn">
                                    <i class="fas fa-times"></i> Seçimi Temizle
                                </button>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Belgeler Listesi -->
                    <div id="documents-list">
                        @if($archive->allDocuments->count() > 1)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="select-all-documents">
                                    <label class="custom-control-label" for="select-all-documents">
                                        <strong>Tümünü Seç</strong>
                                    </label>
                                </div>
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-outline-secondary" id="view-by-category">
                                        <i class="fas fa-layer-group"></i> Kategoriye Göre
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary active" id="view-all">
                                        <i class="fas fa-list"></i> Tümü
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Kategoriye Göre Görünüm -->
                        <div id="category-view" style="display: none;">
                            @php
                                $documentsGrouped = $archive->allDocuments->groupBy('category_id');
                                $categorizedDocuments = $documentsGrouped->filter(function($documents, $categoryId) {
                                    return $categoryId !== null;
                                });
                                $uncategorizedDocuments = $documentsGrouped->get(null, collect());
                            @endphp

                            @foreach($archive->documentCategories->sortBy('order') as $category)
                                @if($categorizedDocuments->has($category->getKey()))
                                    <div class="category-group mb-4">
                                        <div class="category-header bg-light p-2 rounded mb-2">
                                            <h6 class="mb-0 d-flex align-items-center">
                                                @if($category->icon)
                                                    <i class="{{ $category->icon }} mr-2" style="color: {{ $category->color }}"></i>
                                                @endif
                                                {{ $category->name }}
                                                <span class="badge badge-info ml-2">{{ $categorizedDocuments->get($category->getKey())->count() }}</span>
                                            </h6>
                                            @if($category->description)
                                                <small class="text-muted">{{ $category->description }}</small>
                                            @endif
                                        </div>
                                        
                                        @foreach($categorizedDocuments->get($category->getKey())->sortByDesc('sort_order') as $document)
                                            <div class="document-item border rounded p-2 mb-2 ml-3" data-id="{{ $document->id }}">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div class="d-flex align-items-start">
                                                        <div class="custom-control custom-checkbox mr-2 mt-1">
                                                            <input type="checkbox" class="custom-control-input document-checkbox" 
                                                                   id="doc-cat-{{ $document->id }}" value="{{ $document->id }}">
                                                            <label class="custom-control-label" for="doc-cat-{{ $document->id }}"></label>
                                                        </div>
                                                        <div class="mr-2 mt-1">
                                                            <span class="badge badge-info" title="Sıra Numarası">{{ $document->sort_order }}</span>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1">
                                                                <i class="{{ $document->icon_class }}"></i>
                                                                {{ $document->name }}
                                                            </h6>
                                                            @if($document->description)
                                                                <p class="text-muted small mb-1">{{ $document->description }}</p>
                                                            @endif
                                                            <small class="text-muted">
                                                                {{ $document->file_name }} ({{ $document->formatted_size }})
                                                            </small>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex align-items-start">
                                                        <div class="mr-2">
                                                            <div class="input-group input-group-sm" style="width: 80px;">
                                                                <input type="number" class="form-control form-control-sm sort-input" 
                                                                       value="{{ $document->sort_order }}" min="0" max="9999"
                                                                       data-id="{{ $document->id }}" title="Sıra değiştir">
                                                                <div class="input-group-append">
                                                                    <button class="btn btn-outline-success btn-sm update-sort" 
                                                                            data-id="{{ $document->id }}" title="Sırayı Güncelle">
                                                                        <i class="fas fa-check"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="btn-group-vertical btn-group-sm">
                                                            <button type="button" class="btn btn-outline-primary btn-xs edit-document" 
                                                                    data-id="{{ $document->id }}" title="Düzenle">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-outline-danger btn-xs delete-document" 
                                                                    data-id="{{ $document->id }}" title="Sil">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-outline-secondary btn-xs toggle-status" 
                                                                    data-id="{{ $document->id }}" title="Durum">
                                                                <i class="fas fa-{{ $document->is_active ? 'eye' : 'eye-slash' }}"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            @endforeach

                            @if($uncategorizedDocuments->count() > 0)
                                <div class="category-group mb-4">
                                    <div class="category-header bg-light p-2 rounded mb-2">
                                        <h6 class="mb-0 d-flex align-items-center">
                                            <i class="fas fa-folder mr-2 text-muted"></i>
                                            Kategorisiz Belgeler
                                            <span class="badge badge-secondary ml-2">{{ $uncategorizedDocuments->count() }}</span>
                                        </h6>
                                    </div>
                                    
                                    @foreach($uncategorizedDocuments->sortByDesc('sort_order') as $document)
                                        <div class="document-item border rounded p-2 mb-2 ml-3" data-id="{{ $document->id }}">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div class="d-flex align-items-start">
                                                    <div class="custom-control custom-checkbox mr-2 mt-1">
                                                        <input type="checkbox" class="custom-control-input document-checkbox" 
                                                               id="doc-uncat-{{ $document->id }}" value="{{ $document->id }}">
                                                        <label class="custom-control-label" for="doc-uncat-{{ $document->id }}"></label>
                                                    </div>
                                                    <div class="mr-2 mt-1">
                                                        <span class="badge badge-info" title="Sıra Numarası">{{ $document->sort_order }}</span>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1">
                                                            <i class="{{ $document->icon_class }}"></i>
                                                            {{ $document->name }}
                                                        </h6>
                                                        @if($document->description)
                                                            <p class="text-muted small mb-1">{{ $document->description }}</p>
                                                        @endif
                                                        <small class="text-muted">
                                                            {{ $document->file_name }} ({{ $document->formatted_size }})
                                                        </small>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-start">
                                                    <div class="mr-2">
                                                        <div class="input-group input-group-sm" style="width: 80px;">
                                                            <input type="number" class="form-control form-control-sm sort-input" 
                                                                   value="{{ $document->sort_order }}" min="0" max="9999"
                                                                   data-id="{{ $document->id }}" title="Sıra değiştir">
                                                            <div class="input-group-append">
                                                                <button class="btn btn-outline-success btn-sm update-sort" 
                                                                        data-id="{{ $document->id }}" title="Sırayı Güncelle">
                                                                    <i class="fas fa-check"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="btn-group-vertical btn-group-sm">
                                                        <button type="button" class="btn btn-outline-primary btn-xs edit-document" 
                                                                data-id="{{ $document->id }}" title="Düzenle">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-outline-danger btn-xs delete-document" 
                                                                data-id="{{ $document->id }}" title="Sil">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-outline-secondary btn-xs toggle-status" 
                                                                data-id="{{ $document->id }}" title="Durum">
                                                            <i class="fas fa-{{ $document->is_active ? 'eye' : 'eye-slash' }}"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- Normal Liste Görünümü -->
                        <div id="normal-view">
                            @forelse($archive->allDocuments->sortByDesc('sort_order') as $document)
                                <div class="document-item border rounded p-2 mb-2" data-id="{{ $document->id }}">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="d-flex align-items-start">
                                            <div class="custom-control custom-checkbox mr-2 mt-1">
                                                <input type="checkbox" class="custom-control-input document-checkbox" 
                                                       id="doc-{{ $document->id }}" value="{{ $document->id }}">
                                                <label class="custom-control-label" for="doc-{{ $document->id }}"></label>
                                            </div>
                                            <div class="mr-2 mt-1">
                                                <span class="badge badge-info" title="Sıra Numarası">{{ $document->sort_order }}</span>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">
                                                    <i class="{{ $document->icon_class }}"></i>
                                                    {{ $document->name }}
                                                    @if($document->category)
                                                        <span class="badge badge-secondary ml-2" style="background-color: {{ $document->category->color }}">
                                                            @if($document->category->icon)
                                                                <i class="{{ $document->category->icon }}"></i>
                                                            @endif
                                                            {{ $document->category->name }}
                                                        </span>
                                                    @endif
                                                </h6>
                                                @if($document->description)
                                                    <p class="text-muted small mb-1">{{ $document->description }}</p>
                                                @endif
                                                <small class="text-muted">
                                                    {{ $document->file_name }} ({{ $document->formatted_size }})
                                                </small>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-start">
                                            <div class="mr-2">
                                                <div class="input-group input-group-sm" style="width: 80px;">
                                                    <input type="number" class="form-control form-control-sm sort-input" 
                                                           value="{{ $document->sort_order }}" min="0" max="9999"
                                                           data-id="{{ $document->id }}" title="Sıra değiştir">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-outline-success btn-sm update-sort" 
                                                                data-id="{{ $document->id }}" title="Sırayı Güncelle">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="btn-group-vertical btn-group-sm">
                                                <button type="button" class="btn btn-outline-primary btn-xs edit-document" 
                                                        data-id="{{ $document->id }}" title="Düzenle">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-danger btn-xs delete-document" 
                                                        data-id="{{ $document->id }}" title="Sil">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-secondary btn-xs toggle-status" 
                                                        data-id="{{ $document->id }}" title="Durum">
                                                    <i class="fas fa-{{ $document->is_active ? 'eye' : 'eye-slash' }}"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-muted py-3" id="no-documents">
                                    <i class="fas fa-folder-open fa-2x mb-2"></i>
                                    <p>Henüz belge yüklenmemiş.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Belge Düzenleme Modal -->
    <div class="modal fade" id="editDocumentModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Belge Düzenle</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form id="edit-document-form">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <input type="hidden" id="edit_document_id">
                        
                        <div class="form-group">
                            <label for="edit_document_name">Belge Adı <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_document_name" name="name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_document_description">Açıklama</label>
                            <textarea class="form-control" id="edit_document_description" name="description" rows="2"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_document_sort_order">Sıra</label>
                            <input type="number" class="form-control" id="edit_document_sort_order" name="sort_order" min="0">
                        </div>
                        
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="edit_document_is_active" name="is_active" value="1">
                                <label class="custom-control-label" for="edit_document_is_active">Aktif</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">İptal</button>
                        <button type="submit" class="btn btn-primary">Güncelle</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<!-- Kategori Ekleme/Düzenleme Modal -->
<div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" aria-labelledby="categoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="categoryModalLabel">Kategori Ekle</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="categoryForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="category_id" name="category_id">
                    
                    <div class="form-group">
                        <label for="category_name">Kategori Adı <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="category_name" name="name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="category_icon">İkon</label>
                        <input type="text" class="form-control" id="category_icon" name="icon" placeholder="fas fa-folder">
                        <small class="form-text text-muted">FontAwesome ikon sınıfı</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="category_color">Renk</label>
                        <input type="color" class="form-control" id="category_color" name="color" value="#3498db">
                    </div>
                    
                    <div class="form-group">
                        <label for="category_description">Açıklama</label>
                        <textarea class="form-control" id="category_description" name="description" rows="2"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="category_order">Sıra</label>
                        <input type="number" class="form-control" id="category_order" name="order" value="0" min="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">İptal</button>
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
// TinyMCE'yi dinamik olarak yükle
var script = document.createElement('script');
script.type = 'text/javascript';
script.src = '{{ asset("vendor/tinymce/tinymce/js/tinymce/tinymce.min.js") }}';
document.head.appendChild(script);

script.onload = function() {
    // TinyMCE Editör
    tinymce.init({
        selector: '.tinymce',
        plugins: 'preview importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons',
        menubar: 'file edit view insert format tools table help',
        toolbar: 'undo redo | bold italic underline strikethrough | fontfamily image fontsize blocks | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen preview save print | insertfile media template link anchor codesample | ltr rtl',
        toolbar_sticky: true,
        image_advtab: false,
        height: 400,
        quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
        quickbars_insert_toolbar: 'quickimage | quicktable quicklink hr',
        quickbars_insert_toolbar_hover: false,
        quickbars_image_toolbar: false,
        noneditable_class: 'mceNonEditable',
        language: 'tr',
        language_url: '/js/tinymce/langs/tr.js',
        toolbar_mode: 'sliding',
        contextmenu: 'link table',
        skin: 'oxide',
        content_css: 'default',
        images_upload_url: '{{ route("admin.tinymce.upload") }}',
        images_upload_credentials: true,
        branding: false,
        promotion: false,
        relative_urls: false,
        remove_script_host: false,
        convert_urls: false
    });
};

$(document).ready(function() {
    // Slug otomatik oluşturma
    function generateSlug(text) {
        return text
            .toLowerCase()
            .replace(/ğ/g, 'g')
            .replace(/ü/g, 'u')
            .replace(/ş/g, 's')
            .replace(/ı/g, 'i')
            .replace(/ö/g, 'o')
            .replace(/ç/g, 'c')
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .replace(/^-|-$/g, '');
    }

    // Başlık değiştiğinde slug'ı otomatik güncelle (sadece slug boşsa)
    $('#title').on('input', function() {
        var title = $(this).val();
        var currentSlug = $('#slug').val();
        
        // Eğer slug boşsa veya başlıktan otomatik oluşturulmuşsa güncelle
        if (!currentSlug || currentSlug === generateSlug($('#title').data('original-title') || '')) {
            $('#slug').val(generateSlug(title));
        }
    });

    // Slug oluştur butonu
    $('#generate-slug').on('click', function() {
        var title = $('#title').val();
        if (title) {
            $('#slug').val(generateSlug(title));
            $(this).find('i').addClass('fa-spin');
            setTimeout(() => {
                $(this).find('i').removeClass('fa-spin');
            }, 500);
        } else {
            toastr.warning('Önce başlık alanını doldurun.');
        }
    });

    // Slug input'unu temizle
    $('#slug').on('input', function() {
        var slug = $(this).val();
        $(this).val(generateSlug(slug));
    });

    // Orijinal başlığı sakla
    $('#title').data('original-title', $('#title').val());

    // Görünüm değiştirme butonları
    $('#view-by-category').on('click', function() {
        $('#normal-view').hide();
        $('#category-view').show();
        $(this).addClass('active');
        $('#view-all').removeClass('active');
        
        // Tümünü seç checkbox'ını güncelle
        updateBulkActions();
    });
    
    $('#view-all').on('click', function() {
        $('#category-view').hide();
        $('#normal-view').show();
        $(this).addClass('active');
        $('#view-by-category').removeClass('active');
        
        // Tümünü seç checkbox'ını güncelle
        updateBulkActions();
    });

    // Tek belge yükleme formunu göster/gizle
    $('#toggle-single-upload').on('click', function() {
        $('#single-upload-form').toggle();
        var icon = $(this).find('i');
        if ($('#single-upload-form').is(':visible')) {
            icon.removeClass('fa-plus').addClass('fa-minus');
            $(this).html('<i class="fas fa-minus"></i> Tek Belge Formunu Gizle');
        } else {
            icon.removeClass('fa-minus').addClass('fa-plus');
            $(this).html('<i class="fas fa-plus"></i> Tek Belge Ekle');
        }
    });

    // Dosya seçimi önizlemesi
    $('#bulk_document_files').on('change', function() {
        var files = this.files;
        var filesList = $('#files-list');
        var preview = $('#selected-files-preview');
        
        filesList.empty();
        
        // Progress alanını gizle ve temizle
        $('#upload-progress').hide();
        $('#error-summary').hide();
        $('#success-summary').hide();
        $('#reload-button').remove();
        $('#progress-list').empty();
        $('#overall-progress').css('width', '0%').text('0%');
        
        if (files.length > 0) {
            preview.show();
            
            for (var i = 0; i < files.length; i++) {
                var file = files[i];
                var fileSize = (file.size / 1024 / 1024).toFixed(2) + ' MB';
                var fileName = file.name;
                
                // Dosya uzantısına göre ikon belirle
                var extension = fileName.split('.').pop().toLowerCase();
                var iconClass = getFileIcon(extension);
                
                var fileItem = $(`
                    <div class="border rounded p-2 mb-2 d-flex align-items-center">
                        <i class="${iconClass} mr-2"></i>
                        <div class="flex-grow-1">
                            <div class="font-weight-bold">${fileName}</div>
                            <small class="text-muted">${fileSize}</small>
                        </div>
                        <div class="ml-2">
                            <input type="text" class="form-control form-control-sm file-name-input" 
                                   placeholder="Belge adı (opsiyonel)" data-index="${i}">
                        </div>
                    </div>
                `);
                
                filesList.append(fileItem);
            }
        } else {
            preview.hide();
        }
    });

    // Toplu belge yükleme formu
    $('#bulk-document-upload-form').on('submit', function(e) {
        e.preventDefault();
        
        var files = $('#bulk_document_files')[0].files;
        var submitBtn = $(this).find('button[type="submit"]');
        var originalText = submitBtn.html();
        
        if (files.length === 0) {
            toastr.error('Lütfen en az bir dosya seçin.');
            return;
        }
        
        // Progress alanını göster
        $('#upload-progress').show();
        var progressList = $('#progress-list');
        var overallProgress = $('#overall-progress');
        progressList.empty();
        
        // Her dosya için progress bar oluştur
        for (var i = 0; i < files.length; i++) {
            var fileName = files[i].name;
            var progressItem = $(`
                <div class="mb-2" data-file-index="${i}">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <small class="font-weight-bold">${fileName}</small>
                        <small class="text-muted file-status">Bekliyor...</small>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                    </div>
                </div>
            `);
            progressList.append(progressItem);
        }
        
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Yükleniyor...');
        
        // Dosyaları tek tek yükle
        uploadFilesSequentially(files, 0, [], []);
    });

    function uploadFilesSequentially(files, currentIndex, uploadedDocuments, errors) {
        if (currentIndex >= files.length) {
            // Tüm dosyalar işlendi
            handleUploadComplete(uploadedDocuments, errors);
            return;
        }
        
        var file = files[currentIndex];
        var customName = $(`.file-name-input[data-index="${currentIndex}"]`).val();
        var fileName = customName || file.name.split('.')[0];
        
        var formData = new FormData();
        formData.append('file', file);
        formData.append('name', fileName);
        formData.append('category_id', $('#bulk_category_id').val());
        formData.append('_token', '{{ csrf_token() }}');
        
        // Progress bar'ı güncelle
        var progressItem = $(`[data-file-index="${currentIndex}"]`);
        var progressBar = progressItem.find('.progress-bar');
        var statusText = progressItem.find('.file-status');
        
        statusText.text('Yükleniyor...');
        progressBar.addClass('progress-bar-animated progress-bar-striped');
        
        $.ajax({
            url: '{{ route("admin.archives.documents.store", $archive) }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function(evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = (evt.loaded / evt.total) * 100;
                        progressBar.css('width', percentComplete + '%');
                    }
                }, false);
                return xhr;
            },
            success: function(response) {
                if (response.success) {
                    progressBar.removeClass('progress-bar-animated progress-bar-striped')
                              .addClass('bg-success')
                              .css('width', '100%');
                    statusText.text('Başarılı').addClass('text-success');
                    uploadedDocuments.push(response.document);
                } else {
                    progressBar.removeClass('progress-bar-animated progress-bar-striped')
                              .addClass('bg-danger')
                              .css('width', '100%');
                    statusText.text('Hata').addClass('text-danger');
                    errors.push(`${file.name}: ${response.message || 'Bilinmeyen hata'}`);
                }
            },
            error: function(xhr) {
                progressBar.removeClass('progress-bar-animated progress-bar-striped')
                          .addClass('bg-danger')
                          .css('width', '100%');
                statusText.text('Hata').addClass('text-danger');
                
                var errorMsg = 'Bilinmeyen hata';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    var firstError = Object.values(xhr.responseJSON.errors)[0];
                    errorMsg = Array.isArray(firstError) ? firstError[0] : firstError;
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                errors.push(`${file.name}: ${errorMsg}`);
            },
            complete: function() {
                // Genel progress'i güncelle
                var completedCount = currentIndex + 1;
                var overallPercent = (completedCount / files.length) * 100;
                $('#overall-progress').css('width', overallPercent + '%').text(Math.round(overallPercent) + '%');
                
                // Sonraki dosyaya geç
                setTimeout(function() {
                    uploadFilesSequentially(files, currentIndex + 1, uploadedDocuments, errors);
                }, 500);
            }
        });
    }

    function handleUploadComplete(uploadedDocuments, errors) {
        var submitBtn = $('#bulk-document-upload-form').find('button[type="submit"]');
        var originalText = '<i class="fas fa-upload"></i> Belgeleri Yükle';
        
        submitBtn.prop('disabled', false).html(originalText);
        
        var successCount = uploadedDocuments.length;
        var errorCount = errors.length;
        
        // Başarılı dosyalar listesini göster
        if (successCount > 0) {
            var successList = $('#success-list');
            var successHtml = '<ul class="mb-0">';
            uploadedDocuments.forEach(function(doc) {
                successHtml += `<li><strong>${doc.name}</strong> (${doc.file_name})</li>`;
            });
            successHtml += '</ul>';
            successList.html(successHtml);
            $('#success-summary').show();
        }
        
        // Hata listesini göster
        if (errorCount > 0) {
            var errorList = $('#error-list');
            var errorHtml = '<ul class="mb-0">';
            errors.forEach(function(error) {
                errorHtml += `<li>${error}</li>`;
            });
            errorHtml += '</ul>';
            errorList.html(errorHtml);
            $('#error-summary').show();
        }
        
        // Toast mesajları
        if (successCount > 0 && errorCount === 0) {
            toastr.success(`${successCount} belge başarıyla yüklendi.`);
            setTimeout(function() {
                location.reload();
            }, 3000);
        } else if (successCount > 0 && errorCount > 0) {
            toastr.warning(`${successCount} belge yüklendi, ${errorCount} dosyada hata oluştu.`);
            // Sayfa yenileme butonunu göster
            showReloadButton();
        } else {
            toastr.error('Hiçbir belge yüklenemedi.');
            // Sayfa yenileme butonunu göster
            showReloadButton();
        }
    }

    function showReloadButton() {
        // Progress alanının altına yenileme butonu ekle
        if ($('#reload-button').length === 0) {
            var reloadBtn = $(`
                <div class="text-center mt-3" id="reload-button">
                    <button type="button" class="btn btn-primary" onclick="location.reload()">
                        <i class="fas fa-sync-alt"></i> Sayfayı Yenile
                    </button>
                    <button type="button" class="btn btn-secondary ml-2" onclick="resetUploadForm()">
                        <i class="fas fa-times"></i> Formu Temizle
                    </button>
                </div>
            `);
            $('#upload-progress').append(reloadBtn);
        }
    }

    function resetUploadForm() {
        // Formu sıfırla
        $('#bulk-document-upload-form')[0].reset();
        $('#selected-files-preview').hide();
        $('#upload-progress').hide();
        $('#error-summary').hide();
        $('#success-summary').hide();
        $('#reload-button').remove();
        
        // Progress listesini temizle
        $('#progress-list').empty();
        $('#overall-progress').css('width', '0%').text('0%');
    }

    // Tek belge yükleme formu
    $('#document-upload-form').on('submit', function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        var submitBtn = $(this).find('button[type="submit"]');
        var originalText = submitBtn.html();
        
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Yükleniyor...');
        
        $.ajax({
            url: '{{ route("admin.archives.documents.store", $archive) }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    // Formu temizle
                    $('#document-upload-form')[0].reset();
                    
                    // Başarı mesajı
                    toastr.success(response.message);
                    
                    // Belge listesini güncelle
                    location.reload();
                }
            },
            error: function(xhr) {
                var errors = xhr.responseJSON.errors;
                if (errors) {
                    var errorMsg = '';
                    $.each(errors, function(key, value) {
                        errorMsg += value[0] + '\n';
                    });
                    toastr.error(errorMsg);
                } else {
                    toastr.error('Bir hata oluştu.');
                }
            },
            complete: function() {
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });
    
    // Belge düzenleme
    $(document).on('click', '.edit-document', function() {
        var documentId = $(this).data('id');
        var documentItem = $(this).closest('.document-item');
        
        // Modal'ı doldur (basit bir yaklaşım - gerçek uygulamada AJAX ile veri çek)
        $('#edit_document_id').val(documentId);
        $('#editDocumentModal').modal('show');
    });
    
    // Belge düzenleme formu
    $('#edit-document-form').on('submit', function(e) {
        e.preventDefault();
        
        var documentId = $('#edit_document_id').val();
        var formData = $(this).serialize();
        
        $.ajax({
            url: '/admin/archive-documents/' + documentId,
            type: 'PUT',
            data: formData,
            success: function(response) {
                if (response.success) {
                    $('#editDocumentModal').modal('hide');
                    toastr.success(response.message);
                    location.reload();
                }
            },
            error: function(xhr) {
                toastr.error('Bir hata oluştu.');
            }
        });
    });
    
    // Belge silme
    $(document).on('click', '.delete-document', function() {
        if (!confirm('Bu belgeyi silmek istediğinizden emin misiniz?')) {
            return;
        }
        
        var documentId = $(this).data('id');
        var documentItem = $(this).closest('.document-item');
        
        $.ajax({
            url: '/admin/archive-documents/' + documentId,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    documentItem.fadeOut(function() {
                        $(this).remove();
                        
                        // Eğer hiç belge kalmadıysa "belge yok" mesajını göster
                        if ($('.document-item').length === 0) {
                            $('#documents-list').html(`
                                <div class="text-center text-muted py-3" id="no-documents">
                                    <i class="fas fa-folder-open fa-2x mb-2"></i>
                                    <p>Henüz belge yüklenmemiş.</p>
                                </div>
                            `);
                        }
                    });
                    toastr.success(response.message);
                }
            },
            error: function(xhr) {
                toastr.error('Bir hata oluştu.');
            }
        });
    });
    
    // Belge durumu değiştirme
    $(document).on('click', '.toggle-status', function() {
        var documentId = $(this).data('id');
        var button = $(this);
        
        $.ajax({
            url: '/admin/archive-documents/' + documentId + '/toggle-status',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    var icon = button.find('i');
                    if (response.is_active) {
                        icon.removeClass('fa-eye-slash').addClass('fa-eye');
                    } else {
                        icon.removeClass('fa-eye').addClass('fa-eye-slash');
                    }
                    toastr.success(response.message);
                }
            },
            error: function(xhr) {
                toastr.error('Bir hata oluştu.');
            }
        });
    });

    // Sıra güncelleme
    $(document).on('click', '.update-sort', function() {
        var documentId = $(this).data('id');
        var sortInput = $('.sort-input[data-id="' + documentId + '"]');
        var newSortOrder = sortInput.val();
        var button = $(this);
        var originalIcon = button.html();
        
        if (!newSortOrder || newSortOrder < 0) {
            toastr.warning('Lütfen geçerli bir sıra numarası girin.');
            return;
        }
        
        button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
        
        $.ajax({
            url: '/admin/archive-documents/' + documentId + '/update-sort',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                sort_order: newSortOrder
            },
            success: function(response) {
                if (response.success) {
                    // Badge'i güncelle
                    var badge = $('[data-id="' + documentId + '"]').find('.badge');
                    badge.text(newSortOrder);
                    
                    toastr.success(response.message);
                    
                    // Listeyi yeniden sırala (3 saniye sonra)
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                }
            },
            error: function(xhr) {
                var errorMessage = 'Sıra güncellenirken bir hata oluştu.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                toastr.error(errorMessage);
            },
            complete: function() {
                button.prop('disabled', false).html(originalIcon);
            }
        });
    });

    // Enter tuşu ile sıra güncelleme
    $(document).on('keypress', '.sort-input', function(e) {
        if (e.which === 13) { // Enter tuşu
            var documentId = $(this).data('id');
            $('.update-sort[data-id="' + documentId + '"]').click();
        }
    });

    // Dosya uzantısına göre ikon belirleme fonksiyonu
    function getFileIcon(extension) {
        var icons = {
            'pdf': 'fas fa-file-pdf text-danger',
            'doc': 'fas fa-file-word text-primary',
            'docx': 'fas fa-file-word text-primary',
            'xls': 'fas fa-file-excel text-success',
            'xlsx': 'fas fa-file-excel text-success',
            'ppt': 'fas fa-file-powerpoint text-warning',
            'pptx': 'fas fa-file-powerpoint text-warning',
            'txt': 'fas fa-file-alt text-secondary',
            'zip': 'fas fa-file-archive text-info',
            'rar': 'fas fa-file-archive text-info'
        };
        
        return icons[extension] || 'fas fa-file text-secondary';
    }

    // Toplu seçim fonksiyonları
    function updateBulkActions() {
        var selectedCount = $('.document-checkbox:checked:visible').length;
        $('#selected-count').text(selectedCount + ' belge seçildi');
        
        if (selectedCount > 0) {
            $('#bulk-actions').show();
        } else {
            $('#bulk-actions').hide();
        }
        
        // Tümünü seç checkbox'ını güncelle - sadece görünür checkbox'ları say
        var totalCount = $('.document-checkbox:visible').length;
        if (selectedCount === totalCount && totalCount > 0) {
            $('#select-all-documents').prop('checked', true).prop('indeterminate', false);
        } else if (selectedCount > 0) {
            $('#select-all-documents').prop('checked', false).prop('indeterminate', true);
        } else {
            $('#select-all-documents').prop('checked', false).prop('indeterminate', false);
        }
    }

    // Tümünü seç/seçme
    $('#select-all-documents').on('change', function() {
        var isChecked = $(this).is(':checked');
        $('.document-checkbox:visible').prop('checked', isChecked);
        updateBulkActions();
    });

    // Tekil checkbox değişimi
    $(document).on('change', '.document-checkbox', function() {
        updateBulkActions();
    });

    // Seçimi temizle
    $('#clear-selection-btn').on('click', function() {
        $('.document-checkbox:visible').prop('checked', false);
        $('#select-all-documents').prop('checked', false).prop('indeterminate', false);
        updateBulkActions();
    });

    // Toplu silme
    $('#bulk-delete-btn').on('click', function() {
        var selectedIds = [];
        $('.document-checkbox:checked').each(function() {
            selectedIds.push($(this).val());
        });

        if (selectedIds.length === 0) {
            toastr.warning('Lütfen silinecek belgeleri seçin.');
            return;
        }

        var confirmMessage = selectedIds.length === 1 
            ? 'Seçili belgeyi silmek istediğinizden emin misiniz?' 
            : selectedIds.length + ' belgeyi silmek istediğinizden emin misiniz?';

        if (!confirm(confirmMessage)) {
            return;
        }

        var button = $(this);
        var originalText = button.html();
        button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Siliniyor...');

        $.ajax({
            url: '{{ route("admin.archives.documents.bulk-delete", $archive) }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                document_ids: selectedIds
            },
            success: function(response) {
                if (response.success) {
                    // Seçili belgeleri DOM'dan kaldır
                    selectedIds.forEach(function(id) {
                        $('[data-id="' + id + '"]').fadeOut(function() {
                            $(this).remove();
                            
                            // Eğer hiç belge kalmadıysa "belge yok" mesajını göster
                            if ($('.document-item').length === 0) {
                                $('#documents-list').html(`
                                    <div class="text-center text-muted py-3" id="no-documents">
                                        <i class="fas fa-folder-open fa-2x mb-2"></i>
                                        <p>Henüz belge yüklenmemiş.</p>
                                    </div>
                                `);
                                $('#bulk-actions').hide();
                            }
                        });
                    });
                    
                    updateBulkActions();
                    toastr.success(response.message);
                }
            },
            error: function(xhr) {
                var errorMessage = 'Belgeler silinirken bir hata oluştu.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                toastr.error(errorMessage);
            },
            complete: function() {
                button.prop('disabled', false).html(originalText);
            }
        });
    });

    // Kategori Yönetimi
    
    // Kategori sıralama (Drag & Drop)
    var categoriesList = document.getElementById('categories-list');
    if (categoriesList && categoriesList.children.length > 0) {
        var sortable = Sortable.create(categoriesList, {
            handle: '.drag-handle',
            animation: 150,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            dragClass: 'sortable-drag',
            onEnd: function(evt) {
                // Sıralama değiştiğinde
                updateCategoryOrder();
            }
        });
    }

    // Kategori sırasını güncelle
    function updateCategoryOrder() {
        var categoryItems = $('.category-item');
        var orderData = [];
        
        categoryItems.each(function(index) {
            var categoryId = $(this).data('id');
            var newOrder = index + 1;
            orderData.push({
                id: categoryId,
                order: newOrder
            });
            
            // Görsel olarak sıra numarasını güncelle
            $(this).find('small:contains("Sıra:")').text('(Sıra: ' + newOrder + ')');
        });
        
        // AJAX ile sıralamayı kaydet
        $.ajax({
            url: '/admin/archive-document-categories/update-order',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                categories: orderData
            },
            success: function(response) {
                if (response.success) {
                    toastr.success('Kategori sıralaması güncellendi.');
                }
            },
            error: function(xhr) {
                toastr.error('Sıralama güncellenirken bir hata oluştu.');
                // Hata durumunda sayfayı yenile
                setTimeout(function() {
                    location.reload();
                }, 1000);
            }
        });
    }
    
    // Kategori ekleme butonu
    $('#add-category-btn').on('click', function() {
        $('#categoryModalLabel').text('Kategori Ekle');
        $('#categoryForm')[0].reset();
        $('#category_id').val('');
        $('#category_color').val('#3498db');
        $('#categoryModal').modal('show');
    });

    // Kategori düzenleme
    $(document).on('click', '.edit-category', function() {
        var categoryId = $(this).data('id');
        
        $.get('/admin/archive-document-categories/' + categoryId, function(category) {
            $('#categoryModalLabel').text('Kategori Düzenle');
            $('#category_id').val(category.id);
            $('#category_name').val(category.name);
            $('#category_icon').val(category.icon);
            $('#category_color').val(category.color);
            $('#category_description').val(category.description);
            $('#category_order').val(category.order);
            $('#categoryModal').modal('show');
        });
    });

    // Kategori kaydetme
    $('#categoryForm').on('submit', function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize();
        var categoryId = $('#category_id').val();
        var url = categoryId ? '/admin/archive-document-categories/' + categoryId : '/admin/archive-document-categories';
        var method = categoryId ? 'PUT' : 'POST';
        
        // Archive ID'yi ekle
        formData += '&archive_id={{ $archive->id }}';
        
        if (categoryId) {
            formData += '&_method=PUT';
        }
        
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    $('#categoryModal').modal('hide');
                    toastr.success(response.message);
                    
                    // Kategori listesini güncelle
                    updateCategoryList();
                    
                    // Select option'larını güncelle
                    updateCategorySelects();
                }
            },
            error: function(xhr) {
                var errors = xhr.responseJSON.errors;
                if (errors) {
                    var errorMessage = '';
                    Object.keys(errors).forEach(function(key) {
                        errorMessage += errors[key][0] + '\n';
                    });
                    toastr.error(errorMessage);
                } else {
                    toastr.error('Kategori kaydedilirken bir hata oluştu.');
                }
            }
        });
    });

    // Kategori silme
    $(document).on('click', '.delete-category', function() {
        var categoryId = $(this).data('id');
        var categoryName = $(this).closest('.category-item').find('.category-name').text();
        
        if (!confirm('Bu kategoriyi silmek istediğinizden emin misiniz?\n\nKategori: ' + categoryName)) {
            return;
        }
        
        $.ajax({
            url: '/admin/archive-document-categories/' + categoryId,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    
                    // Kategori listesini güncelle
                    updateCategoryList();
                    
                    // Select option'larını güncelle
                    updateCategorySelects();
                }
            },
            error: function(xhr) {
                var errorMessage = 'Kategori silinirken bir hata oluştu.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                toastr.error(errorMessage);
            }
        });
    });

    // Kategori listesini güncelleme
    function updateCategoryList() {
        $.get('/admin/archives/{{ $archive->id }}/categories', function(categories) {
            var html = '';
            
            if (categories.length > 0) {
                categories.forEach(function(category) {
                    html += `
                        <div class="category-item border rounded p-2 mb-2" data-id="${category.id}" data-order="${category.order}">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-grip-vertical text-muted mr-2 drag-handle" style="cursor: move;" title="Sürükle"></i>
                                    ${category.icon ? `<i class="${category.icon} mr-2" style="color: ${category.color}"></i>` : ''}
                                    <span class="category-name">${category.name}</span>
                                    <span class="badge badge-info ml-2">${category.documents_count || 0}</span>
                                    <small class="text-muted ml-2">(Sıra: ${category.order})</small>
                                </div>
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-outline-primary edit-category" 
                                            data-id="${category.id}" title="Düzenle">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-danger delete-category" 
                                            data-id="${category.id}" title="Sil">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                });
            } else {
                html = `
                    <div class="text-center text-muted py-3" id="no-categories">
                        <i class="fas fa-folder-open fa-2x mb-2"></i>
                        <p>Henüz kategori oluşturulmamış.</p>
                    </div>
                `;
            }
            
            $('#categories-list').html(html);
            
            // Sortable'ı yeniden başlat
            var categoriesList = document.getElementById('categories-list');
            if (categoriesList && categoriesList.children.length > 0) {
                Sortable.create(categoriesList, {
                    handle: '.drag-handle',
                    animation: 150,
                    ghostClass: 'sortable-ghost',
                    chosenClass: 'sortable-chosen',
                    dragClass: 'sortable-drag',
                    onEnd: function(evt) {
                        updateCategoryOrder();
                    }
                });
            }
        });
    }

    // Select option'larını güncelleme
    function updateCategorySelects() {
        $.get('/admin/archives/{{ $archive->id }}/categories', function(categories) {
            var options = '<option value="">Kategorisiz</option>';
            
            categories.forEach(function(category) {
                options += `<option value="${category.id}">${category.name}</option>`;
            });
            
            $('#bulk_category_id, #document_category_id').html(options);
        });
    }
});
</script>
@stop 