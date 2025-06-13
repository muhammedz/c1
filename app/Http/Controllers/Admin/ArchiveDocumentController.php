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
            'category_id' => 'nullable|exists:archive_document_categories,id',
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
        $uploadPath = 'uploads';
        $filePath = $uploadPath . '/' . $fileName;
        
        // Klasör yoksa oluştur
        if (!file_exists(public_path($uploadPath))) {
            mkdir(public_path($uploadPath), 0755, true);
        }
        
        // Dosyayı taşı
        $file->move(public_path($uploadPath), $fileName);
        
        // En yüksek sıra numarasını bul ve 1 ekle (yeni belgeler en üste gelsin)
        $maxSortOrder = $archive->allDocuments()->max('sort_order') ?? 0;
        $sortOrder = $request->sort_order ?? ($maxSortOrder + 1);
        
        // Veritabanına kaydet
        $document = $archive->allDocuments()->create([
            'name' => $request->name,
            'description' => $request->description,
            'category_id' => $request->category_id,
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
     * Store multiple documents at once.
     */
    public function bulkStore(Request $request, Archive $archive)
    {
        $request->validate([
            'files' => 'required|array|min:1',
            'files.*' => 'required|file|max:51200', // 50MB max per file
            'names' => 'required|array',
            'names.*' => 'required|string|max:255',
            'category_id' => 'nullable|exists:archive_document_categories,id',
        ]);

        $files = $request->file('files');
        $names = $request->input('names');
        $uploadedDocuments = [];
        $errors = [];

        // Klasör yoksa oluştur
        $uploadPath = 'uploads';
        if (!file_exists(public_path($uploadPath))) {
            mkdir(public_path($uploadPath), 0755, true);
        }

        // En yüksek sıra numarasını bul (yeni belgeler en üste gelsin)
        $maxSortOrder = $archive->allDocuments()->max('sort_order') ?? 0;

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
                $document = $archive->allDocuments()->create([
                    'name' => $documentName,
                    'description' => null,
                    'category_id' => $request->category_id,
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
                $errorMessage = $e->getMessage();
                
                // Yaygın hataları daha anlaşılır hale getir
                if (strpos($errorMessage, 'file_uploads') !== false) {
                    $errorMessage = 'Dosya yükleme özelliği sunucuda kapalı';
                } elseif (strpos($errorMessage, 'upload_max_filesize') !== false) {
                    $errorMessage = 'Dosya boyutu çok büyük (Max: 50MB)';
                } elseif (strpos($errorMessage, 'post_max_size') !== false) {
                    $errorMessage = 'Toplam dosya boyutu çok büyük';
                } elseif (strpos($errorMessage, 'tmp_name') !== false) {
                    $errorMessage = 'Geçici dosya oluşturulamadı';
                } elseif (strpos($errorMessage, 'move_uploaded_file') !== false) {
                    $errorMessage = 'Dosya hedef klasöre taşınamadı';
                } elseif (strpos($errorMessage, 'permission') !== false) {
                    $errorMessage = 'Klasör yazma izni yok';
                }
                
                $errors[] = "{$originalFileName}: {$errorMessage}";
            }
        }

        $successCount = count($uploadedDocuments);
        $errorCount = count($errors);

        if ($successCount > 0 && $errorCount === 0) {
            return response()->json([
                'success' => true,
                'message' => "{$successCount} belge başarıyla yüklendi.",
                'documents' => $uploadedDocuments
            ]);
        } elseif ($successCount > 0 && $errorCount > 0) {
            return response()->json([
                'success' => true,
                'message' => "{$successCount} belge yüklendi, {$errorCount} dosyada hata oluştu.",
                'documents' => $uploadedDocuments,
                'errors' => $errors
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Hiçbir belge yüklenemedi.',
                'errors' => $errors
            ], 422);
        }
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

    /**
     * Update single document sort order
     */
    public function updateSort(Request $request, ArchiveDocument $document)
    {
        $request->validate([
            'sort_order' => 'required|integer|min:0|max:9999',
        ]);

        $document->update(['sort_order' => $request->sort_order]);

        return response()->json([
            'success' => true,
            'message' => 'Belge sırası güncellendi.',
            'sort_order' => $document->sort_order
        ]);
    }

    /**
     * Remove multiple documents at once.
     */
    public function bulkDelete(Request $request, Archive $archive)
    {
        $request->validate([
            'document_ids' => 'required|array|min:1',
            'document_ids.*' => 'required|integer|exists:archive_documents,id',
        ]);

        $documentIds = $request->input('document_ids');
        $deletedCount = 0;
        $errors = [];

        // Seçili belgeleri al ve arşive ait olduklarını kontrol et
        $documents = ArchiveDocument::whereIn('id', $documentIds)
            ->where('archive_id', $archive->id)
            ->get();

        if ($documents->count() !== count($documentIds)) {
            return response()->json([
                'success' => false,
                'message' => 'Bazı belgeler bu arşive ait değil veya bulunamadı.'
            ], 422);
        }

        foreach ($documents as $document) {
            try {
                // Dosyayı fiziksel olarak sil
                if ($document->fileExists()) {
                    unlink(public_path($document->file_path));
                }
                
                // Veritabanından sil
                $document->delete();
                $deletedCount++;
                
            } catch (\Exception $e) {
                $errors[] = "{$document->name}: Silinirken hata oluştu";
            }
        }

        if ($deletedCount > 0 && count($errors) === 0) {
            $message = $deletedCount === 1 
                ? '1 belge başarıyla silindi.' 
                : "{$deletedCount} belge başarıyla silindi.";
                
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } elseif ($deletedCount > 0 && count($errors) > 0) {
            return response()->json([
                'success' => true,
                'message' => "{$deletedCount} belge silindi, " . count($errors) . " belgede hata oluştu.",
                'errors' => $errors
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Hiçbir belge silinemedi.',
                'errors' => $errors
            ], 422);
        }
    }
}
