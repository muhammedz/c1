<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use UniSharp\LaravelFilemanager\Controllers\UploadController;
use Illuminate\Support\Str;

class FileManagerController extends UploadController
{
    /**
     * Config değişkenlerini yükle
     */
    protected function loadConfig()
    {
        $this->config = config('lfm');
        return $this->config;
    }
    
    protected function error($error_type, $variables = []) 
    {
        Log::channel('daily')->error('File Manager Upload Error:', [
            'error' => $error_type,
            'variables' => $variables,
            'request' => request()->all(),
            'files' => request()->file(),
            'user' => auth()->user(),
            'server' => request()->server()
        ]);
        
        return parent::error($error_type, $variables);
    }
    
    public function upload()
    {
        Log::channel('daily')->debug('File Manager Upload Started:', [
            'request' => request()->all(),
            'files' => request()->file(),
            'headers' => request()->headers->all(),
            'user' => auth()->user()
        ]);
        
        try {
            // Konfigürasyon yükle
            $this->loadConfig();
            
            $result = $this->uploadWithUniqueFilename();
            
            Log::channel('daily')->debug('File Manager Upload Result:', [
                'result' => $result
            ]);
            
            return $result;
        } catch (\Exception $e) {
            Log::channel('daily')->error('File Manager Upload Exception:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            throw $e;
        }
    }
    
    /**
     * Benzersiz dosya adı ile yükleme işlemini gerçekleştirir
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    protected function uploadWithUniqueFilename()
    {
        $files = request()->file('upload');
        
        // Tek dosya yüklenmişse onu diziye çevir
        if (!is_array($files)) {
            $files = [$files];
        }
        
        $result = [];
        
        foreach ($files as $file) {
            $this->validateFile($file);
            
            // Dosyanın orijinal adını al
            $originalFileName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $filenameWithoutExt = pathinfo($originalFileName, PATHINFO_FILENAME);
            
            // Yüklenecek klasör yolunu al
            $uploadPath = parent::getPath();
            $uploadDir = public_path($uploadPath);
            
            // Güvenli dosya adı oluştur
            $safeFileName = $this->generateSafeFileName($filenameWithoutExt, $extension, $uploadDir);
            
            // Dosya adını değiştir
            $filename = $safeFileName;
            
            // Dosyayı taşı
            $file->move($uploadDir, $filename);
            
            // Tam URL oluştur
            $url = $this->getFileUrl($uploadPath . '/' . $filename);
            
            $result[] = $this->responseWithSuccess(trans('laravel-filemanager::lfm.upload-success'), $url);
        }
        
        // Eğer tek dosya yüklenmişse ilk sonucu döndür
        if (count($result) === 1) {
            return $result[0];
        }
        
        return $this->responseWithSuccess(trans('laravel-filemanager::lfm.upload-success'), $result);
    }
    
    /**
     * Güvenli ve benzersiz dosya adı oluşturur
     * 
     * @param string $filename Dosya adı (uzantısız)
     * @param string $extension Dosya uzantısı
     * @param string $directory Hedef dizin
     * @return string
     */
    protected function generateSafeFileName($filename, $extension, $directory)
    {
        // Dosya adını güvenli hale getir (Türkçe karakterler, boşluklar, özel karakterler)
        $safeFileName = Str::slug($filename);
        
        // Uzun dosya adlarını kısalt
        if (strlen($safeFileName) > 50) {
            $safeFileName = substr($safeFileName, 0, 50);
        }
        
        // Unix timestamp ekle (aynı isimde dosya olasılığını azaltır)
        $safeFileName = $safeFileName . '_' . time();
        
        // Tam dosya adı (uzantılı)
        $fullFileName = $safeFileName . '.' . $extension;
        
        // Aynı isimde dosya var mı kontrol et
        $counter = 1;
        while (file_exists($directory . '/' . $fullFileName)) {
            $fullFileName = $safeFileName . '_' . $counter . '.' . $extension;
            $counter++;
        }
        
        return $fullFileName;
    }
    
    /**
     * Dosya URL'i oluşturur
     * 
     * @param string $path Dosya yolu
     * @return string
     */
    protected function getFileUrl($path)
    {
        // storage yollarını uploads olarak değiştir
        if (strpos($path, '/storage/') === 0) {
            $path = str_replace('/storage/', '/uploads/', $path);
        }
        
        // images yollarını photos olarak değiştir
        if (strpos($path, '/images/') !== false) {
            $path = str_replace('/images/', '/photos/', $path);
        }
        
        return asset($path);
    }
    
    /**
     * Dosya doğrulama işlemleri - parent'ın metodu override edilmiştir
     *
     * @param  object $file
     * @return null|string
     */
    protected function validateFile($file)
    {
        if (empty($file)) {
            return $this->error('file-empty');
        }

        $fileName = $file->getClientOriginalName();
        $fileSize = $file->getSize() / 1000;
        $extension = $file->getClientOriginalExtension();

        if (!$this->validExtension($extension)) {
            return $this->error('mime-type', ['mime' => $extension]);
        }

        if (!$this->validSize($fileSize)) {
            return $this->error('size-limit', ['size' => $fileSize, 'max' => $this->config['max_size']]);
        }

        // Burada özel olarak dosya adı kontrolünü kaldırıyoruz
        // Çünkü dosya adını benzersiz olarak kendimiz oluşturacağız
        // if (!$this->validName($fileName)) {
        //     return $this->error('invalid-name');
        // }

        return 'valid';
    }
    
    /**
     * Dosya uzantısının geçerli olup olmadığını kontrol eder
     *
     * @param  string  $extension
     * @return boolean
     */
    protected function validExtension($extension)
    {
        $extension = strtolower($extension);
        $allowedExtensions = $this->config['valid_file_extensions'];
        
        if (in_array($extension, $allowedExtensions)) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Dosya boyutunun geçerli olup olmadığını kontrol eder
     *
     * @param  integer  $size
     * @return boolean
     */
    protected function validSize($size)
    {
        $maxSize = $this->config['max_size'];
        
        if ($maxSize == 0 || $size < $maxSize) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Başarılı cevap döndürür
     *
     * @param  string  $message
     * @param  mixed   $result
     * @return mixed
     */
    public function responseWithSuccess($message, $result = null)
    {
        return response()->json([
            'result' => [
                'success' => true,
                'message' => $message,
                'result'  => $result,
            ],
        ]);
    }
} 