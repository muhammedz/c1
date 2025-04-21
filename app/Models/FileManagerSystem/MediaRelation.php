<?php

namespace App\Models\FileManagerSystem;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MediaRelation extends Model
{
    use HasFactory;

    protected $table = 'filemanagersystem_media_relations';

    protected $fillable = [
        'media_id',
        'related_id',
        'related_type',
        'field_name',
        'order',
        'custom_properties'
    ];

    protected $casts = [
        'order' => 'integer',
        'custom_properties' => 'json',
    ];

    /**
     * İlişkilendirilen medya dosyası
     */
    public function media(): BelongsTo
    {
        \Log::debug('MediaRelation Model media() metodu çağrıldı', [
            'media_id' => $this->media_id,
            'tablo' => $this->getTable(),
            'ilişki_değerleri' => [
                'related_type' => $this->related_type,
                'related_id' => $this->related_id
            ]
        ]);
        return $this->belongsTo(Media::class, 'media_id');
    }

    /**
     * Polymorphic ilişki - ilişkili model
     */
    public function related(): MorphTo
    {
        return $this->morphTo('related', 'related_type', 'related_id');
    }

    /**
     * Belirli bir alan adı için ilişkileri sorgulamak için scope
     */
    public function scopeForField($query, $fieldName)
    {
        return $query->where('field_name', $fieldName);
    }

    /**
     * Belirli bir içerik tipi için ilişkileri sorgulamak için scope
     */
    public function scopeForRelatedType($query, $relatedType)
    {
        return $query->where('related_type', $relatedType);
    }
    
    /**
     * İlişki tipine göre sınıf adını döndürür
     */
    public static function getRelatedClass($relatedType)
    {
        $map = [
            'homepage_slider' => \App\Models\Slider::class,
            'corporate_category' => \App\Models\CorporateCategory::class,
            'corporate_member' => \App\Models\CorporateMember::class,
            'news' => \App\Models\News::class,
            // Diğer ilişki tipleri burada tanımlanabilir
        ];
        
        return $map[$relatedType] ?? null;
    }
}
