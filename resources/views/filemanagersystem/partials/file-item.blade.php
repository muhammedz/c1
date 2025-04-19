@php
    $isSelected = isset($selectedFiles) && in_array($file->id, $selectedFiles);
    $fileIcon = match($file->mime_type) {
        'image/jpeg', 'image/png', 'image/gif' => 'fa-image',
        'application/pdf' => 'fa-file-pdf',
        'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'fa-file-word',
        'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'fa-file-excel',
        'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'fa-file-powerpoint',
        default => 'fa-file'
    };
@endphp

<div class="file-item col-md-3 col-sm-6 mb-3 {{ $isSelected ? 'selected' : '' }}" data-file-id="{{ $file->id }}">
    <div class="card h-100">
        <div class="card-body text-center">
            <i class="fas {{ $fileIcon }} fa-3x mb-2"></i>
            <h6 class="card-title text-truncate" title="{{ $file->name }}">{{ $file->name }}</h6>
            <p class="card-text small text-muted">
                {{ $file->getHumanReadableSizeAttribute() }}
            </p>
            <div class="btn-group">
                <button type="button" class="btn btn-sm btn-info" onclick="previewFile({{ $file->id }})">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
        </div>
    </div>
</div> 