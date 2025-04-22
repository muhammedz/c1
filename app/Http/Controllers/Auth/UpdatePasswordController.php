<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class UpdatePasswordController extends Controller
{
    /**
     * Kullanıcının şifresini günceller
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => ['required', function ($attribute, $value, $fail) use ($user) {
                if (!Hash::check($value, $user->password)) {
                    $fail('Mevcut şifreniz doğru değil.');
                }
            }],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user->password = Hash::make($validated['password']);
        $user->save();

        return redirect()->route('admin.profile')->with('success', 'Şifreniz başarıyla güncellendi.');
    }
} 