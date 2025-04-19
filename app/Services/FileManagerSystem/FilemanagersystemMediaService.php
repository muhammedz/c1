<?php

namespace App\Services\FileManagerSystem;

use App\Models\FileManagerSystem\FilemanagersystemMedia;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class FilemanagersystemMediaService
{
    /**
     * Dosya yükleme işlemini gerçekleştirir.
     *
     * @param UploadedFile $file
     * @param array $data
     * @return FilemanagersystemMedia
     */
    public function upload(UploadedFile $file, array $data = [])
    {
        $this->validateFile($file);

        $fileName = $this->generateFileName($file);
        $filePath = $file->storeAs('uploads', $fileName, 'public');

        $media = FilemanagersystemMedia::create([
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $filePath,
            'file_url' => Storage::disk('public')->url($filePath),
            'file_size' => $file->getSize(),
            'file_type' => $file->getMimeType(),
            'extension' => $file->getClientOriginalExtension(),
            'alt_text' => $data['alt_text'] ?? null,
            'title' => $data['title'] ?? null,
            'description' => $data['description'] ?? null,
            'folder_id' => $data['folder_id'] ?? null,
            'is_public' => $data['is_public'] ?? true,
            'custom_properties' => $data['custom_properties'] ?? null,
            'uploaded_by' => auth()->id()
        ]);

        if ($this->isImage($file)) {
            $this->generateThumbnails($media);
        }

        return $media;
    }

    /**
     * Dosyayı doğrular.
     *
     * @param UploadedFile $file
     * @throws \Exception
     */
    public function validateFile(UploadedFile $file)
    {
        $allowedTypes = config('filemanagersystem.allowed_file_types', []);
        $maxSize = config('filemanagersystem.max_file_size', 0) * 1024 * 1024; // MB to bytes

        if (!in_array($file->getMimeType(), $allowedTypes)) {
            throw new \Exception('Bu dosya tipi desteklenmiyor');
        }

        if ($file->getSize() > $maxSize) {
            throw new \Exception('Dosya boyutu çok büyük');
        }
    }

    /**
     * Benzersiz dosya adı oluşturur.
     *
     * @param UploadedFile $file
     * @return string
     */
    public function generateFileName(UploadedFile $file)
    {
        $extension = $file->getClientOriginalExtension();
        $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $slug = Str::slug($name);
        $uniqueName = $slug . '_' . time() . '.' . $extension;

        return $uniqueName;
    }

    /**
     * Dosyanın resim olup olmadığını kontrol eder.
     *
     * @param UploadedFile|FilemanagersystemMedia $file
     * @return bool
     */
    public function isImage($file)
    {
        if ($file instanceof UploadedFile) {
            return in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/gif']);
        } elseif ($file instanceof FilemanagersystemMedia) {
            return in_array($file->file_type, ['image/jpeg', 'image/png', 'image/gif']);
        }
        return false;
    }

    /**
     * Resim için thumbnail'ler oluşturur.
     *
     * @param FilemanagersystemMedia $media
     * @return void
     */
    public function generateThumbnails(FilemanagersystemMedia $media)
    {
        $sizes = config('filemanagersystem.thumbnail_sizes', []);
        $image = Image::read(Storage::disk('public')->path($media->file_path));

        foreach ($sizes as $size) {
            $width = $size['width'] ?? null;
            $height = $size['height'] ?? null;
            $name = $size['name'] ?? "{$width}x{$height}";

            $thumbnail = $image->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $thumbnailPath = 'thumbnails/' . $name . '/' . basename($media->file_path);
            
            // Format belirleme ve kaydetme - V3 uyumlu
            $format = $this->getFormatFromPath($media->file_path);
            $encodedThumbnail = $this->encodeByFormat($thumbnail, $format);
            Storage::disk('public')->put($thumbnailPath, $encodedThumbnail);
        }
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

    /**
     * Dosyayı siler.
     *
     * @param FilemanagersystemMedia $media
     * @return bool
     */
    public function delete(FilemanagersystemMedia $media)
    {
        // Fiziksel dosyayı sil
        Storage::disk('public')->delete($media->file_path);

        // Thumbnail'ları sil
        if ($this->isImage($media)) {
            $sizes = config('filemanagersystem.thumbnail_sizes', []);
            foreach ($sizes as $size) {
                $name = $size['name'] ?? "{$size['width']}x{$size['height']}";
                $thumbnailPath = 'thumbnails/' . $name . '/' . basename($media->file_path);
                Storage::disk('public')->delete($thumbnailPath);
            }
        }

        // Veritabanı kaydını sil
        $media->delete();
    }

    /**
     * Dosyayı taşır.
     *
     * @param FilemanagersystemMedia $media
     * @param int $folderId
     * @return bool
     */
    public function move(FilemanagersystemMedia $media, int $folderId)
    {
        $media->folder_id = $folderId;
        $media->save();
    }
} 