<?php

return [
    /*
    |--------------------------------------------------------------------------
    | API Anahtarları
    |--------------------------------------------------------------------------
    |
    | Bu bölümde API anahtarlarınızı tanımlayabilirsiniz. Bu anahtarlar,
    | ApiKeyMiddleware tarafından doğrulanacak anahtarlardır.
    |
    */

    'keys' => [
        env('API_KEY', 'default-api-key'),
        'test-key',
    ],

    /*
    |--------------------------------------------------------------------------
    | API Rate Limiting
    |--------------------------------------------------------------------------
    |
    | Bu ayarlar API isteklerinin hız sınırlamasını belirler.
    | maxAttempts: Belirtilen süre içinde izin verilen maksimum istek sayısı
    | decayMinutes: İstek sayacının sıfırlanması için gereken süre (dakika)
    |
    */

    'throttle' => [
        'maxAttempts' => env('API_THROTTLE_MAX_ATTEMPTS', 60),
        'decayMinutes' => env('API_THROTTLE_DECAY_MINUTES', 1),
    ],

    /*
    |--------------------------------------------------------------------------
    | API Sürüm Bilgisi
    |--------------------------------------------------------------------------
    |
    | API sürüm bilgileri
    |
    */

    'version' => '1.0',
    'name' => 'News API',
    'description' => 'Haber sistemi için API servisi',
]; 