<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestController extends Controller
{
    /**
     * Test sayfasını gösterir
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('admin.test.index');
    }
} 