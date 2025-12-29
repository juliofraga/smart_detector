<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Lang;

class HomeController extends Controller
{

    public function index()
    {
        $translations = Lang::get('text.home_domain');
        return view('home', ['translations' => $translations]);
    }
}
