<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\News;
use App\Observers\NewsObserver;
use Illuminate\Support\Facades\Blade;
use App\Helpers\ImageHelper;
use App\Helpers\SlugHelper;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\View;
use App\Models\Setting;

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
        
        // Slug helper için Blade directive'leri
        Blade::directive('slugHelper', function () {
            return "<?php echo App\Helpers\SlugHelper::getJsSlugFunction(); ?>";
        });
        
        Blade::directive('turkishCharMap', function () {
            return "<?php echo App\Helpers\SlugHelper::getTurkishCharMapForJs(); ?>";
        });

        // NOTE: Laravel File Manager kodları kaldırıldı
        // \Illuminate\Support\Facades\URL::forceRootUrl(config('app.url'));
        
        // Laravel File Manager lfm.api.response event listener kaldırıldı
        
        // File Manager için yolları düzelt
        // Uploads klasörünün varlığını kontrol et ve yoksa oluştur
        $uploadsPaths = [
            public_path('uploads'),
            public_path('uploads/photos'),
            public_path('uploads/photos/1'),
            public_path('uploads/photos/shares'),
        ];
        
        foreach ($uploadsPaths as $path) {
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            } else {
                chmod($path, 0777);
            }
        }

        // JSON_UNESCAPED_UNICODE ekle - Türkçe karakterlerin düzgün görüntülenmesi için
        JsonResource::withoutWrapping();
        
        // JSON kodlama ayarlarını düzenle
        config(['app.json_encoding_options' => JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES]);

        // AdminLTE sayfalarına favicon bilgisini gönder
        View::composer(['adminlte::page', 'adminlte::master'], function ($view) {
            try {
                $siteFavicon = Setting::where('key', 'site_favicon')->first();
                $view->with('siteFavicon', $siteFavicon);
                
                // Eğer özel favicon varsa, AdminLTE'nin varsayılan favicon'unu devre dışı bırak
                if ($siteFavicon && $siteFavicon->value) {
                    $view->with('customFaviconUrl', asset('uploads/' . $siteFavicon->value));
                }
            } catch (\Exception $e) {
                $view->with('siteFavicon', null);
                $view->with('customFaviconUrl', null);
            }
        });

        // Frontend sayfalarına da favicon bilgisini gönder
        View::composer('layouts.front', function ($view) {
            try {
                $siteFavicon = Setting::where('key', 'site_favicon')->first();
                $view->with('siteFavicon', $siteFavicon);
            } catch (\Exception $e) {
                $view->with('siteFavicon', null);
            }
        });
    }
}
