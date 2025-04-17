/**
 * Resim URL'lerini düzeltmek için yardımcı fonksiyon
 */
(function() {
    // DOM yüklendiğinde çalış
    document.addEventListener('DOMContentLoaded', function() {
        // Tüm resimleri bul
        const images = document.querySelectorAll('img');
        
        // Her resim için kontrol et
        images.forEach(function(img) {
            const src = img.getAttribute('src');
            
            // storage/photos/ ile başlayan resim URL'lerini düzelt
            if (src && src.match(/^\/?storage\/photos\//)) {
                // /storage/ yolundan /test_image.php?path= yoluna çevir
                const newSrc = '/test_image.php?path=' + src.replace(/^\/?storage\//, '');
                img.setAttribute('src', newSrc);
            }
            
            // Data-src özelliği varsa (lazy loading için) onu da düzelt
            const dataSrc = img.getAttribute('data-src');
            if (dataSrc && dataSrc.match(/^\/?storage\/photos\//)) {
                const newDataSrc = '/test_image.php?path=' + dataSrc.replace(/^\/?storage\//, '');
                img.setAttribute('data-src', newDataSrc);
            }
        });
        
        // background-image kullanılan elementleri bul ve düzelt
        const allElements = document.querySelectorAll('*');
        allElements.forEach(function(el) {
            const style = window.getComputedStyle(el);
            const bgImage = style.backgroundImage;
            
            if (bgImage && bgImage.includes('storage/photos/')) {
                // Inline style olarak düzeltme ekle
                const urlMatch = bgImage.match(/url\(['"]?(.*?)['"]?\)/);
                if (urlMatch && urlMatch[1].match(/storage\/photos\//)) {
                    const oldUrl = urlMatch[1];
                    const newUrl = '/test_image.php?path=' + oldUrl.replace(/^\/?storage\//, '');
                    el.style.backgroundImage = `url('${newUrl}')`;
                }
            }
        });
    });
})(); 