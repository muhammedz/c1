<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Services\HeaderService;

class HeaderServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(HeaderService::class, function ($app) {
            return new HeaderService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Header bilgilerini tüm sayfalara ekle
        View::composer(['partials.header'], function ($view) {
            $headerService = app(HeaderService::class);
            $view->with('headerSettings', $headerService->getHeaderSettings());
            $view->with('mainMenuItems', $headerService->getMainMenuItems());
        });
    }
} 