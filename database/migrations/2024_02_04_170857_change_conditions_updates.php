<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeConditionsUpdates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_badges', function (Blueprint $table) {
            $table->decimal('min_of_bookings')->change();
            $table->decimal('min_of_reviews')->change();
            $table->decimal('min_of_amenities')->change();
            $table->decimal('min_of_revenue')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        return true;
    }
}
