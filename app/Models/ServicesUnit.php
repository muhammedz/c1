<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ServicesUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'order',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($unit) {
            if (empty($unit->slug)) {
                $unit->slug = Str::slug($unit->name);
            }
        });
    }

    public function services()
    {
        return $this->hasMany(Service::class, 'services_unit_id');
    }
} 