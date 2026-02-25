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
        'total_item_amount',
    ];

    protected $casts = [
        'price_at_sale' => 'decimal:2',
        'total_item_amount' => 'decimal:2',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
