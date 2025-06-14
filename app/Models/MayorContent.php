<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MayorContent extends Model
{
    use HasFactory;

    protected $table = 'mayor_content';

    protected $fillable = [
        'mayor_id',
        'type',
        'title',
        'description',
        'image',
        'extra_data',
        'sort_order',
        'is_active'
    ];

    protected $casts = [
        'extra_data' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * İçerik türleri
     */
    const TYPE_STORY = 'story';
    const TYPE_AGENDA = 'agenda';
    const TYPE_GALLERY = 'gallery';

    /**
     * Başkan ilişkisi
     */
    public function mayor()
    {
        return $this->belongsTo(Mayor::class);
    }

    /**
     * Görsel URL'ini getir
     */
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }

        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }

        return asset('uploads/' . $this->image);
    }

    /**
     * Türe göre scope
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Aktif içerikler
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Sıralı içerikler
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Hikayeler
     */
    public function scopeStories($query)
    {
        return $query->ofType(self::TYPE_STORY);
    }

    /**
     * Gündem
     */
    public function scopeAgenda($query)
    {
        return $query->ofType(self::TYPE_AGENDA);
    }



    /**
     * Galeri
     */
    public function scopeGallery($query)
    {
        return $query->ofType(self::TYPE_GALLERY);
    }

    /**
     * Extra data'dan belirli bir değeri getir
     */
    public function getExtraValue($key, $default = null)
    {
        return $this->extra_data[$key] ?? $default;
    }

    /**
     * Extra data'ya değer ekle
     */
    public function setExtraValue($key, $value)
    {
        $extraData = $this->extra_data ?? [];
        $extraData[$key] = $value;
        $this->extra_data = $extraData;
        return $this;
    }
}
