<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuSystemItem extends Model
{
    use HasFactory;

    protected $table = 'menu_system_items';

    protected $fillable = [
        'menu_id',
        'parent_id',
        'title',
        'url',
        'order',
        'status',
        'new_tab',
        'icon',
        'target',
        'description',
        'properties'
    ];

    protected $casts = [
        'status' => 'boolean',
        'new_tab' => 'boolean',
        'properties' => 'array',
    ];

    protected $appends = [
        'full_url',
        'has_children'
    ];

    /**
     * Bu öğeye ait menüyü getirir
     */
    public function menu()
    {
        return $this->belongsTo(MenuSystem::class, 'menu_id');
    }

    /**
     * Bu öğenin ebeveynini (üst öğe) getirir
     */
    public function parent()
    {
        return $this->belongsTo(MenuSystemItem::class, 'parent_id');
    }

    /**
     * Bu öğeye ait alt öğeleri getirir
     */
    public function children()
    {
        return $this->hasMany(MenuSystemItem::class, 'parent_id')->orderBy('order');
    }

    /**
     * Bu öğeye ait tüm alt öğeleri recursive olarak getirir
     */
    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }

    /**
     * Bu öğeye ait aktif alt öğeleri getirir
     */
    public function activeChildren()
    {
        return $this->children()->where('status', true);
    }

    /**
     * Bu öğeye ait aktif alt öğeleri recursive olarak getirir
     */
    public function activeChildrenRecursive()
    {
        return $this->activeChildren()->with('activeChildrenRecursive');
    }

    /**
     * Sadece aktif öğeleri getiren scope
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Belirli bir menüye ait öğeleri getiren scope
     */
    public function scopeByMenu($query, $menuId)
    {
        return $query->where('menu_id', $menuId);
    }

    /**
     * Sadece üst seviye öğeleri (parent_id = null) getiren scope
     */
    public function scopeParentItems($query)
    {
        return $query->whereNull('parent_id')->orderBy('order');
    }

    /**
     * Target değerine göre HTML ikonu döndürür
     */
    public function getTargetIcon()
    {
        if ($this->target == '_blank') {
            return '<i class="fas fa-external-link-alt"></i>';
        } elseif ($this->target == '_self') {
            return '<i class="fas fa-link"></i>';
        } elseif ($this->target == 'modal') {
            return '<i class="fas fa-window-maximize"></i>';
        } elseif ($this->target == 'tab') {
            return '<i class="fas fa-tablet-alt"></i>';
        }
        
        return '';
    }

    /**
     * Tam URL oluşturur
     */
    public function getFullUrlAttribute()
    {
        if (filter_var($this->url, FILTER_VALIDATE_URL)) {
            // Tam URL ise olduğu gibi döndür
            return $this->url;
        } elseif (substr($this->url, 0, 1) === '/') {
            // Slash ile başlıyorsa, kök dizinden URL oluştur
            return url($this->url);
        } elseif (!empty($this->url)) {
            // Diğer durumlarda, kök dizine ekle
            return url('/' . $this->url);
        }
        
        return '#';
    }

    /**
     * Alt öğeleri olup olmadığını kontrol eder
     */
    public function getHasChildrenAttribute()
    {
        return $this->children()->count() > 0;
    }

    /**
     * Alt öğelerin sayısını döndürür
     */
    public function getChildrenCountAttribute()
    {
        return $this->children()->count();
    }

    /**
     * Aktif alt öğelerin sayısını döndürür
     */
    public function getActiveChildrenCountAttribute()
    {
        return $this->children()->where('status', true)->count();
    }
} 