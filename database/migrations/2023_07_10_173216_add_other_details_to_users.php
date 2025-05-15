<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOtherDetailsToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {

            $table->longText('myoffice_user_email')->nullable();
            $table->longText('linkedin_link')->nullable();
            $table->longText('facebook_page')->nullable();
            $table->longText('meetup_account')->nullable();
            $table->longText('facebook_personal')->nullable();
            $table->longText('google_plus')->nullable();

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
            //
        });
    }
}
