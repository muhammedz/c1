<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\FileManagerSystem\FilemanagersystemService;

class FilemanagersystemUploadSecurity
{
    protected $filemanagersystemService;

    public function __construct(FilemanagersystemService $filemanagersystemService)
    {
        $this->filemanagersystemService = $filemanagersystemService;
    }

    public function handle(Request $request, Closure $next)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            
            // Dosya tipini kontrol et
            $allowedTypes = config('filemanagersystem.allowed_file_types', []);
            if (!in_array($file->getMimeType(), $allowedTypes)) {
                if ($request->expectsJson()) {
                    return response()->json(['error' => 'Bu dosya tipi desteklenmiyor'], 422);
                }
                return redirect()->back()->with('error', 'Bu dosya tipi desteklenmiyor');
            }

            // Dosya boyutunu kontrol et - 10MB olarak güncellendi
            $maxSize = config('filemanagersystem.max_file_size', 10) * 1024 * 1024; // MB to bytes
            if ($file->getSize() > $maxSize) {
                if ($request->expectsJson()) {
                    return response()->json(['error' => 'Dosya boyutu çok büyük (max: 10MB)'], 422);
                }
                return redirect()->back()->with('error', 'Dosya boyutu çok büyük (max: 10MB)');
            }
        }

        return $next($request);
    }
} 