<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserTransactionsDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_transactions_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('payable_type')->nullable();
            $table->string('payment_id', 10)->nullable();
            $table->string('wallet_id', 40)->nullable();
            $table->string('type', 20)->nullable();
            $table->string('amount', 10)->nullable();
            $table->string('confirmed', 10)->nullable();
            $table->string('full_amount', 10)->nullable();
            $table->string('meta', 100)->nullable();
            $table->string('user_id', 10)->nullable();
            $table->string('booking_id', 10)->nullable();
            $table->string('is_debit', 10)->nullable();
            $table->string('reference_id', 10)->nullable();
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
        Schema::dropIfExists('user_transactions_details');
    }
}
