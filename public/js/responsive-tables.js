/**
 * RESPONSIVE TABLES JAVASCRIPT
 * Mobil cihazlarda tablo optimizasyonu için yardımcı fonksiyonlar
 */

class ResponsiveTableHelper {
    constructor() {
        this.init();
    }

    init() {
        // Sayfa yüklendiğinde
        document.addEventListener('DOMContentLoaded', () => {
            this.makeTablesResponsive();
            this.addScrollIndicators();
            this.addTableUtilities();
        });

        // Pencere boyutu değiştiğinde
        window.addEventListener('resize', () => {
            this.handleResize();
        });
    }

    /**
     * Tüm tabloları responsive yapar
     */
    makeTablesResponsive() {
        const tables = document.querySelectorAll('table:not(.no-responsive)');
        
        tables.forEach(table => {
            this.wrapTable(table);
            this.addMobileLabels(table);
            this.optimizeForMobile(table);
        });
    }

    /**
     * Tabloyu responsive wrapper ile sarar
     */
    wrapTable(table) {
        // Zaten sarılmışsa atla
        if (table.closest('.table-responsive-mobile')) {
            return;
        }

        const wrapper = document.createElement('div');
        wrapper.className = 'table-responsive-mobile';
        
        // Tablo sınıflarını ekle
        if (!table.classList.contains('responsive-table')) {
            table.classList.add('responsive-table');
        }

        // Tabloyu sar
        table.parentNode.insertBefore(wrapper, table);
        wrapper.appendChild(table);
    }

    /**
     * Mobil cihazlar için data-label attribute'ları ekler
     */
    addMobileLabels(table) {
        const headers = table.querySelectorAll('thead th');
        const rows = table.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            cells.forEach((cell, index) => {
                if (headers[index]) {
                    const headerText = headers[index].textContent.trim();
                    cell.setAttribute('data-label', headerText);
                }
            });
        });
    }

    /**
     * Mobil optimizasyonları uygular
     */
    optimizeForMobile(table) {
        // Önemli sütunları işaretle
        this.markImportantColumns(table);
        
        // Aksiyon butonlarını optimize et
        this.optimizeActionButtons(table);
        
        // Uzun metinleri kırp
        this.truncateLongText(table);
    }

    /**
     * Önemli sütunları işaretler
     */
    markImportantColumns(table) {
        const importantKeywords = ['başlık', 'ad', 'isim', 'konu', 'title', 'name', 'subject'];
        const headers = table.querySelectorAll('thead th');
        
        headers.forEach((header, index) => {
            const headerText = header.textContent.toLowerCase();
            const isImportant = importantKeywords.some(keyword => 
                headerText.includes(keyword)
            );
            
            if (isImportant) {
                header.classList.add('important');
                // Bu sütundaki tüm td'leri de işaretle
                const cells = table.querySelectorAll(`tbody tr td:nth-child(${index + 1})`);
                cells.forEach(cell => cell.classList.add('important'));
            }
        });
    }

    /**
     * Aksiyon butonlarını optimize eder
     */
    optimizeActionButtons(table) {
        const actionCells = table.querySelectorAll('td');
        
        actionCells.forEach(cell => {
            const buttons = cell.querySelectorAll('.btn, button, a.btn');
            if (buttons.length > 0) {
                cell.classList.add('action-buttons');
                
                // Butonları küçült
                buttons.forEach(btn => {
                    if (!btn.classList.contains('btn-sm')) {
                        btn.classList.add('btn-sm');
                    }
                });
            }
        });
    }

    /**
     * Uzun metinleri kırpar
     */
    truncateLongText(table) {
        const cells = table.querySelectorAll('td:not(.important):not(.action-buttons)');
        
        cells.forEach(cell => {
            const text = cell.textContent.trim();
            if (text.length > 50) {
                cell.setAttribute('title', text);
                cell.setAttribute('data-toggle', 'tooltip');
            }
        });
    }

    /**
     * Scroll göstergeleri ekler
     */
    addScrollIndicators() {
        const wrappers = document.querySelectorAll('.table-responsive-mobile');
        
        wrappers.forEach(wrapper => {
            wrapper.addEventListener('scroll', () => {
                const isAtStart = wrapper.scrollLeft === 0;
                const isAtEnd = wrapper.scrollLeft >= (wrapper.scrollWidth - wrapper.clientWidth - 1);
                
                wrapper.classList.toggle('scroll-start', isAtStart);
                wrapper.classList.toggle('scroll-end', isAtEnd);
            });
        });
    }

    /**
     * Tablo yardımcı araçları ekler
     */
    addTableUtilities() {
        const tables = document.querySelectorAll('.responsive-table');
        
        tables.forEach(table => {
            this.addTableSearch(table);
            this.addTableSort(table);
            this.addTableExport(table);
        });
    }

    /**
     * Tablo arama özelliği ekler
     */
    addTableSearch(table) {
        // Sadece istenirse arama ekle
        if (!table.hasAttribute('data-searchable')) return;

        const wrapper = table.closest('.table-responsive-mobile');
        if (!wrapper) return;

        const searchContainer = document.createElement('div');
        searchContainer.className = 'table-search-container mb-3';
        searchContainer.innerHTML = `
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Tabloda ara..." id="search-${this.generateId()}">
                <div class="input-group-append">
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>
                </div>
            </div>
        `;

        wrapper.parentNode.insertBefore(searchContainer, wrapper);

        const searchInput = searchContainer.querySelector('input');
        searchInput.addEventListener('input', (e) => {
            this.filterTable(table, e.target.value);
        });
    }

    /**
     * Tablo filtreleme
     */
    filterTable(table, searchTerm) {
        const rows = table.querySelectorAll('tbody tr');
        const term = searchTerm.toLowerCase();

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const show = text.includes(term);
            row.style.display = show ? '' : 'none';
        });
    }

    /**
     * Sıralama özelliği ekler
     */
    addTableSort(table) {
        if (!table.hasAttribute('data-sortable')) return;

        const headers = table.querySelectorAll('thead th');
        
        headers.forEach((header, index) => {
            if (header.hasAttribute('data-no-sort')) return;

            header.classList.add('sortable-table');
            header.style.cursor = 'pointer';
            
            header.addEventListener('click', () => {
                this.sortTable(table, index);
            });
        });
    }

    /**
     * Tablo sıralama
     */
    sortTable(table, columnIndex) {
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        const header = table.querySelector(`thead th:nth-child(${columnIndex + 1})`);
        
        // Sıralama yönünü belirle
        const isAsc = !header.classList.contains('sorted-asc');
        
        // Tüm header'ları temizle
        table.querySelectorAll('thead th').forEach(th => {
            th.classList.remove('sorted-asc', 'sorted-desc');
        });
        
        // Mevcut header'ı işaretle
        header.classList.add(isAsc ? 'sorted-asc' : 'sorted-desc');
        
        // Satırları sırala
        rows.sort((a, b) => {
            const aVal = a.cells[columnIndex].textContent.trim();
            const bVal = b.cells[columnIndex].textContent.trim();
            
            // Sayısal karşılaştırma
            if (!isNaN(aVal) && !isNaN(bVal)) {
                return isAsc ? aVal - bVal : bVal - aVal;
            }
            
            // Metin karşılaştırması
            return isAsc ? 
                aVal.localeCompare(bVal, 'tr') : 
                bVal.localeCompare(aVal, 'tr');
        });
        
        // Satırları yeniden ekle
        rows.forEach(row => tbody.appendChild(row));
    }

    /**
     * Excel export özelliği
     */
    addTableExport(table) {
        if (!table.hasAttribute('data-exportable')) return;

        const wrapper = table.closest('.table-responsive-mobile');
        if (!wrapper) return;

        const exportBtn = document.createElement('button');
        exportBtn.className = 'btn btn-outline-success btn-sm mb-2';
        exportBtn.innerHTML = '<i class="fas fa-download"></i> Excel İndir';
        exportBtn.onclick = () => this.exportToExcel(table);

        wrapper.parentNode.insertBefore(exportBtn, wrapper);
    }

    /**
     * Excel export
     */
    exportToExcel(table) {
        // Basit CSV export
        let csv = '';
        const rows = table.querySelectorAll('tr');
        
        rows.forEach(row => {
            const cells = row.querySelectorAll('th, td');
            const rowData = Array.from(cells).map(cell => 
                '"' + cell.textContent.trim().replace(/"/g, '""') + '"'
            );
            csv += rowData.join(',') + '\n';
        });
        
        // Download
        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        link.setAttribute('download', 'tablo_data.csv');
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    /**
     * Pencere boyutu değişimi
     */
    handleResize() {
        // Mobil kart görünümünü toggle et
        const isMobile = window.innerWidth <= 480;
        const tables = document.querySelectorAll('.responsive-table');
        
        tables.forEach(table => {
            const wrapper = table.closest('.table-responsive-mobile');
            if (wrapper) {
                wrapper.parentElement.classList.toggle('table-to-cards', isMobile);
            }
        });
    }

    /**
     * Benzersiz ID oluşturucu
     */
    generateId() {
        return Math.random().toString(36).substr(2, 9);
    }

    /**
     * Tooltip'leri başlat
     */
    initTooltips() {
        if (typeof $ !== 'undefined' && $.fn.tooltip) {
            $('[data-toggle="tooltip"]').tooltip();
        }
    }

    /**
     * TinyMCE editöründeki tabloları optimize et
     */
    optimizeTinyMCETables() {
        // TinyMCE content alanındaki tabloları bul
        const tinyMCEContent = document.querySelectorAll('.mce-content-body, .tinymce-content');
        
        tinyMCEContent.forEach(content => {
            const tables = content.querySelectorAll('table');
            tables.forEach(table => {
                // Responsive wrapper ekle
                if (!table.closest('.auto-responsive-table')) {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'auto-responsive-table';
                    table.parentNode.insertBefore(wrapper, table);
                    wrapper.appendChild(table);
                }
            });
        });
    }
}

/**
 * GLOBAL HELPER FUNCTIONS
 */

// Tek bir tabloyu responsive yap
window.makeTableResponsive = function(tableSelector) {
    const table = document.querySelector(tableSelector);
    if (table) {
        const helper = new ResponsiveTableHelper();
        helper.wrapTable(table);
        helper.addMobileLabels(table);
        helper.optimizeForMobile(table);
    }
};

// TinyMCE callback için
window.optimizeTinyMCETables = function() {
    const helper = new ResponsiveTableHelper();
    helper.optimizeTinyMCETables();
};

// Manuel olarak kart görünümüne geç
window.toggleTableCardView = function(tableSelector, enable = true) {
    const table = document.querySelector(tableSelector);
    if (table) {
        const wrapper = table.closest('.table-responsive-mobile');
        if (wrapper) {
            wrapper.parentElement.classList.toggle('table-to-cards', enable);
        }
    }
};

// Başlat
new ResponsiveTableHelper();

/**
 * TinyMCE Integration
 */
if (typeof tinymce !== 'undefined') {
    // TinyMCE editörü başlatıldığında tabloları optimize et
    tinymce.on('AddEditor', function(e) {
        e.editor.on('init', function() {
            this.on('NodeChange', function() {
                // Editör içeriği değiştiğinde tabloları kontrol et
                setTimeout(() => {
                    window.optimizeTinyMCETables();
                }, 100);
            });
        });
    });
} 