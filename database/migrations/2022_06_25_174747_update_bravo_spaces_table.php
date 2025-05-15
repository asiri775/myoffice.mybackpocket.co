<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBravoSpacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void  
     */
    public function up()
    {
        Schema::table('bravo_spaces', function ($table) {
            $table->boolean('free_cancellation');
            $table->boolean('rapidbook');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bravo_spaces', function ($table) {
            $table->dropColumn('free_cancellation');
            $table->dropColumn('rapidbook');
        });
    }
}
