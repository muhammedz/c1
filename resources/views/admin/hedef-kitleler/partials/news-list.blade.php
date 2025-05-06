@if($news->count() > 0)
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Başlık</th>
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
                        
                        <span class="badge bg-{{ $statusClass }}">{{ $statusText }}</span>
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
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle"></i> {{ $hedefKitle->name }} hedef kitlesine ait haber bulunmamaktadır.
    </div>
@endif 