<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReviewByColumnToBravoReviewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bravo_review', function (Blueprint $table) {
            $table->string('review_by');
            $table->integer('review_to');
            $table->decimal('rate_number', 2, 1)->change();
        });
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('review_score', 2, 1)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bravo_review', function (Blueprint $table) {
            $table->dropColumn('review_by');
            $table->dropColumn('review_to');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('review_score');
        });
    }
}
