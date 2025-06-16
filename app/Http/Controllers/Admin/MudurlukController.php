<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMudurlukRequest;
use App\Http\Requests\UpdateMudurlukRequest;
use App\Models\Mudurluk;
use App\Models\MudurlukFile;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MudurlukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Mudurluk::query()->withCount('files');

        // Arama
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('summary', 'like', "%{$search}%");
            });
        }

        // Durum filtresi
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Sıralama
        $sortField = $request->get('sort', 'order_column');
        $sortDirection = $request->get('direction', 'asc');
        
        if ($sortField === 'order_column') {
            $query->orderBy('order_column')->orderBy('name');
        } else {
            $query->orderBy($sortField, $sortDirection);
        }

        $mudurlukler = $query->paginate(15)->appends($request->all());

        return view('admin.mudurlukler.index', compact('mudurlukler'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $serviceCategories = ServiceCategory::where('is_active', true)
                                          ->orderBy('name')
                                          ->get();
        
        return view('admin.mudurlukler.create', compact('serviceCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMudurlukRequest $request)
    {
        try {
            DB::beginTransaction();

            // Ana kayıt oluştur
            $data = $request->validated();
            
            // Resim yükleme
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('mudurlukler', 'public');
            }

            $mudurluk = Mudurluk::create($data);

            // Müdürlük kategorilerini ekle
            if ($request->has('service_category_ids')) {
                $mudurluk->serviceCategories()->sync($request->service_category_ids);
            }

            // Hizmet standartları dosyalarını yükle
            $this->uploadFiles($mudurluk, $request, 'hizmet_standartlari');

            // Yönetim şemaları dosyalarını yükle
            $this->uploadFiles($mudurluk, $request, 'yonetim_semalari');

            DB::commit();

            return redirect()->route('admin.mudurlukler.edit', $mudurluk)
                           ->with('success', 'Müdürlük başarıyla oluşturuldu.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Müdürlük oluşturma hatası: ' . $e->getMessage());

            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Müdürlük oluşturulurken bir hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Mudurluk $mudurluk)
    {
        $mudurluk->load(['files' => function($query) {
            $query->where('is_active', true)->orderBy('order_column');
        }, 'serviceCategories']);

        return view('admin.mudurlukler.show', compact('mudurluk'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mudurluk $mudurluk)
    {
        $mudurluk->load(['files' => function($query) {
            $query->orderBy('type')->orderBy('order_column');
        }, 'serviceCategories']);

        $serviceCategories = ServiceCategory::where('is_active', true)
                                          ->orderBy('name')
                                          ->get();

        return view('admin.mudurlukler.edit', compact('mudurluk', 'serviceCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMudurlukRequest $request, Mudurluk $mudurluk)
    {
        try {
            DB::beginTransaction();

            // Ana veriyi güncelle
            $data = $request->validated();
            
            // Resim yükleme
            if ($request->hasFile('image')) {
                // Eski resmi sil
                if ($mudurluk->image) {
                    Storage::disk('public')->delete($mudurluk->image);
                }
                $data['image'] = $request->file('image')->store('mudurlukler', 'public');
            }

            $mudurluk->update($data);

            // Müdürlük kategorilerini güncelle
            if ($request->has('service_category_ids')) {
                $mudurluk->serviceCategories()->sync($request->service_category_ids);
            } else {
                $mudurluk->serviceCategories()->detach();
            }

            // Yeni dosyaları yükle
            $this->uploadFiles($mudurluk, $request, 'hizmet_standartlari');
            $this->uploadFiles($mudurluk, $request, 'yonetim_semalari');

            DB::commit();

            return redirect()->route('admin.mudurlukler.edit', $mudurluk)
                           ->with('success', 'Müdürlük başarıyla güncellendi.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Müdürlük güncelleme hatası: ' . $e->getMessage());

            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Müdürlük güncellenirken bir hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mudurluk $mudurluk)
    {
        try {
            DB::beginTransaction();

            // Dosyaları sil
            foreach ($mudurluk->files as $file) {
                Storage::disk('public')->delete($file->file_path);
            }

            // Resmi sil
            if ($mudurluk->image) {
                Storage::disk('public')->delete($mudurluk->image);
            }

            // Hizmet kategorisi ilişkilerini sil
            $mudurluk->serviceCategories()->detach();

            // Kaydı sil (files cascade ile silinecek)
            $mudurluk->delete();

            DB::commit();

            return redirect()->route('admin.mudurlukler.index')
                           ->with('success', 'Müdürlük başarıyla silindi.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Müdürlük silme hatası: ' . $e->getMessage());

            return redirect()->back()
                           ->with('error', 'Müdürlük silinirken bir hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Upload document (AJAX)
     */
    public function uploadDocument(Request $request, Mudurluk $mudurluk)
    {
        $request->validate([
            'document_name' => 'required|string|max:255',
            'file' => 'required|file|max:51200', // 50MB max
        ]);

        try {
            $file = $request->file('file');
            
            // Dosya bilgilerini al
            $originalFileName = $file->getClientOriginalName();
            $fileSize = $file->getSize();
            
            // Dosya adını temizle ve benzersiz yap
            $originalName = pathinfo($originalFileName, PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $fileName = \Str::slug($originalName) . '_' . time() . '.' . $extension;
            
            // Dosyayı kaydet
            $filePath = $file->storeAs('mudurlukler', $fileName, 'public');
            
            // En yüksek sıra numarasını bul
            $maxOrderColumn = $mudurluk->files()->max('order_column') ?? 0;
            
            // Veritabanına kaydet
            $document = $mudurluk->files()->create([
                'title' => $request->document_name,
                'file_name' => $originalFileName,
                'file_path' => $filePath,
                'file_size' => $fileSize,
                'type' => 'document', // Dinamik belgeler için genel tip
                'order_column' => $maxOrderColumn + 1,
                'is_active' => true,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Belge başarıyla yüklendi.',
                'document' => [
                    'id' => $document->id,
                    'title' => $document->title,
                    'file_name' => $document->file_name,
                    'file_size' => number_format($document->file_size / 1024, 2) . ' KB',
                    'is_active' => $document->is_active,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Belge yükleme hatası: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Belge yüklenirken bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk upload documents (AJAX)
     */
    public function bulkUploadDocuments(Request $request, Mudurluk $mudurluk)
    {


        $request->validate([
            'files' => 'required|array|min:1',
            'files.*' => 'required|file|max:51200', // 50MB max per file
            'names' => 'required|array',
            'names.*' => 'required|string|max:255',
        ]);

        try {
            $files = $request->file('files');
            $names = $request->input('names');
            $uploadedDocuments = [];
            $errors = [];

            // En yüksek sıra numarasını bul
            $maxOrderColumn = $mudurluk->files()->max('order_column') ?? 0;

            foreach ($files as $index => $file) {
                try {

                    
                    // Dosya bilgilerini al
                    $originalFileName = $file->getClientOriginalName();
                    $fileSize = $file->getSize();
                    
                    // Dosya adını temizle ve benzersiz yap
                    $originalName = pathinfo($originalFileName, PATHINFO_FILENAME);
                    $extension = $file->getClientOriginalExtension();
                    $fileName = \Str::slug($originalName) . '_' . time() . '_' . $index . '.' . $extension;
                    
                    // Dosyayı kaydet
                    $filePath = $file->storeAs('mudurlukler', $fileName, 'public');
                    
                    // Belge adını belirle
                    $documentName = isset($names[$index]) && !empty($names[$index]) 
                        ? $names[$index] 
                        : pathinfo($originalFileName, PATHINFO_FILENAME);
                    
                    // Veritabanına kaydet
                    $document = $mudurluk->files()->create([
                        'title' => $documentName,
                        'file_name' => $originalFileName,
                        'file_path' => $filePath,
                        'file_size' => $fileSize,
                        'type' => 'document',
                        'order_column' => $maxOrderColumn + count($files) - $index,
                        'is_active' => true,
                    ]);

                    $uploadedDocuments[] = [
                        'id' => $document->id,
                        'title' => $document->title,
                        'file_name' => $document->file_name,
                        'file_size' => number_format($document->file_size / 1024, 2) . ' KB',
                    ];

                } catch (\Exception $e) {
                    $errors[] = $originalFileName . ': ' . $e->getMessage();
                }
            }

            $responseData = [
                'success' => count($uploadedDocuments) > 0,
                'message' => count($uploadedDocuments) . ' belge başarıyla yüklendi.',
                'uploaded_documents' => $uploadedDocuments,
                'errors' => $errors,
                'total_uploaded' => count($uploadedDocuments),
                'total_errors' => count($errors)
            ];
            

            
            return response()->json($responseData);

        } catch (\Exception $e) {
            Log::error('Toplu belge yükleme hatası: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Belgeler yüklenirken bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update document (AJAX)
     */
    public function updateDocument(Request $request, Mudurluk $mudurluk, MudurlukFile $document)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        try {
            $document->update([
                'title' => $request->title
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Belge başarıyla güncellendi.',
                'document' => [
                    'id' => $document->id,
                    'title' => $document->title,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Belge güncelleme hatası: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Belge güncellenirken bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete document (AJAX)
     */
    public function destroyDocument(Mudurluk $mudurluk, MudurlukFile $document)
    {
        try {
            // Dosyayı sil
            Storage::disk('public')->delete($document->file_path);
            
            // Kaydı sil
            $document->delete();

            return response()->json([
                'success' => true,
                'message' => 'Belge başarıyla silindi.'
            ]);

        } catch (\Exception $e) {
            Log::error('Belge silme hatası: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Belge silinirken bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle document status (AJAX)
     */
    public function toggleDocumentStatus(Mudurluk $mudurluk, MudurlukFile $document)
    {
        try {
            $document->update([
                'is_active' => !$document->is_active
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Belge durumu başarıyla değiştirildi.',
                'is_active' => $document->is_active
            ]);

        } catch (\Exception $e) {
            Log::error('Belge durum değiştirme hatası: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Belge durumu değiştirilirken bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * AJAX - Dosya silme
     */
    public function removeFile(Request $request, MudurlukFile $file)
    {
        try {
            // Dosyayı fiziksel olarak sil
            Storage::disk('public')->delete($file->file_path);
            
            // Veritabanından sil
            $file->delete();

            return response()->json([
                'success' => true,
                'message' => 'Dosya başarıyla silindi.'
            ]);

        } catch (\Exception $e) {
            Log::error('Dosya silme hatası: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Dosya silinirken bir hata oluştu.'
            ], 500);
        }
    }

    /**
     * AJAX - Dosya durumu değiştirme
     */
    public function toggleFileStatus(Request $request, MudurlukFile $file)
    {
        try {
            $file->is_active = !$file->is_active;
            $file->save();

            return response()->json([
                'success' => true,
                'is_active' => $file->is_active,
                'message' => $file->is_active ? 'Dosya aktifleştirildi.' : 'Dosya pasifleştirildi.'
            ]);

        } catch (\Exception $e) {
            Log::error('Dosya durum değişikliği hatası: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Dosya durumu değiştirilirken bir hata oluştu.'
            ], 500);
        }
    }

    /**
     * AJAX - Dosya sıralama
     */
    public function reorderFiles(Request $request)
    {
        try {
            $request->validate([
                'files' => 'required|array',
                'files.*' => 'exists:mudurluk_files,id'
            ]);

            foreach ($request->files as $order => $fileId) {
                MudurlukFile::where('id', $fileId)->update(['order_column' => $order + 1]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Dosya sıralaması güncellendi.'
            ]);

        } catch (\Exception $e) {
            Log::error('Dosya sıralama hatası: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Dosya sıralaması güncellenirken bir hata oluştu.'
            ], 500);
        }
    }

    /**
     * Dosya yükleme helper metodu
     */
    private function uploadFiles(Mudurluk $mudurluk, Request $request, string $type)
    {
        $filesKey = $type . '_files';
        $titlesKey = $type . '_titles';

        if (!$request->hasFile($filesKey)) {
            return;
        }

        $files = $request->file($filesKey);
        $titles = $request->input($titlesKey, []);

        foreach ($files as $index => $file) {
            if ($file && $file->isValid()) {
                // Dosya yolu oluştur
                $fileName = time() . '_' . $index . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs("mudurlukler/{$type}", $fileName, 'public');

                // Veritabanına kaydet
                $mudurluk->files()->create([
                    'type' => $type,
                    'title' => $titles[$index] ?? $file->getClientOriginalName(),
                    'file_path' => $filePath,
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'order_column' => $mudurluk->files()->where('type', $type)->count() + 1,
                    'is_active' => true
                ]);
            }
        }
    }
}
