<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserBadgesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_badges', function (Blueprint $table) {
            $table->bigIncrements('badge_id');
            $table->string('user_id', 10)->nullable();
            $table->string('badge_name', 40)->nullable();
            $table->string('min_of_bookings', 10)->nullable();
            $table->string('min_of_reviews', 10)->nullable();
            $table->string('min_of_amenities', 10)->nullable();
            $table->string('min_of_revenue', 10)->nullable();
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
        Schema::dropIfExists('user_badges');
    }
}
