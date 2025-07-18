<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Setting;

/**
 * Dinamik Session Timeout Middleware
 * 
 * Admin paneli için ayarlanmış session timeout değerini runtime'da uygular.
 * Bu sayede kullanıcılar admin panelinden timeout süresini değiştirebilir.
 */
class DynamicSessionTimeout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Sadece admin paneli için session timeout ayarlarını uygula
        if ($request->is('admin/*')) {
            try {
                // Veritabanından session timeout değerini al
                $sessionTimeout = Setting::get('session_timeout', 120);
                
                // Session lifetime'ı runtime'da güncelle
                config(['session.lifetime' => (int) $sessionTimeout]);
                
                // Session manager'ın timeout değerini de güncelle
                if (app()->has('session')) {
                    $sessionManager = app('session');
                    if (method_exists($sessionManager, 'getSessionConfig')) {
                        $sessionConfig = $sessionManager->getSessionConfig();
                        $sessionConfig['lifetime'] = (int) $sessionTimeout;
                    }
                }
                
            } catch (\Exception $e) {
                // Hata durumunda varsayılan değeri kullan
                \Log::warning('Session timeout ayarı alınamadı, varsayılan değer kullanılıyor: ' . $e->getMessage());
                config(['session.lifetime' => 120]);
            }
        }

        return $next($request);
    }
} 