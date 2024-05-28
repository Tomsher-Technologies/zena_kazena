<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderTransfers extends Model
{
    use HasFactory;

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function shopFrom()
    {
        return $this->belongsTo(Shop::class, 'shop_from', 'id');
    }

    public function shopTo()
    {
        return $this->belongsTo(Shop::class, 'shop_to', 'id');
    }
}
