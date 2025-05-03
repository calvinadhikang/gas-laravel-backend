<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
    use SoftDeletes;

    protected $table = 'inventory';
    protected $fillable = [
        'product_id',
        'stock',
        'stock_used',
        'base_price',
        'type',
        'description',
        'reference_id'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
