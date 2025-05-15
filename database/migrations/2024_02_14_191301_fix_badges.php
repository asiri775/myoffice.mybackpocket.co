<?php

use App\Models\UserBadge;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixBadges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        UserBadge::where('badge_id', '>', 0)->delete();

        Schema::table('user_badges', function (Blueprint $table) {
            $table->string('type');
        });

        $badges = [
            [
                'type' => 'host',
                'badge_name' => 'Newbie',
                'min_of_bookings' => 0,
                'min_of_reviews' => 0,
                'min_of_amenities' => 0,
                'min_of_revenue' => 0,
                'icon' => 'newbie'
            ],
            [
                'type' => 'host',
                'badge_name' => 'Rising Star',
                'min_of_bookings' => 5,
                'min_of_reviews' => 0,
                'min_of_amenities' => 1,
                'min_of_revenue' => 500,
                'icon' => 'rising-star'
            ],
            [
                'type' => 'host',
                'badge_name' => 'Premier',
                'min_of_bookings' => 51,
                'min_of_reviews' => 21,
                'min_of_amenities' => 2,
                'min_of_revenue' => 1000,
                'icon' => 'premier'
            ],
            [
                'type' => 'host',
                'badge_name' => 'Super',
                'min_of_bookings' => 101,
                'min_of_reviews' => 51,
                'min_of_amenities' => 5,
                'min_of_revenue' => 5000,
                'icon' => 'super'
            ],
            [
                'type' => 'host',
                'badge_name' => 'Elite',
                'min_of_bookings' => 301,
                'min_of_reviews' => 101,
                'min_of_amenities' => 5,
                'min_of_revenue' => 10000,
                'icon' => 'elite'
            ],
            [
                'type' => 'guest',
                'badge_name' => 'Starter',
                'min_of_bookings' => 0,
                'min_of_reviews' => 0,
                'min_of_amenities' => 0,
                'min_of_revenue' => 0,
                'icon' => 'starter'
            ],
            [
                'type' => 'guest',
                'badge_name' => 'Friend',
                'min_of_bookings' => 5,
                'min_of_reviews' => 1,
                'min_of_amenities' => 0,
                'min_of_revenue' => 500,
                'icon' => 'friend'
            ],
            [
                'type' => 'guest',
                'badge_name' => 'Family',
                'min_of_bookings' => 21,
                'min_of_reviews' => 21,
                'min_of_amenities' => 0,
                'min_of_revenue' => 1000,
                'icon' => 'family'
            ],
            [
                'type' => 'guest',
                'badge_name' => 'Super',
                'min_of_bookings' => 101,
                'min_of_reviews' => 51,
                'min_of_amenities' => 0,
                'min_of_revenue' => 5000,
                'icon' => 'super'
            ],
            [
                'type' => 'guest',
                'badge_name' => 'Elite',
                'min_of_bookings' => 301,
                'min_of_reviews' => 101,
                'min_of_amenities' => 0,
                'min_of_revenue' => 10000,
                'icon' => 'elite'
            ]
        ];

        UserBadge::insert($badges);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_badges', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
