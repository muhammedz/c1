<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\FileManagerSystem\FilemanagersystemService;
use App\Models\Setting;
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
        // Video formatları
        'video/mp4', 'video/mpeg', 'video/quicktime', 'video/x-msvideo', 'video/avi',
        'video/webm', 'video/ogg', 'video/3gpp', 'video/x-flv', 'video/x-ms-wmv',
        'video/mp2t', 'video/x-matroska', 'application/mp4',
        // Ses formatları
        'audio/mpeg', 'audio/mp3', 'audio/wav', 'audio/ogg', 'audio/aac',
        'audio/x-wav', 'audio/mp4', 'audio/webm', 'audio/flac',
        // Arşivler
        'application/zip', 'application/x-rar-compressed', 'application/x-tar', 'application/gzip'
    ];

    // İzin verilen dosya uzantıları
    private $allowedExtensions = [
        // Resimler
        'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp',
        // Belgeler
        'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'csv',
        // Video formatları
        'mp4', 'avi', 'mov', 'wmv', 'flv', 'webm', 'mkv', 'ogg', '3gp', 'ts',
        // Ses formatları
        'mp3', 'wav', 'ogg', 'aac', 'flac', 'm4a', 'wma',
        // Arşivler
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
                    'extension' => $extension,
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

            // Başarılı video dosyası yüklemelerini loglama
            if (str_starts_with($mimeType, 'video/')) {
                Log::info('✅ Başarılı video dosyası yüklemesi', [
                    'filename' => $originalName,
                    'extension' => $extension,
                    'mime_type' => $mimeType,
                    'size_mb' => round($size / (1024 * 1024), 2),
                    'ip' => $request->ip()
                ]);
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
        try {
            // Admin ayarlarından dosya boyutu limitini al (MB cinsinden)
            $maxSizeMB = Setting::get('max_file_upload_size', 50);
            $maxSize = (int) $maxSizeMB * 1024 * 1024; // Byte'a çevir
        } catch (\Exception $e) {
            // Hata durumunda varsayılan değeri kullan
            Log::warning('Dosya boyutu limiti ayarı alınamadı, varsayılan değer kullanılıyor: ' . $e->getMessage());
            $maxSize = 50 * 1024 * 1024; // 50MB varsayılan
        }
        
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
            'application/zip' => ["\x50\x4B\x03\x04", "\x50\x4B\x05\x06", "\x50\x4B\x07\x08"],
            // Video dosyaları için magic numbers
            'video/mp4' => ["\x00\x00\x00\x18\x66\x74\x79\x70", "\x00\x00\x00\x20\x66\x74\x79\x70"],
            'application/mp4' => ["\x00\x00\x00\x18\x66\x74\x79\x70", "\x00\x00\x00\x20\x66\x74\x79\x70"],
            'video/avi' => ["\x52\x49\x46\x46"],
            'video/x-msvideo' => ["\x52\x49\x46\x46"],
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
            // Video dosyaları için uyumlu türler
            'video/mp4' => ['application/mp4', 'video/mpeg'],
            'application/mp4' => ['video/mp4'],
            'video/quicktime' => ['video/mov'],
            'video/x-msvideo' => ['video/avi'],
            'video/avi' => ['video/x-msvideo'],
            // Ses dosyaları için uyumlu türler
            'audio/mpeg' => ['audio/mp3'],
            'audio/mp3' => ['audio/mpeg'],
            'audio/wav' => ['audio/x-wav'],
            'audio/x-wav' => ['audio/wav'],
        ];

        if (isset($compatibleTypes[$declared])) {
            return in_array($actual, $compatibleTypes[$declared]);
        }

        // PNG için özel kontrol - bazen farklı MIME type algılanabiliyor
        if ($declared === 'image/png' && in_array($actual, ['image/png', 'image/x-png', 'application/octet-stream'])) {
            return true;
        }

        // Video dosyaları için özel kontrol
        if (str_starts_with($declared, 'video/') && str_starts_with($actual, 'video/')) {
            return true;
        }

        // MP4 dosyaları için özel kontrol - bazen application/mp4 olarak algılanabiliyor
        if (($declared === 'video/mp4' && $actual === 'application/mp4') || 
            ($declared === 'application/mp4' && $actual === 'video/mp4')) {
            return true;
        }

        // Ses dosyaları için özel kontrol
        if (str_starts_with($declared, 'audio/') && str_starts_with($actual, 'audio/')) {
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