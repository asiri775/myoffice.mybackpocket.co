<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSpaceDefultPrices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bravo_spaces', function (Blueprint $table) {
            $table->integer('hourly_price_set_default')->default(0);
            $table->integer('daily_price_set_default')->default(0);
            $table->integer('weekly_price_set_default')->default(0);
            $table->integer('monthly_price_set_default')->default(0);
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
            $table->dropColumn('hourly_price_set_default');
            $table->dropColumn('daily_price_set_default');
            $table->dropColumn('weekly_price_set_default');
            $table->dropColumn('monthly_price_set_default');
        });
    }
}
