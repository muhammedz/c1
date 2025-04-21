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
        $request->validate([
            'files' => 'required|array',
            'files.*' => 'required|file|max:10485760', // 10MB
            'folder_id' => 'nullable|exists:filemanagersystem_folders,id',
            'is_public' => 'boolean',
        ]);
        
        $uploadedFiles = [];
        
        foreach ($request->file('files') as $file) {
            $originalName = $file->getClientOriginalName();
            $extension = strtolower($file->getClientOriginalExtension());
            $mimeType = $file->getMimeType();
            $originalSize = $file->getSize();
            $fileName = Str::random(40) . '.' . $extension;
            
            // Dosya türüne göre klasör belirleme
            $folderPath = $this->getMainFolderByMimeType($mimeType);
            
            // Klasör yoksa oluştur
            $fullPath = public_path('uploads/' . $folderPath);
            if (!file_exists($fullPath)) {
                mkdir($fullPath, 0755, true);
            }
            
            try {
                // Dosyayı basitçe kaydet, önce sıkıştırma yapmadan direkt kaydet
                $path = $file->storeAs($folderPath, $fileName, 'uploads');
                $fullPath = public_path('uploads/' . $path);
                $url = asset('uploads/' . $path);
                
                // Resim dosyası ise sıkıştırma ve WebP dönüştürme işlemi yap
                $compressionInfo = null;
                $isImage = strpos($mimeType, 'image/') === 0;
                
                if ($isImage) {
                    // Resmi sıkıştır
                    $compressionInfo = $this->compressImage(
                        $fullPath, 
                        $mimeType, 
                        [
                            'quality' => config('filemanagersystem.image_compression.default_quality'),
                            'size' => config('filemanagersystem.image_compression.default_size')
                        ]
                    );
                }
                
                // Temel veritabanı kaydı oluştur
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
                
                // Resim ise
                if ($isImage && $compressionInfo) {
                    $media->width = $compressionInfo['width'] ?? 0;
                    $media->height = $compressionInfo['height'] ?? 0;
                    $media->has_webp = $compressionInfo['has_webp'] ?? false;
                    $media->webp_url = $compressionInfo['webp_url'] ?? null;
                    $media->webp_path = $compressionInfo['webp_path'] ?? null;
                    $media->compression_rate = $compressionInfo['compression_rate'] ?? 0;
                }
                
                // Değişiklikleri kaydet
                $media->save();
                
                $uploadedFiles[] = $media;
                
            } catch (\Exception $e) {
                Log::error('Dosya yükleme hatası: ' . $e->getMessage(), [
                    'file_name' => $originalName,
                    'mime_type' => $mimeType,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }
        
        // Eğer hiç dosya yüklenmediyse
        if (empty($uploadedFiles)) {
            return redirect()->route('admin.filemanagersystem.media.create')
                ->with('error', 'Dosya yükleme işlemi sırasında bir hata oluştu. Lütfen logları kontrol edin.');
        }
        
        return redirect()->route('admin.filemanagersystem.media.index', ['folder_id' => $request->folder_id])
            ->with('success', count($uploadedFiles) . ' adet dosya başarıyla yüklendi.');
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
        
        return redirect()->route('admin.filemanagersystem.media.index', ['folder_id' => $media->folder_id])
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
            return redirect()->route('admin.filemanagersystem.media.index', ['folder_id' => $folderId])
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
            
            // Eğer resim boyutu limiti aşıyorsa yeniden boyutlandır
            if (is_array($sizeLimit) && count($sizeLimit) >= 2) {
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
            
            // WebP olarak kodla ve kaydet - V3 için doğru kullanım
            $webpImage = $image->toWebp($quality);
            file_put_contents($webpPath, $webpImage);
            
            // Ölçü ve sonuç bilgilerini doldur
            $result['success'] = true;
            $result['path'] = str_replace(public_path('uploads/'), '', $webpPath);
            $result['url'] = asset('uploads/' . $result['path']);
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