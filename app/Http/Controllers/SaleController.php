<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
            ->with(['customer', 'items.product'])
            ->latest('sale_date')
            ->get();
        $customers = auth()->user()->customers()->get();
        $products = auth()->user()->products()->get();

        return view('pages.sales', compact('sales', 'customers', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $sale = auth()->user()->sales()->create([
                    'customer_id' => $request->customer_id,
                    'sale_date' => $request->sale_date == date('Y-m-d')
                                    ? now()
                                    : $request->sale_date.' '.now()->format('H:i:s'),
                    'status' => $request->status,
                    'notes' => $request->notes,
                    'total_amount' => collect($request->items)->sum(fn ($i) => $i['quantity'] * $i['price']),
                ]);

                foreach ($request->items as $item) {
                    // ALTERAÇÃO AQUI: Mapeamos 'price' do form para 'price_at_sale' do banco
                    $sale->items()->create([
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price_at_sale' => $item['price'],
                    ]);

                    // Buscamos o produto com o estoque para validação e desconto
                    $product = Product::with('stock')->findOrFail($item['product_id']);

                    if ($product->stock->quantity < $item['quantity']) {
                        throw new \Exception("Estoque insuficiente para o produto: {$product->name}");
                    }

                    // Descontamos da tabela stocks (coluna quantity)
                    $product->stock->decrement('quantity', $item['quantity']);
                }
            });

            return redirect()->back()->with('success', 'Venda registrada com sucesso!');
        } catch (\Exception $e) {
            // O rollback é automático pelo DB::transaction em caso de Exception
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
    public function update(Request $request, Sale $sale)
    {
        try {
            DB::transaction(function () use ($request, $sale) {
                // 1. Devolver estoque antigo (relacionamento stock -> quantity)
                foreach ($sale->items as $oldItem) {
                    $oldItem->product->stock->increment('quantity', $oldItem->quantity);
                }

                // 2. Atualizar cabeçalho da venda
                $sale->update([
                    'customer_id' => $request->customer_id,
                    'sale_date' => $request->sale_date,
                    'status' => $request->status,
                    'notes' => $request->notes,
                    'total_amount' => collect($request->items)->sum(fn ($i) => $i['quantity'] * $i['price']),
                ]);

                // 3. Remover itens antigos e inserir os novos mapeando o preço
                $sale->items()->delete();

                foreach ($request->items as $itemData) {
                    $sale->items()->create([
                        'product_id' => $itemData['product_id'],
                        'quantity' => $itemData['quantity'],
                        'price_at_sale' => $itemData['price'],
                    ]);

                    // 4. Descontar novo estoque
                    $product = Product::with('stock')->findOrFail($itemData['product_id']);
                    if ($product->stock->quantity < $itemData['quantity']) {
                        throw new \Exception("Estoque insuficiente para: {$product->name}");
                    }
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
        $sale->delete();

        return redirect()->route('sales.index')->with('success', 'Venda excluída com sucesso');
    }
}
