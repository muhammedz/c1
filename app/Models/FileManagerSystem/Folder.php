<?php

namespace App\Models\FileManagerSystem;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Folder extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'filemanagersystem_folders';

    protected $fillable = [
        'folder_name',
        'folder_slug',
        'parent_id',
        'user_id',
        'status',
        'folder_description',
    ];

    /**
     * Name attribute accessor - folder_name'i name olarak kullanabilmek için
     */
    public function getNameAttribute()
    {
        return $this->folder_name;
    }

    /**
     * Path attribute accessor - folder_slug'ı path olarak kullanabilmek için
     */
    public function getPathAttribute()
    {
        return 'folders/' . $this->folder_slug;
    }

    /**
     * Media count accessor
     */
    public function getMediaCountAttribute()
    {
        return $this->medias()->count();
    }

    /**
     * Üst klasör ilişkisi
     */
    public function parent()
    {
        return $this->belongsTo(Folder::class, 'parent_id');
    }

    /**
     * Alt klasörler ilişkisi
     */
    public function children()
    {
        return $this->hasMany(Folder::class, 'parent_id');
    }

    /**
     * Klasör sahibi (kullanıcı) ilişkisi
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Klasördeki medya dosyaları ilişkisi
     */
    public function medias()
    {
        return $this->hasMany(Media::class, 'folder_id');
    }
} 