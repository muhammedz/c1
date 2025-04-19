<?php

namespace App\Models\FileManagerSystem;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Models\User;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Tablo adı
     *
     * @var string
     */
    protected $table = 'filemanagersystem_categories';

    /**
     * Toplu atama için izin verilen alanlar
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
        'created_by',
        'order',
        'is_active',
    ];

    /**
     * Boolean olarak işlenecek özellikler.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Kategori adını ayarlar ve slug oluşturur.
     *
     * @param  string  $value
     * @return void
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        
        if (!isset($this->attributes['slug']) || empty($this->attributes['slug'])) {
            $this->attributes['slug'] = Str::slug($value);
        }
    }

    /**
     * Kategorinin üst kategorisi ile ilişkisi.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Kategorinin alt kategorileri ile ilişkisi.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Tüm alt kategorileri recursive olarak getirir.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function allChildren()
    {
        return $this->children()->with('allChildren');
    }

    /**
     * Kategorinin medya dosyaları ile ilişkisi.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function medias()
    {
        return $this->belongsToMany(Media::class, 'filemanagersystem_media_categories', 'category_id', 'media_id');
    }

    /**
     * Kategoriyi oluşturan kullanıcı ile ilişkisi.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Tüm üst kategorileri alır.
     *
     * @return array
     */
    public function getAncestors()
    {
        $ancestors = [];
        $current = $this->parent;
        
        while ($current) {
            $ancestors[] = $current;
            $current = $current->parent;
        }
        
        return array_reverse($ancestors);
    }

    /**
     * Kategori ağacını oluşturur.
     *
     * @param  mixed  $categories
     * @param  int|null  $parentId
     * @param  int  $level
     * @return array
     */
    public static function buildTree($categories, $parentId = null, $level = 0)
    {
        $branch = [];
        
        foreach ($categories as $category) {
            if ($category->parent_id == $parentId) {
                $category->level = $level;
                $children = self::buildTree($categories, $category->id, $level + 1);
                
                if ($children) {
                    $category->children = $children;
                }
                
                $branch[] = $category;
            }
        }
        
        return $branch;
    }

    /**
     * Aktif kategorileri döndürür.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
}
