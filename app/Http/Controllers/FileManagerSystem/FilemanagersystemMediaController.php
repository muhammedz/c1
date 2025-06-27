<?php

namespace App\Http\Controllers\FileManagerSystem;

use App\Http\Controllers\Controller;
use App\Models\FileManagerSystem\Folder;
use App\Models\FileManagerSystem\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;
use App\Helpers\FileManagerHelper;

class FilemanagersystemMediaController extends Controller
{
    /**
     * Tüm medya dosyalarını listele
     */
    public function index(Request $request)
    {
        $folderId = $request->input('folder_id');
        
        if ($folderId) {
            $folder = Folder::findOrFail($folderId);
            $medias = Media::where('folder_id', $folderId)->orderBy('name')->paginate(20);
        } else {
            $folder = null;
            $medias = Media::orderBy('name')->paginate(20);
        }
        
        return view('filemanagersystem.medias.index', compact('medias', 'folder'));
    }

    /**
     * Yeni medya dosyası yükleme formunu göster
     */
    public function create(Request $request)
    {
        $folderId = $request->input('folder_id');
        $folder = null;
        
        if ($folderId) {
            $folder = Folder::findOrFail($folderId);
        }
        
        $folders = Folder::orderBy('folder_name')->get();
        
        // Resim sıkıştırma ayarları
        $compressionSettings = [
            'qualityOptions' => config('filemanagersystem.image_compression.quality_presets'),
            'sizeOptions' => config('filemanagersystem.image_compression.size_presets'),
            'defaultQuality' => config('filemanagersystem.image_compression.default_quality'),
            'defaultSize' => config('filemanagersystem.image_compression.default_size'),
            'keepOriginal' => config('filemanagersystem.webp_conversion.keep_original')
        ];
        
        return view('filemanagersystem.medias.create', compact('folders', 'folder', 'compressionSettings'));
    }

    /**
     * Yeni medya dosyası yükle
     */
    public function store(Request $request)
    {
        // Debug: İstek bilgilerini logla
        Log::info('Dosya yükleme isteği başladı', [
            'user_id' => Auth::id(),
            'ip' => $request->ip(),
            'content_length' => $request->header('Content-Length'),
            'content_type' => $request->header('Content-Type'),
            'files_count' => $request->hasFile('files') ? count($request->file('files')) : 0,
            'post_max_size' => ini_get('post_max_size'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time')
        ]);

        // Debug: Dosya bilgilerini logla
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $index => $file) {
                Log::info("Dosya {$index} bilgileri", [
                    'original_name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'extension' => $file->getClientOriginalExtension(),
                    'is_valid' => $file->isValid(),
                    'error' => $file->getError()
                ]);
            }
        }

        // Güçlendirilmiş validation kuralları
        try {
            $request->validate([
                'files' => 'required|array|max:10', // Maksimum 10 dosya
                'files.*' => [
                    'required',
                    'file',
                    'max:51200', // 50MB (kilobyte cinsinden)
                    function ($attribute, $value, $fail) {
                        // Dosya uzantısı kontrolü
                        $allowedExtensions = config('filemanagersystem.security.allowed_extensions', []);
                        $extension = strtolower($value->getClientOriginalExtension());
                        
                        if (!in_array($extension, $allowedExtensions)) {
                            $fail('Bu dosya uzantısı desteklenmiyor: ' . $extension);
                        }
                        
                        // MIME type kontrolü
                        $allowedMimeTypes = config('filemanagersystem.security.allowed_mime_types', []);
                        $mimeType = $value->getMimeType();
                        
                        if (!in_array($mimeType, $allowedMimeTypes)) {
                            $fail('Bu dosya türü desteklenmiyor: ' . $mimeType);
                        }
                        
                        // Dosya adı güvenlik kontrolü
                        $filename = $value->getClientOriginalName();
                        if (strpos($filename, '../') !== false || strpos($filename, '..\\') !== false) {
                            $fail('Güvenli olmayan dosya adı.');
                        }
                        
                        // Null byte kontrolü
                        if (strpos($filename, "\0") !== false) {
                            $fail('Geçersiz dosya adı.');
                        }
                    }
                ],
                'folder_id' => 'nullable|exists:filemanagersystem_folders,id',
                'is_public' => 'boolean',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation hatası', [
                'errors' => $e->errors(),
                'user_id' => Auth::id(),
                'ip' => $request->ip()
            ]);
            throw $e;
        }
        
        $uploadedFiles = [];
        $errors = [];
        
        foreach ($request->file('files') as $index => $file) {
            try {
                // Dosya güvenlik kontrolü
                if (!$this->validateFileSecurely($file)) {
                    $errors[] = "Dosya güvenlik kontrolünden geçemedi: " . $file->getClientOriginalName();
                    continue;
                }
                
                $originalName = $file->getClientOriginalName();
                $extension = strtolower($file->getClientOriginalExtension());
                $mimeType = $file->getMimeType();
                $originalSize = $file->getSize();
                
                // Güvenli dosya adı oluştur
                $fileName = $this->generateSecureFileName($originalName, $extension);
                
                // Dosya türüne göre klasör belirleme
                $folderPath = $this->getMainFolderByMimeType($mimeType);
                
                // Klasör yoksa oluştur
                $fullPath = public_path('uploads/' . $folderPath);
                if (!file_exists($fullPath)) {
                    mkdir($fullPath, 0755, true);
                }
                
                // Dosyayı güvenli şekilde kaydet
                $path = $file->storeAs($folderPath, $fileName, 'uploads');
                $fullPath = public_path('uploads/' . $path);
                $url = FileManagerHelper::getFileUrl('uploads/' . $path);
                
                // Resim dosyası ise sıkıştırma ve WebP dönüştürme işlemi yap
                $compressionInfo = null;
                $isImage = strpos($mimeType, 'image/') === 0;
                
                if ($isImage) {
                    // Resim boyutlarını kontrol et
                    $imageInfo = getimagesize($fullPath);
                    if ($imageInfo) {
                        $maxWidth = config('filemanagersystem.image_compression.max_dimensions.width', 4096);
                        $maxHeight = config('filemanagersystem.image_compression.max_dimensions.height', 4096);
                        
                        if ($imageInfo[0] > $maxWidth || $imageInfo[1] > $maxHeight) {
                            // Dosyayı sil ve hata ver
                            unlink($fullPath);
                            $errors[] = "Resim boyutu çok büyük: " . $originalName . " (Max: {$maxWidth}x{$maxHeight})";
                            continue;
                        }
                    }
                    
                    // Resmi sıkıştır
                    $compressionInfo = $this->compressImage(
                        $fullPath, 
                        $mimeType, 
                        [
                            'quality' => config('filemanagersystem.image_compression.default_quality'),
                            'size' => config('filemanagersystem.image_compression.default_size'),
                            'resize' => false // Çözünürlüğü korumak için
                        ]
                    );
                }
                
                // Veritabanı kaydı oluştur
                $media = new Media();
                $media->name = $fileName;
                $media->original_name = $originalName;
                $media->mime_type = $mimeType;
                $media->extension = $extension;
                $media->size = $isImage && $compressionInfo ? $compressionInfo['compressed_size'] : $originalSize;
                $media->original_size = $originalSize;
                $media->path = $path;
                $media->url = $url;
                $media->user_id = Auth::id();
                $media->folder_id = $request->folder_id;
                $media->is_public = $request->has('is_public') ? $request->is_public : false;
                
                // Resim ise ek bilgileri kaydet
                if ($isImage && $compressionInfo) {
                    $media->width = $compressionInfo['width'] ?? 0;
                    $media->height = $compressionInfo['height'] ?? 0;
                    $media->has_webp = $compressionInfo['has_webp'] ?? false;
                    $media->webp_url = $compressionInfo['webp_url'] ?? null;
                    $media->webp_path = $compressionInfo['webp_path'] ?? null;
                    $media->compression_rate = $compressionInfo['compression_rate'] ?? 0;
                }
                
                $media->save();
                
                // Güvenlik logu
                Log::info('Dosya başarıyla yüklendi', [
                    'user_id' => Auth::id(),
                    'filename' => $originalName,
                    'mime_type' => $mimeType,
                    'size' => $originalSize,
                    'ip' => $request->ip()
                ]);
                
                $uploadedFiles[] = $media;
                
            } catch (\Exception $e) {
                Log::error('Dosya yükleme hatası: ' . $e->getMessage(), [
                    'file_name' => $originalName ?? 'unknown',
                    'mime_type' => $mimeType ?? 'unknown',
                    'user_id' => Auth::id(),
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                $errors[] = "Dosya yükleme hatası: " . ($originalName ?? 'Bilinmeyen dosya');
            }
        }
        
        // Sonuç mesajı
        $successCount = count($uploadedFiles);
        $errorCount = count($errors);
        
        // AJAX isteği ise JSON response döndür
        if ($request->ajax() || $request->wantsJson()) {
            if ($successCount > 0 && $errorCount === 0) {
                return response()->json([
                    'success' => true,
                    'message' => $successCount . ' adet dosya başarıyla yüklendi.',
                    'uploaded_files' => collect($uploadedFiles)->map(function($media) {
                        return [
                            'id' => $media->id,
                            'name' => $media->name,
                            'original_name' => $media->original_name,
                            'url' => $media->url,
                            'mime_type' => $media->mime_type,
                            'size' => $media->size
                        ];
                    })->toArray(),
                    'errors' => []
                ]);
            } elseif ($successCount > 0 && $errorCount > 0) {
                return response()->json([
                    'success' => true,
                    'message' => $successCount . ' dosya yüklendi, ' . $errorCount . ' dosyada hata oluştu.',
                    'uploaded_files' => collect($uploadedFiles)->map(function($media) {
                        return [
                            'id' => $media->id,
                            'name' => $media->name,
                            'original_name' => $media->original_name,
                            'url' => $media->url,
                            'mime_type' => $media->mime_type,
                            'size' => $media->size
                        ];
                    })->toArray(),
                    'errors' => $errors
                ], 206); // Partial Content
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Hiçbir dosya yüklenemedi.',
                    'uploaded_files' => [],
                    'errors' => $errors
                ], 400);
            }
        }
        
        // Normal form isteği ise redirect döndür
        if ($successCount > 0 && $errorCount === 0) {
            return redirect()->route('admin.filemanagersystem.index')
                ->with('success', $successCount . ' adet dosya başarıyla yüklendi.');
        } elseif ($successCount > 0 && $errorCount > 0) {
            return redirect()->route('admin.filemanagersystem.index')
                ->with('warning', $successCount . ' dosya yüklendi, ' . $errorCount . ' dosyada hata oluştu.')
                ->with('errors', $errors);
        } else {
            return redirect()->route('admin.filemanagersystem.index')
                ->with('error', 'Hiçbir dosya yüklenemedi.')
                ->with('errors', $errors);
        }
    }

    /**
     * Dosya güvenlik kontrolü
     */
    private function validateFileSecurely($file)
    {
        try {
            // 1. Dosya varlık kontrolü
            if (!$file || !$file->isValid()) {
                return false;
            }

            $originalName = $file->getClientOriginalName();
            $extension = strtolower($file->getClientOriginalExtension());
            $mimeType = $file->getMimeType();
            $size = $file->getSize();

            // 2. Yasaklı uzantılar kontrolü
            $blockedExtensions = config('filemanagersystem.security.blocked_extensions', []);
            if (in_array($extension, $blockedExtensions)) {
                return false;
            }

            // 3. Dosya içeriği kontrolü (executable dosya kontrolü)
            $filePath = $file->getRealPath();
            $content = file_get_contents($filePath, false, null, 0, 1024); // İlk 1KB

            $dangerousPatterns = [
                '/<\?php/i',
                '/<script/i',
                '/eval\s*\(/i',
                '/exec\s*\(/i',
                '/system\s*\(/i',
                '/shell_exec\s*\(/i'
            ];

            foreach ($dangerousPatterns as $pattern) {
                if (preg_match($pattern, $content)) {
                    return false;
                }
            }

            // 4. Magic number kontrolü
            if (config('filemanagersystem.security.validate_magic_numbers', true)) {
                $actualMimeType = mime_content_type($filePath);
                if (!$this->isMimeTypeCompatible($actualMimeType, $mimeType)) {
                    return false;
                }
            }

            return true;

        } catch (\Exception $e) {
            Log::error('Dosya güvenlik kontrolü hatası: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Güvenli dosya adı oluştur
     */
    private function generateSecureFileName($originalName, $extension)
    {
        // Dosya adını temizle
        $baseName = pathinfo($originalName, PATHINFO_FILENAME);
        $baseName = preg_replace('/[^a-zA-Z0-9\-_]/', '_', $baseName);
        $baseName = trim($baseName, '_');
        
        // Boş ise varsayılan ad ver
        if (empty($baseName)) {
            $baseName = 'file';
        }
        
        // Benzersiz ID ekle
        $uniqueId = Str::random(8);
        $timestamp = time();
        
        return $baseName . '_' . $timestamp . '_' . $uniqueId . '.' . $extension;
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
            'image/jpeg' => ['image/jpg'],
            'image/jpg' => ['image/jpeg'],
            'text/plain' => ['text/csv'],
        ];

        if (isset($compatibleTypes[$declared])) {
            return in_array($actual, $compatibleTypes[$declared]);
        }

        return false;
    }

    /**
     * Medya dosyası detaylarını göster
     */
    public function show(Media $media)
    {
        return view('filemanagersystem.medias.show', compact('media'));
    }

    /**
     * Medya dosyası düzenleme formunu göster
     */
    public function edit(Media $media)
    {
        $folders = Folder::orderBy('folder_name')->get();
        return view('filemanagersystem.medias.edit', compact('media', 'folders'));
    }

    /**
     * Medya dosyasını güncelle
     */
    public function update(Request $request, Media $media)
    {
        $request->validate([
            'original_name' => 'required|string|max:255',
            'folder_id' => 'nullable|exists:filemanagersystem_folders,id',
            'is_public' => 'boolean',
        ]);
        
        $media->original_name = $request->original_name;
        $media->folder_id = $request->folder_id;
        $media->is_public = $request->has('is_public') ? $request->is_public : false;
        $media->save();
        
        return redirect()->route('admin.filemanagersystem.index')
            ->with('success', 'Dosya bilgileri başarıyla güncellendi.');
    }

    /**
     * Medya dosyasını sil
     */
    public function destroy(Media $media)
    {
        try {
            $folderId = $media->folder_id;
            
            // Fiziksel dosyaları sil (orijinal ve WebP)
            if (Storage::disk('uploads')->exists($media->path)) {
                Storage::disk('uploads')->delete($media->path);
            }
            
            // WebP dosyası varsa sil
            if ($media->has_webp && $media->webp_path && Storage::disk('uploads')->exists($media->webp_path)) {
                Storage::disk('uploads')->delete($media->webp_path);
            }
            
            // Veritabanı kaydını sil
            $media->delete();
            
            // AJAX isteği ise JSON yanıt döndür
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Dosya başarıyla silindi.'
                ]);
            }
            
            // Normal istek ise yönlendirme döndür
            return redirect()->route('admin.filemanagersystem.index')
                ->with('success', 'Dosya başarıyla silindi.');
        } catch (\Exception $e) {
            // AJAX isteği ise JSON yanıt döndür
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dosya silinirken bir hata oluştu: ' . $e->getMessage()
                ], 500);
            }
            
            // Normal istek ise hata mesajı ile yönlendirme döndür
            return back()->withErrors(['error' => 'Dosya silinirken bir hata oluştu: ' . $e->getMessage()]);
        }
    }

    /**
     * Dosyayı indir
     */
    public function download(Media $media)
    {
        $filePath = public_path('uploads/' . $media->path);
        
        if (file_exists($filePath)) {
            return response()->download($filePath, $media->original_name);
        }
        
        return back()->withErrors(['error' => 'Dosya bulunamadı']);
    }
    
    /**
     * Media ID'sine göre gerçek dosya yolunu döndürür
     */
    public function getFilePath($id)
    {
        try {
            $media = Media::findOrFail($id);
            
            // Gerçek dosya yolunu döndür
            $filePath = '/uploads/' . $media->path;
            
            // WebP sürümü varsa ve kullanılabilirse onu tercih et
            if ($media->has_webp && $media->webp_path) {
                $filePath = '/uploads/' . $media->webp_path;
            }
            
            return response()->json([
                'success' => true,
                'file_path' => $filePath,
                'media_info' => [
                    'id' => $media->id,
                    'name' => $media->name,
                    'original_name' => $media->original_name,
                    'mime_type' => $media->mime_type,
                    'extension' => $media->extension,
                    'has_webp' => $media->has_webp,
                    'url' => $media->url,
                    'webp_url' => $media->webp_url
                ]
            ]);
        } catch (\Exception $e) {
            // Hata durumunda
            return response()->json([
                'success' => false,
                'message' => 'Dosya yolu alınamadı: ' . $e->getMessage()
            ], 404);
        }
    }
    
    /**
     * MIME türüne göre ana klasörü belirler
     */
    private function getMainFolderByMimeType($mimeType)
    {
        if (strpos($mimeType, 'image/') === 0) {
            return 'images';
        } elseif (strpos($mimeType, 'video/') === 0) {
            return 'videos';
        } elseif (strpos($mimeType, 'audio/') === 0) {
            return 'audios';
        } elseif (in_array($mimeType, ['application/zip', 'application/x-rar-compressed', 'application/x-tar'])) {
            return 'archives';
        } else {
            return 'documents';
        }
    }

    /**
     * Resim dosyasını sıkıştırır ve WebP'ye dönüştürür
     * 
     * @param string $imagePath Resim dosyasının fiziksel yolu
     * @param string $mimeType Dosya MIME tipi
     * @param array $options Sıkıştırma seçenekleri
     * @return array Sıkıştırma sonuçları
     */
    private function compressImage($imagePath, $mimeType, $options = [])
    {
        // Varsayılan ayarları yapılandırma dosyasından alalım
        $compressionEnabled = config('filemanagersystem.image_compression.enabled', false);
        $qualityPresets = config('filemanagersystem.image_compression.quality_presets', []);
        $defaultQuality = config('filemanagersystem.image_compression.default_quality', 'medium');
        $sizePresets = config('filemanagersystem.image_compression.size_presets', []);
        $defaultSize = config('filemanagersystem.image_compression.default_size', 'medium');
        $supportedFormats = config('filemanagersystem.image_compression.supported_formats', []);
        
        // Sıkıştırma devre dışı bırakılmışsa sadece boyutları döndür
        if (!$compressionEnabled || !in_array($mimeType, $supportedFormats)) {
            Log::info('Resim sıkıştırma devre dışı veya desteklenmeyen format', [
                'mimeType' => $mimeType,
                'compressionEnabled' => $compressionEnabled
            ]);
            
            // Sonuç dizisi
            $result = [
                'compressed_size' => filesize($imagePath),
                'compression_rate' => 0,
                'webp_url' => null,
                'webp_path' => null,
                'has_webp' => false,
                'width' => 0,
                'height' => 0,
                'quality' => 80
            ];
            
            // Basit bir ölçü için
            if (function_exists('getimagesize')) {
                $imageInfo = getimagesize($imagePath);
                if ($imageInfo) {
                    $result['width'] = $imageInfo[0];
                    $result['height'] = $imageInfo[1];
                }
            }
            
            return $result;
        }
        
        // Orijinal boyut
        $originalSize = filesize($imagePath);
        
        // Seçenekleri belirle
        $qualityOption = $options['quality'] ?? $defaultQuality;
        $sizeOption = $options['size'] ?? $defaultSize;
        $resizeEnabled = isset($options['resize']) ? $options['resize'] : false;
        
        // Kalite değerini seç
        $quality = is_string($qualityOption) ? ($qualityPresets[$qualityOption] ?? 80) : intval($qualityOption);
        
        // Boyut limiti seç
        $sizeLimit = is_string($sizeOption) ? ($sizePresets[$sizeOption] ?? [1920, 1080]) : $sizeOption;
        
        // Sonuç dizisi
        $result = [
            'compressed_size' => $originalSize,
            'compression_rate' => 0,
            'webp_url' => null,
            'webp_path' => null,
            'has_webp' => false,
            'width' => 0,
            'height' => 0,
            'quality' => $quality
        ];
        
        try {
            // Intervention Image v3 kullanımı (Laravel 1.5 için)
            $image = Image::read($imagePath);
            
            // Boyut bilgilerini kaydet
            $result['width'] = $image->width();
            $result['height'] = $image->height();
            
            // ÖNEMLİ: Eğer resize özellikle istenmemişse, boyutlandırma işlemini atla
            // Çözünürlüğü koruyalım, sadece kalite düzenlemesi yapalım
            if ($resizeEnabled && is_array($sizeLimit) && count($sizeLimit) >= 2) {
                $maxWidth = $sizeLimit[0];
                $maxHeight = $sizeLimit[1];
                
                if ($image->width() > $maxWidth || $image->height() > $maxHeight) {
                    $image->resize($maxWidth, $maxHeight, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                    
                    // Yeni boyutları güncelle
                    $result['width'] = $image->width();
                    $result['height'] = $image->height();
                }
            }
            
            // Resim formatını belirle
            $format = $this->getImageFormatFromMimeType($mimeType);
            
            // V3 için doğru encode kullanımı
            $encodedImage = null;
            
            switch ($format) {
                case 'jpg':
                    $encodedImage = $image->toJpeg($quality);
                    break;
                case 'png':
                    $encodedImage = $image->toPng();
                    break;
                case 'gif':
                    $encodedImage = $image->toGif();
                    break;
                case 'webp':
                    $encodedImage = $image->toWebp($quality);
                    break;
                default:
                    $encodedImage = $image->toJpeg($quality);
            }
            
            // Dosyaya kaydet
            file_put_contents($imagePath, $encodedImage);
            
            // Sıkıştırılmış boyutu al
            $compressedSize = filesize($imagePath);
            $result['compressed_size'] = $compressedSize;
            
            // Sıkıştırma oranını hesapla (%)
            if ($originalSize > 0) {
                $result['compression_rate'] = round(100 - (($compressedSize / $originalSize) * 100), 2);
            }
            
            // WebP dönüşümünü yap
            $webpConversion = config('filemanagersystem.webp_conversion.enabled', false);
            if ($webpConversion && in_array($mimeType, config('filemanagersystem.webp_conversion.formats_to_convert', []))) {
                $webpResult = $this->convertToWebP($imagePath, $image, config('filemanagersystem.webp_conversion.quality', 85));
                
                if ($webpResult['success']) {
                    $result['webp_url'] = $webpResult['url'];
                    $result['webp_path'] = $webpResult['path'];
                    $result['has_webp'] = true;
                }
            }
            
            return $result;
        } catch (\Exception $e) {
            // Hata loglama
            Log::error('Resim sıkıştırma hatası: ' . $e->getMessage(), [
                'file' => $imagePath,
                'mime_type' => $mimeType,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return $result;
        }
    }
    
    /**
     * MIME tipinden format adını alır
     */
    private function getImageFormatFromMimeType($mimeType)
    {
        switch ($mimeType) {
            case 'image/jpeg':
            case 'image/jpg':
                return 'jpg';
            case 'image/png':
                return 'png';
            case 'image/gif':
                return 'gif';
            case 'image/webp':
                return 'webp';
            default:
                return 'jpg'; // Varsayılan format
        }
    }
    
    /**
     * Resim dosyasını WebP formatına dönüştürür
     * 
     * @param string $imagePath Orijinal resim dosyasının yolu
     * @param \Intervention\Image\Interfaces\ImageInterface $image Intervention Image nesnesi
     * @param int $quality WebP kalitesi (0-100)
     * @return array Dönüştürme sonuç bilgileri
     */
    private function convertToWebP($imagePath, $image, $quality = 85)
    {
        $result = [
            'success' => false,
            'url' => null,
            'path' => null,
            'size' => null
        ];
        
        try {
            // Orijinal dosya adı ve yolu bilgilerini al
            $pathInfo = pathinfo($imagePath);
            $webpFileName = $pathInfo['filename'] . '.webp';
            $webpPath = $pathInfo['dirname'] . '/' . $webpFileName;
            
            // ÖNEMLİ: İmajı değiştirmeden/boyutlandırmadan WebP'ye çevir
            // Sadece kalite değişikliği yaparak sıkıştırıyoruz
            $webpImage = $image->toWebp($quality);
            file_put_contents($webpPath, $webpImage);
            
            // Ölçü ve sonuç bilgilerini doldur
            $result['success'] = true;
            $result['path'] = str_replace(public_path('uploads/'), '', $webpPath);
            $result['url'] = FileManagerHelper::getFileUrl('uploads/' . $result['path']);
            $result['size'] = filesize($webpPath);
            
            return $result;
        } catch (\Exception $e) {
            Log::error('WebP dönüştürme hatası: ' . $e->getMessage(), [
                'file' => $imagePath,
                'error' => $e->getMessage()
            ]);
            
            return $result;
        }
    }
} 