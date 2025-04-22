<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PasswordProtectionController extends Controller
{
    /**
     * Şifre girişi sayfasını göster
     */
    public function show()
    {
        return view('password-protection');
    }

    /**
     * Şifre doğrulaması yap
     */
    public function check(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);

        // Sabit şifre kontrol edelim (güvenlik için normalde env dosyasından alınmalı)
        $correctPassword = env('SITE_PASSWORD', 'sifreyigirin123');

        if ($request->password === $correctPassword) {
            // Şifre doğruysa oturuma kaydet
            $request->session()->put('site_password_verified', true);
            
            // Ana sayfaya yönlendir
            return redirect()->route('front.home');
        }

        // Şifre yanlışsa hata ile geri dön
        return back()->withErrors([
            'password' => 'Şifre hatalı!'
        ]);
    }
}
