<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraFieldsToTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_transactions', function (Blueprint $table) {
            $table->integer('is_debit')->default(0);
            $table->string('reference_id')->nullable()->default(null);
            $table->string('type')->nullable()->change();
        });

        Schema::table('bravo_payouts', function (Blueprint $table) {
            $table->decimal('fee')->default(0);
            $table->decimal('total')->default(0);
        });

    }

    /**
     * Reverse the migrations.     
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_transactions', function (Blueprint $table) {
            $table->dropColumn(['is_debit', 'reference_id']);
        });
        Schema::table('bravo_payouts', function (Blueprint $table) {
            $table->dropColumn(['fee', 'total']);
        });
    }
}
