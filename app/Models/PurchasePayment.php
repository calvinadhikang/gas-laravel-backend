<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchasePayment extends Model
{
    protected $table = 'purchase_payment';
    protected $fillable = [
        'purchase_id',
        'amount',
        'description',
    ];
}
