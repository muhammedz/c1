@extends('adminlte::page')

@section('title', 'Footer Menüsü Düzenle')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Footer Menüsü Düzenle</h1>
        <div>
            <a href="{{ route('admin.footer.menus.links.index', $menu) }}" class="btn btn-info">
                <i class="fas fa-link"></i> Linkleri Yönet
            </a>
            <a href="{{ route('admin.footer.menus.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Geri Dön
            </a>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit"></i> Menü Bilgileri
                    </h3>
                </div>
                <form action="{{ route('admin.footer.menus.update', $menu) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="title">Menü Başlığı <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title', $menu->title) }}" 
                                   placeholder="Örn: KURUMSAL, HİZMETLERİMİZ" required>
                            @error('title')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">
                                Bu başlık footer'da menü başlığı olarak görünecektir.
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="order">Sıralama <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('order') is-invalid @enderror" 
                                   id="order" name="order" value="{{ old('order', $menu->order) }}" 
                                   min="1" required>
                            @error('order')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">
                                Menünün footer'da görünme sırası. Küçük sayılar önce görünür.
                            </small>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" 
                                       {{ old('is_active', $menu->is_active) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Aktif</label>
                            </div>
                            <small class="form-text text-muted">
                                Pasif menüler footer'da görünmez.
                            </small>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Güncelle
                        </button>
                        <a href="{{ route('admin.footer.menus.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> İptal
                        </a>
                        <div class="float-right">
                            <form action="{{ route('admin.footer.menus.destroy', $menu) }}" method="POST" 
                                  style="display: inline;" 
                                  onsubmit="return confirm('Bu menü silinsin mi? Tüm linkleri de silinecektir!')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash"></i> Sil
                                </button>
                            </form>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle"></i> Menü Bilgileri
                    </h3>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Oluşturulma:</strong></td>
                            <td>{{ $menu->created_at->format('d.m.Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Son Güncelleme:</strong></td>
                            <td>{{ $menu->updated_at->format('d.m.Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Toplam Link:</strong></td>
                            <td>{{ $menu->links->count() }}</td>
                        </tr>
                        <tr>
                            <td><strong>Aktif Link:</strong></td>
                            <td>{{ $menu->activeLinks->count() }}</td>
                        </tr>
                        <tr>
                            <td><strong>Durum:</strong></td>
                            <td>
                                @if($menu->is_active)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-secondary">Pasif</span>
                                @endif
                            </td>
                        </tr>
                    </table>

                    <hr>

                    <h6>Son Linkler:</h6>
                    @forelse($menu->links->take(5) as $link)
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-truncate" style="max-width: 150px;">{{ $link->title }}</span>
                            @if($link->is_active)
                                <span class="badge badge-success badge-sm">Aktif</span>
                            @else
                                <span class="badge badge-secondary badge-sm">Pasif</span>
                            @endif
                        </div>
                    @empty
                        <p class="text-muted">Henüz link eklenmemiş</p>
                    @endforelse

                    @if($menu->links->count() > 5)
                        <small class="text-muted">ve {{ $menu->links->count() - 5 }} link daha...</small>
                    @endif

                    <div class="mt-3">
                        <a href="{{ route('admin.footer.menus.links.index', $menu) }}" class="btn btn-info btn-block">
                            <i class="fas fa-link"></i> Tüm Linkleri Yönet
                        </a>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-list"></i> Diğer Menüler
                    </h3>
                </div>
                <div class="card-body">
                    @foreach(\App\Models\FooterMenu::where('id', '!=', $menu->id)->ordered()->get() as $otherMenu)
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <a href="{{ route('admin.footer.menus.edit', $otherMenu) }}" class="text-decoration-none">
                                {{ $otherMenu->title }}
                            </a>
                            <span class="badge badge-secondary">{{ $otherMenu->order }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Form validasyonu
            $('form').on('submit', function(e) {
                let title = $('#title').val().trim();
                if (title === '') {
                    e.preventDefault();
                    $('#title').addClass('is-invalid');
                    toastr.error('Menü başlığı gereklidir');
                    return false;
                }
            });

            // Başlık otomatik büyük harf
            $('#title').on('input', function() {
                $(this).val($(this).val().toUpperCase());
            });
        });
    </script>
@stop 