<?php

namespace App\Http\Controllers\FileManagerSystem;

use App\Http\Controllers\Controller;
use App\Models\FileManagerSystem\Folder;
use App\Models\FileManagerSystem\Category;
use App\Models\FileManagerSystem\Media;
use App\Services\FileManagerSystem\FilemanagersystemService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use ZipArchive;

class FilemanagersystemController extends Controller
{
    protected $filemanagersystemService;

    public function __construct(FilemanagersystemService $filemanagersystemService)
    {
        $this->filemanagersystemService = $filemanagersystemService;
    }

    /**
     * Dosya yönetim sisteminin ana görünümünü gösterir.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $folders = Folder::withCount('medias')->get();
        $categories = Category::withCount('medias')->get();
        
        // URL bilgilerini tamamlayarak dosyaları getir
        $recentFiles = Media::latest()->take(20)->get();
        
        foreach ($recentFiles as $file) {
            // Eğer URL zaten tam yol içeriyorsa, dokunma
            if (!$file->url || (strpos($file->url, 'http://') !== 0 && strpos($file->url, 'https://') !== 0)) {
                $file->url = asset('uploads/' . $file->path);
            }
        }
        
        return view('filemanagersystem.index', compact('folders', 'categories', 'recentFiles'));
    }

    /**
     * Dosya seçim modalını gösterir.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function picker(Request $request)
    {
        $folders = Folder::all();
        $categories = Category::all();
        
        // Tüm parametreleri al
        $type = $request->input('type');
        $relatedType = $request->input('related_type', 'general');
        $relatedId = $request->input('related_id');
        
        // MediaPickerController'a yönlendir
        if ($type) {
            return redirect()->route('admin.filemanagersystem.mediapicker.index', [
                'type' => $type,
                'related_type' => $relatedType,
                'related_id' => $relatedId
            ]);
        }
        
        return view('filemanagersystem.picker', compact('folders', 'categories'));
    }

    /**
     * Dosya arama sonuçlarını döndürür.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        try {
            $query = Media::query();

            // Arama terimi
            if ($request->has('search') && !empty($request->search)) {
                $query->where(function($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%')
                      ->orWhere('original_name', 'like', '%' . $request->search . '%');
                });
            }

            // Dosya tipine göre filtreleme
            if ($request->has('type') && !empty($request->type)) {
                if ($request->type === 'image') {
                    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'];
                    $query->where(function($q) use ($imageExtensions) {
                        foreach ($imageExtensions as $ext) {
                            $q->orWhere('name', 'like', '%.' . $ext)
                              ->orWhere('mime_type', 'like', 'image/%');
                        }
                    });
                } elseif ($request->type === 'document') {
                    $docExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'rtf', 'odt', 'ods', 'odp'];
                    $query->where(function($q) use ($docExtensions) {
                        foreach ($docExtensions as $ext) {
                            $q->orWhere('name', 'like', '%.' . $ext);
                        }
                        $q->orWhere('mime_type', 'like', 'application/%')
                          ->orWhere('mime_type', 'like', 'text/%');
                    });
                } elseif ($request->type === 'video') {
                    $videoExtensions = ['mp4', 'avi', 'mov', 'wmv', 'flv', 'mkv', 'webm'];
                    $query->where(function($q) use ($videoExtensions) {
                        foreach ($videoExtensions as $ext) {
                            $q->orWhere('name', 'like', '%.' . $ext);
                        }
                        $q->orWhere('mime_type', 'like', 'video/%');
                    });
                } elseif ($request->type === 'audio') {
                    $audioExtensions = ['mp3', 'wav', 'ogg', 'flac', 'aac', 'm4a', 'wma'];
                    $query->where(function($q) use ($audioExtensions) {
                        foreach ($audioExtensions as $ext) {
                            $q->orWhere('name', 'like', '%.' . $ext);
                        }
                        $q->orWhere('mime_type', 'like', 'audio/%');
                    });
                } elseif ($request->type === 'archive') {
                    $archiveExtensions = ['zip', 'rar', '7z', 'tar', 'gz', 'bz2'];
                    $query->where(function($q) use ($archiveExtensions) {
                        foreach ($archiveExtensions as $ext) {
                            $q->orWhere('name', 'like', '%.' . $ext);
                        }
                        $q->orWhere('mime_type', 'like', 'application/zip')
                          ->orWhere('mime_type', 'like', 'application/x-rar%')
                          ->orWhere('mime_type', 'like', 'application/x-compressed%')
                          ->orWhere('mime_type', 'like', 'application/x-tar%');
                    });
                } else {
                    $query->where('file_type', 'like', $request->type . '%');
                }
            }

            // Klasöre göre filtreleme
            if ($request->has('folder_id') && $request->folder_id) {
                $query->where('folder_id', $request->folder_id);
            }

            // Kategoriye göre filtreleme
            if ($request->has('category_id') && $request->category_id) {
                $query->whereHas('categories', function($q) use ($request) {
                    $q->where('id', $request->category_id);
                });
            }

            // Tarihe göre filtreleme
            if ($request->has('date_filter') && !empty($request->date_filter)) {
                $today = now()->startOfDay();
                
                if ($request->date_filter === 'today') {
                    $query->whereDate('created_at', '>=', $today);
                } elseif ($request->date_filter === 'yesterday') {
                    $query->whereDate('created_at', '=', $today->copy()->subDay());
                } elseif ($request->date_filter === 'last_week') {
                    $query->whereDate('created_at', '>=', $today->copy()->subDays(7));
                } elseif ($request->date_filter === 'last_month') {
                    $query->whereDate('created_at', '>=', $today->copy()->subDays(30));
                } elseif ($request->date_filter === 'last_year') {
                    $query->whereDate('created_at', '>=', $today->copy()->subDays(365));
                }
            }
            
            // Boyuta göre filtreleme
            if ($request->has('size_filter') && !empty($request->size_filter)) {
                switch ($request->size_filter) {
                    case 'tiny':
                        $query->where('size', '<', 100 * 1024); // < 100KB
                        break;
                    case 'small':
                        $query->where('size', '>=', 100 * 1024)
                              ->where('size', '<', 1024 * 1024); // 100KB - 1MB
                        break;
                    case 'medium':
                        $query->where('size', '>=', 1024 * 1024)
                              ->where('size', '<', 10 * 1024 * 1024); // 1MB - 10MB
                        break;
                    case 'large':
                        $query->where('size', '>=', 10 * 1024 * 1024)
                              ->where('size', '<', 100 * 1024 * 1024); // 10MB - 100MB
                        break;
                    case 'huge':
                        $query->where('size', '>=', 100 * 1024 * 1024); // > 100MB
                        break;
                }
            }
            
            // Sıralama
            if ($request->has('sort') && !empty($request->sort)) {
                switch ($request->sort) {
                    case 'newest':
                        $query->latest();
                        break;
                    case 'oldest':
                        $query->oldest();
                        break;
                    case 'name_asc':
                        $query->orderBy('name', 'asc');
                        break;
                    case 'name_desc':
                        $query->orderBy('name', 'desc');
                        break;
                    case 'size_asc':
                        $query->orderBy('size', 'asc');
                        break;
                    case 'size_desc':
                        $query->orderBy('size', 'desc');
                        break;
                    default:
                        $query->latest();
                }
            } else {
                $query->latest();
            }

            $files = $query->paginate(20);

            // Dosya URL'lerini ekle ve doğru şekilde oluştur
            $files->getCollection()->transform(function ($file) {
                // Veritabanındaki yolu kontrol et
                $path = $file->path;
                
                // Eski kayıtlarda klasör yapısı olmayabilir, düzelt
                if (!preg_match('#^(images|documents|videos|audios|archives)/#', $path)) {
                    // MIME tipine göre doğru klasörü belirle
                    $folderPath = $this->getMainFolderByMimeType($file->mime_type);
                    
                    // Sadece dosya adını al
                    $filename = basename($path);
                    
                    // Yeni yolu oluştur
                    $correctedPath = $folderPath . '/' . $filename;
                    
                    // Eğer bu yolda dosya varsa, düzeltilmiş yolu kullan
                    if (file_exists(public_path('uploads/' . $correctedPath))) {
                        $path = $correctedPath;
                    }
                }
                
                // URL'yi oluştur
                $file->url = asset('uploads/' . $path);
                $file->file_name = $file->original_name ?? $file->name;
                
                // WebP bilgilerini ekle
                if ($file->has_webp && $file->webp_path) {
                    $file->webp_url = asset('uploads/' . $file->webp_path);
                }
                
                // İnsan tarafından okunabilir formatları ekle
                $file->human_readable_size = $file->getHumanReadableSizeAttribute();
                $file->formatted_webp_size = $file->getFormattedWebpSizeAttribute();
                $file->formatted_date = $file->created_at ? $file->created_at->format('d.m.Y H:i') : '';
                
                return $file;
            });

            return response()->json([
                'success' => true,
                'data' => $files->items(),
                'pagination' => [
                    'total' => $files->total(),
                    'per_page' => $files->perPage(),
                    'current_page' => $files->currentPage(),
                    'last_page' => $files->lastPage()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dosya araması sırasında bir hata oluştu',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
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

    /**
     * Dosya önizleme sayfasını gösterir.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function preview($id)
    {
        // Doğrudan media.show sayfasına yönlendir
        return redirect()->route('admin.filemanagersystem.media.show', ['media' => $id]);
    }

    /**
     * Toplu işlemleri gerçekleştirir.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bulkActions(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'action' => 'required|in:move,categorize,delete,download',
                'files' => 'required|array',
                'files.*' => 'exists:filemanagersystem_medias,id'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            $files = Media::whereIn('id', $request->files)->get();

            switch ($request->action) {
                case 'move':
                    if (!$request->has('folder_id')) {
                        return response()->json(['error' => 'Hedef klasör seçilmedi'], 422);
                    }
                    
                    foreach ($files as $file) {
                        $file->folder_id = $request->folder_id;
                        $file->save();
                    }
                    break;

                case 'categorize':
                    if (!$request->has('category_id')) {
                        return response()->json(['error' => 'Hedef kategori seçilmedi'], 422);
                    }
                    
                    foreach ($files as $file) {
                        $file->categories()->syncWithoutDetaching([$request->category_id]);
                    }
                    break;

                case 'delete':
                    foreach ($files as $file) {
                        $file->delete();
                    }
                    break;

                case 'download':
                    $zip = new ZipArchive;
                    $zipFileName = 'files_' . time() . '.zip';
                    $zipPath = storage_path('app/public/temp/' . $zipFileName);

                    if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
                        foreach ($files as $file) {
                            $filePath = public_path('uploads/' . $file->name);
                            if (File::exists($filePath)) {
                                $zip->addFile($filePath, $file->original_name ?? $file->name);
                            }
                        }
                        $zip->close();
                    }

                    return response()->download($zipPath)->deleteFileAfterSend(true);
                    break;
            }

            return response()->json(['message' => 'İşlem başarıyla tamamlandı']);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'İşlem sırasında bir hata oluştu',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Dosya yönetim sistemi ayarlarını gösterir.
     *
     * @return \Illuminate\View\View
     */
    public function settings()
    {
        $settings = $this->filemanagersystemService->loadConfig();
        return view('filemanagersystem.settings', compact('settings'));
    }

    /**
     * Dosya yönetim sistemi ayarlarını günceller.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateSettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'allowed_file_types' => 'required|array',
            'max_file_size' => 'required|integer|min:1',
            'storage_path' => 'required|string',
            'thumbnail_sizes' => 'required|array',
            'default_folder' => 'required|integer|exists:filemanagersystem_folders,id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->filemanagersystemService->updateConfig($request->all());

        return redirect()->back()->with('success', 'Ayarlar başarıyla güncellendi');
    }

    /**
     * Dosya yönetim sistemi dashboard'unu gösterir.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        $stats = [
            'total_files' => Media::count(),
            'total_folders' => Folder::count(),
            'total_categories' => Category::count(),
            'total_size' => Media::sum('file_size'),
            'recent_uploads' => Media::latest()->take(5)->get(),
            'top_folders' => Folder::withCount('medias')
                ->orderBy('medias_count', 'desc')
                ->take(5)
                ->get(),
            'top_categories' => Category::withCount('medias')
                ->orderBy('medias_count', 'desc')
                ->take(5)
                ->get()
        ];

        return view('filemanagersystem.dashboard', compact('stats'));
    }

    /**
     * TinyMCE editör sayfasını gösterir.
     *
     * @return \Illuminate\View\View
     */
    public function tinymce()
    {
        $folders = Folder::with('children')->whereNull('parent_id')->get();
        $categories = Category::with('children')->whereNull('parent_id')->get();

        return view('filemanagersystem.tinymce', compact('folders', 'categories'));
    }
}
