<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDiscountedPrices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bravo_spaces', function (Blueprint $table) {
            $table->string('discounted_hourly', 15)->nullable();
            $table->string('discounted_daily', 15)->nullable();
            $table->string('discounted_weekly', 15)->nullable();
            $table->string('discounted_monthly', 15)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bravo_spaces', function (Blueprint $table) {
            $table->dropColumn([
                'discounted_hourly',
                'discounted_daily',
                'discounted_weekly',
                'discounted_monthly',
            ]);
        });
    }
}
