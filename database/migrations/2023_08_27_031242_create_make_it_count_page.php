<?php

use App\Helpers\Constants;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Page\Models\Page;

class CreateMakeItCountPage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $model = new Page([
            'slug' => 'make-it-count',
            'title' => 'Make it Count',
            'content' => 'Content Goes Here',
            'status' => 'publish',
            'create_user' => '1',
            'created_at' => Constants::PHP_DATE_FORMAT,
        ]);
        $model->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $model = Page::where('slug', 'make-it-count');
        if ($model != null) {
            $model->delete();
        }
    }
}
