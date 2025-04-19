<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaRelation extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_path',
        'related_to',
        'related_id'
    ];

    /**
     * İlişkili dosyanın tam URL'ini döndürür
     */
    public function getUrlAttribute(): string
    {
        return asset($this->file_path);
    }

    /**
     * İlişkili içeriğe morph ilişkisi
     */
    public function relatable()
    {
        return $this->morphTo();
    }

    /**
     * Belirli bir içerik türü ve ID'ye göre filtreler
     */
    public function scopeForContent($query, $relatedTo, $relatedId)
    {
        return $query->where('related_to', $relatedTo)
                     ->where('related_id', $relatedId);
    }
}
