<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use UniSharp\LaravelFilemanager\Controllers\UploadController;

class FileManagerController extends UploadController
{
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
            $result = parent::upload();
            
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
} 