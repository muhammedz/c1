@extends('adminlte::page')

@section('title', 'Hedef Kitleler')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Hedef Kitleler</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Hedef Kitleler</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div><i class="fas fa-users me-1"></i> Hedef Kitle Listesi</div>
            <a href="{{ route('admin.hedef-kitleler.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Yeni Hedef Kitle
            </a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            
            <table id="hedef-kitleler-table" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th style="width: 50px;">Sıra</th>
                        <th>Ad</th>
                        <th>Açıklama</th>
                        <th style="width: 80px;">Durum</th>
                        <th style="width: 180px;">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="sortable">
                    @foreach($hedefKitleler as $index => $hedefKitle)
                    <tr data-id="{{ $hedefKitle->id }}">
                        <td class="text-center handle">
                            <span class="btn btn-sm btn-light">
                                <i class="fas fa-arrows-alt"></i>
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.hedef-kitleler.show', $hedefKitle) }}" class="text-primary font-weight-bold">
                                {{ $hedefKitle->name }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('admin.hedef-kitleler.show', $hedefKitle) }}" class="text-dark">
                                {{ Str::limit($hedefKitle->description, 50) }}
                            </a>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-{{ $hedefKitle->is_active ? 'success' : 'danger' }}">
                                {{ $hedefKitle->is_active ? 'Aktif' : 'Pasif' }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex">
                                <a href="{{ route('admin.hedef-kitleler.show', $hedefKitle) }}" class="btn btn-info btn-sm me-1">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.hedef-kitleler.edit', $hedefKitle) }}" class="btn btn-primary btn-sm me-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.hedef-kitleler.destroy', $hedefKitle) }}" method="POST" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bu hedef kitleyi silmek istediğinize emin misiniz?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="mt-3">
                {{ $hedefKitleler->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $(function() {
        // Sıralama işlemleri
        $(".sortable").sortable({
            handle: '.handle',
            update: function(event, ui) {
                var order = [];
                $('.sortable tr').each(function() {
                    order.push($(this).data('id'));
                });
                
                $.ajax({
                    url: '{{ route("admin.hedef-kitleler.update-order") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        hedefKitleler: order
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success('Hedef kitle sıralaması güncellendi');
                        } else {
                            toastr.error('Bir hata oluştu');
                        }
                    }
                });
            }
        });
    });
</script>
@endpush 