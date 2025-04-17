<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'image',
        'button_text',
        'button_url',
        'order',
        'is_active'
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];
    
    /**
     * Aktif slider'ları getir
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function active()
    {
        return self::where('is_active', true)->orderBy('order');
    }

    /**
     * Get the image URL
     */
    public function getImageUrlAttribute()
    {
        if (empty($this->image)) {
            return null;
        }

        // Eğer tam URL ise
        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }

        // Eğer /storage/ ile başlıyorsa, asset() ile çevir
        if (strpos($this->image, '/storage/') === 0) {
            return asset(str_replace('/storage/', 'storage/', $this->image));
        }
        
        // Eğer /images/ içeriyorsa, /photos/ ile değiştir
        if (strpos($this->image, '/images/') !== false) {
            $path = str_replace('/images/', '/photos/', $this->image);
            return asset($path);
        }

        // Eğer /uploads/ ile başlıyorsa
        if (strpos($this->image, '/uploads/') === 0) {
            // Başındaki slash'ı kaldır
            return asset(ltrim($this->image, '/'));
        }

        // Eğer bir dosya yolu ise uploads/ ile birleştir
        if (!str_starts_with($this->image, '/')) {
            return asset('uploads/' . $this->image);
        }

        // Varsayılan olarak olduğu gibi döndür ama başındaki slash'ı kaldır
        return asset(ltrim($this->image, '/'));
    }
}
