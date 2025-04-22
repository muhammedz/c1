@extends('adminlte::page')

@section('title', 'Hizmet: ' . $service->title)

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Hizmet: {{ $service->title }}</h1>
        <div>
            <a href="{{ route('admin.services.edit', $service->id) }}" class="btn btn-primary mr-1">
                <i class="fas fa-edit"></i> Düzenle
            </a>
            <a href="{{ route('admin.services.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Hizmet Listesine Dön
            </a>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Hizmet Detayları</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <h4>{{ $service->title }}</h4>
                        <div class="text-muted">
                            <small>
                                <i class="fas fa-link mr-1"></i>{{ $service->slug }} 
                                @if($service->status == 'published')
                                    <span class="badge badge-success ml-2"><i class="fas fa-eye mr-1"></i>Yayında</span>
                                @else
                                    <span class="badge badge-secondary ml-2"><i class="fas fa-eye-slash mr-1"></i>Taslak</span>
                                @endif

                                @if($service->is_featured)
                                    <span class="badge badge-info ml-1"><i class="fas fa-star mr-1"></i>Öne Çıkan</span>
                                @endif

                                @if($service->is_headline)
                                    <span class="badge badge-warning ml-1"><i class="fas fa-bolt mr-1"></i>Manşet</span>
                                @endif
                            </small>
                        </div>
                    </div>

                    @if($service->summary)
                        <div class="form-group">
                            <label>Özet</label>
                            <div class="p-2 bg-light rounded">{{ $service->summary }}</div>
                        </div>
                    @endif

                    <div class="form-group">
                        <label>İçerik</label>
                        <div class="p-2 bg-light rounded content-preview">
                            {!! $service->content !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Bilgiler</h3>
                </div>
                <div class="card-body">
                    <div class="info-box bg-light">
                        <div class="info-box-content">
                            <span class="info-box-text text-muted">Oluşturulma Tarihi</span>
                            <span class="info-box-number text-muted">{{ $service->created_at->format('d.m.Y H:i') }}</span>
                        </div>
                    </div>
                    
                    <div class="info-box bg-light">
                        <div class="info-box-content">
                            <span class="info-box-text text-muted">Son Güncelleme</span>
                            <span class="info-box-number text-muted">{{ $service->updated_at->format('d.m.Y H:i') }}</span>
                        </div>
                    </div>

                    @if($service->published_at)
                        <div class="info-box bg-light">
                            <div class="info-box-content">
                                <span class="info-box-text text-muted">Yayınlanma Tarihi</span>
                                <span class="info-box-number text-muted">{{ $service->published_at->format('d.m.Y H:i') }}</span>
                            </div>
                        </div>
                    @endif

                    <div class="info-box bg-light">
                        <div class="info-box-content">
                            <span class="info-box-text text-muted">Görüntülenme</span>
                            <span class="info-box-number text-muted">{{ $service->view_count ?? 0 }}</span>
                        </div>
                    </div>

                    @if($service->categories && $service->categories->count() > 0)
                        <div class="form-group mt-3">
                            <label>Kategoriler</label>
                            <div>
                                @foreach($service->categories as $category)
                                    <span class="badge badge-primary mr-1">{{ $category->name }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($service->tags && $service->tags->count() > 0)
                        <div class="form-group">
                            <label>Etiketler</label>
                            <div>
                                @foreach($service->tags as $tag)
                                    <span class="badge badge-secondary mr-1">{{ $tag->name }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($service->meta_title || $service->meta_description || $service->meta_keywords)
                        <div class="form-group">
                            <label>SEO Bilgileri</label>
                            <div class="card card-outline card-secondary">
                                <div class="card-body p-2">
                                    @if($service->meta_title)
                                        <div class="mb-1">
                                            <small class="text-muted">Meta Başlık:</small>
                                            <div>{{ $service->meta_title }}</div>
                                        </div>
                                    @endif
                                    
                                    @if($service->meta_description)
                                        <div class="mb-1">
                                            <small class="text-muted">Meta Açıklama:</small>
                                            <div>{{ $service->meta_description }}</div>
                                        </div>
                                    @endif
                                    
                                    @if($service->meta_keywords)
                                        <div>
                                            <small class="text-muted">Meta Anahtar Kelimeler:</small>
                                            <div>{{ $service->meta_keywords }}</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            @if($service->image)
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Ana Görsel</h3>
                    </div>
                    <div class="card-body">
                        <img src="{{ asset(str_replace('/storage/', '', $service->image)) }}" alt="{{ $service->title }}" class="img-fluid">
                    </div>
                </div>
            @endif

            @if($service->gallery && count($service->gallery) > 0)
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Galeri</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($service->gallery as $galleryItem)
                                <div class="col-md-6 mb-2">
                                    <img src="{{ asset(str_replace('/storage/', '', $galleryItem)) }}" class="img-fluid img-thumbnail">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">İşlemler</h3>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.services.edit', $service->id) }}" class="btn btn-primary btn-block">
                        <i class="fas fa-edit mr-1"></i> Düzenle
                    </a>
                    
                    <button type="button" class="btn btn-danger btn-block mt-2" data-toggle="modal" data-target="#deleteModal">
                        <i class="fas fa-trash mr-1"></i> Sil
                    </button>
                    
                    @if($service->status == 'published')
                        <a href="{{ route('services.show', $service->slug) }}" target="_blank" class="btn btn-success btn-block mt-2">
                            <i class="fas fa-external-link-alt mr-1"></i> Site'de Görüntüle
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Hizmeti Sil</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>"{{ $service->title }}" adlı hizmeti silmek istediğinize emin misiniz?</p>
                <p class="text-danger">Bu işlem geri alınamaz!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">İptal</button>
                <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Evet, Sil</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .content-preview {
        max-height: 600px;
        overflow-y: auto;
    }
    .content-preview img {
        max-width: 100%;
        height: auto;
    }
</style>
@stop 