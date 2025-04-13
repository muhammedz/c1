<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class NewsTag extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'slug',
        'usage_count'
    ];
    
    // Slug otomatik oluşturma
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }
    
    // İlişkiler
    
    // Etiketteki haberler
    public function news()
    {
        return $this->belongsToMany(News::class, 'news_tag', 'tag_id', 'news_id')
                    ->withTimestamps();
    }
    
    // Yardımcı metodlar
    
    // Popüler etiketleri getir
    public static function getPopular($limit = 20)
    {
        return self::orderBy('usage_count', 'desc')
                   ->take($limit)
                   ->get();
    }
    
    // Etiket kullanım sayısını arttır
    public function incrementUsage()
    {
        $this->increment('usage_count');
    }
    
    // Etiket kullanım sayısını azalt
    public function decrementUsage()
    {
        if ($this->usage_count > 0) {
            $this->decrement('usage_count');
        }
    }
    
    // İsimden etiket bul veya oluştur
    public static function findOrCreateByName($name)
    {
        $tag = self::where('name', $name)->first();
        
        if (!$tag) {
            $tag = self::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'usage_count' => 1
            ]);
        } else {
            $tag->incrementUsage();
        }
        
        return $tag;
    }
} 