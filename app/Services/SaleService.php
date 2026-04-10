<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Exception;

class SaleService
{
    /**
     * Create a new sale with stock validation and deduction
     */
    public function create(array $data, int $userId): Sale
    {
        return DB::transaction(function () use ($data, $userId) {
            // Pre-flight: validate stock before creating any items
            foreach ($data['items'] as $index => $item) {
                $product = Product::with('stock')->findOrFail($item['product_id']);
                if ($product->stock->quantity < $item['quantity']) {
                    throw new Exception("Estoque insuficiente para o produto: {$product->name}");
                }
            }

            $sale = self::createSaleHeader($data, $userId);
            self::createSaleItemsAndUpdateStock($sale, $data['items']);

            return $sale;
        });
    }

    /**
     * Update an existing sale with stock validation and adjustment
     */
    public function update(Sale $sale, array $data): Sale
    {
        return DB::transaction(function () use ($sale, $data) {
            // 1. Pre-flight: validate stock availability (summing what will be returned)
            $stockAvailable = [];
            foreach ($sale->items as $oldItem) {
                $stockAvailable[$oldItem->product_id] = ($stockAvailable[$oldItem->product_id] ?? $oldItem->product->stock->quantity) + $oldItem->quantity;
            }
            foreach ($data['items'] as $itemData) {
                $available = $stockAvailable[$itemData['product_id']]
                    ?? Product::with('stock')->findOrFail($itemData['product_id'])->stock->quantity;
                if ($available < $itemData['quantity']) {
                    throw new Exception("Estoque insuficiente para: " . Product::find($itemData['product_id'])->name);
                }
            }

            // 2. Return old stock
            foreach ($sale->items as $oldItem) {
                $oldItem->product->stock->increment('quantity', $oldItem->quantity);
            }

            // 3. Update sale header
            $sale->update(self::prepareSaleHeaderData($data));

            // 4. Remove old items and insert new ones
            $sale->items()->delete();
            self::createSaleItemsAndUpdateStock($sale, $data['items']);

            return $sale;
        });
    }

    /**
     * Delete a sale and return stock to inventory
     */
    public function delete(Sale $sale): void
    {
        // Return stock of items before deleting the sale
        foreach ($sale->items as $item) {
            $item->product->stock->increment('quantity', $item->quantity);
        }

        $sale->delete();
    }

    /**
     * Create sale header record
     */
    private function createSaleHeader(array $data, int $userId): Sale
    {
        return auth()->user()->sales()->create([
            'customer_id' => $data['customer_id'],
            'sale_date' => $data['sale_date'] == date('Y-m-d')
                ? now()
                : $data['sale_date'] . ' ' . now()->format('H:i:s'),
            'status' => $data['status'],
            'notes' => $data['notes'],
            'total_amount' => collect($data['items'])->sum(fn ($i) => $i['quantity'] * $i['price']),
        ]);
    }

    /**
     * Prepare sale header data for update
     */
    private function prepareSaleHeaderData(array $data): array
    {
        return [
            'customer_id' => $data['customer_id'],
            'sale_date' => $data['sale_date'],
            'status' => $data['status'],
            'notes' => $data['notes'],
            'total_amount' => collect($data['items'])->sum(fn ($i) => $i['quantity'] * $i['price']),
        ];
    }

    /**
     * Create sale items and update stock quantities
     */
    private function createSaleItemsAndUpdateStock(Sale $sale, array $items): void
    {
        foreach ($items as $item) {
            $sale->items()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price_at_sale' => $item['price'],
            ]);

            $product = Product::with('stock')->findOrFail($item['product_id']);
            $product->stock->decrement('quantity', $item['quantity']);
        }
    }
}