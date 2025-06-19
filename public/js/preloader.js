// Preloader JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Body'ye preloader-active class'ı ekle
    document.body.classList.add('preloader-active');
    
    // Minimum gösterim süresi (0.2 saniye)
    const minShowTime = 200;
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
            preloader.classList.add('fade-out');
            
            // Animasyon tamamlandıktan sonra elementi kaldır
            setTimeout(function() {
                preloader.remove();
                document.body.classList.remove('preloader-active');
            }, 500);
        }
    }
    
    // Fallback: 5 saniye sonra mutlaka gizle
    setTimeout(function() {
        hidePreloader();
    }, 5000);
}); 