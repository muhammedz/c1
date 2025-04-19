<?php

namespace App\Http\Controllers\FileManagerSystem;

use App\Http\Controllers\Controller;
use App\Models\FileManagerSystem\Folder;
use App\Services\FileManagerSystem\FilemanagersystemFolderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class FilemanagersystemFolderController extends Controller
{
    protected $folderService;

    public function __construct(FilemanagersystemFolderService $folderService)
    {
        $this->folderService = $folderService;
    }

    /**
     * Tüm klasörleri listele
     */
    public function index()
    {
        $folders = Folder::withCount('medias')->get();
        return view('filemanagersystem.folders.index', compact('folders'));
    }

    /**
     * Yeni klasör oluştur
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:filemanagersystem_folders,id'
        ]);

        $folder = $this->folderService->create($request->all());

        return redirect()->route('admin.filemanagersystem.folders.index')
            ->with('success', 'Klasör başarıyla oluşturuldu.');
    }

    /**
     * Klasör düzenleme formunu göster
     */
    public function edit(Folder $id)
    {
        $folder = $id; // Route model binding için
        $folders = $this->folderService->all();
        return view('filemanagersystem.folders.edit', compact('folder', 'folders'));
    }

    /**
     * Klasörü güncelle
     */
    public function update(Request $request, Folder $id)
    {
        $folder = $id; // Route model binding için
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:filemanagersystem_folders,id'
        ]);

        $this->folderService->update($folder, $request->all());

        return redirect()->route('admin.filemanagersystem.folders.index')
            ->with('success', 'Klasör başarıyla güncellendi.');
    }

    /**
     * Klasörü sil
     */
    public function destroy(Folder $id)
    {
        $folder = $id; // Route model binding için
        $this->folderService->delete($folder);

        return redirect()->route('admin.filemanagersystem.folders.index')
            ->with('success', 'Klasör başarıyla silindi.');
    }

    /**
     * Klasör detaylarını göster
     */
    public function show(Folder $id)
    {
        $folder = $id; // Route model binding için
        $parentFolder = $folder->parent;
        $subfolders = Folder::where('parent_id', $folder->id)->get();
        $medias = $folder->medias;
        
        return view('filemanagersystem.folders.show', compact('folder', 'parentFolder', 'subfolders', 'medias'));
    }
} 