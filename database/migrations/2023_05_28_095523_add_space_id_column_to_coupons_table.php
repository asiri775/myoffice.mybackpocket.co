<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSpaceIdColumnToCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bravo_coupons', function (Blueprint $table) {
            $table->integer('object_id')->nullable();
            $table->string('object_model')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bravo_coupons', function (Blueprint $table) {
            $table->dropColumn(['object_id', 'object_model']);
        });
    }
}
