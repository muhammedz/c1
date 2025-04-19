<?php

namespace App\Services\FileManagerSystem;

use App\Models\FileManagerSystem\Category;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class FilemanagersystemCategoryService
{
    /**
     * Tüm kategorileri getirir
     */
    public function all(): Collection
    {
        return Category::orderBy('name')->get();
    }

    /**
     * Yeni kategori oluşturur
     */
    public function create(array $data): Category
    {
        $data['slug'] = Str::slug($data['name']);
        $data['user_id'] = Auth::id();
        
        return Category::create($data);
    }

    /**
     * Kategoriyi günceller
     */
    public function update(Category $category, array $data): bool
    {
        $data['slug'] = Str::slug($data['name']);
        return $category->update($data);
    }

    /**
     * Kategoriyi siler
     */
    public function delete(Category $category): bool
    {
        return $category->delete();
    }

    /**
     * Belirli bir kategoriyi getirir
     */
    public function find(int $id): ?Category
    {
        return Category::find($id);
    }

    /**
     * Kategoriye ait dosya sayısını getirir
     */
    public function getFileCount(Category $category): int
    {
        return $category->media()->count();
    }

    /**
     * Kategoriye ait toplam dosya boyutunu getirir
     */
    public function getTotalSize(Category $category): int
    {
        return $category->media()->sum('size');
    }
} 