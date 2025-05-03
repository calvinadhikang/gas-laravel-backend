<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductProfit extends Model
{
    use SoftDeletes;

    protected $table = 'product_profit';
    protected $fillable = [
        'invoice_id',
        'product_id',
        'quantity',
        'price',
        'base_price',
        'profit',
        'total_profit',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function invoice()
    {
        return $this->belongsTo(HInvoice::class);
    }
}
