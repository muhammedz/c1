<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuickMenuCategory extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'icon',
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
     * Bu kategoriye ait menü öğeleri
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(QuickMenuItem::class, 'category_id')->orderBy('order');
    }
    
    /**
     * Sadece aktif öğeleri getir
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activeItems()
    {
        return $this->hasMany(QuickMenuItem::class, 'category_id')
            ->where('is_active', true)
            ->orderBy('order');
    }
    
    /**
     * Aktif kategorileri getir
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function active()
    {
        return self::where('is_active', true)->orderBy('order');
    }
}
