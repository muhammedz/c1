<?php

/**
 * Güvenlik Konfigürasyon Dosyası
 * 
 * Web uygulamasının güvenlik başlıklarını ve politikalarını tanımlar.
 * Production ve development ortamları için farklı ayarlar yapılabilir.
 */

return [
    
    /*
    |--------------------------------------------------------------------------
    | HTTP Güvenlik Başlıkları
    |--------------------------------------------------------------------------
    |
    | Bu başlıklar tüm HTTP yanıtlarına eklenir ve çeşitli güvenlik 
    | tehditlerine karşı koruma sağlar.
    |
    */
    
    'headers' => [
        
        /*
        | X-Frame-Options: Clickjacking saldırılarına karşı koruma
        | Değerler: DENY, SAMEORIGIN, ALLOW-FROM uri
        */
        'x_frame_options' => env('SECURITY_X_FRAME_OPTIONS', 'SAMEORIGIN'),
        
        /*
        | X-Content-Type-Options: MIME sniffing saldırılarını engeller
        | Değer: nosniff (sabit)
        */
        'x_content_type_options' => 'nosniff',
        
        /*
        | Referrer-Policy: Hangi referrer bilgisinin paylaşılacağını kontrol eder
        | Değerler: no-referrer, same-origin, strict-origin-when-cross-origin, etc.
        */
        'referrer_policy' => env('SECURITY_REFERRER_POLICY', 'strict-origin-when-cross-origin'),
        
        /*
        | Strict-Transport-Security: HTTPS kullanımını zorlar
        | Sadece HTTPS bağlantılarda gönderilir
        */
        'strict_transport_security' => env('SECURITY_HSTS', 'max-age=31536000; includeSubDomains; preload'),
        
        /*
        | Content-Security-Policy: XSS ve code injection saldırılarına karşı koruma
        | Bu değer çok kritik! Sitede JavaScript, CSS, resim vb. yüklenmesini kontrol eder
        */
        'content_security_policy' => env('SECURITY_CSP', 
            "default-src 'self'; " .
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' " .
                "https://www.googletagmanager.com " .
                "https://www.google-analytics.com " .
                "https://cdn.jsdelivr.net " .
                "https://cdn.tailwindcss.com " .
                "https://code.jquery.com " .
                "https://cdn.datatables.net " .
                "https://cdnjs.cloudflare.com " .
                "https://cdn.tiny.cloud; " .
            "style-src 'self' 'unsafe-inline' " .
                "https://fonts.googleapis.com " .
                "https://cdn.jsdelivr.net " .
                "https://cdn.tailwindcss.com " .
                "https://cdn.datatables.net " .
                "https://cdnjs.cloudflare.com; " .
            "font-src 'self' " .
                "https://fonts.gstatic.com " .
                "https://cdn.jsdelivr.net " .
                "https://cdnjs.cloudflare.com; " .
            "img-src 'self' data: https: http:; " .
            "connect-src 'self' " .
                "https://www.google-analytics.com " .
                "https://cdn.tiny.cloud; " .
            "frame-src 'self'; " .
            "object-src 'none'; " .
            "base-uri 'self';"
        ),
        
        /*
        | Test modu için CSP - Hataları rapor eder ama engellemez
        | Geliştirme sürecinde CSP kurallarını test etmek için kullanılır
        */
        'csp_report_only' => env('SECURITY_CSP_REPORT_ONLY', null),
        
        /*
        | Ek güvenlik başlıkları
        */
        'x_xss_protection' => '1; mode=block',
        'permissions_policy' => 'camera=(), microphone=(), geolocation=(self), payment=()',
        'x_dns_prefetch_control' => 'off',
    ],
    
    /*
    |--------------------------------------------------------------------------
    | CSP Rapor Endpoint'i
    |--------------------------------------------------------------------------
    |
    | Content Security Policy ihlallerinin raporlanacağı endpoint
    | Bu endpoint CSP violation raporlarını toplar ve analiz eder
    |
    */
    
    'csp_report_uri' => env('SECURITY_CSP_REPORT_URI', '/api/csp-report'),
    
    /*
    |--------------------------------------------------------------------------
    | Güvenlik Modu
    |--------------------------------------------------------------------------
    |
    | Production ortamında daha sıkı güvenlik politikaları
    | Development ortamında daha esnek ayarlar
    |
    */
    
    'strict_mode' => env('APP_ENV') === 'production',
    
]; 