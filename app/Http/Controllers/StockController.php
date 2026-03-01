<?php

namespace App\Http\Controllers;

use App\Http\Requests\StockRequest;
use App\Models\Stock;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stocks = Auth::user()->stock()->get();

        return view('pages.stock', compact('stocks'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StockRequest $request)
    {
        $validated = $request->validated();

        auth()->user()->stock()->updateOrCreate(
            [
                'product_id' => $validated['product_id'],
            ],
            [
                'quantity' => DB::raw('quantity + ' . $validated['quantity']),
            ]
        );

        return redirect()
            ->route('stocks.index')
            ->with('success', 'Quantidade registrada com sucesso');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Stock $stock)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Stock $stock)
    {
        //
    }
}
