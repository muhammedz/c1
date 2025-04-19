<?php

namespace App\Models\FileManagerSystem;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaRelation extends Model
{
    use HasFactory;

    protected $table = 'filemanagersystem_relations';

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
    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id');
    }

    /**
     * Polymorphic ilişki - ilişkili model
     */
    public function related()
    {
        return $this->morphTo();
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
}
