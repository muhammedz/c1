<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuCategory extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'menusystem_id',
        'name',
        'url',
        'order',
        'status'
    ];
    
    // Bir alt başlık bir menüye aittir
    public function menusystem()
    {
        return $this->belongsTo(MenuSystem::class, 'menusystem_id');
    }
    
    // Bir alt başlığın birçok alt menüsü olabilir
    public function items()
    {
        return $this->hasMany(MenuItem::class, 'menu_category_id')->orderBy('order');
    }
}
