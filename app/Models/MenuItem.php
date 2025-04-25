<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'menu_category_id',
        'name',
        'url',
        'icon',
        'order',
        'status'
    ];
    
    // Bir alt menü bir alt başlığa aittir
    public function category()
    {
        return $this->belongsTo(MenuCategory::class, 'menu_category_id');
    }
}
