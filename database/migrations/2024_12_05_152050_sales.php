<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('customer_id');
            $table->string('name');
            $table->string('country_code');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('product_name');
            $table->double('price');
            $table->double('qty');
            $table->double('total');
            $table->double('bill');
            $table->double('discount')->nullable();
            $table->enum('status', [0, 1])->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
