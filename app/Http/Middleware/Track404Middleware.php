<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\NotFoundLog;
use Illuminate\Support\Facades\Cache;

class Track404Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Sadece 404 hatalarını takip et
        if ($response->getStatusCode() === 404) {
            $this->log404Error($request);
        }

        return $response;
    }

    /**
     * 404 hatasını logla
     */
    private function log404Error(Request $request)
    {
        try {
            // Admin paneli ve API isteklerini hariç tut
            if ($this->shouldIgnoreRequest($request)) {
                return;
            }

            $url = $request->getPathInfo();
            $referer = $request->header('referer');
            $userAgent = $request->header('user-agent');
            $ipAddress = $request->ip();

            // Cache kullanarak aynı URL'nin çok sık loglanmasını engelle
            $cacheKey = 'track_404_' . md5($url . $ipAddress);
            
            if (!Cache::has($cacheKey)) {
                // 5 dakika boyunca aynı IP'den aynı URL için log oluşturma
                Cache::put($cacheKey, true, 300);
                
                // Asenkron olarak log kaydet
                dispatch(function () use ($url, $referer, $userAgent, $ipAddress) {
                    NotFoundLog::logNotFound($url, $referer, $userAgent, $ipAddress);
                })->afterResponse();
            }
        } catch (\Exception $e) {
            // Hata durumunda sessizce devam et
            \Log::error('Track404Middleware error: ' . $e->getMessage());
        }
    }

    /**
     * İsteğin göz ardı edilip edilmeyeceğini kontrol et
     */
    private function shouldIgnoreRequest(Request $request): bool
    {
        $url = $request->getPathInfo();
        
        // Göz ardı edilecek URL'ler
        $ignoredPaths = [
            '/admin',
            '/api',
            '/favicon.ico',
            '/robots.txt',
            '/sitemap.xml',
            '/.well-known',
            '/wp-admin', // WordPress bot istekleri
            '/wp-content',
            '/wp-includes',
        ];

        foreach ($ignoredPaths as $path) {
            if (str_starts_with($url, $path)) {
                return true;
            }
        }

        // Dosya uzantıları (resim, css, js vb.)
        $ignoredExtensions = ['.css', '.js', '.png', '.jpg', '.jpeg', '.gif', '.svg', '.ico', '.woff', '.woff2', '.ttf'];
        
        foreach ($ignoredExtensions as $ext) {
            if (str_ends_with($url, $ext)) {
                return true;
            }
        }

        // Bot isteklerini filtrele
        $userAgent = $request->header('user-agent', '');
        $botPatterns = ['bot', 'crawler', 'spider', 'scraper'];
        
        foreach ($botPatterns as $pattern) {
            if (stripos($userAgent, $pattern) !== false) {
                return true;
            }
        }

        return false;
    }
}
