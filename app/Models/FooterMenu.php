<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FooterMenu extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function links()
    {
        return $this->hasMany(FooterMenuLink::class)->orderByRaw('LOWER(title) COLLATE utf8mb4_turkish_ci ASC');
    }

    public function linksByOrder()
    {
        return $this->hasMany(FooterMenuLink::class)->orderBy('order');
    }

    public function activeLinks()
    {
        return $this->hasMany(FooterMenuLink::class)->where('is_active', true)->orderByRaw('LOWER(title) COLLATE utf8mb4_turkish_ci ASC');
    }

    public function activeLinksByOrder()
    {
        return $this->hasMany(FooterMenuLink::class)->where('is_active', true)->orderBy('order');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
