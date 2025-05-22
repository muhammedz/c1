<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchPageController extends Controller
{
    /**
     * Arama sayfasını görüntüle
     */
    public function index(Request $request)
    {
        return "Arama sayfası test! Bu sayfa görünüyorsa, rota çalışıyor demektir.";
    }
}
