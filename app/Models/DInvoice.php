<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DInvoice extends Model
{
    protected $table = 'dinvoice';
    protected $fillable = [
        'invoice_id',
        'product_id',
        'price',
        'quantity',
        'total',
        'ppn',
        'ppn_value',
        'grand_total',
    ];
}
