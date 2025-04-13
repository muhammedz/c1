@extends('adminlte::page')

@section('title', 'Sayfa Detayı')

@section('styles')
<style>
    .content-wrapper {
        background-color: #f4f6f9;
    }
    
    .page-detail-card {
        border: none;
        box-shadow: 0 0 20px rgba(0,0,0,.08);
    }
    
    .page-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .page-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1rem;
        color: #6c757d;
    }
    
    .page-meta-item {
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }
    
    .page-content {
        margin-top: 1.5rem;
        line-height: 1.6;
    }
    
    .page-image {
        width: 100%;
        height: auto;
        max-height: 400px;
        object-fit: cover;
        border-radius: 0.5rem;
        margin-bottom: 1.5rem;
    }
    
    .gallery-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 15px;
        margin-top: 1rem;
    }
    
    .gallery-item {
        position: relative;
        aspect-ratio: 1;
        border-radius: 8px;
        overflow: hidden;
    }
    
    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .info-box {
        background-color: #fff;
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 1rem;
        box-shadow: 0 0 20px rgba(0,0,0,.05);
    }
    
    .info-box-title {
        font-weight: 600;
        margin-bottom: 0.5rem;
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .category-badge, .tag-badge {
        display: inline-block;
        margin-right: 0.3rem;
        margin-bottom: 0.3rem;
        padding: 0.3rem 0.6rem;
        border-radius: 0.3rem;
        font-size: 0.8rem;
    }
    
    .category-badge {
        background-color: #e3f2fd;
        color: #0d6efd;
    }
    
    .tag-badge {
        background-color: #f8f9fa;
        color: #495057;
        border: 1px solid #dee2e6;
    }
</style>
@stop

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2">Sayfa Detayı</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.pages.index') }}" class="text-decoration-none">Sayfalar</a></li>
                    <li class="breadcrumb-item active">Detay</li>
                </ol>
            </nav>
        </div>
        
        <div>
            <a href="{{ route('admin.pages.edit', $page->id) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Düzenle
            </a>
            <a href="{{ route('admin.pages.index') }}" class="btn btn-outline-secondary ml-2">
                <i class="fas fa-arrow-left"></i> Geri Dön
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card page-detail-card mb-4">
                <div class="card-body">
                    <h2 class="page-title">{{ $page->title }}</h2>
                    
                    <div class="page-meta">
                        <div class="page-meta-item">
                            <i class="far fa-calendar-alt"></i>
                            <span>{{ $page->created_at->format('d.m.Y H:i') }}</span>
                        </div>
                        
                        <div class="page-meta-item">
                            <i class="fas fa-eye"></i>
                            <span>{{ $page->view_count ?? 0 }} görüntülenme</span>
                        </div>
                        
                        <div class="page-meta-item">
                            <i class="fas fa-tag"></i>
                            <span>
                                @if($page->categories->count() > 0)
                                    {{ $page->categories->pluck('name')->join(', ') }}
                                @else
                                    Kategori yok
                                @endif
                            </span>
                        </div>
                    </div>
                    
                    @if($page->image)
                        <img src="{{ asset($page->image) }}" alt="{{ $page->title }}" class="page-image">
                    @endif
                    
                    @if($page->summary)
                        <div class="page-summary">
                            <h6 class="font-weight-bold">Özet:</h6>
                            <p>{{ $page->summary }}</p>
                        </div>
                    @endif
                    
                    <div class="page-content">
                        {!! $page->content !!}
                    </div>
                </div>
            </div>
            
            @if(!empty($page->gallery) && count($page->gallery) > 0)
                <div class="card page-detail-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Galeri</h5>
                    </div>
                    <div class="card-body">
                        <div class="gallery-container">
                            @foreach($page->gallery as $image)
                                <div class="gallery-item">
                                    <img src="{{ asset($image) }}" alt="Galeri resmi">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
        
        <div class="col-lg-4">
            <div class="info-box">
                <div class="info-box-title">
                    <i class="fas fa-info-circle text-primary"></i>
                    Sayfa Bilgileri
                </div>
                
                <table class="table table-sm">
                    <tr>
                        <th style="width: 40%">Durum:</th>
                        <td>
                            @if($page->status == 'published')
                                <span class="badge bg-success"><i class="fas fa-check-circle"></i> Yayında</span>
                            @elseif($page->status == 'draft')
                                <span class="badge bg-warning"><i class="fas fa-edit"></i> Taslak</span>
                            @elseif($page->status == 'scheduled')
                                <span class="badge bg-info"><i class="fas fa-clock"></i> Zamanlanmış</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Oluşturuldu:</th>
                        <td>{{ $page->created_at->format('d.m.Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Son Güncelleme:</th>
                        <td>{{ $page->updated_at->format('d.m.Y H:i') }}</td>
                    </tr>
                    @if($page->published_at)
                    <tr>
                        <th>Yayınlanma:</th>
                        <td>{{ $page->published_at->format('d.m.Y H:i') }}</td>
                    </tr>
                    @endif
                    <tr>
                        <th>Görüntülenme:</th>
                        <td>{{ $page->view_count ?? 0 }}</td>
                    </tr>
                    <tr>
                        <th>Öne Çıkan:</th>
                        <td>
                            @if($page->is_featured)
                                <span class="badge bg-success"><i class="fas fa-check"></i> Evet</span>
                            @else
                                <span class="badge bg-secondary"><i class="fas fa-times"></i> Hayır</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
            
            @if($page->categories->count() > 0)
                <div class="info-box">
                    <div class="info-box-title">
                        <i class="fas fa-folder text-warning"></i>
                        Kategoriler
                    </div>
                    <div>
                        @foreach($page->categories as $category)
                            <span class="category-badge">{{ $category->name }}</span>
                        @endforeach
                    </div>
                </div>
            @endif
            
            @if(!empty($page->tags) && count($page->tags) > 0)
                <div class="info-box">
                    <div class="info-box-title">
                        <i class="fas fa-tags text-info"></i>
                        Etiketler
                    </div>
                    <div>
                        @foreach($page->tags as $tag)
                            <span class="tag-badge">{{ $tag }}</span>
                        @endforeach
                    </div>
                </div>
            @endif
            
            <div class="info-box">
                <div class="info-box-title">
                    <i class="fas fa-cog text-secondary"></i>
                    SEO Bilgileri
                </div>
                <table class="table table-sm">
                    <tr>
                        <th style="width: 40%">Meta Başlık:</th>
                        <td>{{ $page->meta_title ?: 'Ayarlanmamış' }}</td>
                    </tr>
                    <tr>
                        <th>Meta Açıklama:</th>
                        <td>{{ $page->meta_description ?: 'Ayarlanmamış' }}</td>
                    </tr>
                    <tr>
                        <th>Slug:</th>
                        <td>{{ $page->slug }}</td>
                    </tr>
                </table>
            </div>
            
            <div class="mt-4">
                <a href="{{ route('admin.pages.edit', $page->id) }}" class="btn btn-primary d-block mb-2">
                    <i class="fas fa-edit"></i> Sayfayı Düzenle
                </a>
                
                <button type="button" class="btn btn-outline-danger d-block w-100" 
                        data-bs-toggle="modal" data-bs-target="#deletePageModal">
                    <i class="fas fa-trash"></i> Sayfayı Sil
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Silme Modal -->
<div class="modal fade" id="deletePageModal" tabindex="-1" aria-labelledby="deletePageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deletePageModalLabel">Sayfayı Sil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bu sayfayı silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.</p>
                <p><strong>Sayfa:</strong> {{ $page->title }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                <form action="{{ route('admin.pages.destroy', $page->id) }}" method="POST" style="display: inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Evet, Sil</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Galeri resimleri için lightbox özelliği eklenebilir
        // ...
    });
</script>
@stop 