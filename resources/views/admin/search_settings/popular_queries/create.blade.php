@extends('adminlte::page')

@section('title', 'Yeni Popüler Arama')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Yeni Popüler Arama Ekle</h3>
                </div>
                
                <div class="card-body">
                    <!-- Hata Mesajları -->
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fas fa-ban"></i> Hata!</h5>
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form action="/admin/search-popular-queries" method="POST">
                        @csrf
                        
                        <div class="form-group">
                            <label for="title">Başlık</label>
                            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="url">URL</label>
                            <input type="text" name="url" id="url" class="form-control @error('url') is-invalid @enderror" value="{{ old('url') }}" required>
                            <small class="form-text text-muted">Örnek: <code>/search?q=Nöbetçi Eczaneler</code></small>
                            @error('url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="icon">İkon</label>
                            <div class="input-group">
                                <input type="text" name="icon" id="icon" class="form-control @error('icon') is-invalid @enderror" value="{{ old('icon') }}">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary" id="iconPickerBtn">
                                        <i class="fas fa-search"></i> İkon Seç
                                    </button>
                                </div>
                            </div>
                            <div class="mt-2">
                                <span class="material-icons" id="iconPreview" style="font-size: 24px;">{{ old('icon') }}</span>
                                <small class="ml-2">Önizleme</small>
                            </div>
                            @error('icon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="order">Sıralama</label>
                                    <input type="number" name="order" id="order" class="form-control @error('order') is-invalid @enderror" value="{{ old('order') }}" min="0">
                                    <small class="form-text text-muted">Boş bırakırsanız otomatik olarak en sona eklenecektir.</small>
                                    @error('order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mt-4">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="isActive" name="is_active" value="1" checked>
                                        <label class="custom-control-label" for="isActive">Aktif</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-right">
                            <a href="{{ route('admin.search-settings.index') }}" class="btn btn-secondary">İptal</a>
                            <button type="submit" class="btn btn-primary">Kaydet</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- İkon Seçici Modal -->
<div class="modal fade" id="iconPickerModal" tabindex="-1" role="dialog" aria-labelledby="iconPickerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="iconPickerModalLabel">İkon Seç</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input type="text" class="form-control" id="iconSearchInput" placeholder="İkon Ara...">
                </div>
                <div class="row" id="iconsList" style="max-height: 400px; overflow-y: auto;">
                    <!-- İkonlar AJAX ile yüklenir -->
                    <div class="col-12 text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Yükleniyor...</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        console.log('Popular Query Form ready');
        
        // Form submit olayını izle
        $('form').on('submit', function(e) {
            console.log('Form submit triggered');
            console.log('Form data:', $(this).serialize());
            // Form normal şekilde submit edilsin
        });
        
        // Switch değişikliklerini izle
        $('#isActive').on('change', function() {
            console.log('Active state changed:', $(this).prop('checked'));
        });
    });
</script>
@endsection

@section('scripts')
<script>
    $(function() {
        // İkon değişince önizleme güncellenir
        $('#icon').on('input', function() {
            $('#iconPreview').text($(this).val());
        });
        
        // İkon seçici modalı aç
        $('#iconPickerBtn').on('click', function() {
            $('#iconPickerModal').modal('show');
            loadIcons();
        });
        
        // İkonları yükle
        function loadIcons() {
            if (!$('#iconsList').data('loaded')) {
                $.ajax({
                    url: '{{ route("admin.search-icons") }}',
                    type: 'GET',
                    success: function(response) {
                        let iconsHtml = '';
                        
                        response.icons.forEach(function(icon) {
                            iconsHtml += `
                                <div class="col-md-3 col-sm-4 col-6 text-center mb-3 icon-item" data-icon="${icon}">
                                    <div class="p-2 border rounded icon-box" style="cursor: pointer;">
                                        <span class="material-icons" style="font-size: 24px;">${icon}</span>
                                        <div class="small text-muted text-truncate mt-1">${icon}</div>
                                    </div>
                                </div>
                            `;
                        });
                        
                        $('#iconsList').html(iconsHtml).data('loaded', true);
                        
                        // İkon seçme
                        $('.icon-box').on('click', function() {
                            const icon = $(this).closest('.icon-item').data('icon');
                            $('#icon').val(icon);
                            $('#iconPreview').text(icon);
                            $('#iconPickerModal').modal('hide');
                        });
                    },
                    error: function() {
                        $('#iconsList').html('<div class="col-12 text-center py-4">İkonlar yüklenirken bir hata oluştu.</div>');
                    }
                });
            }
        }
        
        // İkon arama
        $('#iconSearchInput').on('input', function() {
            const searchTerm = $(this).val().toLowerCase();
            
            $('.icon-item').each(function() {
                const icon = $(this).data('icon').toLowerCase();
                if (icon.includes(searchTerm)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    });
</script>
@endsection 