@extends('adminlte::page')

@section('title', 'Dosya Detayları: ' . $media->name)

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <a href="{{ route('admin.filemanagersystem.media.index') }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left"></i> Medya Listesine Dön
            </a>
            
            @if($media->folder)
            <a href="{{ route('admin.filemanagersystem.folders.show', $media->folder->id) }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-folder"></i> {{ $media->folder->folder_name }}
            </a>
            @endif
            
            <div class="float-right">
                <div class="btn-group mr-2" role="group">
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="copyToClipboard('quick_copy_url')" title="URL'yi Kopyala">
                        <i class="fas fa-copy"></i>
                    </button>
                    <a href="{{ $media->url }}" target="_blank" class="btn btn-outline-success btn-sm" title="Yeni Sekmede Aç">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                    <a href="{{ route('admin.filemanagersystem.media.download', $media) }}" class="btn btn-outline-info btn-sm" title="İndir">
                        <i class="fas fa-download"></i>
                    </a>
                </div>
                
                <a href="{{ route('admin.filemanagersystem.media.edit', ['media' => $media->id]) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-edit"></i> Düzenle
                </a>
                <form action="{{ route('admin.filemanagersystem.media.destroy', ['media' => $media->id]) }}" method="POST" class="d-inline" id="delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bu dosyayı silmek istediğinizden emin misiniz?')">
                        <i class="fas fa-trash"></i> Sil
                    </button>
                </form>
                
                <!-- Gizli input hızlı kopyalama için -->
                <input type="hidden" id="quick_copy_url" value="{{ $media->url }}">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Dosya Detayları</h3>
                    <div class="card-tools">
                        @if($media->folder)
                            <a href="{{ route('admin.filemanagersystem.folders.show', $media->folder->id) }}" class="btn btn-sm btn-secondary">
                                <i class="fas fa-arrow-left mr-1"></i> Klasöre Dön
                            </a>
                        @else
                            <a href="{{ route('admin.filemanagersystem.media.index') }}" class="btn btn-sm btn-secondary">
                                <i class="fas fa-arrow-left mr-1"></i> Dosya Listesine Dön
                            </a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.filemanagersystem.folders.index') }}">Ana Klasör</a></li>
                                @if($media->folder)
                                    @if($media->folder->parent)
                                        <li class="breadcrumb-item"><a href="{{ route('admin.filemanagersystem.folders.show', $media->folder->parent->id) }}">{{ $media->folder->parent->folder_name }}</a></li>
                                    @endif
                                    <li class="breadcrumb-item"><a href="{{ route('admin.filemanagersystem.folders.show', $media->folder->id) }}">{{ $media->folder->folder_name }}</a></li>
                                @endif
                                <li class="breadcrumb-item active">{{ $media->original_name }}</li>
                            </ol>
                        </nav>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fas fa-check"></i> Başarılı!</h5>
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Dosya Önizleme</h5>
                                </div>
                                <div class="card-body text-center">
                                    @if(Str::startsWith($media->mime_type, 'image/'))
                                        @if($media->has_webp)
                                            <picture>
                                                <source srcset="{{ $media->webp_url }}" type="image/webp">
                                                <source srcset="{{ $media->url }}" type="{{ $media->mime_type }}">
                                                <img src="{{ $media->url }}" alt="{{ $media->original_name }}" class="img-fluid mb-3 border">
                                            </picture>
                                        @else
                                            <img src="{{ $media->url }}" alt="{{ $media->original_name }}" class="img-fluid mb-3 border">
                                        @endif
                                    @elseif(Str::startsWith($media->mime_type, 'video/'))
                                        <div class="embed-responsive embed-responsive-16by9 mb-3">
                                            <video class="embed-responsive-item" controls>
                                                <source src="{{ $media->url }}" type="{{ $media->mime_type }}">
                                                Tarayıcınız video oynatmayı desteklemiyor.
                                            </video>
                                        </div>
                                    @elseif($media->mime_type == 'application/pdf')
                                        <div class="embed-responsive embed-responsive-16by9 mb-3">
                                            <embed class="embed-responsive-item" src="{{ $media->url }}" type="application/pdf">
                                        </div>
                                    @else
                                        <div class="py-5">
                                            <i class="{{ $media->getIconClass() }} fa-5x text-secondary mb-3"></i>
                                            <p class="mt-3">Bu dosya türü önizleme için desteklenmiyor.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Dosya Bilgileri</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <th style="width: 140px;">Dosya Adı:</th>
                                                <td>{{ $media->original_name }}</td>
                                            </tr>
                                            <tr>
                                                <th>Sistem Adı:</th>
                                                <td>{{ $media->name }}</td>
                                            </tr>
                                            <tr>
                                                <th>Dosya Tipi:</th>
                                                <td>{{ $media->mime_type }}</td>
                                            </tr>
                                            <tr>
                                                <th>Uzantı:</th>
                                                <td>.{{ $media->extension }}</td>
                                            </tr>
                                            <tr>
                                                <th>Dosya Boyutu:</th>
                                                <td>{{ $media->getFormattedSize() }}</td>
                                            </tr>
                                            @if($media->isCompressed())
                                            <tr>
                                                <th>Orijinal Boyut:</th>
                                                <td>{{ $media->getFormattedOriginalSizeAttribute() }}</td>
                                            </tr>
                                            <tr>
                                                <th>Sıkıştırma:</th>
                                                <td>
                                                    <span class="badge badge-info">{{ number_format($media->getSavingsPercentageAttribute(), 1) }}% Tasarruf</span>
                                                    @if($media->compression_quality)
                                                    <span class="badge badge-secondary">Kalite: {{ $media->compression_quality }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endif
                                            @if($media->has_webp)
                                            <tr>
                                                <th>WebP:</th>
                                                <td>
                                                    <span class="badge badge-success">WebP Mevcut</span>
                                                    <a href="{{ $media->webp_url }}" target="_blank" class="btn btn-xs btn-outline-secondary">
                                                        <i class="fas fa-external-link-alt"></i>
                                                    </a>
                                                    @php
                                                        $webpFilePath = public_path('uploads/' . $media->webp_path);
                                                    @endphp
                                                    @if(file_exists($webpFilePath))
                                                    <br><small class="text-muted">Boyut: {{ round(filesize($webpFilePath) / 1024, 2) }} KB</small>
                                                    @else
                                                    <br><small class="text-muted text-warning">WebP dosya bulunamadı</small>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endif
                                            @if($media->width && $media->height)
                                            <tr>
                                                <th>Boyutlar:</th>
                                                <td>{{ $media->width }} × {{ $media->height }} piksel</td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <th>Klasör:</th>
                                                <td>
                                                    @if($media->folder)
                                                        <a href="{{ route('admin.filemanagersystem.folders.show', $media->folder->id) }}">
                                                            {{ $media->folder->folder_name }}
                                                        </a>
                                                    @else
                                                        <span class="text-muted">Ana Klasör</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Erişim:</th>
                                                <td>
                                                    @if($media->is_public)
                                                        <span class="badge badge-success">Herkese Açık</span>
                                                    @else
                                                        <span class="badge badge-secondary">Sadece Yetkililer</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Yükleyen:</th>
                                                <td>{{ $media->user ? $media->user->name : 'Bilinmiyor' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Yükleme Tarihi:</th>
                                                <td>{{ $media->created_at->format('d.m.Y H:i') }}</td>
                                            </tr>
                                            @if($media->created_at != $media->updated_at)
                                            <tr>
                                                <th>Son Güncelleme:</th>
                                                <td>{{ $media->updated_at->format('d.m.Y H:i') }}</td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <th>Dosya Linki:</th>
                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" id="file_link" class="form-control" value="{{ $media->url }}" readonly>
                                                        <div class="input-group-append">
                                                            <button class="btn btn-outline-primary btn-sm" type="button" onclick="copyToClipboard('file_link')" title="Linki Kopyala">
                                                                <i class="fas fa-copy"></i>
                                                            </button>
                                                            <a href="{{ $media->url }}" target="_blank" class="btn btn-outline-success btn-sm" title="Yeni Sekmede Aç">
                                                                <i class="fas fa-external-link-alt"></i>
                                                            </a>
                                                            <a href="{{ route('admin.filemanagersystem.media.download', $media) }}" class="btn btn-outline-info btn-sm" title="İndir">
                                                                <i class="fas fa-download"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <small class="form-text text-muted">
                                                        <i class="fas fa-info-circle"></i> 
                                                        Bu dosyaya doğrudan erişim linki
                                                        @if(!$media->is_public)
                                                            <span class="text-warning">(Sadece yetkililer erişebilir)</span>
                                                        @endif
                                                    </small>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    
                                    @if($media->is_public)
                                    <div class="mt-4">
                                        <label for="public_url">Genel URL:</label>
                                        <div class="input-group">
                                            <input type="text" id="public_url" class="form-control" value="{{ $media->url }}" readonly>
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('public_url')">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <small class="form-text text-muted">Bu URL ile dosya kimlik doğrulama olmadan erişilebilir.</small>
                                    </div>
                                    @endif
                                    
                                    @if($media->has_webp && $media->is_public)
                                    <div class="mt-2">
                                        <label for="webp_url">WebP URL:</label>
                                        <div class="input-group">
                                            <input type="text" id="webp_url" class="form-control" value="{{ $media->webp_url }}" readonly>
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('webp_url')">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <small class="form-text text-muted">WebP formatı daha küçük boyutlu ve modern tarayıcılarda çalışır.</small>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function copyToClipboard(elementId) {
    var copyText = document.getElementById(elementId);
    
    // Modern clipboard API kullanmayı dene
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(copyText.value).then(function() {
            showCopyNotification("URL başarıyla kopyalandı!");
        }).catch(function() {
            // Fallback yöntemi
            fallbackCopyToClipboard(copyText);
        });
    } else {
        // Fallback yöntemi
        fallbackCopyToClipboard(copyText);
    }
}

function fallbackCopyToClipboard(copyText) {
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    
    try {
        var successful = document.execCommand("copy");
        if (successful) {
            showCopyNotification("URL başarıyla kopyalandı!");
        } else {
            showCopyNotification("Kopyalama başarısız!", "error");
        }
    } catch (err) {
        showCopyNotification("Kopyalama desteklenmiyor!", "error");
    }
}

function showCopyNotification(message, type = "success") {
    // Mevcut bildirimi kaldır
    var existingTooltip = document.getElementById("copy-tooltip");
    if (existingTooltip) {
        document.body.removeChild(existingTooltip);
    }
    
    // Yeni bildirim oluştur
    var tooltip = document.createElement("div");
    tooltip.id = "copy-tooltip";
    tooltip.innerHTML = '<i class="fas fa-' + (type === "success" ? "check" : "exclamation-triangle") + '"></i> ' + message;
    tooltip.style.position = "fixed";
    tooltip.style.left = "50%";
    tooltip.style.top = "20px";
    tooltip.style.transform = "translateX(-50%)";
    tooltip.style.padding = "12px 20px";
    tooltip.style.background = type === "success" ? "#28a745" : "#dc3545";
    tooltip.style.color = "white";
    tooltip.style.borderRadius = "6px";
    tooltip.style.zIndex = "9999";
    tooltip.style.fontSize = "14px";
    tooltip.style.fontWeight = "500";
    tooltip.style.boxShadow = "0 4px 12px rgba(0,0,0,0.15)";
    tooltip.style.animation = "slideDown 0.3s ease-out";
    
    // CSS animasyonu ekle
    if (!document.getElementById("copy-notification-styles")) {
        var style = document.createElement("style");
        style.id = "copy-notification-styles";
        style.textContent = `
            @keyframes slideDown {
                from { opacity: 0; transform: translateX(-50%) translateY(-20px); }
                to { opacity: 1; transform: translateX(-50%) translateY(0); }
            }
            @keyframes slideUp {
                from { opacity: 1; transform: translateX(-50%) translateY(0); }
                to { opacity: 0; transform: translateX(-50%) translateY(-20px); }
            }
        `;
        document.head.appendChild(style);
    }
    
    document.body.appendChild(tooltip);
    
    // 3 saniye sonra kaldır
    setTimeout(function() {
        if (tooltip && tooltip.parentNode) {
            tooltip.style.animation = "slideUp 0.3s ease-out";
            setTimeout(function() {
                if (tooltip && tooltip.parentNode) {
                    document.body.removeChild(tooltip);
                }
            }, 300);
        }
    }, 3000);
}

// Sayfa yüklendiğinde URL'yi otomatik seç
document.addEventListener('DOMContentLoaded', function() {
    // Dosya linkine tıklandığında otomatik seç
    var fileLinkInput = document.getElementById('file_link');
    if (fileLinkInput) {
        fileLinkInput.addEventListener('click', function() {
            this.select();
        });
    }
    
    // Diğer URL inputları için de aynı işlemi yap
    var urlInputs = ['public_url', 'webp_url'];
    urlInputs.forEach(function(inputId) {
        var input = document.getElementById(inputId);
        if (input) {
            input.addEventListener('click', function() {
                this.select();
            });
        }
    });
});
</script>
@endpush
@endsection 