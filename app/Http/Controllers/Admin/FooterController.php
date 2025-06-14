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
            'company_name' => 'required|string|max:255',
            'company_subtitle' => 'required|string|max:255',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'required|string|max:255',
            'contact_center_title' => 'required|string|max:255',
            'contact_center_phone' => 'required|string|max:255',
            'whatsapp_title' => 'required|string|max:255',
            'whatsapp_number' => 'required|string|max:255',
            'email_title' => 'required|string|max:255',
            'email_address' => 'required|email|max:255',
            'kep_title' => 'required|string|max:255',
            'kep_address' => 'required|string|max:255',
            'copyright_left' => 'required|string',
            'copyright_right' => 'required|string',
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
