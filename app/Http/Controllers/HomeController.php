<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $headlines = News::getHeadlines();
        $normalNews = News::getNormalNews();
        
        return view('home', compact('headlines', 'normalNews'));
    }
}
