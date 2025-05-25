<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyExpense extends Model
{
    use HasFactory;
    protected $fillable = [
        'date',
        'purpose_of_expense',
        'amount',
        'spend_method',
        'assign_person',
    ];
}
