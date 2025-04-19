@extends('adminlte::page')

@section('title', 'Dosya Yönetim Sistemi - Dosya Yükle')

@section('content_header')
    <h1>Dosya Yükle</h1>
@stop

@section('content')
<div class="container-fluid">
    <!-- Debug Bilgileri -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-light">
                <div class="card-header">
                    <h5 class="card-title mb-0">PHP Yükleme Limitleri (Debug)</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th>Ayar</th>
                                    <th>Değer</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>PHP Sürümü</td>
                                    <td>{{ $phpUploadLimits['php_version'] }}</td>
                                </tr>
                                <tr>
                                    <td>post_max_size</td>
                                    <td>{{ $phpUploadLimits['post_max_size'] }}</td>
                                </tr>
                                <tr>
                                    <td>upload_max_filesize</td>
                                    <td>{{ $phpUploadLimits['upload_max_filesize'] }}</td>
                                </tr>
                                <tr>
                                    <td>memory_limit</td>
                                    <td>{{ $phpUploadLimits['memory_limit'] }}</td>
                                </tr>
                                <tr>
                                    <td>max_file_uploads</td>
                                    <td>{{ $phpUploadLimits['max_file_uploads'] }}</td>
                                </tr>
                                <tr>
                                    <td>max_execution_time</td>
                                    <td>{{ $phpUploadLimits['max_execution_time'] }} saniye</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-md-8">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.filemanagersystem.dashboard') }}">Ana Klasör</a></li>
                    
                    @if($folder)
                        @php
                        $breadcrumbs = [];
                        $parent = $folder;
                        
                        while($parent) {
                            $breadcrumbs[] = $parent;
                            $parent = $parent->parent;
                        }
                        
                        $breadcrumbs = array_reverse($breadcrumbs);
                        @endphp
                        
                        @foreach($breadcrumbs as $breadcrumb)
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.filemanagersystem.folders.index', ['parent_id' => $breadcrumb->id]) }}">
                                    {{ $breadcrumb->folder_name }}
                                </a>
                            </li>
                        @endforeach
                    @endif
                    
                    <li class="breadcrumb-item active" aria-current="page">Dosya Yükle</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ $folder ? route('admin.filemanagersystem.media.index', ['folder_id' => $folder->id]) : route('admin.filemanagersystem.media.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Geri Dön
            </a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-header">Hızlı Erişim</div>
                <div class="card-body">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.filemanagersystem.folders.index') }}">
                                <i class="fas fa-folder"></i> Ana Klasör
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.filemanagersystem.media.index') }}">
                                <i class="fas fa-file"></i> Tüm Dosyalar
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Resim Sıkıştırma Ayarları Kartı -->
            <div class="card mb-4" id="compressionSettingsCard">
                <div class="card-header">Resim Sıkıştırma Ayarları</div>
                <div class="card-body">
                    <p class="card-text small text-muted mb-3">
                        Bu ayarlar sadece yüklenen resim dosyaları için uygulanacaktır. Diğer dosya türleri etkilenmez.
                    </p>
                    
                    <div class="mb-3">
                        <label for="compression_quality" class="form-label">Sıkıştırma Kalitesi</label>
                        <select class="form-select" id="compression_quality" name="compression_quality" form="upload-form">
                            <option value="none">Sıkıştırma Yapma</option>
                            <option value="low">Düşük (Daha Küçük Dosya)</option>
                            <option value="medium" selected>Orta</option>
                            <option value="high">Yüksek (Daha İyi Kalite)</option>
                        </select>
                        <div class="form-text">Resim kalitesini ve sıkıştırma oranını belirler.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="max_size" class="form-label">Maksimum Boyut</label>
                        <select class="form-select" id="max_size" name="max_size" form="upload-form">
                            <option value="small">Küçük (1280x720)</option>
                            <option value="medium" selected>Orta (1920x1080)</option>
                            <option value="large">Büyük (2560x1440)</option>
                            <option value="original">Orijinal (Boyutu Koru)</option>
                        </select>
                        <div class="form-text">Bu boyuttan daha büyük resimler otomatik olarak küçültülecektir.</div>
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="keep_original" name="keep_original" value="1" checked form="upload-form">
                        <label class="form-check-label" for="keep_original">Orijinal formatı da koru</label>
                        <div class="form-text">Devre dışı bırakırsanız, sadece WebP formatı kaydedilir.</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">Dosya Yükleme</div>
                
                <div class="card-body">
                    <form action="{{ route('admin.filemanagersystem.media.store') }}" method="POST" enctype="multipart/form-data" id="upload-form">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="files" class="form-label">Dosyalar <span class="text-danger">*</span></label>
                            <input type="file" class="form-control @error('files') is-invalid @enderror" id="files" name="files[]" multiple required>
                            <div class="form-text">Birden fazla dosya seçebilirsiniz. Maksimum dosya boyutu: 50MB</div>
                            @error('files')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="folder_id" class="form-label">Klasör</label>
                            <select class="form-select @error('folder_id') is-invalid @enderror" id="folder_id" name="folder_id">
                                <option value="">Ana Klasör</option>
                                @foreach($folders as $folderItem)
                                    <option value="{{ $folderItem->id }}" {{ (old('folder_id') ?? ($folder ? $folder->id : null)) == $folderItem->id ? 'selected' : '' }}>
                                        {{ $folderItem->folder_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('folder_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_public" name="is_public" value="1" {{ old('is_public') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_public">Herkese Açık</label>
                            <div class="form-text">Herkese açık dosyalar kimlik doğrulama olmadan da erişilebilir.</div>
                        </div>
                        
                        <input type="hidden" name="folder_id" value="{{ $folder->id ?? old('folder_id') }}">
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload"></i> Yükle
                            </button>
                        </div>
                        
                        @if(isset($folder))
                            <a href="{{ route('admin.filemanagersystem.folders.show', $folder->id) }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> İptal
                            </a>
                        @else
                            <a href="{{ route('admin.filemanagersystem.media.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> İptal
                            </a>
                        @endif
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
        // Dosya seçildiğinde resim türü kontrolü yaparak ayarları göster/gizle
        $('#files').on('change', function() {
            const files = this.files;
            let hasImage = false;
            
            for (let i = 0; i < files.length; i++) {
                if (files[i].type.startsWith('image/')) {
                    hasImage = true;
                    break;
                }
            }
            
            // Resim dosyası varsa ayarları göster, yoksa gizle
            if (hasImage) {
                $('#compressionSettingsCard').show();
            } else {
                $('#compressionSettingsCard').hide();
            }
        });
        
        // Sayfa yüklendiğinde başlangıçta gizle
        $('#compressionSettingsCard').hide();
    });
</script>
@stop 