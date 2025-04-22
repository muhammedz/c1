<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Gallery;

class GalleryService
{
    /**
     * Galeri fotoğraflarını yükler
     *
     * @param array $files Yüklenecek dosyalar
     * @param string $type Galeri tipi (örn: service, project, page)
     * @param int $referenceId İlgili kaydın ID'si
     * @return array Yüklenen dosyaların bilgileri
     */
    public function uploadGalleryImages(array $files, string $type, int $referenceId)
    {
        $uploadedFiles = [];
        
        try {
            foreach ($files as $index => $file) {
                // Dosya geçerli mi kontrol et
                if (!$file->isValid()) {
                    Log::warning('Geçersiz dosya atlandı', ['file' => $file->getClientOriginalName()]);
                    continue;
                }
                
                // Dosya adı oluştur
                $fileName = $this->createUniqueFileName($file);
                
                // Yükleme dizinini belirle
                $uploadPath = $this->getUploadPath($type);
                
                // Dosyayı yükle
                $path = $file->storeAs($uploadPath, $fileName, 'public');
                
                // Gallery kaydı oluştur
                $gallery = Gallery::create([
                    'type' => $type,
                    'reference_id' => $referenceId,
                    'file_path' => 'storage/' . $path,
                    'original_name' => $file->getClientOriginalName(),
                    'file_name' => $fileName,
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'order' => $this->getNextOrder($type, $referenceId),
                ]);
                
                $uploadedFiles[] = $gallery;
            }
            
            return $uploadedFiles;
        } catch (\Exception $e) {
            Log::error('Galeri resmi yükleme hatası: ' . $e->getMessage(), [
                'type' => $type,
                'reference_id' => $referenceId,
                'trace' => $e->getTraceAsString()
            ]);
            
            throw $e;
        }
    }
    
    /**
     * Galeri fotoğrafını siler
     *
     * @param int $galleryId Silinecek galeri kaydının ID'si
     * @return bool İşlem başarılı mı
     */
    public function deleteGalleryImage(int $galleryId)
    {
        try {
            $gallery = Gallery::findOrFail($galleryId);
            
            // Dosyayı sil
            $filePath = str_replace('storage/', '', $gallery->file_path);
            
            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
            
            // Kaydı sil
            $gallery->delete();
            
            return true;
        } catch (\Exception $e) {
            Log::error('Galeri resmi silme hatası: ' . $e->getMessage(), [
                'gallery_id' => $galleryId,
                'trace' => $e->getTraceAsString()
            ]);
            
            return false;
        }
    }
    
    /**
     * Galerilerin sırasını günceller
     *
     * @param array $galleryIds Sıralanmış galeri ID'leri
     * @return bool İşlem başarılı mı
     */
    public function updateGalleryOrder(array $galleryIds)
    {
        try {
            foreach ($galleryIds as $order => $id) {
                Gallery::where('id', $id)->update(['order' => $order + 1]);
            }
            
            return true;
        } catch (\Exception $e) {
            Log::error('Galeri sıralama hatası: ' . $e->getMessage(), [
                'gallery_ids' => $galleryIds,
                'trace' => $e->getTraceAsString()
            ]);
            
            return false;
        }
    }
    
    /**
     * Benzersiz dosya adı oluşturur
     *
     * @param UploadedFile $file Yüklenecek dosya
     * @return string Benzersiz dosya adı
     */
    private function createUniqueFileName(UploadedFile $file)
    {
        $extension = $file->getClientOriginalExtension();
        $baseName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        
        return $baseName . '_' . time() . '_' . Str::random(5) . '.' . $extension;
    }
    
    /**
     * Dosya tipine göre yükleme dizini belirler
     *
     * @param string $type Galeri tipi
     * @return string Yükleme dizini
     */
    private function getUploadPath(string $type)
    {
        return 'uploads/' . $type . '/gallery';
    }
    
    /**
     * Bir sonraki sıra numarasını belirler
     *
     * @param string $type Galeri tipi
     * @param int $referenceId İlgili kaydın ID'si
     * @return int Sıra numarası
     */
    private function getNextOrder(string $type, int $referenceId)
    {
        $maxOrder = Gallery::where('type', $type)
            ->where('reference_id', $referenceId)
            ->max('order');
            
        return ($maxOrder ?? 0) + 1;
    }
} 