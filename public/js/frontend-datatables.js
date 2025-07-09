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
        
        // DataTables'ı başlat - Zero Configuration benzeri
        $table.DataTable({
            // Sayfalama kapalı
            paging: false,
            
            // Arama
            searching: true,
            
            // Sıralama
            ordering: true,
            
            // Bilgi göstergesi kapalı - daha temiz görünüm
            info: false,
            
            // Basit DOM yapısı
            dom: 'ft',
            
            // Türkçe dil desteği
            language: {
                "emptyTable": "Tabloda herhangi bir veri mevcut değil",
                "loadingRecords": "Yükleniyor...",
                "processing": "İşleniyor...",
                "search": "Ara:",
                "zeroRecords": "Eşleşen kayıt bulunamadı",
                "aria": {
                    "sortAscending": ": artan sütun sıralamasını aktifleştir",
                    "sortDescending": ": azalan sütun sıralamasını aktifleştir"
                }
            },
            
            // Callback fonksiyonları
            initComplete: function() {
                // DataTables başlatıldıktan sonra basit styling uygula
                applySimpleStyling($table);
            }
        });
        
    } catch (error) {
        console.log('DataTables uygulanamadı:', error);
    }
}

/**
 * Basit CSS Styling Uygula - Zero Configuration benzeri
 */
function applySimpleStyling($table) {
    const $wrapper = $table.closest('.dataTables_wrapper');
    
    // Wrapper'a basit class ekle
    $wrapper.addClass('simple-datatable');
    
    // Search input'a placeholder ekle
    $wrapper.find('input[type="search"]').attr('placeholder', 'Ara...');
    
    // Search styling - daha minimal
    $wrapper.find('.dataTables_filter').addClass('mb-3');
    $wrapper.find('input[type="search"]').addClass('form-control form-control-sm');
}



/**
 * Window resize event'i dinle - basit column adjustment
 */
let resizeTimeout;
$(window).on('resize', function() {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(function() {
        $('.dataTables_wrapper table').each(function() {
            const $table = $(this);
            
            // DataTable varsa sadece column adjustment yap
            if ($.fn.DataTable && $.fn.DataTable.isDataTable($table)) {
                $table.DataTable().columns.adjust();
            }
        });
    }, 150);
}); 