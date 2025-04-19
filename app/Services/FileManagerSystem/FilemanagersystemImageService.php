<?php

namespace App\Services\FileManagerSystem;

use App\Models\FileManagerSystem\FilemanagersystemMedia;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Storage;

class FilemanagersystemImageService
{
    public function resize(FilemanagersystemMedia $media, int $width, int $height)
    {
        $image = Image::read(Storage::disk('public')->path($media->file_path));
        
        $image->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        // V3 için encode ve kaydetme
        $format = $this->getFormatFromPath($media->file_path);
        $encodedImage = $this->encodeByFormat($image, $format);
        file_put_contents(Storage::disk('public')->path($media->file_path), $encodedImage);
    }

    public function crop(FilemanagersystemMedia $media, int $x, int $y, int $width, int $height)
    {
        $image = Image::read(Storage::disk('public')->path($media->file_path));
        
        $image->crop($width, $height, $x, $y);
        
        // V3 için encode ve kaydetme
        $format = $this->getFormatFromPath($media->file_path);
        $encodedImage = $this->encodeByFormat($image, $format);
        file_put_contents(Storage::disk('public')->path($media->file_path), $encodedImage);
    }

    public function rotate(FilemanagersystemMedia $media, int $degrees)
    {
        $image = Image::read(Storage::disk('public')->path($media->file_path));
        
        $image->rotate($degrees);
        
        // V3 için encode ve kaydetme
        $format = $this->getFormatFromPath($media->file_path);
        $encodedImage = $this->encodeByFormat($image, $format);
        file_put_contents(Storage::disk('public')->path($media->file_path), $encodedImage);
    }

    public function generateThumbnail(FilemanagersystemMedia $media, string $size)
    {
        $sizes = config('filemanagersystem.thumbnail_sizes', []);
        $sizeConfig = collect($sizes)->firstWhere('name', $size);

        if (!$sizeConfig) {
            throw new \Exception('Belirtilen boyut bulunamadı');
        }

        $image = Image::read(Storage::disk('public')->path($media->file_path));
        
        $image->resize($sizeConfig['width'], $sizeConfig['height'], function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        $thumbnailPath = 'thumbnails/' . $size . '/' . basename($media->file_path);
        
        // V3 için encode ve kaydetme
        $format = $this->getFormatFromPath($media->file_path);
        $encodedImage = $this->encodeByFormat($image, $format);
        Storage::disk('public')->put($thumbnailPath, $encodedImage);
    }

    public function setQuality(FilemanagersystemMedia $media, int $quality)
    {
        $image = Image::read(Storage::disk('public')->path($media->file_path));
        
        // V3 için encode ve kaydetme
        $format = $this->getFormatFromPath($media->file_path);
        $encodedImage = $this->encodeByFormat($image, $format, $quality);
        file_put_contents(Storage::disk('public')->path($media->file_path), $encodedImage);
    }

    public function getDimensions(FilemanagersystemMedia $media)
    {
        $image = Image::read(Storage::disk('public')->path($media->file_path));
        
        return [
            'width' => $image->width(),
            'height' => $image->height()
        ];
    }
    
    /**
     * Format türüne göre doğru encode metodunu kullanır
     */
    private function encodeByFormat($image, $format, $quality = 80)
    {
        switch ($format) {
            case 'jpg':
                return $image->toJpeg($quality);
            case 'png':
                return $image->toPng();
            case 'gif':
                return $image->toGif();
            case 'webp':
                return $image->toWebp($quality);
            default:
                return $image->toJpeg($quality);
        }
    }
    
    /**
     * Dosya yolundan formatı belirler
     */
    private function getFormatFromPath($path)
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                return 'jpg';
            case 'png':
                return 'png';
            case 'gif':
                return 'gif';
            case 'webp':
                return 'webp';
            default:
                return 'jpg'; // Varsayılan format
        }
    }
} 