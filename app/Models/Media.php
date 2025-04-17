<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_name',
        'file_path',
        'file_type',
        'file_size',
        'mime_type',
        'disk',
        'collection_name',
        'custom_properties',
        'uploaded_by'
    ];

    protected $casts = [
        'custom_properties' => 'array',
    ];

    /**
     * Dosyayı yükleyen kullanıcı
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Dosyanın tam URL'ini döndürür
     */
    public function getUrlAttribute(): string
    {
        return asset($this->file_path);
    }
}
