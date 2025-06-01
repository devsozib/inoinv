<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_no',
        'customer_id',
        'bill',
        'discount',
        'payble',
        'sales_by',
        'status',
    ];
}
