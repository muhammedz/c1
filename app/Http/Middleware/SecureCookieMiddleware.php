<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Çerez Güvenlik Kontrol Middleware'i
 * 
 * Tüm response'larda çerezlerin güvenlik bayraklarının
 * doğru şekilde ayarlandığını kontrol eder ve zorlar.
 */
class SecureCookieMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // Response'daki tüm çerezleri kontrol et ve güvenli hale getir
        $this->secureCookies($response);
        
        return $response;
    }
    
    /**
     * Response'daki çerezleri güvenli hale getir
     *
     * @param Response $response
     * @return void
     */
    private function secureCookies(Response $response): void
    {
        $cookies = $response->headers->getCookies();
        
        // Mevcut çerezleri temizle
        $response->headers->removeCookie(null);
        
        foreach ($cookies as $cookie) {
            // Güvenlik bayrakları ile yeni çerez oluştur
            $secureCookie = cookie(
                $cookie->getName(),                    // name
                $cookie->getValue(),                   // value
                $this->calculateMinutes($cookie),      // minutes
                $cookie->getPath(),                    // path
                $cookie->getDomain(),                  // domain
                true,                                  // secure (HTTPS zorunlu)
                true,                                  // httpOnly (JavaScript erişimi engelle)
                $cookie->isRaw(),                      // raw
                'strict'                               // sameSite (maksimum güvenlik)
            );
            
            $response = $response->cookie($secureCookie);
        }
    }
    
    /**
     * Cookie expire time'ı dakikaya çevir
     *
     * @param \Symfony\Component\HttpFoundation\Cookie $cookie
     * @return int
     */
    private function calculateMinutes($cookie): int
    {
        $expiresTime = $cookie->getExpiresTime();
        
        if ($expiresTime === 0) {
            return 0; // Session cookie
        }
        
        $currentTime = time();
        $diffInSeconds = $expiresTime - $currentTime;
        
        return max(0, intval($diffInSeconds / 60));
    }
} 