<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class FileUploadDebug
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Sadece dosya yükleme istekleri için debug
        if ($request->hasFile('files') || $request->is('*/media*')) {
            Log::info('=== DOSYA YÜKLEME DEBUG BAŞLADI ===', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'content_length' => $request->header('Content-Length'),
                'content_type' => $request->header('Content-Type'),
                'has_files' => $request->hasFile('files'),
                'files_count' => $request->hasFile('files') ? count($request->file('files')) : 0,
                'post_data_size' => strlen(serialize($_POST)),
                'server_limits' => [
                    'post_max_size' => ini_get('post_max_size'),
                    'upload_max_filesize' => ini_get('upload_max_filesize'),
                    'max_file_uploads' => ini_get('max_file_uploads'),
                    'memory_limit' => ini_get('memory_limit'),
                    'max_execution_time' => ini_get('max_execution_time'),
                    'max_input_time' => ini_get('max_input_time'),
                ]
            ]);

            // Dosya bilgilerini logla
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $index => $file) {
                    Log::info("Dosya #{$index} bilgileri", [
                        'original_name' => $file->getClientOriginalName(),
                        'size' => $file->getSize(),
                        'size_mb' => round($file->getSize() / 1024 / 1024, 2),
                        'mime_type' => $file->getMimeType(),
                        'extension' => $file->getClientOriginalExtension(),
                        'is_valid' => $file->isValid(),
                        'error' => $file->getError(),
                        'error_message' => $file->getErrorMessage(),
                    ]);
                }
            }
        }

        try {
            $response = $next($request);
            
            // Başarılı response için log
            if ($request->hasFile('files') || $request->is('*/media*')) {
                Log::info('=== DOSYA YÜKLEME BAŞARILI ===', [
                    'status_code' => $response->getStatusCode(),
                    'response_size' => strlen($response->getContent()),
                ]);
            }
            
            return $response;
            
        } catch (\Exception $e) {
            // Hata durumunda detaylı log
            Log::error('=== DOSYA YÜKLEME HATASI ===', [
                'error_class' => get_class($e),
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            throw $e;
        }
    }
} 