<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    protected $fillable = [
        'sale_id',
        'product_id',
        'quantity',
        'price_at_sale',
    ];

    protected function casts(): array
    {
        return [
            'price_at_sale' => 'decimal:2',
        ];
    }

    /**
     * Total for this line: quantity * price_at_sale.
     */
    public function getTotalItemAmountAttribute(): float
    {
        return (float) ($this->quantity * $this->price_at_sale);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
