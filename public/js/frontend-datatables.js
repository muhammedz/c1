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
            // Table width and layout
            autoWidth: false,
            
            // Vertical Scrolling - mobilde de aynı görünüm
            scrollY: $(window).width() <= 768 ? '40vh' : '50vh',
            scrollCollapse: true,
            
            // Sayfalama kapalı - scroll kullanacağız
            paging: false,
            
            // Arama
            searching: true,
            
            // Sıralama
            ordering: true,
            
            // Bilgi göstergesi
            info: true,
            
            // DOM yapısı - basit layout
            dom: '<"row"<"col-sm-12 col-md-6"f><"col-sm-12 col-md-6 text-end"i>>' +
                 '<"row"<"col-sm-12"t>>',
            
            // Türkçe dil desteği
            language: {
                "emptyTable": "Tabloda herhangi bir veri mevcut değil",
                "info": "_TOTAL_ kayıt gösteriliyor",
                "infoEmpty": "Kayıt yok",
                "infoFiltered": "(_MAX_ kayıt içerisinden bulunan)",
                "loadingRecords": "Yükleniyor...",
                "processing": "İşleniyor...",
                "search": "Tabloda ara:",
                "searchPlaceholder": "Arama yapın...",
                "zeroRecords": "Eşleşen kayıt bulunamadı",
                "aria": {
                    "sortAscending": ": artan sütun sıralamasını aktifleştir",
                    "sortDescending": ": azalan sütun sıralamasını aktifleştir"
                }
            },
            
            // Callback fonksiyonları
            initComplete: function() {
                // DataTables başlatıldıktan sonra custom styling uygula
                applyCustomStyling($table);
                // Scroll container optimizasyonu
                optimizeScrollContainer($table);
            },
            
            drawCallback: function() {
                // Her çizim sonrası scroll optimizasyonu
                optimizeScrollContainer($table);
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
    
    // Search input styling
    $wrapper.find('input[type="search"]').addClass('form-control');
    
    // Info container styling
    $wrapper.find('.dataTables_info').addClass('small text-muted');
}

/**
 * Scroll Container Optimizasyonu
 */
function optimizeScrollContainer($table) {
    const $wrapper = $table.closest('.dataTables_wrapper');
    const isMobile = $(window).width() <= 768;
    
    if (isMobile) {
        // Mobil görünüm için class ekle
        $wrapper.addClass('mobile-scroll-view');
        
        // Mobil için controls'ı üst üste diz
        $wrapper.find('.dataTables_filter').parent()
            .removeClass('col-md-6')
            .addClass('col-12 mb-3');
        
        $wrapper.find('.dataTables_info').parent()
            .removeClass('col-md-6 text-end')
            .addClass('col-12 text-center');
        
        // Search input'u tam genişlik yap
        $wrapper.find('.dataTables_filter input').addClass('w-100');
        
        // Scroll hint ekle
        if (!$wrapper.find('.scroll-hint').length) {
            $wrapper.find('.dataTables_scrollBody').before(
                '<div class="scroll-hint">↔ Yatay kaydırarak tüm sütunları görebilirsiniz</div>'
            );
        }
        
        // Scroll body styling
        const $scrollBody = $wrapper.find('.dataTables_scrollBody');
        $scrollBody.css({
            'border': '1px solid #dee2e6',
            'border-radius': '8px',
            'background': 'white'
        });
        
    } else {
        // Desktop görünümü geri yükle
        $wrapper.removeClass('mobile-scroll-view');
        $wrapper.find('.scroll-hint').remove();
        
        // Desktop layout'u geri yükle
        $wrapper.find('.dataTables_filter').parent()
            .removeClass('col-12 mb-3')
            .addClass('col-md-6');
        
        $wrapper.find('.dataTables_info').parent()
            .removeClass('col-12 text-center')
            .addClass('col-md-6 text-end');
            
        $wrapper.find('.dataTables_filter input').removeClass('w-100');
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
            optimizeScrollContainer($table);
            
            // DataTable varsa scroll height'ı güncelle
            if ($.fn.DataTable && $.fn.DataTable.isDataTable($table)) {
                const newScrollY = $(window).width() <= 768 ? '40vh' : '50vh';
                $table.DataTable().settings()[0].oScroll.sY = newScrollY;
                $table.DataTable().columns.adjust().draw();
            }
        });
    }, 150);
}); 