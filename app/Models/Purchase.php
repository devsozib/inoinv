<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    // Optional: define table name if not following conventions
    protected $table = 'purchases';

    // Mass assignable fields
    protected $fillable = [
        'product_id',
        'quantity',
        'unit_price',
        'sub_price',
        'total_price',
        'payment',
        'due',
        'created_by',
        'updated_by',
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
