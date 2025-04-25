<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MenuSystem extends Model
{
    use HasFactory;
    
    protected $table = 'menu_systems';
    
    protected $fillable = [
        'name',
        'slug',
        'type',
        'position',
        'url',
        'order',
        'status',
        'description',
        'properties',
        'footer_text',
        'footer_link'
    ];
    
    protected $casts = [
        'status' => 'boolean',
        'properties' => 'array',
    ];

    /**
     * "boot" metodu ile model olaylarını dinle
     */
    protected static function boot()
    {
        parent::boot();

        // Kayıt edilmeden önce, eğer slug boşsa otomatik olarak oluştur
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });

        // Güncellemeden önce, eğer isim değiştiyse ve slug elle değiştirilmediyse, slug'ı güncelle
        static::updating(function ($model) {
            if ($model->isDirty('name') && !$model->isDirty('slug')) {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    // Bir menünün birçok alt başlığı olabilir
    public function categories()
    {
        return $this->hasMany(MenuCategory::class, 'menusystem_id')->orderBy('order');
    }
    
    // Bir menünün bir açıklaması olabilir
    public function description()
    {
        return $this->hasOne(MenuDescription::class, 'menusystem_id');
    }

    /**
     * Menüye ait tüm öğeleri getirir
     */
    public function items()
    {
        return $this->hasMany(MenuSystemItem::class, 'menu_id');
    }

    /**
     * Menüye ait sadece üst seviye öğeleri getirir
     */
    public function parentItems()
    {
        return $this->items()->whereNull('parent_id')->orderBy('order');
    }

    /**
     * Menüye ait tüm aktif öğeleri getirir
     */
    public function activeItems()
    {
        return $this->items()->where('status', true);
    }

    /**
     * Menüye ait actif üst seviye öğeleri getirir
     */
    public function activeParentItems()
    {
        return $this->parentItems()->where('status', true);
    }

    /**
     * Menü tipi için insan dostu adı döndürür
     */
    public function getTypeNameAttribute()
    {
        $types = [
            1 => 'Ana Menü',
            2 => 'Alt Menü',
            3 => 'Buton Menü',
            4 => 'Footer Menüsü',
            5 => 'Mobil Menü',
            6 => 'Sidebar Menü',
            7 => 'Özel Menü'
        ];

        return $types[$this->type] ?? 'Bilinmeyen';
    }

    /**
     * Menü pozisyonu için insan dostu adı döndürür
     */
    public function getPositionNameAttribute()
    {
        $positions = [
            'header' => 'Üst Menü',
            'footer' => 'Alt Menü',
            'sidebar' => 'Yan Menü',
            'mobile' => 'Mobil Menü',
            'main' => 'Ana Menü',
            'top' => 'En Üst Menü',
            'bottom' => 'En Alt Menü'
        ];

        return $positions[$this->position] ?? 'Bilinmeyen';
    }

    /**
     * Sadece aktif menüleri getiren scope
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Slug'a göre menü bulan scope
     */
    public function scopeBySlug($query, $slug)
    {
        return $query->where('slug', $slug);
    }

    /**
     * Pozisyona göre menüleri getiren scope
     */
    public function scopeByPosition($query, $position)
    {
        return $query->where('position', $position);
    }

    /**
     * Menü tipine göre filtreleyen scope
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Menüde aktif öğe sayısını getirir
     */
    public function getActiveItemsCountAttribute()
    {
        return $this->items()->where('status', true)->count();
    }

    /**
     * Menüyü HTML olarak render eden metot
     */
    public function render($options = [])
    {
        $defaults = [
            'class' => 'menu',
            'item_class' => 'menu-item',
            'active_class' => 'active',
            'dropdown_class' => 'has-dropdown',
            'dropdown_toggle' => '<i class="fas fa-angle-down"></i>',
            'template' => 'default', // default, bootstrap, tailwind, vb.
        ];

        $options = array_merge($defaults, $options);
        
        // Menü öğelerini al
        $items = $this->activeParentItems()->with('childrenRecursive')->get();
        
        // Eğer öğe yoksa boş döndür
        if ($items->isEmpty()) {
            return '';
        }

        // Tema şablonuna göre menüyü render et
        $viewPath = 'menus.templates.' . $options['template'];
        if (view()->exists($viewPath)) {
            return view($viewPath, [
                'menu' => $this,
                'items' => $items,
                'options' => $options
            ])->render();
        }

        // Varsayılan şablon
        return view('menus.templates.default', [
            'menu' => $this,
            'items' => $items,
            'options' => $options
        ])->render();
    }

    /**
     * Menüyü JSON formatında döndürür
     */
    public function toJson($options = 0)
    {
        $data = $this->toArray();
        $data['items'] = $this->activeParentItems()->with('childrenRecursive')->get();
        
        return json_encode($data, $options);
    }
}
