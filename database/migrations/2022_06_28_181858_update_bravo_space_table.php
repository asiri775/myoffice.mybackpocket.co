<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBravoSpaceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bravo_spaces', function ($table) {
            $table->string('discount');
            $table->string('hourly');
            $table->string('daily');
            $table->string('weekly');
            $table->string('monthly');
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
                'discount',
                'hourly',
                'daily',
                'weekly',
                'monthly',
            ]);
        });
    }
}
