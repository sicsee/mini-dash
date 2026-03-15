<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\View\View;

class SiteController extends Controller
{
    public function index(): View
    {
        return view('pages.home');
    }

    public function dashboard()
    {
        $sales = auth()->user()->sales()->with('customer')->latest()->take(6)->get();

        return view('pages.dashboard', [
            'total' => auth()->user()->sales()
                ->where('status', 'concluida') // ou 'concluded', 'pago', etc.
                ->sum('total_amount'),
            'count' => auth()->user()->sales()->count(),
            'avg' => auth()->user()->sales()->avg('total_amount') ?? 0,
            'alert' => Product::whereHas('stock', fn ($q) => $q->where('quantity', '<=', 5))->count(),
            'sales' => $sales,
        ]);
    }
}
