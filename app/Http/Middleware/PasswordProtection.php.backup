<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PasswordProtection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Admin paneline erişimde şifre koruması uygulanmasın
        if (strpos($request->path(), 'admin') === 0 || $request->is('login') || $request->is('password-protection') || $request->is('check-site-password')) {
            return $next($request);
        }

        // Şifre oturumda varsa devam et
        if ($request->session()->has('site_password_verified') && $request->session()->get('site_password_verified') === true) {
            return $next($request);
        }

        // Şifre oturumda yoksa şifre sayfasına yönlendir
        return redirect()->route('password.protection');
    }
}
