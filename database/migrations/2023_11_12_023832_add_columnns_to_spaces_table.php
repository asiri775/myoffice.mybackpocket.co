<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnnsToSpacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bravo_spaces', function (Blueprint $table) {
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('zip')->nullable();
            $table->string('checkin_reminder_time')->nullable();
            $table->text('prearrival_checkin_text')->nullable();
            $table->string('arrival_checkin_reminder')->nullable();
            $table->text('arrival_checkin_text')->nullable();
            $table->string('host_checkin_reminder')->nullable();
            $table->text('host_reminder_text')->nullable();
            $table->string('checkout_reminder_time')->nullable();
            $table->text('departure_reminder_text')->nullable();
            $table->string('latecheckout_reminder_time')->nullable();
            $table->text('latecheckout_reminder_text')->nullable();
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
            $table->dropColumn([
                'city',
                'state',
                'country',
                'zip',
                'checkin_reminder_time',
                'prearrival_checkin_text',
                'arrival_checkin_reminder',
                'arrival_checkin_text',
                'host_checkin_reminder',
                'host_reminder_text',
                'checkout_reminder_time',
                'departure_reminder_text',
                'latecheckout_reminder_time',
                'latecheckout_reminder_text'
            ]);
        });
    }
}
