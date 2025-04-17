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
    }
}
