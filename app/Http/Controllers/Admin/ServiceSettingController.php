<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServiceSetting;
use Illuminate\Support\Facades\Validator;

/**
 * @deprecated Bu controller artık kullanılmamaktadır. ServiceController içindeki settings metodu kullanılmalıdır.
 */
class ServiceSettingController extends Controller
{
    /**
     * Hizmet sayfası ayarlarını düzenleme sayfasını göster
     * @deprecated Bu metod kullanılmamaktadır
     */
    public function edit()
    {
        abort(404, 'Bu sayfa artık kullanılmamaktadır.');
    }
    
    /**
     * Hizmet sayfası ayarlarını güncelle
     * @deprecated Bu metod kullanılmamaktadır
     */
    public function update(Request $request)
    {
        abort(404, 'Bu sayfa artık kullanılmamaktadır.');
    }
} 