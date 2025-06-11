<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Archive;
use App\Models\ArchiveDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArchiveDocumentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Archive $archive)
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
        
        // Dosyayı uploads/archives klasörüne kaydet
        $uploadPath = 'uploads/archives';
        $filePath = $uploadPath . '/' . $fileName;
        
        // Klasör yoksa oluştur
        if (!file_exists(public_path($uploadPath))) {
            mkdir(public_path($uploadPath), 0755, true);
        }
        
        // Dosyayı taşı
        $file->move(public_path($uploadPath), $fileName);
        
        // Veritabanına kaydet
        $document = $archive->allDocuments()->create([
            'name' => $request->name,
            'description' => $request->description,
            'file_path' => $filePath,
            'file_name' => $originalFileName,
            'file_size' => $fileSize,
            'mime_type' => $mimeType,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Belge başarıyla yüklendi.',
            'document' => [
                'id' => $document->id,
                'name' => $document->name,
                'description' => $document->description,
                'file_name' => $document->file_name,
                'file_size' => $document->formatted_size,
                'file_type' => $document->file_type,
                'icon_class' => $document->icon_class,
                'download_url' => $document->download_url,
                'sort_order' => $document->sort_order,
                'is_active' => $document->is_active,
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ArchiveDocument $document)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $document->update([
            'name' => $request->name,
            'description' => $request->description,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Belge başarıyla güncellendi.',
            'document' => [
                'id' => $document->id,
                'name' => $document->name,
                'description' => $document->description,
                'file_name' => $document->file_name,
                'file_size' => $document->formatted_size,
                'file_type' => $document->file_type,
                'icon_class' => $document->icon_class,
                'download_url' => $document->download_url,
                'sort_order' => $document->sort_order,
                'is_active' => $document->is_active,
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ArchiveDocument $document)
    {
        // Dosyayı fiziksel olarak sil
        if ($document->fileExists()) {
            unlink(public_path($document->file_path));
        }

        // Veritabanından sil
        $document->delete();

        return response()->json([
            'success' => true,
            'message' => 'Belge başarıyla silindi.'
        ]);
    }

    /**
     * Update sort order of documents
     */
    public function updateSortOrder(Request $request, Archive $archive)
    {
        $request->validate([
            'documents' => 'required|array',
            'documents.*.id' => 'required|exists:archive_documents,id',
            'documents.*.sort_order' => 'required|integer|min:0',
        ]);

        foreach ($request->documents as $documentData) {
            ArchiveDocument::where('id', $documentData['id'])
                          ->where('archive_id', $archive->id)
                          ->update(['sort_order' => $documentData['sort_order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Belge sıralaması güncellendi.'
        ]);
    }

    /**
     * Toggle document status
     */
    public function toggleStatus(ArchiveDocument $document)
    {
        $document->update(['is_active' => !$document->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Belge durumu güncellendi.',
            'is_active' => $document->is_active
        ]);
    }
}
