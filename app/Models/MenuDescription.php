<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuDescription extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'menusystem_id',
        'description',
        'link_text',
        'link_url'
    ];
    
    // Bir açıklama bir menüye aittir
    public function menusystem()
    {
        return $this->belongsTo(MenuSystem::class, 'menusystem_id');
    }
}
