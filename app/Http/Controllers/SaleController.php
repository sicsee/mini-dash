<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaleRequest;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
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
        $sales = auth()
            ->user()
            ->sales()
            ->with('items.product')
            ->latest('sale_date')
            ->get();
        $customers = auth()->user()->customers()->get();
        $products = auth()->user()->products()->get();

        return view('pages.sales', compact('sales', 'customers', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SaleRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                // Pre-flight: validar estoque antes de criar qualquer item
                foreach ($request->items as $index => $item) {
                    $product = Product::with('stock')->findOrFail($item['product_id']);
                    if ($product->stock->quantity < $item['quantity']) {
                        throw new \Exception("Estoque insuficiente para o produto: {$product->name}");
                    }
                }

                $sale = auth()->user()->sales()->create([
                    'customer_id' => $request->customer_id,
                    'sale_date' => $request->sale_date == date('Y-m-d')
                                    ? now()
                                    : $request->sale_date.' '.now()->format('H:i:s'),
                    'status' => $request->status,
                    'notes' => $request->notes,
                    'total_amount' => collect($request->items)->sum(fn ($i) => $i['quantity'] * $i['price']),
                ]);

                // Criar itens e descontar estoque
                foreach ($request->items as $item) {
                    $sale->items()->create([
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price_at_sale' => $item['price'],
                    ]);

                    $product = Product::with('stock')->findOrFail($item['product_id']);
                    $product->stock->decrement('quantity', $item['quantity']);
                }
            });

            return redirect()->back()->with('success', 'Venda registrada com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
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
    public function update(SaleRequest $request, Sale $sale)
    {
        try {
            DB::transaction(function () use ($request, $sale) {
                // 1. Pre-flight: validar estoque disponível (somando o que será devolvido)
                $stockAvailable = [];
                foreach ($sale->items as $oldItem) {
                    $stockAvailable[$oldItem->product_id] = ($stockAvailable[$oldItem->product_id] ?? $oldItem->product->stock->quantity) + $oldItem->quantity;
                }
                foreach ($request->items as $itemData) {
                    $available = $stockAvailable[$itemData['product_id']]
                        ?? Product::with('stock')->findOrFail($itemData['product_id'])->stock->quantity;
                    if ($available < $itemData['quantity']) {
                        throw new \Exception("Estoque insuficiente para: " . Product::find($itemData['product_id'])->name);
                    }
                }

                // 2. Devolver estoque antigo
                foreach ($sale->items as $oldItem) {
                    $oldItem->product->stock->increment('quantity', $oldItem->quantity);
                }

                // 3. Atualizar cabeçalho da venda
                $sale->update([
                    'customer_id' => $request->customer_id,
                    'sale_date' => $request->sale_date,
                    'status' => $request->status,
                    'notes' => $request->notes,
                    'total_amount' => collect($request->items)->sum(fn ($i) => $i['quantity'] * $i['price']),
                ]);

                // 4. Remover itens antigos e inserir os novos
                $sale->items()->delete();

                foreach ($request->items as $itemData) {
                    $sale->items()->create([
                        'product_id' => $itemData['product_id'],
                        'quantity' => $itemData['quantity'],
                        'price_at_sale' => $itemData['price'],
                    ]);

                    $product = Product::with('stock')->findOrFail($itemData['product_id']);
                    $product->stock->decrement('quantity', $itemData['quantity']);
                }
            });

            return redirect()->back()->with('success', 'Venda atualizada!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale): RedirectResponse
    {
        // Devolve o estoque dos itens antes de deletar a venda
        foreach ($sale->items as $item) {
            $item->product->stock->increment('quantity', $item->quantity);
        }

        $sale->delete();

        return redirect()->route('sales.index')->with('success', 'Venda excluída com sucesso');
    }
}
