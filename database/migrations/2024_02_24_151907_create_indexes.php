<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bravo_spaces', function (Blueprint $table) {
            $table->index(['status', 'discounted_hourly', 'hourly', 'map_lng', 'map_lat', 'max_guests', 'deleted_at'], 'search_index');
            $table->index(['status', 'discounted_hourly', 'hourly', 'title', 'map_lng', 'map_lat', 'max_guests', 'deleted_at'], 'search_index_1');
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
            $table->dropIndex('search_index');
            $table->dropIndex('search_index_1');
        });
    }

}
