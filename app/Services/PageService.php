<?php

namespace App\Services;

use App\Models\Page;
use App\Repositories\PageRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PageService
{
    /**
     * @var PageRepository
     */
    protected $pageRepository;
    
    /**
     * PageService constructor.
     * 
     * @param PageRepository $pageRepository
     */
    public function __construct(PageRepository $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }
    
    /**
     * Sayfaları filtrelerle getir
     * 
     * @param array $filters
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getPages(array $filters)
    {
        return $this->pageRepository->getFilteredPages($filters);
    }
    
    /**
     * Yeni sayfa oluştur
     * 
     * @param array $data
     * @return Page|null
     */
    public function createPage(array $data)
    {
        try {
            DB::beginTransaction();
            
            // Kategori kontrolü ekleyelim
            \Illuminate\Support\Facades\Log::info('PageService createPage kategori kontrolü: ', [
                'categories' => $data['categories'] ?? 'yok'
            ]);
            
            // Kategoriler yoksa boş dizi olarak ayarla
            if (!isset($data['categories'])) {
                $data['categories'] = [];
            }
            
            // Yayın tarihi
            if (isset($data['published_at']) && $data['published_at']) {
                // is_scheduled sütunu olmadığı için kaldırıldı
                // $data['is_scheduled'] = strtotime($data['published_at']) > time();
            } else {
                $data['published_at'] = now();
                // is_scheduled sütunu olmadığı için kaldırıldı
                // $data['is_scheduled'] = false;
            }
            
            // Öne çıkarma
            if (isset($data['is_featured']) && $data['is_featured']) {
                if (Page::maxFeaturedPagesReached()) {
                    throw new \Exception('Maksimum öne çıkan sayfa sayısına ulaşıldı (4).');
                }
                
                $data['is_featured'] = true;
                // featured_order sütunu olmadığı için kaldırıldı
                // $data['featured_order'] = Page::where('is_featured', true)->count() + 1;
            } else {
                $data['is_featured'] = false;
                // featured_order sütunu olmadığı için kaldırıldı
                // $data['featured_order'] = null;
            }
            
            // Sayfayı oluştur
            $page = $this->pageRepository->createPage($data);
            
            // Etiketleri ekle
            if (isset($data['tags']) && $data['tags']) {
                $this->pageRepository->syncTags($page, $data['tags']);
            }
            
            DB::commit();
            
            return $page;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Sayfa oluşturma hatası: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e; // Hatayı yukarı fırlat ki kullanıcıya gösterebilelim
        }
    }
    
    /**
     * Sayfayı güncelle
     * 
     * @param array $data
     * @param int $id
     * @return bool
     */
    public function updatePage(array $data, $id)
    {
        try {
            DB::beginTransaction();
            
            // Başlangıç debug
            Log::info('updatePage başlangıç: ', [
                'page_id' => $id,
                'data' => $data,
                'slug_exists' => isset($data['slug']),
                'slug_value' => $data['slug'] ?? 'yok',
                'gallery_exists' => isset($data['gallery']),
                'gallery_type' => isset($data['gallery']) ? gettype($data['gallery']) : 'yok',
                'gallery_count' => isset($data['gallery']) && is_array($data['gallery']) ? count($data['gallery']) : 0
            ]);
            
            $page = $this->pageRepository->find($id);
            
            if (!$page) {
                throw new \Exception('Sayfa bulunamadı');
            }
            
            // Sayfanın güncellemeden önceki mevcut gallery değeri
            Log::info('Güncellemeden önce mevcut gallery değeri: ', [
                'page_gallery' => $page->gallery,
                'page_gallery_type' => gettype($page->gallery),
                'page_gallery_count' => is_array($page->gallery) ? count($page->gallery) : 0
            ]);
            
            // Yayın tarihi
            if (isset($data['published_at']) && $data['published_at']) {
                $data['is_scheduled'] = strtotime($data['published_at']) > time();
            } else {
                $data['published_at'] = now();
                $data['is_scheduled'] = false;
            }
            
            // Öne çıkarma kontrolü
            if (isset($data['is_featured']) && $data['is_featured']) {
                if (!$page->is_featured && Page::maxFeaturedPagesReached()) {
                    throw new \Exception('Maksimum öne çıkan sayfa sayısına ulaşıldı (4).');
                }
                
                if (!$page->is_featured) {
                    $data['featured_order'] = Page::where('is_featured', true)->count() + 1;
                }
            } else {
                $data['is_featured'] = false;
                $data['featured_order'] = null;
                
                // Eğer daha önce öne çıkan ise, diğer öne çıkan sayfaların sırasını güncelle
                if ($page->is_featured) {
                    $oldOrder = $page->featured_order;
                    Page::where('is_featured', true)
                        ->where('featured_order', '>', $oldOrder)
                        ->decrement('featured_order');
                }
            }
            
            // Gallery değerlerini kontrol et ve düzelt (eğer varsa)
            if (isset($data['gallery']) && is_array($data['gallery'])) {
                Log::info('Gallery güncelleniyor: ', [
                    'gallery_values' => $data['gallery'],
                    'count' => count($data['gallery'])
                ]);
                
                // Boş değerleri filtrele
                $data['gallery'] = array_filter($data['gallery'], function($item) {
                    return !empty($item);
                });
                
                Log::info('Gallery filtrelendikten sonra: ', [
                    'gallery_filtered' => $data['gallery'],
                    'count_after_filter' => count($data['gallery'])
                ]);
            } else {
                Log::warning('Gallery verisi yok veya dizi değil!', [
                    'gallery_in_data' => isset($data['gallery']),
                    'gallery_type' => isset($data['gallery']) ? gettype($data['gallery']) : 'yok'
                ]);
            }
            
            // Sayfayı güncelle
            $result = $this->pageRepository->updatePage($data, $id);
            
            // Güncelleme sonrası durumu logla
            $updatedPage = $this->pageRepository->find($id);
            Log::info('Sayfa güncellendi, gallery durumu: ', [
                'page_gallery_after' => $updatedPage->gallery,
                'page_gallery_type_after' => gettype($updatedPage->gallery),
                'page_gallery_count_after' => is_array($updatedPage->gallery) ? count($updatedPage->gallery) : 0,
                'update_result' => $result
            ]);
            
            if (!$result) {
                throw new \Exception('Sayfa güncellenemedi');
            }
            
            // Etiketleri güncelle
            if (isset($data['tags'])) {
                $this->pageRepository->syncTags($page, $data['tags']);
            }
            
            DB::commit();
            
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Sayfa güncelleme hatası: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
                'data' => $data,
                'page_id' => $id
            ]);
            throw $e; // Hatayı daha üst seviyeye fırlat
        }
    }
    
    /**
     * Sayfayı sil
     * 
     * @param int $id
     * @return bool
     */
    public function deletePage($id)
    {
        try {
            DB::beginTransaction();
            
            $page = $this->pageRepository->find($id);
            
            // Eğer öne çıkan ise, diğer öne çıkan sayfaların sırasını güncelle
            if ($page->is_featured) {
                $oldOrder = $page->featured_order;
                Page::where('is_featured', true)
                    ->where('featured_order', '>', $oldOrder)
                    ->decrement('featured_order');
            }
            
            // Sayfayı sil
            $result = $this->pageRepository->delete($id);
            
            DB::commit();
            
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Sayfa silme hatası: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Öne çıkarma durumunu değiştir
     * 
     * @param Page $page
     * @return bool
     */
    public function toggleFeatured(Page $page)
    {
        try {
            return $this->pageRepository->toggleFeatured($page);
        } catch (\Exception $e) {
            Log::error('Öne çıkarma durumu değiştirme hatası: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Öne çıkan sayfaların sırasını güncelle
     * 
     * @param array $order
     * @return bool
     */
    public function updateFeaturedOrder(array $order)
    {
        try {
            DB::beginTransaction();
            
            foreach ($order as $id => $position) {
                Page::where('id', $id)->update(['featured_order' => $position]);
            }
            
            DB::commit();
            
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Öne çıkan sayfa sırası güncelleme hatası: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Arşiv durumunu değiştir
     * 
     * @param Page $page
     * @return bool
     */
    public function toggleArchive(Page $page)
    {
        try {
            $page->is_archived = !$page->is_archived;
            return $page->save();
        } catch (\Exception $e) {
            Log::error('Arşiv durumu değiştirme hatası: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Durum değiştir (Yayında/Taslak)
     * 
     * @param Page $page
     * @return bool
     */
    public function toggleStatus(Page $page)
    {
        try {
            $page->status = $page->status === 'published' ? 'draft' : 'published';
            return $page->save();
        } catch (\Exception $e) {
            Log::error('Durum değiştirme hatası: ' . $e->getMessage());
            return false;
        }
    }
} 