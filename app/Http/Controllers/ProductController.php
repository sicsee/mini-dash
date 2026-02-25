<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('products.products');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {  
        $validated = $request->validated();

        auth()->user()->products()->create($validated);

        return redirect()
            ->route('site.dashboard')
            ->with('success', 'Produtoi criado com sucesso');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('products.edit' , compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, Product $product)
    {
        if($product->user_id !== auth()->user()->id){
            abort(403, 'Esse produto não é seu');
        }

        $product->update($request->all());

        return redirect()
        ->route('site.dashboard')
        ->with('success',value: 'Produto atualizado com sucesso');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if($product->user_id !== auth()->user()->id){
            abort(403, 'Esse produto não é seu');
        }

        $product->delete();

        return redirect()
            ->route('site.dashboard')
            ->with('success','Produto removido com sucesso');
    }
}
