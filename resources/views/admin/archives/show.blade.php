@extends('adminlte::page')

@section('title', 'Arşiv Detayı')

@section('content_header')
    <h1>Arşiv Detayı</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $archive->title }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.archives.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Geri Dön
                        </a>
                        <a href="{{ route('admin.archives.edit', $archive) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> Düzenle
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    @if($archive->excerpt)
                        <div class="alert alert-info">
                            <strong>Özet:</strong> {{ $archive->excerpt }}
                        </div>
                    @endif

                    @if($archive->content)
                        <div class="content">
                            {!! $archive->content !!}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Arşiv Bilgileri -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Arşiv Bilgileri</h3>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Durum:</strong></td>
                            <td>
                                @if($archive->status == 'published')
                                    <span class="badge badge-success">{{ $archive->status_text }}</span>
                                @elseif($archive->status == 'draft')
                                    <span class="badge badge-warning">{{ $archive->status_text }}</span>
                                @else
                                    <span class="badge badge-secondary">{{ $archive->status_text }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Öne Çıkan:</strong></td>
                            <td>
                                @if($archive->is_featured)
                                    <span class="badge badge-primary">Evet</span>
                                @else
                                    <span class="badge badge-light">Hayır</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Oluşturan:</strong></td>
                            <td>{{ $archive->user->name ?? 'Bilinmiyor' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Oluşturma Tarihi:</strong></td>
                            <td>{{ $archive->created_at->format('d.m.Y H:i') }}</td>
                        </tr>
                        @if($archive->published_at)
                        <tr>
                            <td><strong>Yayın Tarihi:</strong></td>
                            <td>{{ $archive->published_at->format('d.m.Y H:i') }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td><strong>Görüntülenme:</strong></td>
                            <td>{{ $archive->view_count ?? 0 }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Belgeler -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Belgeler ({{ $archive->documents->count() }})</h3>
                </div>
                <div class="card-body">
                    @forelse($archive->documents as $document)
                        <div class="border rounded p-2 mb-2">
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
                            <div class="mt-2">
                                <a href="{{ $document->download_url }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                    <i class="fas fa-download"></i> İndir
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-folder-open fa-2x mb-2"></i>
                            <p>Henüz belge bulunmamaktadır.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@stop 