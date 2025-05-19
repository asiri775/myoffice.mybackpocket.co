<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Bavix\Wallet\Models\Transaction;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    protected function table(): string
    {
        return (new Transaction())->getTable();
    }

    public function up(): void
    {
        Schema::table($this->table(), function (Blueprint $table) {
            //
        });

        if (!Schema::hasColumn($this->table(), 'payable_type')) {
            Schema::table($this->table(), function (Blueprint $table) {
                $table->string('payable_type')->nullable()->after('payable_id')->index();
            });

            // Optionally set a default value, e.g., 'App\User'
            DB::table($this->table())
                ->whereNull('payable_type')
                ->update(['payable_type' => 'App\\User']);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table($this->table(), function (Blueprint $table) {
            $table->dropColumn('payable_type');
        });
    }
};
