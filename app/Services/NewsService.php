<?php

namespace App\Services;

use App\Models\News;
use App\Repositories\NewsRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
            
            DB::commit();
            
            return $news;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Haber oluşturma hatası: ' . $e->getMessage());
            return null;
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
            
            // Yayın tarihi
            if (isset($data['published_at']) && $data['published_at']) {
                $data['is_scheduled'] = strtotime($data['published_at']) > time();
            } else {
                $data['published_at'] = now();
                $data['is_scheduled'] = false;
            }
            
            // Manşet kontrolü
            if (isset($data['is_headline']) && $data['is_headline']) {
                if (!$news->is_headline && News::maxHeadlinesReached()) {
                    throw new \Exception('Maksimum manşet sayısına ulaşıldı (4).');
                }
                
                if (!$news->is_headline) {
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
            
            DB::commit();
            
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Haber güncelleme hatası: ' . $e->getMessage());
            return false;
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
            if ($news->end_date) {
                $news->end_date = null;
            } else {
                $news->end_date = now();
            }
            
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
} 