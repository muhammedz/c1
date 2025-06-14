<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Redirect extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_url',
        'to_url',
        'redirect_type',
        'is_active',
        'hit_count',
        'created_by',
        'description'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Oluşturan kullanıcı ilişkisi
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Aktif yönlendirmeleri getir
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * URL'ye göre yönlendirme bul
     */
    public static function findByUrl($url)
    {
        return self::active()->where('from_url', $url)->first();
    }

    /**
     * Hit count artır
     */
    public function incrementHitCount()
    {
        $this->increment('hit_count');
    }

    /**
     * URL'yi temizle (başındaki ve sonundaki slash'leri kaldır)
     */
    public function setFromUrlAttribute($value)
    {
        $this->attributes['from_url'] = '/' . trim($value, '/');
    }

    /**
     * To URL'yi temizle
     */
    public function setToUrlAttribute($value)
    {
        // Eğer tam URL değilse başına / ekle
        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            $value = '/' . ltrim($value, '/');
        }
        $this->attributes['to_url'] = $value;
    }

    /**
     * Yönlendirme tipini insan okunabilir hale getir
     */
    public function getRedirectTypeTextAttribute()
    {
        return $this->redirect_type === '301' ? 'Kalıcı (301)' : 'Geçici (302)';
    }

    /**
     * Aktif/pasif durumunu toggle et
     */
    public function toggleStatus()
    {
        $this->update(['is_active' => !$this->is_active]);
    }
}
