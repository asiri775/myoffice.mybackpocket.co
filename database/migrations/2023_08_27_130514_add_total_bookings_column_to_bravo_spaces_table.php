<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Booking\Models\Booking;
use Modules\Space\Models\Space;

class AddTotalBookingsColumnToBravoSpacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bravo_spaces', function (Blueprint $table) {
            $table->integer('total_bookings')->default(0)->nullable();
        });

        //update space total bookings
        $bookings = Booking::where('object_model', 'space')->groupBy('object_id')->get();
        if ($bookings != null) {
            foreach ($bookings as $booking) {
                $space = Space::where('id', $booking->object_id)->first();
                if ($space != null) {
                    $space->updateStats();
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bravo_spaces', function (Blueprint $table) {
            $table->dropColumn('total_bookings');
        });
    }
}
