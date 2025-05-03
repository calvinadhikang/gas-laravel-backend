<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HPurchase extends Model
{
    use SoftDeletes;

    protected $table = 'hpurchase';
    protected $fillable = [
        'vendor_id',
        'code',
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

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function product()
    {
        return $this->hasMany(DPurchase::class);
    }
}
