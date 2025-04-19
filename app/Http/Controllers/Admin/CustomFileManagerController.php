<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MediaRelation;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class CustomFileManagerController extends Controller
{
    /**
     * Belirli bir içeriğe ait dosyaları listele
     */
    public function getContentFiles(Request $request)
    {
        // Parametre kontrolleri
        $relatedTo = $request->input('related_to');
        $relatedId = $request->input('related_id');
        
        // Debug log
        \Log::info('Content Files istendi', [
            'related_to' => $relatedTo,
            'related_id' => $relatedId,
            'request' => $request->all(),
            'url' => $request->fullUrl()
        ]);
        
        if (!$relatedTo || !$relatedId) {
            \Log::warning('Content Files parametreleri eksik', [
                'related_to' => $relatedTo,
                'related_id' => $relatedId
            ]);
            
            return response()->json([
                'error' => 'İçerik tipi ve ID parametreleri gereklidir'
            ], 400);
        }
        
        // İlgili içeriğe ait dosyaları bul
        $query = MediaRelation::where('related_to', $relatedTo)
            ->where('related_id', $relatedId)
            ->orderBy('created_at', 'desc');
            
        // Debug query
        \Log::info('Content Files sorgusu', [
            'query_sql' => $query->toSql(),
            'query_bindings' => $query->getBindings()
        ]);
        
        $files = $query->get();
        
        \Log::info('Bulunan dosya sayısı: ' . $files->count());
        
        // İlgili içeriğe ait dosya yoksa, ve ID 'temp' ise veya slider olarak gelen istekler için örnek dosyalar ekleyin
        if (($files->count() == 0 && $relatedId == 'temp') || ($files->count() == 0 && $relatedTo == 'slider')) {
            // Slider seçimi için örnek dosyalar ekle
            \Log::info('Örnek medya dosyaları ekleniyor...');
            
            // Örnek dosya yolları
            $possiblePaths = [
                '/uploads/photos/slide1.jpg',
                '/uploads/photos/slide2.jpg',
                '/images/slider1.jpg',
                '/images/image1.jpg'
            ];
            
            // Eğer public klasöründe bu dosyalar yoksa, varsayılan Laravel logosu ekleyin
            $publicFiles = [];
            foreach ($possiblePaths as $path) {
                if (file_exists(public_path($path))) {
                    $publicFiles[] = $path;
                }
            }
            
            // Eğer hiç dosya bulunamadıysa, vendor/adminlte içinden örnek dosya ekle
            if (empty($publicFiles)) {
                // AdminLTE veya Laravel'in varsayılan görselleri
                $vendorPaths = [
                    '/vendor/adminlte/dist/img/AdminLTELogo.png',
                    '/img/logo.png'
                ];
                
                foreach ($vendorPaths as $path) {
                    if (file_exists(public_path($path))) {
                        $publicFiles[] = $path;
                    }
                }
            }
            
            // Bulunan dosyaları MediaRelation'a ekle
            foreach ($publicFiles as $path) {
                \Log::info('Örnek dosya ekleniyor: ' . $path);
                
                // Aynı dosya daha önce eklenmişse tekrar ekleme
                $exists = MediaRelation::where('file_path', $path)
                    ->where('related_to', $relatedTo)
                    ->where('related_id', $relatedId)
                    ->exists();
                    
                if (!$exists) {
                    MediaRelation::create([
                        'file_path' => $path,
                        'related_to' => $relatedTo,
                        'related_id' => $relatedId
                    ]);
                }
            }
            
            // Eklenen örnek dosyaları tekrar sorgula
            $files = MediaRelation::where('related_to', $relatedTo)
                ->where('related_id', $relatedId)
                ->orderBy('created_at', 'desc')
                ->get();
        }
            
        $result = [];
        
        foreach ($files as $file) {
            // Dosya bilgilerini hazırla
            $fileInfo = [
                'name' => basename($file->file_path),
                'url' => asset($file->file_path), // asset ile tam URL oluştur
                'path' => $file->file_path,
                'time' => $file->created_at->format('Y-m-d H:i:s'),
                'size' => $this->getFileSize($file->file_path),
                'is_file' => true,
                'is_image' => $this->isImage($file->file_path),
                'icon' => $this->getFileIcon($file->file_path),
            ];
            
            // Eğer dosya bir görsel ise thumb_url ekle
            if ($fileInfo['is_image']) {
                $fileInfo['thumb_url'] = $fileInfo['url'];
            }
            
            $result[] = $fileInfo;
        }
        
        \Log::info('İşlenmiş dosya listesi:', ['count' => count($result), 'first_item' => !empty($result) ? $result[0] : null]);
        
        return response()->json([
            'result' => [
                'items' => $result,
                'paginator' => null
            ]
        ]);
    }
    
    /**
     * Dosya boyutunu al
     */
    private function getFileSize($path)
    {
        $fullPath = public_path($path);
        
        if (File::exists($fullPath)) {
            $bytes = File::size($fullPath);
            
            if ($bytes >= 1073741824) {
                return number_format($bytes / 1073741824, 2) . ' GB';
            } elseif ($bytes >= 1048576) {
                return number_format($bytes / 1048576, 2) . ' MB';
            } elseif ($bytes >= 1024) {
                return number_format($bytes / 1024, 2) . ' KB';
            } else {
                return $bytes . ' bytes';
            }
        }
        
        return '0 bytes';
    }
    
    /**
     * Dosya bir görsel mi kontrol et
     */
    private function isImage($path)
    {
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
        
        return in_array(strtolower($extension), $imageExtensions);
    }
    
    /**
     * Dosya türüne göre ikon al
     */
    private function getFileIcon($path)
    {
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        
        switch (strtolower($extension)) {
            case 'pdf':
                return 'fa-file-pdf';
            case 'doc':
            case 'docx':
                return 'fa-file-word';
            case 'xls':
            case 'xlsx':
                return 'fa-file-excel';
            case 'ppt':
            case 'pptx':
                return 'fa-file-powerpoint';
            case 'zip':
            case 'rar':
                return 'fa-file-archive';
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
            case 'bmp':
            case 'webp':
                return 'fa-file-image';
            default:
                return 'fa-file';
        }
    }
    
    /**
     * Özel File Manager sayfasını göster
     */
    public function index(Request $request)
    {
        $relatedTo = $request->input('related_to', '');
        $relatedId = $request->input('related_id', '');
        $type = $request->input('type', 'image');
        
        \Log::info('Custom File Manager sayfası açıldı', [
            'related_to' => $relatedTo,
            'related_id' => $relatedId,
            'type' => $type
        ]);
        
        return view('admin.filemanager.custom', compact('relatedTo', 'relatedId', 'type'));
    }
    
    /**
     * Dosya yükleme işlemi
     */
    public function upload(Request $request)
    {
        $relatedTo = $request->input('related_to');
        $relatedId = $request->input('related_id');
        
        \Log::info('Dosya yükleme isteği alındı', [
            'related_to' => $relatedTo,
            'related_id' => $relatedId
        ]);
        
        // Laravel File Manager'ın kendi upload işlemini kullan
        $fileManagerController = new \App\Http\Controllers\FileManagerController();
        $result = $fileManagerController->upload();
        
        \Log::info('Yükleme sonucu', ['result' => $result]);
        
        // Eğer yükleme başarılıysa ve ilişki bilgileri varsa, veritabanına kaydet
        if (isset($result->original['result']) && $relatedTo && $relatedId) {
            $uploadedPath = $result->original['result'];
            
            // Storage yolunu uploads olarak düzelt
            if (strpos($uploadedPath, '/storage/') !== false) {
                $uploadedPath = str_replace('/storage/', '/uploads/', $uploadedPath);
            }
            
            // MediaRelation tablosuna kaydet
            MediaRelation::create([
                'file_path' => $uploadedPath,
                'related_to' => $relatedTo,
                'related_id' => $relatedId
            ]);
            
            \Log::info('Dosya ilişkisi kaydedildi', [
                'file_path' => $uploadedPath,
                'related_to' => $relatedTo,
                'related_id' => $relatedId
            ]);
        }
        
        return $result;
    }
    
    /**
     * Medya ilişkisi oluştur veya güncelle
     */
    public function saveMediaRelation(Request $request)
    {
        $request->validate([
            'file_path' => 'required|string',
            'related_to' => 'required|string',
            'related_id' => 'required|string'
        ]);
        
        \Log::info('Medya ilişkisi kaydetme isteği', $request->all());
        
        // Dosya yolu formatını düzelt
        $filePath = $request->input('file_path');
        if (strpos($filePath, '/storage/') !== false) {
            $filePath = str_replace('/storage/', '/uploads/', $filePath);
        }
        
        // Eğer aynı dosya ilişkisi zaten varsa, tekrar oluşturma
        $exists = MediaRelation::where('file_path', $filePath)
            ->where('related_to', $request->input('related_to'))
            ->where('related_id', $request->input('related_id'))
            ->exists();
            
        if (!$exists) {
            $relation = MediaRelation::create([
                'file_path' => $filePath,
                'related_to' => $request->input('related_to'),
                'related_id' => $request->input('related_id')
            ]);
            
            \Log::info('Yeni medya ilişkisi kaydedildi', [
                'id' => $relation->id,
                'file_path' => $filePath
            ]);
        } else {
            \Log::info('Medya ilişkisi zaten var, tekrar kaydedilmedi', [
                'file_path' => $filePath
            ]);
        }
        
        return response()->json(['success' => true]);
    }
}
