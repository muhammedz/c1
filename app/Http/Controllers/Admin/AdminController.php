<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     * 
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }
    
    /**
     * Aktif kullanıcıyı döndürür.
     * 
     * @return \App\Models\User
     */
    protected function user()
    {
        return auth()->user();
    }
    
    /**
     * İzin kontrolü yapar.
     * 
     * @param string $permission
     * @return boolean
     */
    protected function can($permission)
    {
        return $this->user()->can($permission);
    }
}
