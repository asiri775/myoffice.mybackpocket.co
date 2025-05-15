<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubjectMsgColumnToContacts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bravo_contact', function (Blueprint $table) {
            $table->string('topic')->nullable();
            $table->string('subject')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bravo_contact', function (Blueprint $table) {
            $table->dropColumn(['topic', 'subject']);
        });
    }
}
