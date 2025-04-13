<?php

namespace App\Services;

use App\Models\Service;
use App\Repositories\ServiceRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ServiceService
{
    /**
     * @var ServiceRepository
     */
    protected $serviceRepository;
    
    /**
     * ServiceService constructor.
     * 
     * @param ServiceRepository $serviceRepository
     */
    public function __construct(ServiceRepository $serviceRepository)
    {
        $this->serviceRepository = $serviceRepository;
    }
    
    /**
     * Hizmetleri filtrelerle getir
     * 
     * @param array $filters
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getServices(array $filters)
    {
        return $this->serviceRepository->getFilteredServices($filters);
    }
    
    /**
     * Yeni hizmet oluştur
     * 
     * @param array $data
     * @return Service|null
     */
    public function createService(array $data)
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
                if (Service::maxHeadlinesReached()) {
                    throw new \Exception('Maksimum manşet sayısına ulaşıldı (4).');
                }
                
                $data['is_headline'] = true;
                $data['headline_order'] = Service::where('is_headline', true)->count() + 1;
            } else {
                $data['is_headline'] = false;
                $data['headline_order'] = null;
            }
            
            // Öne çıkarma
            $data['is_featured'] = isset($data['is_featured']) && $data['is_featured'] ? true : false;
            
            // Hizmeti oluştur
            $service = $this->serviceRepository->createService($data);
            
            // Etiketleri ekle
            if (isset($data['tags']) && $data['tags']) {
                $this->serviceRepository->syncTags($service, $data['tags']);
            }
            
            DB::commit();
            
            return $service;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Hizmet oluşturma hatası: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Hizmeti güncelle
     * 
     * @param array $data
     * @param int $id
     * @return bool
     */
    public function updateService(array $data, $id)
    {
        try {
            DB::beginTransaction();
            
            $service = $this->serviceRepository->find($id);
            
            // Yayın tarihi
            if (isset($data['published_at']) && $data['published_at']) {
                $data['is_scheduled'] = strtotime($data['published_at']) > time();
            } else {
                $data['published_at'] = now();
                $data['is_scheduled'] = false;
            }
            
            // Manşet kontrolü
            if (isset($data['is_headline']) && $data['is_headline']) {
                if (!$service->is_headline && Service::maxHeadlinesReached()) {
                    throw new \Exception('Maksimum manşet sayısına ulaşıldı (4).');
                }
                
                if (!$service->is_headline) {
                    $data['headline_order'] = Service::where('is_headline', true)->count() + 1;
                }
            } else {
                $data['is_headline'] = false;
                $data['headline_order'] = null;
                
                // Eğer daha önce manşet ise, diğer manşetlerin sırasını güncelle
                if ($service->is_headline) {
                    $oldOrder = $service->headline_order;
                    Service::where('is_headline', true)
                        ->where('headline_order', '>', $oldOrder)
                        ->decrement('headline_order');
                }
            }
            
            // Öne çıkarma
            $data['is_featured'] = isset($data['is_featured']) && $data['is_featured'] ? true : false;
            
            // Hizmeti güncelle
            $result = $this->serviceRepository->updateService($data, $id);
            
            // Etiketleri güncelle
            if (isset($data['tags'])) {
                $this->serviceRepository->syncTags($service, $data['tags']);
            }
            
            DB::commit();
            
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Hizmet güncelleme hatası: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Hizmeti sil
     * 
     * @param int $id
     * @return bool
     */
    public function deleteService($id)
    {
        try {
            DB::beginTransaction();
            
            $service = $this->serviceRepository->find($id);
            
            // Eğer manşet ise, diğer manşetlerin sırasını güncelle
            if ($service->is_headline) {
                $oldOrder = $service->headline_order;
                Service::where('is_headline', true)
                    ->where('headline_order', '>', $oldOrder)
                    ->decrement('headline_order');
            }
            
            // Hizmeti sil
            $result = $this->serviceRepository->delete($id);
            
            DB::commit();
            
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Hizmet silme hatası: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Manşet durumunu değiştir
     * 
     * @param Service $service
     * @return bool
     */
    public function toggleHeadline(Service $service)
    {
        try {
            return $this->serviceRepository->toggleHeadline($service);
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
                Service::where('id', $id)->update(['headline_order' => $position]);
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
     * @param Service $service
     * @return bool
     */
    public function toggleFeatured(Service $service)
    {
        try {
            $service->is_featured = !$service->is_featured;
            return $service->save();
        } catch (\Exception $e) {
            Log::error('Öne çıkarma değiştirme hatası: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Arşiv durumunu değiştir
     * 
     * @param Service $service
     * @return bool
     */
    public function toggleArchive(Service $service)
    {
        try {
            if ($service->status == 'archived') {
                $service->status = 'published';
            } else {
                $service->status = 'archived';
            }
            
            return $service->save();
        } catch (\Exception $e) {
            Log::error('Arşiv durumu değiştirme hatası: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Yayın durumunu değiştir
     * 
     * @param Service $service
     * @return bool
     */
    public function toggleStatus(Service $service)
    {
        try {
            $service->status = $service->status == 'published' ? 'draft' : 'published';
            return $service->save();
        } catch (\Exception $e) {
            Log::error('Yayın durumu değiştirme hatası: ' . $e->getMessage());
            return false;
        }
    }
} 