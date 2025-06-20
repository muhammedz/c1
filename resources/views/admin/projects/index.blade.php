@extends('adminlte::page')

@section('title', 'Projeler Yönetimi')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Projeler</h1>
        <div>
            <button type="button" id="toggle-visibility-btn" 
                    class="btn {{ $settings->is_active ? 'btn-success' : 'btn-danger' }} btn-sm mr-2">
                <i class="fas {{ $settings->is_active ? 'fa-eye' : 'fa-eye-slash' }}"></i>
                {{ $settings->is_active ? 'Aktif' : 'Pasif' }}
            </button>
            <div class="btn-group">
                <a href="{{ route('admin.projects.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Yeni Proje
                </a>
                <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-expanded="false">
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="{{ route('admin.projects.categories') }}">
                        <i class="fas fa-tags"></i> Kategorileri Yönet
                    </a>
                    <a class="dropdown-item" href="{{ route('admin.projects.settings') }}">
                        <i class="fas fa-cog"></i> Ayarlar
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('front.projects') }}" target="_blank">
                        <i class="fas fa-external-link-alt"></i> Ön İzleme
                    </a>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Proje Listesi</h3>
                    
                    <div class="card-tools">
                        <button type="button" class="btn btn-success btn-sm" id="save-order">
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
                    
                    <!-- Arama ve Filtreleme -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <form method="GET" action="{{ route('admin.projects.index') }}" class="form-inline">
                                <!-- Arama -->
                                <div class="form-group mr-3">
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control" placeholder="Proje ara..." value="{{ request('search') }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="submit">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Kategori Filtresi -->
                                <div class="form-group mr-3">
                                    <select name="category_id" class="form-control">
                                        <option value="">Tüm Kategoriler</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <!-- Durum Filtresi -->
                                <div class="form-group mr-3">
                                    <select name="status" class="form-control">
                                        <option value="">Tüm Durumlar</option>
                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Pasif</option>
                                        <option value="homepage" {{ request('status') == 'homepage' ? 'selected' : '' }}>Anasayfada</option>
                                    </select>
                                </div>
                                
                                <!-- Sıralama -->
                                <div class="form-group mr-3">
                                    <select name="sort" class="form-control">
                                        <option value="project_date" {{ request('sort', 'project_date') == 'project_date' ? 'selected' : '' }}>Proje Tarihi</option>
                                        <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Proje Adı</option>
                                        <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Oluşturulma Tarihi</option>
                                        <option value="order" {{ request('sort') == 'order' ? 'selected' : '' }}>Sıra</option>
                                    </select>
                                </div>
                                
                                <!-- Sıralama Yönü -->
                                <div class="form-group mr-3">
                                    <select name="direction" class="form-control">
                                        <option value="desc" {{ request('direction', 'desc') == 'desc' ? 'selected' : '' }}>Azalan</option>
                                        <option value="asc" {{ request('direction') == 'asc' ? 'selected' : '' }}>Artan</option>
                                    </select>
                                </div>
                                
                                <!-- Filtrele Butonu -->
                                <button type="submit" class="btn btn-primary mr-2">
                                    <i class="fas fa-filter"></i> Filtrele
                                </button>
                                
                                <!-- Temizle Butonu -->
                                <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Temizle
                                </a>
                            </form>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">Sıra</th>
                                    <th style="width: 100px;">Görsel</th>
                                    <th>Proje Adı</th>
                                    <th>Kategori</th>
                                    <th>Proje Tarihi</th>
                                    <th>Durum</th>
                                    <th style="width: 200px;">İşlemler</th>
                                </tr>
                            </thead>
                            <tbody id="sortable-projects">
                                @forelse($projects as $project)
                                    <tr data-id="{{ $project->id }}">
                                        <td class="text-center handle" style="cursor: move;">
                                            <i class="fas fa-arrows-alt"></i>
                                            <span class="order-number">{{ $project->order }}</span>
                                        </td>
                                        <td>
                                            <img src="{{ $project->cover_image_url }}" alt="{{ $project->title }}" class="img-thumbnail" style="max-height: 60px;">
                                        </td>
                                        <td>
                                            {{ $project->title }}
                                            @if($project->show_on_homepage)
                                                <span class="badge badge-info"><i class="fas fa-home"></i> Anasayfada</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($project->category)
                                                <span class="badge badge-primary">{{ $project->category->name }}</span>
                                            @else
                                                <span class="badge badge-warning">Kategori Yok</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($project->project_date)
                                                <span class="text-muted">{{ $project->project_date->format('d.m.Y') }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            {!! $project->status_badge !!}
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('admin.projects.edit', $project->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                <button type="button" class="btn btn-sm {{ $project->is_active ? 'btn-success' : 'btn-danger' }} toggle-status" data-id="{{ $project->id }}">
                                                    <i class="fas {{ $project->is_active ? 'fa-eye' : 'fa-eye-slash' }}"></i>
                                                </button>
                                                
                                                <button type="button" class="btn btn-sm {{ $project->show_on_homepage ? 'btn-info' : 'btn-secondary' }} toggle-homepage" data-id="{{ $project->id }}">
                                                    <i class="fas fa-home"></i>
                                                </button>
                                                
                                                <form action="{{ route('admin.projects.delete', $project->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger delete-btn">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                            
                                            <a href="{{ route('front.projects.detail', $project->slug) }}" target="_blank" class="btn btn-sm btn-secondary mt-1">
                                                <i class="fas fa-external-link-alt"></i> Görüntüle
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            @if(request()->hasAny(['search', 'category_id', 'status']))
                                                Arama kriterlerine uygun proje bulunamadı.
                                            @else
                                                Henüz proje eklenmemiş.
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Sayfalama -->
                    @if($projects->hasPages())
                        <div class="d-flex justify-content-center">
                            {{ $projects->links() }}
                        </div>
                    @endif
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.projects.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Yeni Proje Ekle
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Özet İstatistik Kartları -->
    <div class="row">
        <div class="col-md-3">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $projects->total() }}</h3>
                    <p>
                        @if(request()->hasAny(['search', 'category_id', 'status']))
                            Filtrelenmiş Proje
                        @else
                            Toplam Proje
                        @endif
                    </p>
                </div>
                <div class="icon">
                    <i class="fas fa-project-diagram"></i>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $projects->where('is_active', true)->count() }}</h3>
                    <p>Aktif Proje</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $categories->count() }}</h3>
                    <p>Kategori</p>
                </div>
                <div class="icon">
                    <i class="fas fa-tags"></i>
                </div>
                <a href="{{ route('admin.projects.categories') }}" class="small-box-footer">
                    Kategorileri Yönet <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="small-box bg-purple">
                <div class="inner">
                    <h3>{{ $projects->where('show_on_homepage', true)->count() }}</h3>
                    <p>Anasayfada Gösterilen</p>
                </div>
                <div class="icon">
                    <i class="fas fa-home"></i>
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
        
        .bg-purple {
            background-color: #6f42c1;
        }
    </style>
@stop

@section('js')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script>
        $(document).ready(function() {
            // Otomatik filtreleme
            $('select[name="category_id"], select[name="status"], select[name="sort"], select[name="direction"]').change(function() {
                $(this).closest('form').submit();
            });
            
            // Modül Görünürlük Değiştirme
            $('#toggle-visibility-btn').click(function() {
                $.ajax({
                    url: '{{ route('admin.projects.toggle-module-visibility') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            const button = $('#toggle-visibility-btn');
                            
                            if (response.is_active) {
                                button.removeClass('btn-danger').addClass('btn-success');
                                button.find('i').removeClass('fa-eye-slash').addClass('fa-eye');
                                button.html('<i class="fas fa-eye"></i> Aktif');
                            } else {
                                button.removeClass('btn-success').addClass('btn-danger');
                                button.find('i').removeClass('fa-eye').addClass('fa-eye-slash');
                                button.html('<i class="fas fa-eye-slash"></i> Pasif');
                            }
                            
                            toastr.success('Projeler modülü görünürlük durumu değiştirildi.');
                        } else {
                            toastr.error('Bir hata oluştu.');
                        }
                    },
                    error: function() {
                        toastr.error('Bir hata oluştu.');
                    }
                });
            });
            
            // Proje Görünürlük Değiştirme
            $('.toggle-status').click(function() {
                const id = $(this).data('id');
                const button = $(this);
                
                $.ajax({
                    url: `/admin/projects/${id}/toggle-visibility`,
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
                            
                            toastr.success('Proje görünürlük durumu değiştirildi.');
                        } else {
                            toastr.error('Bir hata oluştu.');
                        }
                    },
                    error: function() {
                        toastr.error('Bir hata oluştu.');
                    }
                });
            });
            
            // Anasayfada Gösterme Durumunu Değiştirme
            $('.toggle-homepage').click(function() {
                const id = $(this).data('id');
                const button = $(this);
                
                $.ajax({
                    url: `/admin/projects/${id}/toggle-homepage`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            if (response.show_on_homepage) {
                                button.removeClass('btn-secondary').addClass('btn-info');
                                // Anasayfada etiketi ekle
                                const row = button.closest('tr');
                                const titleCell = row.find('td:nth-child(3)');
                                
                                if (!titleCell.find('.badge-info').length) {
                                    titleCell.append('<span class="badge badge-info ml-1"><i class="fas fa-home"></i> Anasayfada</span>');
                                }
                            } else {
                                button.removeClass('btn-info').addClass('btn-secondary');
                                // Anasayfada etiketini kaldır
                                const row = button.closest('tr');
                                row.find('.badge-info').remove();
                            }
                            
                            // Anasayfada gösterilen sayısını güncelle
                            const homepageCountBox = $('.small-box.bg-purple .inner h3');
                            let count = parseInt(homepageCountBox.text());
                            
                            if (response.show_on_homepage) {
                                count++;
                            } else {
                                count--;
                            }
                            
                            homepageCountBox.text(count);
                            
                            toastr.success('Projenin anasayfada gösterilme durumu değiştirildi.');
                        } else {
                            toastr.error('Bir hata oluştu.');
                        }
                    },
                    error: function() {
                        toastr.error('Bir hata oluştu.');
                    }
                });
            });
            
            // Sıralama İşlemleri
            $('#sortable-projects').sortable({
                handle: '.handle',
                update: function(event, ui) {
                    // Sıralamayı güncelle
                    $('#sortable-projects tr').each(function(index) {
                        $(this).find('.order-number').text(index + 1);
                    });
                }
            });
            
            // Sıralamayı Kaydet
            $('#save-order').click(function() {
                const orders = {};
                
                $('#sortable-projects tr').each(function(index) {
                    const id = $(this).data('id');
                    if (id) { // id'nin tanımlı olduğundan emin olun
                        orders[id] = index + 1;
                    }
                });
                
                $.ajax({
                    url: '{{ route('admin.projects.update-order') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        orders: orders
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success('Proje sıralaması güncellendi.');
                            
                            // Sıra numaralarını güncelle
                            $('#sortable-projects tr').each(function(index) {
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
            
            // Silme İşlemi Onayı
            $('.delete-btn').click(function(e) {
                if (!confirm('Bu projeyi silmek istediğinize emin misiniz? Bu işlem geri alınamaz.')) {
                    e.preventDefault();
                }
            });
        });
    </script>
@stop 