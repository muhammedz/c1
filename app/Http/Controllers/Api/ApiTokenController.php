<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class ApiTokenController extends Controller
{
    /**
     * API token oluşturur (Sanctum ile)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createToken(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Kimlik bilgileri geçersiz',
                'status' => 'error'
            ], 401);
        }

        $token = $user->createToken($request->device_name)->plainTextToken;

        return response()->json([
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'status' => 'success'
        ]);
    }

    /**
     * Kullanıcının tüm tokenlarını siler
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function revokeTokens(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Tüm tokenlar silindi',
            'status' => 'success'
        ]);
    }

    /**
     * Belirli bir tokeni siler
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function revokeToken(Request $request, $id)
    {
        $request->user()->tokens()->where('id', $id)->delete();

        return response()->json([
            'message' => 'Token silindi',
            'status' => 'success'
        ]);
    }

    /**
     * Kullanıcıya ait tokenları listeler
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function tokens(Request $request)
    {
        $tokens = $request->user()->tokens;

        return response()->json([
            'tokens' => $tokens,
            'status' => 'success'
        ]);
    }
} 