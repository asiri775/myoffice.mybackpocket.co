<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Core\Models\Terms;

class ChangePoolToGymAmentity extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Terms::where('slug', 'pool')->update(['name'=> 'Gym', 'slug'=>'gym', 'icon' => 'icofont-gym']);   
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Terms::where('slug', 'gym')->update(['name'=> 'Pool', 'slug'=>'pool', 'icon' => 'icofont-swimmer']);   
    }
}
