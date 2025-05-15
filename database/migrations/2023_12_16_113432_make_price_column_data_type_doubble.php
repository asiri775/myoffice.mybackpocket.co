<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakePriceColumnDataTypeDoubble extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bravo_spaces', function ($table) {
            $table->decimal('hourly')->default(0)->change();
            $table->decimal('daily')->default(0)->change();
            $table->decimal('weekly')->default(0)->change();
            $table->decimal('monthly')->default(0)->change();

            $table->decimal('discounted_hourly')->default(0)->change();
            $table->decimal('discounted_daily')->default(0)->change();
            $table->decimal('discounted_weekly')->default(0)->change();
            $table->decimal('discounted_monthly')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        return false;
    }
}
