<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TinyMCEController extends Controller
{
    /**
     * TinyMCE editörü için resim yükleme
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request)
    {
        try {
            if (!$request->hasFile('file')) {
                return response()->json([
                    'error' => 'Dosya bulunamadı!'
                ], 400);
            }

            $file = $request->file('file');
            $originalFilename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '_' . time();
            $extension = $file->getClientOriginalExtension();
            $uploadPath = 'uploads/tinymce';
            
            // Benzersiz dosya adı oluştur
            $filename = $this->createUniqueFilename($uploadPath, $originalFilename, $extension);
            
            // log
            Log::channel('daily')->debug('TinyMCE Upload Attempt:', [
                'filename' => $filename,
                'original_name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime' => $file->getMimeType(),
            ]);

            // Dosyayı public/uploads/tinymce klasörüne kaydet
            $file->move(public_path($uploadPath), $filename);
            $path = $uploadPath . '/' . $filename;
            
            // Asset fonksiyonu ile URL oluştur
            $url = asset($path);

            Log::channel('daily')->debug('TinyMCE Upload Success:', [
                'path' => $path,
                'url' => $url
            ]);

            return response()->json([
                'location' => $url
            ]);
        } catch (\Exception $e) {
            Log::error('TinyMCE Upload Failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'error' => 'Dosya yüklenirken bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Benzersiz dosya adı oluştur
     * Eğer aynı isimde dosya varsa sonuna sayı ekler (örn: resim_1.jpg, resim_2.jpg)
     *
     * @param string $path Dizin yolu
     * @param string $filename Dosya adı (uzantısız)
     * @param string $extension Dosya uzantısı
     * @return string Benzersiz dosya adı (uzantı dahil)
     */
    private function createUniqueFilename($path, $filename, $extension)
    {
        $fullFilename = $filename . '.' . $extension;
        $fullPath = public_path($path . '/' . $fullFilename);
        
        if (!file_exists($fullPath)) {
            return $fullFilename;
        }
        
        $counter = 1;
        while (file_exists($fullPath)) {
            $fullFilename = $filename . '_' . $counter . '.' . $extension;
            $fullPath = public_path($path . '/' . $fullFilename);
            $counter++;
        }
        
        return $fullFilename;
    }
} 