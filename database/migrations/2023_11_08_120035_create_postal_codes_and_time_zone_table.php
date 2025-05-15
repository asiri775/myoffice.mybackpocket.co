<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostalCodesAndTimeZoneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('postal_codes_and_time_zone', function (Blueprint $table) {
            $table->id();
            $table->string('postalcode', 20);
            $table->string('city', 40);
            $table->string('province_abbr', 60);
            $table->string('timezone', 2);
            $table->string('latitude', 40);
            $table->string('longitude', 40);
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
        Schema::dropIfExists('postal_codes_and_time_zone');
    }
}
