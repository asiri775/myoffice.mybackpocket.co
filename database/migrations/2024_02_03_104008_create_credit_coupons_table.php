<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_coupons', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('code');
            $table->string('recepient');
            $table->double('amount');
            $table->double('used')->default(0);
            $table->double('pending');
            $table->string('type');
            $table->string('reference');
            $table->text('notes')->nullable();
            $table->date('expired_at');
            $table->integer('object_id')->nullable();
            $table->string('object_model', 255)->nullable()->default('space');
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
        Schema::dropIfExists('credit_coupons');
    }
}
