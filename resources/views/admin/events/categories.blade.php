@extends('adminlte::page')

@section('title', 'Etkinlik Kategorileri Yönetimi')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Etkinlik Kategorileri</h1>
        <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Etkinliklere Dön
        </a>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-5">
            <!-- Kategori Ekleme/Düzenleme Kartı -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title" id="form-title">Yeni Kategori Ekle</h3>
                </div>
                <div class="card-body">
                    <form id="category-form" action="{{ route('admin.events.categories.store') }}" method="POST">
                        @csrf
                        <input type="hidden" id="form-method" name="_method" value="POST">
                        <input type="hidden" id="category-id" value="">
                        
                        <div class="form-group">
                            <label for="name">Kategori Adı</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Örn: Seminerler" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="slug">Slug</label>
                            <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" placeholder="seminerler">
                            <small class="form-text text-muted">Boş bırakırsanız otomatik oluşturulacaktır.</small>
                            @error('slug')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Açıklama</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" placeholder="Kategori açıklaması (opsiyonel)"></textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="color">Renk</label>
                            <input type="color" class="form-control @error('color') is-invalid @enderror" id="color" name="color" value="#3490dc">
                            <small class="form-text text-muted">Etkinlik takviminde gösterilecek olan renk.</small>
                            @error('color')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="order">Sıralama</label>
                            <input type="number" class="form-control @error('order') is-invalid @enderror" id="order" name="order" value="0" min="0">
                            @error('order')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" checked>
                                <label class="custom-control-label" for="is_active">Aktif</label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Kaydet
                            </button>
                            <button type="button" id="cancel-edit" class="btn btn-secondary d-none">
                                <i class="fas fa-times"></i> İptal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-7">
            <!-- Kategori Listesi Kartı -->
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Kategori Listesi</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-sm btn-success save-order" id="save-order-btn">
                            <i class="fas fa-save"></i> Sıralamayı Kaydet
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fas fa-check"></i> Başarılı!</h5>
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fas fa-ban"></i> Hata!</h5>
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">Sıra</th>
                                    <th>Kategori Adı</th>
                                    <th>Slug</th>
                                    <th style="width: 100px;">Durum</th>
                                    <th style="width: 150px;">İşlemler</th>
                                </tr>
                            </thead>
                            <tbody id="sortable-categories">
                                @forelse($categories as $category)
                                    <tr data-id="{{ $category->id }}">
                                        <td class="text-center handle" style="cursor: move;">
                                            <i class="fas fa-arrows-alt"></i>
                                            <span class="order-number">{{ $category->order }}</span>
                                        </td>
                                        <td>{{ $category->name }}</td>
                                        <td>{{ $category->slug }}</td>
                                        <td class="text-center">
                                            <button class="btn btn-sm {{ $category->is_active ? 'btn-success' : 'btn-danger' }} toggle-status" data-id="{{ $category->id }}">
                                                <i class="fas {{ $category->is_active ? 'fa-eye' : 'fa-eye-slash' }}"></i>
                                            </button>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-primary edit-category" 
                                                data-id="{{ $category->id }}" 
                                                data-name="{{ $category->name }}" 
                                                data-slug="{{ $category->slug }}" 
                                                data-description="{{ $category->description }}"
                                                data-color="{{ $category->color ?? '#3490dc' }}"
                                                data-order="{{ $category->order }}" 
                                                data-active="{{ $category->is_active }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form method="POST" action="{{ route('admin.events.categories.delete', $category->id) }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger delete-btn">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Henüz kategori eklenmemiş.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style>
        .handle {
            cursor: move;
        }
        
        .ui-sortable-helper {
            display: table;
            background-color: #f4f6f9;
            border: 1px dashed #3c8dbc;
        }
    </style>
@stop

@section('js')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script>
        $(document).ready(function() {
            // Kategori düzenleme
            $('.edit-category').click(function() {
                const id = $(this).data('id');
                const name = $(this).data('name');
                const slug = $(this).data('slug');
                const description = $(this).data('description');
                const color = $(this).data('color');
                const order = $(this).data('order');
                const active = $(this).data('active');
                
                $('#form-title').text('Kategori Düzenle');
                $('#category-form').attr('action', "{{ route('admin.events.categories.update', '') }}/" + id);
                $('#form-method').val('POST');
                $('#category-id').val(id);
                $('#name').val(name);
                $('#slug').val(slug);
                $('#description').val(description);
                $('#color').val(color);
                $('#order').val(order);
                $('#is_active').prop('checked', active === 1);
                
                $('#cancel-edit').removeClass('d-none');
                
                $('html, body').animate({
                    scrollTop: $('#category-form').offset().top - 100
                }, 200);
            });
            
            // Düzenleme iptal
            $('#cancel-edit').click(function() {
                $('#form-title').text('Yeni Kategori Ekle');
                $('#category-form').attr('action', "{{ route('admin.events.categories.store') }}");
                $('#form-method').val('POST');
                $('#category-id').val('');
                $('#name').val('');
                $('#slug').val('');
                $('#description').val('');
                $('#color').val('#3490dc');
                $('#order').val(0);
                $('#is_active').prop('checked', true);
                
                $(this).addClass('d-none');
            });
            
            // Slug oluşturma
            $('#name').on('blur', function() {
                if ($('#slug').val() === '') {
                    const slug = $(this).val()
                        .toString()
                        .toLowerCase()
                        .replace(/\s+/g, '-')
                        .replace(/[^\w\-]+/g, '')
                        .replace(/\-\-+/g, '-')
                        .replace(/^-+/, '')
                        .replace(/-+$/, '');
                    
                    $('#slug').val(slug);
                }
            });
            
            // Silme onayı
            $('.delete-btn').click(function(e) {
                if (!confirm('Bu kategoriyi silmek istediğinize emin misiniz? Bu işlem geri alınamaz.')) {
                    e.preventDefault();
                }
            });
            
            // Toggle status
            $('.toggle-status').click(function() {
                const id = $(this).data('id');
                const button = $(this);
                
                $.ajax({
                    url: `{{ route('admin.events.categories.toggle-visibility', '') }}/${id}`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            if (response.is_active) {
                                button.removeClass('btn-danger').addClass('btn-success');
                                button.find('i').removeClass('fa-eye-slash').addClass('fa-eye');
                            } else {
                                button.removeClass('btn-success').addClass('btn-danger');
                                button.find('i').removeClass('fa-eye').addClass('fa-eye-slash');
                            }
                            
                            toastr.success('Kategori durumu değiştirildi.');
                        } else {
                            toastr.error('Bir hata oluştu.');
                        }
                    },
                    error: function() {
                        toastr.error('Bir hata oluştu.');
                    }
                });
            });
            
            // Sıralama işlemleri
            $('#sortable-categories').sortable({
                handle: '.handle',
                update: function(event, ui) {
                    // Sıralamayı güncelle
                    $('#sortable-categories tr').each(function(index) {
                        $(this).find('.order-number').text(index + 1);
                    });
                }
            });
            
            // Sıralamayı kaydet
            $('#save-order-btn').click(function() {
                const orders = [];
                
                $('#sortable-categories tr').each(function(index) {
                    const id = $(this).data('id');
                    if (id) {
                        orders.push({
                            id: id,
                            order: index
                        });
                    }
                });
                
                $.ajax({
                    url: '{{ route("admin.events.categories.update-order") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        order: orders
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success('Kategori sıralaması güncellendi');
                        } else {
                            toastr.error('Bir hata oluştu: ' + response.error);
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Bir hata oluştu: ' + xhr.responseJSON.error);
                    }
                });
            });
        });
    </script>
@stop 