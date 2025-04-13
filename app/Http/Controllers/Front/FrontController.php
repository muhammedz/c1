<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    /**
     * Projeler sayfasını göster
     */
    public function projects(Request $request)
    {
        $settings = \App\Models\ProjectSettings::getSettings();
        
        // Eğer modül aktif değilse ana sayfaya yönlendir
        if (!$settings->is_active) {
            return redirect()->route('front.index');
        }
        
        // Aktif kategoriler
        $categories = \App\Models\ProjectCategory::active()
            ->withCount(['activeProjects'])
            ->orderBy('order')
            ->get();
        
        // Seçilen kategori
        $categoryId = $request->input('category');
        $selectedCategory = null;
        
        if ($categoryId) {
            $selectedCategory = $categories->firstWhere('id', $categoryId);
        }
        
        // Projeler sorgusu
        $projectsQuery = \App\Models\Project::active()->orderBy('order');
        
        // Kategori filtresi
        if ($selectedCategory) {
            $projectsQuery->where('category_id', $selectedCategory->id);
        }
        
        // Sayfalama
        $projects = $projectsQuery->paginate($settings->items_per_page);
        
        return view('front.projects.index', compact('projects', 'categories', 'selectedCategory', 'settings'));
    }

    /**
     * Proje detay sayfasını göster
     */
    public function projectDetail($slug)
    {
        $settings = \App\Models\ProjectSettings::getSettings();
        
        // Eğer modül aktif değilse ana sayfaya yönlendir
        if (!$settings->is_active) {
            return redirect()->route('front.index');
        }
        
        // Projeyi bul
        $project = \App\Models\Project::active()
            ->with(['category', 'gallery'])
            ->where('slug', $slug)
            ->firstOrFail();
        
        // Diğer projeler (aynı kategorideki)
        $relatedProjects = \App\Models\Project::active()
            ->where('id', '!=', $project->id)
            ->where(function($query) use ($project) {
                // Aynı kategorideki projeleri veya herhangi bir projeden seçim yap
                if ($project->category_id) {
                    $query->where('category_id', $project->category_id);
                }
            })
            ->orderBy('order')
            ->limit(3)
            ->get();
        
        return view('front.projects.detail', compact('project', 'relatedProjects', 'settings'));
    }
} 