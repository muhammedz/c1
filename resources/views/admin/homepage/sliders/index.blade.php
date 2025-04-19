@extends('adminlte::page')

@section('title', 'Slider Yönetimi')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Slider Yönetimi</h1>
        <a href="{{ route('admin.homepage.sliders.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Yeni Slider Ekle
        </a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Slider Listesi</h3>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-check"></i> Başarılı!</h5>
                    {{ session('success') }}
                </div>
            @endif
            
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-ban"></i> Hata!</h5>
                    {{ session('error') }}
                </div>
            @endif
            
            @if($sliders->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 60px">Sıra</th>
                                <th style="width: 150px">Görsel</th>
                                <th>Başlık</th>
                                <th>Alt Başlık</th>
                                <th style="width: 100px">Durum</th>
                                <th style="width: 200px">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody id="sliders-sortable">
                            @foreach ($sliders as $slider)
                                <tr data-id="{{ $slider->id }}">
                                    <td>
                                        <span class="handle">
                                            <i class="fas fa-grip-lines"></i>
                                        </span>
                                        <span class="order-number">{{ $slider->order }}</span>
                                    </td>
                                    <td>
                                        @if($slider->filemanagersystem_image)
                                            <img src="{{ $slider->filemanagersystem_image_url }}" alt="{{ $slider->filemanagersystem_image_alt }}" class="img-thumbnail" style="max-height: 50px;">
                                        @else
                                            <div class="text-center text-muted">
                                                <i class="fas fa-image fa-2x"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ $slider->title }}</td>
                                    <td>{{ $slider->subtitle ?? '-' }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm {{ $slider->is_active ? 'btn-success' : 'btn-danger' }} toggle-status" data-id="{{ $slider->id }}">
                                            <i class="fas {{ $slider->is_active ? 'fa-eye' : 'fa-eye-slash' }}"></i>
                                            {{ $slider->is_active ? 'Aktif' : 'Pasif' }}
                                        </button>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.homepage.sliders.edit', $slider->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-edit"></i> Düzenle
                                        </a>
                                        <form action="{{ route('admin.homepage.sliders.delete', $slider->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger delete-confirm">
                                                <i class="fas fa-trash"></i> Sil
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    <i class="icon fas fa-info"></i> Henüz eklenmiş slider bulunmamaktadır. Eklemek için "Yeni Slider Ekle" butonunu kullanabilirsiniz.
                </div>
            @endif
        </div>
    </div>
@stop

@section('js')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $(function() {
            // Sıralama işlevselliği
            $('#sliders-sortable').sortable({
                handle: '.handle',
                items: 'tr',
                axis: 'y',
                update: function(event, ui) {
                    let orders = {};
                    
                    $('#sliders-sortable tr').each(function(index) {
                        const id = $(this).data('id');
                        orders[id] = index;
                        $(this).find('.order-number').text(index);
                    });
                    
                    $.ajax({
                        url: '{{ route("admin.homepage.sliders.order") }}',
                        type: 'POST',
                        data: {
                            orders: orders,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success('Sıralama başarıyla güncellendi.');
                            } else {
                                toastr.error('Sıralama güncellenirken bir hata oluştu.');
                            }
                        },
                        error: function() {
                            toastr.error('Sıralama güncellenirken bir hata oluştu.');
                        }
                    });
                }
            });
            
            // Durum değiştirme işlevselliği
            $('.toggle-status').on('click', function() {
                const button = $(this);
                const id = button.data('id');
                
                $.ajax({
                    url: '/admin/homepage/sliders/' + id + '/toggle',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            if (response.is_active) {
                                button.removeClass('btn-danger').addClass('btn-success');
                                button.find('i').removeClass('fa-eye-slash').addClass('fa-eye');
                                button.contents().last().replaceWith(' Aktif');
                            } else {
                                button.removeClass('btn-success').addClass('btn-danger');
                                button.find('i').removeClass('fa-eye').addClass('fa-eye-slash');
                                button.contents().last().replaceWith(' Pasif');
                            }
                            
                            toastr.success('Durum başarıyla güncellendi.');
                        } else {
                            toastr.error('Durum güncellenirken bir hata oluştu.');
                        }
                    },
                    error: function() {
                        toastr.error('Durum güncellenirken bir hata oluştu.');
                    }
                });
            });
            
            // Silme onayı
            $('.delete-confirm').on('click', function(e) {
                e.preventDefault();
                const form = $(this).closest('form');
                
                Swal.fire({
                    title: 'Emin misiniz?',
                    text: "Bu slider kalıcı olarak silinecektir!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Evet, sil!',
                    cancelButtonText: 'İptal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@stop

@section('css')
    <style>
        .handle {
            cursor: move;
            margin-right: 8px;
        }
        
        #sliders-sortable tr {
            transition: background-color 0.3s;
        }
        
        #sliders-sortable tr.ui-sortable-helper {
            background-color: #f8f9fa;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
    </style>
@stop 