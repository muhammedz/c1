<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementFrontController extends Controller
{
    /**
     * Kullanıcının bir duyuruyu gördüğünü işaretleyen metot
     */
    public function markViewed(Request $request)
    {
        $announcementId = $request->input('id');
        
        if (!$announcementId) {
            return response()->json(['success' => false, 'message' => 'Duyuru ID\'si gerekli'], 400);
        }
        
        $announcement = Announcement::find($announcementId);
        
        if (!$announcement) {
            return response()->json(['success' => false, 'message' => 'Duyuru bulunamadı'], 404);
        }
        
        // Kullanıcının cookie'sinde görülen duyurular listesini al
        $viewedAnnouncements = json_decode($request->cookie('viewed_announcements', '[]'), true);
        
        // Bu duyuru zaten görüldü mü kontrol et
        if (!in_array($announcementId, $viewedAnnouncements)) {
            // Görülen duyurular listesine ekle
            $viewedAnnouncements[] = $announcementId;
            
            // ✅ Güvenli çerez oluşturma (Secure, HttpOnly, SameSite=Strict)
            $cookie = cookie(
                'viewed_announcements',                // name
                json_encode($viewedAnnouncements),     // value
                525600,                                // minutes (1 yıl)
                '/',                                   // path
                null,                                  // domain (otomatik)
                request()->secure(),                   // secure (HTTPS kontrolü)
                true,                                  // httpOnly (JavaScript erişimi engelle)
                false,                                 // raw
                'strict'                               // sameSite (maksimum güvenlik)
            );
            
            return response()->json(['success' => true])
                ->cookie($cookie);
        }
        
        return response()->json(['success' => true, 'message' => 'Duyuru zaten görüntülenmiş']);
    }
}
