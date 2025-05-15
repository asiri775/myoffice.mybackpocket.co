<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimezonesReferenceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timezones_reference', function (Blueprint $table) {
            $table->id();
            $table->string('time_zone', 50);
            $table->string('tz_code', 5);
            $table->string('utc', 20);
            $table->string('zone', 3);
            $table->string('php_time_zones', 20);
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('timezones_reference');
    }
}
