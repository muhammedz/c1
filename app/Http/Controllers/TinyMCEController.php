<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
            $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '_' . time() . '.' . $file->getClientOriginalExtension();
            
            // log
            Log::channel('daily')->debug('TinyMCE Upload Attempt:', [
                'filename' => $filename,
                'original_name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime' => $file->getMimeType(),
            ]);

            // Dosyayı public/uploads klasörüne kaydet
            $path = $file->storeAs('uploads/tinymce', $filename, 'public');
            
            $url = Storage::disk('public')->url($path);

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
} 