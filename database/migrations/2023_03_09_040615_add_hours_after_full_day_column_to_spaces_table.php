<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHoursAfterFullDayColumnToSpacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bravo_spaces', function (Blueprint $table) {
            $table->integer('hours_after_full_day')->nullable()->default(null);
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
            $table->dropColumn('hours_after_full_day');
        });
    }
}
