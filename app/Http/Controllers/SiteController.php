<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class SiteController extends Controller
{
    public function index(): View
    {
        return view('pages.home');
    }

    public function dashboard(): View
    {
        $produtos = auth()->user()->products;

        return view('pages.dashboard', compact('produtos'));
    }
}
