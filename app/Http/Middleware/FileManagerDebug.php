<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class FileManagerDebug
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            return $next($request);
        } catch (\Exception $e) {
            // Hata bilgisini günlüğe yaz
            Log::error('FileManager Error: ' . $e->getMessage());
            Log::error('File: ' . $e->getFile() . ' on line ' . $e->getLine());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            // İstek bir ajax isteği ise JSON yanıtı döndür
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'error' => true,
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ], 500);
            }
            
            // Normal görünüm için basit bir hata mesajı
            return response()->view('errors.500', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }
} 