<?php

namespace App\Http\Controllers;

use App\Http\Requests\StockRequest;
use App\Models\Stocks;

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
    public function update(StockRequest $request, Stocks $stock)
    {
        if($stock->user_id !== auth()->user()->id){
            abort(403, 'Esse produto não é seu');
        }

        $stock->update($request->all());

        return redirect()
            ->route('stocks.index')
            ->with('success','Produto removido com sucesso');
    }
    
}

