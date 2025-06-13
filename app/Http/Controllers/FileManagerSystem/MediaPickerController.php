<?php

namespace App\Http\Controllers\FileManagerSystem;

use App\Http\Controllers\Controller;
use App\Models\FileManagerSystem\Media;
use App\Models\FileManagerSystem\MediaRelation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Helpers\FileManagerHelper;

class MediaPickerController extends Controller
{
    /**
     * Medya seçici modalını gösterir
     */
    public function index(Request $request)
    {
        try {
            $relatedType = $request->input('related_type', 'general');
            $relatedId = $request->input('related_id');
            $type = $request->input('type'); // Tip parametresi (image, document, audio vb.)
            $filter = $request->input('filter', 'related');
            
            return view('filemanagersystem.mediapicker.index', compact('relatedType', 'relatedId', 'type', 'filter'));
        } catch (\Exception $e) {
            \Log::error('MediaPicker açılırken hata oluştu: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return response()->view('filemanagersystem.mediapicker.error', [
                'error' => 'Medya seçici açılırken bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Medya dosyalarını listeler
     */
    public function listMedia(Request $request)
    {
        try {
            $relatedType = $request->input('related_type', 'general');
            $relatedId = $request->input('related_id');
            $filter = $request->input('filter', 'related');
            $page = $request->input('page', 1);
            $type = $request->input('type');
            
            // İlgili dosyaları getir
            $query = Media::query();
            
            // İlişki filtresi
            if ($filter === 'related') {
                if ($relatedId != null && $relatedId != '') {
                    $query->whereHas('relations', function($q) use ($relatedType, $relatedId) {
                        $q->where('related_type', $relatedType)
                          ->where('related_id', $relatedId);
                    });
                } else {
                    $query->where('id', 0); // Boş sonuç
                }
            } elseif ($filter === 'type-related') {
                $query->whereHas('relations', function($q) use ($relatedType) {
                    $q->where('related_type', $relatedType);
                });
            }
            
            // Dosya türüne göre filtreleme
            if ($type === 'image') {
                $query->where('mime_type', 'LIKE', 'image/%');
            } elseif ($type === 'document') {
                $docMimeTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 
                               'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
                $query->whereIn('mime_type', $docMimeTypes);
            } elseif ($type === 'video') {
                $query->where('mime_type', 'LIKE', 'video/%');
            } elseif ($type === 'audio') {
                $query->where('mime_type', 'LIKE', 'audio/%');
            }
            
            // Sayfalama ve sıralama
            $perPage = 20;
            $media = $query->with(['relations', 'folder', 'category'])
                          ->orderBy('created_at', 'desc')
                          ->paginate($perPage, ['*'], 'page', $page);
            
            return response()->json([
                'success' => true,
                'html' => view('filemanagersystem.mediapicker.partials.grid', compact('media', 'type'))->render(),
                'pagination' => view('filemanagersystem.mediapicker.partials.pagination', [
                    'paginator' => $media,
                    'filter' => $filter,
                    'relatedType' => $relatedType,
                    'relatedId' => $relatedId,
                    'type' => $type
                ])->render(),
            ]);
            
        } catch(\Exception $e) {
            \Log::error('MediaPicker Listeme Hatası: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Medya listesi alınırken bir hata oluştu: ' . $e->getMessage(),
                'error_details' => [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ], 500);
        }
    }
    
    /**
     * Yeni dosya yükler
     */
    public function upload(Request $request)
    {
        // Güçlendirilmiş validation kuralları
        $request->validate([
            'file' => [
                'required',
                'file',
                'max:10240', // 10MB (kilobyte cinsinden)
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
                    
                    // Executable dosya kontrolü
                    $blockedExtensions = config('filemanagersystem.security.blocked_extensions', []);
                    if (in_array($extension, $blockedExtensions)) {
                        $fail('Bu dosya türü güvenlik nedeniyle engellenmiştir.');
                    }
                }
            ],
            'related_type' => 'nullable|string',
            'related_id' => 'nullable|string'
        ]);
        
        $file = $request->file('file');
        $relatedType = $request->input('related_type');
        $relatedId = $request->input('related_id');
        
        try {
            // Dosya güvenlik kontrolü
            if (!$this->validateFileSecurely($file, $request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dosya güvenlik kontrolünden geçemedi'
                ], 422);
            }
            
            // Dosya bilgilerini al
            $originalName = $file->getClientOriginalName();
            $extension = strtolower($file->getClientOriginalExtension());
            $mimeType = $file->getMimeType();
            $originalSize = $file->getSize();
            
            // Güvenli dosya adı oluştur
            $fileName = $this->generateSecureFileName($originalName, $extension);
            
            // WebP dönüşümü - Eğer görsel dosyası ise
            $isImage = strpos($mimeType, 'image/') === 0;
            $isSvg = $extension === 'svg';
            $width = 0;
            $height = 0;
            
            // Dosya türüne göre klasör belirleme
            $folderPath = $this->getMainFolderByMimeType($mimeType);
            
            // Klasör yoksa oluştur
            $fullPath = public_path('uploads/' . $folderPath);
            if (!file_exists($fullPath)) {
                mkdir($fullPath, 0755, true);
            }
            
            // Resim sıkıştırma ve WebP dönüştürme işlemi
            if ($isImage && !$isSvg && in_array($extension, ['jpg', 'jpeg', 'png'])) {
                try {
                    $fileNameOrig = $fileName;
                    $fileNameWebp = pathinfo($fileName, PATHINFO_FILENAME) . '.webp';
                    
                    $savePathOrig = $folderPath . '/' . $fileNameOrig;
                    $savePathWebp = $folderPath . '/' . $fileNameWebp;
                    
                    $fullSavePathOrig = public_path('uploads/' . $savePathOrig);
                    $fullSavePathWebp = public_path('uploads/' . $savePathWebp);
                    
                    // Önce orijinal dosyayı kaydet
                    $file->storeAs($folderPath, $fileNameOrig, 'uploads');
                    
                    // ImageManager ile resmi yükle
                    $manager = new ImageManager(new Driver());
                    $image = $manager->read($fullSavePathOrig);
                    
                    // Boyut kontrolü ve yeniden boyutlandırma
                    $currentWidth = $image->width();
                    $currentHeight = $image->height();
                    
                    $maxWidth = 1920;
                    $maxHeight = 1080;
                    
                    if ($currentWidth > $maxWidth || $currentHeight > $maxHeight) {
                        $newWidth = $currentWidth;
                        $newHeight = $currentHeight;
                        
                        if ($currentWidth > $maxWidth) {
                            $ratio = $maxWidth / $currentWidth;
                            $newWidth = $maxWidth;
                            $newHeight = $currentHeight * $ratio;
                        }
                        
                        if ($newHeight > $maxHeight) {
                            $ratio = $maxHeight / $newHeight;
                            $newHeight = $maxHeight;
                            $newWidth = $newWidth * $ratio;
                        }
                        
                        // Resmi yeniden boyutlandır
                        $image->resize($newWidth, $newHeight);
                        
                        $width = $newWidth;
                        $height = $newHeight;
                    }
                    
                    // WebP olarak kaydet (70 kalite - daha iyi sıkıştırma)
                    $image->toWebp(70)->save($fullSavePathWebp);
                    
                    // WebP boyutunu kontrol et
                    $origSize = filesize($fullSavePathOrig);
                    $webpSize = filesize($fullSavePathWebp);
                    
                    // Hangi format daha küçükse onu kullan
                    if ($webpSize < $origSize) {
                        // WebP daha küçük, dosya bilgilerini WebP için güncelle
                        $path = $savePathWebp;
                        $url = FileManagerHelper::getFileUrl('uploads/' . $path);
                        $extension = 'webp';
                        $mimeType = 'image/webp';
                        $newSize = $webpSize;
                        $fileName = $fileNameWebp;
                        
                        // Orijinal dosyayı sil
                        if (file_exists($fullSavePathOrig)) {
                            unlink($fullSavePathOrig);
                        }
                    } else {
                        // Orijinal dosya daha küçük, WebP'yi sil
                        if (file_exists($fullSavePathWebp)) {
                            unlink($fullSavePathWebp);
                        }
                        
                        $path = $savePathOrig;
                        $url = FileManagerHelper::getFileUrl('uploads/' . $path);
                        $newSize = $origSize;
                        $fileName = $fileNameOrig;
                    }
                } catch (\Exception $e) {
                    // Dönüşüm hatası, orijinal dosyayı kullan
                    \Log::error('Görsel işleme hatası: ' . $e->getMessage(), [
                        'file' => $originalName,
                        'error' => $e->getMessage()
                    ]);
                    
                    // Orijinal dosya adı ve uzantısı ile kaydet
                    $path = $file->storeAs($folderPath, $fileName, 'uploads');
                    $fullPath = public_path('uploads/' . $path);
                    $url = FileManagerHelper::getFileUrl('uploads/' . $path);
                    
                    // Görüntü boyutlarını al
                    if ($isImage) {
                        $imageInfo = getimagesize($fullPath);
                        if ($imageInfo) {
                            $width = $imageInfo[0];
                            $height = $imageInfo[1];
                        }
                    }
                    
                    $newSize = $originalSize;
                }
            } else {
                // Görsel olmayan dosyalar veya SVG için normal kaydetme
                $path = $file->storeAs($folderPath, $fileName, 'uploads');
                $fullPath = public_path('uploads/' . $path);
                $url = FileManagerHelper::getFileUrl('uploads/' . $path);
                $newSize = $originalSize;
                
                // Görüntü boyutlarını al (eğer görselse)
                if ($isImage) {
                    $imageInfo = getimagesize($fullPath);
                    if ($imageInfo) {
                        $width = $imageInfo[0];
                        $height = $imageInfo[1];
                    }
                }
            }
            
            // Media kaydı oluştur
            $media = new Media();
            $media->name = $fileName;
            $media->original_name = $originalName;
            $media->mime_type = $mimeType;
            $media->extension = $extension;
            $media->size = $newSize;
            $media->original_size = $originalSize;
            $media->path = $path;
            $media->url = $url;
            $media->width = $width;
            $media->height = $height;
            $media->user_id = Auth::id();
            $media->save();
            
            // İlişkilendirme - Eğer related_type ve related_id belirtilmişse
            $relationCreated = false;
            if ($relatedType && $relatedId) {
                try {
                    // Geçici ID kontrolü (temp_ ile başlıyorsa ilişkilendirme yapma)
                    if (is_string($relatedId) && strpos($relatedId, 'temp_') === 0) {
                        // Geçici ID ile ilişkilendirme yapılmaz, direkt resim URL'sini döndür
                        return response()->json([
                            'success' => true,
                            'media' => $media,
                            'relationCreated' => false,
                            'html' => view('filemanagersystem.mediapicker.partials.media-item', ['item' => $media])->render()
                        ]);
                    }
                    
                    // İlişki var mı kontrol et
                    $existingRelation = MediaRelation::where('media_id', $media->id)
                        ->where('related_type', $relatedType)
                        ->where('related_id', $relatedId)
                        ->first();
                    
                    if (!$existingRelation) {
                        // İlişkiyi oluştur
                        $relation = new MediaRelation();
                        $relation->media_id = $media->id;
                        $relation->related_type = $relatedType;
                        $relation->related_id = $relatedId;
                        $relation->field_name = $request->input('field_name', 'default');
                        $relation->order = $request->input('order', 0);
                        $relation->save();
                        
                        $relationCreated = true;
                    } else {
                        $relationCreated = true;
                    }
                } catch (\Exception $e) {
                    // İlişki oluşturma hatası
                    Log::error('Medya ilişkisi oluşturma hatası: ' . $e->getMessage(), [
                        'media_id' => $media->id,
                        'related_type' => $relatedType,
                        'related_id' => $relatedId,
                        'error' => $e->getMessage()
                    ]);
                }
            }
            
            // Başarılı sonuç döndür
            return response()->json([
                'success' => true,
                'media' => $media,
                'relationCreated' => $relationCreated,
                'html' => view('filemanagersystem.mediapicker.partials.media-item', ['item' => $media])->render()
            ]);
            
        } catch (\Exception $e) {
            // Hata durumunda log tut
            Log::error('Medya yükleme hatası: ' . $e->getMessage(), [
                'file' => $originalName ?? 'unknown',
                'related_type' => $relatedType,
                'related_id' => $relatedId,
                'error' => $e->getMessage(),
                'ip' => $request->ip()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Dosya yüklenirken bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Dosya güvenlik kontrolü
     */
    private function validateFileSecurely($file, $request)
    {
        try {
            // 1. Dosya varlık kontrolü
            if (!$file || !$file->isValid()) {
                Log::warning('MediaPicker: Geçersiz dosya yükleme denemesi', [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ]);
                return false;
            }

            $originalName = $file->getClientOriginalName();
            $extension = strtolower($file->getClientOriginalExtension());
            $mimeType = $file->getMimeType();
            $size = $file->getSize();

            // 2. Yasaklı uzantılar kontrolü
            $blockedExtensions = config('filemanagersystem.security.blocked_extensions', []);
            if (in_array($extension, $blockedExtensions)) {
                Log::warning('MediaPicker: Yasaklı dosya uzantısı', [
                    'extension' => $extension,
                    'filename' => $originalName,
                    'ip' => $request->ip()
                ]);
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
                    Log::warning('MediaPicker: Executable dosya tespit edildi', [
                        'filename' => $originalName,
                        'pattern' => $pattern,
                        'ip' => $request->ip()
                    ]);
                    return false;
                }
            }

            // 4. Magic number kontrolü
            if (config('filemanagersystem.security.validate_magic_numbers', true)) {
                $actualMimeType = mime_content_type($filePath);
                if (!$this->isMimeTypeCompatible($actualMimeType, $mimeType)) {
                    Log::warning('MediaPicker: MIME türü uyumsuzluğu', [
                        'declared_mime' => $mimeType,
                        'actual_mime' => $actualMimeType,
                        'filename' => $originalName,
                        'ip' => $request->ip()
                    ]);
                    return false;
                }
            }

            return true;

        } catch (\Exception $e) {
            Log::error('MediaPicker: Dosya güvenlik kontrolü hatası: ' . $e->getMessage(), [
                'filename' => $originalName ?? 'unknown',
                'ip' => $request->ip()
            ]);
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
     * Var olan bir dosyayı içerik ile ilişkilendirir
     */
    public function relateMedia(Request $request)
    {
        $request->validate([
            'media_id' => 'required|exists:filemanagersystem_medias,id',
            'related_type' => 'required|string',
            'related_id' => 'required|string'
        ]);
        
        $mediaId = $request->input('media_id');
        $relatedType = $request->input('related_type');
        $relatedId = $request->input('related_id');
        
        try {
            // Geçici ID kontrolü (temp_ ile başlıyorsa ilişkilendirme yapma)
            if (is_string($relatedId) && strpos($relatedId, 'temp_') === 0) {
                return response()->json([
                    'success' => true,
                    'message' => 'Geçici ID ile ilişkilendirme atlandı'
                ]);
            }
            
            // İlişki var mı kontrol et
            $relation = MediaRelation::where('media_id', $mediaId)
                ->where('related_type', $relatedType)
                ->where('related_id', $relatedId)
                ->first();
                
            // İlişki yoksa oluştur
            if (!$relation) {
                $relation = new MediaRelation();
                $relation->media_id = $mediaId;
                $relation->related_type = $relatedType;
                $relation->related_id = $relatedId;
                $relation->field_name = $request->input('field_name', 'default');
                $relation->order = $request->input('order', 0);
                $relation->save();
            }
            
            return response()->json([
                'success' => true,
                'message' => 'İlişki başarıyla oluşturuldu'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'İlişki oluşturulurken bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Belirli bir medya dosyasının URL ve yolunu döndürür
     */
    public function getMediaUrl(Request $request)
    {
        try {
            $mediaId = $request->input('media_id');
            
            $media = Media::find($mediaId);
            
            if (!$media) {
                return response()->json([
                    'success' => false,
                    'message' => 'Medya bulunamadı'
                ]);
            }
            
            // URL ve path değerlerini al
            $url = $media->url;
            $path = $media->path;
            
            return response()->json([
                'success' => true,
                'url' => $url,
                'path' => $path,
                'media' => [
                    'id' => $media->id,
                    'name' => $media->original_name,
                    'size' => $media->getHumanReadableSize(),
                    'mime_type' => $media->mime_type
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('MediaPicker getMediaUrl Hatası: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Medya bilgileri alınırken bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Medya önizleme - Medya ID'sine göre resmi gösterir
     */
    public function mediaPreview($id)
    {
        try {
            $media = Media::findOrFail($id);
            
            // Eğer yerel dosya varsa
            $filePath = public_path('uploads/' . $media->path);
            if (file_exists($filePath)) {
                // Dosya tipini kontrol et
                $mimeType = $media->mime_type ?: mime_content_type($filePath);
                
                // WebP, JPG, PNG ve diğer resim türleri için doğru Content-Type ayarla
                $headers = [
                    'Content-Type' => $mimeType,
                    'Cache-Control' => 'public, max-age=86400' // 24 saat cache
                ];
                
                return response()->file($filePath, $headers);
            }
            
            // Eğer URL bir uzak sunucuya işaret ediyorsa
            if (filter_var($media->url, FILTER_VALIDATE_URL)) {
                return redirect($media->url);
            }
            
            // Varsayılan "resim bulunamadı" görseli
            return response()->file(public_path('vendor/adminlte/dist/img/default-150x150.png'), [
                'Content-Type' => 'image/png',
                'Cache-Control' => 'public, max-age=3600'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Medya önizleme hatası: ' . $e->getMessage(), [
                'media_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            // Varsayılan "resim bulunamadı" görseli
            return response()->file(public_path('vendor/adminlte/dist/img/default-150x150.png'), [
                'Content-Type' => 'image/png',
                'Cache-Control' => 'public, max-age=3600'
            ]);
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
}
