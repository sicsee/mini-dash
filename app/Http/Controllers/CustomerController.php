<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{   
    public function __construct()
    {
        $this->authorizeResource(Customer::class, 'customers');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Auth::user()->customers()->get();

        return view('pages.customers', compact('customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerRequest $request)
    {
        $validated = $request->validated();

        auth()->user()->customers()->create($validated);

        return redirect()
            ->route('customers.index')
            ->with('success', 'Cliente criado com sucesso');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerRequest $request, Customer $customer)
    {
        $customer->update($request->all());

        return redirect()
            ->route('customers.index')
            ->with('success',value: 'Cliente atualizado com sucesso');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        //
    }
}
