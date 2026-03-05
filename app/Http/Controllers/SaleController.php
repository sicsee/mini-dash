<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SaleController extends Controller
{   
    public function __construct()
    {
        $this->authorizeResource(Sale::class, parameter: 'sale');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $sales = auth()->user()->sales()->get();
        
        return view('pages.sales', compact('sales'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $validated = request()->validated();

        auth()->user()->sales()->create($validated);

        return redirect()
        ->route('sales.index')
        ->with('success', 'Venda registrada com sucesso');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sale $sale)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sale $sale)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        //
    }
}
