<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class SiteController extends Controller
{
    public function index(): View
    {
        return view("home");
    }

    public function dashboard(): View
    {
        $produtos = auth()->user()->products;

        return view('dashboard', compact('produtos'));
    }
}
