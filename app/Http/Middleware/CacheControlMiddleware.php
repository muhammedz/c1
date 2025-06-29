<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CacheControlMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Sadece başarılı yanıtlar için önbellek başlıkları ekle
        if ($response->getStatusCode() === 200) {
            $path = $request->getPathInfo();
            
            // Font dosyaları için 1 yıl önbellek
            if (preg_match('/\.(ttf|otf|woff|woff2|eot)$/i', $path)) {
                $response->headers->set('Cache-Control', 'public, max-age=31536000, immutable');
                $response->headers->set('Expires', gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');
            }
            // Resimler için 1 ay önbellek
            elseif (preg_match('/\.(jpg|jpeg|png|gif|webp|svg|ico)$/i', $path)) {
                $response->headers->set('Cache-Control', 'public, max-age=2592000');
                $response->headers->set('Expires', gmdate('D, d M Y H:i:s', time() + 2592000) . ' GMT');
            }
            // CSS ve JS dosyaları için 1 hafta önbellek
            elseif (preg_match('/\.(css|js)$/i', $path)) {
                $response->headers->set('Cache-Control', 'public, max-age=604800');
                $response->headers->set('Expires', gmdate('D, d M Y H:i:s', time() + 604800) . ' GMT');
            }
            // PDF dosyaları için 1 gün önbellek
            elseif (preg_match('/\.(pdf)$/i', $path)) {
                $response->headers->set('Cache-Control', 'public, max-age=86400');
                $response->headers->set('Expires', gmdate('D, d M Y H:i:s', time() + 86400) . ' GMT');
            }
        }

        return $response;
    }
}
