<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBuyerFeesAmountColumnToBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bravo_bookings', function (Blueprint $table) {
            $table->string('items')->nullable();
            $table->string('extra_fee_items')->nullable();
            $table->string('guest_fee_items')->nullable();
            $table->string('host_fee_items')->nullable();
            $table->string('coupon_type')->nullable()->comment('global/space');

            $table->double('price')->default(0);
            $table->double('extra_fee')->default(0);
            $table->double('guest_fee')->default(0);
            $table->double('tax')->default(0);
            $table->double('discount')->default(0);
            $table->double('payable_amount')->default(0);
            $table->double('host_fee')->default(0);

            $table->double('admin_amount')->default(0)->comment('amount admin will receive');
            $table->double('host_amount')->default(0)->comment('amount host will receive');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bravo_bookings', function (Blueprint $table) {
            $table->dropColumn([
                'items', 'extra_fee_items', 'guest_fee_items', 'host_fee_items', 'coupon_type',
                'price', 'extra_fee', 'guest_fee', 'tax', 'discount', 'payable_amount', 'host_fee',
                'admin_amount', 'host_amount'
            ]);
        });
    }
}
