<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class FileManagerController extends Controller
{
    public function index()
    {
        return view('admin.filemanager.index');
    }
} 