<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',          // Galeri tipi (service, project, page vb.)
        'reference_id',  // İlgili kayıt ID'si
        'file_path',     // Dosya yolu
        'original_name', // Orijinal dosya adı
        'file_name',     // Sistemde saklanan dosya adı
        'file_size',     // Dosya boyutu (byte)
        'mime_type',     // MIME türü
        'order',         // Sıralama
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'file_size' => 'integer',
        'order' => 'integer',
    ];

    /**
     * Get the URL of the image
     * 
     * @return string
     */
    public function getUrlAttribute()
    {
        return asset($this->file_path);
    }

    /**
     * Scope a query to only include galleries of a given type.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope a query to only include galleries for a specific record.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $referenceId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForReference($query, $referenceId)
    {
        return $query->where('reference_id', $referenceId);
    }

    /**
     * Order by the order column.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }
} 