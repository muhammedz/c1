<?php

namespace App\Services\FileManagerSystem;

use App\Models\FileManagerSystem\Folder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FilemanagersystemFolderService
{
    /**
     * Klasör oluşturur.
     *
     * @param array $data
     * @return Folder
     */
    public function create(array $data)
    {
        $folder = Folder::create([
            'folder_name' => $data['name'],
            'folder_slug' => Str::slug($data['name']),
            'parent_id' => $data['parent_id'] ?? null,
            'user_id' => auth()->id(),
        ]);

        // Fiziksel klasörü oluştur
        Storage::disk('public')->makeDirectory('folders/' . $folder->folder_slug);

        return $folder;
    }

    /**
     * Klasör slug'ı oluşturur.
     *
     * @param string $name
     * @param int|null $parentId
     * @return string
     */
    private function generateSlug(string $name, ?int $parentId = null)
    {
        $slug = Str::slug($name);

        // Üst klasör varsa, slug'ı üst klasörün slug'ı ile birleştir
        if ($parentId) {
            $parent = Folder::find($parentId);
            $slug = $parent->slug . '/' . $slug;
        }

        // Benzersiz slug oluştur
        $originalSlug = $slug;
        $counter = 1;

        while (Folder::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Fiziksel klasör oluşturur.
     *
     * @param string $slug
     * @param int|null $parentId
     * @return string
     */
    private function createPhysicalFolder(string $slug, ?int $parentId = null)
    {
        $path = config('filemanagersystem.storage_path') . '/' . $slug;

        // Klasörü oluştur
        Storage::disk('public')->makeDirectory($path);

        return $path;
    }

    /**
     * Klasörü günceller.
     *
     * @param Folder $folder
     * @param array $data
     * @return bool
     */
    public function update(Folder $folder, array $data)
    {
        $oldSlug = $folder->folder_slug;
        
        $folder->update([
            'folder_name' => $data['name'],
            'folder_slug' => Str::slug($data['name']),
            'parent_id' => $data['parent_id'] ?? $folder->parent_id,
        ]);

        // Fiziksel klasörü taşı
        if ($oldSlug !== $folder->folder_slug) {
            Storage::disk('public')->move('folders/' . $oldSlug, 'folders/' . $folder->folder_slug);
        }

        return $folder;
    }

    /**
     * Klasörü siler.
     *
     * @param Folder $folder
     * @param bool $force
     * @return bool
     */
    public function delete(Folder $folder, bool $force = false)
    {
        if ($force) {
            // Klasör ve içeriğini sil
            Storage::disk('public')->deleteDirectory($folder->path);
            $folder->forceDelete();
        } else {
            // Sadece veritabanı kaydını sil
            $folder->delete();
        }
    }

    /**
     * Klasör ağacını oluşturur.
     *
     * @param mixed $input ID veya folder collection olabilir
     * @return array
     */
    public function buildTree($input = null)
    {
        $tree = [];

        // Eğer $input bir koleksiyon ise, direkt işle
        if (is_object($input) && method_exists($input, 'isEmpty')) {
            $folders = $input;
        } 
        // Eğer $input null veya bir ID ise, veritabanından çek
        else {
            $parentId = $input;
            $folders = Folder::when(is_numeric($parentId), function($query) use ($parentId) {
                return $query->where('parent_id', $parentId);
            })
            ->when(is_null($parentId), function($query) {
                return $query->whereNull('parent_id');
            })
            ->orderBy('order')
            ->get();
        }

        foreach ($folders as $folder) {
            $node = [
                'id' => $folder->id,
                'name' => $folder->name,
                'slug' => $folder->slug,
                'path' => $folder->path,
                'children' => []
            ];

            if (isset($folder->children) && $folder->children->isNotEmpty()) {
                $node['children'] = $this->buildTree($folder->children);
            } else {
                $node['children'] = $this->buildTree($folder->id);
            }

            $tree[] = $node;
        }

        return $tree;
    }

    /**
     * Klasör içeriğini listeler.
     *
     * @param Folder $folder
     * @return array
     */
    public function listContents(Folder $folder)
    {
        $contents = [
            'folders' => $folder->children,
            'files' => $folder->medias,
        ];

        return $contents;
    }

    public function move(Folder $folder, int $parentId)
    {
        $oldPath = $folder->path;
        
        $folder->parent_id = $parentId;
        $folder->path = $this->generatePath(['parent_id' => $parentId]);
        $folder->save();

        // Fiziksel klasörü taşı
        Storage::disk('public')->move($oldPath, $folder->path);
    }

    public function getTree()
    {
        $folders = Folder::with('children')
            ->whereNull('parent_id')
            ->orderBy('order')
            ->get();

        return $this->buildTree($folders);
    }

    /**
     * Tüm klasörleri listeler
     */
    public function all()
    {
        return Folder::all();
    }

    private function generatePath(array $data)
    {
        $path = $data['name'];

        if (isset($data['parent_id'])) {
            $parent = Folder::find($data['parent_id']);
            if ($parent) {
                $path = $parent->path . '/' . $path;
            }
        }

        return $path;
    }
} 