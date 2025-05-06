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
                                'draft' => 'warning',
                            ][$service->status] ?? 'secondary';
                            
                            $statusText = [
                                'published' => 'Yayında',
                                'draft' => 'Taslak',
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