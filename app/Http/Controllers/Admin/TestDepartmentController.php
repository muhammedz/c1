<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class TestDepartmentController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'message' => 'Test departments sayfası artık kaldırıldı.'
        ]);
    }
} 