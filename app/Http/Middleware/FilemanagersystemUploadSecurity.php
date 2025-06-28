<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\FileManagerSystem\FilemanagersystemService;
use Illuminate\Support\Facades\Log;

class FilemanagersystemUploadSecurity
{
    protected $filemanagersystemService;

    // Tehlikeli dosya uzantıları
    private $dangerousExtensions = [
        'php', 'php3', 'php4', 'php5', 'phtml', 'pht', 'phps',
        'asp', 'aspx', 'jsp', 'jspx',
        'exe', 'bat', 'cmd', 'com', 'scr', 'msi',
        'sh', 'bash', 'zsh', 'fish',
        'js', 'vbs', 'ps1', 'py', 'rb', 'pl',
        'htaccess', 'htpasswd', 'ini', 'conf'
    ];

    // İzin verilen MIME türleri
    private $allowedMimeTypes = [
        // Resimler
        'image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml', 'image/bmp',
        // Belgeler
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'text/plain', 'text/csv',
        // Medya
        'video/mp4', 'video/avi', 'video/quicktime', 'video/x-msvideo',
        'audio/mpeg', 'audio/wav', 'audio/ogg',
        // Arşivler
        'application/zip', 'application/x-rar-compressed', 'application/x-tar', 'application/gzip'
    ];

    // İzin verilen dosya uzantıları
    private $allowedExtensions = [
        'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp',
        'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'csv',
        'mp4', 'avi', 'mov', 'mp3', 'wav', 'ogg',
        'zip', 'rar', 'tar', 'gz'
    ];

    public function __construct(FilemanagersystemService $filemanagersystemService)
    {
        $this->filemanagersystemService = $filemanagersystemService;
    }

    public function handle(Request $request, Closure $next)
    {
        // Dosya yükleme isteklerini kontrol et
        if ($request->hasFile('file') || $request->hasFile('files')) {
            $files = [];
            
            // Tek dosya veya çoklu dosya kontrolü
            if ($request->hasFile('file')) {
                $files[] = $request->file('file');
            }
            
            if ($request->hasFile('files')) {
                $files = array_merge($files, $request->file('files'));
            }

            foreach ($files as $file) {
                if (!$this->validateFile($file, $request)) {
                    return $this->getErrorResponse($request, 'Dosya güvenlik kontrolünden geçemedi');
                }
            }
        }

        return $next($request);
    }

    /**
     * Dosya güvenlik kontrollerini yapar
     */
    private function validateFile($file, $request)
    {
        try {
            // 1. Dosya varlık kontrolü
            if (!$file || !$file->isValid()) {
                Log::warning('Geçersiz dosya yükleme denemesi', [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ]);
                return false;
            }

            $originalName = $file->getClientOriginalName();
            $extension = strtolower($file->getClientOriginalExtension());
            $mimeType = $file->getMimeType();
            $size = $file->getSize();

            // 2. Dosya adı güvenlik kontrolü
            if (!$this->validateFileName($originalName)) {
                Log::warning('Güvenli olmayan dosya adı', [
                    'filename' => $originalName,
                    'ip' => $request->ip()
                ]);
                return false;
            }

            // 3. Dosya uzantısı kontrolü
            if (!$this->validateExtension($extension)) {
                Log::warning('İzin verilmeyen dosya uzantısı', [
                    'extension' => $extension,
                    'filename' => $originalName,
                    'ip' => $request->ip()
                ]);
                return false;
            }

            // 4. MIME type kontrolü
            if (!$this->validateMimeType($mimeType)) {
                Log::warning('İzin verilmeyen MIME türü', [
                    'mime_type' => $mimeType,
                    'filename' => $originalName,
                    'ip' => $request->ip()
                ]);
                return false;
            }

            // 5. Dosya boyutu kontrolü
            if (!$this->validateFileSize($size)) {
                Log::warning('Dosya boyutu limiti aşıldı', [
                    'size' => $size,
                    'filename' => $originalName,
                    'ip' => $request->ip()
                ]);
                return false;
            }

            // 6. Magic number kontrolü (dosya içeriği kontrolü)
            if (!$this->validateMagicNumber($file, $mimeType)) {
                Log::warning('Dosya içeriği MIME türü ile uyuşmuyor', [
                    'declared_mime' => $mimeType,
                    'filename' => $originalName,
                    'ip' => $request->ip()
                ]);
                return false;
            }

            // 7. Executable dosya kontrolü
            if ($this->isExecutableFile($file)) {
                Log::warning('Executable dosya yükleme denemesi', [
                    'filename' => $originalName,
                    'mime_type' => $mimeType,
                    'ip' => $request->ip()
                ]);
                return false;
            }

            return true;

        } catch (\Exception $e) {
            Log::error('Dosya validasyon hatası', [
                'error' => $e->getMessage(),
                'filename' => $originalName ?? 'unknown',
                'ip' => $request->ip()
            ]);
            return false;
        }
    }

    /**
     * Dosya adı güvenlik kontrolü
     */
    private function validateFileName($filename)
    {
        // Null byte kontrolü
        if (strpos($filename, "\0") !== false) {
            return false;
        }

        // Path traversal kontrolü
        if (strpos($filename, '../') !== false || strpos($filename, '..\\') !== false) {
            return false;
        }

        // Özel karakterler kontrolü
        if (preg_match('/[<>:"|?*]/', $filename)) {
            return false;
        }

        // Dosya adı uzunluk kontrolü
        if (strlen($filename) > 255) {
            return false;
        }

        return true;
    }

    /**
     * Dosya uzantısı kontrolü
     */
    private function validateExtension($extension)
    {
        // Tehlikeli uzantılar kontrolü
        if (in_array($extension, $this->dangerousExtensions)) {
            return false;
        }

        // İzin verilen uzantılar kontrolü
        return in_array($extension, $this->allowedExtensions);
    }

    /**
     * MIME type kontrolü
     */
    private function validateMimeType($mimeType)
    {
        return in_array($mimeType, $this->allowedMimeTypes);
    }

    /**
     * Dosya boyutu kontrolü
     */
    private function validateFileSize($size)
    {
        $maxSize = 50 * 1024 * 1024; // 50MB sabit limit
        return $size <= $maxSize;
    }

    /**
     * Magic number kontrolü - dosya içeriğinin gerçek türünü kontrol eder
     */
    private function validateMagicNumber($file, $declaredMimeType)
    {
        $filePath = $file->getRealPath();
        
        // Dosya içeriğinden gerçek MIME türünü al
        $actualMimeType = mime_content_type($filePath);
        
        // Bazı MIME türleri için özel kontroller
        $magicNumbers = [
            'image/jpeg' => ["\xFF\xD8\xFF"],
            'image/png' => ["\x89\x50\x4E\x47"],
            'image/gif' => ["\x47\x49\x46\x38"],
            'application/pdf' => ["\x25\x50\x44\x46"],
            'application/zip' => ["\x50\x4B\x03\x04", "\x50\x4B\x05\x06", "\x50\x4B\x07\x08"]
        ];

        // Magic number kontrolü
        if (isset($magicNumbers[$declaredMimeType])) {
            $fileHeader = file_get_contents($filePath, false, null, 0, 10);
            $isValid = false;
            
            foreach ($magicNumbers[$declaredMimeType] as $magicNumber) {
                if (strpos($fileHeader, $magicNumber) === 0) {
                    $isValid = true;
                    break;
                }
            }
            
            if (!$isValid) {
                return false;
            }
        }

        // MIME türü uyumluluk kontrolü
        return $this->isMimeTypeCompatible($actualMimeType, $declaredMimeType);
    }

    /**
     * MIME türü uyumluluğunu kontrol eder
     */
    private function isMimeTypeCompatible($actual, $declared)
    {
        // Tam eşleşme
        if ($actual === $declared) {
            return true;
        }

        // Bilinen uyumlu türler
        $compatibleTypes = [
            'image/jpeg' => ['image/jpg', 'image/pjpeg'],
            'image/jpg' => ['image/jpeg', 'image/pjpeg'],
            'image/png' => ['image/x-png'],
            'image/x-png' => ['image/png'],
            'image/gif' => ['image/x-gif'],
            'image/x-gif' => ['image/gif'],
            'image/webp' => ['image/x-webp'],
            'image/x-webp' => ['image/webp'],
            'text/plain' => ['text/csv'],
        ];

        if (isset($compatibleTypes[$declared])) {
            return in_array($actual, $compatibleTypes[$declared]);
        }

        // PNG için özel kontrol - bazen farklı MIME type algılanabiliyor
        if ($declared === 'image/png' && in_array($actual, ['image/png', 'image/x-png', 'application/octet-stream'])) {
            return true;
        }

        return false;
    }

    /**
     * Executable dosya kontrolü
     */
    private function isExecutableFile($file)
    {
        $filePath = $file->getRealPath();
        
        // Dosya içeriğinde PHP, script etiketleri kontrolü
        $content = file_get_contents($filePath, false, null, 0, 1024); // İlk 1KB'ı kontrol et
        
        $dangerousPatterns = [
            '/<\?php/i',
            '/<script/i',
            '/eval\s*\(/i',
            '/exec\s*\(/i',
            '/system\s*\(/i',
            '/shell_exec\s*\(/i',
            '/passthru\s*\(/i',
            '/base64_decode\s*\(/i'
        ];

        foreach ($dangerousPatterns as $pattern) {
            if (preg_match($pattern, $content)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Hata yanıtı döndürür
     */
    private function getErrorResponse($request, $message)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message,
                'error_code' => 'SECURITY_VIOLATION'
            ], 422);
        }

        return redirect()->back()
            ->withErrors(['file' => $message])
            ->withInput();
    }
} 