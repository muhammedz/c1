<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('roles')->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'nullable|array',
            'is_active' => 'nullable|boolean',
            'bio' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->has('is_active');
        
        // Avatar yükleme işlemi
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $avatarPath;
        }
        
        $user = User::create($validated);
        
        // Rolleri atama
        if ($request->has('roles')) {
            // Rol ID'lerini rol isimlerine çevir
            $roleNames = Role::whereIn('id', $request->roles)->pluck('name')->toArray();
            $user->syncRoles($roleNames);
        }
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Kullanıcı başarıyla oluşturuldu.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::with('roles')->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::with('roles')->findOrFail($id);
        $roles = Role::all();
        $userRoles = $user->roles->pluck('id')->toArray();
        
        return view('admin.users.edit', compact('user', 'roles', 'userRoles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'nullable|array',
            'is_active' => 'nullable|boolean',
            'bio' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }
        
        $validated['is_active'] = $request->has('is_active');
        
        // Avatar yükleme işlemi
        if ($request->hasFile('avatar')) {
            // Eski avatarı sil
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $avatarPath;
        }
        
        $user->update($validated);
        
        // Rolleri güncelleme
        if ($request->has('roles')) {
            // Rol ID'lerini rol isimlerine çevir
            $roleNames = Role::whereIn('id', $request->roles)->pluck('name')->toArray();
            $user->syncRoles($roleNames);
        } else {
            $user->syncRoles([]);
        }
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Kullanıcı başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        
        // Admin kullanıcıyı silmeyi engelle
        if ($user->hasRole('admin') && User::role('admin')->count() <= 1) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Son admin kullanıcısı silinemez.');
        }
        
        $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Kullanıcı başarıyla silindi.');
    }
}
