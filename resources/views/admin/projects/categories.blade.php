@extends('adminlte::page')

@section('title', 'Proje Kategorileri Yönetimi')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Proje Kategorileri</h1>
        <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Projelere Dön
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
                    <form id="category-form" action="{{ route('admin.projects.categories.store') }}" method="POST">
                        @csrf
                        <input type="hidden" id="form-method" name="_method" value="POST">
                        <input type="hidden" id="category-id" value="">
                        
                        <div class="form-group">
                            <label for="name">Kategori Adı</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Örn: Gerçekleşen Projeler" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="slug">Slug (Opsiyonel)</label>
                            <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" placeholder="Örn: gerceklesen-projeler">
                            <small class="form-text text-muted">Boş bırakırsanız, kategori adından otomatik oluşturulacaktır.</small>
                            @error('slug')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="order">Sıralama (Opsiyonel)</label>
                            <input type="number" class="form-control @error('order') is-invalid @enderror" id="order" name="order" placeholder="Örn: 1">
                            <small class="form-text text-muted">Boş bırakırsanız, en son sıraya eklenecektir.</small>
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
                            <button type="submit" class="btn btn-primary">Kaydet</button>
                            <button type="button" id="cancel-edit" class="btn btn-secondary d-none">İptal</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Sıralama Kartı -->
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Kategori Sıralaması</h3>
                </div>
                <div class="card-body">
                    <p>Kategorileri yeni sıralarına sürükleyip bırakın. Değişiklikleriniz otomatik olarak kaydedilecektir.</p>
                    <button type="button" id="save-order" class="btn btn-success btn-block mb-3">
                        <i class="fas fa-save"></i> Sıralamayı Kaydet
                    </button>
                </div>
            </div>
        </div>
        
        <div class="col-md-7">
            <!-- Kategoriler Listesi Kartı -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Kategoriler</h3>
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
                                                data-order="{{ $category->order }}" 
                                                data-active="{{ $category->is_active }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form method="POST" action="{{ route('admin.projects.categories.delete', $category->id) }}" class="d-inline">
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
                const order = $(this).data('order');
                const active = $(this).data('active');
                
                $('#form-title').text('Kategori Düzenle');
                $('#category-form').attr('action', '/admin/projects/categories/' + id);
                $('#form-method').val('PUT');
                $('#category-id').val(id);
                $('#name').val(name);
                $('#slug').val(slug);
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
                $('#category-form').attr('action', '{{ route('admin.projects.categories.store') }}');
                $('#form-method').val('POST');
                $('#category-id').val('');
                $('#name').val('');
                $('#slug').val('');
                $('#order').val('');
                $('#is_active').prop('checked', true);
                
                $(this).addClass('d-none');
            });
            
            // Slugify fonksiyonu
            function slugify(text) {
                return text
                    .toString()
                    .normalize('NFD')
                    .replace(/[\u0300-\u036f]/g, '')
                    .toLowerCase()
                    .trim()
                    .replace(/\s+/g, '-')
                    .replace(/[^\w-]+/g, '')
                    .replace(/--+/g, '-');
            }
            
            // İsimden slug oluştur
            $('#name').on('keyup', function() {
                if ($('#slug').val() === '' || $('#category-id').val() === '') {
                    $('#slug').val(slugify($(this).val()));
                }
            });
            
            // Kategori durumunu değiştir
            $('.toggle-status').click(function() {
                const id = $(this).data('id');
                const button = $(this);
                
                $.ajax({
                    url: `/admin/projects/categories/${id}/toggle-visibility`,
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
            $('#save-order').click(function() {
                const orders = {};
                
                $('#sortable-categories tr').each(function(index) {
                    const id = $(this).data('id');
                    orders[id] = index + 1;
                });
                
                $.ajax({
                    url: '{{ route('admin.projects.categories.update-order') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        orders: orders
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success('Kategori sıralaması güncellendi.');
                            
                            // Sıra numaralarını güncelle
                            $('#sortable-categories tr').each(function(index) {
                                $(this).find('.order-number').text(index + 1);
                            });
                        } else {
                            toastr.error('Bir hata oluştu.');
                        }
                    },
                    error: function() {
                        toastr.error('Bir hata oluştu.');
                    }
                });
            });
            
            // Silme işlemi onayı
            $('.delete-btn').click(function(e) {
                if (!confirm('Bu kategoriyi silmek istediğinize emin misiniz?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
@stop 