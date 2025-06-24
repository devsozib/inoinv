<?php

namespace App\Models;

use App\Models\SalesItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function items()
    {
        return $this->hasMany(SalesItem::class);
    }

}
