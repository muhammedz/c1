<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use UniSharp\LaravelFilemanager\Handlers\LfmPathHelper;

class FileManagerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Laravel File Manager'ın path helper'ını override et
        $this->app->bind(LfmPathHelper::class, function () {
            return new \App\Services\FileManager\CustomLfmPathHelper;
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
} 