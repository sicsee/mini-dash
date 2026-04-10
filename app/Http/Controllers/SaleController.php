<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaleRequest;
use App\Models\Sale;
use App\Services\SaleService;
use Illuminate\Http\RedirectResponse;
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
    public function store(SaleRequest $request, SaleService $saleService)
    {
        try {
            $saleService->create($request->validated(), auth()->id());
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
    public function update(SaleRequest $request, Sale $sale, SaleService $saleService)
    {
        try {
            $saleService->update($sale, $request->validated());
            return redirect()->back()->with('success', 'Venda atualizada!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale, SaleService $saleService): RedirectResponse
    {
        $saleService->delete($sale);
        return redirect()->route('sales.index')->with('success', 'Venda excluída com sucesso');
    }
}
