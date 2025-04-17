<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\News;
use App\Observers\NewsObserver;
use Illuminate\Support\Facades\Blade;
use App\Helpers\ImageHelper;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        
        // Observer sınıflarını kaydet
        News::observe(NewsObserver::class);
        
        // fixImageUrl fonksiyonunu Blade içinde kullanılabilir hale getir
        Blade::directive('fixImageUrl', function ($expression) {
            return "<?php echo App\Helpers\ImageHelper::fixImageUrl($expression); ?>";
        });

        // Laravel File Manager entegrasyonu için public disk URL'lerini düzelt
        \Illuminate\Support\Facades\URL::forceRootUrl(config('app.url'));
        
        // Laravel File Manager'ın yanıt vermeden önce URL dönüşümü yapmasını sağla
        \Event::listen('lfm.api.response', function ($response) {
            if (isset($response['result'])) {
                // URL'deki storage yollarını uploads olarak değiştir
                if (isset($response['result']['result']) && !empty($response['result']['result'])) {
                    $url = $response['result']['result'];
                    if (is_string($url) && strpos($url, '/storage/') !== false) {
                        $response['result']['result'] = str_replace('/storage/', '/uploads/', $url);
                    }
                }
            }
            return $response;
        });
    }
}
