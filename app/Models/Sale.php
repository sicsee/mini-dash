<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Sale extends Model
{

    protected $fillable = [
        'user_id',
        'customer_id',
        'sale_date',
        'total_amount',
        'status',
        'notes',
    ];

    protected $casts = [
        'sale_date' => 'datetime',
        'total_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }
}
