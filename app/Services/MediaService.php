<?php

namespace App\Services;

use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class MediaService
{
    /**
     * Dosyayı yükler ve Media modelini döndürür
     */
    public function upload(UploadedFile $file, string $type = 'general'): Media
    {
        // Dosya bilgilerini al
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $mimeType = $file->getMimeType();
        $size = $file->getSize();

        // Benzersiz dosya adı oluştur
        $filename = $this->createUniqueFilename($originalName);
        
        // Yükleme dizinini belirle
        $uploadPath = $this->getUploadPath($type);
        
        // Dizin yoksa oluştur
        if (!file_exists(public_path($uploadPath))) {
            mkdir(public_path($uploadPath), 0755, true);
        }
        
        // Dosyayı taşı
        $file->move(public_path($uploadPath), $filename);
        
        // Media kaydını oluştur
        $media = Media::create([
            'file_name' => $filename,
            'file_path' => $uploadPath . '/' . $filename,
            'file_type' => $type,
            'file_size' => $size,
            'mime_type' => $mimeType,
            'disk' => 'public',
            'collection_name' => $type,
            'uploaded_by' => auth()->id()
        ]);

        return $media;
    }

    /**
     * Base64 formatındaki dosyayı yükler ve Media modelini döndürür
     */
    public function uploadBase64(string $base64String, string $type = 'general'): Media
    {
        // Base64'ten dosya bilgilerini çıkar
        $fileData = $this->parseBase64($base64String);
        
        // Benzersiz dosya adı oluştur
        $filename = $this->createUniqueFilename('image.' . $fileData['extension']);
        
        // Yükleme dizinini belirle
        $uploadPath = $this->getUploadPath($type);
        
        // Dizin yoksa oluştur
        if (!file_exists(public_path($uploadPath))) {
            mkdir(public_path($uploadPath), 0755, true);
        }
        
        // Dosyayı kaydet
        $path = public_path($uploadPath . '/' . $filename);
        file_put_contents($path, $fileData['data']);
        
        // Dosya boyutunu al
        $size = filesize($path);
        
        // Media kaydını oluştur
        $media = Media::create([
            'file_name' => $filename,
            'file_path' => $uploadPath . '/' . $filename,
            'file_type' => $type,
            'file_size' => $size,
            'mime_type' => $fileData['mime'],
            'disk' => 'public',
            'collection_name' => $type,
            'uploaded_by' => auth()->id()
        ]);

        return $media;
    }

    /**
     * Base64 stringini parse eder
     */
    protected function parseBase64(string $base64String): array
    {
        // data:image/jpeg;base64, kısmını ayır
        $parts = explode(';base64,', $base64String);
        
        if (count($parts) !== 2) {
            throw new \Exception('Geçersiz base64 formatı');
        }
        
        // MIME tipini al
        $mime = str_replace('data:', '', $parts[0]);
        
        // Uzantıyı belirle
        $extension = $this->getExtensionFromMime($mime);
        
        // Base64'ü decode et
        $data = base64_decode($parts[1]);
        
        return [
            'mime' => $mime,
            'extension' => $extension,
            'data' => $data
        ];
    }

    /**
     * MIME tipinden dosya uzantısını belirler
     */
    protected function getExtensionFromMime(string $mime): string
    {
        $map = [
            'image/jpeg' => 'jpg',
            'image/jpg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
            'image/svg+xml' => 'svg',
        ];

        return $map[$mime] ?? 'jpg';
    }

    /**
     * Benzersiz dosya adı oluşturur
     */
    protected function createUniqueFilename(string $originalName): string
    {
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $filename = Str::slug(pathinfo($originalName, PATHINFO_FILENAME));
        
        // Dosya adını oluştur: slug_timestamp.uzanti
        return $filename . '_' . time() . '.' . $extension;
    }

    /**
     * Dosya tipine göre yükleme dizini belirler
     */
    protected function getUploadPath(string $type): string
    {
        $baseUploadPath = 'uploads';
        
        $paths = [
            'slider' => $baseUploadPath . '/sliders',
            'general' => $baseUploadPath . '/general',
            'page' => $baseUploadPath . '/pages',
            'service' => $baseUploadPath . '/services',
            'event' => $baseUploadPath . '/events',
        ];

        return $paths[$type] ?? $baseUploadPath . '/' . $type;
    }

    /**
     * Media için URL döndürür
     */
    public function getUrl(Media $media): string
    {
        return asset($media->file_path);
    }

    /**
     * Media kaydını ve dosyayı siler
     */
    public function delete(Media $media): bool
    {
        $filePath = public_path($media->file_path);
        
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        return $media->delete();
    }
} 