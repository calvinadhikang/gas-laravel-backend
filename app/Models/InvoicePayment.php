<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoicePayment extends Model
{
    protected $table = 'invoice_payment';
    protected $fillable = [
        'invoice_id',
        'amount',
        'description',
    ];
}
