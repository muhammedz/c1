@extends('adminlte::page')

@section('title', 'Sayfalar')

@section('content_header')
    <style>
        .content-header {
            display: none;
        }
    </style>
    <!-- CSRF Token için meta tag eklendi -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('plugins.Toastr', true)
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)
@section('plugins.Sweetalert2', true)

@push('js')
<script>
$(document).ready(function () {
    // CSRF token ayarı
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Türkçe dil tanımlaması - CDN'e istek göndermek yerine doğrudan tanımlıyoruz
    var turkishLanguage = {
        "emptyTable": "Tabloda herhangi bir veri mevcut değil",
        "info": "_TOTAL_ kayıttan _START_ - _END_ arasındaki kayıtlar gösteriliyor",
        "infoEmpty": "Kayıt yok",
        "infoFiltered": "(_MAX_ kayıt içerisinden bulunan)",
        "infoThousands": ".",
        "lengthMenu": "Sayfada _MENU_ kayıt göster",
        "loadingRecords": "Yükleniyor...",
        "processing": "İşleniyor...",
        "search": "Ara:",
        "zeroRecords": "Eşleşen kayıt bulunamadı",
        "paginate": {
            "first": "İlk",
            "last": "Son",
            "next": "Sonraki",
            "previous": "Önceki"
        },
        "aria": {
            "sortAscending": ": artan sütun sıralamasını aktifleştir",
            "sortDescending": ": azalan sütun sıralamasını aktifleştir"
        },
        "select": {
            "rows": {
                "_": "%d kayıt seçildi",
                "1": "1 kayıt seçildi"
            },
            "cells": {
                "1": "1 hücre seçildi",
                "_": "%d hücre seçildi"
            },
            "columns": {
                "1": "1 sütun seçildi",
                "_": "%d sütun seçildi"
            }
        },
        "autoFill": {
            "cancel": "İptal",
            "fillHorizontal": "Hücreleri yatay olarak doldur",
            "fillVertical": "Hücreleri dikey olarak doldur",
            "fill": "Bütün hücreleri <i>%d<\/i> ile doldur"
        },
        "buttons": {
            "collection": "Koleksiyon <span class=\"ui-button-icon-primary ui-icon ui-icon-triangle-1-s\"><\/span>",
            "colvis": "Sütun görünürlüğü",
            "colvisRestore": "Görünürlüğü eski haline getir",
            "copySuccess": {
                "1": "1 satır panoya kopyalandı",
                "_": "%ds satır panoya kopyalandı"
            },
            "copyTitle": "Panoya kopyala",
            "csv": "CSV",
            "excel": "Excel",
            "pageLength": {
                "-1": "Bütün satırları göster",
                "_": "%d satır göster"
            },
            "pdf": "PDF",
            "print": "Yazdır",
            "copy": "Kopyala",
            "copyKeys": "Tablodaki veriyi kopyalamak için CTRL veya u2318 + C tuşlarına basınız. İptal etmek için bu mesaja tıklayın veya escape tuşuna basın."
        },
        "searchBuilder": {
            "add": "Koşul Ekle",
            "button": {
                "0": "Arama Oluşturucu",
                "_": "Arama Oluşturucu (%d)"
            },
            "condition": "Koşul",
            "conditions": {
                "date": {
                    "after": "Sonra",
                    "before": "Önce",
                    "between": "Arasında",
                    "empty": "Boş",
                    "equals": "Eşittir",
                    "not": "Değildir",
                    "notBetween": "Dışında",
                    "notEmpty": "Dolu"
                },
                "number": {
                    "between": "Arasında",
                    "empty": "Boş",
                    "equals": "Eşittir",
                    "gt": "Büyüktür",
                    "gte": "Büyük eşittir",
                    "lt": "Küçüktür",
                    "lte": "Küçük eşittir",
                    "not": "Değildir",
                    "notBetween": "Dışında",
                    "notEmpty": "Dolu"
                },
                "string": {
                    "contains": "İçerir",
                    "empty": "Boş",
                    "endsWith": "İle biter",
                    "equals": "Eşittir",
                    "not": "Değildir",
                    "notEmpty": "Dolu",
                    "startsWith": "İle başlar"
                },
                "array": {
                    "contains": "İçerir",
                    "empty": "Boş",
                    "equals": "Eşittir",
                    "not": "Değildir",
                    "notEmpty": "Dolu",
                    "without": "Hariç"
                }
            },
            "data": "Veri",
            "deleteTitle": "Filtreleme kuralını silin",
            "leftTitle": "Kriteri dışarı çıkart",
            "logicAnd": "ve",
            "logicOr": "veya",
            "rightTitle": "Kriteri içeri al",
            "title": {
                "0": "Arama Oluşturucu",
                "_": "Arama Oluşturucu (%d)"
            },
            "value": "Değer",
            "clearAll": "Filtreleri Temizle"
        },
        "searchPanes": {
            "clearMessage": "Hepsini Temizle",
            "collapse": {
                "0": "Arama Bölmesi",
                "_": "Arama Bölmesi (%d)"
            },
            "count": "{total}",
            "countFiltered": "{shown}\/{total}",
            "emptyPanes": "Arama Bölmesi yok",
            "loadMessage": "Arama Bölmeleri yükleniyor ...",
            "title": "Etkin filtreler - %d"
        },
        "thousands": ".",
        "datetime": {
            "amPm": [
                "öö",
                "ös"
            ],
            "hours": "Saat",
            "minutes": "Dakika",
            "next": "Sonraki",
            "previous": "Önceki",
            "seconds": "Saniye",
            "unknown": "Bilinmeyen"
        },
        "editor": {
            "close": "Kapat",
            "create": {
                "button": "Yeni",
                "title": "Yeni kayıt oluştur",
                "submit": "Kaydet"
            },
            "edit": {
                "button": "Düzenle",
                "title": "Kaydı düzenle",
                "submit": "Güncelle"
            },
            "remove": {
                "button": "Sil",
                "title": "Kayıtları sil",
                "submit": "Sil",
                "confirm": {
                    "_": "%d adet kaydı silmek istediğinize emin misiniz?",
                    "1": "Bu kaydı silmek istediğinizden emin misiniz?"
                }
            },
            "error": {
                "system": "Bir sistem hatası oluştu (Ayrıntılı bilgi)"
            },
            "multi": {
                "title": "Çoklu Değer",
                "info": "Seçili kayıtlar bu alanda farklı değerler içeriyor. Seçili kayıtların hepsinde bu alana aynı değeri atamak için buraya tıklayın; aksi halde her kayıt bu alanda kendi değerini koruyacak.",
                "restore": "Değişiklikleri Geri Al",
                "noMulti": "Bu alan bir grup olarak değil ancak tekil olarak düzenlenebilir."
            }
        }
    };

    // DataTables
    $('#pagesTable').DataTable({
        language: turkishLanguage,
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Tümü"]],
        columns: [
            { data: 'id' },
            { data: 'title' },
            { data: 'categories' },
            { data: 'status' },
            { data: 'published_at' },
            { data: 'featured' },
            { data: 'actions', orderable: false, searchable: false }
        ],
        order: [[0, 'desc']]
    });

    // Öne çıkarma durumunu değiştirme
    $('.toggle-featured').on('click', function(e) {
        e.preventDefault();
        var pageId = $(this).data('id');
        var button = $(this);
        
        $.ajax({
            url: '/admin/pages/' + pageId + '/toggle-featured',
            type: 'POST',
            success: function(response) {
                if (response.success) {
                    // Düğme durumunu güncelle
                    if (button.hasClass('btn-outline-primary')) {
                        button.removeClass('btn-outline-primary').addClass('btn-primary');
                        button.find('i').removeClass('far').addClass('fas');
                    } else {
                        button.removeClass('btn-primary').addClass('btn-outline-primary');
                        button.find('i').removeClass('fas').addClass('far');
                    }
                    
                    // Toastr bildirimi
                    toastr.success('Öne çıkarma durumu başarıyla değiştirildi.');
                    
                    // Sayfayı yenileme
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    toastr.error(response.message || 'Bir hata oluştu.');
                }
            },
            error: function() {
                toastr.error('İşlem sırasında bir hata oluştu.');
            }
        });
    });

    // Durum değiştirme
    $('.toggle-status').on('click', function(e) {
        e.preventDefault();
        var pageId = $(this).data('id');
        var button = $(this);
        
        $.ajax({
            url: '/admin/pages/' + pageId + '/toggle-status',
            type: 'POST',
            success: function(response) {
                if (response.success) {
                    // Düğme metnini ve rengini güncelle
                    if (button.hasClass('btn-success')) {
                        button.removeClass('btn-success').addClass('btn-secondary');
                        button.text('Taslak');
                    } else {
                        button.removeClass('btn-secondary').addClass('btn-success');
                        button.text('Yayında');
                    }
                    
                    // Toastr bildirimi
                    toastr.success('Durum başarıyla değiştirildi.');
                } else {
                    toastr.error(response.message || 'Bir hata oluştu.');
                }
            },
            error: function() {
                toastr.error('İşlem sırasında bir hata oluştu.');
            }
        });
    });

    // Silme onayı
    $('.delete-page').on('click', function(e) {
        e.preventDefault();
        var form = $(this).closest('form');
        
        Swal.fire({
            title: 'Emin misiniz?',
            text: "Bu sayfa kalıcı olarak silinecek!",
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
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-gradient-primary">
                <h3 class="card-title">Sayfalar</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.pages.create') }}" class="btn btn-sm btn-success">
                        <i class="fas fa-plus"></i> Yeni Sayfa Ekle
                    </a>
                </div>
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
                
                <div class="mb-3">
                    <form action="{{ route('admin.pages.index') }}" method="GET" class="form-inline">
                        <div class="form-group mr-2">
                            <select name="category" class="form-control">
                                <option value="">Kategori Seçin</option>
                                @foreach($pageCategories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group mr-2">
                            <select name="status" class="form-control">
                                <option value="">Durum Seçin</option>
                                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Yayında</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Taslak</option>
                            </select>
                        </div>
                        
                        <div class="form-group mr-2">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Ara..." value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        @if(request('category') || request('status') || request('search'))
                            <a href="{{ route('admin.pages.index') }}" class="btn btn-default">Filtreleri Temizle</a>
                        @endif
                    </form>
                </div>
                
                <table id="pagesTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Başlık</th>
                            <th>Kategoriler</th>
                            <th>Durum</th>
                            <th>Yayın Tarihi</th>
                            <th>Öne Çıkan</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pages as $page)
                            <tr>
                                <td>{{ $page->id }}</td>
                                <td>
                                    <img src="{{ $page->image }}" alt="{{ $page->title }}" class="img-thumbnail mr-2" style="max-width: 50px;">
                                    {{ $page->title }}
                                </td>
                                <td>
                                    @foreach($page->categories as $category)
                                        <span class="badge bg-primary">{{ $category->name }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    <button class="btn btn-sm toggle-status {{ $page->status == 'published' ? 'btn-success' : 'btn-secondary' }}" data-id="{{ $page->id }}">
                                        {{ $page->status == 'published' ? 'Yayında' : 'Taslak' }}
                                    </button>
                                </td>
                                <td>{{ $page->published_at ? $page->published_at->format('d.m.Y H:i') : '-' }}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm {{ $page->is_featured ? 'btn-primary' : 'btn-outline-primary' }} toggle-featured" data-id="{{ $page->id }}">
                                        <i class="{{ $page->is_featured ? 'fas' : 'far' }} fa-star"></i>
                                    </button>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.pages.edit', $page->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-edit"></i> Düzenle
                                        </a>
                                        <form action="{{ route('admin.pages.destroy', $page->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger delete-page">
                                                <i class="fas fa-trash"></i> Sil
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop 