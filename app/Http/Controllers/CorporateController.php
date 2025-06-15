<?php

namespace App\Http\Controllers;

use App\Models\CorporateCategory;
use App\Models\CorporateMember;
use Illuminate\Http\Request;

class CorporateController extends Controller
{
    /**
     * Tüm kurumsal kadro kategorilerini gösterir
     */
    public function index()
    {
        $categories = CorporateCategory::active()->ordered()->get();
        return view('frontend.corporate.index', compact('categories'));
    }

    /**
     * Belirli bir kategorinin detayını ve üyelerini gösterir
     */
    public function showCategory($slug)
    {
        $category = CorporateCategory::where('slug', $slug)
            ->active()
            ->firstOrFail();
            
        $members = CorporateMember::where('corporate_category_id', $category->id)
            ->active()
            ->orderBy('created_at', 'asc')
            ->get();
            
        return view('frontend.corporate.category', compact('category', 'members'));
    }

    /**
     * Belirli bir üyenin detayını gösterir
     */
    public function showMember($categorySlug, $memberSlug)
    {
        // Kategori ve slug bilgilerini loglayalım
        \Log::info('showMember çağrıldı', [
            'categorySlug' => $categorySlug, 
            'memberSlug' => $memberSlug,
            'URL' => request()->fullUrl()
        ]);
        
        try {
            $category = CorporateCategory::where('slug', $categorySlug)
                ->active()
                ->firstOrFail();
            
            if (!$category) {
                \Log::error('Kategori bulunamadı', [
                    'categorySlug' => $categorySlug,
                    'SQL' => CorporateCategory::where('slug', $categorySlug)->toSql()
                ]);
                abort(404, 'Kategori bulunamadı');
            }
            
            \Log::info('Kategori bulundu', [
                'category_id' => $category->id, 
                'category_name' => $category->name, 
                'category_status' => $category->status,
                'category_slug' => $category->slug
            ]);
            
            if (!$category->status) {
                \Log::error('Kategori aktif değil', ['categorySlug' => $categorySlug]);
                abort(404, 'Kategori aktif değil');
            }
            
            $query = CorporateMember::where('slug', $memberSlug)
                ->where('corporate_category_id', $category->id);
                
            \Log::info('Üye arama sorgusu', [
                'SQL' => $query->toSql(),
                'Bindings' => $query->getBindings(),
                'memberSlug' => $memberSlug,
                'categoryId' => $category->id
            ]);
            
            $member = $query->first();
                
            if (!$member) {
                \Log::error('Üye bulunamadı', [
                    'memberSlug' => $memberSlug, 
                    'categoryId' => $category->id,
                    'SQL' => $query->toSql(),
                    'Bindings' => $query->getBindings()
                ]);
                abort(404, 'Üye bulunamadı');
            }
            
            \Log::info('Üye bulundu', [
                'member_id' => $member->id, 
                'member_name' => $member->name, 
                'member_status' => $member->status,
                'member_slug' => $member->slug,
                'member_category_id' => $member->corporate_category_id
            ]);
            
            if (!$member->status) {
                \Log::error('Üye aktif değil', ['memberSlug' => $memberSlug]);
                abort(404, 'Üye aktif değil');
            }
            
            // Eğer üyenin detay sayfası görüntülenmez olarak işaretlenmişse 404 hatası ver
            if (!$member->show_detail) {
                \Log::error('Üye detay sayfası devre dışı', ['memberSlug' => $memberSlug]);
                abort(404, 'Bu üyenin detay sayfası bulunmamaktadır');
            }
            
            return view('frontend.corporate.member', compact('category', 'member'));
        } catch (\Exception $e) {
            \Log::error('CorporateController::showMember exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            abort(500, 'Bir hata oluştu');
        }
    }
}
