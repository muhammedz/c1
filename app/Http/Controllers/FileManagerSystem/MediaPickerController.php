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
            
            \Log::debug('MediaPicker Açılıyor', [
                'related_type' => $relatedType,
                'related_id' => $relatedId,
                'type' => $type,
                'filter' => $filter,
                'user_agent' => $request->userAgent(),
                'ip' => $request->ip(),
                'url' => $request->fullUrl()
            ]);
            
            return view('filemanagersystem.mediapicker.index', compact('relatedType', 'relatedId', 'type', 'filter'));
        } catch (\Exception $e) {
            \Log::error('MediaPicker açılırken hata oluştu: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Hata durumunda basit bir hata sayfası göster
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
            \Log::debug('MediaPicker ListMedia başladı', [
                'request_params' => $request->all(),
                'url' => $request->fullUrl()
            ]);
            
            $relatedType = $request->input('related_type', 'general');
            $relatedId = $request->input('related_id');
            $filter = $request->input('filter', 'related'); // 'related', 'all'
            $page = $request->input('page', 1);
            $type = $request->input('type'); // Tip parametresi (image, document, audio vb.)
            
            // DETAYLI DEBUG: Gelen parametreleri logla
            $debugLog = [
                'params' => [
                    'related_type' => $relatedType,
                    'related_id' => $relatedId,
                    'filter' => $filter,
                    'page' => $page,
                    'type' => $type
                ],
                'logs' => []
            ];
            \Log::debug('### MediaPicker İstek Parametreleri ###', $debugLog['params']);
            
            // İlgili dosyaları getir
            $query = Media::query();
            
            // DETAYLI DEBUG: İlk sorgu
            $sql1 = $query->toSql();
            \Log::debug('MediaPicker Başlangıç Sorgusu', ['sql' => $sql1]);
            $debugLog['logs'][] = ['tip' => 'başlangıç_sorgusu', 'sql' => $sql1];
            
            // İlişki filtresi
            if ($filter === 'related') {
                \Log::debug('MediaPicker İlişkili Dosya Filtresi Aktif', [
                    'related_type' => $relatedType,
                    'related_id' => $relatedId
                ]);
                $debugLog['logs'][] = ['tip' => 'ilişki_filtresi_aktif', 'related_type' => $relatedType, 'related_id' => $relatedId];
                
                // DETAYLI DEBUG: İlişki tablosunu kontrol et
                $relations = MediaRelation::where('related_type', $relatedType)
                    ->where('related_id', $relatedId)
                    ->get();
                
                \Log::debug('MediaPicker İlişki Kayıtları', [
                    'ilişki_sayısı' => $relations->count(),
                    'ilişkili_medya_idleri' => $relations->pluck('media_id')->toArray(),
                    'ilişki_detayları' => $relations->map(function($item) {
                        return [
                            'id' => $item->id,
                            'media_id' => $item->media_id,
                            'related_type' => $item->related_type,
                            'related_id' => $item->related_id,
                            'field_name' => $item->field_name,
                            'created_at' => $item->created_at,
                        ];
                    })->toArray()
                ]);
                $debugLog['logs'][] = [
                    'tip' => 'ilişki_kayıtları', 
                    'ilişki_sayısı' => $relations->count(),
                    'ilişkili_medya_idleri' => $relations->pluck('media_id')->toArray(),
                    'ilişki_detayları' => $relations->map(function($item) {
                        return [
                            'id' => $item->id,
                            'media_id' => $item->media_id,
                            'related_type' => $item->related_type,
                            'related_id' => $item->related_id,
                            'field_name' => $item->field_name,
                            'created_at' => $item->created_at,
                        ];
                    })->toArray()
                ];
                
                // İlişkili medya varsa detaylarını logla
                if ($relations->count() > 0) {
                    $mediaIds = $relations->pluck('media_id')->toArray();
                    $mediaDetails = Media::whereIn('id', $mediaIds)->get();
                    
                    \Log::debug('İlişkili Medya Detayları', [
                        'medya_sayısı' => $mediaDetails->count(),
                        'medya_detayları' => $mediaDetails->map(function($item) {
                            return [
                                'id' => $item->id,
                                'name' => $item->name,
                                'url' => $item->url,
                                'mime_type' => $item->mime_type,
                            ];
                        })->toArray()
                    ]);
                    
                    $debugLog['logs'][] = [
                        'tip' => 'ilişkili_medya_detayları',
                        'medya_sayısı' => $mediaDetails->count(),
                        'medya_detayları' => $mediaDetails->map(function($item) {
                            return [
                                'id' => $item->id,
                                'name' => $item->name,
                                'url' => $item->url,
                                'mime_type' => $item->mime_type,
                            ];
                        })->toArray()
                    ];
                }
                
                if ($relatedId != null && $relatedId != '') {
                    $query->whereHas('relations', function($q) use ($relatedType, $relatedId) {
                        $q->where('related_type', $relatedType)
                          ->where('related_id', $relatedId);
                    });
                    
                    // İlişki SQL sorgusunu logla
                    $relationsSql = (clone $query)->toSql();
                    $relationsBindings = (clone $query)->getBindings();
                    \Log::debug('İlişki SQL Sorgusu', [
                        'sql' => $relationsSql, 
                        'bindings' => $relationsBindings
                    ]);
                    $debugLog['logs'][] = [
                        'tip' => 'ilişki_sql_sorgusu', 
                        'sql' => $relationsSql, 
                        'bindings' => $relationsBindings
                    ];
                    
                } else {
                    // İlişki ID'si yoksa ve ilişkili filtre seçildiyse, hiçbir sonuç gösterme
                    $query->where('id', 0); // ID'si 0 olan hiçbir dosya olmadığı için boş sonuç dönecek
                }
            } elseif ($filter === 'type-related') {
                // Belirli içerik tipine ait tüm dosyalar (örn. tüm sliderlar)
                \Log::debug('MediaPicker Tür İlişkili Dosya Filtresi Aktif', [
                    'related_type' => $relatedType
                ]);
                $debugLog['logs'][] = ['tip' => 'tür_ilişki_filtresi_aktif', 'related_type' => $relatedType];
                
                // İlişki tablosunu kontrol et
                $typeRelations = MediaRelation::where('related_type', $relatedType)
                    ->get();
                
                \Log::debug('MediaPicker Tür İlişki Kayıtları', [
                    'ilişki_sayısı' => $typeRelations->count(),
                    'ilişkili_medya_idleri' => $typeRelations->pluck('media_id')->toArray()
                ]);
                $debugLog['logs'][] = [
                    'tip' => 'tür_ilişki_kayıtları', 
                    'ilişki_sayısı' => $typeRelations->count(),
                    'ilişkili_medya_idleri' => $typeRelations->pluck('media_id')->toArray()
                ];
                
                $query->whereHas('relations', function($q) use ($relatedType) {
                    $q->where('related_type', $relatedType);
                });
            } else {
                \Log::debug('MediaPicker Tüm Dosyalar Filtresi Aktif');
                $debugLog['logs'][] = ['tip' => 'tüm_dosyalar_filtresi_aktif'];
            }
            
            // Dosya türüne göre filtreleme
            if ($type === 'image') {
                $query->where('mime_type', 'LIKE', 'image/%');
                $debugLog['logs'][] = ['tip' => 'tür_filtresi', 'tür' => 'image'];
            } elseif ($type === 'document') {
                $docMimeTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 
                                'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 
                                'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 
                                'text/plain'];
                $query->where(function($q) use ($docMimeTypes) {
                    foreach ($docMimeTypes as $mimeType) {
                        $q->orWhere('mime_type', 'LIKE', $mimeType);
                    }
                });
                $debugLog['logs'][] = ['tip' => 'tür_filtresi', 'tür' => 'document'];
            } elseif ($type === 'video') {
                $query->where('mime_type', 'LIKE', 'video/%');
                $debugLog['logs'][] = ['tip' => 'tür_filtresi', 'tür' => 'video'];
            } elseif ($type === 'audio') {
                $query->where('mime_type', 'LIKE', 'audio/%');
                $debugLog['logs'][] = ['tip' => 'tür_filtresi', 'tür' => 'audio'];
            }
            
            // Sıralama
            $query->orderBy('created_at', 'desc');
            
            // SQL'i log'a yaz
            $bindings = $query->getBindings();
            $sql = $query->toSql();
            $sqlWithBindings = preg_replace_callback('/\?/', function() use (&$bindings) {
                return "'" . array_shift($bindings) . "'";
            }, $sql);
            \Log::debug('MediaPicker Final SQL', ['sql' => $sqlWithBindings]);
            $debugLog['logs'][] = ['tip' => 'final_sql', 'sql' => $sqlWithBindings];
            
            // Sayfalama
            $media = $query->paginate(12);
            
            // DETAYLI DEBUG: Media-Relations ilişkisini kontrol et
            $mediaIds = $media->pluck('id')->toArray();
            \Log::debug('MediaPicker Dönüş Sonuçları', [
                'toplam_sonuç' => $media->total(),
                'dönen_medya_idleri' => $mediaIds
            ]);
            $debugLog['logs'][] = [
                'tip' => 'dönüş_sonuçları', 
                'toplam_sonuç' => $media->total(),
                'dönen_medya_idleri' => $mediaIds
            ];
            
            // İlişki tablosunu kontrol et - bu medyaların ilişkileri var mı?
            $mediaRelations = MediaRelation::whereIn('media_id', $mediaIds)->get();
            \Log::debug('MediaPicker Sonuç İlişkileri', [
                'ilişki_sayısı' => $mediaRelations->count(),
                'ilişki_detayları' => $mediaRelations->map(function($item) {
                    return [
                        'media_id' => $item->media_id,
                        'related_type' => $item->related_type,
                        'related_id' => $item->related_id,
                    ];
                })->toArray()
            ]);
            $debugLog['logs'][] = [
                'tip' => 'sonuç_ilişkileri', 
                'ilişki_sayısı' => $mediaRelations->count(),
                'ilişki_detayları' => $mediaRelations->map(function($item) {
                    return [
                        'media_id' => $item->media_id,
                        'related_type' => $item->related_type,
                        'related_id' => $item->related_id,
                    ];
                })->toArray()
            ];
            
            // Media modelindeki relations() metodu çalışıyor mu kontrol et
            $relationTests = [];
            foreach($media->take(3) as $mediaItem) {
                $relationInfo = [
                    'media_id' => $mediaItem->id,
                    'ilişki_sayısı' => $mediaItem->relations()->count(),
                    'ilişki_listesi' => $mediaItem->relations()->get()->map(function($item) {
                        return [
                            'related_type' => $item->related_type,
                            'related_id' => $item->related_id,
                        ];
                    })->toArray()
                ];
                $relationTests[] = $relationInfo;
                \Log::debug('MediaPicker Media İlişki Kontrolü', $relationInfo);
            }
            $debugLog['logs'][] = ['tip' => 'media_ilişki_kontrolü', 'örnekler' => $relationTests];
            
            // Media modeli tablo adını kontrol et
            $modelTableName = (new Media())->getTable();
            $relationModelTableName = (new MediaRelation())->getTable();
            $debugLog['logs'][] = [
                'tip' => 'model_tablo_adları', 
                'media_tablo' => $modelTableName,
                'relation_tablo' => $relationModelTableName
            ];
            \Log::debug('Model tablo adları', [
                'media_tablo' => $modelTableName,
                'relation_tablo' => $relationModelTableName
            ]);
            
            return response()->json([
                'success' => true,
                'debug' => $debugLog,
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
        $request->validate([
            'file' => 'required|file|max:50000', // 50MB
            'related_type' => 'nullable|string',
            'related_id' => 'nullable|string'
        ]);
        
        $file = $request->file('file');
        $relatedType = $request->input('related_type');
        $relatedId = $request->input('related_id');
        
        // DETAYLI DEBUG: Parametreleri logla
        \Log::debug('MediaPicker Upload Parametreler', [
            'has_file' => $request->hasFile('file'),
            'related_type' => $relatedType,
            'related_id' => $relatedId,
            'all_params' => $request->all()
        ]);
        
        try {
            // Dosya bilgilerini al
            $originalName = $file->getClientOriginalName();
            $extension = strtolower($file->getClientOriginalExtension());
            $mimeType = $file->getMimeType();
            $originalSize = $file->getSize();
            $fileName = Str::random(40);
            
            // WebP dönüşümü - Eğer görsel dosyası ise
            $isImage = strpos($mimeType, 'image/') === 0;
            $width = 0;
            $height = 0;
            
            // Dosya türüne göre klasör belirleme
            $folderPath = $this->getMainFolderByMimeType($mimeType);
            
            // Klasör yoksa oluştur
            $fullPath = public_path('uploads/' . $folderPath);
            if (!file_exists($fullPath)) {
                mkdir($fullPath, 0755, true);
            }
            
            // Görsel dosyası ise WebP'ye dönüştür
            if ($isImage && $extension != 'svg') {
                try {
                    // Orijinal dosya adı ve WebP dosya adı
                    $fileNameOrig = $fileName . '.' . $extension;
                    $fileNameWebp = $fileName . '.webp';
                    $savePathOrig = $folderPath . '/' . $fileNameOrig;
                    $savePathWebp = $folderPath . '/' . $fileNameWebp;
                    $fullSavePathOrig = public_path('uploads/' . $savePathOrig);
                    $fullSavePathWebp = public_path('uploads/' . $savePathWebp);
                    
                    // Önce orijinal dosyayı kaydet
                    $file->storeAs($folderPath, $fileNameOrig, 'uploads');
                    
                    // ImageManager ile görüntüyü yükle
                    $manager = new ImageManager(new Driver());
                    $image = $manager->read($file);
                    
                    // Görüntü boyutlarını kaydet
                    $width = $image->width();
                    $height = $image->height();
                    
                    // Boyutlandırma gerekliyse - çok büyük resimler için
                    $maxWidth = 2560; // Maksimum genişlik
                    $maxHeight = 1440; // Maksimum yükseklik
                    
                    if ($width > $maxWidth || $height > $maxHeight) {
                        // En-boy oranını koru (aspect ratio)
                        $aspectRatio = $width / $height;
                        
                        if ($width > $height) {
                            // Yatay görsel
                            $newWidth = min($width, $maxWidth);
                            $newHeight = (int)($newWidth / $aspectRatio);
                            
                            // Yüksekliği kontrol et
                            if ($newHeight > $maxHeight) {
                                $newHeight = $maxHeight;
                                $newWidth = (int)($newHeight * $aspectRatio);
                            }
                        } else {
                            // Dikey görsel
                            $newHeight = min($height, $maxHeight);
                            $newWidth = (int)($newHeight * $aspectRatio);
                            
                            // Genişliği kontrol et
                            if ($newWidth > $maxWidth) {
                                $newWidth = $maxWidth;
                                $newHeight = (int)($newWidth / $aspectRatio);
                            }
                        }
                        
                        // Boyutlandır
                        $image->resize($newWidth, $newHeight);
                        $width = $newWidth;
                        $height = $newHeight;
                    }
                    
                    // WebP olarak kaydet (70 kalite - daha iyi sıkıştırma)
                    $image->toWebp(70)->save($fullSavePathWebp);
                    
                    // WebP boyutunu kontrol et
                    $origSize = filesize($fullSavePathOrig);
                    $webpSize = filesize($fullSavePathWebp);
                    
                    \Log::debug('Dosya boyut karşılaştırması', [
                        'original_size' => $origSize,
                        'webp_size' => $webpSize,
                        'difference' => $origSize - $webpSize,
                        'percent' => round(($origSize - $webpSize) / $origSize * 100, 2) . '%'
                    ]);
                    
                    // Hangi format daha küçükse onu kullan
                    if ($webpSize < $origSize) {
                        // WebP daha küçük, dosya bilgilerini WebP için güncelle
                        $path = $savePathWebp;
                        $url = FileManagerHelper::getFileUrl('uploads/' . $path);
                        $extension = 'webp';
                        $mimeType = 'image/webp';
                        $newSize = $webpSize;
                        
                        // Orijinal dosyayı sil
                        if (file_exists($fullSavePathOrig)) {
                            unlink($fullSavePathOrig);
                        }
                        
                        \Log::debug('WebP formatı kullanılıyor (daha küçük)', [
                            'webp_size' => $webpSize,
                            'original_size' => $origSize,
                            'saved' => round(($origSize - $webpSize) / $origSize * 100, 2) . '%'
                        ]);
                    } else {
                        // Orijinal dosya daha küçük, WebP'yi sil
                        if (file_exists($fullSavePathWebp)) {
                            unlink($fullSavePathWebp);
                        }
                        
                        $path = $savePathOrig;
                        $url = FileManagerHelper::getFileUrl('uploads/' . $path);
                        $newSize = $origSize;
                        
                        \Log::debug('Orijinal format kullanılıyor (daha küçük)', [
                            'original_size' => $origSize,
                            'webp_size' => $webpSize
                        ]);
                    }
                } catch (\Exception $e) {
                    // Dönüşüm hatası, orijinal dosyayı kullan
                    \Log::error('Görsel işleme hatası: ' . $e->getMessage(), [
                        'file' => $originalName,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    
                    // Orijinal dosya adı ve uzantısı ile kaydet
                    $fileName = $fileName . '.' . $extension;
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
                $fileName = $fileName . '.' . $extension;
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
                        \Log::debug('MediaPicker Geçici ID tespit edildi, ilişkilendirme yapılmadı', [
                            'media_id' => $media->id,
                            'related_type' => $relatedType,
                            'related_id' => $relatedId
                        ]);
                        
                        // Geçici ID ile ilişkilendirme yapılmaz, direkt resim URL'sini döndür
                        return response()->json([
                            'success' => true,
                            'media' => $media,
                            'relationCreated' => false,
                            'html' => view('filemanagersystem.mediapicker.partials.media-item', ['item' => $media])->render()
                        ]);
                    }
                    
                    // DETAYLI DEBUG: İlişkilendirme öncesi kontrol
                    \Log::debug('MediaPicker İlişkilendirme Öncesi', [
                        'media_id' => $media->id,
                        'related_type' => $relatedType,
                        'related_id' => $relatedId,
                        'related_id_type' => gettype($relatedId)
                    ]);
                    
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
                        
                        // Log oluştur
                        Log::info('Medya ilişkisi oluşturuldu', [
                            'media_id' => $media->id,
                            'related_type' => $relatedType,
                            'related_id' => $relatedId,
                            'relation_id' => $relation->id
                        ]);
                    } else {
                        // İlişki zaten var
                        \Log::info('Medya ilişkisi zaten mevcut', [
                            'media_id' => $media->id,
                            'related_type' => $relatedType,
                            'related_id' => $relatedId,
                            'relation_id' => $existingRelation->id
                        ]);
                        $relationCreated = true;
                    }
                } catch (\Exception $e) {
                    // İlişki oluşturma hatası
                    Log::error('Medya ilişkisi oluşturma hatası: ' . $e->getMessage(), [
                        'media_id' => $media->id,
                        'related_type' => $relatedType,
                        'related_id' => $relatedId,
                        'error' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine()
                    ]);
                }
            } else {
                // DETAYLI DEBUG: İlişkilendirme atlandı
                \Log::debug('MediaPicker İlişkilendirme Atlandı', [
                    'reason' => (!$relatedType ? 'related_type boş' : '') . (!$relatedId ? 'related_id boş' : ''),
                    'related_type' => $relatedType,
                    'related_id' => $relatedId,
                    'media_id' => $media->id
                ]);
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
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Dosya yüklenirken bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
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
                \Log::debug('MediaPicker Geçici ID tespit edildi, ilişkilendirme yapılmadı', [
                    'media_id' => $mediaId,
                    'related_type' => $relatedType,
                    'related_id' => $relatedId
                ]);
                
                // Geçici ID ile ilişkilendirme yapmadan başarılı döndür
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
                
                \Log::debug('MediaPicker İlişki Oluşturuldu', [
                    'media_id' => $mediaId,
                    'related_type' => $relatedType,
                    'related_id' => $relatedId,
                    'field_name' => $relation->field_name,
                    'order' => $relation->order
                ]);
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
            
            \Log::debug('MediaPicker getMediaUrl Çağrıldı', [
                'media_id' => $mediaId
            ]);
            
            $media = Media::find($mediaId);
            
            if (!$media) {
                \Log::error('MediaPicker getMediaUrl: Medya bulunamadı', [
                    'media_id' => $mediaId
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Medya bulunamadı'
                ]);
            }
            
            // URL ve path değerlerini al
            $url = $media->url;
            $path = $media->path;
            
            \Log::debug('MediaPicker getMediaUrl: Medya bulundu', [
                'media_id' => $mediaId,
                'url' => $url,
                'path' => $path
            ]);
            
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
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
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
