<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProjectImage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'project_id',
        'image_path',
        'title',
        'description',
        'order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'order' => 'integer',
    ];

    /**
     * Bu görselin ait olduğu proje
     */
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    /**
     * Görsel dosya yolunun tam URL'sini döndürür
     */
    public function getImageUrlAttribute()
    {
        if (empty($this->image_path)) {
            return asset('assets/image/project-placeholder.jpg');
        }
        
        if (Str::startsWith($this->image_path, ['http://', 'https://'])) {
            return $this->image_path;
        }
        
        return asset('storage/' . $this->image_path);
    }
} 