@php
// Bu dosya artık kullanılmıyor. Tüm helper fonksiyonları App\Helpers namespace'inde tanımlanmıştır.
// fixImageUrl() fonksiyonu için \App\Helpers\ImageHelper::fixImageUrl() kullanın
// veya Blade direktifi için @fixImageUrl() kullanın.

// Görsel URL'lerini düzeltmek için yardımcı fonksiyonlar
if (!function_exists('fixImageUrl')) {
    /**
     * Storage resimleri için URL düzeltme
     */
    function fixImageUrl($path) {
        if (empty($path)) return '';
        
        // /storage/ ifadesini kaldır
        $path = preg_replace('/^\/?storage\//', '', $path);
        
        return url('/test_image.php?path=' . $path);
    }
}

if (!function_exists('storageUrl')) {
    /**
     * Storage URL oluşturma
     */
    function storageUrl($path) {
        return url('/test_image.php?path=' . $path);
    }
}
@endphp 