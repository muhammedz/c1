<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsDocumentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, News $news)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|max:51200', // 50MB max
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $file = $request->file('file');
        
        // Dosya bilgilerini taşımadan önce al
        $originalFileName = $file->getClientOriginalName();
        $fileSize = $file->getSize();
        $mimeType = $file->getMimeType();
        
        // Dosya adını temizle ve benzersiz yap
        $originalName = pathinfo($originalFileName, PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $fileName = Str::slug($originalName) . '_' . time() . '.' . $extension;
        
        // Dosyayı uploads klasörüne kaydet
        $uploadPath = 'uploads/news/documents';
        $filePath = $uploadPath . '/' . $fileName;
        
        // Klasör yoksa oluştur
        if (!file_exists(public_path($uploadPath))) {
            mkdir(public_path($uploadPath), 0755, true);
        }
        
        // Dosyayı taşı
        $file->move(public_path($uploadPath), $fileName);
        
        // En yüksek sıra numarasını bul ve 1 ekle (yeni belgeler en üste gelsin)
        $maxSortOrder = $news->allDocuments()->max('sort_order') ?? 0;
        $sortOrder = $request->sort_order ?? ($maxSortOrder + 1);
        
        // Veritabanına kaydet
        $document = $news->allDocuments()->create([
            'name' => $request->name,
            'description' => $request->description,
            'file_path' => $filePath,
            'file_name' => $originalFileName,
            'file_size' => $fileSize,
            'mime_type' => $mimeType,
            'sort_order' => $sortOrder,
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Belge başarıyla yüklendi.',
            'document' => [
                'id' => $document->id,
                'name' => $document->name,
                'file_name' => $document->file_name,
                'file_size' => $document->formatted_size,
                'icon_class' => $document->icon_class,
                'url' => $document->url
            ]
        ]);
    }

    /**
     * Store multiple documents at once.
     */
    public function bulkStore(Request $request, News $news)
    {
        $request->validate([
            'files' => 'required|array|min:1',
            'files.*' => 'required|file|max:51200', // 50MB max per file
            'names' => 'required|array',
            'names.*' => 'required|string|max:255',
        ]);

        $files = $request->file('files');
        $names = $request->input('names');
        $uploadedDocuments = [];
        $errors = [];

        // Klasör yoksa oluştur
        $uploadPath = 'uploads/news/documents';
        if (!file_exists(public_path($uploadPath))) {
            mkdir(public_path($uploadPath), 0755, true);
        }

        // En yüksek sıra numarasını bul (yeni belgeler en üste gelsin)
        $maxSortOrder = $news->allDocuments()->max('sort_order') ?? 0;

        foreach ($files as $index => $file) {
            try {
                // Dosya bilgilerini al
                $originalFileName = $file->getClientOriginalName();
                $fileSize = $file->getSize();
                $mimeType = $file->getMimeType();
                
                // Dosya adını temizle ve benzersiz yap
                $originalName = pathinfo($originalFileName, PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $fileName = Str::slug($originalName) . '_' . time() . '_' . $index . '.' . $extension;
                
                $filePath = $uploadPath . '/' . $fileName;
                
                // Dosyayı taşı
                $file->move(public_path($uploadPath), $fileName);
                
                // Belge adını belirle (kullanıcının girdiği ad veya dosya adı)
                $documentName = isset($names[$index]) && !empty($names[$index]) 
                    ? $names[$index] 
                    : pathinfo($originalFileName, PATHINFO_FILENAME);
                
                // Veritabanına kaydet (en son eklenen en üste gelsin)
                $document = $news->allDocuments()->create([
                    'name' => $documentName,
                    'description' => null,
                    'file_path' => $filePath,
                    'file_name' => $originalFileName,
                    'file_size' => $fileSize,
                    'mime_type' => $mimeType,
                    'sort_order' => $maxSortOrder + count($files) - $index,
                    'is_active' => true,
                ]);

                $uploadedDocuments[] = [
                    'id' => $document->id,
                    'name' => $document->name,
                    'file_name' => $document->file_name,
                    'file_size' => $document->formatted_size,
                ];

            } catch (\Exception $e) {
                $errors[] = $originalFileName . ': ' . $e->getMessage();
            }
        }

        return response()->json([
            'success' => count($uploadedDocuments) > 0,
            'message' => count($uploadedDocuments) . ' belge başarıyla yüklendi.',
            'uploaded_documents' => $uploadedDocuments,
            'errors' => $errors,
            'total_uploaded' => count($uploadedDocuments),
            'total_errors' => count($errors)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, News $news, NewsDocument $document)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $document->update($request->only(['name', 'description', 'sort_order']));

        return response()->json([
            'success' => true,
            'message' => 'Belge başarıyla güncellendi.',
            'document' => [
                'id' => $document->id,
                'name' => $document->name,
                'description' => $document->description,
                'sort_order' => $document->sort_order
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(News $news, NewsDocument $document)
    {
        $document->delete();

        return response()->json([
            'success' => true,
            'message' => 'Belge başarıyla silindi.'
        ]);
    }

    /**
     * Bulk delete documents.
     */
    public function bulkDestroy(Request $request, News $news)
    {
        $request->validate([
            'document_ids' => 'required|array|min:1',
            'document_ids.*' => 'exists:news_documents,id'
        ]);

        $documents = NewsDocument::whereIn('id', $request->document_ids)
                                ->where('news_id', $news->id)
                                ->get();

        $deletedCount = 0;
        foreach ($documents as $document) {
            $document->delete();
            $deletedCount++;
        }

        return response()->json([
            'success' => true,
            'message' => $deletedCount . ' belge başarıyla silindi.'
        ]);
    }

    /**
     * Download document.
     */
    public function download(News $news, NewsDocument $document)
    {
        if (!$document->fileExists()) {
            abort(404, 'Dosya bulunamadı.');
        }

        return response()->download(public_path($document->file_path), $document->file_name);
    }
}
