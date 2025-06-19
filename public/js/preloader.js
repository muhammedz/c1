// Preloader JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Body'ye preloader-active class'ı ekle
    document.body.classList.add('preloader-active');
    
    // Minimum gösterim süresi (0.05 saniye)
    const minShowTime = 50;
    const startTime = Date.now();
    
    // Sayfa tamamen yüklendiğinde preloader'ı gizle
    window.addEventListener('load', function() {
        const loadTime = Date.now();
        const elapsedTime = loadTime - startTime;
        
        // Minimum süre dolmadıysa bekle
        const remainingTime = Math.max(0, minShowTime - elapsedTime);
        
        setTimeout(function() {
            hidePreloader();
        }, remainingTime);
    });
    
    // Preloader'ı gizleme fonksiyonu
    function hidePreloader() {
        const preloader = document.getElementById('preloader');
        if (preloader) {
            // Animasyon yok - direk kaldır
            preloader.remove();
            document.body.classList.remove('preloader-active');
        }
    }
    
    // Fallback: 5 saniye sonra mutlaka gizle
    setTimeout(function() {
        hidePreloader();
    }, 5000);
}); 