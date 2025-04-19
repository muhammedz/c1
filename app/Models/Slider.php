<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Slider extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'subtitle',
        'button_text',
        'button_url',
        'order',
        'is_active',
        'filemanagersystem_image',
        'filemanagersystem_image_alt',
        'filemanagersystem_image_title'
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer'
    ];
    
    /**
     * Slider'a ait medya ilişkilerini getir
     */
    public function mediaRelations()
    {
        return MediaRelation::where('related_to', 'slider')
            ->where('related_id', $this->id);
    }

    /**
     * Slider görselinin tam URL'ini döndürür
     */
    public function getFilemanagersystemImageUrlAttribute(): ?string
    {
        if (empty($this->filemanagersystem_image)) {
            return null;
        }
        
        // Eğer URL zaten tam bir URL ise (http:// ile başlıyorsa) direkt döndür
        if (strpos($this->filemanagersystem_image, 'http://') === 0 || strpos($this->filemanagersystem_image, 'https://') === 0) {
            return $this->filemanagersystem_image;
        }
        
        // Dosya uzantısını kontrol et
        $extension = pathinfo($this->filemanagersystem_image, PATHINFO_EXTENSION);
        $filename = pathinfo($this->filemanagersystem_image, PATHINFO_BASENAME);
        
        // Klasör yapısı kontrolleri
        
        // 1. Eski yapı (uploads/dosyaadi.jpg) - Klasör yapısı olmayan
        if (preg_match('#^uploads/([^/]+)$#', $this->filemanagersystem_image, $matches)) {
            $dosyaAdi = $matches[1];
            
            // Dosya türünü belirle
            $folderPath = $this->determineFileType($extension);
            
            // Düzeltilmiş yol
            $fixedPath = "uploads/{$folderPath}/{$dosyaAdi}";
            
            // Dosya fiziksel olarak bu yolda var mı kontrol et
            if (file_exists(public_path($fixedPath))) {
                return asset($fixedPath);
            }
        }
        
        // 2. Tam yol olması gereken format (uploads/images/dosya.jpg)
        if (preg_match('#^uploads/(images|documents|videos|audios|archives)/[^/]+$#', $this->filemanagersystem_image)) {
            // Doğru format, direkt asset olarak döndür
            return asset($this->filemanagersystem_image);
        }
        
        // 3. Klasör adı olmayan format (images/dosya.jpg)
        if (preg_match('#^(images|documents|videos|audios|archives)/[^/]+$#', $this->filemanagersystem_image)) {
            // 'uploads/' ekle
            return asset('uploads/' . $this->filemanagersystem_image);
        }
        
        // Hiçbir kural uymaması durumunda en son çare olarak
        return asset($this->filemanagersystem_image);
    }
    
    /**
     * Dosya uzantısına göre klasör türünü belirler
     */
    private function determineFileType($extension)
    {
        $extension = strtolower($extension);
        
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'];
        $videoExtensions = ['mp4', 'mov', 'avi', 'webm', 'flv', 'mkv'];
        $audioExtensions = ['mp3', 'wav', 'ogg', 'aac', 'flac'];
        $archiveExtensions = ['zip', 'rar', 'tar', 'gz', '7z'];
        
        if (in_array($extension, $imageExtensions)) {
            return 'images';
        } elseif (in_array($extension, $videoExtensions)) {
            return 'videos';
        } elseif (in_array($extension, $audioExtensions)) {
            return 'audios';
        } elseif (in_array($extension, $archiveExtensions)) {
            return 'archives';
        } else {
            return 'documents';
        }
    }

    /**
     * Aktif slider'ları filtrele
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Sıralamaya göre slider'ları getir
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
