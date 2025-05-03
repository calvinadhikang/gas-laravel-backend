<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DPurchase extends Model
{
    protected $table = 'dpurchase';
    protected $fillable = [
        'purchase_id',
        'product_id',
        'price',
        'quantity',
        'total',
        'ppn',
        'ppn_value',
        'grand_total',
    ];
}
