<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FooterMenu;
use App\Models\FooterSetting;
use Illuminate\Support\Facades\Storage;

class FooterController extends Controller
{
    public function index()
    {
        $menus = FooterMenu::with('activeLinks')->active()->ordered()->get();
        $settings = FooterSetting::getSettings();
        
        return view('admin.footer.index', compact('menus', 'settings'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'contact_center_title' => 'nullable|string|max:255',
            'contact_center_phone' => 'nullable|string|max:255',
            'whatsapp_title' => 'nullable|string|max:255',
            'whatsapp_number' => 'nullable|string|max:255',
            'email_title' => 'nullable|string|max:255',
            'email_address' => 'nullable|email|max:255',
            'kep_title' => 'nullable|string|max:255',
            'kep_address' => 'nullable|string|max:255',
            'copyright_left' => 'nullable|string',
            'copyright_right' => 'nullable|string',
            'facebook_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'twitter_url' => 'nullable|url|max:255',
            'youtube_url' => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $settings = FooterSetting::first();
        if (!$settings) {
            $settings = new FooterSetting();
        }

        // Logo yükleme
        if ($request->hasFile('logo')) {
            // Eski logoyu sil
            if ($settings->logo) {
                $oldLogoPath = public_path('uploads/' . $settings->logo);
                if (file_exists($oldLogoPath)) {
                    unlink($oldLogoPath);
                }
            }
            
            $logoFile = $request->file('logo');
            $logoName = time() . '_' . $logoFile->getClientOriginalName();
            $logoPath = 'footer/logos/' . $logoName;
            
            // uploads klasörünü oluştur (yoksa)
            $uploadDir = public_path('uploads/footer/logos');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            // uploads klasörüne kaydet
            $logoFile->move($uploadDir, $logoName);
            $settings->logo = $logoPath;
        }

        $settings->fill($request->except(['logo']));
        $settings->save();

        return redirect()->back()->with('success', 'Footer ayarları başarıyla güncellendi.');
    }

    public function deleteLogo()
    {
        $settings = FooterSetting::first();
        if ($settings && $settings->logo) {
            $logoPath = public_path('uploads/' . $settings->logo);
            if (file_exists($logoPath)) {
                unlink($logoPath);
            }
            $settings->logo = null;
            $settings->save();
        }

        return redirect()->back()->with('success', 'Logo başarıyla silindi.');
    }
}
