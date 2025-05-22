<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchPopularQuery extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'url',
        'icon',
        'order',
        'is_active',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];
    
    /**
     * Get active popular queries ordered by order field
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getActiveQueries($limit = null)
    {
        $query = self::where('is_active', true)
            ->orderBy('order', 'asc');
            
        if ($limit) {
            $query->limit($limit);
        }
        
        return $query->get();
    }
}
