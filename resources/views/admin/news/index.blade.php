@extends('adminlte::page')

@section('title', 'Haberler')

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
    // Toastr için global ayarlar
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut",
        "closeHtml": '<button><i class="fa fa-times"></i></button>'
    };
    
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
                "title": "Çoklu değer",
                "info": "Seçili kayıtlar bu alanda farklı değerler içeriyor. Seçili kayıtların hepsinde bu alana aynı değeri atamak için buraya tıklayın; aksi halde her kayıt bu alanda kendi değerini koruyacak.",
                "restore": "Değişiklikleri geri al",
                "noMulti": "Bu alan bir grup olarak değil ancak tekil olarak düzenlenebilir."
            }
        }
    };

    // DataTables
    var newsTable = $('#news-table').DataTable({
        language: turkishLanguage,
        responsive: true,
        ordering: false, // Sıralamayı kapat, backend sıralamasını kullan
        columnDefs: [
            { orderable: false, targets: [0, 1, 7] } // Bu sütunları sıralanamaz yap
        ],
        // Sayfalandırma alanı düzenlendi, sorunlu alan düzeltildi
        dom: "<'row'<'col-sm-12'tr>>" +
             "<'row mt-2'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 d-flex justify-content-end align-items-center'lp>>",
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Tümü"]]
    });
    
    // DataTables özel arama kutusu
    $('#custom-search').on('keyup', function() {
        newsTable.search(this.value).draw();
    });

    // Kategori filtresi
    $('#category-filter').on('change', function() {
        var searchTerm = $(this).val();
        newsTable.column(3).search(searchTerm).draw();
    });

    // Durum filtresi
    $('#status-filter').on('change', function() {
        var searchTerm = $(this).val();
        newsTable.column(4).search(searchTerm).draw();
    });

    // Özellik filtresi
    $('#feature-filter').on('change', function() {
        var searchTerm = $(this).val();
        newsTable.column(5).search(searchTerm).draw();
    });

    // Tümünü seç
    $('#select-all').on('click', function() {
        var isChecked = $(this).prop('checked');
        $('.checkbox-select-row').prop('checked', isChecked);
        updateBulkActionBar();
    });

    // Checkbox değişikliği
    $(document).on('click', '.checkbox-select-row', function() {
        updateBulkActionBar();
        
        // Tüm checkboxlar seçili mi kontrolü
        var allChecked = $('.checkbox-select-row:checked').length === $('.checkbox-select-row').length;
        $('#select-all').prop('checked', allChecked);
    });
    
    // Toplu işlem çubuğunu güncelle
    function updateBulkActionBar() {
        var checkedCount = $('.checkbox-select-row:checked').length;
        $('.selected-count').text(checkedCount + ' haber seçildi');
        
        if (checkedCount > 0) {
            $('#bulk-actions').addClass('show');
        } else {
            $('#bulk-actions').removeClass('show');
        }
    }

    // Durum değiştirme
    $(document).on('click', '.status-action', function() {
        var id = $(this).data('id');
        var action = $(this).data('action');
        var url = '/admin/news/' + id + '/toggle-status';
        
        // Ajax isteği
        $.ajax({
            url: url,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    showSuccessAlert(response.message || 'Durum başarıyla değiştirildi.');
                } else {
                    showErrorAlert('İşlem başarısız oldu.');
                }
            },
            error: function() {
                showErrorAlert('Bir hata oluştu.');
            }
        });
    });

    // Toplu işlem - Silme
    $(document).on('click', '.bulk-delete', function() {
        var selectedIds = getSelectedIds();
        
        if (selectedIds.length === 0) {
            showErrorAlert('Lütfen en az bir haber seçin.');
            return;
        }
        
        if (confirm('Seçilen ' + selectedIds.length + ' haberi silmek istediğinize emin misiniz? Bu işlem geri alınamaz.')) {
            processBulkAction(selectedIds, 'delete');
        }
    });
    
    // Toplu işlem - Yayınlama
    $(document).on('click', '.bulk-publish', function() {
        var selectedIds = getSelectedIds();
        
        if (selectedIds.length === 0) {
            showErrorAlert('Lütfen en az bir haber seçin.');
            return;
        }
        
        processBulkAction(selectedIds, 'publish');
    });
    
    // Toplu işlem - Taslak yapma
    $(document).on('click', '.bulk-draft', function() {
        var selectedIds = getSelectedIds();
        
        if (selectedIds.length === 0) {
            showErrorAlert('Lütfen en az bir haber seçin.');
            return;
        }
        
        processBulkAction(selectedIds, 'draft');
    });
    
    // Toplu işlem - Arşivleme
    $(document).on('click', '.bulk-archive', function() {
        var selectedIds = getSelectedIds();
        
        if (selectedIds.length === 0) {
            showErrorAlert('Lütfen en az bir haber seçin.');
            return;
        }
        
        processBulkAction(selectedIds, 'archive');
    });
    
    // Seçili haber ID'lerini al
    function getSelectedIds() {
        var ids = [];
        $('.checkbox-select-row:checked').each(function() {
            ids.push($(this).data('id'));
        });
        return ids;
    }
    
    // Toplu işlemi gerçekleştir
    function processBulkAction(ids, action) {
        $.ajax({
            url: '/admin/news/bulk-action',
            type: 'POST',
            data: {
                ids: ids,
                action: action
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    showSuccessAlert(response.message);
                } else {
                    showErrorAlert(response.message || 'İşlem başarısız oldu.');
                }
            },
            error: function() {
                showErrorAlert('Bir hata oluştu.');
            }
        });
    }

    // Manşet değiştirme
    $(document).on('click', '.headline-action', function() {
        var id = $(this).data('id');
        var current = $(this).data('current');
        var url = '/admin/news/' + id + '/toggle-headline';
        
        // Ajax isteği
        $.ajax({
            url: url,
            type: 'POST',
            success: function(response) {
                if (response.success) {
                    showSuccessAlert(response.message || 'Manşet durumu başarıyla değiştirildi.');
                } else {
                    showErrorAlert('İşlem başarısız oldu.');
                }
            },
            error: function() {
                showErrorAlert('Bir hata oluştu.');
            }
        });
    });

    // Öne çıkarma değiştirme
    $(document).on('click', '.featured-action', function() {
        var id = $(this).data('id');
        var current = $(this).data('current');
        var url = '/admin/news/' + id + '/toggle-featured';
        
        // Ajax isteği
        $.ajax({
            url: url,
            type: 'POST',
            success: function(response) {
                if (response.success) {
                    showSuccessAlert(response.message || 'Öne çıkarma durumu başarıyla değiştirildi.');
                } else {
                    showErrorAlert('İşlem başarısız oldu.');
                }
            },
            error: function() {
                showErrorAlert('Bir hata oluştu.');
            }
        });
    });

    // Arşiv değiştirme
    $(document).on('click', '.archive-action', function() {
        var id = $(this).data('id');
        var current = $(this).data('current');
        var url = '/admin/news/' + id + '/toggle-archive';
        
        // Ajax isteği
        $.ajax({
            url: url,
            type: 'POST',
            success: function(response) {
                if (response.success) {
                    showSuccessAlert(response.message || 'Arşiv durumu başarıyla değiştirildi.');
                } else {
                    showErrorAlert('İşlem başarısız oldu.');
                }
            },
            error: function() {
                showErrorAlert('Bir hata oluştu.');
            }
        });
    });

    // Silme işlemi
    $(document).on('click', '.delete-action', function() {
        var id = $(this).data('id');
        
        if (confirm('Bu haberi silmek istediğinize emin misiniz? Bu işlem geri alınamaz.')) {
            $.ajax({
                url: '/admin/news/' + id,
                type: 'DELETE',
                success: function(response) {
                    showSuccessAlert('Haber başarıyla silindi.');
                    location.reload();
                },
                error: function() {
                    showErrorAlert('Silme işlemi sırasında bir hata oluştu.');
                }
            });
        }
    });

    // Başarılı alert
    function showSuccessAlert(message) {
        toastr.success(message);
        setTimeout(function() {
            location.reload();
        }, 1000);
    }

    // Hata alert
    function showErrorAlert(message) {
        toastr.error(message);
    }
});
</script>
@endpush

@push('css')
<style>
    /* Alert kapatma butonu için CSS düzeltmesi */
    .close, .toast-close-button {
        float: right;
        font-size: 1.5rem;
        font-weight: 700;
        line-height: 1;
        color: #000;
        text-shadow: 0 1px 0 #fff;
        opacity: .5;
    }
    
    .close span, .toast-close-button {
        display: block;
        font-family: sans-serif; /* Web-safe font */
    }
    
    .toast-close-button:after {
        content: "×"; /* Unicode karakter × */
        font-size: 20px;
    }
    
    /* Toastr mesajları için ek stiller */
    #toast-container .toast {
        opacity: 1 !important;
    }
    
    .news-dashboard {
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }
    
    .stats-card {
        transition: all 0.3s;
        border-radius: 10px;
        border: none;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        height: 100%;
        overflow: hidden;
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 7px 14px rgba(0,0,0,0.1);
    }
    
    .stats-icon {
        font-size: 2rem;
        background: rgba(0,123,255,0.1);
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
    }
    
    .stats-text {
        font-size: 1rem;
        font-weight: normal;
        color: #6c757d;
    }
    
    .stats-number {
        font-size: 1.8rem;
        font-weight: bold;
        margin-bottom: 0;
        line-height: 1;
    }
    
    .filters-card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        transition: all 0.3s;
        overflow: hidden;
    }
    
    .filters-card .card-header {
        border-bottom: none;
        background-color: white;
        padding: 15px 20px;
    }
    
    .filter-btn {
        border-radius: 6px;
        font-weight: 500;
        padding: 8px 16px;
        transition: all 0.3s;
    }
    
    .filter-btn:hover {
        transform: translateY(-2px);
    }
    
    .form-control, .form-select {
        border-radius: 6px;
        border: 1px solid #e9ecef;
        padding: 10px 15px;
        font-size: 0.9rem;
        box-shadow: none;
        transition: all 0.3s;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
    }
    
    .page-title-box {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .news-count {
        background: #e9f5ff;
        padding: 5px 10px;
        border-radius: 20px;
        color: #0d6efd;
        font-weight: 500;
        font-size: 0.85rem;
        margin-left: 10px;
    }
    
    .page-title {
        font-size: 1.8rem;
        color: #343a40;
        font-weight: 600;
        margin: 0;
    }
    
    .top-action-btn {
        border-radius: 6px;
        padding: 10px 20px;
        font-weight: 500;
        box-shadow: 0 2px 5px rgba(0,0,0,0.15);
        transition: all 0.3s;
    }
    
    .top-action-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    
    .breadcrumb-modern {
        background: transparent;
        padding: 0;
        margin-bottom: 5px;
        font-size: 0.85rem;
    }
    
    .breadcrumb-modern .breadcrumb-item+.breadcrumb-item::before {
        content: "›";
        font-size: 1.1rem;
        line-height: 1;
        vertical-align: middle;
    }
    
    /* Haber kartları için stiller */
    .news-card {
        transition: all 0.3s;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        border: 1px solid #eee;
    }
    
    .news-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .news-card .card-img-top {
        transition: all 0.5s;
    }
    
    .news-card:hover .card-img-top {
        transform: scale(1.05);
    }
    
    .news-card .card-body {
        padding: 1rem;
    }
    
    .news-card .card-title {
        font-weight: 600;
        margin-bottom: 0.5rem;
        font-size: 1.1rem;
        color: #343a40;
    }
    
    .news-card .card-footer {
        border-top: 1px solid rgba(0,0,0,0.05);
        padding: 0.75rem 1rem;
    }
    
    /* Headline kartları için stiller */
    .headline-card {
        transition: all 0.3s;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0,0,0,0.08);
        border: 1px solid #f0f0f0;
        background-color: #fff;
        margin-bottom: 15px;
    }
    
    .headline-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(220,53,69,0.2);
    }
    
    /* Sürükleme işlemleri için stiller */
    .drag-handle {
        cursor: grab !important;
        user-select: none;
        transition: all 0.2s;
        font-weight: bold;
    }
    
    .drag-handle:hover {
        transform: scale(1.1);
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    
    .drag-handle:active {
        cursor: grabbing !important;
    }
    
    .headline-order {
        font-weight: bold;
    }
    
    /* Sürükleme stil efektleri */
    [draggable="true"] {
        cursor: move;
    }
    
    .dragging {
        opacity: 0.4;
        cursor: grabbing;
        z-index: 100;
    }
    
    .drag-over {
        border: 2px dashed #dc3545;
        transform: scale(1.02);
    }
    
    /* Liste görünümü için stiller */
    #list-view .table {
        border-radius: 10px;
        overflow: hidden;
    }
    
    #list-view .table thead th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #e9ecef;
        padding: 12px 15px;
        font-weight: 600;
        color: #495057;
    }
    
    #list-view .table tbody tr {
        transition: all 0.2s;
    }
    
    #list-view .table tbody tr:hover {
        background-color: #f8f9fa;
    }
    
    #list-view .table td {
        padding: 12px 15px;
        vertical-align: middle;
    }
    
    /* Pagination için stiller */
    .pagination {
        border-radius: 30px;
        background-color: #fff;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        padding: 5px;
    }
    
    .pagination .page-item .page-link {
        border-radius: 30px;
        margin: 0 3px;
        padding: 8px 16px;
        color: #6c757d;
        border: none;
        font-weight: 500;
    }
    
    .pagination .page-item.active .page-link {
        background-color: #007bff;
        color: #fff;
    }
    
    .pagination .page-item .page-link:hover {
        background-color: #f8f9fa;
        color: #007bff;
    }
    
    /* Responsive düzenlemeler */
    @media (max-width: 768px) {
        .stats-card {
            margin-bottom: 15px;
        }
        
        .page-title-box {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .page-title-box .top-action-btn {
            margin-top: 15px;
        }
        
        .news-card {
            margin-bottom: 20px;
        }
    }
    
    /* Manşet kartları için stiller */
    .card {
        transition: all 0.3s ease;
        border: 1px solid #dee2e6;
    }
    
    .card:hover {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    
    /* Sürükleme sırasındaki stiller */
    .ui-sortable-helper {
        display: block !important;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
    
    .ui-sortable-placeholder {
        visibility: visible !important;
        background: #f8f9fa;
        border: 2px dashed #dee2e6;
    }
    
    /* Sürükleme tutamacı */
    .handle {
        cursor: move;
        padding: 0.5rem;
        border-radius: 4px;
        transition: background-color 0.2s;
    }
    
    .handle:hover {
        background-color: #f8f9fa;
    }

    /* DataTables stilleri */
    table.dataTable {
        width: 100% !important;
        border-collapse: collapse !important;
    }

    table.dataTable thead th {
        position: relative;
        background-color: #f8f9fa;
        border-bottom: 2px solid #e9ecef;
        font-weight: 600;
        color: #495057;
    }

    table.dataTable tbody tr {
        transition: all 0.2s;
    }

    table.dataTable tbody tr:hover {
        background-color: #f8f9fa;
    }

    table.dataTable tbody td {
        vertical-align: middle;
    }

    .dataTables_wrapper .dataTables_length select {
        min-width: 60px;
        margin-left: 5px;
        margin-right: 5px;
        padding: 0.375rem 2.25rem 0.375rem 0.75rem;
        font-size: 0.9rem;
        border-radius: 0.25rem;
        border: 1px solid #ced4da;
        background-color: #fff;
    }
    
    .dataTables_wrapper .dataTables_length {
        margin-top: 10px;
        margin-right: 10px;
        display: inline-block;
    }
    
    .dataTables_wrapper .dataTables_paginate {
        margin-top: 10px;
        margin-left: 10px;
        float: right;
    }
    
    .dataTables_wrapper .dataTables_info {
        padding-top: 15px;
        color: #6c757d;
        font-size: 0.9rem;
    }

    .custom-filter-group {
        display: flex;
        gap: 10px;
        align-items: center;
        margin-bottom: 15px;
    }

    .custom-filter-group .form-select, 
    .custom-filter-group .form-control {
        max-width: 200px;
    }

    .dt-buttons {
        margin-bottom: 15px;
    }

    .image-thumbnail {
        width: 80px;
        height: 60px;
        object-fit: cover;
        border-radius: 4px;
        border: 1px solid #dee2e6;
    }

    .status-badge {
        padding: 5px 10px;
        border-radius: 50px;
        font-weight: 500;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .status-badge i {
        margin-right: 5px;
    }

    .status-published {
        background-color: #d1e7dd;
        color: #0f5132;
    }

    .status-draft {
        background-color: #fff3cd;
        color: #664d03;
    }

    .status-archived {
        background-color: #e2e3e5;
        color: #41464b;
    }

    .feature-badge {
        padding: 3px 6px;
        border-radius: 4px;
        font-size: 0.75rem;
        margin-right: 5px;
    }

    .table-action-btn {
        padding: 5px 8px;
        font-size: 0.85rem;
        margin: 0 2px;
    }

    .checkbox-select-row {
        width: 20px;
        height: 20px;
    }

    .bulk-actions {
        display: none;
        margin-bottom: 15px;
        padding: 10px 15px;
        background-color: #f8f9fa;
        border-radius: 6px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .bulk-actions.show {
        display: flex;
    }

    .dt-button {
        padding: 5px 10px !important;
        font-size: 0.9rem !important;
    }

    /* Filtre stili */
    .search-container {
        background-color: #f8f9fa;
        border: 1px solid #eaecef;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    
    .dropdown-filter {
        min-width: 160px;
    }
    
    .dropdown-filter .form-select {
        cursor: pointer;
        color: #495057;
        font-size: 0.875rem;
        padding: 0.375rem 2rem 0.375rem 0.75rem;
    }
    
    .dropdown-filter .form-select:focus {
        box-shadow: none;
    }
    
    .search-box {
        min-width: 200px;
    }
    
    .search-box .form-control:focus {
        box-shadow: none;
    }
    
    /* Varsayılan datatable arama kutusunu gizle */
    .dataTables_filter {
        display: none !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Üst Başlık ve İstatistik Bölümü -->
    <div class="page-title-box">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-modern">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Haberler</li>
                </ol>
            </nav>
            <h1 class="page-title">
                Haberler 
                <span class="news-count">{{ $news->total() }} Haber</span>
            </h1>
        </div>
        
        <div>
            <a href="{{ route('admin.news.create') }}" class="btn btn-primary top-action-btn">
                <i class="fas fa-plus-circle me-1"></i> Yeni Haber Ekle
            </a>
        </div>
    </div>
    
    <!-- İstatistik Kartları -->
    <div class="news-dashboard">
        <div class="row g-3">
            <div class="col-md-3">
                <div class="card stats-card">
                    <div class="card-body d-flex align-items-center">
                        <div class="stats-icon text-primary">
                            <i class="fas fa-newspaper"></i>
                        </div>
                        <div>
                            <div class="stats-text">Toplam Haber</div>
                            <h3 class="stats-number">{{ $news->total() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card stats-card">
                    <div class="card-body d-flex align-items-center">
                        <div class="stats-icon text-success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div>
                            <div class="stats-text">Yayında</div>
                            <h3 class="stats-number">{{ App\Models\News::where('status', 'published')->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card stats-card">
                    <div class="card-body d-flex align-items-center">
                        <div class="stats-icon text-warning">
                            <i class="fas fa-edit"></i>
                        </div>
                        <div>
                            <div class="stats-text">Taslak</div>
                            <h3 class="stats-number">{{ App\Models\News::where('status', 'draft')->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card stats-card">
                    <div class="card-body d-flex align-items-center">
                        <div class="stats-icon text-danger">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                        <div>
                            <div class="stats-text">Manşet</div>
                            <h3 class="stats-number">{{ $headlineCount }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Alert Mesajları -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Toplu İşlem Butonları -->
    <div class="bulk-actions" id="bulk-actions">
        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-danger bulk-delete" type="button">
                <i class="fas fa-trash-alt me-1"></i> Seçilenleri Sil
            </button>
            <button class="btn btn-sm btn-success bulk-publish" type="button">
                <i class="fas fa-check-circle me-1"></i> Seçilenleri Yayınla
            </button>
            <button class="btn btn-sm btn-warning bulk-draft" type="button">
                <i class="fas fa-edit me-1"></i> Seçilenleri Taslak Yap
            </button>
            <button class="btn btn-sm btn-secondary bulk-archive" type="button">
                <i class="fas fa-archive me-1"></i> Seçilenleri Arşivle
            </button>
        </div>
        <div class="ms-auto">
            <span class="selected-count">0 haber seçildi</span>
        </div>
    </div>

    <!-- DataTable -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Haber Listesi</h3>
        </div>
        <div class="card-body">
            <div class="search-container p-2 rounded mb-3 d-flex flex-wrap align-items-center gap-2">
                <div class="dropdown-filter">
                    <select id="category-filter" class="form-select form-select-sm border-0 bg-light text-secondary">
                        <option value="">Tüm Kategoriler</option>
                        @foreach($newsCategories as $category)
                            <option value="{{ $category->name }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="dropdown-filter">
                    <select id="status-filter" class="form-select form-select-sm border-0 bg-light text-secondary">
                        <option value="">Tüm Durumlar</option>
                        <option value="Yayında">Yayında</option>
                        <option value="Taslak">Taslak</option>
                    </select>
                </div>
                
                <div class="dropdown-filter">
                    <select id="feature-filter" class="form-select form-select-sm border-0 bg-light text-secondary">
                        <option value="">Tüm Özellikler</option>
                        <option value="Manşet">Manşet</option>
                        <option value="Öne Çıkan">Öne Çıkan</option>
                        <option value="Arşivlenmiş">Arşivlenmiş</option>
                    </select>
                </div>
                
                <div class="ms-auto search-box">
                    <div class="input-group border-0 bg-light rounded pe-0">
                        <span class="input-group-text border-0 bg-transparent px-2">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="search" id="custom-search" class="form-control form-control-sm border-0 bg-light shadow-none" placeholder="Ara...">
                    </div>
                </div>
            </div>
            
            <div class="table-responsive">
                <table id="news-table" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 30px;">
                                <input type="checkbox" id="select-all">
                            </th>
                            <th>Görsel</th>
                            <th>Başlık</th>
                            <th>Kategoriler</th>
                            <th>Durum</th>
                            <th>Özellikler</th>
                            <th>Yayın Tarihi</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($news as $item)
                        <tr data-id="{{ $item->id }}">
                            <td>
                                <input type="checkbox" class="checkbox-select-row" data-id="{{ $item->id }}">
                            </td>
                            <td class="text-center">
                                <img src="{{ asset($item->image) }}" alt="{{ $item->title }}" class="img-thumbnail" width="80">
                            </td>
                            <td>
                                <strong>{{ $item->title }}</strong>
                                @if($item->view_count > 0)
                                <div class="small text-muted mt-1">
                                    <i class="fas fa-eye me-1"></i> {{ $item->view_count }} görüntülenme
                                </div>
                                @endif
                            </td>
                            <td>
                                @foreach($item->categories as $category)
                                    <span class="badge bg-primary me-1">{{ $category->name }}</span>
                                @endforeach
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $item->status == 'published' ? 'bg-success' : 'bg-warning' }}">
                                    <i class="fas {{ $item->status == 'published' ? 'fa-check-circle' : 'fa-edit' }} me-1"></i>
                                    {{ $item->status == 'published' ? 'Yayında' : 'Taslak' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                    @if($item->is_headline)
                                        <span class="badge bg-danger">
                                            <i class="fas fa-bullhorn"></i> Manşet
                                        </span>
                                    @endif
                                    
                                    @if($item->is_featured)
                                        <span class="badge bg-success">
                                            <i class="fas fa-star"></i> Öne Çıkan
                                        </span>
                                    @endif
                                    
                                    @if($item->is_archived)
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-archive"></i> Arşiv
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="small">
                                    <div><i class="far fa-calendar-alt me-1"></i> {{ $item->published_at ? $item->published_at->format('d.m.Y') : $item->created_at->format('d.m.Y') }}</div>
                                    <div class="text-muted">{{ $item->published_at ? $item->published_at->format('H:i') : $item->created_at->format('H:i') }}</div>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.news.edit', $item->id) }}" class="btn btn-sm btn-primary" title="Düzenle">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-secondary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                        <span class="sr-only">Menüyü Aç</span>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item status-action" href="javascript:void(0);" data-id="{{ $item->id }}" data-action="{{ $item->status == 'published' ? 'draft' : 'publish' }}">
                                            @if($item->status == 'published')
                                                <i class="fas fa-file text-warning"></i> Taslağa Çevir
                                            @else
                                                <i class="fas fa-check-circle text-success"></i> Yayınla
                                            @endif
                                        </a>
                                        <a class="dropdown-item headline-action" href="javascript:void(0);" data-id="{{ $item->id }}" data-current="{{ $item->is_headline ? '1' : '0' }}">
                                            @if($item->is_headline)
                                                <i class="fas fa-times-circle text-danger"></i> Manşetten Kaldır
                                            @else
                                                <i class="fas fa-bullhorn text-danger"></i> Manşet Yap
                                            @endif
                                        </a>
                                        <a class="dropdown-item featured-action" href="javascript:void(0);" data-id="{{ $item->id }}" data-current="{{ $item->is_featured ? '1' : '0' }}">
                                            @if($item->is_featured)
                                                <i class="fas fa-times-circle text-warning"></i> Öne Çıkarmayı Kaldır
                                            @else
                                                <i class="fas fa-star text-warning"></i> Öne Çıkar
                                            @endif
                                        </a>
                                        <a class="dropdown-item archive-action" href="javascript:void(0);" data-id="{{ $item->id }}" data-current="{{ $item->is_archived ? '1' : '0' }}">
                                            @if($item->is_archived)
                                                <i class="fas fa-box-open text-info"></i> Arşivden Çıkar
                                            @else
                                                <i class="fas fa-archive text-secondary"></i> Arşive Taşı
                                            @endif
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item delete-action" href="javascript:void(0);" data-id="{{ $item->id }}">
                                            <i class="fas fa-trash-alt text-danger"></i> Sil
                                        </a>
                                    </div>
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
@endsection 