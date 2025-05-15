<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Core\Models\Settings;
use Modules\Media\Models\MediaFile;

class UpdateBuyerHostSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Settings::where('name', 'enable_verify_email_register_user')->update([
            'val' => 1
        ]);

        Settings::where('name', 'subject_email_verify_register_user')->update([
            'val' => 'Active your Account'
        ]);

        Settings::where('name', 'content_email_verify_register_user')->update([
            'val' => '<h1 style="text-align: center;">Welcome!</h1>
            <h3>Hello [first_name] [last_name]</h3>
            <p style="font-size: medium; font-weight: 400;">Thank you for signing up with us! Kindly click on below button to verify your account.</p>
            <p style="font-size: medium; font-weight: 400;">&nbsp;</p>
            <p style="text-align: center;">[button_verify]</p>
            <p style="text-align: center;">&nbsp;</p>
            <p style="font-size: medium; font-weight: 400;">Regards,</p>
            <p style="font-size: medium; font-weight: 400;">MyOffice</p>'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Settings::where('name', 'enable_verify_email_register_user')->update([
            'val' => 0
        ]);
    }
}
