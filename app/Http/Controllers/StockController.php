<?php

namespace App\Http\Controllers;

use App\Http\Requests\StockRequest;
use App\Models\Stock;
use Illuminate\Support\Facades\Auth;

class StockController extends Controller
{   
    public function __construct()
    {
        $this->authorizeResource(Stock::class, 'stocks');
    }
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

        $stock = auth()->user()->stock()
            ->where('product_id', $validated['product_id'])
            ->first();

        if ($stock) {
            $stock->increment('quantity', $validated['quantity']);
        } else {
            auth()->user()->stock()->create([
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity'],
            ]);
        }

        return redirect()
            ->route('stocks.index')
            ->with('success', 'Quantidade registrada com sucesso');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StockRequest $request, Stock $stock)
    {
        $stock->update([
            'quantity' => $request->quantity,
        ]);

        return redirect()
            ->route('stocks.index')
            ->with('success', 'Quantidade atualizada com sucesso');
    }
}
