<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaleRequest;
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
        $sales = auth()->user()
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
    public function store(SaleRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $totalAmount = collect($validated['items'])
            ->reduce(fn (float $carry, array $item) => $carry + ($item['quantity'] * (float) $item['price']), 0.0);

        DB::transaction(function () use ($validated, $totalAmount): void {
            $sale = auth()->user()->sales()->create([
                'customer_id' => $validated['customer_id'],
                'sale_date' => $validated['sale_date'],
                'total_amount' => round($totalAmount, 2),
                'status' => $validated['status'],
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($validated['items'] as $item) {
                $sale->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => (int) $item['quantity'],
                    'price_at_sale' => (float) $item['price'],
                ]);
            }
        });

        return redirect()
            ->route('sales.index')
            ->with('success', 'Venda registrada com sucesso');
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
    public function destroy(Sale $sale): RedirectResponse
    {
        $sale->delete();

        return redirect()
            ->route('sales.index')
            ->with('success', 'Venda excluída com sucesso');
    }
}
