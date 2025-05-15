<?php

use App\Models\UserBadge;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIconColumnToUserBadgesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_badges', function (Blueprint $table) {
            $table->string('icon')->nullable();
        });
        UserBadge::where('badge_name', 'Newbie')->update(['icon' => 'newbie']);
        UserBadge::where('badge_name', 'RisingStar')->update(['icon' => 'rising-star']);
        UserBadge::where('badge_name', 'Premier')->update(['icon' => 'premier']);
        UserBadge::where('badge_name', 'Super')->update(['icon' => 'super']);
        UserBadge::where('badge_name', 'Elite')->update(['icon' => 'elite']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_badges', function (Blueprint $table) {
            $table->dropColumn('icon');
        });
    }
}
