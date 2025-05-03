<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HInvoice extends Model
{
    use SoftDeletes;

    protected $table = 'hinvoice';
    protected $fillable = [
        'code',
        'type',
        'purchase_code',
        'car_type',
        'car_number',
        'status',
        'payment_status',
        'total',
        'paid',
        'ppn',
        'ppn_value',
        'grand_total',
        'description',
        'created_by',
        'deleted_by',
        'deleted_at',
        'payment_due_date',
        'created_at',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
