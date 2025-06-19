<?php

namespace App\Services;

use App\Models\News;
use App\Repositories\NewsRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

class NewsService
{
    /**
     * @var NewsRepository
     */
    protected $newsRepository;
    
    /**
     * NewsService constructor.
     * 
     * @param NewsRepository $newsRepository
     */
    public function __construct(NewsRepository $newsRepository)
    {
        $this->newsRepository = $newsRepository;
    }
    
    /**
     * Haberleri filtrelerle getir
     * 
     * @param array $filters
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getNews(array $filters)
    {
        return $this->newsRepository->getFilteredNews($filters);
    }
    
    /**
     * Yeni haber oluştur
     * 
     * @param array $data
     * @return News|null
     */
    public function createNews(array $data)
    {
        try {
            DB::beginTransaction();
            
            // Yayın tarihi
            if (isset($data['published_at']) && $data['published_at']) {
                $data['is_scheduled'] = strtotime($data['published_at']) > time();
            } else {
                $data['published_at'] = now();
                $data['is_scheduled'] = false;
            }
            
            // Manşet kontrolü
            if (isset($data['is_headline']) && $data['is_headline']) {
                if (News::maxHeadlinesReached()) {
                    throw new \Exception('Maksimum manşet sayısına ulaşıldı (4).');
                }
                
                $data['is_headline'] = true;
                $data['headline_order'] = News::where('is_headline', true)->count() + 1;
            } else {
                $data['is_headline'] = false;
                $data['headline_order'] = null;
            }
            
            // Öne çıkarma
            $data['is_featured'] = isset($data['is_featured']) && $data['is_featured'] ? true : false;
            
            // Haberi oluştur
            $news = $this->newsRepository->createNews($data);
            
            // Etiketleri ekle
            if (isset($data['tags']) && $data['tags']) {
                $this->newsRepository->syncTags($news, $data['tags']);
            }
            
            // İlişkili medya varsa ilişkiyi kur
            if (!empty($data['filemanagersystem_image'])) {
                $this->createMediaRelationForNews($news, $data['filemanagersystem_image']);
            }
            
            // Galeri ilişkilerini kur
            if (!empty($data['filemanagersystem_gallery'])) {
                $galleryData = $data['filemanagersystem_gallery'];
                
                // JSON string ise decode et
                if (is_string($galleryData)) {
                    $galleryData = json_decode($galleryData, true);
                }
                
                if (is_array($galleryData) && !empty($galleryData)) {
                    $this->createGalleryMediaRelations($news, $galleryData);
                }
            }
            
            DB::commit();
            
            return $news;
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Daha detaylı loglama
            $errorDetail = 'Haber oluşturma hatası: ' . $e->getMessage();
            $errorContext = [
                'error_class' => get_class($e),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString(),
                'data' => json_encode($data, JSON_UNESCAPED_UNICODE)
            ];
            
            Log::error($errorDetail, $errorContext);
            
            // Hata mesajını daha açıklayıcı hale getir
            if (strpos($e->getMessage(), 'SQLSTATE[23000]') !== false && strpos($e->getMessage(), 'Duplicate entry') !== false) {
                $errorDetail = 'Aynı başlık veya slug değeri ile bir haber zaten mevcut. Lütfen başlığı değiştirin.';
            } elseif (strpos($e->getMessage(), 'SQLSTATE[23000]') !== false && strpos($e->getMessage(), 'foreign key constraint') !== false) {
                $errorDetail = 'İlişkili bir kayıt bulunamadı. Lütfen seçtiğiniz değerleri kontrol edin.';
            } elseif (strpos($e->getMessage(), 'SQLSTATE[HY000]') !== false && strpos($e->getMessage(), 'General error') !== false) {
                $errorDetail = 'Veritabanı işlemi sırasında genel bir hata oluştu. Lütfen form verilerini kontrol edin.';
            } elseif (strpos($e->getMessage(), 'Call to a member function') !== false) {
                $errorDetail = 'Uygulama hatası: Geçersiz nesne referansı. Lütfen daha sonra tekrar deneyin.';
            }
            
            Log::error('Kullanıcıya gösterilen hata: ' . $errorDetail);
            
            throw new \Exception($errorDetail, 0, $e);
        }
    }
    
    /**
     * Haberi güncelle
     * 
     * @param array $data
     * @param int $id
     * @return bool
     */
    public function updateNews(array $data, $id)
    {
        try {
            DB::beginTransaction();
            
            $news = $this->newsRepository->find($id);
            
            // Hata ayıklama için gelen verileri loglama
            Log::info('Güncelleme verileri: ', [
                'id' => $id,
                'is_headline_original' => $news->is_headline,
                'is_headline_request' => isset($data['is_headline']) ? $data['is_headline'] : 'not_set',
                'all_data' => $data
            ]);
            
            // Veritabanında olmayan alanları çıkar
            if (isset($data['filemanagersystem_image_alt'])) {
                unset($data['filemanagersystem_image_alt']);
            }
            if (isset($data['filemanagersystem_image_title'])) {
                unset($data['filemanagersystem_image_title']);
            }
            
            // Yayın tarihi
            if (isset($data['published_at']) && $data['published_at']) {
                $data['is_scheduled'] = strtotime($data['published_at']) > time();
            } else {
                $data['published_at'] = now();
                $data['is_scheduled'] = false;
            }
            
            // Manşet kontrolü
            if (isset($data['is_headline']) && $data['is_headline']) {
                // Boolean değere dönüştür
                $data['is_headline'] = filter_var($data['is_headline'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ? true : false;
                
                if ($data['is_headline'] && !$news->is_headline && News::maxHeadlinesReached()) {
                    throw new \Exception('Maksimum manşet sayısına ulaşıldı (4).');
                }
                
                if ($data['is_headline'] && !$news->is_headline) {
                    $data['headline_order'] = News::where('is_headline', true)->count() + 1;
                }
            } else {
                $data['is_headline'] = false;
                $data['headline_order'] = null;
                
                // Eğer daha önce manşet ise, diğer manşetlerin sırasını güncelle
                if ($news->is_headline) {
                    $oldOrder = $news->headline_order;
                    News::where('is_headline', true)
                        ->where('headline_order', '>', $oldOrder)
                        ->decrement('headline_order');
                }
            }
            
            // Öne çıkarma
            $data['is_featured'] = isset($data['is_featured']) && $data['is_featured'] ? true : false;
            
            // Haberi güncelle
            $result = $this->newsRepository->updateNews($data, $id);
            
            // Etiketleri güncelle
            if (isset($data['tags'])) {
                $this->newsRepository->syncTags($news, $data['tags']);
            }
            
            // İlişkili medya varsa ilişkiyi güncelle
            if (!empty($data['filemanagersystem_image'])) {
                $this->updateMediaRelationForNews($news, $data['filemanagersystem_image']);
            }
            
            // Galeri ilişkilerini güncelle
            if (!empty($data['filemanagersystem_gallery'])) {
                $galleryData = $data['filemanagersystem_gallery'];
                
                // JSON string ise decode et
                if (is_string($galleryData)) {
                    $galleryData = json_decode($galleryData, true);
                }
                
                if (is_array($galleryData) && !empty($galleryData)) {
                    $this->updateGalleryMediaRelations($news, $galleryData);
                }
            }
            
            DB::commit();
            
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Daha detaylı loglama
            $errorDetail = 'Haber güncelleme hatası: ' . $e->getMessage();
            $errorContext = [
                'error_class' => get_class($e),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString(),
                'data' => json_encode($data, JSON_UNESCAPED_UNICODE),
                'id' => $id
            ];
            
            Log::error($errorDetail, $errorContext);
            
            // Hata mesajını daha açıklayıcı hale getir
            if (strpos($e->getMessage(), 'SQLSTATE[23000]') !== false && strpos($e->getMessage(), 'Duplicate entry') !== false) {
                $errorDetail = 'Aynı başlık veya slug değeri ile bir haber zaten mevcut. Lütfen başlığı değiştirin.';
            } elseif (strpos($e->getMessage(), 'SQLSTATE[23000]') !== false && strpos($e->getMessage(), 'foreign key constraint') !== false) {
                $errorDetail = 'İlişkili bir kayıt bulunamadı. Lütfen seçtiğiniz değerleri kontrol edin.';
            } elseif (strpos($e->getMessage(), 'SQLSTATE[HY000]') !== false && strpos($e->getMessage(), 'General error') !== false) {
                $errorDetail = 'Veritabanı işlemi sırasında genel bir hata oluştu. Lütfen form verilerini kontrol edin.';
            } elseif (strpos($e->getMessage(), 'Call to a member function') !== false) {
                $errorDetail = 'Uygulama hatası: Geçersiz nesne referansı. Lütfen daha sonra tekrar deneyin.';
            } elseif (strpos($e->getMessage(), 'SQLSTATE[42S22]') !== false && strpos($e->getMessage(), 'Column not found') !== false) {
                $errorDetail = 'Veritabanı şeması güncel değil. Yöneticiye başvurun.';
            }
            
            Log::error('Kullanıcıya gösterilen hata: ' . $errorDetail);
            
            throw new \Exception($errorDetail, 0, $e);
        }
    }
    
    /**
     * Haberi sil
     * 
     * @param int $id
     * @return bool
     */
    public function deleteNews($id)
    {
        try {
            DB::beginTransaction();
            
            $news = $this->newsRepository->find($id);
            
            // Eğer manşet ise, diğer manşetlerin sırasını güncelle
            if ($news->is_headline) {
                $oldOrder = $news->headline_order;
                News::where('is_headline', true)
                    ->where('headline_order', '>', $oldOrder)
                    ->decrement('headline_order');
            }
            
            // Haberi sil
            $result = $this->newsRepository->delete($id);
            
            DB::commit();
            
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Haber silme hatası: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Manşet durumunu değiştir
     * 
     * @param News $news
     * @return bool
     */
    public function toggleHeadline(News $news)
    {
        try {
            return $this->newsRepository->toggleHeadline($news);
        } catch (\Exception $e) {
            Log::error('Manşet durumu değiştirme hatası: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Manşet sırasını güncelle
     * 
     * @param array $order
     * @return bool
     */
    public function updateHeadlineOrder(array $order)
    {
        try {
            DB::beginTransaction();
            
            foreach ($order as $id => $position) {
                News::where('id', $id)->update(['headline_order' => $position]);
            }
            
            DB::commit();
            
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Manşet sırası güncelleme hatası: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Öne çıkarma durumunu değiştir
     * 
     * @param News $news
     * @return bool
     */
    public function toggleFeatured(News $news)
    {
        try {
            $news->is_featured = !$news->is_featured;
            return $news->save();
        } catch (\Exception $e) {
            Log::error('Öne çıkarma değiştirme hatası: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Arşiv durumunu değiştir
     * 
     * @param News $news
     * @return bool
     */
    public function toggleArchive(News $news)
    {
        try {
            $news->is_archived = !$news->is_archived;
            return $news->save();
        } catch (\Exception $e) {
            Log::error('Arşiv durumu değiştirme hatası: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Durum değiştir (Yayında/Taslak)
     * 
     * @param News $news
     * @return bool
     */
    public function toggleStatus(News $news)
    {
        try {
            $news->status = $news->status === 'published' ? 'draft' : 'published';
            return $news->save();
        } catch (\Exception $e) {
            Log::error('Durum değiştirme hatası: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Ana görsel için medya ilişkisi oluşturur
     */
    private function createMediaRelationForNews(News $news, string $filemanagersystemImage)
    {
        try {
            // Medya ID'sini bul
            $mediaId = null;
            
            // 1. /uploads/media/123 formatı
            if (preg_match('#^/uploads/media/(\d+)$#', $filemanagersystemImage, $matches)) {
                $mediaId = $matches[1];
            }
            // 2. /admin/filemanagersystem/media/preview/123 formatı
            elseif (preg_match('#/media/preview/(\d+)#', $filemanagersystemImage, $matches)) {
                $mediaId = $matches[1];
            }
            
            if ($mediaId) {
                // Mevcut ilişkiyi kontrol et
                $existingRelation = \App\Models\FileManagerSystem\MediaRelation::where('media_id', $mediaId)
                    ->where('related_type', 'news')
                    ->where('related_id', $news->id)
                    ->where('field_name', 'featured_image')
                    ->first();
                
                // İlişki yoksa oluştur
                if (!$existingRelation) {
                    $mediaRelation = new \App\Models\FileManagerSystem\MediaRelation();
                    $mediaRelation->media_id = $mediaId;
                    $mediaRelation->related_type = 'news';
                    $mediaRelation->related_id = $news->id;
                    $mediaRelation->field_name = 'featured_image';
                    $mediaRelation->order = 0;
                    $mediaRelation->save();
                }
            }
        } catch (\Exception $e) {
            \Log::error('Haber medya ilişkisi oluşturma hatası: ' . $e->getMessage(), [
                'news_id' => $news->id,
                'filemanagersystem_image' => $filemanagersystemImage,
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Galerisi ilişkilerini oluşturur
     */
    private function createGalleryMediaRelations(News $news, array $galleryItems)
    {
        try {
            foreach ($galleryItems as $index => $item) {
                // Medya ID'sini çıkart
                $mediaId = null;
                
                if (is_array($item) && isset($item['id'])) {
                    $mediaId = $item['id'];
                } elseif (is_string($item)) {
                    // URL'den ID'yi çıkart
                    if (preg_match('#^/uploads/media/(\d+)$#', $item, $matches)) {
                        $mediaId = $matches[1];
                    } elseif (preg_match('#/media/preview/(\d+)#', $item, $matches)) {
                        $mediaId = $matches[1];
                    }
                }
                
                if ($mediaId) {
                    // Galeri için ilişki oluştur
                    $mediaRelation = new \App\Models\FileManagerSystem\MediaRelation();
                    $mediaRelation->media_id = $mediaId;
                    $mediaRelation->related_type = 'news';
                    $mediaRelation->related_id = $news->id;
                    $mediaRelation->field_name = 'gallery';
                    $mediaRelation->order = $index;
                    $mediaRelation->save();
                }
            }
        } catch (\Exception $e) {
            \Log::error('Haber galeri ilişkisi oluşturma hatası: ' . $e->getMessage(), [
                'news_id' => $news->id,
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Ana görsel ilişkisini günceller
     */
    private function updateMediaRelationForNews(News $news, string $filemanagersystemImage)
    {
        try {
            // Önce mevcut ana görsel ilişkilerini sil
            \App\Models\FileManagerSystem\MediaRelation::where('related_type', 'news')
                ->where('related_id', $news->id)
                ->where('field_name', 'featured_image')
                ->delete();
            
            // Yeni ilişki oluştur
            $this->createMediaRelationForNews($news, $filemanagersystemImage);
            
        } catch (\Exception $e) {
            \Log::error('Haber medya ilişkisi güncelleme hatası: ' . $e->getMessage(), [
                'news_id' => $news->id,
                'filemanagersystem_image' => $filemanagersystemImage,
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Galeri ilişkilerini günceller
     */
    private function updateGalleryMediaRelations(News $news, array $galleryItems)
    {
        try {
            // Önce mevcut galeri ilişkilerini sil
            \App\Models\FileManagerSystem\MediaRelation::where('related_type', 'news')
                ->where('related_id', $news->id)
                ->where('field_name', 'gallery')
                ->delete();
            
            // Yeni ilişkileri oluştur
            $this->createGalleryMediaRelations($news, $galleryItems);
            
        } catch (\Exception $e) {
            \Log::error('Haber galeri ilişkisi güncelleme hatası: ' . $e->getMessage(), [
                'news_id' => $news->id,
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Galeri resmi yükle
     * 
     * @param UploadedFile $file
     * @return string|bool
     */
    public function uploadGalleryImage($file)
    {
        try {
            $originalFilename = time() . '_' . Str::random(10);
            $extension = $file->getClientOriginalExtension();
            $uploadPath = 'uploads/news/gallery';
            
            // Benzersiz dosya adı oluştur
            $filename = $this->createUniqueFilename($uploadPath, $originalFilename, $extension);
            
            $file->move(public_path($uploadPath), $filename);
            $path = $uploadPath . '/' . $filename;
            
            return asset($path);
        } catch (\Exception $e) {
            Log::error('Galeri resmi yükleme hatası: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Benzersiz dosya adı oluştur
     * 
     * @param string $path Dizin yolu
     * @param string $filename Dosya adı (uzantısız)
     * @param string $extension Dosya uzantısı
     * @return string Benzersiz dosya adı (uzantı dahil)
     */
    private function createUniqueFilename($path, $filename, $extension)
    {
        $fullFilename = $filename . '.' . $extension;
        $fullPath = public_path($path . '/' . $fullFilename);
        
        if (!file_exists($fullPath)) {
            return $fullFilename;
        }
        
        $counter = 1;
        while (file_exists($fullPath)) {
            $fullFilename = $filename . '_' . $counter . '.' . $extension;
            $fullPath = public_path($path . '/' . $fullFilename);
            $counter++;
        }
        
        return $fullFilename;
    }
    
    /**
     * Toplu işlemleri gerçekleştir
     * 
     * @param array $ids Haber ID'leri
     * @param string $action İşlem türü (delete, publish, draft, archive)
     * @return array
     */
    public function handleBulkAction(array $ids, string $action)
    {
        try {
            DB::beginTransaction();
            
            $successCount = 0;
            $errorCount = 0;
            
            switch ($action) {
                case 'delete':
                    foreach ($ids as $id) {
                        try {
                            if ($this->deleteNews($id)) {
                                $successCount++;
                            } else {
                                $errorCount++;
                            }
                        } catch (\Exception $e) {
                            Log::error('Toplu silme hatası (ID: ' . $id . '): ' . $e->getMessage());
                            $errorCount++;
                        }
                    }
                    
                    $message = $successCount . ' haber başarıyla silindi.';
                    if ($errorCount > 0) {
                        $message .= ' ' . $errorCount . ' haber silinirken hata oluştu.';
                    }
                    break;
                    
                case 'publish':
                    $updated = News::whereIn('id', $ids)->update(['status' => 'published']);
                    $message = $updated . ' haber başarıyla yayınlandı.';
                    $successCount = $updated;
                    break;
                    
                case 'draft':
                    $updated = News::whereIn('id', $ids)->update(['status' => 'draft']);
                    $message = $updated . ' haber başarıyla taslak durumuna alındı.';
                    $successCount = $updated;
                    break;
                    
                case 'archive':
                    $updated = News::whereIn('id', $ids)->update(['is_archived' => true]);
                    $message = $updated . ' haber başarıyla arşivlendi.';
                    $successCount = $updated;
                    break;
                    
                default:
                    DB::rollBack();
                    return [
                        'success' => false,
                        'message' => 'Geçersiz işlem türü.'
                    ];
            }
            
            DB::commit();
            
            return [
                'success' => true,
                'message' => $message,
                'successCount' => $successCount,
                'errorCount' => $errorCount
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Toplu işlem hatası: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Toplu işlem sırasında bir hata oluştu: ' . $e->getMessage()
            ];
        }
    }
} 