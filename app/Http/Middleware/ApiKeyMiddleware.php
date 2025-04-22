<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    /**
     * API Key kontrolü yapar
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Önce header'dan API anahtarını kontrol et
        $apiKey = $request->header('X-API-KEY');
        
        // Header'da yoksa query parametresinden kontrol et
        if (!$apiKey) {
            $apiKey = $request->query('api_key');
        }
        
        // API anahtarı yoksa veya geçerli değilse
        if (!$apiKey || !$this->isValidApiKey($apiKey)) {
            return response()->json([
                'message' => 'Geçersiz API anahtarı',
                'status' => 'error'
            ], 401);
        }
        
        return $next($request);
    }
    
    /**
     * API anahtarının geçerli olup olmadığını kontrol eder
     *
     * @param string $apiKey
     * @return bool
     */
    protected function isValidApiKey($apiKey): bool
    {
        // API anahtarlarının saklandığı ayar dosyasından veya veritabanından kontrol edilmeli
        // Burası ihtiyaçlara göre geliştirilebilir
        $validApiKeys = config('api.keys', []);
        
        return in_array($apiKey, $validApiKeys);
    }
} 