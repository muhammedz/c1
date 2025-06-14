<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Redirect;
use Illuminate\Support\Facades\Cache;

class RedirectMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Admin paneli isteklerini hariç tut
        if ($request->is('admin/*') || $request->is('api/*')) {
            return $next($request);
        }

        $currentUrl = $request->getPathInfo();
        
        // Cache'den yönlendirme kuralını kontrol et
        $redirect = $this->findRedirect($currentUrl);
        
        if ($redirect) {
            // Hit count artır (asenkron)
            dispatch(function () use ($redirect) {
                $redirect->incrementHitCount();
            })->afterResponse();
            
            // Yönlendirme yap
            $statusCode = $redirect->redirect_type === '301' ? 301 : 302;
            return redirect($redirect->to_url, $statusCode);
        }

        return $next($request);
    }

    /**
     * Yönlendirme kuralını bul (cache ile)
     */
    private function findRedirect($url): ?Redirect
    {
        $cacheKey = 'redirect_' . md5($url);
        
        return Cache::remember($cacheKey, 3600, function () use ($url) {
            return Redirect::findByUrl($url);
        });
    }
}
