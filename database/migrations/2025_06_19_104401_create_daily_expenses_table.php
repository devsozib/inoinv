<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyExpensesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('daily_expenses', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->unsignedBigInteger('expense_category_id');
            $table->decimal('amount', 15, 2);
            $table->enum('spend_method', ['cash', 'card', 'bank_transfer']);
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('daily_expenses');
    }
}
