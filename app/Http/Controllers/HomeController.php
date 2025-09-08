<?php

namespace App\Http\Controllers;
use Illuminate\Contracts\View\Factory as ViewFactory;

use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function index()
    {
        return view('home');
    }
}
