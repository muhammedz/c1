@extends('adminlte::page')

@section('title', $hedefKitle->name . ' - Hedef Kitle Detayı')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">Hedef Kitle: {{ $hedefKitle->name }}</h1>
            <p class="mb-0 text-muted">Hedef kitle detayları ve ilişkili içerikler</p>
        </div>
        <div>
            <a href="{{ route('admin.hedef-kitleler.edit', $hedefKitle) }}" class="btn btn-primary btn-sm">
                <i class="fas fa-edit mr-1"></i> Düzenle
            </a>
            <a href="{{ route('admin.hedef-kitleler.index') }}" class="btn btn-outline-secondary btn-sm ml-2">
                <i class="fas fa-arrow-left mr-1"></i> Listeye Dön
            </a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-4">
            <!-- Hedef Kitle Bilgileri -->
            <div class="card card-primary card-outline mb-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle mr-1"></i>
                        Hedef Kitle Bilgileri
                    </h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="font-weight-bold">Durum:</label>
                        <div>
                            <span class="badge badge-{{ $hedefKitle->is_active ? 'success' : 'danger' }} px-3 py-2">
                                <i class="fas fa-{{ $hedefKitle->is_active ? 'check' : 'times' }} mr-1"></i>
                                {{ $hedefKitle->is_active ? 'Aktif' : 'Pasif' }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="font-weight-bold">Hedef Kitle Adı:</label>
                        <p>{{ $hedefKitle->name }}</p>
                    </div>
                    
                    <div class="form-group">
                        <label class="font-weight-bold">Slug:</label>
                        <p><code>{{ $hedefKitle->slug }}</code></p>
                    </div>
                    
                    <div class="form-group">
                        <label class="font-weight-bold">Açıklama:</label>
                        <p>{{ $hedefKitle->description ?? 'Açıklama bulunmuyor.' }}</p>
                    </div>
                    
                    <div class="form-group">
                        <label class="font-weight-bold">Sıralama:</label>
                        <p>{{ $hedefKitle->order }}</p>
                    </div>
                    
                    <div class="form-group mb-0">
                        <label class="font-weight-bold">Oluşturulma Tarihi:</label>
                        <p>{{ $hedefKitle->created_at->format('d.m.Y H:i') }}</p>
                    </div>
                </div>
            </div>
            
            <!-- İstatistikler -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-pie mr-1"></i>
                        İstatistikler
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-newspaper text-primary mr-2"></i> İlişkili Haber Sayısı</span>
                            <span class="badge badge-primary badge-pill">{{ $hedefKitle->news()->count() }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-cogs text-info mr-2"></i> İlişkili Hizmet Sayısı</span>
                            <span class="badge badge-info badge-pill">{{ $hedefKitle->services()->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <!-- İlişkili Haberler -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class="fas fa-newspaper mr-1"></i>
                        İlişkili Haberler
                    </h3>
                </div>
                
                <div class="card-body">
                    @if($news->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Başlık</th>
                                        <th>Kategori</th>
                                        <th>Yayın Tarihi</th>
                                        <th>Durum</th>
                                        <th>İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($news as $item)
                                    <tr>
                                        <td>
                                            {{ $item->title }}
                                        </td>
                                        <td>
                                            @if($item->category)
                                                {{ $item->category->name }}
                                            @else
                                                <span class="text-muted">Belirtilmemiş</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $item->published_at ? $item->published_at->format('d.m.Y H:i') : 'Belirtilmemiş' }}
                                        </td>
                                        <td>
                                            @php
                                                $statusClass = [
                                                    'published' => 'success',
                                                    'draft' => 'warning',
                                                    'pending' => 'info',
                                                    'archived' => 'secondary',
                                                    'scheduled' => 'primary',
                                                ][$item->status] ?? 'secondary';
                                                
                                                $statusText = [
                                                    'published' => 'Yayında',
                                                    'draft' => 'Taslak',
                                                    'pending' => 'Beklemede',
                                                    'archived' => 'Arşivlenmiş',
                                                    'scheduled' => 'Zamanlanmış',
                                                ][$item->status] ?? $item->status;
                                            @endphp
                                            
                                            <span class="badge badge-{{ $statusClass }}">{{ $statusText }}</span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('admin.news.edit', $item->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('admin.news.show', $item->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-3">
                            {{ $news->links() }}
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-1"></i> Bu hedef kitleye ait haber bulunmamaktadır.
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- İlişkili Hizmetler -->
            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class="fas fa-cogs mr-1"></i>
                        İlişkili Hizmetler
                    </h3>
                </div>
                
                <div class="card-body">
                    @if($services->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Başlık</th>
                                        <th>Kategoriler</th>
                                        <th>Yayın Tarihi</th>
                                        <th>Durum</th>
                                        <th>İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($services as $service)
                                    <tr>
                                        <td>
                                            {{ $service->title }}
                                        </td>
                                        <td>
                                            @if($service->categories->count() > 0)
                                                @foreach($service->categories as $category)
                                                    <span class="badge badge-info">{{ $category->name }}</span>
                                                @endforeach
                                            @else
                                                <span class="text-muted">Belirtilmemiş</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $service->published_at ? $service->published_at->format('d.m.Y H:i') : 'Belirtilmemiş' }}
                                        </td>
                                        <td>
                                            @php
                                                $statusClass = [
                                                    'published' => 'success',
                                                    'draft' => 'danger',
                                                ][$service->status] ?? 'secondary';
                                                
                                                $statusText = [
                                                    'published' => 'Aktif',
                                                    'draft' => 'Pasif',
                                                ][$service->status] ?? $service->status;
                                            @endphp
                                            
                                            <span class="badge badge-{{ $statusClass }}">{{ $statusText }}</span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('admin.services.edit', $service->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('admin.services.show', $service->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-3">
                            {{ $services->links() }}
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-1"></i> Bu hedef kitleye ait hizmet bulunmamaktadır.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 