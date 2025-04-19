<?php

namespace App\Services\FileManagerSystem;

use App\Models\FileManagerSystem\FilemanagersystemMedia;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class FilemanagersystemImageService
{
    public function resize(FilemanagersystemMedia $media, int $width, int $height)
    {
        $image = Image::make(Storage::disk('public')->path($media->file_path));
        
        $image->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        $image->save(Storage::disk('public')->path($media->file_path));
    }

    public function crop(FilemanagersystemMedia $media, int $x, int $y, int $width, int $height)
    {
        $image = Image::make(Storage::disk('public')->path($media->file_path));
        
        $image->crop($width, $height, $x, $y);
        
        $image->save(Storage::disk('public')->path($media->file_path));
    }

    public function rotate(FilemanagersystemMedia $media, int $degrees)
    {
        $image = Image::make(Storage::disk('public')->path($media->file_path));
        
        $image->rotate($degrees);
        
        $image->save(Storage::disk('public')->path($media->file_path));
    }

    public function generateThumbnail(FilemanagersystemMedia $media, string $size)
    {
        $sizes = config('filemanagersystem.thumbnail_sizes', []);
        $sizeConfig = collect($sizes)->firstWhere('name', $size);

        if (!$sizeConfig) {
            throw new \Exception('Belirtilen boyut bulunamadı');
        }

        $image = Image::make(Storage::disk('public')->path($media->file_path));
        
        $image->resize($sizeConfig['width'], $sizeConfig['height'], function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        $thumbnailPath = 'thumbnails/' . $size . '/' . basename($media->file_path);
        Storage::disk('public')->put($thumbnailPath, $image->encode());
    }

    public function setQuality(FilemanagersystemMedia $media, int $quality)
    {
        $image = Image::make(Storage::disk('public')->path($media->file_path));
        
        $image->save(Storage::disk('public')->path($media->file_path), $quality);
    }

    public function getDimensions(FilemanagersystemMedia $media)
    {
        $image = Image::make(Storage::disk('public')->path($media->file_path));
        
        return [
            'width' => $image->width(),
            'height' => $image->height()
        ];
    }
} 