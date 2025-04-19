<?php

namespace App\Http\Controllers\FileManagerSystem;

use App\Http\Controllers\Controller;
use App\Models\FileManagerSystem\Folder;
use App\Models\FileManagerSystem\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FilemanagersystemMediaController extends Controller
{
    /**
     * Tüm medya dosyalarını listele
     */
    public function index(Request $request)
    {
        $folderId = $request->input('folder_id');
        
        if ($folderId) {
            $folder = Folder::findOrFail($folderId);
            $medias = Media::where('folder_id', $folderId)->orderBy('name')->paginate(20);
        } else {
            $folder = null;
            $medias = Media::orderBy('name')->paginate(20);
        }
        
        return view('filemanagersystem.medias.index', compact('medias', 'folder'));
    }

    /**
     * Yeni medya dosyası yükleme formunu göster
     */
    public function create(Request $request)
    {
        $folderId = $request->input('folder_id');
        $folder = null;
        
        if ($folderId) {
            $folder = Folder::findOrFail($folderId);
        }
        
        $folders = Folder::orderBy('folder_name')->get();
        
        return view('filemanagersystem.medias.create', compact('folders', 'folder'));
    }

    /**
     * Yeni medya dosyası yükle
     */
    public function store(Request $request)
    {
        $request->validate([
            'files' => 'required|array',
            'files.*' => 'required|file|max:52428800', // 50MB
            'folder_id' => 'nullable|exists:filemanagersystem_folders,id',
            'is_public' => 'boolean',
        ]);
        
        $uploadedFiles = [];
        
        foreach ($request->file('files') as $file) {
            $originalName = $file->getClientOriginalName();
            $extension = strtolower($file->getClientOriginalExtension());
            $mimeType = $file->getMimeType();
            $fileSize = $file->getSize();
            $fileName = Str::random(40) . '.' . $extension;
            
            // Dosya türüne göre klasör belirleme
            $folderPath = $this->getMainFolderByMimeType($mimeType);
            
            // Klasör yoksa oluştur
            $fullPath = public_path('uploads/' . $folderPath);
            if (!file_exists($fullPath)) {
                mkdir($fullPath, 0755, true);
            }
            
            // Dosyayı kaydet
            $path = $file->storeAs($folderPath, $fileName, 'uploads');
            $url = asset('uploads/' . $path);
            
            // Veritabanı kaydı
            $media = new Media();
            $media->name = $fileName;
            $media->original_name = $originalName;
            $media->mime_type = $mimeType;
            $media->extension = $extension;
            $media->size = $fileSize;
            $media->path = $path;
            $media->url = $url;
            $media->user_id = Auth::id();
            $media->folder_id = $request->folder_id;
            $media->is_public = $request->has('is_public') ? $request->is_public : false;
            $media->save();
            
            $uploadedFiles[] = $media;
        }
        
        return redirect()->route('admin.filemanagersystem.media.index', ['folder_id' => $request->folder_id])
            ->with('success', count($uploadedFiles) . ' adet dosya başarıyla yüklendi.');
    }

    /**
     * Medya dosyası detaylarını göster
     */
    public function show(Media $media)
    {
        return view('filemanagersystem.medias.show', compact('media'));
    }

    /**
     * Medya dosyası düzenleme formunu göster
     */
    public function edit(Media $media)
    {
        $folders = Folder::orderBy('folder_name')->get();
        return view('filemanagersystem.medias.edit', compact('media', 'folders'));
    }

    /**
     * Medya dosyasını güncelle
     */
    public function update(Request $request, Media $media)
    {
        $request->validate([
            'original_name' => 'required|string|max:255',
            'folder_id' => 'nullable|exists:filemanagersystem_folders,id',
            'is_public' => 'boolean',
        ]);
        
        $media->original_name = $request->original_name;
        $media->folder_id = $request->folder_id;
        $media->is_public = $request->has('is_public') ? $request->is_public : false;
        $media->save();
        
        return redirect()->route('admin.filemanagersystem.media.index', ['folder_id' => $media->folder_id])
            ->with('success', 'Dosya bilgileri başarıyla güncellendi.');
    }

    /**
     * Medya dosyasını sil
     */
    public function destroy(Media $media)
    {
        try {
            $folderId = $media->folder_id;
            
            // Fiziksel dosyayı sil
            if (Storage::disk('uploads')->exists($media->path)) {
                Storage::disk('uploads')->delete($media->path);
            }
            
            // Veritabanı kaydını sil
            $media->delete();
            
            // AJAX isteği ise JSON yanıt döndür
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Dosya başarıyla silindi.'
                ]);
            }
            
            // Normal istek ise yönlendirme döndür
            return redirect()->route('admin.filemanagersystem.media.index', ['folder_id' => $folderId])
                ->with('success', 'Dosya başarıyla silindi.');
        } catch (\Exception $e) {
            // AJAX isteği ise JSON yanıt döndür
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dosya silinirken bir hata oluştu: ' . $e->getMessage()
                ], 500);
            }
            
            // Normal istek ise hata mesajı ile yönlendirme döndür
            return back()->withErrors(['error' => 'Dosya silinirken bir hata oluştu: ' . $e->getMessage()]);
        }
    }

    /**
     * Dosyayı indir
     */
    public function download(Media $media)
    {
        $filePath = public_path('uploads/' . $media->path);
        
        if (file_exists($filePath)) {
            return response()->download($filePath, $media->original_name);
        }
        
        return back()->withErrors(['error' => 'Dosya bulunamadı']);
    }
    
    /**
     * MIME türüne göre ana klasörü belirler
     */
    private function getMainFolderByMimeType($mimeType)
    {
        if (strpos($mimeType, 'image/') === 0) {
            return 'images';
        } elseif (strpos($mimeType, 'video/') === 0) {
            return 'videos';
        } elseif (strpos($mimeType, 'audio/') === 0) {
            return 'audios';
        } elseif (in_array($mimeType, ['application/zip', 'application/x-rar-compressed', 'application/x-tar'])) {
            return 'archives';
        } else {
            return 'documents';
        }
    }
} 