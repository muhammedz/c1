<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileSettings extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'title',
        'profile_photo',
        'filemanagersystem_profile_photo',
        'filemanagersystem_profile_photo_alt',
        'filemanagersystem_profile_photo_title',
        'facebook_url',
        'instagram_url',
        'twitter_url',
        'youtube_url',
        'contact_image',
        'filemanagersystem_contact_image',
        'filemanagersystem_contact_image_alt',
        'filemanagersystem_contact_image_title',
    ];
}
