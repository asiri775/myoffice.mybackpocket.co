<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSpaceTransactionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bravo_booking_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('transaction_id')->nullable();
            $table->string('order_id', 10)->nullable();
            $table->string('payment_type', 40)->nullable();
            $table->string('payment_date', 10)->nullable();
            $table->string('amount', 10)->nullable();
            $table->string('due_amount', 10)->nullable();
            $table->string('full_amount', 10)->nullable();
            $table->string('status', 10)->nullable();
            $table->timestamps();
        });
    }
    
    
    
    
     
    
    
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bravo_booking_transactions');
    }
}
