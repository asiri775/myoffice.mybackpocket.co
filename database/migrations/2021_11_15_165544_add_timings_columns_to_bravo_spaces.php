<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimingsColumnsToBravoSpaces extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bravo_spaces', function (Blueprint $table) {
            $table->string('available_from')->nullable();
            $table->string('available_to')->nullable();
            $table->string('first_working_day')->nullable();
            $table->string('last_working_day')->nullable();
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
            $table->dropColumn(['available_from', 'available_to', 'first_working_day', 'last_working_day']);
        });
    }
}
