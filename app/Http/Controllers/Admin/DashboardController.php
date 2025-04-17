<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Admin panel anasayfa görünümü.
     */
    public function index()
    {
        // İstatistikleri hesapla
        $stats = [
            'total_users' => User::count(),
            'total_posts' => Post::count(),
            'total_pages' => Page::count(),
            'total_categories' => Category::count(),
            'published_posts' => Post::where('is_published', true)->count(),
            'published_pages' => Page::where('is_published', true)->count(),
            'active_categories' => Category::where('is_active', true)->count(),
        ];
        
        // Son eklenen kullanıcılar
        $latest_users = User::latest()->limit(5)->get();
        
        // Son eklenen gönderiler
        $latest_posts = Post::with('creator')->latest()->limit(5)->get();
        
        return view('admin.dashboard', compact('stats', 'latest_users', 'latest_posts'));
    }
    
    /**
     * Admin paneli profil görünümü.
     */
    public function profile()
    {
        return view('admin.profile');
    }
    
    /**
     * Admin paneli profil güncelleme işlemi.
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'bio' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        if ($request->hasFile('avatar')) {
            // Avatar yükleme işlemi
            $uploadPath = 'uploads/avatars';
            $extension = $request->avatar->extension();
            $originalFilename = time();
            
            // Benzersiz dosya adı oluştur
            $avatarName = $this->createUniqueFilename($uploadPath, $originalFilename, $extension);
            
            // Eski dosyayı sil
            if ($user->avatar && file_exists(public_path($user->avatar))) {
                unlink(public_path($user->avatar));
            }
            
            $request->avatar->move(public_path($uploadPath), $avatarName);
            $validated['avatar'] = $uploadPath . '/' . $avatarName;
        }
        
        $user->update($validated);
        
        return redirect()->route('admin.profile')->with('success', 'Profil başarıyla güncellendi.');
    }
    
    /**
     * Benzersiz dosya adı oluştur
     * Eğer aynı isimde dosya varsa sonuna sayı ekler (örn: resim_1.jpg, resim_2.jpg)
     *
     * @param string $path Dizin yolu
     * @param string $filename Dosya adı (uzantısız)
     * @param string $extension Dosya uzantısı
     * @return string Benzersiz dosya adı (uzantı dahil)
     */
    private function createUniqueFilename($path, $filename, $extension)
    {
        $fullFilename = $filename . '.' . $extension;
        $fullPath = public_path($path . '/' . $fullFilename);
        
        if (!file_exists($fullPath)) {
            return $fullFilename;
        }
        
        $counter = 1;
        while (file_exists($fullPath)) {
            $fullFilename = $filename . '_' . $counter . '.' . $extension;
            $fullPath = public_path($path . '/' . $fullFilename);
            $counter++;
        }
        
        return $fullFilename;
    }
}
