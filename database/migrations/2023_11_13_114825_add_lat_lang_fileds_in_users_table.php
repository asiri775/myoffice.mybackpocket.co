<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLatLangFiledsInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('map_lat',20)->nullable();
            $table->string('map_lng',20)->nullable();
            $table->integer('map_zoom')->nullable();
            $table->string('facebook_link')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['map_lat', 'map_lng', 'map_zoom', 'facebook_link']);
        });
    }
}
