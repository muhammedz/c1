<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mayor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'title',
        'profile_image',
        'biography',
        'social_twitter',
        'social_instagram',
        'social_facebook',
        'social_linkedin',
        'social_email',
        'hero_bg_color',
        'hero_bg_image',
        'page_title',
        'meta_description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Başkanın tüm içerikleri
     */
    public function contents()
    {
        return $this->hasMany(MayorContent::class)->orderBy('sort_order');
    }

    /**
     * Hikayeler
     */
    public function stories()
    {
        return $this->hasMany(MayorContent::class)
            ->where('type', 'story')
            ->where('is_active', true)
            ->orderBy('sort_order');
    }

    /**
     * Gündem
     */
    public function agenda()
    {
        return $this->hasMany(MayorContent::class)
            ->where('type', 'agenda')
            ->where('is_active', true)
            ->orderBy('sort_order');
    }

    /**
     * Değerler
     */
    public function values()
    {
        return $this->hasMany(MayorContent::class)
            ->where('type', 'value')
            ->where('is_active', true)
            ->orderBy('sort_order');
    }

    /**
     * Galeri
     */
    public function gallery()
    {
        return $this->hasMany(MayorContent::class)
            ->where('type', 'gallery')
            ->where('is_active', true)
            ->orderBy('sort_order');
    }

    /**
     * Aktif başkanı getir
     */
    public static function getActive()
    {
        return self::where('is_active', true)->first();
    }

    /**
     * Profil fotoğrafının tam URL'ini getir
     */
    public function getProfileImageUrlAttribute()
    {
        if (!$this->profile_image) {
            return null;
        }

        if (filter_var($this->profile_image, FILTER_VALIDATE_URL)) {
            return $this->profile_image;
        }

        return asset('storage/' . $this->profile_image);
    }

    /**
     * Hero arka plan görselinin tam URL'ini getir
     */
    public function getHeroBgImageUrlAttribute()
    {
        if (!$this->hero_bg_image) {
            return null;
        }

        if (filter_var($this->hero_bg_image, FILTER_VALIDATE_URL)) {
            return $this->hero_bg_image;
        }

        return asset('storage/' . $this->hero_bg_image);
    }
}
