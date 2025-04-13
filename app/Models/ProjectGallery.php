<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProjectGallery extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'project_gallery';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id',
        'image_path',
        'title',
        'order',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'order' => 'integer',
    ];
    
    /**
     * Get the project that owns the gallery image.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    
    /**
     * Get the full URL for the image.
     */
    public function getImageUrlAttribute()
    {
        if (!$this->image_path) {
            return asset('images/no-image.jpg');
        }
        
        return Storage::url($this->image_path);
    }
} 