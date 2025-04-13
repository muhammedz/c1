<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventImage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'event_id',
        'image_path',
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
     * Get the event that owns the image.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the full URL for the image.
     */
    public function getImageUrlAttribute()
    {
        if (!$this->image_path) {
            return asset('images/no-image.jpg');
        }
        
        if (Str::startsWith($this->image_path, ['http://', 'https://'])) {
            return $this->image_path;
        }
        
        return asset('storage/' . $this->image_path);
    }
}
