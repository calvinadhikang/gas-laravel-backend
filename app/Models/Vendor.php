<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use SoftDeletes;

    protected $table = 'vendor';
    protected $fillable = [
        'name',
        'phone',
        'address',
        'npwp',
        'email'
    ];
}
