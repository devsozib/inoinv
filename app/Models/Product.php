<?php

namespace App\Models;

use App\Models\Purchase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    public function latestPurchase()
    {
        return $this->hasOne(Purchase::class)->latestOfMany(); // Laravel 8+
    }
}
