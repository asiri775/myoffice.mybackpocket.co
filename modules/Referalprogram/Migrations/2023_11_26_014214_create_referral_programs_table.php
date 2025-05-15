<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferralProgramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('referral_programs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('uri');
            $table->integer('lifetime_minutes')->default(7 * 24 * 60); //in minutes
            $table->decimal('amount', 64, 0)->default(0);
            $table->integer('create_user')->nullable();
            $table->integer('update_user')->nullable();
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
        Schema::dropIfExists('referral_programs');
    }
}