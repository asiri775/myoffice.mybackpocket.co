<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Core\Models\Settings;
use Modules\Page\Models\Page;

class CreateTermsOfServicePage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $model = Page::where('slug', 'terms-of-service')->first();
        if ($model == null) {
            $model = new Page();
            $model->title = "Terms of Service";
            $model->create_user = 1;
            $model->status = 'publish';
            $model->created_at = date("Y-m-d H:i:s");
            $model->content = '<p>Here you can add terms of service content</p>';
            $model->save();
        }

        $settingModel = Settings::where('name', 'terms-of-booking_term_conditions')->first();
        if ($settingModel == null) {
            DB::table('core_settings')->insert([
                    [
                        'name' => 'booking_term_conditions',
                        'val' => $model->id,
                        'group' => "general",
                    ]
                ]
            );
        } else {
            $settingModel->val = $model->id;
            $settingModel->update();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        return true;
    }
}
