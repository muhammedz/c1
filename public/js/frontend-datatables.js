/**
 * Frontend DataTables Entegrasyonu
 * TinyMCE ile oluşturulan tablolar için otomatik DataTables uygulaması
 */

$(document).ready(function() {
    
    // Sayfa yüklendiğinde tüm tabloları DataTables ile aktif et
    initDataTables();
    
    // TinyMCE içerik değişikliklerini dinle (AJAX içerik yüklemesi için)
    $(document).on('DOMNodeInserted', function() {
        initDataTables();
    });
    
});

/**
 * DataTables Başlatma Fonksiyonu
 */
function initDataTables() {
    // Prose container içindeki tabloları bul
    $('.prose table, .mce-content-body table, article table').each(function() {
        const $table = $(this);
        
        // Zaten DataTables uygulanmış mı kontrol et
        if ($table.hasClass('dataTable')) {
            return;
        }
        
        // Minimum 2 satır varsa DataTables uygula (header + 1 data row)
        if ($table.find('tr').length >= 2) {
            applyDataTables($table);
        }
    });
}

/**
 * Tabloya DataTables Uygula
 */
function applyDataTables($table) {
    try {
        // DataTables wrapper'ı oluştur
        $table.wrap('<div class="datatable-wrapper"></div>');
        
        // DataTables'ı başlat
        $table.DataTable({
            // Responsive özellikler
            responsive: true,
            autoWidth: false,
            
            // Sayfalama
            paging: true,
            pageLength: 10,
            lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Tümü"]],
            
            // Arama
            searching: true,
            
            // Sıralama
            ordering: true,
            
            // Bilgi göstergesi
            info: true,
            
            // DOM yapısı - mobil uyumlu
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                 '<"row"<"col-sm-12"t>>' +
                 '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
            
            // Türkçe dil desteği
            language: {
                "emptyTable": "Tabloda herhangi bir veri mevcut değil",
                "info": "_TOTAL_ kayıttan _START_ - _END_ arasındaki kayıtlar gösteriliyor",
                "infoEmpty": "Kayıt yok",
                "infoFiltered": "(_MAX_ kayıt içerisinden bulunan)",
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
                }
            },
            
            // Mobil responsive ayarları - daha iyi mobil deneyim
            responsive: {
                details: {
                    type: 'inline',
                    target: 'tr',
                    renderer: function (api, rowIdx, columns) {
                        var data = $.map(columns, function (col, i) {
                            return col.hidden ?
                                '<div class="mobile-detail-row">' +
                                '<span class="mobile-detail-title">' + col.title + ':</span> ' +
                                '<span class="mobile-detail-data">' + col.data + '</span>' +
                                '</div>' :
                                '';
                        }).join('');
                        
                        return data ? 
                            '<div class="mobile-details-container">' + data + '</div>' : 
                            false;
                    }
                },
                breakpoints: [
                    { name: 'bigdesktop', width: Infinity },
                    { name: 'meddesktop', width: 1480 },
                    { name: 'smalldesktop', width: 1280 },
                    { name: 'medium', width: 1024 },
                    { name: 'tablet', width: 768 },
                    { name: 'fablet', width: 480 },
                    { name: 'phone', width: 320 }
                ]
            },
            
            // Sütun tanımları - mobil için optimize
            columnDefs: [
                {
                    // İlk sütunu mobile-first yapmak için
                    className: 'all',
                    targets: 0
                },
                {
                    // Diğer sütunlar için responsive sınıflar
                    className: 'tablet-l',
                    targets: [1, 2]
                },
                {
                    // En az önemli sütunlar sadece desktop'ta
                    className: 'desktop',
                    targets: '_all'
                }
            ],
            
            // Callback fonksiyonları
            initComplete: function() {
                // DataTables başlatıldıktan sonra custom styling uygula
                applyCustomStyling($table);
            },
            
            drawCallback: function() {
                // Her çizim sonrası mobil optimizasyonu
                optimizeForMobile($table);
            }
        });
        
    } catch (error) {
        console.log('DataTables uygulanamadı:', error);
    }
}

/**
 * Custom CSS Styling Uygula
 */
function applyCustomStyling($table) {
    const $wrapper = $table.closest('.dataTables_wrapper');
    
    // Wrapper'a custom class ekle
    $wrapper.addClass('frontend-datatable');
    
    // Search input'a placeholder ekle
    $wrapper.find('input[type="search"]').attr('placeholder', 'Tabloda ara...');
    
    // Length select'e responsive class ekle
    $wrapper.find('select').addClass('form-select form-select-sm');
    
    // Pagination'a custom class ekle
    $wrapper.find('.paginate_button').addClass('btn btn-sm');
}

/**
 * Mobil Optimizasyon
 */
function optimizeForMobile($table) {
    const $wrapper = $table.closest('.dataTables_wrapper');
    
    if ($(window).width() <= 768) {
        // Mobil görünüm için class ekle
        $wrapper.addClass('mobile-view');
        
        // Mobil için filter ve length'i üst üste diz
        $wrapper.find('.dataTables_length, .dataTables_filter').parent()
            .removeClass('col-md-6')
            .addClass('col-12 mb-3');
        
        // Info ve pagination'ı da üst üste diz
        $wrapper.find('.dataTables_info, .dataTables_paginate').parent()
            .removeClass('col-md-5 col-md-7')
            .addClass('col-12');
        
        // Info'yu merkeze al
        $wrapper.find('.dataTables_info').addClass('text-center mb-3');
        
        // Pagination'ı merkeze al
        $wrapper.find('.dataTables_paginate').addClass('text-center');
        
        // Length select'i küçült
        $wrapper.find('.dataTables_length select').addClass('form-select-sm');
        
        // Search input'u tam genişlik yap
        $wrapper.find('.dataTables_filter input').addClass('w-100');
        
        // Tablo wrapper'ına scroll indicator ekle
        if (!$wrapper.find('.scroll-indicator').length) {
            $wrapper.prepend('<div class="scroll-indicator">← Kaydırarak daha fazla sütun görebilirsiniz →</div>');
        }
        
    } else {
        // Desktop görünümü geri yükle
        $wrapper.removeClass('mobile-view');
        $wrapper.find('.scroll-indicator').remove();
        
        // Desktop layout'u geri yükle
        $wrapper.find('.dataTables_length').parent()
            .removeClass('col-12 mb-3')
            .addClass('col-md-6');
        
        $wrapper.find('.dataTables_filter').parent()
            .removeClass('col-12 mb-3')
            .addClass('col-md-6');
        
        $wrapper.find('.dataTables_info').parent()
            .removeClass('col-12')
            .addClass('col-md-5')
            .find('.dataTables_info')
            .removeClass('text-center mb-3');
        
        $wrapper.find('.dataTables_paginate').parent()
            .removeClass('col-12')
            .addClass('col-md-7')
            .find('.dataTables_paginate')
            .removeClass('text-center');
    }
}

/**
 * Window resize event'i dinle - throttle ile optimize edilmiş
 */
let resizeTimeout;
$(window).on('resize', function() {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(function() {
        $('.dataTables_wrapper table').each(function() {
            const $table = $(this);
            optimizeForMobile($table);
            
            // DataTable varsa responsive recalculation yap
            if ($.fn.DataTable && $.fn.DataTable.isDataTable($table)) {
                $table.DataTable().columns.adjust().responsive.recalc();
            }
        });
    }, 150);
}); 